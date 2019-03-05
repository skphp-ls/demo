<?php
/**
 * 用户
 */
class Members
{

	// 创建分组
	public static function creGroup($name)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/groups/create?access_token=';
		$data = '{"group":{"name":"' . $name . '"}}';
		return AppIdConf::getResult($url, $data);
	}	

	// 查询所有分组
	public static function listGroup()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token=';
		return AppIdConf::getResult($url);			
	}

	// 查询用户所在分组
	public static function getGroupId($openid)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=';
		$data = '{"openid":"' . $openid . '"}';
		return AppIdConf::getResult($url, $data);			
	}

	// 修改分组名	
	public static function uptGroup($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=';
		$data = '{"group":{"id":' . $arr_data['id'] . ',"name":"' . $arr_data['name'] . '"}}';
		return AppIdConf::getResult($url, $data);			
	}


	// 移动用户分组	
	public static function userToGroup($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=';
		$data = '{"openid":"' . $arr_data['openid'] . '", "to_groupid":' . $arr_data['id'] . '}';
		return AppIdConf::getResult($url, $data);			
	}

	// 批量移动用户分组
	public static function batchUserToGroup($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/groups/members/batchupdate?access_token=';
		$str_openid = '';
		foreach ($arr_data['arr_openid'] as $val) 
		{
			$str_openid .= ',"' . $val . '"';
		}
		$str_openid = substr($str_openid, 1);
		$data = '{"openid_list":["' . $str_openid . '"], "to_groupid":' . $arr_data['id'] . '}';
		return AppIdConf::getResult($url, $data);			
	}	

	// 删除分组	
	public static function delGroup($id)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/groups/delete?access_token=';
		$data = '{"group":{"id":' . $id . '}}';
		return AppIdConf::getResult($url, $data);			
	}	

	// 设置备注名
	public static function setRemark($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token=';
		$data = '{"openid":"' . $arr_data['openid'] . '","remark":"' . $arr_data['remark'] . '"}';
		return AppIdConf::getResult($url, $data);
	}

	// 获取用户基本信息（包括UnionID机制）
	public static function getUserInfo($openid)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info?openid=' . $openid . '&lang=zh_CN&access_token=';
		return AppIdConf::getResult($url);
	}

	// 批量获取用户基本信息
	public static function getlistUserInfo($arr_data)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=';
		$str_openid = '';
		foreach ($arr_data['arr_openid'] as $val) 
		{
			$str_openid .= ',{"openid": "' . $val . '","lang": "zh-CN"}';
		}
		$str_openid = substr($str_openid, 1);
		$data = '{"user_list":[' . $str_openid . ']}';
		return AppIdConf::getResult($url);		
	}

	// 获取用户列表
	public static function getUserList($openid = null)
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/user/get?next_openid=' . $openid . '&access_token=';
		return AppIdConf::getResult($url);
	}


	//	获取Web授权用户信息
	//	arr_data{'key' => openid}
	public static function getWebUserInfo($openid)
	{
		$access_token = WxWebAuth::getAccessToken($openid);
		if($access_token == false) return false;
		$token = $access_token['access_token'];
		$openid = $access_token['openid'];
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$openid&lang=zh_CN";
		$data = Curl::getRemoteData($url);
		$arr_data = json_decode($data, true);
		if(isset($arr_data['errcode']))
		{
			return false;
		}
		return $arr_data;
	}
}	