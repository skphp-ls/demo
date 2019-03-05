<?php
/**
 * 	站点目录
 */
if (! defined('APP_PATH')) 
{
	// /home/wwwroot/test
	define('APP_PATH',	dirname(__FILE__));
}
/**
 * 	站点根目录结构
 */
define('CURRENT_TIME', time());
define('CURRENT_DATE', date('Y-m-d H:i:s'));
define('CONF_PATH', APP_PATH . '/config');
define('MODULE_PATH', APP_PATH . '/module');
define('DATA_PATH', APP_PATH . '/data');

/**
 *  启动
 */
include(APP_PATH . '/../init3.php');

/**
 *  站点设置
 */
class AppConfig extends AppRun
{		
	// 设置配置文件信息
	protected function ConfigData()
	{
		return array(
			// 站点配置
			'app' 		=>			'conf_app.inc',

			// 全局参数配置
			'gbl'		=>			'conf_global.inc',

			// 路由
			'route'		=>			'conf_route.inc',

			// mysql配置
			'mysql' 	=>			'conf_mysql.inc',				

			// 模板
			'tpl'		=>			'conf_tpl.inc',
			
			// 日志
			'log'		=>			'conf_log.inc',

			// 上传
			'upload'	=>			'conf_upload.inc',	

			// 微信
			'wx'		=>			array(
					'default' => 'conf_wx.inc',
					'master'  => 'conf_wx_master.inc'
 				),	
			
			// memcache
			'memcache'	=>			array(
					'default' => 'conf_memcache.inc'
				),

			'location'	=>			'conf_location.inc'
		);
	}
}
// 启动
$app = new AppConfig;
try{
	$app->run();
}catch(SkException $e){
	if($app->controller_type == Enum::CONTROLLER_AJAX_TYPE_SUFFIX || $app->controller_type == Enum::CONTROLLER_API_TYPE_SUFFIX)
	{
		encode_json(array('ret' => false, 'msg' => $e->getMessage()));
	}
	die($e->getMessage());
}