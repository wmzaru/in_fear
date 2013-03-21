<?php

function hurl_getmoduleinfo(){
	$info = array(
		"name"=>"Hurl Player",
		"author"=>"Chris Vorndran",
		"version"=>"1.0",
		"category"=>"Travel",
		"download"=>"http://dragonprime.net/users/Sichae/hurl.zip",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"This module is a last resort for those with no means of travel.",
		"settings"=>array(
			"Hurl Player Settings,title",
				"hook"=>"Does this hook to Village or the Travel area,enum,0,Village,1,Travel Area|0",
				"If this setting is set to Travel area the next setting does not need to be set.,note",
				"chance"=>"Chance that Catapult shows up in the village?,range,0,100,1|15",
				"mount"=>"Can players with a mount use it?,bool|0",
			),
		"prefs"=>array(
			"Hurl Player Prefs,title",
				"hurled"=>"Has this player been hurled?,bool|0",
			),
		"requires"=>array(
			"cities"=>"1.0|Eric Stevens,core_download",
		),
	);
	return $info;
}
function hurl_install(){
	module_addhook("village");
	module_addhook("travel");
	module_addhook("newday");
	return true;
}
function hurl_uninstall(){
	return true;
}
function hurl_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "village":
			if (!get_module_setting("hook")){
				$chance = get_module_setting("chance");
				if (e_rand(1,100) < $chance ){
					tlschema($args['schemas']['gatenav']);
					addnav($args['gatenav']);
					tlschema();
					if (!get_module_pref("hurled")) addnav("Catapult","runmodule.php?module=hurl&op=select");
				}
			}
			break;
		case "travel":
			if (get_module_setting("hook")){
				addnav("Hitch a Ride");
				if (!get_module_pref("hurled")) addnav("Catapult","runmodule.php?module=hurl&op=select");
			}
			break;
		case "newday":
			set_module_pref("hurled",0);
			break;
		}
	return $args;
}
function hurl_run(){
	global $session;
	
	// Can I be catapulted? Let's find out!
	$travel = modulehook("count-travels", array('available'=>0,'used'=>0));
	$free = max(0, $travel['available'] - $travel['used']);
	$hurled = get_module_pref("hurled");
	$hurl = 0;
	if ((!$free || get_module_pref("ridetoday","hitch")) && !$session['user']['turns'] && !$hurled) $hurl = 1;
	// If we have no free travels, no turns, haven't been hurled or hitchhiked, then let's go a launching!
	
	$op = httpget('op');
	page_header("Catapulting");
	switch ($op){
		case "select":
			if ($session['user']['hashorse'] > 0 && !get_module_setting("mount")){
				$res = db_query("SELECT mountname FROM ".db_prefix("mounts")." WHERE mountid=".$session['user']['hashorse']."");
				$row = db_fetch_assoc($res);
				output("`#You begin to walk towards the Catapult, but your %s canters away in fear of being hurled across the realm.",$row['mountname']);
			}else{
				if ($hurl){
					output("`#Before you stands a grand Catapult.");
					output("A tiny man walks out from behind it saying, \"`QWell, it looks like ye can be tossed...");
					output("The only questions being, where to?`#\"");
					$vloc = array();
					$vloc = modulehook("validlocation", $vloc);
					ksort($vloc);
					reset($vloc);
					foreach($vloc as $loc=>$val) {
						addnav("Where to?");
						if ($session['user']['location'] != $loc)  addnav(array("Hurl to %s", $loc), "runmodule.php?module=hurl&op=hurl&village=".htmlentities($loc));
					}
					$capital = getsetting("villagename",LOCATION_FIELDS);
					if ($session['user']['location'] != $capital) addnav(array("Hurl to %s", $capital), "runmodule.php?module=hurl&op=hurl&village=".htmlentities($capital));
				}else{
					output("`#The tiny man walks out from behind the large machine.");
					output("\"`QI am sorry... but I can't be hurlin' ye today... I don't feel that ye have met my requirements...`#\"");
				}
			}
			break;
		case "hurl":
			$village = httpget('village');
			$session['user']['location'] = $village;
			output("`#You are catapulted through the air, straight towards `^%s`#.",$village);
			output("With a large crash, you land right in a tree, snapping many limbs on your way down.");
			output("You brush yourself off and venture into town.");
			set_module_pref("hurled",1);
			break;
		}
addnav("Leave");
villagenav();
page_footer();
}
?>