<?php
if (!defined('RAPIDLEECH')) {
    require_once("404.php");
    exit;
}

if (!defined("LANG_DIR")) define("LANG_DIR", "languages/");
require_once(LANG_DIR . "language.$lang.inc.php");

global $htxt, $gtxt;

function insert_timer($countd, $caption ="", $timeouttext = "", $hide = false) {
    global $disable_timer;

    if ($disable_timer === true) {
        return true;
    }
    if (!$countd || !is_numeric($countd)) {
        return false;
    }

    $timerid = rand(1000, time());
    echo ('<div align="center">');
    echo ('<span id="global' . $timerid . '">');
    echo ('<br />');
    echo ('<span class="caption">' . $caption . '</span>&nbsp;&nbsp;');
    echo ('<span id="timerlabel' . $timerid . '" class="caption"></span></span>');
    echo ('</div>');
    echo ('<script type="text/javascript">');
    echo ('var count' . $timerid . '=' . $countd . ';');
    echo ('function timer' . $timerid . '() {');
    echo ('if(count' . $timerid . ' > 0) {');
    echo ('document.getElementById("timerlabel' . $timerid . '").innerHTML = ' . $htxt['_pwait'] . ' + " " + count' . $timerid . ' + " sec...";');
    echo ('count' . $timerid . '=count' . $timerid . ' - 1;');
    echo ('setTimeout("timer' . $timerid . '()", 1000);');
    echo ('}');
    echo ('}');
    echo ('timer' . $timerid . '();');
    echo ('</script>');
    flush ();
    for ($nnn = 0; $nnn < $countd; $nnn++) {
        sleep(1);
    }
    flush ();

    if ($hide === true) {
        echo ('<script type="text/javascript">document.getElementById("global' . $timerid . '").style.display="none";</script>');
        flush ();
        return true;
    }

    if ($timeouttext) {
        echo ('<script type="text/javascript">document.getElementById("global' . $timerid . '").innerHTML = "' . $timeouttext . '";</script>');
        flush ();
        return true;
    }
    return true;
}

function insert_new_timer($countd, $displaytext, $caption = "", $text = "") {
    if (!is_numeric($countd)) {
        html_error("Wrong Counter");
    }
    echo ('<div id="code"></div>');
    echo ('<div align="center">');
    echo ('<div id="dl"><h4>' . $gtxt['js_disable'] . '</h4></div></div>');
    echo ('<script type="text/javascript">');
    echo ('var c = ' . $countd . ';');
    echo ('fc();');
    echo ('function fc() {');
    echo ('if(c>0) {');
    echo ('document.getElementById("dl").innerHTML = "' . $caption . ' ' . $htxt['_pwait'] . ' <b>" + c.toFixed(1) + "</b> ' . $gtxt['_second'] . '...";');
    echo ('c = c - .5;');
    echo ('setTimeout("fc()", 500);');
    echo ('} else {');
    echo ('document.getElementById("dl").style.display="none";');
    echo ('document.getElementById("code").innerHTML = unescape("' . $displaytext . '");');
    echo ('}');
    echo ('}');
    echo ('</script>');
    if (!empty($text)) {
        print $text;
    }
    echo ('</body></html>');
}

function is_page($lpage) {
    global $lastError;
    if (!$lpage) {
        html_error($htxt['_error_retrieve'] . "<br />$lastError", 0);
    }
}

function geturl($host, $port, $url, $referer = 0, $cookie = 0, $post = 0, $saveToFile = 0, $proxy = 0, $pauth = 0, $auth = 0, $scheme = "http", $resume_from = 0, $XMLRequest=0) {
    global $nn, $lastError, $PHP_SELF, $AUTH, $IS_FTP, $FtpBytesTotal, $FtpBytesReceived, $FtpTimeStart, $FtpChunkSize, $Resume, $bytesReceived, $fs, $forbidden_filetypes, $rename_these_filetypes_to, $bw_save, $force_name, $rename_prefix, $rename_suffix, $limitsize, $lowlimitsize, $limitbyip, $ipmu, $ada_acc, $pointboost, $add_ext_5city, $htxt, $gtxt, $mip_enabled, $storage_limit;
    $scheme.= "://";

    if (($post !== 0) && ($scheme == "http://" || $scheme == "https://")) {
        $method = "POST";
        $postdata = formpostdata($post);
        $length = strlen($postdata);
        $content_tl = "Content-Type: application/x-www-form-urlencoded" . $nn . "Content-Length: " . $length . $nn;
    } else {
        $method = "GET";
        $postdata = "";
        $content_tl = "";
    }

    $cookies = '';
    if ($cookie) {
        if (is_array($cookie)) {
            $cookies = "Cookie: " . CookiesToStr($cookie) . $nn;
        } else {
            $cookies = "Cookie: " . $cookie . $nn;
        }
    }
    $referer = $referer ? "Referer: " . $referer . $nn : "";

    if ($scheme == "https://") {
        $scheme = "ssl://";
        $port = 443;
    }

    if ($proxy) {
        list($proxyHost, $proxyPort) = explode(":", $proxy);
        $host = $host . ($port != 80 && $port != 443 ? ":" . $port : "");
        $url = $scheme . $host . $url;
    } else {
        $proxyHost = '';
        $proxyPort = '';
    }

    if ($scheme != "ssl://") {
        $scheme = "";
    }

    $http_auth = ($auth) ? "Authorization: Basic " . $auth . $nn : "";
    $proxyauth = ($pauth) ? "Proxy-Authorization: Basic " . $pauth . $nn : "";

    $request = $method . " " . str_replace(" ", "%20", $url) . " HTTP/1.1" . $nn . "Host: " . $host . $nn . "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.14) Gecko/20080404 Firefox/2.0.0.14" . $nn . "Accept: */*" . $nn . "Accept-Language: en-us;q=0.7,en;q=0.3" . $nn . "Accept-Charset: utf-8,windows-1251;q=0.7,*;q=0.7" . $nn . "Pragma: no-cache" . $nn . "Cache-Control: no-cache" . $nn . ($Resume ["use"] === TRUE ? "Range: bytes=" . $Resume ["from"] . "-" . $nn : "") . $http_auth . $proxyauth . $referer . ($XMLRequest ? "X-Requested-With: XMLHttpRequest" . $nn : "") . $cookies . "Connection: Close" . $nn . $content_tl . $nn . $postdata;

//write_file(CONFIG_DIR."request.txt", $request, 0); // add

    if (isset($mip_enabled) && $mip_enabled) {
        $mip_action = "download";
        echo "<p>Multi IP Enabled</b>...<br />\n";
        if (file_exists(CLASS_DIR . "mip.php")) @include(CLASS_DIR . "mip.php");
    }else {
        $errno = 0;
        $errstr = "";
        $hosts = ($proxyHost ? $scheme . $proxyHost : $scheme . $host) . ':' . ($proxyPort ? $proxyPort : $port);
        $fp = @stream_socket_client($hosts, $errno, $errstr, 120, STREAM_CLIENT_CONNECT);
    }


    if (!$fp) {
        $dis_host = $proxyHost ? $proxyHost : $host;
        $dis_port = $proxyPort ? $proxyPort : $port;
        html_error("Couldn't connect to " . $dis_host . " at port " . $dis_port, 0);
    }

    if ($errno || $errstr) {
        $lastError = $errstr;
        return false;
    }

    if ($saveToFile) {
        if ($proxy) {
            echo "<p>" . $htxt['_con_proxy'] . ": <b>" . $proxyHost . "</b> at port <b>" . $proxyPort . "</b>...<br />\n";
            echo "GET: <b>" . $url . "</b>...<br />\n";
        } else {
            echo "<p>" . $htxt['_con_to'] . ": <b>" . $host . "</b> at port <b>" . $port . "</b>...<br />";
            echo (isset($multi_ip) && $multi_ip ? "using IP: " . $mip_ip . "<br />\n" : "");
        }
    }

#########################################################################
    //echo "<br><br>| ".$request." |<br><br>";
    fputs($fp, $request);
    fflush($fp);
    $timeStart = getmicrotime();

    $header = '';
    do {
        $header .= fgets($fp, 16384);
    } while (strpos($header, $nn . $nn) === false);

#########################################################################
    //echo "<br><br> |".$header." |<br><br>";

    if (!$header) {
        $lastError = "No header received";
        return false;
    }

    $responsecode = "";
    preg_match('/^HTTP\/1\.0|1 ([0-9]+) .*/', $header, $responsecode);
    if (($responsecode[1] == 404 || $responsecode[1] == 403) && $saveToFile) {
        // Do some checking, please, at least tell them what error it was
        if ($responsecode[1] == 403) {
            $lastError = 'The page was not found!';
        } elseif ($responsecode[1] == 404) {
            $lastError = 'You are forbidden to access the page!';
        } else {
            // Weird, it shouldn't come here...
            $lastError = 'The page was either forbidden or not found!';
        }
        return false;
    }
//write_file(CONFIG_DIR."header.txt", $header);

    if ($saveToFile) {
        if (preg_match("#(a-z0-9.]+)?(([a-z]+).[a-z]+([.a-z]+)?)#", $host, $tmp)) {
            $hostclass = $tmp[2];
            if (substr($hostclass, 0, 1) > 0) {
                $hostclass = "d" . $hostclass;
            }
            $hostclass = str_replace(array("-", "."), "_", $hostclass);
            if (file_exists(HOST_DIR . "download/" . $hostclass . ".php")) {
                /* @var $hostvar DownloadClass */
                require(HOST_DIR . "DownloadClass.php");
                require(HOST_DIR . "download/" . $hostclass . ".php");
                $hostvar = new $hostclass();
                $hostvar->CheckBack($header);
            }
        }
        $bytesTotal = trim(cut_str($header, "Content-Length:", "\n"));

        //check sizelimit feature  (it is in MB)
        if ($limitsize > 0) {
            if ($bytesTotal > $limitsize * 1024 * 1024) {
                $lastError = $htxt['_sorry_tobig'] . ": " . $fileSize = bytesToKbOrMbOrGb($bytesTotal) . " &gt;&gt; " . $htxt['_max_filesize'] . " " . $limitsize . " MB";
                return false;
            }
        }
        if ($lowlimitsize > 0) {
            if ($bytesTotal < $lowlimitsize * 1024 * 1024) {
                $lastError = $htxt['_sorry_tosmall'] . ": " . $fileSize = bytesToKbOrMbOrGb($bytesTotal) . " &gt;&gt; " . $htxt['_min_filesize'] . " " . $lowlimitsize . " MB";
                return false;
            }
        }
        // check storage limit (it is in MB)
        if ($storage_limit > 0) {
            $curstorage = calcUsedSpace();
            if (($curstorage + $bytesTotal) > $storage_limit * 1024 * 1024) {
                $lastError = $htxt['_sorry_insuficient_storage'] . ": " . $fileSize = bytesToKbOrMbOrGb($curstorage) . " &gt;&gt; " . $htxt['_storage_limit'] . " " . $storage_limit . " MB";
                return false;
            }
        }

        if ($limitbyip) {
            if ($ada_acc) {
                $ahost = $host;
                $hostfile = "hosts.php";
                require_once(HOST_DIR . 'download/' . $hostfile); // $host redefine as an array colection plugin
                $ar_host = explode(".", $ahost);
                $_host = ''; $pjg = 0;
                for ($i = 0; $i < count($ar_host); $i++) {
                    if ($pjg < 2) {
                        $_host = $ar_host[count($ar_host) - $i - 1] . ($i == 0 ? "" : ".") . $_host;
                    }
                    $pjg++;
                }
                $dumhost = array(); $i = 0;
                foreach ($host as $dh => $flnya) {
                    $dumhost[$i] = $dh; $i++;
                }
                $host = $ahost; //if($post!==0 || $auth!==0 || $cookie!==0){
                if (in_array($_host, $dumhost)) {
                    $fname = basename($saveToFile) . "." . rand(1, 1000);
                    $limitedfile = "./tmp/$ipmu.$fname";
                    $ret = mkdir("$limitedfile", 0777);
                }
            } //-- ada_acc
        } //-- limitbyip

        if (stristr($host, "rapidshare") && $bytesTotal < 10000) {
            while (!feof($fp)) {
                $page_src .= fread($fp, 1024 * 8);
            }
            is_present($page_src, "is already in use with another ip", "This premium account is already in use with another ip.");
        }
        if (stristr($host, "imageshack") && $bytesTotal < 15000) {
            while (!feof($fp)) {
                $page_src .= fread($fp, 1024 * 8);
            }
            is_present($page_src, "To avoid creation of corrupted zip files, you cannot create a zip on this torrent until it is done downloading");
        }

        $redir = "";
        if (trim(preg_match('/[^\-]Location: *(.+)(\r|\n)+/i', $header, $redir))) {
            $redirect = $redir[1];
            $lastError = $htxt['_error_redirectto'] . " [" . $redirect . "]";
            return FALSE;
        }
        if (in_array(cut_str($header, "WWW-Authenticate: ", " "), array("Basic", "Digest"))) {
            $lastError = "This site requires authorization. For the indication of username and password of access it is necessary to use similar url:<br>http://<b>login:password@</b>www.site.com/file.exe";
            return FALSE;
        }
        $ContentType = trim(cut_str($header, "Content-Type:", "\n"));
        if (stristr($host, "rapidshare") && stristr($ContentType, "text/html") && stristr($header, "404 Not Found")) {
            unset($saveToFile);
            $NoDownload = TRUE;
        } elseif (stristr($host, "megaupload") && stristr($ContentType, "text/html")) {
            unset($saveToFile);
            $NoDownload = TRUE;
        }
        if ($Resume["use"] === TRUE && !stristr($header, "Content-Range:")) {
            if (stristr($header, "503 Limit Exceeded")) {
                $lastError = $htxt['_error_resume'];
            } else {
                $lastError = $htxt['_error_noresume'];
            }
            return FALSE;
        }

        if ($force_name) {
            $FileName = $force_name;
            $saveToFile = dirname($saveToFile) . PATH_SPLITTER . $FileName;
        } else {
            $ContentDisposition = trim(cut_str($header, "Content-Disposition:", "\n")) . "\n";
            if ($ContentDisposition && stripos($ContentDisposition, "filename=") !== false) {
                $FileName = trim(trim(trim(trim(trim(cut_str($ContentDisposition, "filename=", "\n")), "="), "?"), ";"), '"');
                if (strpos($FileName, "/") !== false) $FileName = basename($FileName);
                if (preg_match("/UTF\-8\?B\?(.*)$/i", $FileName, $b64)) $FileName = preg_replace("/[^a-zA-Z0-9\-\.]/", "_", base64_decode($b64[1]));
                $saveToFile = dirname($saveToFile) . PATH_SPLITTER . $FileName;
            }
        }

        if (!empty($add_ext_5city) || !empty($rename_suffix) || !empty($rename_prefix)) {
            if (!empty($add_ext_5city)) {
                $ext = str_replace(".", "", $add_ext_5city);
                $File_Name = basename($saveToFile) . "." . $add_ext_5city;
            }
            if (!empty($rename_prefix)) {
                $File_Name = $rename_prefix . '_' . basename($saveToFile);
            }
            if (!empty($rename_suffix)) {
                $ext = strrchr(basename($saveToFile), ".");
                $before_ext = explode($ext, basename($saveToFile));
                $File_Name = $before_ext[0] . '_' . $rename_suffix . $ext;
            }
            $saveToFile = dirname($saveToFile) . PATH_SPLITTER . $File_Name;
        }
        $filetype = strrchr($saveToFile, ".");
        if (is_array($forbidden_filetypes) && in_array(strtolower($filetype), $forbidden_filetypes)) {
            if ($rename_these_filetypes_to !== false) {
                $saveToFile = str_replace($filetype, $rename_these_filetypes_to, $saveToFile);
            } else {
                $lastError = "The filetype $filetype is forbidden to be downloaded";
                return false;
            }
        }

        if ($pointboost > 0) {
            global $_COOKIE;
            // get cookie for the last index FileName
            //$idfnbost = md5(basename($saveToFile));
            //$numero = (isset($_COOKIE["fnboo".$idfnbost])?(int)$_COOKIE["fnboo".$idfnbost]:0);
            $numero = (isset($_POST["numidx"]) ? (int) $_POST["numidx"] : 0);
            $i = $numero;
            $tmSaveFile = $saveToFile . "." . $numero;
            while ($i < 255) {
                if (@file_exists($tmSaveFile)) {
                    $numero++;
                    $tmSaveFile = $saveToFile . "." . $numero;
                    $i = $numero;
                } else {
                    $i = 255;
                }
            }
            $saveToFile = $tmSaveFile;
        }


        if (@file_exists($saveToFile) && $bw_save) {
            html_error('Download: <a href="' . DOWNLOAD_DIR . basename($saveToFile) . '">' . basename($saveToFile) . '</a>', 0);
        }

        if (@file_exists($saveToFile) && $Resume["use"] === TRUE) {
            $fs = @fopen($saveToFile, "ab");
            if (!$fs) {
                $lastError = "File " . basename($saveToFile) . " " . $htxt['_error_cantsave'] . " " . dirname($saveToFile) . "<br />" . $htxt['_error_trychmod'] . ".<br><a href=\"javascript:location.reload();\">" . $htxt['_error_tryagain'] . "</a>";
                return FALSE;
            }
        } else {
            $exist_file = false;
            if (@file_exists($saveToFile)) {
                $saveToFile = dirname($saveToFile) . PATH_SPLITTER . time() . "_" . basename($saveToFile);
            }
            $fs = @fopen($saveToFile, "wb");
            if (!$fs) {
                $secondName = dirname($saveToFile) . PATH_SPLITTER . str_replace(":", "", str_replace("?", "", basename($saveToFile)));
                $fs = @fopen($secondName, "wb");
                if (!$fs) {
                    $lastError = "File " . basename($saveToFile) . " " . $htxt['_error_cantsave'] . " " . dirname($saveToFile) . "<br />" . $htxt['_error_trychmod'] . ".<br><a href=\"javascript:location.reload();\">" . $htxt['_error_tryagain'] . "</a>";
                    return FALSE;
                }
            }
        }

        flock($fs, LOCK_EX);
        //$bytesTotal = intval(trim(cut_str($header, "Content-Length:", "\n")));
        if ($Resume["use"] === TRUE && stristr($header, "Content-Range:")) {
            list($temp, $Resume["range"]) = explode(" ", trim(cut_str($header, "Content-Range:", "\n")));
            list($Resume["range"], $fileSize) = explode("/", $Resume["range"]);
            $fileSize = bytesToKbOrMbOrGb($fileSize);
        } else {
            $fileSize = bytesToKbOrMbOrGb($bytesTotal);
        }
        $chunkSize = GetChunkSize($bytesTotal);

        $File_Name = basename($saveToFile);
        $ext = "";
        if (!empty($add_ext_5city)) {
            $ext = strrchr(basename($saveToFile), ".");
            $File_Name = substr(basename($saveToFile), 0, -strlen($ext));
        }

        print "File <b>" . $File_Name . "<s><font color=red>" . $ext . "</font></s>&nbsp;[<span class='g'>" . $fileSize . "</span>]</b>..";

        require (MISC_DIR . 'tpl/transloadui.php');

        if ($Resume["use"] === TRUE) {
            $received = bytesToKbOrMbOrGb(filesize($saveToFile));
            $percent = round($Resume["from"] / ($bytesTotal + $Resume["from"]) * 100, 2);
            echo "<script type='text/javascript'>pr('" . $percent . "', '" . $received . "', '0');</script>";
            flush ();
        }
    } else {
        $page = $header;
    }

    do {
        $data = @fread($fp, ($saveToFile ? $chunkSize : 16384));
        if ($data == '') break;
        if ($saveToFile) {
            $bytesSaved = fwrite($fs, $data);
            if ($bytesSaved > -1) {
                $bytesReceived += $bytesSaved;
            } else {
                $lastError = $htxt['_error_imposible_record'] . " " . $saveToFile;
                return false;
            }
            if ($bytesReceived >= $bytesTotal) {
                $percent = 100;
            } else {
                $percent = @round(($bytesReceived + $Resume["from"]) / ($bytesTotal + $Resume["from"]) * 100, 2);
            }
            if ($bytesReceived > $last + $chunkSize) {
                $received = bytesToKbOrMbOrGb($bytesReceived + $Resume["from"]);
                $time = getmicrotime() - $timeStart;
                $chunkTime = $time - $lastChunkTime;
                $chunkTime = $chunkTime ? $chunkTime : 1;
                $lastChunkTime = $time;
                $speed = @round($chunkSize / 1024 / $chunkTime, 2);
                echo "<script type='text/javascript'>pr('" . $percent . "', '" . $received . "', '" . $speed . "');</script>";
                $last = $bytesReceived;
            }
        } else {
            $page.= $data;
        }
    } while (strlen($data) > 0);


    if ($saveToFile) {
        flock($fs, LOCK_UN);
        fclose($fs);
        if ($bytesReceived <= 0) {
            $lastError = $htxt['_error_misc'];
            fclose($fp);
            return FALSE;
        }
    }
    fclose($fp);
    if ($saveToFile) {
        return array("time" => sec2time(round($time)), "speed" => @round($bytesTotal / 1024 / (getmicrotime() - $timeStart), 2), "received" => true, "size" => $fileSize, "bytesReceived" => ($bytesReceived + $Resume["from"]), "bytesTotal" => ($bytesTotal + $Resume["from"]), "file" => $saveToFile);
    } else {
        if ($NoDownload) {
            if (stristr($host, "rapidshare")) {
                is_present($page, "You have reached the limit for Free users", "You have reached the limit for Free users.", 0);
                is_present($page, "The download session has expired", "The download session has expired", 0);
                is_present($page, "Wrong access code.", "Wrong access code.", 0);
                is_present($page, "You have entered a wrong code too many times", "You have entered a wrong code too many times", 0);
                print $page;
            } elseif (stristr($host, "megaupload")) {
                is_present($page, "Download limit exceeded", "Download limit exceeded", 0);
                print $page;
            }
        } else {
            return $page;
        }
    }
}

//simple curl function for https:// logins
function sslcurl($link, $cookie = 0, $post = 0, $refer = 0) {
    $mm = !empty($post) ? 1 : 0;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $link);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U;Windows NT 5.1; de;rv:1.8.0.1)\r\nGecko/20060111\r\nFirefox/1.5.0.1');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    if ($mm == 1) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, formpostdata($post));
    }
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_REFERER, $refer);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    // curl_setopt ( $ch , CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $contents .= curl_exec($ch);
    // $info = curl_getinfo($ch);
    // $stat = $info['http_code'];
    curl_close($ch);
    return $contents;
}

function formpostdata($post=array()) {
    $postdata = "";
    foreach ($post as $k => $v) {
        $postdata .= "$k=$v&";
    }
    // Remove the last '&'
    $postdata = substr($postdata, 0, -1);
    return $postdata;
}

function CookiesToStr($cookie=array()) {
    $cookies = "";
    foreach ($cookie as $k => $v) {
        $cookies .= "$k=$v;";
    }
    // Remove the last ';'
    $cookies = substr($cookies, 0, -1);
    return $cookies;
}

function GetCookies($content) {
    // The U option will make sure that it matches the first character
    // So that it won't grab other information about cookie such as expire, domain and etc
    preg_match_all('/Set-Cookie: (.*)(;|\r\n)/U', $content, $temp);
    $cookie = $temp[1];
    $cook = implode('; ', $cookie);
    return $cook;
}

function GetCookiesArr($content, $del=true, $dval='deleted') {
    preg_match_all('/Set-Cookie: (.*)(;|\r\n)/U', $content, $temp);
    $cookie = array();
    foreach ($temp[1] as $v) {
        $v = explode('=', $v, 2);
        $cookie[$v[0]] = $v[1];
        if ($del && $v[1] == $dval) unset($cookie[$v[0]]);
    }
    return $cookie;
}

function GetChunkSize($fsize) {
    if ($fsize <= 1024 * 1024) {
        return 4096;
    }
    if ($fsize <= 1024 * 1024 * 10) {
        return 4096 * 10;
    }
    if ($fsize <= 1024 * 1024 * 40) {
        return 4096 * 30;
    }
    if ($fsize <= 1024 * 1024 * 80) {
        return 4096 * 47;
    }
    if ($fsize <= 1024 * 1024 * 120) {
        return 4096 * 65;
    }
    if ($fsize <= 1024 * 1024 * 150) {
        return 4096 * 70;
    }
    if ($fsize <= 1024 * 1024 * 200) {
        return 4096 * 85;
    }
    if ($fsize <= 1024 * 1024 * 250) {
        return 4096 * 100;
    }
    if ($fsize <= 1024 * 1024 * 300) {
        return 4096 * 115;
    }
    if ($fsize <= 1024 * 1024 * 400) {
        return 4096 * 135;
    }
    if ($fsize <= 1024 * 1024 * 500) {
        return 4096 * 170;
    }
    if ($fsize <= 1024 * 1024 * 1000) {
        return 4096 * 200;
    }
    return 4096 * 210;
}

function upfile($host, $port, $url, $referer, $cookie, $post, $file, $filename, $fieldname, $field2name = "", $proxy = 0, $pauth = 0, $upagent = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.1) Gecko/2008070208 Firefox/3.1") {
    global $nn, $lastError, $sleep_time, $sleep_count;

    $scheme = "http://";
    $bound = "--------" . md5(microtime());
    $saveToFile = 0;

    unset($postdata);
    if ($post) {
        foreach ($post as $key => $value) {
            $postdata .= "--" . $bound . $nn;
            $postdata .= 'Content-Disposition: form-data; name="' . $key . '"' . $nn . $nn;
            $postdata .= $value . $nn;
        }
    }
    $fileSize = getSize($file);

    $fieldname = $fieldname ? $fieldname : file . md5($filename);

    if (!is_readable($file)) {
        $lastError = "Error read file $file";
        return FALSE;
    }
    if ($field2name != '') {
        $postdata.="--" . $bound . $nn;
        $postdata.="Content-Disposition: form-data; name=\"$field2name\"; filename=\"\"" . $nn;
        $postdata.="Content-Type: application/octet-stream" . $nn . $nn;
    }

    $postdata.="--" . $bound . $nn;
    $postdata.="Content-Disposition: form-data; name=\"$fieldname\"; filename=\"$filename\"" . $nn;
    $postdata.="Content-Type: application/octet-stream" . $nn . $nn;

    $cookies = "";

    if ($cookie) {
        if (is_array($cookie)) {
            $cookies = "Cookie: " . CookiesToStr($cookie) . $nn;
        } else {
            $cookies = "Cookie: " . trim($cookie) . $nn;
        }
    }

    $referer = $referer ? "Referer: " . $referer . $nn : "";
    if ($scheme == "https://") {
        $scheme = "ssl://";
        $port = 443;
    }
    if ($proxy) {
        list($proxyHost, $proxyPort) = explode(":", $proxy);
        $url = $scheme . $host . ":" . $port . $url;
        $host = $host . ":" . $port;
    }

    if ($scheme != "ssl://") {
        $scheme = "";
    }

    $http_auth = ($auth) ? "Authorization: Basic " . $auth . $nn : "";
    $proxyauth = ($pauth) ? "Proxy-Authorization: Basic " . $pauth . $nn : "";

    $zapros = "POST " . str_replace(" ", "%20", $url) . " HTTP/1.0" . $nn . "Host: " . $host . $nn . $cookies . "Content-Type: multipart/form-data; boundary=" . $bound . $nn . "Content-Length: " . (strlen($postdata) + strlen($nn . "--" . $bound . "--" . $nn) + $fileSize) . $nn . "User-Agent: " . $upagent . $nn . "Accept: text/xml,application/xml,application/xhtml+xml,text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5" . $nn . "Accept-Language: en-en,en;q=0.5" . $nn . "Accept-Charset: utf-8,windows-1251;koi8-r;q=0.7,*;q=0.7" . $nn . "Connection: Close" . $nn . $http_auth . $proxyauth . $referer . $nn . $postdata;

#print_r($zapros);
#write_file('debug',$zapros);
    $errno = 0;
    $errstr = "";
    $posturl = ($proxyHost ? $scheme . $proxyHost : $scheme . $host) . ':' . ($proxyPort ? $proxyPort : $port);
    $fp = @stream_socket_client($posturl, $errno, $errstr, 120, STREAM_CLIENT_CONNECT);
    //$fp = @fsockopen ( $host, $port, $errno, $errstr, 150 );
    //stream_set_timeout ( $fp, 300 );

    if (!$fp) {
        $dis_host = $proxyHost ? $proxyHost : $host;
        $dis_port = $proxyPort ? $proxyPort : $port;
        html_error("Couldn't connect to " . $dis_host . " at port " . $dis_port, 0);
    }

    if ($errno || $errstr) {
        $lastError = $errstr;
        return false;
    }

    if ($proxy) {
        echo "<p>" . $htxt['_con_proxy'] . ": <b>" . $proxyHost . "</b> at port <b>" . $proxyPort . "</b>...<br />\n";
        echo "UPLOAD: <b>" . $url . "</b>...<br />\n";
    } else {
        echo "<p>" . $htxt['_con_to'] . ": <b>" . $host . "</b> at port <b>" . $port . "</b>...<br />";
    }
    echo "File <b>" . $filename . "</b>, size <b>" . bytesToKbOrMb($fileSize) . "</b>...<br />";

    global $id;
    $id = md5(time () * rand(0, 10));
    require (MISC_DIR . 'tpl/uploadui.php');
    flush();

    $timeStart = getmicrotime();
//    $len = strlen($zapros);

    $chunkSize = GetChunkSize($fileSize);

    fputs($fp, $zapros);
    fflush($fp);

################################################################
    //echo '| '.$zapros.' |';
//    $pac = ceil($fileSize / $chunkSize);
    $fs = fopen($file, 'r');

//    $i = 0;

    $local_sleep = $sleep_count;
//    echo "<script>pr('0','0 KB','0')</script>";
//    flush();
    while (!feof($fs)) {
        $data = fread($fs, $chunkSize);
        if ($data === false) {
            fclose($fs);
            fclose($fp);
            html_error('Error READ Data');
        }

        if (($sleep_count !== false) && ($sleep_time !== false) && is_numeric($sleep_time) && is_numeric($sleep_count) && ($sleep_count > 0) && ($sleep_time > 0)) {
            $local_sleep--;
            if ($local_sleep == 0) {
                usleep($sleep_time);
                $local_sleep = $sleep_count;
            }
        }

        $sendbyte = fputs($fp, $data);
        fflush($fp);

        if ($sendbyte === false) {
            fclose($fs);
            fclose($fp);
            html_error('Error SEND Data');
        }

        $totalsend+=$sendbyte;

        $time = getmicrotime() - $timeStart;
        $chunkTime = $time - $lastChunkTime;
        $chunkTime = $chunkTime ? $chunkTime : 1;
        $lastChunkTime = $time;
        $speed = round($sendbyte / 1024 / $chunkTime, 2);
        $percent = round($totalsend / $fileSize * 100, 2);
        echo "<script type='text/javascript'>pr('" . $percent . "', '" . bytesToKbOrMb($totalsend) . "', '" . $speed . "');</script>\n";
        flush();
    }
    fclose($fs);

    fputs($fp, $nn . "--" . $bound . "--" . $nn);
    fflush($fp);

    while (!feof($fp)) {
        $data = fgets($fp, 16384);
        if ($data === false) {
            break;
        }
        $page.=$data;
    }

    fclose($fp);

    return $page;
}

?>