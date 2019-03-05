<?php
/**
 * 关闭错误报告
 */
//error_reporting(0);
/*
 * 同步时间
 */
date_default_timezone_set('Asia/Shanghai');

/*
 * 记录框架开始时间
 */
define('PAGE_START_TIME', microtime(TRUE));

/*
 * 设置编码UTF-8
 */
header('Content-Type: text/html; charset=UTF-8');

/*
 *	时间戳
 */
define('CURRENT_TIME', time());
/*
 * 日期
 */
define('CURRENT_DATE', date('Y-m-d H:i:s'));

/**
 * 	根目录
 */
define('ROOT_PATH',	 dirname(__FILE__));

/**
 * 	系统目录
 */
define('SYSTEM_PATH',	ROOT_PATH . '/sys3');

/**
 *  系统类库
 */
define('SYSTEM_LIBS',   SYSTEM_PATH . '/libs');

/**
 *  数据库
 */
define('SYSTEM_DB',   SYSTEM_PATH . '/db');

/**
 *  系统函数库
 */
define('SYSTEM_CORE',   SYSTEM_PATH . '/core');

/**
 *  微信函数库
 */
define('SYSTEM_WX',   SYSTEM_PATH . '/wx');

/**
 * 	框架文件载入
 */
include(SYSTEM_PATH . '/Sk.php');

/**
 * 	多站点SERVER_NAME公共文件
 */
$server_name = strtolower(trim(stristr(str_replace(array('.com', '.cn'), '', $_SERVER['SERVER_NAME']), '.'), '.'));
if(is_file(SYSTEM_PATH . "/{$server_name}.php"))
{
	include(SYSTEM_PATH . "/{$server_name}.php");
}
