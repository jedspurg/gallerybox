<?php   defined('C5_EXECUTE') or die("Access Denied."); ?> 
<?php   $form = Loader::helper('form'); ?>
<?php 
// ***** LERTECO_WALL
$posting_type = array('gallerybox', 'set_added', 'SetAdded', 'created a new gallery set: %1$s', 1, 2);

$u = new User();

function checkbox($field, $value, $state, $miscFields = array()) {

	$mf = '';
	if (is_array($miscFields)) {
		foreach($miscFields as $k => $v) {
			$mf .= $k . '="' . $v . '" ';
		}
	}

	$src = ASSETS_URL_IMAGES . '/checkbox_state_' . $state . '.png';
					
	$str = '<a href="javascript:void(0)" ccm-tri-state-startup="' . $state . '" ccm-tri-state-selected="' . $state . '" ><input type="hidden" value="' . $state . '" name="' . $field . ':' . $value . '" /> <img width="16" height="16" src="' . $src . '" ' . $mf . ' /></a>';
	return $str;
}

Loader::model('file_set');
$cnt = Loader::controller('/profile/images');
$s1 = $cnt->getUserSets();

$files = array();
$searchInstance = $_REQUEST['searchInstance'];
$extensions = array();

if (is_array($_REQUEST['fID'])) {
	foreach($_REQUEST['fID'] as $fID) {
		$f = File::getByID($fID);
		$fp = new Permissions($f);
		if ($fp->canRead()) {
			$files[] = $f;
			$extensions[] = strtolower($f->getExtension());
		}
	}
} else {
	$f = File::getByID($_REQUEST['fID']);
	$fp = new Permissions($f);
	if ($fp->canRead()) {
		$files[] = $f;
		$extensions[] = strtolower($f->getExtension());
	}
}

$extensions = array_unique($extensions);
$sets = array();
// tri state checkbox
// state 0 - none of the selected files are in the set
// state 1 - SOME of the selected files are in the set
// state 2 - ALL files are in the set

foreach($s1 as $fs) {
	
	$foundInSets = 0;

	foreach($files as $f) {
		if ($f->inFileSet($fs)) {
			$foundInSets++;
		}
	}

	if ($foundInSets == 0) {
		$state = 0;
	} else if ($foundInSets == count($files)) {
		$state = 2;
	} else {
		$state = 1;
	}
	
	$fs->state = $state;
	$sets[] = $fs;
}


if ($_POST['task'] == 'add_to_sets') {
	
	foreach($_POST as $key => $value) {
	
		if (preg_match('/fsID:/', $key)) {
			$fsIDst = explode(':', $key);
			$fsID = $fsIDst[1];
			
			// so the affected file set is $fsID, the state of the thing is $value
			$fs = FileSet::getByID($fsID);
			$fsp = new Permissions($fs);
			if ($fsp->canAddFile($f)) {
				switch($value) {
					case '0':
						foreach($files as $f) {
							$fs->removeFileFromSet($f);
						}
						break;
					case '1':
						// do nothing
						break;
					case '2':
						foreach($files as $f) {
							$fs->addFileToSet($f);
						}
						break;
				}		
			}			
		}
	}

	if ($_POST['fsNew']) {
		$type = ($_POST['fsNewShare'] == 1) ? FileSet::TYPE_PUBLIC : FileSet::TYPE_PRIVATE;
		$fs = FileSet::createAndGetSet($_POST['fsNewText'], $type);
		//print_r($fs);
		foreach($files as $f) {
			$fs->addFileToSet($f);
		}
		
		// ***** LERTECO_WALL
		//add a notification to the user's wall if the lerteco_wall add-on is installed
		$wall = Loader::package('lerteco_wall');
		if (is_object($wall)) {			
		//it's installed
		$wall_link = '<a href="'.View::url('/gallerybox/userset',$fs->getFileSetID()).'">'.$fs->getFileSetName().'</a>';
		// ideally we would register a posting type on install, then log the post
		// but lerteco_wall might not have been installed when we were installed
		// so we'll pass all the post and type data at once and let lerteco_wall figure out what to do
		// there's a shortcut function for this, inside the package controller, so we don't have to include anything else
		$wall->postAndPossiblyRegister($u->getUserID(), $wall_link, $posting_type);
							
							
		}
	}
	exit;
}
?>

<script type="text/javascript">
$(function() {
	ccm_alSetupSetsForm('<?php  echo $searchInstance?>');
});
</script>


<?php  
	$gbat = Loader::helper('concrete/urls')->getToolsURL('user_add_to', 'gallerybox');
	if (!$disableForm) { ?>
	<form method="post" id="ccm-<?php  echo $searchInstance?>-add-to-set-form" action="<?php  echo $gbat?>">
	<?php  echo $form->hidden('task', 'add_to_sets')?>
	<?php   foreach($files as $f) { ?>
		<input type="hidden" name="fID[]" value="<?php  echo $f->getFileID();?>" />
	<?php   } ?>

<?php   } ?>

	<div style="margin-top: 12px">
	<table border="0" cellspacing="0" cellpadding="0" id="ccm-file-search-advanced-sets-header">
	<tr>
		<?php   if (!$disableTitle) { ?>
		<td width="100%"><h1><?php  echo t('Set')?></h1></td>
		<?php   } ?>
		<td>
		
		
		<div class="ccm-file-sets-search-wrapper-input">
			<?php  echo $form->text('fsAddToSearchName', $searchRequest['fsSearchName'], array('autocomplete' => 'off'))?>
		</div>
		
		</td>
	</tr>
	</table>
	</div>

	
	<?php   $s1 = $cnt->getUserSets(); ?>
	<?php   if (count($s1) > 1) { ?>
	<div class="ccm-file-search-advanced-sets-results">
		<ul id="ccm-file-search-add-to-sets-list">
	
	
	<?php   foreach($sets as $s) { 
		$displaySet = true;
		
		$pf = new Permissions($s);
		if (!$pf->canAddFiles()) { 
			$displaySet = false;
		} else {
			foreach($extensions as $ext) {
				if (!$pf->canAddFileType($ext)) {
					$displaySet = false;
				}
			}
		}
		
		if ($displaySet && $s->getFileSetName() != 'user_gallery_'.$u->getUserID() && $s->getFileSetName() != 'GalleryBoxCollection') {
		?>
	
		<li class="ccm-file-set-add-cb" style="padding-left: 0px">
			<?php  echo checkbox('fsID', $s->getFileSetID(), $s->state)?> <label><?php  echo $s->getFileSetName()?></label>
		</li>
	<?php   } 
	} ?>
	
		</ul>
	</div>
	<?php   } else { ?>
		<?php  echo t('You have not created any image sets yet.')?>
	<?php   } ?>

<?php   if (count($extensions) > 1) { ?>

	<br/><div class="ccm-note"><?php  echo t('If a file set does not appear above, you either have no access to add files to it, or it does not accept the file types %s.', implode(', ', $extensions));?></div>
	
	
<?php   } ?>
<br/>
<hr />

<h2><?php  echo t('Add to New Set')?></h2>

<?php  echo $form->checkbox('fsNew', 1)?> <?php  echo $form->text('fsNewText', array('style' => 'width: 120px', 'onclick' => '$(\'input[name=fsNew]\').attr(\'checked\',true)'))?> <?php  echo $form->checkbox('fsNewShare', 1, true)?> <?php  echo t('Make set public')?>

<?php   if (!$disableForm) { ?>

	<br/><br/>
	<?php  
	$h = Loader::helper('concrete/interface');
	


	?>
    <input type="submit" value="Update" onClick="javascript:window.setTimeout('location.reload(true)', 100);" class="btn primary" />
	</form>
	
<?php   } ?>