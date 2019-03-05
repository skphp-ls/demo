<?php
/**
 * 创建菜单
 */
class WxMenu
{
	// 创建菜单
	public static function init()
	{	
		$btn1 = self::viewBtn('会员中心', 'http://ygdn.boyiweichen.com/member/index');
		$btn2 = self::viewBtn('在线商城', 'http://ygdn.boyiweichen.com/product/shop');
		$btn3 = self::clickBtn('测试', 'click');						
		//$btn2 = self::scancodeBtn();
		//$btn3 = self::picBtn();			
		$data = self::btn($btn1, $btn2, $btn3);
		echo $data;
		$ret = self::upJsonData($data);
		var_dump($ret);
	}

	// 发送
	public static function upJsonData($data, & $msg)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token=';
		$result = AppIdConf::getResult($url, $data, $msg);	
		return $result;
	}

	// create
	public static function btn()
	{
		$args = func_get_args();
		if(is_array($args[0]))
		{
			$args = $args[0];
		}
		$data = join(',', $args);
		$data = str_replace("\n", '', $data);
		$data = str_replace("\t", '', $data);
		return '{"button":[' . $data . ']}';
	}

	// click
	public static function subBtn($name)
	{
		$args = func_get_args();
		if(is_array($args[1])){
			$args = $args[1];
		}else{
			array_shift($args);
		}
		//print_r($args);die;		
		$data = join(',', $args);
		return '{"name":"' . $name . '", "sub_button":[' . $data . ']}';
	}	

	// click
	public static function clickBtn($name, $key)
	{
		return '{"type":"click","name":"' . $name . '", "key":"' . $key . '"}';
	}

	// view
	public static function viewBtn($name, $url)
	{
		return '{"type":"view","name":"' . $name . '", "url":"' . $url . '"}';
	}	

	// view
	public static function xcxBtn($name, $appid, $path, $url = '')
	{
		return '{"type":"miniprogram","name":"' . $name . '", "url":"' . $url . '", "appid":"' . $appid . '", "pagepath":"' . $path . '"}';
	}

	// 扫码
	public static function scancodeBtn()
	{
		return '{
			"name": "扫码",
			"sub_button":[{
				"type":"scancode_waitmsg",
				"name":"扫码带提示",
				"key":"rselfmenu_0_0",
				"sub_button":[]},
				{
					"type":"scancode_push",
					"name":"扫码推事件",
					"key":"rselfmenu_0_1",
					"sub_button":[]
				}
		]}';
	}

	// 发图
	public static function picBtn()
	{
		return  '{
			"name": "发图",
			"sub_button": [
				{
					"type":"pic_sysphoto",
					"name":"系统拍照发图",
					"key":"rselfmenu_1_0",
				   "sub_button":[]
				 }, 
				{
					"type":"pic_photo_or_album",
					"name":"拍照或者相册发图",
					"key":"rselfmenu_1_1",
					"sub_button":[]
				},
				{
					"type":"pic_weixin",
					"name":"微信相册发图",
					"key":"rselfmenu_1_2",
					"sub_button":[]
				}
			]}';
	}	

	// 位置		
	public static function locationBtn()
	{
		return '{
			"name":"发送位置", 
			"type":"location_select", 
			"key":"rselfmenu_2_0"
		}';
	}


	// 获取菜单
	public static function getMenu()
	{
		$url = "https://api.weixin.qq.com/cgi-bin/get_current_selfmenu_info?access_token=";
		return AppIdConf::getResult($url);	
	}

}