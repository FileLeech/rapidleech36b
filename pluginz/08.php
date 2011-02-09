<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
	function Download($link) {
		global $premium_acc;
		
                if ( ($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
                     ($_REQUEST ["premium_acc"] == "on" && $premium_acc ["4shared_com"] ["user"] && $premium_acc ["4shared_com"] ["pass"] ) )        
		{
			DownloadPremium($link);
		}else if( $_POST['pass_pre'] == "ok") {
                global $Referer;
                $link= urldecode($_POST['link']);
                $cookie = urldecode($_POST['cookie']);
                $post["userPass2"]=$_POST['password_pre'];
                $post["dsid"]=$_POST['dsid'];
                $page = GetPage($link, $cookie, $post, $Referer );
                if (stristr ( $page, "Please enter a password to access this file:" ))
                {
                echo  ("<center><font color=red><b>Wrong password .Please re-enter</b></font></center>");
                DownloadPremium($link);exit;
                } 
                $cookie = $cookie."; ".GetCookies($page); 
                if (stristr ( $page, "Location:" )) {
                $linkdl2 =cut_str ($page ,"Location: ","\r");
                $page = GetPage($linkdl2, $cookie, 0, $Referer );
                preg_match('%<FORM name="theForm" method="post" action="(.*)" >%', $page, $match);
                $linkpw2 = $match[1]; 
                $dsid = cut_str($page, 'name="dsid" value="','"');
                $post["dsid"]=$dsid;
                $Url=parse_url($linkpw2);
                $FileName=basename($Url["path"]); 
                RedirectDownload($linkpw2,$FileName,$cookie,$post,$Referer);exit;
                }
                DownloadPremium($link);
    }else{
      DownloadFree($link);
                }               	
	}
	function DownloadFree($link) {
          global $Referer;
          if (stristr($link, ".com/get/")) {
               $link = str_replace('.com/get/', '.com/file/', $link);
          }
          $page = GetPage($link, "4langcookie=en");
          $cookie = GetCookies($page) . "; 4langcookie=en"; //Keep page in english
          is_present($page, "The file link that you requested is not valid.", "The file link that you requested is not valid.");

          if ($_GET["step"] == "1") {
               $post = array();
               $post["userPass2"] = $_POST['userPass2'];
               $post["dsid"] = $_POST['dsid'];
               $cookie = urldecode($_POST['cookie']);
               $page = GetPage($link, $cookie, $post, $link);
               is_present($page, "Please enter a password to access this file", "The password you have entered is not valid.");
               $cookie = GetCookies($page) . "; 4langcookie=en";
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
               echo "<script language='JavaScript'>\nfunction check() {\nvar pass=document.getElementById('filepass');\nif (pass.value == '') { window.alert('You didn\'t enter the password'); return false; }\nelse { return true; }\n}\n</script>\n";
               echo "</form>\n</body>\n</html>";
               exit;
          }

		  preg_match('/.com\/[^\/]+\/([^\/]+)\/?(.*)/i', $link, $L);
          $page = GetPage("http://www.4shared.com/get/{$L[1]}/{$L[2]}", $cookie);

          if (preg_match('/href=\'(http:\/\/dc[^\']+)\'>Download file now/', $page, $D)) {
               $dllink = $D[1];
          } else {
               html_error("Download-link not found.");
          }

          getCountDown($page);         
          $url = parse_url($dllink);
          $FileName = basename($url["path"]);

          RedirectDownload($dllink, $FileName);
     }

	function DownloadPremium($link) {
		global $Referer, $premium_acc;
                
                $cookie = "4langcookie=en";
                $page = GetPage($link,$cookie);
                is_page ( $page );
                is_present ( $page, "The file link that you requested is not valid." );
                $cookie1 = $cookie."; ".GetCookies($page);   

                $post = array ();
                $postlog['remember'] = 'false';
                $postlog['doNotRedirect'] = '1';
                $post ["login"] = $_GET ["premium_user"] ? $_GET ["premium_user"] : $premium_acc ["4shared_com"] ["user"];
                $post ["password"] = $_GET ["premium_pass"] ? $_GET ["premium_pass"] : $premium_acc ["4shared_com"] ["pass"];
                $page = GetPage('http://www.4shared.com/login',$cookie1,$post,0);
                is_page($page);	
                $cookie2 = $cookie."; ".GetCookies($page); 
                if( strpos( $cookie2,"Password=")!== false ){
                }else{ html_error(" Login Failed . Please check your login details. ",0);}   
                $page = GetPage($link,$cookie2,0,$Referer);
                $cookie3 = $cookie."; ".GetCookies($page); 
                if (stristr ( $page, "Please enter a password to access this file:" ))
                {
                preg_match('%<FORM name="theForm" method="post" action="(.*)" >%', $page, $match);
                $linkpw = $match[1]; 
                $dsid = cut_str($page, 'name="dsid" value="','"');
                ?>
                        <center>
                        <form action="<?php echo $PHP_SELF; ?>" method="post"  />
                        <br><b>- Password Protected File -</b><br><font class="small">Please enter a password to access this file:</font><br/>
                        <input type="hidden" name="link" value="<?php echo urlencode($linkpw);?>" />
                        <input type="hidden" name="cookie" value="<?php echo urlencode($cookie2);?>" />
                        <input type="hidden" name="dsid" value="<?php echo $dsid;?>" />
                        <input type="hidden" name="pass_pre" value="ok" />
                        <input type="text" name="password_pre" /><p><input type="submit" value="Submit" /></p>
                        </form>
                        <?php
                        exit ();
                }
                if (stristr ( $page, "Location:" )) {
                $Href=cut_str ($page ,"Location: ","\r");
                $Url=parse_url($Href);
                $FileName=basename($Url["path"]);              
                RedirectDownload($Href,$FileName,$cookie3);exit;
                }
                else{
                html_error ( "Download link not found", 0 );
                }
        }           

     function getCountDown($page) {
          if (preg_match('/var c = (\d+)/', $page, $count)) {
               $countDown = $count[1];
               insert_timer ( $countDown, "Waiting link timelock" );
          }
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

// Written by VinhNhaTrang 25.11.2010
//[21-Nov-2010] Rewritten by Th3-822 & Using some code from the 2shared plugin.
// Rewrite & combine into 36B by Ruud v.Tony
?>
