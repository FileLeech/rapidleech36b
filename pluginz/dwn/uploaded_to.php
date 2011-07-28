<?php
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class uploaded_to extends DownloadClass {

    public function Download($link) {
        global $premium_acc, $options;
        if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) || ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["uploaded_to"] ["user"] && $premium_acc ["uploaded_to"] ["pass"])) {
            $this->DownloadPremium($link);
        } elseif ($_POST['step'] == "1") {
            $this->DownloadFree($link);
        } else {
            $this->Retrieve($link);
        }
    }

    private function Retrieve($link) {
		$page = $this->GetPage($link);

		is_present($page, "uploaded.to/404", "File not found");

		if (!preg_match('/Current waiting period: <span>(\d+)<\/span>/i', $page, $cD)) {
			html_error("Error: Timer not found.");
		}

		insert_timer($cD[1], "Please wait.", "", true);

		if (!preg_match('/Recaptcha\.create\("([^"]+)/i', $this->GetPage('http://uploaded.to/js/download.js'), $rc)) {
			html_error("Error: CAPTCHA not found.");
		}

		$linkcaptcha = "http://www.google.com/recaptcha/api/challenge?k=" . $rc[1];

		$page = $this->GetPage($linkcaptcha, 0, 0, $link);
		$ch = cut_str($page, "challenge : '", "'");

        $data = $this->DefaultParamArr($link, 0, $link);
		$data['step'] = "1";
		$data['recaptcha_challenge_field'] = $ch;

		//Download captcha img.
		$page = $this->GetPage("http://www.google.com/recaptcha/api/image?c=" . $ch, 0, 0, $link);
		$capt_img = substr($page, strpos($page, "\r\n\r\n") + 4);
		$imgfile = $download_dir . "uploaded_to_captcha.jpg";

		if (file_exists($imgfile)) {
			unlink($imgfile);
		}
		if (! write_file($imgfile, $capt_img)) {
			html_error("Error: Can't save CAPTCHA image.");
		}

		$this->EnterCaptcha($imgfile . '?' . time(), $data, 20);
		exit;
	}

    private function DownloadFree($link) {
		$post = array();
		$post['recaptcha_challenge_field'] = $_POST["recaptcha_challenge_field"];
		$post['recaptcha_response_field'] = $_POST["captcha"];

		$page = $this->GetPage(str_replace('/file/', '/io/ticket/captcha/', $link), 0, $post, $link);

		if (preg_match('/\{err:"([^"]+)"\}/i', $page, $err)) {
			$errors = array('captcha' => 'Entered CAPTCHA was incorrect',
				'limit-host' => '"For technical reasons, a download is currently not possible". Try again',
				'limit-dl' => "You've reached your download limit (a file every 60 minutes). Try again later",
				'limit-parallel' => "You're already downloading a file",
				'limit-size' => 'This file is too big (> 1 GB) to download it for free');

			foreach ($errors as $n => $v) {
				is_present($err[1], $n, "Error: $v.");
			}

			html_error("Error validating CAPTCHA.");
		}

		if (!preg_match("@url:'(http://.+/dl/[^']+)@", $page, $dl)) {
			html_error("Error: Download link not found.");
		}

		$this->RedirectDownload($dl[1], 'ul.to_dl');
		exit;
	}

    private function DownloadPremium($link) {
        global $premium_acc;
        $post = array();
        $post["id"] = $_REQUEST["premium_user"] ? trim($_REQUEST["premium_user"]) : $premium_acc ["uploaded_to"] ["user"];
        $post["pw"] = $_REQUEST["premium_pass"] ? trim($_REQUEST["premium_pass"]) : $premium_acc ["uploaded_to"] ["pass"];
        $page = $this->GetPage("http://uploaded.to/io/login", 0, $post, $link);
        $Cookies = GetCookies($page);
        $page = $this->GetPage($link, $Cookies, 0, $link);
		is_present($page, "/404", "File not found");
		is_present($page,"Traffic exhausted","Premium account is out of Bandwidth");
        if (!preg_match('#http://.+/dl?[^\r"]+#', $page, $dlink)) {
            html_error("Error 1x01: Plugin is out of date");
        }
        $this->RedirectDownload(trim($dlink[0]), "uploaded", $Cookies, 0, $link);
        exit;
    }
}

/*
 * free code by Th3-882 14-March-2011
 * premium code by vdhdevil 15-March-2011
 * Updated 01-May-2011
 */
?>
