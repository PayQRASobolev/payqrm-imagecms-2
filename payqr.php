<?php

if (!defined('BASEPATH')) 
{
    exit('No direct script access allowed');
}

class Payqr extends MY_Controller 
{
	/**
     * @var Payqr 
     */
    private static $instance;


	public function __construct()
	{
		parent::__construct();

		$lang = new MY_Lang();

        $lang->load('payqr');

        $this->load->model('payqr_model');

        $this->load->model('payqrbutton_model');

        $this->load->model('product_model');

        $this->load->model('order_model');

        $this->load->model('cart_model');

        $this->load->model('delivery_model');

        $this->load->model('payment_model');

        $this->cart_model->CheckProductInStock();

        $obj = CI::$APP;
        
        $obj->template->registerJsFile('/application/modules/payqr/assets/js/payqr.js');
	}

	public static function getInstance() {

        if (is_null(self::$instance)) {
            self::$instance = new Payqr();
        }
        return self::$instance;
    }

	public function index()
	{
		$this->core->error_404();
	}

	public function payqr_receiver()
	{
		//инициализируем payqr config файл
		ClassLoader::getInstance()
            					->registerNamespacedPath(__DIR__ . '/classes')
            					->registerAlias(__DIR__ . '/classes/payqr', 'Payqr');

		$this->load->model('payqrreceiver_model');

		$this->payqrreceiver_model->receiver();

		return;
	}

	public function clear_cart()
	{
		$this->cart_model->clear();
	}

	public function getMerchantId()
	{
		return $this->payqr_model->getMerchantId();
	}

	/**
	 * @param string $page
	 * @param int|null $product
	 * @return string
	 */
	public function getButton($page, $product = null)
	{
		return $this->payqr_model->initButton($page, $product);
	}

	public function getPayqrModel()
	{
		return $this->payqr_model;
	}

	public function getProductModel()
	{
		return $this->product_model;
	}

	public function getButtonModel()
	{
		return $this->payqrbutton_model;
	}

	public function getOrderModel()
	{
		return $this->order_model;
	}

	public function getCartModel()
	{
		return $this->cart_model;
	}

	public function getDeliveryModel()
	{
		return $this->delivery_model;
	}

	public function getPaymentModel()
	{
		return $this->payment_model;
	}

	public function autoload()
	{
		$this->load->helper("payqr");
	}

	public function changeStatus(){}

	public function handler(){}

	public function initSettings(){}

	public function _install()
	{
		$this->payqr_model->install();
	}

	public function _deinstall()
	{
		$this->payqr_model->uninstall();
	}

	private function __log($message = null, $line = 0, $debug = false, $delete_old_log_file = false)
	{
		if($delete_old_log_file) @unlink("__worker.log");if(empty($message) || !$debug) return;$fp = fopen("__worker.log", "a");fwrite($fp, "[{$line}]\r\n\t{$message}\r\n");fclose($fp);
	}
}