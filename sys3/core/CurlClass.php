<?php
/**
 * CurlClass v2.0.1
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class Curl
{

	private static $ch;

	/**
	 * 	设置头信息 数组
	 */
	public static function setHeader($opt)
	{
		if(isset($opt{0}))
		{
			curl_setopt_array(self::$ch, CURLOPT_HTTPHEADER, $opt); 
		}
	}

	/**
	 * CURL  get_remote_data
	 * @param  [string] $url   [PAI地址]
	 * @param  [array] $post_data [提交的数据]
	 */
	public static function getRemoteData($url, $post_data = null, $isstr = true)
	{
		self::$ch = curl_init(); 
		self::setHeader(array('REQUEST_METHOD' => 'POST'));
		
		// 文件头输出
		curl_setopt(self::$ch, CURLOPT_HEADER, 0);

		// 请求URL
		curl_setopt(self::$ch, CURLOPT_URL, $url);

		// 发送数据
		if( is_array($post_data) && $isstr)
		{
			$post_data = http_build_query($post_data);
		}
	
		if( isset($post_data))
		{
			/**
			if (class_exists('\CURLFile')) {
				curl_setopt(self::$ch, CURLOPT_SAFE_UPLOAD, true);
			} else {
			}
			**/
			if (defined('CURLOPT_SAFE_UPLOAD')) {
				curl_setopt(self::$ch, CURLOPT_SAFE_UPLOAD, false);
			}			
			curl_setopt(self::$ch, CURLOPT_POST, 1);
			curl_setopt(self::$ch, CURLOPT_POSTFIELDS, $post_data);  	      	
		}
		// https
		if(stripos($url, 'https') !== false){
			curl_setopt(self::$ch, CURLOPT_SSL_VERIFYPEER, false); // 对认证证书来源的检查
			curl_setopt(self::$ch, CURLOPT_SSL_VERIFYHOST, false); // 从证书中检查SSL加密算法是否存在	
		}
		curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec(self::$ch);
		curl_close(self::$ch);
		return $result;
	}

	// 资源下载
	public static function downRemoteData($url, $save_file)
	{
		$fp = fopen($save_file,'wb');
		self::$ch = curl_init(); 
      	//curl_setopt(self::$ch, CURLOPT_NOSIGNAL, true);  //注意，毫秒超时一定要设置这个
		//curl_setopt(self::$ch, CURLOPT_TIMEOUT_MS, 100); //超时时间100毫秒		
		//curl_setopt(self::$ch, CURLOPT_TIMEOUT, 1);
		//curl_setopt(self::$ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt(self::$ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt(self::$ch, CURLOPT_URL, $url);
        curl_setopt(self::$ch, CURLOPT_FILE,$fp);
        curl_setopt(self::$ch, CURLOPT_HEADER,0);
        curl_exec(self::$ch);
        curl_close(self::$ch);
        fclose($fp);
        //Sk::$Log->tempLog('asyurl.txt', $save_file, 1);		
        return true;
	}
	
	public static function asynUrl($url)
	{
		$url = APP_SERVER_NAME . $url;
		$ch = curl_init(); 
      	curl_setopt($ch, CURLOPT_NOSIGNAL, true);  //注意，毫秒超时一定要设置这个
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100); //超时时间100毫秒		
		//curl_setopt($ch, CURLOPT_TIMEOUT, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);	
		curl_setopt($ch, CURLOPT_HEADER,0);
		curl_setopt($ch, CURLOPT_URL, $url);
        curl_exec($ch);
        curl_close($ch);
	}
}