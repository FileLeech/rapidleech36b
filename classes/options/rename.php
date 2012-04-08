<?php
function rl_rename() {
	global $list, $PHP_SELF, $L;
?>
<form method="post" action="<?php echo ROOT_URL.basename($PHP_SELF); ?>"><input type="hidden" name="act" value="rename_go" />
		<table align="center" style="text-align: left;">
			<tr>
				<td>
				<table>
<?php
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [$_GET ["files"] [$i]];
?>
<tr>
	<td align="center"><input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>" /><b><?php echo htmlspecialchars(basename($file["name"])); ?></b></td>
</tr>
<tr>
	<td><?php echo $L->say['new_name']; ?>:&nbsp;<input type="text" name="newName[]" size="25"
		value="<?php echo htmlspecialchars(basename($file["name"])); ?>" /></td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<?php
	}
?>
                                  </table>
				</td>
				<td><input type="submit" value="Rename" /></td>
			</tr>
			<tr>
				<td></td>
			</tr>
		</table>
		</form>
<?php
}

function rename_go() {
	global $list, $options, $L;
	$smthExists = FALSE;
	for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
		$file = $list [$_POST ["files"] [$i]];
		
		if (file_exists ( $file ["name"] )) {
			$smthExists = TRUE;
			$newName = dirname ( $file ["name"] ) . PATH_SPLITTER . stripslashes(basename($_POST["newName"][$i]));
			$rest = substr($newName, -1);
			
			if(!preg_match("/^[a-zA-Z0-9_]+$/i", $rest)) { $alpnum = false;}else{$alpnum = true;}
			while(!$alpnum and (strlen($newName)>0))
			{
				$newName = substr($newName, 0, -1);
				$rest = substr($newName, -1);
				if(!preg_match("/^[a-zA-Z0-9_]+$/i", $rest)) { $alpnum = false;}else{$alpnum = true;}
			}
			$filetype = strrchr ( $newName, "." );
			
			if (is_array ( $options['forbidden_filetypes'] ) && in_array ( strtolower ( $filetype ), $options['forbidden_filetypes'] )) {
				echo $L->sprintf($L->say['forbidden_rename'],$filetype)."<br /><br />";
			} else {
				if (@rename ( $file ["name"], $newName )) {
					echo $L->sprintf($L->say['_renamed_to'],htmlspecialchars($file["name"]),htmlspecialchars(basename($newName)))."<br /><br />";
					$list [$_POST ["files"] [$i]] ["name"] = $newName;
				} else {
					echo $L->sprintf($L->say['couldnt_rename_to'],$file['name'])."<br /><br />";
				}
			}
		} else {
			echo $L->sprintf($L->say['not_found'],$file['name'])."<br /><br />";
		}
	}
	if ($smthExists) {
		if (! updateListInFile ( $list )) {
			echo $L->say['couldnt_upd']."<br /><br />";
		}
	}
}
?>