<?php
if (! defined ( 'RAPIDLEECH' ))
{
	require_once ("404.php");
	exit ();
}

	Download( $LINK );
	function Download( $link )
	{
		global $premium_acc;
		if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
			($_REQUEST ["premium_acc"] == "on" && $premium_acc ["hotfile_com"] ["user"] && $premium_acc ["hotfile_com"] ["pass"]) ||
			($_REQUEST["hf_acc"] == "on" && ($_REQUEST ["hf_cookie"] || $_REQUEST["hf_hash"] || $hf_cookie_auth_value))
		   )
		{
			DownloadPremium($link);
		}
		else
		{
			DownloadFree($link);
		}
	}

	function DownloadFree( $link )
	{
	    global $download_dir;
		
	    $hf = (isset($_POST['hf']) ? $_POST['hf'] : "");
		
		if($hf == "ok"){
          $post=unserialize(urldecode($_POST['post']));
          $post["action"] = "checkcaptcha";
          $post["recaptcha_response_field"] = $_POST["captcha"];
          $Referer = $_POST["link"];    
          
          $Url = parse_url($Referer);
          $page = GetPage( $Referer, $cookie, $post, $Referer );
          is_page($page);
          
          
          preg_match('/\/\d+\/\w+\/\w+\/[^\'"]+/i', $page, $down);      
          $LINK="http://hotfile.com/get".$down[0];     
           if ($down[0]==""){
             $dsource = cut_str($page,'<h3','</h3');
             $ddw = cut_str($dsource,'href="','"');
             $LINK=$ddw;
           }
           
          if (!stristr($page,"REGULAR DOWNLOAD")){
            $Url =parse_url($LINK);
            $FileName = preg_replace("/[^a-z0-9_\.]/i", "_", urldecode(basename($Url["path"])));
            $page = GetPage( $LINK );
			
            preg_match('/Location: *(.+)/', $page, $redir);
            if (strpos($redir[1],"http://")===false) {
			  html_error("Server problem. Please try again after",0);
			}
            $redirect=rtrim($redir[1]);
            $Url = parse_url($redirect);
			RedirectDownload( $LINK, $FileName );
           }
		   exit();
        }

		
		$page = GetPage( $link );
		
	    is_present($page,"File not found","File not found, the file is not present or bad link","0");
	    is_present($page,"due to copyright","This file is either removed due to copyright claim or is deleted by the uploader.","0");
	    is_present($page,"You are currently downloading","You are currently downloading. Only one connection with server allow for free users","0");
		
		preg_match_all( '/timerend=d\.getTime\(\)\+(\d+)/i', $page, $arraytime ); 
		$wtime = $arraytime[1][1]/1000;    
		if ( $wtime > 0 ) 
		{
			insert_timer( $wtime, "You reached your hourly traffic limit" ); 
		}
		
		$action = trim ( cut_str( $page, "action value=",">" ) );
		$tm = trim ( cut_str( $page, "tm value=",">" ) );
		$tmhash = trim ( cut_str( $page, "tmhash value=",">" ) );
		$wait = trim ( cut_str( $page,"wait value=",">" ) );
		$waithash = trim ( cut_str( $page, "waithash value=",">" ) );
		
		$post = array();
		$post["action"] = $action;
		$post["tm"] = $tm;
		$post["tmhash"] = $tmhash;
		$post["wait"] = $wait;
		$post["waithash"] = $waithash;
		insert_timer( $wait, "Waiting link timelock" );   
		
		$page = GetPage( $link, 0, $post );
		preg_match( '/http:\/\/.+get\/[^\'"]+/i', $page, $loca );
		$Href = trim( $loca[0] );
		
		preg_match('/\/\d+\/\w+\/\w+\/[^\'"]+/i', $page, $down);      
        if ($down[0]==""){
           $dsource = cut_str($page,'<h3','</h3');
           $ddw = cut_str($dsource,'href="','"');
           $LINK=$ddw;
        }
		
    if ($down[0]=="") {
        $nofinish=true;
        
        $Href= "http://api.recaptcha.net/noscript?k=6LfRJwkAAAAAAGmA3mAiAcAsRsWvfkBijaZWEvkD";
        
        $page = GetPage( $Href );
        is_page($page);
        is_present($page,"Expired session", "Expired session . Go to main page and reattempt", 0);
        
        $cookie = GetCookies($page);
        $ch = cut_str ( $page ,'recaptcha_challenge_field" value="' ,'"' );
        
        if($ch){
           $Href= "http://api.recaptcha.net/image?c=".$ch;
           $page = GetPage($Href, $cookie);
           $headerend = strpos($page,"\r\n\r\n");
           $pass_img = substr($page,$headerend+4);
           $imgfile = $download_dir."hotfile_captcha.jpg";           
           
           if (file_exists($imgfile)){ 
		     unlink($imgfile);
		   }
           write_file($imgfile, $pass_img);
        }else{
           html_error("Error get captcha", 0);
        }

        $captchaid=cut_str($page,"captchaid value=",">");
        $hash1=cut_str($page,"hash1 value=",">");
        $hash2=cut_str($page,"hash2 value=",">");
        
        unset($post);
        $post['recaptcha_challenge_field']=$ch;
        
        print "<form method=\"post\" action=\"".$PHP_SELF.(isset($_GET["idx"]) ? "?&idx=".$_GET["idx"] : "")."\">$nn";
        print "<h4>Enter <img src=\"$imgfile\"></h4>here: <input name=\"captcha\" type=\"text\" >$nn";
        print "<input name=\"link\" value=\"$link\" type=\"hidden\">$nn";  
        print '<input type="hidden" name="post" value="'.urlencode(serialize($post)).'">'.$nn;
        print "<input name=\"hf\" value=\"ok\" type=\"hidden\">$nn";
        print "<input name=\"Submit\" value=\"Submit\" type=\"submit\"></form>";
    }

		if (!$nofinish){
           $Url = parse_url($LINK);
           $FileName = preg_replace("/[^a-z0-9_\.]/i", "_", urldecode(basename($Url["path"])));
           $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, 0, 0, 0, $_GET["proxy"],$pauth); 
           preg_match('/Location: *(.+)/i', $page, $redir);
           if (strpos($redir[1],"http://")===false) {
		     html_error("Server problem. Please try again after",0);
		   }
           $redirect=rtrim($redir[1]);
           $Url = parse_url($redirect);
		   RedirectDownload( $LINK, $FileName );
        }
		
		exit ();
	}
	
	function DownloadPremium($link)
	{
		global $premium_acc, $Referer;
		$Referer1 = "http://hotfile.com/";
		$post = array();
		if($_GET["premium_acc"] == "on" && $_GET["premium_user"] && $_GET["premium_pass"]){
		 $loginUrl = "http://hotfile.com/login.php";
		
		 $post["returnto"] = "/";
		 $post["user"] = $_REQUEST["premium_user"] ? trim( $_REQUEST["premium_user"] ) : $premium_acc["hotfile_com"]["user"];
		 $post["pass"] = $_REQUEST["premium_pass"] ? trim( $_REQUEST["premium_pass"] ) : $premium_acc["hotfile_com"]["pass"];
		 $page = GetPage( $loginUrl, 0, $post, $Referer1 );
			
		 $cookie = GetCookies( $page );
		}
		
		if(!preg_match('/auth=\w{64}/i', $page) && $_GET ["hf_acc"] == "on"){
	      $cookie = trim ( cut_str ( $page, "Set-Cookie:", ";" ) );
	      if ( $_GET ["hf_cookie"]) {
		  	$cookie .= 'auth=' . $_GET ["hf_cookie"];
		  } elseif ($_GET["hf_hash"]) {
		  	$cookie .= 'auth=' . strrev(dcd($_GET["hf_hash"]));
		  } elseif ($hf_cookie_auth_value) {
		  	$cookie .= 'auth=' . $hf_cookie_auth_value;			
		  }
	    }
	   
		$page = GetPage( "http://hotfile.com/?lang=en", $cookie, 0, $Referer1 );
		
		$findpre = trim ( cut_str ( $page, 'id="account"', 'id="lang"' ) );
		$findpre = strpos( $findpre, 'Premium' );
		
		if( false === $findpre )
		{
			html_error( "Login Failed , Bad username/password combination.",0 );
		}
		
		
		$page = GetPage( $link, $cookie, 0, $Referer );
		
		is_present( $page, "File not found", "File not found, the file is not present or bad link","0" );
		is_present( $page, "due to copyright","This file is either removed due to copyright claim or is deleted by the uploader.","0");
	
	    preg_match('/^HTTP\/1\.0|1 ([0-9]+) .*/',$page,$status);
        if ($status[1] == 200) {
          preg_match('/http:\/\/.+get\/[^\'"]+/i', $page, $loca);   
          $Href = rtrim($loca[0]);  
        } else{
          preg_match('/Location:.+?\\r/i', $page, $loca);
          $redir = rtrim($loca[0]);
          preg_match('/http:.+/i', $redir, $loca);
          $Href = rtrim($loca[0]);
        }
	   
		$page = GetPage( $Href, $cookie, 0, $Referer );
		
		preg_match('/Location: *(.+)/i', $page, $newredir );
		$Href = trim ( $newredir [1] );
		
		//$Href = urldecode ( $Href );
		$Url = parse_url( $Href );
		$FileName = basename($Url["path"]);
		//$FileName = urldecode ( $FileName );
		//$FileName = str_replace ( " " , "_" , $FileName );
		RedirectDownload( $Href, $FileName, $cookie, 0, $Referer );
		exit ();
	}
	
	/**
	 * You can use this function to retrieve pages without parsing the link
	 * 
	 * @param string $link The link of the page to retrieve
	 * @param string $cookie The cookie value if you need
	 * @param array $post name=>value of the post data
	 * @param string $referer The referer of the page, it might be the value you are missing if you can't get plugin to work
	 * @param string $auth Page authentication, unneeded in most circumstances
	 */
	function GetPage($link, $cookie = 0, $post = 0, $referer = 0, $auth = 0) {
		global $pauth;
		if (!$referer) {
			global $Referer;
			$referer = $Referer;
		}
		$Url = parse_url(trim($link));
		$page = geturl ( $Url ["host"], $Url ["port"] ? $Url ["port"] : 80, $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : ""), $referer, $cookie, $post, 0, $_GET ["proxy"], $pauth, $auth );
		is_page ( $page );
		return $page;
	}
	
	/**
	 * Use this function instead of insert_location so that we can improve this feature in the future
	 * 
	 * @param string $link The download link of the file
	 * @param string $FileName The name of the file
	 * @param string $cookie The cookie value
	 * @param array $post The post value will be serialized here
	 * @param string $referer The page that refered to this link
	 * @param string $auth In format username:password
	 * @param array $params This parameter allows you to add extra _GET values to be passed on
	 */
	function RedirectDownload($link, $FileName, $cookie = 0, $post = 0, $referer = 0, $auth = "", $params = array()) {
		global $pauth;
		if (!$referer) {
			global $Referer;
			$referer = $Referer;
		}
		$Url = parse_url($link);
		
		if (substr($auth,0,6) != "&auth=") $auth = "&auth=" . $auth;
		if (!is_array($params)) {
			// Some problems with the plugin, quit it
			html_error('Plugin problem! Please report, error: "The parameter passed must be an array"');
		}
		$addon = "";
		if (count((array) $params) > 0) {
			foreach ($params as $name => $value) {
				if (is_array($value)) {
					$value = serialize($value);
				}
				$addon .= '&'.$name.'='.urlencode($value).'&';
			}
			$addon = substr($addon,0,-1);
		}
		$loc = "{$_SERVER['PHP_SELF']}?filename=" . urlencode ( $FileName ) . 
			"&host=" . $Url ["host"] . "&port=" . $Url ["port"] . "&path=" . 
			urlencode ( $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : "") ) . 
			"&referer=" . urlencode ( $referer ) . "&email=" . ($_GET ["domail"] ? $_GET ["email"] : "") . 
			"&partSize=" . ($_GET ["split"] ? $_GET ["partSize"] : "") . "&method=" . $_GET ["method"] . 
			"&proxy=" . ($_GET ["useproxy"] ? $_GET ["proxy"] : "") . "&saveto=" . $_GET ["path"] . 
			"&link=" . urlencode ( $link ) . ($_GET ["add_comment"] == "on" ? "&comment=" . 
			urlencode ( $_GET ["comment"] ) : "") . $auth . ($pauth ? "&pauth=$pauth" : "") . 
			($_GET ["uploadlater"] ? "&uploadlater=".$_GET["uploadlater"]."&uploadtohost=".$_GET['uploadtohost'] : "") .
			"&cookie=" . urlencode($cookie) .
			"&post=" . urlencode ( serialize ( $post ) ) .
			($_POST ["uploadlater"] ? "&uploadlater=".$_POST["uploadlater"]."&uploadtohost=".urlencode($_POST['uploadtohost']) : "").
			($_POST ['autoclose'] ? "&autoclose=1" : "").
			(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "") . $addon;
		
		insert_location ( $loc );
	}	

/**********************************************************	
written by kaox 15-oct-2009
update by kaox 10-jan-2010

Fixed  downloading from free and premium account, Converted in OOPs format, removed un-neccesary code by Raj Malhotra on 27 Feb 2010
**********************************************************/
?>