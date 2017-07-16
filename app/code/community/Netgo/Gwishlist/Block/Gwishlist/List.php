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
 * Gwishlist list block
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author NetGo
 */
class Netgo_Gwishlist_Block_Gwishlist_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author NetGo
     */
    public function __construct()
    {
        parent::__construct();
        $gwishlists = Mage::getResourceModel('netgo_gwishlist/gwishlist_collection')
                         ->addStoreFilter(Mage::app()->getStore())
                         ->addFieldToFilter('status', 1);
        $gwishlists->setOrder('gwishlist_data', 'asc');
        $this->setGwishlists($gwishlists);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return Netgo_Gwishlist_Block_Gwishlist_List
     * @author NetGo
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'netgo_gwishlist.gwishlist.html.pager'
        )
        ->setCollection($this->getGwishlists());
        $this->setChild('pager', $pager);
        $this->getGwishlists()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author NetGo
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
