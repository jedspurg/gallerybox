<?php   defined('C5_EXECUTE') or die("Access Denied."); 
	$av = Loader::helper('concrete/avatar');
	$pkt = Loader::helper('concrete/urls');
	$pkg= Package::getByHandle('gallerybox');
	$nextDisabled = false;
	$prevDisabled = false;
	$commenter = new User();
	$cui = UserInfo::getByID($commenter->getUserID());
	$notes = $this->controller->getNotes($imgID);
	$f = File::getByID($imgID);
	$dUrl = $f->getDownloadURL();
	if($f->getUserID() == $commenter->getUserID() || $commenter->isSuperUser()){
		$editor = true;
	}
	$notesVar = 'notes = [';	
		for($i = 0;$i < count($notes);$i++){
			$notesVar.= $notes[$i]['notes'];
				if ($i < (count($notes) - 1)){
					$notesVar.= ',';
			 }
		 }
	$notesVar.='];';
	 ?>
	<script type="text/javascript">
		var isEditor = false;
        <?php echo $notesVar;?>
		  $(function () {		
			$("a.zoomImage").fancyZoom(
			{scaleImg: true, closeOnClick: true, directory:"<?php    echo $pkt->getPackageURL($pkg).'/images';?>"});	
		});
		
		<?php if($editor){?>
		isEditor = true;
		<?php }?>
	</script>
    
<div class="row">    
<div id="gbx-sidebar" class="span3">
        <div class="gbx-header">

        <?php  
            $f = File::getByID($imgID);
            $fui = UserInfo::getByID($f->getUserID());
		?>
        <?php 
        if ($fui->getAttribute('first_name') == ''){
					$username =  $fui->getUserName();
					}else{
					$username = $fui->getAttribute('first_name').' '.$fui->getAttribute('last_name');
					}
    	?>
        <h5><small>by </small><?php  echo $username?></h5>
            <div class="clearfix">
            <?php echo $av->outputUserAvatar($fui,false,0.65)?>
            
           
            <a href="<?php  echo View::url('/profile',$fui->getUserID())?>" class="btn small pull-right u-button"><strong>Profile</strong><span class="icon user"></span></a>
            <a href="<?php  echo View::url('/gallerybox/user',$fui->getUserID())?>" class="btn small pull-right u-button"><strong>Gallery</strong><span class="icon book"></span></a>
            </div>
            <?php if($editor){?>
            <a href="<?php  echo $this->url('/profile/images?fKeywords='.$f->getFileName())?>" id="edit" class="btn primary editor"><strong>Edit image</strong><span class="icon pen"></span></a>
            <?php }else{?>
            <div class="clearfix"></div>
            <?php }?>
        </div>
		<div class="clearfix"></div>
        <div id="gbx-set-list">
          <h5><?php echo t('Image in sets')?><span class="icon home"></span></h5>
          <div class="set-list-box">
              <?php  $this->controller->getDisplayedSets($imgID)?>
          </div>

        </div>
        <div class="clearfix"></div>
        <div id="gbx-user-stream">
       	
		<h5><?php  echo t('Recent images')?><span class="icon book"></span></h5>
         		
                        <div class="user-gall">
                        
                        <?php
						$i=0; 
						$ih=Loader::helper('image');
						foreach($galleryImages as $img){
						$i++;
						$fv = $img->getApprovedVersion();
						$imgt = $fv->getTitle();
						
						$imgThumb = $ih->getThumbnail($img,180,180);
						$imgH = $imgThumb->height;
						$imgW = $imgThumb->width;
						$topOffset = (69 - $imgH)/2;
						$leftOffset = (69 - $imgW)/2;
							?>
                        <div><a rel="twipsy" title="<?php echo str_replace('"',"'",$imgt)?>" href="<?php  echo View::url('/gallerybox/image',$img->getFileID())?>"><div><img src="<?php echo $imgThumb->src?>" style="margin-top:<?php echo intval($topOffset)?>px;margin-left:<?php echo intval($leftOffset)?>px"/></div></a></div>    
                     <?php }?>   
                     <?php if(count($galleryImages) < 6){
						 for($n = $i+1;$n <= 6; $n++){?>
                     
                      <div><a  href="javascript:void(0);"><img src="<?php echo $pkt->getPackageURL($pkg);?>/images/grid_image.jpg"/></a></div>       
                     	 <?php }?>
                      <?php }?>
                       <a href="<?php  echo View::url('/gallerybox/user',$fui->getUserID())?>" class="btn">View more<span class="icon rightarrow"></span></a>
                   </div> 
                  
        </div>
		<div class="clearfix"></div>
        <div id="gbx-tags">
            <div class="tag-list-box">
            <h5><?php echo t('Tags')?><span class="icon tag"></span></h5>
            <?php  $this->controller->getImageTags($imgID);?>
            </div>
        </div>
        <div class="clearfix"></div>
         <div id="gbx-rss">
            <div class="clearfix">
            <a href="<?php  echo View::url('/gbxrss',$fui->getUserID())?>" class="btn">Subscribe<span class="icon rss"></span></a>

            </div>
        </div>
       
</div>
    
    
    
    <div id="gbx-content-pane" class="span<?php echo Config::get(GBX_GALLERY_INNER_CLASS)?>">
		<div id="gbx-image-view">
        	<div class="clearfix">
            <?php 
			$prev = $this->controller->getPrev(File::getByID($imgID)->getUserID(), $imgID);
			$next = $this->controller->getNext(File::getByID($imgID)->getUserID(), $imgID);
			if($next==''){$nextlink='javascript:void(0);';$nextDisabled=true;}else{$nextlink = $this->url('/gallerybox/image',$next);}
			if($prev==''){$prevlink='javascript:void(0);';$prevDisabled=true;}else{$prevlink = $this->url('/gallerybox/image',$prev);}
			?>
            <a href="<?php  echo $prevlink?>" rel="twipsy" title="previous image" class="btn<?php if($prevDisabled){?> disabled<?php }?> pull-left"><span class="icon leftarrow"></span>Prev</a>
            
            <a class="zoomImage btn pull-left" href="#zoomImage" id="zoomimg" rel="twipsy" title="zoom image">Zoom<span class="icon magnifier"></span></a>
            <?php if(Config::get(GBX_ALLOW_NOTES)==1){?><a href="#" id="addnotelink" rel="twipsy" title="add a note" class="btn pull-left">Add note<span class="icon pen"></span></a><?php }?>
            
			<?php if(Config::get(GBX_ALLOW_EMBED)==1){?><a href="#" data-controls-modal="embed-modal" rel="twipsy" title="get HTML embed code" data-backdrop="true" class="btn pull-left">Embed<span class="icon cog"></span></a><?php }?>
            
      		<?php if(Config::get(GBX_ALLOW_DOWNLOAD)==1){?><a href="<?php echo $dUrl?>" rel="twipsy" title="download full size" class="btn pull-left">Download<span class="icon downarrow"></span></a><?php }?>

            <a href="<?php echo $nextlink?>" id="previmg" rel="twipsy" title="next image" class="btn pull-right<?php if($nextDisabled){?> disabled<?php }?>">Next<span class="icon rightarrow"></span></a>
            </div>
                    
                    
                    
					<div id="gbx-img-wrapper">

                        
                        <?php  $this->controller->getUserImage($imgID);?>

                    
	
					</div>
                    
                    <?php if(Config::get(GBX_ALLOW_COMMENTS)==1){?>
                    <div id="gbx-comments">
                     
                    <h4>Comments and Favorites</h4>
                    
                    <div id="gbx-favs">
                    <?php  Loader::packageElement('fav_bar', 'gallerybox', array('favfID' => $imgID, 'favu' => $favu));?>
                    </div>
                    
                    
                    
                    <?php  
					$comments = $this->controller->getComments($imgID);
					$date = Loader::helper('date');
					 for($i = 0;$i < count($comments);$i++){
						$comui = UserInfo::getByID($comments[$i]['uID']);?>
                        
						
                        <div class="row gbx-comment">
                        <div class="comment-user-img span1 pull-left"><?php echo $av->outputUserAvatar($comui,false,0.5)?></div>
                        <div class="span<?php echo (intval(Config::get(GBX_GALLERY_INNER_CLASS))-1)?> pull-right">
                        
						<?php
                        if ($comui->getAttribute('first_name') == ''){
							$commname =  $comui->getUserName();
						}else{
							$commname = $comui->getAttribute('first_name').' '.$comui->getAttribute('last_name');
						}
						?>
						<a href="<?php echo View::url('/profile',$comments[$i]['uID'])?>"><strong><?php echo $commname?></strong></a>
		
						<span class="date">(<?php echo $date->timeSince(strtotime($comments[$i]['entryDate']))?> ago)</span>
                        <?php
						if($editor){
						?>
                        <a id="<?php echo $comments[$i]['commentID']?>" data-controls-modal="delete-comment-modal" data-backdrop="static" class="close commentIDcopy" data-placement="right" rel="twipsy" title="delete comment">×</a>
							 
                        <?php }?>
						<p><?php echo $comments[$i]['commentText']?></p>
						</div></div>
					<?php }?>
    
                    <?php  if($commenter->isRegistered()){?>
                    <div class="row gbx-comment-form">
                        <div class="comment-user-img span1 pull-left"><?php  echo  $av->outputUserAvatar($cui,false,0.5)?></div>
                        
                        <div class="span<?php echo (intval(Config::get(GBX_GALLERY_INNER_CLASS))-1)?> pull-right">
                        <form id="CommentAddForm" method="post" action="<?php  echo $this->action('add_comment').$imgID?>">
                        <input name="comUID" type="hidden" value="<?php  echo $commenter->getUserID()?>" id="comUID" />
                        <textarea name="imgComment" id="imgComment" />add your comment here...</textarea>
                        <div class="clearfix">
                        <input type="submit" value="Post Comment" class="btn primary pull-right"/>
                        </div>
                        </form>
                        </div>
                    </div>
                    <?php  }else{?>
                    <div class="gbx-comment-form"><p class="gray">You must <a href="<?php  echo $this->url('/login')?>">login</a> to post comments and add favorites</p></div>
                    
                    <?php  }?>

            		</div>
                 </div>
                 
                 <?php }?>
                 
                 <?php if(Config::get(GBX_ALLOW_NOTES)==1){?>
                 
                 <?php  if($commenter->isRegistered()){?>
                    <div id="noteform" >
                        <form id="NoteAddForm" method="post" action="<?php  echo $this->action('add_note').$imgID?>">	

                            <input name="data[Note][x1]" type="hidden" value="" id="NoteX1" />
                            <input name="data[Note][y1]" type="hidden" value="" id="NoteY1" />
                            <input name="data[Note][height]" type="hidden" value="" id="NoteHeight" />
                            <input name="data[Note][width]" type="hidden" value="" id="NoteWidth" />
                            <textarea name="data[Note][note]" id="NoteNote" /></textarea>

                            <div class="clearfix">
                            <input type="submit" value="Submit" class="btn primary pull-right"/> <input type="button" value="Cancel" id="cancelnote" class="btn pull-right pad-right"/>
                            </div>
                         </form>
					</div>
                    <?php  }else{?>
                    <div id="noteform" ><p class="gray">You must <a href="<?php  echo $this->url('/login')?>">login</a> to write notes<div class="form-button"><input type="button" value="Cancel" id="cancelnote" class="btn pull-right pad-right"/></div></p></div>
                    <?php  }?>
                    <?php  }?>
                    

                        

        </div>
</div>
        
        <div id="delete-note-modal" class="modal hide" style="display: none; ">
            <div class="modal-header">
              <a href="#" class="close">×</a>
              <h3>Delete Note</h3>
            </div>
            
            <div class="modal-body">
            <form id="CommentDeleteForm" method="post" action="<?php  echo $this->action('delete_note', $imgID)?>">
              <p>Are you sure that you want to delete this image note?
              <span class="help-block">This action cannot be undone.</span>
              </p>
              <input name="noteID" id="noteID" type="hidden" value=""/>
            </div>
            <div class="modal-footer">
              <input type="submit" value="Delete" class="btn danger"/>
              <a href="#" class="btn secondary close-this">Cancel</a>
              </form>
            </div>
          </div>
          
          <div id="delete-comment-modal" class="modal hide" style="display: none; ">
            <div class="modal-header">
              <a href="#" class="close">×</a>
              <h3>Delete Comment</h3>
            </div>
            <div class="modal-body">
             <form id="CommentDeleteForm" method="post" action="<?php  echo $this->action('delete_comment', $imgID)?>">
              <p>Are you sure that you want to delete this comment?
              <span class="help-block">This action cannot be undone.</span>
              </p>
              <input name="comID" id="comID" type="hidden" value=""/>
            </div>
            <div class="modal-footer">
              <input type="submit" value="Delete" class="btn danger"/>
              <a href="#" class="btn secondary close-this">Cancel</a>
               </form>
            </div>
            
            <div id="embed-modal" class="modal hide" style="display: none; ">
            <div class="modal-header">
              <a href="#" class="close">×</a>
              <h3>Embed Code</h3>
            </div>
            <div class="modal-body">
            <?php  $this->controller->getEmbedCode($imgID);?>
            </div>
            
           
          </div>