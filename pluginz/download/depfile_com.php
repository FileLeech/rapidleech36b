<?php

if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

class depfile_com extends DownloadClass {

	public function Download($link) {
		global $premium_acc;

		if (!$_REQUEST['step']) {
			$this->cookie['sdlanguageid'] = '2';
			$this->page = $this->GetPage($link, $this->cookie);
			is_present($this->page, 'File was not found in the depFile database. It is possible that you provided wrong link.');
			$this->cookie = GetCookiesArr($this->page, $this->cookie);
		}
		$this->link = $link;
		if ($_REQUEST['premium_acc'] == 'on' && (($_REQUEST['premium_user'] && $_REQUEST['premium_pass']) || ($premium_acc['depfile_com']['user'] && $premium_acc['depfile_com']['pass']))) {
			return $this->Premium();
		} else {
			return $this->Free();
		}
	}

	private function Premium() {
		html_error('Unsupported Now!');
	}

	private function Free() {
		if ($_REQUEST['step'] == '1') {
			$post = array();
			$post['vvcid'] = $_POST['vvcid'];
			$post['verifycode'] = $_POST['captcha'];
			$post['FREE'] = $_POST['FREE'];
			$this->link = urldecode($_POST['link']);
			$this->cookie = StrToCookies(urldecode($_POST['cookie']));
			$page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
		} else {
			$data = $this->DefaultParamArr($this->link, $this->cookie);
			$data['step'] = '1';
			$data['vvcid'] = cut_str($this->page, '<input type="hidden" name="vvcid" value="', '"');
			$data['FREE'] = cut_str($this->page, '<input type="submit" name="FREE" value="', '"');
			
			//Download Captcha Image
			if (!preg_match('/\/includes\/vvc\.php\?vvcid=[^"]+/', $this->page, $imgurl)) html_error('Error[Captcha Link not found!]');
            $cap = $this->GetPage('http://depfile.com'.$imgurl[0], $this->cookie);
            $capt_img = substr($cap, strpos($cap, "\r\n\r\n") + 4);
            $imgfile = DOWNLOAD_DIR . "depfile_captcha.jpg";

            if (file_exists($imgfile)) unlink($imgfile);
            if (empty($capt_img) || !write_file($imgfile, $capt_img)) html_error("Error getting CAPTCHA image.", 0);
			
			$this->EnterCaptcha($imgfile, $data, 20);
			exit;
		}
		is_present($page, cut_str($page, '<p class=\'notice\'>', '</p>'));
		if (!preg_match('/var sec=(\d+);/', $page, $w)) html_error('Error[Timer not found!]');
		$this->CountDown($w[1]);
		$dlink = urldecode(cut_str($page, "document.getElementById(\"wait_input\").value= unescape('", "'"));
		if (empty ($dlink)) html_error('Error[Download Link - FREE not found!]');
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $this->cookie, 0, $this->link);
		exit;
	}

}

/*
 * Written by Tony Fauzi Wihana/Ruud v.Tony 2-1-2013
 */
?>
