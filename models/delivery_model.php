<?php

class Delivery_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param int $delivery_id
	 * @return array
	 */
	public function getDeliveryCost($delivery_id)
	{
		//сделать проверку,на разрешение производить оплату PayQR с данным способом доставки
		if(!$this->checkDelivery($delivery_id))
		{
			return false;
		}

		//получаем стоимость доставки товара
		$this->db->where('id', $delivery_id);
		
		$delivery = $this->db->get('shop_delivery_methods')->row();

		return $delivery? $delivery : false;
	}

	/**
	 * @param int $delivery_id
	 * @return bool
	 */
	public function checkDelivery($delivery_id)
	{
		$this->db->where('id', $delivery_id);

		$this->db->where('enabled', 1);

		$res = $this->db->get('shop_delivery_methods');
        
        if ($res->num_rows() == 0)
        {
        	return false;
        }
		
		//проверка разрешения PayQR для способа доставки
        $PayqrModel = \Payqr::getInstance()->getPayqrModel();

        $this->db->where('delivery_method_id', $delivery_id);

        $this->db->where('payment_method_id', $PayqrModel->getPayQRId());
        
        $res = $this->db->get('shop_delivery_methods_systems');

        return !$res->num_rows() ? false : true;
	}

	/**
	 * @param int|null $shop_method_id
	 * 
	 * @return
	 */
	public function installDelivery($shop_method_id = null)
	{
		if(is_null($shop_method_id) || !is_numeric($shop_method_id))
		{
			return false;
		}

		$this->db->where('enabled', 1);
		
		$delivery_methods = $this->db->get('shop_delivery_methods')->result_array();

		foreach($delivery_methods as $delivery_method)
		{
			$this->db->insert('shop_delivery_methods_systems', array('delivery_method_id' => $delivery_method['id'], 'payment_method_id' => $shop_method_id));
		}
	}

	/**
	 * @param int|null $shop_method_id
	 * @return array
	 */
	public function getAvailableDelivery($shop_method_id = null)
	{
		if(is_null($shop_method_id) || !is_numeric($shop_method_id))
		{
			return array();
		}

		$query = "SELECT sdm.id, sdm.price, sdm.free_from, sdm.is_price_in_percent, sdmi.name, sdmi.description
					FROM `shop_delivery_methods` sdm
					LEFT JOIN
						`shop_delivery_methods_systems` sdms ON sdm.id = sdms.delivery_method_id
					LEFT JOIN
						`shop_delivery_methods_i18n` sdmi ON sdmi.id = sdm.id
					WHERE
						sdm.enabled = 1 AND sdms.payment_method_id = '".$shop_method_id."'";

		$deliveries = $this->db->query($query)->result_array();

		return $deliveries? $deliveries : array();
	}

	private function __log($message = null, $line = 0, $debug = false, $delete_old_log_file = false)
	{
		if($delete_old_log_file) @unlink("__worker.log");if(empty($message) || !$debug) return;$fp = fopen("__worker.log", "a");fwrite($fp, "[{$line}]\r\n\t{$message}\r\n");fclose($fp);
	}
}