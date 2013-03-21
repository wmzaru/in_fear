<?php
function citygeneric3_getmoduleinfo(){
	$info = array(
		"name"=>"City - Generic 3",
		"version"=>"1.01",
		"author"=>"Billie Kennedy, modified by DaveS",
		"category"=>"Cities",
		"download"=>"",
		"settings"=>array(
			"Generic City 3 Settings,title",
			"villagename"=>"Name for the village|Teserac",
			"blocklodge"=>"Restrict `^lodge`0 from appearing in this village?,bool|0",
			"blockweapons"=>"Restrict `^weapon shop`0 from appearing in this village?,bool|0",
			"blockarmor"=>"Restrict `^armor shop`0 from appearing in this village?,bool|0",
			"blockclan"=>"Restrict `^clan entry`0 from appearing in this village?,bool|0",
			"blockbank"=>"Restrict `^bank`0 from appearing in this village?,bool|0",
			"blockforest"=>"Restrict `^forest`0 from appearing in this village?,bool|0",
			"blockgypsy"=>"Restrict `^gypsy`0 tent from appearing in this village?,bool|0",
			"blockpvp"=>"Restrict `^pvp`0 in this village?,bool|0",
			"blockgardens"=>"Restrict `^gardens`0 from appearing in this village?,bool|0",
			"blockdwellings"=>"Restrict `^Dwellings access`0 from appearing in this village?,bool|0",
			"Travel,title",
			"travel"=>"Is travel to and from this city restricted to certain start/leave locations?,enum,0,Unrestricted,1,Restricted|0",
			"travelfrom"=>"If Restricted: Where can you travel from,location|".getsetting("villagename", LOCATION_FIELDS),
			"travelto"=>"If Restricted: Where can you travel to,location|".getsetting("villagename", LOCATION_FIELDS),
			"Lodge Points,title",
			"points"=>"Lodge Access:,enum,0,0=None,1,1=Minimum Donor Points,2,2=One Time Amount,3,3=Pay each x days,4,4=Pay Each DK",
			"Options:`n`^0=No Lodge Points Required`n`\$1= Must have a Minimum Number of Donor Points`n`#2=Must spend a one-time amount of lodge points`n`@3=Must spend lodge points every x days`n`%4=Must spend lodge points every dragon kill,note",
			"xdays"=>"`@If set to x days: How many days is the city open if purchased:,int|20", 
			"cost"=>"If lodge access needed: How many lodge points are required/cost for city access?,int|20",
			"Access,title",
			"mindk"=>"How many Dragon Kills does a player have to have for access?,int|0",
			"allowaccess"=>"Allow access chance to this city for all players?,enum,0,No,1,Yes - Every x Newdays,2,Yes - Random Chance|0",
			"announce"=>"Announce that the city is open to everyone in village text of other villages?,bool|0",
			"chance"=>"`@Access Every X Newdays `0or `!Random Chance X in 100`0:,range,1,100,1|10",
			"counter"=>"`@How many Newdays have gone by since the last opening?,int|0",
			"open"=>"Is the city open today for everyone?,bool|0",
		),
		"prefs"=>array(
			"Generic City 3 Preferences,title",
			"donated"=>"Has the player donated for access to this city?,bool|0",
			"counter"=>"How many newdays has it been since the city was open if set for this?,int|0",
		),
		"requires"=>array(
			"cities"=>"1.0|Eric Stevens, part of the core download",
		),
	);
	return $info;
}

function citygeneric3_install(){
	module_addhook("newday-runonce");
	module_addhook("newday");
	module_addhook("dragonkill");
	module_addhook("villagetext");
	module_addhook("village");
	module_addhook("travel");
	module_addhook("validlocation");
	module_addhook("moderate");
	module_addhook("changesetting");
	module_addhook("lodge");
	module_addhook("pvpstart");
	module_addhook("pvpwin");
	module_addhook("pvploss");
	module_addhook("pointsdesc");
	module_addhook("mountfeatures");
	return true;
}

function citygeneric3_uninstall(){
	global $session;
	$vname = getsetting("villagename", LOCATION_FIELDS);
	$gname = get_module_setting("villagename");
	$sql = "UPDATE " . db_prefix("accounts") . " SET location='$vname' WHERE location = '$gname'";
	db_query($sql);
	if ($session['user']['location'] == $gname)
		$session['user']['location'] = $vname;
	return true;
}

function citygeneric3_dohook($hookname,$args){
	global $session,$resline;
	$city = get_module_setting("villagename");
	switch($hookname){
	case "newday-runonce":
		set_module_setting("open",0);
		if (get_module_setting("allowaccess")==1){
			increment_module_setting("counter",1);
			if (get_module_setting("counter")>=get_module_setting("chance")){
				set_module_setting("counter",0);
				set_module_setting("open",1);
				if (get_module_setting("mindk")==0)	addnews("The city of %s is open for everyone to visit today.",get_module_setting("villagename"));
				else addnews("The city of %s is open to visit today for everyone with at least %s dragon kills.",get_module_setting("villagename"),get_module_setting("mindk"));
			}
		}elseif (get_module_setting("allowaccess")==2 && get_module_setting("chance")>=e_rand(1,100)){
			set_module_setting("open",1);
			if (get_module_setting("mindk")==0)	addnews("The city of %s is open for everyone to visit today.",get_module_setting("villagename"));
			else addnews("The city of %s is open to visit today for everyone with at least %s dragon kills.",get_module_setting("villagename"),get_module_setting("mindk"));
		}
	break;
	case "newday":
		if (get_module_setting("points")==3 && get_module_pref("donated")==1){
			increment_module_pref("counter",1);
			if (get_module_pref("counter")>=get_module_setting("xdays")){
				set_module_pref("donated",0);
				output("`nYour pass to visit the town of %s has expired.  Please visit the lodge to purchase a new pass if you would like to continue to visit there.`n",get_module_setting("villagename"));
				debuglog("expired access to $city because the setting number of newdays have passed.");
			}
		}
	break;
	case "dragonkill":
		if (get_module_setting("points")==4 && get_module_pref("donated")==1){
			set_module_pref("donated",0);
			debuglog("expired access to $city after dk.");
		}
	break;
	case "pointsdesc":
		if (get_module_setting("points")>0){
			$args['count']++;
			$format = $args['format'];
			if (get_module_setting("points")==1){
				$str = translate("Access to %s requires that you have received at least %s points.  (These are not used up)");
				$str = sprintf($str, get_module_setting("villagename"),get_module_setting("cost"));
			}elseif (get_module_setting("points")==2){
				$str = translate("Access to %s will cost a one-time fee of %s points.");
				$str = sprintf($str, get_module_setting("villagename"),get_module_setting("cost"));
			}elseif (get_module_setting("points")==3){
				$str = translate("Access to %s will cost %s points every %s days.");
				$str = sprintf($str, get_module_setting("villagename"),get_module_setting("cost"),get_module_setting("xdays"));
			}elseif (get_module_setting("points")==4){
				$str = translate("Access to %s will cost %s points every dragon kill.");
				$str = sprintf($str, get_module_setting("villagename"),get_module_setting("cost"));
			}
			output($format, $str, true);
		}
		break;
	case "lodge":
		if (get_module_setting("points")==2 && get_module_pref("donated")==0) addnav(array("Unlimited Access to %s (%s Points)",get_module_setting("villagename"),get_module_setting("cost")),"runmodule.php?module=citygeneric3&op=lodge");
		elseif (get_module_setting("points")==3 && get_module_pref("donated")==0) addnav(array("Access %s for %s Days (%s Points)",get_module_setting("villagename"),get_module_setting("xdays"),get_module_setting("cost")),"runmodule.php?module=citygeneric3&op=lodge");
		elseif (get_module_setting("points")==4 && get_module_pref("donated")==0) addnav(array("Access %s this DK (%s Points)",get_module_setting("villagename"),get_module_setting("cost")),"runmodule.php?module=citygeneric3&op=lodge");
	break;
	case "pvpwin":
		if ($session['user']['location'] == $city) {
			$args['handled']=true;
			addnews("`4%s`3 defeated `4%s`3 in fair combat near the campfire in %s.", $session['user']['name'],$args['badguy']['creaturename'], $args['badguy']['location']);
		}
	break;
	case "pvploss":
		if ($session['user']['location'] == $city) {
			$args['handled']=true;
			addnews("`%%s`5 has been slain while attacking `^%s`5 near the campfire in `&%s`5.`n%s`0", $session['user']['name'], $args['badguy']['creaturename'], $args['badguy']['location'], $args['taunt']);
		}
		break;
	case "pvpstart":
		if ($session['user']['location'] == $city) {
			$args['atkmsg'] = "`4You wander through the village gates, to where a large group of warriors are singing around the campfire. At the edges of the scrub, in the darkness and away from the others, some foolish warriors have bedded down for the night...`n`nYou have `^%s`4 PvP fights left for today.`n`n";
			$args['schemas']['atkmsg'] = 'module-citygeneric3';
		}
		break;
	case "travel":
		$hotkey = substr($city, 0, 1);
		$cost = get_module_setting("cost");
		$access=0;
		if (get_module_setting("points")==0) $access=1;
		elseif (get_module_setting("points")==1 && $cost <= $session['user']['donation']) $access=1;
		elseif (get_module_pref("donated")==1) $access=1;
		if (get_module_setting("open")==1) $access=1;

		tlschema("module-cities");
		if ($session['user']['superuser'] & SU_EDIT_USERS){
			addnav("Superuser");
			addnav(array("%s?Go to %s", $hotkey, $city),"runmodule.php?module=cities&op=travel&city=$city&su=1");
		}
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $access==0) break;
		if ($session['user']['location']!=$city){
			if (get_module_setting("travel")==0 || (get_module_setting("travel")==1 && $session['user']['location']==get_module_setting("travelfrom"))){
				addnav("More Dangerous Travel");
				addnav(array("%s?Go to %s", $hotkey, $city),"runmodule.php?module=cities&op=travel&city=$city&d=1");
			}
		}
		tlschema();
	break;
	case "changesetting":
		// Ignore anything other than villagename setting changes
		if ($args['setting']=="villagename" && $args['module']=="citygeneric3") {
			if ($session['user']['location'] == $args['old']) {
				$session['user']['location'] = $args['new'];
			}
			$sql = "UPDATE " . db_prefix("accounts") . " SET location='" .
				$args['new'] . "' WHERE location='" . $args['old'] . "'";
			db_query($sql);
		}
		break;
	case "validlocation":
			$args[$city]="village-citygeneric3";
		break;
	case "moderate":
			tlschema("commentary");
			$args["village-citygeneric3"]=sprintf_translate("%s Village", $city);
			tlschema();
		break;
	case "villagetext":
		if ($session['user']['location'] == $city){
			$cost = get_module_setting("cost");
			$xdays= get_module_setting("xdays");
			if ((get_module_setting("points")==1 && $cost > $session['user']['donation']) || (get_module_setting("points")==2 && get_module_pref("donated")==0))  $args['text']="`\$`c`@`bA small village named $city`b`@`c`n`n`2 The residents of $city busy themselves with various tasks of everyday life.  `n`n`^It's a rare opportunity for you to visit $city today.  You can access $city everyday if you would stop at the Lodge to donate to the site!`0";
			elseif (get_module_setting("points")==3 && get_module_pref("donated")==0) $args['text']="`\$`c`@`bA small village named $city`b`@`c`n`n`2 The residents of $city busy themselves with various tasks of everyday life.  `n`n`^It's a rare opportunity for you to visit $city today.  You can access $city everyday for $xdays days if you would stop at the Lodge to donate to the site!`0";
			elseif (get_module_setting("points")==4 && get_module_pref("donated")==0) $args['text']="`\$`c`@`bA small village named $city`b`@`c`n`n`2 The residents of $city busy themselves with various tasks of everyday life.  `n`n`^It's a rare opportunity for you to visit $city today.  You can access $city everyday for until you kill the dragon if you would stop at the Lodge to donate to the site!`0";
			else $args['text']="`\$`c`@`bA small village named $city`b`@`c`n`n`2 The residents of $city busy themselves with various tasks of everyday life.  Small farm animals scurry underfoot as you make your way through the sparce buildings.`n`nMost of the villagers of $city seem to stare at you.  Unsure of who you are, they appear quite suspious of your intent.  Some stop what they are doing just to watch you pass by.`n`nYou get the feeling as if you are quite out of place here.`n`n";
            $args['schemas']['text'] = "module-citygeneric3";
			$args['clock']="`n`7A small sundial at the center of the village reads `&%s`7.`n";
            $args['schemas']['clock'] = "module-citygeneric3";
			if (is_module_active("calendar")) {
				$args['calendar'] = "`n`2Overheard whispers suggest that it is `&%s`2, `&%s %s %s`2.`n";
				$args['schemas']['calendar'] = "module-citygeneric3";
			}
			$args['title']=array("%s Village", $city);
			$args['schemas']['title'] = "module-citygeneric3";
			$args['sayline']="brags";
			$args['schemas']['sayline'] = "module-citygeneric3";
			$args['talk']="`n`&Nearby some visitors brag:`n";
			$args['schemas']['talk'] = "module-citygeneric3";
			$args['newest'] = "";

			if (get_module_setting("blocklodge")==1) blocknav("lodge.php");
			if (get_module_setting("blockweapons")==1) blocknav("weapons.php");
			if (get_module_setting("blockarmor")==1) blocknav("armor.php");
			if (get_module_setting("blockclan")==1) blocknav("clan.php");
			if (get_module_setting("blockpvp")==1) blocknav("pvp.php");
			if (get_module_setting("blockforest")==1) blocknav("forest.php");
			if (get_module_setting("blockbank")==1) blocknav("bank.php");
			if (get_module_setting("blockgardens")==1) blocknav("gardens.php");
			if (get_module_setting("blockgypsy")==1) blocknav("gypsy.php");
			if (get_module_setting("travel")==1) blockmodule("cities");
			if (get_module_setting("blockdwellings")==1 && is_module_active("dwellings")) blockmodule("dwellings");
			
			$args['schemas']['newest'] = "module-citygeneric3";
			$args['gatenav']="Village Gates";
			$args['schemas']['gatenav'] = "module-citygeneric3";
			$args['fightnav']="Nearby Forest";
			$args['schemas']['fightnav'] = "module-citygeneric3";
			$args['marketnav']="Village Market";
			$args['schemas']['marketnav'] = "module-citygeneric3";
			$args['tavernnav']="Drunkard's Lane";
			$args['schemas']['tavernnav'] = "module-citygeneric3";
			$args['section']="village-citygeneric3";
			$args['infonav']="Village Council";
			$args['schemas']['infonav'] = "module-citygeneric3";
		}
		break;

	case "village":
		if (get_module_setting("travel")==1 && $session['user']['location']==$city){
			global $playermount;
			$from = get_module_setting("travelfrom");
			$to = get_module_setting("travelto");
			tlschema($args['schemas']['gatenav']);
			addnav($args['gatenav']);
			tlschema();
			//addnav("S?Slay Other Players","pvp.php?campsite=1");
			$travelleft = get_module_setting('allowance','cities') - get_module_pref('traveltoday','cities');
			if($session['user']['turns'] > 0 || $travelleft > 0){
				addnav(array("Travel to %s",$from),"runmodule.php?module=cities&op=travel&city=$from&d=1");
				if($to != $from) addnav(array("Travel to %s",$to),"runmodule.php?module=cities&op=travel&city=$to&d=1");
			}
		}
		if (get_module_setting("mindk")==0) $text=translate_inline("all");
		else $text="";
		if ($session['user']['location']!= $city && get_module_setting("announce")==1 && $session['user']['dragonkills']>=get_module_setting("mindk")){
			output("`n`^`c`bAnnouncement:  The village of %s is open today for %s citizens to visit.",get_module_setting("villagename"),$text);
			if (get_module_setting("travel")==1) output("`nYou may travel to %s from %s.",get_module_setting("villagename"),get_module_setting("travelfrom"));
			output_notl("`c`0`n`b");
		}
		break;
	}
	return $args;
}

function citygeneric3_run(){
global $session;
$op = httpget("op");
$op2 = httpget("op2");
if ($op=="lodge"){
	page_header("Hunter's Lodge");
	$place=get_module_setting("villagename");
	$cost=get_module_setting("cost");
	output("`c`b`&%s Access`b`c`n`7",$place);
	if ($op2==""){
		$pointsavailable = $session['user']['donation'] - $session['user']['donationspent'];
		output("J.c. Petersen checks the records. `&'I see you're interested in visiting the town of `#%s`&.'`7`n`n",$place);
		if (get_module_setting("mindk")>$session['user']['dragonkills']){
			output("He frowns before continuing, `&'Unfortunately, you haven't killed enough `@Dragons`& to visit the town of `#%s`&.  There's no point in you wasting Lodge Points. I'm sorry.'",$place);
		}elseif ($pointsavailable <$cost){
			output("He looks at the book twice before looking up at you, `&'You don't have enough Lodge Points to purchase a Pass to `#%s`&.  You need `^%s`& points and you only have `^%s`& points.",$place,$cost,$pointsavailable);
		}else{
			output("He smiles before continuing,`& 'It looks like everything should be in order.  Just confirm your purchase and you'll be able to visit `#%s`&.'",$place);
			addnav(array("Purchase Pass to %s (%s Points)",$place,$cost),"runmodule.php?module=citygeneric3&op=lodge&op2=confirm");
		}
	}elseif ($op2=="confirm"){
		$session['user']['donationspent']+=$cost;
		debuglog("purchased access to $city for $cost lodgepoints.");
		set_module_pref("donated",1);
		output("J.C. Petersen hands you a pass to `#%s`7. `&'Be careful and good luck!'`7 he says.",$place);
	}
	addnav("Return to the Lodge","lodge.php");
}
page_footer();
}
?>