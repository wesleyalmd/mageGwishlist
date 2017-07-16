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
 * Gwishlist front contrller
 *
 * @category    Netgo
 * @package     Netgo_Gwishlist
 * @author      NetGo
 */
class Netgo_Gwishlist_GwishlistController extends Mage_Core_Controller_Front_Action
{

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
        $this->_initLayoutMessages('catalog/session');
        $this->_initLayoutMessages('customer/session');
        $this->_initLayoutMessages('checkout/session');
        if (Mage::helper('netgo_gwishlist/gwishlist')->getUseBreadcrumbs()) {
            if ($breadcrumbBlock = $this->getLayout()->getBlock('breadcrumbs')) {
                $breadcrumbBlock->addCrumb(
                    'home',
                    array(
                        'label' => Mage::helper('netgo_gwishlist')->__('Home'),
                        'link'  => Mage::getUrl(),
                    )
                );
                $breadcrumbBlock->addCrumb(
                    'gwishlists',
                    array(
                        'label' => Mage::helper('netgo_gwishlist')->__('Gwishlists'),
                        'link'  => '',
                    )
                );
            }
        }
        $headBlock = $this->getLayout()->getBlock('head');
        if ($headBlock) {
            $headBlock->addLinkRel('canonical', Mage::helper('netgo_gwishlist/gwishlist')->getGwishlistsUrl());
        }
        $this->renderLayout();
    }
	
	public function addAction(){
	
		$postData 	= $this->getRequest()->getPost(); 
		
		
		$qty 		= $postData['qty'];
		
		
		     $prid=  (int)$this->getRequest()->getParam('product');
			 
			  
			  if(empty($postData) and $prid >0)
			  {
			    $postData['product']=$prid;
				$postData['qty']=1;
				$qty=1;
				 
			  }
		   if($qty=='')
		   {
		    $qty=1;
		   }
		$product_id = $postData['product'];

		$product 	= Mage::getModel('catalog/product')->load($product_id);
		
		$product_type 		= $product->getTypeID();
		$super_attribute 	= $postData['super_attribute'];
		$options 			= $postData['options'];

		$gwishlistData['entity_id'] 	= time();
		$gwishlistData['product_id'] 	= $product_id;
		$gwishlistData['type'] 			= $product_type;
		$gwishlistData['options'] 		= serialize($postData);
		$gwishlistData['qty'] 			= $qty;
		
		$cookie_name = "gwishlist";
		
		$product_info = array();
		$product_info = unserialize($_COOKIE['gwishlist']);
		 
		
		if(isset($postData) && count($postData) > 0){
			if(!array_key_exists($gwishlistData['entity_id'] , $product_info)){
				$product_info[$gwishlistData['entity_id'] ] = $gwishlistData;
				setcookie($cookie_name, serialize($product_info), time() + (86400 * 30), "/"); // 86400 = 1 day
			}
		}
		$data = unserialize($_COOKIE['gwishlist']);  
		
		 Mage::getSingleton('core/session')->addSuccess($product->getName()." has been added in wishlist");
						
		  $url=Mage::getBaseUrl().'gwishlist/gwishlist/';
		  $this->_redirectUrl($url);
		
	}
	public function removeitemAction(){
	             
                      $productData=$this->getRequest()->getParams();
					    $itemId=$productData['item'];
				    $predata= $data = unserialize($_COOKIE['gwishlist']); 
                   
					unset($data[$itemId]);
					
					$cookie_name = "gwishlist";
					Mage::setIsDeveloperMode(true);					
					
				setcookie("gwishlist",serialize($predata),time()-1, "/");
				setcookie($cookie_name, serialize($data), time() + (86400 * 30), "/"); // 86400 = 1 day	

				//	die('stopnnn');	
				      Mage::getSingleton('core/session')->addSuccess("Item was successfully deleted");
						$url=Mage::getBaseUrl().'gwishlist/gwishlist/';
				
				  $this->_redirectUrl($url);
					  
		  
	
	}
	
	 public function configureAction()
    {
	  
           $id = (int) $this->getRequest()->getParam('wlid');
		
	
		 $data = unserialize($_COOKIE['gwishlist']); 
		
		  $prodcutId=$data[$id]['product_id'];
		 $bRequest=unserialize($data[$id]['options']);
		
        try {
           
			$_product = Mage::getModel('catalog/product')->load($prodcutId);
				
            $params = new Varien_Object();
            $params->setCategoryId(false);
            $params->setConfigureMode(true);
					
			    $buyRequest = new Varien_Object($bRequest); 
			  
               $params->setBuyRequest($buyRequest);
			
			
            Mage::helper('catalog/product_view')->prepareAndRender($_product->getId(), $this, $params);
        } catch (Mage_Core_Exception $e) {
            Mage::getSingleton('core/session')->addError($e->getMessage());
			 die($e->getMessage());		
           
        } catch (Exception $e) {
            Mage::getSingleton('core/session')->addError($this->__('Cannot configure product'));
            Mage::logException($e);
				
            $this->_redirect('*');
            return;
        }
	
    }
	
	
		public function additemAction(){
	             
                      $productData=$this->getRequest()->getParams();
					  
					
					  $itemId=$productData['item'];
					   $data = unserialize($_COOKIE['gwishlist']); 
		 
					  $prodcutId=$data[$itemId]['product_id'];
					  $prodcutOption=unserialize($data[$itemId]['options']);
					 
					  $options=$prodcutOption['options'];
					
										 
                         $_product = Mage::getModel('catalog/product')->load($prodcutId);
						
						  
						   $cart = Mage::getSingleton('checkout/cart'); 
						   $cart->init();
						  
						  
					
						    $productType=$data[$itemId]['type'];
								try {
							 
								  if($productType=='simple' or $productType=='configurable')
							      {
										
										$product_super_attribute=$prodcutOption['super_attribute'];
											$qty=$prodcutOption['qty'];							 
								 $cart->addProduct($_product , 
										array( 'product_id' => $prodcutId,
													 'qty' => $qty,
													 'super_attribute' => $product_super_attribute,
													 'options' => $options));
													 
								}elseif($productType=='downloadable')
								{
								
								        $links=$prodcutOption['links'];
											$qty=$prodcutOption['qty'];	
								  
								  $cart->addProduct($_product , 
										array( 'product_id' => $prodcutId,
													 'qty' => $qty,
													 'links' => $links,
													 'options' => $options));
								}
								elseif($productType=='bundle')
								{
								  
								   $bundle_option=$prodcutOption['bundle_option'];
									$qty=$prodcutOption['qty'];	
								   
								  $cart->addProduct($_product , 
										array( 'product_id' =>  $prodcutId,
													 'qty' => $qty,
													 'bundle_option' => $bundle_option,
													 'options' => $options));
								}
								elseif($productType=='virtual')
								{
								
								 
									$qty=$prodcutOption['qty'];	
								 
								  $cart->addProduct($_product , 
										array( 'product_id' => $prodcutId,
													 'qty' => $qty,
													 'options' => $options));
								}elseif($productType=='grouped')
								{
								   $super_group=$prodcutOption['super_group'];
									$qty=$prodcutOption['qty'];	
									
								  $cart->addProduct($_product , 
										array( 'product_id' => $prodcutId,
													 'qty' => $qty,
													 'super_group' => $super_group));
								}
								
								
							 $cart->save(); 
							 $url=Mage::getBaseUrl().'checkout/cart/';		 
							}catch (Exception $e) {
												
						       Mage::getSingleton('core/session')->addError($e->getMessage());
							   
							   $url=Mage::getBaseUrl().'gwishlist/gwishlist/configure/wlid/'.$itemId;
							 
						 
										
					          }
						   

                        
                           $this->_redirectUrl($url);							

																  
										  
										  
	
	}
	
	public function updateAction(){
	   
	 $post_data=$this->getRequest()->getPost();
		
	  $qty=$post_data['qty'];
	 
	   try {
	       
		   
		   $predata=  $data = unserialize($_COOKIE['gwishlist']); 
           
		   
				 	foreach($qty as $wlid=>$prqty)
					{
					     
				    
					  $data[$wlid]['qty']=$prqty;
					  $dataOpt = unserialize($data[$wlid]['options']); 
					  $dataOpt['qty']=$prqty;
					  $data[$wlid]['options']=serialize($dataOpt);
						 
						 
                    }
					
					$cookie_name = "gwishlist";
					Mage::setIsDeveloperMode(true);	
				setcookie("gwishlist",serialize($predata),time()-1, "/");
				setcookie($cookie_name, serialize($data), time() + (86400 * 30), "/"); // 86400 = 1 day	
					
					
					 Mage::getSingleton('core/session')->addSuccess('Item was updated');						
		       } 
		catch (Exception $e) {
						
						 Mage::getSingleton('core/session')->addError($e->getMessage());
						
						
			}
	 
	              
		 $url=Mage::getBaseUrl().'gwishlist/gwishlist/';
	   $this->_redirectUrl($url);
		
	}
	
	public function addallitemAction(){
	   
	    $post_data=$this->getRequest()->getPost();
		
		             $data = unserialize($_COOKIE['gwishlist']); 
		 
					 
			   
			   foreach($data as $gwishlistProdcut)
			   {
			          $prodcutId=$gwishlistProdcut['product_id'];
					  $prodcutOption=unserialize($gwishlistProdcut['options']);
					 
					  $options=$prodcutOption['options'];
				   
				       $_product = Mage::getModel('catalog/product')->load($prodcutId);
					   $cart = Mage::getSingleton('checkout/cart'); 
					   $cart->init();
					   $productType=$gwishlistProdcut['type'];
								try {
							 
								  if($productType=='simple' or $productType=='configurable')
							      {
										
										$product_super_attribute=$prodcutOption['super_attribute'];
											$qty=$prodcutOption['qty'];							 
								 $cart->addProduct($_product , 
										array( 'product_id' => $prodcutId,
													 'qty' => $qty,
													 'super_attribute' => $product_super_attribute,
													 'options' => $options));
													 
								}elseif($productType=='downloadable')
								{
								
								        $links=$prodcutOption['links'];
											$qty=$prodcutOption['qty'];	
								  
								  $cart->addProduct($_product , 
										array( 'product_id' => $prodcutId,
													 'qty' => $qty,
													 'links' => $links,
													 'options' => $options));
								}
								elseif($productType=='bundle')
								{
								  
								   $bundle_option=$prodcutOption['bundle_option'];
									$qty=$prodcutOption['qty'];	
								   
								  $cart->addProduct($_product , 
										array( 'product_id' =>  $prodcutId,
													 'qty' => $qty,
													 'bundle_option' => $bundle_option,
													 'options' => $options));
								}
								elseif($productType=='virtual')
								{
								
								 
									$qty=$prodcutOption['qty'];	
								 
								  $cart->addProduct($_product , 
										array( 'product_id' => $prodcutId,
													 'qty' => $qty,
													 'options' => $options));
								}elseif($productType=='grouped')
								{
								   $super_group=$prodcutOption['super_group'];
									$qty=$prodcutOption['qty'];	
									
								  $cart->addProduct($_product , 
										array( 'product_id' => $prodcutId,
													 'qty' => $qty,
													 'super_group' => $super_group));
								}
								
								
							 $cart->save(); 
							 $url=Mage::getBaseUrl().'checkout/cart/';		 
							}catch (Exception $e) {
												
						       Mage::getSingleton('core/session')->addError($e->getMessage());
							   
							   $url=$_product->getUrl();	
							 
						 
										
					          }
				   
				   
				   
			   }
			   
			
             $this->_redirectUrl($url);			 
	 }
	 
	 
	 	public function updategwishlistAction() {
	
    
             	     $post_data=$this->getRequest()->getPost();
		              $wlid= $this->getRequest()->getParam("item");
			        
			
                       			  
				if ($post_data) {

					try {

						$predata=  $data = unserialize($_COOKIE['gwishlist']); 
			        	$qty= $post_data['qty'];
					
					    $data[$wlid]['options']=serialize($post_data);
				        $data[$wlid]['qty']= $qty ;
						
						$cookie_name = "gwishlist";
					     Mage::setIsDeveloperMode(true);	
			     	   setcookie("gwishlist",serialize($predata),time()-1, "/");
				       setcookie($cookie_name, serialize($data), time() + (86400 * 30), "/"); 	
					
					
					     Mage::getSingleton('core/session')->addSuccess('Item was updated');
						 
						 $url=Mage::getBaseUrl().'gwishlist/gwishlist/';
						
					} 
					catch (Exception $e) {
					 $url=Mage::getBaseUrl();
					return;
					}

				}
				
		
				
			 $this->_redirectUrl($url);	
	  
	   
	}
	
}
