<?php
/**
 * ArrayClass v2.0.1
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class ArrData
{

	/**
	 *  给二维数组设置键值索引
	 */
	public static function indexData($key, $arr_data, $prefix = null)
	{
		$new_arr_data = array();
		foreach ($arr_data as $val) 
		{
			$new_arr_data[$prefix . $val[$key]] = $val;
		}
		return $new_arr_data;
	}

	/**
	 *  给二维数组设置键值索引并格式化值
	 */
	public static function indexVal($arr_data, $field, $key = null)
	{
		$new_arr_data = array();
		foreach ($arr_data as $val) 
		{
			if (isset($key)) {
				$new_arr_data[$val[$key]] = $val[$field];
			}else{
				$new_arr_data[] = $val[$field];
			}
		}
		return $new_arr_data;		
	}


	/**
	 *  给二维数组设置键值索引分组
	 */
	public static function indexGroup($key, $arr_data)
	{
		$new_arr_data = array();
		$tmp_arr_data = array();
		foreach ($arr_data as $val) 
		{
			$tmp_arr_data[$val[$key]] = $val[$key];
		}
		foreach ($arr_data as $val) 
		{
			$keyv = $val[$key];
			if($tmp_arr_data[$keyv] == $keyv)
			{
				$new_arr_data[$keyv][] = $val;
			}
		}		
		return $new_arr_data;
	}

}