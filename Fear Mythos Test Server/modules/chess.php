<?php
function chess_getmoduleinfo(){
	require("modules/chess/getmoduleinfo.php");
	return $info;
}

function chess_install(){
	require("modules/chess/install.php");
	return true;
}

function chess_uninstall(){
	$tables = array("chess", "chess_saved");
	foreach ($tables as $db) db_query("DROP TABLE IF EXISTS ".db_prefix($db));
	return true;
}

function chess_dohook($hookname, $args){
	require("modules/chess/dohook.php");
	return $args;
}

function chess_run(){
	global $session;
	
	page_header("Chess");
	addnav("Options");
	
	$id = httpget('id');
	$op = httpget('op');
	$move = httpget('move');
	$you = $session['user']['acctid'];
	$current = get_module_pref('current');
	
	require_once("modules/chess/misc_functions.php");
	require_once("modules/chess/run/$op.php");
	
	page_footer();
}
?>