<?php 
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

	Download( $LINK );
	function Download( $link )
	{
	    global $premium_acc, $hf_cookie_auth_value;		
		if( ($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
			($_REQUEST ["premium_acc"] == "on" && $premium_acc ["ifile_it"] ["user"] && $premium_acc ["ifile_it"] ["pass"])			
		  )
		{
			DownloadPremium($link);
		}
		else
		{
			DownloadFree($link);
		}
	}

	function DownloadFree( $link )
	{
	  global $download_dir, $PHP_SELF, $nn;
	
	  $Referer = $link;
	  $dl_link = "http://ifile.it/dl";
	  
      if($_POST["ffi"] == "ok"){
	    
		$capcay_msg = cek_post_ffi($dl_link);
        
      } // end _POST
	  
	  // not else; just do it again
	  {

        $page = GetPage( $link );
		$cookie = GetCookies( $page );
		
        if(preg_match('/ocation: (.*)/',$page,$loc)){
           $loc = trim($loc[1]);
		   $Referer = $loc;
           $page = GetPage( $loc, $cookie, 0, $Referer);
		
		   if(!preg_match("/__alias_id.+=(?:[\t]+)*\'(.+)'/", $page, $file_key)) html_error('File key __alias_id Not Found!', 0);
	       $dllink = "http://ifile.it/download:dl_request?alias_id=".$file_key[1]."&type=na&esn=1";
	       $page = GetPage( $dllink, $cookie, 0, $Referer);
		   
		}elseif(!isset($_POST["ffi"])){
		   html_error( "Error get link ifile.it/dl, probably changes have been made on the page." , 0 );
		}
        
        if (strpos($page,'captcha":0') )
        {
		   $page = GetPage( $loc, $cookie );
		   // twice is a must
		   $page = GetPage( $loc, $cookie );
		   if(!preg_match('%href="(http://s\d+\.ifile\.it/.+/.+/\d+/.+\..{3})"%U', $page, $dlink)) html_error('Final Download Link Not Found!');
		   $Href = $dlink[1];

		   $FileName = basename($Href);        
		   RedirectDownload( $Href, $FileName );
		   exit ();

	   } elseif (strpos($page,'captcha":1') || $capcay_msg!=''){
        
		   $Referer = $dl_link;
		   load_recapcay($dllink, $cookie, $Referer, $capcay_msg);
		   
        }elseif(!isset($_POST["ffi"])){
           html_error( "Error get json result, probably changes have been made on the page." , 0 );
        }
      }
	
	}  // end DownloadFree


	function cek_post_ffi($dl_link=0){
	    $cookie=$_POST["cookie"];
        $post['recaptcha_response_field']=urlencode($_POST['captcha_check']);
        $post["recaptcha_challenge_field"]=$_POST["ch"];
	    $dllink=urldecode($_POST["link"]);
		foreach($post as $key => $value){
		  $dllink.="&".$key."=".$value;
		}
		$Referer = $dl_link;
		$page = GetPage( $dllink, $cookie, $post, $Referer );

		if (strpos($page,'retry":1') ){
		  $capcay_msg = "incorrect CAPTCHA entered, try again please";
		  return $capcay_msg;
		}else{
		   $page = GetPage( $dl_link, $cookie, 0, $Referer );
		   // twice is a must
		   $page = GetPage( $dl_link, $cookie, 0, $Referer );
		   
		   if(!preg_match('%href="(http://s\d+\.ifile\.it/.+/.+/\d+/.+\..{3})"%U', $page, $dlink)) {
		     html_error('Final Download Link Not Found!', 0);
		   }else{
		     $Href = $dlink[1];
             $FileName = basename($Href);
		     RedirectDownload( $Href, $FileName, $cookie, 0, $Referer );
		     exit();
		   }
		}
	}
	
	
	function load_recapcay($dllink, $cookie=0, $Referer=0, $capcay_msg=''){
	
       $page = GetPage( "http://api.recaptcha.net/challenge?k=6LehdQcAAAAAABt0QFfzJT0yxsFydQsVTADaFakD", 0, 0, $Referer );
       is_present($page,"Expired session", "Expired session . Go to main page and reattempt", 0);        
       $ch = cut_str ( $page ,"challenge : '" ,"'" );
       
       $page = GetPage( "http://api.recaptcha.net/image?c=".$ch, $cookie, 0, $Referer );
       $headerend = strpos($page,"\r\n\r\n");
       $pass_img = substr($page,$headerend+4);
       $imgfile=$download_dir."ifile_captcha.jpg";
       
       if (file_exists($imgfile)){ unlink($imgfile);} 
       write_file($imgfile, $pass_img);
       $dllink = str_replace("type=na","type=recaptcha",$dllink);
       
       print "<p><form method=\"post\" action=\"".$PHP_SELF.(isset($_GET["idx"]) ? "?idx=".$_GET["idx"] : "")."\">$nn";
       print ($capcay_msg!='' ? "<b class='r'>{$capcay_msg}</b><br>$nn" : "");
       print "<b>Please enter code:</b><br>$nn";
       print "<img src=\"{$imgfile}?".rand(1,10000)."\"><br><br>$nn";
       print "<input name=\"cookie\" value=\"$cookie\" type=\"hidden\">$nn";
       print "<input name=\"ch\" value=\"".$ch."\" type=\"hidden\">$nn";
       print "<input name=\"ffi\" value=\"ok\" type=\"hidden\">$nn";
       print "<input name=\"link\" value=\"".urlencode($dllink)."\" type=\"hidden\">$nn";
       print "<input name=\"captcha_check\" type=\"text\" >";
       print "<input name=\"Submit\" value=\"Submit\" type=\"submit\"></form></p>";		   
       exit("</body></html>");
	}
	
	
	function DownloadPremium($link)
	{	   
	   global $premium_acc, $PHP_SELF, $download_dir, $nn;
	   
	   $ch_curl = (extension_loaded("curl") ? 1 : 0);	   
	   $dl_link = "http://ifile.it/dl";
	   
	   if($_POST["ffi"] == "ok"){
	    
		  $capcay_msg = cek_post_ffi($dl_link);

       } // end _POST

	  {
	    if($ch_curl!=1) html_error("Can not proceed, require cURL extension_loaded.", 0);
	    
	    $signin = 'https://secure.ifile.it/account:process_signin';
	    $post = array();
	    $post["usernameFld"] = $_REQUEST["premium_user"] ? trim( $_REQUEST["premium_user"] ) : $premium_acc["ifile_it"]["user"];
	    $post["passwordFld"] = $_REQUEST["premium_pass"] ? trim( $_REQUEST["premium_pass"] ) : $premium_acc["ifile_it"]["pass"];
	    $post["submitBtn"] = "continue";
	    $page = sslcurl( $signin, $post );
	    $cookie = GetCookies($page);
		
	    $Referer = $link;
	    if (!preg_match('%(http\:\/\/ifile.it\/\?timestamp=\d+)">you have successfully signed in%', $page, $redir)) html_error('Invalid Authentication', 0);
	    $loc = trim($redir[1]);
	    $page = GetPage( $loc, $cookie );		
	    $cookie .= '; ' . GetCookies($page);
	    
	    $page = GetPage( $link, $cookie );
		
        if(!preg_match('%ocation: (.+)\r\n%', $page, $rdir)) html_error( "Error get link ifile.it/dl, probably changes have been made on the page." , 0 );
	    
	    $page = GetPage( $rdir[1], $cookie );
	    $Referer = $rdir[1];
	    
        if(!preg_match("/__alias_id.+=(?:[\t]+)*\'(.+)'/", $page, $file_key)) html_error('File key __alias_id Not Found!', 0);
	    $dllink = "http:/"."/ifile.it/download:dl_request?alias_id=".$file_key[1]."&type=na&esn=1";		
	    $page = GetPage( $dllink, $cookie, 0, $Referer);
        
	    if (strpos($page,'captcha":0') )
        {
           $loc = $rdir[1];
		   $page = GetPage( $loc, $cookie, 0, $Referer );
            // gajebo kacrut.. >,<" ; twice is a must
		   $page = GetPage( $loc, $cookie, 0, $Referer );
		   
		   if (!preg_match('%href="(http://s\d+\.ifile\.it/.+/.+/\d+/.+\..{3})"%U', $page, $dlink)) html_error('Final Download Link Not Found!', 0);
		   $Href = $dlink[1];
           $FileName = basename($Href);
		   RedirectDownload( $Href, $FileName, $cookie, 0, $Referer );
		   exit();		 
        }
        elseif (strpos($page,'captcha":1') || $capcay_msg!='')
        {
		   $Referer = $dl_link;
		   load_recapcay($dllink, $cookie, $Referer, $capcay_msg);
        }else{
           html_error( "Error get json result, probably changes have been made on the page." , 0 );
        }
	  }
	   
	   exit ();
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
	function GetPage($link, $cookie = 0, $post = 0, $referer = 0, $auth = 0) {
		global $pauth;
		if (!$referer) {
			global $Referer;
			$referer = $Referer;
		}
		$Url = parse_url(trim($link));
		$page = geturl ( $Url ["host"], defport($Url), $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : ""), $referer, $cookie, $post, 0, $_GET ["proxy"], $pauth, $auth );
		is_page ( $page );
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

    function idnum ($ll){
      $id = "";
      for($i=0; $i<$ll; $i++)
         $id .= floor(rand(0, 9));
      return $id;
    }
	
	function sslcurl ($link, $post = 0, $cookie = 0, $refer = 0)
    {
      $mm = !empty($post) ? 1 : 0;
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $link);
      curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U;Windows NT 5.1; de;rv:1.8.0.1)\r\nGecko/20060111\r\nFirefox/1.5.0.1');
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, $mm);
      curl_setopt($ch, CURLOPT_POSTFIELDS, formpostdata($post));
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_REFERER, $refer);
      curl_setopt($ch, CURLOPT_COOKIE, $cookie) ;
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      // curl_setopt ( $ch , CURLOPT_TIMEOUT, 15);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
      $contents .= curl_exec($ch);
      // $info = curl_getinfo($ch);
      // $stat = $info['http_code'];
      curl_close($ch);
      return $contents;
    }

/************************ifile.it**********************************
// written by kaox 24-may-2009
// update by kaox 15-nov-2009
// update by Idx 03-apr-2010
// update by Idx 27-may-2010
************************ifile.it*********************************/
?>