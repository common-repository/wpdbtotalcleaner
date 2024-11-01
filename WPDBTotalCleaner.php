<?php
/*
Plugin Name: WP-DB-TotalCleaner
Plugin URI: http://www.bgextensions.bgvhod.com/wp-db-totalcleaner/
Author: BgExtensions
Author URI: http://www.bgextensions.bgvhod.com/
Description: WPDBTotalCleaner is a very simple, multisite compatible tool to clean Wordpress Database....
Version: 1.1
*/
// Define Plugin URL For Convenience
define('WPDBTotalCleanerURL',plugins_url('/',__FILE__));

// Hook Plugin Admin Menu
if ( is_multisite() ) {
add_action('network_admin_menu','WPDBTotalCleanerMenu');} else
{
add_action('admin_menu','WPDBTotalCleanerMenu');
}

// Plugin Admin Menu Function
function WPDBTotalCleanerMenu(){
	$mainpage = add_menu_page(__('WPDBTCleaner'), __('WPDBTCleaner'), 'create_users', 'WPDBTotalCleanerMenu', 'WPDBTotalCleanerAdmin', WPDBTotalCleanerURL.'WPDBTotalCleaner.png');
	add_submenu_page('WPDBTotalCleanerAdmin', __('WPDBTCleaner'), __('WPDBTCleaner'), 'create_users', 'WPDBTotalCleanerMenu', 'WPDBTotalCleanerAdmin');

	// Load CSS Only In The Plugin Page Itself
	add_action('admin_print_styles-' . $mainpage, 'WPDBTotalCleanerCSS' );

	// Function To Enqueue CSS File
	function WPDBTotalCleanerCSS(){
		wp_enqueue_style( 'WPDBTotalCleanerCSS',WPDBTotalCleanerURL.'WPDBTotalCleanerCSS.css' );
	}
}

// WPDBTotalCleaner Admin Page Stuffs
function WPDBTotalCleanerAdmin(){
	include "WPDBTotalCleanerAdmin.php";
}
?>