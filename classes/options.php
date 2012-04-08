<?php
if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit;
}

$all_act_files_exist = false;
if ((isset($_GET["act"]) || isset($_POST["act"])) && @$_GET["act"] !== 'files') {
	if(substr($_GET["act"],-3,3)=="_go"){
		$buftxt = "<script type='text/javascript'>" .
				"function slip(){var txt, lst='" . $L->say['hide_last_act'] . "'; var d_txt = d.getElementById('act_hist_txt'); " .
				"var d_img = d.getElementById('act_hist_img');var d_hist = d.getElementById('act_history');txt = d_txt.innerHTML;" .
				"if(txt==lst){ d_txt.innerHTML = '" . $L->say['show_last_act'] . "'; d_img.src = '" . ROOT_URL.IMAGE_DIR . "plus.png'; d_hist.style.display = 'none';" .
				"}else{" .
				"d_txt.innerHTML = lst; d_img.src = '" . ROOT_URL.IMAGE_DIR . "min.png'; d_hist.style.display = '';}" .
				"return false;" .
				"}</script>" .
				"<label onclick='return slip();'><span id='act_hist_txt'>" . $L->say['hide_last_act'] . "</span>" .
				"<input id='act_hist_img' type='image' src='" . ROOT_URL.IMAGE_DIR . "min.png' /></label>" .
				"<div id='act_history' class='acthistory_result'>";
	}elseif($_GET["act"]!='files'){
		$buftxt = "<script type='text/javascript'>" .
				"function closeit(flag){var d_shlast = d.getElementById(\"showlast\");var d_hist = d.getElementById(\"act_history\");" .
				"d_shlast.style.display = (flag?'':'none'); d_hist.style.display = (flag?'none':'');}" .
				"</script>" .
				"<label id='showlast' onclick='closeit(false);' style='display:none;'><span id='act_hist_txt'>" . $L->say['show_last_act'] . "</span>" .
				"<input id='act_hist_img' type='image' src='" . ROOT_URL.IMAGE_DIR . "plus.png' /></label>" .
				"<div id='act_history' class='acthistory_prompt'><div class='closer_btn' onclick='closeit(true);'>" .
				"<span>" . $L->say['close'] . "</span></div><div style='padding-bottom:12px;'></div>";
	}
	if ($options["disable_actions"]) {
		echo $L->say['act_disabled'] . "<br /><br />";
	} elseif ((!is_array($_GET['files']) || count($_GET['files']) < 1) && (!is_array($_POST['files']) || count($_POST['files']) < 1)) {
		echo $L->say['select_one_file'] . "<br /><br />";
	} else {
		$all_act_files_exist = true;
		foreach ($_GET["files"] as $v) {
			$file = $list[$v]['name'];
			if (!is_file($file)) {
				if ($options['2gb_fix'] && in_array($_GET['act'], array('delete', 'delete_go')) && file_exists($file) && !is_dir($file) && !is_link($file)) {
					$size_time = file_data_size_time($file);
				} else {
					$size_time = false;
				}
			}
			if ($size_time === false) {
				$all_act_files_exist = false;
				echo $L->sprintf($L->say['file_not_exists'], '<b>' . htmlentities($file) . '</b>') . '<br />';
				break;
			}
		}
	}
}

if ($all_act_files_exist) {
    echo $buftxt;
	switch ($_GET ["act"]) {
		case "upload" :
			if ($options['disable_to']['act_upload']) { break; }
			if (!defined('UPLOAD_DIR')) define('UPLOAD_DIR', HOST_DIR . 'upload/');
			require(CLASS_DIR . "options/upload.php");
			upload();
			break;

		case "delete" :
			if ($options['disable_to']['act_del']) { break; }
			require(CLASS_DIR . "options/delete.php");
			delete();
			break;

		case "delete_go" :
			if ($options['disable_to']['act_del']) { break; }
			require(CLASS_DIR . "options/delete.php");
			delete_go();
			break;

		case "mail" :
			if ($options['disable_to']['act_mail']) { break; }
			require(CLASS_DIR . "options/mail.php");
			rl_mail();
			break;

		case "mail_go" :
			if ($options['disable_to']['act_mail']) { break; }
			require(CLASS_DIR . "options/mail.php");
			mail_go();
			break;

		case "boxes" :
			if ($options['disable_to']['act_boxes']) { break; }
			require(CLASS_DIR . "options/boxes.php");
			boxes();
			break;

		case "boxes_go" :
			if ($options['disable_to']['act_boxes']) { break; }
			require(CLASS_DIR . "options/boxes.php");
			boxes_go();
			break;

		case "md5" :
			if ($options['disable_to']['act_md5']) { break; }
			require(CLASS_DIR . "options/md5.php");
			rl_md5();
			break;

		case "zip" :
			if ($options['disable_to']['act_zip']) { break; }
			require(CLASS_DIR . "options/zip.php");
			zip();
			break;

		case "zip_go" :
			if ($options['disable_to']['act_zip']) { break; }
			require(CLASS_DIR . "options/zip.php");
			zip_go();
			break;

		case "unzip" :
			if ($options['disable_to']['act_unzip']) { break; }
			require(CLASS_DIR . "options/unzip.php");
			unzip();
			break;

		case "unzip_go" :
			if ($options['disable_to']['act_unzip']) { break; }
			require(CLASS_DIR . "options/unzip.php");
			unzip_go();
			break;

		case "split" :
			if ($options['disable_to']['act_split']) { break; }
			require(CLASS_DIR . "options/split.php");
			rl_split();
			break;

		case "split_go" :
			if ($options['disable_to']['act_split']) { break; }
			require(CLASS_DIR . "options/split.php");
			split_go();
			break;

		case "merge" :
			if ($options['disable_to']['act_merge']) { break; }
			require(CLASS_DIR . "options/merge.php");
			merge();
			break;

		case "merge_go" :
			if ($options['disable_to']['act_merge']) { break; }
			require(CLASS_DIR . "options/merge.php");
			merge_go();
			break;

		case "rename" :
			if ($options['disable_to']['act_rename']) { break; }
			require(CLASS_DIR . "options/rename.php");
			rl_rename();
			break;

		case "rename_go" :
			if ($options['disable_to']['act_rename']) { break; }
			require(CLASS_DIR . "options/rename.php");
			rename_go();
			break;

		//MassRename
		case "mrename" :
			if ($options['disable_to']['act_mrename']) { break; }
			require(CLASS_DIR . "options/mrename.php");
			mrename();
			break;

		case "mrename_go" :
			if ($options['disable_to']['act_mrename']) { break; }
			require(CLASS_DIR . "options/mrename.php");
			mrename_go();
			break;

		//end MassRename


		case "ftp" :
			if ($options['disable_to']['act_ftp']) { break; }
			require(CLASS_DIR . "options/ftp.php");
			ftp();
			break;

		case "ftp_go" :
			if ($options['disable_to']['act_ftp']) { break; }
			require(CLASS_DIR . "options/ftp.php");
			ftp_go();
			break;

		case "unrar" :
			if ($options['disable_to']['act_unrar']) { break; }
			require(CLASS_DIR . "options/unrar.php");
			unrar();
			break;

		case "unrar_go" :
			if ($options['disable_to']['act_unrar']) { break; }
			require(CLASS_DIR . "options/unrar.php");
			unrar_go();
			break;

		case "rar" :
			if ($options['disable_to']['act_rar']) { break; }
			require(CLASS_DIR . "options/rar.php");
			rar();
			break;

		case "rar_go" :
			if ($options['disable_to']['act_rar']) { break; }
			require(CLASS_DIR . "options/rar.php");
			rar_go();
			break;

		case "pack" :
			if ($options['disable_to']['act_pack']) { break; }
			require(CLASS_DIR . "options/pack.php");
			rl_pack();
			break;

		case "pack_go" :
			if ($options['disable_to']['act_pack']) { break; }
			require(CLASS_DIR . "options/pack.php");
			pack_go();
			break;
	}
	echo "</div>";  // box prompt/result
}
?>