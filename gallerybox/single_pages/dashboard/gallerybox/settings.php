<div style="width: 680px">
<?php 
$ih = Loader::helper('concrete/interface');
$form = Loader::helper('form');
?>
    <h1><span><?php echo t('GalleryBox Settings')?></h1>
    <div class="ccm-dashboard-inner">
    <br/>
    <form method="post" id="gbx-settings" action="<?php echo $this->url('/dashboard/gallerybox/settings', 'save_settings')?>">
    
    <table class="entry-form" border="0" cellspacing="1" cellpadding="0" width="600">
	<tr>
		<td style="width: 40%"><?php echo $form->label('gbx_width','Maximum page width')?></td>
		<td style="width: 60%"><?php echo $form->radio('gbx_width', '10', $gbx_width)?>760px<br/>
			<?php echo $form->radio('gbx_width', '11', $gbx_width)?>820px<br/>
            <?php echo $form->radio('gbx_width', '12', $gbx_width)?>880px<br/>
            <?php echo $form->radio('gbx_width', '13', $gbx_width)?>940px<br/>
            <?php echo $form->radio('gbx_width', '14', $gbx_width)?>1000px
        </td>
     </tr>
     <tr>
        <td><?php echo $form->label('gbx_zoom_width','Maximum image zoom width')?></td>
		<td><?php echo $form->text('gbx_zoom_width', $gbx_zoom_width, array('style' => 'width: 50px'))?> px</td>
	
	</tr>
    <tr>
        <td><?php echo $form->label('gbx_zoom_height','Maximum image zoom height')?></td>
		<td><?php echo $form->text('gbx_zoom_height', $gbx_zoom_height, array('style' => 'width: 50px'))?> px</td>
	
	</tr>
    <tr>
        <td><?php echo $form->label('gbx_allow_notes','Allow image notes')?></td>
		<td><?php echo $form->checkbox('gbx_allow_notes', '1', $gbx_allow_notes)?> Yes</td>
	
	</tr>
    <tr>
        <td><?php echo $form->label('gbx_allow_comments','Allow image comments and favorites')?></td>
		<td><?php echo $form->checkbox('gbx_allow_comments', '1', $gbx_allow_comments)?> Yes</td>
	
	</tr>
    <tr>
        <td><?php echo $form->label('gbx_allow_download','Allow image download')?></td>
		<td><?php echo $form->checkbox('gbx_allow_download', '1', $gbx_allow_download)?> Yes</td>
	
	</tr>
    <tr>
        <td><?php echo $form->label('gbx_allow_embed','Show embed code')?></td>
		<td><?php echo $form->checkbox('gbx_allow_embed', '1', $gbx_allow_embed)?> Yes</td>
	
	</tr>
    <tr>
        <td><?php echo $form->label('gbx_allow_rss','Allow RSS feed of image posts')?></td>
		<td><?php echo $form->checkbox('gbx_allow_rss', '1', $gbx_allow_rss)?> Yes</td>
	
	</tr>
    </table>
    	
    	
    	<div class="ccm-spacer">&nbsp;</div>
        <?php echo $ih->submit(t('Save'), 'gbx-settings')?>
        
        <div class="ccm-spacer">&nbsp;</div>
        
        </form>
    </div>
</div>
