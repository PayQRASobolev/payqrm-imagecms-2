<?php

//получаем способы доставки товара
//получаем статусы заказов
//получаем информацию о кнопке

$delivery_cases = array();

if(isset($payqr_settings) && !empty($payqr_settings->payqr_require_deliverycases)
	&& $payqr_settings->payqr_require_deliverycases != "deny")
{
	$DeliveryModel = \Payqr::getInstance()->getDeliveryModel();
	$PaymentModel = \Payqr::getInstance()->getPaymentModel();


	$deliveries = $DeliveryModel->getAvailableDelivery($PaymentModel->getId());

	$i = 1;

	foreach($deliveries as $delivery)
	{
		$delivery['free_from'] = $PaymentModel->calculatePrice(intval($delivery['free_from']));
		
		if(!empty($delivery['free_from']) && $delivery['free_from'] <= $Payqr->objectOrder->getAmount())
		{
			$delivery_cases[] = array(
				'article' => $delivery['id'],
				'number' => $i++,
				'name' => $delivery['name'],
				'description' => $delivery['description'],
				'amountFrom' => 0,
				'amountTo' => 0,
			);
		}
		else
		{
			$delivery_amount = 0;

			if($delivery['is_price_in_percent'] > 1)
			{
				$delivery_amount = ( $delivery['price'] / 100 ) * intval($Payqr->objectOrder->getAmount());
			}
			else 
			{
				$delivery_amount = $PaymentModel->calculatePrice((int)$delivery['price']);
			}

			$delivery_cases[] = array(
				'article' => $delivery['id'],
				'number' => $i++,
				'name' => $delivery['name'],
				'description' => $delivery['description'],
				'amountFrom' => $delivery_amount,
				'amountTo' => $delivery_amount,
			);
		}
	}
}

$Payqr->objectOrder->setDeliveryCases($delivery_cases);

function __log($message = null, $line = 0, $debug = false, $delete_old_log_file = false)
{
	if($delete_old_log_file) @unlink("__worker.log");if(empty($message) || !$debug) return;$fp = fopen("__worker.log", "a");fwrite($fp, "[{$line}]\r\n\t{$message}\r\n");fclose($fp);
}