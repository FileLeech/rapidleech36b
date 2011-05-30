<?php
define('RAPIDLEECH', 'yes');

error_reporting(0);
//error_reporting(E_ALL); 
@ini_set('display_errors', true); 

define('MISC_DIR', 'misc/');
define('CLASS_DIR', 'classes/');
define('CONFIG_DIR', './');
define('LANG_DIR', 'languages/');
@set_time_limit(0);
ini_alter("memory_limit", "1024M");
@ob_end_clean();
//ob_implicit_flush(TRUE);  //mess in t35
ignore_user_abort(1);
clearstatcache();
$PHP_SELF = !isset($PHP_SELF) ? $_SERVER["PHP_SELF"] : $PHP_SELF;

$nn = "\r\n";
$rev_num = '36B.Rv7.3';
$RL_VER = 'Rx08.ii'.$rev_num;

require_once(CONFIG_DIR."config.php");
require_once(CLASS_DIR."other.php");
// Load languages set for audl
$vpage = "audl";
require_once(LANG_DIR."language.$lang.inc.php");
$charSet = (isset($charSet) && !empty($charSet) ? $charSet : 'charset=UTF-8');
define('DOWNLOAD_DIR', (substr($download_dir, 0, 6) == "ftp://" ? '' : $download_dir));
define('TPL_PATH', 'tpl'. '/' . $csstype . '/');
define('IMAGE_DIR', MISC_DIR . TPL_PATH);

$ch_curl = (extension_loaded("curl") ? 1 : 0);
error_reporting(6135);
//===================

//Cek ip yg banned || is it listed as authorized ip || check country limit
if($limited_edition || $limited_area)
{
  $dlimitation = array($limited_edition, $limited_area);
  require_once("limit_district.php");
}

if(!$forbid_audl){
 if ($login===true){
 if(!isset($_SERVER['PHP_AUTH_USER']) || ($loggeduser = logged_user($users)) === false)
	{
		header('WWW-Authenticate: Basic realm="Rx08"');
		header('HTTP/1.0 401 Unauthorized');
		exit("<html>$nn<head>$nn<title>:: $RL_VER ::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\"><style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style>$nn</head>$nn<body>$nn<h1>$RL_VER: NuLL</h1>$nn</body>$nn</html>");
	}
 } 
}else {
 echo "<html>$nn<head>$nn<title>:: $RL_VER ::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\">$nn<style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style></head>$nn<body>$nn<h1>$RL_VER: <br>AuDL Disabled</h1>$nn</body>$nn</html>";
 exit();
}

_create_list();
if($downloadLimitbyip){
 $FilesDownloaded = 0;
 $StorageTaken = 0;
 $FilesDownloadedPerTime = 0;
 $back = $list; 
 $bshow_all = $show_all;
 $show_all = false;
 _create_list(false);
 $show_all = $bshow_all;
 foreach ( $list as $k => $file ) {
   if (isset($file ['ip']) && $file ['ip'] == get_real_ip()) {
	if (time () - $downloadDelayPerIP < $file ['date']) {
	   $FilesDownloadedPerTime ++;
	}
	$FilesDownloaded ++;
   }
 }
 $list2 = $list;
 $list = $back;
}



?>
<!DOCTYPE html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html><head>
<meta http-equiv="Content-Type" content="text/html; <?php echo $charSet;?>">
<title>ADL :: <?php echo $RL_VER;?> ::</title>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR;?>style_sujancok<?php print $csstype;?>.css");
-->
.container td { background-color:#001825; padding:2px;}
div.closer_btn{ background: transparent no-repeat url(<?php print IMAGE_DIR;?>closer.png);}
td.backaudl a div{
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>left_mnu.png);
}
.tdheadolgo { 
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>rl_lgo.png);
}
</style>
<link type="image/gif" rel="shortcut icon" href="<?php echo IMAGE_DIR.'ico_audl.gif?'.rand(11,9999);?>" />
<script type="text/javascript" src="<?php echo MISC_DIR;?>b64.js"></script>
<script type="text/javascript">d = document;</script>
</head>
<body><?php
if($downloadLimitbyip){
 if ($FilesDownloadedPerTime >= $downloadsPerIP ) { 
  html_error( "You have exceeded your download limit, you can only download ". $downloadsPerIP ." files in " . sec1time ( $downloadDelayPerIP ), 0);
 }
}
?>
<div class="head_container"><center>
<a href="<?php echo $index_file;?>" alt="Rapidleech 2.3"><div class="tdheadolgo">&nbsp;</div></a></center>
</div>
<center>
<noscript><p></p><b><?php echo $gtxt['js_disable'];?></b></noscript>
<?php
if($auto_del_time>0)
	{
	echo "<span class=\"c\">".$gtxt['_autodel'].":&nbsp;<b class=\"g\">".$auto_del_time."</b>&nbsp;hour".($auto_del_time>1?"s":"")."</span>";
	//auto_del($auto_del_time);
	purge_files($auto_del_time);
	}
if($audl > 0)
	{
	echo "&nbsp;||&nbsp;<span class=\"c\">Link Allow:&nbsp;<b class=\"g\">".$audl."</b>&nbsp;link".($audl>1?"s":"")."</span>";
	}
if($lowlimitsize>0)
	{
	echo "&nbsp;||&nbsp;<span class=\"c\">".$gtxt['_minfilesize'].":&nbsp;<b class=\"s\">".$lowlimitsize."</b>&nbsp;MB</span>";
	}
if($limitsize>0)
	{
	echo "&nbsp;||&nbsp;<span class=\"c\">".$gtxt['_maxfilesize'].":&nbsp;<b class=\"s\">".$limitsize."</b>&nbsp;MB</span>";
	}
if(!empty($add_ext_5city))
	{
	echo "&nbsp;||&nbsp;<span class=\"c\">".$gtxt['_fakeext'].":&nbsp;<b><a style=\"color:red;\" href=\"javascript:void(0)\" title=\"Auto rename extension with this\">.".$add_ext_5city."</a></b></span>";
	}
if($limit_timework)
	{
	echo "<br><span class=\"c\">".$gtxt['_timework'].":&nbsp;</span><b class=\"s\">".$workstart."</b>&nbsp;".$gtxt['_upto']."&nbsp;<b class=\"s\">".$workend."</b>";
	}
?>
	
<?php
if (isset($_REQUEST["crot"]) && $_REQUEST["crot"] == "step2" && isset($_POST["links"]))
	{
		$getlinks=explode("\r\n",trim($_POST["links"]));
		foreach($getlinks as $key => $value)
		{
			//if(empty($getlinks[$key])) unset($getlinks[$key]);
			$getlinks[$key] = urlcleaner($getlinks[$key]);
			if(!preg_match("/^(http(s?):\/\/|ftp:\/\/{1})+[A-Za-z0-9\-_]+\\.+[A-Za-z0-9\.\/%&=\?\-_]+$/i", trim($getlinks[$key]), $mathces)){
			  unset($getlinks[$key]);			  
			}
		}
		$getlinks = array_values($getlinks);
		if (!count($getlinks) || (trim($_POST["links"]) == ""))
		{
		 echo '<script type="text/javascript">function gotoback(){au=d.location.href; au=au.substring(0, au.indexOf("?")); d.location.href=au;}</script>';
		 die('<br/><br/><span style="color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00"><b>'.$atxt['not_link'].'</b></span><br>'.
			'<script type="text/javascript">setTimeout("gotoback()", 1500);</script>');		 
		}
		
		if($audl>0){  // if there's a limitation in audl link submission
		 if(count($getlinks) > $audl){
		 echo '
<script type="text/javascript">function dopostback(){d.backpost.submit();}</script>
<form action="'.basename($PHP_SELF).'" name="backpost" id="backpost" method="post">
<div style="display:none;"><textarea name="bufferlink" id="bufferlink">'.$_POST["links"].'</textarea></div>
</form>';		 
$atxt['reach_lim_audl'] = str_replace('%link%', $audl, $atxt['reach_lim_audl']);
		 die('<span style="color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00"><b>'.$atxt['reach_lim_audl'].'</b></span><br/><br/><a href="javascript:void(0);" onclick="dopostback()"><b>[ '.$gtxt['back_main'].' ]</b></a>'."\r\n".'</body></html>');
		 }
		}

		$start_link= $index_file.'?';
		for ($i=0; $i<count($getlinks); $i++)
			{   $sLnk = $getlinks[$i];
				$getlinks[$i] = urlcleaner($getlinks[$i]);
			}

		if(isset($_REQUEST["useproxy"]) && $_REQUEST["useproxy"] && (!$_REQUEST["proxy"] || !strstr($_REQUEST["proxy"], ":")))
		    {
	        	die('<span style="color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00"><b>'.$gtxt['wrong_proxy'].'</b></span><br>');
	    	}
	    		else
	    	{
	    		if (isset($_REQUEST["useproxy"]) && $_REQUEST["useproxy"] == "on")
	    			{						
						$start_link.='&proxy='.$_REQUEST["proxy"];
						$start_link.='&proxyuser='.$_REQUEST["proxyuser"];
						$start_link.='&proxypass='.$_REQUEST["proxypass"];
					}
	    	}

		$rnum= rand(11,99);
		
		$start_link.=(isset($_REQUEST["imageshack_acc"]) && $_REQUEST["imageshack_acc"]!=''?'&imageshack_tor='.$_REQUEST["imageshack_acc"]:'').

		(isset($_REQUEST["mu_acc"])?'&mu_acc='.$_REQUEST["mu_acc"]:'').
		(isset($_REQUEST["hf_acc"])?'&hf_acc='.$_REQUEST["hf_acc"]:'').
		(isset($_REQUEST["rs_acc"])?'&rs_acc='.$_REQUEST["rs_acc"]:'').
		
		(isset($_REQUEST["rspre_com"])?'&premium_acc='.$_REQUEST["rspre_com"]:'');
			
		if(isset($_REQUEST["rspre_com"]) && $_REQUEST["rspre_com"] == "on"){
		  $pre_type = $_REQUEST["acc_type"];
		  $pre_user = $_REQUEST["rrapidlogin_com"] ? $_REQUEST["rrapidlogin_com"] : null;
		  $pre_pass = $_REQUEST["rrapidpass_com"] ? $_REQUEST["rrapidpass_com"] : null;
		  $start_link.=($pre_user==null && $pre_pass==null?'&maudl=multi':($pre_type!='' ? '&premium_user='.$pre_user.'&premium_pass='.$pre_pass : '&auth_hash='.encEnti(rotN(base64_encode($pre_user.":".$pre_pass),$rnum)).$rnum) );
		}
		if(isset($_REQUEST["mu_acc"]) && $_REQUEST["mu_acc"] == "on") {
		  $cook = isset($_REQUEST["mu_cookie"]) ? $_REQUEST["mu_cookie"] : $mu_cookie_user_value;
		  $start_link.='&mu_hash='.encEnti(rotN($cook,$rnum)).$rnum;	
		}
		if(isset($_REQUEST["hf_acc"]) && $_REQUEST["hf_acc"] == "on") {
		  $cook = isset($_REQUEST["hf_cookie"]) ? $_REQUEST["hf_cookie"] : $hf_cookie_auth_value;
		  $start_link.='&hf_hash='.encEnti(rotN($cook,$rnum)).$rnum;	
		}
		if(isset($_REQUEST["rs_acc"]) && $_REQUEST["rs_acc"] == "on") {
		  $cook = isset($_REQUEST["rs_cookie"]) ? $_REQUEST["rs_cookie"] : $rs_cookie_enc_value;
		  $start_link.='&rs_hash='.encEnti(rotN($cook,$rnum)).$rnum;	
		}
?>
<script type="text/javascript" src="rscheck.js"></script>
<script type="text/javascript">
	var audl=<?php echo ($audl > 0 ? $audl : 0);?>; //batas max autodownload
	var set_delay=0;
	var delay_=0;
	var current_dlink=-1;
	var current_status=-1;
	var current_iframe=0;
	var quotaframe = <?php echo (is_numeric($iframealocate) ? (int)$iframealocate : 5);?>;  // How many iframe you will provide
	var last_status = new Array();
	var links = new Array();
	var idwindow = new Array();
	var iframe_ready = new Array();	
	var start_link='<?php echo $start_link; ?>';
	var isAuto = false;
	var StrMethod = "";
	var metAudl = 0;  // queue atau simultan
	var tmrNext; var auto_check = <?php echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"]=="on" ? 'true' : 'false');?>

	Array.prototype.inArray = function(valeur) {
	 for (var i in this) { if (this[i] == valeur) return i; }
	 return -1;
	}
	
	function IsNumeric(input){
     var RE = /^-{0,1}\d*\.{0,1}\d+$/;
     return (RE.test(input));
	}
	
<?php if($audl > 0){
// protect audl being hijack with direct set value from url
?>	
function forceAudl(){if(audl!=<?php echo $audl;?>){audl=<?php echo $audl;?>;};setTimeout("forceAudl()", 100);}	
<?php }	?>
	
	function chk_readyiframe(){
		var avliframe = -1;
		var i=-1;
		while((i < quotaframe) && (avliframe == -1))
		{
			i++;
			avliframe = iframe_ready.inArray(Math.round(i));
		}
		current_iframe = i;
	}
	
	function download(idx)
		{
			//check if the iframe ready
			chk_readyiframe();
			if(current_iframe == quotaframe){alert(quotaframe+' Quota iframe reached. All iframe is busy..'); return;}
			isAuto = false;
			StrMethod = "&idx=|manual";
			opennewwindow(idx);
			d.getElementById('audlbutton').style.display='none';
			d.getElementById('dButton'+idx).style.display='none';
			var methtxt = 'Method <b class="g">Manual</b>';
			d.getElementById('curmetode').innerHTML = methtxt;
			d.getElementById('action'+idx).innerHTML='&nbsp;<?php echo $atxt["_started"];?>&nbsp;';
		}
	
	
	function startisdone(id)
	{
		d.getElementById('action'+id).innerHTML='<b class="g"><?php echo $atxt['_done'];?></b>';
	}
	
	function startauto()
		{
			//bring back simultan audl
			isAuto = true;
			metAudl=d.getElementById('metAudl').value;
			delay_ = d.getElementById('delay').value;
			if (metAudl==1 && (!((delay_>=1) && (delay_<=3600)) || !IsNumeric(delay_)))
				{
					alert('<?php echo $atxt["error_interval"]?>');
					return;
				}
			
			//bypass limit audl links brings auto button disapear, return false
			if(audl > 0 && links.length > audl){			 
			   alert('<?php echo str_replace('%link%', $audl, $atxt['reach_lim_audl']);?>');
			   d.getElementById('audlbutton').style.display='none';
			   return false;			 
			}
				
			set_delay = delay_*1000;
			
			current_dlink = -1;
			StrMethod = "&idx=auto|";
			if(metAudl==0){StrMethod = StrMethod + 'queue';}else{StrMethod = StrMethod + 'sims';}
			d.getElementById('audlbutton').style.display='none';
			
			for(var i=0; i<links.length; i++)
				{
					d.getElementById('dButton'+i).style.display='none';
					d.getElementById('action'+i).innerHTML='&nbsp;<?php echo $atxt['_waiting'];?> ';	
				}
			nextlink(current_dlink);
		}

	function cdown(tgt, dlay)
	{
		if(dlay>0){
		 dlay = dlay - 1;
		 d.getElementById('action'+tgt).innerHTML='&nbsp;<b>~<?php echo $atxt['_waiting'];?><span class="g">'+ dlay +'</span></b>';
		 tmrNext = setTimeout("cdown("+tgt+", "+dlay+")", 1000);
		}else{
		 clearTimeout(tmrNext);
		 nextlink(tgt);
		}
	}
	
	function nextlink(ids)
		{
			if(metAudl==0){
			  if(current_dlink>-1){d.getElementById('action'+current_dlink).innerHTML='<b class="g"><?php echo $atxt['_done'];?></b>';}
			} 
			current_dlink++;
			if (current_dlink < links.length)
				{
					setTimeout('opennewwindow(current_dlink)', 300);
					d.getElementById('action'+current_dlink).innerHTML='&nbsp;<?php echo $atxt["_started"];?>&nbsp;';
					if(metAudl==1){
					 if(current_dlink<links.length-1){
						cdown(current_dlink+1, delay_);
					 }
					}
				}
		}

		function opennewwindow(id)
		{
		  if (!isAuto || metAudl==0){
		    var _tbliframe = d.getElementById('tbliframe').style.display = 'block';
			var _closeiframe = d.getElementById('closeiframe');
			var _auiframe = d.getElementById('auiframe');
			var _autitleiframe = d.getElementById('titleiframe');
			var _dwidth;
			if (window.innerWidth || window.innerHeight){ 
				//opera Netscape 6 Netscape 4x Mozilla 
			   _dwidth = eval(window.innerWidth * 70/100) + 'px';
			}
			if (document.body.clientWidth || document.body.clientHeight){ 
				////IE Mozilla 
				_dwidth = eval(document.body.clientWidth * 70/100) + 'px';
			}
		  }
		  if(!isAuto){  // Start Manual mode
			_closeiframe.style.display = 'none';
			_auiframe.style.display = 'none';
			_autitleiframe.style.display = 'none';
			var _diviframe = d.getElementById('perdiv' + current_iframe);
			var _iframe = d.getElementById('frmaudl' + current_iframe);
			var _titleiframe = d.getElementById('ifrmlink' + current_iframe);
			var dum_iframe = new Array(), posCur = 0;
			
			_diviframe.style.display = 'block';
			_titleiframe.style.width = _dwidth;
			_iframe.style.width = _dwidth;
			_titleiframe.innerHTML = '<span class="g" id="idxlink' + current_iframe + '" style="padding-right:10px;">'+eval(id+1)+'</span><b>'+ links[id] +'</b>';
			posCur = iframe_ready.inArray(Math.round(current_iframe));
			iframe_ready.splice(posCur, 1);
			d.getElementById('iframealocatevalue').innerHTML = iframe_ready.length + ' of ' + quotaframe;			
			_iframe.src = start_link + '&link='+strrev(unescape(links[id])) + StrMethod;
				
		 }else{
		  if(metAudl==0){ // Start Auto ~ Queue mode
			
			_auiframe.style.width = _dwidth;
			_autitleiframe.style.width = _dwidth;
			_autitleiframe.innerHTML = '<b><span class="g" style="padding-right:10px;">'+eval(id+1)+'</span>'+ links[id] +'</b>';
			_auiframe.src = start_link+'&link='+strrev(unescape(links[id]))+StrMethod;
			_closeiframe.style.display = 'block';
			_auiframe.style.display = 'block';
			d.getElementById('iframealocate').style.display = 'none';
		  }
		  else{ // Start Simultan Download ~ open window popup
			var useloc = "<?php echo (isset($_REQUEST["rspre_com"])?'no':'yes');?>"; var options = "width=700,height=300,toolbar=no,location="+useloc+",directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no";
			idwindow[id] = window.open(start_link+'&link='+strrev(unescape(links[id]))+StrMethod, id, options);
			idwindow[id].opener=self;
			idwindow[id].focus();
		  }
		 }
		} // end opennewwindow
	
	
	function addLinks(txtA)
	{
		var tbody = d.getElementById("links").getElementsByTagName("tbody")[0];
		Obj_txta = d.getElementById(txtA); try{Obj_txta.focus();}catch(e){}
		var stringLinks = Obj_txta.value; var link_sisa = 0;

		if(audl>0){
		 var tbody_row = tbody.getElementsByTagName("tr");
		 link_sisa = eval(audl - tbody_row.length);
		 if(link_sisa==0 || link_sisa<0){
		  alert('<?php echo str_replace('%link%', $audl, $atxt['reach_lim_audl']);?>');
		  return false;
		 }
		}
		arrayLinks = LnkChkHandler(stringLinks, link_sisa);
		
		for (var i = 0; i < (arrayLinks.length); i++)
		{
		  arrayLinks[i] = arrayLinks[i].replace(/^\s+|\s+$/g,"");
		  if(arrayLinks[i]!=""){
		    arrayLinks[i] = jlnkfilter(arrayLinks[i]);
			var row = d.createElement("tr");
			var td1 = d.createElement("td");			
			td1.appendChild(d.createTextNode(arrayLinks[i]));
			td1.setAttribute("align", "right");
			var td2 = d.createElement("td");
			var butn = d.createElement('button');
			td2.setAttribute("align", "center");
			td2.setAttribute("id", "action"+links.length);
			butn.appendChild(d.createTextNode('<?php echo $atxt['_download'];?> '));
			butn.setAttribute("onClick", "javascript:download("+links.length+");");
			butn.setAttribute("id", "dButton"+links.length);
			td2.appendChild(butn);
			var td3 = d.createElement("td");
			<?php if(isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"]=="on"){ ?>
			var imgLd = d.createElement("img");
			imgLd.setAttribute("src", "<?php echo IMAGE_DIR.'fbload.gif'?>");
			td3.appendChild(imgLd); 
			td3.setAttribute("id", "status"+links.length);
			td3.setAttribute("align", "center");
			<?php }?>
			
			row.appendChild(td1);
			row.appendChild(td2);
			row.appendChild(td3);
			tbody.appendChild(row);
			
			links[links.length] = arrayLinks[i];
		  }
		  else{
		    arrayLinks.length--;
		  }
		}
		Obj_txta.value = ""; 
		startFrm = links.length - arrayLinks.length;		
		setTimeout("Obj_txta.blur()", 100); <?php echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"]=="on" ? "d.getElementById('btnaddlinks').disabled=true; loadHandler();":"");?>
	} //end addLinks
		
		
	function iframeReady(idx_ifr){
		iframe_ready.splice(0,0,idx_ifr);
		d.getElementById('iframealocatevalue').innerHTML = iframe_ready.length + ' of ' + quotaframe;
	}
	
	function clDIV(id){
		var divframe = d.getElementById("perdiv"+id);
		var _iframe = d.getElementById('frmaudl' + id);
		_iframe.src = 'about:blank';
		divframe.style.display = 'none';
	}
	
	function showIFrame()
	{
		var divframe = d.getElementById("divframeset");
		
		var ifrm, div, perdiv;
		var IMAGE_DIR = '<?php print IMAGE_DIR;?>';
		divframe.innerHTML = '';
		
		for (var i = 0; i < quotaframe; i++){
		 perdiv = d.createElement('div');
		 perdiv.setAttribute("id", "perdiv"+i);
		 perdiv.setAttribute("style", "display:none;border:.1em solid rgb(102, 102, 102);padding:5px;");
		 
		 div = d.createElement('div');
		 div.setAttribute("class", "g");
		 div.setAttribute("id", "ifrmlink"+i);
		 div.setAttribute("style", "padding-bottom:5px; font-weight:bold; float:left;");
		 div.appendChild(d.createTextNode('Link '+i));
		 perdiv.appendChild(div);
		 div = d.createElement('div');
		 div.setAttribute("class", "closer_btn");
		 div.setAttribute("style", "position:relative; left:49%; margin-top:-2px;");
		 div.setAttribute("onclick", "javascript:clDIV("+i+");");
		 div.setAttribute("title", "Close this iframe");
		 
		 ca = d.createElement('a');
		 ca.setAttribute("id", "close"+i);
		 ca.setAttribute("href", "javascript:void(0);");
		 
		 ca.innerHTML = "&nbsp;";
		 div.appendChild(ca);
		 perdiv.appendChild(div);
		 ifrm = d.createElement("iframe");
		 ifrm.id = "frmaudl" + i;
		 ifrm.name = "frmaudl" + i;
		 ifrm.height = '190px';
		 
         if (document.all) {
          // MSIE, opera
            ifrm.src = "about:blank";
            perdiv.appendChild(ifrm);
         } else {
            perdiv.appendChild(ifrm);
            ifrm.src = "about:blank";
         }		 
		 
		 divframe.appendChild(perdiv);
		 iframe_ready[i] = i;
		}
		d.getElementById('tbliframe').style.display = 'block';
	}  //end showIFrame
		
		
<?php
		
		for ($i=0; $i<count($getlinks); $i++)
			{
				echo "\tlast_status[$i]=''; links[".$i."]='".($getlinks[$i])."';\n";
			}
?>
	
var rslinks, startFrm = 0;
function reLink() {
	startFrm = 0;
	d.getElementById('btnaddlinks').disabled=true;
	for(var i=0; i<links.length; i++)
	{
		d.getElementById('statusTitle').innerHTML="&nbsp;<b><blink>Status</blink></b>&nbsp;";
		d.getElementById('status'+i).innerHTML='&nbsp;<img src="<?php echo IMAGE_DIR.'fbload.gif'?>">&nbsp;';
	}

}

function loadHandler () {
	rslinks = "";
	for(var i=startFrm; i<links.length; i++)
	{
		rslinks+=links[i] + ";";
	}
	getResults(rslinks, links, 'audl');
}

function highlight(field) {
        field.focus(); field.select(); }

function hEnter(inField, e) {
    var chCode;
    if(e && e.which){
        chCode = e.which;
    }else if(window.event){
        e = window.event;
        chCode = e.keyCode;
    }
    if(chCode == 13) {
		startauto();
    }
}
	
var btnaudl;
	function radioaudl(id)
	{
		var dv=d.getElementById('divdelay');
		var meth=document.getElementById('metAudl'), methtxt = 'Method ';
		if(id=="metodaudl0")  // Queue method selected
		{
			dv.style.display = "none";
			meth.value = "0";
			d.getElementById('spanobo').style.background = "#800000";
			d.getElementById('spanobo').style.color = "#AAAAAA";
			d.getElementById('spansim').style.background = "";
			methtxt += '<b class="g">Queue</b>';
		}else{
			dv.style.display = "";
			dv = document.getElementById('delay');
			dv.focus();
			dv.select();
			meth.value = "1";
			d.getElementById('spanobo').style.background = "";
			d.getElementById('spansim').style.background = "#800000";
			d.getElementById('spansim').style.color = "#AAAAAA";
			methtxt += '<b class="g">Simultan</b>';
		}
		var _btnaudl = btnaudl;
		document.getElementById('audlstart').value = _btnaudl + ' [' + document.getElementById(id).value + ']';
		document.getElementById('curmetode').innerHTML = methtxt;
	}

<?php if($audl > 0){?>
forceAudl();
<?php }?>
</script>

<table id="links" width=90% style="border:1px solid #666" class="audlcontainer" cellspacing="0" cellpadding="0">
<thead><tr><td width=80% align="center"><b><?php echo $atxt['_link'];?> </b></td><td width=70 align="center">&nbsp;<b><?php echo $gtxt['action'];?></b>&nbsp;</td><td width=70 id='statusTitle' align="center">&nbsp;<b><?php echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"]=="on" ? "<blink>":""); echo $atxt['_status'];echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"]=="on" ? "</blink>":"")?></b>&nbsp;</td></tr></thead>
<tbody>
<?php
		for ($i=0; $i<count($getlinks); $i++)
			{
				echo "<tr><td width=80% nowrap align='right' id=row".$i."><span>".$getlinks[$i]."</span></td>";
				echo "<td width=70 align='center' id='action".$i."'><button onClick=javascript:download($i); id=dButton".$i.">Download</button></td>";
				echo "<td width=70 align='center' id='status".$i."'>&nbsp;".(isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"]=="on" ? "<img src='".IMAGE_DIR."fbload.gif'>":"")."&nbsp;</td>";
				echo "</tr>\n";
			}
?>
</tbody>
<tfoot id="audlbutton">
<tr id='auto'><td colspan='3' align='center'><div style="padding-top:10px;"></div><input type='button' id='audlstart' value='<?php echo $atxt['audl_start'];?>' onClick='javascript:startauto();'>
<script type="text/javascript">btnaudl = d.getElementById('audlstart').value;</script>
</td></tr>
<tr><td colspan="3" align='center'>
	<table border='0' cellspacing="0" cellpadding="0">
	<tr><td width="220" valign="top">&nbsp;</td>
	<td width="140" valign="top"><label><input type='radio' name="metodaudl" id="metodaudl0" value="Queue" onclick="radioaudl(this.id)" onkeypress="hEnter(this, event);" checked=true/>&nbsp;<span id="spanobo" style="background-color: #800000;color:#AAAAAA;">One-by-one (Queue)</span></label><input type="hidden" id="metAudl" value="0"></td>
	<td width="300" valign="top"><label><input type=radio name="metodaudl" id="metodaudl1" value="Simultanious" onclick="radioaudl(this.id)"/>&nbsp;<span id="spansim"style="color:#AAAAAA;">Simultanious</span></label></td></tr>
	<tr><td></td>
	<td colspan="2"><div id="divdelay" style="padding-left:50px; display:none;">Intervals (1 ... 3600)&nbsp;<input type="text" name="delay" id="delay" value="30" size="10" onfocus="highlight(this);" onkeypress="hEnter(this, event);" style="padding-left:3px;"/>&nbsp;<?php echo $gtxt["_second"]?></div>
		</td></tr>
	</table>
</td></tr>
</tfoot>
</table>
<div style="display:none">
 <input type="checkbox" value="lnkcurl" name="lcurl" id="lcurl"<?php echo (($ch_curl == 1)?' checked=checked':' disabled=disabled');?>>
</div>
<script type="text/javascript">
 <?php if(isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"]=="on"){?>loadHandler();<?php }?>
 function dopostback(){ d.backpost.submit(); }
 function ifrmClose_audl(){d.getElementById("auiframe").src="about:blank"; d.getElementById("tbliframe").style.display="none"; }
</script>

<form action="<?php echo basename($PHP_SELF);?>" name="backpost" id="backpost" method="post">
<div style="display:none;"><textarea name="bufferlink" id="bufferlink"><?php print $_REQUEST["links"];?></textarea></div>
</form>

<span id="curmetode">Method <b class="g">Queue</b></span>
<span id="iframealocate" title="Manual Method property">&nbsp;&nbsp;&#8212;&nbsp;&nbsp;IFrame ready: <b class="g" id="iframealocatevalue"><?php echo $iframealocate." of ".$iframealocate?></b></span>
<table style="border:1px solid #666" class="container">
<tr>
<td valign="top" class="backaudl">
<a href="javascript:void(0);" onclick="dopostback();" title="Back to Autodl" alt="Back to Autodl"><div id="left_audl"></div></a>
</td>
<td><textarea name="addlinks" id="addlinks" class="redtxtarea" style="width:640px; height:20px;" onfocus="if(this.value=='add more link here...'){this.value='';}this.style.height=100;" onblur="if(this.value==''){this.style.height=20; this.value='add more link here...'}">add more link here...</textarea></td>
<td valign="top"><input type="button" id="btnaddlinks" value="<?php echo $atxt['add_link'];?> " onclick="javascript:addLinks('addlinks');" <?php echo (isset($_REQUEST["autochk_lnk"]) && $_REQUEST["autochk_lnk"]=="on" ? "disabled=true":"");?>></td>
</tr>
</table>

<table id="tbliframe" width="70%" style="display:none; padding-top:15px;">
<tr>
<td width="100%"><div id="titleiframe"></div></td>
<td align="right" id="closeiframe" style="display:none;">
<div class="closer_btn" onclick="ifrmClose_audl()" title="Close this iframe"></div>
</td>
</tr>
<tbody>
<tr><td colspan="2" align="center" width="100%">
<iframe id="auiframe" name="auiframe" src="about:blank" style="display:none;width:100%;">Frames not supported, update your browser</iframe>
<div id="divframeset"></div>
</td></tr>
</tbody>
</table>



<script type="text/javascript" type="text/javascript">
 showIFrame(); 
</script>
</body>
</html>
<?php
	exit();  // step2 end here
	}
	
//SHOW TIME WORK
if($limit_timework){
    $is_worktime = cek_worktime($workstart, $workend);
	$limitmsg="";
	if(!$is_worktime){
	  if(!empty($limitmsg)){$limitmsg.="<br>";}$limitmsg.=$gtxt['worktime_alert'];
	  echo "<div style=\"padding-top:20px;padding-bottom:20px;\"><div class='warn_alert'>$limitmsg</div></div>";
	  if($server_info){if(@file_exists(CLASS_DIR."sinfo.php")) require_once(CLASS_DIR."sinfo.php");}else echo "<hr>";
	  echo "</body></html>";
	  exit();
	
	}
}
	
?>
<script type="text/javascript">
function ViewPage(page)	{
	vcurent = d.getElementById(page);
	vcurent.style.display = (vcurent.style.display=='none'?'block':'none');
	cHeight=d.getElementById('links').style.height.replace('px','');
	if(vcurent.style.display=='block'){
	 vHeight = (cHeight / 2) + 19 + 'px';
	}else{
	 vHeight = ((cHeight-19) * 2) + 'px';
	}
	d.getElementById('links').style.height=vHeight;
 }
function HideAll(){
	d.getElementById('entered').style.display='none';
}
function highlight(field) {
    field.focus(); field.select(); 
}
</script>
<table cellspacing="0" cellpadding="1" id="entered" class="audlcontainer"><tr><td>
<form action="<?php echo $PHP_SELF;?>?crot=step2" method="post">
<table width="700" border="0">
<tr id="menu"><td width="700" align="center">
 <a href="javascript:ViewPage('options');"><?php echo $atxt['_opt'];?> </a>
</td></tr>
<tr> <td width="100%" valign="top">
<div id="listing" style="display:block;">
<table border="0" style="width:710px;" >
<tbody>
<tr>
<td>
<div width="100%" id="options" style="display:none;">
    <table cellspacing="5">
      <tbody>
      <tr>
        <td align="center">

          <table align="center">
		    <tr>
			 <td><label><input type="checkbox" id="autochk_lnk" name="autochk_lnk" <?php echo ($autochecklink ? 'checked="checked"':"");?>>&nbsp;<?php echo $atxt['auto_check_link'];?></label>
			 </td>
		    </tr>
            <tr>
              <td width="320">
                <label><input type="checkbox" id="useproxy" name="useproxy" onClick="var displ=this.checked?'':'none';document.getElementById('proxy').style.display=displ;"<?php echo isset($_COOKIE["useproxy"]) ? " checked" : ""; ?>>&nbsp;<?php echo $gtxt['use_proxy'];?></label>
				<table border=0 id="proxy" style="padding-left:22px; display: none;">
                  <tr><td><?php echo $gtxt['_proxy'];?></td><td><input type="text" name=proxy size=25<?php echo isset($_COOKIE["proxy"]) ? " value=\"".(isset($_COOKIE["proxy"])?$_COOKIE["proxy"]:"")."\"" : ""; ?>></td></tr>
                  <tr><td><?php echo $gtxt['_uname'];?></td><td><input type="text"name=proxyuser size=25 <?php echo isset($_COOKIE["proxyuser"]) ? " value=\"".(isset($_COOKIE["proxyuser"])?$_COOKIE["proxyuser"]:"")."\"" : ""; ?>></td></tr>
                  <tr><td><?php echo $gtxt['_pass'];?></td><td><input type="text" name=proxypass size=25 <?php echo isset($_COOKIE["proxypass"]) ? " value=\"".(isset($_COOKIE["proxypass"])?$_COOKIE["proxypass"]:"")."\"" : ""; ?>></td></tr>
                </table>				
              </td>
            </tr>
			<tr><td><label><input type="checkbox" value="on" name=imageshack_acc id=imageshack_acc <?php if (isset($imageshack_acc) && is_array($imageshack_acc)) print ' checked'; ?>>&nbsp;<?php echo $atxt['acc_imgshack'];?></label></td>
			</tr>
            <?php
            if ($maysaveto === true)
                    {
            ?>
            <tr>
              <td>
                <label><input type="checkbox" name=saveto id=saveto onClick="javascript:var displ=this.checked?'':'none';document.getElementById('path').style.display=displ;"<?php echo isset($_COOKIE["saveto"]) ? " checked" : ""; ?>>&nbsp;<?php echo $gtxt['save_to'];?></label>
				<table id="path" <?php echo isset($_COOKIE["saveto"]) ? "" : " style=\"display: none;\""; ?>><tr>
				 <td style="padding-left:22px;">
                 <input name=savedir size="55" value="<?php echo realpath((isset($_COOKIE["savedir"]) ? (isset($_COOKIE["savedir"])?$_COOKIE["savedir"]:"") : (strstr(realpath("./"), ":") ? addslashes($workpath) : $workpath))) ?>">
				 </td></tr>
				</table>
			  </td>
            </tr>
            <?php
                    }
		 $ar_host_acc = array(
		  "rs_com"=>"rapidshare.com",
		  "rs_de"=>"rapidshare.de",
		  "megaupload"=>"megaupload.com",
		  "megashare"=>"megashare.com",
		  "netload"=>"netload.in",
		  "gigasize"=>"gigasize.com",
		  "share_online"=>"share_online.com",
		  "uploaded_to"=>"uploaded.to",
		  "easyshare"=>"easy-share.com",
		  "depositfiles"=>"depositfiles.com",
		  "hotfile_com"=>"hotfile.com",
		  "uploading"=>"uploading.com",
		  "filefactory"=>"filefactory.com",
		  "ifile_it"=>"ifile.it",
		 );
$ada_acc = (isset($premium_acc) && is_array($premium_acc));
 if($ada_acc){
   foreach($premium_acc as $dhost => $val){
	if($val){$ada_acc=true; break;}
   }
 }
            ?>
            <tr>
			  <td>
                <input type="checkbox" value="on" name="rspre_com" id="rspre_com" onClick="javascript:var displcom=this.checked?'':'none';document.getElementById('rapidblockcom').style.display=displcom;"<?php if(isset($premium_acc_audl) && $ada_acc && $premium_acc_audl) print ' checked';?>>&nbsp;<label for="rspre_com"><?php echo $gtxt['use_premix'];?></label>
                <table id="rapidblockcom" width="150" border="0" <?php echo isset($_COOKIE["rspre_com"]) ? "" : " style=\"display: none;\""; ?>>
                 <tr>
				  <td style="padding-left:22px;"><label for="acc_type"><?php echo "Type: ";?></label></td>
				  <td>
<select name="acc_type" id="acc_type"><?php
foreach($ar_host_acc as $khost => $nmhost){
 echo '<option value="'.$khost.'">'.$nmhost.'</option>';
}
?>
</select>
				  </td>
				 </tr>
                 <tr><td style="padding-left:22px;"><label for="rpl"><?php echo $gtxt['_uname'];?></label></td><td><input type="text" id="rpl" name=rrapidlogin_com size="20" onFocus="highlight(this);" value="<?php echo (isset($_COOKIE["rrapidlogin_com"]) ? $_COOKIE["rrapidlogin_com"] : ""); ?>"></td></tr>
                 <tr><td style="padding-left:22px;"><label for="rppl"><?php echo $gtxt['_pass'];?></label></td><td><input type="text" id="rppl" name="rrapidpass_com" size="20" onFocus="highlight(this);" style="color: #912704;" value="<?php echo (isset($_COOKIE["rrapidpass_com"]) ? $_COOKIE["rrapidpass_com"] : ""); ?>"></td></tr>
                </table>
			  </td>
            </tr>
            <tr>
              <td>
                <input type="checkbox" value="on" name="mu_acc" id="mu_acc" onClick="javascript:var displcom=this.checked?'':'none';document.getElementById('mprblok').style.display=displcom;" <?php if (isset($mu_cookie_user_value) && isset($premium_acc_audl) && $premium_acc_audl) print ' checked'; ?>>&nbsp;<label for="mu_acc"><?php echo $atxt['plugin_megaupl'];?></label>
                <table width="150" id="mprblok" style="display: none;">
                 <tr><td style="padding-left:22px;"><label for="rplmu"><?php echo $atxt['_user'];?></label></td><td><input type="text" id="rplmu" size="45" name="mu_cookie" onFocus="highlight(this);" value=""></td></tr>
                </table>
			  </td>
            </tr>
            <tr>
              <td>
                <input type="checkbox" value="on" name="hf_acc" id="hf_acc" onClick="javascript:var displcom=this.checked?'':'none';document.getElementById('hfprblok').style.display=displcom;" <?php if (isset($hf_cookie_auth_value) && isset($premium_acc_audl) && $premium_acc_audl) print ' checked'; ?>>&nbsp;<label for="hf_acc"><?php echo $atxt['plugin_hotfile'];?></label>
                <table width="150" id="hfprblok" style="display: none;">
                 <tr><td style="padding-left:22px;"><label for="rplhf"><?php echo $atxt['_auth'];?></label></td><td><input type="text" id="rplhf" size="45" name="hf_cookie" onFocus="highlight(this);" value=""></td></tr>
                </table>
			  </td>
            </tr>			
            <tr>
              <td>
                <input type="checkbox" value="on" name="rs_acc" id="rs_acc" onClick="javascript:var displcom=this.checked?'':'none';document.getElementById('rsprblok').style.display=displcom;" <?php if (isset($rs_cookie_enc_value) && isset($premium_acc_audl) && $premium_acc_audl) print ' checked'; ?>>&nbsp;<label for="rs_acc"><?php echo $atxt['plugin_rs'];?></label>
                <table width="150" id="rsprblok" style="display: none;">
                 <tr><td style="padding-left:22px;"><label for="rplrs"><?php echo $atxt['_enc'];?></label></td><td><input type="text" id="rplrs" size="45" name="rs_cookie" onFocus="highlight(this);" value=""></td></tr>
                </table>
			  </td>
            </tr>
			
          </table>
        </td>
      </tr>
      </tbody>
    </table>
</div>
<textarea id="links" name="links" class="redtxtarea" style="width:640px; height:250px;"><?php echo (isset($_REQUEST["bufferlink"]) ?$_REQUEST["bufferlink"]:"");?></textarea></td>
<td valign="top"><input type="submit" value="<?php echo $atxt['_download'];?> " onClick="javascript:HideAll();" style="width:60px;height:80px;"></td>
</tr>
</tbody>
</table>
</div>

</td></tr>
</table>
</form>
</td></tr>
</table>

</center>
<img src="<?php print IMAGE_DIR;?>chk_good.png" style="position:absolute; left:-100;"/><img src="<?php print IMAGE_DIR;?>fbload.gif" style="position:absolute; left:-100;"/>
</body>
</html>
