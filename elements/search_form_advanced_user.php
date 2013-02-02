<?php     defined('C5_EXECUTE') or die("Access Denied."); ?> 
<?php    
Loader::model('file_set');

$searchFields = array(
	'' => '** ' . t('Fields'),
	'size' => t('Size'),
	'type' => t('Type'),
	'extension' => t('Extension'),
	'date_added' => t('Added Between'),
	'added_to' => t('Added to Page')
);

if ($_REQUEST['fType'] != false) {
	unset($searchFields['type']);
}
if ($_REQUEST['fExtension'] != false) {
	unset($searchFields['extension']);
}

$html = Loader::helper('html');

Loader::model('file_attributes');
$searchFieldAttributes = FileAttributeKey::getSearchableList();
foreach($searchFieldAttributes as $ak) {
	$searchFields[$ak->getAttributeKeyID()] = $ak->getAttributeKeyDisplayHandle();
}

$ext1 = FileList::getExtensionList();
$extensions = array();
foreach($ext1 as $value) {
	$extensions[$value] = $value;
}

$t1 = FileList::getTypeList();
$types = array();
foreach($t1 as $value) {
	$types[$value] = FileType::getGenericTypeText($value);
}

?>

<?php     
	$form = Loader::helper('form');
	$ust = Loader::helper('concrete/urls')->getToolsURL('search_results_user', 'gallerybox');
?>

	<form method="get" id="ccm-<?php    echo $searchInstance?>-advanced-search" action="<?php    echo $ust?>">


<input type="hidden" name="searchInstance" value="<?php    echo $searchInstance?>" />
	
<div id="ccm-<?php    echo $searchInstance?>-search-advanced-fields" class="ccm-search-advanced-fields pull-left" >

		<input type="hidden" name="submit_search" value="1" />
	<?php    	
		print $form->hidden('ccm_order_dir', $searchRequest['ccm_order_dir']); 
		print $form->hidden('ccm_order_by', $searchRequest['ccm_order_by']); 
		print $form->hidden('fileSelector', $fileSelector); 
	?>	
		<div id="ccm-search-box-title">
			<img src="<?php    echo ASSETS_URL_IMAGES?>/throbber_white_16.gif" width="16" height="16" class="ccm-search-loading" id="ccm-<?php    echo $searchInstance?>-search-loading" />
			
			<h5><?php    echo t('Search')?></h5>			
		</div>
		
		<div id="ccm-search-advanced-fields-inner">
			<div class="ccm-search-field">
				<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="100%">
					<?php    echo $form->text('fKeywords', $searchRequest['fKeywords'], array('style' => 'width:200px')); ?>
					</td>
				</tr>
				</table>
			</div>
		
			<div class="ccm-search-field">
				<table border="0" cellspacing="0" cellpadding="0" width="100%">
				<tr>
					<td style="white-space: nowrap; width:70%;text-align:left" ><div style="width: 85px; padding-right:5px"><?php    echo t('Results Per Page')?></div></td>
					<td style="width:30%">
						<?php    echo $form->select('numResults', array(
							'10' => '10',
							'25' => '25',
							'50' => '50',
							'100' => '100',
							'500' => '500'
						), $searchRequest['numResults'], array('style' => 'width:65px'))?>
					</td>
				</tr>	
				</table>
			</div>
			
			
			
			
			
			<div id="ccm-search-fields-submit">
				
				<?php    echo $form->submit('ccm-search-files', 'Search', array('class'=>'btn'))?>
			</div>
		</div>
	
</div>

<div id="ccm-<?php    echo $searchInstance?>-sets-search-wrapper" class="pull-right">
	<?php    Loader::packageElement('search_form_sets_user', 'gallerybox', array('searchInstance' => $searchInstance, 'searchRequest' => $searchRequest)) ?>

</div>

</form>	
