<?php   
defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');


?>
<div class="clearfix">

      <label for="title"><?php   echo t('Title (optional)')?></label>
    <div class="input">
       <?php   print $form->text('title',$title)?>
    </div>


</div>
<div class="clearfix">

      <label for="buttonText"><?php   echo t('Button text')?></label>
    <div class="input">
       <?php   print $form->text('buttonText',$buttonText)?>
    </div>


</div>