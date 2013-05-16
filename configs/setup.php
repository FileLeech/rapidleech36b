<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

if (!@file_exists(CONFIG_DIR . 'config.php')) {
	define('DEFAULT_CONFIG_FILE', CONFIG_DIR . 'config.default.php');
	require_once DEFAULT_CONFIG_FILE;
	require_once(CLASS_DIR . 'other.php');

	define('TEMPLATE_DIR', 'tpl/' . $default_options['template_used'] . '/');
	define('IMAGE_DIR', TEMPLATE_DIR . 'skin/' . $default_options["csstype"] . '/');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Rx08 &rsaquo; Rapidleech - Installation</title>
<link rel="stylesheet" href="<?php echo CONFIG_DIR; ?>css/setup.css" type="text/css" />
<?php
if (!isset($_POST['step']) && !isset($_POST['submit'])) {
  function js_special_chars($t) {
    return str_replace(array('\\', "'", '"', '&', "\n", "\r", "\t", chr(8), chr(12)), array("\\", "\\'", "\\\"", "\&", '\\n', "\\r", "\\t", "\\b", "\\f"), $t);
  }
?>
<script type="text/javascript">
/* <![CDATA[ */
var d = document;

function load_default() {
<?php
foreach ($default_options as $k => $v) {
	if (in_array($k, array('disableadvanceeditor', 'xpanel_filename', 'index_file', 'allowcpanel', 'loginCp'))) {
		if (is_array($default_options[$k])) {
			foreach ($default_options[$k] as $ucp => $pcp) {
				$ucp = js_special_chars($ucp); $pcp = js_special_chars($pcp);
				echo "\td.getElementById('usercp').value ='{$ucp}';\r\n";
				echo "\td.getElementById('passcp').value ='{$pcp}';\r\n";
			}
		} elseif(is_bool($default_options[$k])) {
			echo "\td.getElementById('{$k}').".($v ? "setAttribute('checked','checked')" : "removeAttribute('checked')").";\r\n";
		} else {
			$v = js_special_chars($v);
			echo "\td.getElementById('{$k}').value = '{$v}';\r\n";
		}
	}
}
?>
	if (d.getElementById('allowcpanel').checked) {
		d.getElementById('loginCp_form').style.display = 'block';
	} else {
		d.getElementById('loginCp_form').style.display = 'none';
	}
}
d.addEventListener('DOMContentLoaded',function(){
	d.getElementById('continue').removeAttribute('disabled');
	d.getElementById('continue').onclick = function() { d.setup_form.submit();}
	d.getElementById('allowcpanel').onclick = function() {
		var displ = this.checked ? 'block':'none';
		d.getElementById('loginCp_form').style.display = displ;
	}

	load_default();
});
/* ]]> */
</script>
<?php
}
?>
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

		$options = array();
		foreach ($default_options as $k => $v) {
			if (!array_key_exists($k, $options)) {
				$options[$k] = $v;
			}
			if (in_array($k, array('disableadvanceeditor', 'xpanel_filename', 'index_file', 'allowcpanel'))) {
				if (is_bool($default_options[$k])) {
					$options[$k] = (isset($_POST[$k]) && $_POST[$k] ? true : false);
				} else {
					$options[$k] = (isset($_POST[$k]) && $_POST[$k] ? stripslashes($_POST[$k]) : '');
				}
			} else {
				$options[$k] = $v;
			}
		}
		$options['loginCp'] = array();
		if (isset($_POST['usercp']) && isset($_POST['passcp']) && count($_POST['usercp']) > 0 && count($_POST['usercp']) == count($_POST['passcp'])) {
			foreach ($_POST['usercp'] as $k => $u) {
				$u = stripslashes($u); $p = stripslashes($_POST['passcp'][$k]);
				if ($u == '' || $p == '') { $u = $p = 'admin'; }
				$options['loginCp'][$u] = $p;
			}
		}
		ob_start(); var_export($options); $opt = ob_get_contents(); ob_end_clean();
		$opt = (strpos($opt, "\r\n") === false ? str_replace(array("\r", "\n"), "\r\n", $opt) : $opt);
		$opt = "<?php\r\n if (!defined('RAPIDLEECH')) {\r\n\trequire_once('index.html');\r\n\texit;\r\n}\r\n\r\n\$options = " .
				$opt . "; \r\n\r\nrequire_once('accounts.php');\r\n\r\n" .
				"# DEFINE writable dir OR files\r\ndefine('LOG_DIR', CONFIG_DIR . 'logfile/');\r\ndefine('FILES_LST', LOG_DIR . 'Rx08_2208081410_f1L3Z.lst');\r\n" .
				"define('IP_L33CH_L0G', LOG_DIR . 'Rx08_2208081410_IP_L33CH_L0G.lst');\r\ndefine('VISITOR_LST', LOG_DIR . 'Rx08_2208081410_v1zit0r.lst');\r\n" .
				"define('TRAFFIC_LST', LOG_DIR . 'Rx08_2208081410_tR4fic.txt');\r\ndefine('LOG_PHP', LOG_DIR . 'Rx08_2208081410_d4Mn.log.php');\r\n" .
				"define('LASTLOG_LST', LOG_DIR . 'Rx08_2208081410_d4Mn_Last.txt');\r\ndefine('MYUPLOAD_LST', LOG_DIR . 'myuploads.txt');\r\n?>";
		if (!@write_file(CONFIG_DIR . "config.php", $opt, 1)) {
			echo '<p>It was not possible to write the configuration<br />Set permissions of "configs" folder to 0777 and try again</p>';
		}
		if ($opt) @chmod(CONFIG_DIR . "config.php", 0666);
		sleep(1);

		if ($opt) {
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
			$checker["openssl"] = (!extension_loaded('openssl') ? retchk(0, 'Some rapidleech plugin which need https support will not run properly without this extension enabled') : retchk(1));
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
<form action="<?php echo $default_options["index_file"];?>" name='setup_form' method="post">
<table style="width: 100%;" column="2">
<tr><td style="text-align: left;"><input type="checkbox" id="disableadvanceeditor" name="disableadvanceeditor" value="1" />&nbsp;Disable Advance Editor</td><td><b>False</b> for security reason, so you can set xpanel access manualy</td></tr>
<tr><td style="text-align: left;"><div style="padding-left: 5px;"><input type="text" id="xpanel_filename" name="xpanel_filename" size="10" value="" />&nbsp;Xpanel Filename</div></td><td>You need to allow this file in your htaccess if needed</td></tr>
<tr><td style="text-align: left;"><div style="padding-left: 5px;"><input type="text" id="index_file" name="index_file" size="10" value="" />&nbsp;Index Filename</div></td><td>Set index filename, incase you have a different name instead of index.php</td></tr>
<tr><td style="text-align: left;"><input type="checkbox" id="allowcpanel" name="allowcpanel" value="1" />&nbsp;Allow Cpanel</td><td><b>WARNING</b> set this to FALSE will Disable access to xpanel.</td></tr>
<table style="text-align: left; width: 300px;" id="loginCp_form">
<tr><td>Admin Name</td><td><input type="text" id="usercp" name="usercp[]" size="25" value="" /></td></tr>
<tr><td>Admin Pass</td><td><input type="password" id="passcp" name="passcp[]" size="25" value="" /></td></tr>
</table>
</table>
<tr><td>
<div style ="text-align: center;">
<input type="hidden" name="step" value="1" />
<input type="submit" id="continue" value="Let&#8217;s go!" class="button" disabled="disabled" />
</div>
</td></tr>
</form>
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