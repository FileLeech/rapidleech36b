<?php

function unrar() {
  global $PHP_SELF, $txt, $optxt, $options, $list;
	if (count ( $_GET ["files"] ) < 1) {
		echo $optxt['select_one_file']."<br><br>";
	} else {
  require_once(CLASS_DIR."rar.php");
?>
<script type="text/javascript">
/* <![CDATA[ */
function unrar_setCheckboxes(act, filestounrar) {
  var elts = document.getElementsByName('filestounrar['+filestounrar+'][]');
  var elts_cnt = (typeof(elts) != 'undefined') ? elts.length : 0;
  if (elts_cnt) {
    for (var i = 0; i < elts_cnt; i++) {
      elts[i].checked = (act == 1 || act == 0) ? act : elts[i].checked ? 0 : 1;
    }
  }
}
/* ]]> */
</script>
  <form name="unrar_files" method="post" action="<?php echo $PHP_SELF; ?>">
    <table align="center">
      <tr>
        <td>
          <table>
<?php
  $rar_passl_needed = false;
  $any_file_tounrar = false;
  for($i = 0; $i < count($_GET["files"]); $i++) {
    $file = $list[$_GET["files"][$i]];
?>
            <tr align="center">
              <td colspan="2" class="rar-table-main">
                <input type="hidden" name="files[<?php echo $i; ?>]" value="<?php echo $_GET["files"][$i]; ?>" />
                <?php printf($optxt['_filefr'],htmlentities(basename($file["name"]))); ?>
                <br />
                <a href="javascript:void(0);" onclick="unrar_setCheckboxes(1, <?php echo $i;?>);"><?php echo $txt['chk_all']; ?></a> |
                <a href="javascript:void(0);" onclick="unrar_setCheckboxes(0, <?php echo $i;?>);"><?php echo $txt['chk_unchk']; ?></a> |
                <a href="javascript:void(0);" onclick="unrar_setCheckboxes(2, <?php echo $i;?>);"><?php echo $txt['chk_invert']; ?></a>
              </td>
            </tr>
            <tr>					 
              <td colspan="2" class="rar-table-side">
                &nbsp;
<?php
    unset ($rar);
    $rar = new rlRar($file["name"], $options['check_these_before_unzipping'] ? $options['forbidden_filetypes'] : array('.xxx'));
    if ($rar->rar_return === false) { echo $optxt['_unrar']; }
    else {
      $rar_list = $rar->listthem(@$_GET['passwords'][$i], $options['download_dir'], $i);
      if ($rar_list[0] == 'PASS') { $rar_passl_needed = true; echo $optxt['_passlist']; }
      elseif ($rar_list['NEEDP'] == true) { echo $optxt['_passext']; }
      elseif ($rar_list[0] == 'ERROR') { printf($optxt['_unrarerr'],$rar_list[1].' '.$rar_list[2]); }
    }
?>
                <input type="<?php echo ($rar_list['NEEDP'] == true) ? 'password' : 'hidden'; ?>" name="passwords[]" value="<?php echo $_GET['passwords'][$i]; ?>" />
                &nbsp;
              </td>
            </tr>
<?php
    if ($rar_list[0] == 'LIST') {
      $any_file_tounrar = true;
      $rar_list = $rar_list[2];
      foreach($rar_list as $rar_key => $rar_item) {
?>
            <tr>
              <td class="rar-table-left">
                <input type="checkbox" name="filestounrar[<?php echo $i; ?>][]" checked="checked" value="<?php echo base64_encode($rar_key); ?>" />
              </td>
              <td class="rar-table-right"><?php echo $rar_key.' ('.bytesToKbOrMbOrGb($rar_item['size']).')'; ?></td>
            </tr>
<?php
      }
    }
?>
            <tr>
              <td colspan="2" class="rar-table-top" style="border-top:1px solid #666;">&nbsp;</td>
            </tr>
<?php
  }
?>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type="hidden" name="act" value="<?php echo $rar_passl_needed ? 'unrar' : 'unrar_go'; ?>" />
          <input type="submit" value="<?php echo $rar_passl_needed ? $optxt['_trylist'] : $optxt['_unrarsel']; ?>" />
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




function unrar_go() {
  global $PHP_SELF, $optxt, $options, $list;
	if (count ( $_GET ["files"] ) < 1) {
		echo $optxt['select_one_file']."<br><br>";
	} else {
  require_once(CLASS_DIR."rar.php");
?>
  <table align="center">
    <tr>
      <td>
        <table>
<?php
    $rar_dld_in_webdir = false;
    if (dirname($PHP_SELF.'safe') === '/' || substr(ROOT_DIR, 0 - strlen(dirname($PHP_SELF.'safe'))) == dirname($PHP_SELF.'safe')) {
      $rar_tmp = (dirname($PHP_SELF.'safe') === '/' ? ROOT_DIR.'/' : substr(ROOT_DIR, 0, 0 - strlen(dirname($PHP_SELF.'safe'))).'/');
      if (strpos(realpath($options['download_dir']), $rar_tmp) === 0) {
        $rar_dld_in_webdir = true;
        $rar_dld_webpath = '/'.substr(realpath($options['download_dir']), strlen($rar_tmp)).'/';
      }
    }
  for($i = 0; $i < count($_GET["files"]); $i++) {
    $file = $list[$_GET["files"][$i]];
    if (count($_GET['filestounrar'][$i]) == 0) { continue; }
?>
          <tr align="center">
            <td colspan="2" class="rar-table-tlr"><?php printf($optxt['_extracting'],basename($file["name"])); ?></td>
          </tr>
<?php
    foreach ($_GET['filestounrar'][$i] as $rar_item) {
?>
          <tr>
            <td class="rar-tbl-left">
<?php
        echo $rar_dld_in_webdir ? '<a href="'.$rar_dld_webpath.basename(base64_decode($rar_item)).'">' : '';
        echo basename(base64_decode($rar_item));
        echo $rar_dld_in_webdir ? '</a>' : '';
?>
            </td>
            <td clas="rar-tbl-right" id="<?php echo 'unrar'.$_GET["files"][$i].'-'.str_replace('=', '-', $rar_item); ?>" align="center"><?php echo $optxt['_wait']; ?></td>
          </tr>
<?php
    }
?>
          <tr>
            <td colspan="2" class="rar-table-space">&nbsp;</td>
          </tr>
<?php
  }
?>
        </table>
      </td>
    </tr>
    <tr>
      <td>
      </td>
    </tr>
  </table>
  <span id="unrar_finished" style="display:none;"><a href="<?php echo $PHP_SELF."?act=files"; ?>"><?php echo $optxt['_backlist']; ?></a><br /><br /><br /></span>
<?php
}
}




function unrar_go_go() {
  global $optxt, $options, $list;
?>
<script type="text/javascript">
/* <![CDATA[ */
function rar_st(elementid, st){
  document.getElementById(elementid).innerHTML = st;
  return true;
}
/* ]]> */
</script>
<?php
  for($i = 0; $i < count($_GET["files"]); $i++) {
    $file = $list[$_GET["files"][$i]];
    if (count($_GET['filestounrar'][$i]) == 0) { continue; }
    foreach ($_GET['filestounrar'][$i] as $rar_item) {
      flush();
      $rar = new rlRar($file["name"], $options['check_these_before_unzipping'] ? $options['forbidden_filetypes'] : array('.xxx'));
      if ($rar->rar_return === false) {
?>
<script type="text/javascript">rar_st('<?php echo 'unrar'.$_GET["files"][$i].'-'.str_replace('=', '-', $rar_item); ?>', '<?php echo $optxt['rar_disappear']; ?>');</script>
<?php
      }
      else {
        $rar_result = $rar->extract(base64_decode($rar_item), $options['download_dir'], $_GET['passwords'][$i], 'unrar'.$_GET["files"][$i].'-'.str_replace('=', '-', $rar_item), $i);
        echo $rar_result;
        if (strpos($rar_result, ", 'OK')") !== false) {
          _create_list();
          $rar_tolist = realpath($options['download_dir']).'/'.basename(base64_decode($rar_item));
          $time = filemtime($rar_tolist); while (isset($list[$time])) { $time++; }
          $list[$time] = array("name" => $rar_tolist, "size" => bytesToKbOrMbOrGb(filesize($rar_tolist)), "date" => $time);
          if (!updateListInFile($list)) {
?>
<script type="text/javascript">var tmp = document.getElementById('rar_finished'); tmp.innerHTML = "<?php echo $optxt['couldnt_upd_list']; ?><br /><br />" + tmp.innerHTML</script>;
<?php
          }
        }
      }
    }
  }
?>
<script type="text/javascript">document.getElementById('unrar_finished').style.display = '';</script>
<?php
}
?>