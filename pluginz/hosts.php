<?php
$d = dir("pluginz/");
while (false !== ($entry = $d->read())) {
   if (stristr($entry,'.php') {
		$hostname = substr($entry,0,-4);
		$hostname = str_replace('_','.',$hostname);
		if ($hostname == 'easy.share.com') $hostname = 'easy-share.com';
		if ($hostname == 'galaxyscripts.com') continue;
		if ($hostname == 'vBulletin.plug') continue;
		if ($hostname == 'DownloadClass') continue;
		if ($hostname == 'hosts') continue;
		$host[$hostname] = $entry;
   }
}
$d->close();
?>
