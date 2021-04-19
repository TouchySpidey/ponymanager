<?php

function html_head($title = false, $extra_css = [], $extra_js = []) {
	$title = 'PonyManager | '.$title;
	include APPPATH.'views/_head.php';
}

function topbar() {
	include APPPATH.'views/_topbar.php';
}
function main_menu() {
	if (defined('_COMPANY_URI') && defined('_GLOBAL_COMPANY')) {
		include APPPATH.'views/_company_menu.php';
	} else {
		include APPPATH.'views/_general_menu.php';
	}
}

function import_js(...$jss) {
	$string = '';
	$version_constant = VERSION;
	foreach ((array) $jss as $js) {
		$string .= "<script src='".site_url()."frontend/js/$js.js?v=$version_constant'></script>";
	}
	return $string;
}
