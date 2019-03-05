<?php
/**
 * 响应消息
 */
class ResponseMsg
{
	/**
	 * 消息模板
	 */
	private static function tpl($arr_data)
	{
		$arr_data['to_time'] = isset($arr_data['to_time']) ? $arr_data['to_time'] : time();
		$xml =  
		"<xml>
			<ToUserName><![CDATA[{$arr_data[openid]}]]></ToUserName>
			<FromUserName><![CDATA[{$arr_data[wx_id]}]]></FromUserName>
			<CreateTime>{$arr_data[to_time]}</CreateTime>
			<MsgType><![CDATA[{$arr_data[msg_type]}]]></MsgType>
			{$arr_data[content]}
		</xml>";
		return WxCrypt::encryptMsg($xml);
	}

	public static function getTypeMsg($arr_msg, $ktype, $data)
	{
		$msg = null;
		switch ($ktype) 
		{
			case 'text':
				$msg = self::text(
					array(
						'openid'	=>	$arr_msg['FromUserName'],
						'wx_id'		=>	$arr_msg['ToUserName'],
						'content'	=>	$data
					)
				);
				break;

			case 'image':					
				$msg = self::image(
					array(
						'openid'	=>	$arr_msg['FromUserName'],
						'wx_id'		=>	$arr_msg['ToUserName'],
						'media_id'	=>	$data
					)
				);					
				break;

			case 'news':
				Sk::$Conf->WX_TRACE_CATCH && Sk::$Log->traceLog("newsdata:" . $data);
				$data = json_decode($data, true);
				$arr_data = array(
						'openid'	=>	$arr_msg['FromUserName'],
						'wx_id'		=>	$arr_msg['ToUserName'],
						'count'		=>	count($data),
				);
				foreach ($data as $val) 
				{
					$arr_data['item'][] = array(
							'title'		=>	$val['title'],
							'des'		=>	$val['digest'],
							'picurl'	=>	$val['thumb_url'],
							'url'		=>	$val['url']
					);
				}
				$msg = self::news($arr_data);
				break;	

			case 'voice':
				$msg = self::voice(
					array(
						'openid'	=>	$arr_msg['FromUserName'],
						'wx_id'		=>	$arr_msg['ToUserName'],
						'media_id'	=>	$data
					)
				);					
				break;

			case 'video':
				$data = json_decode($data, true);
				$arr_data = array(
						'openid'	=>	$arr_msg['FromUserName'],
						'wx_id'		=>	$arr_msg['ToUserName'],
						'media_id'	=>	$data['media_id'],
						'title'		=>	$data['title'],
						'des'		=>	$data['description']
				);
				$msg = self::video($arr_data);					
				break;
		}	
		return $msg;	
	}

	/**
	 * text 消息
	 * <Content>Content</Content>
	 */
	public static function text($arr_data)
	{
		$arr_data['msg_type'] = __FUNCTION__;
		$arr_data['content'] = "<Content><![CDATA[$arr_data[content]]]></Content>";
		return self::tpl($arr_data);
	}


	/**
	 * image 消息
	 *  <Image>
     *  <MediaId><![CDATA[media_id]]></MediaId>
   	 *  </Image>
	 */
	public static function image($arr_data)
	{
		$arr_data['msg_type'] = __FUNCTION__;
		$arr_data['content'] = "<Image>
					<MediaId><![CDATA[{$arr_data[media_id]}]]></MediaId>
				</Image>";
		return self::tpl($arr_data);
	}	

	/**
	 * newsid 消息   无效
	 *  <News>
     *  <MediaId><![CDATA[media_id]]></MediaId>
   	 *  </News>
	 */
	public static function newsid($arr_data)
	{
		$arr_data['msg_type'] = 'mpnews';
		$arr_data['content'] = "<Mpnews>
					<MediaId><![CDATA[{$arr_data[media_id]}]]></MediaId>
				</Mpnews>";

		return self::tpl($arr_data);
	}	

	/**
	 * voice 消息
	 *  <Voice>
     *  <MediaId><![CDATA[media_id]]></MediaId>
   	 *  </Voice>
	 */
	public static function voice($arr_data)
	{
		$arr_data['msg_type'] = __FUNCTION__;
		$arr_data['content'] = "<Voice>
					<MediaId><![CDATA[{$arr_data[media_id]}]]></MediaId>
				</Voice>";

		return self::tpl($arr_data);
	}	

	/**
	 * video 消息
	 *  <video>
     *  <MediaId><![CDATA[media_id]]></MediaId>
     *  <Title><![CDATA[title]]></Title>
     *  <Description><![CDATA[description]]></Description>
   	 *  </video>
	 */
	public static function video($arr_data)
	{
		$arr_data['msg_type'] = __FUNCTION__;
		$arr_data['content'] = "<Video>
					<MediaId><![CDATA[{$arr_data[media_id]}]]></MediaId>
					<Title><![CDATA[{$arr_data[title]}]]></Title>
					<Description><![CDATA[{$arr_data[des]}]]></Description>	
				</Video>";

		return self::tpl($arr_data);
	}

	/**
	 * news 消息
	 * <ArticleCount>2</ArticleCount>
	 *  <Articles>
     *  	<item>
     *   		<Title><![CDATA[title1]]></Title> 
     *   		<Description><![CDATA[description1]]></Description>
     *   		<PicUrl><![CDATA[picurl]]></PicUrl>
     *   		<Url><![CDATA[url]]></Url>
     *  	</item>	
   	 *  </Articles>
	 */
	public static function news($arr_data)
	{
		$arr_data['msg_type'] = __FUNCTION__;
		$data = "<ArticleCount>{$arr_data[count]}</ArticleCount>
				<Articles>";

		$item = '';
		foreach ($arr_data['item'] as $val) { 
			$item .= '<item>					
							<Title><![CDATA[' . $val['title'] . ']]></Title>
							<Description><![CDATA[' . $val['des'] . ']]></Description>	
							<PicUrl><![CDATA[' . $val['picurl'] . ']]></PicUrl>
							<Url><![CDATA['. $val['url'] . ']]></Url>
					</item>';
		}
		$data .= "
				$item
				</Articles>";

		$arr_data['content'] =  $data;		
		unset($arr_data['item']);
		return self::tpl($arr_data);
	}					
}