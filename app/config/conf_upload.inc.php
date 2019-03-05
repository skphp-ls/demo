<?php
return array(
	// 上传设置
	'up_size'			=>		1024,	
	'up_path'			=>		DATA_PATH,	
	'root_up_path'		=>		'/' . basename(DATA_PATH),
	'up_dir'			=>		'/upfile',
	'isday'				=>		false,
	'up_allow_mime'		=> 		'|gif|jpeg|jpg|png|x-png|',
	'up_allow_bin'		=>		array('ffd8ffe0', '89504e47', '47494638')	 // jpg,png,gif
);