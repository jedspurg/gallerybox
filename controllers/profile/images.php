<?php    
defined('C5_EXECUTE') or die("Access Denied.");
Loader::model('file_list');
Loader::model('file_set');

class ProfileImagesController extends Controller {
	
	var $helpers = array('html', 'form', 'text'); 
	
	public function on_start(){
		$this->error = Loader::helper('validation/error');
		$this->addHeaderItem(Loader::helper('html')->css('ccm.profile.css'));
	}
	
public function view($userID = 0) {
		if(!ENABLE_USER_PROFILES) {
			header("HTTP/1.0 404 Not Found");
			$this->render("/page_not_found");
		}
		
		$html = Loader::helper('html');
		$this->set('form', $form);
		$searchInstance = 'gbx' . time();

		$fileList = $this->getRequestedSearchResults();
		$files = $fileList->getPage();
		$this->addHeaderItem($html->css('jquery.rating.css'));
		$this->addHeaderItem($html->css('ccm.dialog.css'));
		$this->addHeaderItem($html->css('ccm.menus.css'));
		$this->addHeaderItem($html->css('ccm.forms.css'));
		$this->addHeaderItem($html->css('ccm.search.css'));
		$this->addHeaderItem($html->css('ccm.filemanager.css'));
		$this->addHeaderItem($html->css('jquery.ui.css'));
		$this->addHeaderItem($html->css('gallerybox-profile.css', 'gallerybox'));
		


		
		$this->addFooterItem('<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/i18n_js"></script>'); 
	
		$this->addFooterItem($html->javascript('jquery.ui.js'));
		$this->addFooterItem($html->javascript('jquery.form.js'));
		$this->addFooterItem($html->javascript('jquery.rating.js'));
		$this->addFooterItem($html->javascript('ccm.app.js'));
	
		global $c;
		$cID = $c->getCollectionID();

		$this->addFooterItem('<script type="text/javascript" src="' . REL_DIR_FILES_TOOLS_REQUIRED . '/page_controls_menu_js?cID=' . $cID . '&amp;cvID=' . $cvID . '&amp;btask=' . $_REQUEST['btask'] . '&amp;ts=' . time() . '"></script>'); 


	
		$this->addFooterItem($html->javascript('user.filemanager.js', 'gallerybox'));
	
		
		$this->addFooterItem('<script type="text/javascript">$(function() { ccm_activateFileManager(\'DASHBOARD\', \'' . $searchInstance . '\'); });</script>');
		
$this->addFooterItem('<script type="text/javascript">var GBX_SET_TOOL = "'.Loader::helper('concrete/urls')->getToolsURL('user_add_to', 'gallerybox').'";var GBX_COMPLETED_TOOL = "'.Loader::helper('concrete/urls')->getToolsURL('add_to_complete', 'gallerybox').'";var GBX_SET_RELOAD = "'.Loader::helper('concrete/urls')->getToolsURL('search_user_sets_reload', 'gallerybox').'";
</script>');
		



		$canEdit = false;
		$u = new User();

		if ($userID > 0) {
			$profile = UserInfo::getByID($userID);
			if (!is_object($profile)) {
				throw new Exception('Invalid User ID.');
			}
		} else if ($u->isRegistered()) {
			$profile = UserInfo::getByID($u->getUserID());
			$canEdit = true;
		} else {
			$this->set('intro_msg', t('You must sign in order to access this page!'));
			Loader::controller('/login');
			$this->render('/login');
		}
		$this->set('profile', $profile);
		$this->set('av', Loader::helper('concrete/avatar'));
		$this->set('t', Loader::helper('text'));
		$this->set('canEdit',$canEdit);
		$this->set('fileList', $fileList);		
		$this->set('files', $files);		
		
		$this->set('searchInstance', $searchInstance);	
		

		
		

	}
	
	public function getUserSets() {

			$u = new User();
			$db = Loader::db();
			$sets = array();
			$r = $db->Execute('select * from FileSets where uID = ? order by fsName asc', $u->getUserID());
			while ($row = $r->FetchRow()) {
				$fs = new FileSet();
				foreach($row as $key => $value) {
					$fs->{$key} = $value;
				}
				$fsp = new Permissions($fs);
				if ($fsp->canSearchFiles()) {
					$sets[] = $fs;
				}
			}
			return $sets;
		}
	
	public function getRequestedSearchResults() {
		$u = new User();
		$fsName = 'user_gallery_'.$u->getUserID();
		$userSet = FileSet::getByName($fsName);
		if(!is_object($userSet)){
			$userSet = FileSet::createAndGetSet($fsName, FileSet::TYPE_PUBLIC, $u->getUserID());
		}
		$fileList = new FileList();
				
		Loader::model('file_set');
		
		if ($_REQUEST['submit_search']) {
			$fileList->resetSearchRequest();
		}

		$req = $fileList->getSearchRequest();
		
		
		$fldc = FileManagerColumnSet::getCurrent();
		$fldca = new FileManagerAvailableColumnSet();

		$columns = new FileManagerColumnSet();
		
		$columns->addColumn($fldca->getColumnByKey('fvTitle'));
		$columns->addColumn($fldca->getColumnByKey('fDateAdded'));
		
		$sortCol = $fldca->getColumnByKey('fDateAdded');
		$columns->setDefaultSortColumn($sortCol, 'desc');

		if (!isset($columns)) {
			$columns = FileManagerColumnSet::getCurrent();
		}

		$this->set('searchRequest', $req);
		$this->set('columns', $columns);

		$col = $columns->getDefaultSortColumn();	
		$fileList->sortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());
		
		$keywords = htmlentities($req['fKeywords'], ENT_QUOTES, APP_CHARSET);
		
		if ($keywords != '') {
			$fileList->filterByKeywords($keywords);
		}

		if ($req['numResults']) {
			$fileList->setItemsPerPage($req['numResults']);
		}
		
		
				
				
		$fileList->filterBySet($userSet);

		if (isset($_GET['fType']) && $_GET['fType'] != '') {
			$type = $_GET['fType'];
			$fileList->filterByType($type);
		}

		if (isset($_GET['fExtension']) && $_GET['fExtension'] != '') {
			$ext = $_GET['fExtension'];
			$fileList->filterByExtension($ext);
		}
		
		if (isset($_GET['fsID']) && $_GET['fsID'] != '') {
			$fsID = $_GET['fsID'];
			$filterSet = FileSet::getByID($fsID);
			$fileList->filterBySet($filterSet);
		}
		
		$selectedSets = array();

		if (is_array($req['selectedSearchField'])) {
			foreach($req['selectedSearchField'] as $i => $item) {
				// due to the way the form is setup, index will always be one more than the arrays
				if ($item != '') {
					switch($item) {
						case "extension":
							$extension = $req['extension'];
							$fileList->filterByExtension($extension);
							break;
						case "type":
							$type = $req['type'];
							$fileList->filterByType($type);
							break;
						case "date_added":
							$dateFrom = $req['date_from'];
							$dateTo = $req['date_to'];
							if ($dateFrom != '') {
								$dateFrom = date('Y-m-d', strtotime($dateFrom));
								$fileList->filterByDateAdded($dateFrom, '>=');
								$dateFrom .= ' 00:00:00';
							}
							if ($dateTo != '') {
								$dateTo = date('Y-m-d', strtotime($dateTo));
								$dateTo .= ' 23:59:59';
								
								$fileList->filterByDateAdded($dateTo, '<=');
							}
							break;
						case 'added_to':
							$ocID = $req['ocIDSearchField'];
							if ($ocID > 0) {
								$fileList->filterByOriginalPageID($ocID);							
							}
							break;
						case "size":
							$from = $req['size_from'];
							$to = $req['size_to'];
							$fileList->filterBySize($from, $to);
							break;
						default:
							Loader::model('file_attributes');
							$akID = $item;
							$fak = FileAttributeKey::get($akID);
							$type = $fak->getAttributeType();
							$cnt = $type->getController();
							$cnt->setRequestArray($req);
							$cnt->setAttributeKey($fak);
							$cnt->searchForm($fileList);
							break;
					}
				}
			}
		}
		if (isset($req['numResults'])) {
			$fileList->setItemsPerPage($req['numResults']);
		}
		return $fileList;
	}

	
	public function on_before_render() {
		$this->set('error', $this->error);
	}	
	
	
}