<?php
class Login
{
	const LOGIN_ITEM_NAME 		= 		'sk_cash_login_item_';
	const LOGIN_ADMIN_KEY		=		'sk_cash_login_name';

	public static $access_denied  = false;

	// 登录存储
	public static function setLoginInfo($arr_data = null)
	{
		$key = conf('gbl', 'cookiekey'); 
		$json = json_encode($arr_data);	
		return Cookie::setCookie(self::LOGIN_ADMIN_KEY, $json, $key);
	}

	// 获取登陆用户信息
	public static function getLoginInfo($item = null)
	{
		$key = conf('gbl', 'cookiekey'); 
		$data = Cookie::getCookie(self::LOGIN_ADMIN_KEY, $key);
		if ($data) 
		{
			$data = json_decode($data, true);
			return isset($item) ? $data[$item] : $data;
		}
		return array();
	}	

	// 退出登录
	public static function exitLogin()
	{
		Cookie::delCookie(self::LOGIN_ADMIN_KEY);
	}

	// 检测登录
	public static function getLoginState()
	{
		 $key = conf('gbl', 'cookiekey'); 
		 $state = Cookie::getCookie(self::LOGIN_ADMIN_KEY, $key);		
		 return ($state ? true : false);
	}	
}