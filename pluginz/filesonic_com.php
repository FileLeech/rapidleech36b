<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class filesonic_com extends DownloadClass {

	public function Download($link) {
            global $premium_acc, $Referer;
            if(preg_match('/http:\/\/.+filesonic\.com\/folder\/[^\'"]+/i', $link, $match)){
                if(!$match[0]){html_error ( 'Link not found' );}
                $page = $this->GetPage($link,0,0,0);
                preg_match_all('/<td><a href="(.*)">/i', $page, $matches);
                if (! is_file ( "audl.php" )) html_error ( 'audl.php not found' );
		echo "<form action=\"audl.php?crot=step2\" method=post>\n";
		echo "<input type=hidden name=links value='" . implode ( "\r\n", $matches [1] ) . "'>\n";
		foreach ( array ( "useproxy", "proxy", "proxyuser", "proxypass" ) as $v )
		echo "<input type=hidden name=$v value=" . $_GET [$v] . ">\n";
		echo "<script language=\"JavaScript\">void(document.forms[0].submit());</script>\n</form>\n";
		flush ();
		exit ();
            }
            if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["filesonic_com"] ["user"] && $premium_acc ["filesonic_com"] ["pass"])) {
                $this->DownloadPremium($link);
            } elseif ( $_POST['pass_pre'] == "ok") {
                    $post["passwd"]= $_POST['passwd'];
                    $link = $_POST['link'];
                    $cookie = $_POST['cookie'];
                    $page = $this->GetPage($link, $cookie, $post, $Referer );
                    if (stristr ( $page, "Location:" )) {
                        $Href = trim ( cut_str ( $page, "Location: ", "\n" ) );
                        $FileName = $_POST["name"];
                        $this->RedirectDownload($Href,$FileName,$cookie);
                        exit();
                    }
                    $this->DownloadPremium($link);
            } elseif ( $_POST['pass_free'] == "ok") {
                    $post["passwd"] = $_POST['passwd'];
                    $link = $_POST['link'];
                    $cookie = $_POST['cookie'];
                    $page = $this->GetPage($link, $cookie, $post, $Referer );
                    $this->PrepareFree($link);
            } elseif ($_POST['step'] == "1") {
                $this->DownloadFree($link);
            } else {
                $this->PrepareFree($link);
            }
        }

        private function PrepareFree($link) {
            global $Referer, $download_dir;

            $page = $this->GetPage($link);
            is_present($page, "deleted", "This file was deleted");
            if (stristr ($page, "Location:" )) {
                $link = trim(cut_str($page, "Location:","\n"));
                $page = $this->GetPage($link);
            }
            $cookie = GetCookies($page);

            preg_match('%<a href="(.*)" id="free_download">%', $page, $match);
            $flink = 'http://www.filesonic.com/file/'.$match[1];
            $page = $this->GetPage($flink, $cookie, 0, $Referer);
            if(stristr ( $page , 'Download Error' )){
                html_error("Free users may only download 1 file at a time.");
            }
            if (stristr ( $page, "Please wait" )) {
                preg_match('/var countDownDelay = ([0-9]+);/', $page, $wait);
                $this->CountDown($wait[1]);
            }
            $tm = cut_str($page, "name='tm' value='","'");
            $tm_hash = cut_str($page, "name='tm_hash' value='","'");
            $post = array();
            $post['tm'] = $tm;
            $post['tm_hash'] = $tm_hash;
            $page = $this->GetPage($flink, 0, $post, $Referer);
            if (stristr ( $page, "Please Enter Password" )) {
                preg_match('%<form enctype="application/x-www-form-urlencoded" action="(.*)" method="post">%', $page, $match);
                $linkpw = "http://www.filesonic.com".$match[1];
                echo "\n" . '<form name="dl_password" action="' . $PHP_SELF . '" method="post" >' . "\n";
                echo '<input type="hidden" name="link" value="' . $linkpw . '" />' . "\n";
                echo '<input type="hidden" name="cookie" value="' . $cookie . '" />' . "\n";
                echo '<input type="hidden" name="pass_free" value="ok" />' . "\n";
                echo '<h4>Enter password here: <input type="text" name="passwd" id="filepass" size="13" />&nbsp;&nbsp;<input type="submit" onclick="return check()" value="Download File" /></h4>' . "\n";
                echo "<script language='JavaScript'>\nfunction check() {\nvar pass=document.getElementById('filepass');\nif (pass.value == '') { window.alert('You didn\'t enter the password'); return false; }\nelse { return true; }\n}\n</script>\n";
                echo "</form>\n</body>\n</html>";
                exit;
            }
            if (stristr ( $page, "Please Enter Captcha:" )) {
                if (preg_match('/Recaptcha\.create\("([^"]+)/i', $page, $k)) {
                    $k = $k[1];
                    $cachestop = rand();
                } else {
                    html_error("Error getting CAPTCHA data.", 0);
                }
                $page = $this->GetPage("http://www.google.com/recaptcha/api/challenge?k=$k&cachestop=".$cachestop."&ajax=1");
                $cookieapi = GetCookies($page);
                $ch = cut_str($page, "challenge : '", "'");
                if ($ch) {
                    $img = "http://www.google.com/recaptcha/api/image?c=".$ch;
                    $page = $this->GetPage($img, $cookieapi);
                    $capt_img = substr($page, strpos($page, "\r\n\r\n") + 4);
                    $imgfile = $download_dir . "filesonic_captcha.jpg";
                    if (file_exists($imgfile)) {
                        unlink($imgfile);
                    }
                    write_file($imgfile, $capt_img);
                } else {
                    html_error("Error getting CAPTCHA image.", 0);
                }

                $data = array();
                $data['step'] = '1';
                $data['link'] = $flink;
                $data['cookie'] = $cookie;
                $data['recaptcha_challenge_field'] = $ch;
                $data['delete'] = urlencode($imgfile);
                $this->EnterCaptcha($imgfile, $data, 20);
                exit();
            }
        }           

        private function DownloadFree($link) {
            global $Referer;
            @unlink(urldecode($_POST["delete"]));
            $post = array();
            $post['recaptcha_challenge_field'] = $_POST["recaptcha_challenge_field"];
            $post['recaptcha_response_field'] = $_POST["captcha"];
            $link = $_POST["link"];
            $cookie = $_POST["cookie"];
            $page = $this->GetPage($link, $cookie, $post, $Referer);
            if (!preg_match('/http:\/\/.+filesonic\.com\/download\/[^\'"]+/i', $page, $dl)) {
                html_error("Error: Plugin out of date!");
            }
            $dlink = trim($dl[0]);
            $Url = parse_url($dlink);
            $FileName = basename($Url['path']);
            $this->RedirectDownload($dlink, $FileName, $cookie);
            exit ();
        }

        private function DownloadPremium($link) {
            global $premium_acc, $Referer;

            $page = $this->GetPage( $link);
            is_present($page, "deleted", "This file was deleted");
            if (stristr ( $page, "Location:" )) {
                $link = trim(cut_str($page, "Location:","\n"));
            }
            $page = $this->GetPage( $link, 0, 0, $Referer);

            preg_match ( '%<input type="text" value="(.*)" name="URL_%', $page, $fname );
            $linkcheck = $fname[1];
            $filename = basename ($linkcheck);
            $page = $this->GetPage("http://www.filesonic.com");
            $cookie = GetCookies($page);

            $post = array ();
            $postlog['rememberMe'] = '1';
            $post ["email"] = $_GET ["premium_user"] ? $_GET ["premium_user"] : $premium_acc ["filesonic_com"] ["user"];
            $post ["password"] = $_GET ["premium_pass"] ? $_GET ["premium_pass"] : $premium_acc ["filesonic_com"] ["pass"];
            $page = $this->GetPage('http://www.filesonic.com/user/login', $cookie, $post, "http://www.filesonic.com/");
            $cookie = GetCookies($page);
            is_present($cookie, 'role=free', 'Account Free - Login not validated');
            is_notpresent($cookie, 'nickname=', 'Error logging in - Account not found!');
            $page = $this->GetPage($link,$cookie,0,$Referer);
            if (stristr ( $page, "Please Enter Password" )) {
                preg_match('%<form enctype="application/x-www-form-urlencoded" action="(.*)" method="post">%', $page, $match);
                $linkpw = "http://www.filesonic.com".$match[1];
                echo "\n" . '<form name="dl_password" action="' . $PHP_SELF . '" method="post" >' . "\n";
                echo '<input type="hidden" name="link" value="' . $linkpw . '" />' . "\n";
                echo '<input type="hidden" name="cookie" value="' . $cookie . '" />' . "\n";
                echo '<input type="hidden" name="name" value="' . $filename . '" />' . "\n";
                echo '<input type="hidden" name="pass_pre" value="ok" />' . "\n";
                echo '<h4>Enter password here: <input type="text" name="passwd" id="filepass" size="13" />&nbsp;&nbsp;<input type="submit" onclick="return check()" value="Download File" /></h4>' . "\n";
                echo "<script language='JavaScript'>\nfunction check() {\nvar pass=document.getElementById('filepass');\nif (pass.value == '') { window.alert('You didn\'t enter the password'); return false; }\nelse { return true; }\n}\n</script>\n";
                echo "</form>\n</body>\n</html>";
                exit;
            }
            if (!stristr ( $page, "Location:" )) {
                html_error ( "Download link not found", 0 );
            }
            $Href = trim ( cut_str ( $page, "Location: ", "\n" ) );
            $FileName = $filename;
            $this->RedirectDownload($Href,$FileName,$cookie);
        }

}
// Written by VinhNhaTrang 21.10.2010
// fix by VinhNhaTrang 21.11.2010
// rewrite & fixed by Ruud v.Tony 29-04-2011, special credit to Th3-882 & vdhdevil, thx mate for teach me
?>