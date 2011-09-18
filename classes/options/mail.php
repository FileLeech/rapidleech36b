<?php
function xmail() {
	global $disable_deleting, $optxt,$list;
	if (count ( $_GET ["files"] ) < 1) {
			echo $optxt['select_one_file']."<br><br>";
		} else {
    ?>
      <form method="post">
      <input type="hidden" name="act" value="mail_go">
      <?php echo $optxt['_file'];?><?php echo count($_GET["files"]) > 1 ? "s" : ""; ?>:
      <?php
      for($i = 0; $i < count($_GET["files"]); $i++)
        {
          $file = $list[($_GET["files"][$i])];
          ?>
          <input type="hidden" name="files[]" value="<?php echo $_GET["files"][$i]; ?>">
          <b><?php echo basename($file["name"]); ?></b><?php echo $i == count($_GET["files"]) - 1 ? "." : ",&nbsp"; ?>
          <?php
        }
      ?><br><br>
      <table align="center">
        <tr>
          <td>
            Email:&nbsp;<input type="text" size=40 name="email" value="<?php echo ($_COOKIE["email"] ? $_COOKIE["email"] : ""); ?>">
          </td>
          <td>
            <input type="submit" value="Send">
          </td>
        </tr>
        <tr>
         <td>
            <input type="checkbox" name="del_ok" <?php if(!$disable_to["act_del"]) echo "checked"; ?> <?php if($disable_to["act_del"]) echo "disabled"; ?>>&nbsp;<?php echo $optxt['del_success'];?>
         </td>
        </tr>
        <tr>
          <td>
          </td>
        </tr>
        <tr>
          <table>
            <tr>
              <td>
                <input id="splitchkbox" type="checkbox" name="split" onClick="javascript:var displ=this.checked?'':'none';document.getElementById('methodtd2').style.display=displ;"<?php echo $_COOKIE["split"] ? " checked" : ""; ?>>&nbsp;<?php echo $optxt['split_part'];?>
              </td>
              <td>&nbsp;

              </td>
              <td id="methodtd2"<?php echo $_COOKIE["split"] ? "" : " style=\"display: none;\""; ?>>
                <table>
                  <tr>
                    <td>
                      <?php echo $optxt['_method'];?>:&nbsp;<select name="method"><option value="tc"<?php echo $_COOKIE["method"] == "tc" ? " SELECTED='SELECTED'" : ""; ?>>Total Commander</option><option value="rfc"<?php echo $_COOKIE["method"] == "rfc" ? " SELECTED='SELECTED'" : ""; ?>>RFC 2046</option></select>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <?php echo $optxt['part_size'];?>:&nbsp;<input type="text" name="partSize" size="2" value="<?php echo $_COOKIE["partSize"] ? $_COOKIE["partSize"] : 10; ?>">&nbsp;MB
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
      </form>
    <?php
  }
}

function mail_go() {
	global $list, $optxt, $fromaddr, $disable_to;
	//require_once (CLASS_DIR . "mail.php");
	if (! checkmail ( $_GET ["email"] )) {
		echo $optxt['invalid_email']."<br><br>";
	} else {
    $_GET["partSize"] = ((isset($_GET["partSize"]) & $_GET["split"] == "on") ? $_GET["partSize"] * 1024 * 1024 : FALSE);
    for($i = 0; $i < count($_GET["files"]); $i++)
      {
        $file = $list[$_GET["files"][$i]];
        if(file_exists($file["name"]))
          {
            if(xmail("$fromaddr", $_GET[email], "File ".basename($file["name"]), "File: ".basename($file["name"])."\r\n"."Link: ".$file["link"].($file["comment"] ? "\r\nComments: ".str_replace("\\r\\n", "\r\n", $file["comment"]) : ""), $file["name"], $_GET["partSize"], $_GET["method"]))
              {
               if ($_GET["del_ok"] && !$disable_to["act_del"])
                  {
                   if(@unlink($file["name"]))
                     {
                      $v_ads=" ".$optxt['_and_del'];
                      unset($list[$_GET["files"][$i]]);
                     }
                   else
                     {
                      $v_ads=", ".$optxt['_but']." <b>".$optxt['_not_del']."</b>";
                     };
                  } else $v_ads=" !";
                echo "<script type=\"text/javascript\">mail('File <b>".basename($file["name"])."</b> ".$optxt['send_for_addr']." <b>".$_GET["email"]."</b>".$v_ads."', '".md5(basename($file["name"]))."');</script>\r\n<br>";
              }
            else
              {
                echo "<b class=\"r\">".$optxt['error_send']."</b><br>";
              }
          }
        else
          {
            echo $optxt['_file']." <b>".$file["name"]."</b> ".$optxt['not_found']."<br><br>";
          }
      }
  }
}
?>