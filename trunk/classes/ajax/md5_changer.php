<?php
if (!defined('RAPIDLEECH')) {
	require_once ( "index.html" );
	exit;
}

if (isset($_POST['md5fn'])) {

	$ada = false;
	$fn = explode(":", $_POST['md5fn']);
	$idx = $fn[1];
	if (is_readable(DOWNLOAD_DIR . ( $fn[0] ))) {
		if (!in_array(strtolower(strrchr($fn[0], ".")), $options["forbidden_filetypes"])) {
			$ada = true;
		}
	}
	if (!$ada) {
		exit('<b class="r" title="Error or File Not Found!">&lt;!&gt;</b>');	// File not exist
	}

	$arrPengotor = array("0", "0", " ", "//", "/**/", "#");
	if ($idx < 0 || $idx > count($arrPengotor)) {
		$idx = 0;
	}

	//do change here
	$nfs = DOWNLOAD_DIR . $fn[0];
	$hdch = @chmod($nfs, 0666);
	$handle = @fopen($nfs, 'a+');
	if ($handle && $hdch) {
		fwrite($handle, $arrPengotor[$idx]);
		fclose($handle);
		exit('<b class="y" id="_result" title="New FileSize: ' .
				( getfilesize($nfs) ) . ' B" class="txtmd5">' .
				md5_file($nfs) . '</b>');
	} else {
		echo '<b class="r" title="Error!">&lt;T_T&gt;</b>';
	}
	@fclose($handle);
	@chmod($nfs, 0644);
	exit();
}
?>