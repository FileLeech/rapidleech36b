<?php

  /*****************************************************
  ** Title........: Rapidleech PlugMod rev. 36B by eqbal Lang Pack
  ** Author.......: Credits to Pramode & Checkmate & Kloon. Mod by: MsNeil & Idoenk
  ** Filename.....: languages.fa.inc.php
  ** Language.....: Farsi-Persian  
  ** Lang:Mod.....: -
  ** Version......: 0.1
  ** Notes........: *
  ** Updated......: 6-04-2011
  ** Translation..: Dverbame-WWW.DVERLEECH.COM
  *****************************************************/
  // Set Charset of this language
  $charSet = 'charset=UTF-8';
  //$charSet = 'charset=ISO-8859-1';
  
  $scrname = substr(basename($_SERVER["PHP_SELF"]), 0, -strlen(strrchr(basename($_SERVER["PHP_SELF"]), ".")));
  $vpage = (!isset($vpage) ? $scrname : $vpage);


  $gtxt = array(
  // general page; commonly load on every page
     'js_disable'      => 'قابلیت جاوا اسکریپت برای شما غیر فعال است',
     '_bypass_autodel' => 'حذف اتوماتیک (فرعی) بوسیله این پارامتر.',
	 'back_main' => 'بازگشت به صفحه اصلی',
	 
	 'no_files' 	=> 'هیچ فایلی موجود نیست',
	 'tabel_no_file' => 'هیچ فایلی یافت نشد',
	 '_show' => 'نمایش',
     '_downloaded' => 'دانلود شده',
     '_everything' => 'همه',	 
	 
     '_maxfilesize' => 'بیشترین حجم فایل',
     '_minfilesize' => 'کم ترین حجم فایل',
     '_refresh' => 'بروزرسانی',
     '_autodel' => 'حذف خودکار',
     '_pointboost' => 'افزایش پوینت ها',
     '_limitip' => 'محدودیت دانلود',
     '_fakeext' => 'ext مجازی',
     '_fakeext_desc' => 'تغییر نام اتوماتیک پسوند ها با ...',
     '_timework' => 'زمان کار لیچر',
     'wrong_proxy' => 'ادرس پر|و|ک|سی اشتباه وارد شده',
	 'action' => 'عملیات',
     'worktime_alert'=> '&raquo; لیچر در حال حاضر خارج از سرویس میباشد.لطفا بعدا مراجعه فرمایید.',

     'use_premix' => 'استفاده از اکنت',
     'use_proxy' => 'استفاده از تنظیمات پر|و|ک|سی',
     '_proxy' => 'پ|رو|ک|سی :',
     '_uname' => 'نام کاربری:',
     '_pass' => 'پسورد:',

     'save_to' => 'ذخیره در',
     'save_path' => 'مسیر:',
	 
     '_upto' => 'حداکثر تا',

     'tabel_sz' => 'حجم ( سایز)',
	 'tabel_ip' => 'ای پی لیچ ',
     'tabel_dt' => 'تاریخ',
     'tabel_age' => 'عمر فایل',
     'act_del' => 'پاک کردن',

     '_second' => 'ثانیه .',
	 
     '_uploading' => 'در حال اپلود فایل',
	 
     'close' => 'بستن',
	 
     'chk_txt_matches' => 'چک کردن منطبق ها',
     'go_match' => 'تطبیق',	 
     'match_csensitive' => 'حساس به حالت(وضعیت)',
     'match_hideunmatch' => 'مخفی کردن غیر منطبق ها',	 
	 
	 'days' => 'روز(ها)',
	 'hours' => 'ساعت(ها)',
	 'minutes' => 'دقیقه(ها)',
	 'seconds' => 'ثانیه(ها)',
	 'ago' => 'گذشته',
	 'less_a_minute' => 'کم تر از 1دقیقه',
	 
     'unauthorized' => 'شما تاییدیه ورود ندارید..!',
     'banned' => 'شما از سرویس بن شده اید...!',
	 
     'unauthorized_c' => 'کشور شما تایید شده نیست..!',
     'banned_c' => 'کشور شما بن شده است..!',
	 
	 );



/*  ====================================================
*/

switch($vpage)
{
	case "index":
	 $txt = array(
  //main.php it's load from index.php also
     'cpanel'       => 'پنل ادمین ',
     'maintenance'          => 'در حال بهبود...',
     'premix_used_1'         => 'شما یوزر پرمیم خود را استفاده کرده اید.',
     'premix_used_2'         => 'اکنت پرمیوم رایگان در',
     'premix_used_3'         => 'ساعت(ها)!',
     'premix_used_4'         => 'فایل های رایکان برای امروز!',
     'sorry_inc'           => 'با عرض پوزش برای مشکل بوجود امده',
     'quote_alert'=> '&raquo; خطای اعلام پایان ترافیک !',
     'quote_status'=> 'با عرض پوزش وضعیت ترافیک مصرفی :',
     'maxstorage_alert_1'=> '&raquo; حداکثر تعیین شده برای فضا استفاده شده,لطفا تعدادی فایل را پاک نمایید.',
     'maxstorage_alert_2'=> ' یا کمی صبر کنید تا حذف خودکار میزانی فضا را خالی کند.',
     'exceed_alert'=> '* حداکثر محدودیت;',
     'expired_since'=> '* منقضی شدن در;',	 
     'cpuload_sloadhigh'=> 'لود سرور بسیار بالا است,لطفا بعدا مراجعه کنید.',
	 
     'maxjob_limited_1'=> 'محدودیت میزان دانلود سرور تا  ',
     'maxjob_limited_2'=> ' فعالیت ها در یک زمان.',
	 
     'link_transload'              => 'لینک برای انتقال',
     '_transload'              => 'انتقال فایل',
     'referrer'              => 'رجوع دهنده',
     'add_comment'      => 'افزودن نظر',
     'user_stats'   => 'وضعیت کاربر:',
     'limit_leech'   => 'لیچ محدود شده',
     'detect_ip'   => 'ای پی شما :',
     
	 'server_stats'   => 'وضعیت سرور:',
	 'log_act'   => 'خلاصه فعالیت ها :',
	 'lact_files'   => 'فایل(ها)',
	 'lact_autodeleted'   => 'پاک شده با حذف خودکار:',
	 'current_storage'   => 'فضای استفاده شده فعلی :',
	 'current_traffic'   => 'ترافیک فعلی :',
	 'reset_traffic_remain'   => 'ریست ترافیک باقی مانده:',
	 'max_traffic'   => 'حداکثر ترافیک',
	 
     'send_email'  => 'ارسال فایل به ایمیل',
     'email'=> 'ایمیل:',
     'split_file'    => 'جداسازی فایل ها',
     'method'=> 'روش:',
     'tot_com' => 'مدیریت کل',
     'rfc' => 'RFC 2046',
     'part_size' => 'حجم پارت ها:',
	 
	 
     'save_sett' => 'ذخیره تنظیمات',
     'clear_sett' => 'پاک کردن تنظیمات فعلی',
	 
     'plugin_opt' => 'تنظیمات افزونه ها',
     'plugin_disable' => 'غیر فعال کردن همه افزونه ها',
     'plugin_youtube' => 'انتقال ویدیو های یوتیوب با کیفیت بالا (H264)',
     'plugin_imageshack' => 'ImageShack&reg; TorrentService',
     'plugin_megaupl' => 'کوکی مگا اپلود',
     'plugin_hotfile' => 'کوکی هات فایل',
     'plugin_rs' => 'کوکی رپید شیر',
     'plugin_buletin' => 'استفاده از پلاگین وی بولوتین',
	 
     '_user' => 'یوزر=',
     '_auth' => 'یوزر=',
     '_enc' => 'یوزر=',
	 	 
     '_sfrefresh' => 'بروزرسانی',
     'chk_all' => 'مارک کردن همه',
     'chk_unchk' => 'بدون مارک کردن همه',
     'chk_invert' => 'عکس انتخاب',
	 
     'act_upload' => 'اپلود',
     'act_ftp' => 'اف تی پی',
     'act_mail' => 'ایمیل',
     'act_boxes' => 'ثبت توده',
     'act_split' => 'جداسازی فایلها',
     'act_merge' => 'وصل کردن فایل ها',
     'act_md5' => 'MD5 Hash',
	 'act_rar' =>	'Rar فایلها',
	 'act_unrar' => 'Unrar فایلها',

     'act_pack' => 'پک کردن فایل ها',
     'act_zip' => 'زیپ کردن فایل ها',
     'act_unzip' => 'انزیپ فایل ها ( ازمایشی)',
     'act_rename' => 'تغییر نام',
     'act_mrename' => 'تغییر نام دسته ای',
     'act_delete' => 'حذف',
	 
     'tabel_name' => 'نام',
     'tabel_dl' => 'لینک دانلود',
     'tabel_cmt' => 'نظرات',
	 
	 
     'curl_notload_1' => 'شما باید اکستینشن curl را فعال کنید.(http://www.php.net/cURL) و یا شما میتوایند از قسمت دیگری این قابلیت را لود کنید.',
     'curl_notload_2' => ' در config.php.',
     'curl_enable' => ' cURL فعال شد.',
	 
     'php_below_5' => ' ورژن5 PHPتوصیه میشود.',
     'php_server_safemode' => 'اسکریپت با حالت فعال سیف مود قادر به قعالیت نیست.لطفا سیف مود را خاموش کنید .',
	 	 
     'work_with' => 'چک کننده لینک برای لیست زیر فعال است:',
     'link_only' => 'فقط نمایش لینک ها',
     'kill_link_only' => 'فقط لینک ها',
     'debud_mode' => 'اشکال زدایی',
     'debud_mode_notice' => 'لطفا حالت اشکال زدایی را تغییر دهید به ',
     'max_bound_chk_link_1' => 'Maximum No',
     'max_bound_chk_link_2' => 'Of links have been reached.',
     'check_in' => 'انتخاب شده',
	 
     'rs_acc_chk' => 'چک کننده اکنت رپیدشیر',
     'modded' => 'مد شده',
     'un_pass' => 'نام کاربری | رمز ورود',
     'curl_stat' => ' وضعیت cURL:',
     'curl_notice' => 'بدون فعال بدون cURL از اکنت چکر نمیتوانید استفاده کنید.',
     '_on' => 'فعال',
     '_off' => 'غیر فعال',
	 
	 
	 //=========================
	 //=index.php
	 
     'path_not_defined' => 'مسیر برای ذخیره این فایل مشخص نشده.',
     'size_not_true' => 'سایز قسمتی نادرست تشخیص داده شد.',
     'url_unknown' => 'ادرس نامعتبر',
     'url_only_use' => 'فقط استفاده',
     'url_or' => 'یا',
	 
     'downloading' => 'دانلود کردن',
     'prep_dl' => 'اماده سازی ...',
     'leeching' => 'در حال لیچ کردن..',
	 
     'back_main' => 'بازگشت به صفحه اصلی',
     '_error' => 'خطا!',
     '_redirect_to' => 'تغییر  مسیر به ',
     '_redirecting_to' => 'تغییر مسیر به :',
     '_saved' => 'ذخیره شده!',
     '_reload' => 'بازنشانی مجدد',
     '_avg_spd' => 'سرعت میانگین:',
	 
     'error_upd_list' => 'ناتوان در بروزرسانی لیست فایل ها',
     'error_upd_trf_list' => 'ناتوان در بروزرسانی ترافیک',
	 
     'mail_file_sent' => 'فایل به این ادرس ارسال شد:',
     'mail_error_send' => 'خطا در ارسال فایل !',
     'created_file' => 'فایل ساخته شده:',
     'delete_link' => 'لینک حذف :',
     'delete_link_notice' => 'از این لینک حذف برای حذف فایل خود پس از دانلود استفاده کنید.<br>با حذف به موقع فایل به دیگران هم فرصت استفاده بدهید.',
	 'zzzzz' => ''
	 
     );
	 
	 $htxt = array(
  //http.php; it's load from index.php also
     '_pwait'       => 'لطفا منتظر بمانید',
     '_error_retrieve'       => 'خطادر بروزرسانی لینک',
     '_error_redirectto'       => 'خطا ! تغییر مسیر به :',
     '_error_resume'       => 'شما به حداکثر میزان رزیم (توقف وشرع مجدد) رسیده اید.',
     '_error_noresume'       => 'سرور شروع مجدد را ساپورت نمیکند.',
     '_error_cantsave'       => 'ناتوان در ذخیره در ادرس',
     '_error_trychmod'       => 'پرمیشن پوشه فایل ها را 777 قرار دهید.',
     '_error_tryagain'       => 'سعی مجدد',
     '_error_imposible_record'       => 'خارج کردن قسمتی از فایل غیر ممکن است',
     '_error_misc'       => 'ادرس نامعتبر و یا خطای نامشخص رخ داده.',
     '_con_proxy'       => 'متصل شده به پر|وک|سی',
     '_con_to'       => 'متصل شده به',
     '_sorry_tobig'       => 'متسفانه فایل شما خیلی بزرگ است.',
     '_sorry_tosmall'       => 'متاسفانه فایل شما خیلی کوچک است.',
     '_sorry_quotafull'       => 'متاسفانه ترافیک کافی موجود نمیباشد.',
     '_sorry_insuficient_storage' => 'متاسفانه فضای کافی موجود نیست.',
     '_max_filesize' => 'حداکثر حجم فایل:',
     '_min_filesize' => 'حداقل حجم فایل :',
     '_storage_limit' => 'محدودیت فضا :',
     '_retrieving' => 'در حال دريافت صفحه دانلود',
     '_enter' => 'وارد كنيد',
     '_here' => 'در اينجا',

	 'zzzzz' => ''	 
     );	 
 
 
 // Un-translated :: $optxt
 	 $optxt = array(
     'no_support_upl_serv'   => 'هیچ سرویس اپلودی ساپورت نمیشود.',
     'select_one_file'       => 'لطفا حداقل یک فایل را انتخاب کنید.',
     'del_disabled'       	=> 'حذف غیر فعال شده است.',
     '_file'       			=> 'فایل',
     '_host'       			=> 'هاست',
     '_port'       			=> 'پورت',
     '_del'       => 'حذف',
     'these_file'       => 'این فایل ها',
     'this_file'       => 'این فایل',
     '_yes'       => 'بله',
     '_no'       => 'نه',
     '_deleted'       => 'حذف شده.',
     'couldnt_upd_list'       => 'ناتوان در بروزرسانی لیست فایل ها.ناتوان در نوشتن در فایل',
     'error_delete'       => 'خطا در هنکام حذف',
     'not_found'       => 'یافت نشد!',
     'error_upd_list'       => 'خطا در بروزرسانی لیست!',
     'couldnt_upd'       => 'ناتوان در اپدیت!',
     'del_success'       => 'حذف ثبت های موفق',
     'split_part'       => 'جداسازی با پارت',
     '_method'       => 'روش :',
     'part_size'       => 'حجم پارت ها ',
     'invalid_email'       => 'ادرس ایمیل نا معتبر!',
     '_and_del'       => 'و پاک شده',
     '_not_del'       => 'پاک نشده!',
     '_but'       => 'اما',
     'send_for_addr'       => 'ارسال شده برای ادرس',
     'error_send'       => 'خطا در ارسال فایل!',
     'filetype'       => 'نوع فایل',
     'forbidden_unzip'       => 'غیر مجاز برای خارج کردن از زیپ',
     'unzip_success'       => 'با موفقیت از زیپ خارح شد',
     'saveto'       => 'ذخیره در',
     'del_source_aft_split'       => 'حذف فایل اصلی پس از جداسازی موفق فایل',
     'start_split'       => 'شروع به جداسازی',
     'part_of'       => 'قسمت های ',
     'use_method'       => 'استفاده از روش',
     'tot_part'       => 'مجموع قسمت ها',
     'crc_error'       => 'ناتوان در قسمت کردن فایل ها .خطای CRC Error',
     'crc_error_open'       => 'ناتوان در باز کردن فایل منبع',
     'split_error'       => 'ناتوان در تقسیم فایل',
	 'piece_exist'       => 'یک قسمت موجود است.',
	 'crc_exist'       => ' فایل CRC وجود دارد.',
	 'src_notfound'       => 'فایل منبع یافت نشد.',
	 'dir_inexist'       => 'مبدا موجود نیست.',
	 'error_read_file'       => 'خطا در خواندن فایل',
	 'error_open_file'       => 'خطا در باز کردن فایل',
	 'error_write_file'       => 'خطا در نوشتن فایل',
     'split_error_source_not_del'       => 'خطایی رخ داد. فایل منبع پاک نشده است!',
     'source_del'       => 'فایل منبع پاک شده است.',
     'source_file_is'       => 'فایل منبع',
     'error_upd_file_exist'       => 'ناتوان در بروز رسانی! فایل موجود است.',
     'select_crc_001_file_only'       => 'لطفا فقط فایل ها با پسوند .001 و یا .crc را انتخاب نمایید. ',
     'select_crc_001_file'       => 'لطفا فقط فایل ها با پسوند .001 و یا .crc را انتخاب نمایید!',
     'select_crc_file'       => 'لطفا فایل راانتخاب کنید.',
     'size_cec32_wont_check'       => 'حجم فایل و crc32 چک نخواهند شد.',
     'cant_read_crc_file'       => 'ناتوان در خواندن فایل .crc',
     'err_output_file_exist'       => 'خطا فایل خروجی موجود است',
     'err_missing_parts'       => 'خطا! پارت ناقص!',
     'imposible_open_dir'       => 'ناتوان در باز کردن فایل منبع',
     'merge_file_not_found'       => 'فایل های مورد نیاز برای وصل کردن موجود نیستند!',
     'file_not_open'       => 'فایل نمیتواند برای نشوتن باز شود!',
     'filesize_unmatch'       => 'حجم فایل درست نیست!',
     'perform_crc'       => 'نیاز به چک کردن CRC ؟',
     'recommend'       => '-توصیه شده-',
	 
     'select_action'       => 'انتخاب یک عملیات',
     'add_zip'       => 'افزودن فایل به ارشیو زیپ',
     'arcv_name'       => 'نام ارشیو',
     'no_compress'       => 'استفاده نکردن از فشرده سازی',
     'no_subdir'       => 'مجزا از دایرکتوری ها',
     'add_file'       => 'افزودن فایل ها',
     '_arcv'       => 'ارشیو',
     '_arcv_name'       => 'نام ارشیو',   
     'success_created'       => 'با موفقیت ساخته شد!',
     'compress_notice_1'       => 'برای فشرده سازی توسط gz یا bz2 فایل را با پسوند Tar.gz  یا Tar.bz2 ذخیره نمایید.  ',
     'compress_notice_2'       => 'دی غیر این صورت ارشیو با tar از حالت فشرده خارج میشود.',
     'enter_arc_name'       => 'نام ارشیو را وارد نمایید.',
     'ready_exist'       => 'موجود است!',
     '_error'       => 'خطا!',
     'arcv_not_created'       => 'ارشیو ساخته نشده!',
     'error_occur'       => 'خطایی رخ داده!',
     'was_pack'       => 'پک شده',
     'pack_in_arcv'       => 'پک شده در ارشیو',
     'arcv_empty'       => 'ارشیو خالی است.',
     'del_source_aft_upl'       => 'حذف فایل منبع پس از اپلود موفق.',
     'add_extension'       => 'افزودن اسکتینشن',
     '_without'       => 'بدون',
     'rename_to'       => 'تغییر نام به ',
     'couldnt_rename_to'       => 'ناتوان در تغییر نام فایل',
     'new_name'       => 'نام جدید',
     'no_permision_rename'       => 'شما اجازه تغییر فایل ها را ندارید.',
     'success_merge_untes'       => 'با موفقیت به هم وصل شدند.!',
     'success_merge'       => 'با موفقیت به هم وصل شدند!',
     'crc32_unmatch'       => ' چک کردن CRC32 مطابقت ندارد.',
	 
     'you_selected'       => 'شما انتخاب کرده اید.',
     'you_sure_ch_md5'       => 'نیاز به تغییر MD5 این فایل (ها) دارید ؟',
     'cur_md5'       => 'MD5 فعلی',
     'new_md5'       => 'MD5 جدید',
     'change_md5'       => 'تغییر&nbsp;MD5',

//Additional language for RAR/UNRAR
     'rar_disappear'		=> 'Can not find "rar"<br />You may need to download it and extract "rar" to "/rar/" directory',
     '_filearcv'		=> 'فایل های کد در فایل فشرده قرار میگیرند :',
     '_namearcv'      => 'نام فایل فشرده',
     '_raropt'      => 'تنظیمات:',  
     '_rarcpr'		=> 'سطح فشرده سازی :',
     '_store'     => 'ذخيره کردن',
     '_fastest'      =>	'حیلی سریع',
     '_fast'      =>	'سریع',
     '_normal'      =>	'معمولی',
     '_good'      =>	'خوب',
     '_best'      =>	'عالی',
     '_volumes'     =>	'ایجاد جلد',
     '_delarcv'      => 'حذف فایل ها بعد از ساخت فایل فشرده',
     '_solarcv'      =>	'ایجاد فایل جامد',
     '_recarcv'      =>	'ایجاد رکورد بازیابی',
     '_testarcv'      =>	'تست بعد از فشرده سازی',
     '_pasarcv'       => 'استفاده از کلمه عبور',
     '_encarcv'	      =>	'رمزگذاری نام فایل',
     '_patharcv'      =>	'مسیر تنظیم در داخل فایل',
     '_rar'	      =>	'Rar',
     '_crearcv'	      =>	'ساخت فایل فشرده: <b>%1$s</b>',
     '_wait'      =>	'صبر کنید ...',
     '_backlist'      =>	'برگشت به لیست فایلها',
     '_filefr'      =>	'<b>فایل ها از %1$s</b>:',
     '_unrar'	      =>	'unrar پیدا نشد ',
     '_passlist'	      =>	'کلمه عبور برای لیست فایل ها :',
     '_passext'	      =>	'کلمه عبور برای استخراج فایل ها :',
     '_unrarerr'      =>	'خطا:%1$s',
     '_trylist'	      =>	'سعی دوباره برای لیست کردن فایلها',
     '_unrarsel'	      =>	'Unrar انتخاب شده .',
     '_extracting'	      =>	'<b>اسخراج فایل ها از %1$s</b>:',
     '_stat'      =>	'وضعیت:',
     '_separcv'	      =>	'انتخاب متن :',

	 'zzzzz' => ''	 
     );

 
	 break; // end case index
	 
		 
	case "audl":
	$atxt = array(
  //audl.php
     'not_link'       => 'لینک نیست',
     '_link' 	=> 'لینک',
     '_links' 	=> 'لینک ها',
     '_opt' 	=> 'تنظیمات',
     '_status' 	=> 'وضعیت',
     '_download' 	=> 'دانلود',
     '_done' 	=> 'پایان',
     '_waiting' 	=> 'ر حال انتظار..',
     '_started' 	=> 'اغاز شده..',
     'audl_start' 	=> 'اغاز دانلود خودکار',
     'add_link' 	=> 'افزودن لینکها',
     'acc_imgshack' 	=> 'استفاده از اکنت Imageshack',
     'error_interval' 	=> 'خطا در زمان انتظار',
	 'plugin_megaupl' => 'کوکی مگا اپلود',	 
	 'plugin_hotfile' => 'کوکی هات فایل',	 
	 'plugin_rs' => 'کوکی رپید شیر',	 
	 '_user' => 'یوزر=',
     '_auth' => 'یوزر=',
     '_enc' => 'یوزر=',	 
	 'reach_lim_audl' => 'متاسفانه شما قادر به انتقال %link% لینک در هر نوبت میباشید.',
	 'auto_check_link' => 'چک کردن خودکار لینک ها',

	 'zzzzz' => ''
	 
     );
	break; // end case audl
	 
	
	case "lynx":
	$ltxt = array(
  //lynx.php
     '_fname'       => 'نام فایل',
     '_b64_desc' 	=> 'لینک دانلود پایه 64 بیتی',
     '_term' 	=> '+Term',
     '_b64link' 	=> 'B64Link',
     '_deletelink' 	=> 'لینک حذف',
     '_genlink' 	=> 'تولید لینک',
	 
	 'zzzzz' => ''
     );
	 break;  //end case lynx
	 
	case "del":
  $dtxt = array(
  //del.php; 
     '_rsure'       => 'ایا شما مطمئنید؟',
     '_todelete'       => 'حذف این فایل',
     '_sucesdelete'       => 'با موفقیت حذف شد!',
     '_thx'       => 'متشکریم!',
     '_inexist'       => 'موجو نبودن فایل',

	 'zzzzz' => ''
	 
     );
	 break;  //end case del.php
}

?>