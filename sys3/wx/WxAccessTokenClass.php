<?php
/**
 * accesstoken
 */
class WxAccessToken
{
	// 获取token 
	public static function getAccessToken($ckey)
	{
		$conf = AppConf::Conf($ckey);
		$arr_data = self::fileToken($conf['AppId']);
		if($arr_data)
		{
			if($arr_data['expires_time'] > time())
			{
				//print_r($arr_data);
				return $arr_data['access_token'];
			}			
		}
		$url = 'https://api.weixin.qq.com/cgi-bin/token?';
		$arg = array(
				'appid'			=>	$conf['AppId'],
				'secret'		=>	$conf['Secret'],
				'grant_type'	=>	'client_credential'
			);
		$url .= http_build_query($arg);
		$data = Curl::getRemoteData($url);
		$arr_data = json_decode($data, true);
		if(isset($arr_data['errcode']))
		{
			return false;
		}
		$arr_data['expires_time']	= time() + $arr_data['expires_in'] - $conf['ExpireSec'];
		self::fileToken($conf['AppId'], $arr_data);
		return $arr_data['access_token'];			
	}


	//JS-SDK使用权限签名算法 jsapi_ticket
	public static function getJsapiTicket($ckey)
	{
		$ticket_name = 'ticket.json';
		$accesstoken = self::getAccessToken($ckey);
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accesstoken";
		$arr_data = self::fileToken($ticket_name);
		if($arr_data)
		{
			if($arr_data['expires_time'] > time())
			{
				//print_r($arr_data);
				return $arr_data['ticket'];
			}			
		}
		$data = Curl::getRemoteData($url);
		$arr_data = json_decode($data, true);
		if($arr_data['errcode'] > 0)
		{
			return false;
		}
		$arr_data['expires_time']	= time() + $arr_data['expires_in'] - $conf['ExpireSec'];
		self::fileToken($ticket_name, $arr_data);
		return $arr_data['ticket'];		
	}

	// 文件token获取
	public static function fileToken($fname, $data = null)
	{
		$path = AppConf::Conf($ckey, 'TokenWxDir');
		make_full_path($path);
		if (isset($data)) 
		{
			return @file_put_contents($path . '/' . $fname, serialize($data));	
		}else{
			return unserialize(@file_get_contents($path . '/' . $fname));	
		}
	}	
}