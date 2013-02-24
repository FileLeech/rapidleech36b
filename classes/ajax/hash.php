<?php
if (!defined('RAPIDLEECH')) {
	require_once('index.html');
	exit;
}

function utf8_strrev($str) {
	preg_match_all('/./us', $str, $ar);
	return join('', array_reverse($ar[0]));
}

function rotN($s, $n) {
	$s2 = "";
	for ($i = 0; $i < strlen($s); $i++) {
		$c2 = $c1 = ord($s{$i});
		$cap = $c1 & 32;
		$c1&=~$cap;
		$c1 = $c1 > 64 && $c1 < 123 ? (($c1 - 65 + $n) % 26 + 65) : $c1;
		$c1|= $cap;
		if ($c1 < 65 && $c2 > 64 || ($c1 > 90 && $c1 < 97 && ($c2 < 91 || $c2 > 96))) $c1+=26;
		else if ($c1 > 122 && $c2 < 123) $c1-=52;
		if (strtoupper(chr($c2)) === chr($c2)) $c1 = strtoupper(chr($c1)); else $c1 = strtolower(chr($c1));
		$s2.= $c1;
	}
	return $s2;
}

function encEnti($v, $nE) {
	$rt = "";
	for ($i = 0; $i < strlen($v); $i++) {
		$ktr = strtoupper(dechex(ord($v{$i})));

		$uplw = rand(0, 1000);
		if ($uplw % 2 != 0) {
			$ktr = strtoupper($ktr);
		} else {
			$ktr = strtolower($ktr);
		}
		$rt.=$ktr;
	}
	if (strlen($nE) < 2) {
		$nE = trim('0' . $nE);
	}
	return $rt . $nE;
}

(isset($_GET["sg_md5"]) ? $s_md5 = $_GET["sg_md5"] : $s_md5 = '');
(isset($_GET["md5"]) ? $p_md5 = $_GET["md5"] : $p_md5 = '');
(isset($_GET["rs"]) ? $rs_auth = $_GET["rs"] : $rs_auth = '');
(isset($_GET["key"]) ? $rs_key = $_GET["key"] : $rs_key = '');
if (($s_md5 != '') && ($p_md5 != '') && ($rs_auth != '')) exit('null');

if ($p_md5 != '') {
	$parts = explode(":::", $p_md5);
	echo "_hash_" . utf8_strrev(base64_encode(md5($parts[0]) . ":" . md5($parts[1]))) . "_/hash_";
}

if (($rs_auth != '') && ($rs_key != '')) {
	$parts = explode(":::", $rs_auth);
	$usrpass = $parts[0] . ":" . $parts[1];
	$strROTr = rotN(strrev($usrpass), $rs_key);
	$encE = encEnti($strROTr, $rs_key);
	echo "_hash_" . $encE . "_/hash_";
}

if ($s_md5 != '') {
	echo "_hash_" . md5($s_md5) . "_/hash_";
}
?>