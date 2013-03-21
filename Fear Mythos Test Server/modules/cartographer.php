<?
function cartographer_getmoduleinfo(){
	$info = array(
		"name"=>"Cartographer",
		"version"=>"5.22",
		"author"=>"sixf00t4, modularized by DaveS",
		"category"=>"Maps",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1134",
		"settings"=>array( 
		"cartographer Settings,title",
			"storename"=>"What is the name of the store?,text|Cartographer",
			"cartographername"=>"What is the name of the Cartographer?,text|Douglas",
			"cartographerloc"=>"Where does the cartographer appear?,location|".getsetting("villagename", LOCATION_FIELDS),	
			"listloc"=>"List location of purchased maps in the bio?,bool|0",
			"Caution: You MUST know the locations for the stores. If you don't know this you MUST keep this as No.,note",
			"Also: Unnecessary if you are NOT using the cities module,note",
			"1-10,title",
			"Note: The module must be in the village and must have the nav link to nothing or op=enter or op=shop,note",
			"modulename1"=>"`!1: What is the php modulename of this module?,text|abandoncastle",
			"name1"=>"`!1: What is the name of the module?,text|Abandoned Castle",
			"loc1"=>"`!1: What is the name of the setting for the location:,text|",
			"gemcost1"=>"`!1: Cost in gems for map,int|2",
			"goldcost1"=>"`!1: Cost in gold for map?,int|3000",
			"dksneeded1"=>"`!1: Number of DKs needed to buy a map?,int|0",
			"dkclosed1"=>"`!1: Close this location after x number of Dks:,int|1000",
			"modulename2"=>"`@2: What is the php modulename of this module?,text|lonnycastle",
			"name2"=>"`@2: What is the name of the module?,text|Lonny's Castle",
			"loc2"=>"`@2: What is the name of the setting for the location:,text|",
			"gemcost2"=>"`@2: Cost in gems for map,int|2",
			"goldcost2"=>"`@2: Cost in gold for map?,int|3000",
			"dksneeded2"=>"`@2: Number of DKs needed to buy a map?,int|0",
			"dkclosed2"=>"`@2: Close this location after x number of Dks:,int|1000",
			"modulename3"=>"`#3: What is the php modulename of this module?,text|hiklit",
			"name3"=>"`#3: What is the name of the module?,text|Hikaru's Literature",
			"loc3"=>"`#3: What is the name of the setting for the location:,text|",
			"gemcost3"=>"`#3: Cost in gems for map,int|2",
			"goldcost3"=>"`#3: Cost in gold for map?,int|3000",
			"dksneeded3"=>"`#3: Number of DKs needed to buy a map?,int|0",
			"dkclosed3"=>"`#3: Close this location after x number of Dks:,int|1000",
			"modulename4"=>"`\$4: What is the php modulename of this module?,text|battlearena",
			"name4"=>"`\$4: What is the name of the module?,text|Battle Arena",
			"loc4"=>"`\$4: What is the name of the setting for the location:,text|",
			"gemcost4"=>"`\$4: Cost in gems for map,int|2",
			"goldcost4"=>"`\$4: Cost in gold for map?,int|3000",
			"dksneeded4"=>"`\$4: Number of DKs needed to buy a map?,int|0",
			"dkclosed4"=>"`\$4: Close this location after x number of Dks:,int|1000",
			"modulename5"=>"`%5: What is the php modulename of this module?,text|musicshop",
			"name5"=>"`%5: What is the name of the module?,text|The Music Shop",
			"loc5"=>"`%5: What is the name of the setting for the location:,text|",
			"gemcost5"=>"`%5: Cost in gems for map,int|2",
			"goldcost5"=>"`%5: Cost in gold for map?,int|3000",
			"dksneeded5"=>"`%5: Number of DKs needed to buy a map?,int|0",
			"dkclosed5"=>"`%5: Close this location after x number of Dks:,int|1000",
			"modulename6"=>"`^6: What is the php modulename of this module?,text|orchard",
			"name6"=>"`^6: What is the name of the module?,text|The Fruit Orchard",
			"loc6"=>"`^6: What is the name of the setting for the location:,text|",
			"gemcost6"=>"`^6: Cost in gems for map,int|2",
			"goldcost6"=>"`^6: Cost in gold for map?,int|3000",
			"dksneeded6"=>"`^6: Number of DKs needed to buy a map?,int|0",
			"dkclosed6"=>"`^6: Close this location after x number of Dks:,int|1000",
			"modulename7"=>"`&7: What is the php modulename of this module?,text|sweets",
			"name7"=>"`&7: What is the name of the module?,text|Mystie's Sweet Shop",
			"loc7"=>"`&7: What is the name of the setting for the location:,text|",
			"gemcost7"=>"`&7: Cost in gems for map,int|2",
			"goldcost7"=>"`&7: Cost in gold for map?,int|3000",
			"dksneeded7"=>"`&7: Number of DKs needed to buy a map?,int|0",
			"dkclosed7"=>"`&7: Close this location after x number of Dks:,int|1000",
			"modulename8"=>"`Q8: What is the php modulename of this module?,text|muad",
			"name8"=>"`Q8: What is the name of the module?,text|Muad's Smoothie Shop",
			"loc8"=>"`Q8: What is the name of the setting for the location:,text|",
			"gemcost8"=>"`Q8: Cost in gems for map,int|2",
			"goldcost8"=>"`Q8: Cost in gold for map?,int|3000",
			"dksneeded8"=>"`Q8: Number of DKs needed to buy a map?,int|0",
			"dkclosed8"=>"`Q8: Close this location after x number of Dks:,int|1000",
			"modulename9"=>"`)9: What is the php modulename of this module?,text|applebob",
			"name9"=>"`)9: What is the name of the module?,text|Apple Bobbing",
			"loc9"=>"`)9: What is the name of the setting for the location:,text|",
			"gemcost9"=>"`)9: Cost in gems for map,int|2",
			"goldcost9"=>"`)9: Cost in gold for map?,int|3000",
			"dksneeded9"=>"`)9: Number of DKs needed to buy a map?,int|0",
			"dkclosed9"=>"`)9: Close this location after x number of Dks:,int|1000",
			"modulename10"=>"10: What is the php modulename of this module?,text|icecastle",
			"name10"=>"10: What is the name of the module?,text|The Ice Castle",
			"loc10"=>"10: What is the name of the setting for the location:,text|",
			"gemcost10"=>"10: Cost in gems for map,int|2",
			"goldcost10"=>"10: Cost in gold for map?,int|3000",
			"dksneeded10"=>"10: Number of DKs needed to buy a map?,int|0",
			"dkclosed10"=>"10: Close this location after x number of Dks:,int|1000",
			"11-20,title",
			"Note: The module must be in the village and  must have the nav link to nothing or op=enter or op=shop,note",
			"modulename11"=>"`!11: What is the php modulename of this module?,text|",
			"name11"=>"`!11: What is the name of the module?,text|",
			"loc11"=>"!11: What is the name of the setting for the location:,text|",
			"gemcost11"=>"`!11: Cost in gems for map,int|2",
			"goldcost11"=>"`!11: Cost in gold for map?,int|3000",
			"dksneeded11"=>"`!11: Number of DKs needed to buy a map?,int|0",
			"dkclosed11"=>"`!11: Close this location after x number of Dks:,int|1000",
			"modulename12"=>"`@12: What is the php modulename of this module?,text|",
			"name12"=>"`@12: What is the name of the module?,text|",
			"loc12"=>"`@12: What is the name of the setting for the location:,text|",
			"gemcost12"=>"`@12: Cost in gems for map,int|12",
			"goldcost12"=>"`@12: Cost in gold for map?,int|3000",
			"dksneeded12"=>"`@12: Number of DKs needed to buy a map?,int|0",
			"dkclosed12"=>"`@12: Close this location after x number of Dks:,int|1000",
			"modulename13"=>"`#13: What is the php modulename of this module?,text|",
			"name13"=>"`#13: What is the name of the module?,text|",
			"loc13"=>"`#13: What is the name of the setting for the location:,text|",
			"gemcost13"=>"`#13: Cost in gems for map,int|2",
			"goldcost13"=>"`#13: Cost in gold for map?,int|13000",
			"dksneeded13"=>"`#13: Number of DKs needed to buy a map?,int|0",
			"dkclosed13"=>"`#13: Close this location after x number of Dks:,int|1000",
			"modulename14"=>"`\$14: What is the php modulename of this module?,text|",
			"name14"=>"`\$14: What is the name of the module?,text|",
			"loc14"=>"`\$14: What is the name of the setting for the location:,text|",
			"gemcost14"=>"`\$14: Cost in gems for map,int|2",
			"goldcost14"=>"`\$14: Cost in gold for map?,int|3000",
			"dksneeded14"=>"`\$14: Number of DKs needed to buy a map?,int|0",
			"dkclosed14"=>"`\$14: Close this location after x number of Dks:,int|1000",
			"modulename15"=>"`%15: What is the php modulename of this module?,text|",
			"name15"=>"`%15: What is the name of the module?,text|",
			"loc15"=>"`%15: What is the name of the setting for the location:,text|",
			"gemcost15"=>"`%15: Cost in gems for map,int|2",
			"goldcost15"=>"`%15: Cost in gold for map?,int|3000",
			"dksneeded15"=>"`%15: Number of DKs needed to buy a map?,int|0",
			"dkclosed15"=>"`%15: Close this location after x number of Dks:,int|1000",
			"modulename16"=>"`^16: What is the php modulename of this module?,text|",
			"name16"=>"`^16: What is the name of the module?,text|",
			"loc16"=>"`^16: What is the name of the setting for the location:,text|",
			"gemcost16"=>"`^16: Cost in gems for map,int|2",
			"goldcost16"=>"`^16: Cost in gold for map?,int|3000",
			"dksneeded16"=>"`^16: Number of DKs needed to buy a map?,int|0",
			"dkclosed16"=>"`^16: Close this location after x number of Dks:,int|1000",
			"modulename17"=>"`&17: What is the php modulename of this module?,text|",
			"name17"=>"`&17: What is the name of the module?,text|",
			"loc17"=>"`&17: What is the name of the setting for the location:,text|",
			"gemcost17"=>"`&17: Cost in gems for map,int|2",
			"goldcost17"=>"`&17: Cost in gold for map?,int|3000",
			"dksneeded17"=>"`&17: Number of DKs needed to buy a map?,int|0",
			"dkclosed17"=>"`&17: Close this location after x number of Dks:,int|1000",
			"modulename18"=>"`Q18: What is the php modulename of this module?,text|",
			"name18"=>"`Q18: What is the name of the module?,text|",
			"loc18"=>"`Q18: What is the name of the setting for the location:,text|",
			"gemcost18"=>"`Q18: Cost in gems for map,int|2",
			"goldcost18"=>"`Q18: Cost in gold for map?,int|3000",
			"dksneeded18"=>"`Q18: Number of DKs needed to buy a map?,int|0",
			"dkclosed18"=>"`Q18: Close this location after x number of Dks:,int|1000",
			"modulename19"=>"`)19: What is the php modulename of this module?,text|",
			"name19"=>"`)19: What is the name of the module?,text|",
			"loc19"=>"`)19: What is the name of the setting for the location:,text|",
			"gemcost19"=>"`)19: Cost in gems for map,int|2",
			"goldcost19"=>"`)19: Cost in gold for map?,int|3000",
			"dksneeded19"=>"`)19: Number of DKs needed to buy a map?,int|0",
			"dkclosed19"=>"`)19: Close this location after x number of Dks:,int|1000",
			"modulename20"=>"20: What is the php modulename of this module?,text|",
			"name20"=>"20: What is the name of the module?,text|",
			"loc20"=>"20: What is the name of the setting for the location:,text|",
			"gemcost20"=>"20: Cost in gems for map,int|2",
			"goldcost20"=>"20: Cost in gold for map?,int|3000",
			"dksneeded20"=>"20: Number of DKs needed to buy a map?,int|0",
			"dkclosed20"=>"20: Close this location after x number of Dks:,int|2000",
		),
		"prefs"=>array(
			"Map Preferences,title",
			"super"=>"Allow this user automatic access to all locations?,bool|0",
			"Note: Please edit with caution. Consider using the Allprefs Editor instead.,note",
			"allprefs"=>"Preferences for Cartographer,textarea|",
		),
	);
	return $info;
}

function cartographer_install(){
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	module_addhook("bioinfo");
	return true;
}

function cartographer_uninstall(){
	return true;
}

function cartographer_dohook($hookname,$args){
	global $session, $REQUEST_URI;
	require("modules/cartographer/dohook/$hookname.php");
	return $args;
}
function cartographer_run(){
	include("modules/cartographer/cartographer.php");
}
?>