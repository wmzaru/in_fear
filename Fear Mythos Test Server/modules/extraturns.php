<?php
// translator ready
// addnews ready
// mail ready
function extraturns_getmoduleinfo(){
	$info = array(
		"name"=>"Extra Turns",
		"author"=>"Turock",
		"version"=>"1.0",
		"category"=>"Lodge",
		"download"=>"http://dragonprime.net/users/Turock/extraturns.zip",
		"settings"=>array(
			"Extra Turn Module Settings,title",
			"cost"=>"How much does 10 extra turns cost?,int|25"
		),
		"prefs"=>array(
			"Extra Turns User Preferences,title",
			"totalexturns"=>"How many extra turns does the player have?,int|0",
		),
	);
	return $info;
}

function extraturns_install(){
	module_addhook("lodge");
	module_addhook("pointsdesc");
	module_addhook("forest");
	return true;
}
function extraturns_uninstall(){
	return true;
}

function extraturns_dohook($hookname,$args){
	global $session;
	$cost = get_module_setting("cost");
	switch($hookname){
	case "pointsdesc":
		$args['count']++;
		$format = $args['format'];
		$str = translate("10 Extra turns costs %s points.");
		$str = sprintf($str, $cost);
		output($format, $str, true);
		break;
	case "lodge":
		addnav(array("10 Extra Turns (%s points)", $cost),"runmodule.php?module=extraturns&op=buy");
		break;
	case "forest":
		$extraturns = get_module_pref("extraturns");
		if($extraturns > 0) {
			addnav("U?Use 10 Extra Turns","runmodule.php?module=extraturns&op=use");
		}
		break;
	}
	return $args;
}

function extraturns_runevent() {
}

function extraturns_run(){
	global $session;
	$op = httpget("op");
	$cost = get_module_setting("cost");
	if ($op == "use") {
		page_header("Use Extra Turns");
		output("`%You cautiously unstopper a tiny vial of foul smelling liquid and, holding your breath, tip the contents into your mouth.");
		output("What an aweful taste!`n`n`^");
		$extraturns = get_module_pref("extraturns");
		$extraturns-=10;
		set_module_pref("extraturns", $extraturns);
		debuglog("used 10 extra turns");
        $session['user']['turns']+=10;
		output("You now have %s extra turns left.", $extraturns);
		addnav("Continue","forest.php");
	} elseif ($op=="buy"){
		page_header("Hunter's Lodge");
        $extraturns = get_module_pref("extraturns");
        output("`nYou currently have %s extra turns.`n", $extraturns);
		output("`7J. C. Petersen turns to you. \"`&A Vial of Extra Turns costs %s points.`7\", he says.  \"`&Will this suit you?`7\"`n`n", $cost);
		addnav("Confirm Extra Turn Purchase");
		addnav("Yes", "runmodule.php?module=extraturns&op=buyconfirm");
		addnav("No", "lodge.php");
	}elseif ($op=="buyconfirm"){
		page_header("Hunter's Lodge");
		addnav("L?Return to the Lodge","lodge.php");
		$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
		if($pointsavailable >= $cost){
			output("`7J. C. Petersen reaches into his pocket and hands you a tiny vial. \"`&Use it in the forest when needed`7\", he says.`7");
			$extraturns = get_module_pref("extraturns");
			$extraturns+=10;
			set_module_pref("extraturns", $extraturns);
			$session['user']['donationspent'] += $cost;
		} else {
			if ($pointsavailable < $cost) {
				output("`7J. C. Petersen looks down his nose at you. \"`&I'm sorry, but you do not have the %s points required to procure a Vial of Extra Turns.  Please return when you do and I'll be happy to sell you some.`7\"", $cost);
			}
		}
	}
	page_footer();
}
?>
