<?php
function boxes() {
	global $list, $options, $PHP_SELF, $L;
?>
<form method="post" action="<?php echo $PHP_SELF;?>"><input type="hidden" name="act" value="boxes_go" />

<?php
	echo count ( $_GET ["files"] ) . " file" . (count ( $_GET ["files"] ) > 1 ? "s" : "") . ":<br />";
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {

		$file = $list [($_GET ["files"] [$i])];

?>
	<input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>" /> <b><?php echo basename ( $file ["name"] ); ?></b><?php echo $i == count ( $_GET ["files"] ) - 1 ? "." : ",&nbsp"; ?>
<?php
	}
?><br />
<br />
<table align="center">
	<tr>
		<td width="20" align="right"><?php echo $L->say['_emails']; ?>:&nbsp;</td>
		<td>
		<textarea name="emails" cols="30" rows="5" class="redtxtarea"><?php if ($_COOKIE ["email"]) echo $_COOKIE ["email"];?></textarea>
		</td>
	<td><input type="submit" value="<?php echo $L->say['_send']; ?>" /></td>
	</tr>
	<tr>
	<td></td>
		<td><input type="checkbox" name="del_ok" <?php if (!$options['disable_to']['act_del']) echo 'checked="checked"'; ?> <?php if ($options['disable_to']['act_del']) echo 'disabled="disabled"'; ?> />&nbsp;<?php echo $L->say['del_success']; ?></td>
	<td></td>
	</tr>
	</table>
	
		<table>
			<tr>
				<td><input id="splitchkbox" type="checkbox" name="split" onclick="javascript:var displ=this.checked?'':'none';document.getElementById('methodtd2').style.display=displ;"<?php echo $_COOKIE ["split"] ? ' checked="checked"' : ''; ?> />&nbsp;<?php echo $L->say['split_part']; ?></td>
				<td>&nbsp;</td>
				<td id="methodtd2"
					<?php echo $_COOKIE ["split"] ? '' : ' style="display: none;"'; ?>>
				<table>
					<tr>
						<td><?php echo $L->say['_method'];?>:&nbsp;<select name="method">
							<option value="tc" <?php echo $_COOKIE ["method"] == "tc" ? ' selected="selected"' : ''; ?>><?php echo $L->say['tot_com']; ?></option>
							<option value="rfc" <?php echo $_COOKIE ["method"] == "rfc" ? ' selected="selected"' : ''; ?>><?php echo $L->say['rfc']; ?></option>
						</select></td>
					</tr>
					<tr>
						<td><?php echo $L->say['part_size']; ?>:&nbsp;<input type="text" name="partSize" size="2" value="<?php echo ($_COOKIE ["partSize"] ? $_COOKIE ["partSize"] : 10); ?>" />&nbsp;MB
						</td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</form>
<?php
}

function boxes_go() {
	global $list, $options, $fromaddr, $L;
	require_once (CLASS_DIR . "mail.php");
	$_POST ["partSize"] = ((isset ( $_POST ["partSize"] ) & $_POST ["split"] == "on") ? $_POST ["partSize"] * 1024 * 1024 : FALSE);
	$v_mails = explode ( "\n", $_POST['emails'] );
	$v_min = count ( (count ( $_POST ["files"] ) < count ( $v_mails )) ? $_POST ["files"] : $v_mails );
	
	for($i = 0; $i < $v_min; $i ++) {
		$file = $list [$_POST ["files"] [$i]];
		$v_mail = trim ( $v_mails [$i] );
		if (! checkmail ( $v_mail )) {
			echo $L->sprintf($L->say['invalid_email_addr'],$v_mail)."<br /><br />";
		} elseif (file_exists ( $file ["name"] )) {
			if (xmail ( "$fromaddr", $v_mail, "File " . basename ( $file ["name"] ), "File: " . basename ( $file ["name"] ) . "\r\n" . "Link: " . $file ["link"] . ($file ["comment"] ? "\r\nComments: " . str_replace ( "\\r\\n", "\r\n", $file ["comment"] ) : ""), $file ["name"], $_POST ["partSize"], $_POST ["method"] )) {
				if ($_POST["del_ok"] && !$options['disable_to']['act_del']) {
					if (@unlink ( $file ["name"] )) {
						$v_ads = " ".$L->says['_and_del'];
						unset ( $list [$_POST ["files"] [$i]] );
					} else {
						$v_ads = ", ".$L->say['_but']." <b>".$L->say['_not_del']."</b>";
					}
					;
				} else
					$v_ads = " !";
				echo '<script type="text/javascript">'."mail('File <b>" . basename ( $file ["name"] ) . "</b> it is sent for the address <b>" . $v_mail . "</b>" . $v_ads . "', '" . md5 ( basename ( $file ["name"] ) ) . "');</script>\r\n<br />";
			} else {
				echo "<b class=\"r\">".$L->say['error_send']."</b><br />";
			}
		} else {
			echo $L->sprintf($L->say['not_found'],$file['name'])."<br /><br />";
		}
	}
	if (count ( $_POST ["files"] ) < count ( $v_mails )) {
		for($i = count ( $_POST ["files"] ); $i < count ( $v_mails ); $i ++) {
			$v_mail = trim ( $v_mails [$i] );
			echo "$v_mail.</b><br /><br />";
		}
		echo "</b><br />";
	}
	elseif (count ( $_POST ["files"] ) > count ( $v_mails )) {
		for($i = count ( $v_mails ); $i < count ( $_POST ["files"] ); $i ++) {
			$file = $list [$_POST ["files"] [$i]];
			if (file_exists ( $file ["name"] )) {
				echo $file ["name"] . "<br /><br />";
			} else {
				echo $L->sprintf($L->say['not_found'],$file['name'])."<br /><br />";
			}
		}
		echo "<br />";
	}
	if ($_POST ["del_ok"]) {
		if (!updateListInFile ( $list )) {
			echo $L->say['error_upd_list']."<br /><br />";
		}
	}
}
?>