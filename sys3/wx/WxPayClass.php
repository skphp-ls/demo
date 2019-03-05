<?php
require_once "payv3/WxPay.Api.php";
/*
	<script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			$jsApiParameters,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				//alert(res.err_code+res.err_desc+res.err_msg);
				if(res.err_msg == 'get_brand_wcpay_request:ok')
				{
					//location.href = '';
				}
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}

	</script>

	onclick="callpay()" >立即支付
*/
class WxPay
{
	// 订单设置
	// $arr_data = array(
	// 		'body'		=>	$body, 设置商品或支付单简要描述
	// 		'attach'	=>	$attach, 设置附加数据
	// 		'price'		=>	$price, 价格分
	// 		'goods_tag'	=>	$goods_tag, 设置商品标记
	// 		'openid'	=>	$openid,
	//		'notify'	=>	$url
	// );
	public static function jsApiParameters($arr_data, & $msg = false)
	{
		//②、统一下单
		if(isset($arr_data['ptype']{1}))
		{
			new WxPayConfig(Enum::CONF_WX_KEY, $arr_data['ptype']);
		}else{
			new WxPayConfig(Enum::CONF_WX_KEY);
		}
		try
 		{
			$input = new WxPayUnifiedOrder();
			$input->SetBody($arr_data['body']);				// 描述
			$input->SetAttach($arr_data['attach']);			// 附加数据
			$input->SetOut_trade_no(date("YmdHis").time());
			$input->SetTotal_fee($arr_data['price']);		// 金额，只能为整数 分
			$input->SetTime_start(date("YmdHis"));
			$input->SetTime_expire(date("YmdHis", time() + 600));
			$input->SetGoods_tag($arr_data['goods_tag']);	// 代金卷
			if(isset($arr_data['notify']))
			{
				$input->SetNotify_url($arr_data['notify']);
			}
			$input->SetTrade_type("JSAPI");
			$input->SetOpenid($arr_data['openid']);
			$order = WxPayApi::unifiedOrder($input);
			// JS 参数
			//print_r($order);die;
			if(!array_key_exists("appid", $order)
			|| !array_key_exists("prepay_id", $order)
			|| $order['prepay_id'] == "")
			{
				throw new WxPayException("订单出错了，支付失败");
			}
			$jsapi = new WxPayJsApiPay();
			$jsapi->SetAppid($order["appid"]);
			$timeStamp = time();
			$jsapi->SetTimeStamp("$timeStamp");
			$jsapi->SetNonceStr(WxPayApi::getNonceStr());
			$jsapi->SetPackage("prepay_id=" . $order['prepay_id']);
			$jsapi->SetSignType("MD5");
			$jsapi->SetPaySign($jsapi->MakeSign());
			return $jsapi->GetValues();
		}catch(WxPayException $e)
		{
			$msg = $e->errorMessage();
			return false;
		}
		/*
		new WxPayConfig(Enum::CONF_WX_KEY);
		//②、统一下单
		$input = new WxPayUnifiedOrder();
		$input->SetBody($arr_data['body']);				// 描述
		$input->SetAttach($arr_data['attach']);			// 附加数据
		$input->SetOut_trade_no(date("YmdHis").time());
		$input->SetTotal_fee($arr_data['price']);		// 金额，只能为整数 分
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetGoods_tag($arr_data['goods_tag']);	// 代金卷
		if(isset($arr_data['notify']))
		{
			$input->SetNotify_url($arr_data['notify']);
		}
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($arr_data['openid']);
		$order = WxPayApi::unifiedOrder($input);
		// JS 参数
		//print_r($input);die;
		if(!array_key_exists("appid", $order)
		|| !array_key_exists("prepay_id", $order)
		|| $order['prepay_id'] == "")
		{
			throw new WxPayException("参数错误");
		}
		$jsapi = new WxPayJsApiPay();
		$jsapi->SetAppid($order["appid"]);
		$timeStamp = time();
		$jsapi->SetTimeStamp("$timeStamp");
		$jsapi->SetNonceStr(WxPayApi::getNonceStr());
		$jsapi->SetPackage("prepay_id=" . $order['prepay_id']);
		$jsapi->SetSignType("MD5");
		$jsapi->SetPaySign($jsapi->MakeSign());
		return $jsapi->GetValues();
		*/
	}

	// 企业个人转款
	public static function mchPay($arr_data)
	{
		//②、统一下单
		new WxPayConfig(Enum::CONF_WX_KEY);
		$input = new WxPayMch();
		$input->SetOpenid($arr_data['openid']);
		$input->amount($arr_data['amount']);
		if(! isset($arr_data['transaction_id']))
		{
			$arr_data['transaction_id'] = date("YmdHis").time();
		}	
		$input->partnerTradeNo($arr_data['transaction_id']);
		return WxPayApi::mchOrder($input);				
	}

	/**
	 * 
	 * 获取地址js参数
	 * 
	 * @return 获取共享收货地址js函数需要的参数，json格式可以直接做参数使用
	 */
	public static function GetEditAddressParameters()
	{	
		new WxPayConfig(Enum::CONF_WX_KEY);
		$getData = $this->data;
		$data = array();
		$data["appid"] = WxPayConfig::$APPID;
		$data["url"] = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		$time = time();
		$data["timestamp"] = "$time";
		$data["noncestr"] = "1234568";
		$data["accesstoken"] = $getData["access_token"];
		ksort($data);
		$params = $this->ToUrlParams($data);
		$addrSign = sha1($params);
		
		$afterData = array(
			"addrSign" => $addrSign,
			"signType" => "sha1",
			"scope" => "jsapi_address",
			"appId" => WxPayConfig::APPID,
			"timeStamp" => $data["timestamp"],
			"nonceStr" => $data["noncestr"]
		);
		$parameters = json_encode($afterData);
		return $parameters;
	}	
}