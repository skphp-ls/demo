<?php
/**
 * AuthenticateClass v2.0.3
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class Authenticate extends BaseStatic{}
class AuthenticateClass
{

	private $_auth_info = null;

	/**
	 * cookie登录认证
	 */
	public function cookieAuthLogin($user = 'admin', $pass = '1234', $cookie_time = 0)
	{

		if (isset($_COOKIE[Enum::AUTH_USER_LOGIN_KEY]{0})) 
		{
			$auth_info = $_COOKIE[Enum::AUTH_USER_LOGIN_KEY];

		}else{

			$this->_setAuthUser($user, $pass, $cookie_time);
			setcookie(Enum::AUTH_USER_LOGIN_KEY,  $this->_auth_info);	
		}	

		if (! isset($auth_info) || ! $this->_checkUserPass($auth_info)) 
		{
			$this->_loginWindow();
		}


	}	

	/**
	 * session登录认证
	 */
	public function sessionAuthLogin($user = 'admin', $pass = '1234')
	{
		session_start();
		if (isset($_SESSION[Enum::AUTH_USER_LOGIN_KEY]{0})) 
		{
			$auth_info = $_SESSION[Enum::AUTH_USER_LOGIN_KEY];

		}else{

			$this->_setAuthUser($user, $pass);
			$_SESSION[Enum::AUTH_USER_LOGIN_KEY] = $this->_auth_info;
		}	

		if (! isset($auth_info) || ! $this->_checkUserPass($auth_info)) 
		{
			$this->_loginWindow();
		}
	}	

	/**
	 * 登陆窗口
	 *
	 * @return [type] [description]
	 */
	private function _loginWindow()
	{
		Header("WWW-Authenticate: Basic realm=\"Login\"");  
		Header("HTTP/1.0 401 Unauthorized");
		echo Enum::AUTH_USER_LOGIN_RETURN_TIP;
		exit;		
	}

	/**
	 * 验证信息
	 *
	 * @param [array] $auth_info [用户名密码的数组]
	 *
	 * @return [boolean] [是否正确]
	 */
	private function _checkUserPass($auth_info)
	{
		if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) 
		{
			$user_pass = array($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
			$user_pass_json = md5(json_encode($user_pass));
			if ($user_pass_json == $auth_info) 
			{
				return true;
			}
		}
		return false;
	}


	/**
	 * 用户密码
	 * 
	 * @param [string] $user 用户名
	 * @param [string] $pass 密码
	 */
	private function _setAuthUser($user, $pass)
	{
		$auth_info = array($user , $pass);
		$this->_auth_info = md5(json_encode($auth_info));
	}	
}