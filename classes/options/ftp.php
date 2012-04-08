<?php
function ftp() {
	global $list, $options, $PHP_SELF, $L;
?>
<form method="post" action="<?php echo ROOT_URL.basename($PHP_SELF); ?>"><input type="hidden" name="act" value="ftp_go" />
<?php echo $L->say['_file']; ?>:
<?php
	for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
		$file = $list [($_GET ["files"] [$i])];
?>
<input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>" /> <b><?php echo basename ( $file ["name"] ); ?></b><?php echo $i == count ( $_GET ["files"] ) - 1 ? "." : ",&nbsp;"; ?>
<?php
	}
?><br />
		<br />
		<table align="center" style="text-align: left;">
			<tr>
				<td>
				<table>
					<tr>
						<td><?php echo $L->say['_host']; ?>:</td>
						<td><input type="text" name="host" id="host" <?php echo $_COOKIE ["host"] ? ' value="' . $_COOKIE ["host"] . '"' : ''; ?>
							size="23" /></td>
					</tr>
					<tr>
						<td><?php echo $L->say['_port']; ?>:</td>
						<td><input type="text" name="port" id="port" <?php echo $_COOKIE ["port"] ? ' value="' . $_COOKIE ["port"] . '"' : ' value="21"'; ?>
							size="4" /></td>
					</tr>
					<tr>
						<td><?php echo $L->say['acc_username']; ?>:</td>
						<td><input type="text" name="login" id="login" <?php echo $_COOKIE ["login"] ? ' value="' . $_COOKIE ["login"] . '"' : ''; ?>
							size="23" /></td>
					</tr>
					<tr>
						<td><?php echo $L->say['acc_password']; ?>:</td>
						<td><input type="password" name="password" id="password" <?php echo $_COOKIE ["password"] ? ' value="' . $_COOKIE ["password"] . '"' : ''; ?>
							size="23" /></td>
					</tr>
					<tr>
						<td><?php echo $L->say['_directory']; ?>:</td>
						<td><input type="text" name="dir" id="dir" <?php echo $_COOKIE ["dir"] ? ' value="' . $_COOKIE ["dir"] . '"' : ' value="/"'; ?>
							size="23" /></td>
					</tr>
					<tr>
						<td><input type="checkbox" name="del_ok" <?php if ($options['disable_to']['act_del']) echo 'disabled="disabled"'; ?> />&nbsp;<?php echo $L->say['del_source_aft_upl']; ?></td>
					</tr>
				</table>
				</td>
				<td>&nbsp;</td>
				<td>
				<table>
					<tr align="center">
						<td><input type="submit" value="Upload" /></td>
					</tr>
					<tr align="center">
						<td><?php echo $L->say['_opt']; ?></td>
					</tr>
					<tr align="center">
						<td><a href="javascript:setFtpParams();" id="hrefSetFtpParams"><?php echo $L->say['_saveftp']; ?></a> | <a href="javascript:delFtpParams();" id="hrefDelFtpParams"><?php echo $L->say['_delftp']; ?></a></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</form>
<?php
}

function ftp_go() {
	global $list, $options, $L;
	require_once (CLASS_DIR . "ftp.php");
	$ftp = new ftp ( );
	if (! $ftp->SetServer ( $_POST ["host"], ( int ) $_POST ["port"] )) {
		$ftp->quit ();
		echo $L->sprintf($L->say['couldnt_establish_con'],$_POST ["host"] . ":" . $_POST ["port"]);
		echo '<br /><a href="javascript:history.back(-1);">'.$L->say['_back'].'</a><br /><br />';
	} else {
		if (! $ftp->connect ()) {
			$ftp->quit ();
			echo "<br />";
			echo $L->sprintf($L->say['couldnt_establish_con'],$_POST ["host"] . ":" . $_POST ["port"]);
			echo '<br /><a href="javascript:history.back(-1);">'.$L->say['_back'].'</a><br /><br />';
		} else {
			$L->sprintf($L->say['_con'],'ftp://'.$_POST['host'].':'.$_POST['port']);
			if (! $ftp->login ( $_POST ["login"], $_POST ["password"] )) {
				$ftp->quit ();
				echo "<br />";
				echo $L->say['incorrect_userpass'];
				echo '<br /><a href="javascript:history.back(-1);">'.$L->say['_back'].'</a><br /><br />';
			} else {
				//$ftp->Passive(FALSE);
				if (! $ftp->chdir ( $_POST ["dir"] )) {
					$ftp->quit ();
					echo "<br />";
					echo $L->sprintf($L->say['_cant_locatefold'],$_POST['dir']);
					echo '<br /><a href="javascript:history.back(-1);">'.$L->say['_back'].'</a><br /><br />';
				} else {
?>
<br />
				<div id="status"></div>
				<br />
				<table cellspacing="0" cellpadding="0">
					<tr>
						<td></td>
						<td>
						<div class="progressouter">
						<div style="width:298px">
							<div id="progress" class="ftpprogress"></div>
						</div>
						</div>
						</td>
						<td></td>
					</tr>
					<tr>
						<td align="left" id="received">0 KB</td>
						<td align="center" id="percent">0%</td>
						<td align="right" id="speed">0 KB/s</td>
					</tr>
				</table>
				<br />
<?php
					for($i = 0; $i < count ( $_POST ["files"] ); $i ++) {
						$file = $list [$_POST ["files"] [$i]];
						echo '<script type="text/javascript">changeStatus('."'" . addslashes(basename ( $file ["name"] )) . "', '" . $file ["size"] . "');</script>";
						$FtpBytesTotal = filesize ( $file ["name"] );
						$FtpTimeStart = getmicrotime ();
						if ($ftp->put ( $file ["name"], basename ( $file ["name"] ) )) {
							$time = round ( getmicrotime () - $FtpTimeStart );
							$speed = @round ( $FtpBytesTotal / 1024 / $time, 2 );
							echo '<script type="text/javascript">pr(100, '."'" . bytesToKbOrMbOrGb ( $FtpBytesTotal ) . "', " . $speed . ")</script>\r\n";
							flush ();
							
							if ($_POST["del_ok"] && !$options['disable_to']['act_del']) {
								if (@unlink ( $file ["name"] )) {
									unset ( $list [$_POST ["files"] [$i]] );
								}
							}
							
								echo $L->sprintf($L->say['_successupl'],'<a href="ftp://' . $_POST ["login"] . ':' . $_POST ["password"] . '@' . $_POST ["host"] . ':' . $_POST ["port"] . $_POST ["dir"] . '/' . basename ( $file ["name"] ) . '"><b>' . basename ( $file ["name"] ) . '</b></a>');
								echo "<br />".$L->say['_time'].": <b>" . sec2time ( $time ) . "</b><br />".$L->say['_avg_spd'].": <b>" . $speed . " KB/s</b><br /><br />";
						} else {
							echo $L->sprintf($L->say['couldnt_upl_file'],basename($file['name']))."<br />";
						}
					}
					$ftp->quit ();
				}
			}
		}
	
	}
}
?>