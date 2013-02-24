<?php
######## Account Info ########
$upload_acc['depositfiles_com']['user'] = ''; //Set your login
$upload_acc['depositfiles_com']['pass'] = ''; //Set your password
########################

$_GET['proxy'] = isset($_GET['proxy']) ? $_GET['proxy'] : '';
$not_done = true;
$continue_up = false;

if ($upload_acc['depositfiles_com']['user'] && $upload_acc['depositfiles_com']['pass']) {
	$default_acc = true;
	$_REQUEST['up_login'] = $upload_acc['depositfiles_com']['user'];
	$_REQUEST['up_pass'] = $upload_acc['depositfiles_com']['pass'];
	$_REQUEST['action'] = 'FORM';
	echo "<b><center>Using Default Login.</center></b>\n";
} else $default_acc = false;

if (!empty($_REQUEST['action']) && $_REQUEST['action'] == 'FORM') $continue_up = true;
else {
	echo "<table border='0' style='width:270px;' cellspacing='0' align='center'>
	<form method='POST'>
	<input type='hidden' name='action' value='FORM' />
	<tr><td style='white-space:nowrap;'>&nbsp;Login*</td><td>&nbsp;<input type='text' name='up_login' value='' style='width:160px;' /></td></tr>
	<tr><td style='white-space:nowrap;'>&nbsp;Password*</td><td>&nbsp;<input type='password' name='up_pass' value='' style='width:160px;' /></td></tr>\n";
	echo "<tr><td colspan='2' align='center'><br /><input type='submit' value='Upload' /></td></tr>\n";
	echo "<tr><td colspan='2' align='center'><small>*You can set it as default in <b>".basename(__FILE__)."</b></small></td></tr>\n";
	echo "</table>\n</form>\n";
}

if ($continue_up) {
	$login = $not_done = false;
	$cookie = array('lang_current' => 'en');
	$domain = CheckDomain('depositfiles.com');
	$referer = "http://$domain/";

	// Login
	echo "<table style='width:600px;margin:auto;'>\n<tr><td align='center'>\n<div id='login' width='100%' align='center'>Login to $domain</div>\n";

	if (!empty($_REQUEST['up_login']) && !empty($_REQUEST['up_pass'])) {
		if (!$default_acc && !empty($_POST['up_encrypted']) && $_POST['up_encrypted'] == 'true') {
			$_REQUEST['up_login'] = decrypt(urldecode($_REQUEST['up_login']));
			$_REQUEST['up_pass'] = decrypt(urldecode($_REQUEST['up_pass']));
		}
		SkipLoginC(strtolower($_REQUEST['up_login']), $_REQUEST['up_pass']);
		$page = geturl($domain, 80, '/', $referer, $cookie, 0, 0, $_GET['proxy'], $pauth);is_page($page);
		$login = true;
	} else echo "<b><center>Login not found or empty, using non member upload.</center></b>\n";

	// Retrive upload ID
	echo "<script type='text/javascript'>document.getElementById('login').style.display='none';</script>\n<div id='info' width='100%' align='center'>Retrive upload ID</div>\n";

	if (!$login) {
		$page = geturl($domain, 80, '/', $referer, $cookie, 0, 0, $_GET['proxy'], $pauth);is_page($page);
		$cookie = GetCookiesArr($page, $cookie);
	}

	if (!preg_match('@https?://fileshare\d+\.(?:depositfiles|dfiles)\.[^/:\r\n\t\"\'<>]+(?:\:\d+)?/[\w\-]+/[^\?\'"\r\n\<>;\s\t]*@i', $page, $up)) html_error('Error: Cannot find upload server.', 0);

	$post = array();
	$post['MAX_FILE_SIZE'] = cut_str($page, 'name="MAX_FILE_SIZE" value="', '"');
	$post['UPLOAD_IDENTIFIER'] = generate_upload_id();
	$post['go'] = cut_str($page, 'name="go" value="', '"');
	$post['agree'] = '1';

	$up_url = $up[0].'?X-Progress-ID='.$post['UPLOAD_IDENTIFIER'];

	// Uploading
	echo "<script type='text/javascript'>document.getElementById('info').style.display='none';</script>\n";

	$url = parse_url($up_url);
	$upfiles = upfile($url['host'], defport($url), $url['path'].(!empty($url['query']) ? '?'.$url['query'] : ''), $referer, $cookie, $post, $lfile, $lname, 'files', '', $_GET['proxy'], $pauth);

	// Upload Finished
	echo "<script type='text/javascript'>document.getElementById('progressblock').style.display='none';</script>\n";

	is_page($upfiles);

	if (preg_match('@https?://(?:[^/\'"\r\n\s\t<>;]\.)?(?:depositfiles|dfiles)\.[^/\r\n\t\"\'<>]+/files/[^\'"\r\n\s\t<>;]+@i', $upfiles, $dl)) {
		$download_link = $dl[0];
		if (preg_match('@https?://(?:[^/\'"\r\n\s\t<>;]\.)?(?:depositfiles|dfiles)\.[^/\r\n\t\"\'<>]+/rmv/[^\'"\r\n\s\t<>;]+@i', $upfiles, $del)) $delete_link = $del[0];
	} else html_error('Download link not found.', 0);
}

function CheckDomain($domain) {
	global $cookie, $pauth;
	$domain = strtolower($domain);
	$page = geturl($domain, 80, '/', "http://$domain/", $cookie, 0, 0, $_GET['proxy'], $pauth);is_page($page);
	if (($hpos = strpos($page, "\r\n\r\n")) > 0) $page = substr($page, 0, $hpos);
	if (stripos($page, "\nLocation: ") !== false && preg_match('@\nLocation: https?://(?:[^/\r\n]+\.)?((?:depositfiles|dfiles)\.[^/:\r\n\t\"\'<>]+)(?:\:\d+)?/@i', $page, $redir_domain)) {
		$redir_domain = strtolower($redir_domain[1]);
		if ($domain != $redir_domain) $domain = $redir_domain;
	}
	return $domain;
}

function generate_upload_id() {
	$chars = str_split('1234567890qwertyuiopasdfghjklzxcvbnm');
	$uid = time();
	for ($i=0;$i<32;$i++) $uid .= $chars[array_rand($chars)];
	return $uid;
}

// Edited For upload.php usage.
function Show_reCaptcha($pid, $inputs, $sname = 'Upload File') { 
	if (!is_array($inputs)) html_error('Error parsing captcha data.');

	// Themes: 'red', 'white', 'blackglass', 'clean'
	echo "<script language='JavaScript'>var RecaptchaOptions = {theme:'red', lang:'en'};</script>\n\n<center><form name='recaptcha' method='POST'><br />\n";
	foreach ($inputs as $name => $input) echo "<input type='hidden' name='$name' id='$name' value='$input' />\n";
	echo "<script type='text/javascript' src='http://www.google.com/recaptcha/api/challenge?k=$pid'></script><noscript><iframe src='http://www.google.com/recaptcha/api/noscript?k=$pid' height='300' width='500' frameborder='0'></iframe><br /><textarea name='recaptcha_challenge_field' rows='3' cols='40'></textarea><input type='hidden' name='recaptcha_response_field' value='manual_challenge' /></noscript><br /><input type='submit' name='submit' onclick='javascript:return checkc();' value='$sname' />\n<script type='text/javascript'>/*<![CDATA[*/\nfunction checkc(){\nvar capt=document.getElementById('recaptcha_response_field');\nif (capt.value == '') { window.alert('You didn\'t enter the image verification code.'); return false; }\nelse { return true; }\n}\n/*]]>*/</script>\n</form></center>\n</body>\n</html>";
	exit;
}

function Get_Reply($page) {
	if (!function_exists('json_decode')) html_error('Error: Please enable JSON in php.');
	$json = substr($page, strpos($page, "\r\n\r\n") + 4);
	$json = substr($json, strpos($json, '{'));$json = substr($json, 0, strrpos($json, '}') + 1);
	$rply = json_decode($json, true);
	if (!$rply || count($rply) == 0) html_error('Error reading json.');
	return $rply;
}

function Login($user, $pass) {
	global $default_acc, $cookie, $domain, $referer, $pauth;
	$errors = array('CaptchaInvalid' => 'Wrong CAPTCHA entered.', 'InvalidLogIn' => 'Invalid Login/Pass.', 'CaptchaRequired' => 'Captcha Required.');
	if (!empty($_POST['step']) && $_POST['step'] == '1') {
		if (empty($_POST['recaptcha_response_field'])) html_error('You didn\'t enter the image verification code.');
		$post = array('recaptcha_challenge_field' => $_POST['recaptcha_challenge_field'], 'recaptcha_response_field' => $_POST['recaptcha_response_field']);
		$post['login'] = urlencode($user);
		$post['password'] = urlencode($pass);

		$page = geturl($domain, 80, '/api/user/login', $referer.'login.php?return=%2F', $cookie, $post, 0, $_GET['proxy'], $pauth);is_page($page);
		$json = Get_Reply($page);
		if (!empty($json['error'])) html_error('Login Error'. (!empty($errors[$json['error']]) ? ': ' . $errors[$json['error']] : '..'));
		elseif ($json['status'] != 'OK') html_error('Login Failed');

		$cookie = GetCookiesArr($page, $cookie);
		if (empty($cookie['autologin'])) html_error('Login Error: Cannot find "autologin" cookie');

		SaveCookies($user, $pass); // Update cookies file
		return true;
	} else {
		$post = array();
		$post['login'] = urlencode($user);
		$post['password'] = urlencode($pass);

		$page = geturl($domain, 80, '/api/user/login', $referer.'login.php?return=%2F', $cookie, $post, 0, $_GET['proxy'], $pauth);is_page($page);
		$json = Get_Reply($page);
		if (!empty($json['error']) && $json['error'] != 'CaptchaRequired') html_error('Login Error'. (!empty($errors[$json['error']]) ? ': ' . $errors[$json['error']] : '.'));
		elseif ($json['status'] == 'OK') {
			$cookie = GetCookiesArr($page, $cookie);
			if (empty($cookie['autologin'])) html_error('Login Error: Cannot find "autologin" cookie.');
			SaveCookies($user, $pass); // Update cookies file
			return true;
		} elseif (empty($json['error']) || $json['error'] != 'CaptchaRequired') html_error('Login Failed.');

		// Captcha Required
		$page = geturl($domain, 80, '/login.php?return=%2F', $referer, $cookie, 0, 0, $_GET['proxy'], $pauth);is_page($page);
		$cookie = GetCookiesArr($page, $cookie);

		if (!preg_match('@(https?://([^/\r\n\t\s\'\"<>]+\.)?(?:depositfiles|dfiles)\.[^/:\r\n\t\"\'<>]+(?:\:\d+)?)/js/base2\.js@i', $page, $jsurl)) html_error('Cannot find captcha.');
		$jsurl = (empty($jsurl[1])) ? 'http://' . $domain . $jsurl[0] : $jsurl[0];
		$jsurl = parse_url($jsurl);
		$page = geturl($jsurl['host'], defport($jsurl), $jsurl['path'].(!empty($jsurl['query']) ? '?'.$jsurl['query'] : ''), $referer.'login.php?return=%2F', $cookie, 0, 0, $_GET['proxy'], $pauth);is_page($page);

		if (!preg_match('@recaptcha_public_key\s*=\s*[\'\"]([\w\-]+)@i', $page, $cpid)) html_error('reCAPTCHA Not Found.');

		$post = array('action' => 'FORM');
		$post['step'] = '1';
		if (!$default_acc) {
			$post['up_encrypted'] = 'true';
			$post['up_login'] = urlencode(encrypt($user));
			$post['up_pass'] = urlencode(encrypt($pass));
		}
		Show_reCaptcha($cpid[1], $post, 'Login');
	}
}

function IWillNameItLater($cookie, $decrypt=true) {
	if (!is_array($cookie)) {
		if (!empty($cookie)) return $decrypt ? decrypt(urldecode($cookie)) : urlencode(encrypt($cookie));
		return '';
	}
	if (count($cookie) < 1) return $cookie;
	$keys = array_keys($cookie);
	$values = array_values($cookie);
	$keys = $decrypt ? array_map('decrypt', array_map('urldecode', $keys)) : array_map('urlencode', array_map('encrypt', $keys));
	$values = $decrypt ? array_map('decrypt', array_map('urldecode', $values)) : array_map('urlencode', array_map('encrypt', $values));
	return array_combine($keys, $values);
}

function SkipLoginC($user, $pass) {
	global $cookie, $domain, $referer, $options, $pauth;
	if (!defined('DOWNLOAD_DIR')) {
		global $options;
		if (substr($options['download_dir'], -1) != '/') $options['download_dir'] .= '/';
		define('DOWNLOAD_DIR', (substr($options['download_dir'], 0, 6) == 'ftp://' ? '' : $options['download_dir']));
	}

	$filename = DOWNLOAD_DIR.basename('depositfiles_ul.php');
	if (!file_exists($filename)) return Login($user, $pass);

	$file = file($filename);
	$savedcookies = unserialize($file[1]);
	unset($file);

	$hash = hash('crc32b', $user.':'.$pass);
	if (array_key_exists($hash, $savedcookies)) {
		$_secretkey = $options['secretkey'];
		$options['secretkey'] = sha1($user.':'.$pass);
		$cookie = (decrypt(urldecode($savedcookies[$hash]['enc'])) == 'OK') ? IWillNameItLater($savedcookies[$hash]['cookie']) : '';
		$options['secretkey'] = $_secretkey;
		if ((is_array($cookie) && count($cookie) < 1) || empty($cookie)) return Login($user, $pass);

		$page = geturl($domain, 80, '/', $referer, $cookie, 0, 0, $_GET['proxy'], $pauth);is_page($page);
		if (stripos($page, '/logout.php">Logout</a>') === false) return Login($user, $pass);
		SaveCookies($user, $pass); // Update cookies file
		return true;
	}
	return Login($user, $pass);
}

function SaveCookies($user, $pass) {
	global $cookie, $options;
	$maxdays = 7; // Max days to keep cookies saved
	$filename = DOWNLOAD_DIR.basename('depositfiles_ul.php');
	if (file_exists($filename)) {
		$file = file($filename);
		$savedcookies = unserialize($file[1]);
		unset($file);

		// Remove old cookies
		foreach ($savedcookies as $k => $v) if (time() - $v['time'] >= ($maxdays * 24 * 60 * 60)) unset($savedcookies[$k]);
	} else $savedcookies = array();
	$hash = hash('crc32b', $user.':'.$pass);
	$_secretkey = $options['secretkey'];
	$options['secretkey'] = sha1($user.':'.$pass);
	$savedcookies[$hash] = array('time' => time(), 'enc' => urlencode(encrypt('OK')), 'cookie' => IWillNameItLater($cookie, false));
	$options['secretkey'] = $_secretkey;

	write_file($filename, "<?php exit(); ?>\r\n" . serialize($savedcookies));
}

//[06-9-2012] Written by Th3-822.
//[01-1-2013] Fixed login. - Th3-822 (Happy New Year)
//[20-1-2013] Updated for df's new domains. - Th3-822

?>