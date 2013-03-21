<?php

function racebarb_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Barbarian",
		"version"=>"5.01",
		"author"=>"Chris Vorndran, DaveS Modifications, Arieswind Fixes",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Barbarian Race Settings,title",
			"minedeathchance"=>"Chance for Barbarian to die in the mine,range,0,100,1|25",
			"exp"=>"How much extra exp (%) does a Barbarian get?,range,100,200,1|110",
			"gold"=>"How much less gold (%) does a Barbarian get?,range,1,100,1|50",
			"mindk"=>"How many dks do you need before the race is available?,int|16",
			"maxdk"=>"Maximum dks for which this race is available?,int|36",
			"cost"=>"Cost of Race in Lodge Points,int|50",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|8",
		),
		"prefs"=>array(
			"Barbarian Preferences,title",
			"bought"=>"Has Barbarian race been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the race?,int|0",
		),
		"requires"=>array(
			"racehuman"=>"1.0|By Eric Stevens,part of core download",
		),
	);
	return $info;
}

function racebarb_install(){
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("raceminedeath");
	module_addhook("creatureencounter");
	module_addhook("racenames");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("dragonkill");
	return true;
}

function racebarb_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Barbarian'";
	db_query($sql);
	if ($session['user']['race'] == 'Barbarian')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racebarb_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$exp = get_module_setting("exp")/100;
	$gold = get_module_setting("gold")/100;
	$race = "Barbarian";
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
		$str = translate("The race: Barbarian.  Primitive and powerful Humans.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Fortunately your Barbarian skill let you escape unscathed.`n";
			$args['schema']="module-racebarb";
		}
		break;
	case "chooserace":
		if ($session['user']['sex']==SEX_FEMALE)
			break;
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=Barbarian$resline'>High in the mountainous lands around %s</a>, home to the brute and savage `#Barbarian`0 people. The immense race of Barbarians, living in the coldest of regions.`n`n",$city,true);
		addnav("`qBarbarian`0","newday.php?setrace=Barbarian$resline");
		addnav("","newday.php?setrace=Barbarian$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Barbarian Blood (%s points)",$cost),
					"runmodule.php?module=racebarb&op=start");
		}
		break;
	case "setrace":
		if ($session['user']['race']==$race){
			output("`#As a barbarian, you are more knowledgeable at battle.`n`^You gain extra experience from forest fights!");
			if (is_module_active("cities")) {
				if ($session['user']['dragonkills']==0 &&
						$session['user']['age']==0){
					//new farmthing, set them to wandering around this city.
					set_module_setting("newest-$city",
							$session['user']['acctid'],"cities");
				}
				set_module_pref("homecity",$city,"cities");
				$session['user']['location']=$city;
			}
		}
		break;
	case "creatureencounter":
		if ($session['user']['race']==$race){
			//get those folks who haven't manually chosen a race
			racebarb_checkcity();
			$args['creaturegold']=round($args['creaturegold']*$gold,0);
			$args['creatureexp']=round($args['creatureexp']*$exp,0);
		}
		break;
	}
	return $args;
}

function racebarb_checkcity(){
	global $session;
	$race="Barbarian";
	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	
	if ($session['user']['race']==$race && is_module_active("cities")){
		//if they're this race and their home city isn't right, set it up.
		if (get_module_pref("homecity","cities")!=$city){ //home city is wrong
			set_module_pref("homecity",$city,"cities");
		}
	}
	return true;
}

function racebarb_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'Barbarian';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Barbarian Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racebarb&op=yes");
				addnav("No","runmodule.php?module=racebarb&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Barbarian Blood`\$.");
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