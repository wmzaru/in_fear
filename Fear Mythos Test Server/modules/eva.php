<?php

function eva_getmoduleinfo(){
	$info = array(
		"name"=>"Eva's House",
		"author"=>"Spider",
		"version"=>"1.2",
		"category"=>"Alley",
		"download"=>"http://dragonprime.net/users/Spider/darkalley.zip"
	);
	return $info;
}

function eva_install(){
	if (!is_module_installed("darkalley")) {
    output("This module requires the Dark Alley module to be installed.");
    return false;
	}
	else {
		module_addhook("village");
		module_addhook("darkalley");
		return true;
	}
}

function eva_uninstall(){
	return true;
}

function eva_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "village":
		if ($session['user']['location'] == get_module_setting("alleyloc", "darkalley")){
			blocknav("gypsy.php");
		}
		break;
	case "darkalley":
		addnav("Shady Houses");
		addnav("Eva's House", "runmodule.php?module=eva");
		break;
	}
	return $args;
}

function eva_run(){
	global $session;
	require_once("lib/commentary.php");
	require_once("lib/http.php");
	addcommentary();
	$cost = $session['user']['level']*20;
	$op = httpget('op');
	
	if ($op=="pay"){
		if ($session['user']['gold']>=$cost){
			$session['user']['gold']-=$cost;
			debuglog("spent %s gold to speak to the dead",$cost);
			redirect("runmodule.php?module=eva&op=talk");
		}
		else{
			page_header("Eva's House");
			output("`7`c`bEva's House`b`c`n");
			output("`5You offer Eva all of your `^%s`5 gold to hold a seance, however she informs you that the dead may not require money, but she does, and refuses to talk to you further.",$session['user']['gold']);
			addnav("Return to the Alley","runmodule.php?module=darkalley");
		}
	}
	
	elseif ($op=="talk"){
		page_header("In a deep trance, you talk with the shades");
		output("`7`c`bEntranced Seance`b`c`n");
		output("Eva leads you to a small table with a chair either side, and motions you to sit.");
		output("She takes her place and begins the seance, moments later you are entranced and are able to talk to the dead.`n");
		viewcommentary("shade","Project",25,"projects");
		addnav("Snap out of your trance","runmodule.php?module=darkalley");
	}
	
	else{
		checkday();
		page_header("Eva's House");
		output("`7`c`bEva's House`b`c`n");
		output("You tap Eva's door and it creaks open, warily stepping inside you note that noone appears to have opened the door.");
		output("Eva is sitting in a comfortable looking armchair staring at you.`n`n");
		output("`3\"I've been expecting you `#%s`3, welcome.  I take it you wish to converse with the deceased, that will cost you `^%s`3 gold.\"",$session['user']['name'],$cost);
		addnav("Pay to hold a seance","runmodule.php?module=eva&op=pay");
		addnav("Return to the Alley","runmodule.php?module=darkalley");
		if ($session[user][superuser]>1) addnav("Superuser seance","runmodule.php?module=eva&op=talk");
	}
	modulehook("eva-footer");
	page_footer();
}

?>