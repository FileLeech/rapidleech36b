<?php
function rl_pack() {
	global $list, $options, $PHP_SELF, $L;
?>
<form method="post" action="<?php echo ROOT_URL.basename($PHP_SELF); ?>"><input type="hidden" name="act" value="pack_go" />
<?php
	echo count ( $_GET ["files"] ) . " file" . (count ( $_GET ["files"] ) > 1 ? "s" : "") . ":<br />";
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [$_GET ["files"] [$i]];
?>
<input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>" />
<b><?php echo basename ( $file ["name"] ); ?></b><?php echo $i == count ( $_GET ["files"] ) - 1 ? "." : ",&nbsp;"; }?><br />
	<br />
	<table align="center">
		<tr>
			<td><?php echo $L->say['_arcv_name']; ?>:&nbsp;<input type="text" name="arc_name" size="30" value="" />&nbsp;<b>.</b>&nbsp;
				<select name="arc_ext">
				<option value="tar" selected="selected">tar</option>
<?php
			if (!$options['disable_archive_compression']) {
?>
				<option value="tar.gz">tar.gz</option>
				<option value="tar.bz">tar.bz</option>
<?php
			}
?>
				</select>
			</td>
			<td><input type="submit" value="Pack" /></td>
		</tr>
<?php
  if ($options['download_dir_is_changeable']) {
?>
		<tr>
			<td><?php echo $L->say['saveto']; ?>:&nbsp;<input type="text" name="saveTo" size="30" value="<?php echo addslashes ( $options['download_dir'] ); ?>" /></td>
		</tr>
<?php
  }
?>
	</table>
</form>
<?php
}

function pack_go() {
	global $list, $options, $L;
	$arc_name = basename($_POST["arc_name"].'.'.$_POST["arc_ext"]);	
	$saveTo = ($options['download_dir_is_changeable'] ? stripslashes ( $_POST ["saveTo"] [$i] ) : realpath ( $options['download_dir'] )) . '/';
	$v_list = array();
	if (!$_POST["arc_name"] || !$_POST["arc_ext"]) {
		echo $L->say['enter_arc_name']."<br /><br />";
	}
	elseif (file_exists ( $saveTo . $arc_name )) {
		echo $L->sprintf($L->say['err_output_file_exist'],$arc_name)."<br /><br />";
	}
	else {
		for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
			$file = $list [$_POST ["files"] [$i]];
			if (file_exists ( $file ["name"] )) {
			$v_list [] = $file ["name"];
		} else {
			echo $L->sprintf($L->say['not_found'],$file['name'])."<br /><br />";
		}
	}
	if (count($v_list) < 1) {
		echo "<b class=\"r\">".$L->say['error_occur']."</b><br /><br />";
	}
	else {
		$arc_name = $saveTo.$arc_name;
		require_once (CLASS_DIR . "tar.php");
		$tar = new Archive_Tar ( $arc_name );
		if ($tar->error != '') { echo $tar->error."<br /><br />"; }
		else {
			$remove_path = realpath($options['download_dir']).'/';
			$tar->createModify($v_list, '', $remove_path);
			if (! file_exists ( $arc_name )) {
			echo "<b class=\"r\">".$L->say['_error']."</b> ".$L->say['arcv_not_created']."<br /><br />";
		}
		else {
			if (count ( $v_list = $tar->listContent () ) > 0) {
				for($i = 0; $i < sizeof ( $v_list ); $i ++) {
				echo $L->sprintf($L->say['was_pack'],$v_list[$i]['filename'])." <br />";
			}
			echo $L->sprintf($L->say['pack_in_arcv'],$arc_name)."<br />";
			$stmp = strtolower ( $arc_name );
			$arc_method = "Tar";
			if (!$options['disable_archive_compression']) {
				if (strrchr ( $stmp, "tar.gz" ) + 5 == strlen ( $stmp )) { $arc_method = "Tar.gz"; }
				elseif (strrchr ( $stmp, "tar.bz2" ) + 6 == strlen ( $stmp )) { $arc_method = "Tar.bz2"; }
			}
			unset ( $stmp );
			$time = explode ( " ", microtime () );
			$time = str_replace ( "0.", $time [1], $time [0] );
			$list [$time] = array ("name" => $arc_name, "size" => bytesToKbOrMbOrGb ( filesize ( $arc_name ) ), "date" => $time, "link" => "", "comment" => "archive " . $arc_method );
		} else {
			echo "<b class=\"r\">".$L->say['_error']."</b> ".$L->say['arcv_empty']."<br /><br />";
		}
		if (! updateListInFile ( $list )) {
			echo $L->say['couldnt_upd'].'<br /><br />';
		}
        }
      }
    }
  }
}
?>