<?php
/**
 * 日志配置 v2.0.1
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
return array(

	// 追踪日志
	'trace'			=>	'/log/tracelog/' . date('Y-m-d') . '_tracelog.log',

	// 错误日志
	'error'				=>	'/log/errlog/' . date('Y-m-d') . '_errlog.log',

	// sql日志
	'query'			=>	'/log/slow_query/' . date('Y-m-d') . '_slow_query.log',
	
	// 临时日志
	'tmp'				=>	'/log/tmplog',
);