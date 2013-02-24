<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

if (!@file_exists(CONFIG_DIR . 'config.php')) {
	define('DEFAULT_CONFIG_FILE', CONFIG_DIR . 'config.default.php');
	require_once DEFAULT_CONFIG_FILE;

	define('TEMPLATE_DIR', 'tpl/' . $options['template_used'] . '/');
	define('IMAGE_DIR', TEMPLATE_DIR . 'skin/' . $options["csstype"] . '/');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Rx08 &rsaquo; Rapidleech - Installation</title>
<link rel="stylesheet" href="<?php echo CONFIG_DIR; ?>css/setup.css" type="text/css" />
</head>
<body class="install-body">
<div class="container">
<div class="head_container">
<h1 id="logo"><img alt="Rapidleech" src="<?php echo IMAGE_DIR; ?>rl_lgo.png" /></h1>
</div>
<?php
	if (isset($_POST["step"]) && $_POST["step"] == "1") {

		echo ":: POST; Niatna mo ada form utk user customize default primary config. lagi dibuat... :p";
		echo '<br /><br />';
		echo '<p>Saving config file ...</p>';
		$target_conf_file = CONFIG_DIR . 'config.php';
		$ret_copy = copy(DEFAULT_CONFIG_FILE, $target_conf_file);
		if ($ret_copy) @chmod($target_conf_file, 0666);
		sleep(1);

		if ($ret_copy) {
			echo '<p>Config file saved.';
			echo '<center><input type="submit" onclick="location.href=\'' . $options['index_file'] . '\';" value="Let&#8217;s rock n&#8217; roll!" class="button"/></center></p>';
		} else {
			echo '<p>Error while saving config file.</p>';
		}
	} else {
?>
<p>Welcome Leechers. It seems <code>config.php</code> file doesn't exist or it is the first time for this page to be launched.<br />
	Before getting started, you should review some information on your server.</p>
<div class="view_checker">
<div class="m">
<fieldset>
<?php
			$checker = array();

			function retchk($flag, $msg='') {
				if (!isset($flag)) return;
				return
					'<span class="' . ($flag == 1 ? 'okay' : 'fail') . '">' .
					($flag == 1 ? 'Passed' : ($msg != '' ? $msg : 'Failed') ) .
					'</span>';
			}

			$apacver = phpversion();
			$checker["phpver"] = str_replace(".", "", $apacver);
			$checker["phpver"] = ($checker["phpver"] >= 510 ? retchk(1) : retchk(0));
			$checker["safemode"] = (ini_get('safe_mode') ? retchk(0) : retchk(1));
			$checker["stream_socket_client"] = (!function_exists('stream_socket_client') ? retchk(0) : retchk(1));
			$checker["memory_limit"] = ((int) ini_get('memory_limit') > 32 ? retchk(1) : retchk(0));
			$checker["openssl"] = (!extension_loaded('open_ssl') ? retchk(0, 'Some rapidleech plugin which need https support will not run properly without this extension enabled') : retchk(1));
			$checker["curl"] = (!extension_loaded('curl') || !function_exists('curl_init') || !function_exists('curl_exec') ? retchk(0) : retchk(1));
			$checker["fopen"] = (!ini_get('allow_url_fopen') ? retchk(0) : retchk(1));
			$checker["call_time"] = (!ini_get('allow_call_time_pass_reference') ? retchk(0, 'You might see warnings without this turned on') : retchk(1));
			$checker["passthru"] = (!function_exists('passthru') ? retchk(0, 'You might not be able to turn on server stats') : retchk(1));
			$checker["disk_free_space"] = (!function_exists('disk_free_space') ? retchk(0, 'You might not be able to turn on server stats') : retchk(1));

			if (function_exists('apache_get_version')) {
				$apache_version = apache_get_version();
				preg_match('/Apache\/([0-9])\./U', $apache_version, $match);
				$checker["apacver"] = ($match[1] < 2 ? retchk(0, 'Your server might not run rapidleech script properly, please update your php version') : retchk(1) );
			}
?>
<table border="0" width="90%" cellpadding="0" align="center">
	<tr>
		<td>
			<form action="" method="get">
				<table border="0" cellpadding="0" cellspacing="0">
					<tr>
						<td align="center">
							<div class="chk_content">
								<b>Rapidleech pre-Setup Check</b>
							</div>
						</td>
					</tr>
					<tr>
						<td align="left" class="chk_content">
							<ol>
								<li><b>stream socket client</b>: <?php echo $checker["stream_socket_client"]; ?></li>
								<li><b>memory_limit</b>: <?php echo $checker["memory_limit"]; ?></li>
								<li><b>safe_mode</b>: <?php echo $checker["safemode"]; ?></li>
								<li><b>cURL</b>: <?php echo $checker["curl"]; ?></li>
								<li><b>SSL Connection Support</b>: <?php echo $checker["openssl"]; ?></li>
								<li><b>allow_url_fopen</b>: <?php echo $checker["fopen"]; ?></li>
								<li><b>PHP Version - <?php echo $apacver; ?></b>: <?php echo $checker["phpver"]; ?></li>
								<li><b>allow_call_time_pass_reference</b>: <?php echo $checker["call_time"]; ?></li>
								<li><b>passthru</b>: <?php echo $checker["passthru"]; ?></li>
								<li><b>Disk Space Functions</b>: <?php echo $checker["disk_free_space"]; ?></li>

								<?php if ($apache_version) { ?><li><b>Apache Version - <?php echo $apache_version; ?></b>: <?php echo $checker["apacver"]; ?></li><?php } ?>
							</ol>
						</td>
					</tr>
				</table>
				<input type="hidden" name="refresh" value="1" />
				<input type="submit" value="Refresh" class="button"/>
			</form>
		</td>
	</tr>
</table>
</fieldset>
</div>
</div>
<p>If for any reason this Installation doesn't work, You can delete <code>config.php</code> to see this page again. You may also simply open <code>config.default.php</code> in a text editor, set it up with your need, and save it as <code>config.php</code>.</p>
<div style="text-align:center">
<form action="<?php echo $options["index_file"]; ?>" method="post">
<input type="hidden" name="step" value="1" />
<input type="submit" value="Let&#8217;s go!" class="button"/>
</form>
</div>
<?php
	}
?>
</div>
</body>
</html>
<?php
	exit();
} else {
	// Load configuration
	require_once(CONFIG_DIR . 'config.php');
	return;
}
?>