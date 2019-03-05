<?php
/**
 * ConfClass v2.2.0
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class ConfClass
{
	private $_conf_data_keys 		= 	null;
	
	/**
	 * 加载配置文件
	 */
	private function loadConfing($file)
	{ 
		$file_path = CONF_PATH . '/' . $file . '.php';
		if(! is_file($file_path))
		{
			throw new SkException($file_path . '配置文件不存在', 10000);
		}		
		return include($file_path);
	}


	/**
	 * 载入配置文件表
	 */
	public function __construct($data, $key)
	{
		$this->_conf_data_keys = $data;
		$propertys = $this->getData($key);
		foreach ($propertys as $k => $v) 
		{
			$this->$k = $v;
		}
	}

	/**
	 * 载入配置文件信息
	 */
	public function getData($conf, $key = null)
	{
		//print_r($conf);
		if(is_array($conf))
		{
			$conf_key = current($conf);
			$conf_item = end($conf);
		}else{
			$conf_key = $conf;
		}
		if (isset($this->_conf_data_keys[$conf_key])) 
		{
			$conf_file = $this->_conf_data_keys[$conf_key];
			//print_r($conf_file);
			if(is_array($conf_file))
			{
				$conf_item = isset($conf_item) ? $conf_item : 'default';
				$conf_name = $conf_file[$conf_item];
			}else{
				$conf_name = $conf_file;
			}
			//echo $conf_name;
			$data = $this->loadConfing($conf_name);
			if(isset($key)){
				return $data[$key];
			}
			return $data;
		}
	}
}