<?php
if(!defined('RAPIDLEECH')){ 
  require_once("404.php"); exit; 
}

@set_time_limit(0);

function GetBetween($content,$start,$end){
  $r = explode($start, $content);
  if (isset($r[1])){
     $r = explode($end, $r[1]);
     return $r[0];
  }
  return '';
}

function parseResult($text){
 $hasil = null;
 $arr_y = split("\n",$text);
 foreach($arr_y as $kname){
  $line = split("=", $kname, 2);
  $hasil[$line[0]] = $line[1];  
 }
 return $hasil;
}
function _bytesToKbOrMbOrGb($bytes, $caller='TSL') {
	if (is_numeric ( $bytes )) {
		$s = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');		
		$km = '%.'.($caller=='TL' ? '1' : '2').'f ';
		$e = floor(log($bytes)/log(1000));
		$bytes = ($caller=='TL' ? $bytes*1000 : $bytes);
        $nil = (pow(1000, floor($e))>0 ? sprintf($km.$s[$e], ($bytes/pow(1000, floor($e)))):'0 B');		
		return $nil;
	} else {
		$size = "N/A";
		return $size;
	}	
}

// Check ACC RS Multi and Single ( acc.status )
if (isset($_POST['accounts']) || isset($_POST['staccounts'])) {
	
	if(isset($_POST['accounts'])){
	  $accstatus=0;
	  $txtac = $_POST['accounts'];
	}
	else{ // untuk RS Acc Status, cek single acc ato multi. return all acc to $txtacc
	  if(isset($_POST['staccounts']) && isset($premium_acc["rs_com"])){
	   $accstatus=1; $n_acc='';
	   $txtac = (isset($premium_acc["rs_com"]["user"])?($premium_acc["rs_com"]["user"]!='' && $premium_acc["rs_com"]["pass"]!='' ? $premium_acc["rs_com"]["user"].":".$premium_acc["rs_com"]["pass"]:':') : count($premium_acc["rs_com"]));
	   if(is_numeric($txtac)){
	    $n_acc=(int)($txtac-1); $txtac='';
		for($i=0; $i<=$n_acc; $i++){
			$acc = $premium_acc["rs_com"][$i]['user'];
			$pas = $premium_acc["rs_com"][$i]['pass'];
			$txtac.=$acc.":".$pas."\n";
		}
	   }
	  } else {$txtac = ":";}
	}

   //Header--untuk RS Acc Status
   if($accstatus!=0)
   {
	if($txtac!=":")
	 {
		$ipmu = get_real_ip(); $authorized=0;
		if(in_array($ipmu, $ip_premixstat_list))
		{ $return_msg = "<small>Authorized&nbsp;IP&nbsp;detected:&nbsp;".$ipmu."</small>"; $authorized=1; }
		else{$return_msg = "<small>&nbsp;</small>"; }
		$detil = "<th title='Expiration date'>Expired</th>";
		if($authorized==0){$detil="";}
		$return_msg.= "<table width=\"100%\" border=1 cellspacing=\"0\" cellpadding=\"2\"><tr bgcolor=\"#000\" valign=\"bottom\" align=\"center\" class=\"accnfo\"><th title='premium account rapidshare.com'>PremiX</th><th title='TrafficShare left'>Trafic</th>".$detil."<th title='Traffic Left'>Trafic&nbsp;left</th></tr>";		
	 }
	 else
	 {
		exit("<center><small>RS&nbsp;acc&nbsp;not&nbsp;available</small></center>");
	 }
   }
   
$dtaaccounts = explode("\n", $txtac);
$cnt_unsecure = 0;
foreach ($dtaaccounts as $value) {
   list($User, $Pass) = split(":", $value);
   if (empty($User)==FALSE || empty($Pass)==FALSE){	
	  
	if(!isset($_POST['useapi'])){
	   $uname = 'login=';
	   $hostrslink='premiumzone.cgi';
	   $sublink = "https://ssl.rapidshare.com/cgi-bin/".$hostrslink;	   
	    $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $sublink);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
        //set proxy here
        //curl_setopt($ch, CURLOPT_PROXY, 'http://127.0.0.1:8080');
        //curl_setopt($ch, CURLOPT_PROXYPORT, 8080);
        //curl_setopt ($ch, CURLOPT_PROXYUSERPWD, ''); 
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $uname.$User."&password=".$Pass);
        $pagedata = curl_exec($ch);
		curl_close($ch);
	   
	  $valid = array(); 
      //get rapidshare details
	  
	  /*------------------------------*/
	  /* EDITED (again) by R0CKABILLY */
	  /*------------------------------*/
	  ereg("<td>TrafficShare left:</td><td align=right style=\"padding-right:20px;\"><b>([0-9\.]+) GB</b>", $pagedata, $traffic);
	  ereg("<td>Free RapidPoints:</td><td align=right style=\"padding-right:20px;\"><b><span id=\"rpo\">([0-9\.]+)</span></b>", $pagedata, $frpoints);
	  ereg("<td>Premium RapidPoints:</td><td align=right style=\"padding-right:20px;\"><b><span id=\"rppo\">([0-9\.]+)</span></b>", $pagedata, $ppoints);
      ereg("<td>Expiration date:</td><td style=\"padding-right:20px;\"><b>([a-zA-Z\_0-9\ .,]+)</b>", $pagedata, $valid);	  
	  ereg("var email = \"([a-zA-Z\_0-9\ .,@-]+)\";", $pagedata, $emailnye);
	  ereg("schlosszu = \"([0-9])\"", $pagedata, $slock);
	  //check referrer points, by R0CKABILLY -- there's no ref points now
	  //ereg("var refpoints = ([0-9]+);", $pagedata, $ref);

	    $trafficlft=GetBetween(GetBetween($pagedata,"<td>Traffic left:</td><td align=right><b><script>","</script> MB</b></td>"), 'setzeTT(""+Math.ceil(',")));");
	    $tlpart = explode("/", trim($trafficlft));
	    if((int)$tlpart[1] != 0){
		 $trafficlft = round((int)$tlpart[0]/(int)$tlpart[1], 1)."MB";
		}

		//check if it's german rapidshare.com acc, by R0CKABILLY
		if($traffic=="") {
			ereg("<td>TrafficShare &uuml;brig:</td><td align=right style=\"padding-right:20px;\"><b>([0-9\.]+) GB</b></td>", $pagedata, $traffic);
			ereg("<td>G&uuml;ltig bis:</td><td style=\"padding-right:20px;\"><b>([a-zA-Z\_0-9\ .,]+)</b></td>", $pagedata, $valid);
			$trafficlft=GetBetween(GetBetween($pagedata,"<td>Traffic &uuml;brig:</td><td align=right><b><script>","</script> MB</b></td>"), 'setzeTT(""+Math.ceil(',")));");
			$tlpart = explode("/", trim($trafficlft));
			if((int)$tlpart[1] != 0){
			 $trafficlft = round((int)$tlpart[0]/(int)$tlpart[1], 1)."MB";
			}
		}
		//end german acc check
		
		// moved here, used to be $szGb='&nbsp;GB';
		$traffic[1]=$traffic[1]."&nbsp;GB";

		$validity = (empty($valid[1]) ? false : true);
 	    if($validity){
		 $validtmp = explode(",",$valid[1]);
	     $valid[1] = str_replace(" ","",$validtmp[1]);
		}
		$rfpoints = "-"; // cant get refferer rs pointwith this method.
		
/*		
	    $valid[1]  -> >Expiration date
	    $aliasnye ?
	    $emailnye[1] -> email
	    $slock[1]  -> slock (security lock)
	    $traffic[1] -> TrafficShare left
	    $trafficlft -> Traffic left
	    $frpoints[1] -> Free RapidPoints
	    $ppoints[1] -> Premium RapidPoints
	    $rfpoints ?
*/	  
    }else{
	// using Rs.API
	/*=========================== 
	** Get login detail, using API, 
	* Thx to R0CKABILLY
	* Due to security reason like prevention from RS (http://www.megaleecher.net/Rapidshare_API#comment-7489)
	* We make a choice that user can check acc using rsAPI or cURL
	*===========================
	*/	
	   $acc_type = 'prem'; //  or 'col' (colzon)
	   $sublink = "https://api.rapidshare.com/cgi-bin/rsapi.cgi?sub=getaccountdetails_v1&type={$acc_type}&login={$User}&password={$Pass}";
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $sublink);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);        
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);		
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
		curl_close($ch);
	   $apiresult = parseResult($result);
/* ===================
	  NOTE   necessary key: 
	  src: http://www.megaleecher.net/Rapidshare_API
  (Premium User)
  $apiresult["accountid"]
  $apiresult["validuntil"] // Expiration date
  $apiresult["username"]  // username != '' ?
  $apiresult["rsantihack"] // secure!=0 ?
  $apiresult["email"]  // email
  $apiresult["fpoints"]  // Free RapidPoints
  $apiresult["bodkb"]  // TrafficShare left
  $apiresult["premkbleft"]  // Traffic left
  $apiresult["ppoints"]  // Premium RapidPoint
  $apiresult["points"]  // Refferer RapidPoint , i guess
  ----
  (Colzon)
  $apiresult["accountid"]
  $apiresult["username"]  // username != '' ?
  $apiresult["rsantihack"] // secure!=0 ?
  $apiresult["email"]  // email
  $apiresult["fpoints"]  // Free RapidPoints
*/
	  $validity = (!empty($apiresult)?  true: false);
	  
	  $valid[1] = Date('d.MY', $apiresult["validuntil"]);
	  $aliasnye = $apiresult["username"];
	  $emailnye[1] = $apiresult["email"];
	  $slock[1] = $apiresult["rsantihack"];
	  $traffic[1] = _bytesToKbOrMbOrGb($apiresult["bodkb"] * 1000);
	  $trafficlft = _bytesToKbOrMbOrGb($apiresult["premkbleft"], 'TL');
	  //$points[1] = $apiresult["fpoints"];
	  $ppoints = $apiresult["ppoints"];
	  $rfpoints = $apiresult["points"];
	} // end use Rs.API

	  // set security status
	  if($slock[1]=="1"){
		$slocktext = "<b style='color:#00FF00'>ON</b>";
	  }else{
		$slocktext = "<b style='color:#FFFF00'><blink>OFF</blink></b>";
	  }
	  	  
      //display if valid
      if($validity){
	   $slipOut='';
	   if($accstatus==0) // RS Acc Checker
	   {
		$loginfunc = "cz";
		if(!$czone){
		  $loginfunc = "";
		  $slipOut=$traffic[1].":".$valid[1].":".$trafficlft.":";
		}
		$ret_acc = ""; 
		$accauth = "<td><a href=\"javascript:void(0);\" onclick=\"login('{$User}','{$Pass}','{$loginfunc}');\">{$User}</a></td>".
			"<td>".$Pass."</td><td align=center>{$frpoints[1]}</td><td align=center>{$ppoints[1]}</td><td align=center>{$rfpoints}</td>".
			"<td align=right>{$traffic[1]}</td>".
			"<td align=right>{$valid[1]}</td>";
		$rpu = "<td align=center><span title='Security&nbsp;Lock'>{$slocktext}</span></td><td><span>{$emailnye[1]}</span></td>";
		$trhead = "<tr class=\"acc_chk\"";
		$ret_acc= "{$User}:{$Pass}:{$frpoints[1]}:{$slipOut}{$emailnye[1]}:";
		$ret_acc.=($slock[1]=="1")?"secured":"UNSECURE";
	   }  // end RS Acc Checker
	   else
	   {
		  $detil = "<td align=right><span title='Expiration date'>{$valid[1]}</span></td>";
		  $pjgusr = strlen($User); $star = "********************************************";
		  $detilusr = ($pjgusr>3?(($pjgusr-3)>6?'*****':substr($star,0,$pjgusr-3)).substr($User, -3):$User);
		  if($authorized==0){$detil="";}
		  $accauth="<td align=center>{$detilusr}</td><td align=right>{$traffic[1]}</td>{$detil}";
		  $trhead = "<tr class=\"accnfo\"";
	   }	   
       $return_msg.= $trhead." bgcolor=\"#620000\" onMouseOver=\"this.bgColor='#970000';\" onMouseOut=\"this.bgColor='#620000';\">{$accauth}<td align=right>{$trafficlft}</td>{$rpu}</tr>";  
       $validrs[] = "{$User}:{$Pass}";
	  }
	  if($accstatus==0 && empty($emailnye[1])==FALSE){
	     echo $return_msg."<!end><<acc>>{$ret_acc}<</acc>>"; 
	  }
     }
	}

  if($accstatus != 0)
  {
	if((int)count($validrs)==0)
	{
	exit("<center><small style='color:#FFFF00'>active&nbsp;acc&nbsp;not&nbsp;available</small></center>");
	}
	exit($return_msg);
  }
  exit();
}  //end (isset($_POST['accounts']) || isset($_POST['staccounts']))  
  

?>