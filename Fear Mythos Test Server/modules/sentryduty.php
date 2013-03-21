<?php
# Sentry Duty
# by robert of maddrio dot com
# 24Nov2005
# Last update: v1.4 19Mar09
# update: v1.3 18Mar07

require_once("lib/villagenav.php");
require_once("lib/http.php");

function sentryduty_getmoduleinfo(){
	$info = array(
	"name"=>"Sentry Duty",
	"version"=>"1.4",
	"author"=>"`2Robert",
	"category"=>"Village Specials",
	"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	"settings"=>array(
		"Sentry Duty - Settings,title",
		"turns"=>"Turns lost doing duty?,range,2,10,1|2",
		"vnamef"=>"Name of female, player waves hello to?,|Violet",
		"vnamem"=>"Name of male, player waves hello to?,|Seth",
	),
		"prefs"=>array(
		"Sentry Duty - player prefs,title",
		"didduty"=>"Did player do Sentry Duty this rank?,bool|0",
		"totalduty"=>"Total times player did Sentry Duty.,int|0",
		)
	);
	return $info;
}

function sentryduty_install(){
	if (!is_module_active('sentryduty')){
		output("`^ Installing Sentry Duty - village event `n`0");
	}else{
		output("`^ Up Dating Sentry Duty - village event `n`0");
	}
	module_addhook("newday");
	module_addhook("dragonkill");
	module_addeventhook("village","return 50;");
	return true;
}

function sentryduty_uninstall(){
	output("`^ Un-Installing Sentry Duty - village event `n`0");
	return true;
}

function sentryduty_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "newday":
	if (get_module_pref("didduty")==1){
		output("`n`n`2 Having done your Sentry Duty - you feel relieved! `n");
	}else{
		output("`n`n`2 You have not yet been called upon for Sentry Duty, could be any day now. `n");
	}
	break;
	case "dragonkill":
		set_module_pref("didduty",0);
	break;
	}
	return $args;
}

function sentryduty_runevent($type){
	global $session;
	$from = "village.php?";
	$session['user']['specialinc'] = "module:sentryduty";
	$vnamef = get_module_setting("vnamef");
	$vnamem = get_module_setting("vnamem");
	$turns=get_module_setting("turns");
	$didduty = get_module_pref("didduty");
	$op = httpget('op');
	if (is_module_active('alignment')) {
	$evil = get_module_setting('evilalign','alignment');
	$good = get_module_setting('goodalign','alignment');
	$alignment = get_module_pref('alignment','alignment');
	}
	if ($op == "") {
		// Check to see if they ever did sentry duty this rank (once per dk)
		if (($session['user']['turns']>=3) and ($didduty == 0)){
			output("`n`n`2 The Mayor approaches you and says that the `&Village Idiot `2 was supposed to be on Sentry duty today but he is ailing with a terrible flu. `n`n As a favor, he says for you to report to the Guard Tower at once! ");
			addnav(" Sentry Duty ");
			addnav("Report for duty",$from."op=duty");
		}else{
			switch(e_rand(1,4)){
				case 1: output("`n`n`2 The Mayor approaches you and says that the `& Village Idiot `2 was supposed to be on Sentry Duty today. `n`n He says the fool is avoiding his turn in the tower ...but he can not hide for long! "); break;
				case 2: output("`n`n`2 You see the Mayor dragging the `& Village Idiot `2 towards the Guard Tower. `n`n You and other villagers chuckle at this scene. "); break;
				case 3: output("`n`n`2 The Mayor has drafted a local farmer for Sentry Duty today. `n`n He looks silly, up there in the tower holding a pitchfork."); break;
				case 4: output("`n`n`2  You notice that the Guard tower is well manned today as you wander the village. "); break;
			}
			addnav("Continue On",$from."op=no");
		}
	}elseif($op=="duty"){
		$today ++ ;
		set_module_pref("didduty",$today);
		set_module_pref("totalduty",$today);
		output("`n`n`2 Reporting to the Guard Tower as ordered to do so, ");
		output("`n`n Along the way, ");
			switch(e_rand(1,6)){
			case 1: case 4:
			if ($alignment > $good){
				output(" you see %s and give him a cheery smile. ", $vnamem);
			}elseif ($alignment < $evil){
				output(" you see %s and give him a rude gesture. ", $vnamem);
			}else{
				output(" you see %s and wave hello to him. ", $vnamem);
			}
			break;
			case 2: case 5:
			if ($alignment > $good){
				output(" you see %s and give her a cheery smile. ", $vnamef);
			}elseif ($alignment < $evil){
				output(" you see that bitch %s and give her the finger. ", $vnamef);
			}else{
				output(" you see %s and wave hello to her. ", $vnamef);
			}
			break;
			case 3: case 6:
			output(" you curse the Village Idiot under your breath! ");
			break;
			}
		output("`n`n`& Doing your duty really is boring sometimes, this is one of those times. ");
		output("`n`n You spend several hours standing in the Guard Tower. ");
		if ($alignment > $good){
			output("`n`n`@ You vigorously watch for bandits and thieves. ");
		}
		if ($alignment < $evil){
			output("`n`n`\$ You turn your back as your evil friends create havoc in the village!  ");
		}
		output("`n`n`7`b Sentry Duty cost you %s TURNS! `b",$turns);
		addnews("`0 %s `# is called upon for Sentry Duty `0",$session['user']['name']);
		addnav("Leave the Tower",$from."op=no");
		$session['user']['turns']-=$turns;
	}
	if ($op == "no") {
		villagenav();
		$session['user']['specialinc'] = "";
		output("`n`n Glad to be free from Sentry Duty - you continue on `n`n");
	}
}
function sentryduty_run(){
}
?>