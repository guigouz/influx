<?php
class TestController extends Controller {
	function index() {
		
		// check if we'd like to go somewhere else
		if(!empty($this->data['form']['destination'])) {
			redirect('/test/'.$this->data['form']['destination']);
		}
	}
	
	function views() {
		$this->set('myparam', 'This is string a parameter set as $this->set("myparam", content) on the controller. The view will access it as $myparam.');
		
		load("dummy.php");
		$this->set('dummy', dummy_string());
		
		// Example for elements
		$this->set('data', array('name' => 'Influx', 'age' => 'Since Nov/2007', 'color' => 'Who cares?'));
	}
	
	function sessions($data = null) {
		$this->set('sample_data', session('sample_data'));
		
		if($data) {
			
			session('sample_data', $data);
			$this->set('written', true);
			
		}
		
	}
	
	function forms() {
		
	}
}
?>
