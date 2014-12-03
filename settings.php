<?php
/*
Plugin Name: Manual Purge Cache
Plugin URI: http://www.125.cc
Description: Plugin for purging selected cached pages on disk generated with cache plugin or proxy server.
Author: Matias Vangsnes
Version: 0.1
Author URI: http://www.125.cc
*/

// Configuration
define('W3TC_LOCATION', 'wp-content/cache/page_enhanced/');
define('SUPERCACHE_LOCATION', 'wp-content/cache/supercache/');

function mpc_front_end() {
  if ( ! empty($_GET['mpc-clear-url'])) {
    if (is_user_logged_in()) {
      echo '<div class="mpc-frontend-wrapper">';
      $response = mpc_purge_cache($_GET['mpc-clear-url']);
      error_log('ok');
      error_log(print_r($response,1));
      if ( ! empty($response)) {
        error_log('response: '.$response);
        echo '<script type="text/javascript">alert("'.strip_tags(str_replace('</p>', '\n', $response)).'");</script>';
      }
      echo '</div>';
    }
  }
}
add_action('init', 'mpc_front_end');
function mpc_admin() {
  include('manual-purge-cache.php');
}
function mpc_admin_actions() {
  add_options_page("Manual purge cache", "Manual purge cache", 1, "Manual_purge_cache", "mpc_admin");
}
add_action('admin_menu', 'mpc_admin_actions');

function custom_button_example($wp_admin_bar){
  $path = $_SERVER['REQUEST_URI'];
  $uri = get_site_url().$path;

  $args = array(
    'id'     => 'mpc-clear-cache',
    'title'  => 'Clear current page cache',
    'href'   => $uri . '?mpc-clear-url=' . $uri,
    'meta'   => array('class' => 'mpc-clear-cache')
  );
  $wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'custom_button_example', 50);

function mpc_purge_cache($rawurl) {
  $wpurl = site_url();
  $parsedwpurl = parse_url($wpurl);
  $output = '';

  if (strpos($rawurl, "http") === 0) {
    $parsedrawurl = parse_url($rawurl);
    $parsedwpurl = parse_url($wpurl);
    if ($parsedwpurl[host] == $parsedrawurl[host]) {
      $purgepath = ABSPATH . W3TC_LOCATION . $parsedwpurl[host] . $parsedrawurl[path] . "/";
      if (file_exists($purgepath."_index.html") || file_exists($purgepath."_index.html_gzip")) {
        if (file_exists($purgepath."_index.html")) {
          if (!unlink($purgepath."_index.html")) {
            $output.= "<p><strong>Fail!</strong> Error deleting _index.html.";
          } else {
            $output.= "<p><strong>Hepp!</strong> The cached file _index.html is deleted.</p>";
          }
        }

        if (file_exists($purgepath."_index.html_gzip")) {
          if (!unlink($purgepath."_index.html_gzip")) {
            $output.= "<p><strong>Fail!</strong> Error deleting _index.html_gzip.</p>";
          } else {
            $output.= "<p><strong>Hepp!</strong> The cached file _index.html_gzip is deleted.";
          }
        }
      } else {
        if (file_exists($purgepath."_index.html.old")) {
          if (!unlink($purgepath."_index.html.old")) {
            $output.= "<p><strong>Fail!</strong> Error deleting _index.html.old.</p>";
          } else {
            $output.= "<p><strong>Hepp!</strong> The cached file _index.html.old is deleted.</p>";
          }
        }

        if (file_exists($purgepath."_index.html_gzip.old")) {
          if (!unlink($purgepath."_index.html_gzip.old")) {
            $output.= ("<p><strong>Fail!</strong> Error deleting _index.html_gzip.old.</p>");
          } else {
            $output.= ("<p><strong>Hepp!</strong> The cached file _index.html_gzip.old is deleted.</p>");
          }
        } else {
          $output.= "<p><strong>Fail!</strong> There is no cached files in the folder.</p>";
        }
      }
    } else {
      $output.= "<p><strong>Fail!</strong> The posted URL domain is not matching your WordPress installation domain.</p>";
    }
  } else {
    $output.= "<p><strong>Fail!</strong> You must enter a correct URL which begin with http or https (example: http://www.example.com/folder/?id=232323)</p>";
  }
  return $output;
}
?>
