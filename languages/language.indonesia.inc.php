<?php

  /*****************************************************
  ** Title........: Rapidleech PlugMod rev. 36B by eqbal Lang Pack
  ** Author.......: Credits to Pramode & Checkmate & Kloon. Mod by: MsNeil & Idoenk
  ** Filename.....: languages.en.inc.php
  ** Language.....: Bahasa / Indonesia
  ** Lang:Mod.....: Idoenk
  ** Version......: 0.1
  ** Notes........: *
  ** Updated......: 100307 - YYMMDD
  *****************************************************/
  // Set Charset of this language
  $charSet = 'charset=UTF-8';
  //$charSet = 'charset=ISO-8859-1';

  $scrname = substr(basename($_SERVER["PHP_SELF"]), 0, -strlen(strrchr(basename($_SERVER["PHP_SELF"]), ".")));
  $vpage = (!isset($vpage) ? $scrname : $vpage);
  
 
  $gtxt = array(
  // general page; commonly load on every page
     'js_disable'      => 'Javascript Anda tidak aktif',
     '_bypass_autodel' => 'batalkan autodelete dengan param ini',
	 'back_main' => 'Kembali ke main',
	 
	 'no_files' 	=> 'Files tidak ada',
	 'tabel_no_file' => 'Tidak ditemukan file',
	 '_show' => 'Tampilkan',
     '_downloaded' => 'Downloaded',
     '_everything' => 'Semuanya',

	 
     '_maxfilesize' => 'MaxFilesize',
     '_minfilesize' => 'MinFilesize',
     '_refresh' => 'refresh',
     '_autodel' => 'AutoDelete',
     '_pointboost' => 'PointBoost',
	 '_limitip' => 'Download-Terbatas',
     '_fakeext' => 'FakeExt',
     '_fakeext_desc' => 'Auto rename extension dengan setting ini',
     '_timework' => 'RL Waktu Bekerja',
     'wrong_proxy' => 'Alamat proxy tidak valid',
	 'action' => 'Aksi',
     'worktime_alert'=> '&raquo; RL tidak pada Waktu Bekerja, silahkan kembali lain waktu',

     'use_premix' => 'Gunakan PremiX',
     'use_proxy' => 'Settings Proxy',
     '_proxy' => 'Proxy:',
     '_uname' => 'Username:',
     '_pass' => 'Password:',

     'save_to' => 'Simpan Ke',
     'save_path' => 'Path:',
	 
     '_upto' => 'hingga',

     'tabel_sz' => 'Size',
	 'tabel_ip' => 'IP',
     'tabel_dt' => 'Tanggal',
     'tabel_age' => 'Umur File',
     'act_del' => 'Hapus',

     '_second' => 'detik.',
	 
     '_uploading' => 'Mengupload File',
	 
	 'close' => 'Tutup',
	 
     'chk_txt_matches' => 'Check Pencarian',
     'go_match' => 'Cari',	 
     'match_csensitive' => 'Case Sensitive',
     'match_hideunmatch' => 'Hide UnMatch',	 	 
	 
	 'days' => 'hari',
	 'hours' => 'jam',
	 'minutes' => 'menit',
	 'seconds' => 'detik',
	 'ago' => '',
	 'less_a_minute' => 'kurang dari semenit',

     'unauthorized' => 'Anda tidak diijinkan, Silahkan pergi..!',
     'banned' => 'Anda tidak diinginkan disini, Silahkan minggat..!',

     'unauthorized_c' => 'Your country is not authorized, Get Lost..!',
     'banned_c' => 'Your country is banned, disappear now..!',
	 
	 );



/*  ====================================================
*/

switch($vpage)
{
	case "index":
	 $txt = array(
  //main.php it's load from index.php also
     'cpanel'       => 'Panel Kontrol',
     'maintenance'          => 'Sedang direnovasi...!',
     'premix_used_1'         => 'Anda sudah menggunakan',
     'premix_used_2'         => 'PremiX gratis per',
     'premix_used_3'         => 'jam(s)!',
     'premix_used_4'         => 'file gratis untuk hari ini!',
     'sorry_inc'           => 'Maaf atas ketidaknyamanan ini!',
     'quote_alert'=> '&raquo; Batas Trafik Quota Tercapai..!',
     'quote_status'=> 'Sorry, Status Trafik Quota saat ini:',
     'maxstorage_alert_1'=> '&raquo; Batas Maximum Ruang Penyimpanan tercapai, silahkan hapus beberapa file',
     'maxstorage_alert_2'=> ' atau silahkan tunggu sistem autodelete menghapus file',
     'exceed_alert'=> '* batas maximum tercapai;',
     'expired_since'=> '* expired sejak ;',
	 'cpuload_sloadhigh'=> 'Server terlalu sibuk, silahkan tunggu;',
	 
	 'maxjob_limited_1'=> 'Server dibatasi melakukan ',
     'maxjob_limited_2'=> ' simultan downloads secara bersamaan.',
	 
     'link_transload'              => 'Link yang akan ditransload',
     '_transload'              => 'Transload File',
     'referrer'              => 'Referrer',
     'add_comment'      => 'Tambahkan Komentar',
     'user_stats'   => 'Status User:',
     'limit_leech'   => 'Mode Leech Terbatas',
     'detect_ip'   => 'IP Terdeteksi:',
     
	 'server_stats'   => 'Status Server:',
	 'log_act'   => 'Log Aktifitas:',
	 'lact_files'   => 'file(s)',
	 'lact_autodeleted'   => 'terhapus oleh autodelete',	 
	 'current_storage'   => 'Status Ruang Files:',
	 'current_traffic'   => 'Status Traffic:',
	 'reset_traffic_remain'   => 'Sisa Waktu Reset Traffic:',
	 'max_traffic'   => 'maximum quota traffic',
	 
     'send_email'  => 'Kirim File ke Email',
     'email'=> 'Email:',
     'split_file'    => 'Pecah Files',
     'method'=> 'Method:',
     'tot_com' => 'Total Commander',
     'rfc' => 'RFC 2046',
     'part_size' => 'Parts Size:',
	 
	 
	 
     'save_sett' => 'Simpan Settings',
     'clear_sett' => 'Hapus Settings yang digunakan',
	 
     'plugin_opt' => 'Opsi Plugin:',
     'plugin_disable' => 'Matikan Semua Plugins',
     'plugin_youtube' => 'Transfer Video YouTube Kualitas Tinggi, Format Mp4 (H264)',
     'plugin_imageshack' => 'ImageShack&reg; TorrentService',
     'plugin_megaupl' => 'Cookie Megaupload.com',
	 'plugin_hotfile' => 'Hotfile.com Cookie Value',
	 'plugin_rs' => 'Rapidshare.com Cookie Value',
     'plugin_buletin' => 'Gunakan Plugin vBulletin',
	 
     '_user' => 'user=',
	 '_auth' => 'auth=',
	 '_enc' => 'enc=',
	 	 
     '_sfrefresh' => 'Refresh',
     'chk_all' => 'Check Semua',
     'chk_unchk' => 'Un-Check Semua',
     'chk_invert' => 'Balik Pilihan',
     
     'act_upload' => 'Unggah',
     'act_ftp' => 'FTP File',
     'act_mail' => 'E-Mail',
     'act_boxes' => 'Mass Submits',
     'act_split' => 'Pecah Files',
     'act_merge' => 'Gabung Files',
     'act_md5' => 'MD5 Hash',
     'act_pack' => 'Pack Files',
     'act_zip' => 'ZIP Files',
     'act_unzip' => 'Unzip Files (beta)',
     'act_rename' => 'Rubah Nama',
     'act_mrename' => 'Mass Rename',
     'act_delete' => 'Hapus',
	 
     'tabel_name' => 'Nama',
     'tabel_dl' => 'Link Download',
     'tabel_cmt' => 'Komentar',
	 
	 
     'curl_notload_1' => 'Anda harus mengaktifkan ekstensi cURL (http://www.php.net/cURL) atau lakukan set',
     'curl_notload_2' => ' di config.php.',
     'curl_enable' => 'cURL aktif',
	 
     'php_below_5' => 'PHP version 5 is recommended although it is not obligatory',
     'php_server_safemode' => 'Check if your safe mode is turned off as the script cannot work with safe mode on',
	 	 
     'work_with' => 'Pemidai Tautan Bekerja pada',
     'link_only' => 'Tampilkan Links Saja',
     'kill_link_only' => 'Tampilkan Links yang mati saja',
     'debud_mode' => 'Mode Debug',
     'debud_mode_notice' => 'Silahkan genti ke mode debug',
     'max_bound_chk_link_1' => 'Nilai Maximum',
     'max_bound_chk_link_2' => 'dari links dicapai.',
     'check_in' => 'diperiksa dalam',
	 
     'rs_acc_chk' => 'Pemidai Account Rapidshare',
     'modded' => 'Modded',
     'un_pass' => 'username:password',
     'curl_stat' => 'mode cURL:',
     'curl_notice' => 'acc checker tidak dapat digunakan tanpa cURL aktif',
     '_on' => 'ON',
     '_off' => 'OFF',
	 
	 
	 //=========================
	 //=index.php
	 
     'path_not_defined' => 'Path tidak ditentukan untuk penyimpanan file ini',
     'size_not_true' => 'Untrue a size of the part is specified',
     'url_unknown' => 'Tipe URL tidak diketahui',
     'url_only_use' => 'Hanya Gunakan',
     'url_or' => 'atau',
	 
     'downloading' => 'Mendownload',
     'prep_dl' => '...Persiapan',
     'leeching' => 'Leeching..',
	 
     'back_main' => 'Kembali ke main',
     '_error' => 'Kesalahan!',
     '_redirect_to' => 'sedang teralihkan ke',
     '_redirecting_to' => 'Mengalihkan ke:',
     '_saved' => 'Tersimpan!',
     '_reload' => 'Muat Ulang',
     '_avg_spd' => 'Kecepatan Rata-rata:',
	 
     'error_upd_list' => 'Gagal mengupdate list files',
     'error_upd_trf_list' => 'Gagal mengupdate list traffic',
	 
     'mail_file_sent' => 'File terkirim ke alamat ini',
     'mail_error_send' => 'Gagal mengirim file!',
     'delete_link' => 'Link Delete:',
     'delete_link_notice' => 'Gunakan link delete ini setelah Anda selesai mendownload file<br>agar hard-disk Anda tidak terlalu cepat penuh.',
	 'zzzzz' => ''
	 
     );
	 
	 $htxt = array(
  //http.php; it's load from index.php also
     '_pwait'       => 'Silahkan tungu',
     '_error_retrieve'       => 'Gagal mendapatkan link',
     '_error_redirectto'       => 'Kesalahan! sedang teralihkan ke',
     '_error_resume'       => 'Batas Resume tercapai',
     '_error_noresume'       => 'Server ini tidak mendukung resume',
     '_error_cantsave'       => 'gagal menyimpan di direktori',
     '_error_trychmod'       => 'Coba me-chmod folder menjadi 777',
     '_error_tryagain'       => 'Coba lagi',
     '_error_imposible_record'       => 'It is not possible to carry out a record in the file',
     '_error_misc'       => 'URL tidak valid atau Kesalahan aneh terjadi',
     '_con_proxy'       => 'Terhubung ke proxy',
     '_con_to'       => 'Terhubung ke',
     '_sorry_tobig'       => 'Maaf, file Anda terlalu BESAR',
     '_sorry_tosmall'       => 'Maaf, file Anda terlalu kecil',
     '_sorry_quotafull'       => 'Maaf, trafik quota tidak mencukupi',

	 'zzzzz' => ''	 
     );
 
	 $optxt = array(
	  //http.php; it's load from index.php also
     'no_support_upl_serv'   => 'No Supported Upload Services!',
     'select_one_file'       => 'Please select at least one file',
     'del_disabled'       	=> 'Delete Disabled',
     '_file'       			=> 'File',
     '_host'       			=> 'Host',
     '_port'       			=> 'Port',
     '_del'       => 'Delete',
     'these_file'       => 'These Files',
     'this_file'       => 'This File',
     '_yes'       => 'Yes',
     '_no'       => 'No',
     '_deleted'       => 'Deleted',
     'couldnt_upd_list'       => 'Couldn\'t update file list. Problem writing to file!',
     'error_delete'       => 'Error deleting',
     'not_found'       => 'Not Found!',
     'error_upd_list'       => 'Error in updating the list!',
     'couldnt_upd'       => 'Couldn\'t Update',
     'del_success'       => 'Delete successful submits',
     'split_part'       => 'Split by Parts',
     '_method'       => 'Method',
     'part_size'       => 'Parts Size',
     'invalid_email'       => 'Invalid E-mail Address.',
     '_and_del'       => 'and deleted.',
     '_not_del'       => 'not deleted!',
     '_but'       => 'but',
     'send_for_addr'       => 'it is sent for the address',
     'error_send'       => 'Error sending file!',
     'filetype'       => 'The filetype',
     'forbidden_unzip'       => 'is forbidden to be unzipped',
     'unzip_success'       => 'unzipped successfully',
     'saveto'       => 'Save To',
     'del_source_aft_split'       => 'Delete source file after successful split',
     'start_split'       => 'Started to split file',
     'part_of'       => 'parts of',

     'use_method'       => 'Using Method',
     'tot_part'       => 'Total Parts',
     'crc_error'       => 'It is not possible to split the file. CRC Error',
     'crc_error_open'       => 'It is not possible to open source file',
     'split_error'       => 'It was not possible to split the file.',
     
	 'piece_exist'       => 'A piece already exists',
	 'crc_exist'       => 'CRC file already exists',
	 'src_notfound'       => 'Source file not found',
	 'dir_inexist'       => 'Directory doesn\'t exist',
	 'error_read_file'       => 'Error reading the file',
	 'error_open_file'       => 'Error openning file',
	 'error_write_file'       => 'Error writing the file',	 
     'split_error_source_not_del'       => 'An error occured. Source file not deleted!',
     'source_del'       => 'Source file deleted.',
     'source_file_is'       => 'Source file is',
     'error_upd_file_exist'       => 'Couldn\'t update. File already exists!',
     'select_crc_001_file_only'       => 'Please select only the .crc or .001 file!',
     'select_crc_001_file'       => 'Please select the .crc or .001 file!',
     'select_crc_file'       => 'Please select the .crc file!',
     'size_cec32_wont_check'       => 'The file size and crc32 won\'t be check',
     'cant_read_crc_file'       => 'Can\'t read the .crc file!',
     'err_output_file_exist'       => 'Error, Output file already exists',
     'err_missing_parts'       => 'Error, missing or incomplete parts',
     'imposible_open_dir'       => 'It is not possible to open destination file',
     'merge_file_not_found'       => 'The files needed to merge are not found!',
     'file_not_open'       => 'The file can\'t be opened for writing!',
     'filesize_unmatch'       => 'Filesize doesn\'t match!',
     'perform_crc'       => 'Do you want to perform a CRC check?',
     'recommend'       => '(recommended)',
	 
     'select_action'       => 'Select an Action',
     'add_zip'       => 'Add files to a ZIP archive',
     'arcv_name'       => 'Archive Name',
     'no_compress'       => 'Do not use compression',
     'no_subdir'       => 'Do not include directories',
     'add_file'       => 'Add Files',
     '_arcv'       => 'Archive',
     '_arcv_name'       => 'Archive Name',
     'success_created'       => 'successfully created!',
     'compress_notice_1'       => 'For use compress gz or bz2 write extension as Tar.gz or Tar.bz2;!',
     'compress_notice_2'       => 'Else this archive will be uncompress Tar',
     'enter_arc_name'       => 'Please enter an archive name!',
     'ready_exist'       => 'already exists!',
     '_error'       => 'Error!',
     'arcv_not_created'       => 'Archive not created.',
     'error_occur'       => 'An error occured!',
     'was_pack'       => 'was packed',
     'pack_in_arcv'       => 'Packed in archive',
     'arcv_empty'       => 'Archive is Empty.',
     'del_source_aft_upl'       => 'Delete source file after successful upload',
     'add_extension'       => 'Add extension',
     '_without'       => 'without',
     'rename_to'       => 'renamed to',
     'couldnt_rename_to'       => 'Couldn\'t rename the file',
     'new_name'       => 'New name',
     'no_permision_rename'       => 'you don\'t have permission to rename files',
     'success_merge_untes'       => 'successfully merged, but not tested!',
     'success_merge'       => 'successfully merged!',
     'crc32_unmatch'       => 'CRC32 checksum doesn\'t match!',
	 
     'you_selected'       => 'You have selected',
     'you_sure_ch_md5'       => 'Are you sure want to change MD5 of these file(s)?',
     'cur_md5'       => 'Current MD5',
     'new_md5'       => 'New MD5',
     'change_md5'       => 'Change&nbsp;MD5',

	 'zzzzz' => ''	 
     );
 
	 break; // end case index
	 
		 
	case "audl":
	$atxt = array(
  //audl.php
     'not_link'       => 'Link tidak ditemukan',
     '_link' 	=> 'Link',
     '_links' 	=> 'Links',
     '_opt' 	=> 'Opsi',
     '_status' 	=> 'Status',
     '_download' 	=> 'Download',
     '_done' 	=> 'DONE',
     '_waiting' 	=> 'Menunggu...',
     '_started' 	=> 'proses..',
     'audl_start' 	=> 'Mulai auto download',
     'add_link' 	=> 'Tambah links',
     'acc_imgshack' 	=> 'Gunakan Akun Imageshack',
     'error_interval' 	=> 'Kesalahan pada interval delay (dari 1 hingga 3600 detik)',
	 'plugin_megaupl' => 'Cookie Megaupload.com',
	 'plugin_hotfile' => 'Cookie Hotfile.com',
	 'plugin_rs' => 'Cookie Rapidshare.com',
	 '_user' => 'user=',
     '_auth' => 'auth=',
     '_enc' => 'enc=',	 
	 'reach_lim_audl' => 'Maaf tidak dapat memproses lebih dari %link% sekaligus.',
	 'auto_check_link' => 'Periksa Link Otomatis',

	 'zzzzz' => ''
	 
     );
	break; // end case audl
	 
	
	case "lynx":
	$ltxt = array(
  //lynx.php
     '_fname'       => 'NamaFile',
     '_b64_desc' 	=> 'Link Download Base64',
     '_term' 	=> '+Term',
     '_b64link' 	=> 'B64Link',
     '_deletelink' 	=> 'Link Hapus',
     '_genlink' 	=> 'Generate Link',

	 'zzzzz' => ''
     );
	 break;  //end case lynx
	 
	case "del": 	 
  $dtxt = array(
  //del.php; 
     '_rsure'       => 'Apakah Anda yakin untuk',
     '_todelete'       => 'menghapus file ini',
     '_sucesdelete'       => 'berhasil dihapus!',
     '_thx'       => 'Terima Kasih.',
     '_inexist'       => 'File tidak ditemukan',


	 'zzzzz' => ''
	 
     );
	 break;  //end case del.php
}

?>