<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: lynx.php - 06apr2010-Idx $
 *****************************************************/

define('RAPIDLEECH', 'yes');

require_once "./global.php";


define('VERSION', "[ TuxiNuX::TimSukses ][ ccpb::kaskus ]");
$jQ_google_api_file = ROOT_URL.STATIC_DIR."jquery.min.js";

header( "Content-type: text/html; charset=".($charSet!="" ? $charSet : "utf-8") );
#==========MAIN===========
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo ($charSet!="" ? $charSet : "utf-8");?>" />
<title>LynX :: <?php echo $RL_VER;?> ::</title>
<link rel="shortcut icon" href="<?php echo ROOT_URL.IMAGE_DIR.'idmdl.gif?'.rand(11,9999);?>" type="image/gif" />
<link type="text/css" href="<?php print ROOT_URL.IMAGE_DIR;?>style_sujancok<?php print $options["csstype"];?>.css?<?php echo rand(1,9999);?>" rel="stylesheet" media="screen" />
<link type="text/css" href="<?php echo ROOT_URL.STATIC_DIR;?>jQ_fb.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="<?php echo ROOT_URL.STATIC_DIR;?>js.php?lynx"></script>
<script type="text/javascript" src="<?php echo $jQ_google_api_file;?>"></script>
<script type="text/javascript" src="<?php echo ROOT_URL.STATIC_DIR;?>ajax.js"></script>
</head><body>
<div class="head_container"><center>
 <a href="<?php echo ROOT_URL.$options['index_file'];?>" class="tdheadolgo" title="Rapidleech"><span>Rapidleech36B</span></a>
</center></div>
<center>
<?php
//SHOW TIME WORK
if( $options["limit_timework"] ){
  $is_worktime = cek_worktime($options["workstart"], $options["workend"]);
  $limitmsg="";
  if(!$is_worktime){
    if(!empty($limitmsg)){$limitmsg.="<br />";}$limitmsg.=$L->say['worktime_alert'];
    echo "<div style=\"padding-top:20px;padding-bottom:20px;\"><div class=\"warn_alert\">{$limitmsg}</div></div>";
  }
}
else
{

?>
<noscript><p><b><?php echo $L->say['js_disable'];?></b></p></noscript>

<?php
$c = 0; $total_size = 0; $nn = "\n";  $kumulatifsz = true;

// true mean do not count md5_file()
_create_list(true);

if(isset($list["files"]["totalsize"])){
  $total_size = $list["files"]["totalsize"];
  $kumulatifsz = false;
}

if($list){
  //$morethanone = (count($list)>1);
  if ($options["show_all"] === true) unset($Path);
?>

<script type="text/javascript">
/* <![CDATA[ */
var dFile = new Object(); var text = "", thead, tfoot;
<?php
 echo "var _dlpath = '".($options["download_dir"] != "" ? "{$options["download_dir"]}" : "/")."';{$nn}{$nn}";
 foreach($list as $key => $file){
   if(isset($file["name"]) && @file_exists($file["name"])){
	 $c++;
     if($kumulatifsz) $total_size+=getfilesize($file["name"]);
	 echo 'dFile["'.$file["date"].'"] = new Object();'.$nn;
	 echo 'dFile["'.$file["date"].'"]["name"] = "'.xmlentities(basename($file["name"]), false).'"'.$nn;
	 echo 'dFile["'.$file["date"].'"]["size"] = "'.$file["size"].'"'.$nn;
	 echo 'dFile["'.$file["date"].'"]["age"] = "'.$file["age"].'"'.$nn;
	 $rnd = rand(11,99);
	 echo 'dFile["'.$file["date"].'"]["delkey"] = "'.str_replace("=","", rotN(base64_encode($file["date"].':'.'4puZ'), $rnd))."-".$rnd.'"'.$nn;
   }
 } // end loop all files

 echo 'total_size = "'.$total_size.'";'."$nn";
 echo 'c = "'.$c.'";'."$nn";
 $lynx_del = ($options["deletelink_in_lynx"] && !$options["disable_to"]["act_delete"]);
 $morethanone = ($c>1);
?>
thead = "<table id='intbl' cellpadding='1' cellspacing='1' class='sortable' style='display: none;'>";
thead+= "<tr valign='bottom' align='center' id='filelist_title_head' class='filelist_title'>";
thead+= "<td><b class='sorttable_nosort'><\/b><\/td>";
thead+= "<td align=right><b>&nbsp;<?php echo $L->say['_fname'];?> &nbsp;<\/b><\/td>";
thead+= "<td align=left><b>&raquo;&nbsp;|&nbsp;<?php echo $L->say['tabel_sz'];?>&nbsp;<\/b><\/td>";
thead+= "<td><b>&nbsp;<?php echo $L->say['tabel_dt'];?><\/b><\/td>";
thead+= "<td><b class='sorttable_nosort'>&nbsp;<?php echo $L->say['tabel_age'];?><\/b><\/td>";
thead+= "<?php if($lynx_del){?><td><b class='sorttable_nosort'>&nbsp;<?php echo $L->say['act_del'];?><\/b><\/td><?php }?><\/tr>";
tfoot = "<tfoot>";
tfoot+= "<tr class='filelist_title'><td><input type=checkbox id='chksAll' onclick='javascript:sAll(this.checked);'><\/td>";
tfoot+= "<td id='totfile'><?php echo "Total : <b class='y'>".$c."<\/b> file(s)";?><\/td>";
tfoot+= "<td align='right' id='totsz'><?php echo '<b class=\"y\">'.bytesToKbOrMbOrGb($total_size).'<\/b>';?> <\/td>";
tfoot+= "<td align=right><\/td><?php  if($lynx_del){?><td><\/td><?php }?><td><\/td><\/tr><\/tfoot>";

var dtemplate = "<tr id='brs((chkidx))' class='rowlist' onmousedown='clk(\"chkfL-((chkidx))\")' ";
dtemplate+= "onmouseout='if(d.getElementById(\"chkfL-((chkidx))\").checked){this.className=\"rowlist_checked\";}else{this.className=\"rowlist\";}'>";
dtemplate+= "<td><input type=checkbox id='chkfL-((chkidx))' onclick='clk(\"chkfL-((chkidx))\")'><\/td>";
dtemplate+= "<td align=right><span id='fN-((chkidx))'>((filename))<\/span><\/td>";
dtemplate+= "<td align=left><a id='fL-((chkidx))' href='((filelink))' title='((filename))'>";
dtemplate+= "<img src=\'<?php echo ROOT_URL.IMAGE_DIR;?>idmdl.gif\'><\/a>((filesize))<\/td>";
dtemplate+= "<td align=right>&nbsp;&nbsp;((formatdate))<\/td>";
dtemplate+= "<td align=right><small>((fileage))<\/small><\/td>";

dtemplate+= "<?php  if($lynx_del){?><td align=center><a id='dL-((chkidx))' title='Delete: ((filename))' href='del.php?d=((b64filename))&lineid=((chkidx))' rel='facebox'> <img src='<?php echo ROOT_URL.IMAGE_DIR;?>rldel.png'><\/a><\/td><?php }?>";
dtemplate+= "<\/tr>";
/* ]]> */
</script><br />
<?php

}else{

   echo "<br /><span class='warn_alert' style='padding:0 100px 0 100px;'><b>{$L->say['tabel_no_file']}</b></span><br />";
}

if($list && $morethanone){
   echo "<a href=\"javascript:;\" onclick=\"flist_match_hide();\">{$L->say['chk_txt_matches']}</a> | ";
}

if($options["show_all"] === true){
?>
<a href="javascript:showAll();"><?php echo $L->say['_show']."&nbsp;";?>
<script type="text/javascript">
if(getCookie("showAll") == 1) {
  d.write("<?php echo $L->say['_downloaded'];?>");
}else {
  d.write("<?php echo $L->say['_everything'];?>");
}
</script></a>
<?php
}else{
?>
<script type="text/javascript">deleteCookie("showAll");</script>
<?php
}
?>

&nbsp;<input class="refresh" onclick="location.reload();" alt="Refresh" title="Refresh" type="image" src="<?php echo ROOT_URL.IMAGE_DIR;?>refresh.png" style="vertical-align:bottom;" />
<table id="main_tbl" border="0">
<tr><td style="text-align:center;">
<?php if($list && $morethanone){ ?>
<div id="flist_match_hitems" style="display:none;clear:both;" class="acthistory_prompt">
<input type="text" title="<?php echo $L->say['chk_txt_matches'];?>" size="20" id="flist_match_search" onkeypress="javascript:if(event.keyCode==13){return flist_match(); }" />
<input type="image" title="<?php echo $L->say['go_match'];?>" style="vertical-align:bottom;padding-bottom:1px;" src="<?php echo ROOT_URL.STATIC_DIR;?>images/btn_search.png" onclick="return flist_match(); return false;" />&nbsp;
<a href="javascript:;" onclick="flist_match_hide();"><small><?php echo $L->say['close'];?></small></a><div>&nbsp;</div>
<span><input type="checkbox" id="flist_match_ins" />
<label for="flist_match_ins"><?php echo $L->say['match_csensitive'];?></label>
<input type="checkbox" id="flist_match_hide" checked="checked" />
<label for="flist_match_hide"><?php echo $L->say['match_hideunmatch'];?></label>
</span>
</div>
<?php } ?>
</td></tr>
<tr><td>

<table id='tblbaru'><tr><td><div id="dtblbaru"></div></td></tr></table>

</td></tr>
</table>
<script type="text/javascript">var dwindow = '<?php echo '_'.substr(md5(time()),0,7).'_'; ?>'; var Opt = new Array(); Opt = {"nC" : "<?php echo $c;?>", "AdL" : "<?php echo $options["auto_del_time"]; ?>", "DelLink" : <?php echo ($lynx_del ? "true" : "false");?>};</script>

<table width="60%" align="center" cellpadding="0" cellspacing="0">
<tbody id="checknavigat" style="display: none;">
<tr id="warn_alert" style="display:none;"><td id="td_warn" align="center"></td></tr>
<tr><td align="center">
<label><input type="checkbox" name="wterm" id="wterm" onclick="javascript:GenTag();" checked="checked" />&nbsp;<?php echo $L->say['_term'];?> </label>&nbsp;&nbsp;
<?php if($lynx_del){?>
<label><input type="checkbox" name="dellnk" id="dellnk" onclick="javascript:GenTag();" />&nbsp;<?php echo $L->say['_deletelink'];?></label>&nbsp;&nbsp;
<?php }?>
<input type="checkbox" name="add_comment" id="add_comment" onclick="javascript:GenTag();" />&nbsp;<a onclick="javascript:var diq=d.getElementById('add_comment');if(!diq.checked){diq.checked=true;}GenTag();"><u><label for="cmtarea"><?php echo $L->say['_genlink'];?></label></u></a>
</td></tr>
<tr id="comment" style="display: none;"><td align="center"><textarea id="cmtarea" name="comment" rows="5" style="color:#666; width:90%; font-family:verdana,lucida,arial; font-size: 7pt;" readonly="yes" onfocus='highlight(this);'></textarea><br /></td></tr>
</tbody>

<?php }?>
<tr align="center"><td id="usage"><br />
<?php
if($options["auto_del_time"]>0){
  echo "<span class=\"c\">".$L->say['_autodel'].":&nbsp;<b class=\"g\">".$options["auto_del_time"]."</b>&nbsp;{$L->say['hours']}</span>";
  purge_files($options["auto_del_time"]);
}
if($options["lowlimitsize"]>0){
  echo "&nbsp;||&nbsp;<span class=\"c\">".$L->say['_minfilesize'].":&nbsp;<b class=\"s\">".$options["lowlimitsize"]."</b>&nbsp;MB</span>";
}
if($options["limitsize"]>0){
  echo "&nbsp;||&nbsp;<span class=\"c\">".$L->say['_maxfilesize'].":&nbsp;<b class=\"s\">".$options["limitsize"]."</b>&nbsp;MB</span>";
}
if(!empty($options["add_ext_5city"])){
  echo "&nbsp;||&nbsp;<span class=\"c\">".$L->say['_fakeext'].":&nbsp;<b><a style=\"color:red;\" href=\"javascript:;\" title=\"{$L->say['_fakeext_desc']}\">.{$options["add_ext_5city"]}</a></b></span>";
}
if($options["limit_timework"]){
  echo "<br /><span class=\"c\">".$L->say['_timework'].":&nbsp;</span><b class=\"s\">{$options["workstart"]}</b>&nbsp;upto&nbsp;<b class=\"s\">{$options["workend"]}</b>";
}
?><br />
<?php
if($options["navi_left"]["server_info"]){
  if(@file_exists(CLASS_DIR."sinfo.php")) {
    require_once(CLASS_DIR."sinfo.php");
	echo "<div style='padding-left:5px;text-align:center;width:auto;margin-top:-5px;'><small>{$server['property']}". $L->sprintf($L->say["page_load"], $maintimer->stop())."</small></div>";
  }
}else echo "<hr />";
?>
 </td>
</tr>
<tr><td><div align="center"><?php print VERSION; ?><hr /></div></td>
</tr></table>

<?php if($list){
?>
<script type="text/javascript">
/* <![CDATA[ */
trparser();
var dtabelbaru = d.getElementById("tblbaru").getElementsByTagName("div")[0];
dtabelbaru.innerHTML = thead + text + tfoot;
d.getElementById('intbl').style.display='';
d.getElementById('checknavigat').style.display='';
gosetfacebook();
/* ]]> */
</script>
<script type="text/javascript" src="<?php echo ROOT_URL.STATIC_DIR;?>sorttable.js"></script>
<?php
}
?></center></body></html>