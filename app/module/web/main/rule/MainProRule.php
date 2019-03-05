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


$rule['order_mark'] = array(
	'json'	=>	array(
			'fail'	=>array(
						'ret'	=>		false,
						'msg' 	=>		'下单失败'
			),
			'ok'	=>array(
						'ret'	=>		true,
						'msg' 	=>		'下单成功'
			)
		)
);
return $rule;