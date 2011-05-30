<?php
if (!isset($_GET['mod']) and isset($_SERVER['QUERY_STRING'])) {
  $_GET['mod'] = $_SERVER['QUERY_STRING'];
}
$nn = "\r\n";
$validpage = array('main','audl', 'auul', 'lynx');

$req = urldecode($_GET['mod']);
$validSearch = preg_match("/^[a-zA-Z0-9_]+$/i", $req);
if(!$validSearch || !in_array($req, $validpage)){ $req = 'main';}

header('Content-type: application/javascript');

echo "/*=====".$nn."JS Lib".$nn."Mode: ";
echo ($req);
echo "\r\n=====*/\r\n";
?>
var d = document;
function deleteCookie(name, path, domain)
  {
  if (getCookie(name))
    {
    d.cookie = name + "=" +
    ((path) ? "; path=" + path : "") +
    ((domain) ? "; domain=" + domain : "") +
    "; expires=Thu, 01-Jan-70 00:00:01 GMT";
    }
  }
  
function getCookie(name)
  {
  var dc = d.cookie;
  var prefix = name + "=";
  var begin = dc.indexOf("; " + prefix);
  if (begin == -1)
    {
    begin = dc.indexOf(prefix);
    if (begin != 0)
      {
      return null;
      }
    }
  else
    {
    begin += 2;
    }
  var end = d.cookie.indexOf(";", begin);
  if (end == -1)
    {
    end = dc.length;
    }
  return unescape(dc.substring(begin + prefix.length, end));
}


<?php
if($req == 'main' OR $req == 'auul'){
?>
function setCheckboxes(act)
  {
  try{elts = d.forms["flist"].elements["files[]"];}catch(e){}
  if(elts==undefined || elts==null){return false;};
  var elts_cnt  = (typeof(elts.length) != 'undefined') ? elts.length : 0;
  var dc;
  if (elts_cnt)
    {
    for (var i = 0; i < elts_cnt; i++)
      {
      elts[i].checked = (act == 1 || act == 0) ? act : elts[i].checked ? 0 : 1;
 	  dc = d.getElementById('brs'+eval(i+1));
	  if(dc){
	    dc.className = (elts[i].checked == 1) ? 'rowlist_checked' : 'rowlist';
	  }
      }
    }
  }
<?php
} // end-($req == 'main' OR $req == 'auul')
if($req == 'main')
{
?>

function clk(idck)
{
  var cur = document.getElementById(idck).checked;
  document.getElementById(idck).checked = !cur;
}

function neoClass_tab(id){
 var tbobj = d.getElementById("tb" + id);
 d.getElementById("navcell" + id).className = "tab-on";
 tbobj.className = "tab-content show-table";
 //alert(tbobj.className);
}

function switchCell(m)
  {
  var style; var obj = d.getElementById("tb" + m);
  for (i=1; i<=5; i++) {
   if(i != m){
	d.getElementById("navcell" + i).className = "tab-off";
	d.getElementById("tb" + i).className = "hide-table";
   }
  }
  
  setTimeout("neoClass_tab("+m+")", 70);

  
/*
  d.getElementById("navcell5").className = "tab-offr";
  d.getElementById("navcell1").className = "tab-off";
  d.getElementById("navcell2").className = "tab-off";
  d.getElementById("navcell3").className = "tab-off";
  d.getElementById("navcell4").className = "tab-off";

  d.getElementById("tb5").className = "hide-table";
  d.getElementById("tb1").className = "hide-table";
  d.getElementById("tb2").className = "hide-table";
  d.getElementById("tb3").className = "hide-table";
  d.getElementById("tb4").className = "hide-table";
  if(m == 5)
    {
    style = "tab-onr";
    }
  else
    {
    style = "tab-on";
    }
  
  d.getElementById("navcell" + m).className = style;
  //d.getElementById("tb" + m).className = "tab-content show-table";
  d.getElementById("tb" + m).className = "show-table";
*/
  //obj.blur();
  
  }


function clearSettings()
  {
  clear("domail"); clear("email"); clear("split"); clear("method");
  clear("partSize"); clear("useproxy"); clear("proxy"); clear("saveto");
  clear("path"); clear("savesettings");

  d.getElementById('domail').checked = false;
  d.getElementById('splitchkbox').checked = false;
  d.getElementById('useproxy').checked = false;
  d.getElementById('premium_acc').checked = false;
  try{d.getElementById('saveto').checked = false;}catch(e){}
  d.getElementById('savesettings').checked = false;

  d.getElementById('email').value= "";
  d.getElementById('proxy').value= "";
  d.getElementById('proxyuser').value= "";
  d.getElementById('proxypass').value= "";
  d.getElementById('premium_user').value= "";
  d.getElementById('premium_pass').value= "";
                            
  d.getElementById('emailtd').style.display = "none";
  d.getElementById('splittd').style.display = "none";
  d.getElementById('methodtd').style.display = "none";
  d.getElementById('proxy').style.display = "none";
  d.getElementById('prblock').style.display = "none";
  try{d.getElementById('path').style.display = "none";}catch(e){}
  d.getElementById('clearsettings').style.display = "none";
  
  d.cookie = "clearsettings = 1;";
  }

function clear(name)
  {
  d.cookie = name + " = " + "; expires=Thu, 01-Jan-70 00:00:01 GMT";
  }

function showAdd()
  {
  d.getElementById('add').style.display = show ? 'none' : '';
  show = show ? 0 : 1;
  }

function showAdd2()
  {
  d.getElementById('add2').style.display = show2 ? 'none' : '';
  show2 = show2 ? 0 : 1;
  }

function mail(str, field)
  {
  d.getElementById("mailPart." + field).innerHTML = str;
  return true;
  }

function setFtpParams()
  {
  setParam("host"); setParam("port"); setParam("login");
  setParam("password"); setParam("dir");
  d.cookie = "ftpParams=1";
  d.getElementById("hrefSetFtpParams").style.color = "#808080";
  d.getElementById("hrefDelFtpParams").style.color = "#0000FF";
  }

function delFtpParams()
  {
  deleteCookie("host"); deleteCookie("port"); deleteCookie("login");
  deleteCookie("password"); deleteCookie("dir"); deleteCookie("ftpParams");
  d.getElementById("hrefSetFtpParams").style.color = "#0000FF";
  d.getElementById("hrefDelFtpParams").style.color = "#808080";
  }

function setParam(param)
  {
  d.cookie = param + "=" + d.getElementById(param).value;
  }

function pr(percent, received, speed)
  {
	d.getElementById("received").innerHTML = '<b>' + received + '</b>';
	d.getElementById("percent").innerHTML = '<b>' + percent + '%</b>';
	d.getElementById("progress").style.width = percent + '%';
	d.getElementById("speed").innerHTML = '<b>' + speed + ' KB/s</b>';
	d.title = 'Uploaded ' + percent + '%';
	return true;
	}

function zip()
  {
	var i = d.ziplist.act.selectedIndex;
	var selected = d.ziplist.act.options[i].value;
	d.getElementById('add').style.display = 'none';
	switch (selected)
		{
		case "zip_add":
			d.getElementById('add').style.display = 'block';
		break;
		}
	}


<?php 
} //end-$req == 'main'

if($req == 'lynx'){
?>

function trparser(){
  var _tpl = "", i = 0;
 for(var date in dFile) {
   pfile = dFile[date];
   _tpl = dtemplate;
   // do replacement string;  10-May-2009, 23:09
   var now = new Date(parseInt(date) * 1000);
   dDate = now.toGMTString("dd-mmm-yy, HH:MM");
   dDate = dDate.split(" ");
   _tpl = _tpl.replace(/\(\(chkidx\)\)/g, i);
   _tpl = _tpl.replace(/\(\(filename\)\)/g, (pfile["name"]));
   _tpl = _tpl.replace(/\(\(filelink\)\)/g, (_dlpath + (pfile["name"])) );
   _tpl = _tpl.replace(/\(\(filesize\)\)/g, pfile["size"]);
   _tpl = _tpl.replace(/\(\(formatdate\)\)/g, dDate[1]+'-'+dDate[2]+'-'+dDate[3].substr(2,2)+', '+dDate[4].substr(0,2)+':'+dDate[4].substr(3,2)+':'+dDate[4].substr(6,2));
   _tpl = _tpl.replace(/\(\(fileage\)\)/g, pfile["age"]);
   _tpl = _tpl.replace(/\(\(dlpath\)\)/g, _dlpath);
   _tpl = _tpl.replace(/\(\(b64filename\)\)/g, pfile["delkey"]);
   text+= _tpl;
   i++;
 }
}

function clk(idck)
{
  var cur = d.getElementById(idck).checked;
  document.getElementById(idck).checked = !cur;
}

function highlight(field) {
  field.focus(); field.select(); 
}

var idwindow = new Array();
function opennewwindow(id) {
	var options = "width=700,height=250,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no";
	var start_link = d.getElementById(id);
	idwindow[id] = window.open(start_link, dwindow+id, options);
	idwindow[id].opener=self;
	idwindow[id].focus();
}
function sAll(niLai){
	var dc;
	diq=d.getElementById('add_comment').checked=false;
	d.getElementById('comment').style.display='none';
	for (i=0;i< Opt["nC"] ;i++) {
	   d.getElementById('chkfL-'+i).checked=niLai;
	   dc = d.getElementById('brs'+i);
	   //if(niLai){dc.bgColor='#B1F4AE';}else{dc.bgColor='#D49659';}
	   if(niLai){dc.className='rowlist_checked';}else{dc.className='rowlist';}
	}
}
function GenTag(){
	var displ=d.getElementById('add_comment').checked?'':'none';
	if(!displ){
	var chk,buFF="",kd=unescape("%B2"),cLnk=0;
	var nC=Opt["nC"],AdL=Opt["AdL"];
	var ttip="",ttip2="",ttip3="",ttip4="",delink="";
	var Simultan = true; //set this true if your server allow simultan download 
	var codeTagOnly = d.getElementById('wterm').checked ? false:true; //true is without Term of Download
	var DelLink = Opt["DelLink"] && d.getElementById('dellnk').checked;
	var poslynx, hostpath = d.location.href;
	poslynx = hostpath.lastIndexOf("/");
	hostpath = hostpath.substring(0,poslynx+1);
	  if(!codeTagOnly){
		ttip="\n[SIZE=1]Term of Download:";
		if(!Simultan)ttip2="\n-Gak bisa simultan, (kudu hiji"+kd+"||satu"+kd+")"; 
		if(AdL>0)ttip3="\n-Limit: AutoDelete: [b][color=red]" + AdL +" hour(s)[/color][/b]";
		if(DelLink)ttip3+="\n-Delete the file(s) using [i]Delete-Link[/i] after ur download's done";
		ttip4="\n-[color=red]JANGAN post link hasil sulap di thread[/color]";
		ttip4+="\n[center]jangan lupa\n[IMG]http://www.kaskus.us/newhomeimages/good.gif[/IMG][/center]";
	  }	  
	  delink="\n[SPOILER=Delete-Link][SIZE=2][CODE]";	  
	  for (i=0;i< nC ;i++) {
		chk=d.getElementById('chkfL-'+i).checked;
		if((chk)&&(chk!='')) {
			buFF=buFF+"\n"+ (d.getElementById('fL-'+i));
			if(DelLink){
			   dL = d.getElementById('dL-'+i).href;		   
			   dL = dL.substring(0, dL.indexOf('&lineid'));
			   delink=delink+"\n"+ dL;
			}
			cLnk++;
		}
	  }
	  delink=delink+"\n[/CODE][/SIZE][/SPOILER]";
	  if(buFF!=''){
		buFF="[CODE]" + (buFF) + "\n[/CODE]";
		if(DelLink){buFF=buFF+delink;}
		buFF+=ttip;
		if(cLnk>1){buFF+=ttip2+ttip3;}
		else{buFF+=ttip3;}
		buFF+=ttip4;
		if(!codeTagOnly){ buFF+="[/SIZE]";}
	  }
	}
	var dca = d.getElementById('comment');
	var trwarn = d.getElementById('warn_alert');
	if(buFF!="")
	 { dca.style.display=displ;
	   d.getElementById('cmtarea').value=buFF;
	   trwarn.style.display = 'none';
	 }
	 else{
	   dca.style.display='none';
	   d.getElementById('td_warn').innerHTML = '<div class="acthistory_result" style="width:200px;">Error, No Selected Files</div>';
	   trwarn.style.display = '';	   
	 }
}


<?php
}
?>