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
        echo('<div id="mesg" width="100%" align="center"><b>' . $htxt['_retrieving'] . '</b></div>');
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
    public function GetPage($link, $cookie = 0, $post = 0, $referer = 0, $auth = 0, $XMLRequest=0) {
        global $pauth;
        if (!$referer) {
            global $Referer;
            $referer = $Referer;
        }
        $Url = parse_url(trim($link));
        $page = geturl($Url ["host"], $Url ["port"] ? $Url ["port"] : 80, $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : ""), $referer, $cookie, $post, 0, $_GET ["proxy"], $pauth, $auth, $Url ["scheme"], 0, $XMLRequest);
        is_page($page);
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
        //if (substr($auth,0,6) != "&auth=") $auth = "&auth=" . $auth;
        if (is_array($cookie)) {
            $cookie = CookiesToStr($cookie);
        }
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
                $addon .= '&' . $name . '=' . urlencode($value) . '&';
            }
            $addon = substr($addon, 0, -1);
        }
        $loc = "{$_SERVER['PHP_SELF']}?filename=" . urlencode($FileName) .
                "&host=" . $Url ["host"] . "&port=" . $Url ["port"] . "&path=" .
                urlencode($Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : "")) .
                "&referer=" . urlencode($referer) . "&email=" . ($_GET ["domail"] ? $_GET ["email"] : "") .
                "&partSize=" . ($_GET ["split"] ? $_GET ["partSize"] : "") . "&method=" . $_GET ["method"] .
                "&proxy=" . ($_GET ["useproxy"] ? $_GET ["proxy"] : "") . "&saveto=" . $_GET ["path"] .
                "&link=" . urlencode($link) . ($_GET ["add_comment"] == "on" ? "&comment=" .
                urlencode($_GET ["comment"]) : "") . ($auth ? '&auth=' . ($auth == 1 ? 1 : urlencode($auth)) : "") . ($pauth ? "&pauth=$pauth" : "") .
                ($_GET ["uploadlater"] ? "&uploadlater=" . $_GET["uploadlater"] . "&uploadtohost=" . $_GET['uploadtohost'] : "") .
                "&cookie=" . ($cookie ? urlencode(encrypt($cookie)) : 0) .
                "&post=" . urlencode(serialize($post)) .
                ($_POST ["uploadlater"] ? "&uploadlater=" . $_POST["uploadlater"] . "&uploadtohost=" . urlencode($_POST['uploadtohost']) : "") .
                ($_POST ['autoclose'] ? "&autoclose=1" : "") .
                (isset($_GET["idx"]) ? "&idx=" . $_GET["idx"] : "") . $addon;

        if ($force_name) {
            $loc = $loc . "&force_name=" . urlencode($force_name);
        }

        insert_location($loc);
    }

    /**
     * Use this function to move your multiples links array to auto downloader
     *
     * @param array $link_array     normal array containing all download links
     */
    public function moveToAutoDownloader($link_array) {
        global $nn;
        if (count($link_array) == 0) {
            html_error('Error getting links from folder.');
        }

        if (!is_file("audl.php")) {
            html_error('audl.php not found');
        }

        $links = "";
        foreach ($link_array as $key => $value) {
            $links .= $value . $nn;
        }

        echo "<form action='audl.php?crot=step2' method='post' >\n";
        echo "<input type='hidden' name='links' value='" . $links . "'>\n";
        $key_array = array("useproxy", "proxy", "proxyuser", "proxypass", "premium_acc", "premium_user", "premium_pass", "cookieuse", "cookie");
        foreach ($key_array as $v)
            echo "<input type='hidden' name='" . $v . "' value='" . $_GET [$v] . "' >\n";
        echo "<script language='JavaScript'>void(document.forms[0].submit());</script>\n";
        echo "</form>\n";
        flush();
        exit();
    }

    public function CountDown($countDown) {
        insert_timer($countDown, "Waiting link timelock");
    }

    public function JSCountdown($secs, $post = 0, $text='Waiting link timelock') {
        global $PHP_SELF;
        echo "<p><center><span id='dl' class='htmlerror'><b>ERROR: Please enable JavaScript. (Countdown)</b></span><br /><span id='dl2'>Please wait</span></center></p>\n";
        echo "<form action='$PHP_SELF' name='cdwait' method='POST'>\n";
        if ($post) {
            foreach ($post as $name => $input) {
                echo "<input type='hidden' name='$name' id='$name' value='$input' />\n";
            }
        } ?>	<script type="text/javascript">
            var c = <?php echo $secs; ?>;var text = "<?php echo $text; ?>";var c2 = 0;var dl = document.getElementById("dl");var a2 = document.getElementById("dl2");fc();fc2();
            function fc() {
                if (c > 0) {
                    if (c > 120) {
                        dl.innerHTML = text+". Please wait <b>"+ Math.round(c/60) +"</b> minutes...";
                    } else {
                        dl.innerHTML = text+". Please wait <b>"+c+"</b> seconds...";
                    }
                    c = c - 1;
                    setTimeout("fc()", 1000);
                } else {
                    dl.style.display="none";
                    void(<?php if ($post) echo 'document.forms.cdwait.submit()';else echo 'location.reload()'; ?>);
                }
            }
            function fc2(){if(c>120){if(c2<=20){a2.innerHTML=a2.innerHTML+".";c2=c2+1}else{c2=10;a2.innerHTML=""}setTimeout("fc2()",100)}else{dl2.style.display="none"}}<?php
        echo "</script></form></body></html>";
        exit;
    }
    
    /**
     * Use this function to create Captcha display form
     *
     * @param string $captchaImg                    The link of the captcha image or downloaded captcha image on server
     * @param array $inputs                             Key Value pairs for html form input elements ( these elements will be hidden form elements )
     * @param string $captchaSize                   The size of captcha text box
     */
    public function EnterCaptcha($captchaImg, $inputs, $captchaSize = '5') {
        echo "\n";
        echo('<form name="dl" action="' . $_SERVER['PHP_SELF'] . '" method="post">');
        echo "\n";

        foreach ($inputs as $name => $input) {
            echo('<input type="hidden" name="' . $name . '" id="' . $name . '" value="' . $input . '" />');
            echo "\n";
        }

        echo('<h4><b>' . $htxt['_enter'] . ' <img src="' . $captchaImg . '" />' . $htxt['_here'] . ': <input type="text" name="captcha" size="' . $captchaSize . '" />&nbsp;&nbsp;');
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

    /**
     * This function will return a array with the Default Key Value pairs including proxy, method, email, etc.
     *
     * @param string $link -> Adds the link value to the array url encoded if you need it.
     * @param string $cookie -> Adds the cookie value to the array url encoded if you need it.
     * @param string $referer -> Adds the referer value to the array url encoded if you need it. If isn't set, it will load $Referer value. (Set as 0 or false for don't add it in the array.)
     */
    public function DefaultParamArr($link = 0, $cookie = 0, $referer = 1) {
        if ($referer == 1) {
            global $Referer;
            $referer = $Referer;
        }

        $DParam = array();
        if ($link) $DParam['link'] = urlencode($link);
        if ($cookie) $DParam['cookie'] = urlencode($cookie);
        if ($referer) $DParam['referer'] = urlencode($referer);
        $DParam["comment"] = $_GET ["comment"];
        $DParam["email"] = $_GET ["email"];
        $DParam["partSize"] = $_GET ["partSize"];
        $DParam["method"] = $_GET ["method"];
        if ($_GET ["useproxy"]) {
            $DParam["useproxy"] = $_GET ["useproxy"];
            $DParam["proxy"] = $_GET ["proxy"];
            $DParam["proxyuser"] = $_GET ["proxyuser"];
            $DParam["proxypass"] = $_GET ["proxypass"];
        }
        $DParam["path"] = $_GET ["path"];
        if (isset($_GET["idx"])) $DParam["idx"] = $_GET["idx"];
        return $DParam;
    }

    public function changeMesg($mesg) {
        echo('<script>document.getElementById(\'mesg\').innerHTML=\'' . stripslashes($mesg) . '\';</script>');
    }

}

/* * ********************************************************
  Added support of force_name in RedirectDownload function by Raj Malhotra on 02 May 2010
  Fixed  EnterCaptcha function ( Re-Write )  by Raj Malhotra on 16 May 2010
  Added auto-encryption system (szal) 14 June 2010
  Added GetPage support function for https connection by Th3-822 21 April 2011
  Added GetPage support function for xml request by vdhdevil 9 July 2011
  Tweaked DefaultParamArr code by Th3-822 22 July 2011
 * ******************************************************** */
?>