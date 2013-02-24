<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

class slutload_com extends DownloadClass {

	public function Download($link) {
		$page = $this->GetPage($link);
		$cookie = GetCookies($page);
		if (!$dlink = cut_str($page, 'name="FlashVars" value="flv=', '&seekparam')) html_error('Error[Download link not found!]');
		if (!$filename = cut_str($page, '<title>', '</title>')) html_error('Error[Filename not found!]');
		$dlink = urldecode($dlink);
		$filename = str_replace(' ', '_', $filename);
		$this->RedirectDownload($dlink, $filename, $cookie, $link, $filename);
	}
}

/*
 * Written by Tony Fauzi Wihana/Ruud v.Tony 13-02-2013 :|
 */
?>
