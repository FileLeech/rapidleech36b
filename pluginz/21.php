<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
    function Download($link) {
        global $premium_acc, $options;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["bitshare_com"] ["user"] && $premium_acc ["bitshare_com"] ["pass"])) {
            DownloadPremium($link);
        } elseif ($_POST['step'] == "1") {
            DownloadFree($link);
        } else {
            Retrieve($link);
        }
    }

    function Retrieve($link) {
        global $options;
        $page = GetPage($link);
        is_present($page, "Error - File not available", "Error - File not available");
        if (preg_match('#<span id="blocktimecounter">(\d+) seconds#', $page, $wait)) {
            //html_error("Please wait " . $wait[1] . " seconds to start the download");
            insert_timer($wait[1]);
        }
        $Cookies = GetCookies($page);
        if (!preg_match('#var ajaxdl = "(.*)"#', $page, $temp)) {
            html_error("Error 0x01- Plugin is out of date", 0);
        }
        if (!preg_match('#(http.*files-ajax.*)"#', $page, $UrlPost)) {
            html_error("Error 0x02- Plugin is out of date", 0);
        }
        $ajaxdl = $temp[1];
        $post = array();
        $post["request"] = "generateID";
        $post["ajaxid"] = $ajaxdl;
        $page = GetPage(trim($UrlPost[1]), $Cookies, $post, $link);
        if (preg_match("#(\d+)</span> seconds#", $page, $wait)) {
            insert_timer($wait[1]);
        } else {
            insert_timer(60);
        }
        $page = GetPage("http://www.google.com/recaptcha/api/challenge?k=6LdtjrwSAAAAACepq37DE6GDMp1TxvdbW5ui0rdE");
        $ch = cut_str($page, "challenge : '", "'");
        $img = "http://www.google.com/recaptcha/api/image?c=" . $ch;
        $page = GetPage($img);
        $headerend = strpos($page, "\r\n\r\n");
        $pass_img = substr($page, $headerend + 4);
        write_file($download_dir . "bitshare_captcha.jpg", $pass_img);
        $randnum = rand(10000, 100000);
        $img_data = explode("\r\n\r\n", $page);
        $header_img = $img_data[0];
        $data = array();
        $data["request"] = "validateCaptcha";
        $data["ajaxid"] = $ajaxdl;
        $data["recaptcha_challenge_field"] = $ch;
        $data['link'] = urlencode($link);
        $data['step'] = '1';
        $data['cookie'] = urlencode($Cookies);
        $data['urlpost']=urlencode(trim($UrlPost[1]));
        EnterCaptcha($download_dir . "bitshare_captcha.jpg", $data, 20);
        exit;
    }

    function DownloadFree($link) {
        $post = array();
        $ajaxid = $_POST["ajaxid"];
        $post["request"] = "validateCaptcha";
        $post["ajaxid"] = $ajaxid;
        $post["recaptcha_challenge_field"] = $_POST["recaptcha_challenge_field"];
        $post["recaptcha_response_field"] = $_POST['captcha'];
        $Cookies = urldecode($_POST['cookie']);
        $UrlPost=urldecode($_POST['urlpost']);
        $page = GetPage($UrlPost, $Cookies, $post, $link);
        if (!preg_match("#SUCCESS#", $page)) {
            html_error("Wrong captcha");
        }
        unset($post);
        $post["request"] = "getDownloadURL";
        $post["ajaxid"] = $ajaxid;
        $page = GetPage($UrlPost, $Cookies, $post, $link);
        if (!preg_match('#(http://.*)#', $page, $dlink)) {
            html_error("Error 0x10- Plugin is out of date");
        }
        RedirectDownload(trim($dlink[1]), "FileName", $Cookies, 0, $link);
        exit;
    }

    function DownloadPremium($link) {
        global $premium_acc;
        $urllogin = "http://bitshare.com/login.html";
        $post = array();
        $post["user"] = $_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["bitshare_com"] ["user"];
        $post["pass"] = $_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["bitshare_com"] ["pass"];
        $post["rememberlogin"] = "";
        $post["submit"] = "Login";
        $page = GetPage($urllogin, 0, $post, "http://bitshare.com");
        $cookies = GetCookies($page);
        is_notpresent($cookies, "login", "Login Failed , Bad username/password combination");
        $page = GetPage($link, $cookies, 0, "http://bitshare.com");
        is_present($page, "Error - File not available", "Error - File not available");
        if (preg_match("#Location: (.*)#", $page, $temp)) {
        } else {
            if (!preg_match('#http:\/\/.+files-ajax.+\w#', $page,$UrlPost)){
                html_error("Error 1x01: Plugin is out of date");
            }
            if (!preg_match('#ajaxdl = "(.*)"#', $page,$ajaxid)){
                html_error("Error 1x02: Plugin is out of date");
            }
            unset($post);
            $post['request']="generateID";
            $post['ajaxid']=$ajaxid[1];
            $page=GetPage($UrlPost[0], $cookies, $post, $link);
            unset($post);
            $post['request']="getDownloadURL";
            $post['ajaxid']=$ajaxid[1];
            $page=GetPage($UrlPost[0],$cookies,$post,$link);
            if (!preg_match("/(http:\/\/.*\w)/", $page,$temp)){
                html_error("Error 1x03: Plugin is out of date");
            }
        }
        RedirectDownload(trim($temp[1]), "FileName");
        exit();
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

/*
 * by vdhdevil Dec-11-2010
 * rewrite into 36B by Ruud v.Tony
 */
?>