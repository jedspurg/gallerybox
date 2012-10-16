<?php   
	defined('C5_EXECUTE') or die("Access Denied.");
	$av = Loader::helper('concrete/avatar');
	$fs = FileSet::getByID($fsID);
	?> 

<script type="text/javascript">
	$(function () {		
		$("a[rel=popover]").popover({
		live: true,
		placement: 'below'
		});
	});
</script>
<div id="gbx-main-gallery span13">
    <div id="gbx-set-wrapper">
    <?php 
	if ($setOwner->getAttribute('first_name') == ''){
				$username =  $setOwner->getUserName();
			}else{
				$username = $setOwner->getAttribute('first_name').' '.$setOwner->getAttribute('last_name');
			}
	?>
    <div class="set-avatar"><?php  echo  $av->outputUserAvatar($setOwner,false,0.45)?></div>
    <div class="set-info"><h3><?php  echo $fs->getFileSetName()?></h3>by <a href="<?php  echo View::url('/profile',$setOwner->getUserID())?>"><?php  echo $username?></a> &#8226; <a href="<?php  echo View::url('/gallerybox/user',$setOwner->getUserID())?>">Gallery</a></div>
    <div class="clearfix"></div>
    <?php  
        print $setDisplay;

    ?>
     
    </div>
    <div class="clearfix"></div>
</div>
<div class="clearfix"></div>