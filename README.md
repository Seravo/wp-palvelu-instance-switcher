#WP Instance switching

A Wordpress plugin for switching between WordPress instances in WP-palvelu.fi

##Installation

Create a folder under your */wp-plugins* -directory with the name *wp-instance-switching*
and copy the repo there.

**OR**

Create a zip-archive with the aforementioned directory structure.

Add this to your *wp-config.php*
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

