<?php

if (!function_exists('getButton')) {

	/**
	 * @param string $page
	 * @param int $products
	 * @return string
	 */
	function getButton($page, $product = null)
	{
		return CI::$APP->load->module('payqr')->getButton($page, $product);
	}


}

if (!function_exists('getMerchantId')) {

	function getMerchantId()
	{
		return CI::$APP->load->module('payqr')->getMerchantId();
	}
}