<?php
if (!defined('RAPIDLEECH')) {
	require_once("../404.php");
	exit();
}

function callback_render($buffer) {
	$buffer = str_replace("\n", "", $buffer);
	$buffer = str_replace("\t", "", $buffer);
	$buffer = str_replace(chr(13), "", $buffer);
	$buffer = preg_replace("/<!\-\-(?:[a-zA-Z0-9\s\_\-\"\;\/]*)+\-\->/", "", $buffer);
	$buffer = preg_replace("/(>)(?:[\s]{1,}+)(<)/", "$1$2", $buffer);
	return $buffer;
}

//@ob_start('ob_gzhandler');
// Render the main screen
if ($options['compressed_web'] === 1) @ob_start('callback_render');
include(TEMPLATE_DIR . 'header.php');

if ($options['compressed_web'] === 2) @ob_start('callback_render');
include(TEMPLATE_DIR . 'main.php');
include(TEMPLATE_DIR . 'footer.php');

@ob_end_flush();
?>