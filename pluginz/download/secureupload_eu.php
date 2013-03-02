<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

class secureupload_eu extends DownloadClass {

	public function Download($link) {
		global $premium_acc;

		if (!$_REQUEST['step']) {
			$this->cookie = array('lang' => 'english');
			$this->page = $this->GetPage($link, $this->cookie);
			is_present($this->page, 'The file was removed');
		}
		$this->link = $link;
		if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass'])||($premium_acc['secureupload_eu']['user'] && $premium_acc['secureupload_eu']['pass']))) {
			return $this->Premium();
		} else {
			return $this->Free();
		}
	}

	private function Premium() {
		html_error('Error[Unsupported Now!]');
	}

	private function Free() {
		if ($_REQUEST['step'] == '1') {
			$this->link = urldecode($_POST['link']);
			$this->cookie = urldecode($_POST['cookie']);
			$post = array();
			foreach ($_POST['tmp'] as $k => $v) {
				$post[$k] = $v;
			}
			$post['code'] = $_POST['captcha'];
			$page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
		} else {
			$form = cut_str($this->page, '<Form method="POST" action=\'\'>', '</Form>');
			if (!preg_match_all('/type="(hidden|submit)" name="([^"]+)" value="([^"]+)?"/', $form, $match)) html_error('Error[Form Post 1 - FREE not found!]');
			$match = array_combine($match[2], $match[3]);
			$post= array();
			foreach ($match as $key => $value) {
				$post[$key] = $value;
			}
			$page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
		}
		if (stripos($page, 'Enter code below:')) {
			$form = cut_str($page, '<Form name="F1" method="POST" action=""', '</Form>');
			if (stripos($form, 'Wrong captcha')) echo "<center><font color='red'><b>Wrong Captcha, Please retry!</b></font></center>";
			if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $match)) html_error('Error[Form Post 2 - FREE not found!]');
			$match = array_combine($match[1], $match[2]);
			if (!preg_match('/(\d+)<\/span>seconds/', $form, $w)) html_error('Error[Timer not found!]');
			$this->CountDown($w[1]);
			if (!preg_match('/http:\/\/www\.secureupload\.eu\/captchas\/[^\r\n"]+/i', $form, $c)) html_error('Error[Captcha Data not found!]');
            $capt = $this->GetPage($c[0], $this->cookie);
            $capt_img = substr($capt, strpos($capt, "\r\n\r\n") + 4);
            $imgfile = DOWNLOAD_DIR . "secureupload_eu.jpg";
            if (file_exists($imgfile)) unlink($imgfile);
            if (empty($capt_img) || !write_file($imgfile, $capt_img)) html_error("Error getting CAPTCHA image.", 0);

			$data = $this->DefaultParamArr($this->link, $this->cookie);
			foreach ($match as $k => $v) {
				$data["tmp[$k]"] = $v;
			}
			$data['step'] = '1';
			$this->EnterCaptcha($imgfile, $data);
			exit;
		}
		is_present($page, cut_str($page, '<div class="err">', '<br>'));
		if (!preg_match('/href="(https?:\/\/[^\r\n"]+)"><span>Download slow/', $page, $dl)) html_error('Error[Download Link - FREE not found!]');
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $this->cookie, 0, $this->link);
	}
}

/*
 * Written by Tony Fauzi Wihana/Ruud v.Tony 25-02-2013
 */
?>
