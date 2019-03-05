<?php
/**
 * 客服
 */
class KfAccount
{

	//添加客服
	public static function addKf($arr_data)
	{
		$url = 'https://api.weixin.qq.com/customservice/kfaccount/add?access_token=';
		$data = '{"kf_account" : "' . $arr_data['kf_account'] . '","nickname" : "' . $arr_data['nickname'] . '","password" : "' . $arr_data['password'] . '"}';
		return AppIdConf::getResult($url, $data);	
	}

	//修改客服
	public static function uptAccount($arr_data)
	{
		$url = 'https://api.weixin.qq.com/customservice/kfaccount/update?access_token=';
		$data = '{"kf_account" : "' . $arr_data['kf_account'] . '","nickname" : "' . $arr_data['nickname'] . '","password" : "' . $arr_data['password'] . '"}';
		return AppIdConf::getResult($url, $data);
	}

	//删除客服
	public static function delAccount($arr_data)
	{
		$url = 'https://api.weixin.qq.com/customservice/kfaccount/del?access_token=';
		$data = '{"kf_account" : "' . $arr_data['kf_account'] . '","nickname" : "' . $arr_data['nickname'] . '","password" : "' . $arr_data['password'] . '"}';
		return AppIdConf::getResult($url, $data);
	}

	//设置图像 头像图片文件必须是jpg格式，推荐使用640*640大
	public static function upHeadimg()
	{
		$url = 'http://api.weixin.qq.com/customservice/kfaccount/uploadheadimg?access_token=ACCESS_TOKEN&kf_account=KFACCOUNT';

	}

	//客服列表
	public static function listAccount()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/customservice/getkflist?access_token=ACCESS_TOKEN';

	}
}