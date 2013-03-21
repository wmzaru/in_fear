<?php

// Activity Core
// Modeled after the Alignment Core by Chris Vorndran

function activity_getmoduleinfo(){
	$info = array(
		"name"=>"Activity Core",
		"author"=>"Daniel Kalchev",
		"version"=>"1.00",
		"category"=>"General",
		"download"=>"",
		"vertxtloc">"",
		"description"=>"This module will record the player's available activity turns.",
		"settings"=>array(
			"daily"=>"How many activity turns per day?,int|20",
			"systemnewday"=>"Give activity turns on system new day only?,bool|1",
		),
		"prefs-mounts"=>array(
			"Mount Activity Settings,title",
			"Please note that this change happens at newday.,note",
			"ac"=>"How much does having this mount affect a person's activity?,int|0",
			"0 This value to disable. You may also set negative numbers.,note",
		),
		"prefs"=>array(
		    "Activity user preferences,title",
			"activity"=>"Current activity number,int|0",
		),
	);
	return $info;
}

function activity_install(){
	module_addhook("newday");
	module_addhook("newday-runonce");
	return true;
}

function activity_uninstall(){
	return true;
}

function activity_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "newday":
			if (!get_module_setting("systemnewday"))
				set_module_pref("activity",get_module_setting("daily"));
			$id = $session['user']['hashorse'];
			if ($id){
				$ac = get_module_objpref("mounts",$id,"ac");
				if ($ac != ""){
					increment_module_pref("activity",$ac);
				}
			}
			break;
		case "newday-runonce":
			if (get_module_setting("systemnewday"))
				set_module_pref("activity",get_module_setting("daily"));
			break;
	}
	return $args;
}
?>
