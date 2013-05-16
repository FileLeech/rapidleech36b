<?php
/******************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: xpanel.php - 16-04-2012- Modified by _R_V_T_™$
 ******************************************************/

define('RAPIDLEECH', 'yes');

require_once './global.php';

@set_magic_quotes_runtime(0);

// Debug ? show config-template pre-saving
$_debug = false;

$ch_curl = (extension_loaded("curl") ? 1 : 0);

//based on comment http://php.net/manual/en/function.get-magic-quotes-gpc.php
if (get_magic_quotes_gpc()) {
	$process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
	while (list($key, $val) = each($process)) {
		foreach ($val as $k => $v) {
			unset($process[$key][$k]);
			if (is_array($v)) {
				$process[$key][stripslashes($k)] = $v;
				$process[] = & $process[$key][stripslashes($k)];
			} else {
				$process[$key][stripslashes($k)] = stripslashes($v);
			}
		}
	}
	unset($process);
}
$ver_um = '2.0';
define('VERSION', "RL-CPanel " . $ver_um . " by Idoenk :: nohuhu<br />[ TuxiNuX::<a href='javascript:;' title='Kaskus.co.id #14'>CCPB</a> ]");
define('ACCVER', "Rapidshare-ACC Checker [by: reiluke]<br /><small>Thanks to limpy&amp;Viant77</small>");

define('ACC_EMBED', "<span class='g'>account embeded</span>");
define('KEY_EMBED', "<span class='g'>key embeded</span>");
define('CK_EMBED', "<span class='g'>cookie embeded</span>");

define('FILECONFIG', CONFIG_DIR . 'config.php');
define('FILEACCOUNT', CONFIG_DIR . 'accounts.php');
$settingfile = array(FILECONFIG, FILEACCOUNT);

//needed in tpl, check if it false,. ajax will not load.
$ajax_serverfiles = !$options['disable_ajax'];
$showcpanel2 = $options['allowcpanel'];

$arlang = $L->get_languages();
$arTPL = getArrayfromfile('tpl/', '', '', 'dir');
$arCSS = array();
foreach ($arTPL as $k => $v) {
	$arCSS[$v] = getArrayfromfile('tpl/' . $v . '/skin/', '', '', 'dir');
}
$mip_list = (@file_exists(LOG_DIR . "mip_iplist.txt") ? file_get_contents(LOG_DIR . "mip_iplist.txt") : "");

$cp_login = array();
$defaultcp = false;
if (!isset($options['loginCp'])) {
	$options['loginCp'] = array('admin' => 'admin'); //admin=>admin
	$defaultcp = true;
} else {
	foreach ($options['loginCp'] as $u => $p) {
		if ($u . $p === 'admin' . 'admin') {
			$defaultcp = true;
		}
		$cp_login[0] = $u;
		$cp_login[1] = $p;
	}
}
$rl_login = array();
foreach ($options['users'] as $u_rl => $p_rl) {
	$rl_login[0] = $u_rl;
	$rl_login[1] = $p_rl;
}

$ada_pre_acc = (isset($premium_acc) && is_array($premium_acc) && count($premium_acc) > 0);
if ($ada_pre_acc) {
	foreach ($premium_acc as $dhost => $val) {
		if ($val) {
			$ada_pre_acc = true;
			break;
		}
	}
}
$ada_upl_acc = (isset($upload_acc) && is_array($upload_acc) && count($upload_acc) > 0);
if ($ada_upl_acc) {
	foreach ($upload_acc as $dhost => $val) {
		if ($val) {
			$ada_upl_acc = true;
			break;
		}
	}
}


@$task = $_REQUEST["mode"];
#=========LOGIN Area=========
if ($showcpanel2) {
	foreach ($options['loginCp'] as $u => $p) {
		if (!empty($u) && !empty($p)) {
			if ($_SERVER["PHP_AUTH_USER"] != $u || $_SERVER["PHP_AUTH_PW"] != $p) {
				header('WWW-Authenticate: Basic realm="::Rx08 CPanel::"');
				header('HTTP/1.0 401 Unauthorized');
				get_tpl('404', 'Rx08 RL-CPanel: NuLL');
				exit();
			}
		}
	}
} else {
	get_tpl('404', 'Rx08 RL-CPanel Disabled');
	exit();
}
#=========END LOGIN Area=========
#=========Initial Glbl Array=========
/* Now I'll try to add the premium account automated listed based on global array here.
 * Change only the dot (.) with underscore (_) so it will validate authentification.
 * Do remember to use full name eg rapidshare_com for rapidshare.com not rs_com or rapidshare.
 * Now I'll split that into 5 kind of array, 4 for premium account(download), 1 for upload account.
 */
// PREMIUM WITH USER & PASS
$ar_pre_upass_acc = array(// User Pass
	'4shared_com',
	'animeground_com',
	'bayfiles_com',
	'bitshare_com',
	'bulletupload_com',
	'cloudzer_net',
	'cramit_in',
	'crocko_com',
	'cyberlocker_ch',
	'datafile_com',
	'depositfiles_com',
	'ex-load_com',
	'extabit_com',
	'fast-debrid_com',
	'file4sharing_com',
	'fileape_com',
	'filecloud_io',
	'filedino_com',
	'filefactory_com',
	'filefat_com',
	'filegaze_com',
	'filejungle_com',
	'filemates_com',
	'filepost_com',
	'fileserve_com',
	'fileserving_com',
	'filesflash_com',
	'filesmonster_com',
	'freakshare_com',
	'furk_net',
	'gigasize_com',
	'hellshare_com',
	'hotfile_com',
	'howfile_com',
	'jumbofiles_com',
	'letitbit_net',
	'luckyshare_net',
	'maknyos_com',
	'mediafire_com',
	'megashare_com',
	'megashares_com',
	'movshare_net',
	'netload_in',
	'netuploaded_com',
	'nowvideo_eu',
	'oron_com',
	'rapidshare_com',
	'real-debrid_com',
	'sendspace_com',
	'share-online_biz',
	'sharpfile_com',
	'slingfile_com',
	'speedshare_eu',
	'speedyshare_com',
	'turbobit_net',
	'ugotfile_com',
	'ultramegabit_com',
	'uploaded_net',
	'uploading_com',
	'uploadstation_com',
	'upstore_net',
	'youtube_com',
);
// PREMIUM ACCOUNT COOKIE
$ar_pre_cookie_acc = array(
	'autologin' => 'depositfiles_com',
	'auth' => 'hotfile_com',
	'cookie_user' => 'netload_in',
	'enc' => 'rapidshare_com',
	'login' => 'uploaded_net',
	'remembered_user' => 'uploading_com',
);
// PREMIUM ACCOUNT KEY
$ar_pre_key_acc = array(
	'fileflyer_com',
	'letitbit_net',
	'livefile_org',
	'shareflare_net',
	'vip-file_com',
);
// PREMIUM ACCOUNT MULTI (eg rapidshare.com which is useless now since they have
// change their regulation for premium account bandwidth limit)
// Maybe we can add new filehost if the plugin supported :D
$ar_pre_multi_acc = array('rapidshare_com');
// This will print the available premium account in our rapidleech page
// merge and remove duplicate array so we can have all premium account listed :D
$ar_pre_acc_all = array_unique(array_merge($ar_pre_upass_acc, $ar_pre_cookie_acc, $ar_pre_key_acc, $ar_pre_multi_acc));
// UPLOAD ACCOUNT
$ar_up_acc = array(
	'4shared_com',
	'bayfiles_com',
	'bitshare_com',
	'bulletupload_com',
	'cramit_in',
	'crocko_com',
	'cyberlocker_ch',
	'depositfiles_com',
	'extabit_com',
	'file4sharing_com',
	'fileape_com',
	'filecloud_io',
	'filedino_com',
	'filefactory_com',
	'filefat_com',
	'filegaze_com',
	'filejungle_com',
	'filemates_com',
	'filepost_com',
	'fileserve_com',
	'fileserving_com',
	'filesflash_com',
	'filesmonster_com',
	'freakshare_com',
	'gigasize_com',
	'hellshare_com',
	'hotfile_com',
	'howfile_com',
	'jumbofiles_com',
	'letitbit_net',
	'luckyshare_net',
	'maknyos_com',
	'mediafire_com',
	'megashare_com',
	'megashares_com',
	'movshare_net',
	'netload_in',
	'netuploaded_com',
	'nowvideo_eu',
	'oron_com',
	'rapidshare_com',
	'sendspace_com',
	'share-online_biz',
	'sharpfile_com',
	'slingfile_com',
	'speedshare_eu',
	'speedyshare_com',
	'turbobit_net',
	'ugotfile_com',
	'uploaded_net',
	'uploading_com',
	'uploadstation_com',
	'youtube_com',
);
#=========END Glbl Array=========

function view_perms($mode) {
	if (($mode & 0xC000) === 0xC000) {
		$type = "s";
	} elseif (($mode & 0x4000) === 0x4000) {
		$type = "d";
	} elseif (($mode & 0xA000) === 0xA000) {
		$type = "l";
	} elseif (($mode & 0x8000) === 0x8000) {
		$type = "-";
	} elseif (($mode & 0x6000) === 0x6000) {
		$type = "b";
	} elseif (($mode & 0x2000) === 0x2000) {
		$type = "c";
	} elseif (($mode & 0x1000) === 0x1000) {
		$type = "p";
	} else {
		$type = "?";
	}
	$owner["read"] = ($mode & 00400) ? "r" : "-";
	$owner["write"] = ($mode & 00200) ? "w" : "-";
	$owner["execute"] = ($mode & 00100) ? "x" : "-";
	$group["read"] = ($mode & 00040) ? "r" : "-";
	$group["write"] = ($mode & 00020) ? "w" : "-";
	$group["execute"] = ($mode & 00010) ? "x" : "-";
	$world["read"] = ($mode & 00004) ? "r" : "-";
	$world["write"] = ($mode & 00002) ? "w" : "-";
	$world["execute"] = ($mode & 00001) ? "x" : "-";
	if ($mode & 0x800) {
		$owner["execute"] = ($owner["execute"] == "x") ? "s" : "S";
	}
	if ($mode & 0x400) {
		$group["execute"] = ($group["execute"] == "x") ? "s" : "S";
	}
	if ($mode & 0x200) {
		$world["execute"] = ($world["execute"] == "x") ? "t" : "T";
	}
	return $type . join("", $owner) . join("", $group) . join("", $world);
}

function array_trim(&$v) {
	$v = trim($v);
}

function array_string(&$v) {
	if (count($v) == 0 || empty($v)) return $v;
	if (is_array($v)) {
		$result = '';
		foreach ($v as $key => $value) {
			$result .= "$v[$key], ";
		}
		$result = substr($result, 0, strlen($result) - 2);
	} else {
		$v = explode(',', $v);
		array_walk($v, 'array_trim');
		$v = (count($v) > 0 && strlen(trim($v[0])) > 0 ? $v : array());
		$result = $v;
	}
	return $result;
}

function view_size($size) {
	if (!is_numeric($size)) {
		return false;
	} else {
		if ($size >= 1073741824) {
			$size = round($size / 1073741824 * 100) / 100 . "&nbsp;GB";
		} elseif ($size >= 1048576) {
			$size = round($size / 1048576 * 100) / 100 . "&nbsp;MB";
		} elseif ($size >= 1024) {
			$size = round($size / 1024 * 100) / 100 . "&nbsp;KB";
		} else {
			$size = $size . "&nbsp;B";
		}
		return $size;
	}
}

function view_perms_color($o) {
	if (!is_readable($o)) {
		return "<blink><font color='red'>" . view_perms(fileperms($o)) . "</font></blink>";
	} elseif (!is_writable($o)) {
		return "<blink><font color='white'>" . view_perms(fileperms($o)) . "</font></blink> <small>(read-only)</small>";
	} else {
		return "<font color='#00FF00'>" . view_perms(fileperms($o)) . "</font>";
	}
}

// Load language in language folder; get css type; return array
function getArrayfromfile($loc, $predmatch, $succmatch, $find = 'file') {
	if ($loc == '') {
		return false;
	}
	$_CONSTANTS['D_DIR'] = $loc;
	$cleanfn = array();
	$dir = @dir($_CONSTANTS['D_DIR']);
	if ($dir) {
		while (false !== ($file = $dir->read())) {
			switch ($find) {
				case 'file':
					if ($file != "." && $file != ".." && is_file($_CONSTANTS['D_DIR'] . $file)) {
						preg_match("/$predmatch([a-zA-Z0-9_]+)$succmatch/", $file, $match);
						if (count($match) > 0) {
							$cleanfn[] = $match[1];
						}
					}
					break;
				case 'dir':
					if ($file != "." && $file != ".." && is_dir($_CONSTANTS['D_DIR'] . $file)) {
						preg_match("/$predmatch([a-zA-Z0-9_]+)$succmatch/", $file, $match);
						if (count($match) > 0) {
							$cleanfn[] = $match[1];
						}
					}
					break;
			}
		}
		$dir->close();
	}
	return $cleanfn;
}

function GetBetween($content, $start, $end) {
	$r = explode($start, $content);
	if (isset($r[1])) {
		$r = explode($end, $r[1]);
		return $r[0];
	}
	return '';
}

function filterNumericFld($postname, $title, $defaultval) {
	$valpost = (trim($_POST[$postname]) === '' ? $defaultval : (numericPost($postname, $title) ? trim($_POST[$postname]) : $defaultval));
	return $valpost;
}

function numericPost($postname, $errmsg) {
	global $showpostn, $iserr;
	if (!is_numeric(trim($_POST[$postname])) || trim($_POST[$postname]) < 0) {
		$showpostn = true;
		$iserr = '<b class="a">' . $errmsg . ' must be in positive numerical..</b><b class="b">!</b><br />';
		return false;
	} else {
		return true;
	}
}

function cekBlankAcc($parentchk, $usrnya, $passnya) {
	global $iserr;
	if (isset($_POST[$parentchk]) && $_POST[$parentchk] == 'on') {
		if ($passnya != '') {
			$iserr = ($_POST[$passnya] == '' ? $parentchk . " pass Acc is blank" : false);
		}
		if ($usrnya != '') {
			$iserr = $iserr || ($_POST[$usrnya] == '' ? $parentchk . " user Acc is blank" : false);
		}
	}
}

function isblankfield($parentchk, $childfield) {
	global $showpostn, $iserr;
	if ($showpostn) {
		if (isset($_POST[$parentchk]) && $_POST[$parentchk] == 'on') {
			if (empty($_POST[$childfield])) {
				$iserr = '<b class="a">[' . $parentchk . '] Account\'s Username/Password or Cookie or Key is empty..</b><b class="b">!</b><br />';
				return false;
			}
		}
	}
}

function singlefilter($fpar, $fu, $fp, $ff, $acc = 'acc_cookie') {
	global $_COOKIE, $premium_acc;
	if (isset($_POST[$fpar]) && $_POST[$fpar] == 'on') {
		$accchange = (isset($_COOKIE[$fpar]) ? ($_COOKIE[$fpar] != '' ? true : false) : false);
		if (!$accchange) { // no new acc, grab from config
			if ($acc == 'acc_cookie') {
				$key = str_replace("_hashpremix", "", $fpar);
				$_POST[$fu] = $premium_acc[$key]['cookie'];
			} else {
				$key = str_replace('_keypremix', '', $fpar);
				$_POST[$fu] = $premium_acc[$key]['key'];
			}
		} else {
			$_POST[$fu] = str_replace($ff, '', $_POST[$fu]);
			cekBlankAcc($fpar, $fu, $fp);
		}
	}
	setcookie($fpar, "", TIME_NOW - 3600, "/");
}

function fieldfilter($fpar, $fu, $fp, $ff, $acc = "acc_DL") {
	global $premium_acc, $upload_acc, $_COOKIE;
	if ($fpar == '') { // if parent checkbox not given
		$_POST[$fu] = str_replace($ff, '', $_POST[$fu]);
		return $_POST[$fu];
	} else {
		if (isset($_POST[$fpar]) && $_POST[$fpar] == 'on') {
			// check the cookie. new given acc, must change its cookie key
			$accchange = (isset($_COOKIE[$fpar]) ? ($_COOKIE[$fpar] != '' ? true : false) : false);
			if (!$accchange) { // no new acc, grab from config
				if ($acc == "acc_DL") {
					$key = str_replace("_premix", "", $fpar);
					$_POST[$fu] = $premium_acc[$key]["user"];
					$_POST[$fp] = $premium_acc[$key]["pass"];
				} else {
					$key = str_replace("_upload", "", $fpar);
					$_POST[$fu] = $upload_acc[$key]["user"];
					$_POST[$fp] = $upload_acc[$key]["pass"];
				}
			} else { // fill with new acc
				$_POST[$fu] = str_replace($ff, '', $_POST[$fu]);
				if ($fp != '') {
					$_POST[$fp] = str_replace($ff, '', $_POST[$fp]);
				}
				cekBlankAcc($fpar, $fu, $fp);
			}
		} else {
			$_POST[$fu] = "";
			$_POST[$fp] = "";
		}
		setcookie($fpar, "", TIME_NOW - 3600, "/");
	}
}

if ($task == 'editor') {
	if ($options['disableadvanceeditor']) {
		exit("<html><head></head><body><form action='" . basename($PHP_SELF) . "' name='frmmentalback' id='frmmentalback' method='post'>\n
			<input type='hidden' name='_lh' value='KSIAN.deh.GW'>\n
			</form><script type='text/javascript'>document.frmmentalback.submit();alert('Advanced Editor Disabled..!!!');</script></body></html>");
	}
}
// === MAIN
$buffer_TEXT = '';
if (!is_readable(FILECONFIG) or is_dir(FILECONFIG) || !is_readable(FILEACCOUNT) or is_dir(FILEACCOUNT)) {
	foreach ($settingfile as $file) {
		if (file_exists($file)) $buffer_TEXT .="<b>Permision denied (" . htmlspecialchars($file) . ")!</b>";
		else $buffer_TEXT .='<b>' . $file . ' does not exists.</b><br />';
	}
	unset($file);
} else {
	$close_config_page = false;
	$styledisplay = ' style="display:none;"';
	$showpostn = false;
	$iserr = false;
	foreach ($settingfile as $file) {
		$buffer_TEXT .= "<b>File:&nbsp;&nbsp;&nbsp;&nbsp;" . basename($file) . " (" . view_size(filesize($file)) . ") &nbsp; &nbsp; &nbsp; attrib: &nbsp; &nbsp; " . view_perms_color($file) . "</b><br />";
	}
	unset($file);
	$buffer_TEXT .= "You're&nbsp;logged&nbsp;with&nbsp;IP:&nbsp;<b class='g'>" . $visitors->userip . "</b><br /><hr width='800%' />";
	if (isset($_POST['submit']) && $_POST['setupsave'] == 1) {
		#============= WRITE CONFIG ================
		# Final filter to write config
		# Filter level 2; check strict value; raise error if value not valid
		if ($task == 'editor') {
			$edt = $_POST['edit_text'];
			$sfile = $_POST['file'];
			if (!write_file($sfile, $edt, 1)) {
				$buffer_TEXT .= "<b class='a'>Can't write to file!</b>";
			} else {
				$buffer_TEXT .= "<b style='color:#00FF33'>Saved!</b>";
//				$r = $edt;
			}
		} else {
			$showpostn = true;
			//filter input
			$badpetik = array('\'', '"');
			$badip = array('\'', '"', '`', '/', '\\', '|', '<', '>', '?', '+', '=', '^', ':', ';');
			$baddir = array('\'', '"', '`', '\\', '|', '<', '>', '?', '+', '=', '^', '*', ':', ';');
			$badchr = array('\'', '"', '`', '/', '\\', '|', '<', '>', '?', '+', '=', '^', '*', ':', ';');
			$badtime = array('\'', '"', '`', '/', '\\', '|', '<', '>', '?', '+', '=', '^', '*', ';', ',', '.');

			//CONFIG.PHP
			$new_options = array();
			foreach ($options as $k => $v) {
				if (!array_key_exists($k, $new_options)) {
					$new_options[$k] = $v;
				}
			}
			foreach ($options as $k => $v) {
				if (!in_array($k, array('disableadvanceeditor', 'xpanel_filename', 'index_file', 'allowcpanel'))) {
					if (is_array($options[$k])) {
						if ($k == 'forbidden_filetypes') {
							$new_options[$k] = array_string(str_replace($badchr, '', stripslashes($_POST['opt_' . $k])));
						} elseif ($k == 'ip_premixstat_list') {
							$new_options[$k] = array_string(str_replace($badip, '', stripslashes($_POST['opt_' . $k])));
						} elseif (!in_array($k, array('loginCp', 'users'))) {
							foreach ($options[$k] as $key => $value) {
								if (is_bool($options[$k][$key])) {
									$new_options[$k][$key] = (isset($_POST["opt_{$k}"][$key]) && $_POST["opt_{$k}"][$key] ? true : false);
								} elseif (is_numeric($options[$k][$key])) {
									$_POST["opt_{$k}"][$key] = filterNumericFld("opt_{$k}[{$key}]", str_replace('_', ' ', $key), $options[$k][$key]);
									$new_options[$k][$key] = (isset($_POST["opt_{$k}"][$key]) && $_POST["opt_{$k}"][$key] ? floor($_POST["opt_{$k}"][$key]) : 0);
								} else {
									$new_options[$k][$key] = (isset($_POST["opt_{$k}"][$key]) && $_POST["opt_{$k}"][$key] ? stripslashes($_POST["opt_{$k}"][$key]) : '');
								}
							}
						} else {
							continue;
						}
					} elseif (is_bool($options[$k])) {
						$new_options[$k] = (isset($_POST['opt_' . $k]) && $_POST['opt_' . $k] ? true : false);
					} elseif (is_numeric($options[$k])) {
						/*
						  //Check Numeric Field, if invalid or blank, give its default value.
						  >> filterNumericFld($postname, $title, $defaultval) {}
						 */
						$_POST['opt_' . $k] = filterNumericFld('opt_' . $k, str_replace('_', ' ', $k), $options[$k]);
						$new_options[$k] = (isset($_POST['opt_' . $k]) && $_POST['opt_' . $k] ? floor($_POST['opt_' . $k]) : 0);
					} else {
						if ($k == 'download_dir') {
							$_POST['opt_' . $k] = str_replace($baddir, '', $_POST['opt_' . $k]);
							while (strstr($_POST['opt_' . $k], "//")) {
								$_POST['opt_' . $k] = str_replace("//", "/", $_POST['opt_' . $k]);
							}
							if (substr($_POST['opt_' . $k], -1) != '/') {
								$_POST['opt_' . $k] .= '/';
							}
						} elseif (substr($k, 0, 4) == 'work') {
							$_POST['opt_' . $k] = str_replace($badtime, '', $_POST['opt_' . $k]);
						} elseif (substr($k, 0, 4) == 'list') {
							$_POST['opt_' . $k] = str_replace($badip, '', $_POST['opt_' . $k]);
						} elseif (substr($k, 0, -3) == 'CID') {
							$_POST['opt_' . $k] = str_replace($badip, '', strtoupper($_POST['opt_' . $k]));
						} elseif (in_array($k, array('rename_prefix', 'rename_suffix', 'add_ext_5city', 'rename_these_filetypes_to'))) {
							$_POST['opt_' . $k] = str_replace($badchr, '', $_POST['opt_' . $k]);
						}
						$new_options[$k] = (isset($_POST['opt_' . $k]) && $_POST['opt_' . $k] ? stripslashes($_POST['opt_' . $k]) : '');
					}
				} else {
					$new_options[$k] = $v;
				}
			}

			$new_options['loginCp'] = array();
			// CPanel must have a login, at least this default u/p: admin/admin
			if (empty($_POST['cplHash'])) {
				foreach ($options['loginCp'] as $ucp => $pcp) {
					if (empty($ucp)) $ucp = 'admin';
					if (empty($pcp)) $pcp = 'admin';
					$_POST['newusercp[]'] = $ucp;
					$_POST['newpascp[]'] = $pcp;
					$new_options['loginCp'][$ucp] = $pcp;
				}
			} elseif (isset($_POST['newusercp']) && isset($_POST['newpascp']) && count($_POST['newusercp']) > 0 && count($_POST['newusercp']) == count($_POST['newpascp'])) {
				foreach ($_POST['newusercp'] as $k => $u) {
					$u = stripslashes($u);
					$p = stripslashes($_POST['newpascp'][$k]);
					if (empty($u)) $u = 'admin';
					if (empty($p)) $p = 'admin';
					$new_options['loginCp'][$u] = $p;
				}
			}
			$new_options['users'] = array();
			if (empty($_POST['rlauthHash'])) {
				foreach ($options['users'] as $u => $p) {
					if (empty($u)) $u = 'sayur';
					if (empty($p)) $p = 'asem';
					$_POST['users[]'] = $ucp;
					$_POST['pass[]'] = $pcp;
					$new_options['users'][$u] = $p;
				}
			} elseif (isset($_POST['users']) && isset($_POST['pass']) && count($_POST['users']) > 0 && count($_POST['users']) == count($_POST['pass'])) {
				foreach ($_POST['users'] as $k => $u) {
					$u = stripslashes($u);
					$p = stripslashes($_POST['pass'][$k]);
					if (empty($u) || empty($p)) {
						continue;
					}
					$new_options['users'][$u] = $p;
				}
			}

			$tmp = "\r\n\r\n<IfModule mod_rewrite.c>\r\nRewriteEngine on\r\nRewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization},L]\r\n</IfModule>";
			$htacess = @file_get_contents('.htaccess');
			if (empty($htacess)) {
				$iserr = '<b class="a">It was not possible to read .htacess file</b><br />';
			} elseif (isset($_POST['opt_login_cgi']) && $_POST['opt_login_cgi'] == 1) {
				if (strpos($htacess, $tmp) === false) {
					if (!@write_file(".htaccess", $htacess . $tmp, 1)) {
						$iserr = '<b class="a">It was not possible to edit .htacess file to enable CGI authorization fix</b><br />';
						$new_options['login_cgi'] = false;
					}
				}
			} else {
				if (strpos($htacess, $tmp) !== false) {
					if (!@write_file(".htaccess", str_replace($tmp, '', $htacess))) {
						$iserr = '<b class="a">It was not possible to write .htacess file to completely disable CGI authorization fix</b><br />';
					}
				}
			}
			//cek limit by ip
			if (isset($_POST['opt_limitbyIP']) && $_POST['opt_limitbyIP'] == 1) {
				if (empty($_POST['opt_maximum_free_downloads']) || empty($_POST['opt_delay_per_ip'])) {
					if (empty($_POST['opt_maximum_free_downloads'])) {
						$iserr = '<b class="a">Leeching per ip is empty..</b><b class="b">!</b><br />';
					} elseif (empty($_POST['opt_delay_per_ip'])) {
						$iserr = '<b class="a">Delay per ip is empty..</b><b class="b">!</b><br />';
					}
					$new_options['limitbyIP'] = false;
				}
			}

			if (isset($_POST["opt_limitbytraffic"]) && $_POST["opt_limitbytraffic"] == 1) {
				if (!preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $_POST['opt_date_trafic']) || (empty($_POST['opt_max_trafic']))) {
					$iserr = '<b class="a">Expire-Date Traffic is not a Date..</b><b class="b">!</b><br />';
					$new_options['limitbytraffic'] = false;
				}
			}

			if (isset($_POST["opt_limit_timework"]) && $_POST["opt_limit_timework"] == 1) {
				$regtime = '/^([0-9]{1,2}):([0-5][0-9]):([0-5][0-9])$/';
				if ((!preg_match($regtime, $_POST['opt_workstart'])) || (!preg_match($regtime, $_POST['opt_workend']))) {
					$iserr = '<b class="a">Limit by Time is not a Time format..</b><b class="b">!</b><br />';
					$new_options['limit_timework'] = false;
				}
			}
			isblankfield(isset($_POST['opt_limited_edition']) ? $_POST['opt_limited_edition'] : "", $_POST['opt_list_allow_ip']);

			if (empty($_POST['opt_download_dir'])) {
				$iserr = '<b class="a">Download directory is empty..</b><b class="b">!</b><br />';
				$new_options['download_dir'] = $options['download_dir']; // get from the previous config
			}

			if (empty($_POST['newusercp[]']) || empty($_POST['newpascp[]'])) {
				$iserr = '<b class="a">Username or Password login Cpanel is empty..</b><b class="b">!</b><br />';
				$new_options['loginCp'] = $options['loginCp'];
			}
			if ((isset($_POST['opt_login']) && $_POST['opt_login'] == 1) && (empty($_POST['users']) || empty($_POST['pass']))) {
				$iserr = '<b class="a">Username or Password login index page is empty..</b><b class="b">!</b><br />';
				$new_options['login'] = false;
			}
			//==========================
			// Build template config.php
			$opt = var_export($new_options, true);
			$opt = (strpos($opt, "\r\n") === false ? str_replace(array("\r", "\n"), "\r\n", $opt) : $opt);
			$_config = "<?php\r\nif (!defined('RAPIDLEECH')) {\r\n\trequire_once('index.html');\r\n\texit;\r\n}\r\n\r\n\$options = " .
				$opt . ";\r\n\r\nrequire_once('accounts.php');\r\n\r\n# DEFINE writable dir OR files\r\ndefine('LOG_DIR', CONFIG_DIR . 'logfile/');\r\n" .
				"define('FILES_LST', LOG_DIR . 'Rx08_2208081410_f1L3Z.lst');\r\ndefine('IP_L33CH_L0G', LOG_DIR . 'Rx08_2208081410_IP_L33CH_L0G.lst');\r\n" .
				"define('VISITOR_LST', LOG_DIR . 'Rx08_2208081410_v1zit0r.lst');\r\ndefine('TRAFFIC_LST', LOG_DIR . 'Rx08_2208081410_tR4fic.txt');\r\n" .
				"define('LOG_PHP', LOG_DIR . 'Rx08_2208081410_d4Mn.log.php');\r\ndefine('LASTLOG_LST', LOG_DIR . 'Rx08_2208081410_d4Mn_Last.txt');\r\n" .
				"define('MYUPLOAD_LST', LOG_DIR . 'myuploads.txt');\r\n?>";

			// ACCOUNTS.PHP
			$multiacc = false;
			$vector_sacc = array("", "");
			// Use Premium Account, cookie and key counted too
			if (isset($_POST['usepcc']) && $_POST['usepcc'] == 'on') {
				foreach ($ar_pre_upass_acc as $k => $v) {
					if (in_array($v, $ar_pre_multi_acc)) {
						if (isset($_POST[$v . '_premix']) && $_POST[$v . '_premix'] == 'on') {
							// lets count the RS acc
							$idx_mrsacc = (isset($_COOKIE[$v . '_premix']) ? ($_COOKIE[$v . '_premix']) : "null"); // if this is set, assumed user insert new acc

							$arr_acc = array();
							if ($idx_mrsacc == "null") { // nothin new rs acc, grab the from config
								if (isset($premium_acc[$v . '_premix'][0]["user"])) {
									$arr_acc = $premium_acc[$v];
									$multiacc = true;
								} else {
									$arr_acc[0]["user"] = $premium_acc[$v]["user"];
									$arr_acc[0]["pass"] = $premium_acc[$v]["pass"];
									$multiacc = false;
								}
							} else { // we got new acc here
								if (is_numeric($idx_mrsacc)) {
									for ($i = 0; $i <= $idx_mrsacc; $i++) {
										$dumy_arr = array();
										$_POST['user_' . $v . $i] = str_replace($badpetik, '', $_POST['user_' . $v . $i]);
										$_POST['pass_' . $v . $i] = str_replace($badpetik, '', $_POST['pass_' . $v . $i]);
										if ($_POST['user_' . $v . $i] != '' && $_POST['pass_' . $v . $i] != '') {
											$dumy_arr["user"] = $_POST['user_' . $v . $i];
											$dumy_arr["pass"] = $_POST['pass_' . $v . $i];
											$arr_acc[] = $dumy_arr;
										}
										$multiacc = (count($arr_acc) > 1 ? true : false);
									}
								}
							} //-- counting done
							// we do list array vector here...
							if (!$multiacc) { // single acc
								$vector_sacc = explode(":", $arr_acc[0]["user"] . ":" . $arr_acc[0]["pass"]);
							} else {
								$vector_macc = array();
								for ($i = 0; $i < count($arr_acc); $i++) {
									$strROTr = ($arr_acc[$i]["user"] . ":" . $arr_acc[$i]["pass"]);
									$vector_macc[$i] = explode(":", $strROTr);
								}
							} //--end list array vector

							setcookie($v . '_premix', "", TIME_NOW - 3600, "/");
						} //--end if multi premix
					} else {
						fieldfilter($v . '_premix', 'user_' . $v, 'pass_' . $v, $badpetik);
					}
				}
				unset($v);
				foreach ($ar_pre_cookie_acc as $k => $v) {
					singlefilter($v . '_hashpremix', $v . '_cookie', '', $badpetik);
				}
				unset($v);
				foreach ($ar_pre_key_acc as $k => $v) {
					singlefilter($v . '_keypremix', $v . '_key', '', $badpetik, 'acc_key');
				}
				unset($v);
				//-- end $_POST['usepcc']
			} else { // why we need to post empty premium account value?
				// Why we should clean up this POST ? i dunno.
				for ($i = 0; $i < count($ar_pre_upass_acc); $i++) {
					$_POST[$ar_pre_upass_acc[$i] . '_premix'] = '';
				}
				foreach ($ar_pre_cookie_acc as $k => $cookie) {
					$_POST[$cookie . '_hashpremix'] = '';
				}
				for ($i = 0; $i < count($ar_pre_key_acc); $i++) {
					$_POST[$ar_pre_key_acc[$i] . '_keypremix'] = '';
				}
			}

			// If Upload Acc checked
			if (isset($_POST['useulcc']) && $_POST['useulcc'] == 'on') {
				foreach ($ar_up_acc as $k => $v) {
					fieldfilter($v . "_upload", 'user_' . $v . '_up', 'pass_' . $v . '_up', $badpetik, "acc_UL");
				}
				unset($v);
			}

			function fillField_premium($_var, $field, $flag) {
				$buftxt = '';
				foreach ($field as $key => $accsets) {
					$buftxt .= (isset($_POST[$key]) && $_POST[$key] == 'on' ? $flag : '//') . "\${$_var}[\"{$accsets[0]}\"] = array(" . "'user' => '" . stripslashes(trim($_POST[$accsets[1]])) . "', " . "'pass' => '" . stripslashes(trim($_POST[$accsets[2]])) . "');\r\n";
				}
				return $buftxt;
			}

			//===================
			//==========================
			// Build template accounts.php
			$_account = "<?php\nif (!defined('RAPIDLEECH'))\n  {require_once(\"index.html\");exit;}\n\n";
			$_account .= "###-PREMIUM-CONFIG\n\n";
			$_account .= "\$premium_acc = array();\r\n";
			foreach ($ar_pre_upass_acc as $k => $v) {
				if (in_array($v, $ar_pre_multi_acc)) {
					$_account .= (isset($_POST[$v . '_premix']) && $_POST[$v . '_premix'] == 'on' ? ($multiacc ? '//' : '') : '//') . "\$premium_acc[\"$v\"] = array('user' => '" . stripslashes($vector_sacc[0]) . "', 'pass' => '" . stripslashes($vector_sacc[1]) . "');\r\n";
					if (!$multiacc) {
						$_account .= (isset($_POST[$v . '_premix']) && $_POST[$v . '_premix'] == 'on' ? (!$multiacc ? '/*' : '') : '/*');
						$_account .= "// below here is the way u insert multiple acc for " . str_replace('_', '.', $v) . "\r\n";
						$_account .= "\$premium_acc[\"$v\"] = array(\r\n";
						$_account .= "\tarray('user' => 'user1', 'pass' => 'pass1'),\r\n";
						$_account .= "\tarray('user' => 'user2', 'pass' => 'pass2'),\r\n";
						$_account .= "\tarray('user' => 'user3', 'pass' => 'pass3')\r\n";
						$_account .= ");  //foobar - $v- multi; */\r\n";
					} else {
						$_account .= "\$premium_acc[\"$v\"] = array(";
						$vektor = "\r\n";
						for ($i = 0; $i < count($vector_macc); $i++) {
							$vektor .= "  array('user' => '" . stripslashes($vector_macc[$i][0]) . "', 'pass' => '" . stripslashes($vector_macc[$i][1]) . "')";
							if (($i + 1) != count($vector_macc)) {
								$vektor .= ",\r\n";
							}
						}
						$vektor .= "\r\n";
						$_account .= $vektor . ");  //foobar - $v- multi; */\r\n";
					}
				} else {
					$field = array();
					$field[$v . '_premix'] = array($v, "user_$v", "pass_$v");
					$_account .= fillField_premium("premium_acc", $field, '');
				}
			}
			unset($v);
			$_account .= "\r\n\r\n";
			$_account .= "###Premium cookie configuration, dont use this if you have already set another premium account config\n";
			foreach ($ar_pre_cookie_acc as $k => $v) {
				$_account .= (isset($_POST[$v . '_hashpremix']) && $_POST[$v . '_hashpremix'] == 'on' ? '' : '//') . "\$premium_acc[\"$v\"] = array('cookie' => '" . stripslashes(trim($_POST[$v . '_cookie'])) . "');\r\n";
			}
			unset($v);
			$_account .= "\r\n\r\n";
			$_account .= "###Premium key configuration, dont use this if you have already set another premium account config\r\n";
			foreach ($ar_pre_key_acc as $k => $v) {
				$_account .= (isset($_POST[$v . '_keypremix']) && $_POST[$v . '_keypremix'] == 'on' ? '' : '//') . "\$premium_acc[\"$v\"] = array('key' => '" . stripslashes(trim($_POST[$v . '_key'])) . "';\r\n";
			}
			unset($v);
			$_account .= "\r\n\r\n";
			$_account .= "#Upload Account #\r\n";
			$_account .= "\$upload_acc = array();\r\n";
			foreach ($ar_up_acc as $k => $v) {
				$field = array();
				$field[$v . '_upload'] = array($v, 'user_' . $v . '_up', 'pass_' . $v . '_up');
				$_account .= fillField_premium("upload_acc", $field, '');
			}
			unset($v);
			$_account .= "\r\n\r\n";
			$_account .= "#Secret key for cookie encryption\r\n";
			$_account .= "#Make up a random one to protect your premium cookies (max length: 56). Example: \$secretkey = 'UijSY5wjP1Ii'; - DO NOT use this example \$secretkey, or your premium accounts/cookies could be stolen!!\r\n";
			$_account .= "#IF THIS IS NOT SET BEFORE YOU USE PREMIUM SERVICES, YOU WILL BE WARNED BY THE RAPIDLEECH SCRIPT. OTHERWISE YOUR PREMIUM ACCOUNTS AND/OR COOKIES COULD BE COMPROMISED!\r\n";
			$_account .= "\$secretkey = '" . stripslashes(trim($_POST['secretkey'])) . "';//Place your Secret Key\r\n\r\n";

			$_account .= "# Acc info & drop down\r\n";
			$_account .= "\$ar_host_acc = array(\r\n";
			foreach ($ar_pre_acc_all as $key => $value) {
				$_account .= "\t'$value'	=>  '" . str_replace('_', '.', $value) . "',\r\n";
			}
			unset($value);
			$_account .= ");\r\n";
			$_account .= "\r\n\r\n";

			$_account .= "?>";

			if ($_debug) {
				$buffer_TEXT .= 'Debug Mode: <b class="a">ON</b><br />';
				foreach ($settingfile as $s) {
					$buffer_TEXT .= '<textarea name="edit_text" cols="122" rows="20" width="100%" readonly="readonly">' . ($s) . '</textarea><br />';
				}
				unset($s);
			}

			$close_config_page = true;
			$saved_success = false;
			if (!$iserr) {
				$lip = htmlspecialchars($_POST["mip_list"]);
				if (!write_file(FILECONFIG, $_config, 1) || !write_file(FILEACCOUNT, $_account, 1) || !write_file(LOG_DIR . 'mip_iplist.txt', $lip, 1)) {
					$buffer_TEXT .= '<b class="a">Can\'t write to file..</b><b class="b">!</b><br />';
				} else {
					// saving traffic if changed
					if ($options['day_reset_trafic'] != $_POST['opt_day_reset_trafic']) {
						global $traffic;

						$traffic->updTraffic();
					}

					$buffer_TEXT .= '<p><b style="color:#00FF33">Saved!</b></p>';
					$saved_success = true;
				}
				// print the output error message
			} else {
				$buffer_TEXT .= '<br />' . $iserr . '<br /><b class="a">An errors occur, configuration is not saved</b><br />';
			}
			$buffer_TEXT .= '<div style="height:10px;"></div><input id="btn_back" type="button" onclick="location.href=\'./' . basename($PHP_SELF) . '?mode=\'" value="Go Back to Config" />&nbsp;|&nbsp;<input type="button" onclick="location.href=\'./' . $options['index_file'] . '\'" value="Go to main" />';

			if ($close_config_page) {
				if ($saved_success) {
					$arfield = array("acc" => "", "key" => "", "cook" => "", "up" => "");
					foreach ($ar_pre_cookie_acc as $key => $_cpremix) {
						$arfield["cook"] .= "'" . $_cpremix . "_hashpremix'" . ($key != (count($ar_pre_cookie_acc) - 1) ? ", " : "");
					}
					foreach ($ar_pre_upass_acc as $key => $_premix) {
						$arfield["acc"] .= "'" . $_premix . "_premix'" . ($key != (count($ar_pre_upass_acc) - 1) ? ", " : "");
					}
					foreach ($ar_pre_key_acc as $key => $_kpremix) {
						$arfield["key"] .= "'" . $_kpremix . "_keypremix'" . ($key != (count($ar_pre_key_acc) - 1) ? ", " : "");
					}
					foreach ($ar_up_acc as $key => $_upremix) {
						$arfield["up"] .= "'" . $_upremix . "_upload'" . ($key != (count($ar_up_acc) - 1) ? ", " : "");
					}
					$buffer_TEXT .= "<script type=\"text/javascript\">
												 var tmpCk = new Object();
												  tmpCk['custom_rlck'] = Array('rl_ajax');
												  tmpCk['arfield_oth'] = Array(" . $arfield["cook"] . ");
												  tmpCk['arfield_acc'] = Array(" . $arfield["acc"] . ");
												  tmpCk['arfield_acc'] = Array(" . $arfield["key"] . ");
												  tmpCk['arfield_up'] = Array(" . $arfield["up"] . ");
												 for(var subCok in tmpCk){
												  ckRec = tmpCk[subCok];
												  for(var i=0; i<ckRec.length; i++){deleteCookie(ckRec[i], dirpath, '');}
												 }
												 try{document.getElementById('btn_back').focus()}catch(e){};
												</script>";
				}
			}
			$buffer_TEXT .= "\r\n<hr width=\"800\">\r\n<small>" . VERSION . "</small>\r\n</div>\r\n</td></tr>\r\n</table>\r\n</center>\r\n</body>\r\n</html><br />";
			// ===END write_config_file
			#============= WRITE CONFIG ================
//			exit();
		} // end xpanel dashboard
	} // end post submit
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charSet; ?>"/>
<meta name="robots" content="noindex, nofollow"/>
<link rel="shortcut icon" type="image/gif" href="<?php echo IMAGE_DIR . 'ico_home.gif?' . rand(11, 9999); ?>"/>
<title>RL-CPanel :: <?php echo $RL_VER; ?> ::</title>
<link type="text/css" href="<?php echo IMAGE_DIR; ?>style_sujancok<?php print $options["csstype"]; ?>.css?<?php echo rand(1, 9999); ?>" rel="stylesheet" media="screen"/>
<style type="text/css">
<!--
label:hover { text-decoration: underline overline; }
table { border-collapse: collapse; border: 0px solid #1E2637; }
th, td { padding: 0; }
-->
</style>
<script type="text/javascript" src="<?php echo STATIC_DIR; ?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_DIR; ?>js.php?xpanel"></script>
<script type="text/javascript">
/* <![CDATA[ */
var timer, txplain, method, stRet, hdret;
var d = document, lastslash;
var dirpath = location.pathname;
lastslash = dirpath.lastIndexOf("/");
dirpath = dirpath.substring(0, lastslash + 1);
var hash = '<?php echo CLASS_DIR . 'ajax/hash.php'; ?>';
var imgpath = '<?php echo IMAGE_DIR; ?>';
<?php
	if (!isset($_POST['setupsave'])) {
?>
var options = jQuery.parseJSON(<?php echo json_encode(json_encode($options)); ?>);
var general_checkbox = ['limitbyIP', 'downloadLimitbyIP', 'limitbytraffic', 'limited_edition', 'limited_area', 'limit_timework', 'limit_cpuload', 'forbidden_filetypes_block', 'new_window', 'mip_enabled', 'disable_action', 'premix_status', 'show_column_sfile_all', 'navi_left_all', 'forbid_all', 'showautoclose', 'mtn_showconfig', 'mtn_video_options', 'mtn_time'];
var forbid_checkbox = ['audl', 'auul', 'mtn', 'lynx'];

function load_current_config() {
	// Default options value loaded
	$.each(options, function(k, v) {
		if (!in_array(k, ['disableadvanceeditor', 'xpanel_filename', 'index_file', 'allowcpanel', 'list_allow_ip', 'CpuLoadFormat', 'passthru_allowed'])) {
			if (jQuery.type(options[k]) === 'object') {
				if (in_array(k, ['loginCp', 'users'])) {
					var i = 0;
					$.each(options[k], function(u, p) {
						if (k === 'loginCp') {
							$('#newloginCp [name="usercp[]"]').eq(i).val(u);
							$('#newloginCp [name="passcp[]"]').eq(i).val(p);
						} else {
							$('#loginrl [name="users[]"]').eq(i).val(u);
							$('#loginrl [name="pass[]"]').eq(i).val(p);
						}
						i++;
					});
				} else {
					$.each(options[k], function(key, values) {
						if (jQuery.type(options[k][key]) === 'boolean') {
							$('#opt_' + k + '_' + key + '').prop('checked', values ? true : false);
						} else if (jQuery.type(options[k][key]) === 'number') {
							$('#opt_' + k + '_' + key + '').val(Math.floor(values));
						} else {
							$('#opt_' + k + '_' + key + '').val(values);
						}
					});
				}
			} else if (jQuery.type(options[k]) === 'array') {
				$('#opt_' + k + '').val(v);
			} else if (jQuery.type(options[k]) === 'boolean') {
				$('#opt_' + k + '').prop('checked', v ? true : false);
			} else if (jQuery.type(options[k]) === 'number') {
				$('#opt_' + k + '').val(Math.floor(v));
			} else {
				$('#opt_' + k + '').val(v);
			}
		}
	});

	$('#opt_list_allow_ip').val('<?php echo (!empty($options['list_allow_ip']) ? $options['list_allow_ip'] : $visitors->userip); ?>');
	$('#opt_CpuLoadFormat').val('<?php echo (!function_exists('exec') && !function_exists('shell_exec') ? "percent" : "load");?>');
	$('#opt_passthru_allowed').<?php echo (function_exists('passthru') ? "prop({checked: true, disabled: false})" : "prop('checked', false)");?>;
	$('#opt_limited_area').<?php echo (!file_exists(CLASS_DIR . 'geoip.inc.php') || !file_exists(BINARY_DIR . 'geoip/GeoIP.dat') ? "prop('disabled', true); $('#showlimitedarea .g').show()" : "prop('disabled', false); $('#showlimitedarea .g').hide()"); ?>;
	$('#opt_disable_to_act_rar').<?php echo ((substr(PHP_OS, 0, 3) != "WIN" && @file_exists(BINARY_DIR . 'rar') && (function_exists('exec') || function_exists('shell_exec'))) ? "prop('disabled', false); $('#rar .g').hide()" : "prop('disabled', true); $('#rar .g').show()"); ?>;
	$('#opt_disable_to_act_unrar').<?php echo ((substr(PHP_OS, 0, 3) != "WIN" && @file_exists(BINARY_DIR . 'unrar') && (function_exists('exec') || function_exists('shell_exec'))) ? "prop('disabled', false); $('#unrar .g').hide()" : "prop('disabled', true); $('#unrar .g').show()"); ?>;

	$.each(general_checkbox, function(i, v) {
		var gid = str_replace('_', '', v).toLowerCase();
		if (!in_array(v, ['show_column_sfile_all', 'navi_left_all', 'forbid_all'])) {
			if (in_array(v, ['disable_action', 'forbidden_filetypes_block'])) {
				$('#opt_' + v + '').prop('checked') ? $('#' + gid + 'list').hide() : $('#' + gid + 'list').show();
			} else if (v === 'showautoclose' || v.substr(0, 3) === 'mtn') {
				$('#opt_' + (v.substr(0, 3) === 'mtn' ? v + '_enable' : v) + '').prop('checked') ? $('#' + gid + ', #' + gid + 'ket').show() : $('#' + gid + ', #' + gid + 'ket').hide();
			} else {
				$('#opt_' + v + '').prop('checked') ? $('#' + gid + 'list').show() : $('#' + gid + 'list').hide();
			}
		}
	});
	$.each(forbid_checkbox, function(i, v) {
		if ($('#opt_forbid_' + v + '').prop('checked')) {
			v === 'lynx' ? $('#opt_deletelink_in_lynx').prop({disabled: true, checked: false}) : $('#' + v + 'configlist').hide();
			$('#show' + v + ' .r').show();
		} else {
			v === 'lynx' ? $('#opt_deletelink_in_lynx').prop({disabled: false, checked: true}) : $('#' + v + 'configlist').show();
			$('#show' + v + ' .r').hide();
		}
	});
	if ($('#opt_navi_left_server_info').prop('checked')) {
		$('#opt_cpuUsageNFO').prop('disabled', false);
		$('#showcpuusagenfo .r').hide();
	} else {
		$('#opt_cpuUsageNFO').prop({disabled: true, checked: false});
		$('#showcpuusagenfo .r').show();
	}
}

$(document).ready(function() {

	$.each(general_checkbox, function(i, v) {
		var gid = str_replace('_', '', v).toLowerCase();
		$('#opt_' + (v.substr(0, 3) === 'mtn' ? v + '_enable' : v) + '').click(function() {
			if (in_array(v, ['disable_action', 'show_column_sfile_all', 'navi_left_all', 'forbid_all'])) {
				if ($(this).prop('checked')) {
					$('#' + gid + 'list :checkbox').each(function() {
						$(this).prop('checked', true);
					});
				}
				else {
					$('#' + gid + 'list :checkbox').prop('checked', in_array(v, ['disable_action', 'forbid_all']) ? false : true);
				}
				if (v === 'forbid_all') {
					$.each(forbid_checkbox, function(k, values) {
						values === 'lynx' ? $('#opt_forbid_' + values + '').prop('checked') ? $('#opt_deletelink_in_lynx').prop({disabled: true, checked: false}) : $('#opt_deletelink_in_lynx').prop({disabled: false, checked: true}) : $('#' + values + 'configlist').toggle();
						$('#show' + values + ' .r').toggle();
					});
				}
			} else if (v === 'showautoclose' || v.substr(0, 3) === 'mtn') {
				$('#' + gid + ', #' + gid + 'ket').toggle();
			}
			(v !== 'showautoclose' || v.substr(0, 3) !== 'mtn') ? $('#' + gid + 'list').toggle() : '';
		});
	});
	$.each(forbid_checkbox, function(i, v) {
		$('#opt_forbid_' + v + '').click(function() {
			v === 'lynx' ? $(this).prop('checked') ? $('#opt_deletelink_in_lynx').prop({disabled: true, checked: false}) : $('#opt_deletelink_in_lynx').prop({disabled: false, checked: true}) : $('#' + v + 'configlist').toggle();
			$('#show' + v + ' .r').toggle();
		});
	});
	$('#opt_navi_left_server_info').click(function() {
		$(this).prop('checked') ? $('#opt_cpuUsageNFO').prop('disabled', false) : $('#opt_cpuUsageNFO').prop({disabled: true, checked: false});
		$('#showcpuusagenfo .r').toggle();
	});

	load_current_config();
});
<?php
	}
?>
/* ]]> */
</script>
<script type="text/javascript" src="<?php echo STATIC_DIR; ?>rxpanel.js"></script>
<script type="text/javascript" src="<?php echo STATIC_DIR; ?>jscolor/jscolor.js"></script>
</head>
<body>
<div class="head_container" align="center"><a href="javascript:;" class="tdheadolgo" title="Rapidleech"></a></div>
<center>
<table cellspacing="0" cellpadding="1" width="80%" class="tablexpanel">
<tr>
<td>
<div align="center">
<?php
	echo $buffer_TEXT;
	if ($close_config_page) exit();
	include_once(CONFIG_DIR . "xpanelform.tpl.php");
}// end config file exist
?>
<hr width="800%"/>
<small><?php echo VERSION; ?></small>
</div>
</td>
</tr>
</table>
<br/><img src="http://notepad-plus.sourceforge.net/commun/images/npp.animated.logo.gif" alt="notepad++"/>
</center>
</body>
</html>