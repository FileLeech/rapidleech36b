<?php
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}


if (($_GET["premium_acc"] == "on" && $_GET["premium_user"] && $_GET["premium_pass"]) || ($_GET["premium_acc"] == "on" && $premium_acc["turbobit_net"]["user"] && $premium_acc["turbobit_net"]["pass"]))
	{
	    $user = ($_GET["premium_user"] ? $_GET["premium_user"]  : $premium_acc["turbobit_net"]["user"]);
	    $pass = ($_GET["premium_pass"] ? $_GET["premium_pass"]  : $premium_acc["turbobit_net"]["pass"]);

$ref="http://turbobit.net/";
$post["user%5Blogin%5D"]=$user;
$post["user%5Bpass%5D"]=$pass;
$post["user%5Bmemory%5D"]="on";
$post["user%5Bsubmit%5D"]="Login";

$Url=parse_url("http://turbobit.net/user/login");

$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $ref, 0, $post, 0, $_GET["proxy"],$pauth);
is_page($page);

$cookie = GetCookies($page);

	$Url=parse_url($ref);

	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $ref, $cookie, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

	if( stripos( $page ,$user)!== false ){

	}else{ html_error(" Login Failed. Please check your login details ",0); }


	$Url = parse_url($LINK);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, 0, 0, $_GET["proxy"],$pauth);
	//file_put_contents("turbobit_2.txt", $page);
	is_page($page);

	is_present($page, "The file you are looking for is not available", "The file has been deleted");

	$dsrg=cut_str($page,'class="download-file">','Download it!');
	$durl=cut_str($dsrg,'href="','"');
    if(!$durl){$durl="http://turbobit.net//download/redirect/".cut_str($page,"http://turbobit.net//download/redirect/","'");}

	$Url = parse_url($durl);
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, 0, 0, $_GET["proxy"],$pauth);
    $locat=cut_str ($page ,"Location: ","\r");
	$FileName=cut_str($locat,'name=','&');
	$Url=parse_url($locat);

	insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));
}
else
{
$tbit = $_POST['tbit'];
if($tbit == "ok"){
	$post = array();

	if ($_POST["captcha_response"] )
	{
	$post["captcha_response"] = $_POST["captcha_response"];
	$post["captcha_type"]="kohana";
	$post["captcha_subtype"]="";
	}else{

    $post["recaptcha_challenge_field"] = $_POST["recaptcha_challenge_field"];
    $post["recaptcha_response_field"] = $_POST["recaptcha_response_field"];
	$post["captcha_type"]="recaptcha";
	$post["captcha_subtype"]="";
	}


	$cookie = $_POST["cookie"];
	$Referer = $_POST["referer"];
    $link= $_POST["link"];



	$Url = parse_url($link);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $link, $cookie, $post, 0, $_GET["proxy"],$pauth);
	is_page($page);
    $wtime=cut_str($page,'limit: ',',');
    if (!$wtime) html_error ("The captcha code inserted not match",0);
	insert_timer($wtime, "<b>Timer :</b>");
    $Url["path"]=str_replace("free","timeout",$Url["path"]);

	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $link, $cookie, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

//	preg_match('/http:\/\/[^"\']+/i', $page, $go);
$go="http://turbobit.net".cut_str ( $page , "href='" ,"'" );
	$Url = parse_url($go);
	//$FileName=cut_str($go[0],'name=','&');


$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $link, $cookie, 0, 0, $_GET["proxy"],$pauth);
is_page($page);

$locat=cut_str ($page ,"Location: ","\r");
$FileName=basename($locat);

$Url=parse_url($locat);

insert_location("$PHP_SELF?filename=".urlencode($FileName)."&host=".$Url["host"]."&path=".urlencode($Url["path"].($Url["query"] ? "?".$Url["query"] : ""))."&referer=".urlencode($Referer)."&email=".($_GET["domail"] ? $_GET["email"] : "")."&partSize=".($_GET["split"] ? $_GET["partSize"] : "")."&method=".$_GET["method"]."&proxy=".($_GET["useproxy"] ? $_GET["proxy"] : "")."&saveto=".$_GET["path"]."&link=".urlencode($LINK).($_GET["add_comment"] == "on" ? "&comment=".urlencode($_GET["comment"]) : "").($pauth ? "&pauth=$pauth" : "").(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : ""));

}else{
     $page = geturl("turbobit.net",80, "/", 0, 0, 0, 0, $_GET["proxy"],$pauth);
    preg_match_all('/Set-Cookie: (.*);/U',$page,$temp);
    $cookie = $temp[1][2]."; ".$temp[1][1];
    $lang=parse_url("http://turbobit.net/en");
    $page = geturl($lang["host"], $lang["port"] ? $lang["port"] : 80, $lang["path"].($lang["query"] ? "?".$lang["query"] : ""), 0, $cookie, 0, 0, $_GET["proxy"],$pauth);
    $vpath=explode("/",$Url["path"]);
    $ipath="/".$vpath[1];
    $temp= str_replace("/","/download/free/",$ipath);
  //  $tmp =explode(".",$temp);
   // $Url["path"]=$tmp[0];
    $lk=  "http://".$Url["host"].$temp;
    $Url=parse_url($lk)  ;

   /*
	unset($Url);
	$Url=parse_url($link);
    */
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

	if (strpos($page,"The limit of connection was succeeded for your IP")){
			  $wtime=cut_str($page,'limit: ',',');

			  insert_timer($wtime, "<b>The limit of connection was succeeded for your IP Wait:</b>");
	  }


if( stripos( $page ,"recaptcha")!== false ){

	$key="6LcTGLoSAAAAAHCWY9TTIrQfjUlxu6kZlTYP50_c";
	$Url=parse_url("http://api.recaptcha.net/challenge?k=$key");
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, 0, 0, 0, $_GET["proxy"],$pauth);
			is_page($page);
			is_present($page,"Expired session", "Expired session . Go to main page and reattempt", 0);

		$locat=cut_str ($page ,"Location: ","\r");
	$Url=parse_url($locat);
	$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, 0, 0, 0, $_GET["proxy"],$pauth);
	is_page($page);

	$ch=cut_str ( $page ,"challenge : '" ,"'" );
	$chsite=cut_str ( $page ,"server : '" ,"'" );
			if($ch){
			$Url=parse_url($chsite."image?c=".$ch);
			$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
			$headerend = strpos($page,"\r\n\r\n");
			$pass_img = substr($page,$headerend+4);
		  $imgfile=$download_dir."ifile_captcha.jpg";
		   if (file_exists($imgfile)){ unlink($imgfile);}
			write_file($imgfile, $pass_img);
			}else{
			html_error("Error get captcha", 0);
			}

		if($newcaptcha){
         print    "<b>The code is wrong. Please re-enter again:</b><br>$nn";
        }else{
         print    "<b>Please enter code:</b><br>$nn";
        }
	print 	"<form method=\"post\" action=\"".$PHP_SELF.(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "")."\">$nn";

	print	"<img src=\"$imgfile\">$nn";
	print	"<input name=\"cookie\" value=\"$cookie\" type=\"hidden\">$nn";
	print	"<input name=\"tbit\" value=\"ok\" type=\"hidden\">$nn";
	print	"<input name=\"link\" value=\"$lk\" type=\"hidden\">$nn";
	print	"<input name=\"recaptcha_challenge_field\" value=\"$ch\" type=\"hidden\">$nn";
	print	"<input name=\"recaptcha_response_field\" type=\"text\" >";
	print	"<input name=\"Submit\" value=\"Submit\" type=\"submit\"></form>";

}else{

	$img_link=cut_str($page,'Captcha" src="','"');

	  if ($img_link){

      // $capimg = $PHP_SELF."?image=".urlencode($img_link)."&referer=".urlencode($referer)."&cookie=".urlencode($cookie);
        $Url = $img_link;

	  $ch = curl_init($Url);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.14) Gecko/20080404 Firefox/2.0.0.14");
	  curl_setopt($ch, CURLOPT_HEADER, 1);
      curl_setopt($ch, CURLOPT_REFERER, $lk);
	  if ($cookie){curl_setopt($ch, CURLOPT_COOKIE, $cookie) ;}
	  $page = curl_exec($ch);
	  curl_close($ch);

     //   $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $referer, $cookie, 0, 0, $_GET["proxy"],$pauth);
    //   is_page($page);
	//
       $headerend = strpos($page,"PNG");
        $pass_img = substr($page,$headerend-1);
        $imgfile=$download_dir."turbobit_captcha.png";
       if (file_exists($imgfile)){ unlink($imgfile);}
        write_file($imgfile, $pass_img);
        }else{
        html_error("Error get captcha", 0);
        }


			print 	"<form method=\"post\" action=\"".$PHP_SELF.(isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "")."\">$nn";
			print	"<b>Please enter code:</b><br>$nn";
			print	"<img src=\"$imgfile\">$nn";
			print	"<input name=\"referer\" value=\"$referer\" type=\"hidden\">$nn";
		   print    "<input name=\"link\" value=\"$lk\" type=\"hidden\">$nn";
			print	"<input name=\"cookie\" value=\"$cookie\" type=\"hidden\">$nn";
			print	"<input name=\"tbit\" value=\"ok\" type=\"hidden\">$nn";
			print	"<input name=\"captcha_response\" type=\"text\" >";
			print	"<input name=\"Submit\" value=\"Submit\" type=\"submit\"></form>";
}


}
}

/********************************\
written by kaox 18-sep-2009
update by kaox 31-aug-2010
\********************************/

?>
