<?php
include 'Base.php';
class SkGetController extends Base
{
	public $run_redirect = false;

	public function redirect($url = null)
	{
		$this->run_redirect = true;
		if(! isset($url))
		{
			$url = $_SERVER['HTTP_REFERER'];
		}
		redirect($url);
	}

	public function __after()
	{
		$this->run_redirect || $this->redirect();
	}
}