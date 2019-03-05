<?php
/**
 * web 认证授权
 */
class WxWebAuth
{

	// 获取授权URL array(wx, url)
	public static function getAuthUrl($arr_data)
	{
		$conf = WxConf::conf($arr_data['wx']);
		$arr_data['scope'] = isset($arr_data['scope']) ? $arr_data['scope'] : 'snsapi_base';
		$arr_data['state'] = isset($arr_data['state']) ? $arr_data['state'] : 1;
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?";
		$arg = array(
				'appid'			=>	$conf['AppId'],
				'redirect_uri'	=>	urlencode($arr_data['url']),
				'response_type' =>  'code',
				'scope'			=>	$arr_data['scope'],
				'state'			=>	$arr_data['state'] . '#wechat_redirect'
			);
		//print_r($arg);
		$url .= urldecode(http_build_query($arg));
		return $url;
	}

	// 使用CODE获取token 
	public static function getAccessToken($wxkey, $code = null)
	{
		$conf = WxConf::conf($wxkey);
		$code = isset($code) ? $code : $_GET['code'];
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
		$arg = array(
				'appid'			=>	$conf['AppId'],
				'secret'		=>	$conf['Secret'],
				'code' 			=>  $code,
				'grant_type'	=>	'authorization_code'
			);
		$url .= http_build_query($arg);
		$data = Curl::getRemoteData($url);
		//Sk::$Log->tempLog('wx_web_access_token', 'code:' . $code, true);
		//Sk::$Log->tempLog('wx_web_access_token', 'token:' .$data, true);
		$arr_data = json_decode($data, true);
		//print_r($arr_data);	
		if(isset($arr_data['errcode']))
		{
			throw new SkException('access_token出错，请关闭重试', '30001');
		}
		if(! isset($arr_data['access_token']) || ! isset($arr_data['openid']))
		{
			throw new SkException('微信openid授权失败，请重试', '30002');
		}
		return $arr_data;						
	}

	// 获取信息
	public static function getUserInfo($token, $openid)
	{
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
		$data = Curl::getRemoteData($url);
		$arr_data = json_decode($data, true);
		if(isset($arr_data['errcode']))
		{
			//Sk::$Log->tempLog('wx_web_access_token', 'getUserInfo:' . $data, true);	
			throw new SkException('读取userinfo出错，请关闭重试', '30003');
		}
		return $arr_data;		
	}
}