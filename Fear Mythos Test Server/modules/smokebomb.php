<?php

// Thanks to Christian Rutsch for some help

function smokebomb_getmoduleinfo(){
	$info = array(
		"name"=>"Smoke Bomb",
		"version"=>"1.02",
		"author"=>"Enhas, based on racestorm by Chris Vorndran",
		"category"=>"Lodge",
		"download"=>"http://dragonprime.net/users/Enhas/smokebomb.txt",
		"requires"=>array(
			"thieves"=>"1.7|By Shawn Strider, part of the core distribution",
		),
		"settings"=>array(
			"Smoke Bomb Settings,title",
			"cost"=>"Smoke Bomb cost in Lodge / Donation points,int|100",
			"chancefail"=>"Chance that the Smoke Bomb will not work,range,0,100,1|20",
		),
		"prefs"=>array(
			"Smoke Bomb Preferences,title",
			"hasbomb"=>"How many Smoke Bombs does this player have,int|0",
		)
	);
	return $info;
}

function smokebomb_install(){
	module_addhook("pointsdesc");
	module_addhook("lodge");
	module_addhook("footer-forest");
	return true;
}

function smokebomb_uninstall(){
	return true;
}

function smokebomb_dohook($hookname,$args){
	global $session;
	$cost = get_module_setting("cost");
	$hasbomb= get_module_pref("hasbomb");
        $op = httpget('op');
        $elffriend = get_module_setting("elffriend", "thieves");

	switch($hookname){
	case "pointsdesc":                
		$args['count']++;
		$format = $args['format'];
		$str = translate("A Smoke Bomb, which may aid you in creating a diversion.  This costs %s points.");
		$str = sprintf($str, $cost);
		output($format, $str, true);
		break;
	case "lodge":          
		addnav(array("Buy a Smoke Bomb (%s points)",$cost), "runmodule.php?module=smokebomb&op=start");
		break;
	case "footer-forest":
		// footer-forest is a very resource-intensive hook, let's exit as soon as possible
		// So, if the special isn't running: No need to be here!
		// If we have no bombs: No need to be here!
		// if we are already inside the module: Ne need to be here!
		if ($session['user']['specialinc'] != "module:thieves") break;
            if ($session['user']['race'] == "Elf" && $elffriend==1) break;
		if ($hasbomb <= 0) break;
		if ($op=="" || $op=="search") {
		output("`n`nYou remember purchasing a Smoke Bomb earlier, but you are unsure if it will help you in this situation.`0");
		addnav("Throw a Smoke Bomb","runmodule.php?module=smokebomb&op=throwbomb");
            }
		break;
	}
	return $args;
}

function smokebomb_run(){
	global $session;
	$cost = get_module_setting("cost");
	$hasbomb = get_module_pref("hasbomb");
	$op = httpget('op');

	switch ($op){
		case "start":
			page_header("Hunter's Lodge");
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost){
				output("`7J. C. Petersen motions you over to a small shelf along a log wall.  Atop a shelf are many small, grey metal balls.. he reaches up to a shiny one in particular and holds it for you to see.`n`n");
				output("`\$This is a `7Smoke Bomb`\$.  I have an excess supply of these.. when used they will cause a smokescreen, which may help create a diversion.  Do you wish to purchase one?");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=smokebomb&op=yes");
				addnav("No","runmodule.php?module=smokebomb&op=no");
			} else {
				output("`7J. C. Petersen sneers and tells you to come back with more Lodge Points before attempting to buy this item.`0");
                        addnav("Return");
			addnav("L?Return to the Lodge","lodge.php");
			}
			break;
		case "yes":
			page_header("Hunter's Lodge");
			output("`7J. C. Petersen grins, and places the Smoke Bomb into your hand.`n`n");
			output("He says, `\$'Come back if.. I mean when you need more!'`0");
			$session['user']['donationspent'] += $cost;
			$hasbomb++;
			set_module_pref("hasbomb",$hasbomb);
			addnav("Return");
			addnav("L?Return to the Lodge","lodge.php");
			break;
		case "no":
			page_header("Hunter's Lodge");
			output("`7J. C. Petersen places the Smoke Bomb back atop the shelf.`n`n");
			output("`\$As you wish, but didn't you admire the craftsmanship?`Q");
			addnav("Return");
			addnav("L?Return to the Lodge","lodge.php");
			break;
		case "throwbomb":
                        page_header("Smoke Bomb");
			$chancefail = get_module_setting("chancefail");
			output("`6Quickly reaching into a pocket, you pull out a `7Smoke Bomb`6 and throw it at the feet of the thieves!`n`n");
			$hasbomb--;
			set_module_pref("hasbomb",$hasbomb);
			debuglog("threw a Smoke Bomb to try and escape Lonestrider's Thieves");
			if (e_rand(1,100) > $chancefail) {
				output("`6A thick `7smoke`6 quickly encompases the area, and you hear the coughs of `\$Lonestrider`6 and his men as they try to make their way through to get you.`n`n");
				output("`^Not willing to stay here a second longer, you quickly make your escape!`0");
				$session['user']['specialinc']="";
				addnav("Back to the Forest", "forest.php");
			}else{
				output("`6Nothing happens, and the thieves laugh at your feeble effort!  They then swiftly surround you from all sides, and you have no choice but to fight!`0");
				addnav("Stand and Fight!","forest.php?op=stand");
			}
			break;
	}
	page_footer();
}	
?>