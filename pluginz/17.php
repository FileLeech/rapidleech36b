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
		if ( ( $_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"] ) ||
			( $_REQUEST ["premium_acc"] == "on" && $premium_acc ["easyshare"] ["user"] && $premium_acc ["easyshare"] ["pass"] ) )
		{
			DownloadPremium( $link );
		}
		else
		{
			DownloadFree( $link );
		}
	}
		
	function DownloadFree( $link )
	{
		global $pauth;
		$Referer = $link;
		
		$page = GetPage( $link, 0, 0, 0, $pauth );
		
		$cookies = biscottiDiKaox( $page );
		is_present ( $page, 'File was deleted' );
		is_present ( $page, 'File not found' );
        is_present ( $page, 'You have downloaded over 150MB during last hour.' );  
		$FileName = trim ( cut_str ( $page, "<title>Download ", "," ) );
				
		$div = trim ( cut_str ( $page, '<div id="block-captcha">', "</div>" ) );
		$count = trim ( cut_str ( $div, "w='", "'" ) );
		
		insert_timer( $count, "Waiting link timelock");
		
		if ( $src = trim ( cut_str ( $page, "u='", "'" ) ) )
		{
			$Url = parse_url( $link );
			$Href = "http://".$Url["host"].$src;
			$page = GetPage( $Href, $cookies, 0, $Referer, $pauth );
        }
		
        $Href = trim ( cut_str($page,'post" action="','"') );
        $id = trim ( cut_str($page,'"id" value="','"') ); 
		
		$post = array ();
		$post ["captcha"] = "";
		$post ["id"] = $id;
		
		RedirectDownload( $Href, $FileName, $cookies, $post, $Referer, $pauth );
		exit ();
	}
	
	function DownloadPremium( $link )
	{
		global $premium_acc, $pauth, $Referer;
		
		// Getting file name
		$page = GetPage( $link, 0, 0, 0, $pauth );
		is_present ( $page, 'File was deleted' );
		is_present ( $page, 'File not found' );
        $FileName = trim ( cut_str ( $page, "<title>Download ", "," ) );
		// Getting file name end
		
		// login 
		$login = "http://www.easy-share.com/accounts/login";
		
		$post ["login"] = $_REQUEST ["premium_user"] ? $_REQUEST ["premium_user"] : $premium_acc ["easyshare"] ["user"];
		$post ["password"] = $_REQUEST ["premium_pass"] ? $_REQUEST ["premium_pass"] : $premium_acc ["easyshare"] ["pass"];
		$post ["remember"] = "1";
			
		$page = GetPage( $login, 0, $post, "http://www.easy-share.com/", $pauth );
		
		$cook = GetCookies( $page );
		// end login 
		
		is_notpresent ( $cook, "PREMIUM", "Login failed<br>Wrong login/password?" );
		
		$page = GetPage( $link, $cook, 0, 0, $pauth );
		is_present ( $page, 'File was deleted' );
		is_present ( $page, 'File not found' );
		
		if ( !isset($FileName) || $FileName == "" )
		{
			$Url = parse_url ( $link );
			$FileName = ! $FileName ? basename ( $Url ["path"] ) : $FileName;
		}
		
		preg_match ( '/Location:.+?\\r/i', $page, $loca );
		$redir = rtrim ( $loca [0] );
		preg_match ( '/http:.+/i', $redir, $loca );
		$Href = trim ( $loca [0] );
		
		$cookie = $cook . "; " . biscottiDiKaox ( $page );
	
		RedirectDownload( $Href, $FileName, $cookie, 0, $Referer, $pauth );
		exit ();
	}
	
	function biscottiDiKaox( $content )
	{
		preg_match_all("/Set-Cookie: (.*)\n/",$content,$matches);
		foreach ( $matches[1] as $coll ) 
		{
			$bis0=split(";",$coll);
			$bis1=$bis0[0]."; ";
			$bis2=split("=",$bis1);
			$cek=" ".$bis2[0]."="; 
			if( strpos( $bis1,"=deleted" ) || strpos( $bis1,$cek.";" ) ) 
			{
			}
			else
			{
				if ( substr_count( $bis,$cek ) > 0 )
				{
					$patrn=" ".$bis2[0]."=[^ ]+";
					$bis=preg_replace("/$patrn/"," ".$bis1,$bis);     
				} 
				else 
				{
					$bis.=$bis1;
				}
			}
		}  
		
		$bis=str_replace("  "," ",$bis);     
		return rtrim($bis);
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

/**************************************************\  
FIXED by kaox 04/07/2009
FIXED and RE-WRITTEN by rajmalhotra on 10 Jan 2010
FIXED by rajmalhotra on 12 Feb 2010 => FIXED downloading from Premium Account
\**************************************************/	

?>