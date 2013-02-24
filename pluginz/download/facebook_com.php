<?php
if (!defined('RAPIDLEECH')) {
	require_once('index.html');
	exit;
}

class facebook_com extends DownloadClass {
	public function Download($link) {
		if (!preg_match('@://(?:[^/]+\.)?facebook\.com/(?:(?:video/video)|(?:photo))\.php\?v=(\d+)@i', $link, $vid)) html_error('Video ID not found.');
		$page = $this->GetPage('http://www.facebook.com/video/video.php?v='.$vid[1]);
		is_present($page, '>This content is currently unavailable<', 'Video unavailable or deleted.');

		if (!preg_match('@"video_title"\s?,\s?"([^"]+)"@i', $page, $title)) html_error('Video title not found.');
		$badchars = '<>:"/\\|?*\'@#+~{}[]^';
		$title = str_replace(str_split($badchars), '_', urldecode(str_replace('\u0025', '%', trim($title[1]))));

		if (!preg_match('@"video_src"\s?,\s?"(http[^"]+)"@i', $page, $dl)) html_error('Download link not found.');
		//$dllink = urldecode(html_entity_decode(preg_replace('@\\u(\d{4})@i', '&#$1;', $dl[1])));
		$dllink = urldecode(str_replace('\u0025', '%', $dl[1]));

		$ext = strrchr($dllink, '.');if (empty($ext)) $ext = '.flv';
		$fname = $title . $ext;
		$this->RedirectDownload($dllink, $fname);
	}
}

//[25-10-2012] Written by Th3-822. (I won't reply "media-related" plugin requests by Mail/IM/PM, only at forum.)

?>