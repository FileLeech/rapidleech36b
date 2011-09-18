<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
class fileape_com extends DownloadClass{

    public function Download($link){
        global $premium_acc;
        if (($_REQUEST['premium_acc'] == 'on' && $_REQUEST['premium_user'] && $_REQUEST['premium_pass']) || ($_REQUEST['premium_acc'] == 'on' && $premium_acc['fileape']['user'] && $premium_acc['fileape']['pass'])) {
            $this->Premium($link);
        } else {
            $this->Free($link);
        }
    }

    private function Free($link) {
        $page=$this->GetPage($link);
        is_present($page,"This file is either temporarily unavailable or does not exist", "This file is either temporarily unavailable or does not exist");
        if (!preg_match("#Location: (.*)#", $page,$redirect)){
            html_error("Error 1:Plugin is out of date");
        }
        $page=$this->GetPage($redirect[1]);
        insert_timer(15);
        if (!preg_match("#window.location = '(.*)'#",$page,$dlink)){
            html_error("Error 2:Plugin is out of date", 0);
        }
        $page=$this->GetPage($dlink[1]);
        if (!preg_match('#(http:\/\/\w+.fileape.com\/nu\/.*)"#',$page,$dlink)){
            html_error("Error 3: Plugin is out of date");
        }
        $Url=parse_url(trim($dlink[1]));
        $FileName=basename($Url['path']);
        $this->RedirectDownload(trim($dlink[1]), $FileName);
        //echo "<pre>";var_dump(nl2br(htmlentities($page)));echo "</pre>";exit;
        exit;
    }

    private function Premium($link) {
        html_error('Not supported now!');
    }
}

//by vdhdevil
?>
