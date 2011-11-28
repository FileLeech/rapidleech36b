<?php
if (!defined('RAPIDLEECH')) {
    require_once ("index.html");
    exit;
}

class usershare_net extends DownloadClass {

    public function Download($link) {
        global $premium_acc;
        if (($_REQUEST['premium_acc'] == 'on' && $_REQUEST['premium_user'] && $_REQUEST['premium_pass']) || ($_REQUEST['premium_acc'] == 'on' && $premium_acc['usershare_net']['user'] && $premium_acc['usershare_net']['pass'])) {
            html_error('Not supported now');
        } else {
            $this->Free($link);
        }
    }

    private function Free($link) {
        $page = $this->GetPage($link);
        is_present($page, 'The file you were looking for could not be found, sorry for any inconvenience.');

        if (preg_match('#(\d+)</span> seconds#', $page, $wait)) {
            $this->CountDown($wait[1]);
        }

        $id = cut_str($page, 'name="id" value="', '"');
        $rand = cut_str($page, 'name="rand" value="', '"');

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
            $captcha = "";
            foreach ($temp[2] as $value) {
                $captcha.=chr($value);
            }
        }
        $post = array();
        $post['op'] = 'download2';
        $post['id'] = $id;
        $post['rand'] = $rand;
        $post['referer'] = $link;
        $post['method_free'] = '';
        $post['method_premium'] = '';
        $post['code'] = $captcha;
        $post['down_direct'] = '1';
        $page = $this->GetPage($link, 0, $post, $link);
        is_present($page, 'Wrong captcha');
        if (!preg_match('@http:\/\/id(\d+)?\.usershare\.net(:\d+)?\/d\/[^"]+@i', $page, $dl)) html_error('Error: Download link not found!');
        $dlink = trim($dl[0]);
        $FileName = basename($dlink, PHP_URL_PATH);
        $this->RedirectDownload($dlink, $FileName, 0, 0, $link);
        exit;
    }

}

/*
 * usershare.net free download plugin by Ruud v.Tony 03-08-2011
 * fixed by Ruud v.Tony 25-11-2011
 */
?>
