<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
    function Download($link) {
        global $premium_acc;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["oron_com"] ["user"] && $premium_acc ["oron_com"] ["pass"])) {
            DownloadPremium($link);
        } elseif ($_POST['step'] == "1") {
            DownloadFree($link);
        } else {
            Retrieve($link);
        }
    }

    function DownloadFree($link) {
        global $Referer;
        $post['op'] = 'download2';
        $post['method_free'] = 'Free Download';
        $post['method_premium'] = '';
        $post['down_direct'] = '1';
        $post['referer'] = $link;
        $post['recaptcha_challenge_field'] = $_POST['recaptcha_challenge_field'];
        $post['recaptcha_response_field'] = $_POST['captcha'];
        $post['id'] = $_POST['id'];
        $post['rand'] = $_POST['rand'];
        $page = GetPage($link, 0, $post, $link);
        is_present($page, "Wrong captcha", "Wrong captcha . Go to main page and reattempt", 0);
        is_present($page, "Expired session", "Expired session . Go to main page and reattempt", 0);
        $snap = cut_str($page, 'Filename:', '</table>');
        $dwn = cut_str($snap, 'href="', '"');
        if (!$dwn)
            html_error("Error getting download link", 0);
        $Url = parse_url($dwn);
        $FileName = basename($dwn);
        $loc = "$PHP_SELF?filename=" . urlencode($FileName) .
                "&host=" . $Url ["host"] .
                "&port=" . $Url ["port"] .
                "&path=" . urlencode($Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : "")) .
                "&referer=" . urlencode($Referer) .
                "&email=" . ($_GET ["domail"] ? $_GET ["email"] : "") .
                "&partSize=" . ($_GET ["split"] ? $_GET ["partSize"] : "") .
                "&method=" . $_GET ["method"] .
                "&proxy=" . ($_GET ["useproxy"] ? $_GET ["proxy"] : "") .
                "&saveto=" . $_GET ["path"] .
                "&link=" . urlencode($LINK) .
                ($_GET ["add_comment"] == "on" ? "&comment=" . urlencode($_GET ["comment"]) : "") .
                $auth .
                ($pauth ? "&pauth=$pauth" : "");
        insert_location($loc);
    }

    function Retrieve($link) {
        global $Referer, $download_dir;
        $page = GetPage($link);
        is_present($page, "File Not Found", "File Not Found", 0);
        $id = cut_str($page, 'name="id" value="', '"');
        $fname = cut_str($page, 'name="fname" value="', '"');
        $post = array();
        $post['op'] = 'download1';
        $post['usr_login'] = '';
        $post['id'] = $id;
        $post['fname'] = $fname;
        $post['referer'] = '';
        $post['method_free'] = ' Free Download ';
        $page = GetPage($link, 0, $post, $link);
        insert_timer(60);
        $rand = cut_str($page, 'name="rand" value="', '"');
        $referer = cut_str($page, 'referer" value="', '"');
        $down_direct = cut_str($page, 'own_direct" value="', '"');
        $page = GetPage("http://www.google.com/recaptcha/api/challenge?k=6LdzWwYAAAAAAAzlssDhsnar3eAdtMBuV21rqH2N");
        is_present($page, "Expired session", "Expired session . Go to main page and reattempt", 0);
        $cookie = GetCookies($page);
        $ch = cut_str($page, "challenge : '", "'");
        $img = "http://www.google.com/recaptcha/api/image?c=" . $ch;
        $page = GetPage($img);
        $headerend = strpos($page, "\r\n\r\n");
        $pass_img = substr($page, $headerend + 4);
        write_file($download_dir. "oron_captcha.jpg", $pass_img);
        $data = array();
        $data['step'] = 1;
        $data['link'] = urlencode($link);
        $data['id'] = $id;
        $data['rand'] = $rand;
        $data['recaptcha_challenge_field'] = $ch;
        EnterCaptcha($download_dir. "oron_captcha.jpg", $data, 10);
        exit();
    }

    function DownloadPremium($link) {
        global $premium_acc;
        $Referer = "http://oron.com/";
        $loginUrl = "http://oron.com/login.html";
        $post = array();
        $post['login'] = $_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["oron_com"] ["user"];
        $post['password'] = $_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["oron_com"] ["pass"];
        $post['op'] = "login";
        $post['redirect'] = $link;
        $page = GetPage($loginUrl, 0, $post, $Referer);
        $cookie = GetCookies($page);
        is_notpresent($cookie, "login", "Login Failed , Bad username/password combination");
        $page = GetPage($link, $cookie, 0, $Referer);
        is_present($page, "File could not be found due to its possible expiration or removal by the file owner.", "File could not be found due to its possible expiration or removal by the file owner.");
        is_present($page, "You have reached the download limit: 15000 Mb ", "You have reached the download limit: 15000 Mb ");
        $id = cut_str($page, 'name="id" value="', '"');
        $rand = cut_str($page, 'name="rand" value="', '"');
        $referer = cut_str($page, 'referer" value="', '"');
        $down_direct = cut_str($page, 'own_direct" value="', '"');
        $Referer = $link;
        unset($post);
        $post['op'] = 'download2';
        $post['id'] = $id;
        $post['rand'] = $rand;
        $post['referer'] = $Referer;
        $post['method_free'] = '';
        $post['method_premium'] = '1';
        $post['down_direct'] = '1';
        $page = GetPage($link, $cookie, $post, $Referer);
        if (preg_match('#http://(\w+).oron.com[/\w\d.%)( -]+#', $page, $prelink)) {
            $FileName = basename($prelink[0]);
            RedirectDownload($prelink[0], $FileName, $cookie);
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
  WRITTEN BY KAOX 03-oct-09
  UPDATE BY KAOX 06-oct-09 ADD SUPPORT TO CAPTCHA
  UPDATE BY Slider324 17-oct-10 UPDATE SUPPORT TO CAPTCHA
  UPDATE BY vdhdevil  04-Nov-10 UPDATE SUPPORT PREMIUM ACCOUNT
  Rewrite into 36B by Ruud v.Tony
 * \************************************************* */
?>