<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

$es = $_POST['bg']; 
if($es == "ok"){
	$post = array();
	$post["cap_id"] = $_POST["cap_id"];
	$post["cap_secret"] = $_POST["cap_secret"];
	$post["user_code"] = $_POST["captcha"];
	
	$cookie = $_POST["cookie"];
	$Referer = $_POST["link"];
	$Href = $_POST["flink"];
	$FileName = $_POST["name"];
	
	$Url = parse_url($Href);
	
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, $post, 0, $_GET["proxy"],$pauth);
	preg_match('/var check_n = ([0-9]*);/',$page,$count);
	preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
	$cookies = $temp[1];
	$cookie .= ';'.implode(';',$cookies);
	preg_match('/req\.open\("GET", "(.*)"/',$page,$turl);
	$turl = $turl[1];
	$turl = substr($turl,0,-6);
	$turl .= 'ifr?pr=1&zenc=';
	$countnum = $count[1];
	insert_timer($countnum, "Waiting link timelock");
	$Url['query'] = 'rs=getFileLink&rst=&rsrnd='.time().'&rsargs[]=yellow';
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, 0, 0, $_GET["proxy"],$pauth);
	$Url = parse_url($turl);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, 0, 0, $_GET["proxy"],$pauth);
	preg_match('/window\.setTimeout\("window.location.href = \'(.*)\';",1000\);/',$page,$temp);
	$Href = 'http://'.$Url['host'].$temp[1];
	$Referer = $turl;
	$temp = $LINK;
	$temp = str_replace('/file/','/en/file/',$temp);
	$temp = str_replace('.','\.',$temp);
	$temp = str_replace('/','\/',$temp);
	preg_match('/Thanks for downloading <a href="'.$temp.'">(.*)<\/a>/',$page,$FileName);
	$FileName = $FileName[1];
	$Url = parse_url($Href);
	
	//$FileName = !$FileName ? basename($Url["path"]) : $FileName;
	
	//$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
	
	insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&cookie=".urlencode($cookie)."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&method=POST&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
}else{
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	
	preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
	$cookie = $temp[1];
	$cookie = implode(';',$cookie);
	
	$Url['query'] = 'rs=refreshImage&rst=&rsrnd='.time();
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
		
	//preg_match('/action="(.+?)"/', $page, $flink);
	$flink = 'http://'.$Url['host'].str_replace('/file/','/cfile/',$Url['path']);
	
	$page = str_replace('\\','',$page);
	preg_match('/<img src="(.+?)"/', $page, $imglink);
	$img ='http://'.$Url["host"].$imglink[1];
	
	preg_match('/name=cap_id value=(.*)>/U', $page, $cap_id);
	preg_match('/name=cap_secret value=(.*)>/U', $page, $cap_secret);
	
	$Url = parse_url($img);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, 0, 0, $_GET["proxy"],$pauth);
	
	$headerend = strpos($page,"\r\n\r\n");
	$pass_img = substr($page,$headerend+10);
	write_file($download_dir."badongo_captcha.png", $pass_img);
	$randnum = rand(10000, 100000);
	
	$img_data = explode("\r\n\r\n", $page);
	$header_img = $img_data[0];

	print 	"<form method=\"post\" action=\"".$PHP_SELF.(isset($_GET["idx"]) ? "?idx=".$_GET["idx"] : "")."\">$nn";
	print	"<b>Please enter code:</b><br>$nn";
	print	"<img src=\"{$download_dir}badongo_captcha.png?id=".$randnum."\" >$nn";
	print	"<input name=\"link\" value=\"$LINK\" type=\"hidden\">$nn";
	print	"<input name=\"flink\" value=\"$flink\" type=\"hidden\">$nn";
	print	"<input type=hidden name=cap_id value=$cap_id[1]>$nn";
	print	"<input type=hidden name=cap_secret value=$cap_secret[1]>$nn";
	print	"<input name=\"bg\" value=\"ok\" type=\"hidden\">$nn";
	print	"<input name=\"cookie\" value=\"$cookie\" type=\"hidden\">$nn";
	print	"<input name=\"name\" value=\"$name[1]\" type=\"hidden\">$nn";
	print	"<input name=\"captcha\" type=\"text\" >";
	print	"<input name=\"Submit\" value=\"Submit\" type=\"submit\"></form>";
}
?>