<?php

class Payqr_model extends CI_Model {

	/** @var array $payqr_settings */
	private $payqr_settings;

	public function __construct() 
	{
		parent::__construct();

		$this->payqr_settings = $this->getSettings();

	}

	public function getMerchantId()
	{
		if(isset($this->payqr_settings->payqr_merchant_id) && !empty($this->payqr_settings->payqr_merchant_id))
			return $this->payqr_settings->payqr_merchant_id;
	}

	/**
	 * @param $page
	 * @param int|null $product_id
	 * @return string
	 */
	public function initButton($page, $product_id = null)
	{
		$data_cart = array();

		$amount = 0;

		$button_model = \Payqr::getInstance()->getButtonModel();

		$button_model->initSettings($page, $this->payqr_settings);

		$ProductModel = \Payqr::getInstance()->getProductModel();

		$PaymentModel = \Payqr::getInstance()->getPaymentModel();

		if($page === "cart")
		{
			// получаем содержимое корзины
			$items = \Cart\BaseCart::getInstance()->getItems();

			foreach ($items['data'] as $itemData)
			{
				if(isset($itemData->data['instance']) && $itemData->data['instance'] == 'ShopKit')
				{
					$isKit = $ProductModel->checkKit($itemData->kitId);

					//получаем товары из комплекта и подсчитыааем итоговую сумму заказа
					$products = $ProductModel->getKitProducts($itemData->kitId);

					$kit_name = "Комплект товаров: ";

					foreach($products as $product_id)
					{
						$product = $ProductModel->getProduct($product_id, \MY_Controller::getCurrentLocale());

						$kit_name .= " " . $product->name;
					}

					$data_cart[] = array(
						'article'  => $itemData->kitId,
						'amount'   => $PaymentModel->calculatePrice($itemData->price) * $itemData->quantity,
						'quantity' => $itemData->quantity,
						'name'     => $kit_name,
						'imageUrl' => ""
					);
				}

				if(isset($itemData->data['instance']) && $itemData->data['instance'] == 'SProducts')
				{
					//$product = $ProductModel->getProduct($itemData->productId, \MY_Controller::getCurrentLocale());
					$product = $ProductModel->getProductByVar($itemData->data['id'], \MY_Controller::getCurrentLocale());

					if(!isset($product->name))
					{
						continue;
					}

					$data_cart[] = array(
						'article'  => $itemData->productId,//$itemData->id,
						'amount'   => $PaymentModel->calculatePrice($itemData->price) * $itemData->quantity,
						'quantity' => $itemData->quantity,
						'name'     => $product->name,
						'imageUrl' => $ProductModel->getProductImageUrl($itemData->productId)
					);
				}				
			}

			$amount = $PaymentModel->calculatePrice(\Cart\BaseCart::getInstance()->getTotalPrice());
		}
		else if(($page === "product" || $page === "category") && !is_null($product_id))
		{
			//$product = $ProductModel->getProductByVar($product_id, \MY_Controller::getCurrentLocale());
			$product = $ProductModel->getProduct($product_id, \MY_Controller::getCurrentLocale());

			if(!isset($product->name, $product->price, $product->product_id))
			{
				return "";
			}

			$data_cart['quantity'] = 1;
			$data_cart['amount']   = $PaymentModel->calculatePrice($product->price);
			$data_cart['article']  = $product_id;
			$data_cart['name']     = $product->name;
			$data_cart['imageUrl'] = $ProductModel->getProductImageUrl($product->product_id);

			$amount = $PaymentModel->calculatePrice($product->price);
		}
		else
		{
			return "";
		}

		if(empty($amount))
		{
			return "";
		}

		$payqr_button = $button_model->initButton("buy", $data_cart, $amount);

		if(is_null($payqr_button) || !isset($payqr_button))
		{
			return "";
		}

		return $payqr_button;
	}

	/**
	* Get module settings
	* @return array
	*/
	public function getSettings() 
	{
		$settings = $this->db->select('settings')
							->where('identif', 'payqr')
							->get('components')
							->row_array();

		if(isset($settings['settings']) && !empty($settings['settings']))
		{
			$_settings = json_decode($settings['settings']);

			if($_settings !== false)
			{
				if(!isset($_settings->payqr_hook_handler_url) || empty($_settings->payqr_hook_handler_url))
				{
					$_settings->payqr_hook_handler_url= 'http://' . $_SERVER['HTTP_HOST'] . '/payqr/payqr_receiver';
				}
				if(!isset($_settings->payqr_log_url) || empty($_settings->payqr_log_url))
				{
					$_settings->payqr_log_url= 'http://' . $_SERVER['HTTP_HOST'] . '/' . 'payqr.log';
				}
				
				return $_settings;
			}
		}

		return $this->getDefaultSettings();
	}

	/**
	 * @return object
	 */
	public function getDefaultSettings()
	{
		$_settings = new stdClass();
		
		$_settings->payqr_merchant_id= "";
		$_settings->payqr_merchant_secret_key_in= "";
		$_settings->payqr_merchant_secret_key_out= "";
		$_settings->payqr_hook_handler_url= 'http://' . $_SERVER['HTTP_HOST'] . '/payqr/payqr_receiver';
		$_settings->payqr_log_url= 'http://' . $_SERVER['HTTP_HOST'] . '/' . 'payqr.log';
		$_settings->payqr_button_show_on_cart= "yes";
		$_settings->payqr_cart_button_color= "default";
		$_settings->payqr_cart_button_form= "default";
		$_settings->payqr_cart_button_shadow= "default";
		$_settings->payqr_cart_button_gradient= "default";
		$_settings->payqr_cart_button_font_trans= "default";
		$_settings->payqr_cart_button_font_width= "default";
		$_settings->payqr_cart_button_text_case= "default";
		$_settings->payqr_cart_button_height= "auto";
		$_settings->payqr_cart_button_width= "auto";
		$_settings->payqr_button_show_on_product= "yes";
		$_settings->payqr_product_button_color= "default";
		$_settings->payqr_product_button_form= "default";
		$_settings->payqr_product_button_shadow= "default";
		$_settings->payqr_product_button_gradient= "default";
		$_settings->payqr_product_button_font_trans= "default";
		$_settings->payqr_product_button_font_width= "default";
		$_settings->payqr_product_button_text_case= "default";
		$_settings->payqr_product_button_height= "auto";
		$_settings->payqr_product_button_width= "auto";
		$_settings->payqr_button_show_on_category= "yes";
		$_settings->payqr_category_button_color= "default";
		$_settings->payqr_category_button_form= "default";
		$_settings->payqr_category_button_shadow= "default";
		$_settings->payqr_category_button_gradient= "default";
		$_settings->payqr_category_button_font_trans= "default";
		$_settings->payqr_category_button_font_width= "default";
		$_settings->payqr_category_button_text_case= "default";
		$_settings->payqr_category_button_height= "auto";
		$_settings->payqr_category_button_width= "auto";
		$_settings->payqr_status_creatted= "1";
		$_settings->payqr_status_paid= "1";
		$_settings->payqr_status_cancelled= "";
		$_settings->payqr_status_completed= "2";
		$_settings->payqr_require_firstname= "deny";
		$_settings->payqr_require_lastname= "deny";
		$_settings->payqr_require_middlename= "deny";
		$_settings->payqr_require_phone= "deny";
		$_settings->payqr_require_email= "required";
		$_settings->payqr_require_delivery= "required";
		$_settings->payqr_require_deliverycases= "required";
		$_settings->payqr_require_pickpoints= "deny";
		$_settings->payqr_require_promo= "deny";
		$_settings->payqr_promo_code= "";
		$_settings->payqr_user_message_text= "";
		$_settings->payqr_user_message_imageurl= "";
		$_settings->payqr_user_message_url= "";

		return $_settings;
	}

	public function saveSettings($settings)
	{
		try{
			
			$this->db->where('identif', 'payqr');

        	$this->db->update('components', array('settings' => json_encode($settings)));	

        	showMessage(lang('Successfully saved', 'xbanners'), lang('Success', 'admin'));
		}
		catch(Exception $e)
		{
			showMessage($e->getMessage(), lang('Error', 'admin'));
		}
	}


	public function getPayQRId()
	{
		$PaymentModel = \Payqr::getInstance()->getPaymentModel();

		$payqr_payment_id = $PaymentModel->isInstallShopPaymentMethodI18n();

		return is_numeric($payqr_payment_id)? $payqr_payment_id : 1 ;
	}

	public function isRURCurrency()
	{
		$this->db->where('main', 1);
		
		$ResCurrencies = $this->db->get('shop_currencies')->row_array();

		return (isset($ResCurrencies['code']) && $ResCurrencies['code'] == 'RUB')? true: false;
	}

	public function install()
	{
		$PaymentModel = \Payqr::getInstance()->getPaymentModel();
		$DeliveryModel = \Payqr::getInstance()->getDeliveryModel();

		$PaymentModel->installPayment();
		$DeliveryModel->installDelivery($PaymentModel->getId());
	}

	public function uninstall()
	{
		$PaymentModel = \Payqr::getInstance()->getPaymentModel();
		$DeliveryModel = \Payqr::getInstance()->getDeliveryModel();

		$payment_id = $PaymentModel->getId();

		// удаляем платежный сервис
		if(!is_null($payment_id))
		{
			$this->db->where('payment_method_id', $payment_id)->delete('shop_delivery_methods_systems');

			$this->db->where(array('id' => $payment_id, 'locale' => 'ru'))->delete('shop_payment_methods_i18n');

			$this->db->where('id', $payment_id)->delete('shop_payment_methods');
		}
	}

	private function __log($message = null, $line = 0, $debug = false, $delete_old_log_file = false)
	{
		if($delete_old_log_file) @unlink("__worker.log");if(empty($message) || !$debug) return;$fp = fopen("__worker.log", "a");fwrite($fp, "[{$line}]\r\n\t{$message}\r\n");fclose($fp);
	}
}