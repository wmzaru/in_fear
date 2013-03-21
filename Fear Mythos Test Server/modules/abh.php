<?php

function abh_getmoduleinfo(){
	$info = array(
		"name"=>"Abandoned House",
		"author"=>"Spider",
		"version"=>"1.0",
		"category"=>"Alley",
		"download"=>"http://dragonprime.net/users/Spider/darkalley.zip"
	);
	return $info;
}

function abh_install(){
	if (!is_module_installed("darkalley")) {
    output("This module requires the Dark Alley module to be installed.");
    return false;
	}
	module_addhook("darkalley");
	return true;
}

function abh_uninstall(){
	return true;
}

function abh_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "darkalley":
		addnav("Shady Houses");
		addnav("Abandoned House", "runmodule.php?module=abh");
		break;
	}
	return $args;
}

function abh_run(){
	global $session;
	page_header("Abandoned House");

	output("`7`c`bAbandoned House`b`c`n");
	output("You enter the derelict building to discover... nothing.  Oddly enough the place is empty having been abandoned some time ago by it's previous owner.`n`n");
	output("You explore a little, but discover nothing of interest, the previous owner must have taken everything with them when they left.");
	addnav("Return to the Alley","runmodule.php?module=darkalley");

	page_footer();
}

?>