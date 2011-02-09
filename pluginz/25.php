<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
    function Download($link) {
        if ($_POST["step"] == 1) {
            $dlink = $_POST["link"];
            $cookies = $_POST['cookie'];
            $Referer = $_Post['referer'];
            $FileName = $_POST['filename'];
            $Url=parse_url($dlink);
            $FileName=$FileName?$FileName:basename($Url['path']);
            RedirectDownload($dlink, $FileName, $cookies, 0, $Referer, $FileName);
        } else {
            $page = GetPage($link);
            is_present($page, "File does not exist on this server", "File does not exist on this server");
            if (preg_match("#<title>Zippyshare.com - (.*)</title>#", $page, $temp)) {
                $FileName = $temp[1];
            }
            $cookies = GetCookies($page);
            if (!preg_match("#var zipdata = .*#", $page, $data)) {
                html_error("Error 1: Plugin is out of date");
            }
            if (!preg_match("#var fulllink = .*#", $page, $fulllink)) {
                html_error("Error 2: Plugin is out of date");
            }
            $jscript = $data[0] . $fulllink[0];
            $ss = <<<HTML
<html>
<head>
<title>FormLogin</title>
</head>
<body bgcolor="#FFFFFF" text="#000000">
<form method="post" name="plink" action="$PHP_SELF">
<input id="link" name="link" type="hidden">
<input type="hidden" name="cookie" value="$cookies" >
<input type="hidden" name="referer" value="$link" >
<input type="hidden" name="step" value="1" >
<input type="hidden" name="filename" value="$FileName">
</form>
HTML;
            $script = $ss . '<script language="Javascript">' . $jscript . 'document.getElementById("link").value=fulllink; document.plink.submit();</script>';
            echo ($script);
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

/*
 * By vdhdevil Jan-12-2011
 * Rewrite into 36B by Ruud v.Tony
 */
?>