<?php
/**
 * Mysql配置 v2.0.3
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
return array(
		'hostname' => array(
			'master' 	=> '127.0.0.1:3306',
			// 设置从数据库
			'slave'  	=> array(),					
		),

		// 用户
		'username'				=>	'root',

		// 密码
		'password'				=>	'12345678',

		// 数据库
		'database' 				=>	'qchy',

		'charset'				=>	'UTF8',

		// 是否开启慢查询
		'open_slow_query'		=>	true,		
		
		// 设置允许时间秒
		'allow_time'			=>	0.05	
);
