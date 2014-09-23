php-apn
=======

php-apn is a Simple Class for Apple Push Nofitication


Composer
========
This plugin on the Packagist.

[https://packagist.org/packages/kittinan/php-apn](https://packagist.org/packages/kittinan/php-apn)



Usage
=====

```php
$CertPath = 'ck.pem';
$PassPhrase = '12345';

$apn = new \KS\APN($CertPath, $PassPhrase);
$token = 'xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx';
$message = 'Hello Push Notification';
$apn->send($token, $message);

```

License
=======
The MIT License (MIT)
