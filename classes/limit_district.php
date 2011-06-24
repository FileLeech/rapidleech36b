<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

if(!isset($ipmu)) $ipmu = get_real_ip();

$pesankematian = "<html>$nn<head>$nn<title>:: {$RL_VER} ::</title>$nn<meta http-equiv=\"Content-Type\" content=\"text/html; $charSet\"><style type=\"text/css\">$nn<!--$nn@import url(\"".IMAGE_DIR."style_sujancok".$csstype.".css\");$nn-->$nn</style>$nn</head>$nn<body><br>$nn<h1>{$RL_VER}: Hey {$ipmu},";

//Cek ip yg banned
if($dlimitation[0]){
	if(!chk_this_ip('is_baned',$ipmu))
	{
	  if(!chk_this_ip('is_allow',$ipmu)){	
	    exit("{$pesankematian} ".$gtxt['unauthorized'] . "</h1>$nn</body>$nn</html>");
	  }
	}else{	
		exit("{$pesankematian} ".$gtxt['banned'] . "</h1>$nn</body>$nn</html>");
	}
}

//Cek country limitation
if($dlimitation[1]){
  $geo_class = CLASS_DIR."geoip.inc.php";
  $geo_db = CLASS_DIR."GeoIP.dat";
  if(!file_exists($geo_class) || !file_exists($geo_db)) return;
  require_once($geo_class);
  $gi = geoip_open($geo_db, GEOIP_STANDARD);
  // translate user ip to a country code
  $country = geoip_country_code_by_addr($gi, $ipmu);
  $country_name = geoip_country_name_by_addr($gi, $ipmu);
  $list_country_num = $gi->GEOIP_COUNTRY_CODE_TO_NUMBER;
  $list_country_name = $gi->GEOIP_COUNTRY_NAMES;
  // close the geo database
  geoip_close($gi);
  
  $msg_district = (isset($country) && $country!="" ? "Welcome" . $list_country_name["{$country}"] : "");
  $str_arr = str_replace(" ","", trim($allow_CID));
  $ar_allow_CID = ($str_arr!="" ? explode(",", $str_arr) : array());
  $str_arr = str_replace(" ","", trim($baned_CID));
  $ar_baned_CID = ($str_arr!="" ? explode(",", $str_arr):array());

  if(count($ar_baned_CID)>0){
     if(in_array($country, $ar_baned_CID)){
	    exit("{$pesankematian} ".$gtxt['banned_c'] . "</h1>$nn</body>$nn</html>");
	 }
  }
  if(count($ar_allow_CID)>0){
     if(!in_array($country, $ar_allow_CID)){
	    exit("{$pesankematian} ".$gtxt['unauthorized_c'] . "</h1>$nn</body>$nn</html>");
	 }
  }
}
?>