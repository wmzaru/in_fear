<?php
//Date:  February 21, 2006
function signetsale_getmoduleinfo(){
	$info = array(
		"name"=>"`3S`Qi`!g`\$n`3e`Qt `^Sale",
		"version"=>"5.22",
		"author"=>"DaveS, kickme fixes + Aelia HoF",
		"category"=>"Signet Series",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		"settings"=>array(
			"Signet Dungeon Maps Sales,title",
			"mapmaker"=>"If `^cartographer`0 installed use as location to buy maps?,bool|0",
			"mapsaleloc"=>"Where does the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signet`0 Shop appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"usepics"=>"Show small picture of the map to the player upon purchase?,bool|1",
			"dksincewin"=>"Number of DKs before allowing players to replay the Signet Series after completing it?,int|-1",
			"`\$Set to -1 to prevent players from being able to replay the Signet Series. Please set this appropriately high based on the difficulty of dks on your server,note",
			"usehof"=>"Use detailed HoF?,bool|1",
			"pp"=>"Number of players to show per page on the HoF?,int|25",
			"Air Signet Dungeon,title",
			"dk1"=>"`3How many `2dks`3 needed before able to purchase the Air map?,int|0",
			"costturn1"=>"`3How many `@turns`3 are required to buy the Air map?,int|16",
			"costgold1"=>"`3How much `^gold`3 is required to buy the Air map?,int|12000",
			"costgem1"=>"`3How many `%gems`3 are required to buy the Air map?,int|16",
			"Earth Signet Dungeon,title",
			"dk2"=>"`QHow many `2dks`Q needed before able to purchase the Earth map?,int|0",
			"costturn2"=>"`QHow many `@turns`Q are required to buy the Earth map?,int|15",
			"costgold2"=>"`QHow much `^gold `Qis required to buy the Earth map?,int|10000",
			"costgem2"=>"`QHow many `%gems`Q are required to buy the Earth map?,int|15",
			"Water Signet Dungeon,title",
			"dk3"=>"`!How many `2dks`! needed before able to purchase the Water map?,int|0",
			"costturn3"=>"`!How many `@turns`! are required to buy the Water map?,int|15",
			"costgold3"=>"`!How much `^gold`! is required to buy the Water map?,int|10000",
			"costgem3"=>"`!How many `%gems`! are required to buy the Water map?,int|15",
			"Fire Signet Dungeon,title",
			"dk4"=>"`\$How many `2dks`\$ needed before able to purchase the Fire map?,int|0",
			"costturn4"=>"`\$How many `@turns`\$ are required to buy the Fire map?,int|15",
			"costgold4"=>"`\$How much `^gold`\$ is required to buy the Fire map?,int|10000",
			"costgem4"=>"`\$How many `%gems`\$ are required to buy the Fire map?,int|15",
			"Final Signet Dungeon,title",
			"dk5"=>"`%How many `2dks`% needed before able to purchase the Final map?,int|0",
			"costturn5"=>"`%How many `@turns`% are required to buy the Final map?,int|15",
			"costgold5"=>"`%How much `^gold`% is required to buy the Final map?,int|10000",
			"costgem5"=>"`%How many gems are required to buy the Final map?,int|15",
		),
		"prefs"=>array(
			"Signet Dungeon Maps Sales,title",
			"super"=>"Does player have superuser access to the Signet Series?,bool|0",
			"hoftemp"=>"HoF Temporary Information,viewonly|",
			"Signet Dungeon Maps Sales Allprefs,title",
			"allprefs"=>"Preferences for Signet Sale,viewonly|",
		),
	);
	return $info;
}
function signetsale_install(){
	module_addhook("village");
	module_addhook("bioinfo");
	module_addhook("footer-hof");
	module_addhook("dragonkill");
	if (is_module_active("cartographer")) module_addhook("footer-cartographer");
	if (is_module_active("mapmaker")) module_addhook("footer-mapmaker");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	return true;
}
function signetsale_uninstall(){
	return true;
}
function signetsale_dohook($hookname,$args){
	global $session, $REQUEST_URI;
	require("modules/signet/dohooksignetsale/$hookname.php");
	return $args;
}
function signetsale_run(){
	include("modules/signet/signetsale.php");
}
?>
