<?php
if (!defined('RAPIDLEECH'))
 {require_once("404.php"); exit;}


function auto_del($delay = 0)
{
    global $auto_del_time, $forbidden_filetypes;
	if($delay==0){$delay = $auto_del_time;}
    $current_time = getdate();
    $dir = @dir(DOWNLOAD_DIR);
	
    while(false !== ($file = $dir->read()))
      {
      if($file != "." && $file != ".." && !in_array(strtolower(strrchr($file, ".")), $forbidden_filetypes) && substr($file,-4,4)!=".htm" && substr($file,-5,5)!=".html" && $file!=FILES_LST && $file!=".htaccess")
        {
         $time = filemtime(DOWNLOAD_DIR.$file);
         if($delay>0){
                if(($time + ($delay*60*60)) < $current_time["0"]){
				 @unlink(DOWNLOAD_DIR.$file);
				}
			}
        }
      }
    $dir->close();
}

function ipcounter()
{
  global $heute, $delay_per_ip, $ipmu;
  $xhandle=@opendir("tmp");
  if($xhandle)
  {
	while($buin = readdir($xhandle)) {
		if(eregi("$ipmu",$buin)) { $heute=$heute+1; }
	}
	$ckusr = decEnti((string)base64_decode(urldecode($_COOKIE['rxyzusr'])));
	$ckusr = (is_numeric($ckusr) ? $ckusr : '0');
	if(($ckusr!='')&&($ckusr > $heute)){
		$heute = $ckusr;
	}
	else{
		setcookie('rxyzusr',base64_encode(encEnti((string)$heute)),time() + ($delay_per_ip*60*60));
	}
	closedir($xhandle);

	error_reporting(0);
	$time=time();
	$xhandle=@opendir("tmp");
	while($buin = readdir($xhandle)) {
		if($buin=="." || $buin=="..") {$buin="fu"; }		
		$xd=filemtime("tmp/$buin");
		$altr=$time-$xd;
		if($altr>$delay_per_ip*60*60) {
			if(is_dir("tmp/$buin")) {
				rmdir("tmp/$buin"); 
			}
			else {
				unlink("tmp/$buin"); 
			}
		}
	}
	closedir($xhandle);error_reporting(1);
  }
}

function rotN($s, $n){
    $s2 = "";
    for($i = 0; $i < strlen($s); $i++){
        $char2 = $char = ord($s{$i});
        $cap = $char & 32;
        $char &= ~ $cap;
        $char = $char > 64 && $char < 123 ? (($char - 65 + $n) % 26 + 65) : $char;
        $char |= $cap;
        if($char < 65 && $char2 > 64 || ($char > 90 && $char < 97 && ($char2 < 91 || $char2 > 96))) $char += 26;
        else if($char > 122 && $char2 < 123) $char -= 52;
        if(strtoupper(chr($char2)) === chr($char2)) $char = strtoupper(chr($char)); else $char = strtolower(chr($char));
        $s2 .= $char;
    }
    return $s2;
}

function encEnti($v){
  $rt = "";
  for($i=0; $i < strlen($v); $i++){
	$ktr = strtoupper(dechex(ord($v{$i})));
	$rt.=$ktr; 
  }
  return $rt;
}

function decEnti($c){
  $pt = "";
  $pj = strlen($c); $i=0;
  if($pj % 2!=0){return false;}
  while($i<$pj){
    $hs = chr(hexdec(substr($c, $i, 2)));
    $pt.=$hs;
    $i= $i + 2;
  }
  return $pt;
}

function get_real_ip()
{
     $ipRL = false;
     if(!empty($_SERVER['HTTP_CLIENT_IP']))
     {
          $ipRL = $_SERVER['HTTP_CLIENT_IP'];
     }
     if(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
     {
          $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
          if($ipRL)
          {
               array_unshift($ips, $ipRL);
               $ipRL = false;
          }
          for($i = 0; $i < count($ips); $i++)
          {
               if(!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i]))
               {
                    if(version_compare(phpversion(), "5.0.0", ">="))
                    {
                         if(ip2long($ips[$i]) != false)
                         {
                              $ipRL = $ips[$i];
                              break;
                         }
                    }
                    else
                    {
                         if(ip2long($ips[$i]) != - 1)
                         {
                              $ipRL = $ips[$i];
                              break;
                         }
                    }
               }
          }
     }
     return ($ipRL ? $ipRL : $_SERVER['REMOTE_ADDR']);
}

//Check allow / Banned IP Address
function chk_this_ip($meth, $user_ip)
{
	global $list_allow_ip, $list_baned_ip;
	$ip_allow_ar = array();

	if($meth=='is_baned') 
	{
		$list_ip = $list_baned_ip;
	}
	elseif($meth=='is_allow')
	{
		$list_ip = $list_allow_ip;
	}

	$ip_allow_ar = explode(',', $list_ip);
	ksort($ip_allow_ar);
	$check_is = false;
	$ada_bintang = 0; 
	$i=0;

	if($meth=='is_allow' && !$ip_allow_ar[$i]){exit('Terminated.!<br>RL Authorization malfunction:: <b>$list_allow_ip</b> is_empty in (config.php)');}
	
	while(!$check_is && ($ip_allow_ar[$i]))
	{
		$ada_bintang = ($ip_allow_ar[$i]!='*.*.*.*' ? substr_count($ip_allow_ar[$i], '.*') : 4);		
		if($ada_bintang>0)
		{
			if($ada_bintang!=4) {
			 $sub_ip = substr($ip_allow_ar[$i], 0, strpos($ip_allow_ar[$i],'.*')).".";
			 $u_ip_ar = explode('.', $user_ip); 
			 $bit = (int)(3-$ada_bintang);
			 $u_ip = '';
			 for($j=0; $j<=$bit; $j++)
			 {
				$u_ip = $u_ip.$u_ip_ar[$j].".";
			 }
			 if(trim($u_ip)==trim($sub_ip)){$check_is = true;}
			}else{
			 $check_is = true;
			}			
		}
		else
		{
			if(trim($user_ip)==trim($ip_allow_ar[$i])){$check_is = true;}
		}
		$i++;
	}

	return $check_is;
}




function view_size($size)
{
 if (!is_numeric($size)) {return FALSE;}
 else
 {
  if ($size >= 1073741824) {$size = round($size/1073741824*100)/100 ."&nbsp;GB";}
  elseif ($size >= 1048576) {$size = round($size/1048576*100)/100 ."&nbsp;MB";}
  elseif ($size >= 1024) {$size = round($size/1024*100)/100 ."&nbsp;KB";}
  else {$size = $size . "&nbsp;B";}
  return $size;
 }
}

function lnkfilter($siLink)
{
$linkres = $siLink;
if(strpos($siLink, "rapid*share.com/")||(strpos($siLink, "rapid^share.com/"))||(strpos($siLink, "rapdshare.com/"))) 
  {
	$rssuspect = array('rapid*share.com/','rapid^share.com/','rapdshare.com/');
	$rsabs = 'rapidshare.com/';
	$linkres = str_replace($rssuspect, $rsabs, $linkres);
  }
return $linkres;
}

//encryption
function dcd($c){
  $pt = ""; $bfC = $c;
  if($c=="") {return $pt;}
  $rRot = (int)substr($c,-2);
  if(!is_numeric($rRot)){
   return false;
  }
  else{ $bfC = strtoupper(substr($c, 0, -2)); $pj = strlen($bfC); $i=0; }
  if($pj % 2!=0){return false;}
  while($i<$pj){
    $hs = chr(hexdec(substr($bfC, $i, 2)));
    $pt.=$hs; $i= $i + 2;
  }
  $pt = strrev(rotN($pt, -$rRot));
  return $pt;
}
function mcd($armacc){
  $dar = array();
  $n_a = count($armacc);
  for($i=0; $i < $n_a; $i++){
   $buf_ac = array(); $cl_all = array();
   $buf_ac = explode(chr(hexdec("3A")),dcd($armacc[$i]));
   $cl_all["user"] = $buf_ac[0];
   $cl_all["pass"] = $buf_ac[1];
   $dar[] = $cl_all;
  }
  return $dar;
}

/*==========
autoNext function
param: 
 $iset : should it set autoDL true or not
 $forceNext: if download found error keep evaluate next link
 $audl: wot method send from audl.php. eg. sims, queue, manual
==========*/
function autoNext($iset, $forceNext, $audl)
{
	global $showautoclose, $timeautoclose;
	$bfRet = "";
		  $bfRet="\r\n<script type='text/javascript'>\n";
		  $bfRet.="try{if(parent.isAuto==true){id=parent.current_dlink;\n";
		  $bfRet.="parent.startisdone(id);}\nelse{\n id=self.name.substring(self.name.length-1); idLnk=parent.document.getElementById('idxlink'+id).innerHTML; parent.iframeReady(id); idx=eval(idLnk-1); parent.startisdone(idx);\n}\n}\ncatch(e){}\n";
	if($audl != ""){
		if($audl == "sims"){
		  $bfRet.="id=self.name;id=id.substring(id.length-1);\n";
		  $bfRet.="if(window.opener!=null){window.opener.startisdone(id);}\n";
		  }elseif($audl == "queue" || $audl == "manual"){
		  $bfRet.="try{id=parent.current_dlink;\n";
		  $bfRet.="parent.startisdone(id);}catch(e){}\n";
		  }
	}
		$bfRet.="</script>\r\n";
		
		if($iset)
		{
			 $bfRet.="\r\n<script type='text/javascript'>var autoDL = true;\n";
		}else {$bfRet.= "\r\n<script type='text/javascript'>var autoDL;\n";}
		switch($audl)
		{
			case "sims" : $bfRet.= "var not_simultan = false;";
					break;
			default : $bfRet.= "var not_simultan = true;";
					break;
		}
		
		if ( ($showautoclose=="true") &&$forceNext )
		{
			$bfRet.="\r\n<!-- \n var time = ".$timeautoclose.";\nif(autoDL==true && not_simultan){parent.nextlink(id);}\nfunction vbaccept(){\ntime--;frm = document.vbaccept;\nif(frm)frm.submit.value = 'Auto-Close through '+time+'';\n";
			$bfRet.="if(time>0){window.setTimeout(\"vbaccept()\",1);}\n else \nif(frm){frm.submit.value = 'done';\nfrm.submit.disabled=0;window.close(self);}}\n";
			if($audl=='sims'){
			 $bfRet.="if(autoDL==true && not_simultan==false){if(window.opener!=null){vbaccept(); var da = document.getElementById('tdone'); da.href='javascript:if(autoDL==true && not_simultan){window.opener.nextlink(id);}window.close(self);'; document.getElementById('txtdone').innerHTML='Done';}}\n\n";
			}
			$bfRet.="--></script>";
			$bfRet.="<form id=vbaccept name=vbaccept><input type=submit name=submit style='color:#fff;' value=\"done\" disabled></form>";
			
		}
		else{
			$bfRet.="\r\nif(autoDL==true && not_simultan){parent.nextlink(id);}\n if(autoDL==true && not_simultan==false){if(window.opener!=null){\r\n var da = document.getElementById('tdone');  \n da.href='javascript:if(autoDL==true && not_simultan){window.opener.nextlink(id);} window.close(self);';\n document.getElementById('txtdone').innerHTML='Close';}}</script>";		
		}
		return $bfRet;	
}



	
function cek_worktime($workstart, $workend)
{
  global $timezone;
  $tdebug = false;	
  $zone=3600 * $timezone; // GMT +7 ~ Indonesia
  $tn = getdate(strtotime(date("d M Y H:i:s", time() - date("Z") + $zone)));
  $dtstr = " Apr 1983 ";
  $tgl = "14";

  $tnH = $tn["hours"];
  $tnM = $tn["minutes"];
  $tnS = $tn["seconds"];
  if($tnH<10)$tnH='0'.$tnH;
  if($tnM<10)$tnM="0".$tnM;
  if($tnS<10)$tnS="0".$tnS;
  
  $wstart = strtotime($tgl.$dtstr.$workstart);
  $nowUnix = strtotime("$tgl $dtstr $tnH:$tnM:$tnS");
  $ws = explode(":",$workstart);
  $we = explode(":",$workend);
  
  if((int)$ws[0]>=(int)$we[0])
  {
	$tgl = "15";
	if((int)$tnH<=$we[0])
	{
	  $nowUnix = strtotime("$tgl $dtstr $tnH:$tnM:$tnS");
	}
  }  
  $wend = strtotime($tgl.$dtstr.$workend);

  if($tdebug)
  {
  echo $tn."<br>";
  echo "$tgl $dtstr $tnH:$tnM:$tnS<br>$nowUnix<br>";
  echo "$workstart-".$wstart."-".$wend."-$workend";
  }
  if(($nowUnix-$wstart)>0 && ($wend-$nowUnix)>0) {
	  return true;
  }
  else {
	  return false;
  }
}

function get_traffic($filena)
{
	$isinya = array(' ',null);
	$fl = @fopen($filena, "r");
	if($fl){
	 $buftxt = @fgets($fl);	
	 @fclose($fl);
	 $isinya = explode(":", trim($buftxt));
	}
	if(!is_numeric($isinya[0]))
	{
	  $isinya[0] = 0;
	}
	return $isinya;
}

function autoreset_traffic($days, $c_traf){
  global $timezone;
  $zone = (3600 * $timezone);
  
  if($days>0){
	$reset_traffic = false;
	$start_date = $c_traf[1]; 
	$unix_now = strtotime("now") + $zone;
	
	if(!preg_match("/\d{10}/i", $start_date)){
	  $start_date = $unix_now; 
	}else{
	  if($start_date > $unix_now){
	    $start_date = $unix_now; 
	    $reset_traffic = true;
	  }
	}
	
	$next_date = strtotime("+".$days." day", $start_date);
	
	if($next_date <= $unix_now){
	    $reset_traffic = true;
	    $start_date = $unix_now; 
	}

	if($next_date <= $start_date){
	  $value_trafic = ($reset_traffic ? '0' : $c_traf[0]);
	  $start_date = upd_traffictime($value_trafic); // reset traffic
	}
	return $start_date;
  }else{
   return false;
  }
}

function upd_traffictime($cur_traffic){
  global $timezone;
  $zone = (3600 * $timezone);
  
  $fn_trafic = TRAFFIC_LST;
  if(@file_exists($fn_trafic)) {
   $unix_now = strtotime("now") + $zone;
   $value_trafic = $cur_traffic. ":" . $unix_now;
   $ret = @write_traffic($fn_trafic, $value_trafic);
   return $unix_now;
  }
}

function timeremain_traffic($days, $start_date){
  global $timezone;
  $zone = (3600 * $timezone);
  
  $unix_now = strtotime("now") + $zone;
  $next_date = strtotime("+".$days." day", $start_date);
  return ( $next_date - $unix_now );
}

// Log System
function chklatesvisitor($curvisit)
{
	$err = false;
	$latestvisit = false;
	if(!file_exists(LASTLOG_LST))@touch(LASTLOG_LST);	
	//Check is this user the last IP
	if(@file_exists(LASTLOG_LST))
	{
		$fl = fopen(LASTLOG_LST, 'r');
		$buftxt = @fread($fl, @filesize(LASTLOG_LST)); fclose($fl);
		if($buftxt!=""){
		 if($curvisit===$buftxt)
		  {$latestvisit = true;}
		}
	}else{
		$err = true;
	}
	if($err){echo "<br>an error occurs with log system.";}
	return $latestvisit;
}

function saveLogsys($curvisit){
	global $ipmu, $ref, $timezone;
	if(!file_exists(LOG_PHP))@touch(LOG_PHP);
	
	if(@file_exists(LOG_PHP)){
		// Get current content
		$fl = fopen(LOG_PHP, 'r'); 
		$buftxt = @fread($fl, @filesize(LOG_PHP)); fclose($fl);
		
		$buftxt = cut_str($buftxt, "<isi id='isilog'>", "</isi>");
		$_php = "<?php if(!defined(\"RAPIDLEECH\")){\n require_once(\"404.php\");exit;\n}?>\n";
		//$style = "$_php\n<style>\nbody{\nfont-family:verdana;\n font-size:10px;\n color:#FFFFFF;\n background-color:#010e17;\n background-image:url(background_pm.gif);\n background-repeat:repeat-x;\n}\n.g{color:#00FF00;}\n.t{color:#00FF00;\nfont-size:14px;}\n</style>\n";
		$style = "$_php\n\n";
		$h = $timezone; //  GMT+7 for Indonesia.
		$ms = $h * 60 * 60;
		$gmdate = gmdate("d M Y H:i:s", time()+($ms));
		$time = ('GMT+'.$h);
		$agent = $_SERVER['HTTP_USER_AGENT'];
		$fl2 = fopen(LOG_PHP, 'w+');
		$dash=""; for($i=0;$i<=50;$i++){$dash.="-";}
		$title = "<b class='t'>Log System <small>(descending sorted)</small></b><br>";
		$towrite = 
			"$style<body>$title\n<isi id='isilog'><br>\nDate: $gmdate $time".($ref != ""?"<br>\nRefferer: $ref":"").
			"<br>\nBrowser: $agent<br>\nUser IP: <b class='g'>$ipmu</b><br>$dash\n$buftxt</isi></body>";
		fwrite($fl2, $towrite);	fclose($fl2);
		// Update IP Visitor
		$fl = fopen(LASTLOG_LST, 'w');
		fwrite($fl, $curvisit); fclose($fl);
	}else{
		$err = true;
	}
	if($err){echo "<br>an error occurs with log system.";}
}
//---- Log System



//Online User
function GetOnline() {
	if(!file_exists(VISITOR_LST))@touch(VISITOR_LST);
	if (@file_exists(VISITOR_LST)) {
		$timeoffset = 15; // time offset for online user
		$onlines = file_get_contents(VISITOR_LST);
		$onlineList = unserialize($onlines);
		$online = 0;
		if(is_array($onlineList)){
		  foreach ( $onlineList as $time ) {
			if (time() - $time < $timeoffset * 60) {
				$online++;
			}
		  }
		}
		return $online;
	}
}
function UpdateOnline() {
	global $ipmu;
	if(!file_exists(VISITOR_LST))@touch(VISITOR_LST);
	if (@file_exists(VISITOR_LST)) {
		$onlines = file_get_contents(VISITOR_LST );
		$onlineList = unserialize($onlines);
		//$onlineList[$_SERVER['REMOTE_ADDR']] = time();
		$onlineList[$ipmu] = time();
		$content = serialize($onlineList);
		file_put_contents (VISITOR_LST, $content );
	}
}
//--End Online User

function ongoingAdd() {
  global $onGoing;
	if(!file_exists(ONGOING_LST))@touch(ONGOING_LST);
	if (@file_exists(ONGOING_LST)) {
		$ongoings = file_get_contents(ONGOING_LST );
		(is_numeric($ongoings)?$ongoings++:$ongoings=1);
		file_put_contents(ONGOING_LST, $ongoings);
	}
	$onGoing = true;
}

function ongoingGet() {
	if (@file_exists(ONGOING_LST )) {
		$ongoings = file_get_contents(ONGOING_LST);
		return (int)$ongoings;
	}
}

function ongoingRemove() {
  global $onGoing;
	if(@file_exists(ONGOING_LST)) {
		$ongoings = file_get_contents(ONGOING_LST);
		(is_numeric($ongoings)?($ongoings>0?$ongoings--:0):$ongoings=0);
		file_put_contents(ONGOING_LST, $ongoings);
	}
	$onGoing = false;
}

function write_traffic($fname, $isi)
{
  $fl = fopen($fname, "w");
  if(!$fl) {
    return FALSE;
    }
  else
    {
    if(!flock($fl, LOCK_EX)){
      return FALSE;
      }
    else
      {
      if(!fwrite($fl, $isi)){
        return FALSE;
        }
      else
        {
        if(!flock($fl, LOCK_UN)){
          return FALSE;
          }
        else
          {
          if(!fclose($fl)) {
            return FALSE;
            }
          }
        }
      }
    }
  //@fclose($fname);
  return TRUE;
  }


#============================
  
  
function create_hosts_file($host_file = "hosts.php")
	{
	$fp = opendir(HOST_DIR);
	while (($file = readdir($fp)) !== false)
		{
		if (substr($file, -4) == ".inc")
			{
			require_once(HOST_DIR.$file);
			}
		}
	if (!is_array($host))
		{
		print "No host file found";
		}
	else
		{
		$fs = fopen(HOST_DIR.$host_file, "wb");
		if (!$fs)
			{
			print "Cannot create hosts file";
			}
		else
			{
			fwrite($fs, "<?php\r\n\$host = array(\r\n");
			$i = 0;
			foreach ($host as $site => $file)
				{
				if ($i != (count($host) - 1))
					{
					fwrite($fs, "'".$site."' => '".$file."',\r\n");
					}
				else
					{
					fwrite($fs, "'".$site."' => '".$file."');\r\n?>");
					}
				$i++;
				}
			closedir($fp);
			fclose($fs);
			}
		}
	}

function logged_user($u)
	{
	global $_SERVER;
	foreach ($u as $user => $pass)
		{
		if ($_SERVER['PHP_AUTH_USER'] == $user && $_SERVER['PHP_AUTH_PW'] == $pass)
			return true;
		}
	return false;
	}

function utf8_strrev($str)
{
    preg_match_all('/./us', $str, $ar);
    return join('',array_reverse($ar[0]));
}

function is_present($lpage, $mystr, $strerror = "", $head = 0)
	{
  $strerror = $strerror ? $strerror : $mystr;
	if (stristr($lpage, $mystr))
		{
		html_error($strerror, $head);
		}
	}

// tamabahan utk RS
function is_present_tryagain($lpage, $mystr, $strerror = "", $head = 0)
	{
	global $LINK;
	$strerror = $strerror ? $strerror : $mystr;
	if (stristr($lpage, $mystr))
		{
		html_retry($strerror, $head, $LINK);
		}
	}

	
	
function is_notpresent($lpage, $mystr, $strerror, $head = 0)
	{
	if (!stristr($lpage,$mystr))
		{
		 html_error($strerror, $head);
		}
	}

function insert_location($newlocation)
	{
	global $nn;
	list($location, $list) = explode("?", $newlocation);
	$list = explode("&", $list);
	print "<form action=\"$location\" method=\"post\">".$nn;
	foreach ($list as $l)
		{
		list($name, $value) = explode("=", $l);
		print "<input type=\"hidden\" name=\"$name\" value=\"$value\">".$nn;
		
		}
	?>
<script type="text/javascript">
void(document.forms[0].submit());
</script>
</form>
</body>
</html>
	<?php
	flush();
	}

function pause_download()
	{
	global $pathWithName, $PHP_SELF, $_GET, $nn, $bytesReceived, $fs, $fp;
	$status = connection_status();
	if (($status == 2 || $status == 3) && $pathWithName && $bytesReceived > -1)
    {
    flock($fs, LOCK_UN);
		fclose($fs);
		fclose($fp);
    }
	}

function cut_str($str, $left, $right)
  {
  $str = substr(stristr($str, $left), strlen($left));
  $leftLen = strlen(stristr($str, $right));
  $leftLen = $leftLen ? -($leftLen) : strlen($str);
  $str = substr($str, 0, $leftLen);
  return $str;
  }

function write_file($file_name, $data, $trunk = 1)
  {
  if($trunk == 1)
    {
    $mode = "wb";
    }
  elseif ($trunk == 0)
    {
    $mode = "ab";
    }
  $fp = fopen($file_name, $mode);
  if(!$fp)
    {
    return FALSE;
    }
  else
    {
    if(!flock($fp, LOCK_EX))
      {
      return FALSE;
      }
    else
      {
      if(!fwrite($fp, $data))
        {
        return FALSE;
        }
      else
        {
        if(!flock($fp, LOCK_UN))
          {
          return FALSE;
          }
        else
          {
          if(!fclose($fp))
            {
            return FALSE;
            }
          }
        }
      }
    }
  return TRUE;
  }

function read_file($file_name, $count = -1)
  {
  if($count == -1)
    {
    $count = filesize($file_name);
    }
  $fp = fopen($file_name, "rb");
  flock($fp, LOCK_SH);
  $ret = fread($fp, $count);
  flock($fp, LOCK_UN);
  fclose($fp);
  return $ret;
  }

function pre($var)
  {
  echo "<pre>";
  print_r($var);
  echo "</pre>";
  }

function getmicrotime()
  {
  list($usec, $sec) = explode(" ",microtime());
  return ((float)$usec + (float)$sec);
  }

function counteritung($formname, $delay){
  if(!is_numeric($delay)){$delay = 3;}
  if($formname==''){return false;}
  $htmlscript = '
<script type="text/javascript">
var d = document, di='.$delay.'; // in second
function itung(){if(di>0){ setTimeout("itung()",1000); di--; d.getElementById("tmr").innerHTML=di; } else { d.'.$formname.'.submit(); }}setTimeout("itung()",1000);
</script>
  ';
 return $htmlscript;
}


// Tambahan retry
function html_retry($msg, $head = 1, $link, $cook=false)
{
	global $PHP_SELF, $gtxt, $alternatefree, $csstype;
	if(!$alternatefree){html_error($msg, $head);}
 	
  if ($head == 1)
    {
?>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>Upps...</title>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR; ?>style_sujancok<?php print $csstype;?>.css");
-->
.tdheadolgo { 
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>rl_lgo.png);
}
</style>
</head>
<body>
<div class="head_container"><center>
<a href="<?php echo $PHP_SELF;?>"><div class="tdheadolgo"></div></a></center>
</div>
<?php
    }
?>
<center>
<?php 
echo "<div style=\"height:30px;\">&nbsp;</div><span style=\"color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00; line-height:25px\"><b>$msg</b></span><br><br>"; 
echo "Trying free-download (without premium acc).[<b id='tmr' class='g'>~</b>]&nbsp;
	  <a href=\"javascript:void(0);\" onclick=\"document.frmretry.submit();\">[Execute]</a><br><br>";
echo "<a id=\"tdone\" href=\"".$PHP_SELF."\">[&nbsp;<b><span id=\"txtdone\">".$gtxt['back_main']."</b></span>&nbsp;]</a><br>";

echo counteritung('frmretry',15);
	?>
	
<form name="frmretry" action="<?php echo $PHP_SELF;?>" method="post">
<input type="hidden" name="link" value="<?php print $link;?>">
<input type="hidden" name="task" value="retry">
<input type="hidden" name="premium_acc" id="premium_acc" value="">
<input type="hidden" name="mu_acc" id="mu_acc" value="">

</form>
	
</center>
</body>
</html>	
	
<?php
exit;
}

  
  
  
function html_error($msg, $head = 1) {
  global $PHP_SELF, $gtxt, $csstype, $onGoing;
  if ($head == 1)
    {
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<title>Upps...</title>
<style type="text/css">
<!--
@import url("<?php print IMAGE_DIR; ?>style_sujancok<?php print $csstype;?>.css");
-->
.tdheadolgo { 
 background: transparent no-repeat url(<?php print IMAGE_DIR;?>rl_lgo.png);
}
</style>
</head>
<body>
<div class="head_container"><center>
<a href="<?php echo $PHP_SELF;?>"><div class="tdheadolgo"></div></a></center>
</div>
<?php
    }
    ?>
<center>
<?php 
echo "<span style=\"color:red; background-color:#fec; padding:3px; border:2px solid #FFaa00; line-height:25px\"><b>$msg</b></span><br><br>"; 
echo "<a id=\"tdone\" href=\"".$PHP_SELF."\">[&nbsp;<b><span id=\"txtdone\">".$gtxt['back_main']."</span>&nbsp;]</a><br>";

$audlparam = (isset($_GET["idx"])) ? $_GET["idx"] : '';
if($audlparam!=''){
 $audlparam = explode('|',$audlparam);
 if($audlparam[0]!=''){$audlparam[0] = true;}
 if($audlparam[1]==''){$audlparam[1] = 'none';}
 echo autoNext($audlparam[0], false, $audlparam[1]);
}
flush();
?>
</center>

<?php
  if($onGoing)ongoingRemove();
  if ($head == 1){
	echo "</body></html>";
  }
  exit;
}

function sec2time($time)
  {
  global $gtxt;
  $day = round($time / (3600*24), 2);
  if($day >= 1)
    {
    $day = floor($day);
    $time -= $day * 3600 * 24;
    }	
  $hour = round($time / 3600, 2);
  if($hour >= 1)
    {
    $hour = floor($hour);
    $time -= $hour * 3600;
    }
  $min = round($time / 60, 2);
  if($min >= 1)
    {
    $min = floor($min);
    $time -= $min * 60;
    }
  $sec = $time;
  $day  = ( ($day > 1) ? $day." {$gtxt["days"]} " : (($day == 1) ? $day." {$gtxt["days"]} " : "") );
  $hour = ( ($hour > 1) ? $hour." {$gtxt["hours"]} " : (($hour == 1) ? $hour." {$gtxt["hours"]} " : "") );
  $min  = ( ($min > 1) ? $min." {$gtxt["minutes"]} " : (($min == 1) ? $min." {$gtxt["minutes"]} " : "") );
  $sec  = ( ($sec > 1) ? $sec." {$gtxt["seconds"]}" : (($sec == 1 || $sec == 0) ? $sec." {$gtxt["seconds"]}" : "") );
  return $day.$hour.$min.$sec;
  }

function sec1time($time)
  {
  $hour = round($time / 3600, 2);
  if($hour >= 1)
    {
    $hour = floor($hour);
    $time -= $hour * 3600;
    }
  $min = round($time / 60, 2);
  if($min >= 1)
    {
    $min = floor($min);
    $time -= $min * 60;
    }
  $sec = $time;
  $hour = ($hour > 1) ? $hour." hours " : ($hour == 1) ? $hour." hour " : "";
  $min = ($min > 1) ? $min." minutes " : ($min == 1) ? $min." minute " : "";
  $sec = ($sec > 1) ? $sec." seconds" : ($sec == 1) ? $sec." second" : "";
  return $hour.$min.$sec;
  }

function bytesToKbOrMbOrGb($bytes) {
	if (is_numeric ( $bytes ) && $bytes >= 0) {
		$s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$e = floor(log($bytes)/log(1024));
        return (pow(1024, floor($e))>0?sprintf('%.2f '.$s[$e], ($bytes/pow(1024, floor($e)))):'0 B');
	} else {
		//$size = "Unknown";
		$size = sprintf("%u", filesize($file));
	}
	return $size;
}
  
function updateListInFile($list)  {
  if(count($list) > 0)
    {
    foreach($list as $key => $value)
      {
      $list[$key] = serialize($value);
      }
    if(!@write_file(FILES_LST, implode("\r\n", $list)."\r\n") && count($list) > 0)
      {
      return FALSE;
      }
    else
      {
      return TRUE;
      }
    }
  elseif (@file_exists(FILES_LST))
    {
		//return unlink(FILES_LST);
		return fopen(FILES_LST, "w");
    }
}

function updateCozEmpty($f){
  global $list;
  updateListInFile($list);
}

function _cmp_list_enums($a,$b)
  {
    if(isset($a["date"]) && isset($b["date"])){
     return strcmp($a["date"],$b["date"]);
	}else{
     return strcmp($a["name"],$b["name"]);	
	}
  }
  

function count_age($age){
global $gtxt;  
  if($age<=60) return $gtxt["less_a_minute"];
  $jam_str="";
  $jam = floor($age / 3600);
  if($jam >= 1){
    $jam_str= ($jam . " " . $gtxt["hours"] . " " );
    $age -= $jam * 3600; 	
  }
  if($age >= 60){
    $min = floor($age / 60);    
  }
  return $jam_str . ($min > 0 ? $min." ".$gtxt["minutes"] : "") . ($gtxt["ago"]!=""?" ".$gtxt["ago"]:"");
}
  
function _create_list($lynx = false, $medic=false, $d_showall=false)
  {
  global $list, $_COOKIE, $show_all, $forbidden_filetypes, $show_column_sfile, $timezone;
  $glist = array();
  $unix_now = ( time() - date("Z") + (3600 * $timezone));
  if(!$d_showall) $d_showall = (isset($_COOKIE["showAll"]) ? $_COOKIE["showAll"]:false);
  if(($show_all && ($d_showall == 1)) || $medic)
    {  // Show Everything
	if(!defined("ROOT_DIR")){define('ROOT_DIR', realpath("./"));}

    $inCurrDir = stristr(dirname(DOWNLOAD_DIR), ROOT_DIR) ? TRUE : FALSE;
    $dir = @dir(DOWNLOAD_DIR); $totsize = 0; $cnt = 0;
    while(false !== ($file = $dir->read()))
      {
      if($file != "." && $file != ".." && is_array($forbidden_filetypes) 
	     && !in_array(strtolower(strrchr($file, ".")), $forbidden_filetypes) 
		 && is_file(DOWNLOAD_DIR.$file) && basename($file) != FILES_LST)
        {
        $file = DOWNLOAD_DIR.$file;
        //$time = (($show_column_sfile["date"] || $lynx) ? (($inCurrDir!=TRUE) ? filectime(DOWNLOAD_DIR.basename($file)):filectime($file)) : '0');        
        $time = (($inCurrDir!=TRUE) ? @filemtime(DOWNLOAD_DIR.basename($file)) : @filemtime($file));        
		$unix_zone = ( $time - date("Z") + (3600 * $timezone));
        while(isset($glist[$unix_zone])){$unix_zone++;}
		$size = getfilesize($file);
		$agefile = ($unix_now - $unix_zone);
        $glist[$unix_zone] = array("name" => realpath($file),
                              "size" => bytesToKbOrMbOrGb($size),
                              "date" => $unix_zone,
                              "age" => count_age($agefile),
							  );
	    if($show_column_sfile["md5"] && !$lynx) $glist[$unix_zone]["md5"] = md5_file($file);
		$totsize+=$size; $cnt++;		
        }
      }
    $dir->close();
    @uasort($glist,"_cmp_list_enums");
	 if($cnt>0){
	   $glist["files"]["totalsize"] = $totsize;
	   $glist["files"]["totalfile"] = $cnt;
	   $glist["files"]["misc"] = "files";
	 }	 
    }
  else
    {  // Show Downloaded
    if(@file_exists(FILES_LST))
      {
      $glist = file(FILES_LST);
	  $glistReformat = null;
      foreach($glist as $key => $record)
        {
		  $recfile = @unserialize($record);
		  if(is_array($recfile)){
          foreach($recfile as $field => $value)
           {
             if(in_array($field, array("date", "age", "misc"))) {
				$time = @filemtime($recfile["name"]);
			    $unix_zone = ($time != $value ? $time - date("Z") + (3600 * $timezone) : $value);
				if($field=="age") { 
				  $agefile = ($unix_now - $unix_zone);
				  $listReformat[$key]["age"] = count_age($agefile);
				}
				if($field=="date") { 
				  $listReformat[$key][$field] = $unix_zone;
			      $date = $unix_zone;
				}
			 }
			 if($field!="age" && $field!="date") $listReformat[$key][$field] = $value;
           }
		  $glist[$date] = $listReformat[$key];
          unset($glist[$key], $glistReformat[$key]);
		 }
        }
	  @uasort($glist,"_cmp_list_enums");	  
      }
    }
  $list = $glist;
}

function _create_lists(){
  global $lists;
  $glist = array();
  $glistReformat = null;
  if(@file_exists(IP_L33CH_L0G)){
    $glist = file(IP_L33CH_L0G);
    foreach($glist as $key => $record){
      foreach(unserialize($record) as $field => $value){
          $listReformat[$key][$field] = $value;
          if($field == "date") $date = $value;
      }
      $glist[$date] = $listReformat[$key];
        unset($glist[$key], $glistReformat[$key]);
    }
  }
  $lists = $glist;
}
  
  
function relink_path($DL_link, $phpself = "", $spliter = "/", $drname = false){
	global $PHP_SELF, $_SERVER;
	
	if($phpself==""){$phpself = (!$PHP_SELF ? $_SERVER["PHP_SELF"] : $PHP_SELF);}
	$ddot = substr_count($DL_link, '../', 0);

	$rpPath = parse_url($phpself);
	if($phpself != ""){
		$rpPath = substr($rpPath["path"], 0, strlen($rpPath["path"]) - strlen(strrchr($rpPath["path"], $spliter))); 
	}
	for($i=0; $i<$ddot; $i++){
	  $rpPath = parse_url($rpPath);
	  $rpPath = substr($rpPath["path"], 0, strlen($rpPath["path"]) - strlen(strrchr($rpPath["path"], $spliter))); 
	}
	$rpPath = ( (substr($rpPath, -1)==$spliter) ? substr($rpPath, 0, strlen($rpPath)-1):$rpPath);
	$cPath["root"] = $rpPath;
	$cPath["download"] = str_replace("../","",$DL_link);
	return $cPath;

}

function checkmail($mail)
  {
  if(strlen($mail) == 0)
    {
    return false;
    }
  if(!preg_match("/^[a-z0-9_\.-]{1,20}@(([a-z0-9-]+\.)+(com|net|org|mil|".
   "edu|gov|arpa|info|biz|inc|name|[a-z]{2})|[0-9]{1,3}\.[0-9]{1,3}\.[0-".
   "9]{1,3}\.[0-9]{1,3})$/is", $mail))
    {
    return false;
    }
  return true;
  }
/* Fixed Shell exploit by: icedog */
function fixfilename($fname,$fpach='')
	{
		$f_name=basename($fname);
		$f_dir=dirname(eregi_replace("\.\./", "", $fname));
		$f_dir=($f_dir == '.') ? '' : $f_dir;
		$f_dir=eregi_replace("\.\./", "", $f_dir);
		$fpach=eregi_replace("\.\./", "", $fpach);
		$f_name=eregi_replace("\.(php|hta|pl|cgi|sph)", ".xxx", $f_name);
		$ret= ($fpach) ? $fpach.DIRECTORY_SEPARATOR.$f_name : ($f_dir ? $f_dir.DIRECTORY_SEPARATOR : '').$f_name;
		return $ret;
	}

function getBigFilesize($file){
    $INT = 4294967295; //2147483647+2147483647+1;
	$size = filesize($file);
    $fp = @fopen($file, 'r');
    if($fp){
	  fseek($fp, 0, SEEK_END);
	  if (ftell($fp)==0) {  $size += $INT; }
	  if ($size<0) { $size += $INT; }
	  @fclose($fp);
	}
    return $size;
}

function getfilesize($f)
	{
		global $server;
		$stat=stat($f);
		
		//if($server["is_windows"]) return sprintf("%u", $stat[7]);
		if($server["is_windows"]) {
		    return getBigFilesize($f);
		}		
		if (($stat[11] * $stat[12]) < 4*1024*1024*1024) return sprintf("%u", $stat[7]);
	
		global $max_4gb;
		if ($max_4gb === false)
			{
				$tmp_=trim(@shell_exec(" ls -Ll ".@escapeshellarg($f)));
				while (strstr($tmp_,'  ')) { $tmp_=@str_replace('  ',' ',$tmp_); }
				$r=@explode(' ',$tmp_);
				$size_=$r[4];
			}
				else
			{
				$size_=-1;
			}
	
		return $size_;
	}
function bytesToKbOrMb($bytes){
$size = ($bytes >= (1024 * 1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024 * 1024), 2)." TB" : (($bytes >= (1024 * 1024 * 1024)) ? round($bytes / (1024 * 1024 * 1024), 2)." GB" : (($bytes >= (1024 * 1024)) ? round($bytes / (1024 * 1024), 2)." MB" : round($bytes / 1024, 2)." KB"));
return $size;
}
function defport($urls)
	{
		if ($urls["port"] !== '' && isset($urls["port"])) return $urls["port"];
		
		switch (strtolower($urls["scheme"]))
			{
				case "http" : return '80';
				case "https" : return '443';
				case "ftp" : return '21';
			}
	}
function getSize($file)
	{
        $size = filesize($file);
        if ($size < 0)
            if (!(strtoupper(substr(PHP_OS, 0, 3)) == 'WIN'))
                $size = trim(`stat -c%s $file`);
            else{
                $fsobj = new COM("Scripting.FileSystemObject");
                $f = $fsobj->GetFile($file);
                $size = $file->Size;
            }
        return $size;
    }

function purge_files($delay)
  {
    global $timezone; $cnt_deleted = 0;
	if (file_exists(FILES_LST) && is_numeric($delay) && $delay > 0)
	{
		$files_lst = file(FILES_LST);
		$files_new = ""; if(!isset($timezone)) $timezone = 7;
		$unix_now = ( time() - date("Z") + (3600 * $timezone));
		foreach ($files_lst as $files_line)
		{
			$files_data = unserialize(trim($files_line));
			if (isset($files_data["name"]) && @file_exists($files_data["name"]) && is_file($files_data["name"]))
			{
				$filedate = $files_data["date"];
				//$filedate = @filectime($files_data["name"]);
				//$unix_zone_filedate = ( $filedate - date("Z") + (3600 * $timezone));
				$unix_zone_filedate = ( $filedate );
				if(($unix_now -  $unix_zone_filedate) >= ($delay*3600))
				{
					@unlink($files_data["name"]); $cnt_deleted++;
				}
				else
				{
					$files_new .= $files_line;
				}
			}
		}
		//file_put_contents("purge.log", "\r\n\r\n", FILE_APPEND);
		file_put_contents(FILES_LST, $files_new);
	}
	purge_files_ip($delay);
	return $cnt_deleted;
  }

function purge_files_ip($delay)
  {
    global $timezone;
    $unix_now = ( time() - date("Z") + (3600 * $timezone));
	if (file_exists(CONFIG_DIR.IP_L33CH_L0G) && is_numeric($delay) && $delay > 0)
	{
		$files_lst = file(CONFIG_DIR.IP_L33CH_L0G);
		$files_new = "";
		foreach ($files_lst AS $files_line)
		{
			$files_data = unserialize(trim($files_line));
			$filedate = @filectime($files_data["name"]);
			$unix_zone_filedate = ( $filedate - date("Z") + (3600 * $timezone));
			if ($unix_now - $unix_zone_filedate >= ($delay*3600))
			{
        
				//file_put_contents("purge.log", date("d-m-Y H:i:s")." DELETE ".$files_data["name"]."\r\n", FILE_APPEND);
			}
			else
			{
				$files_new .= $files_line;
				//file_put_contents("purge.log", date("d-m-Y H:i:s")." PASS ".$files_data["name"]."\r\n", FILE_APPEND);
			}
		}
		//file_put_contents("purge.log", "\r\n\r\n", FILE_APPEND);
		file_put_contents(CONFIG_DIR.IP_L33CH_L0G, $files_new);
	}
  }

// PHP4 compatibility
if (!function_exists("file_put_contents") && !defined("FILE_APPEND"))
{
	define("FILE_APPEND", 1);
	function file_put_contents($n, $d, $flag = false)
	{
		$mode = ($flag == FILE_APPEND || strtoupper($flag) == "FILE_APPEND") ? "a" : "w";
		$f = @fopen($n, $mode);
		if ($f === false)
		{
			return 0;
		}
		else
		{
			if (is_array($d))
			{
				$d = implode($d);
			}
			$bytes_written = fwrite($f, $d);
			fclose($f);
			return $bytes_written;
		}
	}
}


function calcUsedSpace() {
	$total_size = 0;
	global $list;
	if (!$list) {
		_create_list ();
	}

	if(isset($list["files"]["totalsize"])){
	 $total_size = $list["files"]["totalsize"];
	}else{
		foreach ( $list as $key => $file ) {
			if (@file_exists( $file ["name"] )) {
				$total_size += filesize ( $file ["name"] );
			}
		}	
	}
	return $total_size;
}


if (!function_exists("file_get_contents"))
{
	function file_get_contents($filename, $incpath = false, $resource_context = null)
	{
		if (false === $fh = fopen($filename, "rb", $incpath))
		{
			trigger_error("file_get_contents() failed to open stream: No such file or directory", E_USER_WARNING);
			return false;
		}
		clearstatcache();
		if ($fsize = @filesize($filename))
		{
			$data = fread($fh, $fsize);
		}
		else
		{
			$data = "";
			while (!feof($fh))
			{
				$data .= fread($fh, 8192);
			}
		}
		fclose($fh);
		return $data;
	}
}

// Load language in language folder; get css type; return array
function getArrayfromfile($loc, $predmatch, $succmatch, $find='file'){
  if($loc==''){return false;}
  $_CONSTANTS['D_DIR'] = $loc;
  $cleanfn = array();
  $dir = @dir($_CONSTANTS['D_DIR']);
  if($dir){
    while(false !== ($file = $dir->read())) {
	   switch($find)
	   {
	    case 'file':
         if($file != "." && $file != ".." && is_file($_CONSTANTS['D_DIR'].$file) ) {
		  preg_match("/$predmatch([a-zA-Z0-9_]+)$succmatch/" , $file , $match);
          if(count($match)>0){  $cleanfn[] = $match[1]; }
		 }
		break;
		case 'dir':
		 if($file != "." && $file != ".." && is_dir($_CONSTANTS['D_DIR'].$file) ) {
		  preg_match("/$predmatch([a-zA-Z0-9_]+)$succmatch/" , $file , $match);
          if(count($match)>0){  $cleanfn[] = $match[1]; }
		 }
		 break;
	   }
	  }
  }
  return $cleanfn;
}

// This generate for pointboost manner
function genReload($dlink, $dlay, $cktahu, $numindex){
  $nn = "\r\n";
  $html_form = $nn.'<div style="padding-top:10px;"><b>Pointboost mode-<span class="g">ON</span></b><br><small>This page will reload in [<b id="tmr" class="g">~</b>] seconds</small></div>';
  $html_form .= '<input type="button" name="reboost" value=" GO " onclick="document.frmbooster.submit();">';
  $html_form .= '<div style="display:none;">'.$nn.'<form name="frmbooster" action="'.$PHP_SELF.'" method="post">';
  $html_form .= $nn.'<input type="hidden" name="link" id="link" value="'.$dlink.'">';
  $html_form .= $nn.'<input type="hidden" name="numidx" id="numidx" value="'.(int)$numindex.'">';
  $rnum= rand(11,99); // $cktahu encoded base64
  $html_form .= $nn.'<input type="hidden" name="sssid" value="'.encEnti(rotN($cktahu,$rnum)).$rnum.'">';
  $html_form .= '</form></div><br>';
  $html_form .= counteritung('frmbooster',$dlay);
  return $html_form;
}

// Limit long path link download
function trimlink($link){
$max = 50;
$range = 5;
if(strlen($link)>($max+$range)){
  $opr = '://';
  $stfrom = strpos($link,$opr)+strlen($opr)+1;
  // get the host name
  $lhost=substr($link,0,strpos(substr($link, $stfrom, strlen($link)),"/")+$stfrom);
  $partlnk = explode("/",$link);
  if((strlen($partlnk[count($partlnk)-1])+strlen($lhost))>$max){
    $retfn = '&#8230;'.substr($partlnk[count($partlnk)-1], strlen($lhost.$partlnk[count($partlnk)-1])-$max, strlen($partlnk[count($partlnk)-1]));
  }else
    {$retfn = $partlnk[count($partlnk)-1];}
  return $lhost.'/&#8230;/'.$retfn;
}else{
  return $link;
 }
}


//Clean Link from * if any. //kaskus filter
function urlcleaner($url){
 if(strpos($url, "*")){
  return str_replace("*", "", $url);
 }else{return $url;}
}

// Check file/folder is writable
function is__writable($path)
{
    if ($path{strlen($path)-1}=='/') // recursively return a temporary file path
        return is__writable($path.uniqid(mt_rand()).'.tmp');
    else if (is_dir($path))
        return is__writable($path.'/'.uniqid(mt_rand()).'.tmp');
    // check tmp file for read/write capabilities
    $rm = file_exists($path);
    $f = @fopen($path, 'a');
    if ($f===false)
        return false;
    fclose($f);
    if (!$rm)
        unlink($path);
    return true;
}

 // XML Entity Mandatory Escape Characters
function xmlentities ( $string , $entities=true){
   $string = ($entities ? htmlentities($string) : $string);
   $_str = str_replace ( array ( '&', '"', "'", '<', '>', ' ' ), array ( '&amp;' , '&quot;', '&apos;' , '&lt;' , '&gt;', '&#32;' ), $string );
   return ($_str);
}

 // Inspector strip quotes Characters
function strip_quotes ($string){   
   $_str = str_replace ( array ( '"', "'", ' ' ), array ( '_' , '_', '_'), $string );
   return ($_str);
}

// for debug manner, dump array
function vdump($varray = array(), $textarea=false, $with_br=true){
  $buf = print_r($varray, true);
  if($textarea){
   $buf = "<textarea cols='90' rows='20' style='width:100%;font-size:11px;line-height:10px;'>".$buf."</textarea>";
  }else{
   $buf = ($with_br ? str_replace("\n", "<br>", $buf) : $buf);
  }
  print_r( $buf );
}

// Needed for sload.php & sinfo.php
if(!function_exists('color_cpu')){
function color_cpu($nilai) {
 global $alert_sloadhigh;
 if(!is_numeric($nilai)) {
    return FALSE;
 }else{
    if($nilai <= 33){$clr = "#3D612C";}
    elseif($nilai <= 66){$clr = "#FFCC33";}
    else{$clr = "#FF0000";}
    $nilai = '<b '.($alert_sloadhigh?'class="r':'style="color:'.$clr).'">'.$nilai.'</b>';
    return $nilai;
  }
 }
}

function getServerPlatf(){
  global $server;
	$server["is_windows"] = (strtoupper(substr(PHP_OS, 0, 3)) == "WIN");
	$server["is_linux"] = (stristr(PHP_OS, "Linux"));
	$server["is_mac"] = (stristr(PHP_OS, "Darwin"));
	$server["32bit"] = (PHP_INT_SIZE == 4);
	$server["64bit"] = (PHP_INT_SIZE == 8);
	$server["property"] = "Host ";
	foreach($server as $field => $vtrue){
	  if($vtrue===true){$server["property"].= str_replace("_","&nbsp;", $field) ."&nbsp;";}
	}
	return $server;
}

?>