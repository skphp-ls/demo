<?php
// 微信音乐
class QQMusic
{
	//http://music.qq.com/musicbox/shop/v3/data/hit/hit_newsong.js	
	//http://music.qq.com/musicbox/shop/v3/data/random/1/random1.js
	//http://music.qq.com/musicbox/shop/v3/data/hit/hit_all.js

	// jsonCallback=MusicJsonCallback
	// perpage=20&curpage=1&w=$music
	// jsonCallback=MusicJsonCallback
	const API_URL = 'https://auth-external.music.qq.com/open/fcgi-bin/fcg_weixin_music_search.fcg?remoteplace=txt.weixin.officialaccount&platform=weixin';

	// $arr_data | fase = string , true = array
	public static function getMusicArrData(& $arr_data, $word, $page = 1, $perpage = 10)
	{
		$url = self::API_URL . "&w=$word&curpage=$page&perpage=$perpage";
		$json_result =  Curl::getRemoteData($url);
		$arr_data = json_decode($json_result, true);
		return false;
	}


	// 2018/4/29
	public static function getMusicUrl($songid, $strmid)
	{
		$url = "https://c.y.qq.com/base/fcgi-bin/fcg_music_express_mobile3.fcg?g_tk=5381&loginUin=0&hostUin=0&format=json&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0&cid=205361747&uin=0&songmid=$songid&filename=C400$strmid.m4a&guid=7155071082";
		$json_result =  Curl::getRemoteData($url);
		$arr_data = json_decode($json_result, true);
		if($arr_data['code'] == 0)
		{
			if(isset($arr_data['data']['items'][0]))
			{
				$data = $arr_data['data']['items'][0];
			}else{
				$data = $arr_data['data']['items'];
			}
			$url = "http://dl.stream.qqmusic.qq.com/$data[filename]?vkey=$data[vkey]&guid=7155071082&uin=0&fromtag=66";
			return $url;
		}
		return false;
	}

	// 2018/4/29
	public static function getMusicId(& $arr_data, $w, $page = 1, $limit = 10)
	{
		$url = "http://s.music.qq.com/fcgi-bin/music_search_new_platform?t=0&n=$limit&aggr=1&cr=1&loginUin=0&format=json&inCharset=utf8&outCharset=utf-8&notice=0&platform=jqminiframe.json&needNewCode=0&p=$page&catZhida=0&remoteplace=sizer.newclient.next_song&w=$w";
		$url = "https://c.y.qq.com/soso/fcgi-bin/client_search_cp?ct=24&qqmusic_ver=1298&new_json=1&remoteplace=txt.yqq.song&searchid=68118207120605907&t=0&aggr=1&cr=1&catZhida=1&lossless=0&flag_qc=0&p=$page&n=$limit&w=$w&g_tk=5381&loginUin=0&hostUin=0&format=json&inCharset=utf8&outCharset=utf-8&notice=0&platform=yqq&needNewCode=0";
		//echo $url;
		$json_result =  Curl::getRemoteData($url);
		//echo $json_result;
		$arr_data = json_decode($json_result, true);
		return true;		
	}	
}
