<?php
class MainViewController extends MyViewController
{ 

	// 会员信息
	public function addUser(){
	}

	// 会员修改
	public function uptUser(){
		$id = $this->requestAbs('id');
		$data = ShopUserModel::fetchOne($id);	
		$this->assign('data', $data);
	}
}