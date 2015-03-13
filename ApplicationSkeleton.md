The base application layout Influx understands is
  1. `index.php` - The influx code (everything you need, besides your app, is here)
  1. `_config.php` - Framework-wide configuration _optional_
  1. `app` - The APP dir
    1. `_config.php` - Application-specific configuration _optional_
    1. `html` - HTML files (Javascript, css, images and so on) _optional_
    1. `lib` - External PHP Libraries _optional_
    1. `name.php` - Code for the `NameController` _this is an example_
    1. `views` - Directory containing the views
      1. `default.php` - The default layout used _optional_
      1. `name` - Views for `NameController` _this is an example_
        1. `action.php` - View for the `action()` action on `NameController` _this is an example_

The default "app" provided on the default distribution covers all the funcionality of this framework with nice examples

# 1 `/index.php` #
This is where all the influx logic resides. When a new version comes out, this is the only file you need to update. Yeah, everything on your app/ folder remains intact.

# 2 `/_config.php` #
This optional global configuration file is loaded before anything else.
If you wish to change the default APP directory, this is the place to do so, for example
```
<?php
define('APP', 'anotherapp'); // will use /anotherapp as APP root
?>
```
You can even add some logic there (different apps depending on ip, referrer, etc), it's up to you.

# 3 `/app` #
Your application files lie in the APP directory, which defaults to /app (relative to the index.php file).

It's here you'll put all your controllers, views, external libraries and HTML-related files.

## 3.1 `/app/_config.php` ##
If it exists, it's the first file loaded on your application, in other words, it's here where you preload all external libs, define global functions and classes or tweak influx's constants defined on the [API](API.md).

## 3.2 `/app/html` ##
This is where you should put Javascript, CSS, and image files. The helper functions `css()`, `js()` and `img()` use this as root.

## 3.3 `/app/lib` ##
A place to add your external libs and components. The `load()` helper includes files stored here into the running app.

## 3.4 `/app/name.php` ##
Controllers are named /app/name.php, like
  * /app/mydata.php for `class MydataController extends Controller { ... }`
  * /app/nice\_long\_names.php for `class NiceLongNamesController extends Controller { ... }`
They will be triggered when you access `/mydata` or `/nice_long_names`.

THE DEFAULT Controller is defined by the constant INDEX.

If it's not present, Influx will display a list of all the Controllers (on /app). It should be nice to add
```
<?php 
define('INDEX', 'somecontroller/someaction'); 
?>
```
On `/app/_config.php`

## 3.5 `/app/views` ##
Directory used to store the applications views and layouts.

### 3.5.1 `/app/views/default.php` ###
Where your default application layout is defined, otherwise an ugly hardcoded one will be used.

Rendered views are inserted as the `$_content` variable. A default layout looks like
```
<html>
	<head>
		<title>Influx: Default Layout</title>
	</head>
	<body>
		<?=$_content?>
	</body>
</html>
```

It's possible to have different layouts on the same application, setting the `Controller::layout` variable (`$this->layout = 'other'` inside a controller would render `/app/views/other.php`.

### 3.5.2 `/app/views/name` ###
Views for the `NameController` reside here.

#### 3.5.2.1 `/app/views/name/action.php` ####
View for the `action` action no `name` controller.
When someone accesses `/name/action`, `NameController` is instantiated, `action()` is called and `/app/views/action.php` is rendered into the layout.