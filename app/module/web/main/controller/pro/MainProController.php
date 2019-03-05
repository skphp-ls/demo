<?php
class MainProController extends MyLayoutController
{ 
	const CART_KEY_NAME		=		'pro_cart';
	// 库存记录
	public function index()
	{
		$this->loadGlobal('category');
		$this->page_size = 10;
		$this->page_params = $param = array(
			'c' 	 => $this->requestAbs('c'),
			't'		 => $this->requestAbs('t', 1),
			'n'		 => $this->requestStr('n')
		);
		$where_data = array('union');
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
		$this->getPageCount(ProDepotModel::getRows($where_data));
		$pro_list = ProDepotModel::getList($this->getPageLimit(), $where_data);
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
		$this->assign('primary', 'proindex');
	}


	// 产品下单
	public function order()
	{
		$this->page_size = 10;
		$this->page_params = $param = array(
			'n'		 => $this->requestStr('n')
		);
		$where_data = array('union');
		$where_data[] = array('sid' => $this->login['shopid']);
		if (! empty($param['n'])) 
		{
			$where_data[] = array('orderno like ?', "$param[n]%");
		}
		$this->getPageCount(ProOrderModel::getCount($where_data));
		$data_list = ProOrderModel::pageList($this->getPageLimit(), $where_data);
		if($data_list)
		{
			$this->assign('data_list', $data_list);
			$id = $this->requestAbs('id');
			if($id == 0)
			{
				$id = $data_list[0]['id']; 
			}
			$data = ProOrderModel::fetchOne($id);
			$pro_list = ProOrderGoodsModel::fetchAll(array('orderid' => $id));		
			$this->assign('data', $data);
			$this->assign('pro_list', $pro_list);
		}
		$this->assign('primary', 'proorder');
	}
}