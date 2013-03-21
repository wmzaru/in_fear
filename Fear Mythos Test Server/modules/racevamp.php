<?php

function racevamp_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Vampire",
		"version"=>"5.01",
		"author"=>"Chris Vorndran, DaveS Modifications, Arieswind Fixes",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Vampiric Race Settings,title",
			"favorratio"=>"Divide Favor by this to define the attack buff,int|50",
			"minedeathchance"=>"Chance for Vampires to die in the mine,range,0,100,1|90",
			"mindk"=>"How many DKs do you need before the race is available?,int|50",
			"maxdk"=>"Maximum dks for which this race is available?,int|70",
			"cost"=>"Cost of Race in Lodge Points,int|100",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|12",
		),
		"prefs"=>array(
			"Vampire Preferences,title",
			"bought"=>"Has Vampire race been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the race?,int|0",
		),
		"requires"=>array(
			"racehuman"=>"1.0|By Eric Stevens,part of core download",
		),
	);
	return $info;
}

function racevamp_install(){
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

function racevamp_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Vampire'";
	db_query($sql);
	if ($session['user']['race'] == 'Vampire')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racevamp_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("racehuman")) {
		$city = get_module_setting("villagename", "racehuman");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Vampire";
	$favorratio = get_module_setting("favorratio");
	$vamp = max(round($session['user']['deathpower']/$favorratio),3);
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
		$str = translate("The race: Vampire.  The blood suckers.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creaturedefense']++;
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Fortunately you change into a bat and you escape unscathed.`n";
			$args['schema']="module-racevampire";
		}
		break;
	case "chooserace":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=Vampire$resline'>Growing up, shrouded in darkness of `%%s</a> `&chambered away from the world. `\$Vampiric `&structures protecting you, from those that wish to destroy your kind. You are a creature of the night, wishing to feed...`n`n",$city,true);
		addnav("`\$V`4ampire`0","newday.php?setrace=Vampire$resline");
		addnav("","newday.php?setrace=Vampire$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Vampire Blood (%s points)",$cost),
					"runmodule.php?module=racevamp&op=start");
		}
		break;
	case "setrace":
		if ($session['user']['race']==$race){
			output("`^As a Vampire, you feel the powers of old, coursing in your veins.`nYou gain extra attack!");
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
	case "newday":
		if ($session['user']['race']==$race){
			racevamp_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`@Vampiric Elders`0",
				"atkmod"=>"(<attack>?(1+((1+floor($vamp))/<attack>)):0)",
				"allowintrain"=>1,
				"allowinpvp"=>1,
				"rounds"=>-1,
				)
			);
		}
		break;
	}
	return $args;
}

function racevamp_checkcity(){
	global $session;
	$race="Vampire";
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

function racevamp_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'Vampire';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Vampire Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racevamp&op=yes");
				addnav("No","runmodule.php?module=racevamp&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Vampire Blood`\$.");
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