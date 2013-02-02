<?php    defined('C5_EXECUTE') or die("Access Denied."); 
	$pkt = Loader::helper('concrete/urls');
	$pkg= Package::getByHandle('gallerybox');
?>

<div id="gbx-user-stream">		
            <div class="gbx-stream-title"><?php   echo $title?></div>
            
        <div class="clearfix"></div>
 
         		
            <div class="gbx-wall-gall">
                        
                        <?php  
						$i=0; 
						$ih=Loader::helper('image');
						if(is_array($images)){
						foreach($images as $img){
						$i++;
						$fv = $img->getApprovedVersion();
						$imgt = $fv->getTitle();
						
						$imgThumb = $ih->getThumbnail($img,180,180);
						$imgH = $imgThumb->height;
						$imgW = $imgThumb->width;
						$topOffset = (69 - $imgH)/2;
						$leftOffset = (69 - $imgW)/2;
							?>
                        <div><a rel="twipsy" title="<?php   echo str_replace('"',"'",$imgt)?>" href="<?php    echo View::url('/gallerybox/image',$img->getFileID())?>"><div><img src="<?php   echo $imgThumb->src?>" style="margin-top:<?php   echo intval($topOffset)?>px;margin-left:<?php   echo intval($leftOffset)?>px"/></div></a></div>    
                     <?php   }?>   

                      
                   </div> 
<div class="clearfix"></div>
	<?php   if($moreLink == '1'){?>
  	<a href="<?php   echo $gallLink?>" class="btn"><?php   echo $buttonText?><span class="icon rightarrow"></span></a>
  <?php   }
						}?>   
                  
        </div>
    
<div class="clearfix"></div>