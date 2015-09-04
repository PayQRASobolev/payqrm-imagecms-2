<?php

if(isset($payqr_settings, $payqr_settings->payqr_status_cancelled))
{
	$order_id = $Payqr->objectOrder->getOrderId();
	
	$OrderModel = \Payqr::getInstance()->getOrderModel();

	$payqr_settings->payqr_status_cancelled = intval($payqr_settings->payqr_status_cancelled);

	$OrderModel->updateStatus($order_id, $payqr_settings->payqr_status_cancelled);
}