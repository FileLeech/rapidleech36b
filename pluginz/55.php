<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
    function Download($link){
        if ($_POST['step'] == "1") {
            DownloadFree($link);
        } else {
            Retrieve($link);
        }
    }
    function Retrieve($link){
        $page=GetPage($link);
        $Cookies="lang=english";
        $id=cut_str($page, 'name="id" value="', '"');
        $FileName=cut_str($page, 'name="fname" value="', '"');
        $post=array();
        $post['op']="download1";
        $post['usr_login']="";
        $post['id']=$id;
        $post['fname']=$FileName;
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $page=GetPage($link, $Cookies, $post, $link);
        is_present($page, "The file You requested  reached max downloads limit for Free Users","The file You requested reached max downloads limit for Free Users");
        if (preg_match("#You have to wait (\d+) minute, (\d+) seconds till next download#", $page,$message)){
            html_error($message);
            //insert_timer($message[1]*60+$message(2));
        }
        if (!preg_match("#(\d+)</span> seconds#", $page,$wait)){
            html_error("Error 0x01: Plugin is out of date");
        }
        $rand=cut_str($page, 'name="rand" value="', '"');
        $page = GetPage("http://www.google.com/recaptcha/api/challenge?k=6LfsBboSAAAAAPgMPrGX2sPmOZNw9lkRcitBg4Z2+");
        $ch = cut_str($page, "challenge : '", "'");
        $img = "http://www.google.com/recaptcha/api/image?c=" . $ch;
        insert_timer($wait[1]);
        $data=array();
        $data['link']=urlencode($link);
        $data['step']="1";
        $data['id']=$id;
        $data['rand']=$rand;
        $data['FileName']=$FileName;
        $data['recaptcha_challenge_field']=$ch;
        EnterCaptcha($img, $data, 18);
        exit();
    }
    function DownloadFree($link){
        $post=array();
        $post['op']="download2";
        $post['id']=$_POST['id'];
        $post['rand']=$_POST['rand'];
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $post['method_premium']="";
        $post['recaptcha_challenge_field']=$_POST['recaptcha_challenge_field'];
        $post['recaptcha_response_field']=$_POST['captcha'];
        $post['down_direct']="1";
        $FileName=$_POST['FileName'];
        $page=GetPage($link, 0, $post, $link);
        if (!preg_match('#(http://.+:182.*)">#', $page, $dlink)){
            html_error("Error 0x02 :Plugin is out date");
        }
        RedirectDownload($dlink[1], $FileName, 0, 0, $link);
        //echo "<pre>";var_dump(nl2br(htmlentities($page)));echo "</pre>";exit;
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

//by VDHDEVIL
//Rewrite into 36B by Ruud v.Tony
?>
