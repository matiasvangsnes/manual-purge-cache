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

function mpc_purge_cache($rawurl) {
  $wpurl = site_url();
  $parsedwpurl = parse_url($wpurl);
  if (strpos($rawurl, "http") === 0) {
    $parsedrawurl = parse_url($rawurl);
    $parsedwpurl = parse_url($wpurl);
    if ($parsedwpurl[host] == $parsedrawurl[host]) {
      $purgepath = ABSPATH . W3TC_LOCATION . $parsedwpurl[host] . $parsedrawurl[path] . "/";

      if (file_exists($purgepath."_index.html") || file_exists($purgepath."_index.html_gzip")) {
        if (file_exists($purgepath."_index.html")) {
          if (!unlink($purgepath."_index.html")) {
            echo ("<p><strong>Fail!</strong> Error deleting _index.html.");
          } else {
            echo ("<p><strong>Hepp!</strong> The cached file _index.html is deleted.</p>");
          }
        }

        if (file_exists($purgepath."_index.html_gzip")) {
          if (!unlink($purgepath."_index.html_gzip")) {
            echo ("<p><strong>Fail!</strong> Error deleting _index.html_gzip.</p>");
          } else {
          echo ("<p><strong>Hepp!</strong> The cached file _index.html_gzip is deleted.");
          }
        }
      } else {
        if (file_exists($purgepath."_index.html.old")) {
          if (!unlink($purgepath."_index.html.old")) {
            echo ("<p><strong>Fail!</strong> Error deleting _index.html.old.</p>");
          } else {
            echo ("<p><strong>Hepp!</strong> The cached file _index.html.old is deleted.</p>");
          }
        }

        if (file_exists($purgepath."_index.html_gzip.old")) {
          if (!unlink($purgepath."_index.html_gzip.old")) {
            echo ("<p><strong>Fail!</strong> Error deleting _index.html_gzip.old.</p>");
          } else {
            echo ("<p><strong>Hepp!</strong> The cached file _index.html_gzip.old is deleted.</p>");
          }
        } else {
          echo "<p><strong>Fail!</strong> There is no cached files in the folder.</p>";
        }
      }
    } else {
      echo "<p><strong>Fail!</strong> The posted URL domain is not matching your WordPress installation domain.</p>";
    }
  } else {
    echo "<p><strong>Fail!</strong> You must enter a correct URL which begin with http or https (example: http://www.example.com/folder/?id=232323)</p>";
  }
}
?>
