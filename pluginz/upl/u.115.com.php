<?php

####### Account Info. ###########
$u_115_login = ""; //Set you username
$u_115_pass = ""; //Set your password
##############################

$not_done=true;
$continue_up=false;
if ($u_115_login & $u_115_pass){
	$_REQUEST['my_login'] = $u_115_login;
	$_REQUEST['my_pass'] = $u_115_pass;
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
<tr><td nowrap>&nbsp;User*<td>&nbsp;<input type=text name=my_login value='' style="width:160px;" />&nbsp;</tr>
<tr><td nowrap>&nbsp;Password*<td>&nbsp;<input type=password name=my_pass value='' style="width:160px;" />&nbsp;</tr>
<tr><td colspan=2 align=center><input type=submit value='Upload' /></tr>
<tr><td colspan=2 align=center><small>*You can set it as default in <b><?php echo $page_upload["u.115.com"]; ?></b></small></tr>
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
<div id=login width=100% align=center>Login to U.115.com</div>
<?php 
	                $post['login[account]'] = $_REQUEST['my_login'];
                        $post['login[passwd]'] = $_REQUEST['my_pass'];
                        $page = geturl("my.115.com", 80, "/?action=login&goto=http%3A%2F%2Fu.115.com%2F%3Fac%3Dmy", 0, 0, $post);			
		        is_page($page);
                        $cookie1 = GetCookies($page);
                        $linkaction =cut_str ($page ,"Location: ","\r");
                        if(!$linkaction){
                        html_error("Error logging in - are your logins correct!", 0);
                        } 
                        $Url = parse_url($linkaction);
		        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cookie1, 0, 0, $_GET["proxy"],$pauth);	
		        is_page($page);
                        $cookie = GetCookies($page);               
?>
<script>document.getElementById('login').style.display='none';</script>
<div id=info width=100% align=center>Retrive upload ID</div>
<?php 
			$Url = parse_url("http://u.115.com/?ac=my#ct=frame");
		        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""),"http://u.115.com/?ac=my", $cookie1, 0, 0, $_GET["proxy"],$pauth);	
		        $cookie = GetCookies($page);
                        $cookie_up =cut_str ($page ,"var USER_COOKIE = '","';");
?>
<script>document.getElementById('info').style.display='none';</script>
<?php 		
			$url = parse_url("http://u.115.com/index.php?ctl=upload&action=temp_upload");
                        $fpost = array();
                        $fpost['Filename'] = $lname;
                        $fpost['cookie'] = $cookie_up;
                        $fpost['aid'] = '1';
                        $fpost['cid'] = '0';
                        $fpost['Upload'] = 'Submit Query';
			$agent='Shockwave Flash';
			$upfiles = upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""),"http://u.115.com/?ac=my", $cookie, $fpost, $lfile, $lname, "Filedata" );
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<?php 	
			is_page($upfiles);
                        $rnd=time().rand(100,999);
			$dl_page = "http://u.115.com/?ct=frame&ac=file&aid=1&cid=0&_t=$rnd";
			$Url = parse_url($dl_page);
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), "http://u.115.com/?ac=my", $cookie1, 0, 0, $_GET["proxy"],$pauth);
                        if(preg_match('%<a href="(.*)" target="_blank">%', $page, $flink)){
                        $download_link = $flink[1];
			}else{
				html_error("Finished, Go to your account to see Download-URL.", 0);
			}
	}
?>