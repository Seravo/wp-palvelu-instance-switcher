#WP Instance switching

A Wordpress plugin for switching between Wordpress instances in WP-palvelu.fi

##Installation

Create a folder under your */wp-plugins* -directory with the name *wp-instance-switching*
and copy the repo there.

**OR**

Create a zip-archive with the aforementioned directory structure

Add the following to your wp-config.php:
``` 
  $siteurl = $_SERVER['HTTP_HOST'];
  if ( $siteurl )
  define( 'COOKIEHASH', md5( $siteurl ) . getenv('CONTAINER') );
  else
  define( 'COOKIEHASH', '' );

  define( 'PRODUCTION_ENV', 'YOUR_DEFINITION_HERE' );
  define( 'STAGING_ENV', 'YOUR_DEFINITION_HERE' );
```
Please notice that the "YOUR_DEFINITION" is the HASH-part of your shadow cookie.

The right way when the cookie name is *asd_123*:
```
define( 'PRODUCTION_ENV', '123' );

```
The wrong way: 

```
define( 'PRODUCTION_ENV', 'asd_123' );

```
