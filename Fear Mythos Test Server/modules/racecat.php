<?php

function racecat_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Felyne",
		"version"=>"5.0",
		"author"=>"Shannon Brown, DaveS Modifications",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Felyne Race Settings,title",
			"minedeathchance"=>"Percent chance for Felynes to die in the mine,range,0,100,1|40",
			"gemchance"=>"Percent chance for Felynes to find a gem on battle victory,range,0,100,1|5",
			"gemmessage"=>"Message to display when finding a gem|`&Your Felyne senses tingle, and you notice a `%gem`&!",
			"goldloss"=>"How much less gold (in percent) do the Felynes find?,range,0,100,1|15",
			"mindk"=>"How many DKs do you need before the race is available?,int|42",
			"maxdk"=>"Maximum dks for which this race is available?,int|62",
			"cost"=>"Cost of Race in Lodge Points,int|100",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|12",
		),
		"prefs"=>array(
			"Felyne Preferences,title",
			"bought"=>"Has Felyne race been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the race?,int|0",
		),
		"requires"=>array(
			"raceelf"=>"1.0|By Eric Stevens,part of core download",
		),
	);
	return $info;
}

function racecat_install(){
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("charstats");
	module_addhook("raceminedeath");
	module_addhook("battle-victory");
	module_addhook("creatureencounter");
	module_addhook("pvpadjust");
	module_addhook("adjuststats");
	module_addhook("racenames");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("dragonkill");
	return true;
}

function racecat_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Felyne'";
	db_query($sql);
	if ($session['user']['race'] == 'Felyne')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racecat_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("raceelf")) {
		$city = get_module_setting("villagename", "raceelf");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Felyne";
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
		$str = translate("The race: Felyne.  The cat people.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creaturedefense']+=(2+floor($args['creaturelevel']/5));
			$args['creaturehealth']-= round($args['creaturehealth']*.05, 0);
		}
		break;
	case"adjuststats":
		if ($args['race'] == $race) {
			$args['defense'] += (2+floor($args['level']/5));
			$args['maxhitpoints'] -= round($args['maxhitpoints']*.05, 0);
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Fortunately your felyne athleticism lets you escape unscathed.`n";
			$args['schema']="module-racecat";
		}
		break;
	case "charstats":
		if ($session['user']['race']==$race){
			addcharstat("Vital Info");
			addcharstat("Race", translate_inline($race));
		}
		break;
	case "chooserace":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=Felyne$resline'>On the plains surrounding the city of %s</a>, the city of men, your race of `5Felynes`0, or cat-people in the tongue of the city men, travelled behind the great herds for generations.  Your nimble agility allows you to visit places that larger races can only dream of.`n`n",$city, true);
		addnav("`5Felyne`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Felyne Blood (%s points)",$cost),
					"runmodule.php?module=racecat&op=start");
		}
		break;
	case "setrace":
		if ($session['user']['race']==$race){
			output("`&As a Felyne, your cat-like reflexes allow you to respond very quickly to any attacks.`n");
			output("You gain extra defense!`n");
			output("You have the eye for glittering gems typical of your race, but your childhood as a nomad has left you less knowledgable about currency.`n");
			output("You gain extra gems from forest fights, but you also do not gain as much gold!");
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
		if ($session['user']['level'] < 15 &&
				e_rand(1,100) <= get_module_setting("gemchance")) {
			output(get_module_setting("gemmessage")."`n`0");
			$session['user']['gems']++;
			debuglog("found a gem when slaying a monster, for being a Felyne.");
		}
		break;

	case "creatureencounter":
		if ($session['user']['race']==$race){
			racecat_checkcity();
			$loss = (100 - get_module_setting("goldloss"))/100;
			$args['creaturegold']=round($args['creaturegold']*$loss,0);
		}
		break;
	case "newday":
		if ($session['user']['race']==$race){
			racecat_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`@Cat-like Reflexes`0",
				"defmod"=>"(<defense>?(1+((2+floor(<level>/5))/<defense>)):0)",
				"badguydmgmod"=>1.05,
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-racecat",
				)
			);
		}
		break;
	}

	return $args;
}

function racecat_checkcity(){
	global $session;
	$race="Felyne";
	if (is_module_active("raceelf")) {
		$city = get_module_setting("villagename", "raceelf");
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

function racecat_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'Felyne';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Felyne Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racecat&op=yes");
				addnav("No","runmodule.php?module=racecat&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Felyne Blood`\$.");
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