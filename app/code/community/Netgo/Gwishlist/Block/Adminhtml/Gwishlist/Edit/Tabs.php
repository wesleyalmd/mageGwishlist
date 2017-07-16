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
 * Gwishlist admin edit tabs
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author NetGo
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('gwishlist_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('netgo_gwishlist')->__('Gwishlist'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Edit_Tabs
     * @author NetGo
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_gwishlist',
            array(
                'label'   => Mage::helper('netgo_gwishlist')->__('Gwishlist'),
                'title'   => Mage::helper('netgo_gwishlist')->__('Gwishlist'),
                'content' => $this->getLayout()->createBlock(
                    'netgo_gwishlist/adminhtml_gwishlist_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        if (!Mage::app()->isSingleStoreMode()) {
            $this->addTab(
                'form_store_gwishlist',
                array(
                    'label'   => Mage::helper('netgo_gwishlist')->__('Store views'),
                    'title'   => Mage::helper('netgo_gwishlist')->__('Store views'),
                    'content' => $this->getLayout()->createBlock(
                        'netgo_gwishlist/adminhtml_gwishlist_edit_tab_stores'
                    )
                    ->toHtml(),
                )
            );
        }
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve gwishlist entity
     *
     * @access public
     * @return Netgo_Gwishlist_Model_Gwishlist
     * @author NetGo
     */
    public function getGwishlist()
    {
        return Mage::registry('current_gwishlist');
    }
}
