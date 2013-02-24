<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

class vidbux_com extends DownloadClass {

	public function Download($link) {

		$this->cookie = array('lang' => 'english');
		$this->link = $link;
		if ($_REQUEST['step'] == '1') {
			return $this->PostCaptcha();
		} else {
			return $this->RetrieveCaptcha();
		}
	}

	private function PostCaptcha() {

		$post = array();
		foreach ($_POST['post'] as $k => $v) {
			$post[$k] = $v;
		}
		// post the captcha to get to get the "adcopy_challenge" value
		$imgurl = urldecode($_POST['imgurl']);
		$cookieapi = urldecode($_POST['cookieapi']);
		$postcap = array();
		foreach ($_POST['postcap'] as $k => $v) {
			$postcap[$k] = $v;
		}
		$postcap['adcopy_response'] = $_POST['captcha'];
		$url = 'http://api.solvemedia.com/papi/verify.noscript';
		$check = $this->GetPage($url, $cookieapi, $postcap, $imgurl);
		if (!preg_match('@(https?://[^/|\'|\"|\<|\>|\r|\n]+)?/papi/verify\.pass\.noscript\?[^/|\'|\"|\<|\>|\r|\n]+@i', $check, $resp)) {
			echo ("<center><font color='red'><b>Wrong Captcha!</b></font></center>");
			return $this->RetrieveCaptcha();
		}
		$resp = (empty($resp[1])) ? 'http://api.solvemedia.com' . $resp[0] : $resp[0];
		$check = $this->GetPage($resp, $cookieapi, 0, $url);
		if (!preg_match('@>[\s|\t|\r|\n]*([^<|>|\r|\n]+)[\s|\t|\r|\n]*</textarea>@i', $check, $gibberish)) html_error('CAPTCHA response not found.');
		// we have passed the required step to get "adcopy_challenge" value for post to the link
		$post['adcopy_challenge'] = urlencode($gibberish[1]);
		$post['adcopy_response'] = 'manual_challenge';
		$page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
		if (!preg_match('@eval\s*\(\s*function\s*\(p,a,c,k,e,d\)\s*\{[^\}]+\}\s*\(\s*\'([^\r|\n]*)\'\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*\'([^\']+)\'\.split\([\'|\"](.)[\'|\"]\)\)\)@', $page, $js)) html_error('Error: Embed code not found.');
		$embed = $this->JSun_packer($js[1], $js[2], $js[3], $js[4], $js[5]);
		$dlink = cut_str($embed, "file\',\'", "\'");
		$filename = cut_str($page, 'File Name:</b> ', '</nobr>');
		if (empty($dlink) || empty($filename)) html_error("Error[Download link : {$dlink} or Filename : {$filename} - FREE not found]");
		$this->RedirectDownload($dlink, $filename, $this->cookie, 0, $this->link, $filename);
	}

	private function RetrieveCaptcha() {

		$page = $this->GetPage($this->link, $this->cookie);
		is_present($page, '/404.html', 'No such file or the file has been removed due to copyright infringement issues.');
		$form = cut_str($page, '<form id="captchaForm"', '</form>');
		if (!preg_match('/https?:\/\/api\.solvemedia\.com\/papi\/challenge\.noscript\?k=[^\r\n"]+/i', $form, $c)) html_error('Error[Captcha Data not found!]');
		$challenge = $this->GetPage($c[0], 0, 0, $this->link);
		$cookieapi = GetCookiesArr($challenge);
		if (!preg_match_all('/<input type=hidden name="([^"]+)"( id="([^"]+)")? value="([^"]+)"?/', $challenge, $tmp)) html_error('Error[Post Data Captcha not found!]');
		$tmp = array_combine($tmp[1], $tmp[4]);
		if (!preg_match('@<img [^/|<|>]*src\s?=\s?\"(/papi/media\?c=[^\"|<|>]+)\"@i', $challenge, $ch)) html_error('CAPTCHA img not found.');
		$imgurl = 'http://api.solvemedia.com' . $ch[1];
		$cap = $this->GetPage($imgurl, $cookieapi);
		$capt_img = substr($cap, strpos($cap, "\r\n\r\n") + 4);
		$imgfile = DOWNLOAD_DIR . 'vidbux_captcha.gif';

		if (file_exists($imgfile)) unlink($imgfile);
		if (!write_file($imgfile, $capt_img)) html_error('Error getting CAPTCHA image.');

		if (!preg_match_all('/<input name="([^"]+)" type="hidden" value="([^"]+)?"\/?>/', $form, $match)) html_error('Error[Post Data - FREE not found!]');
		$match = array_combine($match[1], $match[2]);

		$data = $this->DefaultParamArr($this->link, $this->cookie);
		foreach ($match as $k => $v) {
			$data["post[$k]"] = $v;
		}
		foreach ($tmp as $k => $v) {
			$data["postcap[$k]"] = $v;
		}
		$data['imgurl'] = urlencode($imgurl);
		$data['cookieapi'] = urlencode(CookiesToStr($cookieapi));
		$data['step'] = '1';
		$this->EnterCaptcha($imgfile . '?' . time(), $data, 20);
		exit;
	}

	private function JSun_packer($p, $a, $c, $k, $er) {
		$k = explode($er, $k);
		while ($c--) if ($k[$c]) $p = preg_replace('@\b' . base_convert($c, 10, $a) . '\b@', $k[$c], $p);
		return $p;
	}
	
	public function CheckBack($header) {
		is_notpresent($header, 'ontent-Disposition: attachment', 'Error[Your IP might be blocked that prevent from downloading!]');
	}

}

/*
 * Written by Tony Fauzi Wihana/Ruud v.Tony with help from Th3-822, deobfuscated javascript function taken from youwatch.org download plugin
 */
?>
