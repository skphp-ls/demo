<?php
/**
 * Base, BaseJsonRet, BaseStatic v2.4.1
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class Base
{
	protected $Db;
	protected $page_key 	= 	'page';	
	protected $page 		= 	1;
	protected $page_size 	= 	8;
	protected $page_count 	= 	0;	
	protected $page_num 	= 	5;		
	protected $page_params 	= 	null;
	protected $page_url 	= 	null;

	/**
	*  取正整型
	*/
	protected function requestAbs($key, $def = 0)
	{
		if(isset($_REQUEST[$key]))
		{
			$val = intval($_REQUEST[$key]);
			return abs($val <= 0 ? $def : $val);
		}
		return $def;
	}


	/**
	*  取整型
	*/
	protected function requestInt($key, $def = 0)
	{
		if(isset($_REQUEST[$key]))
		{
			$val = intval($_REQUEST[$key]);
			return ($val <= 0 ? $def : $val);
		}
		return $def;
	}

	// 常规字符
	protected function requestStr($key, $rule = null, $def = '')
	{
		if(isset($_REQUEST[$key]))
		{		
			$val = $_REQUEST[$key];
			if(! is_array($val)){
				$val = strip_tags(trim($val));
				$val = htmlspecialchars($val, ENT_QUOTES);
			}
			preg_match_all('/[\x{4e00}-\x{9fff}\w'.$rule.']+/u', $val, $matches);
			return trim(implode('', $matches[0]));
		}		
		return $def;
	}

	// 文本内容
	protected function request($key, $rule = null, $def = '')
	{
		if(isset($_REQUEST[$key]))
		{		
			$val = $_REQUEST[$key];
			if(! is_array($val)){
				$val = strip_tags(trim($val));
				$val = htmlspecialchars($val, ENT_QUOTES);
			}
			preg_match_all('/[\x{4e00}-\x{9fff}\w\pP\pS\n'.$rule.']+/u', $val, $matches);
			return trim(implode('', $matches[0]));
		}		
		return $def;
	}


	/**
	* 获取utf16_to_entities
	*/
	protected function requestU16($key, $def = '')
	{
		if(isset($_REQUEST[$key]))
		{
			if(! is_array($val)){
				$val = strip_tags(trim($val));
				$val = htmlspecialchars($val, ENT_QUOTES);
			}			
			return $this->utf16_to_entities($_REQUEST[$key]);
		}
		return $def;
	}

	/**
	 * 获取字符串转义
	 * htmlspecialchars_decode
	 */
	protected function requestHtml($key, $def = '')
	{
		if(isset($_POST[$key]))
		{
			return htmlspecialchars($_POST[$key], ENT_QUOTES);
		}
		return $def;
	}

	/**
	 * 规则载入
	 */
	protected function loadRule($name)
	{
		//die($this->rule_controller_name);
		$file_path = uc_words($this->rule_controller_name) . Enum::RULE_NAME_SUFFIX;
		$file_path = Sk::$Path->ctl['rule'] . '/'. $file_path . '.php';
		if(! is_file($file_path))
		{
			throw new SkException($file_path . '规则文件未定义', 10004);
		}
		$arr_rule = include($file_path);
		if(isset($arr_rule[$name]))
		{
			return $arr_rule[$name];
		}
		if(isset($arr_rule[Enum::GLOBAL_RULE_NAME]))
		{
			return $arr_rule[Enum::GLOBAL_RULE_NAME];
		}
		if($this->controller_type == Enum::CONTROLLER_AJAX_TYPE_SUFFIX || $this->controller_type == Enum::CONTROLLER_API_TYPE_SUFFIX)
		{			
			throw new SkException($file_path .'规则属性未定义', 10005);		
		}		
	}

	/**
	 * utf16 转换 utf8
	 */	
	protected function utf16_to_entities($content){
		$content = mb_convert_encoding($content, 'utf-16');
		$bin = bin2hex($content);
		$arr = str_split($bin, 4);
		$l = count($arr);
		$str = '';
		for ($n = 0; $n < $l; $n++) {
			if (isset($arr[$n + 1]) && ('0x' . $arr[$n] >= 0xd800 && '0x' . $arr[$n] <= 0xdbff && '0x' . $arr[$n + 1] >= 0xdc00 && '0x' . $arr[$n + 1] <= 0xdfff)) {
				$H = '0x' . $arr[$n];
				$L = '0x' . $arr[$n + 1];
				$code = ($H - 0xD800) * 0x400 + 0x10000 + $L - 0xDC00;
				$str.= '&#' . $code . ';';
				$n++;
			} else {
				$str.=mb_convert_encoding(hex2bin($arr[$n]),'utf-8','utf-16');
			}
		}
		return $str;
	}

	// 1 当前模块
	public function loadGlobal($name = Enum::GLOBAL_FILE_DIR, $mod = null)
	{
		$file_path = MODULE_PATH . '/' . $this->module_name . '/';
		if(isset($mod))
		{
			if($mod == 1){
				$file_path .= $this->dir_controller_name . '/';
			}else{
				$file_path .= $mod . '/';
			}
		}
		$file_path .= 'global/' . $name . '.php';
		//echo $file_path;
		if(!is_file($file_path))
		{
			throw new SkException('载入文件不存在', 10011);
		}
		include($file_path);		
	}	

	// 1 当前模块
	public function loadClass($name, $mod = null)
	{
		$file_path = MODULE_PATH . '/' . $this->module_name . '/';
		if(isset($mod))
		{
			if($mod == 1){
				$file_path .= $this->dir_controller_name . '/';
			}else{
				$file_path .= $mod . '/';
			}
		}
		$file_path .= 'class/' . $name . '.php';
		if(! is_file($file_path))
		{
			throw new SkException('载入文件不存在', 10011);
		}
		include($file_path);	
	}

	/**
	 *  获取Page
	 */
	protected function getPageCount($data_count)
	{
		$page = $this->requestInt($this->page_key, 1);
		$this->page_count = ceil($data_count / $this->page_size);
		if ($page > $this->page_count) 
		{
			$page = $this->page_count;	
		}
		if($page < 1) $page = 1;
		$this->page = $page;
	}


	/**
	 *  获取Limit
	 */
	protected function getPageLimit()
	{
		$index = $this->page * $this->page_size - $this->page_size;
		$index = ($index < 0 ? 0 : $index);
		return array($index, $this->page_size);
	}		
}