<?php
if (!defined('RAPIDLEECH')) {
    require_once("index.html");
    exit();
}

class zomgupload_com extends DownloadClass {

    public function Download($link) {
        global $Referer;
            $page = $this->GetPage($link);
            is_present($page, "File Not Found", "The file you were looking for could not be found");

            $id = cut_str($page, 'name="id" value="','"');
            $FileName = cut_str($page, 'name="fname" value="','"');

            $post = array();
            $post['op'] = "download1";
            $post['usr_login'] = "";
            $post['id'] = $id;
            $post['fname'] = $FileName;
            $post['referer'] = $link;
            $post['method_free'] = " ";
            $page = $this->GetPage($link, 0, $post, $link);
            $rand = cut_str($page, 'name="rand" value="','"');
            if (preg_match('#(\d+)</span> seconds#', $page, $wait)) {
                $this->CountDown($wait[1]);
            }
            if (preg_match_all("#<span style='[^\d]+(\d+)[^\d]+\d+\w+;'>(\d+)</span>#", $page, $temp)) {
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
                    $captcha.=$value;
                }
            }
            unset($post);
            $post['op'] = "download2";
            $post['id'] = $id;
            $post['rand'] = $rand;
            $post['referer'] = $link;
            $post['method_free'] = " ";
            $post['method_premium'] = "";
            $post['code'] = $captcha;
            $post['down_direct'] = "1";
            $page = $this->GetPage($link, 0, $post, $link);
            if (!preg_match('#(http:\/\/.+zomgupload\.com(:\d+)?\/files\/\d+\/[^"]+)"#', $page, $dl)) {
                html_error("Sorry, Download link not found, contact the author n give the link which u have this error");
            }
            $dlink = trim($dl[1]);
            $Url = parse_url($dlink);
            if (!$FileName) $FileName = basename ($Url['path']);
            $this->RedirectDownload($dlink, $FileName, 0, 0, $link);
    }
}

//zomgupload free download plugin by Ruud v.Tony 28-06-2011
?>
