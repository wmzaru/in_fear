<?php

function racegnome_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Gnome",
		"version"=>"5.01",
		"author"=>"T. J. Brumfield - Enderandrew, DaveS Modifications, Arieswind Fixes",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Gnome Race Settings,title",
			"minedeathchance"=>"Chance for Gnomes to die in the mine,range,0,100,1|80",
			"gemchance"=>"Percent chance for Gnomes to find a gem on battle victory,range,0,100,1|5",
			"gemmessage"=>"Message to display when finding a gem|`&Your Gnome nose and cheeks turn a ruddy red as you gleefully discover a `%gem`&!",
			"mindk"=>"How many DKs do you need before the race is available?,int|5",
			"maxdk"=>"Maximum dks for which this race is available?,int|25",
			"cost"=>"Cost of Race in Lodge Points,int|30",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|6",
		),
		"prefs"=>array(
			"Gnome Preferences,title",
			"bought"=>"Has Gnome race been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the race?,int|0",
		),
		"requires"=>array(
			"racedwarf"=>"1.0|By Eric Stevens,part of core download",
		),
	);
	return $info;
}

function racegnome_install(){
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("raceminedeath");
	module_addhook("battle-victory");
	module_addhook("pvpadjust");
	module_addhook("racenames");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("dragonkill");
	return true;
}

function racegnome_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Gargoyle'";
	db_query($sql);
	if ($session['user']['race'] == 'Gargoyle')
		$session['user']['race'] = RACE_UNKNOWN;
	return true;
}

function racegnome_dohook($hookname,$args){
	global $session,$badguy,$resline;

	if (is_module_active("racedwarf")) {
		$city = get_module_setting("villagename", "racedwarf");
	} else {
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	$race = "Gnome";
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$gnome = (int)($session['user']['level']*$session['user']['gems']/2);
	if ($gnome>50) $gnome=50;
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
		$str = translate("The race: Gnomes.  Masters of Gems.  This costs %s points and lasts for %s dragon kills");
		$str = sprintf($str, $cost,$dks);
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;
	case "pvpadjust":
		if ($args['race'] == $race) {
			$badguy['stats']['attack']*=(0.9);
		}
		break;
	case "raceminedeath":
      	if ($session['user']['race'] == $race) {
            $args['chance'] = get_module_setting("minedeathchance");
			$args['racesave'] = "It was dumb luck and your oblivious nature as a Gnome that allowed you to stumble out unscathed.`n";
			$args['schema']="module-racegnome";
      	}
		break;
	case "chooserace":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		output("<a href='newday.php?setrace=gnome$resline'>`6The mountainous lands around %s,</a>`6 both the `^Gnomes`6 and Dwarves make their home.  Here, adventure and excitement are just a means to increase your precious gem collection!`n`n",$city,true);
		addnav("`@Gnome`0","newday.php?setrace=gnome$resline");
		addnav("","newday.php?setrace=gnome$resline");
		break;
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Acquire Gnome Blood (%s points)",$cost),
					"runmodule.php?module=racegnome&op=start");
		}
		break;	
	case "setrace":
		if ($session['user']['race']==$race){
			output("`6As a `^Gnome`6, you are magically attuned to gems and crystals. ");
			output("`n`6Your preternatural senses draw you to find more gems, and as your collection of gems grows, so will your vitality!`n");
			output("You find more gems and get bonus hitpoints each day based upon your gems!`n");
			output("However, your obsession with all things metaphysical has distracted you from martial training.  ");
			output("As such, you're not quite as adept in attacking.`n");
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
		if ($session['user']['race']=="Gnome"){
			racegnome_checkcity();
	    $session['user']['hitpoints']+=$gnome;
	    output("`nYour gems shine briefly and fuse your Gnome body with vitality.  You gain %s hit points!`n", $gnome);
			apply_buff("racialbenefit",array(
				"name"=>"`#Crystal Obsession`0",
				"atkmod"=>0.9,
				"badguydmgmod"=>1.05,
				"allowinpvp"=>1,
				"allowintrain"=>1,
				"rounds"=>-1,
				"schema"=>"module-racegnome",
				)
			);
			if ($session['user']['marriedto']==1) {
				output("`n`@Thanks to your significant other, you are considerably less unkempt, and better grounded.`n");
				$session['user']['charm']++;
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
			debuglog("found a gem when slaying a monster, for being a Gnome.");
		}
		break;
	}
	return $args;
}

function racegnome_checkcity(){
    global $session;
    $race="Gnome";
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

function racegnome_run(){
	global $session;
	page_header("Hunter's Lodge");
	$race = 'Gnome';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `^Gnome Blood`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racegnome&op=yes");
				addnav("No","runmodule.php?module=racegnome&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a cold crimson liquid in it.`n`n");
			output("\"`\$That is pure `^Gnome Blood`\$.");
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