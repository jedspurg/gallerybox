<?php   defined('C5_EXECUTE') or die("Access Denied."); ?> 
<?php  

$s1 = $this->controller->getUserSets();
$form = Loader::helper('form');
$html = Loader::helper('html');

if (count($s1) > 0) { ?>

<div id="ccm-search-advanced-sets">
	<div>
	<table border="0" cellspacing="0" cellpadding="0" id="ccm-file-search-advanced-sets-header">
	<tr>
		<td width="100%"><h5><?php  echo t('Sets')?></h5></td>
		<td>

		
		</td>
	</tr>
	</table>
	</div>
	
		
	<div class="ccm-file-search-advanced-sets-results">
	<ul id="ccm-file-search-advanced-sets-list">
	<?php   foreach($s1 as $fs) { 
		$pfs = new Permissions($fs);
		$u = new User();
		?>
        
        <?php  if($fs->getFileSetName() != 'user_gallery_'.$u->getUserID() && $fs->getFileSetName() !='Blog'){?>
		<li class="ccm-<?php  echo $searchInstance?>-search-advanced-sets-cb">
		<div class="ccm-file-search-advanced-set-controls">
<?php  if ($fs->getFileSetName() != 'user_gallery_'.$u->getUserID()){?>
			<a href="<?php  echo View::url('/dashboard/files/sets', 'view_detail', $fs->getFileSetID())?>"><?php  echo $html->image('icons/wrench.png')?></a>
           <?php  }?>
            
			<?php   if ($pfs->canDeleteFileSet()) { 
			$gbxdt = Loader::helper('concrete/urls')->getToolsURL('delete_user_set', 'gallerybox');?>

				<a href="<?php  echo $gbxdt?>?fsID=<?php  echo $fs->getFileSetID()?>&searchInstance=<?php  echo $searchInstance?>" class="ccm-file-set-delete-window" dialog-title="<?php  echo t('Delete File Set')?>" dialog-width="320" dialog-height="200" dialog-modal="false"><?php  echo $html->image('icons/delete_small.png')?></a>
			<?php   } ?>
		</div>
		<?php  echo $form->checkbox('fsID[' . $fs->getFileSetID() . ']', $fs->getFileSetID(), (is_array($searchRequest['fsID']) && in_array($fs->getFileSetID(), $searchRequest['fsID'])))?> <?php  echo $form->label('fsID[' . $fs->getFileSetID() . ']', $fs->getFileSetName())?></li>
	<?php   
		} 
	}
	?>
	</ul>
	</div>

	
	
</div>

	<script type="text/javascript">
	$(function() {
		$('a.ccm-file-set-delete-window').dialog();
	});	
	</script>
<?php   } ?>