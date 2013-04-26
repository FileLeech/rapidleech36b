<?php
$host = array();
$d = dir(HOST_DIR . 'download/');
$HostnamesToFix = array('cash.file.net' => 'cash-file.net', 'd.h.st' => 'd-h.st', 'ex.load.com' => 'ex-load.com', 'fast.debrid.com' => 'fast-debrid.com', 'real.debrid.com' => 'real-debrid.com', 'share.now.net' => 'share-now.net','share.online.biz' => 'share-online.biz');
$HostnamesToIgnore = array('generic.minifilehost', 'youtube.com(1)', 'vBulletin.plug', 'hosts');
while (false !== ($entry = $d->read())) {
	if (strtolower(strrchr($entry, '.')) == '.php' && stripos($entry, '.JD') === false) {
		$hostname = strtolower(substr($entry, 0, -4));
		if (empty($hostname)) continue;
		$hostname = str_replace('_', '.', $hostname);
		if (array_key_exists($hostname, $HostnamesToFix)) $hostname = $HostnamesToFix[$hostname];
		if (in_array($hostname, $HostnamesToIgnore, true)) continue;
		$host[$hostname] = $entry;
		switch ($hostname) {
		//define the domain so we dont need to make new plugin with only 3-6 lines code
			case '1fichier.com':
				$d1fichier_domains = array('alterupload.com', 'cjoint.net', 'desfichiers.com', 'dfichiers.com', 'megadl.fr', 'mesfichiers.org', 'piecejointe.net', 'pjointe.com', 'tenvoi.com', 'dl4free.com');
				foreach ($d1fichier_domains as $d1fichier) $host["$d1fichier"] = $host['1fichier.com'];
				break;
			case 'cloudzer.net':
				$host['clz.to'] = $host['cloudzer.net'];
				break;
			case 'cramit.in':
				$cramit_domains = array('cramitin.eu', 'cramitin.net', 'cramitin.us');
				foreach ($cramit_domains as $cramit) $host["$cramit"] = $host['cramit.in'];
				break;
			case 'crocko.com':
				$host['easy-share.com'] = $host['crocko.com'];
				break;
			case 'depositfiles.com':
				$depositfiles_domains = array('depositfiles.in', 'depositfiles.mobi', 'depositfiles.net', 'depositfiles.net.cn', 'depositfiles.org', 'depositfiles.co.uk', 'depositfiles.info', 'dfiles.eu', 'dfiles.ru', 'dfiles.co', 'dfiles.co.uk');
				foreach ($depositfiles_domains as $depositfiles) $host["$depositfiles"] = $host['depositfiles.com'];
				break;
			case 'd-h.st':
				$host['dev-host.org'] = $host['d-h.st'];
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
			case 'filecloud.io':
				$host['ifile.it'] = $host['filecloud.io'];
				break;
			case 'multiupload.nl':
				$host['multiupload.com'] = $host['multiupload.nl'];
				break;
			case 'speedyshare.com':
				$host['speedy.sh'] = $host['speedyshare.com'];
				break;
			case 'uploaded.net':
				$uploaded_domain = array('ul.to', 'uploaded.to');
				foreach ($uploaded_domain as $uploaded) $host["$uploaded"] = $host['uploaded.net'];
				break;
			case 'uppit.com':
				$host['up.ht'] = $host['uppit.com'];
				break;
			case 'upstore.net':
				$host['upsto.re'] = $host['upstore.net'];
				break;
		}
	}
}
unset($HostnamesToFix, $HostnamesToIgnore);
$d->close();
?>