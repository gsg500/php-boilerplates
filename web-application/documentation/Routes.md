# Routes

This web-application boilerplate works with a routes file, this file can be found at: **web/routes.php**

Adding routes is very simple and straightforward, the router supports controller actions and anonymous functions.

### Example
* Controlleraction route (recommended)

````
$router->add( '/path', 'controller@action' );
````
* Anonymous function
```
$router->add( '/path', function() {
    echo "Hello world";
}
```

### Url parameters
Adding parameters to your url is just as easy as adding a route

The following placeholders are available:

* {id}
    * Captures all integers
* {name}
    * Captures one word
* {title}
    * Captures a full string that has the following characters: a-zA-Z0-9 and -
    
### Examples
* Path with {id}
```
$router->add( '/path/edit/{id}', 'PageController@edit' );

// will match /path/edit/23
```
* Path with {name}
```
$router->add( '/category/{name}', 'CategoryController@view' );

// will match /category/electronics
```
* Path with {title}
```
$router->add( '/article/{title}', 'ArticleController@view' );

//will match /article/we-had-a-nice-vacation-01
```

Placeholders are registered in **app/services/libraries/ParamType.php**, if you want to have a extra placeholder, feel free to contribute.
