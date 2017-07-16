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
 * Gwishlist front contrller
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
//class Netgo_Gwishlist_GwishlistController extends Mage_Core_Controller_Front_Action
class Netgo_Gwishlist_Block_Customer_Account_Navigation extends Mage_Customer_Block_Account_Navigation
{
    public function removeLinkByName($name) {
		die('comes');
        unset($this->_links[$name]);
    }
}
