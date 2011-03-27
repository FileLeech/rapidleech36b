<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class enterupload_com extends DownloadClass {

    public function Download($link) {

        $page = $this->GetPage($link);
        is_present($page, "File Not Found","File Not Found");
        
        $id = cut_str($page, 'name="id" value="','"');
        $fname = cut_str($page, 'name="fname" value="','"');

        $post = array ();
        $post['op'] = "download1";
        $post['usr_login'] = "";
        $post['id'] = $id;
        $post['fname']= $fname;
        $post['referer'] = "";
        $post['method_free']= "Free Download";
        $page = $this->GetPage($link, 0, $post, 0);
        is_present($page,"You can download files up to 400 Mb only","You can download files up to 400 Mb only");
        if (preg_match("#You have to wait (\d+) minutes, (\d+) seconds till next download#",$page,$message)){
            html_error($message[0]);
        }
        if (preg_match("#(\d+)</span> seconds#",$page,$wait)){
            insert_timer($wait[1]);
        } else {
            insert_timer(50);
        }
        $rand = cut_str($page, 'name="rand" value="','"');
        unset ($post);
        $post['op']="download2";
        $post['id'] = $id;
        $post['rand'] = $rand;
        $post['referer'] = $link;
        $post['method_free'] = "Free Download";
        $post['method_premium']="";
        $post['down_direct']="1";
        $page = $this->GetPage($link, 0, $post, $link);
        if (preg_match('#(http://.*:8080.*)" #', $page, $dlink)){
            $dwn = trim($dlink[1]);
        } else {
            html_error("Download Link Not Found");
        }

        $Url=parse_url($dwn);
        $FileName=basename($Url['path']);
        $this->RedirectDownload($dwn, $FileName, 0, 0, $link, $FileName);
        exit;

    }

}

//Enterupload free download plugin by Ruud v.Tony 28 Feb 2011
?>