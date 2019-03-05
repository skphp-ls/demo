<?php
class MainProAjaxController extends MyAjaxController
{
	const CART_KEY_NAME		=		'pro_cart';

	// 下单
	public function orderMark()
	{
		$cart = Cookie::getCookie(self::CART_KEY_NAME);
		if($cart)
		{
			$cart = json_decode($cart, true);
			if(count($cart) > 0)
			{
				$mark = $this->request('mark');
				$orderno = date('YmdHis') . mt_rand(100, 999);
				$arr_data = array(
					'sid'			=>	$this->login['shopid'],
					'orderno'		=>	$orderno,
					'shopname' 		=>  $this->login['shopname'],
					'cashierid'		=>	$this->login['id'],
					'cashname'		=>	$this->login['truename'],
					'og_num'		=>	count($cart),
					'mark'			=>	$mark,
					'status'		=>  AppConf::ORDER_STATUS_NOPAY
				);
				// 事务
				$this->dbTransaction();				
				$orderid = ProOrderModel::insertData($arr_data);
				if($orderid)
				{
					$param = array_keys($cart);
					$where = str_repeat('id=? or ', count($cart));
					$where = rtrim($where, ' or ');
					$cart_list = ProInfoModel::getList($where, $param);
					//print_r($cart_list);die;
					$pro_data = array();
					$money = 0;
					foreach ($cart_list as $val) 
					{
						$money += $val['pro_money'] * $cart[$val['id']];
						$pro_data[] = array(
							'orderid'			=>		$orderid,
							'orderno'			=>		$orderno,
							'pro_id'			=>		$val['id'],
							'pro_code'			=>		$val['pro_code'],
							'pro_name'			=>		$val['pro_name'],
							'category_name'		=>		$val['category_name'],
							'category_pname'	=>		$val['category_pname'],
							'category_path'		=>		$val['category_path'],
							'pro_type'			=>		$val['pro_type'],
							'pro_unit'			=>		$val['pro_unit'],
							'pro_size'			=>		$val['pro_size'],						
							'og_num'			=>		$cart[$val['id']]
						);
						$depot_data[] = array(
							'sid'				=>		$this->login['shopid'],
							'pro_id'			=>		$val['id'],
							'pro_count'			=>		$cart[$val['id']]
						);						
					}
					$this->result = ProOrderGoodsModel::insertMuchData($pro_data);
					$this->dbFinish();
					if($this->result)
					{
						ProOrderModel::updateData($orderid, array('amonut' => $money));
						ProDepotModel::insertMuchData($depot_data);
						Cookie::delCookie(self::CART_KEY_NAME);
					}
				}
			}
		}
	}	

	// 支付
	public function payMark()
	{
		$id = $this->requestAbs('id');
		$mark = $this->request('mark');
		$status = ProOrderModel::fetchItem($id, 'status');
		if($status == AppConf::ORDER_STATUS_NOPAY)
		{
			$this->result = ProOrderModel::updateData($id, array(
				'status' 	=>	 AppConf::ORDER_STATUS_PAY,
				'pay_mark'	=>   $mark,
				'pay_time'  =>   CURRENT_TIME
			));
		}
	}

	// 新增购物车 
	public function addCart(){
		$id = $this->requestAbs('id');
		$n = $this->requestAbs('n');
		$cart = Cookie::getCookie(self::CART_KEY_NAME);
		if($cart)
		{
			$cart = json_decode($cart, true);
		}
		if(isset($cart[$id]))
		{
			$cart[$id]+= $n;
		}else{
			$cart[$id] = $n;
		}
		$this->result = Cookie::setCookie(self::CART_KEY_NAME, json_encode($cart));
	}		
}