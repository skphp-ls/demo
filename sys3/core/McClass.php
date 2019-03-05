<?php
/**
 * MemcacheClass v2.0.1
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class Mc extends BaseStatic{}
class McClass extends Memcache
{
    public function __construct()
    {
		$conf = Sk::$Conf->getData('memcache');
		$this->connect($conf['host'], $conf['port']);
	}

    public function __destruct()  
    {  
        //Sk::$Conf->WX_TRACE_CATCH && Sk::$Log->traceLog("mclink: true");
        $this->close();
    } 	
}
