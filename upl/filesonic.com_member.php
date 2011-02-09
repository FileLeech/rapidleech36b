<?php
// Default Account Info =================
$default_user = ""; //  Set you username
$default_pass = ""; //  Set your password
//=======================================

$not_done=true;
$continue_up=false;
if ($default_user & $default_pass){
	$_REQUEST['my_login'] = $default_user;
	$_REQUEST['my_pass'] = $default_pass;
	$_REQUEST['action'] = "FORM";
	echo "<b><center>Use Default login/pass.</center></b>\n";
}
if ($_REQUEST['action'] == "FORM")
    $continue_up=true;
else{
?>
	<table border=0 style="width:270px;" cellspacing=0 align=center>
	<form method=post>
		<input type=hidden name=action value='FORM' />
		<tr><td nowrap>&nbsp;Email*<td>&nbsp;<input type=text name=my_login value='' style="width:160px;" />&nbsp;</tr>
		<tr><td nowrap>&nbsp;Password*<td>&nbsp;<input type=password name=my_pass value='' style="width:160px;" />&nbsp;</tr>
		<tr><td colspan=2 align=center><input type=submit value='Upload' /></tr>
		<tr><td colspan=2 align=center><small>*You can set it as default in <b><?php echo $page_upload["filesonic.com_member"]; ?></b></small></tr>
	</form>
	</table>
<?php
}

if ($continue_up) {
	$not_done=false;
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Connecting to filesonic.com</div>
<?php
	$Url=parse_url("http://www.filesonic.com/");
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	$cookies=GetCookies($page);

	if (empty($_REQUEST['my_login']) || empty($_REQUEST['my_pass'])) 
		html_error('No entered Login/Password');
	//
	$Url=parse_url("http://www.filesonic.com/user/login/");
	$post = array();
	$post["rememberMe"] = "1";
	$post["email"]=trim($_REQUEST['my_login']);
	$post["password"]=trim($_REQUEST['my_pass']);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), "http://www.filesonic.com/", $cookies, $post, 0, $_GET["proxy"],$pauth);
	is_page($page);

	if(strpos($page,"nickname") !== false)
		$cookies = GetCookies($page);
	else
		html_error("Can't use premium account!");

	$Url=parse_url("http://www.filesonic.com/");
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), "http://www.filesonic.com/dashboard/", $cookies, 0, 0, $_GET["proxy"],$pauth);
	$cookies .= GetCookies($page);

	//Collection info...
	$sessID = cut_str($cookies, 'PHPSESSID=', ';');
	$originalAction = cut_str($page, 'class="webUpload" action="', '" method');
	//ID
	$currentTime = time();
	$randomID = rand(0, 90000);
	$uploadIdentifier = 'upload_'.$currentTime.'_'.$sessID.'_'.$randomID;
	//POST
	$post = array();
	$post['uploadFiles'] = 'Upload';
	$post['callbackUrl'] = 'http://www.filesonic.com/upload-completed/:uploadProgressId';
	//Ready...
	$uploadURL = $originalAction . '/?X-Progress-ID=' . $uploadIdentifier;

	$url = parse_url($uploadURL);
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
	<script type="text/javascript">document.getElementById('info').style.innerHTML='Uploading...';</script>
<?php
	$upfiles = upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""), 'http://www.filesonic.com/', $cookies, $post, $lfile, $lname, "upload[]", "", $_GET["proxy"],$pauth);
	is_page($upfiles);
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?php
	if (preg_match('/Location: (.*)\r\n/', $upfiles, $matc)) {
		$Url=parse_url($matc[1]);
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 'http://www.filesonic.com/', $cookies, 0, 0, $_GET["proxy"],$pauth);
		
		$rawpage = urldecode($page);

		$Rid = cut_str($rawpage, '"linkId":"F', '"');
		$Rname = cut_str($rawpage, '"filename":"', '"');
		$Rstatus = (int) cut_str($rawpage, '"statusCode":', ',');
		$Rmessage = cut_str($rawpage, '"statusMessage":"', '"');
		
		if ($Rstatus !=0)
			html_error($Rmessage);
		
		$relink = 'http://www.filesonic.com/filesystem/generate-link/F' . $Rid;
		$Url=parse_url($relink);
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 'http://www.filesonic.com/', $cookies, 0, 0, $_GET["proxy"],$pauth);
		
		if (strpos($page, 'Technical problem. Please try again!'))
			html_error('Technical problem. Please try again!');
		elseif (preg_match('/name="URL_(.*)" value="(.*)"/', $page, $matches))
			$download_link = $matches[2];
		else
			html_error("Download link not found!");
	} else
		html_error("Have an error when retriving upload link!");
}
?>

<?php
/* HISTORY: Filesonic Upload Plugin ===================
2010.12.04: Written by thangbom40000 @ Share4u.vn
			Upload plugin for free member.
=====================================================*/
?>