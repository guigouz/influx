This is an overview of the architecture used by influx. [ApplicationSkeleton](ApplicationSkeleton.md) and [API](API.md) present the guts of the framework, while [QuickStart](QuickStart.md) gives you a nice tutorial on how to deploy your first influx application.

# The Components #

The goal here is to simplify your application's organization, and let you focus on functionality.
**Controllers** contain all the application logic. On a true MVC framework,
it's used to pull data from a **Model** and pass it to a **View**.
On Influx's tiny approach, it's up to the user to define the Model
(although I strongly suggest to encapsulate all functionality on separate classes).

## Uh? A controller ? Like the remote one ? ##
A controller encapsulates the functionality of a certain module of your application.
On Influx, it's a plain PHP class with methods that define its actions.

```
/* A class in /app/some.php */
class SomeController extends Controller {

	/* Called when someone accesses http://domain.com/index.php?/some */
	function index() {
	
	}
	
	/* Called when someone accesses http://domain.com/index.php?/some/other */
	function other($give, $me, $params) {
		echo "give $give me $me params $params";
		print_r($this->argv);
	}
}
```
### Invoking controllers and passing parameters ###
Influx automatically exports all your controller actions so they can be accessed by the browser.

When a user accesses _http://domain.com/index.php?/some/action_, Influx will instantiate a new `SomeController` and call its `action()` action.

Parameters are passed automatically to the function, and the `$this->argv` variable (inside a controller) retains an array of what was passed to the action,  for instance, when a user accesses _http://domain.com/index.php?/some/other/parameter/is/nice_, Influx will instantiate `SomeController` and call `other('parameter', 'is', 'nice')`.

To create a private function in a controller, just prepend it with an underscore. Any access to `_actionName` will be denied.

### Form data on controllers ###
Form (POST) data is always passed to the `$this->data['form']` controller variable (`$_POST` is unset after that).
So, a field like `<input type="text" name="somefield"/>`, POSTed data will be accessed through `$this->data['form']['somefield']` on the controller.
Please check the `TestController` on the sample application source code for more info.

## The View ##
The View is what is presented to the user. It receives data from the controller (via variables) to the user and pass data from the user (via POST) back to the controller.

View files must reside on _/app/views/controller/action.php_, so after Influx instantiates `SomeController` and call `action()`, it will render _/app/views/some/action.php_ using the default layout on _/app/views/default.php_. You can change the layout by setting `$this->layout = 'layoutname'` inside the controller (this will render _/app/views/layoutname.php_.

Variables are passed, from the controller to the view, using the controller's `set()` method, for example, if you call `$this->set('myparam', 'This is my parameter');` on the Controller, `$myparam` will be acessible on the view, containing _This is my parameter_.
POSTed variables can be acessed via the special `$form` variable.