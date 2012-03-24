<?php
function rl_rename() {
	global $optxt,$options, $list;
	if (count ( $_GET ["files"] ) < 1) {
		 echo $optxt['select_one_file']."<br><br>";
	} elseif ($disable_deleting) {
		echo $optxt['no_permision_rename'];
	} else {
    ?>
    <form method="post">
      <input type="hidden" name="act" value="rename_go">
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
                    <td align="center"><b><?php echo htmlspecialchars(basename($file["name"])); ?></b></td>
                  </tr>
                  <tr>
                    <td>
					<table><tr>
                      <td><?php echo $optxt['new_name'];?>:</td><td>&nbsp;<input type="text" name="newName[]" size="50" value="<?php echo htmlspecialchars(basename($file["name"])); ?>"></td>
                    </tr></table>
					</td>
                  </tr>
            <?php
          }
      ?>
            </table>
          </td>
        </tr>
      </table>
	  <input type="submit" value="Rename">
    </form>
    <?php
  }
}

function rename_go() {
	global $optxt,$list, $options;
     $smthExists = FALSE;
     for($i = 0; $i < count($_GET["files"]); $i++)
      {
        $file = $list[$_GET["files"][$i]];
        
        if(file_exists($file["name"]))
          {
            $smthExists = TRUE;
            $newName = dirname($file["name"]) . PATH_SPLITTER . trim($_GET["newName"][$i]);
			
			$rest = substr($newName, -1);
			
			if(!preg_match("/^[a-zA-Z0-9_]+$/i", $rest)) { $alpnum = false;}else{$alpnum = true;}
			while(!$alpnum and (strlen($newName)>0))
			{
				$newName = substr($newName, 0, -1);
				$rest = substr($newName, -1);
				if(!preg_match("/^[a-zA-Z0-9_]+$/i", $rest)) { $alpnum = false;}else{$alpnum = true;}
			}
			
			
			
            $filetype = strrchr($newName, ".");                      
			if (is_array($options['forbidden_filetypes']) && in_array(strtolower($filetype), $options['forbidden_filetypes']))
				{
				print "The filetype $filetype is forbidden to be renamed<br><br>";
				}
			else
				{
				if(@rename($file["name"], $newName))
					{
					echo $optxt['_file']." <b>".htmlspecialchars($file["name"])."</b> ".$optxt['rename_to']." <b>".htmlspecialchars(basename($newName))."</b><br><br>";
					$list[$_GET["files"][$i]]["name"] = $newName;
					}
				else
					{
					echo $optxt['couldnt_rename_to']." <b>".$file["name"]."</b>!<br><br>";
					}
				}
          }
        else
         {
           echo $optxt['_file']." <b>".$file["name"]."</b> ".$optxt['not_found']."<br><br>";
         }
      }
     if($smthExists)
       {
         if(!updateListInFile($list))
          {
              echo $optxt['couldnt_upd']."<br><br>";
          }
       }
}
?>