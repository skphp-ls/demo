<?php
class MainLoginController extends AfterViewController
{
	// 会员登陆
	public function index(){}

	public function bindUser()
	{
		$id = $this->requestAbs('id');
		$code = $this->requestStr('code');
		$wx = WxWebAuth::getAccessToken('wx', $code);
		$result = false;
		if($wx['unionid'])
		{
			$result = MemberModel::updateData($id, array('unionid' => $wx['unionid'], 'is_wx' => 1));
		}
		$this->assign('result', $result);
	}

	public function callback()
	{
		$state = $this->requestStr('state');
		$code = $this->requestStr('code');
		$data = WxWebAuth::getAccessToken('web', $code);
		if(isset($data['unionid']))
		{
			//print_r($data);
			$cash = MemberModel::fetchOne(array('unionid' => $data['unionid']));
			if($cash)
			{
				if($cash['is_lock'] > 0)
				{
					die("<div style='text-align:center;'>账户已锁定<br/>请联系公司</div>");
				}
				$arr_data = array(
					'id'	 	 =>		$cash['id'],
					'shopid'	 =>		$cash['shopid'],
					'shopname'	 =>		$cash['shopname'],
					'truename'	 =>		$cash['truename']
				);			
				Login::setLoginInfo($arr_data);
				//redirect('/web/main/index');	
				die("<script>top.location.href='/web/main_sale/order';</script>");
			}
		}
		die("<div style='text-align:center;'>没有登录权限</div>");
	}

	// 退出登录
	public function loginOut()
	{
		Login::exitLogin();
		redirect('index');
	}

	public function phpinfo()
	{
		phpinfo();
	}
}