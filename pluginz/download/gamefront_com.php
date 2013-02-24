<?php
if (!defined('RAPIDLEECH')) {
	require_once('index.html');
	exit();
}

class gamefront_com extends DownloadClass {
	
	public function Download($link) {
		$page = $this->GetPage($link);
		$cookie = GetCookies($page);
		if (!preg_match('/<a href="(http:\/\/[^\r\n"]+)" class="downloadNow" id="downloadLink">/', $page, $tl)) html_error('Error[Redirect Link not found!]');
		$tlink = trim($tl[1]);
		$page = $this->GetPage($tlink, $cookie, 0, $link);
		if (!preg_match("/var downloadUrl = '(https?:\/\/[^\r\n']+)';/", $page, $dl)) html_error('Error[Download Link not found!]');
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie, 0, $link);
		exit();
	}
}

/*
 * by Ruud v.Tony 08-05-2012
 */
?>
