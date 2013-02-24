<?php
if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit;
}

// is limited_edition? Cek ip yg banned
if ($dlimitation[0]) {
	if (!chk_this_ip('is_baned', $visitors->userip)) {
		if (!chk_this_ip('is_allow', $visitors->userip)) {
			get_tpl("404", "<b>" . $visitors->userip . "</b>," . $L->say['unauthorized']);
		}
	} else {
		get_tpl("404", "<b>" . $visitors->userip . "</b>," . $L->say['banned']);
	}
}

// is limited_area? Cek country limitation
if ($dlimitation[1]) {
	$geo_class = CLASS_DIR . "geoip.inc.php";
	$geo_db = BINARY_DIR . "geoip/GeoIP.dat";
	if (!file_exists($geo_class) || !file_exists($geo_db)) return;
	require_once($geo_class);
	$gi = geoip_open($geo_db, GEOIP_STANDARD);
	// translate user ip to a country code
	$country = geoip_country_code_by_addr($gi, $visitors->userip);
	$country_name = geoip_country_name_by_addr($gi, $visitors->userip);
	$list_country_num = $gi->GEOIP_COUNTRY_CODE_TO_NUMBER;
	$list_country_name = $gi->GEOIP_COUNTRY_NAMES;
	// close the geo database
	geoip_close($gi);

	$msg_district = (isset($country) && $country != "" ? "Welcome" . $list_country_name["{$country}"] : "");
	$str_arr = str_replace(" ", "", trim($options["allow_CID"]));
	$ar_allow_CID = ($str_arr != "" ? explode(",", $str_arr) : array());
	$str_arr = str_replace(" ", "", trim($options["baned_CID"]));
	$ar_baned_CID = ($str_arr != "" ? explode(",", $str_arr) : array());

	if (count($ar_baned_CID) > 0) {
		if (in_array($country, $ar_baned_CID)) {
			get_tpl("404", "<b>" . $visitors->userip . "</b>," . $L->say['banned_c']);
		}
	}
	if (count($ar_allow_CID) > 0) {
		if (!in_array($country, $ar_allow_CID)) {
			get_tpl("404", "<b>" . $visitors->userip . "</b>," . $L->say['unauthorized_c']);
		}
	}
}
?>