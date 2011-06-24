<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
class fileape_com extends DownloadClass{
    public function Download($link){
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
}

//by vdhdevil
?>
