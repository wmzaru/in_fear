<?php

function racepenguin_getmoduleinfo(){
	$info = array(
		"name"=>"Race - Penguin based on Race - Storm Giant by Chris Vorndran",
		"version"=>"1.0",
		"author"=>"`!Q`)wyxzl`0",
		"category"=>"Races",
		"download"=>"http://dragonprime.net/index.php?topic=8789.0",
		"requires"=>array(
			"icetown"=>"1.0|By Shannon Brown ,part of core download"
		),
		"settings"=>array(
			"Penguin Race Settings,title",
			"minedeathchance"=>"Percent chance for a `)Pe`&ngu`)in`0 to die in the mine,range,0,100,1|50",
			"mindk"=>"How many DKs do you need before the `)Pe`&ngu`)ins`0 are available?,int|1",
		    "cost"=>"Cost of Emperor Penguin in Lodge Points,int|100",
		),
		"pref"=>array(
			"Penguin Race Preferences,title",
			"bought"=>"Did this player purchase the option to be an Emperor Penguin?,bool|0",
			"chose"=>"Did this player choose to be an Emperor Penguin?,bool|0"
		)
	);
	return $info;
}

function racepenguin_install(){
	if(!is_module_installed("icetown")){
		output("`!The `)Pe`&ngu`)ins`! only choose to live where it is cold. You must install icetown.php.`0");
		return false;
	}
	module_addhook("racenames");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("chooserace");
	module_addhook("setrace");
	module_addhook("newday");
	module_addhook("villagetext");
	module_addhook("validforestloc");
	module_addhook("raceminedeath");
	module_addhook("changesetting");
	module_addhook("pvpadjust");
	module_addhook("pvpwin");
	module_addhook("pvploss");
	module_addhook("footer-village");
	module_addhook("pre-travel");
	return true;
}

function racepenguin_uninstall(){
	global $session;
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Penguin'";
	db_query($sql);
	$sql = "UPDATE  " . db_prefix("accounts") . " SET race='" . RACE_UNKNOWN . "' WHERE race='Emperor Penguin'";
	db_query($sql);
	if ($session['user']['race'] == 'Penguin' || $session['user']['race'] == 'Emperor Penguin'){
		$session['user']['race'] = RACE_UNKNOWN;
	}
	return true;
}

function racepenguin_dohook($hookname,$args){
	global $session, $resline;
	$racedonate = "Emperor Penguin";
	$racenormal = "Penguin";
	$mindk = get_module_setting("mindk");
	
	//penguins only live in ice town if it is possible to get there
	if(racepenguin_can_travel()){
		$city = get_module_setting("villagename", "icetown");
	}else{
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	if(httpget("emp") == "yes"){
		set_module_pref("chose", true);
	}
	if(get_module_pref("chose")){
		$race = $racedonate;
	}else{
		$race = $racenormal;
	}
	switch($hookname){
		case "racenames":
			$args[$race] = $race;
		break;
		case "pointsdesc":
			if($session['user']['dragonkills'] >= $mindk){
				$args['count']++;
				$format = $args['format'];
				$str = translate("The race: `!Emperor `)Pe`&ngu`)in`0; Bigger cousins of the `)Pe`&ngu`)ins`0. This costs %s points");
				$str = sprintf($str, get_module_setting("cost"));
				output($format, $str, true);
			}
		break;
		case "lodge":
			if (!get_module_pref("bought") && $session['user']['dragonkills'] >= $mindk){
				addnav(array("Waddle like an `!Emperor `)Pe`&ngu`)in`0(%s points)", get_module_setting("cost")), "runmodule.php?module=racepenguin&op=buy");
			}
		break;
		case "chooserace":
			set_module_pref("chose", false);
			if($session['user']['dragonkills'] >= $mindk){
				output("<a href='newday.php?setrace=$race$resline'>`!In the chilly city of  %s</a>, `)Pe`&ngu`)ins`0 dwell in cozy little igloos.`n`n",$city, true);
				addnav("`)Pe`&ngu`)in`0","newday.php?setrace=$race$resline");
				addnav("","newday.php?setrace=$race$resline");
			}
			if(get_module_pref("bought")){
				output("<a href='newday.php?setrace=$race$resline'>`!In the chilly city of  %s</a>, `!Emperor `)Pe`&ngu`)ins`0 dwell in cozy igloos`n",$city, true);
				output("`!They are bigger than regular `)Pe`&ngu`)ins`0.`n`n");
				addnav("`!Emperor `)Pe`&ngu`)in`0","newday.php?setrace=$racedonate$resline&emp=yes");
				addnav("","newday.php?setrace=$racedonate$resline&emp=yes");
			}
		break;
		case "setrace":
			if($session['user']['race'] == $race){
				output("`)Pe`&ngu`)ins `!are known for their cuteness and and like to play in the `#cold`!.`n");
				if($race == $racedonate){
					$amount = 4;
				}else{
					$amount = 2;
				}
				$session['user']['charm'] += $amount;//penguins are cute
				if(racepenguin_can_travel()){
					if($session['user']['dragonkills'] >= get_module_setting("mindk") && $session['user']['age'] == 0){
						set_module_setting("newest-$city", $session['user']['acctid'], "cities");
					}
					set_module_pref("homecity", $city, "cities");
					if($session['user']['age'] == 0){
						$session['user']['location'] = $city;
					}
				}
			}
		break;
		case "adjuststats":
		if ($args['race'] == $racedonate) {
			$args['defense'] += (1 + floor(($args['level'] + 1)/4));
		}
		break;
		case "newday":
			if($session['user']['race'] == $racedonate){
				$session['user']['turns']++;
				output("`n`!Because Emperor `)Pe`&ngu`)ins`! are so playful they get an extra turn!`0`n");
			}
			if($session['user']['race'] == $race){
				racepenguin_checkcity();
				apply_buff("racialbenefit", racepenguin_buff());
			}
		break;
		case "villagetext":
			racepenguin_checkcity();
			if ($session['user']['location'] == $city){
				$new = get_module_setting("newest-$city", "cities");
				if($new != 0){
					$sql =  "SELECT name FROM " . db_prefix("accounts") .
						" WHERE acctid='$new'";
					$result = db_query_cached($sql, "newest-$city");
					$row = db_fetch_assoc($result);
					$args['newestplayer'] = $row['name'];
					$args['newestid'] = $new;
				}else{
					$args['newestplayer'] = $new;
					$args['newestid'] = "";
				}
				if($new == $session['user']['acctid']){
					$args['newest']="`n`!You waddle around in the `&snow`! happily waving your flippers.`3";
				}else{
					$args['newest']="`n`!Waddling around in the `&snow`! happily waving their flippers is `#%s`3.";
				}
			}
			$args['schemas']['newest'] = "module-icetown";
			break;
		case "validforestloc":
			if($session['user']['race'] == $race && is_module_active("cities")){
				$args[$city]="village-$race";
			}
			break;
		case "raceminedeath":
			if($session['user']['race'] == $race){
				$args['chance'] = get_module_setting("minedeathchance");
				$args['racesave'] = "`)Pe`&ngu`)ins`! are not known for their speed, but today you shook a tailfeather to get out JUST in time!`0.`n";
				$args['schema']="module-racepenguin";
			}
		break;
		case "changesetting":
			if ($args['setting'] == "villagename" && $args['module']=="icetown") {
				if($session['user']['location'] == $args['old']){
					$session['user']['location'] = $args['new'];
				}
				$sql = "UPDATE " . db_prefix("accounts") .
					" SET location='" . addslashes($args['new']) .
					"' WHERE location='" . addslashes($args['old']) . "'";
				db_query($sql);
				$sql = "UPDATE ".db_prefix("companions")." SET location='".$args['new']." WHERE location='".$args['old']."'";
				db_query($sql);
				if (is_module_active("cities")) {
					set_module_pref("homecity", $city, "cities");
					$sql = "UPDATE " . db_prefix("module_userprefs") .
						" SET value='" . addslashes($args['new']) .
						"' WHERE modulename='cities' AND setting='homecity'" .
						"AND value='" . addslashes($args['old']) . "'";
					db_query($sql);
				}
			}
		break;
		case "pvpadjust":
			if($args['race'] == $racenormal){
				if($session['user']['race'] == $racenormal){
					apply_buff("racialbenefit", racepenguin_nobuff());
				}else if($session['user']['race'] != $racedonate){
					apply_buff("targetracialbenefit", racepenguin_targetbuff());
				}
			}
			//find out if the target is an emperor
			if($args['race'] == $racedonate){
				$args['creaturedefense'] += (1 + floor(($args['creaturelevel'] + 1) / 4));
				if($session['user']['race'] == $racedonate){
					apply_buff("racialbenefit", racepenguin_nobuff());
				}else{
					//regular penguins are not cold enough to damamge
					//emperor penguins
					if($session['user']['race'] == $racenormal){
						strip_buff("racialbenefit");
					}
					apply_buff("targetracialbenefit", racepenguin_targetbuff());
				}
			}
		break;
		case "pvpwin":
		case "pvploss":
			global $options;
			if($options['type'] == 'pvp'){
				strip_buff("targetracialbenefit");
				if($session['user']['race'] == $race){
					apply_buff("racialbenefit", racepenguin_buff());
				}
			}
		break;
		case "footer-village":
			if($session['user']['race'] == $race && 
			   $session['user']['location'] == $city &&
			   $city != getsetting("villagename", LOCATION_FIELDS)){
				unblocknav("lodge.php");
				unblocknav("weapons.php");
				unblocknav("armor.php");
				unblocknav("clan.php");
				unblocknav("pvp.php");
				unblocknav("forest.php");
				unblocknav("gardens.php");
				unblocknav("gypsy.php");
				unblocknav("bank.php");
			}
		break;
		case "pre-travel":
			if($session['user']['race'] == $racedonate && $session['user']['location'] != $city){
				addnav("Belly slide!");
				addnav(array("Slide to %s", $city),"runmodule.php?module=racepenguin&op=slide");
			}
		break;

	}
	return $args;
}

function racepenguin_run(){
	global $session;
	$op = httpget("op");
	
	switch($op){
		case "slide":
			if(racepenguin_can_travel()){
				$city = get_module_setting("villagename", "icetown");
			}else{
				$city = getsetting("villagename", LOCATION_FIELDS);
			}
			$session['user']['location'] = $city;
			page_header("WHeeeeeeeeeee!");
			output("`!You spot a perfect path in the snow that will let you slide all the way to %s`! safely!`n", $city);
			output("You make it there so fast that you dont even use up one of your travels for the day!");
			require_once("lib/villagenav.php");
			villagenav();
		break;
		case "buy":
			page_header("Hunter's Lodge");
			$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
			if ($pointsavailable >= get_module_setting("cost") && !get_module_pref("bought")){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to purchase the `!Emperor `)Pe`&ngu`)in`\$ waddle?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=racepenguin&op=yes");
				addnav("No","runmodule.php?module=racepenguin&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			addnav("Return");
			addnav("L?Return to the Lodge","lodge.php");
		break;
		case "yes":
			page_header("Hunter's Lodge");
			output("`3J. C. Petersen hands you a tiny cold `!blue`3 fish.`n`n");
			output("\"`\$That is what `!Emperor `)Pe`&ngu`)ins `\$ eat.");
			output("Now, You eat it!`3\"`n`n");
			output("You double over, spasming on the ground.");
			output("J. C. Petersen grins, \"`\$Your body shall finish its change upon newday... I suggest you rest.`3\"");
			$session['user']['race'] = "Emperor Penguin";
			$session['user']['donationspent'] += get_module_setting("cost");
			set_module_pref("bought", true);
                  set_module_pref("chose", true);
                  if(racepenguin_can_travel()){
				$city = get_module_setting("villagename", "icetown");
	            }else{
		            $city = getsetting("villagename", LOCATION_FIELDS);
	            }
			if(is_module_active("cities")){
				set_module_setting("newest-$city", $session['user']['acctid'], "cities");
                        set_module_pref("homecity", $city, "cities");
			}
			addnav("Return");
			addnav("L?Return to the Lodge","lodge.php");
		break;
		case "no":
			page_header("Hunter's Lodge");
			output("`3J. C. Petersen looks at you and shakes his head.");
			output("\"`\$I swear to you, waddling is fun!.");
			addnav("Return");
			addnav("L?Return to the Lodge","lodge.php");
		break;
	}
	page_footer();
}

function racepenguin_checkcity(){
	global $session;
	
	if(get_module_pref("chose")){
		$race = "Emperor Penguin";
	}else{
		$race = "Penguin";
	}
	if(racepenguin_can_travel()){
		$city = get_module_setting("villagename", "icetown");
	}else{
		$city = getsetting("villagename", LOCATION_FIELDS);
	}
	if($session['user']['race'] ==$race && is_module_active("cities")){
		if(get_module_pref("homecity", "cities") != $city){ //home city is wrong
			set_module_pref("homecity", $city, "cities");
		}
	}
	return true;
}

function racepenguin_buff(){
	return array(
		"name"=>"`#Brrr it's cold!`0",
		"rounds"=>-1,
		"minioncount"=>1,
		"minbadguydamage"=>0,
		"maxbadguydamage"=>"floor(<level>/3) + 3",
		"areadamage"=>true,
		"effectmsg"=>"{badguy} `!shivers from the `#cold`! and takes `^{damage}`! damage.",
		"effectnodmgmsg"=>"{badguy} `!shivers a little but takes no damage.",
		"effectfailmsg"=>"{badguy} `!likes the `#cold`! almost as much as you do!.",
		"allowinpvp"=>1,
		"allowintrain"=>1,
		"schema"=>"module-racepenguin"
	);
}

function racepenguin_nobuff(){
	return array(
		"name"=>"`#Brrr it's cold!`0",
		"rounds"=>-1,
		"minioncount"=>1,
		"minbadguydamage"=>0,
		"maxbadguydamage"=>0,
		"areadamage"=>true,
		"effectmsg"=>"`!You both like the `#cold`! so no damage is done.",
		"effectnodmgmsg"=>"`!You both like the `#cold`! so no damage is done.",
		"effectfailmsg"=>"`!You both like the `#cold`! so no damage is done.",
		"allowinpvp"=>1,
		"schema"=>"module-racepenguin"
	);
}

function racepenguin_targetbuff(){
	return array(
		"name"=>"",
		"rounds"=>-1,
		"minioncount"=>1,
		"mingoodguydamage"=>0,
		"maxgoodguydamage"=>floor($args['creaturelevel']/3) + 3,
		"areadamage"=>true,
		"effectmsg"=>"`!You shiver from the `#cold`! around {badguy}`! and take `#{damage}`! damage.",
		"effectnodmgmsg"=>"`!You shiver a little but take no damage.",
		"effectfailmsg"=>"`!You like the cold almost as much as {badguy}`! does!.",
		"allowinpvp"=>1,
		"schema"=>"module-racepenguin"
	);
}

function racepenguin_can_travel(){
	global $session;
	
	if(is_module_active("cities") && is_module_active("icetown")){
		if(get_module_setting("allowtravel", "icetown")){
			return true;
		}
		if(is_module_active("icecaravan")){
                        //if penguins are active then everyone needs to be able to travel
                        //to icetown
                        set_module_setting("canvisit", true, "icecaravan");
                        //Penguins dont have to pay either
                        set_module_pref("hasticket", true, "icecaravan");
			return true;
		}
	}
	return false;
}
?>
