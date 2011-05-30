<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

if ($_GET["step"] != "1")
	{
	$LINK = preg_replace("/\.com\/[a-z]{2}\//", ".com/", $LINK);
	$Url = parse_url($LINK);
	}

$cookie="";
if ($_GET["step"] == "1")
	{
	$post["captchacode"] = $_GET["imagecode"];
	$post["captcha"] = $_GET["imagestring"];
	$post["megavar"] = $_GET["megavar"];
	
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
	is_page($page);
	
	is_present($page, "The file you are trying to access is temporarily unavailable");
	if (!stristr($page,"id=\"captchaform"))
		{
		$countDown = trim(cut_str($page, "count=", ";"));
		$countDown = (!is_numeric($countDown) ? 26 : $countDown);
		
		$Href = cut_str($page, 'downloadlink"><a href="', '"');
		$Url = parse_url($Href);
		if (!is_array($Url))
			{
			html_error("Download link not found", 0);
			}
		
		insert_timer($countDown, "The file is being prepared.","",true);
		
		$FileName = !$FileName ? basename($Url["path"]) : $FileName;
		
		insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
		exit();
		}
	}
else
	{
	
	if ($Url["host"] == "www.megarotic.com")
		{
		$Url["host"] = "www.megaporn.com";
		$LINK = str_replace("megarotic.com", "megaporn.com", $LINK);
		}
	
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookie, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);
	
	if (stristr($page, "Location:"))
		{
		$Referer = $LINK;
		$Href = trim(cut_str($page, "ocation:", "\n"));
		$Url = parse_url($Href);
		
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
		is_page($page);
		
		is_present($page, "All download slots assigned to your country", "All download slots assigned to your country are currently in use");
		
		if (!stristr($page, "gencap.php?"))
			{
			print "An error occured, see details below:<br>".$nn.str_replace("<HEAD>", "<HEAD>$nn<base href=\"http://www.megaupload.com\">", $page);
			exit;
			}
		$LINK=$Href;
		}
	
	is_present($page, 'The file you are trying to access is temporarily unavailable');
	is_present($page, 'the link you have clicked is not available', 'Invalid link');
	is_present($page, 'This file has expired due to inactivity');
	
	if (!stristr($page,"id=\"captchaform")) html_error("Image code not found", 0);
	}

$Href = $LINK;
$Referer = $LINK;
$page = cut_str($page,'id="captchaform">','</FORM>');
$imagecode = cut_str($page,'captchacode" value="','"');
$megavar = cut_str($page, '<input type="hidden" name="megavar" value="', '">');

$access_image_url = cut_str($page,'img src="','"');

print "<form name=\"dl\" action=\"".$PHP_SELF.(isset($_GET["idx"]) ? "?idx=".$_GET["idx"] : "")."\">\n";
print "<input type=\"hidden\" name=\"link\" value=\"".urlencode($Href)."\">\n<input type=\"hidden\" name=\"referer\" value=\"".urlencode($Referer)."\">\n<input type=\"hidden\" name=\"fileid\" value=\"$fid\">\n<input type=\"hidden\" name=\"imagecode\" value=\"$imagecode\">\n<input type=\"hidden\" name=\"megavar\" value=\"$megavar\">\n<input type=\"hidden\" name=\"step\" value=\"1\">\n";
print "<input type=\"hidden\" name=\"comment\" id=\"comment\" value=\"".$_GET["comment"]."\">\n<input type=\"hidden\" name=\"email\" id=\"email\" value=\"".$_GET["email"]."\">\n<input type=\"hidden\" name=\"partSize\" id=\"partSize\" value=\"".$_GET["partSize"]."\">\n<input type=\"hidden\" name=\"method\" id=\"method\" value=\"".$_GET["method"]."\">\n";
print "<input type=\"hidden\" name=\"proxy\" id=\"proxy\" value=\"".$_GET["proxy"]."\">\n<input type=\"hidden\" name=\"proxyuser\" id=\"proxyuser\" value=\"".$_GET["proxyuser"]."\">\n<input type=\"hidden\" name=\"proxypass\" id=\"proxypass\" value=\"".$_GET["proxypass"]."\">\n<input type=\"hidden\" name=\"path\" id=\"path\" value=\"".$_GET["path"]."\">\n";
print "<h4>Enter <img src=\"$access_image_url\"> here: <input type=\"text\" name=\"imagestring\" size=\"3\">&nbsp;&nbsp;<input type=\"submit\" onclick=\"return check()\" value=\"Download File\"></h4>\n";
print "<script language=\"JavaScript\">".$nn."function check() {".$nn."var imagecode=document.dl.imagestring.value;".$nn.'if (imagecode == "") { window.alert("You didn\'t enter the image verification code"); return false; }'.$nn.'else { return true; }'.$nn.'}'.$nn.'</script>'.$nn;
print "</form>\n</body>\n</html>";


?>