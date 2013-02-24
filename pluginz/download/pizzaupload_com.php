<?php
if (!defined('RAPIDLEECH')) {
	require_once('index.html');
	exit();
}

class pizzaupload_com extends DownloadClass {
	
	public function Download($link) {
		if ($_REQUEST['step'] == 'Recaptcha') {
			$post['op'] = $_POST['op'];
			$post['id'] = $_POST['id'];
			$post['rand'] = $_POST['rand'];
			$post['referer'] = $_POST['referer'];
			$post['method_free'] = $_POST['method_free'];
			$post['method_premium'] = '';
			$post['recaptcha_challenge_field'] = $_POST['challenge'];
			$post['recaptcha_response_field'] = $_POST['captcha'];
			$post['down_script'] = $_POST['down_script'];
			$link = urldecode($_POST['link']);
			$cookie = urldecode($_POST['cookie']);
			$page = $this->GetPage($link, $cookie, $post, $link);
		} else {
			$page = $this->GetPage($link);
			$cookie = GetCookies($page);
			$form = cut_str($page, '<form method="POST" action=\'\'>', '</form>');
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $one) || !preg_match_all('/<input type="submit" name="(\w+_free)" value="([^"]+)">/', $form, $two)) html_error('Error[Post Data 1 FREE not found!]');
			$match = array_merge(array_combine($one[1], $one[2]), array_combine($two[1], $two[2]));
			$post = array();
			foreach ($match as $k => $v) {
				$post[$k] = $v;
			}
			$page = $this->GetPage($link, $cookie, $post, $link);
		}
		if (stripos($page, 'Type the two words')) {
			$form = cut_str($page, '<form name="F1" method="POST"', '</form>');
			if (stripos($form, cut_str($form, '<div class="err">','</div>'))) echo ("<center><font color='red'><b>Wrong Captcha, Please rety!</b></font></center>");
			if (preg_match('/(\d+)<\/span>&nbsp;<b>seconds/', $form, $w)) $this->CountDown ($w[1]);
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $match)) html_error('Error[Post Data 2 FREE not found!]');
			$match = array_combine($match[1], $match[2]);
			
			if (!preg_match('/\/api\/challenge\?k=([^"]+)"/', $form, $c)) html_error('Error[Captcha Data not found!]');
			$ch = cut_str($this->GetPage("http://www.google.com/recaptcha/api/challenge?k=$c[1]", $cookie), "challenge : '", "'");
			$capt = $this->GetPage("http://www.google.com/recaptcha/api/image?c=" . $ch);
			$capt_img = substr($capt, strpos($capt, "\r\n\r\n") + 4);
			$imgfile = DOWNLOAD_DIR . "pizzaupload_captcha.jpg";
			
			if (file_exists($imgfile)) unlink($imgfile);
			if (empty($capt_img) || !write_file($imgfile, $capt_img)) html_error("Error getting CAPTCHA image.", 0);

			$data = array_merge($this->DefaultParamArr($link, $cookie), $match);
			$data['step'] = 'Recaptcha';
			$data['challenge'] = $ch;
			$this->EnterCaptcha($imgfile, $data, 20);
			exit;
		}
		is_present($page, cut_str($page, '<p class="err">', '<br>'));
		if (!preg_match('/Location: (https?:\/\/[^\r\n]+)/i', $page, $dl)) html_error('Error[Download Link FREE not found!]');
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie, 0, $link);
		exit;
	}
}

/*
 * Written by Ruud v.Tony 09-07-2012
 */
?>
