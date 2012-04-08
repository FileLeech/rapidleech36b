<?php
$host = array();
$d = dir("pluginz/download/");
while (false !== ($entry = $d->read())) {
    if (stristr($entry, '.php') && !stristr($entry, '.JD')) {
        $hostname = substr($entry, 0, -4);
        $hostname = str_replace('_', '.', $hostname);
        if ($hostname == 'real.debrid.com') $hostname = 'real-debrid.com';
        if ($hostname == 'fast.debrid.com') $hostname = 'fast-debrid.com';
        if ($hostname == 'file.upload.net') $hostname = 'file-upload.net';
        if ($hostname == 'share.online.biz') $hostname = 'share-online.biz';
        if ($hostname == 'cash.file.net') $hostname = 'cash-file.net';
        if ($hostname == 'i.filez.com') $hostname = 'i-filez.com';
        if ($hostname == 'galaxyscript.com' || $hostname == 'vBulletin.plug' || $hostname == 'hosts') continue;
        $host[$hostname] = $entry;
        switch ($hostname) {
        //define the domain so we dont need to make new plugin with only 3-6 lines code
            case '1fichier.com':
                $d1fichier_domains = array("alterupload.com", "cjoint.net", "desfichiers.com", "dfichiers.com", "megadl.fr", "mesfichiers.org",
                    "piecejointe.net", "pjointe.com", "tenvoi.com", "dl4free.com");
                foreach ($d1fichier_domains as $d1fichier) {
                    $host["$d1fichier"] = $host['1fichier.com'];
                }
                break;
            case 'cramit.in':
                $cramit_domains = array('cramitin.eu','cramitin.net','cramitin.us');
                foreach ($cramit_domains as $cramit) {
                    $host["$cramit"] = $host['cramit.in'];
                }
                break;
            case 'crocko.com':
                $host['easy-share.com'] = $host['crocko.com'];
                break;
            case 'filepost.com':
                $host['fp.io'] = $host['filepost.com'];
                break;
            case 'filerio.com':
                $host['filekeen.com'] = $host['filerio.com'];
                break;
            case 'freakshare.com':
                $host['freakshare.net'] = $host['freakshare.com'];
                break;
            case 'speedyshare.com':
                $host['speedy.sh'] = $host['speedyshare.com'];
                break;
        }
    }
}
$d->close();
?>
