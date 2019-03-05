<?php
/**
 * BaseStatic v2.0.0
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2015-04-22
 */
class BaseStatic
{
	public static $ARG = null;
	public static function __callStatic($method, $param)
	{
		static $instance = array();
		$class_name = get_called_class();
		// echo $class_name . "<br/>";
		if (! isset($instance[$class_name])) 
		{
			$instance_class_name = $class_name . Enum::CLASS_NAME_SUFFIX;
			//var_dump(self::$ARG);
			if(isset(self::$ARG))
			{
				$instance[$class_name] = new $instance_class_name(self::$ARG);	
			}else{
				$instance[$class_name] = new $instance_class_name;
			}
		}
		// print_r(self::$instance);
		return call_user_func_array(array($instance[$class_name], $method), $param);	
	}
}