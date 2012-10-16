<?php 
defined('C5_EXECUTE') or die("Access Denied.");

	Loader::helper('concrete/file');
	Loader::model('file_attributes');
	Loader::library('file/types');
	Loader::model('file_list');
	Loader::model('file_set');
		
class GalleryboxFavoritesBlockController extends BlockController {
	
	protected $btTable = 'btGalleryboxFavorites';
	protected $btInterfaceWidth = "550";
	protected $btInterfaceHeight = "300";
	protected $btCacheBlockOutputRecord = false;
	protected $btCacheBlockOutput = false;
	protected $btCacheBlockOutputOnPost = false;
	protected $btCacheBlockOutputForRegisteredUsers = false;
	
		
	public function getBlockTypeDescription() {
		return t("Display user GalleryBox favorites.");
	}
	
	public function getBlockTypeName() {
		return t("GalleryBox Favorite Images");
	}
	
	public function on_page_view() {
	
	}
	

	function loadImages($userID=NULL,$numImgs){
		
		if($userID == NULL){
			$uQuery = '';
		}else{
			$uQuery = " where uID = '$userID'";
		}
		
		if($numImgs>0){
			$limit = $numImgs;
		}
		
		$db = Loader::db();
		$in = $db->query("SELECT fID FROM GalleryBoxFavs $uQuery ORDER BY lastTimeMarked DESC LIMIT $limit");
		while($row=$in->fetchrow()){
			$imgs[] = $row;
		}		

		return $imgs;
	}
	
	function delete(){	
		parent::delete();
	}
	
	function loadBlockInformation() {
		
		$this->set('title', $this->title);
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
				}else{ 
					$gbxUID=NULL;
				}
			break;
			
			case '2':
				$gbxUID = $this->uID;
			break;
			
			case '3':
				$gbxUID=NULL;
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

		parent::save($args);
	}
	
	function upgrade() { 
	
		parent::upgrade();
	}
	
	
}

?>
