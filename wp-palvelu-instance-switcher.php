<?php
/**
 * Plugin Name: WP-palvelu.fi Instance Switcher
 * Description: Switch between WP-palvelu.fi instances
 * Version: 1.0.1
 * Author: Seravo Oy
 * Author URI: https://wp-palvelu.fi
 * License: GPLv3
 * Text-domain: wpp-instance-switcher
*/

/**
 * Copyright 2016 Seravo Oy
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 3, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if( ! class_exists( 'WPP_Instance_Switcher' ) ) :

class WPP_Instance_Switcher {
  public static $instance;

  public static function init() {
    if ( is_null( self::$instance ) ) {
      self::$instance = new WPP_Instance_Switcher();
    }
    return self::$instance;
  }

  protected function __construct(){
		// load textdomain for translations
		add_action( 'plugins_loaded',  array( $this, 'load_our_textdomain' ) );

    // only run the instance switcher when in a container environment
    if( ! getenv('CONTAINER') ) {
      return;
    }

    // add the instance switcher menu
    add_action( 'admin_bar_menu', array( $this, 'add_switcher' ), 999 );

    // admin ajax action
    add_action( 'wp_ajax_wpis_change_container', array( $this, 'change_wp_container' ) );
    add_action( 'wp_ajax_nopriv_wpis_change_container', array( $this, 'change_wp_container' ) );

    // styles and scripts for the switcher
    add_action( 'admin_enqueue_scripts', array( $this, 'assets' ), 999);
    add_action( 'wp_enqueue_scripts', array( $this, 'assets' ), 999);

    // display a notice at the bottom of the window when in a shadow
    if ( getenv('WP_ENV') && getenv('WP_ENV') != 'production' ) {
      add_action('admin_footer', array( $this, 'render_shadow_indicator' ) );
      add_action('wp_footer', array( $this, 'render_shadow_indicator' ) );
      add_action('login_footer', array( $this, 'render_shadow_indicator' ) );
    }
  }

  private function get_defined_instances() {
    // get the list of all available constants
    $constants = get_defined_constants();

    // get the wpis specific constants
    foreach( $constants as $key => $constant ) {
      if( ! preg_match( '#WPIS-#', $key ) ) {
        unset( $constants[$key] );
      }
    }
    return $constants;
  }

  /**
   * Load javascript and stylesheets for the switcher
   */
  public function assets(){
    if ( !function_exists( 'is_admin_bar_showing' ) ) {
      return;
    }

    // use this within the admin bar
    if ( !is_admin_bar_showing() ) {
      return;
    }
    wp_enqueue_script( 'wpisjs', plugins_url( '/assets/script.js' , __FILE__), null, null, true );
    wp_enqueue_style( 'wpisjs', plugins_url( '/assets/style.css' , __FILE__), null, null, 'all' );
  }

  /**
   * Create the menu itself
   */
  public function add_switcher( WP_Admin_Bar $wp_admin_bar ){
    if ( ! function_exists( 'is_admin_bar_showing' ) ) {
      return;
    }

    // use this within the admin bar
    if ( ! is_admin_bar_showing() ) {
      return;
    }

    // check permissions
    if( ! current_user_can( 'activate_plugins' )){
      return;
    }

    if( empty( $instances = $this->get_defined_instances() ) ) {
      return;
    }

    $id = 'wpis';
    $current_instance = getenv('CONTAINER');

    $instance_index = strpos($current_instance,'_') + 1;
    for( $x = 0 ; $x < $instance_index; ++$x ) {
      $current_instance = substr($current_instance, 1);
    }

    // define the name of the current instance to be shown in the bar
    foreach( $instances as $key => $instance ) {
      if($current_instance == $instance){
        $current_instance = substr($key, 5);
      }
    }

    $domain = ""; //$this->get_domain( $_SERVER['HTTP_HOST'] );

    if ( getenv('WP_ENV') && getenv('WP_ENV') != 'production' ) {
      $menuclass = 'wpis-warning';
    }

    // create the parent menu here
    $wp_admin_bar->add_menu([
			'id' => $id,
			'title' => $current_instance,
			'href' => '#',
			'meta' => [
				'class' => $menuclass,
			],
		]);

    // add menu entries for each shadow
    foreach($instances as $key => $instance) {
			$wp_admin_bar->add_menu([
				'parent' => $id,
				'title' => substr($key, 5),
				'id' => $instance,
				'href' => "#$instance",
			]);
    }

    // Last item is always to exit shadow
    $wp_admin_bar->add_menu(array(
      'parent' => $id,
      'title' => __('Exit Shadow', 'wpp-instance-switcher'),
      'id' => 'exit-shadow',
      'href' => "#exit",
    ));
  }

	/**
   * Display a notice at the bottom of the window when inside a shadow
   */
  public function render_shadow_indicator() {
?>
<style>#shadow-indicator { font-family: Arial, sans-serif; position: fixed; bottom: 0; left: 0; right: 0; width: 100%; color: #fff; background: #cc0000; z-index: 3000; font-size:16px; line-height: 1; text-align: center; padding: 5px } #shadow-indicator a.clearlink { text-decoration: underline; color: #fff; }</style>
<div id="shadow-indicator">
<?php echo wp_sprintf( __('You are currently in %s.', 'wpp-instance-switcher'), getenv( 'WP_ENV' ) ); ?> <a class="clearlink" href="/?wpp_shadow=clear"><?php _e('Exit', 'wpp-instance-switcher'); ?></a>
</div>
<?php
  }

  /**
   * Load our textdomain
   */
  public static function load_our_textdomain() {
    load_plugin_textdomain( 'wp-safe-updates', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );
  }
}

endif;

$instance_switching = WPP_Instance_Switcher::init();

