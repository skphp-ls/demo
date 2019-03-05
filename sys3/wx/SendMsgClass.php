<?php
/**
 * 发送消息
 */
class SendMsg
{
	private static function send($data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=';
		return AppIdConf::getResult($url, $data);		
	}

	// 发送文本消息
	public static function text($openid, $content)
	{
		$json_msg = '{"touser":"' . $openid . '","msgtype":"text","text":{"content":"' . $content . '"}}';
		//Sk::$Log->tempLog("wx_msg.txt", 'msg_xml:' . $json_msg. "\n", true);
		$result = self::send($json_msg);
		//Sk::$Log->tempLog("wx_msg.txt", 'result:' . $result. "\n", true);
		return $result;
	} 

	// 发送图片消息
	public static function image($openid, $media_id)
	{
		$json_msg = '{"touser":"' . $openid . '","msgtype":"image","image":{"media_id":"' . $media_id . '"}}';
		//save_log("json_msg.txt", 'msg_xml:' . $json_msg. "\n");
		return self::send($json_msg);
	} 

	// 发送语音消息
	public static function voice($openid, $media_id)
	{
		$json_msg = '{"touser":"' . $openid . '","msgtype":"voice","voice":{"media_id":"' . $media_id . '"}}';
		//save_log("json_msg.txt", 'msg_xml:' . $json_msg. "\n");
		return self::send($json_msg);
	}

	// 发送视频消息
	public static function video($openid, $arr_data)
	{
		$json_msg = '{"touser":"' . $openid . '","msgtype":"video","video":{"media_id":"' . $arr_data['media_id'] . '","thumb_media_id":"' . $arr_data['media_id'] . '","title":"' . $arr_data['title'] . '", "description":"' . $arr_data['des'] . '"}}';
		//save_log("json_msg.txt", 'msg_xml:' . $json_msg. "\n");
		return self::send($json_msg);
	}	

	// 发送音乐消息
	public static function music($openid, $arr_data)
	{
		$json_msg = '{"touser":"' . $openid . '","msgtype":"music","music":{"title":"' . $arr_data['title'] . '", "description":"' . $arr_data['des'] . '","musicurl":"' . $arr_data['url'] . '","hqmusicurl":"' . $arr_data['hqurl'] . '", "thumb_media_id":"' . $arr_data['thumb_media_id'] . '"}}';
		//save_log("json_msg.txt", 'msg_xml:' . $json_msg. "\n");
		return self::send($json_msg);
	}	

	// 发送图文消息
	public static function news($openid, $arr_data)
	{
		foreach ($arr_data['item'] as $val)
		{ 		
			$articles .= ',{"title":"' . $val['title'] . '","description":"' . $val['des'] . '","url":"' . $val['url'] . '","picurl":"' . $val['picurl'] . '"}';
		}
		$articles = substr($articles, 1);
		$json_msg = '{"touser":"' . $openid . '","msgtype":"news","news":{"articles":[' . $articles . ']}}';
		//save_log("json_msg.txt", 'msg_xml:' . $json_msg. "\n");
		return self::send($json_msg);
	}
}