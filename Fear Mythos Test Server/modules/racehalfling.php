<?php

function racehalfling_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Halfling",
		"version"=>"5.0",
		"author"=>"Sneakabout, DaveS Modifications, Arieswind Fixes",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Halfling Race Settings,title",
			"minedeathchance"=>"Percent chance for Halflings to die in the mine,range,0,100,1|80",
			"mindk"=>"How many DKs do you need before the race is available?,int|7",
			"maxdk"=>"Maximum dks for which this race is available?,int|27",
			"cost"=>"Cost of Race in Lodge Points,int|30",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|6",
		),
		"prefs"=>array(
			"Halfling Preferences,title",
			"bought"=>"Has Halfling race been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the race?,int|0",
		),
		"requires"=>array(
			"racehuman"=>"1.0|By Eric Stevens,part of core download",
		),
	);
	return $info;
}

function racehalfling_install(){
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("raceminedeath");
	module_addhook("creatureencounter");
	module_addhook("pvpadjust");
	module_addhook("racenames");	
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("dragonkill");
	return true;
}

function racehalfling_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Halfling'";
	db_query($sql);
	if ($session['user']['race'] == 'Halfling')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racehalfling_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Halfling";
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
		$str = translate("The race: Halflings.  Shire Folk.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creaturedefense']+=(1+floor($args['creaturelevel']/5));
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Though you are showered with dust, you nip out through your great speed.`n";
			$args['schema']="module-racehalfling";
		}
		break;
	case "creatureencounter":
		if ($session['user']['race']==$race){
			//get those folks who haven't manually chosen a race
			racehalfling_checkcity();
			$args['creaturegold']=round($args['creaturegold']*1.3,0);
		}
		break;
	case "chooserace":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=Halfling$resline'>In the cavern of %s</a>, many do not spot the sneaky Halflings who nip inbetween the busy dwarves. Smaller than even their dwarven cousins, this folk take pride in being faster, less aggressive and even better at finding gold than their close relatives.`n`n",$city, true);
		addnav("`#Halfling`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Halfling Blood (%s points)",$cost),
					"runmodule.php?module=racehalfling&op=start");
		}
		break;
	case "setrace":
		if ($session['user']['race']==$race){
			output("`@You shoulder your pack and start out to conquer the world, secure in the knowledge that your size makes you hard to hit. However, you'd prefer to do it without killing anyone.`n");
			output("Though small and less hardy, your sharp eyes catch even more gold than a dwarf's!`n");
			if ($session['user']['maxhitpoints']>200) {
				$session['user']['maxhitpoints']-=20;
			} elseif ($session['user']['maxhitpoints']>150) {
				$session['user']['maxhitpoints']-=15;
			} elseif ($session['user']['maxhitpoints']>100) {
				$session['user']['maxhitpoints']-=10;
			}
			if (is_module_active("cities")) {
				set_module_pref("homecity",$city,"cities");
				$session['user']['location']=$city;
			}
		}
		break;
	case "newday":
		if ($session['user']['race']==$race){
			racehalfling_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`#Halfling's Size`0",
				"defmod"=>"(<defense>?(1+((1+floor(<level>/5))/<defense>)):0)",
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-racehalfling",
				)
			);
			if ($session['user']['spirits']!=-6) {
				output("You remember that you don't like attacking people.....");
				$session['user']['playerfights']--;
			}
		}
		break;
	}

	return $args;
}

function racehalfling_checkcity(){
	global $session;
	$race="Halfling";
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

function racehalfling_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'Halfling';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Halfling Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racehalfling&op=yes");
				addnav("No","runmodule.php?module=racehalfling&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Halfling Blood`\$.");
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