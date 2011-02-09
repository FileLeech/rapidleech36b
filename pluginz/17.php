<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
    function Download($link) {
        global $premium_acc;
        if (( $_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"] ) ||
                ( $_REQUEST ["premium_acc"] == "on" && $premium_acc ["easyshare_com"] ["user"] && $premium_acc ["easyshare_com"] ["pass"] )) {
            DownloadPremium($link);
        } else if ($_POST['easy_share'] == "ok") {
            DownloadFree($link);
        } else {
            Retrieve($link);
        }
    }

    function Retrieve($link) {
        global $download_dir;    
        $page = GetPage($link);
        is_present($page, "The file could not be found", "The file could not be found. Please check the download link.");
        is_present($page, "File not available", "File not available");
        is_present($page, "Page not found", "The file could not be found. Please check the download link.");
        $cookie = GetCookies($page);
        $FileName = cut_str($page, 'Download ', ',');
        $FileName = trim($FileName);
        $linkcaptcha = cut_str($page, "/file_contents/captcha/", "'");
        if (!strpos($page, 'method="post" action="')) {
            $time = cut_str($page, "w='", "'");
            insert_timer($time);
            $randnum = rand(10000, 100000);
            $Referer = $link;
            $linkcaptcha = "http://www.easy-share.com/file_contents/captcha/" . $linkcaptcha;
            $page = GetPage($linkcaptcha, $cookie, 0, $Referer);
            $cookie .='; ' . GetCookies($page);
        }
        $linkpost = cut_str($page, 'method="post" action="', '"');
        $linkcaptcha = cut_str($page, 'Recaptcha.create("', '"');
        $valid = cut_str($page, 'name="id" value="', '"');
        $page = GetPage('http://www.google.com/recaptcha/api/challenge?k=' . $linkcaptcha . '&ajax=1');
        $challenge = cut_str($page, "challenge : '", "'");
        $img = 'http://www.google.com/recaptcha/api/image?c=' . $challenge;
        $page = GetPage($img);
        $headerend = strpos($page, "\r\n\r\n");
        $pass_img = substr($page, $headerend + 4);
        write_file($download_dir . "easyshare_captcha.jpg", $pass_img);
        $data = array();
        $data['link'] = $linkpost;
        $data['referer'] = $referer;
        $data['challenge'] = $challenge;
        $data['valid'] = $valid;
        $data['easy_share'] = "ok";
        $data['cookie'] = $cookie;
        $data['FileName'] = $FileName;
        EnterCaptcha($download_dir . "easyshare_captcha.jpg", $data, 5);
    }

    function DownloadFree($link) {
        $post = array();
        $post["recaptcha_challenge_field"] = $_POST['challenge'];
        $post["recaptcha_response_field"] = $_POST['captcha'];
        $post["id"] = $_POST['valid'];
        $cookie = $_POST['cookie'];
        $Referer = $_POST['referer'];
        $FileName = $_POST["FileName"];
        $Url = parse_url($link);
        $FileName = !$FileName ? basename($Url["path"]) : $FileName;
        RedirectDownload($link, $FileName, $cookie, $post, $Referer);
    }

    function DownloadPremium($link) {
        global $premium_acc, $pauth, $Referer;
        $Referer = "http://www.easy-share.com/";
        $page = GetPage($link, 0, 0, 0, $pauth);
        is_present($page, 'File was deleted');
        is_present($page, 'File not found');
        $FileName = trim(cut_str($page, "<title>Download ", ","));
        $FileName = str_replace(" ", ".", $FileName);
        $login = "http://www.easy-share.com/accounts/login";
        $post = array();
        $post ["login"] = $_REQUEST ["premium_user"] ? $_REQUEST ["premium_user"] : $premium_acc ["easyshare"] ["user"];
        $post ["password"] = $_REQUEST ["premium_pass"] ? $_REQUEST ["premium_pass"] : $premium_acc ["easyshare"] ["pass"];
        $post ["remember"] = "1";
        $page = GetPage($login, 0, $post, "http://www.easy-share.com/", $pauth);
        $cookies = GetCookies($page);
        if (!preg_match("#PREMIUM=[\w%]+#", $cookies, $Premium)) {
            html_error("Login Failed , Bad username/password combination");
        }
        preg_match("#PHPSESSID=\w+#", $cookies, $PhpSessId);
        $page = GetPage($link, $cookies, 0, $Referer, $pauth);
        $cookies = $PhpSessId[0] . "; " . $Premium[0] . "; " . GetCookies($page);
        if (preg_match("#Location: (.*)#", $page, $prelink)) {
            if (function_exists(encrypt) && $cookies != "") {
                $cookies = encrypt($cookies);
            }
            $Url = parse_url($prelink[1]);
            insert_location("$PHP_SELF?filename=" . urlencode($FileName) .
                    "&host=" . $Url["host"] .
                    "&path=" . urlencode($Url["path"] . ($Url["query"] ? "?" . $Url["query"] : "")) .
                    "&referer=" . urlencode($Referer) .
                    "&cookie=" . urlencode($cookies) .
                    "&email=" . ($_GET["domail"] ? $_GET["email"] : "") .
                    "&partSize=" . ($_GET["split"] ? $_GET["partSize"] : "") .
                    "&method=" . $_GET["method"] . "&proxy=" . ($_GET["useproxy"] ? $_GET["proxy"] : "") .
                    "&saveto=" . $_GET["path"] . "&link=" . $link . ($_GET["add_comment"] == "on" ? "&comment=" . urlencode($_GET["comment"]) : "") .
                    "&pauth=" . (isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
        }
        exit();
    }

  function EnterCaptcha($captchaImg, $inputs, $captchaSize = '5', $defaultParam_array = array()) {
    echo "\n";
    echo('<form name="dl" action="' . $_SERVER['PHP_SELF'] . '" method="post">');
    echo "\n";
    foreach ($inputs as $name => $input) {
        echo('<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $input . '" />');
        echo "\n";
    }
    if (!empty($defaultParam_array)) {
        foreach ($defaultParam_array as $name => $input) {
            echo('<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $input . '" />');
            echo "\n";
        }
    }
    echo('<h4>Enter <img src="' . $captchaImg . '" /> here: <input type="text" name="captcha" size="' . $captchaSize . '" />&nbsp;&nbsp;');
    echo "\n";
    echo( '<input type="submit" onclick="return check();" value="Enter Captcha" /></h4>');
    echo "\n";
    echo('<script type="text/javascript">');
    echo "\n";
    echo('function check() {');
    echo "\n";
    echo('var captcha=document.dl.captcha.value;');
    echo "\n";
    echo('if (captcha == "") { window.alert("You didn\'t enter the image verification code"); return false; }');
    echo "\n";
    echo('else { return true; }');
    echo "\n";
    echo('}');
    echo "\n";
    echo('</script>');
    echo "\n";
    echo('</form>');
    echo "\n";
    echo('</body>');
    echo "\n";
    echo('</html>');
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


/* * ************************************************\
  FIXED by kaox 04/07/2009
  FIXED and RE-WRITTEN by rajmalhotra on 10 Jan 2010
  FIXED by rajmalhotra on 12 Feb 2010 => FIXED downloading from Premium Account
  FIXED by vdhdevil on 01 Dec 2010 => Fixed Premium for v42
  FIXED by Ruud v.Tony on 6 Feb 2011 => Fixed the free codes, my first rapidleech code made, lol :D
  \************************************************* */
?>