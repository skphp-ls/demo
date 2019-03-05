<?php
class MyViewController extends AfterViewController
{
    public function __construct()
    {
        parent::__construct();
        Login::getLoginState() || die('帐号未登录');
 		$this->login = Login::getLoginInfo();
		BaseStatic::$ARG = array('shopid' => $this->login['shopid']);       
    }

}