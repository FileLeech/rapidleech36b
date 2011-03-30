<?php
if (!defined('RAPIDLEECH')) {
	require('../404.php');
	exit;
}
if ((
        $_GET["premium_acc"] == "on" && $_GET["premium_user"] && $_GET["premium_pass"]) || ($_GET["premium_acc"] == "on" && $premium_acc["fileserve_com"]["user"] && $premium_acc["fileserve_com"]["pass"])) {
    $user = ($_GET["premium_user"] ? $_GET["premium_user"] : $premium_acc["fileserve_com"]["user"]);
    $pass = ($_GET["premium_pass"] ? $_GET["premium_pass"] : $premium_acc["fileserve_com"]["pass"]);
    $ref = "http://www.fileserve.com/login.php";
    $Url = parse_url($ref);
    $post['loginUserName'] = $user;
    $post['loginUserPassword'] = $pass;
    $post['autoLogin'] = '';
    $post['loginFormSubmit'] = "Login";
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), $ref, $cookie, $post, 0, $_GET["proxy"], $pauth);
    is_page($page);
    $cookie = GetCookies($page);
    $Url = parse_url($LINK);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), 0, $cookie, 0, 0, $_GET["proxy"], $pauth);
    is_page($page);
    is_present($page, "File not available", "strerror", 0);
    preg_match('/^HTTP\/1\.0|1 ([0-9]+) .*/', $page, $status);
    if ($status[1] == 200) {
        unset($post);
        $post["download"] = "premium";
        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), $LINK, $cookie, $post, 0, $_GET["proxy"], $pauth);
        is_page($page);
    }
    $cookie .= "; " . GetCookies($page);
    $locat = cut_str($page, "Location: ", "\r");
    $Url = parse_url($locat);
    $FileName = basename($locat);
    if (function_exists(encrypt) && $cookie != "") {
        $cookie = encrypt($cookie);
    }
    $loc = "$PHP_SELF?filename=" . urlencode($FileName) . "&force_name=" . urlencode($FileName) . "&host=" . $Url ["host"] . "&port=" . $Url ["port"] . "&path=" . urlencode($Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : "")) . "&referer=" . urlencode($Referer) . "&email=" . ($_GET ["domail"] ? $_GET ["email"] : "") . "&partSize=" . ($_GET ["split"] ? $_GET ["partSize"] : "") . "&method=" . $_GET ["method"] . "&proxy=" . ($_GET ["useproxy"] ? $_GET ["proxy"] : "") . "&saveto=" . $_GET ["path"] . "&link=" . urlencode($LINK) . ($_GET ["add_comment"] == "on" ? "&comment=" . urlencode($_GET ["comment"]) : "") . $auth . ($pauth ? "&pauth=$pauth" : "") . (isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "") . "&cookie=" . urlencode($cookie);
    insert_location($loc);
} else {
    if ($_POST['step'] == "1") {
        @unlink(urldecode($_POST["delete"]));
        $post = unserialize(urldecode($_POST['post']));
        $cookie = urldecode($_POST["cookie"]);
        $recaptcha_shortencode_field = cut_str($LINK, 'file/', '/');
        $recaptcha_shortencode_field = str_replace("/", "", $recaptcha_shortencode_field);
        $post["recaptcha_shortencode_field"] = $recaptcha_shortencode_field;
        $post["recaptcha_response_field"] = urldecode($_POST["captcha"]);
        $Referer = $_POST["link"];
        $Url = parse_url("http://www.fileserve.com/checkReCaptcha.php");
        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), "http://www.fileserve.com/landing/DL13/download_captcha.js", $cookie, $post, 0, $_GET["proxy"], $pauth);
        is_page($page);
        if (strpos($page, "success") !== false) {

        } else {
            html_error("Wrong code.", 0);
        }
        $Url = parse_url("http://www.fileserve.com/file/" . $recaptcha_shortencode_field);
        unset($post);
        $post["downloadLink"] = "wait";
        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), $LINK, $cookie, $post, 0, $_GET["proxy"], $pauth);
        is_page($page);
        insert_timer(40);
        unset($post);
        $post["downloadLink"] = "show";
        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), $LINK, $cookie, $post, 0, $_GET["proxy"], $pauth);
        is_page($page);
        unset($post);
        $post["download"] = "normal";
        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), $LINK, $cookie, $post, 0, $_GET["proxy"], $pauth);
        is_page($page);
        if (stristr($page, "You need to wait") != false or stristr($page, "Bitte warten") != false) {
            $wait_delay = cut_str($page, "You need to wait ", " seconds to start another download");
            insert_timer($wait_delay);
        }
        if (stristr($page, "has expired") != false) {
            html_error("Download Link expired");
        }
        $locat = cut_str($page, "Location: ", "\r");
        if (!$locat) {
            html_error("Download link not found ", 0);
        }
        $Url = parse_url($locat);
        $FileName = basename($Url["path"]);
        if (function_exists(encrypt) && $cookie != "") {
            $cookie = encrypt($cookie);
        }
        $loc = "$PHP_SELF?filename=" . urlencode($FileName) . "&force_name=" . urlencode($FileName) . "&host=" . $Url ["host"] . "&port=" . $Url ["port"] . "&path=" . urlencode($Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : "")) . "&referer=" . urlencode($Referer) . "&email=" . ($_GET ["domail"] ? $_GET ["email"] : "") . "&partSize=" . ($_GET ["split"] ? $_GET ["partSize"] : "") . "&method=" . $_GET ["method"] . "&proxy=" . ($_GET ["useproxy"] ? $_GET ["proxy"] : "") . "&saveto=" . $_GET ["path"] . "&link=" . urlencode($LINK) . ($_GET ["add_comment"] == "on" ? "&comment=" . urlencode($_GET ["comment"]) : "") . $auth . ($pauth ? "&pauth=$pauth" : "") . (isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "") . "&cookie=" . urlencode($cookie);
        insert_location($loc);
    } else {
        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"], $pauth);
        is_page($page);
        $cookie = GetCookies($page);
        $Url = parse_url("http://www.google.com/recaptcha/api/challenge?k=6LdSvrkSAAAAAOIwNj-IY-Q-p90hQrLinRIpZBPi");
        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"], $pauth);
        is_page($page);
        $cookieApi = GetCookies($page);
        $ch = cut_str($page, "challenge : '", "'");
        if ($ch) {
            $Url = parse_url("http://www.google.com/recaptcha/api/image?c=" . $ch);
            $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"] . ($Url["query"] ? "?" . $Url["query"] : ""), 0, $cookieApi, 0, 0, $_GET["proxy"], $pauth);
            is_page($page);
            $headerend = strpos($page, "\r\n\r\n");
            $pass_img = substr($page, $headerend + 4);
            $imgfile = $download_dir . "fileserve_captcha.jpg";
            if (file_exists($imgfile)) {
                unlink($imgfile);
            }
            write_file($imgfile, $pass_img);
        } else {
            html_error("Error get captcha", 0);
        }
        $post['recaptcha_challenge_field'] = $ch;
        print "<form method=\"post\" action=\"" . $PHP_SELF . (isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "") . "\">$nn";
        print "<h4>Enter <img src=\"$imgfile\"> here:</h4><input name=\"captcha\" type=\"text\" >$nn";
        print "<input name=\"link\" value=\"$Referer\" type=\"hidden\">$nn";
        print '<input type="hidden" name="post" value="' . urlencode(serialize($post)) . '">' . $nn;
        print "<input name=\"step\" value=\"1\" type=\"hidden\">$nn";
        print "<input type=\"hidden\" name=\"cookie\"  value=\"" . urlencode($cookie) . "\" >" . $nn;
        print "<input type=\"hidden\" name=\"delete\"  value=\"" . urlencode($imgfile) . "\" >" . $nn;
        print "<input name=\"Submit\" value=\"Submit\" type=\"submit\"></form>";
    }
}

/* * ***********************************
 * Written by kaox    21-jun-2010     *
 * Updated by Jueki   19-August-2010  *
 * Updated by Jueki   06-October-2010 *
 * Fixed free download by vdhdevil    *
 * *********************************** */
?>
