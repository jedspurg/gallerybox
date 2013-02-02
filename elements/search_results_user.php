<?php     defined('C5_EXECUTE') or die("Access Denied."); ?> 
<?php    
if (isset($_REQUEST['searchInstance'])) {
	$searchInstance = $_REQUEST['searchInstance'];
}
?>

<div id="ccm-list-wrapper"><a name="ccm-<?php    echo $searchInstance?>-list-wrapper-anchor"></a>

<?php    

	$fileList->displaySummary();
	$txt = Loader::helper('text');
	//$keywords = $searchRequest['fKeywords'];
	$soargs = array();
	$soargs['searchInstance'] = $searchInstance;


	
	$bu = Loader::helper('concrete/urls')->getToolsURL('search_results_user', 'gallerybox');
	
	if (count($files) > 0) { ?>	
		<table border="0" cellspacing="0" cellpadding="0" id="ccm-<?php    echo $searchInstance?>-list" class="ccm-results-list" style="width:100%">
    <thead>
		<tr>
			<th><input id="ccm-<?php    echo $searchInstance?>-list-cb-all" type="checkbox" /></th>
			<th><select id="ccm-<?php    echo $searchInstance?>-list-multiple-operations" disabled>
				<option value="">**</option>
				<option value="download"><?php    echo t('Download')?></option>
				<option value="sets"><?php    echo t('Sets')?></option>
				<option value="properties"><?php    echo t('Properties')?></option>
				<option value="delete"><?php    echo t('Delete')?></option>
			</select>
			</th>

			
			<?php     foreach($columns->getColumns() as $col) { ?>
				<?php     if ($col->isColumnSortable()) { ?>
					<th class="<?php    echo $fileList->getSearchResultsClass($col->getColumnKey())?>"><a href="<?php    echo $fileList->getSortByURL($col->getColumnKey(), $col->getColumnDefaultSortDirection(), $bu, $soargs)?>"><?php    echo $col->getColumnName()?></a></th>
				<?php     } else { ?>
					<th><?php    echo t('Type')?></th>
				<?php     } ?>
			<?php     } ?>

		</tr>
    </thead>
	<?php    
		foreach($files as $f) {
			$pf = new Permissions($f);
			if (!isset($striped) || $striped == 'ccm-list-record-alt') {
				$striped = '';
			} else if ($striped == '') { 
				$striped = 'ccm-list-record-alt';
			}
			$star_icon = ($f->isStarred() == 1) ? 'star_yellow.png' : 'star_grey.png';
			$fv = $f->getApprovedVersion(); 
			$canViewInline = $fv->canView() ? 1 : 0;
			$canEdit = ($fv->canEdit() && $pf->canWrite()) ? 1 : 0;
			$pfg = FilePermissions::getGlobal();
			?>
			<tr class="ccm-list-record <?php    echo $striped?>" ccm-file-manager-instance="<?php    echo $searchInstance?>" ccm-file-manager-can-admin="<?php    echo ($pf->canAdmin())?>" ccm-file-manager-can-duplicate="<?php    echo ($pfg->canAddFileType($f->getExtension()))?>" ccm-file-manager-can-delete="<?php    echo $pf->canAdmin()?>" ccm-file-manager-can-view="<?php    echo $canViewInline?>" ccm-file-manager-can-download="<?php    echo $canViewInline?>" ccm-file-manager-can-replace="<?php    echo $pf->canWrite()?>" ccm-file-manager-can-edit="<?php    echo $canEdit?>" fID="<?php    echo $f->getFileID()?>" id="fID<?php    echo $f->getFileID()?>">
			<td class="ccm-file-list-cb" style="vertical-align: middle !important;"><input type="checkbox" value="<?php    echo $f->getFileID()?>" style="margin-left:8px;"/></td>
			<td>
				<div class="ccm-file-list-thumbnail">
					<div class="ccm-file-list-thumbnail-image" fID="<?php    echo $f->getFileID()?>"><table border="0" cellspacing="0" cellpadding="0" height="70" width="100%"><tr><td align="center" fID="<?php    echo $f->getFileID()?>" style="padding: 0px"><?php    echo $fv->getThumbnail(1)?></td></tr></table></div>
				</div>
		
			<?php     if ($fv->hasThumbnail(2)) { ?>
				<div class="ccm-file-list-thumbnail-hover" id="fID<?php    echo $f->getFileID()?>hoverThumbnail"><div><?php    echo $fv->getThumbnail(2)?></div></div>
			<?php     } ?>

				</td>

			<?php     foreach($columns->getColumns() as $col) { ?>
				<?php     // special one for keywords ?>				
				<?php     if ($col->getColumnKey() == 'fvTitle') { ?>
					<td class="ccm-file-list-filename"><div style="word-wrap: break-word; width: 120px"><?php    echo $txt->highlightSearch($fv->getTitle(), $keywords)?></div></td>		
				<?php     } else { ?>
					<td><?php    echo $col->getColumnValue($f)?></td>
				<?php     } ?>
			<?php     } ?>
			
			<?php     /*
			
			*/ ?>
			
			
			
			</tr>
			<?php    
		}

	?>
	
	</table>
	
	

	<?php     } else { ?>
		
		<div class="ccm-results-list-none"><?php    echo t('No images found.')?></div>
		
	
	<?php     }?>
  <?php  
			$summary = $fileList->getSummary();
			if ($summary->pages > 1):
			$paginator = $fileList->getPagination();
			
			?>
      <div class="pagination">
        <ul>
          <li class="prev<?php   if($paginator->getCurrentPage() == $paginator->getPreviousInt()){?> disabled<?php   }?>"><a href="<?php   if($paginator->getCurrentPage() == $paginator->getPreviousInt()){?>javascript:void(0);<?php   }else{?>?ccm_paging_p=<?php   echo $paginator->getPreviousInt()+1;}?>">&larr; Previous</a></li>
          
          <?php    $totNumPages = $paginator->getTotalPages();
								 if($totNumPages > 8){$numPages = 8;}else{$numPages = $totNumPages;}
                for ($i = 1; $i <= $numPages; $i++) {?>
                
										<?php   if(($paginator->getCurrentPage()+1) == $i){?>
                      <li class="active">
                      <a href="?ccm_paging_p=<?php   echo $i;?>"><?php   echo $i;?></a>
                      </li>
                    
                    <?php   }else{?>
                      <li>
                      <a href="?ccm_paging_p=<?php   echo $i;?>"><?php   echo $i;?></a>
                      </li>
                      <?php   }?>
                <?php   }?>
                <?php   if($numPages==8){?>
                 <li>
                      <a href="javascript:void(0);">...</a>
                 </li>
                 <li>
                       <a href="?ccm_paging_p=<?php   echo $totNumPages;?>"><?php   echo $totNumPages;?></a>
                 </li>
                <?php   }?>
               
          <li class="next<?php   if(($paginator->getCurrentPage()) == $paginator->getNextInt()){?> disabled<?php   }?>"><a href="<?php   if($paginator->getCurrentPage() == $paginator->getNextInt()){?>javascript:void(0);<?php   }else{?>?ccm_paging_p=<?php   echo $paginator->getNextInt()+1;}?>">Next &rarr;</a></li>
        </ul>
      </div>
			<?php    endif;  ?>
	
</div>