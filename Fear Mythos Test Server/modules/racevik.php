<?php

function racevik_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Viking",
		"version"=>"5.01",
		"author"=>"`^Harry Balzitch, DaveS Modifications, Arieswind Fixes",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Viking Race Settings,title",
			"minedeathchance"=>"Percent chance for Vikings to die in the mine,range,0,10,1|40",
			"gemchance"=>"Percent chance for Vikings to find a gem on battle victory,range,25,100,1|5",
			"gemmessage"=>"Message to display when finding a gem|`&The Viking spies a a `%gem`&!",
			"goldloss"=>"How much less gold (in percent) do the Vikings find?,range,0,10,1|15",
			"mindk"=>"How many DKs do you need before the race is available?,int|28",
			"maxdk"=>"Maximum dks for which this race is available?,int|48",
			"cost"=>"Cost of Race in Lodge Points,int|75",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|10",
		),
		"prefs"=>array(
			"Viking Preferences,title",
			"bought"=>"Has Viking race been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the race?,int|0",
		),
		"requires"=>array(
			"racehuman"=>"1.0|By Eric Stevens,part of core download",
		),
	);
	return $info;
}

function racevik_install(){
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("raceminedeath");
	module_addhook("battle-victory");
	module_addhook("creatureencounter");
	module_addhook("pvpadjust");
	module_addhook("racenames");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("dragonkill");
	return true;
}

function racevik_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Viking'";
	db_query($sql);
	if ($session['user']['race'] == 'Viking')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racevik_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Viking";
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	switch($hookname){
	case "dragonkill":
		if ($bought==1) {
			increment_module_pref("dksince",1);
			if (get_module_pref("dksince")>=get_module_setting("dklast")){
				set_module_pref("bought",0);
				set_module_pref("dksince",0);
			}
		}
		break;
	case "racenames":
		$args[$race] = $race;
		break;
	case "pointsdesc":
		$args['count']++;
		$format = $args['format'];
		$dks=get_module_setting("dklast");
		$str = translate("The race: Viking.  The explorers.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creaturedefense']+=(2+floor($args['creaturelevel']/5));
			$args['creaturehealth']-= round($args['creaturehealth']*.03, 0);
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Fortunately your Viking strength once again lets you escape.`n";
			$args['schema']="module-racevik";
		}
		break;
	case "chooserace":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=Viking$resline'>From the shores of %s</a>, the city of men, your race of `5Vikings`0, from the north land of Scandavia, who traveled far to reach this land.  Your great strength and stature allows you to stand taller, stronger and be larger, something other races can only dream of.`n`n",$city, true);
		addnav("`5Viking`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Viking Blood (%s points)",$cost),
					"runmodule.php?module=racevik&op=start");
		}
		break;
	case "setrace":
		if ($session['user']['race']==$race){
			output("`&As a Viking, you attack quickly and without hesitation with your mighty Battle Axe.`n");
			output("You gain extra defense!`n");
			output("Your keen eye for gems unusual for your race, but your childhood serving in your parents shops taught you to spot gems quickly.`n");
			output("You gain extra gems from forest fights, but you also gain less gold!");
			if (is_module_active("cities")) {
				if ($session['user']['dragonkills']==0 &&
					$session['user']['age']==0){
					set_module_setting("newest-$city",
					$session['user']['acctid'],"cities");
				}
				set_module_pref("homecity",$city,"cities");
				if ($session['user']['age'] == 0)
				$session['user']['location']=$city;
			}
		}
		break;
	case "battle-victory":
		if ($session['user']['race'] != $race) break;
		if ($args['type'] != "forest") break;
		if ($session['user']['level'] <=15 &&
			e_rand(1,100) <= get_module_setting("gemchance")) {
			output(get_module_setting("gemmessage")."`n`0");
			$session['user']['gems']++;
			debuglog("found a gem when slaying a monster, for being a Viking.");
		}
		break;

	case "creatureencounter":
		if ($session['user']['race']==$race){
			racevik_checkcity();
			$loss = (100 - get_module_setting("goldloss"))/100;
			$args['creaturegold']=round($args['creaturegold']*$loss,0);
		}
		break;
	case "newday":
		if ($session['user']['race']==$race){
			racevik_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`5Viking Battle Axe`0",
				"defmod"=>"(<defense>?(1+((2+floor(<level>/5))/<defense>)):0)",
				"badguydmgmod"=>1.25,
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>100,
				"schema"=>"module-racevik",
				)
			);
		}
		break;
	}

	return $args;
}

function racevik_checkcity(){
	global $session;
	$race="Viking";
	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	
	if ($session['user']['race']==$race && is_module_active("cities")){
		if (get_module_pref("homecity","cities")!=$city){
			set_module_pref("homecity",$city,"cities");
		}
	}	
	return true;
}

function racevik_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'Viking';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Viking Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racevik&op=yes");
				addnav("No","runmodule.php?module=racevik&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Viking Blood`\$.");
			output("Now, drink it all up!`3\"`n`n");
			output("You double over, spasming on the ground.");
			output("J. C. Petersen grins, \"`\$Your body shall finish its change upon newday... I suggest you rest.`3\"");
			$session['user']['race'] = $race;
			$session['user']['donationspent'] += $cost;
			set_module_pref("bought",1);
			break;
		case "no":
			output("`3J. C. Petersen looks at you and shakes his head.");
			output("\"`\$I swear to you, this stuff is top notch.");
			output("This isn't like the crud that `%Cedrik `\$is selling.`3\"");
			break;
	}
	addnav("Return");
	addnav("L?Return to the Lodge","lodge.php");
	page_footer();
}
?>