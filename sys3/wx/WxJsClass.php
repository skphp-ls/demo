<?php
class WxJs
{
	public static function getWxConfig($ckey, $debug = 'false')
	{
		$conf = WxConf::Conf($ckey);
		$timestamp = CURRENT_TIME;
		$signature = self::getSignature($ckey);
		return "wx.config({
	   			 debug: $debug, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
	    		appId: '$conf[AppId]', // 必填，公众号的唯一标识
	   			timestamp: $timestamp, // 必填，生成签名的时间戳
				nonceStr: '$conf[EncodingAESKey]', // 必填，生成签名的随机串
				signature: '$signature',// 必填，签名，见附录1
				jsApiList: ['onMenuShareTimeline',
							'onMenuShareAppMessage',
							'onMenuShareQQ',
							'onMenuShareWeibo',
							'onMenuShareQZone',
							'startRecord',
							'stopRecord',
							'onVoiceRecordEnd',
							'playVoice',
							'pauseVoice',
							'stopVoice',
							'onVoicePlayEnd',
							'uploadVoice',
							'downloadVoice',
							'chooseImage',
							'previewImage',
							'uploadImage',
							'downloadImage',
							'translateVoice',
							'getNetworkType',
							'openLocation',
							'getLocation',
							'hideOptionMenu',
							'showOptionMenu',
							'hideMenuItems',
							'showMenuItems',
							'hideAllNonBaseMenuItem',
							'showAllNonBaseMenuItem',
							'closeWindow',
							'scanQRCode',
							'chooseWXPay',
							'openProductSpecificView',
							'addCard',
							'chooseCard',
							'openCard'] 
							// 必填，需要使用的JS接口列表，所有JS接口列表见附录2
			});";
	}

	public static function getSignature($ckey) 
	{
		$jsapiTicket = WxAccessToken::getJsapiTicket();
		$url = cur_page_url();
		$timestamp = CURRENT_TIME;
		$nonceStr = WxConf::Conf($ckey, 'EncodingAESKey');
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		return sha1($string); 
	}
}