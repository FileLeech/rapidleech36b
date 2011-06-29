<?php
if (!defined('RAPIDLEECH')) {
	require_once("404.php");
	exit;
}

class maknyos_com extends DownloadClass {

    public function Download($link) {
        global $Referer;
            $page = $this->GetPage($link, "lang=indonesia");//force the language into indonesian
            is_present($page, "No such file with this filename", "The file doesn't exist, please check ur link!");
            $cookie = GetCookies($page). "lang=indonesia";

            $id = cut_str($page, 'name="id" value="','"');
            $FileName = cut_str($page, 'name="fname" value="','"');

            $post = array();
            $post['op'] = "download1";
            $post['usr_login'] = "";
            $post['id'] = $id;
            $post['fname'] = $FileName;
            $post['referer'] = $link;
            $post['method_free'] = "Download Gratis";
            $page = $this->GetPage($link, $cookie, $post, $link);
            is_present($page, "This file reached max downloads limit", "This file reached max downloads limit, please retry another time!");
            $cookie = GetCookies($page) . "; lang=indonesia";
            if (preg_match('#(\d+)</span> detik#', $page, $wait)) {
                $this->CountDown($wait[1]);
            }
            //Let's kick their annoyin' captcha \m/
            if (preg_match_all("#<span style='[^\d]+(\d+)[^\d]+\d+\w+;'>\W+(\d+);</span>#", $page, $temp)) {
                for ($i=0;$i<count($temp[1])-1;$i++){
                    for ($j=$i+1;$j<count($temp[1]);$j++){
                        if ($temp[1][$i]>$temp[1][$j]){
                            $n=1;
                            do {
                                $tmp=$temp[$n][$i];
                                $temp[$n][$i]=$temp[$n][$j];
                                $temp[$n][$j]=$tmp;
                                $n++;
                            } while ($n<=2);
                        }
                    }
                }
                $captcha="";
                foreach($temp[2] as $value) {
                    $captcha.=chr($value);
                }
            }
            $rand = cut_str($page, 'name="rand" value="','"');
            unset($post);
            $post['op'] = "download2";
            $post['id'] = $id;
            $post['rand'] = $rand;
            $post['referer'] = $link;
            $post['method_free'] = "Download Gratis";
            $post['method_premium'] = "";
            $post['code'] = $captcha;
            $post['down_script'] = "1";
            $page = $this->GetPage($link, $cookie, $post, $link);
            if (!stristr ( $page, "Location:" )) {
                html_error("Sorry, download link couldn't be found. Contact the author n give the link which u have this error!");
            }
            $dlink = trim (cut_str($page, "Location: ", "\n" ));
            if (!$FileName) $FileName = basename ($Url['path']);
            $this->RedirectDownload($dlink, $FileName, $cookie, 0, $link);
    }
}

//Maknyos free download plugin by Ruud v.Tony 22-06-2011, taken captcha code from http://www.rapidleech.com/topic/11563-finde-captcha-by/
?>
