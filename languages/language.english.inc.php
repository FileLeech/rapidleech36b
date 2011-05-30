<?php

  /*****************************************************
  ** Title........: Rapidleech PlugMod rev. 36B by eqbal Lang Pack
  ** Author.......: Credits to Pramode & Checkmate & Kloon. Mod by: MsNeil & Idoenk
  ** Filename.....: languages.en.inc.php
  ** Language.....: English  
  ** Lang:Mod.....: -
  ** Version......: 0.1
  ** Notes........: *
  ** Updated......: 100307 - YYMMDD
  *****************************************************/
  // Set Charset of this language
  $charSet = 'charset=UTF-8';
  //$charSet = 'charset=ISO-8859-1';
  
  $scrname = substr(basename($_SERVER["PHP_SELF"]), 0, -strlen(strrchr(basename($_SERVER["PHP_SELF"]), ".")));
  $vpage = (!isset($vpage) ? $scrname : $vpage);


  $gtxt = array(
  // general page; commonly load on every page
     'js_disable'      => 'Your Javascript is currently Disabled',
     '_bypass_autodel' => 'bypass autodeletion with this param',
	 'back_main' => 'Go back to main',
	 
	 'no_files' 	=> 'No Files',
	 'tabel_no_file' => 'No files found',
	 '_show' => 'Show',
     '_downloaded' => 'Downloaded',
     '_everything' => 'Everything',	 
	 
     '_maxfilesize' => 'MaxFilesize',
     '_minfilesize' => 'MinFilesize',
     '_refresh' => 'refresh',
     '_autodel' => 'AutoDelete',
     '_pointboost' => 'PointBoost',
     '_limitip' => 'Limit-Download',
     '_fakeext' => 'FakeExt',
     '_fakeext_desc' => 'Auto rename extension with this',
     '_timework' => 'RL Time Work',
     'wrong_proxy' => 'Wrong proxy address entered',
	 'action' => 'Action',
     'worktime_alert'=> '&raquo; RL not in a Working Time, please come-back later',

     'use_premix' => 'Use PremiX',
     'use_proxy' => 'Use Proxy Settings',
     '_proxy' => 'Proxy:',
     '_uname' => 'Username:',
     '_pass' => 'Password:',

     'save_to' => 'Save To',
     'save_path' => 'Path:',
	 
     '_upto' => 'upto',

     'tabel_sz' => 'Size',
	 'tabel_ip' => 'IP Leeched',
     'tabel_dt' => 'Date',
     'tabel_age' => 'File Age',
     'act_del' => 'Delete',

     '_second' => 'seconds.',
	 
     '_uploading' => 'Uploading File',
	 
     'close' => 'Close',
	 
     'chk_txt_matches' => 'Check Matches',
     'go_match' => 'Match',	 
     'match_csensitive' => 'Case Sensitive',
     'match_hideunmatch' => 'Hide UnMatch',	 
	 
	 'days' => 'day(s)',
	 'hours' => 'hour(s)',
	 'minutes' => 'minute(s)',
	 'seconds' => 'second(s)',
	 'ago' => 'ago',
	 'less_a_minute' => 'less than a minute',
	 
     'unauthorized' => 'You\'re not authorized, Get Lost..!',
     'banned' => 'You\'re Banned, disappear now..!',
	 
     'unauthorized_c' => 'Your country is not authorized, Get Lost..!',
     'banned_c' => 'Your country is banned, disappear now..!',
	 
	 );



/*  ====================================================
*/

switch($vpage)
{
	case "index":
	 $txt = array(
  //main.php it's load from index.php also
     'cpanel'       => 'Control Panel',
     'maintenance'          => 'Under Maintenance...!',
     'premix_used_1'         => 'You already use your',
     'premix_used_2'         => 'free PremiX per',
     'premix_used_3'         => 'hour(s)!',
     'premix_used_4'         => 'free files for today!',
     'sorry_inc'           => 'Sorry for this inconvenience!',
     'quote_alert'=> '&raquo; Quota Traffic Limit Alert..!',
     'quote_status'=> 'Sorry, The Quota Traffic status is:',
     'maxstorage_alert_1'=> '&raquo; Maximum Limit Storage reached, please delete some files',
     'maxstorage_alert_2'=> ' or wait until autodelete frees somespace',
     'exceed_alert'=> '* exceed maximum limit;',
     'expired_since'=> '* expired since ;',	 
     'cpuload_sloadhigh'=> 'Server load too high, come back later;',
	 
     'maxjob_limited_1'=> 'Server is limit download upto ',
     'maxjob_limited_2'=> ' tasks at a time.',
	 
     'link_transload'              => 'Link to Transload',
     '_transload'              => 'Transload File',
     'referrer'              => 'Referrer',
     'add_comment'      => 'Add Comments',
     'user_stats'   => 'User Stats:',
     'limit_leech'   => 'Limited Leech Mode',
     'detect_ip'   => 'Detected IP:',
     
	 'server_stats'   => 'Server Stats:',
	 'log_act'   => 'Log Activity:',
	 'lact_files'   => 'file(s)',
	 'lact_autodeleted'   => 'deleted by autodelete',
	 'current_storage'   => 'Current Used Storage:',
	 'current_traffic'   => 'Current Traffic:',
	 'reset_traffic_remain'   => 'Reset Traffic Remaining:',
	 'max_traffic'   => 'maximum quota traffic',
	 
     'send_email'  => 'Send File to Email',
     'email'=> 'Email:',
     'split_file'    => 'Split Files',
     'method'=> 'Method:',
     'tot_com' => 'Total Commander',
     'rfc' => 'RFC 2046',
     'part_size' => 'Parts Size:',
	 
	 
     'save_sett' => 'Save Settings',
     'clear_sett' => 'Clear Current Settings',
	 
     'plugin_opt' => 'PluginOptions:',
     'plugin_disable' => 'Disable All Plugins',
     'plugin_youtube' => 'Transfer YouTube Video in High Quality Mp4 (H264) Format',
     'plugin_imageshack' => 'ImageShack&reg; TorrentService',
     'plugin_megaupl' => 'Megaupload.com Cookie Value',
     'plugin_hotfile' => 'Hotfile.com Cookie Value',
     'plugin_rs' => 'Rapidshare.com Cookie Value',
     'plugin_buletin' => 'Use vBulletin Plugin',
	 
     '_user' => 'user=',
     '_auth' => 'auth=',
     '_enc' => 'enc=',
	 	 
     '_sfrefresh' => 'Refresh',
     'chk_all' => 'Check All',
     'chk_unchk' => 'Un-Check All',
     'chk_invert' => 'Invert Selection',
	 
     'act_upload' => 'Upload',
     'act_ftp' => 'FTP File',
     'act_mail' => 'E-Mail',
     'act_boxes' => 'Mass Submits',
     'act_split' => 'Split Files',
     'act_merge' => 'Merge Files',
     'act_md5' => 'MD5 Hash',
     'act_pack' => 'Pack Files',
     'act_zip' => 'ZIP Files',
     'act_unzip' => 'Unzip Files (beta)',
     'act_rename' => 'Rename',
     'act_mrename' => 'Mass Rename',
     'act_delete' => 'Delete',
	 
     'tabel_name' => 'Name',
     'tabel_dl' => 'Download Link',
     'tabel_cmt' => 'Comments',
	 
	 
     'curl_notload_1' => 'You need to load/activate the cURL extension (http://www.php.net/cURL) or you can set',
     'curl_notload_2' => ' in config.php.',
     'curl_enable' => 'cURL is enabled',
	 
     'php_below_5' => 'PHP version 5 is recommended although it is not obligatory',
     'php_server_safemode' => 'Check if your safe mode is turned off as the script cannot work with safe mode on',
	 	 
     'work_with' => 'Link Checker Works With',
     'link_only' => 'Display Links Only',
     'kill_link_only' => 'Kill Links Only',
     'debud_mode' => 'Debug Mode',
     'debud_mode_notice' => 'Please change the debug mode to',
     'max_bound_chk_link_1' => 'Maximum No',
     'max_bound_chk_link_2' => 'Of links have been reached.',
     'check_in' => 'checked in',
	 
     'rs_acc_chk' => 'Rapidshare Account Checker',
     'modded' => 'Modded',
     'un_pass' => 'username:password',
     'curl_stat' => 'cURL mode:',
     'curl_notice' => 'can not use this acc checker unless cURL is ON',
     '_on' => 'ON',
     '_off' => 'OFF',
	 
	 
	 //=========================
	 //=index.php
	 
     'path_not_defined' => 'Path is not specified for saving this file',
     'size_not_true' => 'Untrue a size of the part is specified',
     'url_unknown' => 'Unknown URL Type',
     'url_only_use' => 'Only Use',
     'url_or' => 'or',
	 
     'downloading' => 'Downloading',
     'prep_dl' => '...Prepare',
     'leeching' => 'Leeching..',
	 
     'back_main' => 'Go back to main',
     '_error' => 'Error!',
     '_redirect_to' => 'it is redirected to',
     '_redirecting_to' => 'Redirecting to:',
     '_saved' => 'Saved!',
     '_reload' => 'Reload',
     '_avg_spd' => 'Average Speed:',
	 
     'error_upd_list' => 'Couldn\'t update the files list',
     'error_upd_trf_list' => 'Couldn\'t update the traffic list',
	 
     'mail_file_sent' => 'File was sent to this address',
     'mail_error_send' => 'Error sending file!',
     'delete_link' => 'Delete Link:',
     'delete_link_notice' => 'Use this delete link after you have finished downloading the file<br>free up space your hard disk for others.',
	 'zzzzz' => ''
	 
     );
	 
	 $htxt = array(
  //http.php; it's load from index.php also
     '_pwait'       => 'Please wait',
     '_error_retrieve'       => 'Error retriving the link',
     '_error_redirectto'       => 'Error! it is redirected to',
     '_error_resume'       => 'Resume limit exceeded',
     '_error_noresume'       => 'This server doesn\'t support resume',
     '_error_cantsave'       => 'cannot be saved in directory',
     '_error_trychmod'       => 'Try to chmod the folder to 777',
     '_error_tryagain'       => 'Try again',
     '_error_imposible_record'       => 'It is not possible to carry out a record in the file',
     '_error_misc'       => 'Invalid URL or unknown error occured',
     '_con_proxy'       => 'Connected to proxy',
     '_con_to'       => 'Connected to',
     '_sorry_tobig'       => 'Sorry, your file is too big',
     '_sorry_tosmall'       => 'Sorry, your file is too small',
     '_sorry_quotafull'       => 'Sorry, insufficient traffic quota',

	 'zzzzz' => ''	 
     );	 
 
 
 // Un-translated :: $optxt
 	 $optxt = array(
     'no_support_upl_serv'   => 'No Supported Upload Services!',
     'select_one_file'       => 'Please select at least one file',
     'del_disabled'       	=> 'Delete Disabled',
     '_file'       			=> 'File',
     '_host'       			=> 'Host',
     '_port'       			=> 'Port',
     '_del'       => 'Delete',
     'these_file'       => 'These Files',
     'this_file'       => 'This File',
     '_yes'       => 'Yes',
     '_no'       => 'No',
     '_deleted'       => 'Deleted',
     'couldnt_upd_list'       => 'Couldn\'t update file list. Problem writing to file!',
     'error_delete'       => 'Error deleting',
     'not_found'       => 'Not Found!',
     'error_upd_list'       => 'Error in updating the list!',
     'couldnt_upd'       => 'Couldn\'t Update',
     'del_success'       => 'Delete successful submits',
     'split_part'       => 'Split by Parts',
     '_method'       => 'Method',
     'part_size'       => 'Parts Size',
     'invalid_email'       => 'Invalid E-mail Address.',
     '_and_del'       => 'and deleted.',
     '_not_del'       => 'not deleted!',
     '_but'       => 'but',
     'send_for_addr'       => 'it is sent for the address',
     'error_send'       => 'Error sending file!',
     'filetype'       => 'The filetype',
     'forbidden_unzip'       => 'is forbidden to be unzipped',
     'unzip_success'       => 'unzipped successfully',
     'saveto'       => 'Save To',
     'del_source_aft_split'       => 'Delete source file after successful split',
     'start_split'       => 'Started to split file',
     'part_of'       => 'parts of',
     'use_method'       => 'Using Method',
     'tot_part'       => 'Total Parts',
     'crc_error'       => 'It is not possible to split the file. CRC Error',
     'crc_error_open'       => 'It is not possible to open source file',
     'split_error'       => 'It was not possible to split the file.',
	 'piece_exist'       => 'A piece already exists',
	 'crc_exist'       => 'CRC file already exists',
	 'src_notfound'       => 'Source file not found',
	 'dir_inexist'       => 'Directory doesn\'t exist',
	 'error_read_file'       => 'Error reading the file',
	 'error_open_file'       => 'Error opening file',
	 'error_write_file'       => 'Error writing the file',
     'split_error_source_not_del'       => 'An error occured. Source file not deleted!',
     'source_del'       => 'Source file deleted.',
     'source_file_is'       => 'Source file is',
     'error_upd_file_exist'       => 'Couldn\'t update. File already exists!',
     'select_crc_001_file_only'       => 'Please select only the .crc or .001 file!',
     'select_crc_001_file'       => 'Please select the .crc or .001 file!',
     'select_crc_file'       => 'Please select the .crc file!',
     'size_cec32_wont_check'       => 'The file size and crc32 won\'t be check',
     'cant_read_crc_file'       => 'Can\'t read the .crc file!',
     'err_output_file_exist'       => 'Error, Output file already exists',
     'err_missing_parts'       => 'Error, missing or incomplete parts',
     'imposible_open_dir'       => 'It is not possible to open destination file',
     'merge_file_not_found'       => 'The files needed to merge are not found!',
     'file_not_open'       => 'The file can\'t be opened for writing!',
     'filesize_unmatch'       => 'Filesize doesn\'t match!',
     'perform_crc'       => 'Do you want to perform a CRC check?',
     'recommend'       => '(recommended)',
	 
     'select_action'       => 'Select an Action',
     'add_zip'       => 'Add files to a ZIP archive',
     'arcv_name'       => 'Archive Name',
     'no_compress'       => 'Do not use compression',
     'no_subdir'       => 'Do not include directories',
     'add_file'       => 'Add Files',
     '_arcv'       => 'Archive',
     '_arcv_name'       => 'Archive Name',
     'success_created'       => 'successfully created!',
     'compress_notice_1'       => 'For use compress gz or bz2 write extension as Tar.gz or Tar.bz2;!',
     'compress_notice_2'       => 'Else this archive will be uncompress Tar',
     'enter_arc_name'       => 'Please enter an archive name!',
     'ready_exist'       => 'already exists!',
     '_error'       => 'Error!',
     'arcv_not_created'       => 'Archive not created.',
     'error_occur'       => 'An error occured!',
     'was_pack'       => 'was packed',
     'pack_in_arcv'       => 'Packed in archive',
     'arcv_empty'       => 'Archive is Empty.',
     'del_source_aft_upl'       => 'Delete source file after successful upload',
     'add_extension'       => 'Add extension',
     '_without'       => 'without',
     'rename_to'       => 'renamed to',
     'couldnt_rename_to'       => 'Couldn\'t rename the file',
     'new_name'       => 'New name',
     'no_permision_rename'       => 'you don\'t have permission to rename files',
     'success_merge_untes'       => 'successfully merged, but not tested!',
     'success_merge'       => 'successfully merged!',
     'crc32_unmatch'       => 'CRC32 checksum doesn\'t match!',
	 
     'you_selected'       => 'You have selected',
     'you_sure_ch_md5'       => 'Are you sure want to change MD5 of these file(s)?',
     'cur_md5'       => 'Current MD5',
     'new_md5'       => 'New MD5',
     'change_md5'       => 'Change&nbsp;MD5',

/*
<?echo $optxt['crc32_unmatch'];?>
*/
	 'zzzzz' => ''	 
     );

 
	 break; // end case index
	 
		 
	case "audl":
	$atxt = array(
  //audl.php
     'not_link'       => 'Not LINK',
     '_link' 	=> 'Link',
     '_links' 	=> 'Links',
     '_opt' 	=> 'Options',
     '_status' 	=> 'Status',
     '_download' 	=> 'Download',
     '_done' 	=> 'DONE',
     '_waiting' 	=> 'Waiting...',
     '_started' 	=> 'started..',
     'audl_start' 	=> 'Start auto download',
     'add_link' 	=> 'Add links',
     'acc_imgshack' 	=> 'Use Imageshack Account',
     'error_interval' 	=> 'Errors in the interval of delay (from 1 to 3600 seconds)',
	 'plugin_megaupl' => 'Cookie Megaupload.com',	 
	 'plugin_hotfile' => 'Cookie Hotfile.com',	 
	 'plugin_rs' => 'Cookie Rapidshare.com',	 
	 '_user' => 'user=',
     '_auth' => 'auth=',
     '_enc' => 'enc=',	 
	 'reach_lim_audl' => 'Sorry you can not proceed more than %link% Links at once.',
	 'auto_check_link' => 'Auto Check Links',

	 'zzzzz' => ''
	 
     );
	break; // end case audl
	 
	
	case "lynx":
	$ltxt = array(
  //lynx.php
     '_fname'       => 'FileName',
     '_b64_desc' 	=> 'Base64 Download Link',
     '_term' 	=> '+Term',
     '_b64link' 	=> 'B64Link',
     '_deletelink' 	=> 'Delete Link',
     '_genlink' 	=> 'Generate Link',
	 
	 'zzzzz' => ''
     );
	 break;  //end case lynx
	 
	case "del":
  $dtxt = array(
  //del.php; 
     '_rsure'       => 'Are you sure want to',
     '_todelete'       => 'delete this file',
     '_sucesdelete'       => 'successfully deleted!',
     '_thx'       => 'Thank you.',
     '_inexist'       => 'File inexistence',

	 'zzzzz' => ''
	 
     );
	 break;  //end case del.php
}

?>