<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}
  
if (  $_GET["premium_acc"] == "on" && ($_GET["auth_hash"] || ($_GET["premium_user"] && $_GET["premium_pass"]) || ($premium_acc["rs_com"]["user"] && $premium_acc["rs_com"]["pass"]) ||(is_array($premium_acc["rs_com"][0])) || $_GET["maudl"]=='multi')
   || ($_GET["rs_acc"] == "on" && ($_GET["rs_cookie"] || $_GET["rs_hash"] || isset($rs_cookie_enc_value)) )
   || $_POST["sssid"] )
	{
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

	is_present($page,"The file could not be found.", "The file could not be found. Please check the download link.");
	is_present($page,"This limit is reached", "This file is neither allocated to a Premium Account, or a Collector's Account, and can therefore only be downloaded 10 times. This limit is reached.");
	is_present($page,"Due to a violation of our terms of use, the file has been removed from the server.");
	is_present($page,"This file is suspected to contain illegal content and has been blocked.");
	is_present($page,"The uploader has removed this file from the server.");
	is_present($page,"This file has been removed from the server, because the file has not been accessed in a long time.");
	is_present($page,"is momentarily not available", "This server is momentarily not available.  We are aware of this and are working to get this resolved.");
	is_present($page,"unavailable due to hardware-problems", "Server unavailable due to hardware-problems");
					
	$FileName = basename(trim(cut_str($page, '<form action="', '"')));
	!$FileName ? $FileName = basename($Url["path"]) : "";
	
	if($_GET["auth_hash"]){
	 require_once(CLASS_DIR."other.php");
	 $split_hash = explode(":", base64_decode(utf8_strrev(dcd($_GET["auth_hash"]))));
	 if(count($split_hash)>1)
	 {
		$_GET["premium_user"] = $split_hash[0];
		$_GET["premium_pass"] = $split_hash[1];
	 }
	}elseif(is_array($premium_acc["rs_com"][0])){
		// RS-Multiple acc by TheOnly
		$totalacc = count($premium_acc["rs_com"]);
		$success = false;
		for($i = 0; $i <= $totalacc; $i ++) {
			$_GET["premium_user"] = $premium_acc["rs_com"][$i]['user'];
			$_GET["premium_pass"] = $premium_acc["rs_com"][$i]['pass'];
			$auth = base64_encode($_GET["premium_user"].":".$_GET["premium_pass"]);
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth, $auth);
			is_page($page);
			if (stristr($page,"Account has been found, but the password is incorrect")) continue;
			if (stristr($page,"Account has not been found")) continue;
			if (stristr($page,"You have exceeded the download limit.")) continue;

			if(stristr($page, "Location:")) {
				$success = true;
				break;
			}
		}
		if(!$success){
			//html_error( "No usable premium account", 0);
			html_retry("No usable premium account",0,$LINK);			
		}
	}
	
	if($_POST["sssid"] || $_GET["premium_user"] || $premium_acc["rs_com"]){
	  $auth = $_POST["sssid"] ? (utf8_strrev(dcd($_POST["sssid"]))) : ($_GET["premium_user"]!='' ? base64_encode($_GET["premium_user"].":".$_GET["premium_pass"]) : base64_encode($premium_acc["rs_com"]["user"].":".$premium_acc["rs_com"]["pass"]));
	}

	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth, $auth);
	is_page($page);
	  
	$cook = trim ( cut_str ( $page, "Set-Cookie:", ";" ) );
	if($_GET["rs_acc"] && !stristr ( $cook, "enc=" )){
	  if (isset($_GET["rs_cookie"])) {
	  	$cook .= 'enc=' . $_GET["rs_cookie"];
	  } elseif (isset($_GET["rs_hash"])) {
	  	$cook .= 'enc=' . strrev(dcd($_GET["rs_hash"]));
	  } elseif (isset($rs_cookie_enc_value)) {
	  	$cook .= 'enc=' . $rs_cookie_enc_value;
	  }
	  //$cook .= "; expires=Mon, 21-Nov-2020 16:01:23 GMT";
	  $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, $cook, 0, 0, $_GET["proxy"],$pauth);
	  is_page($page);
	}
	
	//is_present($page,"Account found, but password is incorrect");
	//is_present($page,"Account not found");
	is_present_tryagain($page,"Account has been found, but the password is incorrect");
	is_present_tryagain($page,"Account has not been found");

	if (stristr($page, "Location:"))
		{
		$Href = trim(cut_str($page, "Location:","\n"));
		$Url =  parse_url($Href);

	 	insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : ""). "&cookie=" . urlencode ( $cook ) . "&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=".$pauth : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
		}
	else
		{
		 //html_error("Cannot use premium account", 0);
		 html_retry("Cannot use premium account",0,$LINK);
		}		
	}
else
	{
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
		is_page($page);
		
		is_present($page,"Due to a violation of our terms of use, the file has been removed from the server.");
		is_present($page,"This limit is reached", "This file is neither allocated to a Premium Account, or a Collector's Account, and can therefore only be downloaded 10 times. This limit is reached.");
		is_present($page,"This file is suspected to contain illegal content and has been blocked.");
		is_present($page,"The file could not be found.", "The file could not be found. Please check the download link.");
		is_present($page,"The uploader has removed this file from the server.");
		is_present($page,"This file has been removed from the server, because the file has not been accessed in a long time.");
		is_present($page,"is momentarily not available", "This server is momentarily not available.  We are aware of this and are working to get this resolved.");
		is_present($page,"unavailable due to hardware-problems", "Server unavailable due to hardware-problems");
		is_present($page, "is already downloading a file","Your IP-address is already downloading a file, Please wait until the download is completed.");
						
		$post = array();
		$post["dl.start"] = "Free";
						
		$Href = trim(cut_str($page, '<form action="', '"'));
		$refimg = $Href;
		$Url = parse_url($Href);
						
		$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : "") ,$LINK , 0, $post, 0, $_GET["proxy"],$pauth);
		is_page($page);
			
		is_present($page, "is not allowed to use the free-service anymore today","No more free downloads from this IP today");
		is_present($page,"This limit is reached", "This file is neither allocated to a Premium Account, or a Collector's Account, and can therefore only be downloaded 10 times. This limit is reached.");
		is_present($page, "This file exceeds your download-limit","Download limit exceeded");
		is_present($page, "no more download slots left for non-members", "Unfortunately our servers are overloaded, we have no more download slots left for non-members","No more download slots");
		is_present($page, "is already downloading a file","Your IP-address is already downloading a file, Please wait until the download is completed.");
		
		is_present($page, "is already downloading a file","Your IP-address is already downloading a file, Please wait until the download is completed.");

		
		if (stristr($page, "try again in")){
			$minutes = trim (cut_str($page, "Or try again in about ", " minutes."));
			if(!is_numeric($minutes)) {
			  $minutes = trim (cut_str($page, "Please try again in ", " minute"));
			}
			if(is_numeric($minutes)) {
?>
<script type="text/javascript">
wait_time = <?php echo ($minutes + 1)*60000;?>;
function waitLoop() {
 if (wait_time == 0) {
 location.reload();
 }
 wait_time = wait_time - 60000;
 document.getElementById('waitTime').innerHTML = wait_time / 60000;
 setTimeout("waitLoop()",60000);
}
</script>
<?php
				html_error ( "Download limit exceeded. You have to wait <font color=black><span id='waitTime'>$minutes</span></font> minute(s) until the next download.<script>waitLoop();</script>", 0 );
			} else {
				html_error ( "Download limit exceeded. Err: " . htmlspecialchars($minutes), 0 );
			}
		}
/*
		if (stristr($page, "Would you like more?"))
			{
			$minutes = trim(cut_str($page, "Or try again in about ", " minutes."));
			if ($minutes)
				{
				html_error("Download limit exceeded. You have to wait <font color=black>$minutes</font> minute(s) until the next download.", 0);
				}
			else
				{
				html_error("Download limit exceeded.", 0);
				}
			}
*/

		if(stristr($page, "Too many users downloading right now") || stristr($page, "Too many connections"))
			{
			html_error("Too many users downloading right now", 0);
			}
		if(stristr($page, "no more download slots available for free"))
			{
			html_error("There are no more download slots available for free users right now", 0);
			}
	
		/*
		if(preg_match('/Happy Hour/', $page)){
			$rhh = 'active';
		}
		*/
		$countDown = trim(cut_str($page, "var c=", ";"));
		
		//preg_match('/Security Question.*<p>(.*)?<br>/', $page, $security_q);
		preg_match('%<form name="dlf?".*</form>%s', $page, $form_content);
		$middle_str = str_replace("\\", "", preg_replace('/(\' *\+.*?(\r\n)*.*?\'|display:none;)/s', '', $form_content[0]));
		$code = '<center>'.trim($middle_str);

		$FileAddr = trim(cut_str($code, '<form name="dlf" action="', '"'));
		//preg_match('/<form name="dlf?.*(http:.*?)"/', $code, $FAddr);
		//$FileAddr = $FAddr[1];
		$Href = parse_url($FileAddr);
		$FileName = basename($Href["path"]);
		
			
		if (!$FileAddr)
			{
			html_error("Error getting download link", 0);
			}
					
		$code = str_replace($FileAddr, $PHP_SELF.(isset($_GET["idx"]) ? "?idx=".$_GET["idx"] : ""), $code);
		$code = preg_replace('/<input type=image.*?".*?>/', $nn.'<input type=submit name="goDol" id="goDol" value=Download onclick="return true;">', $code);
		$code = preg_replace('%<div><img.*Advanced download settings</div>%s', '', $code);
		$endStr = "</p>";
		$endTxt = strpos($code, $endStr) + strlen($endStr);
		$code = substr($code, 0, $endTxt);

		
		preg_match_all("/http:\/\/rs(.*).rapidshare.com\/(.*)".$FileName."/iU", $code, $matches);

		if (!$matches)
			{
			html_error("Error getting available server's list", 0);
			}
						
		for ($i = 0; $i < count($matches[0]); $i++)
			{
			$Url = parse_url($matches[0][$i]);
			$code = str_replace("document.dlf.action='".$matches[0][$i], "document.dlf.host.value='".$Url["host"], $code);
			}
		
		$timeautosub = 150;
		$code = str_replace("</form>", $nn, $code);
		global $autosubmit;
		
		$code.= $nn."<input type=\"hidden\" name=\"filename\" value=\"".urlencode($FileName)."\">$nn<input type=\"hidden\" name=\"link\" value=\"".urlencode($LINK)."\">$nn<input type=\"hidden\" name=\"referer\" value=\"".urlencode($Referer)."\">$nn<input type=\"hidden\" name=\"saveto\" value=\"".$_GET["path"]."\">$nn<input type=\"hidden\" name=\"host\" value=\"".$Href["host"]."\">$nn<input type=\"hidden\" name=\"path\" value=\"".urlencode($Href["path"])."\">$nn";
				
		$code.= ($_GET["add_comment"] == "on" ? "<input type=\"hidden\" name=\"comment\" value=\"".urlencode($_GET["comment"])."\">$nn" : "")."<input type=\"hidden\" name=\"email\" value=\"".($_GET["domail"] ? $_GET["email"] : "")."\">$nn<input type=\"hidden\" name=\"partSize\" value=\"".($_GET["split"] ? $_GET["partSize"] : "")."\">$nn";
		$code.= "<input type=\"hidden\" name=\"method\" value=\"".$_GET["method"]."\">$nn<input type=\"hidden\" name=\"proxy\" value=\"".($_GET["useproxy"] ? $_GET["proxy"] : "")."\">$nn".($pauth ? "<input type=\"hidden\" name=\"pauth\" value=\"".$pauth."\">$nn" : "");
		if($autosubmit){
		 $code.= "<input type=text name=txtSubmit style='text-align:center; font-weight: bold;' value=\"autosubmit\" disabled >";
		}
		$code.= "</form></center>";
		
		//$js_code = "<script language=\"JavaScript\">".$nn."function check() {".$nn."var imagecode=document.dlf.accesscode.value;".$nn."var path=document.dlf.path.value;".$nn; $js_code.= 'if (imagecode == "") { window.alert("You didn\'t enter the image verification code"); return false; }'.$nn.'else {'.$nn.'document.dlf.path.value=path+escape("?accesscode="+imagecode);'.$nn.'return true; }'.$nn.'}'.$nn.'</script>'.$nn;
	  
		if($autosubmit){
		$js_code.= "<script language=\"JavaScript\">var time=".$timeautosub.";".$nn."dtit=document.title;";
		#$js_code.= "function vbaccept(){".$nn."if(c>0){window.setTimeout(\"vbaccept()\",500);}else{window.setTimeout(\"FireUp()\",500);}}".$nn."try{counterC=c;}catch(err){window.setTimeout(\"FireUp()\",500);}if(counterC!=undefined){window.setTimeout(\"vbaccept()\",2000);}".$nn;
		
		$js_code.= "function FireUp(){".$nn."frm=window.document.dlf;".$nn."time--;".$nn."if(frm){".$nn."frm.txtSubmit.value='AutoSubmit ['+time+']';".$nn."if(time>0){window.setTimeout(\"FireUp()\",1);}".$nn."else {frm.goDol.click();}}".$nn."}";
		if($countDown){
		 $js_code.= $nn."c_chk(); function c_chk(){ if(c>0){document.title=c+' '+dtit;setTimeout('c_chk()',1000);} else{setTimeout('FireUp()',500);} }";
		}else{ $js_code.= "FireUp();";}
		$js_code.= $nn."</script>";		
		}
		
		if (!$countDown)
		  {	print $code.$nn.$nn.$js_code."$nn</body>$nn</html>";}
		else
		  {	insert_new_timer($countDown, rawurlencode($code), "Download-Ticket reserved.", $js_code);}
	}
?>