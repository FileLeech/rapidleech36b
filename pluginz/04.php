<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}
				
$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"], 0, 0, 0, 0, $_GET["proxy"],$pauth);
is_page($page);
				
is_present($page,"There are no free download slots available");
is_present($page,"Sorry, the file you requested is not available");
//$countDown=trim(cut_str($page,"var count = ",";"));
//insert_timer($countDown, "File is being prepared.","",true);

$code_enc = cut_str($page,'function enc(text){','}</script>');
if (!$code_enc)
	{
	html_error('Error getting link');
	}
				
$par1 = cut_str($code_enc,'Array();',';');
list($tmp,$par1) = explode('=',$par1);
				
$par2 = cut_str($code_enc,"='","';");
				
$dec_text = cut_str($page,"enc(base64ToText('","')));");
				
$d64text = sendspace_base64ToText($dec_text);
$urlnew = sendspace_enc($par1,$par2,$d64text);

is_notpresent($urlnew,'href="','Error decrypting URL page');
				
$Href = cut_str($urlnew,'href="','" onclick');
if (!$Href)
	{
	html_error('Error decrypting URL page');
	}

$Url = parse_url($Href);
$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
if(preg_match('/location: (.*)/im', $page, $loc)){
	$Href = 'http://'.$Url["host"].trim($loc[1]);
}
$Url = parse_url($Href);
$FileName = !$FileName ? basename($Url["path"]) : $FileName;

insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "")."&auth=".$auth.($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
			
function sendspace_enc($par1,$par2,$text)
  {
  $myarr = array();

  for ($i = 0; $i < $par1; $i++)
    {
		$myarr[$i] = $i;
    }
	
  for ($j = 0,$k = $j,$l = $myarr; $j < $par1; $j++)
    {
		$k = (ord($par2[$j%strlen($par2)])+$l[$j]+$k)%$par1;
		$m = $l[$j];
		$l[$j] = $l[$k];
		$l[$k] = $m;
		$l[$k] = $l[$k]^5;
    }

  for ($res = '', $k = 0,$n = 0;$n < strlen($text); $n++)
    {
		$o = $n%$par1;
		$k = ($l[$o]+$k)%$par1;
		$p = $l[$o];
		$l[$o] = $l[$k];
		$l[$k] = $p;
		$res.= chr(ord($text[$n])^$l[($l[$o]+$l[$k])%$par1]);
    }

  return $res;
  }

function sendspace_base64ToText($t)
  {
	$b64s = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_"';
  $r = '';
  $m = 0;
  $a = 0;
  $l = strlen($t);
    
  for($n = 0; $n<$l; $n++)
    {
    $c = strpos($b64s,$t[$n]);
    if($c >= 0)
      {
      if($m)
       	{
       	$d = ($c << (8-$m))& 255 | $a;
        $r.= chr($d);
        }
      $a = $c >> $m;
      $m+= 2;
      if($m == 8)
        {
        $m = 0;
        }
      }
    }
    
  return $r;
  }
?>