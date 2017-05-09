# WP-palvelu.fi Instance Switcher
[![Latest Stable Version](https://poser.pugx.org/seravo/wp-palvelu-instance-switcher/v/stable)](https://packagist.org/packages/seravo/wp-palvelu-instance-switcher) [![Total Downloads](https://poser.pugx.org/seravo/wp-palvelu-instance-switcher/downloads)](https://packagist.org/packages/seravo/wp-palvelu-instance-switcher) [![Latest Unstable Version](https://poser.pugx.org/seravo/wp-palvelu-instance-switcher/v/unstable)](https://packagist.org/packages/seravo/wp-palvelu-instance-switcher) [![License](https://poser.pugx.org/seravo/wp-palvelu-instance-switcher/license)](https://packagist.org/packages/seravo/wp-palvelu-instance-switcher)

A WordPress must-use plugin for easily switching between WP-palvelu.fi shadows

## THIS REPOSITORY IS DEPRECATED

This plugin as of commit bfb019a923838bf60fbc3bad652c823303b09239 was merged into the [Seravo Plugin](https://github.com/Seravo/seravo-plugin), in pull request https://github.com/Seravo/seravo-plugin/pull/36.

All future development goes there and this repository will eventually be deleted in 2018 or so.

## Filters

You can insert your own admin notice for users that are in shadow
```php
function my_shadow_admin_notice($admin_notice, $current_screen) {
  return '<div class="notice notice-error"><p>This is staging. All content edited here will be lost. Return to production to create or edit content.</p></div>';
}
add_filter( 'wpp_instance_switcher_admin_notice', 'my_shadow_admin_notice', 10, 2 );
```
