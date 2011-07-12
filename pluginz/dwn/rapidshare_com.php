<?php

if (!defined('RAPIDLEECH')) {
	require_once ("404.php");
	exit();
}

class rapidshare_com extends DownloadClass {
	public $usecurl, $lastmesg, $apiurl;
	private $fileid, $filename;
	public function Download($link) {
		global $premium_acc, $rs_cookie_enc_value, $htxt;
		$this->apiurl = "https://api.rapidshare.com/cgi-bin/rsapi.cgi";
		// Check for https support.
		$this->usecurl = $cantuse = false;
		if (!extension_loaded('openssl')) {
			if (extension_loaded('curl')) {
				$cV = curl_version();
				if (!in_array('https', $cV['protocols'], true)) $cantuse = true;
				$this->usecurl = true;
			} else $cantuse = true;
			if ($cantuse) html_error("Need OpenSSL enabled or cURL (with SSL) to use this plugin.");
		}

		//Continue free download?
		if ($_REQUEST['rs_freedl'] == 'ok') {
			$this->changeMesg("RS Free Download<br />Starting download...");
			$host = $_REQUEST['rs_host'];
			$fileid = $_REQUEST['rs_fileid'];
			$filename = $_REQUEST['rs_filename'];
			$dlauth = $_REQUEST['rs_dlauth'];
			$this->RedirectDownload("http://" . $host . "/cgi-bin/rsapi.cgi?sub=download&editparentlocation=0&bin=1&fileid=" . $fileid . "&filename=" . urlencode($filename) . "&dlauth=$dlauth", $filename);
			exit;
		}

		$link = str_replace('http://', 'https://', $link);
		$URl = parse_url(trim($link));
		if (preg_match("/!download\|([^\|]+)\|(\d+)\|([^\|]+)/i", $URl["fragment"], $m)) $link = "https://rapidshare.com/files/{$m[2]}/{$m[3]}";
		$page = $this->GetPageS($link);

		is_present($page, "ERROR: Filename invalid.", "Filename invalid. Please check the download link.");
		is_present($page, "ERROR: File ID invalid.", "File ID invalid. Please check the download link.");
		is_present($page, "ERROR: Unassigned file limit of 10 downloads reached.", "Unassigned file limit of 10 downloads reached.");
		is_present($page, "ERROR: Server under repair.", "Server under repair. Please try again later");

		if ($linkb = $this->ReLocation($page, 0)) $page = $this->GetPageS($linkb);
		if (!preg_match("/!download\|([^\|]+)\|(\d+)\|([^\|]+)/i", $page, $m)) html_error("Cannot check link");
		$this->fileid = $m[2];
		$this->filename = $m[3];

		$rserrors = array("This file was not found on our server.",
			"The file was deleted by the owner or the administrators.",
			"The file was deleted due to our inactivity-rule (no downloads).",
			"The file is suspected to be contrary to our terms and conditions and has been locked up for clarification.",
			"The file has been removed from the server due of infringement of the copyright-laws.",
			"The file is corrupted or incomplete.");
		$errors = array("ERROR: File not found." => 0, "ERROR: File physically not found." => 0,
			"ERROR: File deleted R1." => 1, "ERROR: File deleted R2." => 1,
			"ERROR: File deleted R3." => 2, "ERROR: File deleted R5." => 2,
			"ERROR: File deleted R4." => 3, "ERROR: File deleted R8." => 3,
			"ERROR: File deleted R10." => 4, "ERROR: File deleted R11." => 4,
			"ERROR: File deleted R12." => 4, "ERROR: File deleted R13." => 4,
			"ERROR: File deleted R14." => 4, "ERROR: File deleted R15." => 4,
			"ERROR: This file is marked as illegal." => 4, // R10=Game;R11=Movie/Video;R12=Music;R13=Software;R14=Image;R15=Literature
			"ERROR: raid error on server." => 5, "ERROR: File incomplete." => 5);
		foreach ($errors as $err => $errn) {
			is_present($page, $err, $rserrors[$errn]);
		}
		unset($page);

		if ($_REQUEST["rs_acc"] == "on" && (!empty($_GET["rs_cookie"]) || !empty($_GET["rs_hash"]) || !empty($rs_cookie_enc_value))) {
			if (!empty($_GET["rs_cookie"])) {
				$cookie = $_GET["rs_cookie"];
			} elseif (!empty($_GET["rs_hash"])) {
				$cookie = strrev(dcd($_GET["rs_hash"]));
			} else {
				$cookie = $rs_cookie_enc_value;
			}
			$this->lastmesg = $htxt['_retrieving'] . "<br />RS Premium Download [Cookie]";
			$this->changeMesg($this->lastmesg);

			return $this->PremiumCookieDownload($cookie);
		}elseif (!empty($_POST["sssid"]) || ($_REQUEST["premium_acc"] == "on" && (($_GET["maudl"] == 'multi' && !empty($_GET["auth_hash"])) || (!empty($_REQUEST["premium_user"]) && !empty($_REQUEST["premium_pass"])) || (!empty($premium_acc["rs_com"]['user']) && !empty($premium_acc["rs_com"]['pass']))))) {
			$this->lastmesg = $htxt['_retrieving'] . "<br />RS Premium Download";
			$this->changeMesg($this->lastmesg);

			return $this->DownloadPremium();
		} else {
			$this->lastmesg = $htxt['_retrieving'] . "<br />RS Free Download";
			$this->changeMesg($this->lastmesg);

			return $this->DownloadFree($link);
		}
	}
	private function DownloadFree($link) {
		global $Referer;
		$page = $this->GetPageS($this->apiurl."?sub=download&fileid={$this->fileid}&filename={$this->filename}&try=1");

		is_present($page, "ERROR: This file is too big to download it for free.", "This file is too big to download it for free.");
		is_present($page, "ERROR: You need RapidPro to download more files from your IP address.", "Too many parallel downloads from your IP address.");
		is_present($page, "ERROR: Please stop flooding our download servers.", "Flood: Please try again in 5 minutes or later.");
		is_present($page, "ERROR: Too many users downloading", "Too many users downloading right now. Please try again later.");
		is_present($page, "ERROR: All free download slots are full.", "All free download slots are full. Please try again later.");

		if (stristr($page, "ERROR: You need to wait ")) {
			$seconds = trim(cut_str($page, "ERROR: You need to wait ", " seconds until"));
			return $this->JSCountdown($seconds,0,'Download limit exceeded');
		}

		$data = substr(strrchr($page, "\n"), 1);
		$data = explode(":", $data);
		if ($data[0] == "DL") {
			$details = explode(",", $data[1]);
			$host = $details[0];
			$dlauth = $details[1];
			$countdown = $details[2];

			$data = array('rs_host' => urlencode($host), 'rs_fileid' => $this->fileid,
				'rs_filename' => urlencode($this->filename), 'rs_dlauth' => urlencode($dlauth),
				'link' => urlencode($link), 'referer' => urlencode($Referer), 'rs_freedl' => 'ok');
			$this->JSCountdown($countdown, $data);
		} else {
			html_error("Download link not found.");
		}
	}
	private function JSCountdown($secs, $post = 0, $text='Waiting link timelock') {
		global $PHP_SELF;
		echo "<p><center><span id='dl' class='htmlerror'><b>ERROR: Please enable JavaScript. (Countdown)</b></span><br /><span id='dl2'>Please wait</span></center></p>\n";
		echo "<form action='$PHP_SELF' name='cdwait' method='POST'>\n";
		if ($post) {
			foreach ($post as $name => $input) {
				echo "<input type='hidden' name='$name' id='$name' value='$input' />\n";
			}
		}?>	<script type="text/javascript">
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
		function fc2(){if(c>120){if(c2<=20){a2.innerHTML=a2.innerHTML+".";c2=c2+1}else{c2=10;a2.innerHTML=""}setTimeout("fc2()",100)}else{dl2.style.display="none"}}<?php echo "</script></form></body></html>";
		exit;
	}
	private function DownloadPremium() {
		global $premium_acc;

		if (($_GET["maudl"] == 'multi' && !empty($_GET["auth_hash"])) || (!empty($_POST["sssid"]))) {
			$ahash = (($_GET["maudl"] == 'multi' && !empty($_GET["auth_hash"])) ? $_GET["auth_hash"] : $_POST["sssid"]);
			$ahash = explode(":", base64_decode(utf8_strrev(dcd($ahash))));
			if(count($ahash) == 2 && (!empty($ahash[0]) && !empty($ahash[1]))) {
				$_REQUEST["premium_user"] = $ahash[0];
				$_REQUEST["premium_pass"] = $ahash[1];
			}
			unset($ahash);
		}
		if (!empty($_REQUEST["premium_user"]) && !empty($_REQUEST["premium_pass"])) {
			$user = $_REQUEST["premium_user"];
			$pass = $_REQUEST["premium_pass"];
		} else {
			$user = $premium_acc["rs_com"]["user"];
			$pass = $premium_acc["rs_com"]["pass"];
		}

		$cookie = $this->ChkAccInfo('login', $user, $pass);
		$cookie = "enc=$cookie;";

		$page = $this->GetPageS("https://rapidshare.com/files/{$this->fileid}/{$this->filename}", $cookie);
		if (!stristr($page, "Location:")) html_error("Cannot use premium account", 0);

		$Href = $this->ReLocation($page);
		$this->RedirectDownload($Href, $this->filename, $cookie);
	}
	private function PremiumCookieDownload($cookie) {
		$this->ChkAccInfo($cookie);
		$cookie = "enc=$cookie;";

		$page = $this->GetPageS("https://rapidshare.com/files/{$this->fileid}/{$this->filename}", $cookie);
		if (!stristr($page, "Location:")) html_error("Cannot use premium account", 0);

		$Href = $this->ReLocation($page);
		$this->RedirectDownload($Href, $this->filename, $cookie);
	}
	private function ChkAccInfo($cookie, $user='', $pass='') {
		if ($cookie != "login") {
			$page = $this->GetPageS($this->apiurl."?sub=getaccountdetails&cookie=" . $cookie);
			$t1 = 'Cookie';$t2 = 'cookie';
		} elseif (!empty($user) && !empty($pass)) {
			$user = urlencode($user);
			$pass = urlencode($pass);
			$page = $this->GetPageS($this->apiurl."?sub=getaccountdetails&withcookie=1&login=$user&password=$pass");
			$t1 = 'Error';$t2 = 'login details';
		} else html_error("Login failed. User/Password empty.");

		is_present($page, "ERROR: IP blocked.", "[ERROR] Rapidshare has locked your IP. (Too many failed logins sended)");
		is_present($page, "ERROR: Login failed. Login data invalid.",
			"[$t1] Invalid $t2.");
		is_present($page, "ERROR: Login failed. Password incorrect or account not found.",
			"[$t1] Login failed. User/Password incorrect or could not be found.");
		is_present($page, "ERROR: Login failed. Account not validated.",
			"[$t1] Login failed. Account not validated.");
		is_present($page, "ERROR: Login failed. Account locked.",
			"[$t1] Login failed. Account locked.");
		is_present($page, "ERROR: Login failed.",
			"[$t1] Login failed. Invalid $t2?");

		$page = substr($page, strpos($page, "\r\n\r\n") + 4);
		$arr1 = explode("\n", $page);
		$info = array();
		foreach ($arr1 as $key => $val) {
			$arr2 = explode("=", $val);
			foreach ($arr2 as $key2 => $val2) {
				$arr3[] = $val2;
			}
		}
		for ($i = 0; $i <= count($arr3); $i += 2) {
			if (array_key_exists($i, $arr3)) {
				if ($arr3[$i] != "") {
					$info[trim($arr3[$i])] = trim($arr3[$i + 1]);
				}
			}
		}

		if ($info['servertime'] >= $info['billeduntil']) {
			html_error("[$t1] RapidPro has expired or is inactive.");
		} elseif ($info['directstart'] == 0) {
			$dd = $this->GetPageS($this->apiurl."?cookie=$cookie&sub=setaccountdetails&directstart=1");
			if (substr(strrchr($dd, "\n"), 1) != 'OK') {
				html_error("[$t1] Error enabling direct downloads. Please do it manually.");
			}
			$this->changeMesg($this->lastmesg.'<br />Direct downloads has been enabled in your account');
		}
		if ($user) return $info['cookie'];
	}
	private function ReLocation($page, $stop=1) {
		if (!preg_match('@Location: https?://((\w+\.)?rapidshare\.com/[^\r|\n]+)@i', $page, $rloc)) {
			if ($stop) html_error("Redirection not found.");
			else return false;
		}
		return "https://" . $rloc[1];
	}
	private function GetPageS($link, $cookie = 0, $post = 0, $referer = 0, $auth = 0) {
		if (!$referer) {
			global $Referer;
			$referer = $Referer;
		}
		$url = parse_url(trim($link));

		if ($this->usecurl && strstr($this->lastmesg, "RS Premium")) html_error("Need OpenSSL enabled for premium download.");
		if ($this->usecurl) $page = $this->cURL($link, $cookie, $post, $referer, base64_decode($auth));
		else $page = $this->GetPage($link, $cookie, $post, $referer, $auth);
		return $page;
	}
	private function cURL($link, $cookie = 0, $post = 0, $referer = 0, $auth = 0) {
		$opt = array(CURLOPT_HEADER => 1, CURLOPT_COOKIE => $cookie, CURLOPT_REFERER => $referer,
			CURLOPT_SSL_VERIFYPEER => 0, CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.6) Gecko/20050317 Firefox/1.0.2");
		if ($post != '0') {
			$POST = "";
			foreach ($post as $k => $v) {
				$POST .= "$k=$v&";
			}
			$opt[CURLOPT_POST] = 1;
			$opt[CURLOPT_POSTFIELDS] = substr($POST, 0, -1);
		}
		if ($auth) {
			$opt[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
			$opt[CURLOPT_USERPWD] = $auth;
		}
		$ch = curl_init($link);
		foreach ($opt as $O => $V) { // Using this instead of 'curl_setopt_array'
			curl_setopt($ch, $O, $V);
		}
		$page = curl_exec($ch);
		$errz = curl_errno($ch);
		$errz2 = curl_error($ch);
		curl_close($ch);

		if ($errz != 0) html_error("[cURL:$errz] $errz2");
		return $page;
	}
}
// updated by rajmalhotra  on 17 Dec 09 :  added some error messages
// Fixed by rajmalhotra  on 28 Dec 09
//updated 08-jun-2010 for standard auth system (szal)
//[07-OCT-10]  Free download rewritten/fixed by Th3-822
//[30-OCT-10]  Premium download fixed for new links/error msg support & Added 4 error msg to free download. -Th3-822
//[13-NOV-10]  Added error msg for "Account locked" in premium download && Fixed + Added 1 error msg to free download && Fixed regex for get link. -Th3-822
//[13-JAN-11]  & [22-JAN-11]  Added full support for premium cookie & Added function for check RS-API limits &  Minor change in 'ChkAccInfo'. -Th3-822
//[17-MAR-11]  Premium: Add var ($DisSSL) and code for Disable SSL downloads && Changed limit to 1000 & Err Msg in 'Check_Limit'. - Th3-822
//[18-MAR-11]  Premium: Now SSL downloads will be disabled if OpenSSL isn't loaded && Added 5 status msgs with changeMesg() :D - Th3-822
//[19-APR-11]  Plugin checked & fixed for work with new changes at RS && SSL support is needed (It will show error if OpenSSL isn't loaded)... Including for get data in Free download :( . - Th3-822
//[20-APR-11]  FreeDL: Added new functions for use https with cURL if OpenSSL isn't loaded. - Th3-822
//[21-APR-11]  Plugin edited to work in 36B (With cookies & hashes) && Fixed $post in cURL function. (Oops, but isn't used by the plugin)... - Th3-822
//[29-MAY-11]  Premium: Removed support for multi RS logins (Isn't needed now) & changed the login process using 'ChkAccInfo'. Free: Changed countdown, added new function. And plugin checked. - Th3-822
//[10-JUL-11]  Check_Limit() function isn't needed now, removed & Thinking about delete the old fixes info (Too long for read. :D ). - Th3-822

?>