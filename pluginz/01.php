<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
	function Download($link) {
		global $premium_acc, $mu_cookie_user_value;
		//Get link folder
		$matches = "";
		$Url = parse_url(trim($link));
		if (preg_match ( "/f=(\w+)/", $Url ["query"], $matches )) {
			$page = GetPage("http://www.megaupload.com/xml/folderfiles.php?folderid=" . $matches [1]);
			if (! preg_match_all ( "/url=\"(http[^\"]+)\"/", $page, $matches )) html_error ( 'link not found' );
			
			if (! is_file ( "audl.php" )) html_error ( 'audl.php not found' );
			echo "<form action=\"audl.php?GO=GO\" method=post>\n";
			echo "<input type=hidden name=links value='" . implode ( "\r\n", $matches [1] ) . "'>\n";
			foreach ( array ( "useproxy", "proxy", "proxyuser", "proxypass" ) as $v )
				echo "<input type=hidden name=$v value=" . $_GET [$v] . ">\n";
			echo "<script language=\"JavaScript\">void(document.forms[0].submit());</script>\n</form>\n";
			flush ();
			exit ();
		}
		//Redirect
    if (($_GET ["premium_acc"] == "on" && $_GET ["premium_user"] && $_GET ["premium_pass"])
    || ($_GET ["premium_acc"] == "on" && $premium_acc ["megaupload"] ["user"] && $premium_acc ["megaupload"] ["pass"])
    || ($_GET ["mu_acc"] == "on" && ($_GET ["mu_cookie"] || $_GET["mu_hash"] || $_GET["auth_hash"] || $mu_cookie_user_value)) )
		{
			DownloadPremium($link);
		} 
		else 
		{
			DownloadFree($link);
		}
	}
	
	function DownloadFree($link) 
	{
        global $Referer;
		$post = array();

		//Get password
		$arr = explode("|", $link);
		if (count($arr)>=2) 
		{
			$link = $arr[0];
			$post ["filepassword"] = $arr[1];
		}
		
		$page = GetPage($link, 0, $post, $Referer);
        is_present ( $page, "The file you are trying to access is temporarily unavailable" );
		is_present ( $page, "link you have clicked is not available", "File not found, Unfortunately, the link you have clicked is not available!" );

		if (stristr($page,'password protected')) 
		{
			html_error("Password is incorrect! Input link with password: Link|Password.");
        }
		
		$countDown = trim ( cut_str ( $page, "count=",";" ) );
		//$countDown = rand(5, 10);
		insert_timer( $countDown, "<b>Megaupload Free User</b>.","",true );
		
		preg_match('/http:\/\/(.*)" class="down_butt1"/', $page, $match);
		if (isset($match[1])) 
		{
			$Href = 'http://'.$match[1];
			$Url = parse_url ( html_entity_decode($Href, ENT_QUOTES, 'UTF-8') );
			if (! is_array ( $Url )) 
			{
				html_error ( "Download link not found, Plugin needs to be updated Error 1!", 0 );
			}
			$FileName = ! $FileName ? strip_quotes(basename ( stripslashes($Url ["path"]) )) : $FileName;
			RedirectDownload( $Href, $FileName );
			exit ();
		}
		else 
		{
			html_error ( "Download link not found, Plugin needs to be updated Error 2!", 0 );
        }
	}
	
	function DownloadPremium($link) 
	{
		global $Referer, $premium_acc, $mu_cookie_user_value;
		
		$post = array();
		$post ['login'] = 1;

    if(isset($_GET["auth_hash"])){
      require_once("other.php");
      $split_hash = explode(":", strrev(dcd($_GET["auth_hash"])));
      if(count($split_hash)>1)
      {
      $_GET["premium_user"] = $split_hash[0];
      $_GET["premium_pass"] = $split_hash[1];
      }
    }
                  
		$post ["username"] = $_GET ["premium_user"] ? $_GET ["premium_user"] : $premium_acc ["megaupload"] ["user"];
		$post ["password"] = $_GET ["premium_pass"] ? $_GET ["premium_pass"] : $premium_acc ["megaupload"] ["pass"];
		$page = GetPage('http://www.megaupload.com/?c=login',0,$post,'http://www.megaupload.com');
		is_page($page);

    $premium_cookie = trim ( cut_str ( $page, "Set-Cookie:", ";" ) );
                   
    if ($mu_cookie_user_value) {
          $premium_cookie = 'user=' . $mu_cookie_user_value;
    } elseif ($_GET ["mu_acc"] == "on" && $_GET ["mu_cookie"]) {
          $premium_cookie = 'user=' . $_GET ["mu_cookie"];
    } elseif ($_GET["mu_hash"]) {
          $premium_cookie = 'user=' . strrev(dcd($_GET["mu_hash"]));             
    } elseif (! stristr ( $premium_cookie, "user" )) {
           html_error ( "Cannot use premium account", 0 );
    }
	
		//Get password
		$post = array();
		$arr = explode("|", $link);
		if (count($arr)>=2) 
		{
			$link = $arr[0];
			$post ["filepassword"] = $arr[1];
		}
		
		$page = GetPage($link,$premium_cookie,$post,$Referer);
		is_present ( $page, "The file you are trying to access is temporarily unavailable" );
		is_present ( $page, "link you have clicked is not available", "File not found, Unfortunately, the link you have clicked is not available!" );
				
		if (stristr($page,'password protected')) 
		{
			html_error("Password is incorrect! Input link with password: LINK|PASSWORD.");
		}

        if (stristr ( $page, "Location:" )) 
		{
			//Premium with Direct active
			$Href = trim ( cut_str ( $page, "Location: ", "\n" ) );	
        } 
		elseif (preg_match('/http:\/\/(.*)" class="down_ad_butt1"/', $page, $match)) 
		{
			//Premium with Direct disable
			$Href = "http://" . $match[1];
			$Referer = $link;
        } 
		elseif (preg_match('/http:\/\/(.*)" class="down_butt1"/', $page, $match)) 
		{
			//Free account - member
			echo "<div>Using free acoount - You're member</div>";
			$Href = "http://" . $match[1];
			$Referer = $link;
		} 
		else 
		{
			html_error ( "Download link not found, Plugin needs to be updated!", 0 );
        }
		
		$Url = parse_url ( html_entity_decode($Href, ENT_QUOTES, 'UTF-8') );
		$FileName = ! $FileName ? strip_quotes(basename ( stripslashes($Url ["path"]) )) : $FileName;
		RedirectDownload( $Href, $FileName, $premium_cookie );
	}

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


/*******************megaupload.com*******************************\
megaupload.com download plugin
Updated by Raj Malhotra on 10 Jan 2010 => MegaUpload captcha is downloaded on server, then display
Fixed by Raj Malhotra on 20 Jan 2010   => Fixed for Download link not found in happy hour
Fixed by VinhNhaTrang on 13 Oct 2010
Fixed by VinhNhaTrang on 30 Nov 2010
Fixed by thangbom40000 on 1 Dec 2010   => Fix for free user and premium download, no wait time, no capcha with free user.
Fixed by thangbom40000 on 4 Dec 2010   => Fix input link with password: LINK|PASSWORD
Updated by Raj Malhotra on 12 Dec 2010 => Added some improvements
Rewrite into 36B by Ruud v.Tony, also added auth hash based idoenx mods
\*******************megaupload.com*******************************/
 ?>