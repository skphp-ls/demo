<?php
include 'Base.php';
/**
 * TemplateClass v3.0.1
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class SkTemplate extends Base
{
	private $_vars = null;
	private $_conf = null;
	public function __construct()
	{
		$this->_conf = Sk::$Conf->getData('tpl');
		$this->_vars['static'] = $this->_conf['server_url'] . '/' . $this->_conf['server_name'];
		$this->_vars['gbl_static'] = $this->_conf['server_url'] . '/' . Enum::GLOBAL_FILE_DIR;
		// 载入global
		$gfile = Sk::$Path->module['tempates'] . '/' . Enum::GLOBAL_FILE_DIR . '/' . Enum::GLOBAL_FILE_NAME;
		//echo $gfile;
		if(is_file($gfile)) include_once($gfile);	
		// 载入global
		$gfile = Sk::$Path->ctl['tempates'] . '/' . Enum::GLOBAL_FILE_DIR . '/' . Enum::GLOBAL_FILE_NAME;
		//echo $gfile;
		if(is_file($gfile)) include_once($gfile);	

	}

	// befor
	public function __before()
	{
		$this->_vars['ajax_url'] = '/' . $this->module_name . '/' . $this->rule_controller_name . '_ajax/' . $this->method_name;
	}

	/**
	 * 赋值给模板
	 */
	public function assign($key, $val)
	{
		$this->_vars[$key] = $val;
	}

	/**
	 * 获取模块内容
	 */
	public function fetch($tpl_name, $gbl = false)
	{		
		$conf = $this->_conf;
		if($gbl)
		{	
			$tpl_file = Sk::$Path->module['tempates'] . $conf['tpl_foler'] . '/' . $tpl_name . $conf['tpl_extension'];
		}else{
			// 规则
			$arr_rule = $this->loadRule($tpl_name);
			// print_r($arr_rule);
			if($arr_rule['rule'])
			{
				$this->_vars['arr_rule'] = $arr_rule['rule'];
			}
			$tpl_file = Sk::$Path->ctl['tempates'] . $conf['tpl_foler'] . '/' . $this->dir_child_controller_name . '/' . $tpl_name . $conf['tpl_extension'];
		}
		return $this->_getObContent($tpl_file);
	}

	/**
	 * 输出显示模板
	 */
	public function display($tpl_name, $gbl = false)
	{	
		echo($this->fetch($tpl_name, $gbl));
	}

	/**
	 * 输出缓存
	 */
	private function _getObContent($tpl_file)
	{
		if (is_file($tpl_file))
		{
			extract($this->_vars);
			ob_start();
			include($tpl_file);
			$content = ob_get_contents();
			ob_end_clean();
			return $content;
		}else{
			throw new SkException($tpl_file . '模板不存在', 80001);
		}
	}
}

/**
 * SkViewController
 */
class SkViewController extends SkTemplate
{
	public $run_layout = false,  $run_view = false;

	public function __construct()
	{
		parent::__construct();			
	}

	/**
	 *  分页
	 */
	public function getPagination()
	{
		if ($this->page_count == 0) 
		{
			return '';
		}
		$li = '<li><a href="{#page_url#}?{#page#}={#num#}{#param#}">{#num#}</a></li>';
		$active_li = '<li class="active"><a href="javascript:;">{#num#}<span class="sr-only">(current)</span></a></li>';
		$pre_li = '<li><a href="{#page_url#}?{#page#}={#pre#}{#param#}" aria-label="Previous"><span aria-hidden="true">«</span></a></li>';
		$next_li = '<li><a href="{#page_url#}?{#page#}={#next#}{#param#}" aria-label="Next"><span aria-hidden="true">»</span></a></li>';
		$param_str = '';
		if(isset($this->page_params))
		{	
			foreach ($this->page_params as $key => $val) 
			{
				$param_str .= '&' . $key . '=' . $val;
			}
			//unset($this->page_params);
			//print_r($params);
		}

		$pagination = '';

		if($this->page > 1)
		{
			$pagination .= $pre_li;
		}		

		if($this->page_count <= $this->page_num)
		{
			for ($i=1; $i<=$this->page_count; $i++) 
			{ 
				if($i == $this->page)
				{
					$pagination .= str_replace('{#num#}', $i, $active_li);
				}else{
					$pagination .= str_replace('{#num#}', $i, $li);	
				}
			}
		}else{
			$start = $this->page - $this->page_num;
			$end = $this->page + $this->page_num;
			if($start < 1)
			{
				$end += abs($start)+1;
				$start = 1;
			}			
			if($end > $this->page_count)
			{
				$end = $this->page_count;
			}				
			for ($i=$start; $i<=$end; $i++) 
			{ 
				if($i == $this->page)
				{
					$pagination .= str_replace('{#num#}', $i, $active_li);
				}else{
					$pagination .= str_replace('{#num#}', $i, $li);	
				}
			}			
		}

		if($this->page < $this->page_count)
		{
			$pagination .= $next_li;
		}
		$pagination = str_replace('{#page#}', $this->page_key, $pagination);
		$pagination = str_replace('{#pre#}', ($this->page-1), $pagination);
		$pagination = str_replace('{#next#}', ($this->page+1), $pagination);
		$pagination = str_replace('{#param#}', $param_str, $pagination);
		$pagination = str_replace('{#page_url#}', $this->page_url, $pagination);		
		$pagination = "<ul class='pagination'>$pagination</ul>";
		return $pagination;
	}

	/*
	 * JS分页
	 */
	public function getJsPagination()
	{
		$pagination = $this->getPagination();
		$pagination = str_replace('"?', '"#?', $pagination);
		return $pagination;
	}


	/**
	 * 解析模版
	 */
	protected function view($tpl_name = null)
	{
		$tpl_name = $rule_name = isset($tpl_name) ? $tpl_name : $this->method_name;
		$this->assign('tpl_name', $tpl_name);	
		$this->display($tpl_name);
	}

    /**
     * 解析模版
     */
    protected function layout($layout = 'layout')
    { 
    	$this->run_layout = true;
		$content = $this->fetch($this->method_name);
        $this->assign('layout_content', $content);
        $this->display($layout, true);
    }
}

class AfterViewController extends SkViewController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function __after()
    {
        $this->run_view || $this->view();
    }
}


class AfterLayoutController extends SkViewController
{
	public function __construct()
	{
		parent::__construct();
	}

	public function __after()
	{
		$this->run_layout || $this->layout();
	}
}