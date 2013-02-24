<?php
if (!defined('RAPIDLEECH')) {
	require('index.html');
	exit();
}

class filemac_com extends DownloadClass {
	
	public function Download($link) {
		$page = $this->GetPage($link);
		$form = cut_str($page, '<Form method="POST" action=\'\'>', '</form>');
		if (!preg_match_all('/<input type="hidden" name="([^"]+)" value="([^"]+)?">/', $form, $one) || !preg_match_all('/<input type="submit" name="(\w+_free)" value="([^"]+)">/', $form, $two)) html_error('Error[Post Form Free 1 not found!]');
		$match = array_merge(array_combine($one[1], $one[2]), array_combine($two[1], $two[2]));
		$post = array();
		foreach ($match as $k => $v) {
			$post[$k] = $v;
		}
		$page = $this->GetPage($link, 0, $post, $link);
		
	}
}

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
