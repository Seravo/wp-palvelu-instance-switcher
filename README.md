# WP-palvelu.fi Instance Switcher

A WordPress plugin for easily switching between WP-palvelu.fi instances

## Installation

1. Clone this project to your wp-content/mu-plugins directory.

2. Add this to your *wp-config.php*

```
$siteurl = $_SERVER['HTTP_HOST'];
if ( $siteurl )
    define( 'COOKIEHASH', md5( $siteurl ) . getenv('CONTAINER') );
else
    define( 'COOKIEHASH', '' );
```

To add instances, you have to define them in *wp-config.php* in the following way:
```
define( 'WPIS-PRODUCTION', '1234aa' );
define( 'WPIS-STAGING', '567bb' );
```

Use the "WPIS-" -prefix followed by the name of your instance to name the constants.
Use the hash part of your container name to define the constant value. If the name of
your container is "asdasd_123", use "123" to define the value.

