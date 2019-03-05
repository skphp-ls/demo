<?php
/**
 * BaseRsModel v3.0.0
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2015-04-22
 */
class BaseRsModel
{
	private $order_sort				=	' desc';
	protected $_table 				= 	null;

	/**
	 * 初始化
	 */
	public function __construct()
	{	
		if(! Sk::$Db)
		{
			$sk = new Sk;
			$sk->Db();
		}	
		$this->_table = strtolower(uc_lower(strstr(get_class($this), Enum::MODEL_NAME_SUFFIX, true)));	
		if (! isset($this->primary_key)) 
		{
			throw new SkException('主键未设置', 80020);
		}
	}

	/**
	 * 转换SQL
	 */
	public function sqlParam($where_data)
	{
		if (count($where_data)) 
		{
			$sqlfield = null;
			$param = array(); 
			if($where_data[0] == 'union')
			{
				unset($where_data[0]);
				if(count($where_data))
				{
					//print_r($where_data);
					foreach ($where_data as $val) 
					{
						if(isset($val[0]))
						{
							$sqlfield .= "$val[0] and ";
							unset($val[0]);
							$param = array_merge($param, $val);						
						}else{
							$sqlfield .= key($val) . ' = ? and ';
							$param[] = current($val);
						}
					}					
				}else{
					return array(
						'field'	=>	true,
						'param'	=>	false
					);					
				}
			}else{
				if(isset($where_data[0]))
				{
					$sqlfield = "$where_data[0] and ";
					unset($where_data[0]);
					$param = array_merge($param, $where_data);
				}else{
					foreach ($where_data as $key => $val) 
					{
						$sqlfield .= "$key = ? and ";
						$param[] = $val;
					}	
				}
			}
			//cho $sqlfield; 
			if (isset($sqlfield)) 
			{
				//$sqlfield = substr($sqlfield, 0, strlen($sqlfield) - 4);
				$sqlfield = rtrim($sqlfield, 'and ');
			}
			return array(
					'field'	=>	$sqlfield,
					'param'	=>	$param
				);			
		}
		return array(
			'field'	=>	true,
			'param'	=>	false
		);		
	}	

	/**
	 * 统计
	 */
	public function getCount($where_data = null, $debug = false)
	{
		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(is_array($where_data))
		{
			$sp = $this->sqlParam($where_data);
			$rs->find('select ' . $this->primary_key, "where $sp[field]", $sp['param']);
			return $rs->rowCount;		
		}
		$rs->find('select ' . $this->primary_key);
		return $rs->rowCount;
	}

	/**
	 *  单列
	 */
	public function fetchItem($where_data, $field = null, $debug = false)
	{
		$field = isset($field) ? $field : $this->primary_key;
		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(is_array($where_data))
		{
			$sp = $this->sqlParam($where_data);
			$rs->findColumn('select ' . $field, "where $sp[field]", $sp['param']);
			return $rs->getData();	
		}
		$rs->findColumn('select ' . $field, 'where ' . $this->primary_key . '=?', array($where_data));
		return $rs->getData();	
	}

	/**
	 *  单行
	 */
	public function fetchOne($where_data, $field = '*', $debug = false)
	{
		$field = isset($field) ? $field : $this->primary_key;
		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(is_array($where_data))
		{
			$sp = $this->sqlParam($where_data);
			$rs->findOne('select ' . $field, "where $sp[field]", $sp['param']);
			return $rs->getData();	
		}
		$rs->findOne('select ' . $field, 'where ' . $this->primary_key . '=?', array($where_data));
		return $rs->getData();
	}	


	/**
	 *  全部数据
	 */
	public function fetchAll($where_data = null, $field = '*', $orderby = null, $debug = false)
	{
		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(is_array($where_data))
		{
			$sp = $this->sqlParam($where_data);
			$rs->where($sp['field'], $sp['param']);
		}
		if(isset($field))
		{
			$rs->select($field);
		}		
		if(isset($orderby))
		{	
			$rs->orderby($orderby);
		}else{
			if (isset($this->orderby_key)) 
			{
				$rs->orderby($this->orderby_key);
			}else{
				$rs->orderby($this->primary_key . $this->order_sort);
			}
		}
		$rs->find();
		return $rs->getData();	
	}


	/**
	 *  排序数据
	 */
	public function orderOne($where_data, $orderby, $field = '*', $debug = false)
	{

		$field = isset($field) ? $field : $this->primary_key;
		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(is_array($where_data))
		{
			$sp = $this->sqlParam($where_data);
			$rs->findOne('select ' . $field, "orderby $orderby", "where $sp[field]", $sp['param']);
			return $rs->getData();	
		}
		$rs->findOne('select ' . $field, "orderby $orderby", 'where ' . $this->primary_key . '=?', array($where_data));
		return $rs->getData();
	}

	
	/*
	 *  分页数据
	 */
	public function pageList($limit, $where_data = null, $field = '*', $orderby = null, $debug = false)
	{
		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(is_array($where_data))
		{
			$sp = $this->sqlParam($where_data);
			$rs->where($sp['field'], $sp['param']);
		}		
		if(isset($field))
		{
			$rs->select($field);
		}
		if(isset($orderby))
		{	
			$rs->orderby($orderby);
		}else{
			if (isset($this->orderby_key)) 
			{
				$rs->orderby($this->orderby_key);
			}else{
				$rs->orderby($this->primary_key . $this->order_sort);
			}
		}
		$rs->limit($limit);	
		$rs->find();
		return $rs->getData();
	}


	/**
	 *  修改
	 */
	public function updateData($where_data, $data, $debug = false)
	{
		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(is_array($where_data))
		{
			$sp = $this->sqlParam($where_data);
			$rs->where($sp['field'], $sp['param']);
		}else{
			$rs->where($this->primary_key . '=?', array($where_data));
		}
		return $rs->update($data);
	}

	/**
	 *  修改
	 */
	public function countData($where_data, $data, $debug = false)
	{
		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(is_array($where_data))
		{
			$sp = $this->sqlParam($where_data);
			$rs->where($sp['field'], $sp['param']);
		}else{
			$rs->where($this->primary_key . '=?', array($where_data));
		}	
		return $rs->rcount($data);
	}


	/**
	 *  删除
	 */
	public function delData($where_data, $debug = false)
	{
		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(is_array($where_data))
		{
			$sp = $this->sqlParam($where_data);
			$rs->where($sp['field'], $sp['param']);
		}else{
			$rs->where($this->primary_key . '=?', array($where_data));
		}	
		return $rs->delete();
	}	


	/**
	 *  新增
	 */
	public function insertData($data, $where_data = null, $debug = false)
	{

		$rs = new RecordSet($this->_table);
		$rs->debugSql = $debug;
		if(isset($where_data))
		{
			if(is_array($where_data))
			{
				$sp = $this->sqlParam($where_data);
				$rs->where($sp['field'], $sp['param']);
			}else{
				$rs->where($this->primary_key . '=?', array($where_data));
			}		
			$rs->findOne('select ' . $this->primary_key);
			if ($rs->rowCount <= 0) 
			{
				return $rs->insert($data);
			}
			return false;	
		}
		return $rs->insert($data);
	}


	/**
	 *  批量新增
	 */	
	public function insertMuchData($data, $field = null, $debug = false)
	{
		// 设置字段
		if (! isset($field)) 
		{
			$field = array_keys($data[0]);
		}
		$sqlfield = implode(',', $field);
		$sqlval = str_repeat(",?", count($field));
		$sqlval = substr($sqlval, 1);
		$sqlvals = '';
		$param = array();
		foreach ($data as $val) 
		{
			$sqlvals .= ",($sqlval)";
			$param = array_merge($param, array_values($val));
		}
		$sqlvals = substr($sqlvals, 1);
		$table = strtolower($this->_table);
		$sql = "INSERT INTO $table ($sqlfield) VALUES $sqlvals";		
		Sk::$Db->debug_sql = $debug;	
		Sk::$Db->query($sql, $param);
		return Sk::$Db->affectedRows();
	}

}