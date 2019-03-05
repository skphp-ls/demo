<?php
/**
 * InstanceMysql v2.2.1
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class InstanceMysql
{
	private $_conf = null;
	private $link  = array('master' => null, 'slave' => null);

	public function __construct()
	{
		$this->_conf = Sk::$Conf->getData('mysql');
		$this->open_slow_query = $this->_conf['open_slow_query'];
		$this->allow_time = $this->_conf['allow_time'];		
	}

	/**
	 * 选择数据连接
	 */
	public function getLink($first = null)
	{

		if($first == 's')
		{
			$host = $this->_conf['hostname']['master'];
			if($this->link['master'] == null) $this->link['master'] = $this->_getMysqlConnect($host);		
			$this->pdo = $this->link['master'];
		}else{
			if (count($this->_conf['hostname']['slave']) > 0) 
			{
				shuffle($this->_conf['hostname']['slave']);
				$host = $this->_conf['hostname']['slave'][0];
				if($this->link['slave'] == null) $this->link['slave'] = $this->_getMysqlConnect($host);
				$this->pdo = $this->link['slave'];
			}else{
				$host = $this->_conf['hostname']['master'];
				if($this->link['master'] == null) $this->link['master'] = $this->_getMysqlConnect($host);
				$this->pdo = $this->link['master'];
			}		
		}
		
	}

	/**
	 *  数据库连接
	 */
	private function _getMysqlConnect($host)
	{
		$host = explode(':', $host);
		isset($host[1]) || $host[1] = 3306;
		try{
			$op = array(
					PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \''. $this->_conf['charset'] .'\''
				);
			$link = new pdo('mysql:dbname=' . $this->_conf['database'] . ';host=' . $host[0] . ';port=' . $host[1], $this->_conf['username'], $this->_conf['password'], $op);
			$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$link->setAttribute(PDO::ATTR_CASE, PDO::CASE_LOWER);	
			//die('mysql:dbname=' . $this->_conf['database'] . ';host=' . $host[0] . ';port=' . $host[1] .'---' .  $this->_conf['username'] .'---' . $this->_conf['password']);	
			return $link;
		}catch(PDOException $e){
			throw new SkException($e->getMessage(), 80000);
		}
	}	
}