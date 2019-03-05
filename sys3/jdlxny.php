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
	const ORDER_STATUS_NOPAY			=			8;		//待处理


	public static $ST = array(
		self::ORDER_STATUS_CREATE		=>	'<label style="color:red">待处理</label>',
		self::ORDER_STATUS_CONFIRM		=>	'<label style="color:blue">店长确认</label>',
		self::ORDER_STATUS_VERIFY		=>	'<label style="color:green">已核对</label>',
		self::ORDER_STATUS_EXCEPTION 	=>  '<label style="color:red">异常</label>',
		self::ORDER_STATUS_FINISH		=>	'<label style="color:grey">已完结</label>',
		self::ORDER_STATUS_DELIVER		=>  '<label style="color:khaki">已发货</label>',
		self::ORDER_STATUS_RECEIVE		=>	'<label style="color:coral">已收货</label>',
		self::ORDER_STATUS_PAY			=>	'<label style="color:brown">已支付</label>',
		self::ORDER_STATUS_NOPAY		=>	'<label style="color:blue">未支付</label>'
	);


	const PRODUCT_TYPE_1				=			0;		//	普通
	const PRODUCT_TYPE_2				=			1;		// 	次数
	const PRODUCT_TYPE_3				=			2;		//	时限


	public static $PT = array(self::PRODUCT_TYPE_1 => '三元锂', self::PRODUCT_TYPE_2 => '磷酸铁', self::PRODUCT_TYPE_3 => '钛酸锂');

	public static $SEX = array('0' => '未知', '1' => '先生', '2' => '女士');
}