<?php
class Cookie{

	// 配置
	private static $_domain = false;
	private static $_path = '/';	
	private static $_key_name = 'skphp_';

	// 设置配置
	public static function setDomain($domain)
	{
		$this->$_domain = $domain;
	}

	// 设置cookie
	public static function setCookie($key, $val, $keys = null, $exp_time = 0)
	{
		if ($exp_time > 0) {
			$exp_time = time() + $exp_time;
		}
		setcookie($key, urlencode($val), $exp_time, self::$_path, self::$_domain, NULL, true);
		if (isset($keys))
		{
			$sha_val = sha1($val);
			$vs = substr($sha_val, 0, 16);
			$ve = substr($sha_val, 16);
			$sha_keys = sha1($keys);
			$ks = substr($sha_keys, 0, 16);
			$ke = substr($sha_keys, 16);		
			$sign = sha1($vs.$ke.$ve.$ks);
			setcookie(self::$_key_name . $key, $sign, $exp_time, self::$_path, self::$_domain, NULL, true);
		}
		return true;			
	}



	// 获取cookie
	public static function getCookie($key, $keys = null)
	{
		if (isset($_COOKIE[$key])) 
		{
			$val = urldecode($_COOKIE[$key]);	
			if (isset($keys)) 
			{
				$keys_val = $_COOKIE[self::$_key_name . $key];
				$sha_val = sha1($val);
				$vs = substr($sha_val, 0, 16);
				$ve = substr($sha_val, 16);
				$sha_keys = sha1($keys);
				$ks = substr($sha_keys, 0, 16);
				$ke = substr($sha_keys, 16);			
				$sign = sha1($vs.$ke.$ve.$ks);
				//echo $keys_val . '==' . $sign;
				if ($keys_val != $sign)
				{
					return false;
				}
			}	
			return $val;		
		}		
		return false;		
	}	

	// 删除cookie
	public static function delCookie($key)
	{
		setcookie($key, null, -1, self::$_path, self::$_domain, NULL, true);
		setcookie(self::$_key_name . '_' . $key, null, -1, self::$_path, self::$_domain, NULL, true);
	}
}