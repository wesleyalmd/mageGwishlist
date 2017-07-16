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
 * Gwishlist admin block
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Block_Adminhtml_Gwishlist extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_gwishlist';
        $this->_blockGroup         = 'netgo_gwishlist';
        parent::__construct();
        $this->_headerText         = Mage::helper('netgo_gwishlist')->__('Gwishlist');
        $this->_updateButton('add', 'label', Mage::helper('netgo_gwishlist')->__('Add Gwishlist'));

    }
}
