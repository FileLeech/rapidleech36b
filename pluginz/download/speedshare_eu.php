<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

class speedshare_eu extends DownloadClass {
	
	public function Download($link) {
		global $premium_acc;
		
		if (!$_REQUEST['step']) {
			$this->page = $this->GetPage($link, 'lang=english');
			is_present($this->page, '<b>File Not Found</b>');
			$this->cookie = GetCookiesArr($this->page);
		}
		$this->link = $link;
		if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass'])||($premium_acc['speedshare_eu']['user'] && $premium_acc['speedshare_eu']['pass']))) {
			return $this->Premium();
		} else {
			return $this->Free();
		}
	}
	
	private function Premium() {
		$cookie = $this->login();
		$page = $this->GetPage($this->link, $cookie);
		if (stripos($page, 'HTTP/1.1 200')) {
			$form = cut_str($page, '<h3>Download File</h3>', '</Form>');
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $match)) html_error('Error[Post Data - PREMIUM not found!]');
			$match = array_combine($match[1], $match[2]);
			$post = array();
			foreach ($match as $k => $v) {
				$post[$k] = $v;
			}
			$page = $this->GetPage($this->link, $cookie, $post);
		}
		if (!preg_match('/Location: (https?:\/\/[^\r\n]+)/i', $page, $dl)) html_error ('Error[Download Link - PREMIUM not found!]');
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie);
	}
	
	private function login() {
		global $premium_acc;
		
        $user = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["speedshare_eu"] ["user"]);
        $pass = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["speedshare_eu"] ["pass"]);
        if (empty($user) || empty($pass)) html_error("Login failed, username[$user] or password[$pass] is empty!");
		
		$posturl = 'http://speedshare.eu/';
		$post = array();
		$post['op'] = 'login';
		$post['redirect'] = $posturl;
		$post['login'] = $user;
		$post['password'] = $pass;
		$post['x'] = rand(11,45);
		$post['y'] = rand(1,8);
		$page = $this->GetPage($posturl, $this->cookie, $post, $posturl.'login.html');
		is_present($page, cut_str($page, "<b class='err'>", '</b>'));
		$cookie = GetCookiesArr($page, $this->cookie);
		
		//check account
		$page = $this->GetPage($posturl.'?op=my_account', $cookie, 0, $posturl);
		is_notpresent($page, '<TD>Premium account expire:</TD>', 'Account isn\'t premium?');
		
		return $cookie;
	}
	
	private function Free() {
		
		if ($_REQUEST['step'] == '1') {
			$this->link = urldecode($_POST['link']);
			$this->cookie = StrToCookies(urldecode($_POST['cookie']));
			$post = array();
			foreach ($_POST["tmp"] as $k => $v) {
				$post[$k] = $v;
			}
			$post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
			$post['recaptcha_response_field'] = $_POST['recaptcha_response_field'];
			$page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
		} else {
			$form = cut_str($this->page, '<Form method="POST" action=\'\'>', '</Form>');
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $match)) html_error('Error[Post Data 1 - FREE not found!]');
			$match = array_combine($match[1], $match[2]);
			$post = array();
			foreach ($match as $k => $v) {
				$post[$k] = $v;
			}
			$post['x'] = rand(11, 70);
			$post['y'] = rand(11, 20);
			$page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
		}
		if (stripos($page, 'Type the two words')) {
			$form = cut_str($page, '<Form name="F1" method="POST" action=""', '</Form>');
			if (stripos($form, 'Wrong captcha')) echo ("<center><font color='red'><b>Wrong Captcha, Please Retry!</b></font></center>");
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $match)) html_error('Error[Post Data 2 - FREE not found!]');
			$match = array_combine($match[1], $match[2]);
			if (!preg_match('/\/recaptcha\/api\/challenge\?k=([^"]+)"/', $form, $c)) html_error ('Error[Captcha data not found!]');
			if (!preg_match('/(\d+)<\/span> seconds/', $form, $w)) html_error('Error[Timer not found!]');
			$this->CountDown($w[1]);
			
			$data = $this->DefaultParamArr($this->link, $this->cookie);
			$data['step'] = '1';
			foreach ($match as $k => $v) {
				$data["tmp[$k]"] = $v;
			}
			$this->Show_reCaptcha($c[1], $data);
			exit;
		}
		if (!preg_match('/Location: (https?:\/\/[^\r\n]+)/i', $page, $dl)) html_error('Error[Download Link - FREE not found!]');
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $this->cookie, 0, $this->link);
		exit;
	}
	
    private function Show_reCaptcha($pid, $inputs) {
        global $PHP_SELF;
        if (!is_array($inputs)) {
            html_error("Error parsing captcha data.");
        }
        // Themes: 'red', 'white', 'blackglass', 'clean'
        echo "<script language='JavaScript'>var RecaptchaOptions={theme:'white', lang:'en'};</script>\n";
        echo "\n<center><form name='dl' action='$PHP_SELF' method='post' ><br />\n";
        foreach ($inputs as $name => $input) {
            echo "<input type='hidden' name='$name' id='$name' value='$input' />\n";
        }
        echo "<script type='text/javascript' src='http://www.google.com/recaptcha/api/challenge?k=$pid'></script>";
        echo "<noscript><iframe src='http://www.google.com/recaptcha/api/noscript?k=$pid' height='300' width='500' frameborder='0'></iframe><br />";
        echo "<textarea name='recaptcha_challenge_field' rows='3' cols='40'></textarea><input type='hidden' name='recaptcha_response_field' value='manual_challenge' /></noscript><br />";
        echo "<input type='submit' name='submit' onclick='javascript:return checkc();' value='Enter Captcha' />\n";
        echo "<script type='text/javascript'>/*<![CDATA[*/\nfunction checkc(){\nvar capt=document.getElementById('recaptcha_response_field');\nif (capt.value == '') { window.alert('You didn\'t enter the image verification code.'); return false; }\nelse { return true; }\n}\n/*]]>*/</script>\n";
        echo "</form></center>\n</body>\n</html>";
        exit;
    }
	
}

/*
 * Written by Tony Fauzi Wihana/Ruud v.Tony 15-01-2013
 * Updated to support premium by Tony Fauzi Wihana/Ruud v.Tony 16-01-2013
 */
?>
