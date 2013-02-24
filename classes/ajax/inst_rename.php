<?php
if (!defined('RAPIDLEECH')) {
	require_once ( "index.html" );
	exit;
}

if (isset($_POST['ren'])) {
	@clearstatcache();
	_create_list();

	header("Content-type: text/html; charset=" . ( $charSet != "" ? $charSet : "UTF-8" ));

	$postFName = (string) $_POST['ren'];
	$partFN = explode("|", $postFName, 3); // time | newName | fileName
	$fileName = rawurldecode(base64_decode($partFN[2]));

	$smthExists = FALSE;
	foreach ($list as $time => $record) {
		if ($fileName == basename($record["name"])) {
			$fileName = $list[$partFN[0]]["name"];
			$lineset = $record;
			break;
		}
	}

	$ck_showall = ( isset($_COOKIE["showAll"]) ? $_COOKIE["showAll"] : false );
	if ($ck_showall == 1) { // we are in showall mode
		// force and get list in showdownload mode
		unset($list);
		_create_list(false, false, 2);
		$lineset = ( isset($list[$partFN[0]]) ? $list[$partFN[0]] : '' );
	}


	if (file_exists($fileName)) {

		$oldName = $fileName;
		$newName = str_replace("~", "", iconv("UTF-8", "ISO-8859-1//TRANSLIT", rawurldecode(base64_decode($partFN[1]))));
		$newName = dirname($oldName) . PATH_SPLITTER . $newName;

		$filetype = strrchr($newName, ".");
		if (is_array($options['forbidden_filetypes']) && in_array(strtolower($filetype), $options['forbidden_filetypes'])) {
			// ERROR
			echo "((suc))0((/suc))<msg>" . $L->sprintf($L->say['forbidden_rename'], $filetype) . "</msg>";
		} else {
			if (@rename($oldName, $newName)) {
				$smthExists = TRUE;


				foreach ($list as $key => $file) {
					if ($file["name"] == $oldName) {
						$list[$key] = $lineset;
						$list[$key]["name"] = $newName;
					}
				}
				echo "((suc))1((/suc))<msg>" . $L->say['_file'] . " " . ( basename($oldName) ) . " " . $L->say['rename_to'] . " <b>" . ( basename($newName) ) . " {$postFName}</msg>";
			} else {
				// ERROR
				echo "((suc))0((/suc))<msg>" . $L->sprintf($L->say['forbidden_rename'], $filetype) . "</msg>";
			}
		}
	} else {
		echo "((suc))0((/suc))<msg>" . $L->say['_file'] . " [" . $partFN[0] . "] " . $fileName . " " . $L->say['not_found'] . "\n" . print_r($list, true) . "</msg>";
	}
	if ($smthExists) {
		if (!updateListInFile($list)) {
			echo "((suc))0((/suc))<msg>" . $L->say['couldnt_upd'] . "</msg>";
		}
	}
}
?>