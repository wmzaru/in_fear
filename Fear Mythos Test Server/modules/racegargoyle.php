<?php

function racegargoyle_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Gargoyle",
		"version"=>"5.01",
		"author"=>"Sneakabout, DaveS Modifications, Arieswind Fixes",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Gargoyle Race Settings,title",
			"minedeathchance"=>"Percent chance for Gargoyles to die in the mine,range,0,100,1|90",
			"mindk"=>"How many DKs do you need before the race is available?,int|60",
			"maxdk"=>"Maximum dks for which this race is available?,int|80",
			"cost"=>"Cost of Race in Lodge Points,int|125",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|14",
		),
		"prefs"=>array(
			"Giant Preferences,title",
			"bought"=>"Has Giant race been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the race?,int|0",
		),
		"requires"=>array(
			"racedwarf"=>"1.0|By Eric Stevens,part of core download",
		),
	);
	return $info;
}

function racegargoyle_install(){
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("raceminedeath");
	module_addhook("pvpadjust");
	module_addhook("racenames");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("dragonkill");
	return true;
}

function racegargoyle_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Gargoyle'";
	db_query($sql);
	if ($session['user']['race'] == 'Gargoyle')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racegargoyle_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("racedwarf")) {
		$city = get_module_setting("villagename", "racedwarf");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Gargoyle";
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
		$str = translate("The race: Gargoyle.  Guardians of the Home.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creaturedefense']+=(1.25+floor($args['creaturelevel']/5));
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "The rocks bounce off your tough hide and you escape unharmed.`n";
			$args['schema']="module-racegargoyle";
		}
		break;
	case "chooserace":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=Gargoyle$resline'>In the lowest caverns of %s</a>, there is a place where even the bravest dwarfs fear to tread, for it is home to many fearsome creatures. One of these are the Gargoyles -  slow, blood-lusting beasts, with hides strong enough to survive the hostile environment.`n`n",$city, true);
		addnav("`7Gargoyle`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Gargoyle Blood (%s points)",$cost),
					"runmodule.php?module=racegargoyle&op=start");
		}
		break;	
	case "setrace":
		if ($session['user']['race']==$race){
			output("`7As a Gargoyle, you have much stronger defenses and a ferocious bloodlust.`n");
			output("Your tough hide slows you down when you move!`n");
			if (is_module_active("cities")) {
				set_module_pref("homecity",$city,"cities");
				$session['user']['location']=$city;
			}
		}
		break;
	case "newday":
		if ($session['user']['race']==$race){
			racegargoyle_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`7Gargoyle's Hide`0",
				"defmod"=>"(<defense>?(1.25+((1+floor(<level>/5))/<defense>)):0)",
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-racegargoyle",
				)
			);
			$turnloss=round(($session['user']['turns']*0.25),0);
			output("`n`7You feel the need to kill another! You gain a PvP fight!`nYour tough hide makes it more difficult to move! You lose %s Forest Fights!",$turnloss);
			$session['user']['turns']-=$turnloss;
			if ($session['user']['spirite']!=-6) $session['user']['playerfights']++;
		}
		break;
	}
	return $args;
}

function racegargoyle_checkcity(){
	global $session;
	$race="Gargoyle";
	if (is_module_active("racedwarf")) {
		$city = get_module_setting("villagename", "racedwarf");
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

function racegargoyle_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'Gargoyle';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Gargoyle Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racegargoyle&op=yes");
				addnav("No","runmodule.php?module=racegargoyle&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Gargoyle Blood`\$.");
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