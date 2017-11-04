# talog

[![Build Status](https://travis-ci.org/tarosky/talog.svg?branch=master)](https://travis-ci.org/tarosky/talog)

A logging plugin for WordPress. You always can see what changed and who changed it.

Download: https://github.com/tarosky/talog/releases

## It is logging:

* WordPress
	* Core updates
	* Plugin/Theme updates
	* Language updates
* Post/Page/Attachment
	* Created
	* Updated
	* Deleted
* Plugin
	* Activated
	* Deactivated
* Theme
	* Switched
* User
	* Logged in
* XML-RPC
	* Authenticated
	* Created
	* Updated
	* Deleted
* Debug
	* PHP errors

## Customizing

### Add your custom log events

1. Create a class that extends the `Talog\Logger` class.
2. Load the class by `Talog\init_log()` like following.

```
add_action( 'plugins_loaded', function() {
	Talog\init_log( 'Hello\Example' );
} );
```

* [The example class is here.](https://github.com/tarosky/talog/blob/master/example/Example.php)
* See also [defaults](https://github.com/tarosky/talog/tree/master/src/Logger).

## Screenshot

### List of logs

![](https://www.evernote.com/l/ABUg-wL0wbtAFoQ8dTuN-206ZVeKmSk2NwgB/image.png)

### Detail screen of the log

Updated post:

![](https://www.evernote.com/l/ABWIQoGQcxdAnaPmKKVHawUxZ3UIJfTs64EB/image.png)

Last error of PHP:

![](https://www.evernote.com/l/ABW7wljExtpNLq2XZ5p72-zKkH7PQ6FBYxQB/image.png)
