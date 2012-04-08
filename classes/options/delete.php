<?php
function delete() {
	global $list, $PHP_SELF, $L;
?>
<form method="post" action="<?php echo ROOT_URL.basename($PHP_SELF);?>"><input type="hidden" name="act" value="delete_go" />
<?php
	echo $L->say[count($_GET['files']) > 1 ? '_files' : '_file'].':';
	foreach ($_GET['files'] as $k => $v) {
		echo '<input type="hidden" name="files[]" value="'.$v.'" /><br />';
		echo '<b>'.htmlspecialchars(basename($list[$v]['name'])).'</b>';
	}
?>
<br />
<?php echo $L->say['_delfile']; ?>?
<br />
<table>
	<tr>
		<td><input type="submit" name="yes" style="width: 33px; height: 23px"
			value="<?php echo $L->say['_yes']; ?>" />
		</td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td><input type="submit" name="no" style="width: 33px; height: 23px"
			value="<?php echo $L->say['_no']; ?>" />
		</td>
	</tr>
</table>
</form>
<?php
}

function delete_go() {
	global $list, $PHP_SELF, $L;
	if (isset($_POST["yes"])) {
		for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
			$file = $list [$_POST ["files"] [$i]];
			if (file_exists ( $file ["name"] )) {
				if (@unlink ( $file ["name"] )) {
					echo $L->sprintf($L->say['_filedel'],htmlspecialchars(basename($file['name'])))."<br /><br />";
					unset ( $list [$_POST ["files"] [$i]] );
				} else {
					echo $L->sprintf($L->say['error_delete'],htmlspecialchars(basename($file['name']))). "<br /><br />";
				}
			} else {
				unset ( $list [$_POST ["files"] [$i]] );
				echo $L->sprintf($L->say['not_found'],htmlspecialchars(basename($file['name'])))."<br />";
			}
		}
		if (! updateListInFile ( $list )) {
			echo "<b class=\"r\">".$L->say['error_upd_list']."</b><br /><br />";
		}
	} else {
		echo('<script type="text/javascript">location.href="'.ROOT_URL.basename($PHP_SELF).'?act=files";</script>');
	}
}
?>