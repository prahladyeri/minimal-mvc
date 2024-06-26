![license](https://img.shields.io/github/license/prahladyeri/minimal-mvc.svg)
![last-commit](https://img.shields.io/github/last-commit/prahladyeri/minimal-mvc.svg)
[![patreon](https://img.shields.io/badge/Patreon-brown.svg?logo=patreon)](https://www.patreon.com/prahladyeri)
[![paypal](https://img.shields.io/badge/PayPal-blue.svg?logo=paypal)](https://paypal.me/prahladyeri)
[![follow](https://img.shields.io/twitter/follow/prahladyeri.svg?style=social)](https://twitter.com/prahladyeri)

**minimal-mvc** is a humble attempt to de-cruft and de-bloat the scene of web frameworks. It's a specialized micro framework for following use cases or traits:

1. Freelancers, students and hobbyists who want to experiment with PHP.
2. Your app has simple CRUD workflow and just needs basic routing and templating capabilities of PHP.
3. Your app is mostly frontend heavy (SPA, etc.) and uses PHP for very basic features like routing.
4. You are developing a REST API.
5. You find it unnecessary to optimize for hypothetical futuristic scaling.
6. Not a huge fan of applying OOP everywhere.
7. Generally prefer to work with core language capabilities than hand-holding of a heavy framework.

**How to use minimal-mvc framework:**

Just download this repo and use it to prototype your app. The core consists of only two PHP scripts which are required in index.php:

- `core/router.php` - For routing capabilities.
- `core/util.php` - For generic utility functions.

In `index.php`, you can handle basic routing easily like this:

```php
function index() {
	echo "<h1>It Works!</h1>";
};
```

This is a very simple routing arrangement where each function inside index.php is a route with the `index()` function being the main or default route. For example, `/` routes to `index()`, `/api` routes to `api()`, etc.

```php
function api() {
	echo "<p>Pattern Match!</p>"; // http://localhost/api/foo
	echo "<p>The uri segments are :".print_r(uri_segments(),true)."</p>";
};
```

You can know the current HTTP method by `get_method()` utility function and get individual route segments using the `uri_segment()` utility function (such as 'api' in case of `uri_segment(1)` where route is `/api/foo/bar`). Similarly, `uri_segments()` returns an array consisting of all route segments.

For views/templates, you can use the load_template() utility function as shown in this built-in example:

```php
function testmvc() {
	$vars = ["foo"=>'bar', 'title'=>'Testing'];
	load_template('templates/dummy.php', $vars);
};
```

The template system works on a stereotype base template (`templates/base.php`) which can include all the frontend details like link and script tags to bootstrap, react, jquery, etc. And it should contain a placeholder called `$__contentfile` somewhere in the body section for the contents of "child template" (such as the built-in `templates/dummy.php` template) which is derived or inherited from the base template and directly passed in the `load_template()` utility function. In that child template, all variables you pass (`$vars` in this example) will be extrapolated for you to use. Note that we will not use any specific template language like `jinja` or `twig` as PHP itself is a template engine.

In addition to that, the framework also includes a static directory to store your static files like stylesheets, ECMA scripts, images, etc.

Other useful utility functions are `base_url()` and `site_url()`. These are useful for resolving full url paths when your app is hosted inside a sub folder like `http://<some-domain>/subfolder` or when you want to resolve the actual url from a route such as "foo/bar". For almost everything else under the Sun, PHP is more than capable of handling whatever you throw at it.

The routing capability provided here is very basic, any further implementation will be a DIY. Other frameworks provide fancy routes like `/foo/bar/{slug}` and `/article/{locale}` which appears mind-blowing initially. But once you consider that PHP provides you a built-in called `$_SERVER['REQUEST_URI']` which you can parse yourself inside the `/foo/bar/` or `/article/` routes to get these yourself, that magic starts waning! To make things a bit easier, this framework provides you the shortcut utility function `uri_segment()` as mentioned earlier to determine these so called fancy variables:

```php
echo uri_segment(3); // outputs the {slug} value or third segment in the URI
```

**What Next?**

The util.php is a work in progress and will keep improving with time. The idea is really that simple, PHP was originally built as a language that employed functions to manage its workflow (to a great extent, it still does), and minimal-mvc is also in the same spirit. If your app increases in complexity or scale, you can put the controller logic inside additional script modules and require them in index.php like this:

```php
function foo() {
	require_once("controllers/foo_controller.php");
};
```

It's upto you whether you name that folder controllers or something else, whether you use classes inside the script or plain old functions. As I said, there will be no hand holding!