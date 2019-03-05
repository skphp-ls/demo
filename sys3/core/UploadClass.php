<?php
/**
 * UploadClass v2.0.0
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2015-11-25
 */
class Upload extends BaseStatic{}
class UploadClass
{
	private $_conf = null;
	private $_ret_data = array();
	private $_up_dir = null;
	private $_up_type = null;
	private $_up_size = null;

	public function __construct()
	{
		$this->_conf = Sk::$Conf->getData('upload');
	}


	// 获取文件 $key 表单、 $type 允许类型, $dir 项目 , up_szie kB
	public function getFile($key, $up_dir, $fname = null, $up_type = null)
	{
		$this->_up_type = $up_type;
		$this->_up_dir = $up_dir;
		$file = $_FILES[$key];
		$size = $file['size'];
		if (is_array($size)) 
		{
			for ($i=0; $i < count($size); $i++)
			{ 
				$this->_moveFile($file, $fname, $i);
			}
		}else{
			$this->_moveFile($file, $fname);
		}
		return $this->_ret_data;
	}

	// 移动文件
	private function _moveFile($file, $fname, $idx = null)
	{
		if (isset($idx)) 
		{
			$size = $file['size'][$idx];
			$errno = $file['error'][$idx];
			$type = $file['type'][$idx];
			$name = $file['name'][$idx];
			$tmp_name = $file['tmp_name'][$idx];
		}else{
			$idx = 0;
			$size = $file['size'];
			$errno = $file['error'];
			$type = $file['type'];
			$name = $file['name'];
			$tmp_name = $file['tmp_name'];
		}
		// 类型检测
		if($this->_isImgType($tmp_name) == false)
		{
			$this->_ret_data[$idx]['errno'] =  11;			
			return false;
		}		
		if (! $this->_isSize($size)) 
		{
			$this->_ret_data[$idx]['errno'] =  10;
			return false;
		}
		if ($errno > 0) 
		{
			$this->_ret_data[$idx]['errno'] =  5;	
			return false;		
		}
		if (! is_uploaded_file($tmp_name))
		{
			$this->_ret_data[$idx]['errno'] =  12;
			return false;
		}
		$extension = pathinfo($name, PATHINFO_EXTENSION);
		$up_path = $this->_conf['up_path'];
		$file_path = $this->_conf['up_dir'] . '/' . $this->_up_dir;
		if ($this->_conf['isday']) 
		{
			$file_path .= '/' . date('Ymd');
		}
		$path = $up_path . $file_path;
		if(! is_dir($path))
		{
			make_full_path($path);	
		}
		if (empty($fname))
		{
			$fname = '/' . date('YmdHis') . rand(1000, 9999) . ".$extension";
		}else{
			$fname = '/' . $fname . ".$extension";
		}
		$new_fname = $path . $fname;
		$result = move_uploaded_file($tmp_name , $new_fname);
		if ($result) 
		{
			$this->_ret_data[$idx]['errno'] =  0;
			$this->_ret_data[$idx]['file'] = $this->_conf['root_up_path'] . $file_path . $fname;
			return true;
		}
		$this->_ret_data[$idx]['errno'] =  13;
		return false;
	}

	// 类型判断
	private function _isImgType($fn)
	{
		$file=fopen($fn, "rb"); 
		$bin=fread($file, 15); 		//只读2字节 
		fclose($file); 
		$hex = unpack("H*", $bin);
		$hex = substr($hex[1], 0, 8);
		//$tbin = substr($bin, 0, intval($blen)); ///需要比较文件头长度
		//strtolower($v[0])==strtolower(array_shift(unpack("H*", $tbin)));
		//if(strtolower($v[0])==strtolower(array_shift(unpack("H*",$tbin))))
		return in_array($hex, $this->_conf['up_allow_bin']);
	}

	// 大小判断
	private function _isSize($size)
	{
		if(isset($this->_up_szie))
		{
			if ($size > ($this->_up_szie * 1024)) 
			{
				return false;
			}
		}else{
			if ($size > ($this->_conf['up_size'] * 1024)) 
			{
				return false;
			}
		}
		return true;
	}
}