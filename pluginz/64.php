<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
	function Download( $link )
	{
		global $premium_acc;
		DownloadFree($link);
	}

	function DownloadFree($link)
	{
		global $nn, $PHP_SELF, $pauth;
		
		$LINK =  str_replace("https", "http", $LINK);
		$page = GetPage($link);
		is_page($page);
		
		preg_match('/action="([^\"]*)"/i', $page, $redir);
		$Href = trim($redir[1]);
		
		$random_furk = cut_str($page,'<input type="hidden" name="rand" value="','" />');
		$countd = cut_str($page,'startFreeDownload(',');');
	
		insert_timer($countd, "Waiting link timelock");
	
		$post = Array();
		$post["rand"] = $random_furk;
		$Url = parse_url($Href);
		
		$FileName = !$FileName ? basename($Url["path"]) : $FileName;
	
		RedirectDownload($Href,$FileName,0, $post);
		exit ();
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

// Created by rajmalhotra on 05 Dec 09
// Fixed countdown by Th3-822 on 31 Dec 10
// Rewrite into 36B by Ruud v.Tony
?>