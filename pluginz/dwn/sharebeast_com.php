<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

class sharebeast_com extends DownloadClass {

    public function Download($link) {

        $page = $this->GetPage($link);
        is_present($page, "File Not Found", "The file you were looking for could not be found");

        $id = cut_str($page, 'name="id" value="','"');
        $rand = cut_str($page, 'name="rand" value="','"');

        $post = array();
        $post['op'] = "download2";
        $post['id'] = $id;
        $post['rand'] = $rand;
        $post['referer'] = $link;
        $post['method_free'] = "";
        $post['method_premium'] = "";
        $post['down_script'] = "1";
        $post['x'] = rand(0, 141);
        $post['y'] = rand(0, 26);
        $page = $this->GetPage($link, 0, $post, $link);
        if (!preg_match('/Location: (.+)/i', $page, $dl)) {
            html_error("Error : Download link not found!!!");
        }
        $dlink = trim($dl[1]);
        $Url = parse_url($dlink);
        $FileName = basename($Url['path']);
        $this->RedirectDownload($dlink, $FileName, 0, 0, $link);
        exit;
    }

}
/**************************************************\
Sharebeast download plugin by Ruud v.Tony 05-04-2011
\**************************************************/
?>