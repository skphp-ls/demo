<?php
/**
 * CodeClass v2.0.3
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class Code
{
	
	/**
	 * 加密和解密函数
	 * @param string $string
	 * @param string $operation
	 * @param string $key
	 * @param int $expiry
	 * @return string
	 */
	public static function authCode($string, $key, $operation = 'DECODE', $expiry = 0) 
	{
		$ckey_length = 4;
		$key = md5($key);
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ?
					   substr($string, 0, $ckey_length):
					   substr(md5(microtime()), -$ckey_length)) : '';

		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);

		$string = $operation == 'DECODE' ?
						base64_decode(substr($string, $ckey_length)) :
						sprintf('%010d', $expiry ? $expiry + time() : 0).
							  substr(md5($string.$keyb), 0, 16).$string;

		$string_length = strlen($string);

		$result = '';
		$box = range(0, 255);

		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}

		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}

		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}

		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0)
				 && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16))
			{
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			return $keyc.str_replace('=', '', base64_encode($result));
		}
	}	

  
    /** 
     * AES加密,解密 默认192和256两种长度 
     * @param string $str   需加密的字符串 
     * @param string $key   密钥
     */  
    public static function aesCode($str, $key, $operation = 'DECODE', $mcrypt_rijndael = MCRYPT_RIJNDAEL_128, $mode = MCRYPT_MODE_ECB)
    {  
		$iv = $key;  
		if($operation != 'DECODE')
		{
			$str = mcrypt_encrypt($mcrypt_rijndael, $key, $str, $mode, $iv);
			$str = base64_encode($str);
			return $str;
		}
		$str = base64_decode($str);
	    $str = mcrypt_decrypt($mcrypt_rijndael, $key, $str, $mode, $iv); 
	    return $str;
    }	


    // des
	public static function encrypt($str, $key)
	{  
	    
	    $block = mcrypt_get_block_size('des', 'ecb');  
	    $pad = $block - (strlen($str) % $block);  
	    $str .= str_repeat(chr($pad), $pad);  
		return mcrypt_encrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);   
	} 


	public static function decrypt($str, $key)   
	{
	    $str = mcrypt_decrypt(MCRYPT_DES, $key, $str, MCRYPT_MODE_ECB);  
	    $block = mcrypt_get_block_size('des', 'ecb');  
	    $pad = ord($str[($len = strlen($str)) - 1]);
	    return substr($str, 0, strlen($str) - $pad);  
	    
	} 	 		   
}