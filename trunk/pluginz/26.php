<?php
if (!defined('RAPIDLEECH'))
  {require_once("404.php");exit;}

$debug = array(
   "cookie"   => false,
   "req"      => false,
   "chiper"   => false,
   "lastlink" => false,
   "stop"     => false,
 );


if($debug["req"]) showDeb($_REQUEST, 0, "_REQ");

if (($_GET["premium_acc"] == "on" && $_GET["premium_user"] && $_GET["premium_pass"]) || ($_GET["premium_acc"] == "on" && $premium_acc["mediafire"]["user"] && $premium_acc["mediafire"]["pass"]))
{
	//////////////////////////////////////////////////////////// START PREMIUM /////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////// END PREMIUM ///////////////////////////////////////////////////////////////
}
else
{
	//////////////////////////////////////////////////////////// START FREE /////////////////////////////////////////////////////////////
    if($_POST["step"]){
     $cookie=$_POST["cookie"];
     $post["recaptcha_challenge_field"]=$_POST["ch"];
     $post['recaptcha_response_field']=urlencode($_POST['captcha']);
     $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, $post, 0, $_GET["proxy"],$pauth);
     is_page($page);
    }

 
    if($_POST["passfile"])
    {
      $post=array();
      $post["downloadp"]=$_POST["downloadp"];
      $cookie=$_POST["cookie"];
      $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cookie, $post, 0, $_GET["proxy"],$pauth);
      is_page($page);
    }else{

      $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
      is_page($page);
      $cookie = GetCookies($page); 
      
	  if($debug["cookie"]) showDeb($cookie, 0, "cookie");
	  
      if(preg_match('/Location: (.*)/i', $page, $redir))
      {
        preg_match('/Location:.*error/i', $page) ? html_error("The Link is Invalid or the File is Deleted.", 0) : '';
        $Href = trim($redir[1]);
        if( strpos( $Href ,"http://mediafire.com")!== false ){
        }else{
          $Href="http://www.mediafire.com".$Href;
        }
        $Url = parse_url($Href);
        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
        is_page($page);
      }
     }
    $ev = cut_str($page, "Eo(); ", "; ").";"; 
    if(preg_match("/dh\('(.*)'\)/i", $ev, $pass)){
      
      echo("<div style=\"text-align: center\"><br><br>");
      
      if($pass[1])
      {
          echo ("<div style=\"text-align: center\">The password  '".$pass[1]."'  is INVALID please correct the error.</div>");
      }else
      {
          echo ("<div style=\"text-align: center\">The file is password protect, please enter the password</div>");
      }
      $code = '<div style="text-align: center"><form method="post" action="'.$PHP_SELF.'">'.$nn;
      $code .= '<input type="text" name="downloadp"> <input type="submit" value="Send password">'.$nn;
      $code .= '<input type="hidden" name="cookie" value="'.$cookie.'">'.$nn;
      $code .= '<input type="hidden" name="passfile" value="true">'.$nn;
      $code .= '<input type="hidden" name="link" value="'.urlencode($LINK).'">'.$nn;
      $code .= '</form></div>';
      echo $code;
      die;
    }
    
    if( strpos( $page ,"GetCaptcha('")!== false ){
$Url=parse_url("http://api.recaptcha.net/challenge?k=6LextQUAAAAAALlQv0DSHOYxqF3DftRZxA5yebEe");
$page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, 0, 0, 0, $_GET["proxy"],$pauth);
        is_page($page);
        is_present($page,"Expired session", "Expired session . Go to main page and reattempt", 0);
        
        $cook = GetCookies($page);
        $ch = cut_str ( $page ,"challenge : '" ,"'" );
        $Url=parse_url("http://api.recaptcha.net/image?c=".$ch);
        $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $Referer, $cook, 0, 0, $_GET["proxy"],$pauth);
        $headerend = strpos($page,"\r\n\r\n");
        $pass_img = substr($page,$headerend+4);
        $imgfile=$download_dir."mediafire_captcha.jpg";
        
        if (file_exists($imgfile)){ unlink($imgfile);} 
        write_file($imgfile, $pass_img);

        $post['recaptcha_challenge_field']=$ch;

        $code = '<form method="post" action="'.$PHP_SELF.'">'.$nn;
        $code .= '<input type="hidden" name="step" value="1">'.$nn;
        $code .= '<input type="hidden" name="link" value="'.urlencode($LINK).'">'.$nn;
        $code .= '<input type="hidden" name="ch" value="'.$ch.'">'.$nn;
        $code .= '<input type="hidden" name="cookie" value="'.$cookie.'">'.$nn;
        $code .= 'Please enter : <img src="'.$imgfile.'?'.rand(1,10000).'"><br><br>'.$nn;
        $code .= '<input type="text" name="captcha"> <input type="submit" value="Download">'.$nn;
        $code .= '</form>';
        echo ($code) ;
        die;
    }
     
     
        
    $string=DecoMfire($ev);		 
    
    $fid = cut_str (";".$string ,';' ,'(' );
    $snap = cut_str ( $page ,$fid ,'io.style' );
    $frid = cut_str ( $snap ,"io=document.getElementById('" ,"'" );
    $meta1= cut_str ( $string ,"('" ,"')" );
    $dat=explode("','",$meta1);
    
    $startLink="http://www.mediafire.com/dynamic/download.php?qk=".$dat[0]."&pk=".$dat[1]."&r=".$dat[2];
   
    $Url=parse_url($startLink);
   
    $page = geturl($Url["host"], $Url["port"] ? $Url["port"] : 80, $Url["path"].($Url["query"] ? "?".$Url["query"] : ""), $LINK, $cookie, 0, 0, $_GET["proxy"],$pauth);
    is_page($page);

	
	if($debug["chiper"]) showDeb($page, 1, "Chiper.PAGE");
	
	

    if(strpos($page, "Click here to start download") !== false && strpos($page, "unescape") === false)
	{
      $page = str_replace("Click here to start download", "\n", $page);
      preg_match_all("/\=(?:\s|)parent\.document\.getElementById\(\'(\w+)(?:[^<]+)(.+)(?:[^>])/",$page,$matches);
      foreach($matches[1] as $idx => $idval){
        if($idval==$frid){
          $link=Finalize($matches[2][$idx],$page);	
          break;
        }
      }
    }else
	{
      preg_match_all("/;var.+?unescape\(.+?eval/",$page,$matches);  
      foreach($matches[0] as $tmp){
        $string=DecoMfire($tmp);
        if( strpos( $string ,$frid)!== false ){
          $link=Finalize($string,$page);
          break;
        }
      }
    }
	
	if($debug["lastlink"]) showDeb($link,0,"LastLink");
    
    $Url = parse_url($link);
    $torep = array(" ", "+");
    $FileName = str_replace($torep,"_",basename($link));
    
    $loc = "$PHP_SELF?filename=" . $FileName . 
        "&force_name=".$FileName .
        "&host=" . $Url ["host"] . 
        "&port=" . $Url ["port"] . 
        "&path=" . urlencode ( $Url ["path"] . ($Url ["query"] ? "?" . $Url ["query"] : "") ) . 
        "&referer=" . urlencode ( $Referer ) . 
        "&email=" . ($_GET ["domail"] ? $_GET ["email"] : "") . 
        "&partSize=" . ($_GET ["split"] ? $_GET ["partSize"] : "") . 
        "&method=" . $_GET ["method"] . 
        "&proxy=" . ($_GET ["useproxy"] ? $_GET ["proxy"] : "") . 
        "&saveto=" . $_GET ["path"] . 
        "&link=" . urlencode ( $LINK ) . 
                   ($_GET ["add_comment"] == "on" ? "&comment=" . urlencode ( $_GET ["comment"] ) : "") .
                    $auth . 
                   ($pauth ? "&pauth=$pauth" : "") .
        (isset($_GET["idx"]) ? "&idx=".$_GET["idx"] : "") .     
        "&cookie=" . urlencode($cookie) ;
    
	
	if($debug["stop"]) exit("<br>".$loc);
    insert_location ( $loc );
}

//////////////////////////////////////////////////////////// END FREE ///////////////////////////////////////////////////////////////

function showDeb($var, $esc = 0, $label=''){
  print_r("<br><br>");
  $var = ($label!='' ? "<blink>".$label."</blink><br>":"") . ($esc==1 ? htmlspecialchars($var) : $var);
  print_r($var);  
  print_r("<br>");
}

function DecoMfire ($string){
  do
  {
    $snap = cut_str ( $string ,'unescape(' ,';eval' );
    $cont = cut_str ( $snap ,"i<" ,";" );
    if(!is_numeric($cont)){
      $cont = cut_str ( $string ,$cont."=" ,";" );  
    }
    $data = cut_str ( $snap.";" ,"'" ,";" );
    $el = cut_str ( $snap ,"charCodeAt(i)^" ,")" ); 
    $elev=explode("^",$el); 
    $udec = urldecode($data);
    for($i=0;$i<$cont;$i++){
      $op=substr($udec,$i,1);
      $op2=ord($op);
      foreach($elev as $ee){
        $op2=$op2^$ee;
      }
      //$op2=ord($op)^$el;
      $tmp.=chr($op2);
      $string=$tmp;
    }
    $tmp="";
  }
  while(strpos( $string ,"eval(")!== false);
  return $string;
}

function Finalize ($string,$page){
 $vall = cut_str ( $string ,"'/' +" ,"+'g/'" );
 $hst =  cut_str ( $string ,"http://\" + '" ,"'" ); 
 $id =   cut_str ( $string ,"g/' + '" ,"'" ); 
 $fl =   cut_str ( $string ,$id."' + '/" ,"\"" ); 
 $temps = explode("+",$vall);
 foreach ($temps as $temp)
 {
     if (empty($temp)) continue;
     preg_match('/'.trim($temp).' ?= ?\'(.*?)\';/', $page, $temp2);
     $mpath1.= $temp2[1];
 }
 $Href = 'http://'.$hst.'/'.$mpath1.'g/'.$id.'/'.$fl;
 return $Href;
}

/************mediafire.com*************\
 WRITTEN BY KAOX 11-mar-10
 Upate By Idx 26-mar-2010
\************mediafire.com*************/
?>