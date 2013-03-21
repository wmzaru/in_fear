<?php

function sweets_getmoduleinfo(){
	$info = array(
		"name"=>"Mystie's Sweet Shoppe",
		"author"=>"Chris Vorndran",
		"version"=>"1.42",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=33",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"User can purchase sweets, in order to gain boons and such.",
		"settings"=>array(
			"Mystie's Sweets Shoppe Settings,title",
				"times"=>"How many times per day,int|3",
				"menu_cost"=>"What is the cost of a sample in gold?,int|100",
			"Mystie's Sweets Shoppe - Choco,title",
				"dump"=>"Which method?,enum,0,Chocolate Dumping,1,Choco-Guns|0",
				"Chocolate Dumping is just for fun. Choco-Guns have an actual effect.,note",
				"maxdump"=>"How many times can chocolate be dumped?,int|10",
				"gun_cost"=>"What is the cost to fire the Choco-Guns in gold?,int|500",
			"Mystie's Sweets Shoppe Location,title",
				"mindk"=>"What is the minimum DK before this shop will appear to a user?,int|0",
				"sweetloc"=>"Where does Mystie appear,location|".getsetting("villagename", LOCATION_FIELDS),
			),
		"prefs"=>array(
			"Mystie's Sweets Shoppe Preferences,title",
				"stuff"=>"Everything for the Sweet Shoppe,viewonly|",
			),
		);
	return $info;
}
function sweets_install(){
	module_addhook("moderate");
	module_addhook("changesetting");
	module_addhook("village");
	module_addhook("newday");
	return true;
}
function sweets_uninstall(){
	db_query("DELETE FROM ".db_prefix("commentary")." WHERE section='sweettalk'");
	return true;
}
function sweets_dohook($hookname,$args){
	global $session;
	$stuff = unserialize(get_module_pref("stuff"));
	switch ($hookname){
		case "changesetting":
			if ($args['setting'] == "villagename") {
				if ($args['old'] == get_module_setting("sweetloc")) {
					set_module_setting("sweetloc", $args['new']);
				}
			}
			break;
		case "moderate":
			$args['sweettalk'] = "Sweets Shop";
			break;
		case "village":
			if ($session['user']['location'] == get_module_setting("sweetloc")
				&& $session['user']['dragonkills'] >= get_module_setting("mindk")) {
				tlschema($args['schemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				addnav("Mystie's Sweets Shop","runmodule.php?module=sweets&op=enter");
			}
			if ($stuff['event'] <> ""){
				$message = $stuff['event_msg'];
				output("`4`c`bSpecial Event: %s`b`c`7`n`n",$message);
				$stuff['event'] = "";
				$stuff['event_msg'] = "";
			}
			break;
		case "newday":
			$stuff['used'] = 0;
			$stuff['gun_use'] = 0;
			$stuff['dump'] = 0;
			if ($stuff['covered']){
				$choco = array(
						"name"=>"`qChocolate Sluggishness",
						"rounds"=>15,
						"wearoff"=>"`qThe chocolate melts off in the glaring sun.",
						"atkmod"=>.9,
						"roundmsg"=>"`qThe stickiness of the chocolate makes you slow in battle!",
						"schema"=>"module-sweets",
					);
				apply_buff("choco_buff",$choco);
				output("`n`qYou find yourself covered in chocolate and don't feel the energy to fight in the forest.");
				output("You lose `@1 `qturn!`n");
				$session['user']['turns']--;
				$stuff['covered'] = 0;
			}
			break;
		}
	set_module_pref('stuff',serialize($stuff));
	return $args;
}
function sweets_run(){
	global $session;
	require_once("modules/sweets/sweets_run.php");
	page_footer();
}
?>