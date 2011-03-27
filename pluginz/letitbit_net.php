<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class letitbit_net extends DownloadClass {
     public function Download($link) {
		global $premium_acc;

                if ( ($_REQUEST ["premium_acc"] == "on" && $_REQUEST ["premium_user"] && $_REQUEST ["premium_pass"]) ||
			($_REQUEST ["premium_acc"] == "on" && $premium_acc ["letitbit_net"] ["pass"] ) )
		{
			$this->DownloadPremium($link);
		}
                else{
                        $this->DownloadFree($link);
                }
	}
private function DownloadFree($link) {
        global $Referer;
            $page = $this->GetPage($link);
            is_present($page, "The requested file was not found");
            is_present($page, "Gesuchte Datei wurde nicht gefunden", "The requested file was not found");
            is_present($page, "Запрашиваемый файл не найден", "The requested file was not found");
            $cookie = GetCookies($page);
            $Urlact = "http://letitbit.net".cut_str ( $page, 'id="ifree_form" action="' ,'" ');
            $uid5 = cut_str($page, 'name="uid5" value="','"');
            $uid = cut_str($page, 'name="uid" value="','"');
            $name = cut_str($page, 'name="name" value="','"');
            $pin = cut_str($page, 'name="pin" value="','"');
            $realuid = cut_str($page, 'name="realuid" value="','"');
            $realname = cut_str($page, 'name="realname" value="','"');
            $host = cut_str($page, 'name="host" value="','"');
            $ssserver = cut_str($page, 'name="ssserver" value="','"');
            $sssize = cut_str($page, 'name="sssize" value="','"');
            $dir = cut_str($page, 'name="dir" value="','"');
            $optiondir = cut_str($page, 'name="optiondir" value="','"');
            $pin_wm = cut_str($page, 'name="pin_wm" value="','"');
            $md5crypt = cut_str($page, 'name="md5crypt" value="','"');
            $realuid_free = cut_str($page, 'name="realuid_free" value="','"');
            $post = array ();
            $post['uid5'] = $uid5;
            $post['uid'] = $uid;
            $post['name'] = $name;
            $post['pin'] = $pin;
            $post['realuid'] = $realuid;
            $post['realname'] = $realname;
            $post['host'] = $host;
            $post['ssserver'] = $ssserver;
            $post['sssize'] = $sssize;
            $post['dir'] = $dir;
            $post['optiondir'] = $optiondir;
            $post['lsarrserverra']= "letitbit.net";
            $post['pin_wm']= $pin_wm;
            $post['md5crypt'] = $md5crypt;
            $post['realuid_free'] = $realuid_free;
            $post['pin_wm_tarif'] = "default";
            $post['submit_way_selection2'] = "Regular download";
            $Url = parse_url($Urlact);
            $page = $this->GetPage($Urlact, $cookie, $post, $link);
            if (preg_match('/http:\/\/r\d+\.letitbit\.net(:\d+)?\/download(\d+)?\/[^\/]+\/[^\/]+\/[^\/]+\/[^\'\"|<|\r|\n]+/i', $this->GetPage(urldecode(cut_str($page, 'downloadFree_linksV2=',';'))), $EZ)) {
                  $Dlink = trim($EZ[0]);
                  $Filename = basename($Dlink);
                  $this->RedirectDownload($Dlink, $Filename, 0, 0, 0, $Filename);
                exit;
            } else {           
            $t=explode(";", GetCookies($page));
            $cookie .=";". $t[0].";".$t[2];
            preg_match('%<form action="(.*)" method="post" id="dvifree">%', $page, $UT);
            $Urlact = $UT[1];
            $ac_http_referer = cut_str($page, 'name="ac_http_referer" value="','"');
            $rand = cut_str($page, 'name="rand" value="','"');
            unset($post);
            $post['uid5'] = $uid5;
            $post['uid'] = $uid;
            $post['name'] = $name;
            $post['pin'] = $pin;
            $post['realuid'] = $realuid;
            $post['realname'] = $realname;
            $post['host'] = $host;
            $post['ssserver'] = $ssserver;
            $post['sssize'] = $sssize;
            $post['dir'] = "";
            $post['optiondir'] = "";
            $post['lsarrserverra'] = 'letitbit.net';
            $post['pin_wm'] = $pin_wm;
            $post['md5crypt'] = $md5crypt;
            $post['realuid_free'] = $realuid_free;
            $post['pin_wm_tarif'] = "default";
            $post['submit_way_selection2'] = "Regular download";
            $post['ac_http_referer'] = $ac_http_referer;
            $post['links_sent'] = "1";
            $post['rand'] = $rand;
            $Url = parse_url($Urlact);
            $page = $this->GetPage($Urlact, $cookie, $post, $link);
            if(preg_match ( '%Wait for Your turn: <br/><span id="seconds" style="font-size:18px">(.*)</span>%', $page, $wait )){
                $countDown = $wait[1];
                insert_timer( $countDown, "Wait for Your turn.","",true );
            } else {
                preg_match ( '/seconds = ([0-9]+);/', $page, $wait);
                $countDown = $wait[1];
                insert_timer( $countDown, "Waiting link timelock.","",true );
            }
            preg_match("/ajax_check_url = '(.+)';/", $page, $temp);
            $tlink = $temp[1];
            $Url = parse_url($tlink);
            $page = $this->GetPage($tlink, $cookie, $post, $link);
            if (!preg_match('/http:(.*)/', $page, $dlink)) {
                html_error ( "The file is temporarily unavailable for download. Please try a little bit later");
            }
            $dwn = trim($dlink[0]);
            $Url = parse_url($dwn);
            $Filename = basename ($Url['path']);
            $this->RedirectDownload($dwn, $Filename, $cookie, 0, $tlink, $Filename);
        exit;
    }
}
private function DownloadPremium($link) {
    global $Referer, $premium_acc;
    $Url=parse_url($link);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""),$Referer, 0, 0, 0, $_GET["proxy"],$pauth);
    is_page($page);
	is_present($page,"404 Not Found","The requested file was not found");
    $cookie= GetCookies($page);
    $download_link = urldecode(cut_str($page,'download_link=',';'));
    $post['submit_way_selection1']="HIGH Speed Download";
	$post['way_selection']="1";
    $Url=parse_url($link);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""),$Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
    is_page($page);
    $act = "http://letitbit.net".cut_str ( $page ,'<form action="' ,'"' );
    $uid5 = cut_str($page,'name="uid5" value="','"');
    $uid = cut_str($page,'name="uid" value="','"');
    $name = cut_str($page,'name="name" value="','"');
    $pin = cut_str($page,'name="pin" value="','" />');
    $realuid = cut_str($page,'e="realuid" value="','"');
    $realname = cut_str($page,'="realname" value="','"');
    $host = cut_str($page,'name="host" value="','"');
    $ssserver = cut_str($page,'="ssserver" value="','"');
    $sssize = cut_str($page,'me="sssize" value="','"');
    $optiondir = cut_str($page,'me="optiondir" value="','"');
    unset($post);
    $post['pass']=$_GET ["premium_pass"] ? $_GET ["premium_pass"] : $premium_acc ["letitbit_net"] ["pass"];
    $post['uid5']=$uid5;
    $post['uid']=$uid;
    $post['name']=$name;
    $post['pin']=$pin;
    $post['realuid']=$realuid;
    $post['realname']=$realname;
    $post['host']=$host;
    $post['ssserver']=$ssserver;
    $post['sssize']=$sssize;
    $post['optiondir']=$optiondir;
    $post['pin_wm']='';
    $post['submit']='Download file';
    $Url=parse_url($act);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""),$Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
    is_page($page);
	is_present($page,"smscheckPassNotExists","Please Check Your Premium Account");
	preg_match ( "#src=\"(\/sms\/.*)\"#", $page, $dlink );
    $dlink = $dlink[1];
	$src = "http://letitbit.net$dlink";
    insert_timer( 5, "Wait for Redirect Download Link.","",true );
	$Url=parse_url($src);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""),$act, $cookie, $post, 0, $_GET["proxy"],$pauth);
    is_page($page);
    if(preg_match ( '%href="(.*)" style="font-size:16px; text-align:center;"%', $page, $dlink )){
    $Href = $dlink[1];
    }
    if(!$Href)
    {
    html_error ( "The file is temporarily unavailable for download. Please try a little bit later", 0 );
    }
    $Url = parse_url($Href);
    $FileName = basename($Href);
    if (function_exists(encrypt) && $cookie!=""){$cookie=encrypt($cookie);};
    insert_location("index.php?filename=".urlencode($FileName)."&force_name=".urlencode($FileName)."&host=".$Url["host"]."&port=".$Url["port"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&cookie=".urlencode($cookie)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($link).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
    }
}

/*************************\
 WRITTEN BY VinhNhaTrang 15-11-2010
 Fix the premium code by code by vdhdevil
 Fix the free download code by vdhdevil & Ruud v.Tony 25-3-2011
 Add alternatif free download code by Th3-882 25-3-2011
\*************************/
?>