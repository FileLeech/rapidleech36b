<?php
if (! defined ( 'RAPIDLEECH' ))
  {require_once("404.php");exit;}

	$link = $LINK;
	if ( isset($_POST['step'] ) ) 
	{
		if ( $_POST['step'] == 1 ) 
		{
			return DownloadFree($link);
		} 
		else 
		{
			return EnterCaptchaCode($link);
		}
	} 
	else 
	{
		EnterCaptchaCode($link);
	}
	
	function EnterCaptchaCode($link)
	{
		global $nn, $PHP_SELF, $pauth;
		$page = GetPage($link);
		
		//is_present ( $page, "Due to a violation of our terms of use, the file has been removed from the server." );

		$cookie = "";
		preg_match_all("/Set-Cookie: ([^;]+;)/", $page, $cook);
		$arraySize = count($cook);

		for ( $i=0;$i<$arraySize;$i++)
		{
			$cookie=$cookie.array_shift($cook[1]);
		}
			
		$count = trim ( cut_str ( $page, '<span id="countdown">', '</span>' ) );
		
		$op = trim ( cut_str ( $page, '<input type="hidden" name="op" value="', '"' ) );
		$id = trim ( cut_str ( $page, '<input type="hidden" name="id" value="', '"' ) );
		$rand = trim ( cut_str ( $page, '<input type="hidden" name="rand" value="', '"' ) );
		$referer = trim ( cut_str ( $page, '<input type="hidden" name="referer" value="', '"' ) );
		$method_free = trim ( cut_str ( $page, '<input type="hidden" name="method_free" value="', '"' ) );
		$method_premium = trim ( cut_str ( $page, '<input type="hidden" name="method_premium" value="', '"' ) );
		$down_script = trim ( cut_str ( $page, '<input type="hidden" name="down_script" value="', '"' ) );
		
		$captchaImage = trim ( cut_str ( $page, '<img src="http://evilshare.com/captchas/', '">' ) );
		$captcha_access_url = "http://evilshare.com/captchas/".$captchaImage;
		
		insert_timer( $count, "Waiting link timelock");
		
		print "<form name=\"dl\" action=\"".$PHP_SELF."?".(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "")."\" method=\"post\">\n";
		print "<input type=\"hidden\" name=\"link\" value=\"" . urlencode ( $link ) . "\">\n";
		
		print "<input type=\"hidden\" name=\"op\" value=\"" . urlencode ( $op ) . "\">\n";
		print "<input type=\"hidden\" name=\"id\" value=\"" . urlencode ( $id ) . "\">\n";
		print "<input type=\"hidden\" name=\"rand\" value=\"" . urlencode ( $rand ) . "\">\n";
		print "<input type=\"hidden\" name=\"referer\" value=\"" . urlencode ( $referer ) . "\">\n";
		print "<input type=\"hidden\" name=\"method_free\" value=\"" . urlencode ( $method_free ) . "\">\n";
		print "<input type=\"hidden\" name=\"method_premium\" value=\"" . urlencode ( $method_premium ) . "\">\n";
		print "<input type=\"hidden\" name=\"down_script\" value=\"" . urlencode ( $down_script ) . "\">\n";
		print "<input type=\"hidden\" name=\"step\" value=\"1\">\n";
		
		print "<input type=\"hidden\" name=\"comment\" id=\"comment\" value=\"" . $_GET ["comment"] . "\">\n";
		print "<input type=\"hidden\" name=\"email\" id=\"email\" value=\"" . $_GET ["email"] . "\">\n";
		print "<input type=\"hidden\" name=\"partSize\" id=\"partSize\" value=\"" . $_GET ["partSize"] . "\">\n";
		print "<input type=\"hidden\" name=\"method\" id=\"method\" value=\"" . $_GET ["method"] . "\">\n";
		print "<input type=\"hidden\" name=\"proxy\" id=\"proxy\" value=\"" . $_GET ["proxy"] . "\">\n";
		print "<input type=\"hidden\" name=\"proxyuser\" id=\"proxyuser\" value=\"" . $_GET ["proxyuser"] . "\">\n";
		print "<input type=\"hidden\" name=\"proxypass\" id=\"proxypass\" value=\"" . $_GET ["proxypass"] . "\">\n";
		print "<input type=\"hidden\" name=\"path\" id=\"path\" value=\"" . $_GET ["path"] . "\">\n";
		print "<h4>Enter <img src=\"$captcha_access_url\" > here: ";
		print "<input type=\"text\" name=\"code\" size=\"4\">&nbsp;&nbsp;";
		print "<input type=\"submit\" onclick=\"return check()\" value=\"Download File\"></h4>\n";
		
		print "<script language=\"JavaScript\">" . $nn . "function check() {" . $nn . "var imagecode=document.dl.code.value;" . $nn . 'if (imagecode == "") { window.alert("You didn\'t enter the image verification code"); return false; }' . $nn . 'else { return true; }' . $nn . '}' . $nn . '</script>' . $nn;
		print "</form>\n</body>\n</html>";
	}	
		
	function DownloadFree($link)
	{
		global $Referer;
				
		if ( $_GET ["step"] == "1" ) 
		{
			$post = array ();
			$post ["op"] = $_GET ["op"];
			$post ["id"] = $_GET ["id"];
			$post ["rand"] = $_GET ["rand"];
			$post ["referer"] = $_GET ["referer"];
			$post ["method_free"] = $_GET ["method_free"];
			$post ["method_premium"] = $_GET ["method_premium"];
			$post ["code"] = $_GET ["code"];
			$post ["down_script"] = $_GET ["down_script"];			
		} else
		{
			// error
			html_error ( "Kindly execute catpcha step then this step come.", 0 );
		}
		
		$page = GetPage($link, 0, $post, $Referer );
		preg_match ( '/Location: (.*)/', $page, $newredir );
		
		$FileName = "";		
		$Href = trim ( $newredir [1] );
		$Url = parse_url ( $Href );
		$FileName = ! $FileName ? basename ( $Url ["path"] ) : $FileName;
		
		//RedirectDownload($Href,$FileName,$cookie, 0,$Referer);
		RedirectDownload( $Href, $FileName, 0, 0, $Referer );
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
	
// download plug-in writted by rajmalhotra  12 Dec 2009		
?>