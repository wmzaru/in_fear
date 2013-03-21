<?php

function darkalley_getmoduleinfo(){
	$info = array(
		"name"=>"The Dark Alley",
		"author"=>"Spider",
		"version"=>"1.2",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/users/Spider/darkalley.zip",
		"settings"=>array(
			"specialchance"=>"Chance for Something Special in the Alley,range,0,100,1|15",
			"alleyloc"=>"What city is the alley in?,location|".getsetting("villagename", LOCATION_FIELDS)
		)
	);
	return $info;
}

function darkalley_install(){
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("moderate");
	return true;
}

function darkalley_uninstall(){
	return true;
}

function darkalley_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("alleyloc")) {
				set_module_setting("alleyloc", $args['new']);
			}
		}
		break;
	case "moderate":
		$args['alley'] = "Dark Alley";
		break;
	case "village":
		if ($session['user']['location'] == get_module_setting("alleyloc")){
			addnav($args["tavernnav"]);
			addnav("Dark Alley", "runmodule.php?module=darkalley");
		}
		break;
	}
	return $args;
}

function darkalley_run(){
	global $session;
	require_once("lib/villagenav.php");
	require_once("lib/commentary.php");
	require_once("lib/events.php");
	require_once("lib/http.php");
	addcommentary();
	page_header("Dark Alley");

	$skipalleydesc = handle_event("darkalley");

	$op = httpget('op');
	$com = httpget('comscroll');
	$comment = httppost('insertcommentary');
	if (!$op && $com=="" && !$comment) {
		if (module_events("darkalley", get_module_setting("specialchance", "darkalley")) != 0) {
			if (checknavs()) {
				page_footer();
			} else {
				$session['user']['specialinc'] = "";
				$session['user']['specialmisc'] = "";
				$skipalleydesc=true;
				$op = "";
				httpset("op", "");
			}
		}
	}

	addnav("Shady Houses");
	addnav("Other");
	villagenav();
	modulehook("darkalley");

	if (!$skipalleydesc) {
		output("`7`c`bDark Alley`b`c`n");
		output("`6You walk warily down the alleyway, knowing that it's only a short distance from the town centre, but at the same time realising the dangers that could lurk in the shadows.`n`n");
		output("Lining the alley is a series of houses that look somewhat unsavoury, the kind of places you wouldn't want to be seen entering, but at the same time, the kind of places that are necessary in a town.");
	}
	output("`n`n`5You hear a muttering in the shadows:`n");
	viewcommentary("alley","Conspire",25,"mutters");
	module_display_events("darkalley", "runmodule.php?module=darkalley");
	page_footer();
}

?>