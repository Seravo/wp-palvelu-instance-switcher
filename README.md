#WP Instance switching:

A Wordpress plugin for switching between Wordpress instances in WP-palvelu.fi

add this to your wp-config.php:
	
	$siteurl = $_SERVER['HTTP_HOST'];
	if ( $siteurl )
		define( 'COOKIEHASH', md5( $siteurl ) . getenv('CONTAINER') );
	else
		define( 'COOKIEHASH', '' );
