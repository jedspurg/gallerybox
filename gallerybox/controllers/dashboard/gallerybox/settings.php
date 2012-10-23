<?php    
defined('C5_EXECUTE') or die("Access Denied.");

class DashboardGalleryboxSettingsController extends DashboardBaseController { 

	

	public function view() {
		
		$this->set('gbx_width_outer',Config::get('GBX_GALLERY_OUTER_CLASS'));
		$this->set('gbx_width',Config::get('GBX_GALLERY_INNER_CLASS'));
		$this->set('gbx_zoom_width',Config::get('GBX_MAX_ZOOM_WIDTH'));
		$this->set('gbx_zoom_height',Config::get('GBX_MAX_ZOOM_HEIGHT'));
		$this->set('gbx_allow_notes',Config::get('GBX_ALLOW_NOTES'));
		$this->set('gbx_allow_embed',Config::get('GBX_ALLOW_EMBED'));
		$this->set('gbx_allow_download',Config::get('GBX_ALLOW_DOWNLOAD'));
		$this->set('gbx_allow_comments',Config::get('GBX_ALLOW_COMMENTS'));
		$this->set('gbx_allow_rss',Config::get('GBX_ALLOW_RSS'));
		$this->set('gbx_watermark',Config::get('GBX_WATERMARK'));
		$this->set('gbx_watermark_img',File::getByID(intval(Config::get('GBX_WATERMARK_IMG'))));
	
	}
	
	public function save_settings() {
		
		if($this->post('gbx_width') != ''){
			Config::save('GBX_GALLERY_INNER_CLASS', $this->post('gbx_width'));
		}
		if($this->post('gbx_zoom_width') != ''){
			Config::save('GBX_MAX_ZOOM_WIDTH', $this->post('gbx_zoom_width'));
		}
		if($this->post('gbx_zoom_height') != ''){
			Config::save('GBX_MAX_ZOOM_HEIGHT', $this->post('gbx_zoom_height'));
		}
		
		Config::save('GBX_ALLOW_NOTES', $this->post('gbx_allow_notes'));
		Config::save('GBX_ALLOW_EMBED', $this->post('gbx_allow_embed'));
		Config::save('GBX_ALLOW_DOWNLOAD', $this->post('gbx_allow_download'));
		Config::save('GBX_ALLOW_COMMENTS', $this->post('gbx_allow_comments'));
		Config::save('GBX_ALLOW_RSS', $this->post('gbx_allow_rss'));
		Config::save('GBX_WATERMARK', $this->post('gbx_watermark'));
		Config::save('GBX_WATERMARK_IMG', $this->post('gbx_watermark_img'));
		
		$this->set('message', t('Settings saved.'));
		$this->view();
		
		
		
			
	}
	
	
	
	
}
