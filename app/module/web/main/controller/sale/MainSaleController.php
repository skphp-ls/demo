<?php
class MainSaleController extends MyLayoutController
{ 

	const CART_KEY_NAME		=		'pro_sale_cart';
	
	public function index()
	{
		$this->loadGlobal('category');
		$id = $this->requestAbs('id');
		$this->page_size = 10;
		$this->page_params = $param = array(
			'c' 	 => $this->requestAbs('c'),
			't'		 => $this->requestAbs('t', 1),
			'n'		 => $this->requestStr('n')
		);
		$where_data = array('union');
		//$where_data[] = array('sid' => $this->login['shopid']);	
		if ($param['c'] > 0) 
		{
			$where_data[] = array('category_path like ?', ",$param[c],%");
		}
		if (! empty($param['n'])) 
		{
			if($param['t'] == 1)
			{
				$where_data[] = array('pro_name like ?', "$param[n]%");
			}else{
				$where_data[] = array('pro_code like ?', "$param[n]%");
			}
		}
		$this->getPageCount(ProDepotModel::getRRows($where_data));
		$pro_list = ProDepotModel::getRList($this->getPageLimit(), $where_data);
		$this->assign('pro_list', $pro_list);

		# 购物车
		//Cookie::delCookie('pro_cart');
		$cart = Cookie::getCookie(self::CART_KEY_NAME);
		if($cart)
		{
			$cart = json_decode($cart, true);
			if(count($cart) > 0)
			{
				$param = array_keys($cart);
				$where = array_fill(0, count($cart), 'id=?');
				$where = implode(' or ', $where);
				$where = rtrim($where, ' or ');
				$cart_list = ProInfoModel::getList($where, $param);
				$this->assign('cart', $cart);
				$this->assign('cart_list', $cart_list);
			}
		}

		#客户信息
		$user_list = ShopUserModel::fetchAll(array(
			'sid'	=>	$this->login['shopid']
		), 'id,truename,sex,phone');
		$this->assign('user_list', $user_list);
		$this->assign('primary', 'saleindex');
	}

	public function order()
	{
		$id = $this->requestAbs('id');
		$this->page_size = 10;
		$this->page_params = $param = array(
			'n'		 => $this->requestStr('n')
		);
		$where_data = array('union');
		$where_data[] = array('sid' => $this->login['shopid']);			
		if (! empty($param['n'])) 
		{
			$where_data[] = array('pid like ?', "$param[n]%");
		}
		$this->getPageCount(ShopSaleModel::getCount($where_data));
		$data_list = ShopSaleModel::pageList($this->getPageLimit(), $where_data);
		if($data_list)
		{
			$this->assign('data_list', $data_list);
			$id = $this->requestAbs('id');
			if($id == 0)
			{
				$id = $data_list[0]['id']; 
			}
			$data = ShopSaleModel::fetchOne($id);
			$pro_list = ShopSaleGoodsModel::fetchAll(array('pid' => $id));		
			$this->assign('data', $data);
			$this->assign('pro_list', $pro_list);
		}		
		$this->assign('primary', 'saleorder');
	}
}