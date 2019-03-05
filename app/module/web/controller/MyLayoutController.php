<?php
class MyLayoutController extends AfterLayoutController
{
	public function __construct()
	{
		parent::__construct();
		Login::getLoginState() || redirect('/web/main_login/index');
		$this->login = Login::getLoginInfo();
		BaseStatic::$ARG = array('shopid' => $this->login['shopid']);
	}
}