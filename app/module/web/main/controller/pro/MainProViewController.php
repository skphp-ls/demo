<?php
class MainProViewController extends MyViewController
{ 
	const CART_KEY_NAME = 'pro_cart';

	// 产品查看
	public function vproduct()
	{
		$id = $this->requestAbs('id');
		$data = ProInfoModel::fetchOne($id);
		$this->assign('data', $data);
	}

	// 下单
	public function orderMark()
	{
		$iscart = 'false';
		$cart = Cookie::getCookie(self::CART_KEY_NAME);
		if($cart)
		{
			$iscart = 'true';
		}
		$this->assign('iscart', $iscart);	
	}

	// 下单
	public function payMark()
	{
		$id = $this->requestAbs('id');
		$this->assign('id', $id);
	}	
}