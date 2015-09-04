<?php

use \Payqr\classes\payqr_logs;
use \Payqr\classes\payqr_receiver;

class PayqrReceiver_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
	}

	public function receiver()
	{

		$PayqrModel = \Payqr::getInstance()->getPayqrModel();

		$payqr_settings = $PayqrModel->getSettings();

		\Payqr\payqr_config::init($payqr_settings->payqr_merchant_id, $payqr_settings->payqr_merchant_secret_key_in, $payqr_settings->payqr_merchant_secret_key_out);
		\Payqr\payqr_config::$logFile = "payqr.log";
		\Payqr\payqr_config::$enabledLog = true;

		payqr_logs::addEnter();

		try{
			$Payqr = new payqr_receiver(); // создаем объект payqr_receiver
			$Payqr->receiving(); // получаем идентификатор счета на оплату в PayQR
			// проверяем тип уведомления от PayQR
			switch ($Payqr->getType()) {
				case 'invoice.deliverycases.updating':
					// нужно вернуть в PayQR список способов доставки для покупателя
					require_once PAYQR_HANDLER.'invoice.deliverycases.updating.php';
					break;
				case 'invoice.pickpoints.updating':
					// нужно вернуть в PayQR список пунктов самовывоза для покупателя
					require_once PAYQR_HANDLER.'invoice.pickpoints.updating.php';
					break;
				case 'invoice.order.creating':
					// нужно создать заказ в своей учетной системе, если заказ еще не был создан, и вернуть в PayQR полученный номер заказа (orderId), если его еще не было
					require_once PAYQR_HANDLER.'invoice.order.creating.php';
					break;
				case 'invoice.paid':
					// нужно зафиксировать успешную оплату конкретного заказа
					require_once PAYQR_HANDLER.'invoice.paid.php';
					break;
				case 'invoice.failed':
					// ошибка совершения покупки, операция дальше продолжаться не будет
					require_once PAYQR_HANDLER.'invoice.failed.php';
					break;
				case 'invoice.cancelled':
					// PayQR зафиксировал отмену конкретного заказа до его оплаты
					require_once PAYQR_HANDLER.'invoice.cancelled.php';
					break;
				case 'invoice.reverted':
					// PayQR зафиксировал полную отмену конкретного счета (заказа) и возврат всей суммы денежных средств по нему
					require_once PAYQR_HANDLER.'invoice.reverted.php';
					break;
				case 'revert.failed':
					// PayQR отказал интернет-сайту в отмене счета и возврате денежных средств покупателю
					require_once PAYQR_HANDLER.'revert.failed.php';
					break;
				case 'revert.succeeded':
					// PayQR зафиксировал отмену счета интернет-сайтом и вернул денежные средства покупателю
					require_once PAYQR_HANDLER.'revert.succeeded.php';
					break;
				default:
					break;
			}
			$Payqr->response();
		}
		catch (payqr_exeption $e)
		{
			if(file_exists(PAYQR_ERROR_HANDLER.'invoice_action_error.php'))
			{
				$response = $e->response;
				require PAYQR_ERROR_HANDLER.'receiver_error.php';
			}
		}
	}

	private function __log($message = null, $line = 0, $debug = false, $delete_old_log_file = false)
	{
		if($delete_old_log_file) @unlink("__worker.log");if(empty($message) || !$debug) return;$fp = fopen("__worker.log", "a");fwrite($fp, "[{$line}]\r\n\t{$message}\r\n");fclose($fp);
	}
}