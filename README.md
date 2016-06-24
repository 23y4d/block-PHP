# Security: Automatically block someone using a PHP and .htaccess


This can be helpful for a number of reasons. For example, you can use this class to ban people that are snooping around your website or see your file in website , or to ban robots that don’t respect your robots.txt file  



## Usage ##
create file named d.php  and put in this config 

```php
<?php
  require_once 'block.php';
  
  $whitelist = array(
  // '123.123.123.123',
  // '127.0.0.1'
);

new block($email,$whitelist);

?>
```

## Author
* [ZEYAD BESISO](https://github.com/23y4d/)

## Copyright and license
 Copyright 2016 under MIT License.
