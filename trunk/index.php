<?php
/*
 * Influx
 * (c) 2007 Guilherme Barile
 * 
 * Distributed under the New BSD licence at http://www.guigo.us/projects/influx
 * 
 * Influx is a simple (really simple) controller for php apps
 *
 * A lot of this funcionality was taken from CakePHP, so
 * If you need DB support and more features, check out http://cakephp.org
 * 
 * Everything you (should) need is in this file. 
 * Check for docs at http://code.google.com/p/influx
 * 
 */

/* Let the carnage begin */
session_start();

// bootstrapping...
define('DS', DIRECTORY_SEPARATOR);

define('ROOT', dirname(__FILE__).DS);

if(file_exists(ROOT.'_config.php'))
	include(ROOT.'_config.php');
	
if(!defined('APP'))
	define('APP', 'app');

define('APP_ROOT', ROOT.APP.DS);

$sn = dirname($_SERVER['PHP_SELF']);
if($sn != "/") $sn .= '/';
define('WEBROOT', $sn);

if(file_exists(APP_ROOT.'_config.php'))
	include(APP_ROOT.'_config.php');

if(!defined('LOGOUT_TRIGGER'))
	define('LOGOUT_TRIGGER', "logout"); // there's LOGOUT_CALLBACK too
	
if(!empty($_SERVER['argv'])) {
	$cmd = @substr($_SERVER['argv'][0],1);
	define('SCRIPT_NAME', 'index.php?');
}
else {
	$cmd = @substr($_SERVER['QUERY_STRING'],1);
	define('SCRIPT_NAME', 'index.php?');
}

// and then, we run
new Influx($cmd);

// classes and functions (in this order, and then hopefully in alphabetical order too)
class Controller {
	var $autoRender = true;
	var $description = "";
	var $argv = array();
	var $data = array();
	var $layout = 'default';
	var $action = '';
	var $name = '';
	
	/**
	 * Occurs after the action has been processed (and before autorendering)
	 * @return 
	 */
	function after() {
		
	}
	
	/**
	 * Occurs before processing the desired action
	 * @return 
	 */
	function before() {
		
	}

	/**
	 * Called when a method is missing (not callable)
	 * It's important to notice that all the controller parameters (form, action, argv) are already set at this point
	 *
	 */
	function missing() {
		echo "missing {$this->action} on controller {$this->name}";
		exit(); // TODO render página de erro (layout)
	}
	
	function render($_action = null) {
		
		if(!$_action) 
			$_action = $this->action;
			
		$_view = APP_ROOT.'views'.DS.$this->name.DS.$_action.'.php';
		
		if(!is_readable($_view)) {
			$this->action = $_action;
			Influx::missingView($this);
		}
		else {
			Influx::renderFile($_view, $this->layout, $this->data);
			
		}
		
		$this->autoRender = false;
	}
	
	function set($name, $value) {
		$this->data[$name] = $value;
	}

}

class Influx {
	var $templates = array(
		'default' =>
		"<html></html>",
		'ajax' =>
		"<html></html>",
		'flash' => "<html></html>");
	
	/**
	 * Lists all controllers
	 * @return 
	 */
	function index() {
		// TODO list all controllers
		echo "This is the mech index";
		exit;
	}
	
	function invalidAction() {
		echo "Invalid action specified";
		exit;
	}
	
	function missingController($name = "") {
		echo "Missing controller $name";
		exit;
	}
	
	function missingView(&$controller) {
		echo "Missing view ".APP_ROOT.'views/'.$controller->name.'/'.$controller->action.'.php';
	}
	
	function className($_name) {
		
		$_name[0] = strtoupper($_name[0]);
		
		$name = "";
		
		// Check for underscores _
		for($i = 0; $i < strlen($_name); $i++) {
			if($_name[$i] == '_')
				$_name[$i + 1] = strtoupper($_name[$i+1]);
			else
				$name .= $_name[$i];
		}
		
		return($name.'Controller');
	}

	function Influx($cmd) {
		$args = split('/', $cmd);
		if(empty($args[0])) {
			if(defined('INDEX'))
				$args = split('/', INDEX);
			else {
				Influx::index();
			}
		}
		
		if($args[0] == LOGOUT_TRIGGER) {
			if(defined("LOGOUT_CALLBACK")) { 
				call_user_func(LOGOUT_CALLBACK);
			}
			else {
				session_destroy();
			}
			redirect('/');
		}
		
		// Instantiate the controller
		$_controller = Influx::loadController($args[0]);
		$_controller->action = (empty($args[1]) ? 'index' : $args[1]);
		// set up the parameters
		for($i = 2; $i < count($args); $i++) {
			$_controller->argv[] = $args[$i];
		}
		
		// check if data was submitted, populate the $data field
		if(isset($_POST)) {
			$_controller->set('form', $_POST);
			unset($_POST);
		}
		
		// Security-paranoia
		if($_controller->action[0] == "_" || is_callable(array('Controller', $_controller->action))) {
			Influx::invalidAction();
		}
		
		$_controller->before();
		
		if(is_callable(array(&$_controller, $_controller->action)))
			call_user_func_array(array(&$_controller, $_controller->action), $_controller->argv);	
		else
			$_controller->missing();
			
		$_controller->after();
		if($_controller->autoRender) {
			$_controller->render();
		}
	}

	function loadController($name) {
		if(is_readable(APP_ROOT.$name.'.php')) {
			include(APP_ROOT.$name.'.php');
			
			$className = Influx::className($name);
			
			if(class_exists($className)) {
				$controller = new $className();
				$controller->name = $name;
				return $controller;
			}
		}

		// If we got here, there's an error!
		Influx::missingController($name);

	}
	
	
	function renderFile($__view, $__layout, $__data) {
		
		foreach($__data as $__name => $__value) {
			$$__name = $__value;
		}
		
		unset($__data);
		unset($__name);
		unset($__value);
		
		ob_start();
		include($__view);
		$_content = ob_get_clean();
		
		$_layout = APP_ROOT.'views'.DS.$__layout.'.php';
	
		if(!is_readable($_layout)) {
			// TODO use default layout (hardcoded) _ URGENT FOR RELEASE
		}
		else {
			include($_layout);
		}

	}	
}

/**
 * Inserts a link to a css file
 * @return 
 * @param $file Object
 */
function css($file) {
	return "<link rel=\"stylesheet\" href=\"".WEBROOT.APP."/html/".$file."\"/>\n";
}

/**
 * Inserts an image tag (<img>)
 * @return 
 * @param $file The image file name
 * @param $attrs Additional element attributes
 */
function img($file, $attrs = array()) {
	$a = "";
	foreach($attrs as $key => $value) {
		$a .= " $key=\"$value\"";
	}
	
	return "<img src=\"".WEBROOT.APP."/html/".$file."\"$a/>";
}

/**
 * Returns TRUE if this in an AJAX request
 * @return 
 */
function isAjax() {
	return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&  ($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest'));
}

/**
 * Inserts a script tag
 * @return 
 * @param $file The script file name
 */
function js($file) {
	return "<script type=\"text/javascript\" src=\"".WEBROOT.APP."/html/".$file."\"></script>\n";
}

/**
 * Loads a file from APP/lib
 * @return 
 * @param $lib The file name to include
 */
function load($__lib, $__params = array()) {
	$__libfile = APP_ROOT.'lib'.DS.$__lib;
	unset($__lib);
	
	if(!is_readable($__libfile)) {
		echo "Error importing $__libfile (can't read!)";
	}
	else {
		foreach($__params as $__name => $__value)
			$$__name = $__value;
		unset($__name);
		unset($__value);
		unset($__params);
		
 		include($__libfile);
	}
}

/**
 * Redirects the user to another controller/action
 * @return 
 * @param $to URL to go to, in the "controller/action/parameters" form
 */
function redirect($to) {
	header('Location: '.url($to));
	exit;
}

/**
 * Return the right url for the controller/action passed
 * @return 
 * @param $to URL to go to, in the "controller/action/parameters" form
 */
function url($to) {
	if($to == '/') $to = '';
	return WEBROOT.SCRIPT_NAME.$to;
}

/**
 * Reads or writes to the session
 * @return 
 * @param $name The name of the variable
 * @param $value [optional] Value for the variable, if empty returns the variable's value
 */
function session($name, $value = null) {
	if($value === null) {
		if(isset($_SESSION[$name]))
			return $_SESSION[$name];
		else
			return null;
	}
	else {
		$_SESSION[$name] = $value;
	}
}
?>