<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class dailymotion_com extends DownloadClass {

    public function Download($link) {
        $page = $this->GetPage($link);
        $FileName = trim(cut_str($page, 'vs_videotitle:"', '"'));
        $FileName = str_replace(" ", ".", $FileName) . ".mp4";
        $Cookies = GetCookies($page);
        if (!preg_match("#(http%3A%5C%2F%5C%2Fwww.dailymotion.com%5C%2Fcdn%5C%2FH264.+)%22%7D%7D%2C%22#", $page, $temp)) {
            html_error("Error 0x00: Plugin is out of date");
        }
        $temp = str_replace("\\", "", urldecode($temp[1])) . "&redirect=0";
        $page = $this->GetPage($temp, $Cookies);
        if (!preg_match('#http://.+dailymotion.com/video/[^"]+#', $page, $dlink)) {
            html_error("Error 0x01: Plugin is out of date");
        }
        $this->RedirectDownload($dlink[0], $FileName, $Cookies, 0, $temp);
        exit;
    }

}

?>
