<?php

if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}

class Admin extends BaseAdminController {

	public function __construct()
	{
		parent::__construct();
		
		$lang = new MY_Lang();
		
		$lang->load('payqr');

		$this->lang->load('payqr');

		$this->load->model('payqr_model');
		
		$this->load->language('payqr');

		$obj = CI::$APP;

		$obj->template->registerJsFile('/application/modules/payqr/assets/js/admin.js');
	}

	public function index()
	{
		\CMSFactory\assetManager::create()
			->setData(
					[
						'entity' => $this->payqr_model->getSettings(),
						'isRUR' => $this->payqr_model->isRURCurrency()
					]
				)
			->renderAdmin('payqr');
	}

	public function save_button()
	{
		foreach($_POST as $key => $_postData)
		{
			if(strpos($key, "payqr") === false )
			{
				unset($_POST[$key]);
			}
		}

		$this->payqr_model->saveSettings($_POST);
	}
}
