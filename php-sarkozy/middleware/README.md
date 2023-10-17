# How to use Middleware module

This module allows you to create middleware which allows you to intercept the request or response, this can be interesting for authentication or any other action to be performed on a request or response.

## Simple Middleware

```php
#[Middleware]
class MyMiddleware implements MiddlewareInterface{

    function intercept_request(Request $request): Request{
        // Do something with the request
        return $request;
    }

    function intercept_response(Response $response): Response{
        // Do something with the response
        return $response;
    }
}
```

To create Middleware there are two things to respect: 
* Use the `#[Middleware]` attribute 
* And implement the `MiddlewareInterface` interface


## Middleware Priorities

```php
#[Middleware(2)]
class MyMiddleware1 implements MiddlewareInterface{

    //...

}

#[Middleware(1)]
class MyMiddleware2 implements MiddlewareInterface{

    //...

}
```
The `#[Middleware(int)]` attribute allows you to sort middleware in order of execution. 

In this case, MyMiddleware1 will be executed before the second even if it is declared after.

## Note

``Middlewares`` are instantiated at application initialization, you can then create variables to manipulate them as you wish
