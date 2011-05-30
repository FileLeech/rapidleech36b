<?php
if (! defined ( 'RAPIDLEECH' ))
{
	require_once ("404.php");
	exit ();
}
	
	Download( $LINK );
	
	function Download($link) 
	{
		global $premium_acc;
		if ( ($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
			($_REQUEST ["premium_acc"] == "on" && $premium_acc ["x7"] ["user"] && $premium_acc ["x7"] ["pass"] ) )
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
		global $Referer;
		
		$Url = parse_url( $link );
		$id = basename($Url['path']);
		//preg_match('%/x7.to/(.+?)/%', $link, $id);
		//$id = $id[1];
		
		$getlink = "http://x7.to/james/ticket/dl/$id";
		
		$post = Array();
		$page = GetPage( $getlink, 0, $post, $Referer );
			
		$cookie = GetCookies($page);
		
		$badJson = trim ( cut_str ( $page, '{', '}' ) );
		$badJson = "{". $badJson. "}";
		
				
		$raj = convertToJson( $badJson );
		
		$json = str_replace( "'", "\"", $raj );
		$jsarray = json_decode($json, true);
				
		if (!$jsarray)
		{
			html_error("Cannot decode Json string!",0);
		}

		if ( $jsarray['err'] == "limit-dl" )
		{
			html_error("Download limit exceeded.",0 );
		}
		else if ( $jsarray['type'] == "download" )
		{
			$Url = parse_url( $jsarray['url'] );
			$downloadId = basename($Url['path']);
			
			if ( ( $jsarray['url'] == "" ) || ( $downloadId == "dl" ) )
			{
				html_error("File not found. Kindly check the link",0 );
			}
			else
			{
				$count = $jsarray['wait'];
				if ( $count > 0 )
				{
					insert_timer( $count, "Waiting link timelock", "", true );
				}
			}
		}
				
		$Href = trim( $jsarray['url'] );
		$FileName = $id;
		
		RedirectDownload($Href, $FileName, $cookie);
		exit ();
	}
	
	function DownloadPremium( $link )
	{
		html_error("Working with Premium Account is not coded. This plugin need to update.",0);
	}
	
	function convertToJson( $badJson )
	{
		$badJson = str_replace( "{", "", $badJson );
		
		$pieces = explode( ",", $badJson );

		$json = "{";
		$isModified = false;
		foreach( $pieces as $key => $value )
		{		
			if ( $isModified )
			{
				$json = $json.",";
			}
			
			$piece = explode(":", $value);
			
			$json = $json."'".$piece[0]."'";
			for ( $i = 1; $i<count( $piece ); $i++ )
			{
				$json = $json.":".$piece[$i];
			} 
						
			$isModified = true;
		}
		
		return $json;
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
WRITTEN by rajmalhotra  26 Jan 2010
\**************************************************/
?>