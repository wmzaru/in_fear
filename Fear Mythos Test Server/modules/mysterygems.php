<?php
function mysterygems_getmoduleinfo(){
	require("modules/mysterygems/getmoduleinfo.php");
	return $info;
}
function mysterygems_install(){
	require_once("modules/mysterygems/install.php");
	return true;
}
function mysterygems_uninstall(){return true;}
function mysterygems_dohook($hookname, $args){
	global $session;
	require_once("modules/mysterygems/dohook/$hookname.php");
	return $args;
}
function mysterygems_run(){
	global $playermount, $session;
	page_header("Gem's Eternal Mysteries");
	$op = httpget('op');
	require_once("modules/mysterygems/run/case_$op.php");
	page_footer();
}
?>