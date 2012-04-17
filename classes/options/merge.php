<?php
function merge() {
	global $options, $list, $PHP_SELF, $L;
	if (count($_GET["files"]) !== 1) {
		echo $L->say['select_crc_001_file_only']."<br /><br />";
	}
	else {
		$file = $list [$_GET ["files"] [0]];
		if (substr ( $file ["name"], - 4 ) == '.001' && is_file ( substr ( $file ["name"], 0, - 4 ) . '.crc' )) {
			echo $L->say['select_crc_file']."<br /><br />";
		} elseif (substr ( $file ["name"], - 4 ) !== '.crc' && substr ( $file ["name"], - 4 ) !== '.001') {
			echo $L->say['select_crc_001_file']."<br /><br />";
		} else {
			echo $L->say['merge_file']." <b>".basename(substr($file["name"], 0, -4))."</b><br /><br />";
			$usingcrcfile = (substr ( $file ["name"], - 4 ) === '.001') ? false : true;
?>
<form method="post" action="<?php echo $PHP_SELF; ?>"><input type="hidden" name="files[0]" value="<?php echo $_GET ["files"] [0]; ?>" />
<table>
<?php
			if ($usingcrcfile) {
?>
<tr>
<td align="left"><input type="checkbox" name="crc_check" value="1" checked="checked" onclick="javascript:var displ=this.checked?'inline':'none';document.getElementById('crc_check_mode').style.display=displ;" />&nbsp;<?php echo $L->say['perform_crc']; ?><br />
			<span id="crc_check_mode"><?php echo $L->say['crc32_check']; ?>:<br />
<?php
				if (function_exists ( 'hash_file' )) {
?><input type="radio" name="crc_mode" value="hash_file" checked="checked" />&nbsp;<?php echo $L->say['use_hash']; ?><br />
<?php } ?>
<input type="radio" name="crc_mode" value="file_read" />&nbsp;<?php echo $L->say['read_file']; ?><br />
<input type="radio" name="crc_mode" value="fake"<?php if (! function_exists ( 'hash_file' )) { echo 'checked="checked"'; }?> />&nbsp;<?php echo $L->say['fake_crc']; ?></span></td>
</tr>
<tr>
<td><input type="checkbox" name="del_ok" <?php echo $options['disable_to']['act_del']? 'disabled="disabled"' : 'checked="checked"'; ?> />&nbsp;<?php echo $L->say['del_sourcefile']; ?></td>
</tr>
<?php
					} else {
?>
<tr>
<td align="center"><?php echo $L->say['notice']; ?>: <b><?php echo $L->say['size_cec32_wont_check']; ?></b></td>
</tr>
<?php
					}
?>
<tr>
<td align="center"><input type="hidden" name="act" value="merge_go" /> <input type="submit" value="<?php echo $L->say['act_merge']; ?>" /></td>
</tr>
</table>
</form>
<?php
		}
	}
}

function merge_go() {
	global $list, $options, $L;
	if (count($_POST["files"]) !== 1) {
		echo $L->say['select_crc_001_file_only']."<br /><br />";
	} else {
		$file = $list [$_POST ["files"] [0]];
		if (substr ( $file ["name"], - 4 ) == '.001' && is_file ( substr ( $file ["name"], 0, - 4 ) . '.crc' )) {
			echo $L->say['select_crc_file']."<br /><br />";
		} elseif (substr ( $file ["name"], - 4 ) !== '.crc' && substr ( $file ["name"], - 4 ) !== '.001') {
			echo $L->say['select_crc_001_file']."<br /><br />";
		} else {
			$usingcrcfile = (substr ( $file ["name"], - 4 ) === '.001') ? false : true;
			if (! $usingcrcfile) {
				$data = array ('filename' => basename ( substr ( $file ["name"], 0, - 4 ) ), 'size' => - 1, 'crc32' => '00111111' );
			} else {
				$fs = @fopen ( $file ["name"], "rb" );
			}
			if ($usingcrcfile && ! $fs) {
				echo $L->say['cant_read_crc_file']."<br /><br />";
			} else {
				if ($usingcrcfile) {
					$data = array ();
					while ( ! feof ( $fs ) ) {
						$data_ = explode ( '=', trim ( fgets ( $fs ) ), 2 );
						$data [$data_ [0]] = $data_ [1];
					}
					fclose ( $fs );
				}
				$path = realpath ( DOWNLOAD_DIR ) . '/';
				$filename = basename ( $data ['filename'] );
				$partfiles = array ();
				$partsSize = 0;
				for($j = 1; $j < 10000; $j ++) {
					if (! is_file ( $path . $filename . '.' . sprintf ( "%03d", $j ) )) {
						if ($j == 1) {
							$partsSize = - 1;
						}
						break;
					}
					$partfiles [] = $path . $filename . '.' . sprintf ( "%03d", $j );
					$partsSize += filesize ( $path . $filename . '.' . sprintf ( "%03d", $j ) );
				}
				if (file_exists ( $path . $filename )) {
					echo $L->sprintf($L->say['err_output_file_exist'],$path . $filename)."<br /><br />";
				} elseif ($usingcrcfile && $partsSize != $data ['size']) {
					echo $L->say['err_missing_parts']."<br /><br />";
				} elseif ($options['check_these_before_unzipping'] && is_array ( $options['forbidden_filetypes'] ) && in_array ( strtolower ( strrchr ( $filename, "." ) ), $options['forbidden_filetypes'] )) {
					echo $L->sprintf($L->say['_err_filetype'],strrchr ( $filename, "." ))."<br /><br />";
				} else {
					$merge_buffer_size = 2 * 1024 * 1024;
					$merge_dest = @fopen ( $path . $filename, "wb" );
					if (! $merge_dest) {
						echo $L->sprintf($L->say['imposible_open_dir'],$path . $filename)."<br /><br />";
					} else {
						$merge_ok = true;
						foreach ( $partfiles as $part ) {
							$merge_source = @fopen ( $part, "rb" );
							while ( ! feof ( $merge_source ) ) {
								$merge_buffer = fread ( $merge_source, $merge_buffer_size );
								if ($merge_buffer === false) {
									echo $L->sprintf($L->say['error_read_file'],$part)."<br /><br />";
									$merge_ok = false;
									break;
								}
								if (fwrite ( $merge_dest, $merge_buffer ) === false) {
									echo $L->sprintf($L->say['error_write_file'],$path . $filename)."<br /><br />";
									$merge_ok = false;
									break;
								}
							}
							fclose ( $merge_source );
							if (! $merge_ok) {
								break;
							}
						}
						fclose ( $merge_dest );
						if ($merge_ok) {
							$fc = ($_POST ['crc_mode'] == 'file_read') ? dechex ( crc32 ( read_file ( $path . $filename ) ) ) : (($_POST ['crc_mode'] == 'hash_file' && function_exists ( 'hash_file' )) ? hash_file ( 'crc32b', $path . $filename ) : '111111');
							$fc = str_repeat ( "0", 8 - strlen ( $fc ) ) . strtoupper ( $fc );
							if ($fc != strtoupper ( $data ["crc32"] )) {
								echo $L->say['crc32_unmatch']."<br /><br />";
							} else {
								echo $L->sprintf($L->say['success_merge'],$filename).'!<br /><br />';
								if ($usingcrcfile && $fc != '00111111' && $_POST["del_ok"] && !$options['disable_to']['act_del']) {
									if ($usingcrcfile) {
										$partfiles [] = $file ["name"];
									}
									foreach ( $partfiles as $part ) {
										if (@unlink ( $part )) {
											foreach ( $list as $list_key => $list_file ) {
												if ($list_file ["name"] === $part) {
													unset ( $list [$list_key] );
												}
											}
											echo "<b>" . basename ( $part ) . "</b> ".$L->say['_deleted'].".<br />";
										} else {
											echo "<b>" . basename ( $part ) . "</b> ".$L->say['_not_del'].".<br />";
										}
									}
									echo "<br />";
								}
								$time = getNowzone(@filemtime($path.$filename));
								while ( isset ( $list [$time] ) ) {
									$time ++;
								}
								$list [$time] = array ("name" => $path . $filename, "size" => bytesToKbOrMbOrGb ( $partsSize ), "date" => $time );
								if (! updateListInFile ( $list )) {
									echo $L->say['couldnt_upd_list']."<br /><br />";
								}
							}
						}
					}
				}
			}
		}
	}
}
?>