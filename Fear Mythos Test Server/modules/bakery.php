<?php 
function bakery_getmoduleinfo(){
    $info = array(
        "name"=>"Hara's Bakery",
        "version"=>"5.2",
        "author"=>"DaveS",
        "category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=176",
        "settings"=>array(
			"Bakery,title",
            "eclair"=>"Cost for an Eclair, int|160",
            "danish"=>"Cost for a Danish, int|200",
            "croissant"=>"Cost for a Croissant, int|175",
            "tart"=>"Cost for a Tart, int|215",
            "strudel"=>"Cost for a Strudel, int|230",
            "dayold"=>"Cost for a Day Old Pastry, int|40",
            "cakecost"=>"Cost for Cake with a File, int|2000",
            "bakeryloc"=>"Where does the bakery appear,location|".getsetting("villagename", LOCATION_FIELDS),
        ),
        "prefs"=>array(
			"Bakery Allprefs,title",
			"Note: Please edit with caution. Consider using the Allprefs Editor instead.,note",
			"allprefs"=>"Preferences for Bakery,textarea|",
        )
    );
	return $info;
}
function bakery_install(){
	module_addhook("changesetting");
	module_addhook("village");
	module_addhook("newday");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	module_addhook("sheriff-jail");
	return true;
}
function bakery_uninstall(){
	return true;
}
function bakery_dohook($hookname,$args){
	global $session;
	require("modules/bakery/dohook/$hookname.php");
	return $args;
}
function bakery_run(){
	include("modules/bakery/bakery.php");
}
?>