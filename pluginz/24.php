<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}


if (($_GET ["premium_acc"] == "on" && $_GET ["premium_user"] && $_GET ["premium_pass"]) || ($_GET ["premium_acc"] == "on" && $premium_acc ["cramit_in"] ["user"] && $premium_acc ["cramit_in"] ["pass"])) {




echo "<center>..............</center>";





}else{

if ($_GET ["step"] != "second") {

		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookie, 0, 0, $_GET["proxy"],$pauth);
		is_page($page);

		$err = cut_str($page,'class="err">','</');
		if($err){html_error("$err",0);}
		is_present($page,"<Title>File Not Found</Title>", "File Not Found", 0);

		$id = cut_str($page,'name="id" value="','"');
		$fname = cut_str($page,'name="fname" value="','"');
		$referer = cut_str($page,'name="referer" value="','"');

		$post=array();
		$post['op'] = 'download1';
		$post['usr_login'] = '';
		$post['id'] = $id;
		$post['fname'] = $fname;
		$post['referer'] = '';
		$post['method_free'] = 'Free Download';

		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, 0, $post, 0, $_GET["proxy"],$pauth);
		is_page($page);

		$err = cut_str($page,'class="err">','</');
		if($err){html_error("$err",0);}	

	preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
	$cookie = implode(';',$temp[1]);
		$rand = cut_str($page,'name="rand" value="','"');
		$referer = cut_str($page,'name="referer" value="','"');
		$down_direct = cut_str($page,'own_direct" value="','"');
		
		unset($post);
		$post['op'] = 'download2';
		$post['id'] = $id;
		$post['rand'] = $rand;
		$post['referer'] = $referer;
		$post['method_free'] = 'Free Download';
		$post['method_premium'] = '';
		$post['down_direct'] = '1';
		$countdown = cut_str($page,'<span id="countdown">','</span>');

	$info = cut_str($page,'<tr><td align=right><b>Filename:','</small>');
	echo "<center>";
	echo "<table cellpadding=0 cellspacing=3 width=360>";
	echo "<tr><td align=right><b>Filename:$info</small></td></tr></table>";
	echo "</center>";
	$k = trim(cut_str($page, 'challenge?k=','"'));
	if($k){
$arcapt = apireca($k, $download_dir, $cookie, "cramit");
$post['recaptcha_challenge_field'] = $arcapt[rcf];

	echo "<center>".$nn;
	echo "<form name=\"F1\" method=\"post\" action=\"".$PHP_SELF.(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "")."\">".$nn;
	echo "<input type=\"hidden\" name=\"step\" value=\"second\">".$nn;
	echo "<input type=\"hidden\" name=\"post\" value=\"".urlencode(serialize($post))."\">".$nn;
	echo "<input type=\"hidden\" name=\"link\" value=\"".urlencode($LINK)."\">".$nn;
	echo "<input type=\"hidden\" name=\"cookie\" value=\"".urlencode($arcapt[cookie])."\">".$nn;
	echo "<h3>Type the two words:<br><img src=\"".$arcapt[capfile]."\"><br>here: <input type=\"text\" name=\"captcha\"><br></h3>".$nn;

	$code = "<input type=\"submit\" value=\"Download File\">".$nn;
	$code .= "</form></h4></center>";
	if (!$countdown)
		{
		print $code.$nn.$nn.$js_code."$nn</body>$nn</html>";
		}
	else
		{
		insert_new_timer($countdown+5, rawurlencode($code), "Cramit Free User.", $js_code);
		}
	}else{
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, 0, $post, 0, $_GET["proxy"],$pauth);
		is_page($page);
		is_present($page,"Expired session", "Expired session . Go to main page and reattempt", 0);
		$err = cut_str($page,'class="err">','</');
		if($err){html_error("$err",0);}

		$dwndr = cut_str ($page ,'LINK" ACTION="' ,'"' );
		if (!$dwndr) html_error( "Error getting download link" , 0 );
//echo "<center><li><a href=\"$dwndr\" target=_blank>$dwndr</a></li></center>";exit;
		$Url = parse_url($dwndr);
		$FileName = basename($dwndr);

insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&port=".$Url["port"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($LINK)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));


	}





}else{

		$post=unserialize(urldecode($_POST['post']));
		$post['recaptcha_response_field'] = $_POST['captcha'];

		insert_timer(5);

		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, 0, $post, 0, $_GET["proxy"],$pauth);
		is_page($page);
		is_present($page,"Wrong captcha", "Wrong captcha . Go to main page and reattempt", 0);
		is_present($page,"Expired session", "Expired session . Go to main page and reattempt", 0);

		$err = cut_str($page,'class="err">','</');
		if($err){html_error("$err",0);}

		$dwndr = cut_str ($page ,'LINK" ACTION="' ,'"' );
		if (!$dwndr) html_error( "Error getting download link" , 0 ); 
//echo "<center><li><a href=\"$dwndr\" target=_blank>$dwndr</a></li></center>";exit;
		$Url = parse_url($dwndr);
		$FileName = basename($dwndr);

insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&port=".$Url["port"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($LINK)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));


}

}


function apireca($k, $optionsd, $cookies, $sr){
		$Url = parse_url("http://api.recaptcha.net/challenge?k=$k");
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
		is_page($page);
	if(preg_match('/Location: *(.+)/i', $page, $redir )){
	$newreca = trim( $redir[1] );
	$Url = parse_url( $newreca );
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookies, 0, 0, $_GET["proxy"], $pauth);
	is_page($page);
		$rcf = cut_str ( $page ,"challenge:'" ,"'" );
		if(!$rcf){$rcf = cut_str ( $page ,"challenge : '" ,"'" );}
		$Url = parse_url("http://www.google.com/recaptcha/api/image?c=".$rcf);
	}else{
		$rcf = cut_str ( $page ,"challenge : '" ,"'" );	
		$cookie = GetCookies($page);
		$Url = parse_url("http://api.recaptcha.net/image?c=".$rcf);
		}
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
		$headerend = strpos($page,"\r\n\r\n");
		$cap_img = substr($page,$headerend+4);
		$capfile = $optionsd.$sr."_captcha.jpg";

		if (file_exists($capfile)){unlink($capfile);} 
		write_file($capfile, $cap_img);
if(!$rcf){html_error("Error getting captcha", 0);}
	$cookies .= "; " . $cookie;
$arcapt = array();
$arcapt[cookie] = $cookies;
$arcapt[rcf] = $rcf;
$arcapt[capfile] = $capfile;
return $arcapt;

}


?>