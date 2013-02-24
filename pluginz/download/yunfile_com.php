<?php
if (!defined('RAPIDLEECH')) {
	require_once('index.html');
	exit;
}

class yunfile_com extends DownloadClass {
	
	public function Download($link) {
		$page = $this->GetPage($link);
		if (preg_match('/(\d+)<\/span> seconds/', $page, $wait)) $this->CountDown ($wait[1]);
		$free = 'http://yunfile.com'.cut_str($page, '<a id="downpage_link" href="', '"');
		$page = $this->GetPage($free, 0, 0, $link);
		$cookie = GetCookies($page);
		if (!preg_match('/setCookie\("(v\w+)", "(\w+)"/', $page, $ca)) html_error('Additional Cookie not found!');
		$cookie = $cookie . "; $ca[1]=$ca[2]";
		$form = cut_str($page, '<form class="tform" ', '</form>');
		$dlink = cut_str($form, 'action="', '" method="post"');
		$filename = basename(parse_url($dlink, PHP_URL_PATH));
		if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)" \/>/', $form, $match)) html_error('Error : Post Data not found!');
		$match = array_combine($match[1], $match[2]);
		$post = array();
		foreach ($match as $key => $value) {
			$post[$key] = $value;
		}
		$this->RedirectDownload($dlink, $filename, $cookie, $post, $link);
		exit();
	}
}

/*
 * by Ruud v.Tony 20-03-2012
 */
?>
