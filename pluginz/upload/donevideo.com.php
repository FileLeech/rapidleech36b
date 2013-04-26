    <?php
    ####### Plugin's Settings #######
    $domain = 'www.donevideo.com'; // May require the www. (Check first if the site adds the www.)
    $IsXfsFree = false; // Change to true if the host is using XFS free.
    $_path = '/'; // URL path to XFS script, default: '/'
    $acc_key_name = 'donevideo_com'; // (XFS Pro)
    $custom_op_name = 'upload'; // Custom ?op=value for checking upload page, default: 'upload'
    $anonupload = true; // Allow non registered users upload. | (XFS Pro)
    $anonlimit = 0; // Filesize limit for non registered users (MB) - 0 = Plugin's limit | (XFS Pro)
    ########################
     
    ######## Account Info ########
    $upload_acc[$acc_key_name]['user'] = 'XXXXXX'; //Set your login
    $upload_acc[$acc_key_name]['pass'] = 'XXXXXX'; //Set your password
    ########################
     
    $_GET['proxy'] = isset($_GET['proxy']) ? $_GET['proxy'] : '';
    $not_done = true;
     
    if (!$IsXfsFree && !empty($upload_acc[$acc_key_name]['user']) && !empty($upload_acc[$acc_key_name]['pass'])) {
            $_REQUEST['up_login'] = $upload_acc[$acc_key_name]['user'];
            $_REQUEST['up_pass'] = $upload_acc[$acc_key_name]['pass'];
            $_REQUEST['action'] = 'FORM';
            echo "<b><center>Using Default Login.</center></b>\n";
    }
     
    if (!$IsXfsFree && (empty($_REQUEST['action']) || $_REQUEST['action'] != 'FORM')) {
            echo "<table border='0' style='width:270px;' cellspacing='0' align='center'>
            <form method='POST'>
            <input type='hidden' name='action' value='FORM' />
            <tr><td style='white-space:nowrap;'>&nbsp;Username*</td><td>&nbsp;<input type='text' name='up_login' value='' style='width:160px;' /></td></tr>
            <tr><td style='white-space:nowrap;'>&nbsp;Password*</td><td>&nbsp;<input type='password' name='up_pass' value='' style='width:160px;' /></td></tr>\n";
            echo "<tr><td colspan='2' align='center'><br /><input type='submit' value='Upload' /></td></tr>\n";
            echo "<tr><td colspan='2' align='center'><small>*You can set it as default in <b>".basename(__FILE__)."</b></small></td></tr>\n";
            echo "</table>\n</form>\n";
    } else {
            $not_done = false;
            if (substr($_path, 0, 1) != '/') $_path = '/'.$_path;
            if (substr($_path, -1) != '/') $_path .= '/';
            $referer = "http://$domain$_path";
     
            // Login
            echo "<table style='width:600px;margin:auto;'>\n<tr><td align='center'>\n<div id='login' width='100%' align='center'>Login to ".str_replace('www.', '', $domain)."</div>\n";
     
            $cookie = array('lang' => 'english');
            if (!$IsXfsFree && !empty($_REQUEST['up_login']) && !empty($_REQUEST['up_pass'])) {
                    $post = array();
                    $post['op'] = 'login';
                    $post['redirect'] = '';
                    $post['login'] = $_REQUEST['up_login'];
                    $post['password'] = $_REQUEST['up_pass'];
     
                    $page = geturl($domain, 80, $_path.'?op=login', $referer, $cookie, $post, 0, $_GET['proxy'], $pauth);is_page($page);
                    $header = substr($page, 0, strpos($page, "\r\n\r\n"));
                    if (stripos($header, "\nLocation: ") !== false && preg_match('@\nLocation: (https?://[^\r\n]+)@i', $header, $redir) && 'www.' . strtolower($domain) == strtolower(parse_url($redir[1], PHP_URL_HOST))) html_error("Please set \$domain to 'www.$domain'.");
                    if (preg_match('@Incorrect ((Username)|(Login)) or Password@i', $page)) html_error('Login failed: User/Password incorrect.');
                    is_present($page, 'op=resend_activation', 'Login failed: Your account isn\'t confirmed yet.');
                    is_notpresent($header, 'Set-Cookie: xfss=', 'Error: Cannot find session cookie.');
                    $cookie = GetCookiesArr($header);
                    $cookie['lang'] = 'english';
                    $login = true;
            } elseif ($IsXfsFree) $login = false;
            else {
                    if (!$anonupload) html_error('Login failed: User/Password empty.');
                    echo "<b><center>Login not found or empty, using non member upload.</center></b>\n";
                    if ($anonlimit > 0 && $fsize > $anonlimit*1024*1024) html_error('File is too big for anon upload');
                    $login = false;
            }
     
            // Retrive upload ID
            echo "<script type='text/javascript'>document.getElementById('login').style.display='none';</script>\n<div id='info' width='100%' align='center'>Retrive upload ID</div>\n";
     
            $page = geturl($domain, 80, $_path.'?op='.(empty($custom_op_name) ? 'upload' : urlencode($custom_op_name)), $referer, $cookie, 0, 0, $_GET['proxy'], $pauth);is_page($page);
            if (substr($page, 9, 3) != '200') {
                    $page = geturl($domain, 80, $_path, $referer, $cookie, 0, 0, $_GET['proxy'], $pauth);is_page($page);
            }
            if (!$login) {
                    $header = substr($page, 0, strpos($page, "\r\n\r\n"));
                    if (stripos($header, "\nLocation: ") !== false && preg_match('@\nLocation: (https?://[^\r\n]+)@i', $header, $redir) && 'www.' . strtolower($domain) == strtolower(parse_url($redir[1], PHP_URL_HOST))) html_error("Please set \$domain to 'www.$domain'.");
            }
            if (!preg_match('@action="((https?://[^/\"]+)?/[^\?\"]+upload\.cgi)\?@i',$page, $up)) html_error('Error: Cannot find upload server.', 0);
            $up[1] = (empty($up[2])) ? "http://$domain".$up[1] : $up[1];
     
            $uid = '';for ($i = 0; $i < 12; $i++) $uid .= rand(0,9);
     
            $post = array();
            $post['upload_type'] = 'file';
            $post['sess_id'] = cut_str($page, 'name="sess_id" value="', '"');
            $post['srv_tmp_url'] = cut_str($page, 'name="srv_tmp_url" value="', '"');
            $post['link_rcpt'] = '';
            $post['link_pass'] = '';
            $post['file_0_descr'] = 'Uploaded by Rapidleech.';
            $post['file_0_public'] = '1';
            $post['tos'] = '1';
            $post['submit_btn'] = ' Upload! ';
     
            $up_url = $up[1]."?upload_id=$uid&js_on=1";
            if (!$IsXfsFree) $up_url .= '&utype='.cut_str($page, "var utype='", "'").'&upload_type=file';
     
            // Uploading
            echo "<script type='text/javascript'>document.getElementById('info').style.display='none';</script>\n";
     
            $url = parse_url($up_url);
            $upfiles = upfile($url['host'], defport($url), $url['path'].(!empty($url['query']) ? '?'.$url['query'] : ''), $referer, $cookie, $post, $lfile, $lname, 'file_0', '', $_GET['proxy'], $pauth);
     
            // Upload Finished
            echo "<script type='text/javascript'>document.getElementById('progressblock').style.display='none';</script>\n";
     
            is_page($upfiles);
     
            if (!$IsXfsFree) {
                    $post = array();
                    $post['op'] = 'upload_result';
                    if (!preg_match('@name=[\'\"]fn[\'\"](?:(?:[\s\t]*>)|(?:[\s\t]*value=[\'\"]))([^\'\"<>]+)@i', $upfiles, $fn)) html_error('Error: fn value not found.');
                    $post['fn'] = trim($fn[1]);
                    $post['st'] = 'OK';
            } else {
                    $page = cut_str($upfiles, '<Form name=\'F1\'', '</Form>');
                    if (!preg_match_all('@<textarea [^<>]*name=\'([^\']+)\'[^<>]*>([^>]*)</textarea>@i', $page, $textareas)) html_error('Error: upload_result data not found.');
                    $post = array_map('urlencode', array_map('html_entity_decode', array_combine($textareas[1], $textareas[2])));
            }
     
            $page = geturl($domain, 80, $_path, $up_url, $cookie, $post, 0, $_GET['proxy'], $pauth);is_page($page);
            $host_rexexp = 'https?://(?:www\.)?'.preg_quote(str_replace('www.', '', $domain).$_path, '@');
     
            if (preg_match('@('.$host_rexexp.'\w{12}(?:/[^\?/<>\"\'\r\n]+)?(?:\.html?)?)\?killcode=\w+@i', $page, $lnk)) {
                    $download_link = $lnk[1];
                    $delete_link = $lnk[0];
            } elseif (preg_match('@'.$host_rexexp.'del-(\w{12})-\w+/([^<>\"\'\r\n]+)@i', $page, $lnk)) {
                    $download_link = substr($lnk[0], 0, (stripos($lnk[0], '/del-') + 1)) . $lnk[2] . '/' . $lnk[3];
                    $delete_link = $lnk[0];
            } elseif (preg_match('@'.$host_rexexp.'\w{12}(?:/[^\?/<>\"\'\r\n]+)?(?:\.html?)?(?=[\r\n\t\s\'\"<>])@i', $page, $lnk)) $download_link = $lnk[0];
            else html_error('Download link not found.', 0);
    }
     
    //[17-8-2012] Written by Th3-822
    //[30-9-2012] Using ?op=upload for some sites that need it. - Th3-822
    //[17-10-2012] Added "domain requires the www." check. - Th3-822
    //[08-11-2012] Added XFS Free support. Th3-822
     
    ?>
