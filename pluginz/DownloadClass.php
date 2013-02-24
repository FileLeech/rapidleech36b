<?php
if (!defined('RAPIDLEECH')) {
	require('../404.php');
	exit;
}

class DownloadClass {
	/*
	 * Prints the initial form for displaying messages
	 * @return void
	 */

	public function __construct($echo = true) {
		global $L;
		if (!$echo) return;
		echo('<table width="600" align="center">');
		echo('<tr>');
		echo('<td align="center">');
		echo('<div id="mesg" width="100%" align="center">' . $L->say['_retrieving'] . '</div>');
	}

	/*
	 * You can use this function to retrieve pages without parsing the link
	 * @param string $link -> The link of the page to retrieve
	 * @param string $cookie -> The cookie value if you need
	 * @param array $post -> Array name=>value of the post data
	 * @param string $referer -> The referer of the page, it might be the value you are missing if you can't get plugin to work
	 * @param string $auth -> Page authentication, unneeded in most circumstances
	 */

	public function GetPage($link, $cookie = 0, $post = 0, $referer = 0, $auth = 0, $XMLRequest = 0) {
		global $options;
		if (!$referer) {
			global $Referer;
			$referer = $Referer;
		}
		$cURL = $options['use_curl'] && extension_loaded('curl') && function_exists('curl_init') && function_exists('curl_exec') ? true : false;
		$Url = parse_url(trim($link));
		if ($Url ['scheme'] == 'https') {
			$chttps = false;
			if ($cURL) {
				$cV = curl_version();
				if (in_array('https', $cV['protocols'], true)) $chttps = true;
			}
			if (!extension_loaded('openssl') && !$chttps) html_error('This server doesn\'t support https connections.');
			elseif (!$chttps) $cURL = false;
		}

		if ($cURL) {
			if ($XMLRequest) $referer .= "\r\nX-Requested-With: XMLHttpRequest";
			$page = cURL($link, $cookie, $post, $referer, $auth);
		} else {
			global $pauth;
			$page = geturl($Url['host'], defport($Url), $Url['path'] . (!empty($Url['query']) ? '?' . $Url['query'] : ''), $referer, $cookie, $post, 0, !empty($_GET['proxy']) ? $_GET['proxy'] : '', $pauth, $auth, $Url['scheme'], 0, $XMLRequest);
			is_page($page);
		}
		return $page;
	}

	/*
	 * Use this function instead of insert_location so that we can improve this feature in the future
	 * @param string $link -> The download link of the file
	 * @param string $FileName -> The name of the file
	 * @param string $cookie -> The cookie value
	 * @param array $post -> The post value will be serialized here
	 * @param string $referer -> The page that refered to this link
	 * @param string $auth -> In format username:password
	 * @param array $params -> This parameter allows you to add extra _GET values to be passed on
	 */

	public function RedirectDownload($link, $FileName, $cookie = 0, $post = 0, $referer = 0, $force_name = 0, $auth = 0, $addon = array()) {
		global $pauth;
		if (!$referer) {
			global $Referer;
			$referer = $Referer;
		}
		$url = parse_url($link);
		$params = $this->DefaultParamArr($link, (!empty($cookie) ? (is_array($cookie) ? encrypt(CookiesToStr($cookie)) : encrypt($cookie)) : 0), $referer);
		$params['filename'] = urlencode($FileName);
		if (!empty($force_name)) $params['force_name'] = urlencode($force_name);
		$params['host'] = urlencode($url['host']);
		if (!empty($url['port'])) $params['port'] = urlencode($url['port']);
		$params['path'] = urlencode($url['path'] . (!empty($url['query']) ? '?' . $url['query'] : ''));
		if (!empty($post)) $params['post'] = urlencode(encrypt(serialize($post)));
		if (!empty($auth)) $params['auth'] = urlencode($auth);
		if (!empty($addon)) {
			if (!is_array($addon)) html_error('Plugin problem! Please report, error: "The parameter passed must be an array"'); // Some problems with the plugin, quit it
			foreach ($addon as $name => $value) $params[$name] = (is_array($value) ? urlencode(serialize($value)) : urlencode($value));
		}
		insert_location($params);
	}

	/*
	 * Use this function to move your multiples links array to auto downloader
	 * @param array $link_array -> Normal array containing all download links
	 */

	public function moveToAutoDownloader($link_array) {
		global $PHP_SELF, $options;
		if (empty($link_array) || !is_array($link_array) || count($link_array) == 0) html_error('Error getting links from folder.');

		if (!@is_file('audl.php') || !empty($options['forbid_audl'])) html_error('audl.php not found or you have disable auto download feature!');

		$pos = strrpos($PHP_SELF, '/');
		$audlpath = ($pos !== false) ? substr($PHP_SELF, 0, $pos + 1).'audl.php?GO=GO' : 'audl.php?GO=GO';
		$inputs = GetDefaultParams();
		$inputs['links'] = implode("\r\n", $link_array);

		$key_array = array('premium_acc', 'premium_user', 'premium_pass', 'df_acc', 'df_cookie', 'hf_acc', 'hf_cookie', 'net_acc', 'net_cookie', 'rs_acc', 'rs_cookie', 'ul_acc', 'ul_cookie', 'upl_acc', 'upl_cookie', 'cookieuse', 'cookie');
		foreach ($key_array as $v) if (!empty($_GET[$v])) $inputs[$v] = urlencode($_GET[$v]);
		insert_location($inputs, $audlpath);
		exit();
	}

	public function CountDown($countDown) {
		if ($countDown <= 0) return;
		insert_timer($countDown, "Waiting link timelock");
	}

	/*
	 * Use this function to create Captcha display form
	 * @param string $captchaImg -> The link of the captcha image or downloaded captcha image on server
	 * @param array $inputs -> Key Value pairs for html form input elements ( these elements will be hidden form elements )
	 * @param string $captchaSize -> The size of captcha text box
	 */

	public function EnterCaptcha($captchaImg, $inputs, $captchaSize = '5') {
		global $L;
		echo "\n<form name='captcha' action='{$_SERVER['SCRIPT_NAME']}' method='POST'>\n";
		foreach ($inputs as $name => $input) echo "\t<input type='hidden' name='$name' id='$name' value='$input' />\n";
		echo "\t<h4>" . $L->say['_enter'] . " <img alt='CAPTCHA Image' src='$captchaImg' /> " . $L->say['_here'] . ": <input type='text' name='captcha' size='$captchaSize' />&nbsp;&nbsp;\n\t\t<input type='submit' onclick='return check();' value='Enter Captcha' />\n\t</h4>\n\t<script type='text/javascript'>/* <![CDATA[ */\n\t\tfunction check() {\n\t\t\tvar captcha=document.dl.captcha.value;\n\t\t\tif (captcha == '') {\n\t\t\t\twindow.alert('You didn\'t enter the image verification code');\n\t\t\t\treturn false;\n\t\t\t} else return true;\n\t\t}\n\t/* ]]> */</script>\n</form>\n</body>\n</html>";
	}

	/*
	 * This function will return an array with the Default Key Value pairs including proxy, method, email, etc.
	 * @param string $link -> Adds the link value to the array url encoded if you need it.
	 * @param string $cookie -> Adds the cookie value to the array url encoded if you need it.
	 * @param string $referer -> Adds the referer value to the array url encoded if you need it. If isn't set, it will load $Referer value. (Set as 0 or false for not add it in the array.)
	 */

	public function DefaultParamArr($link = 0, $cookie = 0, $referer = 1) {
		if ($referer == 1) {
			global $Referer;
			$referer = $Referer;
		}
		if (is_array($cookie)) $cookie = CookiesToStr($cookie);

		$DParam = GetDefaultParams();
		if ($link) $DParam['link'] = urlencode($link);
		if ($cookie) $DParam['cookie'] = urlencode($cookie);
		if ($referer) $DParam['referer'] = urlencode($referer);
		return $DParam;
	}

	public function changeMesg($mesg) {
		echo('<script type="text/javascript">document.getElementById(\'mesg\').innerHTML="' . stripslashes($mesg) . '";</script>');
	}

}
?>