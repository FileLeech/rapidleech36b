<?php
function delete() {
	global $optxt, $disable_deleting,$list;
	if (count ( $_GET ["files"] ) < 1) {
			echo $optxt['select_one_file']."<br><br>";
		} elseif ($disable_deleting) {
			echo $optxt['del_disabled'];
		} else {
				?>
<form method="post"><input type="hidden" name="act" value="delete_go">
                              File<?php
			echo count ( $_GET ["files"] ) > 1 ? "s" : "";
				?>:
                              <?php
			for($i = 0; $i < count ( $_GET ["files"] ); $i ++) {
				$file = $list [$_GET ["files"] [$i]];
					?>
          <input type="hidden" name="files[]" value="<?php echo $_GET ["files"] [$i]; ?>">
		  <b><?php echo htmlspecialchars(basename ( $file ["name"] )); ?></b><?php echo $i == count ( $_GET ["files"] ) - 1 ? "." : ",&nbsp"; ?>
<?php
			}
?><br>Delete<?php echo count ( $_GET ["files"] ) > 1 ? " These Files" : " This File"; ?>?<br>
<table>
	<tr>
		<td><input type="submit" name="yes" style="width: 33px; height: 23px"
			value="Yes"></td>
		<td>&nbsp;&nbsp;&nbsp;</td>
		<td><input type="submit" name="no" style="width: 33px; height: 23px"
			value="No"></td>
	</tr>
</table>
</form>
<?php
	}
}

function delete_go() {
	global $optxt, $list;
	if ($_GET ["yes"])                           
	{
      for($i = 0; $i < count($_GET["files"]); $i++)
        {
          $file = $list[$_GET["files"][$i]];
          if(file_exists($file["name"]))
            {
              if(@unlink($file["name"]))
                {
                  echo $optxt['_file']." <b>".htmlspecialchars($file["name"])."</b> ".$optxt['_deleted']."<br><br>";
                  unset($list[$_GET["files"][$i]]);
                }
              else
                {
                  echo "<b class=\"r\">".$optxt['error_delete']."</b> the file <b>".htmlspecialchars($file["name"])."</b>!<br><br>";
                }
            }
          else
            {
              echo $optxt['_file']." <b>".htmlspecialchars($file["name"])."</b> <b class=\"r\">".$optxt['not_found']."</b><br><br>";
            }
        }
      if(!updateListInFile($list))
        {
            echo "<b class=\"r\">".$optxt['error_upd_list']."</b><br><br>";
        }
    }
  else
    {
      ?>
        <script  type="text/javascript">
          location.href="<?php echo $PHP_SELF."?act=files"; ?>";
        </script>
      <?php
    }  
}
?>