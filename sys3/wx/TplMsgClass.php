<?php
class TplMsg
{
	public static function send($data)
	{	
		$url = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=';
		$data['appid'] =  AppIdConf::Conf('AppId');
		if(! isset($data['pagepath']))
		{
			$data['pagepath'] = '';
		}
		if(! isset($data['url']))
		{
			$data['url'] = '';
		}
		$content = '';
		$arr_data = $data['content'];
		foreach ($arr_data as $key => $val) 
		{
			$content .= '"' . $key . '":{"value":"'.$val['value'].'","color":"'.$val['color'].'"},';
		}
		$content = rtrim($content, ',');			
		$json = '{"touser":"'.$data['openid'].'","template_id":"'.$data['tid'].'","url":"'.$data['url'].'","appid":"'.$data['appid'].'","pagepath":"'.$data['pagepath'].'","data":{'.$content.'}}';
		//echo $json;
		$result = AppIdConf::getResult($url, $json, $msg);
		//print_r($msg);
		return $result;
	}

	public static function czMsg($data)
	{
		$data['tid'] = '5D679fu8yXAdGoFBTbkAhDznOloYAZqmmJ0AJ59M_8Y';
		self::send($data);
	}

	public static function txMsg($data)
	{
		$data['tid'] = 'S9QUvDcAhem3wtdVZa4jd2qt9Ow8WYzQGEzPHAYTl0s';
		self::send($data);		
	}
}