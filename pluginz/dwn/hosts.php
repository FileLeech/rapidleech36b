<?php
$d = dir("pluginz/dwn/");
while (false !== ($entry = $d->read())) {
   if (stristr($entry,'.php')&& !stristr($entry,'index.php')) {
        $hostname = substr($entry,0,-4);
        $hostname = str_replace('_','.',$hostname);
        if ($hostname == 'easy.share.com') $hostname = 'easy-share.com';
        if ($hostname == 'share.online.biz') $hostname = 'share-online.biz';
        if ($hostname == 'galaxyscripts.com') continue;
        if ($hostname == 'vBulletin.plug') continue;
        if ($hostname == 'hosts') continue;
        $host[$hostname] = $entry;
        switch ($hostname) {
            case 'filesonic.com':
                $filesonic_domains = array(
                                            'asia',
                                            'at',
                                            'be',
                                            'bg',
                                            'cc',
                                            'ch',
                                            'cl',
                                            'co.id',
                                            'co.il',
                                            'co.nz',
                                            'co.th',
                                            'com.au',
                                            'com.eg',
                                            'com.hk',
                                            'com.eg',
                                            'com.tr',
                                            'com.vn',
                                            'cz',
                                            'es',
                                            'fi',
                                            'fr',
                                            'gr',
                                            'hk',
                                            'hr',
                                            'hu',
                                            'in',
                                            'it',
                                            'jp',
                                            'kr',
                                            'me',
                                            'mx',
                                            'my',
                                            'net',
                                            'ml',
                                            'pe',
                                            'pk',
                                            'pt',
                                            'ro',
                                            'rs',
                                            'se',
                                            'sg',
                                            'sk',
                                            'tw',
                                            'ua',
                                            'vn',
                                            );
                foreach ($filesonic_domains as $tld) {
                    $host["filesonic.$tld"] = $host['filesonic.com'];
                }
                $host['sharingmatrix.com'] = $host['filesonic.com'];
                break;
            case 'torrific.com':
                $host['btaccel.com'] = $host['torrific.com'];
                break;
            case 'cramit.in':
                $cramit_domains = array(
                                        'eu',
                                        'net',
                                        'us',
                                        );
                foreach ($cramit_domains as $tld) {
                    $host["cramit.$tld"] = $host['cramit.in'];
                }
                break;
            case 'rghost.net':
                $host['rghost.ru'] = $host['rghost.net'];
                break;
            case 'kickload.com':
                $host['storage.to'] = $host['kickload.com'];
                break;
            case 'megaporn.com':
                $host['megarotic.com'] = $host['megaporn.com'];
                break;
			default:
				break;
        }
    }
}
$d->close();
?>