<?php
require_once('lib/systemmail.php');
if (!httpget('no')){
	$chall = explode("|",get_module_pref('challenges'));
	$key = array_search($id, $chall);
	unset($chall[$key]);
	$challim = implode("|",$chall);
	set_module_pref('challenges',$challim);

	set_module_pref('current',$id);
	set_module_pref('current',$you,FALSE,$id);

	set_module_pref('color',"W");
	set_module_pref('color',"B",FALSE,$id);

	chess_formgameinfo($you, $id);
	output("`c`#LET'S PLAY!`0`c");
	systemmail($id, "`QChess Challenge!`0", "Your chess challenge was accepted!`n`nGO TO THE GARDENS TO PLAY!", $you);
	addnav("PLAY!", "runmodule.php?module=chess&op=play");
} else {
	$chall = explode("|",get_module_pref('challenges'));
	$key = array_search($id, $chall);
	unset($chall[$key]);
	$challim = implode("|",$chall);
	set_module_pref('challenges',$challim);
	output("`c`#Challenge was declined!`0`c");
	systemmail($id, "`QChess Challenge!`0", "Your chess challenge was declined.", $you);
	addnav("Return to Main Page", "runmodule.php?module=chess&op=enter");
	addnav("Return to Village", "village.php");
}
?>