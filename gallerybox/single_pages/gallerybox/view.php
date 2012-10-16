<?php   defined('C5_EXECUTE') or die("Access Denied."); ?>

<div class="gbx-body">	
	<?php  
      
      switch ($this->controller->getTask()) {
		  
          case 'userset':
          Loader::packageElement('set_view', 'gallerybox', array('setDisplay' => $setDisplay, 'paging' => $paging, 'setOwner' => $setOwner, 'fsID' => $fsID));
          break;
		  
		  case 'delete_comment':
          echo $commentID;
          break;
		  
		  case 'search':
          Loader::packageElement('search_view', 'gallerybox', array('searchResultsList' => $searchResultsList, 'searchPaging' => $searchPaging, 'keywords' => $keywords));
		  break;
		  
		  case 'user':
          Loader::packageElement('user_view', 'gallerybox', array('userFilesDisplay' => $userFilesDisplay, 'userPaging' => $userPaging, 'user' => $user));
          break;
		  
          case 'tag':
          Loader::packageElement('tag_view', 'gallerybox', array('taggedImgList' => $taggedImgList, 'paging' => $paging, 'tagword' => $tagword));
          break;
		  
          case 'image':
          Loader::packageElement('image_view', 'gallerybox', array('imgID' => $imgID, 'favu' => $favu, 'galleryImages'=>$galleryImages));
          break;
		  
		  default:
          Loader::packageElement('gallery_view', 'gallerybox', array('pagename' => $c->getCollectionName(),'gallery' => $gallery, 'paging' => $paging));
          break;
      }
    ?>
        
</div>