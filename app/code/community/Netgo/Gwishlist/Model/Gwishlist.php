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
 * Gwishlist model
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Model_Gwishlist extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'netgo_gwishlist_gwishlist';
    const CACHE_TAG = 'netgo_gwishlist_gwishlist';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'netgo_gwishlist_gwishlist';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'gwishlist';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('netgo_gwishlist/gwishlist');
    }

    /**
     * before save gwishlist
     *
     * @access protected
     * @return Netgo_Gwishlist_Model_Gwishlist
     * @author NetGo
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save gwishlist relation
     *
     * @access public
     * @return Netgo_Gwishlist_Model_Gwishlist
     * @author NetGo
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author NetGo
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
