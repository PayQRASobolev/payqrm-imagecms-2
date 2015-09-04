<?php

class Product_model extends CI_Model {
	
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * @param int|null product_id
	 * @return array
	 */
	public function getProductByVar($productvar_id, $locale = 'ru')
	{
		$query = "SELECT shpv.id, shpv.product_id, shpv.mainImage, shpv.price, shpv.price_in_main, shpv.stock, shpv.currency, shpi18n.name, shpvi18n.name as vname
				FROM `shop_product_variants` shpv
				LEFT JOIN `shop_products_i18n` shpi18n ON shpv.product_id = shpi18n.id
				LEFT JOIN `shop_product_variants_i18n` shpvi18n ON shpv.id = shpvi18n.id and shpvi18n.locale = shpi18n.locale
				WHERE
					shpi18n.locale = '". $locale ."' AND shpv.id = '". $this->db->escape_str($productvar_id)."'";

		$res = $this->db->query($query)->row();

		return $res;
	}

	/**
	 * @param int|null product_id
	 * @param string $locale
	 * @param bool $kit
	 * @return array
	 */
	public function getProduct($product_id, $locale = 'ru', $kit = false)
	{
		$query = "SELECT shpv.id, shpv.product_id, shpv.mainImage, shpv.price, shpv.price_in_main, shpv.stock, shpv.currency, shpi18n.name, shpvi18n.name as vname
				FROM `shop_product_variants` shpv
				LEFT JOIN `shop_products_i18n` shpi18n ON shpv.product_id = shpi18n.id
				LEFT JOIN `shop_product_variants_i18n` shpvi18n ON shpv.id = shpvi18n.id and shpvi18n.locale = shpi18n.locale
				WHERE
					shpi18n.locale = '". $locale ."' AND shpv.product_id = '". $this->db->escape_str($product_id)."' " . ($kit? " ORDER BY position ASC" : "");

		$res = $this->db->query($query)->row();

		return $res;
	}

	/**
	 * @param int $product_id
	 * @return array
	 */
	public function getProductImages($product_id)
	{
		$product = $this->getProduct($product_id, \MY_Controller::getCurrentLocale());

		if(isset($product) && !empty($product->mainImage))
		{
			return array(0 => $product->mainImage);
		}
		return array();
	}

	/**
	 * @param int $product_id
	 * @return mixed|string
	 */
	public function getProductImage($product_id)
	{
		$images = $this->getProductImages($product_id);

		if(isset($images[0]))
		{
			return $images[0];
		}
		return;
	}

	/**
	 * @param int $product_id
	 * @return null|string
	 */
	public function getProductImageUrl($product_id)
	{
		$pImg = $this->getProductImage($product_id);

		if(!empty($pImg) && file_exists( "uploads/shop/products/medium/" . $pImg))
		{
			return 'http://' . $_SERVER['HTTP_HOST'] . "/uploads/shop/products/medium/" . $pImg;
		}
		return null;
	}

	/**
	 * @param int $kit_id
	 * @return bool
	 */
	public function checkKit($kit_id)
	{
		$resKit = $this->db->where('id', $kit_id)->get('shop_kit')->row();

		return $resKit? true : false;
	}

	/**
	 * @param int $kit_id
	 * @return array
	 */
	public function getKitProducts($kit_id)
	{
		$products = array();

		if(!$this->checkKit($kit_id))
		{
			return array();
		}

		//получаем товары 
		//
		$resKit = $this->db->where('id', $kit_id)->get('shop_kit')->row();

		if($resKit && isset($resKit->product_id))
			$products[] = $resKit->product_id;

		$resKitAdditional = $this->db->where('kit_id' , $kit_id)->get('shop_kit_product')->result_array();

		foreach($resKitAdditional as $kit_product)
		{
			$products[] = $kit_product['product_id'];
		}

		return $products;
	}

	/**
	 * @param int $kit_id
	 * @param int $product_id
	 * @param bool $IS_MAX_DISCOUNT
	 * @return int
	 */
	public function getKitDiscount($kit_id, $product_id, $IS_MAX_DISCOUNT = true)
	{
		$max_discount = 0;

		$resKitAdditional = $this->db->where(array('product_id' => $product_id, 'kit_id' => $kit_id))->get('shop_kit_product')->result_array();

		foreach($resKitAdditional as $kit_product)
		{
			if($max_discount <= $kit_product['discount'])
			{
				$max_discount = $kit_product['discount'];
			}
		}

		return $max_discount;
	}

	/**
	 * @param int $kit_id
	 * @param int $product_id
	 * @return bool
	 */
	public function checkIsMainProductKit($kit_id, $product_id)
	{
		//
		$res = $this->db->where(array('product_id' => $product_id, 'id' => $kit_id))->get('shop_kit')->row();

		return $res ? true : false;
	}

	private function __log($message = null, $line = 0, $debug = false, $delete_old_log_file = false)
	{
		if($delete_old_log_file) @unlink("__worker.log");if(empty($message) || !$debug) return;$fp = fopen("__worker.log", "a");fwrite($fp, "[{$line}]\r\n\t" . basename(__DIR__) . "\r\n\t{$message}\r\n");fclose($fp);
	}
}