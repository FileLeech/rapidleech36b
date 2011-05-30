<?php 
 if(!defined('RAPIDLEECH'))
  { require_once("404.php"); exit; }
?>
<hr>
<SCRIPT LANGUAGE="JavaScript"> 
<!-- Begin 
function getthedate(){ 
 var mydate=new Date();var hours=mydate.getHours(); var minutes=mydate.getMinutes(); var seconds=mydate.getSeconds(); 
 if(hours<10){hours="0"+hours;}if(minutes<10){minutes="0"+minutes;} if(seconds<10){seconds="0"+seconds;}
 var cdate="<span style=\"color:#999\">"+hours+":"+minutes+":"+seconds+"&nbsp;</span><span style=\"color:#FF8700\">(local&nbsp;time)</span>";
 if (document.all) 
     document.all.clock.innerHTML=cdate; 
 else if (document.getElementById) 
     document.getElementById("clock").innerHTML=cdate; 
 else 
     document.write(cdate); 
} 
if (!document.all&&!document.getElementById) setInterval("getthedate()",1000); 

function goforit(){ 
    if (document.all||document.getElementById) setInterval("getthedate()",1000); 
} 
    window.onload=goforit; 
// End --> 
</SCRIPT>
<table cellspacing="2" cellpadding="2">
<tr><td>

<?php
function sload_winosname() {
	$wUnameB = php_uname ( "v" );
	$wUnameBM = php_uname ( "r" );
	$wUnameB = eregi_replace ( "build ", "", $wUnameB );
	if ($wUnameBM == "5.0" && ($wUnameB == "2195")) {
		$wVer = "Windows 2000";
	}
	if ($wUnameBM == "5.1" && ($wUnameB == "2600")) {
		$wVer = "Windows XP";
	}
	if ($wUnameBM == "5.2" && ($wUnameB == "3790")) {
		$wVer = "Windows Server 2003";
	}
	if ($wUnameBM == "6.0" && (php_uname ( "v" ) == "build 6000")) {
		$wVer = "Windows Vista";
	}
	if ($wUnameBM == "6.0" && (php_uname ( "v" ) == "build 6001")) {
		$wVer = "Windows Vista SP1";
	}
	return $wVer;
}


{
	 global $timezone;
	 $zone = 3600 * $timezone;
	 $unix_now = strtotime(date("d M Y H:i:s", time() - date("Z") + $zone));
	 $thetimeis = getdate($unix_now);
     $thehour = $thetimeis['hours']; 
     $theminute = $thetimeis['minutes']; 
     $thesecond = $thetimeis['seconds']; 
	if(strlen($thehour)==1)$thehour='0'.$thehour;
	if(strlen($theminute)==1)$theminute="0".$theminute;
	if(strlen($thesecond)==1)$thesecond="0".$thesecond;
	echo "$thehour:$theminute:$thesecond"; 
	echo '<span style="color:#FF8700;">&nbsp;(server&nbsp;time)</span><br>';
	echo '<span id="clock"></span>';

 //if ($os == "windows"){
 if ($server["is_windows"]){
	if($cpuUsageNFO && isset($nocpus) && isset($loadcpu)){
	 if($nocpus==0 || $loadcpu==''){
	  $cpulast=0;
	  $wmi = new COM("Winmgmts://");
	  $cpus = @$wmi->execquery("SELECT * FROM Win32_Processor");	  
	  foreach ($cpus as $cpu)
       {
		$cpulast += $cpu->loadpercentage; $nocpus++;
		$loadcpu.= " ~".color_cpu($cpulast)."%";
       }
	   $alert_sloadhigh = ($cpulast >= $ServerLoadAllowed);
	 }
	 echo '<div style="padding-top:3px;"></div><small><span class="c">'; //$loadcpu=''; $c=0;
	 echo '<b>'.$nocpus.' </b>CPU'.$loadcpu.'</span>&nbsp;'.
		($ServerLoadAllowed>0 ? '&#187;&nbsp;('.($alert_sloadhigh?'<blink>':'').color_cpu($cpulast).($alert_sloadhigh?'</blink>':'').' of <b>'.$ServerLoadAllowed.'</b>)' : '~').'</small><br>'.
		'<img src="'.CLASS_DIR.'bar.php?rating='.round($cpulast,"2").'" border="0"><br>';
	}
 }  //--end windows os
 
 //elseif ($os == "linux"){
 elseif ($server["is_linux"]){
   if($cpuUsageNFO && isset($nocpus) && isset($loadcpu)){
     function getStat($_statPath)
     {
         if (trim($_statPath) == '')
         {
             $_statPath = '/proc/stat';
         }
         ob_start();
         @passthru('cat ' . $_statPath);
         $stat = @ob_get_contents();
         @ob_end_clean();
         if (substr($stat, 0, 3) == 'cpu')
         {
             $parts = explode(" ", preg_replace("!cpu +!", "", $stat));
         }
         else
         {
             return false;
         }
         $return = array();
         $return['user'] = $parts[0];
         $return['nice'] = $parts[1];
         $return['system'] = $parts[2];
         $return['idle'] = $parts[3];
         return $return;
     } //--end getStat

     function getCpuUsage($_statPath = '/proc/stat')
     {
		$msge = "<div id='getCpuUsage'><small class='r'>getCpuUsage():<br>couldn't access STAT path</small></div>
<script type='text/javascript'>function killElement(){ document.getElementById('getCpuUsage').style.display='none'; } setTimeout('killElement()', 5000);</script>";
        $time1 = getStat($_statPath) or false;
        sleep(1);
        $time2 = getStat($_statPath) or false;

         $delta = array();
		if($time1!=false && $time2!=false){
			foreach ($time1 as $k => $v)
			{
				$delta[$k] = $time2[$k] - $v;
			}

			$deltaTotal = array_sum($delta);
			$percentages = array();

			foreach ($delta as $k => $v)
			{
				$percentages[$k] = round($v / $deltaTotal * 100, 2);
			}
			return $percentages;
		}else{
		 echo $msge;
		 return false; 
		}
     } //--end getCpuUsage
	

	{
	  $cpu = getCpuUsage();
	  if($cpu!==false){
		  echo '<div style="padding-top:3px;"></div><small><span class="c">';		  
		  if ($CpuLoadFormat == "percent") {
		   if($cpu){ $cpulast = 100 - $cpu['idle'];
		   } else { $cpulast = 0; }			
			echo "CPU :: ".color_cpu(round($cpulast,"0"))."%</span>&nbsp;";  //' (Max allowed: ".$ServerLoadAllowed."%)<br>";
		  } else {
			$stats = @exec('uptime');
			if(!$stats){
				$stats = @shell_exec('uptime');
			}
			preg_match('/averages?: ([0-9\.]+),[\s]+([0-9\.]+),[\s]+([0-9\.]+)/', $stats, $regs);
			$cpulast = $regs[1];			
			echo "CPU :: ".color_cpu($cpulast)."</span>&nbsp;"; //." (Max allowed: ".$ServerLoadAllowed.")<br>";
		  }
		  $alert_sloadhigh = ($cpulast >= $ServerLoadAllowed);
		  echo ($ServerLoadAllowed>0 ? '&#187;&nbsp;('.($alert_sloadhigh?'<blink>':'').color_cpu($cpulast).($alert_sloadhigh?'</blink>':'').' of <b>'.$ServerLoadAllowed.'</b>)' : '~').'</small><br>'.
			'<img src="'.CLASS_DIR.'bar.php?rating='.round($cpulast,"2").'" border="0"><br>';
	  }
	 
	} //--end linux cpuUsageNFO
   } //--end syarat
 } //--end linux os

 
}


#if (is_callable("disk_free_space")) 
if(function_exists('disk_free_space') && function_exists('disk_total_space')) {
 $d = realpath("./");
 $free = disk_free_space($d); 
 $total = disk_total_space($d); 
 if (($free === FALSE)||($free < 0)) {$free = 0;} 
 if (($total === FALSE)||($total < 0)) {$total = 0;} 
 $free_percent = ($total>0?round(($free * 100)/$total,2):0);
 if($free){
  echo "<div style=\"padding-top:6px;\"></div><div style='width:170px;'><small>&nbsp;Free&nbsp;<b><span class='s'>".view_size($free)."</span></b>&nbsp;of&nbsp;<b><span class='g'>".view_size($total)."</span></b>";
  echo '<br><img src="' . CLASS_DIR . 'bar.php?rating=' . round ( (100-$free_percent), "2" ) . '" border="0" style="display:none;" onload="setTimeout(this.style.display=\'\',1500);">';
  echo "<div style='text-align:right;'><b class='s'>".$free_percent."%</b> Free</div></small></div>";
 }
}

?>
</td></tr>
</table>