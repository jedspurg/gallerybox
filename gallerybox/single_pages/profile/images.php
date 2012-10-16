<?php   defined('C5_EXECUTE') or die("Access Denied."); 
   $nh = Loader::helper('navigation');
   $return = $nh->getCollectionURL($c);

?>
<div id="ccm-profile-wrapper">
    <?php   Loader::element('profile/sidebar', array('profile'=> $profile)); ?>
	
    <div id="ccm-profile-body">	
    	<div class="btn-pad">
				<a href="<?php  echo View::url('/gallerybox/user',$profile->getUserID())?>" class="btn"><strong>My gallery</strong><span class="icon rightarrow"></span></a>
      </div>

    	<div id="ccm-profile-body-items">
        <?php 
$u = new User();
$ch = Loader::helper('concrete/file');
$h = Loader::helper('concrete/interface');
$form = Loader::helper('form');



$types = array('jpg','png','gif','jpeg');

$ocID = $c->getCollectionID();
$types = $ch->serializeUploadFileExtensions($types);
$valt = Loader::helper('validation/token');
?>


<script type="text/javascript" src="<?php  echo ASSETS_URL_JAVASCRIPT?>/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php  echo ASSETS_URL_JAVASCRIPT?>/swfupload/swfupload.handlers.js"></script>
<script type="text/javascript" src="<?php  echo ASSETS_URL_JAVASCRIPT?>/swfupload/swfupload.fileprogress.js"></script>
<script type="text/javascript" src="<?php  echo ASSETS_URL_JAVASCRIPT?>/swfupload/swfupload.queue.js"></script>

<script type="text/javascript">
var ccm_fiActiveTab = "ccm-file-upload-multiple";
$("#ccm-file-import-tabs a").click(function() {
	$("li.ccm-nav-active").removeClass('ccm-nav-active');
	$("#" + ccm_fiActiveTab + "-tab").hide();
	ccm_fiActiveTab = $(this).attr('id');
	$(this).parent().addClass("ccm-nav-active");
	$("#" + ccm_fiActiveTab + "-tab").show();
});
</script>




<?php  
$umf = ini_get('upload_max_filesize');
$umf = str_ireplace(array('M', 'K', 'G'), array(' MB', 'KB', ' GB'), $umf);
?>

<script type="text/javascript">

var swfu;
$(function() { 



	swfu = new SWFUpload({

		flash_url : "<?php  echo ASSETS_URL_FLASH?>/swfupload/swfupload.swf",
		upload_url : "<?php  echo Loader::helper('concrete/urls')->getToolsURL('user_multiple', 'gallerybox')?>",
		post_params: {'ccm-session' : "<?php   echo session_id(); ?>",'searchInstance': '<?php  echo $searchInstance?>', 'ocID' : '<?php  echo $ocID?>', 'ccm_token' : '<?php  echo $valt->generate("upload")?>'},
		file_size_limit : "<?php  echo $umf?>",
		file_types : "<?php  echo $types?>",
		button_window_mode : SWFUpload.WINDOW_MODE.TRANSPARENT,
		file_types_description : "All Files",
		file_upload_limit : 100,
		button_cursor: SWFUpload.CURSOR.HAND,
		file_queue_limit : 0,
		custom_settings : {
			progressTarget : "ccm-file-upload-multiple-list",
			cancelButtonId : "ccm-file-upload-multiple-btnCancel"
		},
		debug: false,

		// Button settings
		button_image_url: "<?php  echo ASSETS_URL_IMAGES?>/icons/add_file_swfupload.png",	// Relative to the Flash file
		button_width: "80",
		button_text: '<span class="uploadButtonText"><?php  echo t('Add Files')?><\/span>',
		button_height: "16",
		button_text_left_padding: 18,
		button_text_style: ".uploadButtonText {background-color: #eee; font-family: Helvetica Neue, Helvetica, Arial}",
		button_placeholder_id: "ccm-file-upload-multiple-spanButtonPlaceHolder",
		
		// The event handler functions are defined in handlers.js
		// wrapped function with apply are so c5 can do anything special it needs to
		// some functions needed to be overridden completly
		file_queued_handler : function (file) {
			fileQueued.apply(this,[file]);
		},
		file_queue_error_handler : fileQueueError,
		file_dialog_complete_handler : function(numFilesSelected, numFilesQueued){
			try {
				if (numFilesSelected > 0) {					
					$("#ccm-file-upload-multiple-btnCancel").removeClass('disabled');
				}								
				//this.startUpload();
			} catch (ex)  {
				this.debug(ex);
			}		
		},
		upload_start_handler : uploadStart,
		upload_progress_handler : function(file, bytesLoaded, bytesTotal){
			try {
				var percent = Math.ceil((bytesLoaded / bytesTotal) * 100);
		
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				progress.setProgress(percent);
				
				progress.setStatus("Uploading... ("+percent+"%)");
			} catch (ex) {
				this.debug(ex);
			}		
		},
		upload_error_handler : uploadError,
		upload_success_handler : function(file, serverData){
			try {
				eval('serverData = '+serverData);
				var progress = new FileProgress(file, this.customSettings.progressTarget);
				if (serverData['error'] == true) {
					progress.setError(serverData['message']);
				} else {
					progress.setComplete();		
				}
				progress.toggleCancel(false);
				if(serverData['id']){
					if(!this.highlight){this.highlight = [];}
					this.highlight.push(serverData['id']);
					if(ccm_uploadedFiles && serverData['id']!='undefined') ccm_uploadedFiles.push(serverData['id']);
					
				} 
				 
			} catch (ex) {
				this.debug(ex);
			}		
		},
		upload_complete_handler : uploadComplete, 
		queue_complete_handler : function(file){
			// queueComplete() from swfupload.handlers.js
			//console.log(ccm_uploadedFiles.length);
			if (ccm_uploadedFiles.length > 0) {
				queueComplete();
				ccm_filesUploadedDialog('<?php  echo $searchInstance?>'); 
					
			}
		}
	});

	
});
</script>

<div class="gbx-modal">
    <div class="modal-header">
      
      <h4><?php  echo t('Upload Images')?></h4>
    </div>
    <div class="modal-body">
    <form id="form1" action="<?php  echo DISPATCHER_FILENAME?>" method="post" enctype="multipart/form-data">
      <table border="0" width="100%" cellspacing="0" cellpadding="0" id="ccm-file-upload-multiple-list">
		<tr>
			<th colspan="2"><div style="width: 80px; float: right"><span id="ccm-file-upload-multiple-spanButtonPlaceHolder"></span></div><?php  echo t('Upload Queue');?></th>
		</tr>
		</table>
		
		<div class="ccm-spacer">&nbsp;</div><br/>
		
		<!--
		<div>

		<div id="ccm-file-upload-multiple-results-wrapper">

		<div style="width: 100px; float: right; text-align: right"></div>

		<div id="ccm-file-upload-multiple-results">0 <?php  echo t('Files Uploaded');?></div>
		
		<div class="ccm-spacer">&nbsp;</div>
		
		</div>
		
		</div>
		<br style="clear:left;"/> //-->
      
      
    </div>
    <div class="modal-footer">
     <a href="javascript:void(0);" class="btn primary pull-right" onclick="swfu.startUpload()">Start uploads</a>
    <a href="javascript:void(0);" class="btn pull-right disabled" onclick="swfu.cancelQueue()" id="ccm-file-upload-multiple-btnCancel">Cancel</a>
    </form>
    <span class="help-block">
       <?php  echo t('Upload Max File Size: %s', ini_get('upload_max_filesize'))?>
    </span>
    <span class="help-block">
       <?php  echo t('Post Max Size: %s', ini_get('post_max_size'))?>
    </span>

    </div>
</div>

<br/>

<div class="gbx-modal">
    <div class="modal-header">
      
      <h4><?php  echo t('Manage Images')?></h4>
    </div>
    <div class="modal-body">	
			<?php   
      $fp = FilePermissions::getGlobal();
      $c = Page::getCurrentPage();
      $ocID = $c->getCollectionID();
      if ($fp->canSearchFiles()) { 
      ?>	
      
      <table id="ccm-search-form-table" >
			<tr>
					<td valign="top" class="ccm-search-form-advanced-col">
					<?php   Loader::packageElement('search_form_advanced_user', 'gallerybox', array('searchInstance' => $searchInstance, 'searchRequest' => $searchRequest)); ?>
				</td>	
        </tr>
        <tr>	
		
				<td valign="top" width="100%">	
					
					<div id="ccm-search-advanced-results-wrapper">
					
						
						
						<div id="ccm-<?php  echo $searchInstance?>-search-results" class="ccm-file-list">
                        

						
							<?php   Loader::packageElement('search_results_user', 'gallerybox', array('searchInstance' => $searchInstance, 'searchRequest' => $searchRequest, 'columns' => $columns, 'files' => $files, 'fileList' => $fileList, 'pagination' => $pagination)); 
							
							?>
						
						</div>
					
					</div>
				
				</td>	
			</tr>
		</table>		
    <?php  }?>
      
      
		</div>

</div>


















	
		
		


        

		</div>
    </div>
	
	<div class="ccm-spacer"></div>
	
