<?php
function mrename() {
	global $list, $PHP_SELF, $L;
?>
<form method="post" action="<?php echo $PHP_SELF; ?>"><input type="hidden" name="act" value="mrename_go" />
<?php echo $L->say['_file']; ?>:
<?php
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [$_GET ["files"] [$i]];
?>
<input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>" /> <b><?php echo basename ( $file ["name"] ); ?></b><?php echo $i == count ( $_GET ["files"] ) - 1 ? "." : ",&nbsp;"; ?>
<?php
	}
?>
<table>
<tr>
<td valign="middle"><b><?php echo $L->say['add_extension']; ?>&nbsp;</b><font size="2" color="yellow">&nbsp; <b><?php echo $L->say['_without']; ?>.</b>&nbsp; (dot)</font><b><input type="text" name="extension" size="10" value="" />&nbsp;<?php echo $L->say['_to']; ?> <?php echo $L->say['_file']; ?>.</b>&nbsp;
<input name="yes" type="submit" style="height: 23px" value="<?php echo $L->say['_rename']; ?>" />&nbsp;&nbsp;
<input name="no" type="submit" style="height: 23px" value="<?php echo $L->say['_cancel']; ?>" /></td>
</tr>
</table>
</form>
<?php
}

function mrename_go() {
	global $list, $options, $PHP_SELF, $L;
	if ($_POST ["yes"] && @trim($_POST['extension'])) {
		$_POST ['extension'] = @trim ( $_POST ['extension'] );
		
		while ( $_POST ['extension'] [0] == '.' )
			$_POST ['extension'] = substr ( $_POST ['extension'], 1 );
		
		if ($_POST [extension]) {
			for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
				$file = $list [$_POST ["files"] [$i]];
				if (file_exists ( $file ["name"] )) {
					$filetype = '.' . strtolower ( $_POST ['extension'] );
					if (is_array ( $options['forbidden_filetypes'] ) && in_array ( '.' . strtolower ( $_POST ['extension'] ), $options['forbidden_filetypes'] )) {
						echo $L->sprintf($L->say['forbidden_rename'],$filetype).'<br /><br />';
					} else {
						if (@rename ( $file ["name"], fixfilename ( $file ["name"] . ".{$_POST['extension']}" ) )) {
							echo $L->sprintf($L->say['_renamed_to'].'<br />',basename($file['name']),fixfilename ( basename ( $file ["name"] . ".{$_POST['extension']}" ) ));
							$list [$_POST ["files"] [$i]] ["name"] .= '.' . $_POST ['extension'];
							$list [$_POST ["files"] [$i]] ["name"] = fixfilename ( $list [$_POST ["files"] [$i]] ["name"] );
						} else {
							echo $L->sprintf($L->say['_error_ren'],basename($file['name'])).'<br />';
						}
					}
				} else {
					echo $L->sprintf($L->say['not_found'],basename($file['name'])).'<br />';
				}
			}
			if (! updateListInFile ( $list ))
				echo "<b class=\"r\">".$L->say['error_upd_list']."</b><br />";
		}
	} else {
?>
<script type="text/javascript">location.href="<?php echo $PHP_SELF . "?act=files"; ?>";</script>
<?php
	}
}
?>