<?php
class AppConf
{
	const ORDER_STATUS_CREATE			=			0;		//待处理
	const ORDER_STATUS_CONFIRM			=			1;		//已确认
	const ORDER_STATUS_VERIFY			=			2;		//已核对
	const ORDER_STATUS_DELIVER			=			3;		//已发货
	const ORDER_STATUS_RECEIVE			=			4;		//已收货
	const ORDER_STATUS_EXCEPTION		=			5;		//异常				
	const ORDER_STATUS_FINISH			=			6;		//完结	
	const ORDER_STATUS_PAY				=			7;		//已支付	

	const PRODUCT_TYPE_NORMAL			=			0;		//	普通
	const PRODUCT_TYPE_COUNT			=			1;		// 	次数
	const PRODUCT_TYPE_MONTH			=			2;		//	时限

	public static $SEX = array('0' => '未知', '1' => '先生', '2' => '女士');

	public static $PT = array(self::PRODUCT_TYPE_NORMAL => '家居', self::PRODUCT_TYPE_COUNT => '次数', self::PRODUCT_TYPE_MONTH => '时限');

	public static $ST = array(
		self::ORDER_STATUS_CREATE		=>	'<label style="color:red">待处理</label>',
		self::ORDER_STATUS_CONFIRM		=>	'<label style="color:blue">店长确认</label>',
		self::ORDER_STATUS_VERIFY		=>	'<label style="color:green">已核对</label>',
		self::ORDER_STATUS_EXCEPTION 	=>  '<label style="color:red">异常</label>',
		self::ORDER_STATUS_FINISH		=>	'<label style="color:grey">已完结</label>',
		self::ORDER_STATUS_DELIVER		=>  '<label style="color:khaki">已发货</label>',
		self::ORDER_STATUS_RECEIVE		=>	'<label style="color:coral">已收货</label>',
		self::ORDER_STATUS_PAY			=>	'<label style="color:brown">已支付</label>'
	);

	public static function IDENTITY($id = null, $item = null)
	{
		$key = 'member_identity';
		// Mc::delete($key);
		$data = Mc::get($key);
		if($data == false)
		{
			$data_list = ConfMemberModel::fetchAll();
			$data = ArrData::indexData('id', $data_list);
			Mc::set($key, $data);
		}
		if(isset($id) && isset($item))
		{
			return $data[$id][$item];
		}
		if(isset($id))
		{
			return $data[$id];
		}		
		return $data;
	}
}