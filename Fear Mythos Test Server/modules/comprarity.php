<?php

function comprarity_getmoduleinfo() {
	$info = array (
		"name" => "Companions Rarity",
		"version" => "1.0",
		"author" => "`%Iori`#, based on Mount Rarity by `\$Red Yates",
		"category" => "Companions",
		"download" => "",
		"settings" => array (
			"Companions Rarity settings,title",
			"showout" => "Show missing companions?,bool|0",
		),
		"prefs-companions" => array (
			"Companions Rarity Preferences,title",
			"rarity" => "Percentage chance of companion being available each day,range,1,100,1|100",
			"unavailable" => "Is companion unavailable today,bool|0",
		),
	);
	return $info;
}

function comprarity_install() {
	module_addhook("newday-runonce");
	module_addhook("alter-companion");
	return true;
}

function comprarity_uninstall() {
	return true;
}

function comprarity_dohook($hookname, $args) {
	switch ($hookname) {
	case "newday-runonce":
		$sql = "SELECT companionid FROM ".db_prefix("companions")." WHERE companionactive=1";
		$result = db_query($sql);
		while($row = db_fetch_assoc($result)) {
			$id = $row['companionid'];
			$rarity = get_module_objpref("companions",$id,"rarity");
			if (e_rand(1,100) > $rarity) {
				set_module_objpref("companions", $id, "unavailable", 1);
			} else {
				// You need to reset the availability if it's not unavailable
				// otherwise, it never becomes available again!
				set_module_objpref("companions", $id, "unavailable", 0);
			}
		}
		break;
	case "alter-companion":
		$id = $args['companionid'];
		$out = get_module_objpref("companions",$id,"unavailable");
		if ($out == 1) {
			blocknav("mercenarycamp.php?op=buy&id=$id");
			if (!get_module_setting("showout")) {
				unset($args['name']);
			} else {
				$args['name'] .= '`) - unavailable today`0';
			}
			//make sure the unable-to-buy nav and description will never show up...
			unset($args['companioncostgold']);
			unset($args['companioncostgems']);
			unset($args['description']);
		}
		break;
	}
	return $args;
}
?>