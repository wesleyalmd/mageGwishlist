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
 * Gwishlist admin controller
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_Adminhtml_Gwishlist_GwishlistController extends Netgo_Gwishlist_Controller_Adminhtml_Gwishlist
{
    /**
     * init the gwishlist
     *
     * @access protected
     * @return Netgo_Gwishlist_Model_Gwishlist
     */
    protected function _initGwishlist()
    {
        $gwishlistId  = (int) $this->getRequest()->getParam('id');
        $gwishlist    = Mage::getModel('netgo_gwishlist/gwishlist');
        if ($gwishlistId) {
            $gwishlist->load($gwishlistId);
        }
        Mage::register('current_gwishlist', $gwishlist);
        return $gwishlist;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('netgo_gwishlist')->__('Guest Wish List'))
             ->_title(Mage::helper('netgo_gwishlist')->__('Gwishlists'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit gwishlist - action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function editAction()
    {
        $gwishlistId    = $this->getRequest()->getParam('id');
        $gwishlist      = $this->_initGwishlist();
        if ($gwishlistId && !$gwishlist->getId()) {
            $this->_getSession()->addError(
                Mage::helper('netgo_gwishlist')->__('This gwishlist no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getGwishlistData(true);
        if (!empty($data)) {
            $gwishlist->setData($data);
        }
        Mage::register('gwishlist_data', $gwishlist);
        $this->loadLayout();
        $this->_title(Mage::helper('netgo_gwishlist')->__('Guest Wish List'))
             ->_title(Mage::helper('netgo_gwishlist')->__('Gwishlists'));
        if ($gwishlist->getId()) {
            $this->_title($gwishlist->getGwishlistData());
        } else {
            $this->_title(Mage::helper('netgo_gwishlist')->__('Add gwishlist'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new gwishlist action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save gwishlist - action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('gwishlist')) {
            try {
                $gwishlist = $this->_initGwishlist();
                $gwishlist->addData($data);
                $gwishlist->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('netgo_gwishlist')->__('Gwishlist was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $gwishlist->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setGwishlistData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('netgo_gwishlist')->__('There was a problem saving the gwishlist.')
                );
                Mage::getSingleton('adminhtml/session')->setGwishlistData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('netgo_gwishlist')->__('Unable to find gwishlist to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete gwishlist - action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $gwishlist = Mage::getModel('netgo_gwishlist/gwishlist');
                $gwishlist->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('netgo_gwishlist')->__('Gwishlist was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('netgo_gwishlist')->__('There was an error deleting gwishlist.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('netgo_gwishlist')->__('Could not find gwishlist to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete gwishlist - action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function massDeleteAction()
    {
        $gwishlistIds = $this->getRequest()->getParam('gwishlist');
        if (!is_array($gwishlistIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('netgo_gwishlist')->__('Please select gwishlists to delete.')
            );
        } else {
            try {
                foreach ($gwishlistIds as $gwishlistId) {
                    $gwishlist = Mage::getModel('netgo_gwishlist/gwishlist');
                    $gwishlist->setId($gwishlistId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('netgo_gwishlist')->__('Total of %d gwishlists were successfully deleted.', count($gwishlistIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('netgo_gwishlist')->__('There was an error deleting gwishlists.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function massStatusAction()
    {
        $gwishlistIds = $this->getRequest()->getParam('gwishlist');
        if (!is_array($gwishlistIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('netgo_gwishlist')->__('Please select gwishlists.')
            );
        } else {
            try {
                foreach ($gwishlistIds as $gwishlistId) {
                $gwishlist = Mage::getSingleton('netgo_gwishlist/gwishlist')->load($gwishlistId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d gwishlists were successfully updated.', count($gwishlistIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('netgo_gwishlist')->__('There was an error updating gwishlists.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function exportCsvAction()
    {
        $fileName   = 'gwishlist.csv';
        $content    = $this->getLayout()->createBlock('netgo_gwishlist/adminhtml_gwishlist_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function exportExcelAction()
    {
        $fileName   = 'gwishlist.xls';
        $content    = $this->getLayout()->createBlock('netgo_gwishlist/adminhtml_gwishlist_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author NetGo
     */
    public function exportXmlAction()
    {
        $fileName   = 'gwishlist.xml';
        $content    = $this->getLayout()->createBlock('netgo_gwishlist/adminhtml_gwishlist_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author NetGo
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('netgo_gwishlist/gwishlist');
    }
}
