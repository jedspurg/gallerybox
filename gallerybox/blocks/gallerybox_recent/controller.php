<?php   
defined('C5_EXECUTE') or die("Access Denied.");

	Loader::helper('concrete/file');
	Loader::model('file_attributes');
	Loader::library('file/types');
	Loader::model('file_list');
	Loader::model('file_set');
		
class GalleryboxRecentBlockController extends BlockController {
	
	protected $btTable = 'btGalleryboxRecent';
	protected $btInterfaceWidth = "550";
	protected $btInterfaceHeight = "300";
	protected $btCacheBlockOutputRecord = false;
	protected $btCacheBlockOutput = false;
	protected $btCacheBlockOutputOnPost = false;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	
		
	public function getBlockTypeDescription() {
		return t("Display recent user GalleryBox additions.");
	}
	
	public function getBlockTypeName() {
		return t("Recent GalleryBox Images");
	}
	
	public function on_page_view() {
		
	}
	

	function loadImages($uID=NULL,$numImgs){
		if($uID==NULL){
			$fs = FileSet::getByName('GalleryBoxCollection');
		}else{
			$fs = FileSet::getByName('user_gallery_'.$uID);
		}
		
		if(is_object($fs)){
			$fileList = new FileList();		
			$fileList->filterBySet($fs);
			$fileList->filterByType(FileType::T_IMAGE);	
	
			$fldca = new FileManagerAvailableColumnSet();
	
			$columns = new FileManagerColumnSet();
	
			$sortCol = $fldca->getColumnByKey('fDateAdded');
			$columns->setDefaultSortColumn($sortCol, 'desc');
	
			$columns = FileManagerColumnSet::getCurrent();
		
			$col = $columns->getDefaultSortColumn();	
			$fileList->sortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());
			
			$files = $fileList->get(intval($numImgs));
			
			return $files;
		}else{
			return false;
		}
	}
	
	function delete(){	
		parent::delete();
	}
	
	function loadBlockInformation() {
		
		$this->set('title', $this->title);
		$this->set('moreLink', $this->moreLink);
		$this->set('buttonText', $this->buttonText);
		$this->set('numImgs', $this->numImgs);
		$this->set('uID', $this->uID);
		$this->set('type', $this->type);	
		$this->set('bID', $this->bID);				
	}
	
	function view() {
		
		
		$this->loadBlockInformation();
		
		
		switch ($this->type){
			
			case '1':
				$view = View::getInstance(); 
				if ( is_object($view) && is_object($view->controller) && is_object($view->controller->getvar('profile')) ){ 
					$userInfo = $view->controller->getvar('profile'); 
					$gbxUID = $userInfo->getUserID();
					$this->set('gallLink', View::url('/gallerybox/user', $gbxUID));
				}else{ 
					$gbxUID=NULL;
					$this->set('gallLink', View::url('/gallerybox'));
				}
			break;
			
			case '2':
				$gbxUID = $this->uID;
				$this->set('gallLink', View::url('/gallerybox/user', $gbxUID));
			break;
			
			case '3':
				$gbxUID=NULL;
				$this->set('gallLink', View::url('/gallerybox'));
			break;
			
		}

		$this->set('images',$this->loadImages($gbxUID,$this->numImgs));
	}

	function add() {
		$this->loadBlockInformation();
	}
	
	function edit() {
		$this->loadBlockInformation();
		
	}
	
	function duplicate($nbID) {
		parent::duplicate($nbID);
		
	}
	
	function save($args) { 
		if($args['moreLink'] !='1'){
			$args['moreLink'] = '0';
		}

		parent::save($args);
	}
	
	function upgrade() { 
	
		parent::upgrade();
	}
	
	
}

?>
