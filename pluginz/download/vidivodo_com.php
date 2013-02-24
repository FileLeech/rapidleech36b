<?php
if (!defined('RAPIDLEECH')) {
	require_once ("index.html");
	exit();
}

class vidivodo_com extends DownloadClass {

	public function Download($link) {

		$cookie = array('langfil' => 'en');
		$page = $this->GetPage($link, $cookie);
		$cookie = GetCookiesArr($page, $cookie);
		// check redirect
		$rdc = 0;
		while (($redir = $this->ChkRedir($page)) && $rdc < 5) {
			$page = $this->GetPage($redir, $cookie);
			$cookie = GetCookiesArr($page, $cookie);
			$rdc++;
		}
		is_present($page, 'The video you have requested is not available');
		if (!preg_match('/mediaid:\'([^\']+)\'/', $page, $mid)) html_error('Error[Media id not found!]');
		$page = $this->GetPage("http://en.vidivodo.com/player/getxml?mediaid={$mid[1]}&publisherid=vidivodo&type=", $cookie, 0, 'http://en.vidivodo.com/swf/player/MediaPlayer.swf');
		$cookie = GetCookiesArr($page, $cookie);
		if (!preg_match('/https?:\/\/ss\d+\.vidivodo\.com(:\d+)?\/vidivodo\/vidservers\/[^\r\n\]]+/i', $page, $dl)) html_error('Error[Download link not found!]');
		$dlink = trim($dl[0]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie);
	}

	private function ChkRedir($page, $rgpath = '/') {
		$hpos = strpos($page, "\r\n\r\n");
		$headers = empty($hpos) ? $page : substr($page, 0, $hpos);

		if (preg_match('@Location: ((https?://(?:[^/|\r|\n]+\.)?vidivodo\.com)?' . $rgpath . '[^\r|\n]*)@i', $headers, $redir)) $redir = (empty($redir[2])) ? 'http://www.vidivodo.com' . $redir[1] : $redir[1];

		return (empty($redir) ? false : $redir);
	}

}

/*
 * Written by Tony Fauzi Wihana/Ruud v.Tony 27/01/2013
 * simplified the redirect page code taken from Rapidgator.net download plugin by Th3-822
 */
?>
