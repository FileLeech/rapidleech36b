<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}
  
/*######################################
  # Indowebster.com Rapidleech Pluginz #
  #          blackriderz@olc           #
  #   http://blackriderz.web.id        #
  #                                    #
  ######################################*/
$idws = $_POST['idws'];
if($idws=="ok"){
$referer = $_POST['link'];
$Url = parse_url($_POST['link']);
$FileName = basename($Url["path"]);
insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&port=".$Url["port"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($referer)."&cookie=".urlencode($cookies)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".$redir2[1].($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
} else {
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), 0, 0, 0, 0, $_GET["proxy"],$pauth);
			is_page($page);
			preg_match_all('/Cookie: (.*);/U',$page,$temp);
			$cookie = $temp[1];
			$cookies = implode(';',$cookie);
			$link_step_1 = trim(cut_str($page, '<div style="float:right;"><a href="','"'));
			$link_step_1 = "/".$link_step_1;
		/* Step 1 */
		if($cookies){
$page = geturl("www.indowebster.com", 80, $link_step_1, $Referer, $cookies, 0, 0, $_GET["proxy"],$pauth);
is_page($page);
$action_url = cut_str($page, '<form action="','"');
$action_url = "http://www.indowebster.com/".$action_url;
$post = array();
preg_match("/<input type=\"hidden\" value=\"(.+)\" name=\"id\" \/>/",$page,$id);
preg_match("/<input type=\"hidden\" value=\"(.+)\" name=\"name\" \/>/",$page,$name);
$post["kuncis"] = cut_str($page, '<input type="hidden" value="','" name="kuncis"');
$post["id"] = $id[1];
$post["name"] = $name[1];
$post["button.x"] = rand(1,80);
$post["button.y"] = rand(1,20);
if($post){
/* step 2*/
	$Url = parse_url($action_url);
        $referer = $action_url;	
$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookies, $post, 0, $_GET["proxy"],$pauth);
is_page($page);
preg_match('/refresh: 0; url=(.+)/',$page,$dl);
$Link = trim($dl[1]);
if($Link){
print 	"<form method=\"post\" action=\"".$PHP_SELF.(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "")."\">$nn";
print   "<input type=\"hidden\" name=\"idws\" value=\"ok\">$nn";
print	"<input name=\"link\" value=\"$Link\" type=\"hidden\">$nn";
print	"<input name=\"submit\" value=\"Download\" type=\"submit\"></form>";
} else {
echo "File Not Found";
}
}
}
}
/*#########################
  # Fixed by Blackriderz  #
  #########################*/
  ?>