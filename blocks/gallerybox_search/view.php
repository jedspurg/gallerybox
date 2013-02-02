<?php    defined('C5_EXECUTE') or die("Access Denied."); 
	
?>

<div class="gbx-search-box">
<?php   if(strlen($title) > 0){?>
	<h3><?php   echo $title?></h3>
<?php   }?>
<form id="GallerySearchForm_<?php   echo $bID?>" method="post" action="<?php    echo $this->url('/gallerybox/search')?>"><input type="text" name="keywords" id="keywords" value=""/> <button type="submit" class="btn primary"><?php   if (strlen($buttonText) >0){echo $buttonText;}else{echo t('Search');}?></button></form>

<div class="clearfix"></div>		
</div>