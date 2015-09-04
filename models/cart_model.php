<?php

class Cart_model extends CI_Model{

	private $amount;

	public function __construct()
	{
		parent::__construct();

		$this->amount = 0;
	}

	/**
	 * 
	 * @return int;
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	* @param $payqr_cart
	* @return bool
	*/
	public function actualizeCart(&$payqr_cart)
	{
		$ProductModel = \Payqr::getInstance()->getProductModel();

		$PaymentModel = \Payqr::getInstance()->getPaymentModel();

		foreach($payqr_cart as $payqr_product)
		{
			//получаем товар и его цену и модифицируем data-cart
			$item = $ProductModel->getProduct($payqr_product->article, \MY_Controller::getCurrentLocale());

			//пересчитываем сумму позиции товаров для комплекта товаров
			if($ProductModel->checkKit($payqr_product->article))
			{
				$products = $ProductModel->getKitProducts($payqr_product->article);

				$product_kit_amount = 0;

				$kit_discount = 0;

				$kit_name = "Комплект товаров: ";

				foreach($products as $product_id)
				{
					$kit_item = $ProductModel->getProduct($product_id, \MY_Controller::getCurrentLocale());

					if($kit_discount = $ProductModel->getKitDiscount($payqr_product->article, $product_id))
					{
						$product_kit_amount += isset($kit_item->price)? ceil($kit_item->price - ($kit_item->price * $kit_discount / 100) ): 0 ;
					}
					else
					{
						$product_kit_amount += isset($kit_item->price)? $kit_item->price : 0 ;
					}

					$kit_name .= " " . $kit_item->name;
				}

				//рассчитываем стоимость комплекта
				$payqr_product->amount = $PaymentModel->calculatePrice($product_kit_amount) * $payqr_product->quantity;

				$payqr_product->name = $kit_name;

				continue;
			}

			if(!empty($item))
			{
				$payqr_product->amount = isset($item->price) && !empty($item->price) ? $item->price : 0;

				$payqr_product->amount = $PaymentModel->calculatePrice($payqr_product->amount) * $payqr_product->quantity;

				$payqr_product->name = $item->name;

				continue;
			}

			return false;
		}

		return true;
	}

	/**
	 * @param array $payqr_cart
	 * @return int $discount
	 */
	public function calcCartDiscount($payqr_cart)
	{
		$discount = 0;

		//Подсчитываем суммарную скидку на data-cart
		$ProductModel = \Payqr::getInstance()->getProductModel();

		$PaymentModel = \Payqr::getInstance()->getPaymentModel();

		foreach($payqr_cart as $payqr_product)
		{
			//пересчитываем сумму позиции товаров для комплекта товаров
			if($ProductModel->checkKit($payqr_product->article))
			{
				$products = $ProductModel->getKitProducts($payqr_product->article);

				foreach($products as $product_id)
				{
					$product = $ProductModel->getProduct($product_id, \MY_Controller::getCurrentLocale());

					if($kit_discount = $ProductModel->getKitDiscount($payqr_product->article, $product_id))
					{
						$discount += ceil($product->price * $kit_discount /100 ) ; //* $payqr_product->quantity;
					}
				}
			}
		}

		return $discount;
	}

	/**
	* @param array $payqr_cart
	* @param mixed $delivery_id
	* @return int
	*/
	public function getCartAmount($payqr_cart, $delivery_id = null)
	{
		$total = 0;

		$ProductModel = \Payqr::getInstance()->getProductModel();

		$PaymentModel = \Payqr::getInstance()->getPaymentModel();

		$checkInStock = $this->CheckProductInStock();

		foreach($payqr_cart as $cart_product)
		{
			//пересчитываем сумму позиции товаров для комплекта товаров
			if($ProductModel->checkKit($cart_product->article))
			{
				$products = $ProductModel->getKitProducts($cart_product->article);

				$product_kit_amount = 0;

				$kit_discount = 0;

				foreach($products as $product_id)
				{
					$kit_item = $ProductModel->getProduct($product_id, \MY_Controller::getCurrentLocale(), 'kit');

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
				$total += $PaymentModel->calculatePrice($product_kit_amount) * $cart_product->quantity;
			}
			else 
			{
				$product = $ProductModel->getProduct($cart_product->article, \MY_Controller::getCurrentLocale());

				$total += $cart_product->quantity * $PaymentModel->calculatePrice($product->price);

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

			$delivery->free_from = intval($delivery->free_from);

			$delivery->free_from = $PaymentModel->calculatePrice($delivery->free_from);
			
			if(isset($delivery->free_from) && !empty($delivery->free_from))
			{
				if($total < $delivery->free_from)
				{
					$delivery->price = $PaymentModel->calculatePrice((int)$delivery->price);

					$total += $delivery->price;
				}
				else {
					$this->__log("товар больше указанной суммы в БД", __LINE__, true);
				}
			}
			else
			{
				$total += $PaymentModel->calculatePrice((int)$delivery->price);
			}
			
			if(isset($delivery->is_price_in_precent))
			{
				$delivery->is_price_in_precent = intval($delivery->is_price_in_precent);

				$total = $total + ceil($total * $delivery->is_price_in_precent/100);
			}
		}

		return $total;
	}

	public function CheckProductInStock()
	{
		$res = $this->db->where('name','ordersCheckStocks')->get('shop_settings')->row_array();

		if(!$res || empty($res->value))
		{
			return false;
		}
		return true;
	}

	public function clear()
	{
		//Очистка корзины
		\Cart\BaseCart::getInstance()->removeAll();
	}

	private function __log($message = null, $line = 0, $debug = false, $delete_old_log_file = false)
	{
		if($delete_old_log_file) @unlink("__worker.log");if(empty($message) || !$debug) return;$fp = fopen("__worker.log", "a");fwrite($fp, "[{$line}]\r\n\t{$message}\r\n");fclose($fp);
	}
}