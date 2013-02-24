<?php
if (!defined('RAPIDLEECH')) {
	require_once('index.html');
	exit();
}

class dbank_com extends DownloadClass {
	
	public function Download($link) {
		$page = $this->GetPage($link);
		if (!preg_match('@downloadUrl="(https?:\/\/[^\r\n"]+)"@i', $page, $rd)) html_error('Error[REDIRECT LINK 1 NOT FOUND]!!!');
		$page = $this->GetPage($rd[1], 0, 0, $link);
		if (!preg_match('@Location: (https?:\/\/[^\r\n]+)@i', $page, $dl)) html_error('Error[REDIRECT LINK 2 NOT FOUND!]');
		$page = $this->GetPage($rd[1], 0, 0, $link);
		if (!preg_match('@Location: (https?:\/\/[^\r\n]+)@i', $page, $dl)) html_error('Error[DOWNLOAD LINK NOT FOUND!]');
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, 0, 0, $link);
		exit();
	}
}

/*
 * by Ruud v.Tony 15-03-2012
 */
?>
