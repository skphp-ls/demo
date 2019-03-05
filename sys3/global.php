<?php
/**
 * 系统全局函数 v2.0.4
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */

/**
 * 
 */
function get_server_name($https = false)
{
	$http =  $https ? 'https://' : 'http://';
	return $http . $_SERVER['SERVER_NAME'];
}

/**
 * 	PHP_URL_PATH
 */
function cur_url($component = PHP_URL_PATH)
{
	return parse_url(cur_page_url(),  $component);
}

/**
 * 	urlencode
**/
function en_cur_url()
{
	return urlencode(cur_page_url());	
}

/**
 * 	获取当前完整url
 */
function cur_page_url($ec = false) 
{
	//print_r($_SERVER["HTTPS"]);die;
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on") 
	{
		$pageURL .= 's';
	}
	$pageURL .= '://' . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
	//$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
	if($ec) $pageURL = urlencode($pageURL);
	return $pageURL;
}


/**
 *  跳转
 */
function redirect($url)
{
	header("Location: $url");
	die;
}

/**
 * JS验证模版
 */
function js_check($arr_rule, $evt = null)
{
	if($arr_rule)
	{
		foreach ($arr_rule as $key => $val) 
		{
			$method = $val['rule'][0];
			array_shift($val['rule']);
			if($method == 'regExp'){
				$val['rule'] = str_replace("'", '', $val['rule']);
				$args = '[' . $val['rule'][0] . ']';
			}else{
				$args = json_encode($val['rule']);
			}
			if(isset($evt))
			{
				$msg = $val['msg'];
				echo "items['{$key}'].check({
						method : '{$method}',
						arguments : {$args},
						showMsg : function (input){
							input.attr({'data-content':'$msg','data-trigger':'manual'}).popover('show');		
						},
						ok : function (input){
							input.popover('hide');
						}
					}, '$evt');";
			}else{
				echo "items['{$key}'].check({
						method : '{$method}',
						arguments : {$args},
						showMsg : function (input){
							input.attr('data-content', '$msg').popover('show');		
						},
						ok : function (input){
							input.popover('hide');
						}
					});";			
			}
		}		
	}

}

/**
 * 循环创建目录
 */
function make_full_path($dir, $mode = 0777) 
{
	if (!file_exists($dir))
	{ 
		make_full_path(dirname($dir)); 
		mkdir($dir, $mode);  
	}	
}


/**
 *  下划线字符转换驼峰
 */
function uc_words($str)
{
	$str = ucwords(strtolower(str_replace('_', ' ', $str)));
	return str_replace(' ', '', $str);
}	

/**
 *  驼峰转换下划线字符
 */
function uc_lower($str)
{
	$str = preg_replace('/([A-Z][a-z]+)/', '$1_', $str);
	return substr($str, 0, -1);	
}		


/**
 * global 配置信息
 */
function conf($key, $item = null)
{
	return Sk::$Conf->getData($key, $item);
}


// 去除特殊字符
function strip_entities($str, $def = '匿名') 
{
	preg_match_all('/[\x{4e00}-\x{9fff}\w]+/u', $str, $matches);
	if(empty($matches[0][0]))
	{
		return $def;
	}
	return implode('', $matches[0]);
}

// JSON 格式化
function encode_json($arr_data)
{
	die(json_encode($arr_data, JSON_UNESCAPED_UNICODE));
}


/**
 * 是否手机客户端
 */
function ismobile() {
	// 如果有HTTP_X_WAP_PROFILE则一定是移动设备
	if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
	    return true;

	//此条摘自TPM智能切换模板引擎，适合TPM开发
	if(isset ($_SERVER['HTTP_CLIENT']) &&'PhoneClient'==$_SERVER['HTTP_CLIENT'])
	    return true;
	//如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
	if (isset ($_SERVER['HTTP_VIA']))
	    //找不到为flase,否则为true
	    return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
	//判断手机发送的客户端标志,兼容性有待提高
	if (isset ($_SERVER['HTTP_USER_AGENT'])) {
	    $clientkeywords = array(
	        'nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile'
	    );
	    //从HTTP_USER_AGENT中查找手机浏览器的关键字
	    if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
	        return true;
	    }
	}
	//协议法，因为有可能不准确，放到最后判断
	if (isset ($_SERVER['HTTP_ACCEPT'])) {
	    // 如果只支持wml并且不支持html那一定是移动设备
	    // 如果支持wml和html但是wml在html之前则是移动设备
	    if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
	        return true;
	    }
	}
	return false;
}



/**
 * 进程所占内存大小
 */
function memory()
{
	$memory = memory_get_usage()/1024/1024;
	if($memory > 1){
		return number_format($memory, 2) . 'MB';
	}else{
		return number_format(($memory*1024), 2) . 'KB';
	}
}

/**
 * 取得请求消耗的时间
 */
function request_spend_time($format = 5)
{
	return number_format(microtime(TRUE) - PAGE_START_TIME, $format);
}