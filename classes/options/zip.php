<?php
function zip() {
	global $list, $options, $PHP_SELF, $L;
?>
<form name="ziplist" method="post" action="<?php echo $PHP_SELF;?>"><input type="hidden" name="act" value="zip_go" />
	<table cellspacing="5">
		<tr>
			<td align="center"><strong><?php echo $L->say['add_zip'];?></strong></td>
		</tr>
		<tr>
			<td align="center">
				<table>
					<tr>
						<td><?php echo $L->say['arcv_name'];?>:&nbsp;<input type="text" name="archive" size="25" value=".zip" /></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="no_compression"<?php echo ($options['disable_archive_compression'] ? ' disabled="disabled" checked="checked"' : ''); ?> />&nbsp;<?php echo $L->say['no_compress'];?></td>
					</tr>
				</table>
				<table>
					<tr>
						<td><input type="submit" value="<?php echo $L->say['add_file'];?>" /></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
<?php
	echo "<br />Selected File" . (count ( $_GET ["files"] ) > 1 ? "s" : "") . ": ";
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [($_GET ["files"] [$i])];
		echo "<input type=\"hidden\" name=\"files[]\" value=\"{$_GET[files][$i]}\" />\r\n";
		echo "<b>" . basename ( $file ["name"] ) . "</b>";
		echo ($i == count ( $_GET ["files"] ) - 1) ? "." : ",&nbsp;";
	}
?>
<br /><br />
</form>
<?php
}

function zip_go() {
	global $list, $options, $L;
	$saveTo = realpath ( $options['download_dir'] ) . '/';
	$_POST ["archive"] = (strlen ( trim ( urldecode ( $_POST ["archive"] ) ) ) > 4 && substr ( trim ( urldecode ( $_POST ["archive"] ) ), - 4 ) == ".zip") ? trim ( urldecode ( $_POST ["archive"] ) ) : "archive.zip";
	$_POST ["archive"] = $saveTo.basename($_POST ["archive"]);
	for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
		$files [] = $list [($_POST ["files"] [$i])];
	}
	foreach ( $files as $file ) {
		$CurrDir = ROOT_DIR;
		$inCurrDir = stristr ( dirname ( $file ["name"] ), $CurrDir ) ? TRUE : FALSE;
		if ($inCurrDir) {
			$add_files [] = substr ( $file ["name"], (strlen ( $CurrDir )) );
		}
	}
	require_once (CLASS_DIR . "pclzip.php");
	$archive = new PclZip ( $_POST ["archive"] );
	$no_compression = ($options['disable_archive_compression'] || isset($_POST["no_compression"]));
	if (file_exists ( $_POST ["archive"] )) {
		if ($no_compression) { $v_list = $archive->add ( $add_files, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_NO_COMPRESSION); }
		else { $v_list = $archive->add ( $add_files, PCLZIP_OPT_REMOVE_ALL_PATH); }
	} else {
		if ($no_compression) { $v_list = $archive->create ( $add_files, PCLZIP_OPT_REMOVE_ALL_PATH, PCLZIP_OPT_NO_COMPRESSION); }
		else { $v_list = $archive->create ( $add_files, PCLZIP_OPT_REMOVE_ALL_PATH); }
	}
	if ($v_list == 0) {
		echo $L->say['_error'].": "  . $archive->errorInfo ( true ) . "<br /><br />";
		return;
	} else {
		echo $L->say['_arcv']." <b>" . $_POST ["archive"] . "</b> ".$L->say['success_created']."<br /><br />";
	}
	if (is_file($_POST['archive'])) {
		$time = getNowzone(@filemtime($_POST['archive'])); while (isset($list[$time])) { $time++; }
		$list[$time] = array("name" => $_POST['archive'], "size" => bytesToKbOrMbOrGb(filesize($_POST['archive'])), "date" => $time);
		if (!updateListInFile($list)) { echo $L->say['couldnt_upd_list']."<br /><br />"; }
	}
}
?>