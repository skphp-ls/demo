<?php
/**
 * CaptchaClass v2.0.3
 * 
 * @auth ls
 * @mail:56160681@qq.com 
 * @date:2014-11-25
 */
class Captcha extends BaseStatic{}
class CaptchaClass
{

	private $_im;


	/**
	 * 验证码比较
	 */
	public function isCompareCaptcha($captcha, $captcha_key = Enum::SITE_CAPTCHA_KEY)
	{
		session_start();
		if(isset($_SESSION[$captcha_key])){
			if(strtolower($captcha) == strtolower($_SESSION[$captcha_key]))
			{
				return true;
			}			
		}
		return false;
	}

	/**
	 * 输出一个验证码图片
	 * @parem $config array(
	 *                   'font_size'=>文字大小（默认14）,
	 *                   'img_height'=>图片高度（默认24）,
	 *                   'img_width'=>图片宽度（默认68）,
	 *                   'use_boder'=>使用边框（默认true）,
	 *                 );
	 */
	public function getCaptchaImage( $config = array() )
	{
		$font = array('Monofur', 'ggbi', 'simhei', 'Yahei');
		shuffle($font);
		$font_file 	 = SYSTEM_PATH . '/data/fonts/ggbi.ttf';
		//echo $font_file;
		$font_size   = isset($config['font_size']) ? $config['font_size'] : 18;
		$img_height  = isset($config['img_height']) ? $config['img_height'] : 36;
		$img_width   = isset($config['img_width']) ? $config['img_width'] : 180;
		$use_boder   = isset($config['use_boder']) ? $config['use_boder'] : true;
		$side_word   = isset($config['side_word']) ? $config['side_word'] : '-';
		$side_word_index  = isset($config['side_word_index']) ? $config['side_word_index'] : 25;

		//创建图片，并设置背景色
		$this->_im = imagecreate($img_width, $img_height);
		ImageColorAllocate($this->_im, 255,255,255);

		//文字随机颜色
		$red_color  = ImageColorAllocate($this->_im, 255, 0, 0);
		$grey_color  = ImageColorAllocate($this->_im, 190, 190, 190);
		$black_color = ImageColorAllocate($this->_im, 0, 0, 0);

		//获取随机字符
		$rndstring_1  = str_repeat($side_word, 9);
		$rndstring_2  = $this->rndAlpha();
		$rndcode_len = strlen($rndstring_1);

		//背景横线
		for($j=3; $j<=$img_height-3; $j=$j+9)
		{
			 imageline($this->_im, 2, $j, $img_width - 2, $j, $grey_color);
		}
		
		//背景竖线
		for($j=2;$j<$img_width;$j=$j+10)
		{
			imageline($this->_im, $j, 0, $j+8, $img_height, $grey_color);
		}

		//画边框
		if( $use_boder)
		{
			imagerectangle($this->_im, 0, 0, $img_width-1, $img_height-1, $grey_color);
		}

		// 位置信息
		$rnd_index = range(0, 8);
		shuffle($rnd_index);
		$rnd_index = array_slice($rnd_index, 0, 4);

		//输出文字
		$str = '';
		for($i=0;$i<$rndcode_len;$i++)
		{
			$x = $i == 0 ? 10 : $i*($font_size+2);
			$y = 29;
			$c = mt_rand(0, 10);
			$current_color = $black_color;
			if (in_array($i, $rnd_index)) 
			{	
				$str .= $rndstring_2{$i};       	
				$current_color = $red_color;
			}
			if (function_exists('imagettftext')) 
			{				
				imagettftext($this->_im, $font_size, $c, $x, $y, $black_color, $font_file, $rndstring_2{$i});
				imagettftext($this->_im, $font_size, 0, $x, $side_word_index, $current_color, $font_file, $rndstring_1{$i});
			} 
		}
		$this->setSession($str);
		$this->showImage();
	}

	/**
	 * 设置session
	 */
	private function setSession($code)
	{
		session_start();
		$_SESSION[Enum::SITE_CAPTCHA_KEY] = strtolower($code);
	}

	/**
	 * 输出
	 *
	 * @return [type] [description]
	 */
	private function showImage()
	{
		header("Pragma:no-cache\r\n");
		header("Cache-Control:no-cache\r\n");
		header("Expires:0\r\n");
		if(function_exists("imagejpeg")) {
			header("content-type:image/jpeg\r\n");
			imagejpeg($this->_im);
		} else {
			header("content-type:image/png\r\n");
			imagepng($this->_im);
		}
		imagedestroy($this->_im);
		exit;		
	}

	/**
	 * 随机数字
	 */
	private function rndNumber($num = 9)
	{
		$range = range(1, 9);
		shuffle($range);
		$rnd = array_slice($range, 0, $num);
		return join('', $rnd);
	}

	/**
	 * 随机字母
	 */
	private function rndAlpha($num = 9)
	{
		$range = range('A', 'Z');
		shuffle($range);
		$rnd = array_slice($range, 0, $num);
		 return join('', $rnd);
	}

}

