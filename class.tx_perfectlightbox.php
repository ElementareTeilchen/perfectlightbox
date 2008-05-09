<?php
/***************************************************************
*  Copyright notice
*  
*  (c) 2007 Benjamin Niediek <ben(at)channel-eight.de>
*  All rights reserved
*
*  This script is part of the Typo3 project. The Typo3 project is 
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
* 
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
* 
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/** 
 * Script 'class.tx_perfectlightbox.php'
 *
 * @author	Benjamin Niediek <ben(at)channel-eight.de>
 */

class tx_perfectlightbox {
    var $cObj; // Reference to the calling object.

    function main($content,$conf) {
		$uid = (1 == intval($conf['ignoreUid'])?'':$this->cObj->data['uid']);
		if ($this->cObj->data['tx_perfectlightbox_activate']==1) {
			$lightboxParams = 'rel="lightbox"';
		}
		if ($this->cObj->data['tx_perfectlightbox_activate']==1
		AND $this->cObj->data['tx_perfectlightbox_imageset']==1) {
			$lightboxParams = 'rel="lightbox[lb'.$uid.']"';
		}
		if ($this->cObj->data['tx_perfectlightbox_activate']==1
		AND $this->cObj->data['tx_perfectlightbox_imageset']==1
		AND $this->cObj->data['tx_perfectlightbox_presentation']==1) {
			$lightboxParams = 'rel="lightbox[presentlb'.$uid.']"';
		}
		if ($this->cObj->data['tx_perfectlightbox_activate']==1
		AND $this->cObj->data['tx_perfectlightbox_imageset']==1
		AND $this->cObj->data['tx_perfectlightbox_presentation']==0
		AND $this->cObj->data['tx_perfectlightbox_slideshow']==1) {
			$lightboxParams = 'rel="lightbox[lb'.$uid.'slideshow]"';
		}
		if ($this->cObj->data['tx_perfectlightbox_activate']==1
		AND $this->cObj->data['tx_perfectlightbox_imageset']==1
		AND $this->cObj->data['tx_perfectlightbox_presentation']==1
		AND $this->cObj->data['tx_perfectlightbox_slideshow']==1) {
			$lightboxParams = 'rel="lightbox[presentlb'.$uid.'slideshow]"';
		}
		if ($this->cObj->data['image_link']!='') {
			$lightboxParams = '';
		}
        return '<a href="'.$content["url"].'"'.$content["targetParams"].' '.$content["aTagParams"].' '.$lightboxParams.'>'.$linkImg;
    }
	
    function useGlobal($content,$conf) {
		$uid = (1 == intval($conf['ignoreUid'])?'':$this->cObj->data['uid']);	
		if ($this->cObj->data['image_zoom']==1) {
			$lightboxParams = 'rel="lightbox[lb'.$uid.']"';
		}
		if ($this->cObj->data['image_zoom']==1
		AND $this->cObj->data['tx_perfectlightbox_presentation']==1) {
			$lightboxParams = 'rel="lightbox[presentlb'.$uid.']"';
		}
		if ($this->cObj->data['image_zoom']==1
		AND $this->cObj->data['tx_perfectlightbox_presentation']==0
		AND $this->cObj->data['tx_perfectlightbox_slideshow']==1) {
			$lightboxParams = 'rel="lightbox[lb'.$uid.'slideshow]"';
		}
		if ($this->cObj->data['image_zoom']==1
		AND $this->cObj->data['tx_perfectlightbox_presentation']==1
		AND $this->cObj->data['tx_perfectlightbox_slideshow']==1) {
			$lightboxParams = 'rel="lightbox[presentlb'.$uid.'slideshow]"';
		}
		if ($this->cObj->data['image_link']!='') {
			$lightboxParams = '';
		}
        return '<a href="'.$content["url"].'"'.$content["targetParams"].' '.$content["aTagParams"].' '.$lightboxParams.'>'.$linkImg;
    }
	
	/**
	 * Example function that sets the register var "IMAGE_NUM_CURRENT" to the the current image number.
	 *
	 * @param	array		$paramArray: $markerArray and $config of the current news item in an array
	 * @param	[type]		$conf: ...
	 * @return	array		the processed markerArray
	 */
	function ttnewsImageMarkerFunc($paramArray,$conf) {	
		$markerArray = $paramArray[0];
		$lConf = $paramArray[1];
		$pObj = &$conf['parentObj'];
		$row = $pObj->local_cObj->data;
		$textRenderObj = $paramArray[2];
	
		$imageNum = isset($lConf['imageCount']) ? $lConf['imageCount']:1;
		$imageNum = t3lib_div::intInRange($imageNum, 0, 100);
		$theImgCode = '';
		$imgs = t3lib_div::trimExplode(',', $row['image'], 1);
		$imgsCaptions = explode(chr(10), $row['imagecaption']);
		reset($imgs);
		$cc = 0;
		
		if (((count($imgs) > 1 && $pObj->config['firstImageIsPreview'])||
		(count($imgs) >= 1 && $pObj->config['forceFirstImageIsPreview'])) && 
		//Thanks to Steffen Thierock for this hint
		#$textRenderObj == 'displaySingle') {
		$pObj->config['code'] == 'SINGLE') {
			array_shift($imgs);
			array_shift($imgsCaptions);
		}
	
		while (list(, $val) = each($imgs)) {
			if ($cc == $imageNum) break;
			if ($val) {
				$lConf['image.']['altText'] = '';
				$lConf['image.']['altText'] = $lConf['image.']['altText'];
				$lConf['image.']['file'] = 'uploads/pics/'.$val;
				switch($lConf['imgAltTextField']) {
					case 'image': $lConf['image.']['altText'] .= $val;
					break;
					case 'imagecaption': $lConf['image.']['altText'] .= $imgsCaptions[$cc];
					break;
					default: $lConf['image.']['altText'] .= $row[$lConf['imgAltTextField']];
				}
			}
			// These line are all that's needed!
			if (((count($imgs) > 1 && $pObj->config['firstImageIsPreview'])||
			(count($imgs) >= 1 && $pObj->config['forceFirstImageIsPreview'])) && 
			$pObj->config['code'] == 'SINGLE') {
				$GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = $cc+1;
			} else {
				$GLOBALS['TSFE']->register['IMAGE_NUM_CURRENT'] = $cc;
			}
			// End
			$theImgCode .= $pObj->local_cObj->wrap($pObj->local_cObj->IMAGE($lConf['image.']).$pObj->local_cObj->stdWrap($imgsCaptions[$cc], $lConf['caption_stdWrap.']),$lConf['imageWrapIfAny_'.$cc]);
			$cc++;
		}
		$markerArray['###NEWS_IMAGE###'] = '';
		if ($cc) {
			$markerArray['###NEWS_IMAGE###'] = $pObj->local_cObj->wrap(trim($theImgCode), $lConf['imageWrapIfAny']);
		}
		return $markerArray;
	}
}

// XCLASS inclusion code
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/perfectlightbox/class.tx_perfectlightbox.php']) {
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/perfectlightbox/class.tx_perfectlightbox.php']);
}
?>