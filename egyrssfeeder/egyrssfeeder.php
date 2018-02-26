<?php
/*
Plugin Name:  egyrssfeeder
Plugin URI:   https://github.com/ghost5egy
Description:  egyrssfeeder is a free wordpress pugin to add posts to your website other sources rss link 
Version:      1
Author:       Ghost5egy
Author URI:   https://github.com/ghost5egy
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
egyrssfeeder is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
egyrssfeeder is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with egyrssfeeder. If not, see {License URI}.
*/

// will run in the installion of plugin
function egyrssfeeder_install(){
/**		register_post_type( 'egyrssfeeder', array(
            'labels' => array(
                'name' => __( 'News Feed', 'egyrssfeeder' ),
                'singular_name' => __( 'News Feed', 'egyrssfeeder_init' ) ),
            'rewrite' => false,
            'query_var' => false )
        );
**/
		$egyrssfeeder_options['count'] = 0;
		savedata($egyrssfeeder_options);
		if (! wp_next_scheduled ( 'my_10_event' )) {
			wp_schedule_event(time(), 'every_10_minutes', 'my_10_event');
		}
}


// will run in the uninstallion of plugin
function egyrssfeeder_uninstall(){
//		delete_option('egyrssfeeder_options');
//		unregister_post_type( 'egyrssfeeder' );		
		wp_clear_scheduled_hook('my_10_event');
}

//creates menu in the admin area 
function egyrssfeeder_menu(){
		$page_title = 'Egy Rss Feeder';
		$menu_title = 'Egy Rss Feeder';
		$capability = 'manage_options';
		$menu_slug  = 'egyrssfeeder-info';
		$function   = 'egyrssfeeder_init';
		$icon_url   = 'dashicons-rss';
		$position   = 4;
		add_menu_page( $page_title,
						$menu_title, 
						$capability, 
						$menu_slug, 
						$function, 
						$icon_url, 
						$position );
		add_submenu_page('egyrssfeeder-info','All Feeds',  'All Feeds', 'manage_options', 'egyrssfeeder_manage' , 'egyrssfeeder_menucreate'  );
		add_submenu_page('egyrssfeeder-info','Add Feed', 'Add Feed', 'manage_options', 'egyrssfeeder_feed', 'egyrssfeeder_feed' );
		remove_submenu_page('egyrssfeeder-info','egyrssfeeder-info');
}

function getdata(){
	return get_option('egyrssfeeder_options');
}

function savedata($egyrssfeeder_options){
	update_option('egyrssfeeder_options', $egyrssfeeder_options);
}

// main menu page caller 
function egyrssfeeder_menucreate(){
        include (plugin_dir_path( __FILE__ ).'admin/manage.php');
}

// add feed page caller 
function egyrssfeeder_feed(){
        include (plugin_dir_path( __FILE__ ).'admin/feeds.php');
}

function do_this_10(){
	$egyrssfeederss = getdata();
	foreach($egyrssfeederss as $k => $rsssources){
		if($k == 'count'){
			continue;
		}else{
			if($rsssources['autoupdate'] == "1"){
				$egyrssfeederss[$k]['title'] = 'this is cron job';
				echo " ".$k." ";
			}else{
				continue;
			}
		}
	}
}

register_activation_hook( __FILE__, 'egyrssfeeder_install' );
register_deactivation_hook(__FILE__, 'egyrssfeeder_uninstall');
add_action('my_10_event', 'do_this_10'); //remove comment from install function and add 'do_this_10()' function 
add_action( 'admin_menu', 'egyrssfeeder_menu' );

?>