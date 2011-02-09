<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
	function Download($link) {
		global $premium_acc, $mu_cookie_user_value;

		if (preg_match ( "/v=(\w+)/", $link, $matches )) {
			$vid = $matches[1];
			//Direct download type...
		if (($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
			($_REQUEST ["premium_acc"] == "on" && $premium_acc ["megaupload"] ["user"] && $premium_acc ["megaupload"] ["pass"]) ||
			($_REQUEST["mu_acc"] == "on" && ($_REQUEST ["mu_cookie"] || $_REQUEST["mu_hash"] || $mu_cookie_auth_value))
			{
				DownloadPremium($vid);
			} else {
				DownloadFree($vid);
			}
		} else {
			html_error ("Bad link! Please check your link again!");
		}
	}
	
	function DownloadFree($vid) {
		global $Referer;
		
		$newurl = "http://www.megavideo.com/xml/videolink.php?v=" . $vid;
		$url = parse_url ( $newurl );
		$xml = geturl ( $url ["host"], $url ["port"] ? $url ["port"] : 80, $url ["path"] . ($url ["query"] ? "?" . $url ["query"] : ""), $Referer, 0, 0, 0, $_GET ["proxy"], $pauth );
		//
		$xmld = urldecode ($xml);
		$vs = cut_str ( $xml, '" s="', '"' );
		$vun = cut_str ( $xml, '" un="', '"' );
		$vk1 = cut_str ( $xml, '" k1="', '"' );
		$vk2 = cut_str ( $xml, '" k2="', '"' );
		$filename = cut_str ( $xml, '" title="', '"' ) . ".flv";
		//
		$sid = mvDecode ( $vun, $vk1, $vk2 );
		//
		$dlink = "http://www" . $vs . ".megavideo.com/files/" . $sid . "/";
		$Url = parse_url ( $dlink );
		
		//redirect
		insert_location ( "$PHP_SELF?filename=" . ($filename) . "&host=" . $Url ["host"] . "&port=" . $Url ["port"] . "&path=" . urlencode ( $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : "") ) . "&referer=" . urlencode ( $Referer ) . "&cookie=" . urlencode ( $cookie ) . "&email=" . ($_GET ["domail"] ? $_GET ["email"] : "") . "&partSize=" . ($_GET ["split"] ? $_GET ["partSize"] : "") . "&method=" . $_GET ["method"] . "&proxy=" . ($_GET ["useproxy"] ? $_GET ["proxy"] : "") . "&saveto=" . $_GET ["path"] . "&link=" . urlencode ( $LINK ) . ($_GET ["add_comment"] == "on" ? "&comment=" . urlencode ( $_GET ["comment"] ) : "") . ($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "") );
	}
	
	function DownloadPremium($vid) {
		global $Referer, $premium_acc, $mu_cookie_user_value;

		//Log
		$post = array ();
		$post ['login'] = 1;

		if(isset($_GET["auth_hash"])){
		 require_once("other.php");
		 $split_hash = explode(":", strrev(dcd($_GET["auth_hash"])));
		 if(count($split_hash)>1)
		 {
			$_GET["premium_user"] = $split_hash[0];
			$_GET["premium_pass"] = $split_hash[1];
		 }
		}		
		
		$post ["username"] = $_GET ["premium_user"] ? $_GET ["premium_user"] : $premium_acc ["megaupload"] ["user"];
		$post ["password"] = $_GET ["premium_pass"] ? $_GET ["premium_pass"] : $premium_acc ["megaupload"] ["pass"];
		$Url = parse_url ('http://www.megavideo.com/?c=login');
		$page = geturl ( $Url ["host"], $Url ["port"] ? $Url ["port"] : 80, $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : ""), 'http://www.megaupload.com', 0, $post, 0, $_GET ["proxy"], $pauth );
		is_page($page);
		
		$premium_cookie = trim ( cut_str ( $page, "Set-Cookie:", ";" ) );
		
		if ($mu_cookie_user_value) {
			$premium_cookie = 'user=' . $mu_cookie_user_value;
		} elseif ($_GET ["mu_acc"] == "on" && $_GET ["mu_cookie"]) {
			$premium_cookie = 'user=' . $_GET ["mu_cookie"];
		} elseif ($_GET["mu_hash"]) {
			$premium_cookie = 'user=' . strrev(dcd($_GET["mu_hash"]));		
		} elseif (! stristr ( $premium_cookie, "user" )) {
			html_error ( "Cannot use premium account", 0 );
		}

		//GetInfo...
		$newurl = "http://www.megavideo.com/xml/player_login.php?u={$premium_cookie}&v=" . $vid;
		$Url = parse_url ( $newurl );
		$page = geturl ( $Url ["host"], $Url ["port"] ? $Url ["port"] : 80, $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : ""), 0, 0, 0, 0, $_GET ["proxy"], $pauth );

		if (preg_match ( '/downloadurl="(.*)"/', $page, $result )) {
			$insert = urldecode ( $result [1] );
			$Url = parse_url ( $insert );
			$FileName = ! $FileName ? basename ( $Url ["path"] ) : $FileName;
			
			insert_location ( "$PHP_SELF?filename=" . urlencode ( $FileName ) . "&host=" . $Url ["host"] . "&path=" . urlencode ( $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : "") ) . "&referer=" . urlencode ( $Referer ) . "&email=" . ($_GET ["domail"] ? $_GET ["email"] : "") . "&partSize=" . ($_GET ["split"] ? $_GET ["partSize"] : "") . "&method=" . $_GET ["method"] . "&proxy=" . ($_GET ["useproxy"] ? $_GET ["proxy"] : "") . "&saveto=" . $_GET ["path"] . "&link=" . urlencode ( $LINK ) . ($_GET ["add_comment"] == "on" ? "&comment=" . urlencode ( $_GET ["comment"] ) : "") . "&auth=" . $auth . ($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "") );
		} else {
			html_error ("Premium account can't use. Please uncheck Use premium!");
		}
	}
	
	function mvDecode ($str, $key1, $key2) {
		$_loc1 = array ();
		for($_loc3 = 0; $_loc3 < strlen ( $str ); ++ $_loc3) {
			switch ($str {$_loc3}) {
				case "0" :
					$_loc1 [] = "0000";
					break;
				case "1" :
					$_loc1 [] = "0001";
					break;
				case "2" :
					$_loc1 [] = "0010";
					break;
				case "3" :
					$_loc1 [] = "0011";
					break;
				case "4" :
					$_loc1 [] = "0100";
					break;
				case "5" :
					$_loc1 [] = "0101";
					break;
				case "6" :
					$_loc1 [] = "0110";
					break;
				case "7" :
					$_loc1 [] = "0111";
					break;
				case "8" :
					$_loc1 [] = "1000";
					break;
				case "9" :
					$_loc1 [] = "1001";
					break;
				case "a" :
					$_loc1 [] = "1010";
					break;
				case "b" :
					$_loc1 [] = "1011";
					break;
				case "c" :
					$_loc1 [] = "1100";
					break;
				case "d" :
					$_loc1 [] = "1101";
					break;
				case "e" :
					$_loc1 [] = "1110";
					break;
				case "f" :
					$_loc1 [] = "1111";
					break;
			}
		}
		$_loc1 = join ( "", $_loc1 );
		$_loc1 = str_split ( $_loc1 );
		$_loc6 = array ();
		$kx = 0;
		for($_loc3 = 0; $_loc3 < 384; ++ $_loc3) {
			$key1 = ($key1 * 11 + 77213) % 81371;
			$key2 = ($key2 * 17 + 92717) % 192811;
			$_loc6 [$_loc3] = ($key1 + $key2) % 128;
		}
		for($_loc3 = 256; $_loc3 >= 0; -- $_loc3) {
			$_loc5 = $_loc6 [$_loc3];
			$_loc4 = $_loc3 % 128;
			$_loc8 = $_loc1 [$_loc5];
			$_loc1 [$_loc5] = $_loc1 [$_loc4];
			$_loc1 [$_loc4] = $_loc8;
		}
		for($_loc3 = 0; $_loc3 < 128; ++ $_loc3) {
			$_loc1 [$_loc3] = $_loc1 [$_loc3] ^ $_loc6 [$_loc3 + 256] & 1;
		}
		$_loc12 = join ( $_loc1, "" );
		$_loc7 = array ();
		for($_loc3 = 0; $_loc3 < strlen ( $_loc12 ); $_loc3 = $_loc3 + 4) {
			$_loc9 = substr ( $_loc12, $_loc3, 4 );
			$_loc7 [] = $_loc9;
		}
		$_loc2 = array ();
		for($_loc3 = 0; $_loc3 < count ( $_loc7 ); ++ $_loc3) {
			switch ($_loc7 [$_loc3]) {
				case "0000" :
					$_loc2 [] = "0";
					break;
				case "0001" :
					$_loc2 [] = "1";
					break;
				case "0010" :
					$_loc2 [] = "2";
					break;
				case "0011" :
					$_loc2 [] = "3";
					break;
				case "0100" :
					$_loc2 [] = "4";
					break;
				case "0101" :
					$_loc2 [] = "5";
					break;
				case "0110" :
					$_loc2 [] = "6";
					break;
				case "0111" :
					$_loc2 [] = "7";
					break;
				case "1000" :
					$_loc2 [] = "8";
					break;
				case "1001" :
					$_loc2 [] = "9";
					break;
				case "1010" :
					$_loc2 [] = "a";
					break;
				case "1011" :
					$_loc2 [] = "b";
					break;
				case "1100" :
					$_loc2 [] = "c";
					break;
				case "1101" :
					$_loc2 [] = "d";
					break;
				case "1110" :
					$_loc2 [] = "e";
					break;
				case "1111" :
					$_loc2 [] = "f";
					break;
			}
		}
		return (join ( $_loc2, "" ));
	}
}
/* HISTORY --------------------------------------------------
2009.04.25: Megavideo download plug-in written by kaox
2009.05.06: Premium download plug-in written by mrbrownee70
2010.12.04: Rewrite by thangbom40000 @ Share4u.vn
			Fixed download plugin for free & premium.
Rewrite into 36B by Ruud v.Tony			
-----------------------------------------------------------*/
?>
