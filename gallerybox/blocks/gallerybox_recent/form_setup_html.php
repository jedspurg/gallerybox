<?php   
defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
$uForm = Loader::helper('form/user_selector');

?>
<div class="clearfix">
    <label for="title"><?php   echo t('Title (optional)')?></label>
    <div class="input">
      <?php   print $form->text('title',$title)?>
    </div>
</div>
<div class="clearfix">
    <label for="type"><?php   echo t('Type')?></label>
    <div class="input">
      <?php   print $form->select('type', array('1'=>'Profile', '2'=>'Selected User', '3'=>'All Recent'), $type);?>
    </div>
</div>
<div class="clearfix">
  <div id="user-selector2" <?php   if($type != '2'){?>style="display:none"<?php   }?>>
  <?php  
  print $uForm->selectUser('uID', $uID, $javascriptFunc = 'ccm_triggerSelectUser');
  ?>
  </div>
</div>

<div class="clearfix">
    <label for="numImgs"><?php   echo t('Maximum number of images')?></label>
    <div class="input">
       <?php   print $form->text('numImgs',$numImgs)?>
    </div>
</div>
<div class="clearfix">
    <label for="moreLink"><?php   echo t('Include gallery link')?></label>
    <div class="input">
       <?php   print $form->checkbox('moreLink','1',$moreLink)?>
    </div>
</div>
<div class="clearfix">
  <div id="moreLinkText" <?php   if($moreLink != '1'){?>style="display:none"<?php   }?>>
      <label for="buttonText"><?php   echo t('Button text')?></label>
    <div class="input">
       <?php   print $form->text('buttonText',$buttonText)?>
    </div>

  </div>
</div>