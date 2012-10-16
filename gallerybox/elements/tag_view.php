<?php   defined('C5_EXECUTE') or die("Access Denied.");?> 
<script type="text/javascript">
	$(function () {
		$("a[rel=twipsy]").twipsy({
		live: true
		});
	});
</script>
<div id="gbx-main-gallery">
 <div id="gbx-page-name"><h3>Images tagged with <em><?php  echo $tagword?></em></h3></div><div id="gallery-search"><form id="GallerySearchForm" method="post" action="<?php  echo $this->action('search')?>"><input type="text" name="keywords" id="keywords" value=""/><input type="submit" value="Search" class="btn primary"/></form></div>

    <div class="clearbox"></div>
    	<div id="gallery-inner">
<?php  
	print $taggedImgList;
?>

 <?php
			$summary = $paging->getSummary();
			if ($summary->pages > 1):
			$paginator = $paging->getPagination();
			
			?>
      <div class="pagination">
        <ul>
          <li class="prev<?php if($paginator->getCurrentPage() == $paginator->getPreviousInt()){?> disabled<?php }?>"><a href="<?php if($paginator->getCurrentPage() == $paginator->getPreviousInt()){?>javascript:void(0);<?php }else{?>?ccm_paging_p=<?php echo $paginator->getPreviousInt()+1;}?>">&larr; Previous</a></li>
          
          <?php  $totNumPages = $paginator->getTotalPages();
								 if($totNumPages > 9){$numPages = 9;}else{$numPages = $totNumPages;}
                for ($i = 1; $i <= $numPages; $i++) {?>
                
										<?php if(($paginator->getCurrentPage()+1) == $i){?>
                      <li class="active">
                      <a href="?ccm_paging_p=<?php echo $i;?>"><?php echo $i;?></a>
                      </li>
                    
                    <?php }else{?>
                      <li>
                      <a href="?ccm_paging_p=<?php echo $i;?>"><?php echo $i;?></a>
                      </li>
                      <?php }?>
                <?php }?>
                <?php if($numPages==9){?>
                 <li>
                      <a href="javascript:void(0);">...</a>
                 </li>
                 <li>
                       <a href="?ccm_paging_p=<?php echo $totNumPages;?>"><?php echo $totNumPages;?></a>
                 </li>
                <?php }?>
               
          <li class="next<?php if(($paginator->getCurrentPage()) == $paginator->getNextInt()){?> disabled<?php }?>"><a href="<?php if($paginator->getCurrentPage() == $paginator->getNextInt()){?>javascript:void(0);<?php }else{?>?ccm_paging_p=<?php echo $paginator->getNextInt()+1;}?>">Next &rarr;</a></li>
        </ul>
      </div>
			<?php  endif;  ?> 
      
</div>
</div>
<div class="clearbox"></div>