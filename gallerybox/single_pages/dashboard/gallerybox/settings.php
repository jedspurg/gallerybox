<?php   defined('C5_EXECUTE') or die("Access Denied.");
$form = Loader::helper('form');
$dh = Loader::helper('concrete/dashboard');
$al = Loader::helper('concrete/asset_library');

echo $dh->getDashboardPaneHeaderWrapper(t('GalleryBox Settings'), false, 'span10 offset3', false)?>

<script type="text/javascript">
$(function() {
	$("#wm-img-select").hide();
	<?php   if ($gbx_watermark == '1'){?>
		$("#wm-img-select").show();
	<?php   }?>
	
	$("#gbx_watermark").click(function() {
		$("#wm-img-select").toggle();
	});
	
});
</script>

<form method="post" id="gbx-settings" action="<?php   echo $this->url('/dashboard/gallerybox/settings', 'save_settings')?>">
<div class="ccm-pane-body">

<h3><?php   echo t('Maximum page width')?></h3>
<div class="clearfix inputs-list">
    <ul class="inputs-list">
     <li>
        <label>
          <?php   echo $form->radio('gbx_width', '9', $gbx_width)?>
          <span>700px</span>
        </label>
      </li> 
      <li>
        <label>
          <?php   echo $form->radio('gbx_width', '10', $gbx_width)?>
          <span>760px</span>
        </label>
      </li> 
      <li>
        <label>
          <?php   echo $form->radio('gbx_width', '11', $gbx_width)?>
          <span>820px</span>
        </label>
      </li>
      <li>
        <label>
          <?php   echo $form->radio('gbx_width', '12', $gbx_width)?>
          <span>880px</span>
        </label>
      </li>
      <li>
        <label>
          <?php   echo $form->radio('gbx_width', '13', $gbx_width)?>
          <span>940px</span>
        </label>
      </li> 
      <li>
        <label>
          <?php   echo $form->radio('gbx_width', '14', $gbx_width)?>
          <span>1000px</span>
        </label>
      </li>   
    </ul>
  </div>


<h3><?php   echo t('Maximum image zoom width')?></h3>
<div class="clearfix inputs-list">
	
<label><?php   echo $form->text('gbx_zoom_width', $gbx_zoom_width, array('style' => 'width: 50px'))?> px</label>

</div>		


<h3><?php   echo t('Maximum image zoom height')?></h3>
<div class="clearfix inputs-list">
<label><?php   echo $form->text('gbx_zoom_height', $gbx_zoom_height, array('style' => 'width: 50px'))?> px</label>
</div>
	


<h3><?php   echo t('Allow image notes')?></h3>
<div class="clearfix inputs-list">
<label><?php   echo $form->checkbox('gbx_allow_notes', '1', $gbx_allow_notes)?> <?php   echo t('Yes')?></label>
</div>



<h3><?php   echo t('Allow image comments and favorites')?></h3>
<div class="clearfix inputs-list">
<label><?php   echo $form->checkbox('gbx_allow_comments', '1', $gbx_allow_comments)?> <?php   echo t('Yes')?></label>
</div>



<h3><?php   echo t('Allow image download')?></h3>
<div class="clearfix inputs-list">
<label><?php   echo $form->checkbox('gbx_allow_download', '1', $gbx_allow_download)?> <?php   echo t('Yes')?></label>
</div>


<h3><?php   echo t('Show embed code')?></h3>
<div class="clearfix inputs-list">
<label><?php   echo $form->checkbox('gbx_allow_embed', '1', $gbx_allow_embed)?> <?php   echo t('Yes')?></label>
</div>


<h3><?php   echo t('Allow RSS feed of image posts')?></h3>
<div class="clearfix inputs-list">
<label><?php   echo $form->checkbox('gbx_allow_rss', '1', $gbx_allow_rss)?> <?php   echo t('Yes')?></label>

</div>

<h3><?php   echo t('Watermark images')?></h3>
<div class="clearfix inputs-list">
<label><?php   echo $form->checkbox('gbx_watermark', '1', $gbx_watermark)?> <?php   echo t('Yes')?></label>
</div>
<div id="wm-img-select">
    <h3><?php   echo t('Select watermark')?></h3>
    <p>If no file is chosen, the default GalleryBox watermark will be used.<br/>
    <strong>Important: Watermark images should be PNG-8 files with alpha transparency.</strong></p>
    <div class="clearfix inputs-list">
   <?php    echo $al->image('ccm-image', 'gbx_watermark_img', 'Choose file',$gbx_watermark_img);?>
    </div>
</div>
	
    </div>
    <div class="ccm-pane-footer">
        <?php  
        print $interface->submit(t('Save'), 'gbx-settings', 'right', 'primary');
        ?>    
    </div>
</form>
<?php   echo $dh->getDashboardPaneFooterWrapper(false);?> 
