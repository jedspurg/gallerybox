<?php     

defined('C5_EXECUTE') or die(_("Access Denied."));

class GalleryboxPackage extends Package {

	protected $pkgHandle = 'gallerybox';
	protected $appVersionRequired = '5.5';
	protected $pkgVersion = '1.6.6';
	
	public function getPackageDescription() {
		return t('Adds a user gallery to your website.');
	}
	
	public function getPackageName() {
		return t('GalleryBox');
	}
	

	public function on_start() {  

	}
	
	public function install() {
		$pkg = parent::install();
		
		Loader::model('single_page');
		Loader::model('file_set');
		
		//Install single pages
		
		//Profile images page		
		$gbp=Page::getByPath('/profile/images');
		if( !is_object($gbp) || !intval($gbp->getCollectionID()) ){ 
			$gbp = SinglePage::add('/profile/images', $pkg);
		}
		if( is_object($gbp) && intval($gbp->getCollectionID())  ){
			 $gbp->update(array('cName'=>t('My Images')));	
		}else throw new Exception( t('Error: /profile/images page not created') );
		
		//GalleryBox main page
		$gbp=Page::getByPath('/gallerybox');
		if( !is_object($gbp) || !intval($gbp->getCollectionID()) ){ 
			$gbp = SinglePage::add('/gallerybox', $pkg);
		}
		if( is_object($gbp) && intval($gbp->getCollectionID())  ){
			 $gbp->update(array('cName'=>t('Gallery')));	
		}else throw new Exception( t('Error: /gallerybox page not created') );
		
		//GalleryBox dashboard settings page
		$gbp=Page::getByPath('/dashboard/gallerybox/');
		if( !is_object($gbp) || !intval($gbp->getCollectionID()) ){ 
			$gbp = SinglePage::add('/dashboard/gallerybox/', $pkg);
		}
		if( is_object($gbp) && intval($gbp->getCollectionID())  ){
			 $gbp->update(array('cName'=>t('GalleryBox'), 'cDescription'=>t("Configure user gallery settings")));	
		}else throw new Exception( t('Error: /dashboard/gallerybox/settings/ page not created') );
		
		$gbp=Page::getByPath('/dashboard/gallerybox/settings/');
		if( !is_object($gbp) || !intval($gbp->getCollectionID()) ){ 
			$gbp = SinglePage::add('/dashboard/gallerybox/settings/', $pkg);
		}
		if( is_object($gbp) && intval($gbp->getCollectionID())  ){
			 $gbp->update(array('cName'=>t('Settings'), 'cDescription'=>t("Configure user gallery settings")));	
		}else throw new Exception( t('Error: /dashboard/gallerybox/settings/ page not created') );
		
		//GalleryBox RSS feed
		$gbp=Page::getByPath('/gbxrss');
		if( !is_object($gbp) || !intval($gbp->getCollectionID()) ){ 
			$gbp = SinglePage::add('/gbxrss', $pkg);
		}
		if( is_object($gbp) && intval($gbp->getCollectionID())  ){
			 $gbp->update(array('cName'=>t('GalleryBox RSS'), 'cDescription'=>t("GalleryBox RSS")));
			 $gbp->setAttribute('exclude_nav',1);
			 $gbp->setAttribute('exclude_page_list',1);	
		}else throw new Exception( t('Error: /gbxrss page not created') );
		
		
		//default settings, if not set
		if( !intval(Config::get('GBX_GALLERY_OUTER_CLASS')) ) 
			Config::save('GBX_GALLERY_OUTER_CLASS', '13');
			
		if( !intval(Config::get('GBX_GALLERY_INNER_CLASS')) ) 
			Config::save('GBX_GALLERY_INNER_CLASS', '10');
			
		if( !intval(Config::get('GBX_MAX_ZOOM_WIDTH')) ) 
			Config::save('GBX_MAX_ZOOM_WIDTH', 900);
			
		if( !intval(Config::get('GBX_MAX_ZOOM_HEIGHT')) ) 
			Config::save('GBX_MAX_ZOOM_HEIGHT', 700);
			
		if( !intval(Config::get('GBX_ALLOW_NOTES')) ) 
			Config::save('GBX_ALLOW_NOTES', 1);
			
		if( !intval(Config::get('GBX_ALLOW_EMBED')) ) 
			Config::save('GBX_ALLOW_EMBED', 1);
			
		if( !intval(Config::get('GBX_ALLOW_DOWNLOAD')) ) 
			Config::save('GBX_ALLOW_DOWNLOAD', 1);
		
		if( !intval(Config::get('GBX_ALLOW_COMMENTS')) ) 
			Config::save('GBX_ALLOW_COMMENTS', 1);
			
		if( !intval(Config::get('GBX_ALLOW_RSS')) ) 
			Config::save('GBX_ALLOW_RSS', 1);
			
		if( !intval(Config::get('GBX_WATERMARK')) ) 
			Config::save('GBX_WATERMARK', 1);
	
			
		//Install blocks
		$gbx_rec = BlockType::getByHandle('gallerybox_recent');
		if(! is_object($gbx_rec)){
			BlockType::installBlockTypeFromPackage('gallerybox_recent', $pkg);
		}
		$gbx_fav = BlockType::getByHandle('gallerybox_favorites');
		if(! is_object($gbx_fav)){
			BlockType::installBlockTypeFromPackage('gallerybox_favorites', $pkg);
		}
		$gbx_srch = BlockType::getByHandle('gallerybox_search');
		if(! is_object($gbx_srch)){
			BlockType::installBlockTypeFromPackage('gallerybox_search', $pkg);
		}
		
		//Create the global GalleryBox file set. All user images will be added to this set. Makes sorting much easier!
		$fs = FileSet::createAndGetSet('GalleryBoxCollection', FileSet::TYPE_PUBLIC);
		
		//Set User permission to allow administering of their own files
		//$gfs = FileSet::getGlobal();
		//$usergroup = Group::getByID(2);
		
		//if(version_compare(APP_VERSION,'5.5.2.1', '>')){
			//$gfs->assignPermissions($usergroup, array(10, 10, 3, 3, 3), $extensions);
		//}else{
			//$gfs->setPermissions($usergroup, 10, 10, 3, 3, 3, $extensions);
		//}
		
		//Enable public profiles
		Config::save('ENABLE_USER_PROFILES', true);

	}
	
	
	public function upgrade() {
		
		Loader::model('single_page');
		Loader::model('file_set');
		$pkg = Package::getByHandle('gallerybox');
		
		//GalleryBox dashboard settings page
		$gbp=Page::getByPath('/dashboard/gallerybox/');
		if( !is_object($gbp) || !intval($gbp->getCollectionID()) ){ 
			$gbp = SinglePage::add('/dashboard/gallerybox/', $pkg);
		}
		if( is_object($gbp) && intval($gbp->getCollectionID())  ){
			 $gbp->update(array('cName'=>t('GalleryBox'), 'cDescription'=>t("Configure user gallery settings")));	
		}else throw new Exception( t('Error: /dashboard/gallerybox/settings/ page not created') );
		
		$gbp=Page::getByPath('/dashboard/gallerybox/settings/');
		if( !is_object($gbp) || !intval($gbp->getCollectionID()) ){ 
			$gbp = SinglePage::add('/dashboard/gallerybox/settings/', $pkg);
		}
		if( is_object($gbp) && intval($gbp->getCollectionID())  ){
			 $gbp->update(array('cName'=>t('Settings'), 'cDescription'=>t("Configure user gallery settings")));	
		}else throw new Exception( t('Error: /dashboard/gallerybox/settings/ page not created') );
		
		//GalleryBox RSS feed
		$gbp=Page::getByPath('/gbxrss');
		if( !is_object($gbp) || !intval($gbp->getCollectionID()) ){ 
			$gbp = SinglePage::add('/gbxrss', $pkg);
		}
		if( is_object($gbp) && intval($gbp->getCollectionID())  ){
			 $gbp->update(array('cName'=>t('GalleryBox RSS'), 'cDescription'=>t("GalleryBox RSS")));
			 $gbp->setAttribute('exclude_nav',1);
			 $gbp->setAttribute('exclude_page_list',1);	
		}else throw new Exception( t('Error: /gbxrss page not created') );
		
		
		//default settings, if not set
		if( !intval(Config::get('GBX_GALLERY_OUTER_CLASS')) ) 
			Config::save('GBX_GALLERY_OUTER_CLASS', '13');
			
		if( !intval(Config::get('GBX_GALLERY_INNER_CLASS')) ) 
			Config::save('GBX_GALLERY_INNER_CLASS', '10');
			
		if( !intval(Config::get('GBX_MAX_ZOOM_WIDTH')) ) 
			Config::save('GBX_MAX_ZOOM_WIDTH', 900);
			
		if( !intval(Config::get('GBX_MAX_ZOOM_HEIGHT')) ) 
			Config::save('GBX_MAX_ZOOM_HEIGHT', 700);
			
		if( !intval(Config::get('GBX_ALLOW_NOTES')) ) 
			Config::save('GBX_ALLOW_NOTES', 1);
			
		if( !intval(Config::get('GBX_ALLOW_EMBED')) ) 
			Config::save('GBX_ALLOW_EMBED', 1);
			
		if( !intval(Config::get('GBX_ALLOW_DOWNLOAD')) ) 
			Config::save('GBX_ALLOW_DOWNLOAD', 1);
		
		if( !intval(Config::get('GBX_ALLOW_COMMENTS')) ) 
			Config::save('GBX_ALLOW_COMMENTS', 1);
			
		if( !intval(Config::get('GBX_ALLOW_RSS')) ) 
			Config::save('GBX_ALLOW_RSS', 1);
			
		if( !intval(Config::get('GBX_WATERMARK')) ) 
			Config::save('GBX_WATERMARK', 1);
			
		//Install blocks
		$gbx_rec = BlockType::getByHandle('gallerybox_recent');
		if(! is_object($gbx_rec)){
			BlockType::installBlockTypeFromPackage('gallerybox_recent', $pkg);
		}
		$gbx_fav = BlockType::getByHandle('gallerybox_favorites');
		if(! is_object($gbx_fav)){
			BlockType::installBlockTypeFromPackage('gallerybox_favorites', $pkg);
		}
		$gbx_srch = BlockType::getByHandle('gallerybox_search');
		if(! is_object($gbx_srch)){
			BlockType::installBlockTypeFromPackage('gallerybox_search', $pkg);
		}
		
		//Set User permission to allow administering of their own files
		$gfs = FileSet::getGlobal();
		$usergroup = Group::getByID(2);
		$gfs->setPermissions($usergroup, 10, 10, 3, 3, 3, $extensions);
		
		Config::save('ENABLE_USER_PROFILES', true);
			
		parent::upgrade();
		

	}
	
	public function uninstall(){
			
		$db= Loader::db();
		$db->Execute("DROP TABLE btGalleryboxRecent");
		$db->Execute("DROP TABLE btGalleryboxFavorites");
		
		parent::uninstall();
	}
	

}

?>
