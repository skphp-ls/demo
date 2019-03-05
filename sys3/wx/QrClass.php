<?php
class Qr
{
	public static function getQrPath($val)
	{
		$dir = AppIdConf::conf('QrPathDir');
		$path = AppIdConf::conf('QrPath');
		$file = '/' . $dir . '/' . $val . '.jpg';
		if(is_file($path . $file))
		{
			return DATA_PATH . $file;
		}
		return false;
	}

	// 二维码 
	// 'QR_SCENE,QR_STR_SCENE,QR_LIMIT_SCENE,QR_LIMIT_STR_SCENE'
	public static function makeQrcode($val, $type = 'QR_SCENE')
	{
		$json_data = '';
		switch ($type) 
		{
			case 'QR_SCENE':
				$json_data = '{"expire_seconds":2592000,"action_name":"QR_SCENE","action_info":{"scene":{"scene_id":'.$val.'}}}';
				break;
			case 'QR_STR_SCENE':
				$json_data = '{"expire_seconds":2592000,"action_name":"QR_STR_SCENE","action_info":{"scene":{"scene_str":"'.$val.'"}}}';
				break;
			case 'QR_LIMIT_SCENE':
				$json_data = '{"action_name":"QR_LIMIT_SCENE","action_info":{"scene":{"scene_id":'.$val.'}}}';
				break;		
			case 'QR_LIMIT_STR_SCENE':
				$json_data = '{"action_name":"QR_LIMIT_STR_SCENE","action_info":{"scene":{"scene_str":'.$val.'}}}';
				break;
		}
		//Sk::$Log->tempLog("wx_scan.txt", 'qrcode::' . $json_data ."\n", true);
		$url = 'https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=';
		$err = null;
		$result =  AppIdConf::getResult($url, $json_data, $err);
		//Sk::$Log->tempLog("wx_scan.txt", 'qrcode::' . json_encode($result) ."\n", true);
		if($result)
		{
			$ticket = urlencode($result['ticket']);
			$file = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket";
			$qr = file_get_contents($file);
			$path = AppIdConf::conf('QrPath');
			$dir = AppIdConf::conf('QrPathDir');
			make_full_path($path);		
			$file_name = $dir . '/' . $val . '.jpg';
			$file_path = $path . '/' . $file_name;
			@file_put_contents($file_path, $qr);
			return DATA_PATH . '/' . $file_name;
		}
		return false;
	}	


	/**
		// 保存到本地
		if(isset($fname))
		{	
			//header("content-type:image/jpeg\r\n");
			//echo $qr;
			header ( 'Content-Description: File Transfer' );
			header ( 'Content-Type: application/octet-stream' );
			header ( 'Content-Disposition: attachment; filename=' . $fname);
			header ( 'Content-Transfer-Encoding: binary' );
			header ( 'Expires: 0' );
			header ( 'Cache-Control: must-revalidate' );
			header ( 'Pragma: public' );
			header ( 'Content-Length: '  .  filesize ( $file ));
			ob_clean ();
			flush ();
			readfile ( $file );
			exit;
		}
	**/

	// 短网址
	public static function makeShortUrl($url)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/shorturl?access_token=";
		$data = '{"action":"long2short","long_url":"$url"}';
		return AppIdConf::getResult($url, $data);
	}
}