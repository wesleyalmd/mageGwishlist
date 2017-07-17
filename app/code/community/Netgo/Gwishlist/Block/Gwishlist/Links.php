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
 * @category    design
 * @package     Netgo_Gwishlist
 * @author      Wesley Almeida <wesleyalmd@gmail.com>
 */
?>
<?php
class Netgo_Gwishlist_Block_Gwishlist_Links extends Mage_Core_Block_Template
{
    
    public function addGwishlistLink()
    {
		$parentBlock = $this->getParentBlock();
		
		if(!Mage::getSingleton('customer/session')->isLoggedIn()){
			$parentBlock->removeLinkBlock('wishlist_link');
			$data = unserialize($_COOKIE['gwishlist']);
               
			if( $data==''){
				$count=0;
			} else {
				$count=count($data) ;
			}
			
			if ($count == 1) {
				$text = $this->__('My Wishlist (%d item)', $count);

			} elseif ($count > 0) {
				$text = $this->__('My Wishlist (%d items)', $count);

			} else {
				$text = $this->__('My Wishlist');
			}
		   
			$parentBlock->addLink($text, 'netgo_gwishlist/gwishlist', $text, true, array(), 11, null, 'class="top-link-gwlnk"');

		}

		return $this;
    }

}
