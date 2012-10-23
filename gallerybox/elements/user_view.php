<?php     defined('C5_EXECUTE') or die("Access Denied."); 
	$av = Loader::helper('concrete/avatar');
	$pkt = Loader::helper('concrete/urls');
	$pkg= Package::getByHandle('gallerybox');
	?> 
<div class="row">    
    <div id="gbx-sidebar" class="span3">
    
        <div class="gbx-header">
                <?php   
        if ($user->getAttribute('first_name') == ''){
					$username =  $user->getUserName();
					}else{
					$username = $user->getAttribute('first_name').' '.$user->getAttribute('last_name');
					}
    	?>
        <h5><small>by </small><?php    echo $username?></h5>
            <div class="clearfix">
            <?php   echo $av->outputUserAvatar($user,false,0.65)?>
            <a href="<?php    echo View::url('/profile',$user->getUserID())?>" class="btn small pull-right u-button"><strong><?php   echo t('Profile')?></strong><span class="icon user"></span></a>
            <a href="<?php    echo View::url('/gallerybox/user',$user->getUserID())?>" class="btn small pull-right u-button"><strong><?php   echo t('Gallery')?></strong><span class="icon book"></span></a>
            </div>
            <div class="clearfix"></div>

        </div>
		
        <div class="clearfix"></div>
        <div id="gbx-set-list">
 		  <h5><?php   echo t('My image sets')?><span class="icon home"></span></h5>
          <div class="set-list-box">
              <?php    $this->controller->getUserSets($user->getUserID())?>
          </div>
        </div>
        
		<div class="clearfix"></div>
        <div id="gbx-tags">
        	<div class="tag-list-box">
            	<h5><?php   echo t('My Tags')?><span class="icon tag"></span></h5>
            	<?php    $this->controller->getUserTags($user->getUserID())?>
            </div>
        </div>
        
        <?php   if(Config::get(GBX_ALLOW_RSS)==1){?>
         <div class="clearfix"></div>    
         <div id="gbx-rss">
            <div class="clearfix">
            <a href="<?php    echo View::url('/gbxrss',$user->getUserID())?>" class="btn" target="_blank"><?php   echo t('Subscribe')?><span class="icon rss"></span></a>
            </div>
        </div>
        <?php   }?>
           
    </div>
    
    
    
    <div id="gbx-content-pane" class="span<?php   echo Config::get(GBX_GALLERY_INNER_CLASS)?>">
      <div id="gbx-image-view">
            <div class="clearfix">
      
                  <?php    
									print $userFilesDisplay;
									
									?>   
                  
                   <?php  
			$summary = $userPaging->getSummary();
			if ($summary->pages > 1):
			$paginator = $userPaging->getPagination();
			
			?>
      <div class="pagination">
        <ul>
          <li class="prev<?php   if($paginator->getCurrentPage() == $paginator->getPreviousInt()){?> disabled<?php   }?>"><a href="<?php   if($paginator->getCurrentPage() == $paginator->getPreviousInt()){?>javascript:void(0);<?php   }else{?>?ccm_paging_p=<?php   echo $paginator->getPreviousInt()+1;}?>">&larr; <?php   echo t('Previous')?></a></li>
          
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
               
          <li class="next<?php   if(($paginator->getCurrentPage()) == $paginator->getNextInt()){?> disabled<?php   }?>"><a href="<?php   if($paginator->getCurrentPage() == $paginator->getNextInt()){?>javascript:void(0);<?php   }else{?>?ccm_paging_p=<?php   echo $paginator->getNextInt()+1;}?>"><?php   echo t('Next')?> &rarr;</a></li>
        </ul>
      </div>
			<?php    endif;  ?> 

          </div>
      </div>
    </div>  
            
           
</div>
          
              
