<?php

// Configuration
define('W3TC_LOCATION', 'wp-content/cache/page_enhanced/');
define('SUPERCACHE_LOCATION', 'wp-content/cache/supercache/');
$rawurl = $_POST["mpc-url"];

if (isset($_POST["mpc-url"])) {
?>
<div id="message" class="updated">
<?php
	mpc_purge_cache($rawurl);
?>
</div>
<?php
}
?>
<div class="wrap">
	<h2><?php echo __("Manual purge cache", "mpc") ?></h2>
<?php
if (file_exists(ABSPATH . W3TC_LOCATION)) {
		echo ("<p>You are using W3 Total Cache.</p>");
}
elseif (file_exists(ABSPATH . SUPERCACHE_LOCATION)) {
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
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Purge cached page" />
		</p>
	</form>
</div>
