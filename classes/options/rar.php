<?php

function rar() {
  global $PHP_SELF, $optxt, $list;
  if (!is_file(ROOT_DIR.'/rar/rar')) { echo $optxt['rar_disappear'].'<br /><br />'; }
  else {
?>
  <form name="rar_files" method="post" action="<?php echo $PHP_SELF; ?>">
    <table align="center" class="rar-table">
      <tr>
        <td>
          <table>
            <tr>
              <td colspan="2" class="rar-main-td">
                <b><?php echo $optxt['_filearcv']; ?></b>
              </td>
            </tr>
<?php
    for($i = 0; $i < count($_GET["files"]); $i++) {
      $file = $list[$_GET["files"][$i]];
?>
            <tr>
              <td class="rar-options-left-td">
                <input type="hidden" name="files[<?php echo $i; ?>]" value="<?php echo $_GET["files"][$i]; ?>" />
                <input type="hidden" name="rar_opts[filestorar][<?php echo $i; ?>]" value="<?php echo $_GET["files"][$i]; ?>" />
                <?php echo basename($file["name"]); ?>
              </td>
              <td class="rar-rb-td">
                <?php echo $file["size"]; ?>
              </td>
            </tr>
<?php
    }
?>
            <tr>
              <td colspan="2" class="rar-main-td">
                <b><?php echo $optxt['_namearcv']; ?></b>
              </td>
            </tr>
            <tr>
              <td colspan="2" class="rar-main-td">
                <input onkeyup="javascript:this.value=this.value.replace(/[^a-z0-9\ \.\-\_]/gi,'_');" type="text" size="60" name="rar_opts[rarfilename]" value="<?php echo count($_GET["files"]) == 1 ? preg_replace("/[^a-z0-9\\040\\.\\-\\_]/i", '_', basename($file["name"])) : '' ; ?>" />.rar
              </td>
            </tr>
            <tr>
              <td colspan="2" class="rar-main-td">
                <b><?php echo $optxt['_raropt']; ?></b>
              </td>
            </tr>
            <tr>
              <td class="rar-options-left-td">
                <?php echo $optxt['_rarcpr']; ?>
                <select name="rar_opts[comp_lvl]">
                  <option value="0" selected="selected"><?php echo $optxt['_store']; ?></option>
<?php
      if (!$options['disable_archive_compression']) {
?>
                  <option value="1"><?php echo $optxt['_fastest']; ?></option>
                  <option value="2"><?php echo $optxt['_fast']; ?></option>
                  <option value="3"><?php echo $optxt['_normal']; ?></option>
                  <option value="4"><?php echo $optxt['_good']; ?></option>
                  <option value="5"><?php echo $optxt['_best']; ?></option>
<?php
      }
?>
                </select>
              </td>
              <td class="rar-options-right-td">
                <input type="checkbox" name="rar_opts[vols]" value="1" onclick="javascript:var displ=this.checked?'inline':'none';document.getElementById('rar_opts_vols').style.display=displ;var fc=document.getElementsByName('rar_opts[vols_s]')[0]; fc.focus(); fc.selectionStart = 0; fc.selectionEnd = fc.value.length;" /><?php echo $optxt['_volumes']; ?>
                <span id="rar_opts_vols" style="display:none">
                  <br />Size: <input type="text" size="3" name="rar_opts[vols_s]" value="1" />&nbsp;
                  <select name="rar_opts[vols_sm]">
                  	<option value="0">bytes</option>
                  	<option value="1">kilobytes(*1024)</option>
                  	<option value="2">Kilobytes(*1000)</option>
                  	<option value="3" selected="selected">megabytes(*1024)</option>
                  	<option value="4">Megabytes(*1000)</option>
                  	<option value="5">gigabytes(*1024)</option>
                  	<option value="6">Gigabytes(*1000)</option>
                  </select>
                </span>
              </td>
            </tr>
            <tr>
              <td class="rar-options-left-td">
                <input type="checkbox" name="rar_opts[delete]" value="1"<?php echo ($options['disable_deleting'] ? ' disabled="disabled"' : '');?> /><?php echo $optxt['_delarcv']; ?>
              </td>
              <td class="rar-options-right-td">
                <input type="checkbox" name="rar_opts[solid]" value="1" /><?php echo $optxt['_solarcv']; ?>
              </td>
            </tr>
            <tr>
              <td class="rar-options-left-td">
                <input type="checkbox" name="rar_opts[rec_rec]" value="1" onclick="javascript:var displ=this.checked?'inline':'none';document.getElementById('rar_opts_rec_rec').style.display=displ; var fc=document.getElementsByName('rar_opts[rec_rec_s]')[0]; fc.focus(); fc.selectionStart = 0; fc.selectionEnd = fc.value.length;" /><?php echo $optxt['_recarcv']; ?>
                <span id="rar_opts_rec_rec" style="display:none">
                  <br />From 1 to 10: <input type="text" size="3" name="rar_opts[rec_rec_s]" value="1" />%
                </span>
              </td>
              <td class="rar-options-right-td">
                <input type="checkbox" name="rar_opts[test]" value="1" /><?php echo $optxt['_testarcv']; ?>
              </td>
            </tr>
            <tr>
              <td class="rar-options-left-td">
                <input type="checkbox" name="rar_opts[use_pass1]" value="1" onclick="javascript:var displ=this.checked?'inline':'none';document.getElementById('rar_opts_pass').style.display=displ;document.getElementsByName('rar_opts[pass]')[0].focus();" /><?php echo $optxt['_pasarcv']; ?>
                <span id="rar_opts_pass" style="display:none">
                  <br />
                  <input type="password" size="15" name="rar_opts[pass]" value="" />
                  <br />
                  <input type="checkbox" name="rar_opts[use_pass2]" value="1" /><?php echo $optxt['_encarcv']; ?>
                </span>
              </td>
              <td class="rar-options-right-td">
                <input type="checkbox" name="rar_opts[path_i]" value="1" onclick="javascript:var displ=this.checked?'inline':'none';document.getElementById('rar_opts_path_i').style.display=displ;document.getElementsByName('rar_opts[path_i_path]')[0].focus();" /><?php echo $optxt['_patharcv']; ?>
                <span id="rar_opts_path_i" style="display:none;"><br /><input type="text" size="15" name="rar_opts[path_i_path]" value="" /></span>
              </td>
            </tr>
            <tr>
              <td class="rar-options-left-td">
                <input type="checkbox" name="rar_opts[separated]" value="1" /><?php echo $optxt['_separcv']; ?>
              </td>
              <td class="rar-options-right-td">
                &nbsp;
              </td>
            </tr>
            <tr>
              <td colspan="2" class="rar-options-right-td">
                &nbsp;
              </td>
            </tr>
            <tr>
              <td colspan="2" class="rar-space-td">&nbsp;</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td align="center">
          <input type="hidden" name="act" value="rar_go" />
          <input type="submit" value="<?php echo $optxt['_rar']; ?>" />
        </td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
    </table>
  </form>
<?php
  }
}



function rar_go() {
  global $PHP_SELF, $optxt, $list;
  require_once(CLASS_DIR."rar.php");
?>
  <table align="center" style="text-align: left;">
    <tr>
      <td>
<?php
  if ($_GET['rar_opts']['separated']) {
    foreach ($_GET['rar_opts']['filestorar'] as $k => $v) { if (empty($list[$v])) { unset($_GET['rar_opts']['filestorar'][$k]); } }
    $am = count($_GET['rar_opts']['filestorar']);
  }
  else {
    $_GET['rar_opts']['rarfilename'] = trim(basename(stripslashes($_GET['rar_opts']['rarfilename'])));
    if (substr(strtolower($_GET['rar_opts']['rarfilename']), -4) != '.rar') { $_GET['rar_opts']['rarfilename'] .= '.rar'; }
    $am = 1;
  }

  for($i = 0; $i < $am; $i++) {
    if ($_GET['rar_opts']['separated']) { $name = htmlentities(basename($list[$_GET['rar_opts']['filestorar'][$i]]['name']).'.rar'); }
?>
        <table align="center">
          <tr>
            <td colspan="2" class="rar-main-td">
              <?php printf($optxt['_crearcv'], ($_GET['rar_opts']['separated'] ? $name : $_GET['rar_opts']['rarfilename'])); ?>
            </td>
          </tr>
          <tr>
            <td class="rar-options-left-td"><?php echo $optxt['_stat']; ?></td>
            <td id="rar_status<?php echo $i; ?>" class="rar-rb-td"><?php echo $optxt['_wait']; ?></td>
          </tr>
          <tr>
            <td colspan="2" class="rar-space-td">&nbsp;</td>
          </tr>
        </table>
<?php
  }
?>
      </td>
    </tr>
    <tr>
      <td>
      </td>
    </tr>
  </table>
  <span id="rar_finished" style="display:none;"><a href="<?php echo $PHP_SELF."?act=files"; ?>"><?php echo $optxt['_backlist']; ?></a><br /><br /><br /></span>
<?php
}



function rar_go_go() {
  global $txt, $optxt, $check_these_before_unzipping, $download_dir, $forbidden_filetypes, $list;
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
  flush();
  require_once(CLASS_DIR."rar.php");

  if ($options['disable_deleting']) { $_GET['rar_opts']['delete'] = 0; }
  if ($options['disable_archive_compression']) { $_GET['rar_opts']['comp_lvl'] = 0; }

  if ($_GET['rar_opts']['separated']) { $am = count($_GET['rar_opts']['filestorar']); }
  else { $am = 1; }
  for($i = 0; $i < $am; $i++) {
    $name = ($_GET['rar_opts']['separated']) ? basename($list[$_GET['rar_opts']['filestorar'][$i]]['name']).'.rar' : $_GET['rar_opts']['rarfilename'];
    $rar = new rlRar($name, $check_these_before_unzipping ? $forbidden_filetypes : array('.xxx'));
    if ($rar->rar_return !== 'rar') {
?>
<script type="text/javascript">rar_st('rar_status<?php echo $i; ?>', '<?php echo $optxt['_text']; ?>');</script>
<?php 
    }
    else {
      $rar_opts_tmp = $_GET['rar_opts'];
      if ($_GET['rar_opts']['separated']) { $rar_opts_tmp['filestorar'] = array($_GET['rar_opts']['filestorar'][$i]); }
      $rar_result = $rar->addtoarchive($rar_opts_tmp, $download_dir, 'rar_status'.$i, $i);
      echo $rar_result;
      if (strpos($rar_result, ", 'Done')") !== false) {
        _create_list();
        clearstatcache();
        if ($_GET['rar_opts']['delete'] == true) {
          foreach ($_GET['rar_opts']['filestorar'] as $rar_tounlist) {
            $rar_tounlist = basename($list[$rar_tounlist]['name']);
            if (empty($rar_tounlist)) { continue; }
            $rar_tounlist = realpath($download_dir).'/'.$rar_tounlist;
            if (is_file($rar_tounlist)) { continue; }
            foreach ($list as $list_key => $list_item) {
              if ($list_item['name'] === $rar_tounlist) { unset($list[$list_key]); }
            }
          }
        }
        $rar_tolist = realpath($download_dir).'/'.basename($rar->filename);
        if ($_GET['rar_opts']['vols'] && !is_file($rar_tolist)) {
          if (substr(strtolower($rar_tolist), -4) == '.rar') { $rar_tolist = substr($rar_tolist, 0, -4); }
          $tmp = basename(strtolower($rar_tolist)).'.part';
          $rar_dir = opendir(realpath($download_dir).'/');
          while (false !== ($rar_f_dd = readdir($rar_dir))) {
            $rar_f_dd_ = basename(strtolower($rar_f_dd));
            if ($tmp == substr($rar_f_dd_, 0, strlen($tmp)) && is_numeric(substr($rar_f_dd_, strlen($tmp), -4))) {
              $rar_f_dd = realpath($download_dir).'/'.basename($rar_f_dd);
              $time = filemtime($rar_f_dd); while (isset($list[$time])) { $time++; }
              $list[$time] = array("name" => $rar_f_dd, "size" => bytesToKbOrMbOrGb(filesize($rar_f_dd)), "date" => $time);
            }
          }
          closedir($rar_dir);
        }
        elseif (is_file($rar_tolist)) {
          $time = filemtime($rar_tolist); while (isset($list[$time])) { $time++; }
          $list[$time] = array("name" => $rar_tolist, "size" => bytesToKbOrMbOrGb(filesize($rar_tolist)), "date" => $time);
        }
        if (!updateListInFile($list)) {
?>
<script type="text/javascript">var tmp = document.getElementById('rar_finished'); tmp.innerHTML = "<?php echo $txt['error_upd_list']; ?><br /><br />" + tmp.innerHTML</script>;
<?php
        }
      }
    }
  }
?>
<script type="text/javascript">document.getElementById('rar_finished').style.display = '';</script>
<?php
}
?>