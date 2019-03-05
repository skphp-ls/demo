<?php
class Image
{
	/*
	缩略图
	*/
	public static function toThumbnail( $OldImagePath, $NewImagePath, $NewWidth=154, $NewHeight=134)
	{
		// 取出原图，获得图形信息getimagesize参数说明：0(宽),1(高),2(1gif/2jpg/3png),3(width="638" height="340")
		$OldImageInfo = getimagesize($OldImagePath);
		if ( $OldImageInfo[2] == 1 ) $OldImg = @imagecreatefromgif($OldImagePath);
		elseif ( $OldImageInfo[2] == 2 ) $OldImg = @imagecreatefromjpeg($OldImagePath);
		else $OldImg = @imagecreatefrompng($OldImagePath);

		// 创建图形,imagecreate参数说明：宽,高
		$NewImg = imagecreatetruecolor( $NewWidth, $NewHeight );

		//创建色彩,参数：图形,red(0-255),green(0-255),blue(0-255)
		$black = ImageColorAllocate( $NewImg, 0, 0, 0 ); //黑色
		$white = ImageColorAllocate( $NewImg, 255, 255, 255 ); //白色
		$red   = ImageColorAllocate( $NewImg, 255, 0, 0 ); //红色
		$blue  = ImageColorAllocate( $NewImg, 0, 0, 255 ); //蓝色
		$other = ImageColorAllocate( $NewImg, 0, 255, 0 );

		//新图形高宽处理
		$WriteNewWidth = $NewHeight*($OldImageInfo[0] / $OldImageInfo[1]); //要写入的高度
		$WriteNewHeight = $NewWidth*($OldImageInfo[1] / $OldImageInfo[0]); //要写入的宽度
		
		//这样处理图片比例会失调，但可以填满背景
		if (($OldImageInfo[0] / $NewWidth) > ($org_info[1] / $NewHeight))
		{
			$WriteNewWidth  = $NewWidth;
			$WriteNewHeight  = $NewWidth / ($OldImageInfo[0] / $OldImageInfo[1]);
		}else{
			$WriteNewWidth  = $NewHeight * ($OldImageInfo[0] / $OldImageInfo[1]);
			$WriteNewHeight = $NewHeight;
		}
		//以$NewHeight为基础,如果新宽小于或等于$NewWidth,则成立
		if ( $WriteNewWidth <= $NewWidth ) {
			$WriteNewWidth = $WriteNewWidth; //用判断后的大小
			$WriteNewHeight = $NewHeight; //用规定的大小
			$WriteX = floor( ($NewWidth-$WriteNewWidth) / 2 ); //在新图片上写入的X位置计算
			$WriteY = 0;
		} else {
			$WriteNewWidth = $NewWidth; // 用规定的大小
			$WriteNewHeight = $WriteNewHeight; //用判断后的大小
			$WriteX = 0;
			$WriteY = floor( ($NewHeight-$WriteNewHeight) / 2 ); //在新图片上写入的X位置计算
		}

		//旧图形缩小后,写入到新图形上(复制),imagecopyresized参数说明：新旧, 新xy旧xy, 新宽高旧宽高
		@imageCopyreSampled( $NewImg, $OldImg, $WriteX, $WriteY, 0, 0, $WriteNewWidth, $WriteNewHeight, $OldImageInfo[0], $OldImageInfo[1] );

		//保存文件
	//    @imagegif( $NewImg, $NewImagePath );
		@imagejpeg($NewImg, $NewImagePath, 100);
		//结束图形
		@imagedestroy($NewImg);
	}


	/**
	 * 等比缩放
	 * 生成缩略图函数（支持图片格式：gif、jpeg、png和bmp）
	 * @author ruxing.li
	 * @param  string $src      源图片路径
	 * @param  int    $width    缩略图宽度（只指定高度时进行等比缩放）
	 * @param  int    $width    缩略图高度（只指定宽度时进行等比缩放）
	 * @param  string $filename 保存路径（不指定时直接输出到浏览器）
	 * @return bool
	 */
	public static function mkThumbnail($src, $filename = null, $width = null, $height = null) {
		if (!isset($width) && !isset($height))
			return false;
		if (isset($width) && $width <= 0)
			return false;
		if (isset($height) && $height <= 0)
			return false;

		$size = getimagesize($src);
		if (!$size)
			return false;

		list($src_w, $src_h, $src_type) = $size;
		$src_mime = $size['mime'];
		switch($src_type) {
			case 1 :
				$img_type = 'gif';
				break;
			case 2 :
				$img_type = 'jpeg';
				break;
			case 3 :
				$img_type = 'png';
				break;
			case 15 :
				$img_type = 'wbmp';
				break;
			default :
				return false;
		}

		if (!isset($width))
			$width = $src_w * ($height / $src_h);
		if (!isset($height))
			$height = $src_h * ($width / $src_w);

		$imagecreatefunc = 'imagecreatefrom' . $img_type;
		$src_img = $imagecreatefunc($src);
		$dest_img = imagecreatetruecolor($width, $height);
		imagecopyresampled($dest_img, $src_img, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

		$imagefunc = 'image' . $img_type;
		if ($filename) {
			$imagefunc($dest_img, $filename, 100);
		} else {
			header('Content-Type: ' . $src_mime);
			$imagefunc($dest_img);
		}
		imagedestroy($src_img);
		imagedestroy($dest_img);
		return true;
	}	

	/**
		* 无损裁剪图片
	*/
	public static function resizeImage($src,$name,$maxwidth,$maxheight)
	{
		$im=imagecreatefromjpeg($src);
		$pic_width = imagesx($im);
		$pic_height = imagesy($im);
		if(($maxwidth && $pic_width > $maxwidth) || ($maxheight && $pic_height > $maxheight))
		{
			if($maxwidth && $pic_width>$maxwidth)
			{
				$widthratio = $maxwidth/$pic_width;
				$resizewidth_tag = true;
			}
			if($maxheight && $pic_height>$maxheight)
			{
				$heightratio = $maxheight/$pic_height;
				$resizeheight_tag = true;
			}
			if($resizewidth_tag && $resizeheight_tag)
			{
				if($widthratio<$heightratio)
				$ratio = $widthratio;
				else
				$ratio = $heightratio;
			}
			if($resizewidth_tag && !$resizeheight_tag) $ratio = $widthratio;
			if($resizeheight_tag && !$resizewidth_tag) $ratio = $heightratio;
			$newwidth = $pic_width * $ratio;
			$newheight = $pic_height * $ratio;
			if(function_exists("imagecopyresampled"))
			{
				$newim = imagecreatetruecolor($newwidth,$newheight);//PHP系统函数
				imagecopyresampled($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);//PHP系统函数
			}else
			{
				$newim = imagecreate($newwidth,$newheight);
				imagecopyresized($newim,$im,0,0,0,0,$newwidth,$newheight,$pic_width,$pic_height);
			}
			imagejpeg($newim,$name,100);
			imagedestroy($newim);
		}else{
			imagejpeg($im,$name,100);
		}
	}
}