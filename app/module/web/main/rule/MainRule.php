<?php
$rule['global'] = array(
	'json'	=>	array(
			'fail'	=>array(
						'ret'	=>		false,
						'msg' 	=>		'操作失败'
			),
			'ok'	=>array(
						'ret'	=>		true,
						'msg' 	=>		'操作成功'
			)
		)
);
return $rule;