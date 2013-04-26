<?php
/*****************************************************
 * Rapidleech 36B Rv.8
 *
 * $Id: mtn.php $
 *****************************************************/

define('RAPIDLEECH', 'yes');

require_once "./global.php";

define('CREDITS', '<small class="footer2">Sakib Hossain | Slider234 | DarkNight<br />Improved and updated by jmsmarcelo</small>');
// Do you want to know why I renamed options for mtn with mtn_ ... ? This is the answer :D
$mtn = array();
foreach ($options as $k => $v) {
	if (strpos($k, 'mtn_') !== false) {
		$mtn[$k] = $v;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charSet;?>" />
<title>MTN :: <?php echo $RL_VER; ?> ::</title>
<link type="text/css" href="<?php print IMAGE_DIR; ?>style_sujancok<?php print $options["csstype"];?>.css?<?php echo rand(1, 9999);?>" rel="stylesheet" media="screen" />
<link type="image/gif" rel="shortcut icon" href="<?php echo IMAGE_DIR . 'ico_home.gif?' . rand(11, 9999);?>" />
<script type="text/javascript" src="<?php echo STATIC_DIR;?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo STATIC_DIR;?>js.php?mtn"></script>
<script type="text/javascript" src="<?php echo STATIC_DIR;?>jscolor/jscolor.js"></script>
<?php
if (!isset($_POST['generate'])) {
?>
<script type="text/javascript">
/* <![CDATA[ */
var mtn_options = jQuery.parseJSON(<?php echo json_encode(json_encode($mtn));?>);
var help_id = ['videofile', 'width', 'height', 'text', 'edge', 'step', 'individualshots', 'saveinfo'];

function load_current_config() {
// Now use the filtered options for mtn :D
	$.each(mtn_options, function(k, v){
		if (k !== 'mtn_showconfig') {
			if (jQuery.type(mtn_options[k]) === 'object') {
				$.each(mtn_options[k], function(key, values){
					if (jQuery.type(mtn_options[k][key]) === 'boolean') {
						$('#'+ k +'_'+ key +'').prop('checked', values ? true : false);
					} else if(jQuery.type(mtn_options[k][key]) === 'number') {
						$('#'+ k +'_'+ key +'').val(Math.floor(values));
					} else {
						$('#'+ k +'_'+ key +'').val(values);
					}
				});
			} else if (jQuery.type(mtn_options[k]) === 'boolean') {
				$('#'+ k +'').prop('checked', v ? true : false);
			} else if(jQuery.type(mtn_options[k]) === 'number') {
				$('#'+ k +'').val(Math.floor(v));
			} else {
				$('#'+ k +'').val(v);
			}
		}
	});
	$('#mtn_video_options_enable').prop('checked') ? $('#mtn_video_options_0').show() : $('#mtn_video_options_0').hide();
	$('#mtn_time_enable').prop('checked') ? $('#mtn_time_0').show() : $('#mtn_time_0').hide();
}

$(document).ready(function() {
	$("#generate").removeAttr("disabled");
	$('#generate').click(function() { document.mtn_form.submit(); });

	$("#mtn_video_options_enable").click(function() { $('#mtn_video_options_0').toggle(); } );
	$("#mtn_time_enable").click(function() { $('#mtn_time_0').toggle(); } );

	$.each(help_id, function(i, v){
		$('#'+ v +'').mouseout(function() { $('#help_'+ v +'').css('display', 'none'); }).mouseover(function() { $('#help_'+ v +'').css('display', 'block'); });
	});

	load_current_config();
});
/* ]]> */
</script>
<?php
}
?>
</head>
<body>
<div class="head_container" align="center">
<a href="<?php echo $options['index_file']; ?>" class="tdheadolgo" title="Rapidleech"></a>
</div>
<br />
<div align="center">
<noscript><p><b><?php echo $L->say['js_disable'];?></b></p></noscript>
<?php
if ($options["limit_timework"]) {
	$is_worktime = cek_worktime($options["workstart"], $options["workend"]);
	$limitmsg = "";
	if (!$is_worktime) {
		if (!empty($limitmsg)) {
			$limitmsg.="<br />";
		}$limitmsg.=$L->sprintf($L->say['worktime_alert'], $options["workstart"]);
		echo "<div style=\"padding-top:20px;padding-bottom:20px;\"><div class=\"warn_alert\">{$limitmsg}</div></div>";
		if ($options['navi_left']['server_info']) {
			if (@file_exists(CLASS_DIR . "sinfo.php")) {
				require_once(CLASS_DIR . "sinfo.php");
				echo "<div style='padding-left:5px;text-align:center;width:auto;margin-top:-5px;'><small>{$server['property']}" . $L->sprintf($L->say["page_load"], $maintimer->stop()) . "</small></div>";
			}
		}else echo "<hr />";
		echo "</div></body></html>";
	}
}
?>
<b style="font-size: 30px; font-family: Arial;">Movie Thumbnailer</b><br /><br /><br />
<?php include(CONFIG_DIR . 'create.php'); ?>
<br /><?php echo CREDITS; ?>
<br />
</div>
</body>
</html>