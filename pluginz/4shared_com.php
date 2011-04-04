<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class d4shared_com extends DownloadClass {
     public function Download($link) {
          global $Referer;
          if (stristr($link, ".com/get/")) {
               $link = str_replace('.com/get/', '.com/file/', $link);
          }
          $page = $this->GetPage($link, "4langcookie=en");
          $cookie = GetCookies($page) . "; 4langcookie=en"; //Keep page in english
          is_present($page, "The file link that you requested is not valid.");
          is_present($page, "The file is suspected of illegal or copyrighted content.");

          if ($_GET["step"] == "1") {
               $post = array();
               $post["userPass2"] = $_POST['userPass2'];
               $post["dsid"] = $_POST['dsid'];
               $cookie = urldecode($_POST['cookie']);
               $page = $this->GetPage($link, $cookie, $post, $link);

               is_present($page, "Please enter a password to access this file", "The password you have entered is not valid.");
               $cookie = "$cookie; " . GetCookies($page);
          } elseif (stristr($page, 'Please enter a password to access this file')) {
               echo "\n" . '<form name="dl_password" action="' . $PHP_SELF . '" method="post" >' . "\n";
               echo '<input type="hidden" name="link" value="' . urlencode($link) . '" />' . "\n";
               echo '<input type="hidden" name="referer" value="' . urlencode($Referer) . '" />' . "\n";
               echo '<input type="hidden" name="cookie" value="' . urlencode($cookie) . '" />' . "\n";
               echo '<input type="hidden" name="step" value="1" />' . "\n";
               echo '<input type="hidden" name="comment" id="comment" value="' . $_GET["comment"] . '" />' . "\n";
               echo '<input type="hidden" name="email" id="email" value="' . $_GET["email"] . '" />' . "\n";
               echo '<input type="hidden" name="partSize" id="partSize" value="' . $_GET["partSize"] . '" />' . "\n";
               echo '<input type="hidden" name="method" id="method" value="' . $_GET["method"] . '" />' . "\n";
               echo '<input type="hidden" name="proxy" id="proxy" value="' . $_GET["proxy"] . '" />' . "\n";
               echo '<input type="hidden" name="proxyuser" id="proxyuser" value="' . $_GET["proxyuser"] . '" />' . "\n";
               echo '<input type="hidden" name="proxypass" id="proxypass" value="' . $_GET["proxypass"] . '" />' . "\n";
               echo '<input type="hidden" name="path" id="path" value="' . $_GET["path"] . '" />' . "\n";
               echo '<input type="hidden" name="dsid" value="' . trim(cut_str($page, 'name="dsid" value="', '"')) . '" />' . "\n";
               echo '<h4>Enter password here: <input type="text" name="userPass2" id="filepass" size="13" />&nbsp;&nbsp;<input type="submit" onclick="return check()" value="Download File" /></h4>' . "\n";
               echo "<script type='text/javascript'>\nfunction check() {\nvar pass=document.getElementById('filepass');\nif (pass.value == '') { window.alert('You didn\'t enter the password'); return false; }\nelse { return true; }\n}\n</script>\n";
               echo "</form>\n</body>\n</html>";
               exit;
          }

          preg_match('/.com\/[^\/]+\/([^\/]+)\/?(.*)/i', $link, $L);
          $page = $this->GetPage("http://www.4shared.com/get/{$L[1]}/{$L[2]}", $cookie);

          if (preg_match('/href=\'(http:\/\/dc[^\']+)\'>Download file now/i', $page, $D)) {
               $cookie = "$cookie; " . GetCookies($page);
               $dllink = $D[1];
          } else {
               html_error("Download-link not found.");
          }

          $this->getCountDown($page);
          $url = parse_url($dllink);
          $FileName = basename($url["path"]);

          $this->RedirectDownload($dllink, $FileName, $cookie, (($_GET["step"] == "1" && !stristr($dllink, "Confirmed=true")) ? $post : 0));
     }

     private function getCountDown($page) {
          if (preg_match('/var c = (\d+)/', $page, $count)) {
               $countDown = $count[1];
               $this->CountDown($countDown);
          }
     }
}

//[21-Nov-2010] Rewritten by Th3-822 & Using some code from the 2shared plugin.
//[26-Jan-2011] Fixed cookies for download pass-protected files. - Th3-822
//[02-Apr-2011] Fixed error when downloading pass-protected files & Added 1 Error Msg. - Th3-822

?>