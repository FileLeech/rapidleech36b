<?php
if(!defined('RAPIDLEECH')){ 
 require_once("404.php"); exit; 
}

if(isset($_POST['TBLoad'])) {
 $charSet = 'charset=ISO-8859-1';
 $expires = 60; // set expired cached page in 60 sec
 header("content-type:text/xml; $charSet");
 header("Expires: ".gmdate("D, d M Y H:i:s", time()+$expires)."GMT");

 clearstatcache();
 if(!defined('IMAGE_DIR')){ define('IMAGE_DIR', 'misc/');}
 if(!defined('CLASS_DIR')){ define('CLASS_DIR', 'classes/');	}
 if(!defined('LANG_DIR')){ define('LANG_DIR', 'languages/');	} 
 // "http://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js"
 $jQ_google_api_file = IMAGE_DIR . "jquery.min.js";
 
 if(!isset($PHP_SELF)){ $PHP_SELF = !$PHP_SELF ? $_SERVER["PHP_SELF"] : $PHP_SELF;}
 $vpage = "index"; $rn = "\r\n";
 require_once(LANG_DIR."language.{$options['lang']}.inc.php");
 
 _create_list();
 $resXML = '';
 
 {
/*==try with XML==*/

  if($list)
  {
   $resXML='<data>'.$rn;   
   
   //action files
   $resXML.= '<actions>'.$rn; 
   if(!$options['disable_action']){
    $resXML.='<option>blank:-1:'.$gtxt['action'].'</option>'.$rn;
    foreach($options['disable_to'] as $act => $val){
	 if(!$val){
	  $goodtogo = false;
	  switch($act){
	    case 'act_pack';
		 if(@file_exists(CLASS_DIR."pear.php")||@file_exists(CLASS_DIR."tar.php")){$goodtogo = true;}
		break;
	    case 'act_zip';
		 if(@file_exists(CLASS_DIR."pclzip.php")){$goodtogo = true;}
		break;
	    case 'act_unzip';
		 if(@file_exists(CLASS_DIR."unzip.php")){$goodtogo = true;}
		break;
		default: $goodtogo = true; break;
	  }
	  $valupl = str_replace("act_", "", $act);
	  if($goodtogo) {$resXML.= '<option>'.$valupl.':'.$act.':'.$txt[$act].'</option>'.$rn;}
	 }
    }
   }
   $resXML.= '</actions>'.$rn; 
   
   $feat_ajax = array();
   $ajax_rename_ada = @file_exists("rsajax_ren.js");
   $feat_ajax["ajax_rename"] = (!$options['disable_to']["act_rename"] && $ajax_rename_ada && !$options['disable_ajaxren'] ? '1' : '0');
   $resXML.= '<instantrename>'.($feat_ajax["ajax_rename"]).'</instantrename>'.$rn.$rn;
   
   $jQ_google_api_online = @fopen($jQ_google_api_file, "r");
   $feat_ajax["ajax_delete"] = (!$options['disable_to']["act_delete"] && $jQ_google_api_online ? '1' : '0');
   $resXML.= '<ajaxdelete>'.($feat_ajax["ajax_delete"]).'</ajaxdelete>'.$rn.$rn;
   
   //querying header
   $resXML.= '<columns>'.$rn; 
   $resXML.= ' <chkbox> </chkbox>'.$rn;
   $resXML.= ' <filename>'.$txt['tabel_name'].'</filename>'.$rn;
   $resXML.= ' <filesize>'.$gtxt['tabel_sz'].'</filesize>'.$rn;
   
   if($_COOKIE['showAll']==1 && $options['show_column_sfile']["md5"]==true){
    $resXML.= ' <filemd5>MD5</filemd5>'.$rn;
   }
   if($_COOKIE['showAll']!=1 && $options['show_column_sfile']["downloadlink"]==true){   
    $resXML.= ' <filelink>'.$txt['tabel_dl'].'</filelink>'.$rn;
   }
   if($options['show_column_sfile']["comments"]==true){
    $resXML.= ' <filecomment>'.$txt['tabel_cmt'].'</filecomment>'.$rn;
   }
   if($options['show_column_sfile']["date"]==true){
    $resXML.= ' <filedate>'.$gtxt['tabel_dt'].'</filedate>'.$rn;
   }
   if($options['show_column_sfile']["age"]==true){
    $resXML.= ' <fileage>'.$gtxt['tabel_age'].'</fileage>'.$rn;
   }
   if($_COOKIE["showAll"]!=1 && $options['show_column_sfile']["ip"]==true){
    $resXML.= ' <fileip>'.$gtxt['tabel_ip'].'</fileip>'.$rn; 
   }
   
   if($feat_ajax["ajax_delete"]=='1'){
    $resXML.= ' <ajax_del>'.$gtxt['act_del'].'</ajax_del>'.$rn; 
   }
   
   $resXML.= '</columns>'.$rn;    
   
   $total_files = 0; $total_size = 0; $kumulatifsz = true;
   if(isset($list["files"]["totalsize"])){
    $total_size = $list["files"]["totalsize"];
	$kumulatifsz = false;
   }
   $resXML.= "\n".'<files>'.$rn;
   foreach($list as $key => $file){
    if(@file_exists($file["name"]))
    {
       $total_files++;
       if($kumulatifsz) $total_size+=getfilesize($file["name"]);
       $inCurrDir = strstr(dirname($file["name"]), ROOT_DIR) ? TRUE : FALSE;
       if($inCurrDir){
         $Path = parse_url($PHP_SELF);
         $Path = substr($Path["path"], 0, strlen($Path["path"]) - strlen(strrchr($Path["path"], "/")));
       }else{
		 $cl_Path = relink_path(DOWNLOAD_DIR, $PHP_SELF);
		}
	$resXML.= "\n".' <fileid>brs'.$total_files.'</fileid>'.$rn;
	$resXML.= ' <filename>'.xmlentities(basename($file["name"])).'</filename>'.$rn;
	   
	   $_fpath = ($inCurrDir?$Path.str_replace('\\',"/",substr(dirname($file["name"]),strlen(ROOT_DIR)))."/".xmlentities(basename($file["name"])) : $cl_Path["root"]."/".$cl_Path["download"].xmlentities(basename($file["name"])));	   
	$resXML.= ' <filepath>'.($_fpath).'</filepath>'.$rn;
	   
	   $_fsize = str_replace(" ", "((space))",$file["size"]);
	   $_fsize = str_replace("&nbsp;", "((space))",$_fsize);
	$resXML.= ' <filesize>'.$_fsize.'</filesize>'.$rn;
	   
	   $_fdate = '';
	   if($options['show_column_sfile']["date"]==true){
	     $_fdate = str_replace(" ","((space))",date("d.m.Y H:i:s", $file["date"]));
	     $_fdate = str_replace("&nbsp;","((space))",$_fdate);
	$resXML.= ' <filedate>'.$_fdate.'</filedate>'.$rn;
	   }
	$resXML.= ' <filedateid>'.$file["date"].'</filedateid>'.$rn;
	
	$resXML.= ' <fileage>'.$file["age"].'</fileage>'.$rn;
	   
	   $_fmd5 = '';
	   if($_COOKIE['showAll']==1 && $options['show_column_sfile']["md5"]==true) { 
	    $_fmd5 = $file["md5"];
	$resXML.= ' <filemd5>'.$_fmd5.'</filemd5>'.$rn;
	   }
	   
	   $_flink = ''; $_flinktr = '';
	   if($_COOKIE["showAll"]!=1 && $options['show_column_sfile']["downloadlink"]==true){
		if($file["link"]==''){
		 $filelink = '#';
		 $trfilelink = ' ';
		}else{
		 $filelink = xmlentities($file["link"]);
		 $trfilelink = trimlink($filelink);
		}
		$resXML.= ' <filelink>'.$filelink.'</filelink>'.$rn;
		$resXML.= ' <filelinktr>'.$trfilelink.'</filelinktr>'.$rn;
	   }
	   
	   $_fcomment = '';
	   if($options['show_column_sfile']["comments"]==true && $file["comment"]){
	    $_fcomment = str_replace("\\r\\n", "<br/>", $file["comment"]);
	$resXML.= ' <filecomment>'.$_fcomment.'</filecomment>'.$rn;
	   }	
	   
	   $_fip = '';
	   if($_COOKIE["showAll"]!=1 && $options['show_column_sfile']["ip"]==true) {
	    $_fip = $file["ip"];
	$resXML.= ' <fileip>'.$_fip.'</fileip>'.$rn;
	   }
	   
	   $_fdkey = '';
	   if($feat_ajax["ajax_delete"]=="1") {
	    $rnd = rand(11,99);
	    $_fdkey = str_replace("=","", rotN(base64_encode($file["date"].':'.'4puZ'), $rnd))."-".$rnd;
	$resXML.= ' <filedelkey>'.$_fdkey.'</filedelkey>'.$rn;
	   }
	   

	} //--end file_exits	
   } //--end foreach
   $resXML.= '</files>'.$rn;
   
   if(isset($list["files"]["totalfile"]))$total_files = $list["files"]["totalfile"];
   $resXML.= '<succeed>'.($total_files > 0 ? '1':'0').'</succeed>'.$rn;
   $resXML.= ($total_files > 0 ? '' : '<msg>'.$gtxt['tabel_no_file'].'</msg>'.$rn);
   
   $resXML.= "\n".'<totalfiles>'.$total_files.'</totalfiles>'.$rn;
   $_totsize = str_replace(" ", "((space))",bytesToKbOrMbOrGb($total_size));
   $_totsize = str_replace("&nbsp;", "((space))",$_totsize);
   $resXML.= '<totalsize>'.$_totsize.'</totalsize>'.$rn;

      
   //count span column
    $n_column= 0;
	foreach($options['show_column_sfile'] as $cocol => $colval){
      if($colval){
	   switch($cocol){
	     case "ip": // add colspan when not show all & column ip shown
		   if(!$_COOKIE["showAll"]==1){ $n_column++; }
		 break;
		 case "md5": // add colspan when show all & column md5 shown
		   if($_COOKIE["showAll"]==1){ $n_column++; }
		 break;
		 case "downloadlink": // add colspan when downloadlink shown and it's in showdownloaded
		   if($_COOKIE["showAll"]!=1){ $n_column++; }
		 break;
	     default: $n_column++;
		 break;
	   }	   
	  } //-end $colval enable
    } //-end foreach    
   $resXML.= '<columnspan>'.($feat_ajax["ajax_delete"]=="1" ? ($n_column+1) : $n_column).'</columnspan>'.$rn;
   
   unset($total_files,$total_size);

   // process counter
   $time2 = explode(" ", microtime());
   $endtime2 = $time2[1] + $time2[0]; unset($time2);
   $totaltime2 = ($endtime2 - $begintime2);
   
   $_tdtpl .='</tbody></table><small class="y"><b>'.count($list).'</b></small><small class="footer3"> file'.(count($list)>1?'s':'').' loaded in '.$totaltime2.' sec</small></div></div></form>';
   
   
   $resXML.= '<proceestime>'.$totaltime2.'</proceestime>'.$rn;      
   $resXML.= '</data>';   
  }else{ //--file not found  
   $resXML='<data>'.$rn;
   $resXML.='<succeed>0</succeed>'.$rn;
   $resXML.='<msg>'.$gtxt['tabel_no_file'].'</msg>'.$rn;
   $resXML.='</data>';
  }
  
 }
 exit($resXML);
} 

?>