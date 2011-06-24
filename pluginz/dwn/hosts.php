<?php
$d = dir("pluginz/dwn/");
while (false !== ($entry = $d->read())) {
   if (stristr($entry,'.php')) {
        $hostname = substr($entry,0,-4);
        $hostname = str_replace('_','.',$hostname);
        if ($hostname == 'easy.share.com') $hostname = 'easy-share.com';
        if ($hostname == 'galaxyscripts.com') continue;
        if ($hostname == 'index') continue;
        if ($hostname == 'vBulletin.plug') continue;
        if ($hostname == 'hosts') continue;
        $host[$hostname] = $entry;
   }
}
$d->close();

// Filesonic extra domains... (*http://i53.tinypic.com/2yl72ap.jpg*, 46 domains.).
if (array_key_exists('filesonic.com', $host)) {
    $host['sharingmatrix.com'] = $host['filesonic.com'];
    $filesonic_domains = array('net', 'jp', 'tw', 'it', 'in', 'kr', 'vn', 'hk', 'co.il',
        'sg', 'pk', 'fr', 'at', 'be', 'bg', 'ch', 'cl', 'co.id', 'co.th', 'com.au', 'com.eg',
        'com.hk', 'com.tr', 'com.vn', 'cz', 'es', 'fi', 'gr', 'hr', 'hu', 'mx', 'my', 'pe',
        'pt', 'ro', 'rs', 'se', 'sk', 'ua', 'asia', 'cc', 'co.nz', 'me', 'nl', 'tv');
    foreach ($filesonic_domains as $tld) {
        $host["filesonic.$tld"] = $host['filesonic.com'];
    }
}

?>