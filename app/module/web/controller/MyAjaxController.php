<?php
class MyAjaxController extends AfterAjaxController
{
	public function __construct()
	{
		Login::getLoginState() || $this->echoJson('帐号未登录');
 		$this->login = Login::getLoginInfo();
		BaseStatic::$ARG = array('shopid' => $this->login['shopid']);  		
	}
}