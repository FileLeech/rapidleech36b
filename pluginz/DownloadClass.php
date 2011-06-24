<?php
if (!defined('RAPIDLEECH')) {
	require('../404.php');
	exit;
}

class DownloadClass {
	/**
	 * Prints the initial form for displaying messages
	 * 
	 * @return void
	 */
	public function __construct() {
		global $htxt;
		echo('<table width="600" align="center">');
		echo('<tr>');
		echo('<td align="center">');
		echo('<div id="mesg" width="100%" align="center"><b>'.$htxt['_retrieving'].'</b></div>');
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
	public function GetPage($link, $cookie = 0, $post = 0, $referer = 0, $auth = 0) {
		global $pauth;
		if (!$referer) {
			global $Referer;
			$referer = $Referer;
		}
		$Url = parse_url(trim($link));
		$page = geturl ( $Url ["host"], $Url ["port"] ? $Url ["port"] : 80, $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : ""), $referer, $cookie, $post, 0, $_GET ["proxy"], $pauth, $auth, $Url ["scheme"] );
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
	public function RedirectDownload($link, $FileName, $cookie = 0, $post = 0, $referer = 0, $force_name = 0, $auth = "", $params = array()) {
		global $pauth;
		if (!$referer) {
			global $Referer;
			$referer = $Referer;
		}

		$Url = parse_url($link);

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
			urlencode ( $_GET ["comment"] ) : "") . ($auth ? '&auth=' . ($auth == 1 ? 1 : urlencode($auth)) : "") . ($pauth ? "&pauth=$pauth" : "") .
			($_GET ["uploadlater"] ? "&uploadlater=".$_GET["uploadlater"]."&uploadtohost=".$_GET['uploadtohost'] : "") .
			"&cookie=" . ($cookie ? urlencode(encrypt($cookie)) : 0).
			"&post=" . urlencode ( serialize ( $post ) ) .
			($_POST ["uploadlater"] ? "&uploadlater=".$_POST["uploadlater"]."&uploadtohost=".urlencode($_POST['uploadtohost']) : "").
			($_POST ['autoclose'] ? "&autoclose=1" : "").
			(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "") . $addon;

		if ( $force_name )
		{
			$loc = $loc . "&force_name=" . urlencode ( $force_name );
		}

		insert_location ( $loc );
	}

  public function moveToAutoDownloader( $link_array )
	{
		global $nn;
		if ( count( $link_array ) == 0 )
		{
			html_error( 'Error getting links from folder.' );
		}

		if (! is_file( "audl.php" ) )
		{
			html_error ( 'audl.php not found' );
		}

		$links = "";
		foreach( $link_array as $key => $value )
		{
			$links .= $value . $nn;
		}

		echo "<form action='audl.php?crot=step2' method='post' >\n";
		echo "<input type='hidden' name='links' value='" . $links . "' />\n";
		$key_array = array ( "useproxy", "proxy", "proxyuser", "proxypass" );
		foreach ( $key_array as $v )
		echo "<input type='hidden' name='" . $v . "' value='" . $_GET [$v] . "' />\n";
		echo "<script type='text/javascript'>void(document.forms[0].submit());</script>\n";
		echo "</form>\n";
		flush();
		exit();
	}

	public function CountDown($countDown) {
		insert_timer ( $countDown, "Waiting link timelock" );
	}

	public function EnterCaptcha( $captchaImg, $inputs, $captchaSize = '5' ) 
	{
		$defaultParam_array = array();
		$defaultParam_array["comment"] = $_GET ["comment"];
		$defaultParam_array["email"] = $_GET ["email"];
		$defaultParam_array["partSize"] = $_GET ["partSize"];
		$defaultParam_array["method"] = $_GET ["method"];
		$defaultParam_array["proxy"] = $_GET ["proxy"];
		$defaultParam_array["proxyuser"] = $_GET ["proxyuser"];
		$defaultParam_array["proxypass"] = $_GET ["proxypass"];
		$defaultParam_array["path"] = $_GET ["path"];

		$this->EnterCaptchaDefault( $captchaImg, $inputs, $captchaSize, $defaultParam_array );
	}

	public function EnterCaptchaDefault($captchaImg, $inputs) {
		global $htxt;
		echo('<form name="dl" action="'.$_SERVER['PHP_SELF'].'" method="post">');
		foreach ($inputs as $name => $input) {
			echo('<input type="hidden" name="'.$name.'" id="'.$name.'" value="'.$input.'" />');
		}
		echo('<h4><b>'.$htxt['_enter'].' </b><img src="'.$captchaImg.'" /><b>'.$htxt['_here'].':</b><input type="text" name="captcha" size="5" />&nbsp;&nbsp;<input type="submit" onclick="return check();" value="Transload File" /></h4>');
		echo('<script type="text/javascript">');
		echo('function check() {');
		echo('var captcha=document.dl.captcha.value;');
		echo('if (captcha == "") { window.alert("You didn\'t enter the image verification code"); return false; }');
		echo('else { return true; }');
		echo('}');
		echo('</script>');
		echo('</form>');
		echo('</body>');
		echo('</html>');
	}

	public function changeMesg($mesg) {
		echo('<script>document.getElementById(\'mesg\').innerHTML=\''.stripslashes($mesg).'\';</script>');
	}
}

/**********************************************************
Added support of force_name in RedirectDownload function by Raj Malhotra on 02 May 2010
Fixed  EnterCaptcha function ( Re-Write )  by Raj Malhotra on 16 May 2010
Added auto-encryption system (szal) 14 June 2010
**********************************************************/
?>