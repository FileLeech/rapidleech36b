<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

if ($_GET ["step"] != "second") {

  $page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
  is_page($page);
	is_present($page,"filename mismatched or file does not exist", "FileId and filename mismatched or file does not exist!");
	is_present($page,"You are trying to download file larger than", "Only premium members may download file larger than 400MB.");
	is_present($page,"existing download session", "You have an existing download session.<br>As a free member you can download only 1 file at a time.");

	preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
	$cookie = implode(';',$temp[1]);
	$cookie = urlencode($cookie);
	$wait = cut_str($page,"countdown({seconds: ",",");

        echo "<center>$nn";
        echo "<form method=\"post\" action=\"$PHP_SELF\">$nn";
        echo "<input type=hidden name=step value=second>\n";
        echo "<input type=hidden name=wait value=$wait>\n";
        echo "<b>Please enter code:</b><br>$nn";
        echo "<img src=\"http://azsharing.com/captchas/" . rand() . "\" ><br>$nn";
        echo "<input name=\"link\" value=\"$LINK\" type=\"hidden\">$nn";
        echo "<input name=\"referer\" value=\"$Referer\" type=\"hidden\">$nn";
        echo "<input name=\"cookie\" value=\"$cookie\" type=\"hidden\">$nn";
        echo "<input name=\"captcha\" type=\"text\" >";
        echo "<input name=\"Submit\" value=\"Submit\" type=\"submit\"></form></center>";

}else{

	$post = array();
	$post['ugfCaptchaKey'] = $_POST['captcha'];
	$post["referer"] = $_POST["referer"];
	$wait = $_POST["wait"];
	$cookie = urldecode($_POST["cookie"]);
	$lica = "http://azsharing.com/captcha?key=#".$post['ugfCaptchaKey']."";

	$Url = parse_url($lica);
	$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, array(captchacode=>$_POST['captchacode']), 0, $_GET["proxy"],$pauth);
	is_page($page);

	is_present($page,'Session Expired!');
	is_present($page,'invalid key');
	insert_timer($wait, "Getting link.");

	$Href ="http://azsharing.com/file/get-file";
	$Url = parse_url($Href);
	$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, array(captchacode=>$_POST['captchacode']), 0, $_GET["proxy"],$pauth);
	is_page($page);
	preg_match('/http:\/\/.+azsharing\.com\/d\/[^\'"]+/i', $page, $down);   
	$Url=parse_url($down[0]);



	$FileName = basename($Url["path"]);

insert_location("$PHP_SELF?cookie=".urlencode($cookie)."&filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));

}
?>