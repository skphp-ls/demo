<?php
/**
 * LogClass v2.2.0
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class LogClass 
{

	private $_conf 				= 		null;

	/**
	 * 初始化路径
	 */
	public function __construct()
	{	
		$this->_conf = Sk::$Conf->getData('log');
	}

	/**
	 * 写日志
	 */
	public function saveLog($log_file, $content, $append = false)
	{
		$log_file = DATA_PATH . $log_file;
		make_full_path(dirname($log_file));
		if($append){
			$result = file_put_contents($log_file, $content . "\n", FILE_APPEND);	
		}else{
			$result = file_put_contents($log_file, $content);
		}
	}

	/**
	 * 临时日志
	 */
	public function tempLog($log_file, $content, $append = false)
	{
		$log_file = $this->_conf['tmp'] . '/' . $log_file;
		$this->saveLog($log_file, $content, $append);	
	}

	/**
	 * 错误日志
	 */
	public function errorLog($content, $append = true)
	{
		$log_file = $this->_conf['error'];
		$this->saveLog($log_file, $content, $append);	
	}

	/**
	 * 跟踪日志
	 */
	public function traceLog($content, $append = true)
	{
		$log_file = $this->_conf['trace'];
		$this->saveLog($log_file, $content, $append);			
	}

	/**
	 * SQL日志
	 */
	public function sqlLog($content, $append = true)
	{
		$log_file = $this->_conf['query'];
		$this->saveLog($log_file, $content, $append);			
	}	
}