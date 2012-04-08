<?php
if (! defined ( 'RAPIDLEECH' )) {
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
        is_present($page, 'Reason for deletion', 'This file is either removed due to Copyright Claim, has Expired or is deleted by the uploader.');

        if (preg_match('#(\d+)</span> seconds#', $page, $wait)) {
            $this->CountDown($wait[1]);
        }

        $id = cut_str($page, 'name="id" value="','"');
        $FileName = cut_str($page, 'name="fname" value="','"');

        $post = array();
        $post['op'] = cut_str($page, 'name="op" value="','"');
        $post['usr_login'] = '';
        $post['id'] = $id;
        $post['fname'] = $FileName;
        $post['referer'] = $link;
        $post['date'] = '985125c92140d055d8e841ce08c6cf80';
        $post['method_free'] = 'Slow Speed Download';
        $page = $this->GetPage($link, 0, $post, $link);
        unset ($post);
        $post['op'] = 'download2';
        $post['id'] = $id;
        $post['rand'] = cut_str($page, 'name="rand" value="','"');
        $post['referer'] = $link;
        $post['method_free'] = 'Slow Speed Download';
        $post['method_premium'] = '';
        $post['down_script'] = '1';
        $post['x'] = rand(0,127);
        $post['y'] = rand(0,36);
        $page = $this->GetPage($link, 0, $post, $link);
        if (!preg_match('%<a href="(.+)" target="_blank">%', $page, $dl)) html_error('Error: Download link not found!');
        $Url = parse_url($dl[1]);
        if(!$FileName) $FileName = basename($Url['path']);
        $this->RedirectDownload($dl[1], $FileName, 0, 0, $link);
        exit;
    }
}

/*
 * usershare.net free download plugin by Ruud v.Tony 03-08-2011
 */
?>
