<?php
/**
 * 素材
 * 1、对于临时素材，每个素材（media_id）
 * 会在开发者上传或粉丝发送到微信服务器3天后自动删除
 * （所以用户发送给开发者的素材，若开发者需要，应尽快下载到本地），
 * 以节省服务器资源。
 * 2、media_id是可复用的。
 * 3、素材的格式大小等要求与公众平台官网一致。
 * 具体是，图片大小不超过2M，支持bmp/png/jpeg/jpg/gif格式，
 * 语音大小不超过5M，长度不超过60秒，支持mp3/wma/wav/amr格式
 * 4、需使用https调用本接口。
 */
class Material
{

	public static function getTmpMaterialFile($media_id, $upfolder, $fname = null)
	{
		$access_token = WxAccessToken::getAccessToken();
		$url = 'https://api.weixin.qq.com/cgi-bin/media/get?media_id=' . $media_id . '&access_token=' . $access_token;
		//echo $url;
		return wx_material_down($url, $upfolder, $fname);
	}

	// 素材总数
	public static function getMaterialCount()
	{	
		$url = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=';
		return AppIdConf::getResult($url);		
	}

	// 素材列表
	public static function getMaterialList($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=';
		return AppIdConf::getResult($url, $arr_data);
	}

	// 获取永久素材
	public static function getMaterial($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=';
		return AppIdConf::getResult($url, $arr_data);
	}

	//新增临时素材
	// 上传临时素材	type = image/voice/video/thumb  type:image/voice/video/thumb,media:url
	public static function upTmpMaterial($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/media/upload?type=' . $arr_data['type'] . '&access_token=';
		return self::upload($url, $arr_data);
	}

	/**
	 * 1、新增的永久素材也可以在公众平台官网素材管理模块中看到
	 * 2、永久素材的数量是有上限的，请谨慎新增。
	 * 图文消息素材和图片素材的上限为5000，其他类型为1000
	 * 3、素材的格式大小等要求与公众平台官网一致。具体是，
	 * 图片大小不超过2M，支持bmp/png/jpeg/jpg/gif格式，语音大小不超过5M，
	 * 长度不超过60秒，支持mp3/wma/wav/amr格式
	 * 4、调用该接口需https协议
	 */
	
	// 新增永久图文素材
	// 图文素材
	public static function upMaterialNews($arr_data)
	{
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=ACCESS_TOKEN";
		$data = '';
		foreach ($arr_data as $val) 
		{
			$data .= ',[{
					"title":' . $val['title'] . ',
					"thumb_media_id": ' . $val['thumb_media_id'] . ',
					"author":' . $val['author'] . ',
					"digest":' . $val['digest'] . ',
					"show_cover_pic":' . $val['show_cover_pic'] . ',
					"content":' . $val['content'] . ',
					"content_source_url":' . $val['content_source_url'] . '
				}]';
		}
		$data = substr($data, 1);
		$data = '{"articles":' . $data . '}';
		return AppIdConf::getResult($url, $arr_data);
	}

	// 新增永久素材
	// 上传素材 type:image/voice/video/thumb,media:url
	public static function upMaterial($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=';
		return self::upload($url, $arr_data);
	}

	// 新增永久图文素材URL
	// 上传图片获取URL 图片仅支持jpg/png格式，大小必须在1MB以下。  media:url
	public static function getUpImageUrl($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=';
		return self::upload($url, $arr_data);				
	}

	// 上传
	private static function upload($url, $arr_data)
	{
		$path = SiteMap::getAppTreeItem(Enum::CONF_APP_DATA_KEY);
		$access_token = WxAccessToken::getAccessToken();
		$filename = realpath($path . $arr_data['media']);  		
		$arr_data['media'] = '@' . $filename;
		if (class_exists('CURLFile')) {
			$arr_data['media'] = new CURLFile(realpath($filename));
		} else {
			$arr_data['media'] = '@' . realpath($filename);
		}	
		if($arr_data['type'] == 'video')
		{
			$arr_data['description'] = array(
					'title'			=>		$arr_data['title'],
					'introduction'	=>		$arr_data['introduction']
				);
			unset($arr_data['title']);unset($arr_data['introduction']);
		}		 
		$url .= $access_token;
		//print_r($arr_data);
		$data = Curl::getRemoteData($url, $arr_data, false);
		//echo $data;
		$arr_data = json_decode($data, true);	
		if(isset($arr_data['errcode']))
		{
			return false;
		}
		return $arr_data;	
	}
}
