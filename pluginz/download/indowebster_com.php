<?php
if (!defined('RAPIDLEECH')) {
	require_once ('index.html');
	exit();
}

class indowebster_com extends DownloadClass {
	
	public function Download($link) {
		$page = $this->GetPage($link);
		is_present($page, '404 Page Not Found', 'The file might have been deleted due to copyright infringement issue');
		$cookie = GetCookiesArr($page);
		if (!preg_match('/<a href="(http:\/\/[^\r\n"]+)" class="downloadBtn">/', $page, $chk)) html_error ('Error[Free link not found!]');
		$link = trim($chk[1]);
		$page = $this->GetPage($link, $cookie, 0, $link);
		if (!preg_match('/var s = (\d+);/', $page, $w)) html_error('Error[Timer not found!]');
		$this->CountDown($w[1]);
		$tlink = 'http://www.indowebster.com/ajax/downloads/gdl';
		$form = cut_str($page, "$.post('$tlink',{", "}");
		if (!preg_match_all("/([^:]+):'([^']+)',?/", $form, $match)) html_error('Error[Post Data FREE not found!]');
		$match = array_combine($match[1], $match[2]);
		$post = array();
		foreach ($match as $k => $v) {
			$post[$k] = $v;
		}
		$page = $this->GetPage($tlink, $cookie, $post, $link);
		$tlink = substr($page, strpos($page, "\r\n\r\n") + 4);
		if ($tlink == '') html_error('Error[Redirect Link FREE not found!]');
		$page = $this->GetPage($tlink, $cookie, 0, $link);
		if (!preg_match('/Location: (https?:\/\/[^\r\n]+)/i', $page, $dl)) html_error('Error[Download Link FREE not found!]');
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie, 0, $link);
		exit();
	}
}

/*
 * Written by Ruud v.Tony 04-07-2012
 */
?>
