<?php

function gopherhole_getmoduleinfo(){
	$info = array(
	"name"=>"Gopher Hole",
	"version"=>"1.0",
	"author"=>"`6Harry Balzitch",
	"category"=>"Forest Specials",
	"download"=>"http://dragonprime.net/users/Harry%20B/gopherhole.zip",
	"settings"=>array(
	"Gopher Hole Settings,title",
	"mingold"=>"Minimum gold to find (multiplied by level),range,100,300,1|100",
	"maxgold"=>"Maximum gold to find (multiplied by level),range,500,1000,1|100"
	),
	);
	return $info;
}

function gopherhole_install(){
	module_addeventhook("forest","return 100;");
	return true;
}

function gopherhole_uninstall(){
	return true;
}

function gopherhole_dohook($hookname,$args){
	return $args;
}

function gopherhole_runevent($type)
{
	global $session;
	$min = $session['user']['level']*get_module_setting("mingold");
	$max = $session['user']['level']*get_module_setting("maxgold");
	$gold = e_rand($min, $max);
	output("`n`2You stumble into a gopher hole! `n`nLittle did you know a `@Leprechaun `2was sleeping in there.");
	output("`n`n Trapped by your foot, the `@Leprechaun `2has no choice but to hand over %s gold to you for his freedom!", $gold);
	$session['user']['gold']+=$gold;
	debuglog("found $gold gold in the forest");
}

function gopherhole_run(){
}
?>