<?php
class MainAjaxController extends MyAjaxController
{
	// 客户资料
	public function addUser()
	{   
		$truename = $this->requestStr('truename');
		$phone = $this->requestStr('phone');
		$sex = $this->requestInt('sex');
		$address = $this->requestStr('address');	
 		$sid =  $this->login['shopid'];
 		$arr_data = array(
 			'sid'		 	 =>	 $sid,
 			'sex'		 	 =>	 $sex,
 			'truename'  	 =>  $truename,
			'phone'  	 	 =>  $phone,
			'address'		 =>	 $address
		);
		$this->result = ShopUserModel::insertData($arr_data);
	} 

	// 客户资料
	public function uptUser()
	{   
		$id = $this->requestAbs('id');
		$truename = $this->requestStr('truename');
		$phone = $this->requestStr('phone');
		$sex = $this->requestInt('sex');
		$address = $this->requestStr('address');	
 		$arr_data = array(
 			'sex'		 	 =>	 $sex,
 			'truename'  	 =>  $truename,
			'phone'  	 	 =>  $phone,
			'address'		 =>	 $address
		);
		$this->result = ShopUserModel::updateData($id, $arr_data);	
	} 
}