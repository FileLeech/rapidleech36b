<?php

####### Free Account Info. ###########
$mediafire_login = ""; //  Set you username
$mediafire_pass = ""; //  Set your password
##############################

$not_done=true;
$continue_up=false;
if ($mediafire_login & $mediafire_pass){
	$_REQUEST['my_login'] = $mediafire_login;
	$_REQUEST['my_pass'] = $mediafire_pass;
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
<tr><td colspan=2 align=center><small>*You can set it as default in <b><?php echo $page_upload["mediafire.com"]; ?></b></small></tr>
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
			$Url=parse_url("http://www.mediafire.com/");
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
			$cookies=GetCookies($page);

			if (empty($_REQUEST['my_login']) || empty($_REQUEST['my_pass'])) html_error('No entered Login/Password');
			$Url=parse_url("http://www.mediafire.com/dynamic/login.php");
			$post["login_email"]=trim($_REQUEST['my_login']);
			$post["login_pass"]=trim($_REQUEST['my_pass']);
			$post["submit_login"]="Login+to+MediaFire";
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), "http://www.mediafire.com/", $cookies, $post, 0, $_GET["proxy"],$pauth);
			is_page($page);
			if(strpos($page,"user=") !== false)
			{
				$cookies=$cookies . '; ' . GetCookies($page);
			}

$matc = array();
preg_match("/ukey=[^ ;\r\n]+/",$cookies,$matc);
$ukey=$matc[0];
preg_match("/user=[^ ;\r\n]+/",$cookies,$matc);
$user=$matc[0];
$rnn=rand(10000,99999);                                
			$Url=parse_url("http://www.mediafire.com/basicapi/uploaderconfiguration.php?".$rnn);
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), "http://www.mediafire.com/", $cookies, 0, 0, $_GET["proxy"],$pauth);
			is_page($page);

$track= cut_str ( $page ,'<trackkey>' ,'</trackkey>' );
$uploadkey= cut_str ( $page ,'<folderkey>' ,'</folderkey>' );
$MFULConfig = cut_str ( $page ,'<MFULConfig>' ,'</MFULConfig>' );

$url=parse_url("http://www.mediafire.com/douploadtoapi/?track=".$track."&".$ukey."&".$user."&uploadkey=".$uploadkey."&upload=0");
	
?>
<script>document.getElementById('info').style.display='none';</script>
    
<table width=600 align=center>
</td></tr>
<tr><td align=center>
<?
unset($post);
$post["Filename"]=$lname;
$post["Upload"]="Submit Query";
$upagent = "Shockwave Flash";
$upfiles = upfile($url["host"],$url["port"] ? $url["port"] : 80, $url["path"].($url["query"] ? "?".$url["query"] : ""), 0, $ukey, $post, $lfile, $lname, "Filedata",0,0,0,$upagent);

is_page($upfiles);
?>
<script>document.getElementById('progressblock').style.display='none';</script>
<div id=final width=100% align=center>Get final code</div>
<?php
			$key = cut_str($upfiles,"<key>","</key>");
			if (!$key) html_error("Error retrive final id");
			$error=true;
			for ($i=1;$i<12;$i++){
				sleep(4);
				$page = geturl("www.mediafire.com",80,"/basicapi/pollupload.php?key=".$key."&MFULConfig=".$MFULConfig,"http://www.mediafire.com/myfiles.php",$cookies);
				if (stristr($page,"<fileerror>13</fileerror>")){
				html_error("File already existent!  for your link get it in mediafire member section");					
				}
				if (strstr($page,"No more requests")){$error = false; break;}
				
			}
			if ($error == true) html_error("Error verification time out!");
			$links_up_file = cut_str($page,'<quickkey>','</quickkey>');

			if (!$links_up_file){
				//echo $page;
			html_error("Error retrive upload links!");
			}

			$download_link = 'http://www.mediafire.com/?'.$links_up_file;	
	}

/*************************\
 Written by kaox 20-feb-10
 Updated by szalinski 26-Aug-09
 Updated by kaox 03-apr-10
 Updated by VinhNhaTrang 19.11.2010
\*************************/
?>