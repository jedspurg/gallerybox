<?php    
defined('C5_EXECUTE') or die("Access Denied.");

		Loader::helper('concrete/file');
		Loader::model('file_attributes');
		Loader::library('file/types');
		Loader::model('file_list');
		Loader::model('file_set');
		Loader::model('users_friends');
		
		
class GalleryboxController extends Controller { 
	
	

	// ***** LERTECO_WALL
	// we configure the posting type data up here because it'll never change
	// the array should have the same arguments as the PostingType->LoadOrUpdateOrRegister() function
	// LoadOrUpdateOrRegister($pkg, $type, $name, $post_template, $post_template_example_arr, $share_with)
	// share_with is a constant, but I've hardcoded it here so that we don't need any includes
	// const SHAREWITH_FRIENDS = 1; const SHAREWITH_ALL = 2;
	protected $posting_type_note = array('gallerybox', 'note_added', 'NoteAdded', 'wrote a note on %1$s', 1, 2);
	protected $posting_type_comment = array('gallerybox', 'comment_added', 'CommentAdded', 'left a comment on %1$s', 1, 2);
	protected $posting_type_fav = array('gallerybox', 'faved', 'Faved', 'marked the image %1$s as a favorite', 1, 2);

	public function on_start() {
		$this->error = Loader::helper('validation/error');
		$html = Loader::helper('html');
		$this->addHeaderItem($html->css('gallerybox.css', 'gallerybox'));


		$this->addFooterItem($html->javascript('imgareaselect.js', 'gallerybox'));
		$this->addFooterItem($html->javascript('imgnotes.js', 'gallerybox'));
		$this->addFooterItem($html->javascript('fancyzoom.js','gallerybox'));
		$this->addFooterItem($html->javascript('modal.js','gallerybox'));
		$this->addFooterItem($html->javascript('twipsy.js','gallerybox'));
		$this->addFooterItem($html->javascript('popover.js','gallerybox'));
		
	}
	
	/* automagically run by the controller once we're done with the current method */
	/* method is passed to this method, the method that we were just finished running */
	public function on_before_render() {
		if ($this->error->has()) {
			$this->set('error', $this->error);
		}
	}

	public function view() {
		$fs = FileSet::getByName('GalleryBoxCollection');
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
		$fileList->setItemsPerPage(30);
		$files = $fileList->getPage();
		$fileList->getPagination();
		$ih =Loader::helper('image');
		
		$mainGalleryList = '';
		foreach($files as $img){
			
			$fv = $img->getApprovedVersion();
			$imgt = $fv->getTitle();
			$imgd = $fv->getDescription();
			$imgThumb = $ih->getThumbnail($img,300,300);
			
			$imgH = $imgThumb->height;
			$imgW = $imgThumb->width;
			$topOffset = (112 - $imgH)/2;
			$leftOffset = (112 - $imgW)/2;
			
			$imgui = UserInfo::getByID($img->getUserID());
			if ($imgui->getAttribute('first_name') == ''){
				$username =  $imgui->getUserName();
			}else{
				$username = $imgui->getAttribute('first_name').' '.$imgui->getAttribute('last_name');
			}
			
			$title = substr(htmlspecialchars($imgt),0,24);
			if (strlen($imgt) > 24){
				$title .= '...';
			}
			$mainGalleryList .='<div class="gbx-gallery-item"><div class="gbximgwrapper"><a href="'.View::url('/gallerybox/image',$img->getFileID()).'" rel="overpop" data-placement="below" data-content="by '.$username.': '.htmlspecialchars(str_replace('"',"'",$imgd)).'" title="'.$title.'">';
			$mainGalleryList .= '<div><img src="'.$imgThumb->src.'" width="'.$imgThumb->width.'" height="'.$imgThumb->height.'" title="'.str_replace('"',"'",$imgt).'" style="margin-top:'.intval($topOffset).'px;margin-left:'.intval($leftOffset).'px"/></div></a></div></div>';
				
		}
		$mainGalleryList .='<div class="clearfix"></div>';

		$this->set('gallery',$mainGalleryList);
		$this->set('paging',$fileList);

	}
	
	public function image($fID=NULL){
		
		if($fID==NULL){
			$this->redirect('/gallerybox/');		
		}
		
			$html = Loader::helper('html');
			$this->addHeaderItem($html->javascript('gbx.js','gallerybox'));
								
			$this->set('imgID', $fID);
			$f = File::getByID($fID);
			$fvr = $f->getApprovedVersion();
			$title = $fvr->getTitle();
			
			$favu = new User();
			$this->set('favfID',$fID);
			$this->set('favu',$favu);
			
			
			$fs = FileSet::getByName('user_gallery_'.$f->getUserID());
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
			$fileList->setItemsPerPage(6);
			$files = $fileList->getPage();
			$this->set('ih',Loader::helper('image'));
			$this->set('galleryImages', $files);
				
			
	}	
	

	
	
public function tag($tag=''){
		
		$fileList = new FileList();		
		$fileList->filterByTag($tag);
		$fileList->filterByType(FileType::T_IMAGE);	

		$fldca = new FileManagerAvailableColumnSet();

		$columns = new FileManagerColumnSet();

		$sortCol = $fldca->getColumnByKey('fDateAdded');
		$columns->setDefaultSortColumn($sortCol, 'desc');

		
		$columns = FileManagerColumnSet::getCurrent();
		

		$col = $columns->getDefaultSortColumn();	
		$fileList->sortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());
		
		$fileList->setItemsPerPage(30);
		$files = $fileList->getPage();
		$fileList->getPagination();
		
		$ih =Loader::helper('image');
		$mainGalleryList = '';
		foreach($files as $img){
			
			$fv = $img->getApprovedVersion();
			$imgt = $fv->getTitle();
			$imgThumb = $ih->getThumbnail($img,300,300);
			
			$imgH = $imgThumb->height;
			$imgW = $imgThumb->width;
			$topOffset = (112 - $imgH)/2;
			$leftOffset = (112 - $imgW)/2;
			
			$imgui = UserInfo::getByID($img->getUserID());
			if ($imgui->getAttribute('first_name') == ''){
				$username =  $imgui->getUserName();
			}else{
				$username = $imgui->getAttribute('first_name').' '.$imgui->getAttribute('last_name');
			}
			$mainGalleryList .='<div class="gbx-gallery-item"><div class="gbximgwrapper"><a href="'.View::url('/gallerybox/image',$img->getFileID()).'" rel="poptool" title="'.$username.': '.htmlspecialchars($imgt).'">';
			$mainGalleryList .= '<div><img src="'.$imgThumb->src.'" width="'.$imgThumb->width.'" height="'.$imgThumb->height.'" title="'.str_replace('"',"'",$imgt).'" style="margin-top:'.intval($topOffset).'px;margin-left:'.intval($leftOffset).'px"/></div></a></div></div>';
				
		}
		$mainGalleryList .='<div class="clearfix"></div>';
		$this->set('taggedImgList', $mainGalleryList);
		$this->set('paging',$fileList);
		$this->set('tagword', $tag);
	}
	
	public function userset($fsID=NULL){
		
		if($fsID==NULL){
			$this->redirect('/gallerybox/');		
		}

		$fileList = new FileList();	
		$fs = FileSet::getByID($fsID);
		$fileList->filterBySet($fs);
		$fileList->filterByType(FileType::T_IMAGE);	
		
		$fldca = new FileManagerAvailableColumnSet();

		$columns = new FileManagerColumnSet();

		$sortCol = $fldca->getColumnByKey('fDateAdded');
		$columns->setDefaultSortColumn($sortCol, 'desc');

		
		$columns = FileManagerColumnSet::getCurrent();
		

		$col = $columns->getDefaultSortColumn();	
		$fileList->sortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());
		
		$files = $fileList->get();
		
		$ih =Loader::helper('image');
		$setDisplay = '<div id="gbx-user-set">';
			$i=1;
			foreach($files as $img){

			$fv = $img->getApprovedVersion();
			$imgt = $fv->getTitle();
			$imgd = $fv->getDescription();
			$imgCover = $ih->getThumbnail($img,600,600);
			$imgThumb = $ih->getThumbnail($img,180,180);
			
			
			$imgH = $imgThumb->height;
			$imgW = $imgThumb->width;
			$topOffset = (69 - $imgH)/2;
			$leftOffset = (69 - $imgW)/2;
			
			
			$imgCH = $imgCover->height;
			$imgCW = $imgCover->width;
			$topCoverOffset = (219 - $imgCH)/2;
			$leftCoverOffset = (219 - $imgCW)/2;
						
			$imgui = UserInfo::getByID($img->getUserID());
			if ($imgui->getAttribute('first_name') == ''){
				$username =  $imgui->getUserName();
			}else{
				$username = $imgui->getAttribute('first_name').' '.$imgui->getAttribute('last_name');
			}
			
			$title = substr(htmlspecialchars($imgt),0,24);
			if (strlen($imgt) > 24){
				$title .= '...';
			}
			
				if($i==1){
        $setDisplay .='<div class="user-set-cover">';
        $setDisplay .='<div><a rel="overpop" data-placement="right" data-content="'.htmlspecialchars(str_replace('"',"'",$imgd)).'" title="'.str_replace('"',"'",$title).'" href="'.View::url('/gallerybox/image',$img->getFileID()).'"><div><img src="'.$imgCover->src.'" style="margin-top:'.intval($topCoverOffset).'px;margin-left:'.intval($leftCoverOffset).'px"/></div></a>';
        $setDisplay .='</div>';
					
        $setDisplay .='<div class="user-set-gall">';
				}else{
        
        $setDisplay .='<div><a rel="overpop" data-placement="below" data-content="'.htmlspecialchars(str_replace('"',"'",$imgd)).'" title="'.str_replace('"',"'",$title).'" href="'.View::url('/gallerybox/image',$img->getFileID()).'"><div><img src="'.$imgThumb->src.'" style="margin-top:'.intval($topOffset).'px;margin-left:'.intval($leftOffset).'>px"/></div></a></div>';  
					
				 }
			
			$i++;
			}
		$setDisplay .= '</div></div>';
		$this->set('setDisplay', $setDisplay);
		$this->set('setOwner',$imgui);
		$this->set('fsID',$fsID);
	
	}
	
	public function user($uID=NULL){
		if($uID==NULL){
			$this->redirect('/gallerybox/');		
		}
		$userList = new FileList();	
		$fs = FileSet::getByName('user_gallery_'.$uID);
		$userList->filterBySet($fs);
		$userList->filterByType(FileType::T_IMAGE);	

		$fldca = new FileManagerAvailableColumnSet();

		$columns = new FileManagerColumnSet();

		$sortCol = $fldca->getColumnByKey('fDateAdded');
		$columns->setDefaultSortColumn($sortCol, 'desc');

		
		$columns = FileManagerColumnSet::getCurrent();
		

		$col = $columns->getDefaultSortColumn();	
		$userList->sortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());
		
		$userList->setItemsPerPage(12);
		$userFiles = $userList->getPage();
		if(count($userFiles) == 0){
			$this->redirect('/gallerybox/');		
		}
		$userList->getPagination();
		$ih =Loader::helper('image');
		$wm = Loader::helper('watermark','gallerybox');
		$userFilesDisplay = '';
		$i=1;
		foreach($userFiles as $img){

			$fv = $img->getApprovedVersion();
			$imgt = $fv->getTitle();
			$imgd = $fv->getDescription();
			$imgThumb = $ih->getThumbnail($img,460,460);
			$imgH = $imgThumb->height;
			$imgW = $imgThumb->width;
			$topOffset = (174 - $imgH)/2;
			$leftOffset = (174 - $imgW)/2;
			$imgui = UserInfo::getByID($img->getUserID());
			if ($imgui->getAttribute('first_name') == ''){
				$username =  $imgui->getUserName();
			}else{
				$username = $imgui->getAttribute('first_name').' '.$imgui->getAttribute('last_name');
			}
			$userFilesDisplay .= '<div class="gbx-user-item';
			if(($i%3)==0){
				$userFilesDisplay .= ' pull-right';
			}
			
			$userFilesDisplay .='">';
			$userFilesDisplay .= '<a href="'.View::url('/gallerybox/image',$img->getFileID()).'"><h4>'.substr($imgt,0,16);
			if (strlen($imgt) > 16){
				$userFilesDisplay .='...';
			}
			$userFilesDisplay .='</h4></a>';
			$userFilesDisplay .= '<div class="gbximgwrapper"><a href="'.View::url('/gallerybox/image',$img->getFileID()).'">';
			
			if(Config::get('GBX_WATERMARK')	 > 0){
					$userFilesDisplay .= '<div><img src="'.$wm->watermark($imgThumb->src).'" width="'.$imgThumb->width.'" height="'.$imgThumb->height.'" title="'.str_replace('"',"'",$imgt).'" style="margin-top:'.intval($topOffset).'px;margin-left:'.intval($leftOffset).'px"/></div></a></div>';
				}else{
					$userFilesDisplay .= '<div><img src="'.$imgThumb->src.'" width="'.$imgThumb->width.'" height="'.$imgThumb->height.'" title="'.str_replace('"',"'",$imgt).'" style="margin-top:'.intval($topOffset).'px;margin-left:'.intval($leftOffset).'px"/></div></a></div>';
				}
			
			
			$userFilesDisplay .= '<div class="clearfix"></div>';

			
			
			
			$userFilesDisplay .= '<div class="imgcomm">';
			
			if(Config::get('GBX_ALLOW_COMMENTS') == '1'){
				$commnum = count($this->getComments($img->getFileID()));
				$userFilesDisplay .= '<a href="'.View::url('/gallerybox/image',$img->getFileID()).'#gbx-user-comments">'.$commnum.' comment';
				if($commnum !=1){
					$userFilesDisplay .='s';
				}
				$userFilesDisplay .='</a>';
			}
			
			if(Config::get('GBX_ALLOW_NOTES') == '1' && Config::get('GBX_ALLOW_COMMENTS') == '1'){
				$userFilesDisplay .=' &#8226; ';
			}
			
			if(Config::get('GBX_ALLOW_NOTES') == '1'){
				$notesnum = count($this->getNotes($img->getFileID()));
				$userFilesDisplay .=$notesnum.' note';
				if($notesnum !=1){
					$userFilesDisplay .= 's';
				}
			}
			$userFilesDisplay .= '</div>';
			
			
      $userFilesDisplay .= '<div class="imgdate">Added on '.date('F j, Y',strtotime($img->getDateAdded())).'</div>';
			$userFilesDisplay .= '</div>';
				$i++;
		}

		$userFilesDisplay .='<div class="clearbox"></div>';
		$this->set('userFilesDisplay', $userFilesDisplay);
		$this->set('userPaging',$userList);
		$this->set('user',$imgui);
	
	}
	
	public function search(){
		$fs = FileSet::getByName('GalleryBoxCollection');
		$fileList = new FileList();	
		$fileList->filterBySet($fs);
		$fileList->filterByKeywords($this->post('keywords'));
		$fileList->filterByType(FileType::T_IMAGE);	

		$fldca = new FileManagerAvailableColumnSet();

		$columns = new FileManagerColumnSet();

		$sortCol = $fldca->getColumnByKey('fDateAdded');
		$columns->setDefaultSortColumn($sortCol, 'desc');

		
		$columns = FileManagerColumnSet::getCurrent();
		

		$col = $columns->getDefaultSortColumn();	
		$fileList->sortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());
		
		$fileList->setItemsPerPage(30);
		$files = $fileList->getPage();
		$fileList->getPagination();
		
		$ih =Loader::helper('image');
				$mainGalleryList = '';
		foreach($files as $img){
			
			$fv = $img->getApprovedVersion();
			$imgt = $fv->getTitle();
			$imgThumb = $ih->getThumbnail($img,300,300);
			
			$imgH = $imgThumb->height;
			$imgW = $imgThumb->width;
			$topOffset = (112 - $imgH)/2;
			$leftOffset = (112 - $imgW)/2;
			
			$imgui = UserInfo::getByID($img->getUserID());
			if ($imgui->getAttribute('first_name') == ''){
				$username =  $imgui->getUserName();
			}else{
				$username = $imgui->getAttribute('first_name').' '.$imgui->getAttribute('last_name');
			}
			$mainGalleryList .='<div class="gbx-gallery-item"><div class="gbximgwrapper"><a href="'.View::url('/gallerybox/image',$img->getFileID()).'" rel="poptool" title="'.$username.': '.htmlspecialchars($imgt).'">';
			$mainGalleryList .= '<div><img src="'.$imgThumb->src.'" width="'.$imgThumb->width.'" height="'.$imgThumb->height.'" title="'.str_replace('"',"'",$imgt).'" style="margin-top:'.intval($topOffset).'px;margin-left:'.intval($leftOffset).'px"/></div></a></div></div>';
				
		}
		$mainGalleryList .='<div class="clearfix"></div>';
		$this->set('searchResultsList', $mainGalleryList);
		$this->set('searchPaging',$fileList);
		$this->set('keywords', $this->post('keywords'));
	}
	
	public function getDisplayedSets($fID){
		
      	$f = File::getByID($fID);
		$sets = $f->getFileSets();
		$fuID = $f->getUserID();
		
		foreach($sets as $set){
			if ($set->getFileSetName() != 'user_gallery_'.$fuID && $set->getFileSetName() !=  '' && $set->getFileSetName() != 'GalleryBoxCollection'){
				$setList.= '<a href="'.View::url('/gallerybox/userset').$set->getFileSetID().'">'.$set->getFileSetName().'</a>';
				
			}
		}
		
		
		print $setList;
	}
	
	public function getUserSets($uID){

		$db = Loader::db();
		$sets = array();
		$r = $db->Execute("select * from FileSets where  uID = '$uID' order by fsName asc");
		while ($row = $r->FetchRow()) {
			$fs = new FileSet();
			foreach($row as $key => $value) {
				$fs->{$key} = $value;
			}

				$sets[] = $fs;

		}
		$setList = '';
		foreach($sets as $set){
		if ($set->getFileSetName() != 'user_gallery_'.$uID && $set->getFileSetName() !=  '' && $set->getFileSetName() != 'GalleryBoxCollection'){
			$setList.= '<a href="'.View::url('/gallerybox/userset').$set->getFileSetID().'">'.$set->getFileSetName().'</a>';
				
			}
		}
		
		
		print $setList;
		}
	
	private function truncateDesc($string, $limit, $break=' ', $pad='...') {
		if(strlen($string) <= $limit) return $string;
			if(false !== ($breakpoint = strpos($string, $break, $limit))) { 
				if($breakpoint < strlen($string) - 1) { 
					$string = substr($string, 0, $breakpoint) . $pad; 
				} 
		} 
		return $string; 
	}
	
	
	public function getUserImage($fID){
		
		$ih =Loader::helper('image');
		$wm = Loader::helper('watermark','gallerybox');
      	$f = File::getByID($fID); 
		$fv = $f->getApprovedVersion();
		$imgt = $fv->getTitle();
		$fullpath = $fv->getPath();
		$imgd= $fv->getDescription();
		$zoomimage = $ih->getThumbnail($f,Config::get(GBX_MAX_ZOOM_WIDTH),Config::get(GBX_MAX_ZOOM_HEIGHT));
		
		switch(Config::get(GBX_GALLERY_INNER_CLASS)){
			case '8':
			$imgMax='460';
			break;
			case '9':
			$imgMax='520';
			break;
			case '10':
			$imgMax='580';
			break;
			case '11':
			$imgMax='640';
			break;
			case '12':
			$imgMax='700';
			break;
			case '13':
			$imgMax='760';
			break;
			case '14':
			$imgMax='820';
			break;
		}


		$imgThumb = $ih->getThumbnail($f,$imgMax,$imgMax);
		
		if(Config::get('GBX_WATERMARK')	 > 0){
			$gbxImg = '<img id="gbxImg"  src="'.$wm->watermark($imgThumb->src).'" width="'.$imgThumb->width.'" height="'.$imgThumb->height.'" />';
		}else{
			$gbxImg = '<img id="gbxImg"  src="'.$imgThumb->src.'" width="'.$imgThumb->width.'" height="'.$imgThumb->height.'" />';
		}
		
		
		$gbxImg .= '<h3>'.$imgt.'</h3>';
		$gbxImg .= '<p>'.nl2br($imgd).'</p>';
		
		$gbxImg .='<div id="zoomImage">';
		if(Config::get('GBX_WATERMARK')	 > 0){
			$gbxImg .='<img src="'.$wm->watermark($zoomimage->src).'" alt="'.$imgt.'" width="'.$zoomimage->width.'" height="'.$zoomimage->height.'"/>';
		}else{
			$gbxImg .='<img src="'.$zoomimage->src.'" alt="'.$imgt.'" width="'.$zoomimage->width.'" height="'.$zoomimage->height.'"/>';
		}
		
		$gbxImg .='<p id="zoomImage_caption">'.$imgt.'</p>';
		$gbxImg .='</div>';
		
		print $gbxImg;
			
	}
	
	public function getEmbedCode($fID){
		
		$ih =Loader::helper('image');
      	$f = File::getByID($fID); 
		$fv = $f->getApprovedVersion();
		$imgt = $fv->getTitle();
		$imgThumb = $ih->getThumbnail($f,350,400);
		$gbxImgCode = '<textarea class="embed-text" onFocus="this.select()">';
		$gbxImgCode .= '<a href="'.BASE_URL.DIR_REL.'/index.php/gallerybox/image/'.$fID.'" alt="'.$imgt.'" title="'.$imgt.'">';
		$gbxImgCode .= '<img src="'.BASE_URL.$imgThumb->src.'" width="'.$imgThumb->width.'" height="'.$imgThumb->height.'" border="0"/>';
		$gbxImgCode .= '</a>';
		$gbxImgCode .= '</textarea>';
		print $gbxImgCode;
			
	}
	
	public function getUserTags($uID){
		$fileList = new FileList();	
		$fs = FileSet::getByName('user_gallery_'.$uID);
		$fileList->filterBySet($fs);
		$fileList->filterByType(FileType::T_IMAGE);	
		$files = $fileList->get($itemsToGet = 1000, $offset = 0);
		


		foreach($files as $img){
			$fv = $img->getApprovedVersion(); 
			$tags = explode("\n",rtrim(ltrim($fv->getTags())));
			foreach($tags as $tag){
				if($tag != ''){
				$myTags[]=$tag;
				}
			}

		}
		if(is_array($myTags)){
			sort($myTags);
			$finalTags = array_unique($myTags);
			foreach($finalTags as $t){
				if($t !=''){
					$tagList.='<a href="'.View::url('/gallerybox/tag').$t.'">'.$t.'</a>';
				}
			}
		}
		
		
		print $tagList;
		
	}
	
	public function getPrev($fuID, $fID){
		Loader::model('file_list');
		$fl = new FileList();
		$fs = FileSet::getByName('user_gallery_'.$fuID);
		$fl->filterBySet($fs);
		$fl->sortBy('fDateAdded', 'desc');
		$files = $fl->get($itemsToGet = 1000, $offset = 0);
		$fileIndex = array();
		foreach($files as $file){
			
			if($file->getFileID() == $fID){
				$prev = end($fileIndex);
			}
			
			array_push($fileIndex,$file->getFileID());
		}
		return $prev;	

	}
	
	public function getNext($fuID, $fID){
		Loader::model('file_list');
		$fl = new FileList();
		$fs = FileSet::getByName('user_gallery_'.$fuID);
		$fl->filterBySet($fs);
		$fl->sortBy('fDateAdded', 'desc');
		$files = $fl->get($itemsToGet = 1000, $offset = 0);
		$fileIndex = array();
		$i=0;
		foreach($files as $file){	
		
			array_push($fileIndex,$file->getFileID());
			
			if($i==1){
				$next = end($fileIndex);
				break;
			}
			
			if($file->getFileID() == $fID){
				$i=1;
			}
			
		}
		return $next;	
	}
	
	public function getImageTags($fID){
		$img = File::getByID($fID);
		$fv = $img->getApprovedVersion(); 
		$tags = explode("\n",rtrim($fv->getTags()));
		$tagList='';
		foreach($tags as $tag){
			if($tag !=''){
				$tagList.='<a href="'.View::url('/gallerybox/tag').$tag.'">'.$tag.'</a>';
			}
		}
		
		
		print $tagList;
		
	}
	
	public function add_note($fID){
		$u = new User();
		$ui = UserInfo::getByID($u->getUserID());
		if ($ui->getAttribute('first_name') == ''){
			$username =  $ui->getUserName();
		}else{
			$username = $ui->getAttribute('first_name').' '.$ui->getAttribute('last_name');
		}
		$data = $this->post('data');
		
		$f = File::getByID($fID);
		if($f->getUserID() == $u->getUserID()){		
		$noteData = '{"x1":"'.$data['Note']['x1'].'","y1":"'.$data['Note']['y1'].'","height":"'.$data['Note']['height'].'","width":"'.$data['Note']['width'].'","note":"<div class=\'note-by-owner\'>'.str_replace('"',"'",$data['Note']['note']).'</div>"}';	
		}else{
		$noteData = '{"x1":"'.$data['Note']['x1'].'","y1":"'.$data['Note']['y1'].'","height":"'.$data['Note']['height'].'","width":"'.$data['Note']['width'].'","note":"<div class=\'note-by-user\'>'.str_replace('"',"'",$data['Note']['note']).'<br/>-<em>'.$username.'</em></div>"}';
		}
		
		$data = array('fID' => $fID, 'notes' => $noteData);
		
		// ***** LERTECO_WALL
		//add a notification to the user's wall if the lerteco_wall add-on is installed
		$wall = Loader::package('lerteco_wall');
		if (is_object($wall)) {
			
		$owner = UserInfo::getByID($f->getUserID());
		if ($owner->getAttribute('first_name') == ''){
			$ownername =  $owner->getUserName();
		}else{
			$ownername = $owner->getAttribute('first_name').' '.$owner->getAttribute('last_name');
		}
			
		//it's installed
		$wall_link = '<a href="'.View::url('/gallerybox/image',$fID).'">'.$ownername.'\'s image</a>';
		// ideally we would register a posting type on install, then log the post
		// but lerteco_wall might not have been installed when we were installed
		// so we'll pass all the post and type data at once and let lerteco_wall figure out what to do
		// there's a shortcut function for this, inside the package controller, so we don't have to include anything else
		$wall->postAndPossiblyRegister($u->getUserID(), $wall_link, $this->posting_type_note);
							
							
		}
			

		$this->saveNote($data);
		$this->redirect(View::url('/gallerybox/image/'.$fID));
	

	}
	
	public function delete_note($fID) {
		
     	$noteID = $this->post('noteID');
		$coor=split("_",$noteID);
		$X = $coor[0];
		$Y = $coor[1];
		$compStatement = '{"x1":"'.$X.'","y1":"'.$Y.'",';
	 	$db = Loader::db();
		$in = $db->execute("DELETE from GalleryBoxNotes where fID = '$fID' AND notes LIKE '%$compStatement%'");
		$this->redirect(View::url('/gallerybox/image/'.$fID));
	}
	
	public function add_comment($fID){
		$u = new User();		

		$data = array('fID' => $fID, 'comUID' => $this->post('comUID'), 'imgComment' => $this->post('imgComment'));
		
		// ***** LERTECO_WALL
		//add a notification to the user's wall if the lerteco_wall add-on is installed
		$wall = Loader::package('lerteco_wall');
		if (is_object($wall)) {
		$f = File::getByID($fID);	
		$owner = UserInfo::getByID($f->getUserID());
		if ($owner->getAttribute('first_name') == ''){
			$ownername =  $owner->getUserName();
		}else{
			$ownername = $owner->getAttribute('first_name').' '.$owner->getAttribute('last_name');
		}
			
		//it's installed
		$wall_link = '<a href="'.View::url('/gallerybox/image',$fID).'">'.$ownername.'\'s image</a>';
		// ideally we would register a posting type on install, then log the post
		// but lerteco_wall might not have been installed when we were installed
		// so we'll pass all the post and type data at once and let lerteco_wall figure out what to do
		// there's a shortcut function for this, inside the package controller, so we don't have to include anything else
		$wall->postAndPossiblyRegister($u->getUserID(), $wall_link, $this->posting_type_comment);
							
							
		}

		$this->saveComment($data);
		$this->redirect(View::url('/gallerybox/image/'.$fID.'#gbx-user-comments'));
	}
	
	private function saveNote($data) {

		$db= Loader::db();
		$q = ("INSERT INTO GalleryBoxNotes (fID, notes) VALUES (?,?)");
		$db->EXECUTE($q,$data);

	}
	
	private function saveComment($data) {

		$db= Loader::db();
		$q = ("INSERT INTO GalleryBoxComments (fID, uID, commentText) VALUES (?,?,?)");
		$db->EXECUTE($q,$data);

	}
	
	public function getNotes($fID){
		
		$db = Loader::db();
		$in = $db->query("SELECT notes FROM GalleryBoxNotes where fID = '$fID'");
		while($row=$in->fetchrow()){
			$notes[] = $row;
		}		

		return $notes;
	}
	

	
	
	public function delete_comment($fID) {
		
     	$commentID = $this->post('comID');
	 	$db = Loader::db();
		$ic = $db->execute("DELETE from GalleryBoxComments where commentID = '$commentID'");
		$this->redirect(View::url('/gallerybox/image/'.$fID.'#gbx-user-comments'));
	}
	
	public function getComments($fID){
		$db = Loader::db();
		$ic = $db->query("SELECT * FROM GalleryBoxComments where fID = '$fID'");
		while($row=$ic->fetchrow()){
			$comments[] = $row;
		}		

		return $comments;
	}
	
	public function userFavsThis($u, $fID) {
		if (!$u->isRegistered()) {
			return false;
		}
		$db = Loader::db();
		$uID = $u->getUserID();
		$hasMarked = $db->getOne("SELECT uID FROM GalleryBoxFavs WHERE uID = ? AND fID = ?",array($uID, $fID));
		return $hasMarked > 0;
	}
	
	public function add_fav($fID) {
		$u = new User();
		if($u->isLoggedIn() && $u->getUserID() > 0) {
 			$this->markFav($fID, $u->getUserID());
		}

                // ***** LERTECO_WALL
                //add a notification to the user's wall if the lerteco_wall add-on is installed
                $wall = Loader::package('lerteco_wall');
				
                if (is_object($wall)) {
                    //it's installed
					$f = File::getByID($fID); 
					$fv = $f->getApprovedVersion();
					$imgt = $fv->getTitle();
					$wall_link = '<a href="'.View::url('/gallerybox/image',$fID).'">'.$imgt.'</a>';
                    // ideally we would register a posting type on install, then log the post
                    // but lerteco_wall might not have been installed when we were installed
                    // so we'll pass all the post and type data at once and let lerteco_wall figure out what to do
                    // there's a shortcut function for this, inside the package controller, so we don't have to include anything else
                    $wall->postAndPossiblyRegister($u->getUserID(), $wall_link, $this->posting_type_fav);
                }


			$this->redirect(View::url('/gallerybox/image',$fID.'#gbx-user-comments'));

	}
	
	public function getUsersThatFav($fID) {

		$db = Loader::db();

		$res = $db->query("SELECT * FROM GalleryBoxFavs WHERE fID = ?",array($fID));
		while($row=$res->fetchrow()){
			$favusers[] = $row;
		}	
		return $favusers;
	} 

	public function markFav($fID, $uID) {
		$db = Loader::db();
		$has_marked = $db->getOne("SELECT uID FROM GalleryBoxFavs WHERE uID = ? AND fID = ?",array($uID, $fID));
		
		if($has_marked <= 0) {
			$db->query("REPLACE INTO GalleryBoxFavs (uID, fID) VALUES (?,?)",array($uID, $fID));
		}
	}
	
	

	
}
