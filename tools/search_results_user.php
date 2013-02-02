<?php    
defined('C5_EXECUTE') or die(_("Access Denied."));
$fp = FilePermissions::getGlobal();
if (!$fp->canAccessFileManager()) {
	die(_("Unable to access the file manager."));
}

$u = new User();
	
Loader::model('file_list');

$cnt = Loader::controller('/profile/images');
$fileList = $cnt->getRequestedSearchResults();

$files = $fileList->getPage();
$pagination = $fileList->getPagination();
$searchRequest = $cnt->get('searchRequest');
$columns = $cnt->get('columns');

Loader::packageElement('search_results_user', 'gallerybox', array('searchInstance' => $searchInstance, 'searchRequest' => $searchRequest, 'columns' => $columns, 'files' => $files, 'fileList' => $fileList, 'pagination' => $pagination));
