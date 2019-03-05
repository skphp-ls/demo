<?php
// 访问统计
function total_account_count($id, $model)
{
	$readday = $model::fetchItem($id, 'readday');
	$day = date('d');
	$rnd = mt_rand(1,5);
	//echo $rnd;
	$model::cumulData($id, array('readcount'=> "+$rnd"));
	if($readday == $day)
	{
		$model::cumulData($id, array('readdaycount'=> "+$rnd"));	
	}else{
		$model::updateData($id, array('readdaycount' => "+$rnd", 'readday' => $day));	
	}
}
// 计时器
function timer($key, $seconds)
{
	$timer = (int)Mc::get($key);
	$timer = $seconds - (CURRENT_TIME - $timer);
	if($timer <= 0)
	{
		Mc::set($key, CURRENT_TIME, 0, $seconds);
	}
	return $timer;  
}

// 城市缓存
function cache_city($cid = null)
{
	$city_town = Mc::get('city');
	if($city_town == false)
	{
		$city_town = CityTownModel::fetchAll();
		$city_town = ArrData::setKeyArrData('id', $city_town);
		Mc::set('city', $city_town, MEMCACHE_COMPRESSED);
	}
	if(isset($cid))
	{
		return $city_town[$cid];
	}
	return $city_town;
}

// 获取商户分类
function cache_sclass($qclass)
{
	$sclass = Mc::get('sclass_' .$qclass );
	if($sclass == false)
	{
		$sclass = ShopClassModel::fetchAllByIndex(array('qclass' => $qclass));
		//$sclass = ArrData::setKeyArrData('id', $sclass);
		$sclass = ArrData::setKeySingleArrData($sclass, 'c_name', 'id');
		Mc::set('sclass_' . $qclass, $sclass, MEMCACHE_COMPRESSED);
	}
	if(isset($cid))
	{
		return $sclass[$cid];
	}
	return $sclass;  
}

/**微信**/
function wx_islogin($subscribe = true)
{
	$key = get_global('cookiekey');  	
	//Cookie::delCookie('memberlogin');	
	$memberid = Cookie::getCookie('memberlogin', $key);
	//var_dump($memberid);die;
	// 是否登录
	if($memberid == false)
	{
		// 微信认证
		$code = null;
		if(isset($_GET['code']))
		{
			$code = $_GET['code'];
		}
		$memberid = wx_login($code);
		if($memberid){
			Cookie::setCookie('memberlogin', $memberid, $key);			
		}
	}
	// 帐号
	$is_lock = get_cache_info($memberid, 'is_lock');
	if($is_lock === false)
	{
		redirect('/mobile/error/subscribe');
	}
	// 锁定
	if($is_lock == 2)
	{
		redirect('/mobile/error/lock');
	}
	/*
	if(! in_array($memberid, array(74)))
	{
		redirect('/mobile/error/access');
		die;
	}*/
	return $memberid;
}

// 公众号登录
function wx_login($code)
{
	if(! $code)
	{
		$back_url = wx_auth_url(cur_page_url());
		//die($back_url);
		redirect($back_url);	
		die;		
	}
	$wx_arr_data = WxWebAuth::getAccessToken($code);
	//print_r($wx_arr_data);die;
	if($wx_arr_data == false)
	{
		redirect(wx_auth_url(cur_page_url()));	
		die;		
	}
	$wx_arr_data = WxWebAuth::getUserInfo($wx_arr_data['access_token'], $wx_arr_data['openid']);
	$unionid = $wx_arr_data['unionid'];
	$memberid = MemberWechatModel::fetchItemByIndex(array('unionid' => $unionid), 'memberid');
	return $memberid;
}

// memcache缓存
function get_cache_info($memberid, $item = null)
{
	$key = 'ce_' . $memberid;
	$member = Mc::get($key);
	if($member == false)
	{
		$expires = Conf::CACHE_EXPIRE_TIME;
		$member = MemberModel::fetchOne($memberid, 'id,openid,nickname,truename,phone,is_lock,is_admin,is_shop,cache,is_verify');
		if($member)
		{
			Mc::set($key, $member, MEMCACHE_COMPRESSED, $expires);
		}else{
			return false;
		}
	}
	return ($item ? $member[$item] : $member);
}

// memcache清除
function del_cache($memberid)
{
	$key = 'ce_' . $memberid;
	return Mc::delete($key);	
}


// 'snsapi_userinfo'
function wx_auth_url($url, $scope = 'snsapi_base', $state = null)
{
	$arr_data = array(
		'url'	=>	$url,
		'scope'	=>	$scope,
		'state'	=>	$state
	);
	return WxWebAuth::getAuthUrl($arr_data);	
}