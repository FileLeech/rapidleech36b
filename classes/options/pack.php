<?php
function pack() {
	global $optxt,$list;
	if (count ( $_GET ["files"] ) < 1) {
		echo $optxt['select_one_file']."<br><br>";
	} else {
    ?>
      <form method="post">
      <input type="hidden" name="act" value="pack_go">
      <?php
      echo count($_GET["files"])." file".(count($_GET["files"]) > 1 ? "s" : "").":<br>";

      for($i = 0; $i < count($_GET["files"]); $i++)
        {
          $file = $list[$_GET["files"][$i]];
          ?>
          <input type="hidden" name="files[]" value="<?php echo $_GET["files"][$i]; ?>">
          <b><?php echo basename($file["name"]); ?></b><?php echo $i == count($_GET["files"]) - 1 ? "." : ",&nbsp;";
        }
      ?><br><br>
       <table align="center">
         <tr>
           <td>
             <?php echo $optxt['_arcv_name'];?>:&nbsp;<input type="text" name="arc_name" size="30">
           </td>
           <td>
             <input type="submit" value="Pack">
           </td>
         </tr>
         <tr>
           <td>
             <?php echo $optxt['saveto'];?>:&nbsp;<input type="text" name="path" size="30" value="<?php echo ($_COOKIE["path"] ? $_COOKIE["path"] : (strstr(ROOT_DIR, "\\") ? addslashes(dirname(__FILE__)) : dirname(__FILE__))); ?>">
           </td>
         </tr>
       </table>
       <table align="center">
        <tr>
          <td>
            <?php echo $optxt['compress_notice_1'];?><br>
            <?php echo $optxt['compress_notice_2'];?><br>
          </td>
        </tr>
       </table>
      </form>
    <?php
  }
}

function pack_go() {
	global $optxt,$list;
  $smthExists=true;
  if(count($_GET["files"]) < 1)
   { echo $optxt['select_one_file']."<br><br>"; break; }

  $arc_name=$_GET["arc_name"];
  if (!$arc_name)
   { echo $optxt['enter_arc_name']."<br><br>"; break; };

  if (file_exists($arc_name))
   { echo "File <b>".$arc_name."</b> ".$optxt['ready_exist']."<br><br>"; break; }

	$part_ext = explode(".", $arc_name); $restrict = false; $i = 0;
	while(!$restrict && ($i < count($part_ext)))
	{
		$restrict = in_array(".".$part_ext[$i], $forbidden_filetypes);
		$i= $i + 1;
	}
	if ($restrict)
	{
		print "<b class=\"r\">".$optxt['_error']."</b> ".$optxt['arcv_not_created']."<br><br>"; break;
	}
	

  for($i = 0; $i < count($_GET["files"]); $i++)
   {
    $file = $list[$_GET["files"][$i]];
    if(file_exists($file["name"]))
     {
      $v_list[] = $file["name"];
     } else
     {
       echo "File <b>".$file["name"]."</b> ".$optxt['not_found']."<br><br>";
     }
   }
  if (!$v_list)
   { echo "<b class=\"r\">".$optxt['error_occur']."</b><br><br>"; break; }
  $arc_name = $path.'/'.$arc_name;
  //$arc_name = dirname($arc_name).PATH_SPLITTER.$arc_name;


  require_once(CLASS_DIR."tar.php");
  $tar = new Archive_Tar($arc_name);
  $tar->create($v_list,$arc_method);
  if (!file_exists($arc_name))
   { echo "<b class=\"r\">".$optxt['_error']."</b> ".$optxt['arcv_not_created']."<br><br>"; break; }

  if (count($v_list  =  $tar->listContent()) > 0)
   {
     echo $optxt['_file'].""; echo count($v_list)>1 ? "s" : ""; echo "<br>";
      for ($i=0; $i<sizeof($v_list); $i++)
       {
         echo $optxt['_file']." ".$v_list[$i]["filename"]." ".$optxt['was_pack']." <br>";
       }
      echo $optxt['pack_in_arcv']." <b>$arc_name</b><br>";


      $stmp=strtolower($arc_name);
      if (strrchr($stmp,"tar.gz"  )+5==strlen($stmp))
         {  $arc_method="Tar.gz"; }
  elseif (strrchr($stmp,"tar.bz2" )+6==strlen($stmp))
         {  $arc_method="Tar.bz2";}
  else   {  $arc_method="Tar";    };
      unset($stmp);

      $time = explode(" ", microtime());
      $time = str_replace("0.", $time[1], $time[0]);
      $list[$time] = array("name"    => $arc_name,
                           "size"    => bytesToKbOrMbOrGb(filesize($arc_name)),
                           "date"    => $time,
                           "link"    => "",
                           "comment" => "archive ".$arc_method);
    } else { echo "<b class=\"r\">".$optxt['_error']."</b> ".$optxt['arcv_empty']."<br><br>"; }
  if(!updateListInFile($list))
    {
     echo $optxt['couldnt_upd']."<br><br>";
    }
}
?>