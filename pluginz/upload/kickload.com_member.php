<?php
####### Account Info. ###########
$kick_login = "";
$kick_pass = "";
##############################

					$not_done=true;
					$continue_up=false;
					if ($kick_login & $kick_pass){
						$_REQUEST['kick_login'] = $kick_login;
						$_REQUEST['kick_pass'] = $kick_pass;
						$_REQUEST['action'] = "FORM";
						echo "<b><center>Automatic Login</center></b>\n";
					}
					if ($_REQUEST['action'] == "FORM")
						$continue_up=true;
					else{
?>
			<script>document.getElementById('info').style.display='none';</script>
            <div id='info' width='100%' align='center' style="font-weight:bold; font-size:16px">LOGIN</div> 
            <form method="post">
            <table border="0" style="width:270px;" cellspacing="0" align="center">
            <input type="hidden" name="action" value="FORM">
            <tr><td nowrap>&nbsp;Login<td>&nbsp;<input type="text" name="kick_login" value="" style="width:195px;">&nbsp;</tr>
            <tr><td nowrap>&nbsp;Password<td>&nbsp;<input type="password" name="kick_pass" value="" style="width:195px;">&nbsp;</tr>
            <tr><td colspan="2" align="center"><input type="submit" value="Upload"></tr>
            </table>
            </form>
<?php
}

if ($continue_up)
	{
		$not_done=false;
?>
<table width=600 align=center> 
</td></tr> 
<tr><td align=center>
			<script>document.getElementById('info').style.display='none';</script>
			<div id='info' width='100%' align='center'>Login</div>
<?php
			$Url=parse_url('http://kickload.com/login/');
			if ($_REQUEST['action'] == "FORM" && !empty($_REQUEST['kick_login']) && !empty($_REQUEST['kick_pass'])){
			$post["email"]=$_REQUEST['kick_login'];
			$post["password"]=$_REQUEST['kick_pass'];
			$post["login"]="1";
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), "kickload.com", 0, $post, 0, $_GET["proxy"], $pauth);
			is_page($page);
			is_present($page, "Wrong user name and/or password.", "Wrong user name and/or password.");
			$cookies = GetCookies($page);
			}else{
				html_error('Is empty User or Password');
				}
?>
						<script>document.getElementById('info').style.display='none';</script>
                        <div id='info' width=100% align=center>Connecting Upload</div> 
<?php
			$Url=parse_url('http://kickload.com/');
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), "http://kickload.com/", $cookies, 0, 0, $_GET["proxy"], $pauth);
			preg_match('@name="UPLOAD_IDENTIFIER"[\r|\n|\s]+value="([^"]+)"@i',$page,$idsess);
			$upload_url = $idsess[1];
			preg_match('@<form[\r|\n|\s]+action="([^"]+)"@i',$page,$urlupload);
			$upload_url = $urlupload[1];
			$url = parse_url($upload_url);
			$post['UPLOAD_IDENTIFIER'] = '$idsess[1]';
			$post['upload_password'] = '';
			$post['upload_folder'] = '';
?>
				<script>document.getElementById('info').style.display='none';</script>
<?php
			$upfiles = upfile("ftp1.kickload.com", 80, "/upload.php", 0, $cookies, $post, $lfile, $lname, 'file1');
?>
						<script type='text/javascript'>document.getElementById('progressblock').style.display='none';</script>
<?php
			is_page($upfiles);
			preg_match('#location: ([0-9a-zA-Z%./:-]+)#', $upfiles, $link);
			$Url = parse_url($link[1]);
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookies, 0, 0, $_GET["proxy"],$pauth);
			preg_match('#href="([^"]+)" target="_blank"#', $page, $link);
			preg_match('#id="del1" value="([^"]+)"#', $page, $delet);
			$download_link = $link[1];
			$delete_link = $delet[1];
}
/**
written by simplesdescarga 12/01/2012
**/   
?>