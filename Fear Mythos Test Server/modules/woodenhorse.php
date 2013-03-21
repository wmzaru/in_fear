<?php
#	Wooden Horse 24Aug2005
#	Author: Robert of Maddrio dot com
#	Something to do in the garden
#	v1.1 corrects download link 10/2009

function woodenhorse_getmoduleinfo(){
	$info = array(
	"name"=>"Wooden Horse",
	"version"=>"1.1",
	"author"=>"`2Robert",
	"category"=>"Gardens",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	);
	return $info;
}
function woodenhorse_install(){
	module_addhook("gardens");
	return true;
}
function woodenhorse_uninstall(){
	return true;
}
function woodenhorse_dohook($hookname,$args){
	switch($hookname){
	case "gardens":
	addnav("View");
	addnav("Wooden Horse","runmodule.php?module=woodenhorse");
	break;  
    }
return $args;
}
function woodenhorse_run(){
	global $session;
	page_header("The Gardens");
	addnav(" dismount horse ");
	addnav("Get off horse","gardens.php");
	output("`c`b`2The Gardens`b`c`n`n");
	output("`& Walking through the Gardens you find a wooden horse. `n`n");
	output("`2 You sit on the wooden horse for awhile and pretend its real, `n`n");
	output(" looking over your shoulder you see, `n`n");
	switch (e_rand(1,6)) {
		case 1: case 4:
		output("`2 villagers staring at you sitting on the children's plaything.");
		break;
		case 2: case 5:
		output("`2 a small girl with crossed arms and a big frown on her face `n`n");
		output(" is giving you the evil eye while she waits for you `n`n");
		output(" to get off the wooden horse so she can play.");
		break;
		case 3: case 6:
		output("`2 two small boys with wooden swords waiting for you `n`n");
		output(" to get off the wooden horse so they can play on it.");
		break;
	}
page_footer();
}
?>