<?php
/**
 * RecordSet v2.4.1
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-08-22
 */
class RecordSet
{
	private $_opt =	null;
	private $_table = null;	
	private $_ret_data = false;

	public $debugSql = false;
	public $rowCount = 0, $insertId = 0;

	/**
	 * 初始化
	 */
	public function __construct($table)
	{
		$this->_table = $table;
	}


	/**
	 * 参数初始化
	 */
	private function _initOpt()
	{
		$this->_opt = array(
			'field'		=>	null,
			'where'		=>	null,
			'orderby'	=>	null,
			'limit'		=>	null,
			'param'		=>	null,
			'charset'   =>  null
		);		
	}

	/**
	 * 设置字段
	 */
	public function select($field)
	{
		$this->_opt['field'] = $field;
	}	

	/**
	 * 设置条件
	 */
	public function where($where, $param = null)
	{
		$this->_opt['where'] = $where;
		is_array($param) && $this->_opt['param'] = $param;
	}

	/**
	 * 设置排序
	 */
	public function orderby($order)
	{
		$this->_opt['orderby'] = $order;
	}

	/**
	 * 设置limit
	 */
	public function limit($limit)
	{
		if(is_array($limit))
		{
			$this->_opt['limit'] = "$limit[0], $limit[1]";
		}else{
			$this->_opt['limit'] = $limit;
		}
	}

	/**
	 * 设置limit
	 */
	public function charset($charset)
	{
		$this->_opt['charset'] = $charset;
	}


	/**
	 * 设置table
	 */
	public function param($param)
	{
		$this->_opt['param'] = $param;
	}

	/**
	 * 字符串查询
	 * (select *),(limit 1,2),(orderby id desc) 
	 */
	public function find()
	{
		$args = func_get_args();
		$this->_findCondition($args);
		return $this->_query();
	}

	/**
	 *  查询单行
	 */
	public function findOne()
	{
		$args = func_get_args();
		$this->_findCondition($args);
		return $this->_query('findOne');
	}
	
	/**
	 *  查询单列
	 */	
	public function findColumn()
	{
		$args = func_get_args();
		$this->_findCondition($args);
		return $this->_query('findColumn');	
	}

	/**
	 *  find条件构造
	 */
	private function _findCondition($args)
	{
		$args_nums = count($args);
		if($args_nums == 0)
		{
			return false;
		}
		$arg = $args[$args_nums-1];
		if (is_array($arg)) 
		{
			$args_nums--;
			$this->_opt['param'] = $arg;
		}
		for ($i=0; $i < $args_nums; $i++) 
		{ 
			$key = strtolower(strstr($args[$i], ' ', true));
			$key = ($key == 'select') ? 'field' : $key;
			$this->_opt[$key] = ltrim(strstr($args[$i], ' '));
		}		
	}

	/**
	 *  获取数据
	 */
	public function getData($col = null)
	{
		return isset($col) ? $this->_ret_data[$col] : $this->_ret_data;
	}


	/**
	 * SQL 语句捕捉
	 */
	private function _debugSql()
	{
		if ($this->debugSql) 
		{
			Sk::$Db->debug_sql = true;
		}
	}

	/**
	 * 获取参数
	 */
	private function _getSqlOpt()
	{
		$opt = $this->_opt;
		$field = isset($opt['field']) ? $opt['field'] : '*';		
		$where = isset($opt['where']) ? "WHERE $opt[where]" : null;	
		$orderby = isset($opt['orderby']) ? "ORDER BY $opt[orderby]" : null;
		$limit = isset($opt['limit']) ? "LIMIT $opt[limit]" : null;	
		$charset =  isset($opt['charset']) ? $opt['charset'] : null;	
		return array(
				'field'		=>	 $field,
				'where' 	=>	 $where,
				'orderby' 	=>	 $orderby,
				'limit'		=>	 $limit,
				'param'		=>	 $opt['param'],
				'charset'	=>	 $charset
			);	
	}

	/**
	 * 查询
	 */
	private function _query($getDbMethod = 'fetchAll')
	{
		$opt = $this->_getSqlOpt();
		$table = $this->_table;
		$sql = rtrim("SELECT $opt[field] FROM $table $opt[where] $opt[orderby] $opt[limit]");
		$this->_debugSql();
		if(isset($opt['charset'])) Sk::$Db->query('SET NAMES ' . $opt['charset']);	
		$this->rowCount = Sk::$Db->query($sql, $opt['param']);
		if ($this->rowCount == 0) 
		{
			return false;
		}
		switch($getDbMethod)
		{
			case 'findOne' :
				$this->_ret_data = Sk::$Db->fetchOne();
				break;
			case 'findColumn' :
				$this->_ret_data = Sk::$Db->fetchColumn();
				break;				
			default :
				$this->_ret_data = Sk::$Db->fetchAll();
		}
		return true;
	}


	/**
	 * 新增
	 */
	public function insert($data)
	{
		$field = array_keys($data);
		$param = array_values($data);
		$sqlfield = implode(',', $field);
		$sqlval = str_repeat ( ",?" ,   count($field));
		$sqlval = substr($sqlval, 1);	
		$table = $this->_table;
		$sql = "INSERT INTO $table ($sqlfield) VALUES ($sqlval)";
		$this->_debugSql();
		$affected_rows = Sk::$Db->query($sql, $param);
		if($affected_rows > 0)
		{
			return Sk::$Db->insertId();
		}
		return false;
	}


	/**
	 * 修改
	 */
	public function update($data)
	{	
		$opt = $this->_getSqlOpt();
		if (! isset($opt['where']))
		{
			throw new SkException('未设置条件', 80010);
		}	
		$field = array_keys($data);
		$param = array_values($data);
		if (isset($opt['param'])) {
			$param = array_merge($param, $opt['param']);
		}
		$sqlfield = implode(' = ?,', $field) . ' = ?';	
		$table = $this->_table;
		$sql = "UPDATE $table SET $sqlfield $opt[where] $opt[limit]";
		$this->_debugSql();
		return Sk::$Db->query($sql, $param);	
	}

	/**
	 * 修改
	 */
	public function rcount($data)
	{	
		$opt = $this->_getSqlOpt();
		if (! isset($opt['where']))
		{
			throw new SkException('未设置条件', 80012);
		}
		$sqlfield = '';
		if(is_array($data))
		{
			foreach ($data as $key => $val) 
			{
				$sqlfield .= "$key = $key$val,";
			}			
		}else{
			$sqlfield = "$data = $data+1,";
		}
		$sqlfield = rtrim($sqlfield, ',');
		$table = $this->_table;			
		$sql = "UPDATE $table SET $sqlfield $opt[where] $opt[limit]";
		$this->_debugSql();
		return Sk::$Db->query($sql, $opt['param']);	
	}


	/**
	 * 删除
	 */
	public function delete()
	{		
		$opt = $this->_getSqlOpt();
		if (! isset($opt['where'])) 
		{
			throw new SkException('未设置条件', 80011);
		}	
		$table = $this->_table;	
		$sql = "DELETE FROM $table $opt[where] $opt[limit]";
		$this->_debugSql();
		return Sk::$Db->query($sql, $opt['param']);
	}
	
}