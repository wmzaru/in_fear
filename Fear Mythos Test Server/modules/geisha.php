<?php

function geisha_getmoduleinfo(){
	$info = array(
	"name"=>"Geisha Girls",
	"version"=>"1.0",
	"author"=>"`6Harry B and Kenny Chu",
	"category"=>"Forest Specials",
	"download"=>"http://dragonprime.net/users/Harry%20B/geisha.zip",
	"settings"=>array(
	"Geisha Girls Settings,title",
	"mingold"=>"Minimum gold (multiplied by level),range,100,500,1|100",
	"maxgold"=>"Maximum gold (multiplied by level),range,800,1000,1|100"
	),
	);
	return $info;
}

function geisha_install(){
	module_addeventhook("forest","return 100;");
	return true;
}

function geisha_uninstall(){
	return true;
}

function geisha_dohook($hookname,$args){
	return $args;
}

function geisha_runevent($type)
{
	global $session;
	$min = $session['user']['level']*get_module_setting("mingold");
	$max = $session['user']['level']*get_module_setting("maxgold");
	$gold = e_rand($min, $max);
	output("`n`2You hear screams and run over to where they come from. `n`nYou see a `@Forest Bandit `2trying to dishonor a Geisha Girl. ");
	output("`n`n You rush over and chase the Bandit into the woods, he is too quick and you fail in catching him. ");
	output("`n`n The Geisha Girls are grateful for your service and give you %s gold for saving their honor!", $gold);
	$session['user']['gold']+=$gold;
	debuglog("gain $gold gold from helping geisha girls");
}

function geisha_run(){
}
?>