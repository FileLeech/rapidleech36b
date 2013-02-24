<?php
if (!defined('RAPIDLEECH')) {
	require_once('index.html');
	exit();
}

class filesega_com extends DownloadClass {
	
	public $cookie, $page;
	public function Download($link) {
		global $premium_acc;
		
		if (!$_REQUEST['step']) {
			$this->cookie = array('lang' => 'english');
			$this->page = $this->GetPage($link, $this->cookie);
			is_present($this->page, 'The file you were looking for could not be found, sorry for any inconvenience.');
		}
		$this->link = $link;
		if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass'])||($premium_acc['filesega_com']['user'] && $premium_acc['filesega_com']['pass']))) {
			return $this->Premium();
		} else {
			return $this->Free();
		}
	}
	
	private function Free() {
		if ($_REQUEST['step'] == '1') {
			$post['op'] = $_POST['op'];
			$post['id'] = $_POST['id'];
			$post['rand'] = $_POST['rand'];
			$post['referer'] = $_POST['referer'];
			$post['method_free'] = $_POST['method_free'];
			$post['method_premium'] = '';
			$post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
			$post['recaptcha_response_field'] = $_POST['recaptcha_response_field'];
			$post['down_direct'] = $_POST['down_direct'];
			$this->link = urldecode($_POST['link']);
			$this->cookie = urldecode($_POST['cookie']);
			$page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
		} else {
			$form = cut_str($this->page, '<Form method="POST" action=\'\'>', '</Form>');
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $one) || !preg_match_all('/<input type="submit" name="(\w+_free)" value="([^"]+)?">/', $form, $two)) html_error('Error[Form Post Data1 not found!]');
			$match = array_merge(array_combine($one[1], $one[2]), array_combine($two[1], $two[2]));
			$post = array();
			foreach ($match as $k => $v) {
				$post[$k] = $v;
			}
			$page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
		}
		if (stripos($page, "Type the two words")) {
			$form = cut_str($page, '<Form name="F1" method="POST" action=""', '</Form>');
			if (stripos($form, cut_str($form, '<p class="err">', '</p>'))) echo ("<center><font color='red'><b>".cut_str($form, '<p class="err">', '</p>')."</b></font></center>");
			if (!preg_match('/(\d+)<\/span> seconds/', $form, $wait)) html_error('Error[Timer not found!]');
			$this->CountDown($wait[1]);
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $match)) html_error ('Error[Post Data 2 not found!]');
			$data = array_merge(array_combine($match[1], $match[2]), $this->DefaultParamArr($this->link, $this->cookie));
			$data['step'] = '1';
			if (!preg_match('@\/api\/challenge\?k=([^"]+)"@', $form, $c)) html_error('Error [Captcha Data not found!]');
			$this->Show_reCaptcha($c[1], $data);
			exit();
		}
		is_present($page, cut_str($page, '<div class="err">', '<br>'));
		if (!preg_match('/https?:\/\/\w+\d+\.filesega\.com(:\d+)?\/d\/[^\r\n"]+/', $page, $dl)) html_error ('Error[Download Link not found!]');
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
            echo "<input type='hidden' name='$name' value='$input' />\n";
        }
        echo "<script type='text/javascript' src='http://www.google.com/recaptcha/api/challenge?k=$pid'></script>";
        echo "<noscript><iframe src='http://www.google.com/recaptcha/api/noscript?k=$pid' height='300' width='500' frameborder='0'></iframe><br />";
        echo "<textarea name='recaptcha_challenge_field' rows='3' cols='40'></textarea><input type='hidden' name='recaptcha_response_field' value='manual_challenge' /></noscript><br />";
        echo "<input type='submit' name='submit' onclick='javascript:return checkc();' value='Enter Captcha' />\n";
        echo "<script type='text/javascript'>/*<![CDATA[*/\nfunction checkc(){\nvar capt=document.getElementById('recaptcha_response_field');\nif (capt.value == '') { window.alert('You didn\'t enter the image verification code.'); return false; }\nelse { return true; }\n}\n/*]]>*/</script>\n";
        echo "</form></center>\n</body>\n</html>";
        exit();
    }
	
	private function Premium() {
		$cookie = $this->login();
		$page = $this->GetPage($this->link, $cookie);
	}
	
	private function login() {
		global $premium_acc;
		
	}
}
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
