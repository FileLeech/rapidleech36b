<?php
if (!defined('RAPIDLEECH')) {
	require_once 'index.html';
	exit;
}

class filenuke_com extends DownloadClass {

	public function Download($link) {
		$cookie = 'lang=english';
		$page = $this->GetPage($link, $cookie);
		is_present($page, 'The file you were looking for could not be found, sorry for any inconvenience.');
		$form = cut_str($page, '<form method="POST" action=\'\'>', '</form>');
		if (!preg_match_all('/type="hidden" name="([^"]+)" value="([^"]+)?"/', $form, $one) || !preg_match_all('/type="submit" name="(\w+_free)" .* value="([^"]+)"/', $form, $two)) html_error('Error[Form Post Data 1 not found!]');
		$match = array_merge(array_combine($one[1], $one[2]), array_combine($two[1], $two[2]));
		$post = array();
		foreach ($match as $key => $value) {
			$post[$key] = $value;
		}
		$page = $this->GetPage($link, $cookie, $post, $link);
		$form = cut_str($page, '<Form name="F1" method="POST" action=""', '</Form>');
		if (!preg_match_all('/type="hidden" name="([^"]+)" value="([^"]+)?"/', $form, $match)) html_error('Error[Post Data 2 not found!]');
		$match = array_combine($match[1], $match[2]);
		$post = array();
		foreach ($match as $key => $value) {
			$post[$key] = $value;
		}
		$page = $this->GetPage($link, $cookie, $post, $link);
		if (!preg_match('/Location: (https?:\/\/[^\r\n]+)/i', $page, $dl)) html_error('Error[Download Link not found!]');
		$dlink = trim($dl[1]);
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		$this->RedirectDownload($dlink, $filename, $cookie);
	}
}

/*
 * Written by Tony Fauzi Wihana/Ruud v.Tony 26/02/2013
 */
?>
