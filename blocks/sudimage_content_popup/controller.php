<?php 
	defined('C5_EXECUTE') or die("Access Denied.");	
	Loader::block('library_file');
	class SudimageContentPopupBlockController extends BlockController {

		protected $btInterfaceWidth = 300;
		protected $btInterfaceHeight = 520;
		protected $btTable = 'btSudimageContentPopup';

		/** 
		 * Used for localization. If we want to localize the name/description we have to include this
		 */
		public function getBlockTypeDescription() {
			return t("Add a content pop-up.");
		}
		
		public function getBlockTypeName() {
			return t("Content Pop-Up");
		}		
		
		public function getJavaScriptStrings() {
			return array(
				'image-required' => t('You must select an image.')
			);
		}
	
	
		function getFileID() {return $this->fID;}
		function getFileOnstateID() {return $this->fOnstateID;}
		function getFileOnstateObject() {
			if ($this->fOnstateID > 0) {
				return File::getByID($this->fOnstateID);
			}
		}
		function getFileObject() {
			return File::getByID($this->fID);
		}		
		function getAltText() {return $this->altText;}
		function getInternalLink() {return $this->internalLink;}
		function getExternalLink() {return $this->externalLink;}
		
		public function save($args) {	
			$args['externalLinkNewWindow'] = ($args['externalLinkNewWindow'] != 1) ? 0 : 1;
			$args['fOnstateID'] = ($args['fOnstateID'] != '') ? $args['fOnstateID'] : 0;
			$args['fID'] = ($args['fID'] != '') ? $args['fID'] : 0;
			$args['maxWidth'] = (intval($args['maxWidth']) > 0) ? intval($args['maxWidth']) : 0;
			$args['maxHeight'] = (intval($args['maxHeight']) > 0) ? intval($args['maxHeight']) : 0;
			parent::save($args);
		}

		function getContentAndGenerate($align = false, $style = false, $id = null) {
			$v = View::getInstance();		
			$c = Page::getCurrentPage();
			$bID = $this->bID;
			
			$f = $this->getFileObject();
			$fullPath = $f->getPath();
			$relPath = $f->getRelativePath();			
			$size = @getimagesize($fullPath);
			
			if ($this->maxWidth > 0 || $this->maxHeight > 0) {
				$mw = $this->maxWidth > 0 ? $this->maxWidth : $size[0];
				$mh = $this->maxHeight > 0 ? $this->maxHeight : $size[1];
				$ih = Loader::helper('image');
				$thumb = $ih->getThumbnail($f, $mw, $mh);
				$sizeStr = ' width="' . $thumb->width . '" height="' . $thumb->height . '"';
				$relPath = $thumb->src;
			} else {
				$sizeStr = $size[3];
			}
			
			$img = "<img border=\"0\" class=\"ccm-image-block\" alt=\"{$this->altText}\" src=\"{$relPath}\" {$sizeStr} ";
			$img .= ($align) ? "align=\"{$align}\" " : '';
			
			$img .= ($style) ? "style=\"{$style}\" " : '';
			if($this->fOnstateID != 0) {
				$fos = $this->getFileOnstateObject();
				
				if ($this->maxWidth > 0 || $this->maxHeight > 0) {
					$thumbHover = $ih->getThumbnail($fos, $mw, $mh);				
					$relPathHover = $thumbHover->src;
				} else {
					$relPathHover = $fos->getRelativePath();
				}

				$img .= " onmouseover=\"this.src = '{$relPathHover}'\" ";
				$img .= " onmouseout=\"this.src = '{$relPath}'\" ";
			}
			
			$img .= ($id) ? "id=\"{$id}\" " : "";
			$img .= "/>";
			if($this->externalLink) {
				$ajax_url = $this->externalLink;
				$img = "<a href=\"{$ajax_url}\" class=\"fancybox-link iframe\">" . $img ."</a>";
			} else {
				$ajax_url = View::url('/tools/packages/sudimage_content_popup/ajax_loader/?id='.$this->internalLink);
				$img = "<a href=\"{$ajax_url}\" class=\"fancybox-link\">" . $img ."</a>";
			}
			
			return $img;
		}
		
		public function on_page_view() {
			$html = Loader::helper('html');
			$this->addHeaderItem($html->css('jquery.fancybox-1.3.4.css','sudimage_content_popup'));
			$this->addHeaderItem($html->javascript('jquery.easing-1.3.pack.js','sudimage_content_popup'));
			$this->addHeaderItem($html->javascript('jquery.mousewheel-3.0.4.pack.js','sudimage_content_popup'));
			$this->addHeaderItem($html->javascript('jquery.fancybox-1.3.4.pack.js','sudimage_content_popup'));
			$this->addHeaderItem('<script type="text/javascript">$(document).ready(function() { $(".fancybox-link").fancybox({padding:2});});</script>');
		}

	}

?>