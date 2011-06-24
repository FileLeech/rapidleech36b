<?php

####### Free Account Info. ###########
$fileserve_login = "H3F"; //  Set you username
$fileserve_pass = "N HERE"; //  Set your password
##############################

$not_done=true;
$continue_up=false;
if ($fileserve_login & $fileserve_pass){
	$_REQUEST['my_login'] = $fileserve_login;
	$_REQUEST['my_pass'] = $fileserve_pass;
	$_REQUEST['action'] = "FORM";
	echo "<b><center>FileServe Use Default login/pass.</center></b>\n";
}
if ($_REQUEST['action'] == "FORM")
    $continue_up=true;
else{
?>
<table border=0 style="width:270px;" cellspacing=0 align=center>
<form method=post>
<input type=hidden name=action value='FORM' />
<tr><td nowrap>&nbsp;User*<td>&nbsp;<input type=text name=my_login value='' style="width:160px;" />&nbsp;</tr>
<tr><td nowrap>&nbsp;Password*<td>&nbsp;<input type=password name=my_pass value='' style="width:160px;" />&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload' /></tr>
<tr><td colspan=2 align=center><small>*You can set it as default in <b><?php echo $page_upload["fileserve.com"]; ?></b></small></tr>
</form>
</table>
<?php
	}

if ($continue_up)
	{
		$not_done=false;
?>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<div id=info width=100% align=center>Retrive upload ID</div>
<?php
			if (empty($_REQUEST['my_login']) || empty($_REQUEST['my_pass'])) html_error('No entered Login/Password');
			$Url = parse_url("http://www.fileserve.com/login.php");
			$post["loginUserName"] = trim($_REQUEST['my_login']);
			$post["loginUserPassword"] = trim($_REQUEST['my_pass']);
                        $post["autoLogin"]="on";
			$post["loginFormSubmit"] = "Login";
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), "http://www.fileserve.com/login.php", 0, $post, 0, $_GET["proxy"],$pauth);
			
			preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
			$cookie = $temp[1];
			$cookies = implode(';',$cookie);
			$Url=parse_url("http://www.fileserve.com/upload-file.php");
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), "http://www.fileserve.com/", $cookies, 0, 0, $_GET["proxy"],$pauth);
			
                        preg_match("/http:\/\/[0-9A-Za-z]+\.fileserve\.com\/upload\/upload\.php\?X\-Progress\-ID=/i", $page, $matches);
		        $upload_server = trim($matches[0]);
                        preg_match("/serverId\" value=\"[0-9]+/i", $page, $matches);
		        $server_id = str_ireplace("serverId\" value=\"", "", $matches[0]);
		        preg_match("/userId\" value=\"[0-9]+/i", $page, $matches);
		        $user_id = str_ireplace("userId\" value=\"", "", $matches[0]);
		        preg_match("/uploadHostURL\" value=\"[0-9A-Za-z.]+/i", $page, $matches);
		        $server_url = trim(str_ireplace("uploadHostURL\" value=\"", "", $matches[0]));
                        $Url = parse_url("http://www.fileserve.com/upload-track.php");
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), "http://www.fileserve.com/", $cookies, 0, 0, $_GET["proxy"],$pauth);
                        $sid = str_replace("\\","",cut_str($page,'{"sessionId":"','"'));
		        $upload_server .= $sid;	
?>
<script>document.getElementById('info').style.display='none';</script>
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
                        $url = parse_url($upload_server);
                        unset($post);
                        $post["serverId"] = $server_id;
                        $post["userId"] = $user_id;
                        $post["uploadHostURL"] = $server_url;
                        $post["uploadSessionId"] = $sid;
                        $post["affiliateId"]="";
                        $post["subAffiliateId"]="";
                        $post["landingId"]="";
                        $upfiles = upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""), 0, $cookies, $post, $lfile, $lname,"file");
                        is_page($upfiles);
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<div id=final width=100% align=center>Get final code</div>
<?php			
                        $Url = parse_url("http://www.fileserve.com/upload-result.php");
                        unset($post);
                        $post["uploadSessionId[]"]=$sid;
			$page = geturl($Url["host"], defport($Url), $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookies, $post, 0, $_GET["proxy"],$pauth);
			preg_match("/http:\/\/www\.fileserve.com\/file\/[0-9A-Za-z]+/i", $page, $linkdl);
			$download_link = trim($linkdl[0]);
                        preg_match("/delete\/[a-zA-Z0-9%]+/i", $page, $matches);			
	                $delete_link  = $download_link.'/'.$matches[0];			
	}

/*********************************************\
 Written By lickleecher 2010-07-16
 Updated By lickleecher For User Id 2010-10-7
\*********************************************/
?>
