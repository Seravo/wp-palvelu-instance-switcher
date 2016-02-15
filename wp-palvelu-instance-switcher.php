<?php
/**
 * Plugin Name: WP-palvelu.fi Instance Switcher
 * Description: Switch between WP-palvelu.fi instances
 * Version:     1.0
 * Author: Seravo Oy
 * Author URI: http://seravo.fi
 * License: GPLv3
*/

/**
 * Copyright 2015 Seravo Oy
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

class WPP_Instance_Switcher {

  /**
   * Do the necessary initialisations here
   */
  public static function get_instance() {
    static $instance = null;
    if (null === $instance) {
      $instance = new WPP_Instance_Switcher();
    }
    return $instance;
  }

  protected function __construct(){
    add_action( 'admin_bar_menu', array( $this, 'wpis_modify_admin_bar' ), 999 );
    add_action( 'wp_ajax_wpis_change_container', array( $this, 'change_wp_container' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'wpis_init_scripts' ), 999);
    add_action( 'wp_enqueue_scripts', array( $this, 'wpis_init_scripts' ), 999);
  }

  /**
   * Load plugin specific scripts
   */
  public function wpis_init_scripts(){
    if ( !function_exists( 'is_admin_bar_showing' ) ) {
      return;
    }
    // use this within the admin bar
    if ( !is_admin_bar_showing() ) {
      return;
    }
    wp_enqueue_script( 'wpisjs', plugins_url( '/script.js' , __FILE__), null, null, true );
    wp_enqueue_style( 'wpisjs', plugins_url( '/style.css' , __FILE__), null, null, 'all' );
  }

  /**
   * Create the menu itself
   */
  public function wpis_modify_admin_bar( WP_Admin_Bar $wp_admin_bar ){

    if ( !function_exists( 'is_admin_bar_showing' ) ) {
      return;
    }

    // use this within the admin bar
    if ( !is_admin_bar_showing() ) {
      return;
    }

    // check permissions
    if(!current_user_can( 'activate_plugins' )){
      return;
     }

    $instances = $this->get_defined_instances();
    $id = 'wpis';
    $current_instance = getenv('CONTAINER');

    // "env_hash" made to "hash"
    $instance_index = strpos($current_instance,'_') + 1;
    for( $x = 0 ; $x < $instance_index; ++$x ) {
      $current_instance = substr($current_instance, 1);
    }

    // define the name of the current instance to be shown in the bar
    foreach( $instances as $key => $instance ){
      if($current_instance == $instance){
        $current_instance = substr($key, 5);
      }
    }

    $domain = ""; //$this->get_domain( $_SERVER['HTTP_HOST'] );

    if ( getenv('WP_ENV') && getenv('WP_ENV') != 'production') {
      $menuclass = 'wpis-warning';
    }

    // create the parent menu here
    $wp_admin_bar->add_menu(array('id' => $id, 'title' => $current_instance, 'href' => '#', 'meta' => array('class' => $menuclass)));

    // for every instance create a menu entries
    foreach($instances as $key => $instance) {
       $wp_admin_bar->add_menu(array(
         'parent' => $id,
         'title' => substr($key, 5),
         'id' => $instance,
         'href' => "#$instance",
       ));
     }

    // Last item is always to exit shadow
    $wp_admin_bar->add_menu(array(
      'parent' => $id,
      'title' => 'Exit shadow',
      'id' => 0,
      'href' => "#exit",
    ));

    }

  private function get_defined_instances(){
    // get the list of all available constants
    $constants = get_defined_constants();
    // get the wpis specific constants
    foreach( $constants as $key => $constant){
      if(!preg_match('#WPIS-#',$key)){
        unset($constants[$key]);
      }
    }
    return $constants;
  }

  // prevent default behaviour
  private function __clone(){}
  private function __wakeup(){}
}

$instance_switching = WPP_Instance_Switcher::get_instance();
