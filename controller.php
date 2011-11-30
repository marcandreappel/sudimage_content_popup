<?php    

defined('C5_EXECUTE') or die(_("Access Denied."));

class SudimageContentPopupPackage extends Package {

	protected $pkgHandle = 'sudimage_content_popup';
	protected $appVersionRequired = '5.4';
	protected $pkgVersion = '0.2.0';
	
	public function getPackageDescription() {
		return t("Add an universal modal pop-up.");
	}
	
	public function getPackageName() {
		return t("Sudimage Pop-Up");
	}
	
	public function install() {
		$pkg = parent::install();
		
		// install block		
		BlockType::installBlockTypeFromPackage('sudimage_content_popup', $pkg);
		
	}
	
}