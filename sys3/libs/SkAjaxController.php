<?php
/**
 * SkAjaxController v2.0.0
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2015-04-22
 */
include 'Base.php';
class SkAjaxController extends Base
{	
	public $result = false, $run_result = false;
	public $arr_rule = null;
	private $_db_transaction = false;

	// befor
	public function __before()
	{
		$this->arr_rule = $this->loadRule($this->method_name);	
		$this->checkItemRule();	
	}

	// 开启事务
	protected function dbTransaction()
	{
		$this->_db_transaction = true;
		if(! Sk::$Db)
		{
			$sk = new Sk;
			$sk->Db();
		}		
		Sk::$Db->begin();
	}

	// 完成事务
	protected function dbFinish()
	{
		if($this->_db_transaction)
		{
			if($this->result)
			{
				Sk::$Db->commit();
			}else{
				Sk::$Db->rollBack();
			}			
		}
	}


	// 检测项
	protected function checkItemRule()
	{
		$arr_rule = $this->arr_rule['rule'];
		$valid_class = Sk::$Conf->CHECK_RULE_CLASS;
		if($arr_rule)
		{
			foreach ($arr_rule as $key => $rule) 
			{
				$fun = $rule['rule'];
				$msg = $rule['msg'];
				$method = $fun[0];
				$param = array_slice($fun, 1);
				//print_r($key);
				array_unshift($param, $_REQUEST[$key]);
				//print_r($param);
				$this->result = call_user_func_array(array($valid_class, $method), $param);
				if ($this->result == false) 
				{
					$this->echoJson($msg); 
					break;
				}
			}				
		}
	}	

	protected function echoJson($msg)
	{
		$result = array('ret' => false, 'msg' => $msg);
		encode_json($result);
	}

	protected function echoResult($data = null, $msg = null)
	{
		$this->run_result = true;
		$json = $this->arr_rule['json'];
		$result = $this->result ? $json['ok'] : $json['fail'];
		if($msg) $result['msg'] = $msg;
		if($data) $result['data'] = $data;
		encode_json($result);
	}
}


// After
class AfterAjaxController extends SkAjaxController
{
    public function __after()
    {
        $this->run_result || $this->echoResult();
    }		
}

/**
 * SkPostController
 */
class SkPostController extends SkAjaxController
{
	/**
	 * 生成签名
	 * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
	 */
	public function makeSign($arr_data = null)
	{
		
		$data = $_GET;
		if(isset($arr_data))
		{
			 $data = array_merge($data, $arr_data);
		}
		//签名步骤一：按字典序排序参数
		ksort($data);
		$string = "";
		foreach ($data as $k => $v)
		{
			if($k != "sign"){
				$string .= $k . "=" . $v . "&";
			}
		}
		$string = trim($string, "&");
		//签名步骤二：在string后加入KEY
		$dstr = $string = $string . '&key=' . conf('gbl', 'cookiekey');
		//echo $string;
		//签名步骤三：MD5加密
		$string = strtoupper(md5($string));
		$sign = strtoupper($_GET['sign']); 
		//echo "$string != $sign";
		if($string != $sign)
		{
			$this->echoJson("$dstr-{$string}!={$sign}签名错误");
		}
	}
}

// After
class AfterPostController extends SkPostController
{
    public function __after()
    {
        $this->run_result || $this->echoResult();
    }		
}
