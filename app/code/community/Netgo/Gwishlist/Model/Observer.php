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
 * @author      Netgo
 */


class Netgo_Gwishlist_Model_Observer{
  
    public function addCustomerWishlist(Varien_Event_Observer $observer){
        $customer = $observer->getEvent()->getData();
		$customerId = $customer['customer']->getEntityId();
		
		   $predata=$data = unserialize($_COOKIE['gwishlist']);
	     	$wishlist = Mage::getModel('wishlist/wishlist')->loadByCustomer($customerId, true);
			Mage::register('wishlist', $wishlist);
			
	     if($data!='')
		 {
		    foreach($data as $gwishlistProdcut)
			   {
			           $productId=$gwishlistProdcut['product_id'];
					  $prodcutOption=unserialize($gwishlistProdcut['options']);
					 
					   
					   
				   try {
	               	$product = Mage::getModel('catalog/product')->load($productId);
				                       	
					$buyRequest = new Varien_Object($prodcutOption);

					$result = $wishlist->addNewItem($product, $buyRequest);
				$wishlist->save();

                  Mage::dispatchEvent(
                                'wishlist_add_product',
                            array(
                                'wishlist'  => $wishlist,
                                'product'   => $product,
                                'item'      => $result
                            )
                            );

                            Mage::helper('wishlist')->calculate();
							
							
				
                
            } catch (Mage_Core_Exception $e) {
             Mage::getSingleton('core/session')->addError($e->getMessage());
			
        }
        catch (Exception $e) {
           Mage::getSingleton('core/session')->addError($e->getMessage());
		    
        }			
	}
			   Mage::getSingleton('core/session')->addSuccess('Items moved in wishlist');
          setcookie("gwishlist",serialize($predata),time()-1, "/");
	}	  
    
    }
}
