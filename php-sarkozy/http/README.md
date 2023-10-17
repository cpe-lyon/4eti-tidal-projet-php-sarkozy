# How to use Http module

This module allows you to create endpoints easily, to do this you simply need to create a `Sarkontroller` and implement functions which will each correspond to an endpoint.

## Content-type

```php
#[Sarkontroller]
class MyController{

    #[HttpProduces("application/json")]
    public function json(){
        return array (
                "toto",
                24,
                "fruits"  => array("a" => "orange", "b" => "banana", "c" => "apple"),
                "numbers" => array(1, 2, 3, 4, 5, 6),
                "holes"   => array("first", 5 => "second", "third")
        );
    }

}
```
The `#[HttpProduces("content/type")]` attribute allows forcing the Content-type of the HTTP response although there is a Content-type detection mechanism to try to guess the return type.



## Headers
```php
#[Sarkontroller]
class MyController{

    #[HttpProduces("image/x-icon")]
    #[HttpEnforceHeader("Content-Disposition","inline; filename=\"favicon.ico\"")]
    #[HttpEnforceHeader('Access-Control-Allow-Origin', '*')]
    #[HttpEnforceHeader("Content-Security-Policy", "default-src *")]
    public function faviconico(){
        $data = file_get_contents("./favicon.ico");
        return $data;
    }

}
```
The `#[HttpEnforceHeader('header', 'value')]` attribute allows you to add headers to the HTTP response