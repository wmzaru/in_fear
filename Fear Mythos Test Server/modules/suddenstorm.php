<?php
/*
* Date:     April 16, 2005
* Author:   Robert of MaddRio dot com
* v 1.1     optimized code
*/
function suddenstorm_getmoduleinfo(){
	$info = array(
	"name"=>"Sudden Storm",
	"version"=>"1.1",
	"author"=>"`2Robert",
	"category"=>"Forest Specials",
	"download"=>"http://dragonprime.net/users/Robert/suddenstorm098.zip",
	"settings"=>array(
		"Sudden Storm Settings,title",
		"minloss"=>"Minimum turns to lose,range,1,10,1|1",
		"maxloss"=>"Maximum turns to lose,range,2,20,1|3"
		),
	);
	return $info;
}

function suddenstorm_install(){
	module_addeventhook("forest","return 100;");
	return true;
}

function suddenstorm_uninstall(){
	return true;
}

function suddenstorm_dohook($hookname,$args){
	return $args;
}
function suddenstorm_runevent($type){
global $session;
$minloss = get_module_setting("minloss");
$maxloss = get_module_setting("maxloss");
output("`n`n`2 The sky darkens as rain clouds suddenly appear, an unexpected storm is upon you! `n`n ");
	switch(e_rand(1,5)){
	case 1: case 3: case 5:
	output(" You quickly seek shelter under a large tree, the storm passes as fast as it came. `n`n");
	output("`^ You only lost $minloss turns waiting out this sudden storm. ");
	$session['user']['turns']-=$minloss;
	if ($session['user']['turns']<=0) $session['user']['turns']=0;
//	debuglog(" lost $minloss turn waiting out a thunder storm ");
	break;
	case 2: case 4:
	output(" You quickly seek shelter under a large tree, the storm is a terrible one and lasts for some time. `n`n");
	output("`^ You lost $maxloss turns waiting out this sudden storm. ");
	$session['user']['turns']-=$maxloss;
	if ($session['user']['turns']<=0) $session['user']['turns']=0;
//	debuglog(" lost $maxloss turns waiting out a thunder storm ");
    break;
	}
}
function suddenstorm_run(){
}
?>