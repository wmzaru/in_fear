<?php
require_once("modules/cityprefs/lib.php");

function villagedkers_getmoduleinfo(){
	$info = array(
		"name"=>"Village Dragon Killers",
		"author"=>"<a href=\"http://www.sixf00t4.com\" target=_new>Sixf00t4</a>",
		"version"=>"20070207",
		"category"=>"Cities",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1197",
		"vertxtloc"=>"http://www.legendofsix.com/",
		"description"=>"Shows the latest dragon killer for a village in the village description",
        "prefs-city"=>array(
            "latestdker"=>"Who is the most recent DKer?,text|",
        ),
		"requires"=>array(
			"cities"=>"1.0|Eric Stevens,core_download",
            "cityprefs"=>"20051113|By Sixf00t4, available on DragonPrime",
		),
	);
	return $info;
}




function villagedkers_install(){
	module_addhook("village-desc");
	module_addhook("dragonkill");
	return true;
}
function villagedkers_uninstall(){
	return true;
}
function villagedkers_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "dragonkill":
            set_module_objpref("city",get_cityprefs_cityid("cityname",get_module_pref("homecity","cities")),"latestdker",$session['user']['name']);

		break;

		case "village-desc":
            $cityid=get_cityprefs_cityid("cityname",$session['user']['location']);            
            if(get_module_objpref("city",$cityid,"latestdker")!="") output("`nThe local villagers are busy erecting a statue to the latest dragon killer of their community, %s.",get_module_objpref("city",$cityid,"latestdker"));
		break;
		}
	return $args;
}
function villagedkers_run(){
}
?>