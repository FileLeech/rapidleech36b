﻿<?php
// Default Account Info =================
$xupload_user = ''; //  Set you username
$xupload_pass = ''; //  Set your password
//=======================================

			$not_done = true;
			$continue_up = false;
			if ($xupload_user & $xupload_pass) {
				$_REQUEST['10u_user'] = $xupload_user;
				$_REQUEST['10u_pass'] = $xupload_pass;
				$_REQUEST['action'] = "FORM";
				echo "<b><center>Automatic Login 10Upload.com</center></b>\n";
			}
			if ($_REQUEST['action'] == "FORM"){
				$continue_up = true;
			}else {
?>
					<script>document.getElementById('info').style.display='none';</script>
                    <div id='info' width='100%' align='center' style="font-weight:bold; font-size:16px">LOGIN</div> 
    <table border=0 style="width:270px;" cellspacing=0 align=center>
        <form method="post">
        <input type='hidden' name='action' value='FORM' />
            <tr><td nowrap>&nbsp;User*<td>&nbsp;<input type='text' name='10u_user' value='' style="width:160px;" />&nbsp;</tr>
            <tr><td nowrap>&nbsp;Password*<td>&nbsp;<input type='password' name='10u_pass' value='' style="width:160px;" />&nbsp;</tr>
            <tr><td colspan=2 align=center><input type='submit' value='Upload' /></tr>
            <tr><td colspan=2 align=center><small>*You can set it as default in <b><?php echo $page_upload["10upload.com_member"]; ?></b></small></tr>
        </form>
    </table>
<?php
			}
			if ($continue_up) {
				$not_done = false;
?>
    <table width=600 align=center>
    </td></tr>
    <tr><td align=center>
    		<script>document.getElementById('info').style.display='none';</script>
            <div id='info' width='100%' align='center'>Login to 10Upload.com</div>
        <?php
        if (!empty($_REQUEST['10u_user']) && !empty($_REQUEST['10u_pass'])){
        $post["op"] = "login";
        $post["redirect"] = "http://10upload.com/";
		$post["login"] = trim($_REQUEST['10u_user']);
        $post["password"] = trim($_REQUEST['10u_pass']);
		$page = geturl("www.10upload.com", 80, "/", "http://10upload.com/login.html", 0, $post, $_GET["proxy"]);
		is_page($page);
		is_present($page, "Incorrect Login or Password", "Incorrect Login or Password");
		$cookie = GetCookies($page);
		}else{
		html_error ('Error, user and/or password is empty, please go back and try again!');
	}
        ?> 
<script>document.getElementById('info').style.display='none';</script>
<div id=info width=100% align=center>Retrive upload ID</div> 
<?php
			$url = parse_url('http://www.10upload.com/');
			$page = geturl($url["host"], $url["port"] ? $url["port"] : 80, $url["path"] . ($url["query"] ? "?" . $url["query"] : ""), "http://www.10upload.com/login.html", $cookie, 0, 0, $_GET["proxy"], $pauth);
			if(!preg_match('#action="([^"]+)"#', $page, $up)){
				html_error('Cannot get url action upload.', 0);
			}
			if(!preg_match("#var[\r|\n|\s]+utype='([^']+)'#", $page, $utype)){
				html_error('Cannot get user information.', 0);
			}
			if(!preg_match('#name="sess_id"[\r|\n|\s]+value="([^"]+)"#', $page, $sid)){
				html_error('Cannot get session id.', 0);
			}
			if(!preg_match('#name="srv_tmp_url"[\r|\n|\s]+value="([^"]+)"#', $page, $tmp)){
				html_error('Cannot get tmp url.', 0);
			}
			$uid = uid();
			$url = parse_url($up[1].$uid.'&js_on=1&utype='.$utype[1].'&upload_type=file');
			$post["upload_type"] = "file";
            $post["sess_id"] = $sid[1];
            $post["srv_tmp_url"] = $tmp[1];
			$post["link_rcpt"] = '';
			$post["link_pass"] = '';
			$post["file_0_descr"] = '';
			$post["file_0_public"] = '1';
			$post['tos'] = '1';
			$upfiles = upfile($url["host"], $url["port"] ? $url["port"] : 80, $url["path"] . ($url["query"] ? "?" . $url["query"] : ""), "http://10upload.com/", $cookie, $post, $lfile, $lname, "file_0");
?>

<script>document.getElementById('progressblock').style.display='none';</script>
<?php
is_page($upfiles);
			preg_match_all("#>([^><]+)<#", $upfiles, $dl);
			if($dl[1][1] != 'OK')
				html_error('Erro in upload');
			$post['fn'] = $dl[1][0];
			$post['st'] = 'OK';
			$post['op'] = 'upload_result';
			$page = geturl("www.10upload.com", 80, "/", 0, $cookie, $post, 0, $_GET["proxy"]);
				if(!empty($dl[1][0]))
					$download_link = 'http://10upload.com/'.$dl[1][0].'/'.$lname.'.html';
				else
					html_error ("Didn't find download link!");
				if(preg_match("#killcode=([^=<]+)<#", $page, $del))
					$delete_link = $download_link.'?killcode='.$del[1];
				else
					html_error ("Didn't find delete link!");
	}
function uid(){
				$nu = "0123456789";
				for($i=0; $i < 12; $i++){
				$rand .= $nu{mt_rand() % strlen($nu)};
				}
				return $rand;
				//function by simplesdescraga 05/02/2012
	}
/**
written by simplesdescarga 05/02/2012
**/   
?>