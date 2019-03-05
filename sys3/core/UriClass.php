


/**
 *  URL
 */
function set_url_query($key, $val)
{
	$url = cur_page_url();
	if(strpos($url, '?') !== false)
	{	
		$url = parse_url($url);
		$qstr = parse_str($url['query'], $q); 
		$q[$key] = $val;
		$query =  http_build_query($q);
	}else{
		$query = "$key=$val";
	}	
	return $query;
}

function reset_url_query($key)
{
	$url_str = cur_page_url();
	$url = parse_url($url_str);
	if(isset($url['query']))
	{	
		parse_str($url['query'], $q); 
		unset($q[$key]);
		if(count($q) > 0)
		{
			$query =  http_build_query($q);
			$url_str = $url['path'] . '?' . $query;
		}else{
			$url_str = $url['path'];
		}
	}
	return $url_str;
}


/**
 *  URL过期
 */
function url_time($url)
{
	if(strpos($url, '?') !== false)
	{	
		$url = parse_url($url);
		$qstr = parse_str($url['query'], $q); 
		$q['exp'] = time();
		$url = $url['scheme'] . '://' . $url['host'] . $url['path'] . '?' . http_build_query($q);
	}else{
		$url .= "?exp=" . time();
	}	
	return $url;
}

