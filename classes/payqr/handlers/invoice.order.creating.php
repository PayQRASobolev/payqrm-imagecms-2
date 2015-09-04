<?php

//Пользователя гость
$user_id = null;

$cData = $Payqr->objectOrder->getCustomer();
$uData = json_decode($Payqr->objectOrder->getUserData());
$uData = isset($uData[0])? $uData[0] : $uData;

if(!isset($uData->user_id) && empty($uData->user_id)) 
{
	if(!isset($cData->email) && !isset($uData->email))
	{
		//фэйлим заказ клиента
		//система не передала email клиента
		return false;
	}

	//создаем пользователя в системе
	$user_email = isset($cData->email)? $cData->email : $uData->email;


	$user_id = null;
}
else
{
	//имеем клиента, от имени которого будем создавать заказ в системе
	$user_id = $uData->user_id;
}

//Формируем информацию о покупателе
$user_for_order_comment = "Заказ сделан через платежный сервис PayQR. ";

if(isset($cData->firstName) && !empty($cData->firstName))
{
	$user_for_order_comment .= "Имя покупателя: " . $cData->firstName . ". ";
}

if(isset($cData->lastName) && !empty($cData->lastName))
{
	$user_for_order_comment .= "Фамилия покупателя: " . $cData->lastName . ". ";
}

if(isset($cData->middlename) && !empty($cData->middlename))
{
	$user_for_order_comment .= "Отчество покупателя: " . $cData->middlename . ". ";
}

if(isset($cData->delivery) && !empty($cData->delivery))
{
	$user_for_order_comment .= "Доставка покупателя: " . $cData->delivery . ". ";
}

if(isset($cData->promo) && !empty($cData->promo))
{
	$user_for_order_comment .= "Промо-код покупателя: " . $cData->promo . ". ";
}

if(isset($cData->email) && !empty($cData->email))
{
	$user_for_order_comment .= "Email покупателя: " . $cData->email . ". ";
}

if(isset($cData->phone) && !empty($cData->phone))
{
	$user_for_order_comment .= "Телефон покупателя: " . $cData->phone . ". ";
}

//получаем статусы заказов
$status_created = isset($payqr_settings->payqr_status_creatted) && !empty($payqr_settings->payqr_status_creatted)? $payqr_settings->payqr_status_creatted : 0;


//создаем заказ на основе актуализированным данных
$OrderModel = \Payqr::getInstance()->getOrderModel();

$OrderModel->setComment($user_for_order_comment);

$oOrder = $OrderModel->initOrder($Payqr);

$amount = $OrderModel->getTotal();

if(empty($amount))
{
	//не смогли посчитать сумму заказа
	return false;
}

$Payqr->objectOrder->setAmount($amount);

$order_id = $OrderModel->CreateOrder();

if(!$order_id)
{
	//фэйлим заказ клиента
	//не получилось создать заказ
	return false;
}

//формируем данные для таблицы order_items
$Payqr->objectOrder->setOrderId($order_id);

//проверяем в каком контексте был приобретен товар
if(isset($uData->page) && in_array($uData->page, array('category', 'product')))
{
	//корзину не будем очищать, пропускаем данную ветку
}
else 
{
	//производим очистку корзины
}

$userdata = array(
			"user_id" => $user_id,
			"session_id" => $uData->session_id,
			"order_id" => $order_id,
			"amount" => $amount,
			"message" => (isset($payqr_settings->payqr_user_message_text)? $payqr_settings->payqr_user_message_text : ""),
			"messageImageURL" => (isset($payqr_settings->payqr_user_message_imageurl)? $payqr_settings->payqr_user_message_imageurl : ""),
			"messageURL" => (isset($payqr_settings->payqr_user_message_url)? $payqr_settings->payqr_user_message_url : "")
);

$Payqr->objectOrder->setUserData(json_encode($userdata));