<?php

if(isset($payqr_settings, $payqr_settings->payqr_status_paid))
{
	$order_id = $Payqr->objectOrder->getOrderId();

	$OrderModel = \Payqr::getInstance()->getOrderModel();

	$payqr_settings->payqr_status_paid = intval($payqr_settings->payqr_status_paid);

	$OrderModel->updateStatus($order_id, $payqr_settings->payqr_status_paid);

	$OrderModel->setPaid($order_id);	
}