<?php
/**
 * ValidateClass v2.0.1
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class Validate
{

	/**
	 *  正则表达式
	 */
	public static function regExp($str, $rule)
	{
		return preg_match($rule, $str);	
	}

	/**
	 * 长度检测
	 */
	public static function isLen($str, $min, $max = null, $chr = 'utf8')
	{
		$strlen = mb_strlen($str, $chr);
		if($strlen < $min)
		{
			return false;
		}
		if (isset($max) && $strlen > $max) 
		{
			return false;
		}
		return true;	
	}

	/**
	 *  是否空值
	 */
	public static function isEmpty($str)
	{

		return self::isLen($str, 1);	
	}

	/**
	 *  是否数字
	 */
	public static function isNumeric($str)
	{

		return self::isNum($str, 1);	
	}

	/**
	 *  等于
	 */
	public static function isVal($str, $val)
	{
		//die("$str, $val");
		return $str != $val;
	}

	/**
	 *  不等于
	 */
	public static function noVal($str, $val)
	{
		return $str == $val;	
	}

	/**
	 *  大于
	 */
	public static function isLt($str, $min, $max = null)
	{
		$str = intval($str);
		$ret = $str > $min;
		if(isset($max))
		{
			$ret = $str <= $max;
		}
		return $ret;
	}

	/**
	 *  数字长度
	 */
	public static function isNum($str, $min = 1, $max = 0)
	{
		if($max > 0)
		{
			return preg_match('/^[\d\.]{' . $min . ',' . $max . '}$/', $str);
		}
		return preg_match('/^[\d\.]{' . $min . ',}$/', $str);	
	}

	/**
	 *  非数字值的长度
	 */
	public static function isStr($str, $min = 1, $max = 100)
	{

		return preg_match('/^\[A-Za-z]{' . $min . ',' . $max . '}$/', $str);	
	}

	/**
	 * 用户名
	 */
	public static function isUserName($str, $min = 5, $max = 20)
	{
		return preg_match('/^[a-zA-Z]\w{' . $min . ',' . $max . '}$/', $str);
	}


	/**
	 * IP地址
	 */
	public static function isIp($str)
	{
		return preg_match('/((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)(\.((25[0-5])|(2[0-4]\d)|(1\d\d)|([1-9]\d)|\d)){3}/', $str);
	}
	
	/**
	 * 日期格式 支持短日期 长日期
	 */	
	public static function isDate($str)
	{
		return preg_match('/^\d{4}\-\d{1,2}\-\d{1,2}(\s+\d{1,2}\:\d{1,2}\:\d{1,2})?$/', $str);
	}

	/**
	 * 手机号码
	 */
	public static function isMobile($str)
	{
		$ret =  preg_match('/^0?(18[8|9]|13[0-9]{1}|15[0-9]{1})(\d{8})$/', $str);
		return $ret;
	}

	/**
	 * 邮政编码
	 */
	public static function isZip($str)
	{
		return preg_match('/^[1-9]\d{5}$/', $str);
	}

	/**
	 * 验证邮件地址
	 * ??是否做进一步验证??
	 * @param string $str
	 * @return boolean
	 */
	public static function isEmail($str)
	{
		return preg_match('/^[a-z0-9]+([\+_\-\.]?[a-z0-9]+)*@([a-z0-9]+[\-]?[a-z0-9]+\.)+[a-z]{2,6}$/i', $str);
	}


	/**
	 * 验证URL地址
	 *
	 * @param string $str
	 * @return boolean
	 */
	public static function isUrl($str)
	{
		return preg_match('|^http://[_=&///?\.a-zA-Z0-9-]+$|i', $str);
	}

	/** JS 以下方法不支持  */
	
	/**
	 * UTF8中文 长度
	 */
	public static function isUt8Ch($str, $min = 2, $max = 10)
	{
		return preg_match('/^[\x{4e00}-\x{9fa5}]{' . $min . ',' . $max . '}$/u', $str);
	}

	/**
	 * GBK中文 长度
	 */
	public static function isGbkCh($str, $min = 2, $max = 10)
	{
		return preg_match('/^[".chr(0xa1)."-".chr(0xff)."]{' . $min . ',' . $max . '}$/', $str);
	}

	/**
	 * 判断是否有效EMAIL
	 * @param string $str
	 * @return boolean
	 */
	public static function isRealEmail($str)
	{
		$emailValidator = new Zend_Validate_EmailAddress();
		if ($emailValidator->isValid($str))
		{
			 return true;
		}
		return false;
	}


	
	/**
	 *  身份证
	 *
	 * @param string $id
	 * @return boolean
	 */
	public static function isIdentityCard($str)
	{
		$id = strtoupper($str);
		$regx = "/(^\d{15}$)|(^\d{17}([0-9]|X)$)/";
		$arr_split = array();
		if(!preg_match($regx, $id))
		{
			return false;
		}
		if(15==strlen($id)) //检查15位
		{
			$regx = "/^(\d{6})+(\d{2})+(\d{2})+(\d{2})+(\d{3})$/";

			@preg_match($regx, $id, $arr_split);
			//检查生日日期是否正确
			$dtm_birth = "19".$arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
			if(!strtotime($dtm_birth))
			{
				return false;
			}else{
				return true;
			}
		}
		else           //检查18位
		{
			$regx = "/^(\d{6})+(\d{4})+(\d{2})+(\d{2})+(\d{3})([0-9]|X)$/";
			@preg_match($regx, $id, $arr_split);
			$dtm_birth = $arr_split[2] . '/' . $arr_split[3]. '/' .$arr_split[4];
			if(!strtotime($dtm_birth))  //检查生日日期是否正确
			{
				return false;
			}
			else
			{
				//检验18位身份证的校验码是否正确。
				//校验位按照ISO 7064:1983.MOD 11-2的规定生成，X可以认为是数字10。
				$arr_int = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);
				$arr_ch = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');
				$sign = 0;
				for ( $i = 0; $i < 17; $i++ )
				{
					$b = (int) $id{$i};
					$w = $arr_int[$i];
					$sign += $b * $w;
				}
				$n  = $sign % 11;
				$val_num = $arr_ch[$n];
				if ($val_num != substr($id,17, 1))
				{
					return false;
				}
				else
				{
					return true;
				}
			}
		}
	}
}
