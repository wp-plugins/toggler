<?php  
/* 
Plugin Name: Toggler 
Plugin URI: http://dev.nimrodtsabari.net/wp/toggler-a-wordpress-plugin/ 
Description: Toggler lets you esaily toggle anything you want from withing a Post/Page.
Version: 0.2
Author: Nimrod Tsabari
Author URI: http://www.nimrodtsabari.net
*/  
/*  Copyright 2012  Nimrod Tsabari  (email : nmrdxt@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/?>
<?php

define('TOGGLER_VER', '0.2');
define('TOGGLER_DIR', plugin_dir_url( __FILE__ ));

/* Toggler : Init */
/* -------------- */

function init_toggler() {

	wp_register_style('toggler-style', TOGGLER_DIR . 'css/toggler.css');
	wp_enqueue_style('toggler-style');
	wp_register_script('toggler-script', TOGGLER_DIR . 'js/toggler.js', array('jquery'));
	wp_enqueue_script('toggler-script');
}

add_action('wp_enqueue_scripts', 'init_toggler');


/* Toggler : Shortcode adction */
/* --------------------------- */
/* @author Nimrod Tsabari
 * @since 0.1b
 * 
 * Attributes : 
 * 	"role = switch"				- for links, wrap this attr the section you want to act as the toggling button.
 * 	"role = target"				- wrap the section you want to be toggled with this attr.
 *  "connector = <enter name>" 	- connect the link and the target with the same key. 
 * 					if not used a default one is generated 
 * 	"ext = id:<enter id name>"		- if you want to toggle external elemets, add this to the link shortcode,
 * 					it will connect the link with the external element with that has this id attribute.
 * 	"ext = class:<enter class name>	- same as the above only for element with a certain that class.
 * 	"show = yes / no"				- the default state of the target, shown or hidden.
 *  "inline = yes / no"				- whether you want the target to be inline with whats around it
 *  "ghost = yes / no"				- ghost toggling or non-ghost toggling.
 */
function set_toggler($atts,$content=null) {
  extract(shortcode_atts(array(
      'role'		=> '',
      'connector'   => '',
      'ext'			=> '',
      'show'		=> 'no',
      'inline'		=> 'no',
      'ghost'		=> 'yes'
    ), $atts));

  /* Variables */
  $html = '';

  $connector	= sanitize_title(trim($connector));
  $ext 			= trim($ext);
  $show 		= strtolower($show);
  $inline 		= strtolower($inline);
  $ghost 		= strtolower($ghost);
  $role			= strtolower($role);
  
  $tag = '0';
  if ((in_array($role,array('switch','target'))) && ($content != '')) {
	 	
	  /* Show / Hide */
	  if ($show == 'yes') {
	  	$default_state = ($ghost == 'no' ? 'toggler-show-noghost' : 'toggler-show-ghost');
	  } else {
	  	$default_state = ($ghost == 'no' ? 'toggler-hide-noghost' : 'toggler-hide-ghost');
	  }
	
	  $display = ($inline == 'yes' ? ' toggler-inline' : ' toggle-block'); 
	
	  /* External Links */ 	  
	  if ($ext != '') {
	  	if (strpos($ext,'id:') === 0) {
	  		$tag = '#';
			$ext = ' ' . sanitize_title(trim(substr($ext,3)));
		} elseif (strpos($ext,'class:') === 0) {
			$tag = '.';
			$ext = ' ' . sanitize_title(trim(substr($ext,6)));
		} else {
			$tag = '0';
		}
	  }
	  /* Connector */
	  if ($connector != '') {
	  	 $link_connect = ' toggler-class-' . $connector; 
	  } else {
	  	 $link_connect = ' toggler-class-default'; 
	  }
	  /* Updating Classes of External Elements */
	  if ($tag !== '0') {
		$html .= '<script type="text/javascript">updateExtClasses("' . $tag .'", "' . trim($ext) . '", "' . $default_state . '", "'. trim($link_connect) .'");</script>';
		$link_connect .= '.'. trim($ext);
	  }
	
	  $content = str_replace("\r\n", '', $content);
	
	  /* Is it a Switch or a Target ?*/	
	  if ($role == 'switch') $html .= '<div class="toggler-link' . $display . $link_connect . '">' . do_shortcode($content) . '</div>';
	  if ($role == 'target') $html .= '<div class="toggler-target' . $display . $link_connect . '"><div class="'. $default_state .'">' . do_shortcode($content) . '</div></div>';
	  /* Go! */
	  return $html;
  } 
 }

add_shortcode( 'toggler', 'set_toggler' );
?>