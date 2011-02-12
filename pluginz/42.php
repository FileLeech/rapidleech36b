<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
	  
	Download( $LINK );
	function Download( $link )
	{
		global $premium_acc;
		DownloadFree($link);
	}

	function DownloadFree($link)
	{
		global $nn, $PHP_SELF, $pauth;
		$page = GetPage($link);
				
		$un = trim ( cut_str ( $page, 'flashvars.un = "', '";' ) );
		$k1 = trim ( cut_str ( $page, 'flashvars.k1 = "', '";' ) );
		$k2 = trim ( cut_str ( $page, 'flashvars.k2 = "', '";' ) );
		$s = trim ( cut_str ( $page, 'flashvars.s = "', '";' ) );
		
		$id = decrypt($un, $k1, $k2 );	
		$Href = "http://www".$s.".megaporn.com/files/".$id."/";
				
		$FileName = $id.".flv";
		RedirectDownload( $Href, $FileName );
		exit ();
	}
	
	function decrypt($str, $key1, $key2 )
	{
		$a = array();
		$b = array();
		
		$strArray = str_split( $str );
		for( $i=0; $i<sizeof($strArray); $i++ )
		{
			$hexToDec = hexdec( $strArray[$i] );
			$decToBin = decbin( $hexToDec );
			$v = $decToBin;
			while( strlen( $v ) < 4 ) $v="0".$v;
			array_push( $a , $v );
		}
		
		$arryStr = join("", $a);
		$a = str_split( $arryStr );
			
		for( $i=0; $i<384; $i++ )
		{
			$key1=($key1*11+77213 )%81371;
			$key2=($key2*17+92717)%192811;
			$b[$i]=($key1+$key2)%128;
		}
	
		for( $i=256; $i>=0; $i-- )
		{
			$c = $b[$i];
			$d = $i%128;
			$e = $a[$c];
			$a[$c] = $a[$d];
			$a[$d] = $e;
		}
		
		for( $i=0; $i<128; $i++ )
		{
			$a[$i] = $a[$i]^$b[$i+256]&1;
		}
		
		$f = join("", $a);
		$b = array();
		
		for( $i=0; $i<strlen( $f ); $i+=4 )
		{
			$_loc9 = substr( $f , $i , 4 );
			array_push( $b , $_loc9 );
		}
		
		$f = array();
		for( $i=0; $i<sizeof($b); $i++ )
		{
			$binToDec = bindec ( $b[$i] );
			$decToHex = dechex ( $binToDec );
			array_push( $f , $decToHex );
		}
		return join("", $f);
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
	

// download plug-in writted by rajmalhotra  09 Dec 2009
// rewrite into 36B by Ruud v.Tony

?>