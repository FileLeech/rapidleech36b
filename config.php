<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

//for security reason we add this value so you just can set manualy directly to this file
//whether you want disable/enable advanced editor in xpanel.
$disableadvanceeditor = false;
$xpanel_filename = 'xpanel.php'; // u need to allow this file in your htaccess if needed

//set index filename, needed in audl incase you have a different name instead index.php
$index_file = 'index.php';

###-LOGIN-CONFIG
$allowcpanel =  true; // WARNING, set this to FALSE will Disable access to xpanel.
$loginCp = array('admin'=>'admin'); // user=>pass

$login = false;
$users = array('' => '');  // user=>pass

###-PREMIUM-CONFIG

//$premium_acc = array();
//$premium_acc["rs_com"] = array('user' => '', 'pass' => '');

/*// below here is the way u insert multiple acc for rapidshare.com
$premium_acc["rs_com"] = array(
  array('user' => 'user1', 'pass' => 'pass1'),
  array('user' => 'user2', 'pass' => 'pass2'),
  array('user' => 'user3', 'pass' => 'pass3')
);  //foobar - rs_com- multi; */
//$premium_acc["rs_de"] = array('user' => '', 'pass' => '');
//$premium_acc["megaupload"] = array('user' => '', 'pass' => '');
//$premium_acc["megashare"] = array('user' => '', 'pass' => '');
//$premium_acc["netload"] = array('user' => '', 'pass' => '');
//$premium_acc["gigasize"] = array('user' => '', 'pass' => '');
//$premium_acc["vBulletin_acc"] = array('user' => '', 'pass' => '');
//$premium_acc["uploaded_to"] = array('user' => '', 'pass' => '');
//$premium_acc["easyshare"] = array('user' => '', 'pass' => '');
//$premium_acc["depositfiles"] = array('user' => '', 'pass' => '');
//$premium_acc["hotfile_com"] = array('user' => '', 'pass' => '');
//$premium_acc["uploading"] = array('user' => '', 'pass' => '');
//$premium_acc["filefactory"] = array('user' => '', 'pass' => '');
//$premium_acc["ifile_it"] = array('user' => '', 'pass' => '');
//$premium_acc["sendspace"] = array('user' => '', 'pass' => '');
//$premium_acc["fileserve_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filesonic_com"] = array('user' => '', 'pass' => '');
//$premium_acc["oron_com"] = array('user' => '', 'pass' => '');
//$premium_acc["duckload_com"] = array('user' => '', 'pass' => '');
//$premium_acc["shareonline_biz"] = array('user' => '', 'pass' => '');
//$premium_acc["torrific_com"] = array('user' => '', 'pass' => '');
#Auto Download Premium Account #
//$premium_acc_audl = false;

#Megaupload cookie #
//$mu_cookie_user_value = '';

#Hotfile cookie #
//$hf_cookie_auth_value = '';

#Rapidshare cookie #
//$rs_cookie_enc_value = '';

###Imageshack Torrent Account ###
//$imageshack_acc = array('user' => '', 'pass' => '');

#Upload Account #
//$upload_acc["mediafire_up"] = array('user' => '', 'pass' => '');
//$upload_acc["rs_com_coll"] = array('user' => '', 'pass' => '');
//$upload_acc["rs_com_pre"] = array('user' => '', 'pass' => '');
//$upload_acc["mu_pre"] = array('user' => '', 'pass' => '');
//$upload_acc["hf_com"] = array('user' => '', 'pass' => '');
//$upload_acc["easysh_mem"] = array('user' => '', 'pass' => '');
//$upload_acc["deposit_up"] = array('user' => '', 'pass' => '');
//$upload_acc["uploading_up"] = array('user' => '', 'pass' => '');


#-LIMITATION-CONFIG
$limitbyip = false; //limit RL by IP; dont forget chmod 777 to folder tmp
$maximum_free_downloads = 5; //how many times it'll granted?
$delay_per_ip = 12; //(in hour) recycle allowable IP

$auto_del_time = 0; //(in hour) delete leeched file
$limitsize = 0; //(in MB) limit upper-bound of filesize
$lowlimitsize = 0; //(in MB) limit lower-bound of filesize

$storage_limit = 0; //(in MB) limit your server files storage.  1 * 1024 = 1 GB; 

$downloadLimitbyip = false; //limit RL by IP
$downloadsPerIP = 2; //how many times it'll granted?
$downloadDelayPerIP = 3600; //(in second)

$audl = 0; //how many link allow to auto-download work ?
$auul = 0; //how many file allow to auto-upload work ?

$limitbytraffic = false; //limit RL by Traffic Flow
$max_trafic = 81920; // (in MB). eg: 1 GB = 1 * (1024) MB
$date_trafic = '14/04/2020'; // (d-day traffic quota expired). date in dd/mm/YYYY 
$day_reset_trafic = 1; // auto reset traffic. delay in days; 

$limited_edition = false; // limit authorization RL by ip address (banned and allowd list) 
$list_allow_ip = '127.0.0.1'; // White list ip. eg. 111.111.111.111, 255.*.*.*  //--never blank this shit if you set $limited_edition = true 
$list_baned_ip = ''; // blacklist ip, u think so?!. eg. 111.111.111.111, 222.*.*.*, 212.212.212.* 

$limited_area = false; // limit authorization RL by ID Country
$allow_CID = ''; // White list Country ID, blank mean all country is allowed. eg. ID, MY; // allow only Indonesia And Malaysia
$baned_CID = ''; // Blacklist Country ID, blank mean no country is banned. eg. US; // all country from US being banned.

$limit_timework = false; // limit your RL by time. Client's Current time depend on (Server timezone)
$workstart = '00:00:00'; // Your RL start to work
$workend = '23:59:00'; // Your RL end to work

$limit_cpuload = false; // limit cpu load and task server job
  $ServerLoadAllowed = 0; // Maximum server load allowed; Disable = 0
  $CpuLoadFormat = 'load'; // Value = 'load' for load format; 'percent' for percent format
  ((!function_exists('exec')&&!function_exists('shell_exec'))?$CpuLoadFormat = 'percent':null); //CpuLoadFormat must be in percent mode if required functions is not exists
  $passthru_allowed = (!function_exists('passthru')?false:true) OR FALSE; // Does your host allows passthru?
  $MaxServerJob = 10; // Maximum server jobs at a time; Disable = 0

###-FILE CONFIG
$download_dir = "0x14/"; // Your downloaded files are saved here;
$download_dir_is_changeable = false; // To allow users to change the download dir ( index page )

$maysaveto = false; // To allow users to change downloaded files to saved ( in audl )

$forbidden_filetypes = array('.htaccess', '.htpasswd', '.php', '.php3', '.php4', '.php5', '.phtml', '.asp', '.aspx', '.cgi');
$rename_these_filetypes_to = '.xxx';
$check_these_before_unzipping = true;

$disable_action = false; //no action menus
$disable_to = array( // disabled action files properties
 'act_upload' => false,
 'act_ftp' => false,
 'act_mail' => false,
 'act_boxes' => false,
 'act_split' => false,
 'act_merge' => false,
 'act_md5' => false,
 'act_rar' => false,
 'act_unrar' => false,
 'act_pack' => false,
 'act_zip' => false,
 'act_unzip' => false,
 'act_rename' => false,
 'act_mrename' => false,
 'act_delete' => false,
);

$show_column_sfile = array(  // property server_file's column
 'md5' => false,
 'downloadlink' => true,
 'comments' => false,
 'date' => true,
 'age' => true,
 'ip' => true,
);

$show_all = true;
$bw_save = true; 

$deletelink_in_lynx = true; 

#Auto-Rename #
$rename_prefix = '';//eg. mysite => mysite_file_name.rar
$rename_suffix = '';//eg. mysite => file_name_mysite.rar
$add_ext_5city = '';//eg. ccpb => file_name.rar.ccpb

//define writable files
define('LOG_DIR', 'logfile/');
define('FILES_LST', LOG_DIR.'Rx08_2208081410_f1L3Z.lst');
define('IP_L33CH_L0G', LOG_DIR.'Rx08_2208081410_IP_L33CH_L0G.lst');
define('VISITOR_LST', LOG_DIR.'Rx08_2208081410_v1zit0r.lst');
define('TRAFFIC_LST', LOG_DIR.'Rx08_2208081410_tR4fic.txt');
define('LOG_PHP', LOG_DIR.'Rx08_2208081410_d4Mn.log.php');
define('LASTLOG_LST', LOG_DIR.'Rx08_2208081410_d4Mn_Last.txt');
define('MYUPLOAD_LST', LOG_DIR.'myuploads.txt');
define('ONGOING_LST', LOG_DIR.'Rx08_2208081410_ongoingTask.lst');

###-VIEW-CONFIG
$navi_left = array(
 'showcpanel' => true,
 'showplugins' => true,
 'showaudl' => true,
 'showauul' => true,
 'showlynx' => true,
 'server_info' => true,
);

 $forbid_audl = false;
 $forbid_auul = false;
 $forbid_lynx = false;
 $cpuUsageNFO = true;  // require server_info = true
$OnlineVisitor = true; //Show Online Visitor

$premix_status = true; // enable acc premix status
$ip_premixstat_list = array('127.0.0.1'); // trusted ip, can view detil acc.

###-MISC-CONFIG
$no_cache = false;
$redir = true;

$disable_ajax = false; //switch to old method, No-Ajax in Serverfiles
$disable_ajaxren = false; //toogle ajax instant rename. require: rsajax.js; rsajax_ren.js
$logact = true; //do log-activity of the users
$alternatefree = true; //Auto switch freedownload if premium not good
$showautoclose= true;//autoclose popup when leeching in audl
$timeautoclose= 250;
$autochecklink = true; // Auto check submited link in audl

$mip_enabled= false; //If you need to disable multiple ip support, set to false
$mip_arotate= true; //Auto change to next ip after start transload process

$secretkey = '';//Place your Secret Key
$iframealocate = 10;//how many iframe to allocate in audl for manual method.
$pointboost = 0;//boost your RS-Point with this feature!!
$autosubmit = true;

$timezone = 7; // set Timezone. It is GMT+(7) for Indonesia.
$lang = 'english'; // set Language.

$csstype = '_default'; // set Theme to your RL. eg. _default
?>