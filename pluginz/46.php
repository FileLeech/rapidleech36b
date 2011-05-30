<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
is_page($page);
if (preg_match('/var timer=([0-9]+);/', $page, $count)) {
	$countDown = $count[1];
	insert_timer($countDown, "Waiting link timelock");
}
preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
$cookie = $temp[1];
$cook = implode(';',$cookie);
preg_match('/<form id="free-down" action="(.*)" method="post">/',$page,$formurl);
$formurl = trim($formurl[1]);
if (!$formurl) {
	html_error('Error getting form link');
}
$post['down-type'] = 'free';
$post['type'] = '';
preg_match('/<input type="hidden" name="dtoken" value="(.*)" \/>/',$page,$dtoken);
$dtoken = trim($dtoken[1]);
if (!$dtoken) {
	html_error('Milledrive.com must have changed their code');
}
$post['dtoken'] = $dtoken;
$Url = parse_url($formurl);
$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $LINK, $cook, $post, 0, $_GET["proxy"],$pauth);
$formurl2 = $formurl;
preg_match('/<form id="free-down" action="(.*)" method="post">/',$page,$formurl);
$formurl = trim($formurl[1]);
if (!$formurl) {
	html_error('Error getting form link');
}
preg_match('/<input type="hidden" name="ticket" value="(.*)" \/>/',$page,$ticket);
$ticket = trim($ticket[1]);
if (!$ticket) {
	html_error('Milledrive.com must have changed their code');
}
preg_match('/<input type="hidden" name="down-url" value="(.*)" \/>/',$page,$down);
$down = trim($down[1]);
if (!$down) {
	html_error('Milledrive.com must have changed their code');
}
unset($post);
$post['ticket'] = $ticket;
$post['down-url'] = $down;
$Referer = $formurl2;
$link = $formurl;
$Url = parse_url($formurl);
$FileName = !$FileName ? basename($Url["path"]) : $FileName;
$loc = "{$_SERVER['PHP_SELF']}?filename=" . urlencode ( $FileName ) . 
	"&host=" . $Url ["host"] . "&port=" . $Url ["port"] . "&path=" . 
	urlencode ( $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : "") ) . 
	"&referer=" . urlencode ( $Referer ) . "&email=" . ($_GET ["domail"] ? $_GET ["email"] : "") . 
	"&partSize=" . ($_GET ["split"] ? $_GET ["partSize"] : "") . "&method=" . $_GET ["method"] . 
	"&proxy=" . ($_GET ["useproxy"] ? $_GET ["proxy"] : "") . "&saveto=" . $_GET ["path"] . 
	"&link=" . urlencode ( $link ) . ($_GET ["add_comment"] == "on" ? "&comment=" . 
	urlencode ( $_GET ["comment"] ) : "") . $auth . ($pauth ? "&pauth=$pauth" : "") . 
	"&cookie=" . urlencode($cook) .
	"&post=" . urlencode ( serialize ( $post ) ).(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "");
insert_location ( $loc );
//$Url = parse_url($formurl);
//$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], $formurl2, $cook, $post, 0, $_GET["proxy"],$pauth);
//var_dump(nl2br(htmlentities($page)));
?>