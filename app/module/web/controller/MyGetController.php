<?php
class MyGetController extends SkGetController
{
	public function __construct()
	{
		Login::getLoginState() || redirect('/system/admin_login/index');
 		$this->login = Login::getLoginInfo();
		BaseStatic::$ARG = array('shopid' => $this->login['shopid']);  				
	}
}