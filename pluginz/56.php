<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
    function Download($link) {
        global $premium_acc;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["movreel_com"] ["user"] && $premium_acc ["movreel_com"] ["pass"])) {
            DownloadPremium($link);
        } else {
            DownloadFree($link);
        }
    }
    function DownloadPremium($link){
        html_error("Not support now");
    }
    function DownloadFree($link){
        $Urllogin="http://movreel.com/login.html";
        $post=array();
        $post['op']="login";
        $post['redirect']=$link;
        $post['login']="dvdriprl";
        $post['password']="123456";
        $post['x']="33";
        $post['y']="14";
        $page=GetPage($Urllogin,0,$post,$link);
        $Cookies=GetCookies($page);
        $page=GetPage($link, $Cookies, 0, $link);
        $id=cut_str($page, 'name="id" value="', '"');
        $FileName=cut_str($page, 'name="fname" value="', '"');
        unset($post);
        $post['op']="download1";
        $post['usr_login']="";
        $post['id']=$id;
        $post['fname']=$FileName;
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $page=GetPage($link,$Cookies,$post,$link);
        $rand=cut_str($page, 'name="rand" value="', '"');
        unset($post);
        $post['op']="download2";
        $post['id']=$id;
        $post['rand']=$rand;
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $post['method_premium']="";
        $post['down_direct']="1";
        $post['down_direct']="1";
        $post['x']=rand(0,70);
        $post['y']=rand(0,19);
        $page=GetPage($link, $Cookies, $post, $link);
        if (!preg_match('#(http://.+:182.+)">#', $page,$dlink)){
            html_error("Error 0x01: Plugin is out of date");
        }
        RedirectDownload(trim($dlink[1]), $FileName, $Cookies, 0, $link);
        exit;
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
