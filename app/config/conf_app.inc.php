<?php
/**
 * 站点配置 v2.0.4
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
/**
 * 自动路由
 */ 
$config['AUTO_ROUTE']				=		true;

/**
 * 路由层级
 */ 
$config['ROUTE_LEVEL']				=		2;

/**
 * 默认模块名称
 */		
$config['DEFAULT_MODULE']			=		'web';	

/**
 * 默认控制器名称
 */		
$config['DEFAULT_CONTROLLER']		=		'index';		

/**
 * 默认控制器方法名称
 */		
$config['DEFAULT_METHOD']			=		'index';

/**
 * 强制规则填写
 */		
$config['CONTROLLER_RULE_ON']		=		true;


/**
 * 禁止全局环境参数变量取值
 */		
$config['SERVER_REQUEST_MODE']		=		false;

/**
 * 跟踪
 */		
$config['WX_TRACE_CATCH']			=		true;

/**
 * 表单允许类型
 */
$config['FORM_ALLOW_TYPE'] 			= 		array(
												'multipart/form-data', 
												'application/x-www-form-urlencoded');

/**
* 验证类启用
*/
$config['CHECK_RULE_CLASS']          =       'Validate';


/**
* 自动载入全局类
*/
$config['AUTO_MOD_CLASS']          =       true;


/**
* 自动载模块类
*/
$config['AUTO_CTL_CLASS']          =       true;

return $config;