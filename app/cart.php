<?php
class CartController extends Controller {
	
	function index() {
		
		// Load the "Cart" session variable
		$cart_contents = session('cart');
		
		// Export that to the view (/app/views/cart/index.php)
		$this->set('cart_contents', $cart_contents);
	}
	
	function checkout() {
		// Check if the user submitted his/hers email address
		if(isset($this->data['form']['email'])) {
			// Time to checkout
			redirect('/cart/confirm/'.$this->data['form']['email']);
		}
	}
	
	// Adds a product to the cart
	function add($product) {
		// load the cart
		$cart = session('cart');
		
		// append the new product to it
		$cart[] = $product;
		
		// save again
		session('cart', $cart);
		
		// back to the products
		redirect('/products');
		
	}
	
	function confirm($email = null) {
		// If no email was set, redirect to the cart contents
		if(!$email) {
			redirect('/cart');
		}
		
		// Set $email in the view
		$this->set('email', $email);
		
		// clean up the cart
		session('cart', array());
	}
}
?>