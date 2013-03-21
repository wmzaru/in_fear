<?php
function desert_getmoduleinfo(){
	if (is_module_active("oasis")) {
		$city = get_module_setting("villagename", "oasis");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$info = array(
		"name"=>"The Desert",
		"version"=>"1.0",
		"author"=>"Christian Rutsch",
		"category"=>"Forest",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1241",
		"description"=>"Replaces the forest in one city with a desert. Needs translation engine enabled. Needs game version v1.11!",
		"settings"=>array(
			"The Desert - Settings,title",
			"location"=>"Where is the desert?,location|".$city,
			"expbonus"=>"Multiply exp gained by,floatrange,0.8,1.25,0.01|1.05",
			"This also affects the strength of creatures!,note",
		),
		"prefs-creatures"=>array(
			"The Desert - Creature Settings,title",
			"desertcreature"=>"This monster may appear in the desert?,bool|0"
		),
	);
	return $info;
}

function desert_install(){
	module_addhook("village");
	module_addhook("creatureencounter");
	module_addhook("header-forest");
	module_addhook("footer-forest");
	module_addhook("forest-desc");
	module_addhook("collect-events");
	if (!is_module_installed('desert')) {
		$sql_statements = array(
			"`\$You head for the section of forest you know to contain foes that you're a bit more comfortable with.`0`n"
			=> "`\$You head for the section of desert you know to contain foes that you're a bit more comfortable with.`0`n",
			"`\$You head for the section of forest which contains creatures of your nightmares, hoping to find one of them injured.`0`n"
			=> "`\$You head for the section of desert which contains creatures of your nightmares, hoping to find one of them injured.`0`n",
			"`\$You head for the section of forest which contains creatures of your nightmares, looking for the biggest and baddest ones there.`0`n"
			=> "`\$You head for the section of desert which contains creatures of your nightmares, looking for the biggest and baddest ones there.`0`n",
		);
		foreach ($sql_statements as $intext => $outtext) {
			$sql = "INSERT INTO ".db_prefix("translations")." (intext, outtext, uri, language) VALUES ('".addslashes($intext)."', '".addslashes($outtext)."', 'module-desert', 'en')";
			db_query($sql);
		}
	}
	return true;
}

function desert_uninstall(){
	return true;
}

function desert_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "creatureencounter":
			if ($session['user']['location'] == get_module_setting("location")) {
				$creatures = db_prefix("creatures");
				$module_objprefs = db_prefix("module_objprefs");
				$sql = "SELECT c.creaturename AS creaturename, c.creatureweapon AS creatureweapon
							FROM $creatures AS c
							INNER JOIN $module_objprefs AS mo ON mo.objid = c.creatureid
							WHERE mo.objtype='creatures'
								AND mo.modulename='desert'
								AND mo.setting='desertcreature'
								AND mo.value=1
								AND c.creaturelevel = {$args['creaturelevel']}
							ORDER BY rand(".e_rand().")
							LIMIT 1";
				$result = db_query($sql);
				if (db_num_rows($result)) {
					$row = db_fetch_assoc($result);
					$args['creaturename'] = $row['creaturename'];
					$args['creatureweapon'] = $row['creatureweapon'];
					$args['creaturehealth'] = round($args['creaturehealth'] * (0.1 + get_module_setting("expbonus")));
					$args['creatureattack'] = round($args['creatureattack'] * (0.1 + get_module_setting("expbonus")));
					$args['creaturedefense'] = round($args['creaturedefense'] * (0.1 + get_module_setting("expbonus")));
				} else {
					$args['creaturename'] = "`tDesert Warrior";
					$args['creatureweapon'] = "`tAncient Sword";
				}
				// X% Experience Bonus for desert fights.
				$args['creatureexp'] *= get_module_setting("expbonus");
			}
			break;
		case "collect-events":
			if ($session['user']['location'] == get_module_setting("location")) {
				foreach($args as $index => $event) {
					$event['rawchance'] = 0;
					$events[$index] = $event;
				}
				$args = $events;
			}
			break;
		case "village":
			if ($session['user']['location'] == get_module_setting("location")) {
				addnav($args['gatenav']);
				addnav("D?The Desert", "forest.php?location=wueste");
			}
			break;
		case "header-forest":
			if (httpget('location')=="wueste" || $session['user']['location'] == get_module_setting('location')) {
				if (httpget('op') == "") {
					global $block_new_output;
					$block_new_output = true;
				}
				blocknav("runmodule.php",true);
				blocknav("healer.php",true);
				tlschema("module-desert");
			}
			break;
		case "footer-forest":
			if (httpget('location')=="wueste" || $session['user']['location'] == get_module_setting('location')) {
				page_header("Die Wüste");
			}
			break;
		case "forest-desc":
			if (httpget('location')=="wueste" || $session['user']['location'] == get_module_setting('location')) {
				page_header("Die Wüste");
				global $block_new_output;
				$block_new_output = false;
				output("`c`pThe sea of lost dreams`c`n`n");
				output("`xAs far as your eyes can see, you are sorrounded by a sea of sand and dust that awaits those ready and brave enough to explore it.");
				output("Once in a while the warm wind picks up some grains of sand, whirling them around in the hot sun of the day, leading them to dance and sparkle through the air.");
				output("Just to be picked up again as they are close to touching the ground again.");
				output("The whole view is bathed is red and golden warmth which like waves invite you to lose yourself in the beauty of this amazing view and let yourself be drifted away.");
				output("But your eyes may be deceived. What lurks below the sand? What's hidden from the eyes of an unknowing, uncautious traveller?");
				$block_new_output = true;
			}
			break;
	}
	return $args;
}

function desert_run(){
}
?>