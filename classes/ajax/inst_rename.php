<?php
if(!defined('RAPIDLEECH')){ 
 require_once("404.php"); exit; 
}

if(isset($_POST['ren'])) {
 @clearstatcache();
 global $list, $options, $optxt; 
 define('PATH_SPLITTER', (strstr(ROOT_DIR, "\\") ? "\\" : "/"));
 
 $vpage = "index";
 require_once(LANG_DIR."language.{$options['lang']}.inc.php");
 _create_list();
 //$charSet = 'charset=ISO-8859-1';
 $charSet = 'charset=UTF-8';
 header("Content-type: text/html; $charSet");
 

 $postFName = (string)$_POST['ren'];
 $partFN = explode("|", $postFName, 3); // time|newName|fileName
 $fileName = rawurldecode(base64_decode($partFN[2]));
  
 $smthExists = FALSE;
 foreach($list as $time => $record){
   if($fileName==basename($record["name"])){
	 $fileName = $list[$partFN[0]]["name"];
	 $lineset = $record;
	 break;
   }
 }
 
 $ck_showall = (isset($_COOKIE["showAll"]) ? $_COOKIE["showAll"]:false); 
 if($ck_showall==1){ // we are in showall mode
   // force and get list in showdownload mode
   unset($list);
   _create_list(false, false, 2); 
   $lineset = (isset($list[$partFN[0]]) ? $list[$partFN[0]] : '');
 }

 
 if(file_exists($fileName)) {
  
  $oldName = $fileName;
  $newName = str_replace("~","", iconv("UTF-8", "ISO-8859-1//TRANSLIT", rawurldecode(base64_decode($partFN[1])) ));
  $newName = dirname($oldName) . PATH_SPLITTER . $newName;
  
  $filetype = strrchr($newName, ".");                      
  if(is_array($options['forbidden_filetypes']) && in_array(strtolower($filetype), $options['forbidden_filetypes'])){
	// ERROR
	echo "((suc))0((/suc))<msg>The filetype ".$filetype." is forbidden to be renamed</msg>";
  }
  else{
	if(@rename($oldName, $newName)){
	  $smthExists = TRUE;
	  

	  foreach($list as $key => $file){
	    if($file["name"] == $oldName){
		  $list[$key] = $lineset;
	      $list[$key]["name"] = $newName;		  
	    }
	  }	  
	  echo "((suc))1((/suc))<msg>".$optxt['_file']." ".(basename($oldName))." ".$optxt['rename_to']." <b>".(basename($newName))." {$postFName}</msg>";	  
	}
	else{
	  // ERROR
	  //echo $optxt['couldnt_rename_to']." <b>".$oldName."</b>!";
	  echo "((suc))0((/suc))<msg>The filetype ".$filetype." is forbidden to be renamed</msg>";
	}
  }
 }else{
   echo "((suc))0((/suc))<msg>".$optxt['_file']." [".$partFN[0]."] ".$fileName." ".$optxt['not_found']."\n".print_r($list,true)."</msg>";
 }
 if($smthExists){
	//_create_list();
    if(!updateListInFile($list)){
	  echo "((suc))0((/suc))<msg>".$optxt['couldnt_upd']."</msg>";
    }
 } 

 
 
}
?>