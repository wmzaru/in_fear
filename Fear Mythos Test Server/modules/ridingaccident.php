<?php
// Finished on August 4 2005
// Set chance of encounter lower to decrease the amount of times you encounter this module.
// addnews ready
function ridingaccident_getmoduleinfo(){
	$info = array(
		"name"=>"Riding Accident 1.1",
		"version"=>"1.1",
		"author"=>"Fliquid",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/users/fliquid/ridingaccident.txt",
	);	
	return $info;
}

function ridingaccident_install(){
	module_addeventhook("forest", "return 25;");
	module_addeventhook("travel", "return 10;");
	return true;
}

function ridingaccident_uninstall(){
	return true;
}

function ridingaccident_dohook($hookname,$args){
	return $args;
}

function ridingaccident_runevent($type,$link)
{
	global $session;
	if ($session['user']['hashorse'] > 0) {
	output("`n`^As you ride your mount it suddenly trips and falls on the ground.`0");
	output("`^You quickly rush over to see if it's alright.`0");
	output("`^The poor animal has a broken leg and suffers enormously.`0");
	output("`^You release the animal from its suffering.`0");
	$session['user']['hashorse'] = 0;
	unset($session['bufflist']['mount']);
	debuglog("your mount has died");
	addnews("%s mount had an accident in the forest today, it died.",$session['user']['name']);
	} else {
	output("`n`^As you walk you almost trip over a loose branch!`0");
	output("`^You move it aside, those things can be dangerous for horses.`0");
	output("`n`^You find a gem underneath it!.`0");
	$session['user']['gems']++;
	debuglog("found a gem under a branch");
	}
}
function ridingaccident_run(){
}
?>