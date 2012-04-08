<?php
if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit;
}

###-PREMIUM-CONFIG
//$premium_acc = array();
### Remove '//' from the beginning and enter your username and password for enable premium account ###
//$premium_acc["rapidshare_com"] = array('user' => '', 'pass' => '');
// For multiple rapidshare premium accounts only - if you are using multiple accounts below, comment out the line above
//$premium_acc["rapidshare_com"] = array(array('user' => '', 'pass' => ''),array('user' => '', 'pass' => ''),array('user' => '', 'pass' => ''));
//$premium_acc["4shared_com"] = array('user' => '', 'pass' => '');
//$premium_acc["bayfiles_com"] = array('user' => '', 'pass' => '');
//$premium_acc["bitshare_com"] = array('user' => '', 'pass' => '');
//$premium_acc["bulletupload_com"] = array('user' => '', 'pass' => '');
//$premium_acc["cramit_in"] = array('user' => '', 'pass' => '');
//$premium_acc["crocko_com"] = array('user' => '', 'pass' => '');
//$premium_acc["depositfiles_com"] = array('user' => '', 'pass' => '');
//$premium_acc["extabit_com"] = array('user' => '', 'pass' => '');
//$premium_acc["fast-debrid_com"] = array('user' => '', 'pass' => '');
//$premium_acc["file4sharing_com"] = array('user' => '', 'pass' => '');
//$premium_acc["fileape_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filedino_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filedude_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filefactory_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filefat_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filejungle_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filepost_com"] = array('user' => '', 'pass' => '');
//$premium_acc["fileserve_com"] = array('user' => '', 'pass' => '');
//$premium_acc["fileserving_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filesflash_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filesmonster_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filesonic_com"] = array('user' => '', 'pass' => '');
//$premium_acc["freakshare_com"] = array('user' => '', 'pass' => '');
//$premium_acc["furk_net"] = array('user' => '', 'pass' => '');
//$premium_acc["gigasize_com"] = array('user' => '', 'pass' => '');
//$premium_acc["hellshare_com"] = array('user' => '', 'pass' => '');
//$premium_acc["hotfile_com"] = array('user' => '', 'pass' => '');
//$premium_acc["ifile_it"] = array('user' => '', 'pass' => '');
//$premium_acc["jumbofiles_com"] = array('user' => '', 'pass' => '');
//$premium_acc["letitbit_net"] = array('user' => '', 'pass' => '');
//$premium_acc["maknyos_com"] = array('user' => '', 'pass' => '');
//$premium_acc["mediafire_com"] = array('user' => '', 'pass' => '');
//$premium_acc["megashare_com"] = array('user' => '', 'pass' => '');
//$premium_acc["megashares_com"] = array('user' => '', 'pass' => '');
//$premium_acc["movshare_net"] = array('user' => '', 'pass' => '');
//$premium_acc["netload_in"] = array('user' => '', 'pass' => '');
//$premium_acc["netuploaded_com"] = array('user' => '', 'pass' => '');
//$premium_acc["oron_com"] = array('user' => '', 'pass' => '');
//$premium_acc["real-debrid_com"] = array('user' => '', 'pass' => '');
//$premium_acc["sendspace_com"] = array('user' => '', 'pass' => '');
//$premium_acc["shareonline_biz"] = array('user' => '', 'pass' => '');
//$premium_acc["speedyshare_com"] = array('user' => '', 'pass' => '');
//$premium_acc["turbobit_net"] = array('user' => '', 'pass' => '');
//$premium_acc["ugotfile_com"] = array('user' => '', 'pass' => '');
//$premium_acc["uploaded_to"] = array('user' => '', 'pass' => '');
//$premium_acc["uploading_com"] = array('user' => '', 'pass' => '');
//$premium_acc["uploadstation_com"] = array('user' => '', 'pass' => '');
//$premium_acc["vBulletin_acc"] = array('user' => '', 'pass' => '');
//$premium_acc["wupload_com"] = array('user' => '', 'pass' => '');
//$premium_acc["youtube_com"] = array('user' => '', 'pass' => '');
//$premium_acc["filegaze_com"] = array('user' => '', 'pass' => '');

###Premium cookie configuration, dont use this if you have already set another premium account config
//$premium_acc["depositfiles_com"] = array('cookie' => '');
//$premium_acc["hotfile_com"] = array('cookie' => '');
//$premium_acc["rapidshare_com"] = array('cookie' => '');
//$premium_acc["uploaded_to"] = array('cookie' => '');
//$premium_acc["uploading_com"] = array('cookie' => '');

###Premium key configuration, dont use this if you have already set another premium account config
//$premium_acc["fileflyer_com"] = array('pass' => '');
//$premium_acc["letitbit_net"] = array('pass' => '');
//$premium_acc["shareflare_net"] = array('pass' => '');
//$premium_acc["vip_file_com"] = array('pass' => '');

#Auto Download Premium Account #
$premium_acc_audl = false;

#Upload Account #
//$upload_acc = array();
//$upload_acc["rapidshare_com"] = array('user' => '', 'pass' => '');
//$upload_acc["4shared_com"] = array('user' => '', 'pass' => '');
//$upload_acc["bayfiles_com"] = array('user' => '', 'pass' => '');
//$upload_acc["bitshare_com"] = array('user' => '', 'pass' => '');
//$upload_acc["bulletupload_com"] = array('user' => '', 'pass' => '');
//$upload_acc["cramit_in"] = array('user' => '', 'pass' => '');
//$upload_acc["crocko_com"] = array('user' => '', 'pass' => '');
//$upload_acc["depositfiles_com"] = array('user' => '', 'pass' => '');
//$upload_acc["extabit_com"] = array('user' => '', 'pass' => '');
//$upload_acc["fast-debrid_com"] = array('user' => '', 'pass' => '');
//$upload_acc["file4sharing_com"] = array('user' => '', 'pass' => '');
//$upload_acc["fileape_com"] = array('user' => '', 'pass' => '');
//$upload_acc["filedino_com"] = array('user' => '', 'pass' => '');
//$upload_acc["filedude_com"] = array('user' => '', 'pass' => '');
//$upload_acc["filefactory_com"] = array('user' => '', 'pass' => '');
//$upload_acc["filefat_com"] = array('user' => '', 'pass' => '');
//$upload_acc["filejungle_com"] = array('user' => '', 'pass' => '');
//$upload_acc["filepost_com"] = array('user' => '', 'pass' => '');
//$upload_acc["fileserving_com"] = array('user' => '', 'pass' => '');
//$upload_acc["filesflash_com"] = array('user' => '', 'pass' => '');
//$upload_acc["filesmonster_com"] = array('user' => '', 'pass' => '');
//$upload_acc["filesonic_com"] = array('user' => '', 'pass' => '');
//$upload_acc["freakshare_com"] = array('user' => '', 'pass' => '');
//$upload_acc["furk_net"] = array('user' => '', 'pass' => '');
//$upload_acc["gigasize_com"] = array('user' => '', 'pass' => '');
//$upload_acc["hellshare_com"] = array('user' => '', 'pass' => '');
//$upload_acc["hotfile_com"] = array('user' => '', 'pass' => '');
//$upload_acc["ifile_it"] = array('user' => '', 'pass' => '');
//$upload_acc["jumbofiles_com"] = array('user' => '', 'pass' => '');
//$upload_acc["letitbit_net"] = array('user' => '', 'pass' => '');
//$upload_acc["maknyos_com"] = array('user' => '', 'pass' => '');
//$upload_acc["mediafire_com"] = array('user' => '', 'pass' => '');
//$upload_acc["megashare_com"] = array('user' => '', 'pass' => '');
//$upload_acc["megashares_com"] = array('user' => '', 'pass' => '');
//$upload_acc["movshare_net"] = array('user' => '', 'pass' => '');
//$upload_acc["netload_in"] = array('user' => '', 'pass' => '');
//$upload_acc["netuploaded_com"] = array('user' => '', 'pass' => '');
//$upload_acc["oron_com"] = array('user' => '', 'pass' => '');
//$upload_acc["real-debrid_com"] = array('user' => '', 'pass' => '');
//$upload_acc["sendspace_com"] = array('user' => '', 'pass' => '');
//$upload_acc["shareonline_biz"] = array('user' => '', 'pass' => '');
//$upload_acc["speedyshare_com"] = array('user' => '', 'pass' => '');
//$upload_acc["turbobit_net"] = array('user' => '', 'pass' => '');
//$upload_acc["ugotfile_com"] = array('user' => '', 'pass' => '');
//$upload_acc["uploaded_to"] = array('user' => '', 'pass' => '');
//$upload_acc["uploading_com"] = array('user' => '', 'pass' => '');
//$upload_acc["uploadstation_com"] = array('user' => '', 'pass' => '');
//$upload_acc["vBulletin_acc"] = array('user' => '', 'pass' => '');
//$upload_acc["wupload_com"] = array('user' => '', 'pass' => '');
//$upload_acc["youtube_com"] = array('user' => '', 'pass' => '');

#Secret key for cookie encryption
#Make up a random one to protect your premium cookies (max length: 56). Example: $secretkey = 'UijSY5wjP1Ii'; - DO NOT use this example $secretkey, or your premium accounts/cookies could be stolen!!
#IF THIS IS NOT SET BEFORE YOU USE PREMIUM SERVICES, YOU WILL BE WARNED BY THE RAPIDLEECH SCRIPT. OTHERWISE YOUR PREMIUM ACCOUNTS AND/OR COOKIES COULD BE COMPROMISED!
$secretkey = '';

# Acc info & drop down
$ar_host_acc = array(
'4shared_com' => '4shared.com',
'animeground_com' => 'animeground.com',
'bayfiles_com' => 'bayfiles.com',
'bitshare_com' => 'bitshare.com',
'bulletupload_com' => 'bulletupload.com',
'cramit_in' => 'cramit.in',
'crocko_com' => 'crocko.com',
'depositfiles_com' => 'depositfiles.com',
'extabit_com' => 'extabit.com',
'fast-debrid_com' => 'fast-debrid.com',
'file4sharing_com' => 'file4sharing.com',
'fileape_com' => 'fileape.com',
'filedino_com' => 'filedino.com',
'filedude_com' => 'filedude.com',
'filefactory_com' => 'filefactory.com',
'filefat_com' => 'filefat.com',
'filejungle_com' => 'filejungle.com',
'filepost_com' => 'filepost.com',
'fileserve_com' => 'fileserve.com',
'fileserving_com' => 'fileserving.com',
'filesflash_com' => 'filesflash.com',
'filesmonster_com' => 'filesmonster.com',
'filesonic_com' => 'filesonic.com',
'freakshare_com' => 'freakshare.com',
'furk_net' => 'furk.net',
'gigasize_com' => 'gigasize.com',
'hellshare_com' => 'hellshare.com',
'hotfile_com' => 'hotfile.com',
'ifile_it' => 'ifile.it',
'jumbofiles_com' => 'jumbofiles.com',
'kickload_com' => 'kickload.com',
'letitbit_net' => 'letitbit.net',
'maknyos_com' => 'maknyos.com',
'mediafire_com' => 'mediafire.com',
'megashare_com' => 'megashare.com',
'megashares_com' => 'megashares.com',
'movshare_net' => 'movshare.net',
'netload_in' => 'netload.in',
'netuploaded_com' => 'netuploaded.com',
'oron_com' => 'oron.com',
'rapidshare_com' => 'rapidshare.com',
'real-debrid_com' => 'real-debrid.com',
'sendspace_com' => 'sendspace.com',
'shareflare_net' => 'shareflare.net',
'share-online_biz' => 'share-online.biz',
'speedyshare_com' => 'speedyshare.com',
'turbobit_net' => 'turbobit.net',
'ugotfile_com' => 'ugotfile.com',
'uploaded_to' => 'uploaded.to',
'uploading_com' => 'uploading.com',
'uploadstation_com' => 'uploadstation.com',
'vBulletin_acc' => 'vbulletin.acc',
'wupload_com' => 'wupload.com',
'youtube_com' => 'youtube.com',
'filegaze_com' => 'filegaze.com',
);
?>