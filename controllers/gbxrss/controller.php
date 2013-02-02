<?php   defined('C5_EXECUTE') or die("Access Denied."); 

		Loader::helper('concrete/file');
		Loader::model('file_attributes');
		Loader::library('file/types');
		Loader::model('file_list');
		Loader::model('file_set');
		
class GbxrssController extends Controller {
	
public function view($uID=NULL){
$email_strip = array("http://", "https://", "www.");
		
$userFiles = $this->getImages($uID);

header('Content-type: text/xml');
		
		//use object buffer to get XML for caching 
		ob_start(); 
		echo "<?php   xml version=\"1.0\"?>\n"; 
		?>
		<rss version="2.0" 
    		 xmlns:media="http://search.yahoo.com/mrss/"
         xmlns:atom="http://www.w3.org/2005/Atom"
         xmlns:content="http://purl.org/rss/1.0/modules/content/"
         xmlns:fh="http://purl.org/syndication/history/1.0">
			<channel>
      <?php   if (User::getByUserID($uID)->isRegistered()){
		  $rssui = UserInfo::getByID($uID);
		  $av = Loader::helper('concrete/avatar');
		  if ($rssui->getAttribute('first_name') == ''){
				$username =  $rssui->getUserName();
			}else{
				$username = $rssui->getAttribute('first_name').' '.$rssui->getAttribute('last_name');
			}
	  ?>
                <title>Gallery images from <?php   echo $username?></title>
                <link><?php   echo BASE_URL.View::url('/gallerybox/user',$uID)?></link>
                <description></description>
                <pubDate><?php   echo  date("D, d M Y H:i:s T") ?></pubDate>
                <lastBuildDate><?php   echo  date("D, d M Y H:i:s T") ?></lastBuildDate>
                <generator><?php   echo BASE_URL.DIR_REL?></generator>
                <image>
                  <url><?php   echo BASE_URL.$av->getImagePath($rssui)?></url>
                  <title>Gallery images from <?php   echo $username?></title>
                  <link><?php   echo BASE_URL.View::url('/profile',$uID)?></link>
                </image>
              

      <?php   }else{?>
      		  <title>Gallery images from <?php   echo SITE?></title>
              <link><?php   echo BASE_URL.DIR_REL?></link>
              <description></description>
              <pubDate><?php   echo  date("D, d M Y H:i:s T") ?></pubDate>
              <lastBuildDate><?php   echo  date("D, d M Y H:i:s T") ?></lastBuildDate>
              <generator><?php   echo BASE_URL.DIR_REL?></generator>
      
      <?php   }?>
    <?php   
		$ih =Loader::helper('image');

		foreach($userFiles as $img){

			$fv = $img->getApprovedVersion();
			$imgt = $fv->getTitle();
			$imgd = $fv->getDescription();
			$imgFull = $ih->getThumbnail($img,600,600);
			$imgThumb = $ih->getThumbnail($img,75,75);
			$imgui = UserInfo::getByID($img->getUserID());
			if ($imgui->getAttribute('first_name') == ''){
				$username =  $imgui->getUserName();
			}else{
				$username = $imgui->getAttribute('first_name').' '.$imgui->getAttribute('last_name');
			}
		?>
    
    		<item>
        <title><?php   echo htmlspecialchars($imgt)?></title>
        <link><?php   echo BASE_URL.View::url('/gallerybox/image',$img->getFileID())?></link>
        <description>&lt;img src=&quot;<?php   echo BASE_URL.$imgFull->src?>&quot; width=&quot;<?php   echo $imgFull->width?>&quot; height=&quot;<?php   echo $imgFull->height?>&quot; alt=&quot;<?php   echo htmlspecialchars($imgt)?>&quot; /&gt;&lt;/a&gt;&lt;p&gt;<?php   echo htmlspecialchars($imgd)?>&lt;/p&gt;</description>
        <pubDate><?php   echo  date("D, d M Y H:i:s T", strtotime($img->getDateAdded())) ?></pubDate>
        <author>nobody@<?php   echo str_replace($email_strip, '', BASE_URL)?> (<?php   echo $username?>)</author>
        <guid><?php   echo BASE_URL.View::url('/gallerybox/image',$img->getFileID())?></guid>
        <media:content url="<?php   echo BASE_URL.$imgFull->src?>" 
                     type="image/jpeg"
                     height="<?php   echo $imgFull->height?>"
                     width="<?php   echo $imgFull->width?>"/>
        <media:title><?php   echo htmlspecialchars($imgt)?></media:title>
        <media:thumbnail url="<?php   echo BASE_URL.$imgThumb->src?>" height="<?php   echo $imgThumb->height?>" width="<?php   echo $imgThumb->width?>" />
        <media:credit role="photographer"><?php   echo $username?></media:credit>
        </item>
     <?php   }?>
      </channel>
    </rss>  
<?php  		
		$rssXML = ob_get_contents();
		ob_end_clean();
		echo $rssXML; 
		die;
	}
	
	public function getImages($uID=NULL){

		$userList = new FileList();	
		if($uID==NULL){
			$fs = FileSet::getByName('GalleryBoxCollection');
		}else{
			$fs = FileSet::getByName('user_gallery_'.$uID);
		}
		$userList->filterBySet($fs);
		$userList->filterByType(FileType::T_IMAGE);	

		$fldca = new FileManagerAvailableColumnSet();

		$columns = new FileManagerColumnSet();

		$sortCol = $fldca->getColumnByKey('fDateAdded');
		$columns->setDefaultSortColumn($sortCol, 'desc');

		
		$columns = FileManagerColumnSet::getCurrent();
		

		$col = $columns->getDefaultSortColumn();	
		$userList->sortBy($col->getColumnKey(), $col->getColumnDefaultSortDirection());
		
		
		$userFiles = $userList->get(20);
		
		return $userFiles;
	
	}
	
	
}