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
 * Gwishlist admin edit form
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        parent::__construct();
        $this->_blockGroup = 'netgo_gwishlist';
        $this->_controller = 'adminhtml_gwishlist';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('netgo_gwishlist')->__('Save Gwishlist')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('netgo_gwishlist')->__('Delete Gwishlist')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('netgo_gwishlist')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author NetGo
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_gwishlist') && Mage::registry('current_gwishlist')->getId()) {
            return Mage::helper('netgo_gwishlist')->__(
                "Edit Gwishlist '%s'",
                $this->escapeHtml(Mage::registry('current_gwishlist')->getGwishlistData())
            );
        } else {
            return Mage::helper('netgo_gwishlist')->__('Add Gwishlist');
        }
    }
}
