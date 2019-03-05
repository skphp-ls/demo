<?php
class MainSaleAjaxController extends MyAjaxController
{
	const CART_KEY_NAME		=		'pro_sale_cart';

	// 下单
	public function index()
	{
		$cart = Cookie::getCookie(self::CART_KEY_NAME);
		if($cart)
		{
			$cart = json_decode($cart, true);
			if(count($cart) > 0)
			{
				$userid = $_POST['userid'][0];
				$amonut = $this->requestInt('amonut');
				$mark = $this->request('mark');
				$arr_data = array(
					'sid'			=>	$this->login['shopid'],
					'suid'			=>	$userid,
					'sname'			=>	ShopUserModel::fetchItem($userid, 'truename'),
					'shopname' 		=>  $this->login['shopname'],
					'cashierid'		=>	$this->login['id'],
					'cashname'		=>	$this->login['truename'],
					'og_num'		=>	count($cart),
					'amonut'		=>	$amonut,
					'mark'			=>	$mark
				);
				// 事务
				$this->dbTransaction();				
				$pid = ShopSaleModel::insertData($arr_data);
				if($pid)
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
							'pid'				=>		$pid,
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
					}
					$this->result = ShopSaleGoodsModel::insertMuchData($pro_data);
					$this->dbFinish();
					if($this->result)
					{
						Cookie::delCookie(self::CART_KEY_NAME);
					}
				}
			}
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