<?php

function racedarkelf_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Dark Elf",
		"version"=>"5.01",
		"author"=>"Kevin Hatfield - Arune, DaveS Modifications, Arieswind Fixes",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Dark Elf Race Settings,title",
			"minedeathchance"=>"Chance for Dark Elves to die in the mine,range,0,100,1|90",
			"mindk"=>"How many DKs do you need before the race is available?,int|2",
			"maxdk"=>"Maximum dks for which this race is available?,int|22",
			"cost"=>"Cost of Race in Lodge Points,int|25",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|5",
		),
		"prefs"=>array(
			"Dark Elf Preferences,title",
			"bought"=>"Has Dark Elf race been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the race?,int|0",
		),
		"requires"=>array(
			"raceelf"=>"1.0|By Eric Stevens,part of core download",
		),
	);
	return $info;
}

function racedarkelf_install(){
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

function racedarkelf_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='DarkElf'";
	db_query($sql);
	if ($session['user']['race'] == 'DarkElf')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racedarkelf_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("raceelf")) {
		$city = get_module_setting("villagename", "raceelf");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "DarkElf";
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
		$str = translate("The race: Dark Elf.  The Nocturnal Elves.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creatureattack']++;
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Fortunately your elvish athleticism lets you escape unscathed.`n";
			$args['schema']="module-racedarkelf";
		}
		break;
	case "chooserace":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=DarkElf$resline'>In the center of $city</a>`2 as a `@Dark Elf`2, wandering the city you smile to yourself..What an excellent day this is going to be! `n`n",true);
		addnav("`@Dark Elf`0","newday.php?setrace=DarkElf$resline");
		addnav("","newday.php?setrace=DarkElf$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Dark Elf Blood (%s points)",$cost),
					"runmodule.php?module=racedarkelf&op=start");
		}
		break;
	case "setrace":
		if ($session['user']['race']==$race){
			output("`@As a Dark Elf, you are adepth to darkness. `n`^Your muscular build and your enhanced perception increases your defense!");
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
		if ($session['user']['race']=="DarkElf"){
			racedarkelf_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`@Elven Perception`0",
				"defmod"=>"1+.7/<defense>",
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				)
			);
		}
		break;
	}
	return $args;
}

function racedarkelf_checkcity(){
	global $session;
	$race="DarkElf";
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

function racedarkelf_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'DarkElf';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Dark Elf Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racedarkelf&op=yes");
				addnav("No","runmodule.php?module=racedarkelf&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Dark Elf Blood`\$.");
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