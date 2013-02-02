<?php    
defined('C5_EXECUTE') or die("Access Denied.");

class WatermarkHelper {
			
		
public function watermark($cacheSrc){
		
		$dir = DIR_FILES_CACHE.'/';
		$reldir = REL_DIR_FILES_CACHE.'/';
		$watermarkImg = Config::get('GBX_WATERMARK_IMG');
	
	
		if($watermarkImg > 0){
			$f = File::getByID(intval($watermarkImg));
			$fv = $f->getApprovedVersion();
			$watermark = imagecreatefrompng($fv->getPath());
		}else{
			$watermark = imagecreatefrompng(DIR_PACKAGES.'/gallerybox/images/watermark.png');	
		}
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$image = imagecreatetruecolor($watermark_width, $watermark_height);
		
		$srcInfo = pathinfo($cacheSrc);
		$srcFileName =  basename($cacheSrc,'.'.$srcInfo['extension']);
		$source = $dir.$srcFileName.'.'.$srcInfo['extension'];
		
		if($srcInfo['extension'] == strtolower('jpg') or $srcInfo['extension'] == strtolower('jpeg')){
			$image = imagecreatefromjpeg($source);
		}
		if($srcInfo['extension'] == strtolower('gif')){
			$image = imagecreatefromgif($source);
		}
		if($srcInfo['extension'] == strtolower('png')){
			$image = imagecreatefrompng($source);
		}
		$size = getimagesize($source);
		
	
		// Merge watermark with the original image
		

	

		imagecopymerge($image, $watermark, ($size[0]-$watermark_width)/2, ($size[1]-$watermark_height)/2, 0, 0, $watermark_width, $watermark_height, 50);
		
		
		$info = pathinfo($source);
		$file_name =  basename($source,'.'.$info['extension']);
		
		imagejpeg($image, $dir.$file_name.'-stamped.'.$info['extension']);
		imagedestroy($image);
		imagedestroy($watermark);
		
		@chmod($dir.$file_name.'-stamped.'.$info['extension'], 0644);
		
		return $reldir.$file_name.'-stamped.'.$info['extension'];
		
		}
}

