<?php
/*
Plugin Name: WP Instance switching
Description: Switch between WP instances
Version:     0.0.1
Author:      Tari Zahabi / Seravo
Domain Path: /languages/
License:     BSD 2-Clause

* Copyright (c) 2015, Tari Zahabi
* All rights reserved.
* Redistribution and use in source and binary forms, with or without
* modification, are permitted provided that the following conditions are met:
*
*     * Redistributions of source code must retain the above copyright
*       notice, this list of conditions and the following disclaimer.
*     * Redistributions in binary form must reproduce the above copyright
*       notice, this list of conditions and the following disclaimer in the
*       documentation and/or other materials provided with the distribution.
*     * Neither the name of the University of California, Berkeley nor the
*       names of its contributors may be used to endorse or promote products
*       derived from this software without specific prior written permission.
*
* THIS SOFTWARE IS PROVIDED BY THE REGENTS AND CONTRIBUTORS ``AS IS'' AND ANY
* EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
* DISCLAIMED. IN NO EVENT SHALL THE REGENTS AND CONTRIBUTORS BE LIABLE FOR ANY
* DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
* (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
* LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
* ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
* (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
* SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.

*/


class instance_switching {

  /**
  * Do the necessary initializations here
  */  
  public static function get_instance() {
    static $instance = null;
    if (null === $instance) {
      $instance = new instance_switching();
    }
    return $instance;
  }
  
  protected function __construct(){
    //add_action( 'admin_init', array( $this, 'wpis_set_instance_cookie' ),999 );
    add_action( 'admin_bar_menu', array( $this, 'wpis_modify_admin_bar' ),999 );
    add_action( 'wp_ajax_wpis_change_container', array( $this, 'change_wp_container' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'wpis_init_scripts' ),999);
  }

  /**
   * Load plugin specific scripts
   */
  
  public function wpis_init_scripts(){    
    wp_register_script( 'js.cookie', plugins_url( '/script/js.cookie.js' , __FILE__), null, null, true );
    wp_register_script( 'wpisjs', plugins_url( '/script/wpis.js' , __FILE__), null, null, true );
    wp_enqueue_script( 'js.cookie' );
    wp_enqueue_script( 'wpisjs' );
  }

  /**
   * Create the menu itself 
   */

  public function wpis_modify_admin_bar( WP_Admin_Bar $wp_admin_bar ){    
    if ( !function_exists( 'is_admin_bar_showing' ) ) {
      return;
    }
    if ( !is_admin_bar_showing() ) {
      return;
    }

    if(!current_user_can( 'activate_plugins' )){
      return;
     }   
   
    $instances = array('production' => PRODUCTION_ENV, 'shadow-1' => STAGING_ENV);
    $id = 'wpis';
    $current_instance = getenv('CONTAINER');
    
    //"env_hash" made to "hash"
    $instance_index = strpos($current_instance,'_') + 1;
    for($x=0;$x<$instance_index;$x++){
        $current_instance = substr($current_instance, 1);
    }
    //define the name of the current instance to be shown in the bar
    foreach( $instances as $key => $instance ){
      if($current_instance == $instance){
        $current_instance = $key;
      }
    }
    
    $domain = $_SERVER['HTTP_HOST'];
    $domain_index=strpos($domain,'.');
    
    //chop chop 
    for($x=0;$x<$domain_index;$x++){
        $domain = substr($domain, 1);
    }
    
    //create the parent menu here
    $wp_admin_bar->add_menu(array('id' => $id, 'title' => $current_instance, 'href' => '#'));
    //for every instance create a menu entrys
    foreach($instances as $key => $instance){ 
      $wp_admin_bar->add_menu(array
      ( 'parent' => $id,
        'title' => $key,
        'id' => $instance,
        'href' => '#',
        'meta' =>
          array('onclick' =>'wpisSetShadow("'.$instance.'","'.$domain.'");')));
          
        }
  }

  //prevent default behaviour
  private function __clone(){}
  private function __wakeup(){}
}

$instance_switching = instance_switching::get_instance();
