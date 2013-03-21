<?php
# Version:      1.3
# Updated       July 24, 2006
# Date:         January 26, 2005
# Author:       Robert of maddrio.com / v1.0 Converted by: Kevin Hatfield - Arune
# Distro Site:  http://www.dragonprime.net
# LOGD VER:     Module for 1.x.x

function lep_getmoduleinfo(){
	$info = array(
		"name"=>"Leprechaun",
		"version"=>"1.3",
		"author"=>"`2Robert<br>`7Converted by: Arune",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?topic=2215.0",
		"settings"=>array(
			"Leprechaun - Settings,title",
			"mingold"=>"Minimum gold to steal? (per player level),range,1,100,1|10",
			"maxgold"=>"Maximum gold to steal? (per player level),range,5,200,5|25",
		),
	);
	return $info;
}
function lep_install(){
	if (!is_module_active('lep')){
		output("`^ Installing Leprechaun - forest event `n`0");
	}else{
		output("`^ Up Dating Leprechaun - forest event `n`0");
	}
	module_addeventhook("forest","return 100;");
	return true;
}

function lep_uninstall(){
	output("`^ Un-Installing Leprechaun - forest event `n`0");
	return true;
}

function lep_dohook($hookname,$args){
	return $args;
}

function lep_runevent($type){
	global $session;
	$level=$session['user']['level'];
	$min=get_module_setting("mingold");
	$max=get_module_setting("maxgold");
	$gold = e_rand($level*$min,$level*$max);
	output("`n`n`2 You hear a strange noise and it causes you to see what was. ");
	output("`n`n Stepping lightly, you head towards a group of dark shade tree's. ");
	output("`n`n You see a `@ Leprechaun `2 relieving himself, leaving his Pot of Gold within your reach. ");
	output("`n`n Very quietly, you reach out and *relieve* him of %s gold! ",$gold);
	$session['user']['gold']+=$gold;
	debuglog("stole $gold gold from a leprechaun"); 
}
function lep_run(){
}
?>