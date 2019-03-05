<?php
/**
 * App, AppRun, AppRoute, AppServer v2.4.4
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class App
{	
	public $module_name					=		null;
	public $controller_name				=		null;
	public $rule_controller_name		=		null;
	public $dir_controller_name			=		null;
	public $dir_child_controller_name   =		null;	
	public $method_name					=		null;
	public $params						=		null;
	public $controller_type 			=		null;
	// XMLHttpRequest 
	public $post_ajax					=		null;	
	// Content-Type	
	public $post_allow_type 			=		null;

	// 设置模块结构目录
	protected function ModuleData()
	{
		$path = MODULE_PATH . '/' . $this->module_name;
		return array(
			'root'			=>			$path,
			'class' 		=>			$path . '/class',
			'global'		=>			$path . '/global',
			'controller'	=>			$path . '/controller',
			'model'			=>			$path . '/model',
			'tempates'		=>			$path . '/templates'
		);
	}

	// 设置控制器结构目录
	protected function CtlData()
	{
		$path = MODULE_PATH . '/' . $this->module_name . '/' . $this->dir_controller_name;
		return array(
			'root'			=>			$path,
			'class' 		=>			$path .'/class',
			'global'		=>			$path .'/global',
			'controller'	=>			$path .'/controller',
			'rule'			=>			$path .'/rule',
			'tempates'		=>			$path .'/templates'
		);
	}
}

class AppRun extends App
{
	/**
	 * 启动
	 */
	public function run()
	{
		$this->_initConfing();
		$this->_initDefValue();
		$this->_initGlobal();
		$this->_initController();		
	}

	/**
	 *  载入系统类
	 */
	private function _initConfing()
	{
		$sk = new Sk();
		$sk->Conf($this->ConfigData(), 'app');
		$sk->Log();
	}

	/**
	 *  默认配置载入
	 */
	private function _initDefValue()
	{
		$this->module_name = Sk::$Conf->DEFAULT_MODULE;
		$this->controller_name = Sk::$Conf->DEFAULT_CONTROLLER;	
		$this->method_name 	= Sk::$Conf->DEFAULT_METHOD;
		$this->_initSrvParameter();
		$ctl_name = $this->controller_name;
		$suffix = strrchr($ctl_name, '_');
		//echo $suffix;
		$suffix = trim($suffix, '_');
		switch ($suffix) {
			case strtolower(Enum::CONTROLLER_API_TYPE_SUFFIX):
				include(SYSTEM_LIBS . '/SkAjaxController.php');
				$this->controller_type = Enum::CONTROLLER_API_TYPE_SUFFIX;	
				$this->rule_controller_name  = str_ireplace('_' . $this->controller_type, '', $ctl_name);
				break;
			case strtolower(Enum::CONTROLLER_AJAX_TYPE_SUFFIX):
				include(SYSTEM_LIBS . '/SkAjaxController.php');
				$this->controller_type = Enum::CONTROLLER_AJAX_TYPE_SUFFIX;	
				$this->rule_controller_name  = str_ireplace('_' . $this->controller_type, '', $ctl_name);
				break;					
			case strtolower(Enum::CONTROLLER_GET_TYPE_SUFFIX):
				include(SYSTEM_LIBS . '/SkGetController.php');
				$this->controller_type = Enum::CONTROLLER_GET_TYPE_SUFFIX;	
				$this->rule_controller_name  = str_ireplace('_' . $this->controller_type, '', $ctl_name);
				break;
			case strtolower(Enum::CONTROLLER_VIEW_TYPE_SUFFIX):
				include(SYSTEM_LIBS . '/SkViewController.php');
				$this->controller_type = Enum::CONTROLLER_VIEW_TYPE_SUFFIX;	
				$this->rule_controller_name  = str_ireplace('_' . $this->controller_type, '', $ctl_name);
				break;				
			default:
				include(SYSTEM_LIBS . '/SkViewController.php');
				$this->controller_type = Enum::CONTROLLER_VIEW_TYPE_SUFFIX;	
				$this->rule_controller_name  = $ctl_name;
				break;
		}
		$this->dir_controller_name = $this->_getModuleName($this->controller_name);	
		$this->dir_child_controller_name = $this->_getChilControllerName($this->rule_controller_name);	
		//print_r($this);
		Sk::$Path = new stdClass();
		Sk::$Path->module = $this->ModuleData();
		Sk::$Path->ctl = $this->CtlData();
	}

	/**
	 *  默认全局函数
	 */
	private function _initGlobal()
	{
		// 全局自定义类
		spl_autoload_register('Sk::AutoLoad');
		// 全局文件
		$file_path = Sk::$Path->module['global'] . '/'. Enum::GLOBAL_FILE_NAME;
		if(is_file($file_path))
		{
			include($file_path);
		}	
		$file_path = Sk::$Path->ctl['global'] . '/'. Enum::GLOBAL_FILE_NAME;
		if(is_file($file_path))
		{
			include($file_path);
		}	
		// 模块类库
		$in_dirlist = array('.');
		if(Sk::$Conf->AUTO_MOD_CLASS)
		{
			$in_dirlist[] = Sk::$Path->module['class'];
		}
		if(Sk::$Conf->AUTO_CTL_CLASS)
		{
			$in_dirlist[] =  Sk::$Path->ctl['class'];
		}
   		set_include_path(join(PATH_SEPARATOR, $in_dirlist));  
   		//print_r(get_include_path());		
	}

	/**
	 * 实例化控制器
	 */
	private function _initController()
	{
		// 控制器
		$controller_name = uc_words($this->controller_name) . Enum::CONTROLLER_NAME_SUFFIX;
		$file_path = Sk::$Path->ctl['controller'];
		//echo ($this->dir_child_controller_name);
		if(isset($this->dir_child_controller_name))
		{
			$file_path .= '/' . $this->dir_child_controller_name;
		}
		$file_path .= '/'. $controller_name . '.php';
		//echo $file_path;
		if(! is_file($file_path))
		{
			throw new SkException($file_path . '控制器不存在', 10002);
		}
		include($file_path);
		$cls = new $controller_name;
		// 参数传递
		$cls->params = $this->params;
		$cls->module_name = $this->module_name;
		$cls->dir_controller_name = $this->dir_controller_name;
		$cls->rule_controller_name = $this->rule_controller_name;
		$cls->dir_child_controller_name = $this->dir_child_controller_name;
		$cls->controller_type = $this->controller_type;
		$cls->method_name = $this->method_name;
		// 加载控制
		if (method_exists($cls, '__before')) 
		{
			$cls->__before();
		}	
		$clt_method_name = lcfirst(uc_words($this->method_name));		
		if (method_exists($cls, $clt_method_name)) 
		{
			$cls->$clt_method_name();
		}else{
			throw new SkException('方法不存在', 80000);
		}
		if (method_exists($cls, '__after')) 
		{
			$cls->__after();
		}
	}

	/**
	 * 获取子模块
	 */
	private function _getChilControllerName($ctl_name)
	{
		if(stripos($ctl_name, '_') !== false)
		{
			$ctl_name = strrchr($ctl_name, '_');
			$ctl_name = trim($ctl_name, '_');
		}
		return $ctl_name;
	}

	/**
	 * 获取模块名称
	 */
	private function _getModuleName($ctl_name)
	{
		if(stripos($ctl_name, '_') !== false)
		{
			$ctl_name = stristr($ctl_name, '_', true);
		}
		return $ctl_name;
	}

	/**
	 *  参数
	 */
	private function _initSrvParameter()
	{
		$app_srv = new AppServer;
		$this->post_ajax = $app_srv->isAjaxPost();
		$this->post_allow_type = $app_srv->isAllowPost();
		$result = false;
		if (Sk::$Conf->AUTO_ROUTE) 
		{
			$result = $this->_initRoute();
		}
		if ($result == false) 
		{
			$this->_initUri();
		}		
	}

	/**
	 *  路由控制器初始化
	 */
	private function _initRoute()
	{
		$app_route = new AppRoute;
		$app_controller = $app_route->getRouteItem();
		if ($app_controller == false) 
		{
			return false;
		}
		$params = $app_route->getRouteParam();
		$this->params = $params == false ? null : $params;
		$this->controller_name = $app_controller[0];
		$this->method_name = $app_controller[1];
		if (isset($app_controller[2])) 
		{
			$this->controller_name = $app_controller[1];
			$this->method_name = $app_controller[2];			
			$this->module_name = $app_controller[0];
		}
		return true;
	}

	/**
	 *  URI初始化
	 */
	private function _initUri()
	{
		$app_server = new AppServer;
		$query_uri = $app_server->getQueryUri();

		if ($query_uri == '/') 
		{
			return true;					
		}
		$uri_section = substr_count($query_uri, '/');
		$uri_section = explode('/', $query_uri);
		if (isset($uri_section[3])) 
		{
			$this->module_name = $uri_section[1];
			$this->controller_name = $uri_section[2];
			$this->method_name = $uri_section[3];		
			return true;
		}
		if (isset($uri_section[2])) 
		{
			$this->controller_name = $uri_section[1];
			$this->method_name = $uri_section[2];	
			return true;
		}
		if (isset($uri_section[1]))
		{
			$this->method_name = $uri_section[1];
			return true;
		}
	}
}


class AppRoute extends AppServer
{
	private $_route_key	= null;
	private $_query_uri = null;
	/**
	 *  获取路由参数
	 */
	public function getRouteParam()
	{
		if (! isset($this->_route_key)) 
		{
			return false;
		}
		if (strpos($this->_route_key, '/:') === false) 
		{
			return false;
		}
		$route_key_prefix = strstr($this->_route_key, '/:', true);
		$route_key_suffix = str_replace($route_key_prefix, '', $this->_route_key);
		$query_uri_suffix = str_replace($route_key_prefix, '', $this->_query_uri);

		if (! isset($route_key_suffix{0})) 
		{
			return false;
		}
		$param_key = explode('/:', $route_key_suffix);
		$param_val = explode('/', $query_uri_suffix);
		$param_array = array_combine($param_key, $param_val);
		array_shift($param_array);

		return $param_array;
	}	

	/**
	 *  获取路由指定项
	 */
	public function getRouteItem()
	{
		$route_conf = Sk::$Conf->getData('route');
		$route_row_key = $this->getPostMethod();
		$query_uri = $this->_query_uri = $this->getQueryUri();

		// 获取类型对应路由KEY
		if(isset($route_conf['*']))
		{
			$route_key_list = array_keys($route_conf['*']);
		}
		if(isset($route_conf[$route_row_key]))
		{
			$route_key_list = array_merge($route_key, array_keys($route_conf[$route_row_key]));
		}

		$uri_section = substr_count($query_uri, '/');
		$route_key = $this->_route_key = $this->_getRouteKey($uri_section, $query_uri, $route_key_list);		
		
		if ($route_key == false) 
		{
			return false;
		}
		if (isset($route_conf[$route_row_key][$route_key])) 
		{
			return $route_conf[$route_row_key][$route_key];
		}

		return $route_conf['*'][$route_key];
	}

	/**
	 * 获取路由指定项KEY
	 */
	private function _getRouteKey($uri_section, $query_uri, $route_key_list)
	{

		$route_level = Sk::$Conf->ROUTE_LEVEL;

		if ($uri_section <= $route_level) 
		{
			if (! in_array($query_uri, $route_key_list)) 
			{
				return false;
			}

			return $query_uri;
		}

		$uri_group = explode('/', $query_uri);
		$route_key_prefix = array_slice($uri_group, 0, $route_level + 1);
		$route_key_prefix = join('/', $route_key_prefix);
		$route_key_rule = preg_quote ( $route_key_prefix ,  '/' );
		$rule = '/^' .  $route_key_rule . '(\/\:[\w_]+){' . ($uri_section - $route_level)  . '}$/';
		$route_key = preg_grep($rule, $route_key_list);		
		
		if (count($route_key) == 0) 
		{
			return false;
		}

		return current($route_key);
	}
}

class AppServer 
{
	/**
	 * 获取路URI
	 */
	public function getQueryUri()
	{

		if(isset($_SERVER['PATH_INFO'])){
			return $_SERVER['PATH_INFO'];
		}
		$context_prefix = isset($_SERVER['CONTEXT_PREFIX']) ? $_SERVER['CONTEXT_PREFIX'] : '';
		$request_uri = $_SERVER['REQUEST_URI'];
		$script_name = $_SERVER['SCRIPT_NAME'];
		$query_string = $_SERVER['QUERY_STRING'];
		$uri= basename($script_name);
		$uri = str_replace($uri, '', $request_uri);
		$uri = substr($uri, strlen($context_prefix));
	
		if(isset($query_string{0}))
		{
			$uri = str_replace('?' . $query_string, '', $uri);
		}
		$uri = $this->_checkUri(strtolower($uri));
		
		return $uri;
	}

	/**
	 *  获取提交方式
	 */
	public function getPostMethod()
	{
		return isset($_SERVER['REQUEST_METHOD']) ? strtolower($_SERVER['REQUEST_METHOD']) : 'get';
	}

	/**
	 *  识别ajax提交
	 *  nginx fastcgi_params.default 
	 *  fastcgi_param HTTP_X_REQUESTED_WITH $http_x_requested_with; 
	 */
	public function isAjaxPost()
	{
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) 
			? $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' : false ;		
	}	

	/**
	 *  识别Api提交
	 */
	public function isAllowPost()
	{
		$mime_type = '';
		if(function_exists('getallheaders'))
		{
			$header = getallheaders();
			if(isset($header['Content-Type']))
			{
				$mime_type = $header['Content-Type'];
			}else{
				return false;
			}
		}else{
			if(isset($_SERVER['HTTP_CONTENT_TYPE']))
			{
				$mime_type = $_SERVER['HTTP_CONTENT_TYPE'];
			}else{
				return false;
			}

		}
		if (strpos($mime_type, ';') !== false) {
			$mime_type = strstr($mime_type, ';', true);
		}

		$form_type = Sk::$Conf->FORM_ALLOW_TYPE;
		if (in_array($mime_type, $form_type)) 
		{
			return false;
		}
		return true;
	}

	/**
	 * 规范URI
	 */
	private function _checkUri($query_uri)
	{
	 
		$query_uri = str_replace('\\', '/', $query_uri);  

		if(stripos($query_uri, '//') !== false){

			$query_uri = str_replace('//', '/', $query_uri);
			$this->_checkUri($query_uri);

		}else{

			return $query_uri;
		}
	}		
}

