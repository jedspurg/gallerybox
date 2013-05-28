<?php     
	defined('C5_EXECUTE') or die("Access Denied.");

?> 
<script type="text/javascript">
	$(function () {
		$("a[rel=overpop]").popover({
		live: true,
		placement: 'below'
		});
	});
</script>

<div id="gallery-search"><form id="GallerySearchForm" method="post" action="<?php    echo $this->action('search')?>"><input type="text" name="keywords" id="keywords" value=""/><button type="submit" class="btn primary"><?php   echo t('Search')?></button></form></div>
<?php if(count($files) > 0){  ?>
    <div class="clearbox"></div>
    	<div class="span<?php   echo (intval(Config::get(GBX_GALLERY_INNER_CLASS))+3)?>">
			<?php
        foreach($files as $img):
          Loader::packageElement('gallery_image', 'gallerybox', array('img' => $img));
        endforeach;
      ?>
      <div class="clearfix"></div>
      
      <?php  
			$summary = $paging->getSummary();
      if ($summary->pages > 1):
      $paginator = $paging->getPagination();
			?>
      <div class="pagination">
        <ul>
          <li class="prev<?php   if($paginator->getCurrentPage() == $paginator->getPreviousInt()){?> disabled<?php   }?>"><a href="<?php   if($paginator->getCurrentPage() == $paginator->getPreviousInt()){?>javascript:void(0);<?php   }else{?>?ccm_paging_p=<?php   echo $paginator->getPreviousInt()+1;}?>">&larr; <?php   t('Previous')?></a></li>
          
          <?php    $totNumPages = $paginator->getTotalPages();
								 if($totNumPages > 9){$numPages = 9;}else{$numPages = $totNumPages;}
                for ($i = 1; $i <= $numPages; $i++) {?>
                
										<?php   if(($paginator->getCurrentPage()+1) == $i){?>
                      <li class="active">
                      <a href="?ccm_paging_p=<?php   echo $i;?>"><?php   echo $i;?></a>
                      </li>
                    
                    <?php   }else{?>
                      <li>
                      <a href="?ccm_paging_p=<?php   echo $i;?>"><?php   echo $i;?></a>
                      </li>
                      <?php   }?>
                <?php   }?>
                <?php   if($numPages==9){?>
                 <li>
                      <a href="javascript:void(0);">...</a>
                 </li>
                 <li>
                       <a href="?ccm_paging_p=<?php   echo $totNumPages;?>"><?php   echo $totNumPages;?></a>
                 </li>
                <?php   }?>
               
          <li class="next<?php   if(($paginator->getCurrentPage()) == $paginator->getNextInt()){?> disabled<?php   }?>"><a href="<?php   if($paginator->getCurrentPage() == $paginator->getNextInt()){?>javascript:void(0);<?php   }else{?>?ccm_paging_p=<?php   echo $paginator->getNextInt()+1;}?>"><?php   t('Next')?> &rarr;</a></li>
        </ul>
      </div>
			<?php    endif;  ?> 
      
        </div>

<?php }?>
<div class="clearbox"></div>