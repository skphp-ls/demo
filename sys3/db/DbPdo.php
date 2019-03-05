<?php
/**
 * DbPdo v2.0.4
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
include('DefineLink.php');
include('InstanceMysql.php');
class DbPdo extends InstanceMysql implements DefineLink
{
	private $_sql = null, $_param = null;
	protected $open_slow_query = false, $allow_time = 0, $charset = false;
	public $pdo = null, $_stmt = null, $debug_sql = false;

	/**
	 * read + write
	 **/
	public function query($sql, $param = null, $result_type = PDO::FETCH_ASSOC)
	{
		$this->_sql = $sql;
		$this->_param = $param;
		$start_time = microtime(true);
		try{
			$this->getLink(strtolower($sql{0}));
			$this->_debugSql($sql, $param);
			//$this->charset && $this->pdo->query('SET NAMES ' . $this->charset);
			if (is_array($param)) 
			{
				$stmt = $this->pdo->prepare($sql);
				$stmt->execute($param);
			}else{
				$stmt = $this->pdo->query($sql);
			}
			$this->saveSlowQuery($start_time, $this->showSql());
			$this->_stmt = $stmt;
			return $this->_stmt->rowCount();
		}catch(PDOException $e){
			throw new SkException($e->getMessage(), 80001);
			//Sk::$Log->errorLog("\n" . date('Y-m-d H:i:s') .$err_msg);
		}
	}

	/**
	 * SQL
	 */
	public function showSql()
	{
		$param = $this->_param;
		$sql = $this->_sql;
		if (count($param) > 0) 
		{
			$rule = array();
			$sql = str_replace('?', "'?'", $sql);
			$rule = array_fill(0, count($param), '/\?/');
			$sql = preg_replace($rule, $param, $sql, 1);
		}
		//Sk::$Log->tempLog("sql.txt", 'sql::' . $sql ."\n", true);			
		return $sql;
	}

	/**
	 *  事务开启
	 */
	public function begin()
	{
		$this->getLink('i');
		$this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 0); //关闭自动提交
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
		$this->pdo->beginTransaction();
	}

	/**
	 * 回滚
	 */
	public function rollBack()
	{
		$this->pdo->rollBack();
	}	

	/**
	 * 提交
	 */
	public function commit()
	{
		$this->pdo->commit();
		//$this->pdo->setAttribute(PDO::ATTR_AUTOCOMMIT, 1); //自动提交
	}

	/**
	 * 返回受影响数目
	 **/
	public function affectedRows()
	{
		return $this->_stmt->rowCount();
	}

	/**
	 * (读)返回单条记录数据
	 **/
	public function fetchOne($result_type = PDO::FETCH_ASSOC)
	{
		return $this->_stmt->fetch($result_type);
	}

	/**
	 * (读)返回多条记录数据
	 **/
	public function fetchAll($result_type = PDO::FETCH_ASSOC)
	{
		return $this->_stmt->fetchAll($result_type);
	}

	/**
	 *  (读)返回单列记录数据
	 */
	public function fetchColumn()
	{
		return $this->_stmt->fetchColumn();
	}	


	/**
	 * (读)返回多条记录数据
	 **/
	public function getAll($sql, $param = null, $result_type = PDO::FETCH_ASSOC)
	{
		$this->query($sql, $param);
		return $this->_stmt->fetchAll($result_type);
	}

	/**
	 * (读)返回单条记录数据
	 **/
	public function getOne($sql, $param = null, $result_type = PDO::FETCH_ASSOC)
	{
		$this->query($sql, $param);
		return $this->_stmt->fetchOne($result_type);
	}

	/**
	 * 取得最后一次插入记录的ID值
	 **/
	public function insertId()
	{
		return $this->pdo->lastInsertId();
	}

	/**
	 * 数据库慢查询日志
	 */
	private function saveSlowQuery($start_time, $sql)
	{
		if (! $this->open_slow_query)
		{
			return false;
		}

		$querytime = microtime(true) - $start_time;

		if($querytime > $this->allow_time) 
		{
			$sql_log = 'query:' . date('Y-m-d H:i:s', $start_time) . "; 用时: $querytime\n		sql:{$sql}\n";
			Sk::$Log->sqlLog($sql_log); 
		}
	}

	/**
	 * SQL 语句捕捉
	 */
	private function _debugSql($sql, $param)
	{
		if ($this->debug_sql) 
		{
			echo 'sql:' . $sql;
			echo 'param:';
			print_r($param);
			echo $this->showSql();
			die;
		}
	}	
}
