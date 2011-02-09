<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
	function Download( $link ) 
	{
		$page = GetPage($link);
		is_present( $page, "file link that you requested is not valid", "The file link that you requested is not valid. Please contact link publisher or try to make a search", "0" );
		$cookie = GetCookies($page);

		if ($_GET ["step"] == "1") {
			$post = Array();
			$post["userPass2"] = $_POST['userPass2'];
			$cookie = urldecode($_POST['cookie']);
			$page = GetPage($link,$cookie,$post,$link);
			is_present($page, "enter password to access this file", "The password you have entered is not valid.");
		} elseif (stristr($page, 'enter password to access this file')) {
			echo "\n" . '<form name="dl_password" action="' . $PHP_SELF . '" method="post" >' . "\n";
			echo '<input type="hidden" name="link" value="' . urlencode ($link) . '" />' . "\n";
			echo '<input type="hidden" name="referer" value="' . urlencode ($Referer) . '" />' . "\n";
			echo '<input type="hidden" name="cookie" value="' . urlencode ($cookie) . '" />' . "\n";
			echo '<input type="hidden" name="step" value="1" />' . "\n";
			echo '<input type="hidden" name="comment" id="comment" value="' . $_GET ["comment"] . '" />' . "\n";
			echo '<input type="hidden" name="email" id="email" value="' . $_GET ["email"] . '" />' . "\n";
			echo '<input type="hidden" name="partSize" id="partSize" value="' . $_GET ["partSize"] . '" />' . "\n";
			echo '<input type="hidden" name="method" id="method" value="' . $_GET ["method"] . '" />' . "\n";
			echo '<input type="hidden" name="proxy" id="proxy" value="' . $_GET ["proxy"] . '" />' . "\n";
			echo '<input type="hidden" name="proxyuser" id="proxyuser" value="' . $_GET ["proxyuser"] . '" />' . "\n";
			echo '<input type="hidden" name="proxypass" id="proxypass" value="' . $_GET ["proxypass"] . '" />' . "\n";
			echo '<input type="hidden" name="path" id="path" value="' . $_GET ["path"] . '" />' . "\n";
			echo '<h4>Enter password here: <input type="text" name="userPass2" id="filepass" size="13" />&nbsp;&nbsp;<input type="submit" onclick="return check()" value="Download File" /></h4>' . "\n";
			echo "<script language='JavaScript'>\nfunction check() {\nvar pass=document.getElementById('filepass');\nif (pass.value == '') { window.alert('You didn\'t enter the password'); return false; }\nelse { return true; }\n}\n</script>\n";
			echo "</form>\n</body>\n</html>";
			exit;
		}

		getCountDown($page);
		$FileName = trim(cut_str($page, 'name="Description" content="', ' download free at 2shared.'));

		// Retrieve download link
		if (preg_match ('/dc(\d+)\.2shared\.com\/download(\d)\/([^\'|\"]+)/i', $page, $L)) {
			$dllink = "http://dc" . $L[1] . ".2shared.com/download" . $L[2] . "/" . $L[3];
		} else {
			html_error("Download-link not found.");
		}

		RedirectDownload($dllink, $FileName, $cookie);
	}
	
	function getCountDown($page) 
	{
		if (preg_match ( '/var c = ([0-9])*;/', $page, $count ) ) 
		{
			$countDown = $count [1];
			insert_timer ( $countDown, "Waiting link timelock" );
		}
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


/********************************************************
Fixed by Raj Malhotra on 10 April 2010 => Fix Reloading to main page when link does not exists.

Fixed by Th3-822 on 30 October 2010 => Fixed & Added support for password protected files.
Fixed by Th3-822 on 25 December 2010 => Fixed: 2shared changed it's system (Again... Now shows dlink in same page)
Rewrite into 36B by Ruud v.Tony
*********************************************************/
?>