<?php
class ProductsController extends Controller {
	
	function index() {
		
		// this might have come from a database, you know
		$products = array('Corn', 'Rice', 'Beans', 'Milk', 'Meat', 'Tea', 'Coffee', 'Cake', 'Soda', 'Pie', 'Matches', 'Beer');
		
		$this->set('products', $products);
	}
}
?>