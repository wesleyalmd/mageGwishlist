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
 * Gwishlist resource model
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Model_Resource_Gwishlist extends Mage_Core_Model_Resource_Db_Abstract
{

    /**
     * constructor
     *
     * @access public
     * @author NetGo
     */
    public function _construct()
    {
        $this->_init('netgo_gwishlist/gwishlist', 'entity_id');
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @access public
     * @param int $gwishlistId
     * @return array
     * @author NetGo
     */
    public function lookupStoreIds($gwishlistId)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('netgo_gwishlist/gwishlist_store'), 'store_id')
            ->where('gwishlist_id = ?', (int)$gwishlistId);
        return $adapter->fetchCol($select);
    }

    /**
     * Perform operations after object load
     *
     * @access public
     * @param Mage_Core_Model_Abstract $object
     * @return Netgo_Gwishlist_Model_Resource_Gwishlist
     * @author NetGo
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
        }
        return parent::_afterLoad($object);
    }

    /**
     * Retrieve select object for load object data
     *
     * @param string $field
     * @param mixed $value
     * @param Netgo_Gwishlist_Model_Gwishlist $object
     * @return Zend_Db_Select
     */
    protected function _getLoadSelect($field, $value, $object)
    {
        $select = parent::_getLoadSelect($field, $value, $object);
        if ($object->getStoreId()) {
            $storeIds = array(Mage_Core_Model_App::ADMIN_STORE_ID, (int)$object->getStoreId());
            $select->join(
                array('gwishlist_gwishlist_store' => $this->getTable('netgo_gwishlist/gwishlist_store')),
                $this->getMainTable() . '.entity_id = gwishlist_gwishlist_store.gwishlist_id',
                array()
            )
            ->where('gwishlist_gwishlist_store.store_id IN (?)', $storeIds)
            ->order('gwishlist_gwishlist_store.store_id DESC')
            ->limit(1);
        }
        return $select;
    }

    /**
     * Assign gwishlist to store views
     *
     * @access protected
     * @param Mage_Core_Model_Abstract $object
     * @return Netgo_Gwishlist_Model_Resource_Gwishlist
     * @author NetGo
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();
        if (empty($newStores)) {
            $newStores = (array)$object->getStoreId();
        }
        $table  = $this->getTable('netgo_gwishlist/gwishlist_store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);
        if ($delete) {
            $where = array(
                'gwishlist_id = ?' => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }
        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'gwishlist_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }}
