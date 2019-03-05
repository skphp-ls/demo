<?php
class ProDepotModel  extends BaseStatic{}
class ProDepotModelClass extends BaseRsModel
{
	// 设置主健
	public $primary_key			=  	'id';

	public function __construct($arg)
	{
		parent::__construct();
		$this->shopid = $arg['shopid'];
	}

	public function getRows($where_data)
	{
		$sid = $this->shopid;
		$where = $this->sqlParam($where_data);
		$sql = "select p.id id from pro_info p left join pro_depot d on sid=$sid and p.id=d.pro_id where $where[field]";
		Sk::$Db->query($sql, $where['param']);
		return Sk::$Db->affectedRows();
	}

	// left join 获取列表
	public function getList($limit, $where_data)
	{
		$sid = $this->shopid;
		$where = $this->sqlParam($where_data);
		$sql = "select p.*,pro_depot from pro_info p left join pro_depot d on sid=$sid and p.id=d.pro_id where $where[field] limit $limit[0],$limit[1]";
		//Sk::$Db->debug_sql = 1;
		Sk::$Db->query($sql, $where['param']);
		return Sk::$Db->fetchAll();
	}

	public function getRRows($where_data)
	{
		$sid = $this->shopid;
		$where = $this->sqlParam($where_data);
		$sql = "select p.id id from pro_info p right join pro_depot d on sid=$sid and p.id=d.pro_id and pro_depot > 0 where $where[field]";
		Sk::$Db->query($sql, $where['param']);
		return Sk::$Db->affectedRows();
	}

	// right join 获取列表
	public function getRList($limit, $where_data)
	{
		$sid = $this->shopid;
		$where = $this->sqlParam($where_data);
		$sql = "select p.*,pro_depot from pro_info p right join pro_depot d on sid=$sid and p.id=d.pro_id and pro_depot > 0 where $where[field] limit $limit[0],$limit[1]";
		//echo $sql;
		// Sk::$Db->debug_sql = 1;
		Sk::$Db->query($sql, $where['param']);
		return Sk::$Db->fetchAll();
	}

	// 库存增加
	public function depotCount($where, $param)
	{
		$sid = $this->shopid;
		$sql = "update pro_depot set pro_depot=pro_depot+pro_count,pro_count=0 where sid=$sid and $where";
		//Sk::$Db->debug_sql = 1;
		Sk::$Db->query($sql, $param);
		return Sk::$Db->affectedRows();		
	}
}