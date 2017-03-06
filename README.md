# WP-palvelu.fi Instance Switcher
[![Latest Stable Version](https://poser.pugx.org/seravo/wp-palvelu-instance-switcher/v/stable)](https://packagist.org/packages/seravo/wp-palvelu-instance-switcher) [![Total Downloads](https://poser.pugx.org/seravo/wp-palvelu-instance-switcher/downloads)](https://packagist.org/packages/seravo/wp-palvelu-instance-switcher) [![Latest Unstable Version](https://poser.pugx.org/seravo/wp-palvelu-instance-switcher/v/unstable)](https://packagist.org/packages/seravo/wp-palvelu-instance-switcher) [![License](https://poser.pugx.org/seravo/wp-palvelu-instance-switcher/license)](https://packagist.org/packages/seravo/wp-palvelu-instance-switcher)

A WordPress must-use plugin for easily switching between WP-palvelu.fi shadows

## Installation

### The Composer Way (preferred)

Install the plugin via [Composer](https://getcomposer.org/)
```
composer require seravo/wp-palvelu-instance-switcher
```

Activate the plugin
```
wp plugin activate wp-palvelu-instance-switcher
```

### The Old Fashioned Way

You can also install the plugin by directly uploading the zip file as instructed below:

1. [Download the plugin](archive/master.zip)
2. Upload to the plugin to /wp-content/plugins/ via the WordPress plugin uploader or your preferred method
3. Activate the plugin

## Configuration

Add these lines to your *wp-config.php*

```php
/**
 * WP-palvelu.fi Instance Switcher required configuration
 */
$wpis_siteurl = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : getenv('HTTPS_DOMAIN_ALIAS');
if ( $wpis_siteurl )
  define( 'COOKIEHASH', md5( $wpis_siteurl ) . getenv('CONTAINER') );
else
  define( 'COOKIEHASH', '' );
```

To add instances, you have to define them in *wp-config.php* in the following way:
```php
define( 'WPIS-PRODUCTION', '1234aa' );
define( 'WPIS-STAGING', '5678bb' );
define( 'WPIS-DEVELOPMENT', '9012cc' );
```

Use the "WPIS-" -prefix followed by the name of your instance to name the constants.
Use the hash part of your container name to define the constant value. If the name of
your container is "asdasd_123", use "123" to define the value.

## Filters

You can insert your own admin notice for users that are in shadow
```php
function my_shadow_admin_notice($admin_notice, $current_screen) {
  return '<div class="notice notice-error"><p>This is staging. All content edited here will be lost. Return to production to create or edit content.</p></div>';
}
add_filter( 'wpp_instance_switcher_admin_notice', 'my_shadow_admin_notice', 10, 2 );
```
