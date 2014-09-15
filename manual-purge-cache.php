<?php

// Configuration
$w3tc_location = "wp-content/cache/page_enhanced/";
$supercache_location = "wp-content/cache/supercache/";
$rawurl = $_POST["mpc-url"];
$wpurl = site_url();
$parsedwpurl = parse_url($wpurl);

if (isset($_POST["mpc-url"])) {
?>
<div id="message" class="updated">
<?php
	if (strpos($rawurl, "http") === 0) {
		$parsedrawurl = parse_url($rawurl);
		$parsedwpurl = parse_url($wpurl);
		if ($parsedwpurl[host] == $parsedrawurl[host]) {
			$purgepath = ABSPATH . $w3tc_location . $parsedwpurl[host] . $parsedrawurl[path] . "/";
			if (file_exists($purgepath."_index.html")) {
				if (!unlink($purgepath."_index.html")) {
					echo ("<p><strong>Fail!</strong> Error deleting _index.html.");
				}
				else {
					echo ("<p><strong>Hepp!</strong> The cached file _index.html is deleted.</p>");
				}
			}
			elseif (file_exists($purgepath."_index.html_gzip")) {
      	if (!unlink($purgepath."_index.html_gzip")) {
        	echo ("<p><strong>Fail!</strong> Error deleting _index.html_gzip.</p>");
      	}
      	else {
      	echo ("<p><strong>Hepp!</strong> The cached file _index.html_gzip is deleted.");
      	}
			}
      elseif (file_exists($purgepath."_index.html.old")) {
      	if (!unlink($purgepath."_index.html.old")) {
        	echo ("<p><strong>Fail!</strong> Error deleting _index.html.old.</p>");
        }
        else {
        	echo ("<p><strong>Hepp!</strong> The cached file _index.html.old is deleted.</p>");
        }
			}
      elseif (file_exists($purgepath."_index.html_gzip.old")) {
      	if (!unlink($purgepath."_index.html_gzip.old")) {
        	echo ("<p><strong>Fail!</strong> Error deleting _index.html_gzip.old.</p>");
        }
        else {
        	echo ("<p><strong>Hepp!</strong> The cached file _index.html_gzip.old is deleted.</p>");
        }
      }
      else {
      	echo "<p><strong>Fail!</strong> There is no cached files in the folder.</p>";
      }
		}
		else {
			echo "<p><strong>Fail!</strong> The posted URL domain is not matching your WordPress installation domain.</p>";
		}
	}
	else {
		echo "<p><strong>Fail!</strong> You must enter a correct URL which begin with http or https (example: http://www.example.com/folder/?id=232323)</p>";
	}
?>
</div>
<?php
}
?>
<div class="wrap">
	<h2><?php echo __("Manual purge cache", "mpc") ?></h2>
<?php
if (file_exists(ABSPATH . $w3tc_location)) {
		echo ("<p>You are using W3 Total Cache.</p>");
}
elseif (file_exists(ABSPATH . $supercache_location)) {
		echo ("<p>You are using WP Super Cache.</p>");
}
else {
		echo ("<p>You seem to not have either W3 Total Cache or Super Cache installed</p>");
}
?>
	<form method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label for="mpc-url">URL to purge</label>
				</th>
				<td>
				<input name="mpc-url" type="text" id="mpc-url" class="regular-text ltr" />
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Purge cached page"  />
		</p>
	</form>
</div>
