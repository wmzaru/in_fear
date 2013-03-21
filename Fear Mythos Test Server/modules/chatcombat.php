<?php

function chatcombat_getmoduleinfo(){
	$info = array(
		"name"=>"Chat Combat",
		"author"=>"Maynard Price, modeled after Fighting Zone Chatroom by Oliver Brendel",
		"version"=>"1.0",
		"category"=>"Village",
		"download"=>"",
	);
	return $info;
}

function chatcombat_install(){
	module_addhook("village");
	return true;
}

function chatcombat_uninstall(){
	return true;
}

function chatcombat_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "village":
			tlschema($args['schemas']['gatenav']);
			addnav($args['gatenav']);
			tlschema();
			addnav("Chat Combat","runmodule.php?module=chatcombat&op=main");
		break;
	}
	return $args;
}

function chatcombat_run(){
	global $session;
	$op = httpget('op');
	villagenav();
	addnav("Battle Arenas");
	addnav("Main Arena","runmodule.php?module=chatcombat&op=main");
	addnav("Space Arena","runmodule.php?module=chatcombat&op=space");
	addnav("Alternate Dimension","runmodule.php?module=chatcombat&op=altdim");
	if ($op =="main"){
		require_once("lib/commentary.php");
		require_once("lib/villagenav.php");
		page_header("Main Arena");
		output("`b`i`c`\$Main Arena`c`i`b`n");
		output("You arrive in the middle of a great arena. Your surroundings largly resemble the Colloseum.");
		output("You notice a large sign in the middle of the arena. It Says:");
		output("Here you may participate in all manner of combat! Anything goes, as long as it can be typed. Please keep it clean.");
		addcommentary();
		commentdisplay("`n`n`@Nearbye people yell:`n","chatcombat1","Yell",20,"yells");
	page_footer();
	}
	elseif ($op =="space"){
		require_once("lib/commentary.php");
		require_once("lib/villagenav.php");
		page_header("Space Arena");
		output("`b`i`c`\$Space Arena`c`i`b`n");
		output("You arrive in the Space Arena. Your surroundings remind you of old sci-fi movies.");
		output("You notice a large sign in the middle of the arena. It Says:");
		output("Here you may participate in all manner of combat! Anything goes, as long as it can be typed. Please keep it clean.");
		addcommentary();
		commentdisplay("`n`n`@Nearbye people yell:`n","chatcombat2","Yell",20,"yells");
	page_footer();
	}
	elseif ($op =="altdim"){
		require_once("lib/commentary.php");
		require_once("lib/villagenav.php");
		page_header("Alternate Dimension");
		output("`b`i`c`\$Alternate Dimension`c`i`b`n");
		output("You step through a rift in the space-time continuum and arrive in an alternate dimension.");
		output("You notice a large sign in the middle of the arena which dominates your surroundings. It Says:");
		output("Here you may participate in all manner of combat! Anything goes, as long as it can be typed. Please keep it clean.");
		addcommentary();
		commentdisplay("`n`n`@Nearbye people yell:`n","chatcombat3","Yell",20,"yells");
	page_footer();
	}
}