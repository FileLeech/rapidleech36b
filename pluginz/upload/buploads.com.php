<?php
/*
 * Plugin Devloped by Soumo Gorai .
 * Contact Mail : ashsohat123@gmail.com
 * Facebook : facebook.com/soumo.gorai1
 * twitter: @soumogorai
 */

####### Account Info. ###########
$fourshare_vn_login = ""; //Set your user id (login)
$fourshare_vn_pass = ""; //Set your password
##############################

$not_done = true;
$continue_up = false;
if ($fourshare_vn_login && $fourshare_vn_pass) {
    $_REQUEST['my_login'] = $fourshare_vn_login;
    $_REQUEST['my_pass'] = $fourshare_vn_pass;
    $_REQUEST['action'] = "FORM";
    echo "<b><center>Use Default login/pass.</center></b>\n";
}
if ($_REQUEST['action'] == "FORM")
    $continue_up = true;
else {
    ?>
    <table border=0 style="width:270px;" cellspacing=0 align=center>
        <form method=post>
            <input type=hidden name=action value='FORM' />
            <tr><td nowrap>&nbsp;Username*<td>&nbsp;<input type=text name=my_login value='' style="width:160px;" />&nbsp;</tr>
            <tr><td nowrap>&nbsp;Password*<td>&nbsp;<input type=password name=my_pass value='' style="width:160px;" />&nbsp;</tr>
            <tr><td colspan=2 align=center><input type=submit value='Upload' /></tr>
            <tr><td colspan=2 align=center><small>*You can set it as default in <b><?php echo $page_upload["4share.vn"];
    exit; ?></b></small></tr>
    </table>
    </form>

    <?php
}
//$continue_up = true;
if ($continue_up) {
	    $not_done = false;
    ?>
    <table width=600 align=center>
    </td></tr>
    <tr><td align=center>
            <div id=login width=100% align=center>Login to buploads.com</div>
            <?php
			$post['user'] = $_REQUEST['my_login'];
            $post['pass'] = $_REQUEST['my_pass'];
            $post['act'] = 'login';
			
            $page = geturl("www.buploads.com", 80, "/en/login.php", 'http://www.buploads.com/en/index.php', 0, $post);			
			is_page($page);
			//echo $page; exit;
			if(!preg_match('#ocation:(.*)\r\n#',$page)){
				html_error('Invalid Login Details');
			}
			$cookie = GetCookies($page);
			$cookie = str_replace('mfh_logined=0; mfh_uid=0; ','',$cookie);
?>
<script>document.getElementById('login').style.display='none';</script>
<div id=info width=100% align=center>Retrive upload ID</div>
<?php 
$nlink = geturl("www.buploads.com", 80, "/en/index.php", 0, $cookie, 0);
$formlink = cut_str($nlink,'action="','"');
	preg_match_all('#<input(.*)>#',$nlink,$im);
	foreach($im['0'] as $m1){
		if(in_array(cut_str($m1,'name="','"'),array('mobNo','TextArea'))){
			continue;
		}else{
		$idata[cut_str($m1,'name="','"')] = cut_str($m1,'value="','"');
		}
	}
	$idata['uploadfile_0'] = $lname;
	$url = parse_url($formlink);
	?>
	<script>document.getElementById('login').style.display='none';</script>
    <div id=info width=100% align=center>Upload Started</div>
    <script>document.getElementById('info').style.display='none';</script>
	<?php
	$upfiles = upfile($url["host"], $url["port"] ? $url["port"] : 80, $url["path"] . ($url["query"] ? "?" . $url["query"] : ""), 0, $cookie, $idata, $lfile, $lname, "Filedata");
	?>
    <script>document.getElementById('progressblock').style.display='none';</script>
    <?php
    is_page($upfiles);
	preg_match('#ocation: (.*)\r\n#',$upfiles,$ilk1);
	$Url = parse_url($ilk1['1']);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	preg_match('#sessionid=(.*)&#',$ilk1['1'],$ilk2);
	$Url = parse_url('http://buploads.com/cross.php?&redir=1');
	$page_t = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $ilk1['1'], 0, 0, 0, $_GET["proxy"],$pauth);
	$mbil = 'http://buploads.com/getlinks.php?submitnums=1&sessionid='.$ilk2['1'];
	$Url = parse_url($mbil);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	$download_link = 'http://buploads.com/en/file/'.cut_str($page,'/en/file/','.html').'.html';
	$delete_link = 'http://buploads.com/en/delete.php?id='.cut_str($page,'http://buploads.com/en/delete.php?id=','"');
	
}

/*
 * Plugin Devloped by Soumo Gorai .
 * Contact Mail : ashsohat123@gmail.com
 * Facebook : facebook.com/soumo.gorai1
 * twitter: @soumogorai
 */
?>
			
			