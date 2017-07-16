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
 * Gwishlist helper
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Helper_Gwishlist extends Mage_Core_Helper_Abstract
{

    /**
     * get the url to the gwishlists list page
     *
     * @access public
     * @return string
     * @author NetGo
     */
    public function getGwishlistsUrl()
    {
        if ($listKey = Mage::getStoreConfig('netgo_gwishlist/gwishlist/url_rewrite_list')) {
            return Mage::getUrl('', array('_direct'=>$listKey));
        }
        return Mage::getUrl('netgo_gwishlist/gwishlist/index');
    }
	
	public function checkGwishlists()
    {
        die('ram');
        return 0;
    }

    /**
     * check if breadcrumbs can be used
     *
     * @access public
     * @return bool
     * @author NetGo
     */
    public function getUseBreadcrumbs()
    {
        return Mage::getStoreConfigFlag('netgo_gwishlist/gwishlist/breadcrumbs');
    }
}
