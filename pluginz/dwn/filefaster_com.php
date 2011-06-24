<?php
if (!defined('RAPIDLEECH')) {
    require_once("index.html");
    exit;
}

class filefaster_com extends DownloadClass {
    public function Download($link) {
        global $Referer;
            $page = $this->GetPage($link);
            is_present($page, "No such file with this filename", "No such file with this filename");

            $id = cut_str($page, 'name="id" value="','"');
            $FileName = cut_str($page, 'name="fname" value="','"');

            $post = array();
            $post['op'] = "download1";
            $post['usr_login'] = "";
            $post['id'] = $id;
            $post['fname'] = $FileName;
            $post['referer'] = $link;
            $post['method_free'] = "Free Download";
            $page = $this->GetPage($link, 0, $post, $link);
            if (preg_match('#(\d+)</b></font></span> seconds#', $page, $wait)) {
                $this->CountDown($wait[1]);
            }
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
            unset ($post);
            $post['op'] = "download2";
            $post['id'] = $id;
            $post['rand'] = $rand;
            $post['referer'] = $link;
            $post['method_free'] = "Free Download";
            $post['method_premium'] = "";
            $post['code'] = $captcha;
            $post['down_script'] = "1";
            $page = $this->GetPage($link, 0, $post, $link);
            if (!stristr ( $page, "Location:" )) {
                html_error("Sorry, download link couldn't be found. Contact the author n give the link which u have this error!");
            }
            $dlink = trim (cut_str($page, "Location: ", "\n" ));
            if (!$FileName) $FileName = basename ($Url['path']);
            $this->RedirectDownload($dlink, $FileName, 0, 0, $link);
    }
}

//filefaster free download plugin by Ruud v.Tony 23-06-2011
?>
