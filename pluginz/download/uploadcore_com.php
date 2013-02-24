<?php

/* Written by dumpweed 3-10-2012
 * Fixed regexp 1-23-2013
 * Do check if file is exist or not, do check the post value (I always missed that too, ask Th3-822),
 * do check the regex is it match or not with textarea($var_dlink) or html_error($dlink)
 */

if (!defined('RAPIDLEECH')) {
	require_once ("index.html");
	exit();
}

class uploadcore_com extends DownloadClass {

	public function Download($link) {
		$page = $this->GetPage($link);
		is_present($page, 'File Not Found');
		$cookie = GetCookiesArr($page);
		if (preg_match('#Location: (https?://\w+\.uploadcore\.com/[^\r\n\t\s]+)#i', $page, $rd)) {
			$link = trim($rd[1]);
			$page = $this->GetPage($link, $cookie);
			$cookie = GetCookiesArr($page, $cookie);
		}

		$id = cut_str($page, 'name="id" value="', '"');
		$FileName = cut_str($page, 'name="fname" value="', '"');
		$referer = cut_str($page, 'name="referer" value="', '"');

		$post = array();
		$post['op'] = "download1";
		$post['usr_login'] = "";
		$post['id'] = $id;
		$post['fname'] = $FileName;
		$post['referer'] = $referer;
		$post['method_free'] = " ";
		$page = $this->GetPage($link, $cookie, $post, $link);

		$rand = cut_str($page, 'name="rand" value="', '"');
		unset($post);
		$post['op'] = "download2";
		$post['id'] = $id;
		$post['rand'] = $rand;
		$post['referer'] = $referer;
		$post['method_free'] = " ";
		$post['method_premium'] = "";
		$post['down_direct'] = "1";
		$page = $this->GetPage($link, $cookie, $post, $link);

		if (!preg_match("#(http://\w+\d\.uploadcore\.net(:\d+)?/d/[^\r\n\s\t'\"]+)#", $page, $dl)) {
			html_error("Error, download link not found!");
		}
		$dlink = trim($dl[1]);
		$Url = parse_url($dlink);
		if (!$FileName) $FileName = basename($Url['path']);
		$this->RedirectDownload($dlink, $FileName, $cookie, 0, $link);
		exit();
	}

}

?>