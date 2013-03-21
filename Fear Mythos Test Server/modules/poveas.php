<?php
//2.0 Dog wants bones every once in a while.
//2.5 Dog runs away sometimes.
//2.51 fixed negative purchase cheat.
//3.1  DaveS Tweaks
function poveas_getmoduleinfo(){
	$info = array(
		"name"=>"Poveas's Pheasant Hunt",
		"version"=>"3.11",
		"author"=>"Reznarth, Updates by Lonny Luberts, Tweaks by DaveS",
		"category"=>"Forest",
		"download"=>"",
		"settings"=>array(
			"Poveas Settings,title",
			"limitloc"=>"Limit Poveas's Location?,enum,0,No,1,Yes - One City by Location,2,Yes - By Cityprefs|0",
			"poveasloc"=>"If Limited by Location: Where does Poveas appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"Note: Do NOT leave Poveas's only location in a city without a Forest if you want it to be used!,note",
			"huntingturns"=>"Hunting turns per day,int|5",
			"dogcost"=>"How much does dog cost?,int|250",
			"bowcost"=>"How much does bow cost?,int|50",
			"arrowcost"=>"How much does arrow cost?,int|5",
			"bonecost"=>"How much does bone cost?,int|2",
			"usehof"=>"Use the HoF?,bool|0",
		),
		"prefs"=>array(
			"Poveas User Preferences,title",
			"usedhuntingturns"=>"How many times did they hunt today?,int|0",
			"hasdog"=>"Which Dog do they own?,range,0,15,1|0",
			"hasbow"=>"Do they have a bow?,bool|0",
			"arrows"=>"How many arrows do they have?,range,0,20,1|0",
			"feedbone"=>"Needs to feed the dog a bone?,bool|0",
			"hunthof"=>"How many successful hunts has player had?,int|0",
		),
		"prefs-city"=>array(			"hunthere" => "Allow Poveas to appear here?,bool|1",		),
	);
	return $info;
}
function poveas_install(){
	module_addhook("forest");
	module_addhook("newday");
	module_addhook("footer-hof");
	module_addhook("dragonkill");
	return true;
}
function poveas_uninstall(){
	return true;
}
function poveas_dohook($hookname,$args){
	global $session;
	switch($hookname){
		case "newday":
			if ($args['resurrection'] != 'true') {
				set_module_pref("usedhuntingturns",0);
			}
		break;
		case "dragonkill":
			set_module_pref("hasdog",0);
			set_module_pref("hasbow",0);
			set_module_pref("arrows",0);
			set_module_pref("feedbone",0);
		break;
		case "forest":
			$allowed=0;
			if (is_module_active("cityprefs") && get_module_setting("limitloc")==2){
				require_once("modules/cityprefs/lib.php");
				$loc=get_cityprefs_cityid("location",$session['user']['location']);
				$allowed= get_module_objpref("city",$loc,"hunthere","poveas");
			}elseif(is_module_active("cities") && get_module_setting("limitloc")==1){
				if ($session['user']['location'] == get_module_setting("poveasloc")) $allowed=1;
			}elseif(is_module_active("cities")==0 && is_module_active("cityprefs")==0) $allowed=1;
			if ($allowed==1){
				addnav("Poveas's Pheasant Hunt","runmodule.php?module=poveas&op=enter");
			}
		break;
		case "footer-hof":
			if(get_module_setting('usehof')){
				addnav("Warrior Rankings");
				addnav("Pheasant Hunters", "runmodule.php?module=poveas&op=hof");	
			}
		break;
	}
	return $args;
}
function poveas_run(){
	include("modules/poveas/poveas.php");
}
?>