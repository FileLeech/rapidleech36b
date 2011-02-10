<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
    function Download($link) {
        global $premium_acc;
        if (strpos($link, "/dl/")) {
            $link = str_replace("/dl/", "/download/", $link);
        }
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["duckload_com"] ["user"] && $premium_acc ["duckload_com"] ["pass"])) {
            DownloadPremium($link);
        } else if (strpos($link, "/play/")) {
            DownloadFreeVid($link);
        } else {
            DownloadFree($link);
        }
    }

    function DownloadFreeVid($link) {
        $page = GetPage($link);
        is_present($page, "File not found", "File not found");
        $Cookies = GetCookies($page);
        if (!preg_match("#Free Stream \((\d+)\)#", $page, $count)) {
            html_error("Error 0x11: Plugin is out of date");
        }
        insert_timer($count[1]);
        $post = array();
        if (!preg_match('#name="(\w+-\w+-\w+)".+value="(\w+)"#', $page, $temp)) {
            html_error("Error 0x12: Plugin is out of date");
        }
        $post[$temp[1]] = $temp[2];
        if (!preg_match('#<button name="(.*)" t#', $page, $temp)) {
            html_error("Error 0x13: Plugin is out of date");
        }
        $post[$temp[1]] = "";
        $page = GetPage($link, $Cookies, $post, $link);
        if (!preg_match('#http://dl\d+[^"]+#', $page, $dlink)) {
            html_error("Error 0x14: Plugin is out of date");
        }
        $Url = parse_url(trim($dlink[0]));
        $FileName = basename($Url['path']);
        RedirectDownload(trim($dlink[0]), $FileName, $Cookies, 0, $link);
        exit;
    }

    function DownloadFree($link) {
        $page = GetPage($link);
        is_present($page, "File not found", "File not found");
        $Cookies = GetCookies($page);
        if (!preg_match("#Free Download \((\d+)\)#", $page, $count)) {
            html_error("Error 0x01: Plugin is out of date");
        }
        insert_timer($count[1], "Timer 1:");
        $post = array();
        if (!preg_match('#name="(\w+-\w+-\w+)".+value="(\w+)"#', $page, $temp)) {
            html_error("Error 0x02: Plugin is out of date");
        }
        $post[$temp[1]] = $temp[2];
        if (!preg_match('#<button name="(.*)" t#', $page, $temp)) {
            html_error("Error 0x03: Plugin is out of date");
        }
        $post[$temp[1]] = "";
        $page = GetPage($link, $Cookies, $post, $link);
        if (!preg_match("#(\d+)</span> seconds#", $page, $count)) {
            html_error("Error 0x04: Plugin is out of date");
        }
        insert_timer($count[1], "Timer 2:");
        unset($post);
        $post = array();
        if (!preg_match('#name="(\w+-\w+-\w+)".+value="(\w+)"#', $page, $temp)) {
            html_error("Error 0x05: Plugin is out of date");
        }
        $post[$temp[1]] = $temp[2];
        if (!preg_match('#<button name="(.*)" t#', $page, $temp)) {
            html_error("Error 0x06: Plugin is out of date");
        }
        $post[$temp[1]] = "";
        $page = GetPage($link, $Cookies, $post, $link);
        if (!preg_match("#http:\/\/dl\d+.+#", $page, $dlink)) {
            html_error("Error 0x07: Plugin is out of date");
        }
        $Url = parse_url(trim($dlink[0]));
        $FileName = basename($Url['path']);
        RedirectDownload(trim($dlink[0]), $FileName, $Cookies, 0, $link);
        exit;
    }

    function DownloadPremium($link) {
        global $premium_acc;
        $username = $_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["duckload_com"] ["user"];
        $password = $_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["duckload_com"] ["pass"];
        $apiurl = "http://www.duckload.com/api/public/login&user=" . $username . "&pw=" . $password . "&fmt=json&source=TOPNAV";
        $page = GetPage($apiurl);
        is_present($page, "You have entered an incorrect password", "Login Failed , Bad username/password combination");
        $Cookies = GetCookies($page);
        $page = GetPage($link, $Cookies);
        is_present($page, "File not found", "File not found");
        if (!preg_match('#Location: (.+)#', $page, $dlink)) {
            if (!preg_match("#http://.+ddl=1#", $page, $temp)) {
                html_error("Error 1x01: Plugin is out of date");
            }
            $page = GetPage($temp[0], $Cookies, 0, $link);
            if (!preg_match('#Location: (.+)#', $page, $dlink)) {
                html_error("Error 1x02: Plugin is out of date");
            }
        }
        $Url = parse_url(trim($dlink[1]));
        $FileName = basename($Url['path']);
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
 * Created by vdhdevil 30-Dec-2010
 * Updated 10-Feb: fixed premium download problem
 * Rewrite into 36B by Ruud v.Tony
 */
?>
