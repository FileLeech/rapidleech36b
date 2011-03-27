<?php
if (! defined ( 'RAPIDLEECH' )) {
	require_once ("index.html");
	exit ();
}

class filesonic_com extends DownloadClass {

	public function Download($link) {
		global $premium_acc;
		if(preg_match('/http:\/\/.+filesonic\.com\/folder\/[^\'"]+/i', $link, $match)){
                if(!$match[0]){html_error ( 'Link not found' );}
                $page = $this->GetPage($link,0,0,0); 
                preg_match_all('/<td><a href="(.*)">/i', $page, $matches);
                if (! is_file ( "audl.php" )) html_error ( 'audl.php not found' );
			echo "<form action=\"audl.php?GO=GO\" method=post>\n";
			echo "<input type=hidden name=links value='" . implode ( "\r\n", $matches [1] ) . "'>\n";
			foreach ( array ( "useproxy", "proxy", "proxyuser", "proxypass" ) as $v )
				echo "<input type=hidden name=$v value=" . $_GET [$v] . ">\n";
			echo "<script language=\"JavaScript\">void(document.forms[0].submit());</script>\n</form>\n";
			flush ();
			exit ();
                }
                if ( ($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
			($_REQUEST ["premium_acc"] == "on" && $premium_acc ["filesonic_com"] ["user"] && $premium_acc ["filesonic_com"] ["pass"] ) ) 
                      
		{
			$this->DownloadPremium($link);
                }else
                if( $_POST['pass_pre'] == "ok") { 
                global $Referer;
                $post["passwd"]=$_POST['password'];
                $link=$_POST['link'];
                $cookie=$_POST['cookie'];
                $page = $this->GetPage($link, $cookie, $post, $Referer );
                is_page($page);
                if (stristr ( $page, "Location:" )) {
                $Href = trim ( cut_str ( $page, "Location: ", "\n" ) );
                $FileName = $_POST["name"];               
                $this->RedirectDownload($Href,$FileName,$cookie);exit;}
                $this->DownloadPremium($link);
                }else
                if( $_POST['pass_free'] == "ok") {
                global $Referer;    
                $post["passwd"]=$_POST['password_free'];
                $link=$_POST['link'];
                $cookie=$_POST['cookie'];
                $page = $this->GetPage($link, $cookie, $post, $Referer );
                is_page($page);
                $this->DownloadFree($link);
                }
                else
                {
                        $this->DownloadFree($link);
                }               	
	}
	private function DownloadFree($link) {
                global $Referer;
               
                if( $_POST['captcha'] == "ok") {
		@unlink(urldecode($_POST["delete"]));
		$post=unserialize(urldecode($_POST['post']));
		$post["recaptcha_response_field"] =$_POST["capt"];
                $cookie=urldecode($_POST["cookie"]);
		$link = $_POST["link"];
		$page = $this->GetPage($link,$cookie,$post,$Referer); 
		is_page($page);
                if(preg_match('/http:\/\/.+filesonic\.com\/download\/[^\'"]+/i', $page, $linkdr)){
                $Href = trim ($linkdr[0]);
                $FileName = $_POST["name"];               
                $this->RedirectDownload($Href,$FileName,$cookie);exit;}
                }
                if($_POST['captcha'] == "ok"){echo  ("<center><font color=red><b>Wrong captcha .Please re-enter</b></font></center>");}

		$page = $this->GetPage( $link, 0, 0, 0);
                $cookie = GetCookies($page);
                if (stristr ( $page, "Location:" ))
		{
                $link = trim(cut_str($page, "Location:","\n"));
                }
                $page = $this->GetPage($link,$cookie,0,0);
                is_page($page);
                preg_match('/\/file\/(\d+)/i', $link, $mh);
	        $id = $mh[1];
                preg_match ( '%<input type="text" value="(.*)" name="URL_%', $page, $fname );
                $linkcheck = $fname[1];
                $FileName = basename ($linkcheck);
                preg_match('/<a href="(.*)" id="free_download">/', $page, $match);
                $linkcd = "http://www.filesonic.com/file/$id/$match[1]"; 
                $page = $this->GetPage($linkcd,$cookie,0,$Referer);
                is_page($page);	
                if(strpos ( $page ,'<h3><span>Download Error</span></a></h3>' )){
                        html_error("Download link error by parallel downloads", 0);
                }
                if (stristr ( $page, "Please wait" ))
                {
                preg_match ( '/var countDownDelay = (.*);/', $page, $count );
		$countDown = trim($count [1]); 
                if($countDown<90){
                insert_timer( $countDown, "Waiting link timelock","",true );
                }
                if ($countDown>90) {
                    echo ('<script type="text/javascript">');
                    echo ('wait_time = ' . ($countDown + 1) . ';');
                    echo ('function waitLoop() {');
                    echo ('if (wait_time == 0) {');
                    echo ('location.reload();');
                    echo ('}');
                    echo ('wait_time = wait_time - 1;');
                    echo ('document.getElementById("waitTime").innerHTML = wait_time;');
                    echo ('setTimeout("waitLoop()",1000);');
                    echo ('}');
                    echo ('</script>');
		    echo '<br /><img src="http://images3.rapidshare.com/img/waitingdude.png" alt="" /><br /><br />';
                    html_error("Download limit exceeded. You have to wait <font color=black><span id='waitTime'>$countDown</span></font> second(s) until the next download.<script>waitLoop();</script>", 0);
                    }   
                }
                if (stristr ( $page, "Please Enter Password:" ))
                {
                preg_match('%<form enctype="application/x-www-form-urlencoded" action="(.*)" method="post">%', $page, $match);
                $linkpw = "http://www.filesonic.com$match[1]"; 
                ?>
                        <center>
                        <form action="<?php echo $PHP_SELF; ?>" method="post"  />
                        <br><b>- Password Protected File -</b><br><font class="small">Please enter the password below to download this file</font><br/>
                        <input type="hidden" name="link" value="<?php echo $linkpw;?>" />
                        <input type="hidden" name="cookie" value="<?php echo $cookie;?>" />
                        <input type="hidden" name="pass_free" value="ok" />
                        <input type="text" name="password_free" /><p><input type="submit" value="Submit" /></p>
                        </form>
                        <?php
                        exit ();
                }
                $tm = cut_str($page, "name='tm' value='","'");
                $tm_hash = cut_str($page, "name='tm_hash' value='","'");
                unset($post);
                $post["tm"] =$tm;
                $post["tm_hash"] =$tm_hash;
                $page = $this->GetPage($linkcd,$cookie,$post,$Referer);
                is_page($page);	
                if (stristr ( $page, "Please Enter Captcha:" ))
                {     
                preg_match('/<form action="(.*)" method="post" id="captchaForm">/', $page, $match);
                $linkstart = "$link$match[1]";
                $ch = cut_str ( $page ,'Recaptcha.create("' ,'"' );
                $cachestop = rand();
                $linkcaptcha ="http://www.google.com/recaptcha/api/challenge?k=$ch&cachestop=".$cachestop."&ajax=1";
                $page = $this->GetPage($linkcaptcha,0,0,0); 
		is_page($page);
		$cookieApi = GetCookies($page);
		$ch=cut_str ( $page ,"challenge : '" ,"'" );
                if($ch) {
                          $linkch = "http://www.google.com/recaptcha/api/image?c=".$ch;
                          $page = $this->GetPage($linkch,$cookieApi,0,0); 
			  $headerend = strpos($page,"\r\n\r\n");
			  $pass_img = substr($page,$headerend+4);
			  $imgfile= $download_dir."filesonic_captcha.jpg";
			  if (file_exists($imgfile)) {unlink($imgfile);}
			  write_file($imgfile, $pass_img);
		} else {html_error("Error get captcha. Please try again!", 0);}
		$post['recaptcha_challenge_field']=$ch;
		print	"<form method=\"post\" action=\"".$PHP_SELF.(isset($_GET["audl"]) ? "?audl=doum" : "")."\">$nn";
		print	"<h4>Enter <img src=\"$imgfile\"> here:</h4><input name=\"capt\" type=\"text\" >$nn";
		print	"<input name=\"link\" value=\"$linkstart\" type=\"hidden\">$nn";
		print   '<input type="hidden" name="post" value="'.urlencode(serialize($post)).'">'.$nn;
		print	"<input name=\"captcha\" value=\"ok\" type=\"hidden\">$nn";
                print	"<input type=\"hidden\" name=\"cookie\"  value=\"".urlencode($cookie)."\" >".$nn;
		print	"<input name=\"name\" value=\"$FileName\" type=\"hidden\">$nn";
		print	"<input type=\"hidden\" name=\"delete\"  value=\"".urlencode($imgfile)."\" >".$nn;
		print	"<input name=\"Submit\" value=\"Submit\" type=\"submit\"></form>";
                exit;
                }
                if(strpos ( $page ,'<h3><span>Download Error</span></a></h3>' )){
                        html_error("Download link error by parallel downloads", 0);
                }
                if(preg_match('/http:\/\/.+filesonic\.com\/download\/[^\'"]+/i', $page, $linkdr)){
                $Href = trim ($linkdr[0]);
                $FileName = basename ($link);              
                $this->RedirectDownload($Href,$FileName,$cookie);
                }
                $this->DownloadFree($link);       
	}
	private function DownloadPremium($link) {
		global $Referer, $premium_acc;
                
                $page = $this->GetPage( $link, 0, 0, 0);
                is_present($page, 'This file was deleted', 'Download link not found');
                if (stristr ( $page, "Location:" ))
		{
                $link = trim(cut_str($page, "Location:","\n"));
                }
                $page = $this->GetPage( $link, 0, 0, $Referer);
                preg_match ( '%<input type="text" value="(.*)" name="URL_%', $page, $fname );
                $linkcheck = $fname[1];
                $filename = basename ($linkcheck);
                $page = $this->GetPage("http://www.filesonic.com",0,0,0);
		is_page($page);			
		$cookie = GetCookies($page);
                $post = array ();
                $postlog['rememberMe'] = '1';
                $post ["email"] = $_GET ["premium_user"] ? $_GET ["premium_user"] : $premium_acc ["filesonic_com"] ["user"];
                $post ["password"] = $_GET ["premium_pass"] ? $_GET ["premium_pass"] : $premium_acc ["filesonic_com"] ["pass"];
                $page = $this->GetPage('http://www.filesonic.com/user/login',$cookie,$post,0);
                is_page($page);	
                $cookie = GetCookies($page);
                is_notpresent($cookie, 'nickname=', 'Error logging in - Account not found!');
                $page = $this->GetPage($link,$cookie,0,$Referer);
                 if (stristr ( $page, "Please Enter Password:" ))
                {      
                 preg_match('%<form enctype="application/x-www-form-urlencoded" action="(.*)" method="post">%', $page, $match);
                 $linkpw = "http://www.filesonic.com$match[1]"; 
                        ?>
                        <center>
                        <form action="<?php echo $PHP_SELF; ?>" method="post"  />
                        <br><b>- Password Protected File -</b><br><font class="small">Please enter the password below to download this file</font><br/>
                        <input type="hidden" name="link" value="<?php echo $linkpw;?>" />
                        <input type="hidden" name="cookie" value="<?php echo $cookie;?>" />
                        <input type="hidden" name="name" value="<?php echo $filename;?>" />
                        <input type="hidden" name="pass_pre" value="ok" />
                        <input type="text" name="password" /><p><input type="submit" value="Submit" /></p>
                        </form>
                        <?php
                        exit ();
                }
                if (stristr ( $page, "Location:" )) {
                $Href = trim ( cut_str ( $page, "Location: ", "\n" ) );
                $FileName = $filename;               
                $this->RedirectDownload($Href,$FileName,$cookie);
                }
                else{
                html_error ( "Download link not found", 0 );
                }   
        }           
}
// Written by VinhNhaTrang 21.10.2010
// fix by VinhNhaTrang 21.11.2010
?>