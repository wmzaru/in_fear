<?php

function gravegold_getmoduleinfo(){
	$info = array(
		"name"=>"Grave Gold",
		"version"=>"1.0",
		"author"=>"`^Harry Balzitch",
		"category"=>"Graveyard Specials",
		"download"=>"http://dragonprime.net/users/Harry%20B/gravegold.zip",
	);
	return $info;
}

function gravegold_install(){
	module_addeventhook("graveyard", "return 100;");
	return true;
}

function gravegold_uninstall(){
	return true;
}

function gravegold_dohook($hookname,$args){
	return $args;
}

function gravegold_runevent($type)
{
	global $session;
	$gold=$session['user']['gold']*2.5;
	output("`^As you search bodies lying around the graveyard, you find many pouches still filled with gold.`n");
	output(" Aren't you the lucky one!`n");
	$session['user']['gold']+=$gold;
	$session['user']['goldinbank']+=$gold;
}

function gravegold_run(){
}
?>