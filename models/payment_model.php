<?php

class Payment_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Метод производит начальную установку 
	 * данных для платежной системы
	 * 
	 * @return bool
	 */
	public function installPayment()
	{
		if(!$this->isInstallShopPaymentMethod())
		{
			//ToDo currency id

			$query_spm = "INSERT INTO `shop_payment_methods` ( `active`, `currency_id`, `position`, `payment_system_name` ) 
							VALUES ( 1, 1, 0, 'payment_method_payqr')";

			$this->db->query( $query_spm );

			$payment_id = $this->db->insert_id();

			if(!is_numeric($payment_id))
			{
				return false;
			}
		}

		if(!$this->isInstallShopPaymentMethodI18n($payment_id))
		{
			$query_spmi18n = "INSERT INTO `shop_payment_methods_i18n` (`id`, `locale`, `name`, `description`) VALUES('".$payment_id."', 'ru', 'PayQR', '<p>PayQR платежная система</p>')";

			$res = $this->db->query( $query_spmi18n );

			if(!$res)
			{
				return false;
			}
		}

		return true;
	}

	private function isInstallShopPaymentMethod()
	{
		$res = $this->db->like('payment_system_name', 'payqr')->get('shop_payment_methods')->row();

		return !$res ? false : true;
	}

	/**
	 * 
	 * @return int | null
	 */
	public function getId()
	{
		$res = $this->db->like('payment_system_name', 'payqr')->get('shop_payment_methods')->row();

		return $res->id? $res->id : null;
	}
	
	/**
	 * Возвращает id PayQR платежной системы
	 * 
	 * @return int | bool
	 */
	public function isInstallShopPaymentMethodI18n($payment_id)
	{
		$query = "SELECT id FROM shop_payment_methods_i18n where name like '%payqr%' or name like '%PayQR%' or name like '%Payqr%'";

		$payment_res = $this->db->query($query)->row();

		//$res = $this->db->where('id', $payment_res->id)->delete('shop_payment_methods_i18n');
		
		return $res? false: $payment_res->id;
	}

	private function getRUBId()
	{
		$this->db->where('code', 'RUB');
		
		$ResCurrencies = $this->db->get('shop_currencies')->row_array();

		if(isset($ResCurrencies['id']) && !empty($ResCurrencies['id']))
		{
			return $ResCurrencies['id'];
		}

		$currentCurrency = \Currency\Currency::create()->current;

		return $currentCurrency;
	}

	public function calculatePrice($price)
	{
		$RUBCurrencyId = $this->getRUBId();

		if(empty($RUBCurrencyId))
		{
			return $price;
		}

		$converted_price = currency_convert($price, $RUBCurrencyId);

		if(isset($converted_price['second'], $converted_price['second']['price']) && !empty($converted_price['second']['price']))
		{
			return $converted_price['second']['price'];
		}

		return $price;
	}

	private function __log($message = null, $line = 0, $debug = false, $delete_old_log_file = false)
	{
		if($delete_old_log_file) @unlink("__worker.log");if(empty($message) || !$debug) return;$fp = fopen("__worker.log", "a");fwrite($fp, "[{$line}]\r\n\t{$message}\r\n");fclose($fp);
	}
}