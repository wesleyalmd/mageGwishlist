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
 * Gwishlist edit form tab
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Edit_Tab_Form
     * @author NetGo
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('gwishlist_');
        $form->setFieldNameSuffix('gwishlist');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'gwishlist_form',
            array('legend' => Mage::helper('netgo_gwishlist')->__('Gwishlist'))
        );

        $fieldset->addField(
            'product_id',
            'text',
            array(
                'label' => Mage::helper('netgo_gwishlist')->__('Product ID'),
                'name'  => 'product_id',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'gwishlist_data',
            'text',
            array(
                'label' => Mage::helper('netgo_gwishlist')->__('Data'),
                'name'  => 'gwishlist_data',

           )
        );

        $fieldset->addField(
            'guest_ip',
            'text',
            array(
                'label' => Mage::helper('netgo_gwishlist')->__('Guest IP'),
                'name'  => 'guest_ip',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('netgo_gwishlist')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('netgo_gwishlist')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('netgo_gwishlist')->__('Disabled'),
                    ),
                ),
            )
        );
        if (Mage::app()->isSingleStoreMode()) {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            Mage::registry('current_gwishlist')->setStoreId(Mage::app()->getStore(true)->getId());
        }
        $formValues = Mage::registry('current_gwishlist')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getGwishlistData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getGwishlistData());
            Mage::getSingleton('adminhtml/session')->setGwishlistData(null);
        } elseif (Mage::registry('current_gwishlist')) {
            $formValues = array_merge($formValues, Mage::registry('current_gwishlist')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
