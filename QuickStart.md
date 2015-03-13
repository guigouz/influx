This application is included in the default Influx distribution, to run it, access
http://your_site/influx/index.php?/products

# 1. Requirements #
  * PHP4 (not tested with version 5, although it should work)

# 2. Design your application #
It's really important to define the controllers involved in the process before starting to code.

In this simple example, we'll have a simple shop with no user login, so we'll have
  * ProductsController, which will list the products available
  * CartController, which we'll use to add products to the cart and checkout

# 3. Code #

## The Controllers ##

`/app/products.php` the ProductsController
```
<?php
class ProductsController extends Controller {

	/**
	 * Lists all the products in the store
	 *
	 */
	function index() {
		
		// this might have come from a database, you know
		$products = array('Corn', 'Rice', 'Beans', 'Milk', 'Meat', 'Tea', 'Coffee', 'Cake', 'Soda', 'Pie', 'Matches', 'Beer');
		
		$this->set('products', $products);
	}
}
?>
```

`/app/cart.php`, The CartController
```
<?php
class CartController extends Controller {

	/**
	 * Lists the products in the shopping cart
	 *
	 */	
	function index() {
		
		// Load the "Cart" session variable
		$cart_contents = session('cart');
		
		// Export that to the view (/app/views/cart/index.php)
		$this->set('cart_contents', $cart_contents);
	}

	/**
	 * Displays the checkout form
	 *
	 */	
	function checkout() {

	}
	
	/**
	 * Adds a product to the cart
	 * Please note that this function has no view. 
	 * It just adds the product to the session and redirects back to the Products list
	 */ 
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

	/**
	 * Displays a message confirming the checkout
	 *
	 */
	function confirm() {
		// If no email was set, redirect to the cart contents
		if(!isset($this->data['form']['email'])) {
			redirect('/cart');
		}
		
		// on a real app we'd at least send an email to the user
		
		// clean up the cart
		session('cart', array());
	}
}
?>
```

## The Views ##
`/app/views/products/index.php` - Lists all products
```
<table width="100%" border="1">
<?php
foreach($products as $product):
?>
<tr><td><?=$product ?></td><td><a href="<?=url("/cart/add/$product") ?>">Add to Cart</a></td></tr>
<?php
endforeach;
?>
</table>
<p><a href="<?=url("/cart") ?>">Show cart contents</a></p>
```

`/app/views/cart/index.php` - Lists the cart's content
```
<h1>Your cart has</h1>
<?php if(count($cart_contents) == 0): ?>
<p>No Items | <a href="<?=url('/products') ?>">Continue Shopping</a></p>
<?php 
else: 
foreach($cart_contents as $product) {
	echo "<p>$product</p>";
}
?>
<p><a href="<?=url('/cart/checkout') ?>">Proceed to checkout</a> | <a href="<?=url('/products') ?>">Continue Shopping</a></p>
<?php
endif;
?>
```

`/app/views/cart/checkout.php` - Asks for the user's email address and POSTs it to checkout
```
<form action="<?=url('/cart/confirm') ?>" method="post">
<p>Please type in your email address: <input type="text" name="email"/></p>
<p><input type="submit" value="Confirm checkout"></p>
</form>
```

`/app/views/cart/confirm.php` - Confirms that the checkout went ok
```
<h1>Thank you for shopping at the influx webstore!</h1>

<?php
// The $form variable is always automatically set. 
// It contains the variables the app received via POST
?>
<p>Payment details were sent to <?=$form['email'] ?></p>

<p><a href="<?=url('/products') ?>">Shop Again!</a></p>
```