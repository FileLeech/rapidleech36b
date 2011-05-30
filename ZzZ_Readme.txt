---------------
Latest Revision
## Project: Rx08.ii36B
## Src: http://ocean-leecher.net/forum/index.php
## SVN/Ori.Source Track: http://code.google.com/p/rapidleech/
##
## Desc: Moded Rapidleech script
## Lic Code: GNU General Public License v3
---------------

Rv.7.3:
 * Fix Traffic update
 * Fix update FILES_LST after deleteIt
 * Fix write/update FILES_LST
 * Fix CSS for tdheadolgo & head_container
 * Fix language
 * Fix & Recoded xpanel
 * Fix & Update zippyshare.com plugin
 * Add hotfile cookie plugin; rapidshare cookie plugin
 * Add Multi_IP (betha)
 * update freakshare.net
 * update hotfile.com
 * update easyshare plugin
 * update zippyshare.com plugin
 * added x7.to download plugin .... finally :p
 * litle fix in mediafire and hotfile for audl
 * update hotfile.com plugin 
 * update speedyshare.com plugin
 * update storage.to plugin 
 * update uploading.com upload plugin 

Rv.7.2:
 * Roll-back public POST/GET key "pr_user" & "pr_pass"
 * Fix several plugins
 * trying get youtube title video for filename
 * failed load serverfiles after downloading redirected youtube video. doing xmlentities for file["link"].
 * update mediafire plugin by kaox 26.php
 * update megashares.com plugin by kaox 11.php
 * Fix msg after deleteIt() (del.php)
 * update zippyshare plugin by kaox 25.php
 * Fix mediafire linkchecker
 * Fix Failed calcUsedSpace (Current Used Storage)
 * add freakshare.net plugin 74.php
 * add sendspace to xpanel
 
Rv.7.1RC:
 * Fix ifile.it acc in xpanel. Thx ROCK.
 * Add btaccel plugin, Thx Viant.
 * Fix uploading.com acc in xpanel (xpanel.php; xpanelform.tpl.php)
 * Fix audl; auul; lynx $limited_area (Country Limitation) always true; (audl.php; auul.php; lynx.php)
 * Fix simultan downlod which is not workin after Rv.5,.
 * Add Limit By Country, db Require classes/GeoIP.dat; SRC: http://geolite.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz
 * Add File Age Column
 * _create_list() always get filectime (last changed) directly from file. 
 * Fix Autodelete issue by adding server timezone.
 * Fix CSS delete confirm, literaly looks like facebook
 * Add Delete Link in Server Files, just like @ Lynx
 * (+)misc/jquery.min.js now included (src from http://ajax.googleapis.com/)
 * Add Switch Using Ajax directly -- (client-side view only). inspired from Gmail, Thx G. >,<
 * Fix failed filesort after doing "release me"
 * Several updated plugin, Thx to Viant.
 
 * deprecated "Running Task Download" feature.
 * clean-up duplicate procedure/function.
 * _create_list() try store filesize & filecount,.
 * Support filesize 2Gb (test-under Win-32bit).
 * Fix lynx.php jQuery ignored when jquery.min.js unavailable.
 * Fix xpanel.php clear cookie.
 * Fix missed Check downloadLimitbyip; Thx Akangage.
 * (Minor) <hr> positioning issue ; Thx habs.
 * (Minor) bug in del.php; Thx ROCK.
 * (Minor) Traffic Left Unit in RS Acc Status ; Thx ROCK.

Rv.7:
 * fix CPU Limit not work; Thx DarkNight
 * fix xpanel failed in numeric check. Thx R0CKABILLY
 * fix TBLoad failed when filename containing Ampersand (&)
 * fix header html_error page 
 * fix limitbyip counter when MU-cookie embeded alone - R.1; Thx Viant77
 * fix saving with UTF-8 from Unicode filename.
 * fix rs (german) acc checker; Thx R0CKABILLY 
 * Add Ajax instant edit (rename file); +rsajax_ren.js; +misc/editsave.png 
 * Add Check Text Matches By Daniel_X_x.
 * Add Auto-Reset Traffic.
 * Add sharebase acc in xpanel. Thx Viant77
 * Switchable to old procedure (dont use ajax in list server-files).
 * Split ajax_main.php for each action
 * deprecated css in Rx08_2208081410_d4Mn.log.php
 * css for themes add node, look for detail.
 
 ! known bug:
   -failed get download link with Unicode filename,., rename it first instead. :nohope:

Rv.6.1:
 * fix xpanel, saving MUhash; premium_acc_audl.
 * fix audl.php, use acc based on premium_acc_audl
 * fix auul.php, clone feature from Rv.5 (MYUPLOAD_LST link)
 * fix index.php, Free DL MU plugin ~line 277
 * fix index.php, Delete Link not workin ~line 568. Thx DarkNight.
 * fix other.php, relist IP_L33CH_L0G on delete file
 * fix [DL] plugin by kaox: filefactory; zshare; uploading. Thx Viant77.
Upd-LOG: index.php; auul.php, audl.php, xpanel.php, xpanelform.tpl.php, misc/js.php

Rv.6: 
 * deprecated feature: safemode
 * deprecated useless status-bar ajax
 
 * static javascript now set as link
 * support limited acc for any available acc
 * add audl limit link
 * ajax delete file in lynx. using Facebox(for jQuery)
 * using googleapis external link for jquery.min.js
 * TBLoad fully with XML parsing.
 * add upload acc in xpanel,.Thx DarkNight, Viant.
 * supported themes:
   -matrix, by habs-zizi.
   -kav by habs.
 
 * fix css thingie main.php 
 * fix css audl iframe 
 * fix css upload
 * fix audl check valid url link
 * fix audl global premium acc embeded
 * fix xpanel upload acc
 * fix xpanel disable action files settings
 * fix index pasca leech (filectime path file)
 * fix limitbyip for every host in plugin hosts . Thx DarkNight
 * fix link checker: (uploading.com; easy-share.com; hotfile.com)
 
 * rename file: rschk.php -> ajax_lnk.php
 * rename file: acchk.php -> ajax_main.php
 
 * fix hotfile plugin premium user
 * fix uploading plugin premium user 

Rv.6b:
* AutoSubmit RS. Free link when c>0
* html_retry failed to switch to free download mode
* outside root download folder
* download dir tested work @ parent dir or higher
* fix path location JS cookie
* Server Checker (separated tool) by TheOnly
* Limit cpuload thx to TheOnly
* Limit MaxJob
* Add lang element
ChangeLog:
02.php; other.php; index.php; acchk.php; xpanel.php; xpanelform.tpl.php; style_sujancok_default.css; style_sujancok_fb.css; rxpanel.js; del.php; language.en.inc.php; language.id.inc.php; 

Rv.5
#$#:
=css_thingie
* Minor Update on css; selective theme.
* setCheckboxes() do change className for tr
* replace tab-button (made by kyra :">) Thanks

=arround_cpanel
* Collapse/Expand grouped config settings, in cpanel
* Add ViewConfig, in cpanel.
* Options to choose shown Column in Server Files. 
* MD5 Column defaultly not shown. eat lot of time show list in ShowAll mode.
* value="" on every embeded account/cookie
* Delete cookie "ShowAll" when showall disabled in cpanel.

* _create_list() never do md5_file in lynx.php. suppose to be faster.
* Clear Setting do try{} for inexist element id
* Fix Link Checker works w/o curl (fgc mode on)
* Link Checker do replace rapidsh*t masking filter
* Optimize Link Checker loop, fill break after one match link checked.
* Fix megarotic||megaporn Link Checker
* Merge-Split w/o crc file. thx to Daniel_X_x.
* Audl addlink() do check correct link format.
* Multiaccount for rapidshare, thx to TheOnly
* Online Visitor. thx to habs,TheOnly
* DEFINE all writable files in index,config.
* is__writable do check FILES_LST is it chmod 777
* Silent mail function error

* Rebuild pointbooster, now working not just in opera.
* fix pointbooster failed get acc on next load
* pointbooster do rewrite downloaded file (saving space)
* pointbooster disabled when fired from audl

* TBLoad ajax update:
:: (TBLoad) refresh failed, coz of http_referrer checking in acchk.php. switch use authorized session. Issued by ind0coder, Thanks.
:: trim long download link in server files
:: boxed table in server files, thx to TheOnly
:: customized action files
:: keep thead viewable in boxed table files
:: add "release me" -> release pure table from the box.
:: mod action files prompt and result; add close button

* Plugins Fix:
__DLPlug:netload.in, thx to mamah vivi
__DLPlug:megaupload, test using cookie,. working.

changed file LOG:
other.php; main.php; acchk.php; options.php; audl.php; lynx.php, 02.php; config.php; xpanel.php; xpanelform.tpl.php; mail.php; js.php