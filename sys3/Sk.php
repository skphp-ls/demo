<?php
/**
 * skytrainPHP v1.1.0
 * 
 * @auth ls qq:56160681 2014-11-25
 */
/**
 *  错误类载入
 */
include(SYSTEM_LIBS . '/SkException.php');

/**
 *  枚举常量
 */
include(SYSTEM_PATH . '/Enum.php');

/**
 *  载入全局函数
 */
include(SYSTEM_PATH . '/global.php');

/**
 *  站点载入
 */
include(SYSTEM_PATH . '/App.php');

/**
 *  静态文件调用
 */
include(SYSTEM_LIBS . '/BaseStatic.php');	

/**
 *  模型类文件载入
 */
include(SYSTEM_LIBS . '/BaseRsModel.php');
/**
 *  SK全局类
 */
class Sk
{
	public static $Path, $Conf, $Log;
	public static $Db, $RecordSet;

	// 配置
	public function Conf($path, $key)
	{
		if(! self::$Conf)
		{
			$classname =  'ConfClass';
			include SYSTEM_LIBS . "/$classname.php";
			self::$Conf = new $classname($path, $key);   			
		}
	}

	// 日志
	public function Log()
	{
		if(! self::$Log)
		{
			$classname =  'LogClass';
			include SYSTEM_LIBS . "/$classname.php";
			self::$Log = new $classname();   			
		}
	}

	// 数据库
	public function Db()
	{
		if(! self::$Db)
		{
			$classname =  'DbPdo';
			include(SYSTEM_LIBS . '/RecordSet.php');
			include(SYSTEM_DB . "/$classname.php");
			self::$Db = new $classname();   			
		}
	}

	// 注册
	public static function AutoLoad($class_name)
	{
		$class_name_suffix = Enum::CLASS_NAME_SUFFIX . ',';
		if (stripos($class_name. ',', $class_name_suffix) !== false) {
			$class_name = str_ireplace($class_name_suffix, '', $class_name . ',');
		}
		$str_name = strtolower(uc_lower($class_name));
		$prefix = strstr($str_name, '_', true);
		$suffix = strrchr($str_name, '_');
		if($suffix)
		{
			$suffix = ltrim($suffix, '_');
		}
		switch ($suffix) {
			case strtolower(Enum::CONTROLLER_NAME_SUFFIX):
				$file_path = Sk::$Path->module['controller'] . '/'. $class_name . '.php';
				if(! is_file($file_path))
				{
					//echo ;
					throw new SkException($file_path . '扩展控制器不存在', 10003);
				}
				include_once($file_path);				
				break;
			case strtolower(Enum::MODEL_NAME_SUFFIX):
				$dir = preg_replace('/(^[A-Z][a-z]+)(\w*)/', '$1', $class_name);
				$file_path = Sk::$Path->module['model'] . '/' . strtolower($dir) . '/' . $class_name . Enum::CLASS_NAME_SUFFIX . '.php';
				// echo $file_path;
				if(! is_file($file_path))
				{
					throw new SkException($file_path . '数据模型不存在', 10004);
				}           
				include_once($file_path);			
				break;
			default:
				$class_name = $class_name . Enum::CLASS_NAME_SUFFIX;
				// 微信类库
				if($prefix == strtolower(Enum::WX_CLASS_PREFIX))
				{
					$file_path = SYSTEM_WX . '/'. $class_name . '.php';
					if(is_file($file_path))
					{
						include_once($file_path);
						break;
					}
				}else{
					// 系统类库
					$file_path = SYSTEM_CORE . '/'. $class_name . '.php';
					if(is_file($file_path))
					{
						include_once($file_path);
						break;
					}				
				}
				include_once($class_name . '.php');
		}

	}
}