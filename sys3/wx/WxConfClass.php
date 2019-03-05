<?php
/**
 *  基本配置
 */
class WxConf
{
	private static $conf 				=			null;
	public static $arr_signature		=			array();		

	// 获取APPID 配置
	public static function conf($item = null, $key = null)
	{
		if(! isset(self::$conf))
		{
			self::$conf = Sk::$Conf->getData($item);
		}
		if(isset($key))
		{
			return self::$conf[$key];
		}
		return self::$conf;
	}

	// 微信URL验证
	public static function valid()
	{
		$arr_data = self::$arr_signature;
		$echoStr = $arr_data["echostr"];
		Sk::$Conf->WX_TRACE_CATCH && Sk::$Log->traceLog('echoStr:' . $echoStr);
		if(isset($echoStr{0}))
		{
			$token = self::conf('TOKEN');
			$arr_signature = array($token, $arr_data['timestamp'], $arr_data['nonce']);
			// use SORT_STRING rule
			sort($arr_signature, SORT_STRING);
			$signature = implode($arr_signature);
			$signature = sha1($signature);
			Sk::$Conf->WX_TRACE_CATCH && Sk::$Log->traceLog("signature:$signature == $arr_data[signature]");
			if( $signature == $arr_data['signature'] ){
				exit($echoStr);
			}
			exit('error');       	
		}		
	}

	// 请求
	public static function getData($url, $data)
	{
		$access_token = WxAccessToken::getAccessToken();
		$url .= $access_token;
		if (isset($data)) 
		{
			$data = Curl::getRemoteData($url, $data);
		}else{
			$data = Curl::getRemoteData($url);
		}
		return json_decode($data, true);
	}

	// 结果
	public static function getResult($url, $data = null, & $errmsg = null)
	{
		$arr_data = self::getData($url, $data);
		if(isset($arr_data['errcode']))
		{
			if($arr_data['errcode'] != '0')
			{
				$errmsg = json_encode($arr_data);
				return false;
			}			
		}
		return $arr_data;
	}

	// 重复消息
	public static function ckRepeat($msg_id)
	{
		$dequee = WxAccessToken::fileToken('msgid_dequee');
		if($dequee == false)
		{
			$dequee[$msg_id] = time() + 10;
		}else{
			// 清空队列
			foreach ($dequee as $key => $val) 
			{
				if($val  < time())
				{
					unset($dequee[$key]);
				}
			}
			// 排重
			if(isset($dequee[$msg_id])) exit;
		}
		WxAccessToken::fileToken('msgid_dequee', $dequee);
	}	
}