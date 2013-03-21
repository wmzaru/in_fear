<?php
#	the Tablet  18April2005
#	Author: Robert of Maddrio dot com
#	Something to see in the garden
#	Grammar corrections by Talisman
# v1.2 minor updates

function tablet_getmoduleinfo(){
	$info = array(
	"name"=>"the Tablet",
	"version"=>"1.2",
	"author"=>"`2Robert",
	"category"=>"Gardens",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	"settings"=>array(
		"the Table Settings,title",
		"village"=>"What is the name of city the tablet is dedicated to?,location|".getsetting("villagename", LOCATION_FIELDS)
		)
	);
	return $info;
}
function tablet_install(){
	module_addhook("gardens");
	return true;
}
function tablet_uninstall(){
	output("`n`c`b`^ Garden Tablet Module Uninstalled`0`b`c");
	return true;
}
function tablet_dohook($hookname,$args){
	switch($hookname){
	case "gardens":
	addnav("View");
	addnav("The Tablet","runmodule.php?module=tablet");
	break;
	}
return $args;
}
function tablet_run(){
	global $session;
	$village=get_module_setting("village");
	page_header("The Gardens");
	output("`c`b`!Tablet of the fallen`0`b`c`n`n");
	addnav(" exit ");
	addnav("the Gardens","gardens.php");
	output("`2 While wandering around the Gardens you come upon a small stone tablet on the ground. `n");
	output(" You look at the white marble stone upon which it is inscribed: `n`n");
	output("`& Dedicated to fallen hero's of %s `& for their ultimate sacrifices, `n",$village);
	output(" in giving their lives for the benefit of all, `n");
	output(" nay by sickness or the sword of a foe, `n");
	output(" but with courage and bravery, by a Green Dragon they did fall. ");
page_footer();
}
?>