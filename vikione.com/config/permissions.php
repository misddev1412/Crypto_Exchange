<?php 
/** 
 * Here is the permissions control
 *
 * uses: 'manage_user' => ['ControllerName'] @array
 */

return [
	"dashboard" => ['Admin\AdminController'],
	'manage_user' => ['Admin\UsersController'],
	'manage_tranx' => ['Admin\TransactionController'],
	'manage_kyc' => ['Admin\KycController'],
	'manage_stage' => ['Admin\IcoController'],
	'manage_setting' => [
		'Admin\PageController',
		'Admin\SettingController',
		'Admin\LanguageController',
		'Admin\PaymentMethodController',
		'Admin\IcoController@settings',
		'Admin\IcoController@update_settings'
	]
];