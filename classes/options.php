<?php 
if (!defined('RAPIDLEECH'))
  {  require_once("404.php");  exit;  }
  
if (! $disable_action) {

if(substr($_GET["act"],-3,3)=="_go"){
echo "
<script type='text/javascript'>
function slip(){var txt, lst='Hide Last Action';var d_txt = d.getElementById('act_hist_txt');var d_img = d.getElementById('act_hist_img');var d_hist = d.getElementById('act_history');txt = d_txt.innerHTML;
 if(txt==lst){ d_txt.innerHTML = 'Show Last Action'; d_img.src = '".IMAGE_DIR."plus.png'; d_hist.style.display = 'none';
 }else{
 d_txt.innerHTML = lst; d_img.src = '".IMAGE_DIR."min.png'; d_hist.style.display = '';}
 return false;
}
</script>
<label onclick='return slip();'><span id='act_hist_txt'>Hide Last Action</span><input id='act_hist_img' type='image' src='".IMAGE_DIR."min.png'/></label>
<div id='act_history' class='acthistory_result'>";
	
}elseif($_GET["act"]!='files'){

echo "
<script type='text/javascript'>
function closeit(flag){var d_shlast = d.getElementById(\"showlast\");var d_hist = d.getElementById(\"act_history\");   d_shlast.style.display = (flag?'':'none'); d_hist.style.display = (flag?'none':'');}
</script>
<label id='showlast' onclick='closeit(false);' style='display:none;'><span id='act_hist_txt'>Show Last Action</span><input id='act_hist_img' type='image' src='".IMAGE_DIR."plus.png'></label>
<div id='act_history' class='acthistory_prompt'><div class='closer_btn' onclick='closeit(true);'><span>".$gtxt['close']."</span></div><div style='padding-bottom:12px;'></div>";
}


	switch ($_GET ["act"]) {
		case "upload" :
			if(!defined('UPLOAD_DIR')){define(UPLOAD_DIR, 'upl/');}
			require(CLASS_DIR . "options/upload.php");
			upload();
			break;
		
		case "delete" :
			require(CLASS_DIR . "options/delete.php");
			delete();
			break;
		
		case "delete_go" :
			require(CLASS_DIR . "options/delete.php");
			delete_go();
			break;
		
		case "mail" :
			require(CLASS_DIR . "options/mail.php");
			xmail();
			break;
		
		case "mail_go" :
			require(CLASS_DIR . "options/mail.php");
			mail_go();
			break;
		
		case "boxes" :
			require(CLASS_DIR . "options/boxes.php");
			boxes();
			break;
		
		case "boxes_go" :
			require(CLASS_DIR . "options/boxes.php");
			boxes_go();
			break;
		
		case "md5" :
			require(CLASS_DIR . "options/md5.php");
			rl_md5();
			break;
		
		case "unzip" :
			require(CLASS_DIR . "options/unzip.php");
			unzip();
			break;
		
		case "unzip_go" :
			require(CLASS_DIR . "options/unzip.php");
			unzip_go();
			break;
		
		case "split" :
			require(CLASS_DIR . "options/split.php");
			_split();
			break;
		
		case "split_go" :
			require(CLASS_DIR . "options/split.php");
			split_go();
			break;
		
		case "merge" :
			require(CLASS_DIR . "options/merge.php");
			merge();
			break;
		
		case "merge_go" :
			require(CLASS_DIR . "options/merge.php");
			merge_go();
			break;
		
		case "rename" :
			require(CLASS_DIR . "options/rename.php");
			rl_rename();
			break;
		
		case "rename_go" :
			require(CLASS_DIR . "options/rename.php");
			rename_go();
			break;
		
		//MassRename
		case "mrename" :
			require(CLASS_DIR . "options/mrename.php");
			mrename();
			break;
		
		case "mrename_go" :
			require(CLASS_DIR . "options/mrename.php");
			mrename_go();
			break;
		
		//end MassRename
		

		case "ftp" :
			require(CLASS_DIR . "options/ftp.php");
			ftp();
			break;
		
		case "ftp_go" :
			require(CLASS_DIR . "options/ftp.php");
			ftp_go();
			break;
		
		case "zip" :
			require(CLASS_DIR . "options/zip.php");
			zip();
			break;
		
		case "zip_add" :
			require(CLASS_DIR . "options/zip.php");
			zip_go();
			break;
		
		case "pack" :
			require(CLASS_DIR . "options/pack.php");
			pack();
			break;
			
		
		case "pack_go" :
			require(CLASS_DIR . "options/pack.php");
			pack_go();
			break;
	}
	echo "</div>";  // box prompt/result
}else{
 echo "Actions Disabled.";
}
?>