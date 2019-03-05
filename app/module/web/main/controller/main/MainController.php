<?php
class MainController extends MyLayoutController
{ 
	public function user(){
		$this->page_size = 10;
		$this->page_params = $param = array(
			'n' => $this->requestStr('n')
		);
		$where_data = array('sid' => $this->login['shopid']);
		if (! empty($param['n'])) 
		{
			$where_data = array('phone like ?', "$param[n]%");
		}
		$this->getPageCount(ShopUserModel::getCount($where_data));
		$data_list = ShopUserModel::pageList($this->getPageLimit(), $where_data);
		
		if($data_list)
		{
			$this->assign('data_list', $data_list);
			$id = $this->requestAbs('id');
			if($id == 0)
			{
				$id = $data_list[0]['id']; 
			}
			$data = ShopUserModel::fetchOne($id);
			$pro_list = ShopSaleModel::fetchAll(array('suid' => $id));		
			$this->assign('data', $data);
			$this->assign('pro_list', $pro_list);
		}
		$this->assign('primary', 'user');
	}

}