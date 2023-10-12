# How to use routing

## Basic usage

```php
#[HttpPath("/my-method","get")]
function controller_method(String $name, String $timestamp){
    $timestamp = intval($timestamp);
    $date = date("m.d.y", $timestamp);
    return "[$date] Hello $name";
}
```
A GET request to `/mymethod?name=Carla&timestamp=1201953600` should answer `[02.02.2008] Hello Carla`

Two controller methods can't share the same route, even if parameters are different.

## Methods
By default, GET is used

Arguments from GET will always be String.

## In-path arguments
```php
#[HttpPath("/hello/[username]", "post")]
function hello(#[HttpInPath("username")] $name){
    return "Hello $name";
}
```
A POST request to `/hello/Edouard` should answer `Hello Edouard`

Note: a request to `/hello/name/Edouard` will match sith `$name="name/Edouard"`. To prevent is, add a slash: `/hello/[username]/`

## Optional arguments
```php
#[HttpPath("/hello")]
function hello($name="World"){
    return "Hello $name";
}
```
With this controller:\
`/hello` -> `Hello World`\
`/hello?name=Francois` -> `Hello Francois`

In-path arguments should not be optional, but you can do this:

```php
function rawHello($name="World"){
    return "Hello $name";
}

#[HttpPath("/hello/[username]")]
function hello(#[HttpInPath] $username){
    return $this->rawHello($username);
}


#[HttpPath("/hello")]
function helloNull(){
    return $this->rawHello();
}

```



## Route collision
Considering these routes:
- `/[name]/`
- `/[fullpath]`
- `/[operation]/nicolas`
- `/hello/[name]`
- `/hello/[name]/[surname]`
- `/hello/[name]/onlyname`

`/hello` matches 2 routes: `/[name]/` and `/[fullpath]`
 - `/[name]/` is more specific, and therefore will be used

`/hello/patrick/onlyname` matches 4 routes: `/hello/[name]` , `/hello/[name]/[surname]`, `/hello/[name]/onlyname` and `/[fullpath]`
 - **routes with more slashes are more specific**, so only `/hello/[name]/[surname]` and `/hello/[name]/onlyname` will be considered
 - the first difference encountered is `[surname] | onlyname` **routes with no argument on this diff are more specific**, so `/hello/[name]/onlyname` will be used

