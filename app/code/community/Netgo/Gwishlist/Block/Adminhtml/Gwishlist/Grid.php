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
 * Gwishlist admin grid block
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author NetGo
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('gwishlistGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Grid
     * @author NetGo
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('netgo_gwishlist/gwishlist')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Grid
     * @author NetGo
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('netgo_gwishlist')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'gwishlist_data',
            array(
                'header'    => Mage::helper('netgo_gwishlist')->__('Data'),
                'align'     => 'left',
                'index'     => 'gwishlist_data',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('netgo_gwishlist')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('netgo_gwishlist')->__('Enabled'),
                    '0' => Mage::helper('netgo_gwishlist')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'product_id',
            array(
                'header' => Mage::helper('netgo_gwishlist')->__('Product ID'),
                'index'  => 'product_id',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'guest_ip',
            array(
                'header' => Mage::helper('netgo_gwishlist')->__('Guest IP'),
                'index'  => 'guest_ip',
                'type'=> 'text',

            )
        );
        if (!Mage::app()->isSingleStoreMode() && !$this->_isExport) {
            $this->addColumn(
                'store_id',
                array(
                    'header'     => Mage::helper('netgo_gwishlist')->__('Store Views'),
                    'index'      => 'store_id',
                    'type'       => 'store',
                    'store_all'  => true,
                    'store_view' => true,
                    'sortable'   => false,
                    'filter_condition_callback'=> array($this, '_filterStoreCondition'),
                )
            );
        }
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('netgo_gwishlist')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('netgo_gwishlist')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('netgo_gwishlist')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('netgo_gwishlist')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('netgo_gwishlist')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('netgo_gwishlist')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('netgo_gwishlist')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Grid
     * @author NetGo
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('gwishlist');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('netgo_gwishlist')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('netgo_gwishlist')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('netgo_gwishlist')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('netgo_gwishlist')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('netgo_gwishlist')->__('Enabled'),
                            '0' => Mage::helper('netgo_gwishlist')->__('Disabled'),
                        )
                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Netgo_Gwishlist_Model_Gwishlist
     * @return string
     * @author NetGo
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author NetGo
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Grid
     * @author NetGo
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    /**
     * filter store column
     *
     * @access protected
     * @param Netgo_Gwishlist_Model_Resource_Gwishlist_Collection $collection
     * @param Mage_Adminhtml_Block_Widget_Grid_Column $column
     * @return Netgo_Gwishlist_Block_Adminhtml_Gwishlist_Grid
     * @author NetGo
     */
    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $collection->addStoreFilter($value);
        return $this;
    }
}
