<?php

class PayqrButton_model extends CI_Model {
	
	//место расположение кнопки, данные параметры учиываются в форме настройки кнопки
	private $page_prefix_list = array("cart", "product", "category");

	//заполняем настройками, получаемыми из БД
	private $db_payqr_button_config = array();

	//текущая обрабатыаемая страница
	private $page;

	//каталог имеющихся значения css классов
	private $button_config_css = array(
		"payqr_button_color",
		"payqr_button_form",
		"payqr_button_shadow",
		"payqr_button_gradient",
		"payqr_button_font_trans",
		"payqr_button_font_width",
		"payqr_button_text_case"
	);

	//
	private $button_config_style = array(
		"payqr_button_height",
		"payqr_button_width"
	);
	
	private $button_config_attr = array(
		"payqr_require_firstname",
		"payqr_require_lastname",
		"payqr_require_middlename",
		"payqr_require_phone",
		"payqr_require_email",
		"payqr_require_delivery",
		"payqr_require_deliverycases",
		"payqr_require_pickpoints",
		"payqr_require_promo"
	);

	public function __construct()
	{
		parent::__construct();		
	}

	public function initSettings($page, $payqr_settings)
	{
		$this->page = $page;
		
		$this->getDBPayQRButtonConfig($payqr_settings);
	}

	/**
	 * @param string $scenario
	 * @param array $data_cart
	 * @param float $amount
	 * @param array $user_data
	 * Производим инициализацию кнопки 
	 */
	public function initButton($scenario = "buy", $data_cart = array(), $amount = 0, $user_data = array())
	{
		$css = $attr = $style = "";

		if(!$this->isShow())
		{
			return "";
		}
		/*
		//Инициализируем сообщения пользователя
		if(isset($this->db_payqr_button_config['common']['payqr_user_message_text']) && !empty($this->db_payqr_button_config['common']['payqr_user_message_text']))
		{
			//
			$attr .= "data-message-text='".$this->db_payqr_button_config['common']['payqr_user_message_text']."' ";
		}
		if(isset($this->db_payqr_button_config['common']['payqr_user_message_imageurl']) && !empty($this->db_payqr_button_config['common']['payqr_user_message_imageurl']))
		{
			//
			$attr .= "data-message-imageurl='".$this->db_payqr_button_config['common']['payqr_user_message_imageurl']."' ";
		}
		if(isset($this->db_payqr_button_config['common']['payqr_user_message_url']) && !empty($this->db_payqr_button_config['common']['payqr_user_message_url']))
		{
			//
			$attr .= "data-message-url='".$this->db_payqr_button_config['common']['payqr_user_message_url']."' ";
		}
		*/
		//

		if(!isset($this->db_payqr_button_config[$this->page]) || empty($this->db_payqr_button_config[$this->page]))
		{
			return 
				"<button class='payqr-button'
						data-scenario='". $scenario ."' 
						data-cart='" . json_encode($data_cart, JSON_UNESCAPED_UNICODE) . "' 
						data-amount='" . $amount . "'
						" .$this->initUserData($user_data). ">
					Купить быстрее
				</button>";
		}

		$_db_payqr_button_config = array_merge($this->db_payqr_button_config[$this->page], $this->db_payqr_button_config['common']);

		foreach($_db_payqr_button_config as $property_name => $property_value)
		{
			$property_type  = $this->checkPropertyType($property_name);

			if(!in_array($property_type, array('attr', 'css', 'style')))
			{
				continue;
			}

			if(in_array($property_value, array('default', 'auto')) || empty($property_value))
			{
				continue;
			}

			switch ($property_type)
			{
				case 'css':
					$css .= ' payqr-button_' . $property_value;
					break;
				case 'attr':
					$attr .= ' data-' . str_replace('payqr_require_', '', $property_name) . "-required='" . $property_value . "' ";
					break;
				case 'style':
					
					preg_match_all("/[0-9]*/i", $property_value, $matches);
					
					if(isset($matches[0]) && !empty($matches[0]))
					{
						$style .= ' '. str_replace('payqr_'.$this->page.'_button_', '', $property_name).':'. implode($matches[0], '') .'px;';
					}
					break;
				default:
					break;
			}
		}


		if(!empty($css)) $css = " class='payqr-button " . $css . "' ";
		else $css = " class='payqr-button' ";

		if(!empty($attr)) $attr = $attr;

		if(!empty($style)) $style = " style='" . $style . "' ";

		if(in_array($this->page, ['product', 'category']))
		{
			$data_cart = json_encode(array($data_cart), JSON_UNESCAPED_UNICODE);
		}
		if($this->page == 'cart')
		{
			$data_cart = json_encode($data_cart, JSON_UNESCAPED_UNICODE);
		}

		return 
				"<button ". $css . $attr . $style ." 
						data-scenario='". $scenario ."' 
						data-cart='" . $data_cart . "' 
						data-amount='" . $amount . "' 
						" .$this->initUserData($user_data). ">
					Купить быстрее
				</button>";
	}

	public function initUserData(array $user_data)
	{
		foreach($user_data as $key => $data)
		{
			if(empty($data))
			{
				unset($user_data[$key]);
			}
		}

		return empty($user_data)? "" : "data-userdata='".json_encode(array($user_data))."'";
	}

	/**
	 * @param array $payqr_settings
	 * @return mixed
	 * 
	 * Заполняем переменную характеристик
	 */
	public function getDBPayQRButtonConfig($payqr_settings)
	{
		if(empty($payqr_settings))
		{
			$this->db_payqr_button_config  = array();

			return;
		}

		foreach($this->page_prefix_list as $page)
		{
			foreach($payqr_settings as $key => $setting)
			{
				if(strpos($key, $page) !== false)
				{
					$this->db_payqr_button_config[$page][$key] = $setting;
				}
				else 
				{
					$this->db_payqr_button_config['common'][$key] = $setting;
				}
			}
		}
		return;
	}

	/**
	 * @return bool
	 */
	public function isShow()
	{
		if(empty($this->db_payqr_button_config) || !isset($this->db_payqr_button_config[$this->page]))
		{
			return false;
		}

		if($this->db_payqr_button_config[$this->page]['payqr_button_show_on_' . $this->page] == "no")
		{
			return false;
		}

		return true;
	}

	/**
	 * @param string $property
	 * @return string ("attr" | "css" | "style")
	 */
	private function checkPropertyType($property)
	{
		if($this->isPropertyAttr($property))
		{
			return "attr";
		}
		if($this->isPropertyCss($property))
		{
			return "css";
		}
		if($this->isPropertyStyle($property))
		{
			return "style";
		}
	}

	/**
	 * @param string $property
	 * @return bool
	 */
	private function isPropertyCss($property)
	{
		$bytton_property = str_replace( '_' . $this->page, '', $property);

		$bytton_property = trim($bytton_property);

		return in_array($bytton_property, $this->button_config_css)? true : false;
	}

	/**
	 * @param string $property
	 * @return bool
	 */
	private function isPropertyAttr($property)
	{
		return in_array($property, $this->button_config_attr)? true : false;
	}

	/**
	 * @param string $property
	 * @return bool
	 */
	private function isPropertyStyle($property)
	{
		$bytton_property = str_replace( '_' . $this->page, '', $property);

		return in_array($bytton_property, $this->button_config_style)? true : false;
	}
}