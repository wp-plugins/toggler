<?php  
/* 
Plugin Name: Toggler 
Plugin URI: http://www.omniwp.com/plugins/toggler-a-wordpress-plugin/ 
Description: Toggler lets you esaily toggle anything you want from withing a Post/Page.
Version: 1.0
Author: Nimrod Tsabari / omniWP
Author URI: http://www.omniwp.com
*/  
/*  Copyright 2012 Nimrod Tsabari / omniWP  (email : yo@omniwp.com)

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

define('TOGGLER_VER', '1.0');
define('TOGGLER_DIR', plugin_dir_url( __FILE__ ));

/* Toggler : Init */
/* -------------- */

function init_toggler() {

	wp_register_style('toggler-style', TOGGLER_DIR . 'css/toggler.css');
	wp_enqueue_style('toggler-style');
	wp_register_script('toggler-script', TOGGLER_DIR . 'js/toggler.min.js', array('jquery'));
	wp_enqueue_script('toggler-script');
}

add_action('wp_enqueue_scripts', 'init_toggler');


/* Toggler : Activation */
/* -------------------- */

define('TOGGLER_NAME', 'Toggler');
define('TOGGLER_SLUG', 'toggler_registration');

register_activation_hook(__file__,'omni_toggler_admin_activate');
add_action('admin_notices', 'omni_toggler_admin_notices');	

function omni_toggler_admin_activate() {
	$reason = get_option('omni_plugin_reason');
	if ($reason == 'nothanks') { 
		update_option('omni_plugin_on_list',0);
	} else {		
		add_option('omni_plugin_on_list',0);
		add_option('omni_plugin_reason','');
	}
}

function omni_toggler_admin_notices() {
	if ( get_option('omni_plugin_on_list') < 2 ){		
		echo "<div class='updated'><p>" . sprintf(__('<a href="%s">' . TOGGLER_NAME . '</a> needs your attention.'), "options-general.php?page=" . TOGGLER_SLUG). "</p></div>";
	}
} 

/*  Toggler : Admin Part  */
/* --------------------- */
/* Inspired by Purwedi Kurniawan's SEO Searchterms Tagging 2 Pluging */

function toggler_admin() {
	if (omni_toggler_list_status()) omni_toggler_thank_you(); 
}            

function toggler_admin_init() {
	$onlist = get_option('omni_plugin_on_list');
	if ($onlist < '2') add_options_page("Toggler| Registration", "Toggler| Registration", 1, "toggler_registration", "toggler_admin");
}

add_action('admin_menu', 'toggler_admin_init');

function omni_toggler_thank_you() {
	wp_redirect(admin_url());
}

function omni_toggler_list_status() {
	$onlist = get_option('omni_plugin_on_list');
	$reason = get_option('omni_plugin_reason');
	if ( trim($_GET['onlist']) == 1 || $_GET['no'] == 1 ) {
		$onlist = 2;
		if ($_GET['onlist'] == 1) update_option('omni_plugin_reason','onlist');
		if ($_GET['no'] == 1) {
			 if ($reason != 'onlist') update_option('omni_plugin_reason','nothanks');
		}
		update_option('omni_plugin_on_list', $onlist);
	} 
	if ( ((trim($_GET['activate']) != '' && trim($_GET['from']) != '') || trim($_GET['activate_again']) != '') && $onlist != 2 ) { 
		update_option('omni_plugin_list_name', $_GET['name']);
		update_option('omni_plugin_list_email', $_GET['from']);
		$onlist = 1;
		update_option('omni_plugin_on_list', $onlist);
	}
	if ($onlist == '0') {
		if (isset($_GET['noheader'])) require_once(ABSPATH . 'wp-admin/admin-header.php');
		omni_toggler_register_form_1('toggler_registration');
	} elseif ($onlist == '1') {
		if (isset($_GET['noheader'])) require_once(ABSPATH . 'wp-admin/admin-header.php');
		$name = get_option('omni_plugin_list_name');
		$email = get_option('omni_plugin_list_email');
		omni_toggler_do_list_form_2('toggler_confirm',$name,$email);
	} elseif ($onlist == '2') {
		return true;
	}
}

function omni_toggler_register_form_1($fname) {
	global $current_user;
	get_currentuserinfo();
	$name = $current_user->user_firstname;
	$email = $current_user->user_email;
?>
	<div class="register" style="width:50%; margin: 100px auto; border: 1px solid #BBB; padding: 20px;outline-offset: 2px;outline: 1px dashed #eee;box-shadow: 0 0 10px 2px #bbb;">
		<p class="box-title" style="margin: -20px; background: #489; padding: 20px; margin-bottom: 20px; border-bottom: 3px solid #267; color: #EEE; font-size: 30px; text-shadow: 1px 2px #267;">
			Please register the plugin...
		</p>
		<p>Registration is <strong style="font-size: 1.1em;">Free</strong> and only has to be done <strong style="font-size: 1.1em;">once</strong>. If you've register before or don't want to register, just click the "No Thank You!" button and you'll be redirected back to the Dashboard.</p>
		<p>In addition, you'll receive a a detailed tutorial on how to use the plugin and a complimentary subscription to our Email Newsletter which will give you a wealth of tips and advice on Blogging and Wordpress. Of course, you can unsubscribe anytime you want.</p>
		<p><?php omni_toggler_registration_form($fname,$name,$email);?></p>
		<p style="background: #F8F8F8; border: 1px dotted #ddd; padding: 10px; border-radius: 5px; margin-top: 20px;"><strong>Disclaimer:</strong> Your contact information will be handled with the strictest of confidence and will never be sold or shared with anyone.</p>
	</div>	
<?php
}

function omni_toggler_registration_form($fname,$uname,$uemail,$btn='Register',$hide=0, $activate_again='') {
	$wp_url = get_bloginfo('wpurl');
	$wp_url = (strpos($wp_url,'http://') === false) ? get_bloginfo('siteurl') : $wp_url;
	$thankyou_url = $wp_url.'/wp-admin/options-general.php?page='.$_GET['page'].'&amp;noheader=true';
	$onlist_url   = $wp_url.'/wp-admin/options-general.php?page='.$_GET['page'].'&amp;onlist=1'.'&amp;noheader=true';
	$nothankyou_url   = $wp_url.'/wp-admin/options-general.php?page='.$_GET['page'].'&amp;no=1'.'&amp;noheader=true';
	?>
	
	<?php if ( $activate_again != 1 ) { ?>
	<script><!--
	function trim(str){ return str.replace(/(^\s+|\s+$)/g, ''); }
	function imo_validate_form() {
		var name = document.<?php echo $fname;?>.name;
		var email = document.<?php echo $fname;?>.from;
		var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
		var err = ''
		if ( trim(name.value) == '' )
			err += '- Name Required\n';
		if ( reg.test(email.value) == false )
			err += '- Valid Email Required\n';
		if ( err != '' ) {
			alert(err);
			return false;
		}
		return true;
	}
	//-->
	</script>
	<?php } ?>
	<form name="<?php echo $fname;?>" method="post" action="http://www.aweber.com/scripts/addlead.pl" <?php if($activate_again!=1){;?>onsubmit="return imo_validate_form();"<?php }?> style="text-align:center;" >
		<input type="hidden" name="meta_web_form_id" value="81071885" />
		<input type="hidden" name="listname" value="toggler" />  
		<input type="hidden" name="redirect" value="<?php echo $thankyou_url;?>">
		<input type="hidden" name="meta_redirect_onlist" value="<?php echo $onlist_url;?>">
		<input type="hidden" name="meta_adtracking" value="toggler_register" />
		<input type="hidden" name="meta_message" value="1">
		<input type="hidden" name="meta_required" value="from,name">
		<input type="hidden" name="meta_forward_vars" value="1">	
		 <?php if ( $activate_again == 1 ) { ?> 	
			 <input type="hidden" name="activate_again" value="1">
		 <?php } ?>		 
		<?php if ( $hide == 1 ) { ?> 
			<input type="hidden" name="name" value="<?php echo $uname;?>">
			<input type="hidden" name="from" value="<?php echo $uemail;?>">
		<?php } else { ?>
			<p>Name: </td><td><input type="text" name="name" value="<?php echo $uname;?>" size="25" maxlength="150" />
			<br />Email: </td><td><input type="text" name="from" value="<?php echo $uemail;?>" size="25" maxlength="150" /></p>
		<?php } ?>
		<input class="button-primary" type="submit" name="activate" value="<?php echo $btn; ?>" style="font-size: 14px !important; padding: 5px 20px;" />
	</form>
    <form name="nothankyou" method="post" action="<?php echo $nothankyou_url;?>" style="text-align:center;">
	    <input class="button" type="submit" name="nothankyou" value="No Thank You!" />
    </form>
	<?php
}

function omni_toggler_do_list_form_2($fname,$uname,$uemail) {
	$msg = 'You have not clicked on the confirmation link yet. A confirmation email has been sent to you again. Please check your email and click on the confirmation link to register the plugin.';
	if ( trim($_GET['activate_again']) != '' && $msg != '' ) {
		echo '<div id="message" class="updated fade"><p><strong>'.$msg.'</strong></p></div>';
	}
	?>
	<div class="register" style="width:50%; margin: 100px auto; border: 1px dotted #bbb; padding: 20px;">
		<p class="box-title" style="margin: -20px; background: #489; padding: 20px; margin-bottom: 20px; border-bottom: 3px solid #267; color: #EEE; font-size: 30px; text-shadow: 1px 2px #267;">Thank you...</p>
		<p>A confirmation email has just been sent to your email @ "<?php echo $uemail;?>". In order to register the plugin, check your email and click on the link in that email.</p>
		<p>Click on the button below to Verify and Activate the plugin.</p>
		<p><?php omni_toggler_registration_form($fname.'_0',$uname,$uemail,'Verify and Activate',$hide=1,$activate_again=1);?></p>
		<p>Disclaimer: Your contact information will be handled with the strictest confidence and will never be sold or shared with third parties.</p>
	</div>	
	<?php
}

/*  Toggler : Adding a Custom Field to Attachmetns */
/* ------------------------------------------------ */

function toggler_attachment_fields_to_edit($form_fields, $post) {
	$form_fields["toggler_icon"]["label"] = __("Toggler Icon");
	$form_fields["toggler_icon"]["input"] = "text";
	$form_fields["toggler_icon"]["value"] = get_post_meta($post->ID, "_toggler_icon", true);
  	$form_fields["toggler_icon"]["extra_rows"] = array(  
      "ppaw_style" => "Give the icon an identifier to use in a Toggler.");
	return $form_fields;
}

add_filter("attachment_fields_to_edit", "toggler_attachment_fields_to_edit", null, 2);

function toggler_attachment_fields_to_save($post, $attachment) {  
  if(isset($attachment['toggler_icon'])){  
    update_post_meta($post['ID'], '_toggler_icon', $attachment['toggler_icon']);  
  }  
  return $post;  
}  

add_filter('attachment_fields_to_save','toggler_attachment_fields_to_save',null,2);


function extract_toggler_icon($icon,$pid) {
	$args = array(
       'post_type' => 'attachment',
       'post_mime_type' => 'image',
       'numberposts' => -1,
       'post_status' => null,
       'post_parent' => $pid
      );
  
    $atts = get_posts($args);
	
	$icon_on_src = '';
	$icon_off_src = '';
	
	if ($atts) {
		foreach ($atts as $att) {
			$aid = $att->ID;
	        $agate  = trim(get_post_meta($aid,'_toggler_icon',true));

			$icon_on = $icon . '-show';
			$icon_off = $icon . '-hide';
			
			if ($agate == $icon_on) $icon_on_src = wp_get_attachment_image_src($aid,'full'); 
			if ($agate == $icon_off) $icon_off_src = wp_get_attachment_image_src($aid,'full');
		}
	}

	return array($icon_on_src[0],$icon_off_src[0]); 
}

//* Toggler : Shortcode adction */
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
 * 	"group = text"				- whether to group elements and what name to give that group
 * 
 */
function set_toggler($atts,$content=null) {
  extract(shortcode_atts(array(
      'role'		=> '',
      'connector'   => '',
      'ext'			=> '',
      'show'		=> 'no',
      'inline'		=> 'no',
      'ghost'		=> 'yes',
      'group'		=> '',
      'hover'		=> 'no',
      'icon'		=> 'none',
      'icon_size'	=> '18',
      'icon_top'	=> '',
      'icon_left'	=> '5',
      'icon_color'	=> 'white',
      'icon_background'	=> 'black',
      'icon_reset'	=> 'yes',
      'icon_position'=> 'normal'
    ), $atts));

  /* Variables */
  $html = '';

  $connector	= sanitize_title(trim($connector));
  $ext 			= trim($ext);
  $show 		= strtolower($show);
  $inline 		= strtolower($inline);
  $ghost 		= strtolower($ghost);
  $role			= strtolower($role);
  $group		= trim($group);
  $hover		= trim($hover);
  $icon 		= trim($icon);
  $icon_size	= trim($icon_size);
  $icon_top		= trim($icon_top);
  $icon_left	= trim($icon_left);
  $icon_color	= trim($icon_color);
  $icon_background	= trim($icon_background);
  $icon_position	= trim($icon_position);
  $icon_reset	= trim($icon_reset);
      
  if ($group != '') {
  	$group_class = ' toggler-group-' . $group;
	$group_class .= ' toggler-focus';
  }
  
  $tag = '0';
  
  if ($show == "yes") {
  	$icon_off_class = 'toggler-icon-show ';
  	$icon_on_class = 'toggler-icon-hide ';
  } else {
  	$icon_off_class = 'toggler-icon-hide ';
  	$icon_on_class = 'toggler-icon-show ';
  }
  
  $icon_border_radius = intval($icon_size) - 1;
  $icon_font_size = intval($icon_size) - 2;
  $icon_right = $icon_left;
  $icon_left = intval($icon_size) + $icon_left;
  
  
  $plus_styling = '';
  if ($icon_position == 'float') {
	  $plus_styling .= 'position: absolute; ';
	  $plus_styling .= 'margin-left: -' . $icon_left . 'px; ';
	  $plus_styling .= 'margin-top: ' . $icon_top . 'px; ';
  } else {
	  $plus_styling .= 'position: relative; ';
	  $plus_styling .= 'margin-right: ' . $icon_right . 'px; ';
	  $plus_styling .= 'top: ' . $icon_top . 'px; ';
  }
  $plus_styling .= 'text-align: center; ';
  $plus_styling .= 'border-radius: ' . $icon_border_radius . 'px; ';
  $plus_styling .= 'width: ' . intval($icon_size) . 'px; ';
  $plus_styling .= 'height: ' . intval($icon_size) . 'px; ';
  $plus_styling .= 'line-height: ' . $icon_font_size . 'px; ';
  $plus_styling .= 'font-size: ' . $icon_font_size . 'px; ';
  $plus_styling .= 'background: ' . $icon_background . '; ';
  $plus_styling .= 'color: ' . $icon_color . '; ';
  
  $icon_styling = '';
  if ($icon_position == 'float') {
	  $icon_styling .= 'position: absolute; ';
	  $icon_styling .= 'margin-left: -' . $icon_left . 'px; ';
	  $icon_styling .= 'margin-top: ' . $icon_top . 'px; ';
  } else {
	  $icon_styling .= 'position: relative; ';
	  $icon_styling .= 'margin-right: ' . $icon_right . 'px; ';
	  $icon_styling .= 'top: ' . $icon_top . 'px; ';
  }
  
  $icon_img_styling = '';
  if ($icon_reset == 'yes') 
	  $icon_img_styling .= 'border: 0; padding: 0; margin: 0; box-shadow: 0; ';
  $icon_img_styling .= 'width: ' . intval($icon_size) . 'px; ';
  	
  
  
  if ((in_array($role,array('switch','target'))) && ($content != '')) {
   	  $plus_html = '';

	  if ($icon == 'plus') {
	  	$plus_html = '<span class="toggler-icon-on ' . $icon_on_class . '" style="' . $plus_styling . '">+</span><span class="toggler-icon-off ' . $icon_off_class . '" style="' . $plus_styling . '">-</span>';
	  }
	  
	  if (($icon != 'plus') && ($icon != '')) {
	  	$icons = extract_toggler_icon($icon, get_the_ID());
		if (($icons[0] != '') && ($icons[1] != '')) {
		  	$plus_html = '<span class="toggler-icon-on ' . $icon_on_class . '" style="' . $icon_styling . '"><img class="toggler-icon-' . $icon . '" src="' . $icons[0] . '" style="' . $icon_img_styling . '" alt="" /></span>';
		  	$plus_html .= '<span class="toggler-icon-off ' . $icon_off_class . '" style="' . $icon_styling . '"><img class="toggler-icon-' . $icon . '" src="' . $icons[1] . '" style="' . $icon_img_styling . '" alt="" /></span>';
		}
	  }
	  
	  /* Show / Hide */
	  if ($show == 'yes') {
	  	$default_state = ($ghost == 'no' ? 'toggler-show-noghost' : 'toggler-show-ghost');
	  } else {
	  	$default_state = ($ghost == 'no' ? 'toggler-hide-noghost' : 'toggler-hide-ghost');
	  }
	
	  $display = ($inline == 'yes' ? ' toggler-inline' : ' toggle-block'); 
	  
	  $hover = ($hover == 'yes' ? ' toggler-hover' : ' toggler-click');
	
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
		$html .= '<script type="text/javascript">updateExtClasses("' . $role . '","' . $tag .'", "' . trim($ext) . '", "' . $default_state . '", "'. trim($link_connect) . '");';
		if ($group != '') 
			if ($ext != '') {
				$html .= 'updateGruopTarget("' . trim($link_connect) . '.' . trim($ext) . '","' . trim($group_class) . '");';
			} else {
				$html .= 'updateGruopTarget("' . trim($link_connect) . '","' . trim($group_class) . '");';
			}
		$html .= '</script>';
		$ext_class = '.'. trim($ext);
	  }

	  //$content = str_replace("\r\n", '', trim($content));
	  // Handling Wordpress closing peeing
	  if (substr($content,0,4) == '</p>') $content = substr($content,5);
	
	  /* Is it a Switch or a Target ?*/	
	  if ($role == 'switch') $html .= '<div class="toggler-link' . $hover . $display . $link_connect . $ext_class . $group_class . '">' . do_shortcode($plus_html . $content) . '</div>';
	  if ($role == 'target') $html .= '<div class="toggler-target' . $display . $link_connect . $group_class . '"><div class="'. $default_state .'">' . do_shortcode($content) . '</div></div>';
	  /* Go! */
	  return $html;
  } 
}

add_shortcode( 'toggler', 'set_toggler' );
?>