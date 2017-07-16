<?php
/**
 * Netgo_Gwishlist extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Netgo
 * @package        Netgo_Gwishlist
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Gwishlist default helper
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author NetGo
     */
	 
			  
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }
	
	  public function getUrl($product)
				{
				 $whlsturl='';
				 if(Mage::getStoreConfig('netgo_gwishlist/gwishlist/enable')==1)
					{
					  if(!Mage::getSingleton('customer/session')->isLoggedIn()):
					  
						$whislisturl=Mage::getBaseUrl().'gwishlist/gwishlist/add/product/'.$product->getId();
						 $whlsturl= '<li><a href="'.$whislisturl.'" class="link-wishlist">Add to Wishlist</a></li>';
					 else:
					   if (Mage::helper('wishlist')->isAllow()) :
					   
						 $whislisturl = Mage::helper('wishlist')->getAddUrl($product);
						  $whlsturl= '<li><a href="'.$whislisturl.'" class="link-wishlist">Add to Wishlist</a></li>';
						 endif;
					 endif;
				
				 }
				  return $whlsturl;
				}
}
