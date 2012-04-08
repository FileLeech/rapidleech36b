<?php
if (!defined('RAPIDLEECH')) {
	require_once ("index.html");
	exit();
}

class d4shared_com extends DownloadClass {

	private $page, $cookie, $pA;
	public $link;

	public function Download($link) {
		global $premium_acc;
		$this->cookie = array('4langcookie' => 'en');

		if (stristr($link, ".com/get/")) {
			$link = str_replace('.com/get/', '.com/file/', $link);
		}
		$this->link = $link;
		$this->page = $this->GetPage($this->link, $this->cookie);
		$this->cookie = GetCookiesArr($this->page, $this->cookie);
		is_present($this->page, "The file link that you requested is not valid.");
		is_present($this->page, "The file is suspected of illegal or copyrighted content.");

		if ($_REQUEST["premium_acc"] == "on" && ((!empty($_REQUEST["premium_user"]) && !empty($_REQUEST["premium_pass"])) || ($premium_acc["4shared_com"]["user"] && $premium_acc["4shared_com"]["pass"]))) {
			$this->pA = (!empty($_REQUEST["premium_user"]) && !empty($_REQUEST["premium_pass"]) ? true : false);
			return $this->login();
		} else {
			return $this->FreeDownload();
		}
	}

	private function FreeDownload() {
		$this->CheckForPass();

		if (preg_match('@<a [^>]*href="(http://dc\d+\.4shared\.com/download/[^/]+/[^/|"]+)"[^>]*>.+Download Now.+</a>@i', $this->page, $DL)) {
			if (preg_match('/(?:\?|&)dirPwdVerified=(\w+)/i', $this->link, $pwd)) $DL[1] .= "&dirPwdVerified=" . $pwd[1];
			$FileName = urldecode(basename(parse_url($DL[1], PHP_URL_PATH)));
			$this->RedirectDownload($DL[1], $FileName, $this->cookie);
			return;
		}

		if (!preg_match('/.com\/[^\/]+\/([^\/]+)\/?(.*)/i', $this->link, $L)) html_error("Invalid link?");
		$this->page = $this->GetPage("http://www.4shared.com/get/{$L[1]}/{$L[2]}", $this->cookie);

		if (preg_match('/href=\'(http:\/\/dc[^\']+)\'[^>]*>Download file now/i', $this->page, $D)) {
			$this->cookie = GetCookiesArr($this->page, $this->cookie);
			$dllink = $D[1];
			if (preg_match('/(?:\?|&)dirPwdVerified=(\w+)/i', $this->link, $pwd)) $dllink .= "&dirPwdVerified=" . $pwd[1];
		} else {
			is_present($this->page, "?err=not-logged\r\n", "You need to be logged in for download this file.");
			html_error("Download-link not found.");
		}

		$FileName = urldecode(basename(parse_url($dllink, PHP_URL_PATH)));

		if (!preg_match('/var c = (\d+)/', $this->page, $count)) html_error("Timer not found.");

		if ($count[1] <= 120) $this->CountDown($count[1]);
		else {
			$data = $this->DefaultParamArr($dllink, encrypt($this->cookie));
			$data['filename'] = urlencode($FileName);
			$data['host'] = $url["host"];
			$data['port'] = $url["port"];
			$data['path'] = urlencode($url["path"] . ($url["query"] ? "?" . $url["query"] : ""));
			$data['saveto'] = $_GET["path"];
			$this->JSCountdown($count[1], $data);
		}

		$this->RedirectDownload($dllink, $FileName, $this->cookie);
	}

	private function CheckForPass($predl=false) {
		global $PHP_SELF, $L;
		if (isset($_GET["step"]) && $_GET["step"] == "1") {
			$post = array();
			$post["userPass2"] = $_POST['userPass2'];
			$post["dsid"] = trim($_POST['dsid']);
			$this->page = $this->GetPage($this->link, $this->cookie, $post, $this->link);
			is_present($this->page, "Please enter a password to access this file", "The password you have entered is not valid.");
		} elseif (stristr($this->page, 'Please enter a password to access this file')) {
			echo "\n" . '<center><form name="dl_password" action="' . $PHP_SELF . '" method="post" >' . "\n";
			$data = $this->DefaultParamArr($this->link);
			$data['step'] = 1;
			$data['dsid'] = cut_str($this->page, 'name="dsid" value="', '"');
			foreach ($data as $name => $val) echo "<input type='hidden' name='$name' id='$name' value='$val' />\n";
			if ($predl) echo '<br /><input type="checkbox" name="premium_acc" id="premium_acc" onclick="javascript:var displ=this.checked?\'\':\'none\';document.getElementById(\'premiumblock\').style.display=displ;" ' . (!$pA ? 'checked="checked"' : '') . ' />&nbsp;' . $L->say['use_premix'] . '<br /><div id="premiumblock" style="display: none;"><br /><table width="150" border="0"><tr><td>' . $L->say['_uname'] . ':&nbsp;</td><td><input type="text" name="premium_user" id="premium_user" size="15" value="" /></td></tr><tr><td>' . $L->say['_pass'] . ':&nbsp;</td><td><input type="password" name="premium_pass" id="premium_pass" size="15" value="" /></td></tr></table></div><br />';
			echo '<h4>Enter password here: <input type="text" name="userPass2" id="filepass" size="13" />&nbsp;&nbsp;<input type="submit" onclick="return check()" value="Download File" /></h4>' . "\n";
			echo "<script type='text/javascript'>\nfunction check() {\nvar pass=document.getElementById('filepass');\nif (pass.value == '') { window.alert('You didn\'t enter the password'); return false; }\nelse { return true; }\n}\n</script>\n";
			echo "\n</form></center>\n</body>\n</html>";
			exit;
		}
		$this->cookie = GetCookiesArr($this->page, $this->cookie, true, array('', 'deleted', '""'));
	}

	private function PremiumDownload() {
		$this->page = $this->GetPage($this->link, $this->cookie);
		$this->CheckForPass(true);

		if (stripos($this->page, "\r\nContent-Length: 0\r\n") !== false) {
			is_notpresent($this->page, "\r\nLocation:", "Error: Direct link not found.");
			if (!preg_match('@Location: (http://dc\d+.4shared.com/download/[^\r|\n]+)@i', $this->page, $dl)) html_error("Error: Download-link not found 2.");
		} elseif (!preg_match('@type="text" value="(http://dc\d+.4shared.com/download/[^"]+)"@i', $this->page, $dl)) {
			html_error("Error: Download-link not found.");
		}
		$dllink = $dl[1];
		if (preg_match('/(?:\?|&)dirPwdVerified=(\w+)/i', $this->link, $pwd)) $dllink .= "&dirPwdVerified=" . $pwd[1];

		$FileName = urldecode(basename(parse_url($dllink, PHP_URL_PATH)));
		$this->RedirectDownload($dllink, $FileName, $this->cookie);
	}

	private function login() {
		global $premium_acc, $L;
		$email = ($this->pA ? $_REQUEST["premium_user"] : $premium_acc["4shared_com"]["user"]);
		$pass = ($this->pA ? $_REQUEST["premium_pass"] : $premium_acc["4shared_com"]["pass"]);
		if (empty($email) || empty($pass)) html_error("Login Failed: EMail or Password is empty. Please check login data.");

		$postURL = "http://www.4shared.com/login";
		$post["login"] = urlencode($email);
		$post["password"] = urlencode($pass);
		$post["remember"] = "false";
		$post["doNotRedirect"] = "true";
		$page = $this->GetPage($postURL, $this->cookie, $post, $postURL);
		$this->cookie = GetCookiesArr($page, $this->cookie, true, array('', 'deleted', '""'));

		is_present($page, "Invalid e-mail address or password", "Login Failed: Invalid Username/Email or Password.");
		if (stripos($page, '"ok":false') !== false && $err = cut_str($page, '"rejectReason":"', '"')) html_error("Login Failed: 4S says: '$err'.");
		if (empty($this->cookie['Login'])) html_error('Login Failed. Cookie "Login" not found.');
		if (empty($this->cookie['Password'])) html_error('Login Failed. Cookie "Password" not found.');

		// Chk Acc.
		$page = $this->GetPage("http://www.4shared.com/account/home.jsp", $this->cookie);
		$this->cookie = GetCookiesArr($page, $this->cookie, true, array('', 'deleted', '""'));

		$quota = cut_str($page, 'Bandwidth:', "</div>");

		if ($quota === false || !preg_match('/"quota(?:(?:usagebar" title=")|(?:count">))([\d|\.]+)% of ([\d|\.]+) (\w+)/i', $quota, $qm)) {
			$this->changeMesg($L->say['_retrieving'] . "<br /><b>Account isn\\\'t premium?</b><br />Using it as member.");

			$this->page = $this->GetPage($this->link, $this->cookie);
			return $this->FreeDownload();
		}

		$used = floatval($qm[1]);
		$total = floatval($qm[2]);

		// I have to check the BW... I will show it too :)
		$this->changeMesg($L->say['_retrieving'] . "<br />4S Premium Download<br />Bandwidth: $used% of $total {$qm[3]}.");
		if ($used >= 95) html_error("Bandwidth limit trigered: Bandwidth: $used% - Limit: 95%");

		return $this->PremiumDownload();
	}

}

//[21-Nov-2010] Rewritten by Th3-822 & Using some code from the 2shared plugin.
//[26-Jan-2011] Fixed cookies for download pass-protected files. - Th3-822
//[02-Apr-2011] Fixed error when downloading pass-protected files & Added 1 Error Msg. - Th3-822
//[07-May-2011] Some edits to the plugin && Added Premium download support. - Th3-822
//[25-Jul-2011] Using a function for longer link timelock at free download. -Th3-822
//[12-Sep-2011] Fixed regex for get BW usage in Premium && Password in files can be skiped with '?dirPwdVerified=xxxxxxxx' in the url. -Th3-822
//[15-Oct-2011] JSCountdown was added in DownloadClass.php... Removed declaration from plugin. - Th3-822
//[21-Nov-2011] Fixed regexp for get dlink in FreeDL. - Th3-822
//[23-Mar-2012] Added support for member (its needed for some links) & some changes & small fixes. - Th3-822
?>