<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class kickload_com extends DownloadClass {

    public function Download($link) {

        $page = $this->GetPage($link);
        is_present($page, "This file couldn't be found!", "This file doesn't exit or has been deleted.");

        $post = array();
        $post['free_download'] = "1";
        $post['free_download1.x'] = rand(0,83);
        $post['free_download1.y'] = rand(0,20);
        $post['free_download1'] = "1";
        $page = $this->GetPage($link, 0, $post, $link);
        preg_match('/<div id="time2wait" style="display:inline;">(\d+)<\/div>/', $page, $wait);
        $countdown = $wait[1];
        insert_timer($countdown, "Download is being prepared.", "", true);
        $ticket = cut_str($page, 'id="ticket" value="','"');
        unset($post);
        $post['ticket'] = $ticket;
        $post['x'] = rand(0,78);
        $post['y'] = rand(0,19);

        if(!preg_match('/form method="post" action="(.*)"/', $page, $dl)) {
            html_error("Error, Download link not found bla...bla...bla...he3x");
        }

        $dlink = trim($dl[1]);
        $Url = parse_url($dlink);
        $FileName = basename($Url['path']);
        $this->RedirectDownload($dlink, $FileName, 0, $post, $link);
        exit;
    }

}

//Kickload Free Download Plugin by Ruud v.Tony 05-04-2011
?>
