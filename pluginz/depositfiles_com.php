<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class depositfiles_com extends DownloadClass {

    public function Download($link) {
	global $premium_acc, $Referer;
	if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
            ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["depositfiles"] ["user"] && $premium_acc ["depositfiles"] ["pass"]))
	{
            $this->DownloadPremium($link);
	} else	{
            $this->DownloadFree($link);
	}
    }

    private function DownloadFree($link) {
        global $Referer;

            $page = $this->GetPage($link);
            if (preg_match("/Location: *(.+)/i", $page, $loc)) {
                $link = "http://depositfiles.com$loc[1]";
                $page = $this->GetPage($link);
            }
            $tcookies = GetCookies($page);
            $cookie = $tcookies;
            is_present($page, "Such file does not exist or it has been removed for infringement of copyrights.");
            is_present($page, "Your IP is already downloading a file from our system.");
            is_present($page, "We are sorry, but all downloading slots for your country are busy.");

            $post = array();
            $post['gateway_result'] = "1";
            $page = $this->GetPage($link, $cookie, $post, $link);
            $cookie = $tcookies . "; " . GetCookies($page);
            if (stristr($page, 'You used up your limit for file downloading!')) {
                preg_match('/([0-9]+) minute\(s\)/', $page, $minutes);
                html_error("Download limit exceeded. Try again in " . trim($minutes [1]) . " minute(s)", 0);
            } else {
                preg_match('/<span id="download_waiter_remain">(.*)<\/span>/', $page, $countDown);
                $countDown = (int) $countDown [1];
                insert_timer($countDown, "The file is being prepared.", "", true);
            }

            if (!preg_match("#/get_file.php[^&|']+#", $page, $temp)) {
                html_error("Error");
            }
            $tlink = "http://depositfiles.com" . $temp[0];
            $cookie = $tcookies . "; " . GetCookies($page);
            $page = $this->GetPage($tlink, $cookie, $post, $link);
            if (preg_match("/Location: (.+)/i", $page, $linkdf)) {
                $link = urldecode($linkdf[1]);
                $link = "http://depositfiles.com$link";
                $page = $this->GetPage($link, $cookie, $post, $link);
                preg_match('/<form action="(.*)" method="get"/U', $page, $dlink);
                $Url = parse_url($dlink[1]);
                $FileName = basename($Url['path']);
                $this->RedirectDownload($dlink[1], $FileName, $cookie);
                exit;
            } else {
                echo "<pre>";
                var_dump(nl2br(htmlentities($page)));
                echo "</pre>";
                html_error("error");
            }
            exit;
    }

    private function DownloadPremium($link) {
	global $premium_acc, $Referer;

            $login = "http://depositfiles.com/en/login.php";
            $post = array ();
            $post['go'] = "1";
            $post['login'] = $_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["depositfiles"] ["user"];
            $post['password'] = $_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["depositfiles"] ["pass"];
            $page = $this->GetPage($login, 0, $post, "http://depositfiles.com/en/");
            $cookie = GetCookies($page);
            is_notpresent ( $cookie, "autologin", "Login Failed , Bad username/password combination" );
            $page = $this->GetPage($link, $cookie, 0, $link);
            if (preg_match("/Location: *(.+)/i", $page, $loc)) {
                $link = "http://depositfiles.com$loc[1]";
                $page = $this->GetPage($link, $cookie, 0, $link);
            }
            is_present ( $page, 'has been removed', "The file has been removed" );
            preg_match ( "/http:\/\/.+auth-[^'\"]+/i", $page, $dlink );
            $Url = parse_url($dlink[0]);
            $FileName = basename($Url['path']);
            $this->RedirectDownload($dlink[0], $FileName, $cookie);
            exit;
    }
}

//Depositfiles Download Plugin by vdhdevil & Ruud v.Tony 19-3-2011

?>