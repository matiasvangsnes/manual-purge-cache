<?php
/*
Plugin Name: Manual Purge Cache
Plugin URI: http://www.125.cc
Description: Plugin for purging selected cached pages on disk generated with cache plugin or proxy server.
Author: Matias Vangsnes
Version: 0.1
Author URI: http://www.125.cc
*/
function mpc_admin() {
    include('manual-purge-cache.php');
}
function mpc_admin_actions() {
    add_options_page("Manual purge cache", "Manual purge cache", 1, "Manual_purge_cache", "mpc_admin");
}
add_action('admin_menu', 'mpc_admin_actions');
?>
