#WP Instance switching

A Wordpress plugin for switching between Wordpress instances in WP-palvelu.fi

##Installation

Create a folder under your */wp-plugins directory with the name *wp-instance-switching*
and copy the repo there.
**OR**
Create a zip-archive with the directory structure described above and install the plugin
via wp-admin.

Define the instances in *wp-instance-switching.php* and add the following to your wp-config.php:
``` 
  $siteurl = $_SERVER['HTTP_HOST'];
  if ( $siteurl )
  define( 'COOKIEHASH', md5( $siteurl ) . getenv('CONTAINER') );
  else
  define( 'COOKIEHASH', '' );
```
