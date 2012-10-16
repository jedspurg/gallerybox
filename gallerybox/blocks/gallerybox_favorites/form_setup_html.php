<?php 
defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
$uForm = Loader::helper('form/user_selector');

?>
<div class="clearfix">
    <label for="title">Title (optional)</label>
    <div class="input">
      <?php print $form->text('title',$title)?>
    </div>
</div>
<div class="clearfix">
    <label for="type">Type</label>
    <div class="input">
      <?php print $form->select('type', array('1'=>'Profile', '2'=>'Selected User', '3'=>'All Site Favorites'), $type);?>
    </div>
</div>
<div class="clearfix">
  <div id="user-selector2" <?php if($type != '2'){?>style="display:none"<?php }?>>
  <?php
  print $uForm->selectUser('uID', $uID, $javascriptFunc = 'ccm_triggerSelectUser');
  ?>
  </div>
</div>

<div class="clearfix">
    <label for="numImgs">Maximum number of images</label>
    <div class="input">
       <?php print $form->text('numImgs',$numImgs)?>
    </div>
</div>

