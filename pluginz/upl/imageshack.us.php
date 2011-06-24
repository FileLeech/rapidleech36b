<?php

####### Account Info. ###########
$upload_acc['imageshack_us']['user'] = ""; //Set your email
$upload_acc['imageshack_us']['pass'] = ""; //Set your password
##############################

$not_done=true;
$continue_up=false;
$sizes = array("resample"=>"Do not resize", "optimize"=>"Optimize without resize", "100x100"=>"100x75 (avatar)", "150x150"=>"150x112 (thumbnail)", "320x320"=>"320x240 (for websites and email)", "640x640"=>"640x480 (for message boards)", "800x800"=>"800x600 (15-inch monitor)", "1024x1024"=>"1024x768 (17-inch monitor)", "1280x1280"=>"1280x1024 (19-inch monitor)", "1600x1600"=>"1600x1200 (21-inch monitor)");
if ($upload_acc['imageshack_us']['user'] && $upload_acc['imageshack_us']['pass']){
	$_REQUEST['up_login'] = $upload_acc['imageshack_us']['user'];
	$_REQUEST['up_pass'] = $upload_acc['imageshack_us']['pass'];
	$_REQUEST['action'] = "FORM";
	echo "<b><center>Using Default Login and Pass.</center></b>\n";
}
if ($_REQUEST['action'] == "FORM")
	$continue_up=true;
else{
?>
<table border='0' style="width:270px;" cellspacing='0' align='center'>
<form method='POST'>
<input type='hidden' name='action' value='FORM' />
<tr><td nowrap>&nbsp;Username or Email*</td><td>&nbsp;<input type='text' name='up_login' value='' style='width:160px;' />&nbsp;</td></tr>
<tr><td nowrap>&nbsp;Password*</td><td>&nbsp;<input type='password' name='up_pass' value='' style='width:160px;' />&nbsp;</td></tr>
<tr><td colspan='2' align='center'><input type='submit' value='Upload' /></td></tr>
<tr><td colspan='2' align='center'><small>*You can set it as default in <b><?php echo $page_upload["imageshack.us"]; ?></b></small></td></tr>
<tr><td colspan='2' align='center'><br />Upload options:</td></tr>
<tr><td colspan='2' align='center'><br />Resize image:&nbsp;&nbsp;<select name="up_optsize" style="height:20px">
<?php foreach($sizes as $n => $v) echo "<option value='$n'>$v</option>"; ?>
</select></td></tr>
<tr><td colspan='2' align='center'><input type="checkbox" name="up_rembar" value="yes" />&nbsp; Remove label in thumbnail</td></tr>
</table>
</form>
<script type="text/javascript">self.resizeTo(700,350);/*Try to Resize Window*/</script>
<?php
}

if ($continue_up) {
	$not_done=false;
?><table width='600' align='center'>
</td></tr>
<tr><td align='center'>
<div id='login' width='100%' align='center'>Login to Imageshack</div>
<script type='text/javascript'>window.moveTo(0,0);window.resizeTo(screen.width,screen.height);</script>
<?php 
	$cookie = "no_multi=1; lang=en";
	$login = false;
	if (!empty($_REQUEST['up_login']) && !empty($_REQUEST['up_pass'])) {
		$post['username'] = $_REQUEST['up_login'];
		$post['password'] = $_REQUEST['up_pass'];
		$post['stay_logged_in'] = 'true';
		$post['format'] = 'json';

		$page = geturl("imageshack.us", 80, "/auth.php", 0, $cookie, $post);is_page($page);

		is_present($page, '"status":false', "Login: User or Password incorrect.");
		is_notpresent($page, "Set-Cookie: isUSER=", "Error in login.");
		$cookie = "$cookie; " . GetCookies($page);
		$login = true;
	} else {
		echo "<b><center>Login not found or empty, using non member upload.</center></b>\n";
	}

?><script type='text/javascript'>document.getElementById('login').style.display='none';</script>
<div id='info' width='100%' align='center'>Retrive upload ID</div>
<?php 
	$page = geturl("imageshack.us", 80, "/", 0, $cookie);is_page($page);
	$cookie = "$cookie; " . GetCookies($page);
	$post = array();
	$post['Filename'] = $lname;
	$post['new_flash_uploader'] = 'y';
	$post['rembar'] = ($_REQUEST['up_rembar'] == 'yes') ? 1 : 0;
	if (array_key_exists($_REQUEST['up_optsize'], $sizes)) $post['optsize'] = $_REQUEST['up_optsize'];
	else $post['optsize'] = 'resample';
	if ($post['optsize'] != 'resample') $post['optimage'] = 1;
	$post['swfupload'] = 1;
	if (login) {
		$usl = cut_str($cookie, 'ulevel=', ';');
		$post['ulevel'] = ($usl == 'deleted' ? 'null' : $usl);
		$post['myimages'] = cut_str($cookie, 'myimages=', ';');
		$post['isUSER'] = cut_str($cookie, 'isUSER=', ';');
	} else $post['ulevel'] = $post['myimages'] = $post['isUSER'] = 'null';
	$post['Upload'] = 'Submit Query';

	$up_loc = "http://www.imageshack.us/upload_api.php";

?><script type='text/javascript'>document.getElementById('info').style.display='none';</script>
<?php
	$url = parse_url($up_loc);
	$upfiles = upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),'http://imageshack.us/', $cookie, $post, $lfile, $lname, "fileupload");
?><script type='text/javascript'>document.getElementById('progressblock').style.display='none';</script>
<?php
	is_page($upfiles);

	echo "\n<table width=100% border=0>";
	if(preg_match('@<image_link>([^<]+)</image_link>@i', $upfiles, $il)) echo '<tr><td width="100" nowrap="nowrap" align="right">Direct Link:<td width="80%"><input value="'.$il[1].'" class="upstyles-dllink" readonly="readonly" /></tr>';
	if(preg_match('@<thumb_link>([^<]+)</thumb_link>@i', $upfiles, $il)) echo '<tr><td width="100" nowrap="nowrap" align="right">Thumbnail Link:<td width="80%"><input value="'.$il[1].'" class="upstyles-dllink" readonly="readonly" /></tr>';
	echo "</table>\n";

	if(preg_match('@<is_link>([^<]+)</is_link>@i', $upfiles, $il)) $download_link = $il[1];
	else html_error("Download link not found.", 0);
}

//[25-5-2011]  Written by Th3-822. // Maded for img upload.

?>