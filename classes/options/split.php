<?php
function _split() {
	global $PHP_SELF, $optxt,$list, $options, $disable_deleting;
	if (count ( $_GET ["files"] ) < 1) {
		echo $optxt['select_one_file']."<br><br>";
	} else {
    ?>
    <form method="post">
      <input type="hidden" name="act" value="split_go">
       <table align="center">
        <tr>
          <td>
            <table>
      <?php
        for($i = 0; $i < count($_GET["files"]); $i++)
          {
            $file = $list[$_GET["files"][$i]];
            ?>
              <input type="hidden" name="files[]" value="<?php echo $_GET["files"][$i]; ?>">
                  <tr>
                    <td align="center"><b><?php echo basename($file["name"]); ?></b></td>
                  </tr>
                  <tr>
                    <td>
                      <?php echo $optxt['part_size'];?>:&nbsp;<input type="text" name="partSize[]" size="2" value="<?php echo ($_COOKIE["partSize"] ? $_COOKIE["partSize"] : 10); ?>">&nbsp;MB
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <?php echo $optxt['saveto'];?>:&nbsp;<input type="text" name="saveTo[]" size="40" value="<?php echo addslashes(dirname($file["name"])); ?>">
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <input type="checkbox" name="del_ok" <?php if(!$options['disable_to']["act_del"]) echo "checked"; ?> <?php if($options['disable_to']["act_del"]) echo "disabled"; ?>>&nbsp;<?php echo $optxt['del_source_aft_split'];?>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      CRC32 generation mode:<br>
                      <?php if (function_exists('hash_file')) { ?><input type="radio" name="crc_mode[<?php echo $i; ?>]" value="hash_file" checked>&nbsp;Use hash_file (Recommended)<br><?php } ?>
                      <input type="radio" name="crc_mode[<?php echo $i; ?>]" value="file_read">&nbsp;Read file to memory<br>
                      <input type="radio" name="crc_mode[<?php echo $i; ?>]" value="fake" <?php if (!function_exists('hash_file')) { echo 'checked'; } ?>>&nbsp;Fake crc
                    </td>
                  </tr>
                  <tr>
                    <td>
                    </td>
                  </tr>
			<?php
          }
      ?>
            </table>
          </td>
          <td>
            <input type="submit" value="Split">
          </td>
        </tr>
        <tr>
          <td>
          </td>
        </tr>
      </table>
    </form>
    <?php
  }
}

function split_go() {
	global $list, $optxt,$options;
    for($i = 0; $i < count($_GET["files"]); $i++) {
      $split_ok = true;
      $file = $list[$_GET["files"][$i]];
      $partSize = round(($_GET["partSize"][$i]) * 1024 * 1024);
      $saveTo = ($options['download_dir_is_changeable'] ? stripslashes($_GET["saveTo"][$i]) : realpath($options['download_dir'])).'/';
      $dest_name = basename($file["name"]);
      $fileSize = filesize($file["name"]);
      $totalParts = ceil($fileSize / $partSize);
      $crc = ($_GET['crc_mode'][$i] == 'file_read') ? dechex(crc32(read_file($file["name"]))) : 
      (($_GET['crc_mode'][$i] == 'hash_file' && function_exists('hash_file')) ? hash_file('crc32b', $file["name"]) : '111111');
      $crc = str_repeat("0", 8 - strlen($crc)).strtoupper($crc);
      echo $optxt["start_split"]." <b>".basename($file["name"])."</b> ".$optxt["part_of"]." ".bytesToKbOrMbOrGb($partSize).", ".$optxt["use_method"]." - Total Commander...<br>";
      echo $optxt["tot_part"].": <b>".$totalParts."</b><br><br>";
      for($j = 1; $j <= $totalParts; $j++) {
        if (file_exists($saveTo.$dest_name.'.'.sprintf("%03d", $j))) {
          echo $optxt["split_error"]." ".$optxt["piece_exist"]."<b>".$dest_name.'.'.sprintf("%03d", $j)."</b> !<br><br>";
          continue 2;
        }
      }
      if (file_exists($saveTo.$dest_name.'.crc')) {
        echo $optxt["split_error"]." ".$optxt["crc_exist"]."<b>".$dest_name.'.crc'."</b> !<br><br>";
      }
      elseif (!is_file($file["name"])) {
        echo $optxt["split_error"]." ".$optxt["src_notfound"]."<b>".$file["name"]."</b> !<br><br>";                              
      }
      elseif (!is_dir($saveTo)) {
        echo $optxt["split_error"]." ".$optxt["dir_inexist"]."<b>".$saveTo."</b> !<br><br>";
      }
      elseif(!@write_file($saveTo.$dest_name.".crc", "filename=".$dest_name."\r\n"."size=".$fileSize."\r\n"."crc32=".$crc."\r\n")) {
        echo $optxt["split_error"]." CRC Error<b>".$dest_name.".crc"."</b> !<br><br>";
      }
      else {
        $time = filectime($saveTo.$dest_name.'.crc'); while (isset($list[$time])) { $time++; }
        $list[$time] = array("name" => $saveTo.$dest_name.'.crc',
          "size" => bytesToKbOrMbOrGb(filesize($saveTo.$dest_name.'.crc')), "date" => $time);
        $split_buffer_size = 2 * 1024 * 1024;
        $split_source = @fopen($file["name"], "rb");
        if (!$split_source) {
          echo $optxt["crc_error_open"]." <b>".$file["name"]."</b> !<br><br>";
          continue;
        }
        for($j = 1; $j <= $totalParts; $j++) {
          $split_dest = @fopen($saveTo.$dest_name.'.'.sprintf("%03d", $j), "wb");
          if (!$split_dest) {
            echo $optxt["error_open_file"]." <b>".$dest_name.'.'.sprintf("%03d", $j)."</b> !<br><br>";
            $split_ok = false;
            break;
          }
          $split_write_times = floor($partSize / $split_buffer_size);
          for($k = 0; $k < $split_write_times; $k++) {
            $split_buffer = fread($split_source, $split_buffer_size);
            if (fwrite($split_dest, $split_buffer) === false) {
              echo $optxt["error_write_file"]." <b>".$dest_name.'.'.sprintf("%03d", $j)."</b> !<br><br>";
              $split_ok = false;
              break;
            }
          }
          $split_rest = $partSize - ($split_write_times * $split_buffer_size);
          if ($split_ok && $split_rest > 0) {
            $split_buffer = fread($split_source, $split_rest);
            if (fwrite($split_dest, $split_buffer) === false) {
              echo $optxt["error_write_file"]." <b>".$dest_name.'.'.sprintf("%03d", $j)."</b> !<br><br>";
              $split_ok = false;
            }
          }
          fclose($split_dest);
          if ($split_ok) {
            $time = filectime($saveTo.$dest_name.'.'.sprintf("%03d", $j)); while (isset($list[$time])) { $time++; }
            $list[$time] = array("name" => $saveTo.$dest_name.'.'.sprintf("%03d", $j),
              "size" => bytesToKbOrMbOrGb(filesize($saveTo.$dest_name.'.'.sprintf("%03d", $j))), "date" => $time);
          }
        }
        fclose($split_source);
        if ($split_ok) {
          if ($_GET["del_ok"] && !$options['disable_to']["act_del"]) {
            if(@unlink($file["name"])) {
              unset($list[$_GET["files"][$i]]);
              echo $optxt["source_del"]."<br><br>";
            }
            else { echo $optxt["source_file_is"]."<b>".$optxt["_not_del"]."</b><br><br>"; }
          }
        }
        if (!updateListInFile($list)) { echo $optxt["couldnt_upd_list"]."<br><br>"; }
      }
    }
}
?>