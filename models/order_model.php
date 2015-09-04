<?php

class Order_model extends CI_Model{

	private $data_cart;

	private $user_data;

	private $cust_data;

	private $delivery;

	private $delivery_case_selected;

	private $comment;

	
	public function __construct()
	{
		parent::__construct();
	}
	
	public function initOrder(&$Payqr)
	{
		//получаем информацю о товарах
		$this->payqr_cart = $Payqr->objectOrder->getCart();

		//сохраняем информацию о пользователе
		$this->user_data = $Payqr->objectOrder->getUserData();

		//
		$this->cust_data = $Payqr->objectOrder->getCustomer();

		//
		$this->delivery = $Payqr->objectOrder->getDelivery();

		//
		$this->delivery_case_selected = $Payqr->objectOrder->getDeliveryCasesSelected();

		foreach($this->payqr_cart as $product_cart)
		{
			$this->data_cart[] = $product_cart;
		}
	}

	/**
	 * 
	 * @return int
	 */
	public function CreateOrder()
	{
		$order_id = 0;

		//формируем заказ
		$oData = $this->orderPrepareData();

		//производим вставку данных
		$order_id = $this->insertOrderData($oData);

		return $order_id;
	}

	/**
	 * 
	 * @return null|int
	 */
	public function getTotal()
	{
		$total = 0;

		//производим актуализацию корзины
		$CartModel = \Payqr::getInstance()->getCartModel();

		if(!$CartModel->actualizeCart($this->payqr_cart))
		{
			//не получилось актуализировать корзину
			return false;
		}

		//проверяем была ли выбрана доставка пользователем
		if(isset($this->delivery_case_selected, $this->delivery_case_selected->article) && !empty($this->delivery_case_selected->article))
		{
			$total = $CartModel->getCartAmount($this->payqr_cart, $this->delivery_case_selected->article);
		}
		else
		{
			$total = $CartModel->getCartAmount($this->payqr_cart, null);
		}
		return $total;
	}

	/**
	 * 
	 * @return null|int
	 */
	public function getClearTotal()
	{
		//Считаем сумму товара
		$total = 0;

		$ProductModel = \Payqr::getInstance()->getProductModel();

		$CartModel = \Payqr::getInstance()->getCartModel();

		$checkInStock = $CartModel->CheckProductInStock();

		foreach($this->payqr_cart as $cart_product)
		{
			//пересчитываем сумму позиции товаров для комплекта товаров
			if($ProductModel->checkKit($cart_product->article))
			{
				$products = $ProductModel->getKitProducts($cart_product->article);

				$product_kit_amount = 0;

				$kit_discount = 0;

				foreach($products as $product_id)
				{
					$kit_item = $ProductModel->getProduct($product_id, \MY_Controller::getCurrentLocale());

					if($kit_discount = $ProductModel->getKitDiscount($cart_product->article, $product_id))
					{
						$product_kit_amount += isset($kit_item->price)? ceil($kit_item->price - ($kit_item->price * $kit_discount / 100) ): 0 ;
					}
					else
					{
						$product_kit_amount += isset($kit_item->price)? $kit_item->price : 0 ;
					}
				}
				
				//рассчитываем стоимость комплекта
				$total += $product_kit_amount * $cart_product->quantity;
			}
			else 
			{
				$product = $ProductModel->getProduct($cart_product->article, \MY_Controller::getCurrentLocale());

				$total += $cart_product->quantity * $product->price;

				//Проверяем товар на quantity на складе
				if($checkInStock && $product->stock == 0)
				{
					return 0;
				}
			}
		}

		if(!is_null($delivery_id))
		{
			$DeliveryModel = \Payqr::getInstance()->getDeliveryModel();

			//получаем способ доставки
			$delivery = $DeliveryModel->getDeliveryCost($delivery_id);

			if((is_bool($delivery) && !$delivery) || !isset($delivery->id))
			{
				return false;
			}
			
			if(isset($delivery->free_from))
			{
				$delivery->free_from = intval($delivery->free_from);

				if($total < $delivery->free_from)
				{
					$total += $delivery->price;
				}
				else {
					$this->__log("товар больше указанной суммы в БД", __LINE__, true);
				}
			}
			
			if(isset($delivery->is_price_in_precent))
			{
				$delivery->is_price_in_precent = intval($delivery->is_price_in_precent);

				$total = $total + ceil($total * $delivery->is_price_in_precent/100);
			}
		}

		return $total;
	}

	/**
	 * @param array $oData
	 * @return null|int
	 */
	private function insertOrderData($oData)
	{
		$query = "INSERT INTO `shop_orders` (`order_key`, 
											`delivery_method`, 
											`delivery_price`, 
											`status`, 
											`user_full_name`, 
											`user_email`, 
											`user_phone`, 
											`user_deliver_to`, 
											`user_comment`, 
											`date_created`,
											`date_updated`,
											`user_ip`,
											`user_id`,
											`payment_method`,
											`discount`,
											`discount_info`,
											`total_price`,
											`origin_price`
											)
						VALUES(
								'".$this->db->escape_str('test')."',
								'".$this->db->escape_str($oData['delivery_id'])."',
								'".$this->db->escape_str($oData['delivery_cost'])."',
								'".$oData['status']."',
								'".$this->db->escape_str($oData['user_full_name'])."',
								'".$this->db->escape_str($oData['user_email'])."',
								'".$this->db->escape_str(isset($oData['user_phone'])? $oData['user_phone'] : null)."',
								'".$this->db->escape_str($oData['address'])."',
								'".$this->db->escape_str($oData['comment'])."',
								'".time()."',
								'".time()."',
								'".$oData['user_ip']."',
								'".(!$this->dx_auth->is_logged_in() ? null : $this->dx_auth->get_user_id())."',
								'".$oData['payment_id']."',
								'".(isset($oData['discount']) && !empty($oData['discount'])? $oData['discount'] : "null")."',
								'".(isset($oData['discount_info']) && !empty($oData['discount_info'])? $oData['discount_info'] : "null")."',
								'".$this->db->escape_str($oData['summ'])."',
								'".$this->db->escape_str($oData['summ'])."'
							)";

		$this->db->query($query);

		$order_id = $this->db->insert_id();

		if(!$order_id)
		{
			return false;
		}

		//необходимо вставить данные в shop_orders_products

		$ProductModel = \Payqr::getInstance()->getProductModel();

		foreach($this->payqr_cart as $payqr_product)
		{
			$product = $ProductModel->getProduct($payqr_product->article, \MY_Controller::getCurrentLocale());

			//Проверяем, товар в комплекте или нет
			if($ProductModel->checkKit($payqr_product->article))
			{
				$products = $ProductModel->getKitProducts($payqr_product->article);

				foreach($products as $product_id)
				{
					$product = $ProductModel->getProduct($product_id, \MY_Controller::getCurrentLocale());

					$is_main_kit_product = true;

					$kit_discount = $ProductModel->getKitDiscount($payqr_product->article, $product_id);

					$query = "INSERT INTO `shop_orders_products` (`order_id`,
														`product_id`,
														`variant_id`,
														`product_name`,
														`variant_name`,
														`price`,
														`origin_price`,
														`quantity`,
														`kit_id`,
														`is_main`)
						VALUES(
								".$order_id.",
								".$product->product_id.",
								".$product->id.",
								'".$product->name."',
								'".$product->vname."',
								". ($kit_discount? ceil( $product->price - ($product->price* $kit_discount/100) ) : $product->price) .",
								".$product->price.",
								".$payqr_product->quantity.",
								".$this->db->escape_str($payqr_product->article).",
								".( $ProductModel->checkIsMainProductKit($payqr_product->article, $product_id)? 1 : 0 )."
							)";
					
					$this->db->query($query);
				}

				continue;
			}
			elseif(!isset($product->id, $product->product_id, $product->name, $product->vname))
			{
				continue;
			}

			$query = "INSERT INTO `shop_orders_products` (`order_id`,
														`product_id`,
														`variant_id`,
														`product_name`,
														`variant_name`,
														`price`,
														`origin_price`,
														`quantity`)
						VALUES(
								".$order_id.",
								".$product->product_id.",
								".$product->id.",
								'".$product->name."',
								'".$product->vname."',
								".$product->price.",
								".$product->price.",
								".$payqr_product->quantity.")";

			$this->db->query($query);
		}

		if($order_id)
		{
			return $order_id;
		}

		return null;
	}

	/**
	 * @return array
	 */
	private function orderPrepareData()
	{
		$oData = array();

		if(isset($this->cust_data->email) && !empty($this->cust_data->email))
		{
			$oData['user_email'] = $this->cust_data->email;
		}

		if(isset($this->cust_data->firstName) && !empty($this->cust_data->firstName))
		{
			$oData['user_full_name'] = $this->cust_data->firstName;
		}

		if(isset($this->cust_data->lastName) && !empty($this->cust_data->lastName))
		{
			$oData['user_full_name'] .= " " . $this->cust_data->lastName;
		}

		if(isset($this->cust_data->middlename) && !empty($this->cust_data->middlename))
		{
			$oData['user_full_name'] .= " " . $this->cust_data->middlename;
		}

		if(isset($oData['user_full_name']) && empty($oData['user_full_name']))
		{
			$oData['user_full_name'] .= "Укажите запрос параметров пользователя в настройках PayQR";
		}

		if(isset($this->cust_data->phone) && !empty($this->cust_data->phone))
		{
			$oData['user_phone'] = $this->cust_data->phone;
		}
		
		//формиурем адрес доставки
		$oData['address'] = "";

		if(isset($this->delivery->country) && !empty($this->delivery->country))
		{
			$oData['address'] .= " ". $this->delivery->country;
		}

		if(isset($this->delivery->city) && !empty($this->delivery->city))
		{
			$oData['address'] .= ", ". $this->delivery->city;
		}

		if(isset($this->delivery->zip) && !empty($this->delivery->zip))
		{
			$oData['address'] .= ", ". $this->delivery->zip;
		}

		if(isset($this->delivery->street) && !empty($this->delivery->street))
		{
			$oData['address'] .= ", ". $this->delivery->street;
		}

		if(isset($this->delivery->house) && !empty($this->delivery->house))
		{
			$oData['address'] .= ", ". $this->delivery->house;
		}

		$oData['address'] = trim($oData['address']);

		if(empty($oData['address']))
		{
			$oData['address'] .= "Укажите запрос адреса доставки пользователя в настройках PayQR";
		}

		
		$oData['summ'] = $this->getClearTotal();


		if(isset($this->delivery_case_selected->article) && !empty($this->delivery_case_selected->article))
		{
			$oData['delivery_id'] = $this->delivery_case_selected->article;
		}
		else 
		{
			$oData['delivery_id'] = 3;//без доставки
		}

		if(isset($this->delivery_case_selected->amountTo) && !empty($this->delivery_case_selected->amountTo))
		{
			$oData['delivery_cost'] = $this->delivery_case_selected->amountTo;
		}
		else 
		{
			$oData['delivery_cost'] = 0;
		}

		$PayqrModel = \Payqr::getInstance()->getPayqrModel();

		$oData['payment_id'] = $PayqrModel->getPayQRId();


		$CartModel = \Payqr::getInstance()->getCartModel();
		
		$oData['discount'] = $CartModel->calcCartDiscount($this->payqr_cart);

		$oData['discount_info'] = !is_null($oData['discount'])? "product" : null;

		$oData['status']	= 1; //ожидает оплаты

		$oData['comment'] = !empty($this->comment) ? $this->comment : "Заказ сделан через платежную систему PayQR";

		$oData['user_ip'] = $_SERVER['REMOTE_ADDR'];

		return $oData;
	}

	public function setComment($comment)
	{
		$this->comment = $comment;
	}

	/**
	 * @param int $order_id
	 * @param int $status
	 */
	public function updateStatus($order_id, $status)
	{
		try{
			$this->db->where('id', $order_id);
			$this->db->update('shop_orders', array('status' => $status));
		}
		catch(Exception $e)
		{
			throw new Exception("Ошибка изменения статуса заказа " . $order_id);
		}
	}

	public function setPaid($order_id)
	{
		try{
			$this->db->where('id', $order_id);
			$this->db->update('shop_orders', array('paid' => 1));
		}
		catch(Exception $e)
		{
			throw new Exception("Ошибка изменения статуса оплаты заказа " . $order_id);
		}
	}

	private function __log($message = null, $line = 0, $debug = false, $delete_old_log_file = false)
	{
		if($delete_old_log_file) @unlink("__worker.log");if(empty($message) || !$debug) return;$fp = fopen("__worker.log", "a");fwrite($fp, "[{$line}]\r\n\t{$message}\r\n");fclose($fp);
	}
}