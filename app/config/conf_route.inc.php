<?php
/**
 * 路由 v2.0.3
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
$routes = array();

/**
 * 默认网站路由
 */

/*
$routes['*']['/']									=			array('web', 'product', 'shop');
$routes['*']['/default']							=			array('a', 'b');

$routes['*']['/default/index']						=			array('aa', 'bb');

$routes['*']['/default/ccc']						=			array('aaa', 'bbb');

$routes['*']['/admin/list/:page']					=			array('ad', 'index');

$routes['*']['/web/admin/test']						=			array('hh', 'gg');
*/
$routes['*']['/admin/list/:action/:page']			=			array('default', 'index');

/**
 * 路由结束
 */
return $routes;