# EasyAuth
#### A easy compnent for jwt.

##### How to use?
````
composer require ibyeyoga/easy-auth
````
##### And then:
```
use IBye\EasyAuth\Auth;

$auth = new Auth();

//getting token
$token = $auth->getToken('hello world');

//parsing token
$info = $auth->parseToken($token);
```