<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$com_info = array(
    'menu_name' => lang('PayQR', 'payqr'), // Menu name
    'description' => lang('PayQR платежная система', 'payqr'), // Module Description
    'admin_type' => 'inside', // Open admin class in new window or not. Possible values window/inside
    'window_type' => 'xhr', // Load method. Possible values xhr/iframe
    'w' => 600, // Window width
    'h' => 550, // Window height
    'version' => '1.0', // Module version
    'author' => 'rakot9@yandex.ru', // Author info
    'icon_class' => 'icon-bullhorn'
);

