<?php
if (!defined('RAPIDLEECH')) {
	require_once ( "index.html" );
	exit;
}

if (isset($_POST['TBLoad'])) {
	$expires = 60; // set expired cached page in 60 sec
	header("content-type:text/xml; charset=$charSet");
	header("Expires: " . gmdate("D, d M Y H:i:s", TIME_NOW + $expires) . "GMT");

	clearstatcache();

	$rn = "\r\n";

	_create_list();
	$resXML = '';
	{
	/* ==try with XML== */

		if ($list) {
			$resXML = '<data>' . $rn;

			//action files
			$resXML.= '<actions>' . $rn;
			if (!$options["disable_action"]) {
				$resXML.='<option>blank:-1:' . $L->say['action'] . '</option>' . $rn;

				foreach ($options["disable_to"] as $k => $val) {
					if (!$val) {
						$goodtogo = false;
						switch ($k) {
							case 'act_pack':
								if (@file_exists(CLASS_DIR . "pear.php") || @file_exists(CLASS_DIR . "tar.php")) {
									$goodtogo = true;
								}
								break;
							case 'act_zip':
								if (@file_exists(CLASS_DIR . "pclzip.php")) {
									$goodtogo = true;
								}
								break;
							case 'act_unzip':
								if (@file_exists(CLASS_DIR . "unzip.php")) {
									$goodtogo = true;
								}
								break;
							case 'act_rar': case 'act_unrar':
								if (strtoupper(substr(PHP_OS, 0, 3)) != "WIN" && @file_exists(CLASS_DIR . "rar.php")) {
									switch ($k) {
										case 'act_unrar':
											if (@file_exists(BINARY_DIR . 'rar/rar') || @file_exists(BINARY_DIR . 'rar/unrar')) {
												$goodtogo = true;
											}
											break;
										default:
											$goodtogo = true;
											break;
									}
								}
								break;
							default:
								$goodtogo = true;
								break;
						}
						$valupl = str_replace("act_", "", $k);
						if ($goodtogo) {
							$resXML.= '<option>' . $valupl . ':' . $k . ':' . $L->say[$k] . '</option>' . $rn;
						}
					}
				}
			}
			$resXML.= '</actions>' . $rn;

			$feat_ajax = array();
			$feat_ajax["ajax_rename"] = (!$options["disable_to"]["act_rename"] && !$options["disable_ajaxren"] ? '1' : '0' );
			$resXML.= '<instantrename>' . ( $feat_ajax["ajax_rename"] ) . '</instantrename>' . $rn . $rn;

			$feat_ajax["ajax_delete"] = (!$options["disable_to"]["act_delete"] ? '1' : '0' );
			$resXML.= '<ajaxdelete>' . ( $feat_ajax["ajax_delete"] ) . '</ajaxdelete>' . $rn . $rn;

			//querying header
			$resXML.= '<columns>' . $rn;
			$resXML.= ' <chkbox> </chkbox>' . $rn;
			$resXML.= ' <filename>' . $L->say['tabel_name'] . '</filename>' . $rn;
			$resXML.= ' <filesize>' . $L->say['tabel_sz'] . '</filesize>' . $rn;

			if (isset($_COOKIE['showAll']) && $_COOKIE['showAll'] == 1 && $options["show_column_sfile"]["md5"] == true) {
				$resXML.= ' <filemd5>MD5</filemd5>' . $rn;
			}
			if ((!isset($_COOKIE['showAll']) || ( isset($_COOKIE['showAll']) && $_COOKIE['showAll'] != 1 ) ) && $options["show_column_sfile"]["downloadlink"] == true) {
				$resXML.= ' <filelink>' . $L->say['tabel_dl'] . '</filelink>' . $rn;
			}
			if ($options["show_column_sfile"]["comments"] == true) {
				$resXML.= ' <filecomment>' . $L->say['tabel_cmt'] . '</filecomment>' . $rn;
			}
			if ($options["show_column_sfile"]["date"] == true) {
				$resXML.= ' <filedate>' . $L->say['tabel_dt'] . '</filedate>' . $rn;
			}
			if ($options["show_column_sfile"]["age"] == true) {
				$resXML.= ' <fileage>' . $L->say['tabel_age'] . '</fileage>' . $rn;
			}
			if ((!isset($_COOKIE['showAll']) || ( isset($_COOKIE['showAll']) && $_COOKIE['showAll'] != 1 ) ) && $options["show_column_sfile"]["ip"] == true) {
				$resXML.= ' <fileip>' . $L->say['tabel_ip'] . '</fileip>' . $rn;
			}

			if ($feat_ajax["ajax_delete"] == '1') {
				$resXML.= ' <ajax_del>' . $L->say['act_del'] . '</ajax_del>' . $rn;
			}

			$resXML.= '</columns>' . $rn;

			$total_files = 0;
			$total_size = 0;
			$kumulatifsz = true;
			if (isset($list["files"]["totalsize"])) {
				$total_size = $list["files"]["totalsize"];
				$kumulatifsz = false;
			}
			$resXML.= "\n" . '<files>' . $rn;
			foreach ($list as $key => $file) {
				if (@file_exists($file["name"])) {
					$total_files++;
					if ($kumulatifsz) $total_size+=getfilesize($file["name"]);
					$resXML.= "\n" . ' <fileid>brs' . $total_files . '</fileid>' . $rn;
					$resXML.= ' <filename>' . xmlentities(basename($file["name"])) . '</filename>' . $rn;

					$resXML.= ' <filepath>' . link_for_file($file['name'], true) . '</filepath>' . $rn;

					$_fsize = str_replace(" ", "((space))", $file["size"]);
					$_fsize = str_replace("&nbsp;", "((space))", $_fsize);
					$resXML.= ' <filesize>' . $_fsize . '</filesize>' . $rn;

					$_fdate = '';
					if ($options["show_column_sfile"]["date"] == true) {
						$_fdate = str_replace(" ", "((space))", date("d.m.Y H:i:s", $file["date"]));
						$_fdate = str_replace("&nbsp;", "((space))", $_fdate);
						$resXML.= ' <filedate>' . $_fdate . '</filedate>' . $rn;
					}
					$resXML.= ' <filedateid>' . $file["date"] . '</filedateid>' . $rn;

					$resXML.= ' <fileage>' . $file["age"] . '</fileage>' . $rn;

					$_fmd5 = '';
					if (isset($_COOKIE['showAll']) && $_COOKIE['showAll'] == 1 && $options["show_column_sfile"]["md5"] == true) {
						$_fmd5 = ( isset($file["md5"]) ? $file["md5"] : "" );
						$resXML.= ' <filemd5>' . $_fmd5 . '</filemd5>' . $rn;
					}

					$_flink = '';
					$_flinktr = '';
					if ((!isset($_COOKIE['showAll']) || ( isset($_COOKIE['showAll']) && $_COOKIE['showAll'] != 1 ) ) && $options["show_column_sfile"]["downloadlink"] == true) {
						if (isset($file["link"]) && $file["link"] != '') {
							$_flink = xmlentities($file["link"]);
							$_flinktr = trimlink($_flink);
						} else {
							$_flink = '#';
							$_flinktr = '?';
						}
						$resXML.= ' <filelink>' . $_flink . '</filelink>' . $rn;
						$resXML.= ' <filelinktr>' . $_flinktr . '</filelinktr>' . $rn;
					}

					$_fcomment = '';
					if ($options["show_column_sfile"]["comments"] == true && $file["comment"]) {
						$_fcomment = str_replace("\\r\\n", "<br/>", ( isset($file["comment"]) ? $file["comment"] : ""));
						$resXML.= ' <filecomment>' . $_fcomment . '</filecomment>' . $rn;
					}

					$_fip = '';
					if ((!isset($_COOKIE['showAll']) || ( isset($_COOKIE['showAll']) && $_COOKIE['showAll'] != 1 ) ) && $options["show_column_sfile"]["ip"] == true) {
						$_fip = ( isset($file["ip"]) ? $file["ip"] : "?" );
						$resXML.= ' <fileip>' . $_fip . '</fileip>' . $rn;
					}

					$_fdkey = '';
					if ($feat_ajax["ajax_delete"] == "1") {
						$rnd = rand(11, 99);
						$_fdkey = str_replace("=", "", rotN(base64_encode($file["date"] . ':' . '4puZ'), $rnd)) . "-" . $rnd;
						$resXML.= ' <filedelkey>' . $_fdkey . '</filedelkey>' . $rn;
					}
				} //--end file_exits
			} //--end foreach
			$resXML.= '</files>' . $rn;

			if (isset($list["files"]["totalfile"])) $total_files = $list["files"]["totalfile"];
			$resXML.= '<succeed>' . ( $total_files > 0 ? '1' : '0' ) . '</succeed>' . $rn;
			$resXML.= ( $total_files > 0 ? '' : '<msg>' . $L->say['tabel_no_file'] . '</msg>' . $rn );

			$resXML.= "\n" . '<totalfiles>' . $total_files . '</totalfiles>' . $rn;
			$_totsize = str_replace(" ", "((space))", bytesToKbOrMbOrGb($total_size));
			$_totsize = str_replace("&nbsp;", "((space))", $_totsize);
			$resXML.= '<totalsize>' . $_totsize . '</totalsize>' . $rn;


			//count span column
			$n_column = 0;
			foreach ($options["show_column_sfile"] as $cocol => $colval) {
				if ($colval) {
					switch ($cocol) {
						case "ip": // add colspan when not show all & column ip shown
							if (!isset($_COOKIE['showAll']) || ( isset($_COOKIE['showAll']) && $_COOKIE['showAll'] != 1 )) {
								$n_column++;
							}
							break;
						case "md5": // add colspan when show all & column md5 shown
							if (isset($_COOKIE['showAll']) && $_COOKIE['showAll'] == 1) {
								$n_column++;
							}
							break;
						case "downloadlink": // add colspan when downloadlink shown and it's in showdownloaded
							if (!isset($_COOKIE['showAll']) || ( isset($_COOKIE['showAll']) && $_COOKIE['showAll'] != 1 )) {
								$n_column++;
							}
							break;
						default:
							$n_column++;
							break;
					}
				} //-end $colval enable
			} //-end foreach
			$resXML.= '<columnspan>' . ( $feat_ajax["ajax_delete"] == "1" ? ( $n_column + 1 ) : $n_column ) . '</columnspan>' . $rn;

			unset($total_files, $total_size);

			$totaltime2 = $maintimer->stop();
			$resXML.= '<proceestime>' . $totaltime2 . '</proceestime>' . $rn;
			$resXML.= '</data>';
		} else { //--file not found
			$resXML = '<data>' . $rn;
			$resXML.='<succeed>0</succeed>' . $rn;
			$resXML.='<msg>' . $L->say['tabel_no_file'] . '</msg>' . $rn;
			$resXML.='</data>';
		}
	}
	exit($resXML);
}
?>