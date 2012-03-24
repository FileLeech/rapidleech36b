<?php
function unzip() {
	global $optxt,$list;
	if (count ( $_GET ["files"] ) < 1) {
		echo $optxt['select_one_file']."<br><br>";
	} else {
    ?>
    <form method="post">
      <input type="hidden" name="act" value="unzip_go">
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
                    </td>
                  </tr>
            <?php
          }
      ?>
            </table>
          </td>
          <td>
            <input type="submit" value="Unzip">
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

function unzip_go() {
	global $list, $optxt,$options;
  $unzip_file = FALSE;
 require_once(CLASS_DIR."unzip.php");
  for($i = 0; $i < count($_GET["files"]); $i++)
   {
  $file = $list[$_GET["files"][$i]];
     if(file_exists($file["name"]))
       {
		$zip = new dUnzip2($file["name"]);
		//$zip->debug = true;
		
		if($options['check_these_before_unzipping'])
			{
			$allf = $zip->getList();
			foreach($allf as $file => $property)
				{
				$newName = $property['file_name'];
				$rest = substr($newName, -1);
				if(eregi('[^a-zA-Z0-9_]', $rest)){$alpnum = false;}else{$alpnum = true;}
				while(!$alpnum and (strlen($newName)>0))
				{
					$newName = substr($newName, 0, -1);
					$rest = substr($newName, -1);
					if(eregi('[^a-zA-Z0-9_]', $rest)){$alpnum = false;}else{$alpnum = true;}
				}
				
				$zfiletype = strrchr($newName, ".");
				if (is_array($options['forbidden_filetypes']) && in_array(strtolower($zfiletype), $options['forbidden_filetypes']))
					{
					exit($optxt['filetype']." $zfiletype ".$optxt['forbidden_unzip']."<script>alert('".$optxt['filetype']." $zfiletype ".$optxt['forbidden_unzip']."');var poslynx, hpath = document.location.href; poslynx = hpath.lastIndexOf('/'); hpath = hpath.substring(0,poslynx+1); window.document.location.href=hpath;</script>");
					}
				
				}
			}
		$zip->unzipAll($options['download_dir']);
		if($zip->getList() != false){
			echo "<b>".$file["name"]."</b>&nbsp;".$optxt['unzip_success']."<br>";
		}
         $unzip_file = TRUE;
       }
     else
      {
        echo $optxt['_file']." <b>".$file["name"]."</b> ".$optxt['not_found']."<br><br>";
      }
   }
  if($unzip_file)
    {
      if(!updateListInFile($list))
       {
           echo $optxt['couldnt_upd']."<br><br>";
       }
    }
}
?>