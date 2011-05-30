<?php
define('RAPIDLEECH', 'yes');
define('IMAGE_DIR', 'misc/');
define('CLASS_DIR', 'classes/');
require_once(CLASS_DIR."other.php");

// Initiate timer counter
  error_reporting(1);
  
  $fgc = 1; // fgc=1 = file_get_contents mode on instead of CURL
  $maxlinks = 300;
	
  function curl($link, $post='0'){
	 global $fgc;
	 if($fgc==1) {
		$page = file_get_contents($link);
	 }
	 else {
		$ch = curl_init($link);
		curl_setopt($ch, CURLOPT_HEADER, 0);
	    if(preg_match("/megashares\.com/i" , $link))
		{
            curl_setopt($ch, CURLOPT_COOKIE, 1);
		    curl_setopt($ch, CURLOPT_COOKIEJAR, "1");
		    curl_setopt($ch, CURLOPT_COOKIEFILE, "1");
		}
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if($post != '0') 
		{

			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		}
		$page = curl_exec($ch);
		curl_close($ch);
		}
	 return($page);
  } //--end curl

  function check($link, $x, $regex, $szpatern='', $pattern='', $replace='') {
		if(!empty($pattern)) 
		{	$link = preg_replace($pattern, $replace, $link);	}
		$page = curl($link);
		$link = htmlentities($link, ENT_QUOTES);
		flush();
		ob_flush();
		
		
		//$fsize=GetBetween($page,$pattern);
		if(!empty($szpatern)){
		  // already containing / and /
		 preg_match("{$szpatern}", $page, $fsize);
		 if(!$fsize){$fsize='';}
		}
		

		$picEror = "<img width='13' alt='error link' src='".IMAGE_DIR."chk_error.png'>";
		$picGood = "<img width='13' alt='good link' src='".IMAGE_DIR."chk_good.png'>";
		
		
		
		if($_POST['d'] && preg_match("/$regex/i", $page)) {
			echo "<span class=\"g\">".$picGood."&nbsp;(".trim($fsize[1]).")</span>\n";  
		} elseif($_POST['d'] && preg_match("/The file you are trying to access is temporarily unavailable./i", $page)) {
			echo "<span class=\"y\">$picEror</span>\n";
		} elseif($_POST['d'] && !preg_match("/$regex/i", $page)) {
			echo "<span class=\"r\">$picGood&nbsp;(".trim($fsize[1]).")</span>\n";			
		} elseif(!$_POST['d'] && preg_match("/$regex/i", $page)) {
			echo "<span class=\"g\">$picGood&nbsp;(".trim($fsize[1]).")</span>\n";  
		} elseif(!$_POST['d'] && preg_match("/The file you are trying to access is temporarily unavailable./i", $page)) {
			echo "<span class=\"y\">$picEror</span>\n";
		} else {
			echo "<span class=\"r\">$picEror</span>\n";
		}
	} //-- end check
	
function getParam($key, $url){
 $ada = strstr($url, $key);
 if($ada){
  $mulai = strlen($key);
  if(substr($key, -1, -1) != "="){$mulai++;}
  $ret = substr($ada, $mulai, (strpos($ada,';')?strpos($ada,';'):strlen($ada))-$mulai);
 }else{  
  $ret = '';
 }
 return $ret;
}	
	
//=====
// MAIN
//=====
  if (trim($_POST['url'])!=''){
    $audl_sect = false;
	$buflinks = $_POST['url'];
	if(getParam('|_curl', $buflinks)=='on'){
		$fgc=0; // use cURL mode ON
	}
	//locate section 
	if(getParam('|_section', $buflinks)=='audl'){
	 $audl_sect = true;
	}	
    if(!$audl_sect){
     $time = explode (' ', microtime());
     $time = $time[1] + $time[0];
     $begintime = $time;
    }
	$valLink = getParam('|_url', $buflinks);
	$buflinks = urlcleaner(utf8_strrev(base64_decode($valLink)));
	$alllinks = array();
	$alllinks = explode(" ", $buflinks);
	$alllinks = implode(";", $buflinks);
	$alllinks = explode(";", trim($buflinks));	
	//$alllinks = implode("\n", $buflinks);
	
	$l = 1;
	$x = 1;
	
	//$alllinks = array_unique($alllinks); //removes duplicates
	if(!count($alllinks))
	{
	  die('<p><br><span style="color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00"><b>Not LINK</b></span><br>');
	}
	echo "<result>";
	foreach($alllinks as $link) {
		if (empty($link)) continue;
	    $link = trim(str_replace(" ","",$link));
		if(preg_match("/^(http)\:\/\/(www\.)?anonym\.to\/\?/i", $link)){
			$link = explode("?", $link);
			unset($link[0]);
			$link = implode($link, "?");
			  if($kl == 1)
			  echo"<div class=\"n\"><a href=\"$link\"><b>$link</b></a></div>\n";
			  flush();
		}
		
	   if(preg_match("/^(http)\:\/\/(www\.)?lix\.in\//i", $link)){
		  $post = 'tiny='.trim(substr(strstr($link, 'n/'), 2)).'&submit=continue';
		  preg_match('@name="ifram" src="(.+?)"@i', curl($link, $post), $match);
		  $link = $match[1];
			if($kl == 1)
			echo"<div class=\"n\"><a href=\"$link\"><b>$link</b></a></div>\n";
			flush();
	   }
		


		if(preg_match("/^(http)\:\/\/(www\.)?linkbucks\.com\/link\//i" , $link)) {
		   $page = curl($link);
		   preg_match("/<a href=\"(.+)\" id=\"aSkipLink\">/" , $page , $match);
		   $link = $match[1];
			 if($kl == 1)
			 echo"<div class=\"n\"><a href=\"$link\"><b>$link</b></a></div>\n";
			 flush();
		}
	
		 if(preg_match("/usercash\.com/i" , $link)) {
			$page = curl($link);
			preg_match("/<TITLE>(.+)<\/TITLE>/" , $page , $match);
			$link = $match[1];
			  if($kl == 1)
			  echo"<div class=\"n\"><a href=\"$link\"><b>$link</b></a></div>\n";
			  flush();
		 }  
		 
		if(preg_match("/rapidshare\.com\/users\//i" , $link)) {
		  $page = curl($link);
		  preg_match_all("/<a href=\"(.+)\" target=\"_blank\">/" , $page , $match);
		  unset($match[1][0]);
			foreach($match[1] as $link)
			 {
				if($l == 1)
			   {
				  check(trim($link), $x, "You would like to download the following file::" );
				  $x++;
			   }
			if($kl == 1)
			 echo"<div class=\"n\"><a href=\"$link\"><b>$link</b></a></div>\n";
			 flush();
			   }
	   }
	
	if($l == 1) {
		$sites = 
		array(
			 array("rapidshare\.com\/files\/", 'class="downloadlink"', "/>\|\s(.*)</"),
			 array("megaupload\.com/([a-z]{2}\/)?\?d=", "(Filename:)|(All download slots assigned to your country)", '/File size:<\/font>\s<font style="font-family:arial;\scolor:#000000; font-size:13px;">(.*)</'),
			 array("megashares\.com\/\?d01=", "Click here to download", "/size:\s(.*)</"),
			 //array("hotfile\.com\/dl\/", "Downloading", '/class="size">\|\s(.*)<\/span>/'),
			 array("hotfile\.com\/dl\/", "Downloading", '/Downloading:(?:[^\|]+)\|<\/span>\s<strong>(.*)<\/strong/'),
			array("2shared\.com\/file\/", "Download", '/File\ssize:<\/span>\s(.*)\s&nbsp;/'),
			 array("4shared\.com\/file\/", "Download Now", '/Size:</b>.*\">(.*)/s'),
			 array("filefactory\.com\/file\/", "(download link)|(Please try again later)", '/\t<span>(.*)\sfile/'),
			array("rapidshare\.de\/files\/", "You want to download"),
			 array("mediafire\.com\/\?", "download_file_title", '/sharedtabsfileinfo1-fs.+?([0-9.]+\s\w+)">/'),
			 array("www.mediafire\.com\/file\/", "You requested"),
			 array("netload\.in\/datei[0-9a-zA-Z]{10}\/", "File download", '/<span\sstyle\="color\:\s\#8d8d8d\;">\,(.*)<\/span>/'),
			 array("depositfiles\.com\/([a-z]{2}\/)?files\/", "File Name", '/File\ssize:\s<b>(.*)<\/b>/', "@(com\/files\/)|(com\/[a-z]{2}\/files\/)@i", "com/en/files/"),
			 array("sendspace\.com\/file\/", "The download link is located below.", '/Size:<\/b>\s(.*)\s/'),
			 array("usaupload\.net\/d\/", "This is the download page for file", '/File\ssize:<\/strong>\s(.*)</'),
			 array("uploading\.com\/files\/", "File size", '/size:\s<b>(.*)<\/b>/'),
			 array("savefile\.com\/files\/", "link to this file", '/filesize: (.*)</'),
			 array("axifile\.com\/?", "You have request", '/You\shave\srequest\s.*file\s\((.*)\)/', "@com\?@i", "com/?"),
			array("cocoshare\.cc\/[0-9]+\/", "Filesize:"),
			array("badongo\.com\/([a-z]{2}\/)?(file)|(vid)\/", "fileBoxMenu"),
			array("ifile\.it\/", "Request Download Ticket", ''),
			 array("(d\.turboupload\.com\/)|(turboupload.com\/download\/)", "(Please wait while we prepare your file.)|(You have requested the file)", '/You\shave\srequested\s.*>\s\((.*)\)/'),
			array("files\.to\/get\/", "You requested the following file"),
			array("gigasize\.com\/get\.php\?d=", "Downloaded", '/Size:\s<span>(.*)</'),
			 array("ziddu\.com\/", "Download Link",'/File\sSize\s:.*normal12black">(.*)\s</s'),
			 array("zshare\.net\/(download|audio|video)\/", "Downloads:", '/File\sSize:.*">(.*)</'),
			 array("uploaded\.to\/(\?id=|file\/)", "Filename:", '/Filesize:.*<td>[\s](.*)\t<\//'),
			array("filefront\.com\/", "http://static4.filefront.com/ffv6/graphics/b_download_still.gif"),
	//array("uploadpalace\.com\/[a-zA-Z]{2}\/file\/[0-9]+\/", "Filename:"),
			 array("speedyshare\.com\/files\/[0-9]+\/", "File download", '/<div\sclass=result>File\ssize\s(.*),\suploaded/'),
			array("momupload\.com\/files\/", "You want to download the file"),
			 array("rnbload\.com\/file/" , "Filename:", '/Filesize:\s(.*)</'),
			array("ifolder\.ru\/[0-9]+", "ints_code"),
			array("adrive\.com\/public\/", "view"),			 
			 //array("easy-share\.com" , "Download", '/"sfamily2\sc">.*\((.*)\)</'),
			 array("easy-share\.com" , "Download", '/px18\stxtgray\sfamily2\sc">.*\s\((.*)\)</'),
			 array("bitroad\.net\/download\/[0-9a-z]+\/", "Download a file", '/content_text.*\[\s(.*)\]</'),
			 array("megarotic\.com/([a-z]{2}\/)?\?d=", "(Filename:)|(All download slots assigned to your country)", '/File\ssize:.*">(.*)</'),  
			array("egoshare\.com" , "You have requested"),
			array("flyupload\.flyupload.com\/get\?fid" , "Download Now"),
			array("megashare\.com\/[0-9]+", "Free"),
			array("kewlshare\.com\/dl\/", "File Name"),
			array("filebase\.to\/files\/", "Der Download wird vorbereitet..."),
			array("bigupload\.com\/files\/", "You have requested"),
			array("yousendit\.com\/download\/", "Download File"),
			array("uploadline\.com\/", "File Download Link Generated"),
  array("shareserver\.net\/files\/get\/", "Download Here"),
  array("getupload\.org\/en\/file\/", "File name"),
  array("uploadbox.com\/files\/", "File name"),
  array("vip-file\.com\/download\/", "File name"),
  array("enterupload\.com\/", "Download File"),
  array("share-now\.net\/files\/", "Download Now"),
  array("upit\.to\/file:", "Download"),                                
  array("netgull\.com\/?\?d=", "File name"),
  array("sms4file\.com\/download\/", "File Name"),
  array("bigshare\.eu\/(download\.php)?\?", "File name"),
  array("sharebee\.com\/", "Filename"),
  array("sharecash\.org\/(download\.php)?\?id=", "File Info"),
			array("speedshare\.org\/(download\.php)?\?id=", "Sie haben"),
			array("letitbit\.net\/download\/", "File::"),
			array("saveqube\.com\/getfile\/", "File size"),
			array("friendlyfiles\.net\/download\/", "Download a file"),
			array("qubefiles\.com\/?\?file=\/getfile\/", "File size"),
			array("uploadjockey\.com\/download\/", "Download file from the following hosts"),
			array("wikisend\.com\/download\/", "Download link"),
			array("ugotfile\.com\/file\/", "embed", "/<span\sstyle\=\"font\-size\:\s14px;\">(.*)<\/span/"),
			array("misterupload\.net\/en\/file\/", "File name:"),
  array("filesavr\.com\/","This file has been downloaded"),
			array("filedropper\.com\/", "File Details"),
  array("youshare\.com\/", "Download"),
  array("sharemobile\.ro\/file\.php?\?id=", "Filename"),
			array("file-rack\.com\/files\/", "Download File"),
			array("indowebster\.com\/", "Download Link", '/Size:<\/b>\s(.*)<\/div/'),
			array("storage\.to\/get\/", "Downloading:", '/Downloading:(?:[^\(]+)\((.*)\)/'),
			array("zippyshare\.com\/v\/", "You have requested", '/Size:(?:[^>]+)>(.*)<\/font/'),
			array("freakshare\.net\/files\/", "box_heading", '/box_heading.+\s-\s(.*)<\/h1/'),
			//<h1 class="box_heading" style="text-align:center;">
			);
		$LnkOccur = false;
		foreach($sites as $site) {
			if(preg_match("/{$site[0]}/i", $link)) {
				check(trim($link), $x, $site[1], $site[2], $site[3], $site[4]);
				$x++;
				$LnkOccur = true;
				break;
			}
		}

		if(!$LnkOccur && $link!=''){
		  echo "<span class=\"y\">&nbsp;<b><a alt='unknown link' title=$link>?</a></b>\n</span>";
		}
		if($x > $maxlinks) {
			echo "<p style=\"text-align:center\">Maximum No ($maxlinks) Of links have been reached.</p>";
			exit();
		}
	}// --end if
  } // --end foreach
  
  if(!$audl_sect){
  	$time = explode(" ", microtime());
	$time = $time[1] + $time[0];
	$endtime = $time;
	$totaltime = ($endtime - $begintime);	
	$x--;
	$plural = ($x == 1) ? "" : "s";
	($fgc == 0) ? $method = 'cURL' : $method = 'file_get_contents';
	echo "<timerequire> link".$plural." checked in ".$totaltime." seconds. Method (".(!$fgc?"cURL":"file_get_contents").")</timerequire>";
  }	
  echo "</result>";
}
?>