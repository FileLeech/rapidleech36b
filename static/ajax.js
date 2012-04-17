/**
* ===========================
* Location    : ./static/rsajax.js
* Machine     :
* Property of : RL OLC 36B
*
* Re-generated   : 1812-09 // ddmm-yy
* ===========================
*/
var xhttp = new Array();
var myacc = new Object();
myacc['acc'] = new Array();
myacc.j = 0;
myacc.cnt_valid = 0;
myacc.unsec = 0;
myacc.res = '';
myacc.valid = '';
myacc.longacc = '';
myacc.shortacc = '';
myacc.multicheck = false;
myacc.index_status = false;
myacc.head = '';
myacc.param = '';
myacc.idbuttonrschk = 'btnchkacc';
myacc.divwaitrschk = 'waiting';
myacc.chead = '<table cellspacing=\"0\" cellpadding=\"2\" class=\"tbl_acc\"><tr id="tr_acc_header"><th>' + php_js_strings[2] + '</th> <th>' + php_js_strings[3] + '</th><th title=\"' + php_js_strings[4] + '\">' + php_js_strings[5] + '</th><th title=\"' + php_js_strings[6] + '\">' + php_js_strings[7] + '</th><th title=\"' + php_js_strings[8] + '\">' + php_js_strings[9] + '</th><th title=\"' + php_js_strings[13] + '\">' + php_js_strings[14] + '</th><th title=\"' + php_js_strings[17] + '\">' + php_js_strings[18] + '</th><th title=\"' + php_js_strings[15] + '\">' + php_js_strings[16] + '</th><th title=\"' + php_js_strings[10] + '\">' + php_js_strings[11] + '</th><th>' + php_js_strings[12] + '</th></tr>';
var d = document;
var _debug2 = false;
var _debug = false;

function AJAXInteraction(c, f, g, h, j, k) {
	var l = init();
	l.onreadystatechange = processRequest;

	function init() {
		if (typeof XMLHttpRequest == 'undefined') {
			objects = Array('Microsoft.XmlHttp', 'MSXML2.XmlHttp', 'MSXML2.XmlHttp.3.0', 'MSXML2.XmlHttp.4.0', 'MSXML2.XmlHttp.5.0');
			for (i = 0; i < objects.length; i++) {
				try {
					return new ActiveXObject(objects[i])
				} catch (e) {}
			}
		} else {
			return new XMLHttpRequest()
		}
	};

	function processRequest() {
		var a = '';
		var b;
		if (l.readyState == 4) {
			if (l.status == 200) {
				if (f) {
					b = (_debug == true ? l.responseText : (k != 'xml' ? l.responseText : l.responseXML));
					if (_debug) alert(b);
					f(b, g, h, j, k)
				}
			}
		} else {
			if (j.multicheck > 0) {
				wprogress = parseInt((j.j + 1) * 100 / j.acc.length);
				a = "<b class='g'>" + j.acc[j.j] + "</b><table align='center'><tr><td><div style='border:#BBBBBB 1px solid; width:300px; height:10px;'><div id='progress' style='background-color:#18f20d; margin:1px; width:" + wprogress + "%; height:8px;'></div></div></td></tr></table><span id='nfoacc'>" + "#[<b class='g'>" + eval(j.j + 1) + "</b>] of " + j.acc.length + "</span>"
			}
			d.getElementById(g).innerHTML = a + '&nbsp;<span style="background-color:#800000;">&nbsp;<img alt="loading" src="' + STATIC_DIR + 'images/fbload.gif" alt="' + php_js_strings[1] + '">&nbsp;&nbsp;<b class="o">' + php_js_strings[1] + '</b>&nbsp;</span>'
		}
	};
	this.doGet = function () {
		l.open("GET", c, true);
		l.send(null)
	};
	this.doPost = function (a) {
		l.open("POST", c, true);
		l.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		l.setRequestHeader("Content-length", a.length);
		l.send(a)
	}
};
var fcallback = function (a, b, c, f, g) {
		var h = a;
		if (g == 'xml') {
			createTable(h, b, c);
			return
		}
		f.res = f.res + h;
		if (f.multicheck && h != '') {
			d_acc = getBetween(h, '<<acc>>', '<</acc>>');
			f.longacc += d_acc + '\n';
			f.cnt_valid += 1;
			startcode = f.res.indexOf('<!end>');
			if (startcode != -1) {
				f.res = f.res.substr(0, startcode)
			}
			if (h.indexOf('<blink>OFF</blink>') != -1) {
				f.unsec += 1
			}
			f.valid = f.valid + f.acc[f.j] + '\n'
		}
		if (f.multicheck) {
			f.head = f.chead;
			f.index_status = false;
			f.shortacc = cleanacc(f.valid);
			txtarea = '</table><b class="g">' + php_js_strings[27] + '</b>&nbsp;' + php_js_strings[19] + '<br /><textarea id="validacc" class="redtxtarea" style="width: 100%; height: 140px">' + f.longacc + '</textarea>';
			txtarea += '<table style="width: 100%;"><tr><td width="50%" align="left"><small>' + php_js_strings[20] + ' <b class="o">' + (f.j + 1) + '</b><br />' + php_js_strings[22] + ' <b class="y"><blink>' + f.unsec + '</blink></b><br />' + php_js_strings[21] + ' <b class="r">' + ((f.j + 1) - f.cnt_valid) + '</b></small></td><td align="right" valign="top"><label><input type="checkbox" id="nodet" onclick="NoDetail(myacc.shortacc,myacc.longacc);">' + php_js_strings[23] + '</label></td></tr></table>'
		}
		if (f.res != '' && f.multicheck) {
			d.getElementById(c).innerHTML = f.head + f.res + txtarea
		}
		if (f.index_status == true) {
			try {
				d.getElementById(f.idbuttonrschk).disabled = false;
				d.getElementById(f.divwaitrschk).innerHTML = '';
				d.getElementById(f.divwaitrschk).style.display = 'none'
			} catch (e) {}
		}
		if (!f.multicheck || f.j >= (f.acc.length - 1)) {
			d.getElementById(c).style.display = '';
			if (f.multicheck) {
				d.getElementById(f.idbuttonrschk).disabled = false;
				if (f.res == '') {
					d.getElementById(c).innerHTML = '<center><small class="y">' + php_js_strings[24] + '</small></center>'
				}
				try {
					d.getElementById('refresh').style.display = ''
				} catch (e) {}
			} else {
				d.getElementById(c).innerHTML = h;
				h = '';
				f.res = ''
			}
			if (b != c) {
				d.getElementById(b).innerHTML = '';
				d.getElementById(b).style.display = 'none'
			}
			f.head = '';
			txtarea = '';
			clearTimeout(document.ajxtmr)
		} else {
			f.j += 1;
			callAjax(phpjx, b, c, f, g)
		}
	};

function callAjax(a, b, c, d, e) {
	var f = (e == undefined ? 'text' : e);
	ai = null;
	ai = new AJAXInteraction(a, fcallback, b, c, d, f);
	if (d.multicheck) {
		ai.doPost(d.param + encodeURI(d.acc[d.j]))
	} else {
		ai.doPost(d.param)
	}
};

function createHeader(a, b) {
	var c = d.createElement("div");
	c.id = "parentcontainer";
	var e = d.createElement("div");
	e.setAttribute("style", "text-align:center;margin-top:-10px;");
	var f = d.createElement("a");
	f.setAttribute("href", "javascript:;");
	f.setAttribute("onclick", "d=document; dcnt=d.getElementById(\'flcontainer\'); ddcnt=d.getElementById(\'divcontainer\'); if(dcnt) {dcnt.style.overflow=\'visible\';d.getElementById(\'parentcontainer\').innerHTML=ddcnt.innerHTML;};try{gosetfacebook();waitandload();}catch(e){};");
	var g = d.createElement("span");
	g.setAttribute("id", "release");
	g.setAttribute("title", "release me..");
	g.appendChild(d.createTextNode("release"));
	f.appendChild(g);
	e.appendChild(f);
	c.appendChild(e);
	e = d.createElement("div");
	e.setAttribute("id", "divcontainer");
	var h = d.createElement("table");
	h.setAttribute("id", "intbl");
	h.setAttribute("class", "filelist sortable");
	h.setAttribute("cellpadding", "2");
	h.setAttribute("cellspacing", "1");
	var k = d.createElement("thead");
	k.setAttribute("id", "flheader");
	tr = d.createElement("tr");
	tr.setAttribute("class", "filelist_title");
	tr.setAttribute("align", "center");
	tr.setAttribute("valign", "bottom");
	var n = a.getElementsByTagName('totalfiles').item(0);
	n = n.childNodes.item(0).data;
	var l = new Object();
	var m;
	var o = a.getElementsByTagName('columns').item(0);
	var p = '';
	p = a.getElementsByTagName('ajaxdelete').item(0);
	p = (p ? p.childNodes.item(0).data : 0);
	for (var q = 0; q < o.childNodes.length; q++) {
		var r = o.childNodes.item(q);
		for (var i = 0; i < r.childNodes.length; i++) {
			var s = r.childNodes.item(i);
			var t = '';
			var u = '';
			td = d.createElement("td");
			if (r.tagName == "filename") {
				var v = a.getElementsByTagName('option').item(0);
				if (v != null) {
					var w = d.createElement("select");
					w.id = "sel_actionfile";
					w.setAttribute("name", "act");
					w.setAttribute("style", "text-align:left; float:left;");
					w.setAttribute("onchange", "javascript:void(document.flist.submit());");
					var y = a.getElementsByTagName('actions').item(0);
					for (var x = 0; x < y.childNodes.length; x++) {
						var z = y.childNodes.item(x);
						for (var j = 0; j < z.childNodes.length; j++) {
							var A = z.childNodes.item(j);
							act_ret = A.data.split(":", 3);
							var B = d.createElement("option");
							if (act_ret[0] != "-1") {
								B.setAttribute("value", act_ret[0])
							}
							u = d.createTextNode(act_ret[2]);
							B.appendChild(u);
							w.appendChild(B)
						}
					}
					td.appendChild(w);
					t = "(" + n + " file" + (n > 1 ? "s" : "") + ") "
				}
			}
			m = d.createElement("b");
			if (r.tagName == "ajax_del" && p != "1") continue;
			if (r.tagName == "chkbox" || r.tagName == "ajax_del" || r.tagName == "fileage") {
				m.setAttribute("class", "sorttable_nosort");
				if (r.tagName == "ajax_del") td.setAttribute("style", "padding-right:20px;")
			}
			u = d.createTextNode((t != '' ? t : '') + s.data);
			m.appendChild(u);
			td.appendChild(m);
			tr.appendChild(td);
			l[r.tagName] = s.data
		}
	}
	k.appendChild(tr);
	h.appendChild(k);
	var C = d.createElement("tbody");
	var D = (n >= 13 ? "280px" : "100%");
	C.id = "flcontainer";
	C.setAttribute("style", "height: " + D + ";");
	var E = '((__row_container__))';
	var F = d.createTextNode(E);
	C.appendChild(F);
	if (n > 0) {
		var G = d.createElement("tfoot");
		tr = d.createElement("tr");
		tr.id = "sffooter";
		td = d.createElement("td");
		tr.appendChild(td);
		td = d.createElement("td");
		u = d.createTextNode("Total : " + n + " file" + (n > 1 ? 's' : ''));
		td.appendChild(u);
		tr.appendChild(td);
		td = d.createElement("td");
		var H = a.getElementsByTagName('totalsize').item(0);
		u = d.createTextNode(cleanTxt(H.childNodes.item(0).data));
		td.appendChild(u);
		tr.appendChild(td);
		td = d.createElement("td");
		var I = a.getElementsByTagName('columnspan').item(0);
		I = (I ? I.childNodes.item(0).data : -1);
		if (I > 0) {
			u = d.createTextNode(I);
			td.setAttribute("colspan", "" + u.nodeValue + "");
			tr.appendChild(td)
		}
		G.appendChild(tr)
	}
	var J = a.getElementsByTagName('proceestime').item(0);
	var K = d.createElement("div");
	var L = d.createElement("small");
	m = d.createElement("b");
	u = d.createTextNode(n);
	m.appendChild(u);
	L.setAttribute("class", "y");
	L.appendChild(m);
	K.appendChild(L);
	L = d.createElement("small");
	L.setAttribute("class", "footer3");
	u = d.createTextNode(' file' + (n > 1 ? 's' : '') + ' loaded in ' + J.childNodes.item(0).data + ' sec');
	L.appendChild(u);
	K.appendChild(L);
	h.appendChild(C);
	h.appendChild(G);
	e.appendChild(h);
	c.appendChild(e);
	b.appendChild(c);
	b.appendChild(K);
	createContent(a, b, E, l)
};

function createContent(a, b, c, d) {
	var e = "\n";
	var f = new Object();
	var g = -1;
	var h = a.getElementsByTagName('files').item(0);
	for (var j = 0; j < h.childNodes.length; j++) {
		var k = h.childNodes.item(j);
		for (i = 0; i < k.childNodes.length; i++) {
			var l = k.childNodes.item(i);
			if (k.tagName == 'fileid') {
				g++;
				f['file' + g] = new Object()
			}
			f['file' + g][k.tagName] = l.data
		}
	}
	if (_debug2) {
		alert(dump(f))
	}
	var m = new Object();
	m["tagIRen"] = a.getElementsByTagName('instantrename').item(0);
	m["tagIRen"] = (m["tagIRen"] ? m["tagIRen"].childNodes.item(0).data : 0);
	m["ajaxDel"] = a.getElementsByTagName('ajaxdelete').item(0);
	m["ajaxDel"] = (m["ajaxDel"] ? m["ajaxDel"].childNodes.item(0).data : 0);
	var n = '';
	var o = new Object();
	n = '<tr id="brs((chkidx))" class="rowlist" onmousedown="clk(\'cek_((chkidx))\', this);" onmouseout="if(document.getElementById(\'cek_((chkidx))\').checked){this.className=\'rowlist_checked\';}else{this.className=\'rowlist\';}" title="((filename))" align="center">' + e;
	n += '<td>';
	n += (m["tagIRen"] == 1 ? '<div id="rename_container" onmouseup="ren(this, \'fn_((chkidx))\', \'((dateid))\')"><div class="rename_es"></div></div>' : '');
	n += '<input name="files[]" id="cek_((chkidx))" value="((dateid))" onclick="clk(\'cek_((chkidx))\')" type="checkbox"></td>' + e;
	n += '<td id="fn_((chkidx))"><b><a href="((filepath))">((filename))</a></b></td>' + e;
	n += '<td>((filesize))</td>';
	n += (undefined != d['filelink'] ? '<td title="((filelink))"><a href="((filelink))">((filelinktr))</a></td>' + e : '');
	n += (undefined != d['filemd5'] ? '<td class="txtmd5">((filemd5))</td>' + e : '');
	n += (undefined != d['filecomment'] ? '<td>((filecomment))</td>' + e : '');
	n += (undefined != d['filedate'] ? '<td>((filedate))</td>' + e : '');
	n += (undefined != d['fileage'] ? '<td><small>((fileage))</small></td>' + e : '');
	n += (undefined != d['fileip'] ? '<td>((fileip))</td>' + e : '');
	n += (m["ajaxDel"] == 1 ? '<td><a id="dL-((chkidx))" href="del.php?d=((filedelkey))&lineid=((chkidx))" rel="facebox"> <img src="' + IMG_DIR_TPL + 'rldel.png" alt="X"></a></td>' : '');
	n += '</tr>';
	var p = "",
		text = "";
	for (var q in f) {
		pfile = f[q];
		p = n;
		p = p.replace(/\(\(chkidx\)\)/g, pfile["fileid"].replace(/brs/g, ''));
		p = p.replace(/\(\(filename\)\)/g, pfile["filename"]);
		p = p.replace(/\(\(filepath\)\)/g, pfile["filepath"]);
		p = p.replace(/\(\(dateid\)\)/g, pfile["filedateid"]);
		p = p.replace(/\(\(filesize\)\)/g, pfile["filesize"]);
		p = p.replace(/\(\(filelink\)\)/g, pfile["filelink"]);
		p = p.replace(/\(\(filelinktr\)\)/g, pfile["filelinktr"]);
		p = p.replace(/\(\(filecomment\)\)/g, (pfile["filecomment"] != undefined ? pfile["filecomment"] : "-"));
		p = p.replace(/\(\(filemd5\)\)/g, pfile["filemd5"]);
		p = p.replace(/\(\(filedate\)\)/g, pfile["filedate"]);
		p = p.replace(/\(\(fileage\)\)/g, pfile["fileage"]);
		p = p.replace(/\(\(fileip\)\)/g, pfile["fileip"]);
		p = p.replace(/\(\(filedelkey\)\)/g, pfile["filedelkey"]);
		text += p
	}
	text = cleanTxt(text);
	bufRes = b.innerHTML;
	bufRes = bufRes.replace(c, text);
	b.innerHTML = bufRes
};

function createTable(a, b, c) {
	var f = false;
	if (_debug) {
		alert('Table Creator\n' + a)
	} else {
		f = a
	}
	var g = d.getElementById(c);
	g.innerHTML = '';
	if (f) {
		var h = f.getElementsByTagName('data').item(0);
		var i = f.getElementsByTagName('succeed').item(0).childNodes.item(0).data;
		if (!h) {
			alert('Invalid XML root')
		}
		if (i == 0) {
			g.innerHTML = f.getElementsByTagName('msg').item(0).firstChild.data
		} else if (h) {
			createHeader(f, g);
			if (_debug2) {
				g.innerHTML += dbPar(g.innerHTML)
			}
		}
		d.getElementById(b).innerHTML = '';
		d.getElementById(b).style.display = 'none';
		try {
			gosetfacebook();
			waitandload()
		} catch (e) {}
	}
};

function cleanTxt(a) {
	return a.replace(/\(\(space\)\)/g, " ")
};

function isset(a) {
	return (typeof (window[a]) != 'undefined')
};

function dbPar(a) {
	return bufText = "<br />Debug2:<br /><textarea rows='10' cols='70'>" + a.replace(/>/g, "&gt;").replace(/</g, "&lt;") + "</textarea>"
};

function dump(a, b) {
	var c = "";
	if (!b) {
		b = 0
	}
	var d = "";
	for (var j = 0; j < b + 1; j++) d += "    ";
	if (typeof (a) == 'object') {
		for (var e in a) {
			var f = a[e];
			if (typeof (f) == 'object') {
				c += d + "'" + e + "' ...\n";
				c += dump(f, b + 1)
			} else {
				c += d + "'" + e + "' => \"" + f + "\"\n"
			}
		}
	} else {
		c = "===>" + a + "<===(" + typeof (a) + ")"
	}
	return c
};

function get(a, b, c, f) {
	var g = '';
	var h = 'text';
	myacc.unsec = 0;
	myacc.cnt_valid = 0;
	myacc.longacc = '';
	ibtnsubmit = f;
	myacc.param = a;
	myacc.multicheck = false;
	myacc.index_status = false;
	switch (a) {
	case 'staccounts':
		myacc.param = 'staccounts=1';
		myacc.index_status = true;
		try {
			d.getElementById(myacc.idbuttonrschk).disabled = true;
			d.getElementById(myacc.divwaitrschk).innerHTML = '<span style="color:#239FD9;">' + php_js_strings[1] + '<br />' + php_js_strings[25] + '</span>';
			d.getElementById(myacc.divwaitrschk).style.display = ''
		} catch (e) {}
		break;
	case 'accounts':
		g = (d.getElementById(a).value);
		if (g.indexOf(":") == -1) {
			return
		}
		myacc.param = 'accounts=';
		myacc.multicheck = true;
		try {
			d.getElementById('refresh').style.display = 'none'
		} catch (e) {}
		break;
	case 'posttraffic':
		myacc.param = 'fntrafic=' + d.getElementById('posttraffic').value;
		break;
	case 'misionMd5':
		myacc.param = 'md5fn=' + d.getElementById('misionMd5').value;
		break;
	case 'doTBLoad':
		myacc.param = 'TBLoad=' + d.getElementById('rfr').value + ':::';
		h = 'xml';
		break;
	case 'deletelog':
		myacc.param = 'dellog=' + d.getElementById('deletelog').value;
		break;
	case 'ajxRename':
		myacc.param = 'ren=' + d.getElementById('renhiddenval').value;
		break;
	default:
		myacc.param = '';
		break
	};
	d.getElementById(b).innerHTML = '&nbsp;<span id="waiting_tbload" style="background-color:#800000;">&nbsp;<img alt="loading" src="' + STATIC_DIR + 'images/fbload.gif">&nbsp;&nbsp;<b class="o">' + php_js_strings[1] + '</b>&nbsp;</span>';
	d.getElementById(b).style.display = '';
	if (myacc.param != '') {
		if (myacc.multicheck) {
			myacc.acc = makeAray(g);
			myacc.j = 0;
			myacc.res = '';
			myacc.valid = '';
			myacc.head = myacc.chead;
			d.getElementById(ibtnsubmit).disabled = true
		}
		callAjax(phpjx, b, c, myacc, h)
	}
};

function makeAray(v) {
	var a = v.split('\n');
	for (var i = 0; i < a.length; i++) {
		/\s$/.test(a[i]) ? a[i] = a[i].replace(/\s$/, '') : null;
		if (a[i].length == 0) {
			a.splice(i, 1)
		}
	}
	return a
};

function login(u, p, a) {
	var d = document;
	var d = d, loginuser;
	var b = "https://ssl.rapidshare.com/cgi-bin/";
	var c = d.getElementById('myform');
	if (a == "cz") {
		loginuser = "username";
		c.action = b + "collectorszone.cgi"
	} else {
		loginuser = "login";
		c.action = b + "premiumzone.cgi"
	}
	d.getElementById(loginuser).value = u;
	d.getElementById("password").value = p;
	d.myform.submit()
};

function pr(a) {
	var d = document;
	d.getElementById("progress").style.width = a + '%';
	return true
};

function setzeTT(a) {
	var t = a;
	var b = " ";
	if (a.length > 3) {
		t = setzeTT(a.substr(0, a.length - 3)) + b + a.substr(a.length - 3)
	}
	return t
};

function NoDetail(s, L) {
	var d = document;
	var a = d.getElementById('nodet').checked ? true : false;
	var b = '';
	if (a) {
		b = s
	} else {
		b = L
	}
	d.getElementById('validacc').innerHTML = b
}

function cleanacc(a) {
	var b, part_acc_b, bufacc = '';
	b = a.split('\n');
	for (var i = 0; i < b.length; i++) {
		if (b[i].indexOf(':') != -1) {
			part_acc_b = b[i].split(':');
			bufacc += part_acc_b[0] + ':' + part_acc_b[1] + '\n'
		}
	}
	return bufacc
};

function getBetween(a, b, c) {
	var d = a.indexOf(b);
	var e = a.indexOf(c);
	var f = a.substring(eval(d + b.length), e);
	return f
};

function _gLoad(a, b) {
	frmTB = d.getElementById('frmTBLoad');
	frmTB.action = 'javascript:get("doTBLoad", "' + a + '", "' + b + '", "")';
	frmTB.submit()
};

function disableIt(a) {
	var b = new Array;
	var c = new Array(true, 'redtxtareadisable', true, '');
	var e = new Array(false, 'redtxtarea', false, 'none');
	if (a == 1) {
		b = c
	} else {
		b = e
	}
	d.getElementById('links').readOnly = b[0];
	d.getElementById('links').className = b[1];
	d.getElementById('btnlnkchk').disabled = b[2];
	d.getElementById('lnkwait').style.display = b[3]
};

function checkLink() {
	var a = Array();
	disableIt(1);
	links = d.getElementById('links').value;
	a = LnkChkHandler(links);
	if (a.length != 0) {
		getResults(dlinks, a, 'index')
	} else {
		alert('Bad Link URL');
		disableIt(0)
	}
};
//--- end rsajax.js

// ================

/**
* ===========================
* Location    : ./static/rscheck.js
* Machine     :
* Property of : RL OLC 36B
*
* Re-generated   : 0703-10 // ddmm-yy
* ===========================
*/
var http_req_rs = false, d = document;
var section, rslinks, startFrm = 0;
var array_links = new Array();
var arResp = new Array();

function makePOSTReqRs(a, b) {
	if (typeof XMLHttpRequest == 'undefined') {
		objects = Array('Microsoft.XmlHttp', 'MSXML2.XmlHttp', 'MSXML2.XmlHttp.3.0', 'MSXML2.XmlHttp.4.0', 'MSXML2.XmlHttp.5.0');
		for (i = 0; i < objects.length; i++) {
			try {
				http_req_rs = new ActiveXObject(objects[i])
			} catch (e) {}
		}
	} else {
		http_req_rs = new XMLHttpRequest()
	}
	if (!http_req_rs) {
		alert('Cannot create XMLHTTP instance');
		return false
	}
	http_req_rs.open('POST', a, true);
	http_req_rs.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http_req_rs.setRequestHeader("Content-length", b.length);
	http_req_rs.setRequestHeader("Connection", "close");
	http_req_rs.onreadystatechange = function () {
		handleResults(http_req_rs)
	};
	http_req_rs.send(b)
}

function handleResults(a) {
	if (a.readyState == 4) {
		if (a.status == 200) {
			if (a.responseText == 'url.blank') {
				d.body.innerHTML = 'Enter a valid URL'
			} else {
				var b = a.responseText, j = 0, txrest;
				var c = unescape("%EF%BB%BF");
				rtime = getBetween(b, '<timerequire>', '</timerequire>');
				b = getBetween(b, '<result>', '</result>');
				b = b.replace(c, "");
				arResp = b.split("\n");
				if (undefined != arResp) {
					if (section == 'audl') {
						for (var i = startFrm; i < array_links.length; i++) {
							d.getElementById('status' + i).innerHTML = arResp[j];
							d.getElementById('statusTitle').innerHTML = "&nbsp;<b><a href='javascript:;' onclick='reLink(); loadHandler();'>" + php_js_strings[26] + "</a></b>&nbsp;";
							j++
						}
						d.getElementById('btnaddlinks').disabled = false
					} else {
						txrest = '<div id="listlink">';
						cntunknow = 0;
						cntgood = 0;
						linkonly = d.getElementById('d').checked;
						for (var i = startFrm; i < array_links.length; i++) {
							good = (arResp[j].indexOf("good link") != -1);
							if (good) {
								cntgood++
							}
							unknow = (arResp[j].indexOf("unknown link") != -1);
							if (unknow) {
								cntunknow++
							}
							txrest += '<a class=' + (good ? '"g"' : '"r"') + ' href="' + array_links[i] + '" target="_blank">' + array_links[i] + '</a>' + (linkonly ? '' : '&nbsp;' + arResp[j]) + '<br />\n';
							j++
						}
						txrest += '</div>';
						spc = '&nbsp;';
						lnk = 'Link:&nbsp;';
						txrest += '<br /><hr /><center><small style="color:#fff"><b class="o">' + array_links.length + '</b> ' + rtime + '<br />\n';
						txrest += 'Dead&nbsp;' + lnk + '<b class="r">' + eval(array_links.length - cntgood - cntunknow) + '</b>\n';
						txrest += spc + spc + '&#8212;' + spc + spc + 'Good&nbsp;' + lnk + '<b class="g">' + eval(cntgood) + '</b>\n';
						txrest += spc + spc + '&#8212;' + spc + spc + 'Unknown&nbsp;' + lnk + '<b class="y">' + eval(cntunknow) + '</b></small></center>\n';
						d.getElementById('linkresult').innerHTML = txrest;
						disableIt(0)
					}
					if (section == 'audl') {
						d.getElementById('btnaddlinks').disabled = false
					} else {
						disableIt(0)
					}
				}
			}
		} else {
			alert('There was a problem with the request.')
		}
	}
}

function chklinkOnly(a) {
	var b = '';
	try {
		d_target = d.getElementById('listlink');
		d_target = (d_target && arResp.length)
	} catch (e) {}
	if (d_target == null) {
		return false
	}
	for (var i = 0; i < array_links.length; i++) {
		good = (arResp[i].indexOf("good link") != -1);
		b += '<a class=' + (good ? '"g"' : '"r"') + ' href="' + array_links[i] + '" target="_blank">' + array_links[i] + '</a>' + (a ? '' : '&nbsp;' + arResp[i]) + '<br />\n'
	}
	d.getElementById('listlink').innerHTML = b
}

function strrev(a) {
	var b = a, reversed = "";
	for (i = b.length; i >= 0; i--) {
		reversed = reversed + b.charAt(i)
	}
	hsl = encodeBase64(reversed);
	return hsl
}

function jlnkfilter(a) {
	var b = a, charsuspect = new Array("*");
	var c = "";
	srcL: for (var i = 0; i < charsuspect.length; i++) {
		if (b.indexOf(charsuspect[i]) != -1) {
			b = b.replace(charsuspect[i], c);
			break srcL
		}
	}
	return b
}

function LnkChkHandler(a, b) {
	var c = new RegExp('\r\n', "g");
	var d = new RegExp('\n', "g");
	var e = /((^http)*).\:\/\/.([a-zA-Z0-9\.])/;
	dlinks = a;
	dlinks = dlinks.replace(c, "\n");
	arlinks = dlinks.split(d);
	dlinks = '';
	var f = (b > 0 ? b : arlinks.length);
	for (i = 0; i < f; i++) {
		if (arlinks[i].substring(0, 7) != 'ht' + 'tp://') {
			arlinks[i] = 'ht' + 'tp://' + arlinks[i]
		}
		if (e.test(arlinks[i])) {
			dlinks += jlnkfilter(arlinks[i]) + ';'
		}
	}
	dlinks = dlinks.substring(0, dlinks.length - 1);
	arlinks = dlinks.split(";");
	return arlinks
}

function getResults(a, b, c) {
	var f = '';
	section = c;
	try {
		f += (d.getElementById('lcurl').checked == true ? '|_curl=on;' : '')
	} catch (e) {}
	var g = '&url=' + f + '|_section=' + section + ';' + '|_url=' + strrev(unescape(a));
	array_links = b;
	makePOSTReqRs(phpjx, g)
}
//--- End rscheck

// ==============

/**
* ===========================
* Location    : ./static/rsajax_ren.js
* Property of : RL OLC 36B
*
* Re-generated   : 1812-09 // ddmm-yy
* ===========================
*/
var _rendebug = false;
var objFName = new Object();
var RenCarier = new Object();
var cRow = -1;

function fieldEnter(a, b) {
	b = (b) ? b : window.event;
	if (b.keyCode == 13 && a.value != "") {
		ren(RenCarier[cRow]["obj"], 'fn_' + cRow, RenCarier[cRow]["fid"]);
		return false
	} else {
		return true
	}
}

function strip_quotes(a) {
	var b = /[\'\"\s\\\\\/\?\:\`\~\%\#\&\@\*\+\=\^\|\>\<\;]/g;
	return a.replace(b, "_").replace(/</g, "&lt;").replace(/>/g, "&gt;")
}

function recode_escape(a) {
	a = unescape(a);
	return a.replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/&/g, "&amp;")
}

function parseFN(a) {
	var b = new Array();
	var c = /.*href="(.+?)">/i;
	var d = /<a.*">(.+?)<\/a>/i;
	b[0] = d.exec(a);
	b[1] = c.exec(a);
	var e = new Array();
	e[0] = b[0][1];
	e[1] = b[1][1].substring(0, b[1][1].lastIndexOf("/") + 1);
	return e
}

function ren(a, b, c) {
	var e = b.replace("fn_", "");
	var f;
	var g = d.getElementById(b);
	var h = /class="(.+?)">/i;
	var i = h.exec(a.innerHTML);
	if (i[1] == "rename_es") {
		if (cRow != -1) {
			restoreRow(cRow)
		}
		cRow = e;
		var j = parseFN(g.innerHTML);
		objFName[cRow] = new Object();
		objFName[cRow]["filename"] = j[0];
		objFName[cRow]["path"] = j[1];
		g.innerHTML = '<input type="text" onkeypress="return fieldEnter(this, event)" name="fn_edit' + cRow + '" id="fn_edit' + cRow + '"  style="width:100%;padding-left:2px;" value="' + objFName[cRow]["filename"] + '"/><div style="display:none;" id="waitRename"></div><span id="resultRename" style="display:none;position:absolute;top:-1000px;"></span><input id="renhiddenval" style="display:none;" value="' + c + '"/>';
		f = d.getElementById("fn_edit" + cRow);
		f.focus();
		a.innerHTML = '<div class="' + i[1] + "_save" + '"></div>';
		RenCarier[cRow] = new Object();
		RenCarier[cRow]["obj"] = a;
		RenCarier[cRow]["td"] = g;
		RenCarier[cRow]["fid"] = c;
		RenCarier[cRow]["newFN"] = f.value;
		RenCarier[cRow]["clsRen"] = i[1].replace("_save", "")
	} else {
		f = d.getElementById("fn_edit" + cRow);
		var k = strip_quotes(f.value);
		var l = objFName[e]["filename"].replace(/\&amp;/g, "&");
		if (k != l) {
			f.style.disabled = "true";
			objFName[cRow]["newfilename"] = (k);
			d.getElementById("renhiddenval").value += "|" + encodeBase64(encodeURI(k)) + "|" + encodeBase64(encodeURI(l));
			doRename()
		} else {
			restoreRow(cRow)
		}
	}
}

function doRename() {
	ajxtmr = setTimeout("get('ajxRename','waitRename','resultRename','')", 300);
	var a = setTimeout("renameDone()", 700)
}

function renameDone() {
	var a = d.getElementById("resultRename");
	if (a.innerHTML == "") {
		setTimeout("renameDone()", 700)
	} else {
		var b = a.innerHTML;
		if (_rendebug) {
			showDebug(b);
		}
		var c = parseInt(getBetween(b, '((suc))', '((/suc))'));
		if (c == 1) {
			objFName[cRow]["filename"] = recode_escape(objFName[cRow]["newfilename"])
		} else {
			if (!_rendebug) {
				alert(getBetween(b, '<msg>', '</msg>'))
			}
		};
		restoreRow(cRow);
		a.style.display = "none"
	}
}

function restoreRow(a) {
	RenCarier[a]["td"].innerHTML = '<b><a href="' + (objFName[a]["path"] + (objFName[a]["filename"])) + '">' + (objFName[a]["filename"]) + '</a></b>';
	RenCarier[a]["obj"].innerHTML = '<div class="' + RenCarier[a]["clsRen"] + '"></div>';
	cRow = -1
}

function showDebug(txt) {
	var pcon = document.getElementById('parentcontainer');
	var log = document.getElementById('log_rename');
	if (!log) {
		var log = document.createElement('textarea');
		log.id = 'log_rename';
		log.setAttribute("style", "width:100%;height:120px;font-size:11px;");
		log.appendChild(document.createTextNode(txt));
		document.body.insertBefore(log, document.body.firstChild);
	} else {
		log.innerHTML = txt;
	}
}
//--- end rsajax_ren

/**
* ===========================
* Facebox (for jQuery)
* version: 1.2 (05/05/2008)
* @requires jQuery v1.2 or later
*  Simply bind a function to any of these hooks:
*   $(document).bind('reveal.facebox', function() { ...stuff to do after the facebox and contents are revealed... })
*
* Location    : ./static/jQ_ajax
* Machine     :
* Property of : RL OLC 36B
*
* Re-generated   : 1812-09 // ddmm-yy
* ===========================
*/
(function($) {
  $.facebox = function(data, klass) {
    $.facebox.loading()

    if (data.ajax) fillFaceboxFromAjax(data.ajax, klass)
    else if (data.image) fillFaceboxFromImage(data.image, klass)
    else if (data.div) fillFaceboxFromHref(data.div, klass)
    else if ($.isFunction(data)) data.call($)
    else $.facebox.reveal(data, klass)
  }

  /*
   * Public, $.facebox methods
   */

  $.extend($.facebox, {
    settings: {
      opacity      : 0,
      overlay      : true,
      titlePop 	   : 'Delete file?',
      loadingText  : '', // Loading...
      loadingImage : '', // static/images/loading.gif
      closeImage   : 'static/images/nav_close6.png',
      imageTypes   : [ 'png', 'jpg', 'jpeg', 'gif' ],
      faceboxHtml  : '\
    <div id="facebox" style="display:none;"> \
      <div class="fbpopup"> \
        <table id="anu"> \
          <tbody> \
            <tr> \
              <td class="tl"/><td class="bu"/><td class="tr"/> \
            </tr> \
            <tr> \
              <td class="bu"/> \
              <td class="body"> \
                <div class="header"> \
                  <span id="thead_title"></span> \
                  <a href="#" class="close"> \
                    <div title="close" class="close_image" /></div> \
                  </a> \
                </div> \
			    <div class="content"></div> \
              </td> \
              <td class="bu"/> \
            </tr> \
            <tr> \
              <td class="bl"/><td class="bu"/><td class="br"/> \
            </tr> \
          </tbody> \
        </table> \
      </div> \
    </div>'
    },

    loading: function() {

      init()
      if ($('#facebox .ngeload').length == 1) return true
      showOverlay()

      $('#facebox .content').empty()
      $('#facebox .body').children().hide().end().
        append('<div class="ngeload">'+($.facebox.settings.loadingImage ? '<center><img src="'+$.facebox.settings.loadingImage+'"/></center>' :
		       $.facebox.settings.loadingText)+'</div>')

      $('#facebox').css({
        top:	getPageScroll()[1] + (getPageHeight() / 10),
        //top:	getPageScroll()[1] + ($('#wgaris').offset().top - 20 ),
        left:	$(window).width() / 3
        //left:	($('#wgaris').width()) + 10
      }).show()

      $(document).bind('keydown.facebox', function(e) {
        if (e.keyCode == 27) $.facebox.close()
        return true
      })
      $(document).trigger('loading.facebox')
    },

    reveal: function(data, klass) {
      $(document).trigger('beforeReveal.facebox')
      if (klass) $('#facebox .content').addClass(klass)
      //$('#facebox #thead_title').append($.facebox.settings.titlePop)
      $('#facebox #thead_title').html($.facebox.settings.titlePop)
      $('#facebox .content').append(data)
      $('#facebox .ngeload').remove()
      $('#facebox .body').children().fadeIn('normal')
      $('#facebox').css('left', $(window).width() / 2 - ($('#facebox table').width() / 2))
      //$('#facebox').css('left', ($('#wgaris').width()) + 10)
      $(document).trigger('reveal.facebox').trigger('afterReveal.facebox')
    },

    close: function() {
      $(document).trigger('close.facebox')
      return false
    }
  })

  /*
   * Public, $.fn methods
   */

  $.fn.facebox = function(settings) {
    if ($(this).length == 0) return

    init(settings)

    function clickHandler() {
      $.facebox.loading(true)

      // support for rel="facebox.inline_popup" syntax, to add a class
      // also supports deprecated "facebox[.inline_popup]" syntax
      var klass = this.rel.match(/facebox\[?\.(\w+)\]?/)
      if (klass) klass = klass[1]

      fillFaceboxFromHref(this.href, klass)
      return false
    }

    return this.bind('click.facebox', clickHandler)
  }

  /*
   * Private methods
   */

  // called one time to setup facebox on this page
  function init(settings) {
    if ($.facebox.settings.inited) return true
    else $.facebox.settings.inited = true

    $(document).trigger('init.facebox')
    //makeCompatible()
    var imageTypes = $.facebox.settings.imageTypes.join('|')
    $.facebox.settings.imageTypesRegexp = new RegExp('\.(' + imageTypes + ')$', 'i')

    if (settings) $.extend($.facebox.settings, settings)
	makeCompatible()

    $('body').append($.facebox.settings.faceboxHtml)

    var preload = [ new Image(), new Image() ]
    preload[0].src = $.facebox.settings.closeImage
    preload[1].src = $.facebox.settings.loadingImage

    $('#facebox').find('.b:first, .bl, .br, .tl, .tr').each(function() {
      preload.push(new Image())
      preload.slice(-1).src = $(this).css('background-image').replace(/url\((.+)\)/, '$1')
    })
    $('#facebox .close_image').css('background-image', 'url('+$.facebox.settings.closeImage+')').click($.facebox.close)
  }

  // getPageScroll() by quirksmode.com
  function getPageScroll() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
      yScroll = self.pageYOffset;
      xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
      yScroll = document.documentElement.scrollTop;
      xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
      yScroll = document.body.scrollTop;
      xScroll = document.body.scrollLeft;
    }
    return new Array(xScroll,yScroll)
  }

  // Adapted from getPageSize() by quirksmode.com
  function getPageHeight() {
    var windowHeight
    if (self.innerHeight) {	// all except Explorer
      windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
      windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
      windowHeight = document.body.clientHeight;
    }
    return windowHeight
  }

  // Backwards compatibility
  function makeCompatible() {
    var $s = $.facebox.settings

    $s.loadingImage = $s.loading_image || $s.loadingImage
    $s.loadingText = $s.loading_text || $s.loadingText
    $s.closeImage = $s.close_image || $s.closeImage
    $s.imageTypes = $s.image_types || $s.imageTypes
    $s.faceboxHtml = $s.facebox_html || $s.faceboxHtml
  }

  // Figures out what you want to display and displays it
  // formats are:
  //     div: #id
  //   image: blah.extension
  //    ajax: anything else
  function fillFaceboxFromHref(href, klass) {
    // div
    if (href.match(/#/)) {
      var url    = window.location.href.split('#')[0]
      var target = href.replace(url,'')
      if (target == '#') return
      $.facebox.reveal($(target).html(), klass)

    // image
    } else if (href.match($.facebox.settings.imageTypesRegexp)) {
      fillFaceboxFromImage(href, klass)
    // ajax
    } else {
      fillFaceboxFromAjax(href, klass)
    }
  }

  function fillFaceboxFromImage(href, klass) {
    var image = new Image()
    image.onload = function() {
      $.facebox.reveal('<div class="image"><img src="' + image.src + '" /></div>', klass)
    }
    image.src = href
  }

  function fillFaceboxFromAjax(href, klass) {
    $.get(href, function(data) { $.facebox.reveal(data, klass) })
  }

  function skipOverlay() {
    return $.facebox.settings.overlay == false || $.facebox.settings.opacity === null
  }

  function showOverlay() {
    if (skipOverlay()) return

    if ($('#facebox_overlay').length == 0)
      $("body").append('<div id="facebox_overlay" class="facebox_hide"></div>')

    $('#facebox_overlay').hide().addClass("facebox_overlayBG")
      .css('opacity', $.facebox.settings.opacity)
      .click(function() { $(document).trigger('close.facebox') })
      .fadeIn(120)
    return false
  }

  function hideOverlay() {
    if (skipOverlay()) return

    $('#facebox_overlay').fadeOut(180, function(){
      $("#facebox_overlay").removeClass("facebox_overlayBG")
      $("#facebox_overlay").addClass("facebox_hide")
      $("#facebox_overlay").remove()
    })

    return false
  }

  /*
   * Bindings
   */
  $(document).bind('close.facebox', function() {
    $(document).unbind('keydown.facebox')
    $('#facebox').fadeOut(function() {
      $('#facebox .content').removeClass().addClass('content')
      hideOverlay()
      $('#facebox .ngeload').remove()
    })
  })

})(jQuery);
//--- end jQ_ajax