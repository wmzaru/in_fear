<?php
# Dead Cat - robert of maddrio.com
# 1.4 adds debug
# 1.3 adds admin settings
# 1.2 adds additional outcome


function deadcat_getmoduleinfo(){
	$info = array(
	"name"=>"Dead Cat",
	"version"=>"1.4",
	"author"=>"`2Robert",
	"category"=>"Graveyard Specials",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
		"settings"=>array(
		"Dead Cat - Event Settings,title",
		"name"=>"Who grants the favor?,|Ramius",
		"minfav"=>"Minimum favor,range,1,25,1|3",
		"maxfav"=>"Maximum favor,range,2,50,1|8"
		),
	);
	return $info;
}

function deadcat_install(){
	if (!is_module_active('deadcat')){
		output("`^ Installing Dead Cat - graveyard event `n`0");
	}else{
		output("`^ Up Dating Dead cat - graveyard event `n`0");
	}
	module_addeventhook("graveyard","return 100;");
	return true;
}

function deadcat_uninstall(){
	output("`^ Un-Installing Dead Cat - graveyard event `n`0");
	return true;
}

function deadcat_dohook($hookname,$args){
	return $args;
}

function deadcat_runevent($type){
	global $session;
	$name = get_module_setting("name");
	$min = get_module_setting("minfav");
	$max = get_module_setting("maxfav");
	$fav = e_rand($min, $max);
	output("`n`n`7 As you wander the graveyard, you see something running toward you. ");
	output("`n`n You quickly step off to the side and toss a tombstone upon it. `n");
	output("`7 Upon lifting the tombstone, ");
	switch(e_rand(1,5)){
		case 1: case 2:
		output("`7  you find out it was only a cat! `n`n");
		output("`& You know that `\$ %s `& will be `i very pleased `i with you! ",$name);
		$session['user']['gravefights']+=1;
		$session['user']['deathpower']+=$fav;
		break;
		case 3:
		output("`7 you find nothing. `n`n Maybe you were seeing things?");
		break;
		case 4: case 5:
		output("`7 you find it was only a `b big hairy bug `b, silly you!");
		break;
	}
}

function deadcat_run(){
}
?>