<?php
  $ih =Loader::helper('image');
  $fv = $img->getApprovedVersion();
  $imgt = $fv->getTitle();
  $imgd = $fv->getDescription();
  $imgThumb = $ih->getThumbnail($img,300,300);
  $imgH = $imgThumb->height;
  $imgW = $imgThumb->width;
  $topOffset = (112 - $imgH)/2;
  $leftOffset = (112 - $imgW)/2;
  $imgui = UserInfo::getByID($img->getUserID());
  if(!is_object($imgui)){
    $imgui = UserInfo::getByID(1);
  }
  if ($imgui->getAttribute('first_name') == ''){
    $username =  $imgui->getUserName();
  }else{
    $username = $imgui->getAttribute('first_name').' '.$imgui->getAttribute('last_name');
  }
  $title = substr(htmlspecialchars($imgt),0,24);
  if (strlen($imgt) > 24){
    $title .= '...';
  }
?>
<div class="gbx-gallery-item">
  <div class="gbximgwrapper">
    <a href="<?php echo View::url('/gallerybox/image',$img->getFileID())?>" rel="overpop" data-placement="below" data-content="by <?php echo $username?>: <?php echo htmlspecialchars(str_replace('"',"'",$imgd))?>" title="<?php echo $title?>">
      <div>
        <img src="<?php echo $imgThumb->src?>" width="<?php echo $imgThumb->width?>" height="<?php echo $imgThumb->height?>" title="<?php echo str_replace('"',"'",$imgt)?>" style="margin-top:<?php echo intval($topOffset)?>px;margin-left:<?php echo intval($leftOffset)?>px"/>
      </div>
    </a>
  </div>
</div>

