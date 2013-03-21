<?php

function raceimp_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Imp",
		"version"=>"5.01",
		"author"=>"Chris Vorndran, DaveS Modifications, Arieswind Fixes",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Imp Race Settings,title",
			"minedeathchance"=>"Chance for Imp to die in the mine,range,0,100,1|25",
			"divide"=>"Charm is divided by this value to give buff,int|5",
			"mindk"=>"How many DKs do you need before the race is available?,int|10",
			"maxdk"=>"Maximum dks for which this race is available?,int|30",
			"cost"=>"Cost of Race in Lodge Points,int|50",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|8",
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

function raceimp_install(){
	module_addhook("chooserace");
	module_addhook("setrace");	
	module_addhook("newday");
	module_addhook("raceminedeath");
	module_addhook("racenames");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("dragonkill");
	return true;
}

function raceimp_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Imp'";
	db_query($sql);
	if ($session['user']['race'] == 'Imp')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function raceimp_dohook($hookname,$args){
	global $session,$resline;

	if (is_module_active("racedwarf")) {
		$city = get_module_setting("villagename", "racedwarf");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Imp";
	$divide = get_module_setting("divide");
	$imp = max(3,(round($session['user']['charm']/$divide)));
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
		$str = translate("The race: Giants.  Powerful and Great.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "raceminedeath":
		if ($session['user']['race'] == $race) {
			$args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "Fortunately your Imp skill let you escape unscathed.`n";
			$args['schema']="module-raceimp";
		}
		break;
	case "chooserace":
		if ($session['user']['sex']==SEX_FEMALE)
			break;
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=Imp$resline'>The land of Imps</a> Hellshade, `5hidden away from the world. `^Impish`0 `5 houses, crafted out of molten rock. Hidden in the deepest of crags, protected from the world of the normal folk. You are a very small being, only able to fly. You feel the need to trick others.`n`n",true);
		addnav("`\$I`)mp`0","newday.php?setrace=Imp$resline");
		addnav("","newday.php?setrace=Imp$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Giant Blood (%s points)",$cost),
					"runmodule.php?module=racegiant&op=start");
		}
		break;	
	case "setrace":
		if ($session['user']['race']==$race){
			output("`^As an Imp, you feel your matured skin protect you.`nYou gain extra attack!");
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
			raceimp_checkcity();
			apply_buff("racialbenefit",array(
				"name"=>"`@Imp Trickery`0",
				"atkmod"=>"(<attack>?(1+((1+floor($imp))/<attack>)):0)",
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

function raceimp_checkcity(){
	global $session;
	$race="Imp";
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

function raceimp_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'Imp';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Imp Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=raceimp&op=yes");
				addnav("No","runmodule.php?module=raceimp&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Imp Blood`\$.");
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