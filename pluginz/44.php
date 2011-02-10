<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
    function Download($link) {
        global $premium_acc;
        if (($_REQUEST["premium_acc"] == "on" && $_REQUEST["premium_user"] && $_REQUEST["premium_pass"]) || ($_REQUEST["premium_acc"] == "on" && $premium_acc["filedude_com"]["user"] && $premium_acc["filedude_com"]["pass"])) {
            DownloadPremium($link);
        } elseif ($_POST['step'] == 1) {
            return DownloadFree($link);
        } else {
            return Prepare($link);
        }
    }

    function Prepare($link) {
        $page = GetPage($link);
        is_present($page, "The file you've requested doesn't exist ", "The file you've requested doesn't exist ");
        if (!preg_match('#(http://www.filedude.com/captcha/.*)"#', $page, $temp)) {
            html_error("Error: Image not found");
        }
        $fraction = cut_str($page, '<form action="', '"');
        $session = cut_str($page, 'name="session" value="', '" ');
        $data['step'] = '1';
        $data['session'] = $session;
        $data['link'] = urlencode($link);
        $data['referer'] = urlencode($link);
        $data['fraction'] = urlencode("http://www.filedude.com" . $fraction);
        EnterCaptcha($temp[1], $data);
        echo $code;
    }

    function DownloadFree($link) {
        if (empty($_POST['captcha'])) {
            html_error("You didn't enter the image verification code.");
        }
        $post = array();
        $post['session'] = $_POST['session'];
        $post['captcha'] = $_POST['captcha'];
        $Referer = $_POST['referer'];
        $dlink = urldecode($_POST['fraction']);
        $page = GetPage($dlink, 0, $post, $Referer);
        if (!preg_match('#(http://.*/getN/.*)">#', $page, $temp)) {
            html_error("Error: Download link not found");
        }
        $Url = parse_url($temp[1]);
        $FileName = basename($Url["path"]);
        RedirectDownload($temp[1], $FileName);
        exit;
    }
    function DownloadPremium($link){
        global $premium_acc;
        $post=array();
        $post['user']=$_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["filedude_com"] ["user"];
        $post['pass']=$_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["filedude_com"] ["pass"];
        $page=GetPage("http://www.filedude.com/premium_login", 0, $post, $link);
        $Cookies=GetCookies($page);
        $page=GetPage($link, $Cookies, 0, $link);
        is_present($page, "The file you've requested doesn't exist ", "The file you've requested doesn't exist ");
        if (!preg_match("#Location: (.+)#", $page,$dlink)){
            html_error("Error 1x01: Plugin is out of date");
        }
        $Url=parse_url(trim($dlink[1]));
        $FileName=basename($Url['path']);
        RedirectDownload(trim($dlink[1]), $FileName, $Cookies, 0, $link);
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

//by VDHDEVIL 09 Jan 2011
//Rewrite into 36B 09 Jan 2011


?>