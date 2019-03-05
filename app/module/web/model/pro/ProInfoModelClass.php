<?php
class ProInfoModel  extends BaseStatic{}
class ProInfoModelClass extends BaseRsModel
{
	// 设置主健
	public $primary_key			=  	'id';
	public $orderby_key			=   'order_num desc';	

	public function getList($where, $param)
	{
		$sql = "select * from pro_info where $where";
		Sk::$Db->query($sql, $param);
		return Sk::$Db->fetchAll();
	}		
}