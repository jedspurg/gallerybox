<?php   
defined('C5_EXECUTE') or die("Access Denied.");

		
class GalleryboxSearchBlockController extends BlockController {
	
	protected $btTable = 'btGalleryboxSearch';
	protected $btInterfaceWidth = "550";
	protected $btInterfaceHeight = "150";
	protected $btCacheBlockOutputRecord = true;
	protected $btCacheBlockOutput = true;
	protected $btCacheBlockOutputOnPost = true;
	protected $btCacheBlockOutputForRegisteredUsers = true;
	
		
	public function getBlockTypeDescription() {
		return t("Display a search box for GalleryBox.");
	}
	
	public function getBlockTypeName() {
		return t("GalleryBox Search");
	}
	
	public function on_page_view() {
		
	}
	
	
	function delete(){	
		parent::delete();
	}
	
	function loadBlockInformation() {
	
		$this->set('buttonText', $this->buttonText);
		$this->set('title', $this->title);
				
	}
	
	function view() {

		$this->loadBlockInformation();
	
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
