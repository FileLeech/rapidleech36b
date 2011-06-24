<?php

####### Account Info. ###########
$upload_acc['uploadstation_com']['user'] = ""; //Set you username
$upload_acc['uploadstation_com']['pass'] = ""; //Set your password
##############################

$not_done=true;
$continue_up=false;
if ($upload_acc['uploadstation_com']['user'] && $upload_acc['uploadstation_com']['pass']){
	$_REQUEST['up_login'] = $upload_acc['uploadstation_com']['user'];
	$_REQUEST['up_pass'] = $upload_acc['uploadstation_com']['pass'];
	$_REQUEST['action'] = "FORM";
	echo "<b><center>Using Default Login and Pass.</center></b>\n";
}
if ($_REQUEST['action'] == "FORM")
	$continue_up=true;
else{
?>
<table border='0' style="width:270px;" cellspacing='0' align='center'>
<form method='post'>
<input type='hidden' name='action' value='FORM' />
<tr><td nowrap>&nbsp;User*<td>&nbsp;<input type='text' name='up_login' value='' style='width:160px;' />&nbsp;</tr>
<tr><td nowrap>&nbsp;Password*<td>&nbsp;<input type='password' name='up_pass' value='' style='width:160px;' />&nbsp;</tr>
<tr><td colspan='2' align='center'><input type='submit' value='Upload' /></tr>
<tr><td colspan='2' align='center'><small>*You can set it as default in <b><?php echo $page_upload["uploadstation.com"]; ?></b></small></tr>
</table>
</form>

<?php
	}

if ($continue_up)
	{
		$not_done=false;
?>
<table width='600' align='center'>
</td></tr>
<tr><td align='center'>
<div id='login' width='100%' align='center'>Login to Uploadstation</div>
<?php 
	if (!empty($_REQUEST['up_login']) && !empty($_REQUEST['up_pass'])) {
		$post['loginUserName'] = $_REQUEST['up_login'];
		$post['loginUserPassword'] = $_REQUEST['up_pass'];
		$post['autoLogin'] = 'on';
		$post['loginFormSubmit'] = 'Login';

		$page = geturl("www.uploadstation.com", 80, "/login.php", 0, 0, $post);
		is_page($page);
		is_present($page, "should be larger than or equal to 6", "Username or password too short.");
		is_present($page, "Username doesn't exist.", "Username doesn't exist.");
		is_present($page, "Wrong password.", "Wrong password.");
		is_notpresent($page, "Logging in", "Login error."); // "Houston, We've Got a Problem"
		$cookie = GetCookies($page);
	} else {
		echo "<b><center>Login not found or empty, using non member upload.</center></b>\n";
		$page = geturl("www.uploadstation.com", 80, "/index.php");
		is_page($page);
	}

?>
<script type='text/javascript'>document.getElementById('login').style.display='none';</script>
<div id='info' width='100%' align='center'>Retrive upload ID</div>
<?php 
	$page = geturl("www.uploadstation.com", 80, "/upload.php", 0, $cookie);
	is_page($page);

	if(!preg_match('@id="uploadForm"[\r|\n|\s]+action="([^"]+)"@i', $page, $act)){
		html_error('Cannot get form action.', 0); // "Houston, We've Got a Problem"
	}
	$Url = parse_url($act[1]."?callback=jsonp");
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookie, 0, 0, $_GET["proxy"],$pauth);
	if(!preg_match("@sessionId:'([^']+)'@i", $page, $sid)){
		html_error('Cannot get Session ID.', 0); // "Houston, We've Got a Problem"
	}
	$up_loc = $act[1] . $sid[1];
?>
<script type='text/javascript'>document.getElementById('info').style.display='none';</script>
<?php
		$url = parse_url($up_loc);
		$upfiles = upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),'http://uploadstation.com/', $cookie, 0, $lfile, $lname, "file");
?>
<script type='text/javascript'>document.getElementById('progressblock').style.display='none';</script>
<?php
		is_page($upfiles);
		is_present($upfiles, "ERROR2\r\n", "Error in upload."); // "Houston, We've Got a Problem"

		if(preg_match('@"shortenCode":"([^"]+)"@i', $upfiles, $sc)) {
			$download_link = "http://www.uploadstation.com/file/" . $sc[1];
			if(preg_match('@"deleteCode":"([^"]+)"@i', $upfiles, $del)) {
				$delete_link = $download_link . "/delete/" . $del[1];
			} else {
				$delete_link = "ERROR: Deletion-Link not Found.";
			}
			if(preg_match('@"fileName":"([^"]+)"@i', $upfiles, $fn)) {
				$download_link .= "/" . $fn[1];
			}
		} else { // Link not found?. Look again.
			$Url = parse_url($act[1]."?sessionId={$sid[1]}&callback=jsonp");
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookie, 0, 0, $_GET["proxy"],$pauth);
			is_present($page, "ERROR2\r\n", "Error in upload. [2]"); // "Houston, We've Got a Problem"
			is_notpresent($page, '"state":"done"', "Error in upload. [3]"); // "Houston, We've Got a Problem" Too

			if(preg_match('@"shortenCode":"([^"]+)"@i', $page, $sc)) {
				$download_link = "http://www.uploadstation.com/file/" . $sc[1];
				if(preg_match('@"deleteCode":"([^"]+)"@i', $page, $del)) {
					$delete_link = $download_link . "/delete/" . $del[1];
				} else {
					$delete_link = "ERROR: Deletion-Link not Found.";
				}
				if(preg_match('@"fileName":"([^"]+)"@i', $page, $fn)) {
					$download_link .= "/" . $fn[1];
				}
			} elseif ($post) {
				html_error("Download link not found, try looking in your account.", 0);
			} else {
				html_error("Download link not found.", 0);
			}
		}
	}

//[10-4-2011]  Written by Th3-822. // Using code from 4shared plugin.
//[11-4-2011]  Some code updates for work, fixed error msgs, added a '&' in default login checker. - Th3-822

?>