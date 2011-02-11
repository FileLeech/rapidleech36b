<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}


	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"] ,$pauth);
	is_page($page);

	if (!preg_match('#fmt_url_map=(.+?)&#', $page, $fmt_url_map)) html_error('Video link not found.');
	$fmt_url_maps = preg_split('%,%', urldecode($fmt_url_map[1]));
	$fmts = array(22,35,18,34,6,5,0,17,13);
	$yt_fmt = $_POST['yt_fmt'];

		if ($_POST['ytube_mp4'] == 'on')
		{
			foreach ($fmt_url_maps as $fmtlist)
			{
				$furlmap = preg_split('%\|%', $fmtlist);
				$fmturlmaps[$furlmap[0]] = $furlmap[1];
			}

			if ($yt_fmt == 'highest')
			{
				foreach ($fmts as $fmt)
				{
					if (in_array($fmt, array_keys($fmturlmaps)))
					{
						$furl = $fmturlmaps[$fmt];
						break;
					}
				}
			}
			else
			{
				if (!$furl = $fmturlmaps[$yt_fmt])
				{
					preg_match ('/"t": "([^\"]+)/', $page, $video_t);
					preg_match ('/"video_id": "([^\"]+)/', $page, $video_id);
					preg_match ('%var swfUrl = canPlayV9Swf\(\) \? "(.+)\.swf" :%U', $page, $refmatch);
					preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
					$cookie = implode(';',$temp[1]);
					$gurl = "http://www.youtube.com/get_video?video_id=" . $video_id [1] . "&t=" . $video_t [1] . "&el=detailpage&ps=&fmt=$yt_fmt";
					$Url = parse_url($gurl);
					$pagea = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $refmatch [1], $cookie, 0, 0, $_GET["proxy"] ,$pauth);
					is_page($pagea);

					if (! preg_match ('%ocation: (.+)\r\n%', $pagea, $durl)) html_error ('Specified video format not found');
					$furl = $durl[1];
				}
			}
		}
		else
		{
			foreach ($fmt_url_maps as $fmtlist)
			{
				$furlmap = preg_split('%\|%', $fmtlist);
				$fmturlmaps[] = $furlmap;
			}
			$fmt = $fmturlmaps[0][0];
			$furl = $fmturlmaps[0][1];
		}

		if (preg_match ('%0|5|6|34|35%', $yt_fmt)) $ext = '.flv';
		elseif (preg_match ('%18|22%', $yt_fmt)) $ext = '.mp4';
		elseif (preg_match ('%13|17%', $yt_fmt)) $ext = '.3gp';
		elseif (preg_match ('%highest%', $yt_fmt)) $ext = '.mp4';
		else $ext = '.flv';

		if (!preg_match('#<title>.*YouTube.*-(.*)</title>#Us', $page, $title)) html_error('No video title found! Download halted.');
		if (!$video_id) preg_match ('#video_id=(.+?)&#', $page, $video_id);

		$FileName = str_replace (Array ("\\", "/", ":", "*", "?", "\"", "<", ">", "|"), "_", html_entity_decode (trim($title[1]))) . (isset ($_POST ['yt_fmt']) && $_POST ['yt_fmt'] !== 'highest' ? '-[' . $video_id[1] . '][f' . $_POST ['yt_fmt'] . ']' : '-[' . $video_id[1] . '][f' . $fmt . ']') . $ext;

		if ($_POST ['ytdirect'] == 'on')
		{
			echo "<br /><br /><h4><a style='color:yellow' href='" . urldecode($furl) . "'>Click here or copy the link to your download manager to download</a></h4>";
			echo "<input name='dlurl' style='width: 1000px; border: 1px solid #55AAFF; background-color: #FFFFFF; padding:3px' value='" . urldecode($furl) . "' onclick='javascript:this.select();' readonly></input>";
		}
		else
		{
			$Referer = $refmatch [1];			
			$force_name = $FileName;
			$Url = parse_url($furl);
insert_location("$PHP_SELF?filename=".urlencode($FileName)."&force_name=".urlencode($force_name)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($refmatch [1])."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : ""));

		}


?>