<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
    function Download($link) {
        global $premium_acc;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["enterupload_com"] ["user"] && $premium_acc ["enterupload_com"] ["pass"])) {
            DownloadPremium($link);
        } else {
            DownloadFree($link);
        }
    }
    function DownloadPremium($link){
        //using cookies
        $post=array();
        $post['op']="login";
        $post['redirect']=$link;
        $post['login']="dvdriprl";
        $post['password']="123456";
        $post['x']=rand(0,21);
        $post['y']=rand(0,6);
        $page=GetPage("http://www.enterupload.com/login.html", 0, $post, $link);
        $Cookies=GetCookies($page);
        $count=null;
        $Cookies=preg_replace("#xfss=.*#", "xfss=xxxxxxxx", $Cookies,-1,$count);
        $page=GetPage($link,$Cookies,0,$link);
        if (!preg_match('#Location: (.*)#', $page, $dlink)){
            html_error("Error 1:Plugin is out of date");
        }
        $Url=parse_url(trim($dlink[1]));
        $FileName=basename($Url['path']);
        RedirectDownload(trim($dlink[1]), $FileName, $Cookies, 0, $link, $FileName);
        exit;
    }
    function DownloadFree($link){
        $post=array();
        $post['op']="login";
        $post['redirect']=$link;
        $post['login']="dvdriprl";
        $post['password']="123456";
        $post['x']=rand(0,21);
        $post['y']=rand(0,6);
        $page=GetPage("http://www.enterupload.com/login.html", 0, $post, $link);
        $Cookies=GetCookies($page);
        $page=GetPage($link,$Cookies,0,$link);
        is_present($page, "File Not Found","File Not Found");
        $id=cut_str($page,'name="id" value="','"');
        $FileName=cut_str($page,'name="fname" value="','"');
        unset($post);
        $post['op']="download1";
        $post['usr_login']="";
        $post['id']=$id;
        $post['fname']=$FileName;
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $page=GetPage($link,$Cookies,$post,$link);
        is_present($page,"You can download files up to 400 Mb only","You can download files up to 400 Mb only");
        $rand=cut_str($page, 'name="rand" value="', '"');
        if (preg_match("#You have to wait (\d+) minutes, (\d+) seconds till next download#",$page,$message)){
            html_error($message);
            //insert_timer($message[1]*60+$message[2]);
        }
        if (preg_match("#(\d+)</span> seconds#",$page,$wait)){
            insert_timer($wait[1]);
        } else {
            insert_timer(40);
        }
        unset($post);
        $post['op']="download2";
        $post['id']=$id;
        $post['rand']=$rand;
        $post['referer']=$link;
        $post['method_free']="Free Download";
        $post['method_premium']="";
        $post['down_direct']="1";
        $page=GetPage($link,$Cookies,$post,$link);
        if (!preg_match('#(http://.*:8080.*)" #', $page, $dlink)){
            html_error("Error 1:Plugin is out of date");
        }
        RedirectDownload(trim($dlink[1]), $FileName, $Cookies, 0, $link, $FileName);
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

/*
 * by vdhdevil 24-DEC-2010
 */

?>