<?php
if (!defined('RAPIDLEECH')) {
	require_once("index.html");
	exit;
}

class extabit_com extends DownloadClass {

	public function Download($link) {
		global $premium_acc;

		if (!$_REQUEST['step']) {
			$this->cookie = array('language' => 'en');
			$this->page = $this->GetPage($link, $this->cookie);
			if (preg_match("/Location: (https?:\/\/[^\r\n]+)/i", $this->page, $redir)) {
				$link = trim($redir[1]);
				$this->page = $this->GetPage($link, $this->cookie);
			}
			is_present($this->page, 'File not found');
		}
		$this->link = $link;
		if ($_REQUEST ["premium_acc"] == "on" && ((!empty($_REQUEST ["premium_user"]) && !empty($_REQUEST ["premium_pass"])) || (!empty($premium_acc ["extabit_com"] ["user"]) && !empty($premium_acc ["extabit_com"] ["pass"])))) {
			$this->DownloadPremium();
		} elseif ($_REQUEST['step'] == '1') {
			$this->DownloadFree();
		} else {
			$this->Retrieve();
		}
	}

	private function DownloadFree() {
		$recap = $_POST['recap'];
		$captcha = $_POST['recaptcha_response_field'];
		$challenge = $_POST['recaptcha_challenge_field'];
		$this->link = urldecode($_POST['link']);
		$this->cookie = StrToCookies(urldecode($_POST['cookie']));
		$page = $this->GetPage("$this->link?type=recaptcha&challenge=$challenge&capture=$captcha", $this->cookie, 0, $this->link, 0, 1);
		$this->cookie = GetCookiesArr($page, $this->cookie);
		if (preg_match('@\{"(\w+)":(\w+)?,?"?(\w+)?"?:?"([^"]+)"\}@', $page, $ck)) {
			switch ($ck[1]) {
				case 'ok':
					$Url = $this->link . $ck[4];
					$page = $this->GetPage($Url, $this->cookie, 0, $this->link);
					if (!preg_match('/https?:\/\/guest\d+\.extabit\.com\/[^\r\n\s\t"]+/', $page, $dl)) html_error("Error[DownloadLink - FREE] not found!");
					$dlink = trim($dl[0]);
					$filename = basename(parse_url($dlink, PHP_URL_PATH));
					$this->RedirectDownload($dlink, $filename, $cookie, 0, $this->link);
					break;

				case 'err':
					echo ("<center><font color='red'><b>$ck[4]</b></font></center>");

					$data = $this->DefaultParamArr($this->link, $this->cookie);
					$data['step'] = '1';
					$data['recap'] = $recap;
					$this->Show_reCaptcha($recap, $data);
					break;
			}
			is_present($ck[1], 'err', $ck[4]);
		}
	}

	private function Retrieve() {
		is_present($this->page, "Only premium users can download this file");
		is_notpresent($this->page, 'I want download for free', cut_str($this->page, '<div class="b-download-free download-link">', '<a class="b-get-premium" href="/premium.jsp">'));
		$this->cookie = GetCookiesArr($this->page, $this->cookie);
		if (!preg_match('/<div id="\w+">(\d+)<\/div>/', $this->page, $wait)) html_error('Error [Timer not found!]');
		$this->CountDown($wait[1]);
		$form = cut_str($this->page, 'class="download_link_captcha_en">', '</form>');
		$Url = 'http://extabit.com' . cut_str($form, '<form action="', '"');
		if (!preg_match('/api\/challenge\?k=([^"]+)/', $form, $k) && !preg_match('/api\/noscript\?k=([^"]+)/', $form, $k)) html_error('Error [Captcha Data not found!]');

		$data = $this->DefaultParamArr($Url, $this->cookie);
		$data['step'] = '1';
		$data['recap'] = $k[1];
		$this->Show_reCaptcha($k[1], $data);
		exit;
	}

	private function DownloadPremium() {

		$Cookies = $this->login();
		$page = $this->GetPage($this->link, $Cookies);
		if (!preg_match('#http://[a-z]\d+\.extabit\.com/[^\r\n"]+#', $page, $dlink)) html_error("Error[Download Link - PREMIUM not found!]");
		$filename = basename(parse_url($dlink[0], PHP_URL_PATH));
		$this->RedirectDownload($dlink[0], $filename, $Cookies, 0, $this->link);
	}

	private function login() {
		global $premium_acc;

		$email = ($_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["extabit_com"] ["user"]);
		$password = ($_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["extabit_com"] ["pass"]);
		if (empty($email) || empty($password)) html_error("Login failed, username or password is empty!");

		$posturl = 'http://extabit.com/';
		$post['email'] = $email;
		$post['pass'] = $password;
		$post['auth_submit_login.x'] = rand(0, 56);
		$post['auth_submit_login.y'] = rand(0, 13);
		$page = $this->GetPage($posturl . 'login.jsp', $this->cookie, $post, $posturl);
		is_present($page, 'http://extabit.com/?err=2', 'Username or password is incorrect.');
		$cookie = GetCookiesArr($page, $this->cookie);

		//check account
		$page = $this->GetPage($posturl . 'profile.jsp', $cookie, 0, $posturl . 'login.jsp');
		$cookie = GetCookiesArr($page, $cookie);
		is_notpresent($page, 'Premium is active till', 'Error[Account isn\'t premium?]');

		return $cookie;
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
 * by vdhdevil
 * fixed captcha also redirect link by Ruud v.Tony 06-02-2012
 * fixed filename error in premium also recaptcha in free by Ruud v.Tony 28-03-2012
 * fixed free download code by Tony Fauzi Wihana/Ruud v.Tony 08-03-2013, some regexp taken based on Th3-822 comment in extabit thread
 */
?>
