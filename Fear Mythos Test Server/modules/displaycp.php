<?php

function displaycp_getmoduleinfo(){
	$info = array(
		"name"=>"Stat Display Control Panel",
		"author"=>"Chris Vorndran",
		"version"=>"1.22",
		"category"=>"Stat Display",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=63",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"This module will display many different things for a user, including drunkeness, specialty, charm, DKs and many other things.",
		"settings"=>array(
			"Drunkeness Bar Settings,title",
			"sober"=>"Name of True Soberness,text|Sober",
			"level1"=>"Name of Level One of Drunkeness,text|Buzzed",
			"level2"=>"Name of Level Two of Drunkeness,text|Tipsy",
			"level3"=>"Name of Level Three of Drunkeness,text|Sloshed",
			"level4"=>"Name of Level Four of Drunkeness,text|Stumbling",
			"level5"=>"Name of True Drunkeness,text|Crap-Faced",
			"Favor Display Settings,title",
			"wo"=>"Which heading does this fall under,enum,0,Vital Info,1,Personal Info,2,Extra Info|0",
			"Admin Overrides,title",
			"spec"=>"Allow users to show Specialty,bool|1",
			"charm"=>"Allow users to show Charm,bool|1",
			"dk"=>"Allow users to show Dragonkills,bool|1",
			"dsdk"=>"Allow users to see Days Since DK,bool|1",
			"gib"=>"Allow users to show Gold in Bank,bool|1",
			"mast"=>"Allow users to show Seen Master,bool|1",
			"fav"=>"Allow users to show Favor,bool|1",
			"donate"=>"Allow users to show Donation Points,bool|1",
			"drunk"=>"Allow users to show Drunkeness,bool|1",
			"pf"=>"Allow users to show PvPs,bool|1",
		),
		"prefs"=>array(
			"Stat Display Control Panel,title",
			"user_showspec"=>"Do you wish for Specialty to be displayed?,bool|1",
			"user_showcharm"=>"Do you wish for Charm to be displayed?,bool|0",
			"user_showdk"=>"Do you wish for Dragonkills to be displayed?,bool|1",
			"user_showdsdk"=>"Do you wish for Day Since DK to be displayed?,bool|1",
			"user_showgib"=>"Do you wish for Gold In Bank to be displayed?,bool|0",
			"user_showmast"=>"Do you wish to see if you have fought your master yet today?,bool|0",
			"user_sfav"=>"Do you wish to see Favor `iwhilst alive`i?,bool|0",
			"user_showpart"=>"Do you wish to see your current Donation Points?,bool|0",
			"user_showfull"=>"Do you wish to see your total Donation Points?,bool|0",
			"user_showdrunk"=>"Do you wish to see your Drunkeness?,bool|0",
			"user_showpf"=>"Do you wish to see your PvPs?,bool|0",
			"user_note"=>"Some of these may be overridden by your local Admin,note",
		),
	);
	return $info;
}
function displaycp_install(){
	module_addhook("charstats");
	return true;
}
function displaycp_uninstall(){
	return true;
}
function displaycp_dohook($hookname,$args){
	global $session;
	$specialty = modulehook("specialtynames");
	switch ($hookname){
		case "charstats":
			if (get_module_pref("user_showspec") && get_module_setting("spec")){
				$spec = $specialty[$session['user']['specialty']];
				setcharstat ("Vital Info","Specialty",$spec);
			}
			if (get_module_pref("user_showdk") && get_module_setting("dk")){
				$amnt = $session['user']['dragonkills'];
				setcharstat ("Extra Info","Dragonkills",$amnt);
			}
			if (get_module_pref("user_showdsdk") && get_module_setting("dsdk")){
				$amnt = $session['user']['age'];
				setcharstat ("Extra Info","Days Since DK",$amnt);
			}
			if (get_module_pref("user_showcharm")  && get_module_setting("charm")){
				$amnt = $session['user']['charm'];
				setcharstat ("Personal Info","Charm",$amnt);
			}
			if (get_module_pref("user_showgib") && get_module_setting("gib")){
				$amnt = $session['user']['goldinbank'];
				setcharstat ("Personal Info","Gold in Bank",$amnt);
			}
			if (get_module_pref("user_showmast") && get_module_setting("mast")){
				$amnt = translate_inline($session['user']['seenmaster'] == 0?"No":"Yes");
				setcharstat("Personal Info","Seen Master",$amnt);
			}
			if (get_module_pref("user_sfav") && ($session['user']['alive']) 
				&& get_module_setting("fav")){
				if (get_module_setting("wo") == 0) $title = "Vital Info";
				if (get_module_setting("wo")) $title = "Personal Info";
				if (get_module_setting("wo") == 2) $title = "Extra Info";
				$amnt = $session['user']['deathpower'];
				setcharstat($title,"Favor",$amnt);
			}
			if (get_module_pref("user_showpart") && get_module_setting("donate")){
				$amnt = $session['user']['donation']-$session['user']['donationspent'];
				setcharstat("Extra Info","`&Donation Points (`#Available`&)`0",$amnt);
			}
			if (get_module_pref("user_showfull") && get_module_setting("donate")){
				$amnt = $session['user']['donation'];
				setcharstat("Extra Info","`&Donation Points (`#Total`&)`0",$amnt);
			}
			if (get_module_pref('user_showdrunk') && get_module_setting("drunk")){
				$len = 0;
				$max = 100;
				$drunk = get_module_pref("drunkeness","drinks");
				if($drunk > $max) $len = $max;
				else $len = $drunk;
				$pct = round($len / $max * 100, 0);
				$nonpct = 100-$pct;
				if ($pct > 100) {
				   $pct = 100;
				   $nonpct = 0;
				} elseif ($pct < 0) {
				   $pct = 0;
				   $nonpct = 100;
				}
				if ($drunk < 5){
					$level = get_module_setting("sober");
					$barcolor = "#FF1111";
				}elseif ($drunk < 20){
					$level = get_module_setting("level1");
					$barcolor = "#D50374";
				}elseif ($drunk < 40){
					$level = get_module_setting("level2");
					$barcolor = "#4803D5";
				}elseif ($drunk < 60){
					$level = get_module_setting("level3");
					$barcolor = "#028073";
				}elseif ($drunk < 80){
					$level = get_module_setting("level4");
					$barcolor = "#016108";
				}else{
					$level = get_module_setting("level5");
					$barcolor = "#5E4604";
				}
				$barbgcol = "#777777";
				$drunk = "";
				$drunk .= "`b$level`b";
				$drunk .= "<br />";
				$drunk .= "<table style='border: solid 1px #000000' bgcolor='$barbgcol' cellpadding='0' cellspacing='0' width='70' height='5'><tr><td width='$pct%' bgcolor='$barcolor'></td><td width='$nonpct%'></td></tr></table>";
				setcharstat("Extra Info","Drunkeness",$drunk);
			}
			if (get_module_pref('user_showpf') && get_module_setting("pf")){
				setcharstat("Extra Info","Player Fights (PVPs)",$session['user']['playerfights']);
			}	
			break;
		}
	return $args;
}
function displaycp_run(){
}
?>