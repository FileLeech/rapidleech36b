<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class usershare_net extends DownloadClass {

    public function Download($link) {
        $page = $this->GetPage($link);
        is_present($page, "Reason for deletion", "This file is either removed due to Copyright Claim, has Expired or is deleted by the uploader.");

        if (!preg_match('#(http://.*:443.+)">#', $page, $dlink)) {
            html_error("Error: Plugin is out of date");
        }
        $Url = parse_url(trim($dlink[1]));
        $FileName = basename($Url['path']);
        $this->RedirectDownload(trim($dlink[1]), $FileName);
        exit;
    }

}

//by vdhdevil
?>
