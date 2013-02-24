<?php
if (!defined('RAPIDLEECH')) {
	require_once ('index.html');
	exit();
}

class d4savefile_com extends DownloadClass {

	public function Download($link) {
		if ($_REQUEST['step'] == 'Recaptcha') {
			$post['op'] = $_POST['op'];
			$post['id'] = $_POST['id'];
			$post['rand'] = $_POST['rand'];
			$post['referer'] = $_POST['referer'];
			$post['method_free'] = $_POST['method_free'];
			$post['method_premium'] = '';
			$post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
			$post['recaptcha_response_field'] = $_POST['recaptcha_response_field'];
			$post['down_direct'] = $_POST['down_direct'];
			$link = urldecode($_POST['link']);
			$cookie = urldecode($_POST['cookie']);
			$page = $this->GetPage($link, $cookie, $post, $link);
		} else {
			$page = $this->GetPage($link);
			is_present($page, 'The file you were looking for could not be found, sorry for any inconvenience.');
			$cookie = GetCookies($page);
			$form = cut_str($page, '<form method="POST" action=\'\'>', '</form>');
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $one) || !preg_match_all('/<input type="submit" name="(\w+_free)" value="([^"]+)">/', $form, $two)) html_error('Error[Post Data 1 not found!]');
			$match = array_merge(array_combine($one[1], $one[2]), array_combine($two[1], $two[2]));
			$post = array();
			foreach ($match as $key => $value) {
				$post[$key] = $value;
			}
			$page = $this->GetPage($link, $cookie, $post, $link);
		}
		if (stripos($page, 'Type the two words')) {
			$form = cut_str($page, '<form name="F1" method="POST"', '</form>');
			if (stripos($form, cut_str($form, '<div class="err">', '</div>'))) echo ("<center><font color='red'><b>Wrong Captcha, Please rety!</b></font></center>");
			if (!preg_match('/(\d+)<\/span> seconds/', $form, $wait)) html_error('Error[Timer not found!]');
			$this->CountDown($wait[1]);
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $match)) html_error('Error[Post Data 2 not found!]');
			$match = array_combine($match[1], $match[2]);
			$data = array_merge($this->DefaultParamArr($link, $cookie), $match);
			$data['step'] = 'Recaptcha';
			if (!preg_match('/\/api\/challenge\?k=([^"]+)"/', $form, $c)) html_error('Error[Captcha Data not found!]');
			$this->Show_reCaptcha($c[1], $data);
			exit();
		}
		is_present($page, cut_str($page, '<div class="err">', '<br>'));
		if (!preg_match('/https?:\/\/[a-zA-Z]+\d+\.4savefile\.com\/[^\r\n\'"]+/', $page, $dl)) html_error('Error[Download link FREE not found!]');
		$dlink = trim($dl[0]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie, 0, $link);
		exit();
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
 * written by Ruud v.Tony 18-06-2012
 */
?>
