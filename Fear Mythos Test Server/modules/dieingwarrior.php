<?php
// Dieing Warrior - Robert Riochas
// Dieing Warrior v1.0 19May2005
// added new outcome v1.1 12Jul2005
// admin settings added v1.2 24Jan2006

function dieingwarrior_getmoduleinfo(){
	$info = array(
	"name"=>"Dieing Warrior",
	"version"=>"1.2",
	"author"=>"`2Robert",
	"category"=>"Graveyard Specials",
	"download"=>"http://dragonprime.net/users/robert/dieingwarrior_v1x.zip",
	"settings"=>array(
		"Dieing Warrior - Settings,title",
		"lordname"=>"Name of the underworld Lord?,|Ramius",
		"minfavor"=>"Minimum favor to gain or lose,range,1,10,1|5",
		"maxfavor"=>"Maximum favor to gain or lose,range,1,25,1|10"
		),
	);
	return $info;
}

function dieingwarrior_install(){
	module_addeventhook("graveyard","return 100;");
	return true;
}

function dieingwarrior_uninstall(){
	return true;
}

function dieingwarrior_dohook($hookname,$args){
	return $args;
}

function dieingwarrior_runevent($type){
	global $session;
	$lordname = get_module_setting("lordname");
	$min = get_module_setting("minfavor");
	$max = get_module_setting("maxfavor");
	$sum = e_rand($min,$max);
	output("`n`n`7 As you wander the graveyard, you come upon a dieing warrior. `n");
	output("He gasps and begs for water ...water ...water. `n`n");
	switch(e_rand(1,6)){
	case 1: case 2:
	output("`7 You see a small pool of rancid water nearby, you wet the lips the dieing warrior. `n`n");
	output("`\$ Ramius `7has heard of your act of mercy and kindness. `n`n");
	output("`& You know that `\$ %s `& is `i not very pleased `i with you! `n",$lordname);
	output(" You have lost %s favor with `\$ %s`&! ",$sum,$lordname);
	$session['user']['deathpower']-= $sum;
	break;
	case 3:
	output("`7 You look around and see only small pools of rancid water, you figure you cant be of any assistance. ");
	break;
	case 4: case 5:
	output("`7 As you near the dieing warrior, you notice he breaths his last breath and you can't help him. ");
	break;
	case 6:
	output("`7 You drip water in front of the dieing warrior, taunting him with delight in your heart! `n`n");
	output("`\$ %s `7has heard of your lack of mercy. `n`n",$lordname);
	output("`& You know that `\$ %s `& is `i is very pleased `i with you! `n",$lordname);
	output(" You have gained %s favor with `\$ %s `&! ",$sum,$lordname);
	$session['user']['deathpower']+= $sum;
}
}

function dieingwarrior_run(){
}
?>