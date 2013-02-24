<?php
if (!defined('RAPIDLEECH')) {
	require('../404.php');
	exit();
}

if (!empty($_COOKIE["clearsettings"])) {
	setcookie("domail", "", TIME_NOW - 3600);
	setcookie("email", "", TIME_NOW - 3600);
	setcookie("saveto", "", TIME_NOW - 3600);
	setcookie("path", "", TIME_NOW - 3600);
	setcookie("useproxy", "", TIME_NOW - 3600);
	setcookie("proxy", "", TIME_NOW - 3600);
	setcookie("proxyuser", "", TIME_NOW - 3600);
	setcookie("proxypass", "", TIME_NOW - 3600);
	setcookie("split", "", TIME_NOW - 3600);
	setcookie("partSize", "", TIME_NOW - 3600);
	setcookie("savesettings", "", TIME_NOW - 3600);
	setcookie("clearsettings", "", TIME_NOW - 3600);
	setcookie("premium_acc", "", TIME_NOW - 3600);
	setcookie("premium_user", "", TIME_NOW - 3600);
	setcookie("premium_pass", "", TIME_NOW - 3600);
}

if (isset($_REQUEST["savesettings"]) && $_REQUEST["savesettings"] == "on") {
	setcookie("savesettings", TRUE, TIME_NOW + 800600);
	if ($_REQUEST ["domail"] == "on") {
		setcookie("domail", TRUE, TIME_NOW + 800600);
		if (checkmail($_REQUEST ["email"])) {
			setcookie("email", $_REQUEST ["email"], TIME_NOW + 800600);
		} else {
			setcookie("email", "", TIME_NOW - 3600);
		}

		if ($_REQUEST ["split"] == "on") {
			setcookie("split", TRUE, TIME_NOW + 800600);
			if (is_numeric($_REQUEST ["partSize"])) {
				setcookie("partSize", $_REQUEST ["partSize"], TIME_NOW + 800600);
			} else {
				setcookie("partSize", "", TIME_NOW - 3600);
			}
			if (in_array($_REQUEST ["method"], array("tc", "rfc"))) {
				setcookie("method", $_REQUEST ["method"], TIME_NOW + 800600);
			} else {
				setcookie("method", "", TIME_NOW - 3600);
			}
		} else {
			setcookie("split", "", TIME_NOW - 3600);
		}
	} else {
		setcookie("domail", "", TIME_NOW - 3600);
	}

	if ($_REQUEST ["saveto"] == "on") {
		setcookie("saveto", TRUE, TIME_NOW + 800600);
		if (isset($_REQUEST ["path"])) {
			setcookie("path", $_REQUEST ["path"], TIME_NOW + 800600);
		} else {
			setcookie("path", "", TIME_NOW - 3600);
		}
	} else {
		setcookie("saveto", "", TIME_NOW - 3600);
	}

	if ($_REQUEST ["useproxy"] == "on") {
		setcookie("useproxy", TRUE, TIME_NOW + 800600);
		if (strlen(strstr($_REQUEST ["proxy"], ":")) > 0) {
			setcookie("proxy", $_REQUEST ["proxy"], TIME_NOW + 800600);
		} else {
			setcookie("proxy", "", TIME_NOW - 3600);
		}

		if ($_REQUEST ["proxyuser"]) {
			setcookie("proxyuser", $_REQUEST ["proxyuser"], TIME_NOW + 800600);
		} else {
			setcookie("proxyuser", "", TIME_NOW - 3600);
		}

		if ($_REQUEST ["proxypass"]) {
			setcookie("proxypass", $_REQUEST ["proxypass"], TIME_NOW + 800600);
		} else {
			setcookie("proxypass", "", TIME_NOW - 3600);
		}
	} else {
		setcookie("useproxy", "", TIME_NOW - 3600);
	}

	if ($_REQUEST ["premium_acc"] == "on") {
		setcookie("premium_acc", $_REQUEST ["premium_acc"], TIME_NOW + 800600);
		if (isset($_REQUEST ["premium_user"]) && isset($_REQUEST ["premium_pass"])) {
			setcookie("premium_user", $_REQUEST ["premium_user"], TIME_NOW + 800600);
			setcookie("premium_pass", $_REQUEST ["premium_pass"], TIME_NOW + 800600);
		} else {
			setcookie("premium_user", "", TIME_NOW - 3600);
			setcookie("premium_pass", "", TIME_NOW - 3600);
		}
	} else {
		setcookie("premium_acc", "", TIME_NOW - 3600);
	}
}
?>