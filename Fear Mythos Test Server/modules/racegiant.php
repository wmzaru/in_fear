<?php

function racegiant_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Giant",
		"version"=>"5.01",
		"author"=>"Keith, DaveS Modifications, Arieswind Fixes",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Giant Race Settings,title",
			"minedeathchance"=>"Percent chance for Giants to die in the mine,range,0,100,1|25",
			"gemchance"=>"Percent chance for Giants to find a gem on battle victory,range,0,100,1|10",
			"mindk"=>"How many DKs do you need before the race is available?,int|34",
			"maxdk"=>"Maximum dks for which this race is available?,int|54",
			"cost"=>"Cost of Race in Lodge Points,int|100",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|12",
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

function racegiant_install(){
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

function racegiant_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Giant'";
	db_query($sql);
	if ($session['user']['race'] == 'Giant')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racegiant_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("racedwarf")) {
		$city = get_module_setting("villagename", "racedwarf");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Giant";
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
		$str = translate("The race: Imps. Mean, little, and Green. The pranksters.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$args['creaturedefense']-=(1+floor($args['creaturelevel']/5));
			$args['creatureattack']-=(1+floor($args['creaturelevel']/5));
		}
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Fortunately your gigantic strength lets you escape unscathed.`n";
			$args['schema']="module-racegiant";
		}
		break;
	case "chooserace":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=Giant$resline'>As a young child you never remember looking up to anyone.</a> You stood with your head as high as the trees for hourse watching the clouds go by. Never feeling totaly accepted you often spent most of your time allone wading in the river bed. You are one of the few remaining Giants in %s.`n`n",$city, true);
		addnav("`5Giant`0","newday.php?setrace=$race$resline");
		addnav("","newday.php?setrace=$race$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Giant Blood (%s points)",$cost),
					"runmodule.php?module=racegiant&op=start");
		}
		break;	
	case "setrace":
		if ($session['user']['race']==$race){ // it helps if you capitalize correctly
			output("`&As a Giant, your size makes you much stronger, and can effortlessly wield weapons, but giants are aslo a little clumsy.`n");
			output("You gain extra attack!`n");
			output("You lose some defense!`n");
			if (is_module_active("cities")) {
				if ($session['user']['dragonkills']==0 &&
						$session['user']['age']==0){
					//new farmthing, set them to wandering around this city.
					set_module_setting("newest-$city",
							$session['user']['acctid'],"cities");
				}
				set_module_pref("homecity",$city,"cities");
				if ($session['user']['age'] == 0)
					$session['user']['location']=$city;
			}
		}
		break;
	case "newday":
		if ($session['user']['race']==$race){
			racecat_checkcity();
			$session['user']['turns']++;
			output("`n`&Because you are giant, you gain `^an extra`& forest fight for today!`n`0");
			apply_buff("racialbenefit",array(
				"name"=>"`@Giant Abilitys`0",
	 			"atkmod"=>"(<attack>?(1+((1+floor(<level>/5))/<attack>)):0)",
				"defmod"=>"(<defense>?(-1+((2+floor(<level>/5))/<defense>)):0)",
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-racegiant",
				)
			);
		}
		break;
	}

	return $args;
}

function racegiant_checkcity(){
	global $session;
	$race="Giant";
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

function racegiant_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'Giant';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Giant Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racegiant&op=yes");
				addnav("No","runmodule.php?module=racegiant&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Giant Blood`\$.");
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