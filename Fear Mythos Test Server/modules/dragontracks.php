<?php
# Dragon Tracks - 17April2005
# Author: Robert of Maddrio dot com
# converted from an 097 forest event

function dragontracks_getmoduleinfo(){
	$info = array(
	"name"=>"Dragon Tracks ",
	"version"=>"1.1",
	"author"=>"`2Robert",
	"category"=>"Forest Specials",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	);
	return $info;
}

function dragontracks_install(){
	output("`^ Installing Dragon Tracks - forest event `n`0");
	module_addeventhook("forest","return 25;");
	return true;
}

function dragontracks_uninstall(){
	output("`^ Un-Installing Dragon Tracks - forest event `n`0");
	return true;
}

function dragontracks_dohook($hookname,$args){
	return $args;
}

function dragontracks_runevent($type){
	global $session;
	$chance = 60;
	$rand = e_rand(1,100);
	if ($rand <= $chance) {
		output("`n`n`2 You stumble into a dark part of the forest. ");
		output("`n`n You quickly notice upon the ground, `ifresh dragon tracks`i! ");
		output("`n`n Following the tracks with hopes of finding the `@Green Dragon`2, your effort fails. ");
		if ($session['user']['turns'] >=2 ){
			output("`n`n`2 You lost time for `^1 `2forest fight! ");
			$session['user']['turns']--;
		}
	}else{
		output("`n`n`2 You stumble into a dark part of the forest. ");
		output("`n`n You quickly notice upon the ground, `ifresh dragon tracks`i! ");
		output("`n`n Following the tracks with hopes of finding the `@Green Dragon`2, your effort fails. ");
		output("`n`n Better luck next time! ");
	}
}

function dragontracks_run(){
}
?>