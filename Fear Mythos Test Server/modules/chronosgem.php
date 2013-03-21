<?php

// Heavily modified from base Storm Giant module code

function chronosgem_getmoduleinfo(){
	$info = array(
		"name"=>"Chronos Gem",
		"version"=>"1.01",
		"author"=>"Enhas, based on racestorm by Chris Vorndran",
		"category"=>"Lodge",
		"download"=>"http://dragonprime.net/users/Enhas/chronosgem.txt",
		"settings"=>array(
			"Chronos Gem Settings,title",
			"cost"=>"Chronos Gem cost in Lodge / Donation points,int|175",
		),
		"prefs"=>array(
			"Chronos Gem Preferences,title",
			"hasgem"=>"How many Chronos Gems does this player have,int|0",
		)
	);
	return $info;
}

function chronosgem_install(){
	module_addhook("village");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	return true;
}

function chronosgem_uninstall(){
	return true;
}

function chronosgem_dohook($hookname,$args){
	global $session;
	$cost = get_module_setting("cost");
      $hasgem = get_module_pref("hasgem");
	switch($hookname){
	case "pointsdesc":
		$args['count']++;
		$format = $args['format'];
		$str = translate("A Chronos Gem, which when used grants a New Day to the player.  This costs %s points.");
		$str = sprintf($str, $cost);
		output($format, $str, true);
		break;
	case "lodge":
			addnav(array("Buy a Chronos Gem (%s points)",$cost),
					"runmodule.php?module=chronosgem&op=start");
		break;
	case "village":
		if ($hasgem >= 1) {
                  tlschema($args['schemas']['othernav']);
			addnav($args['othernav']);
			tlschema();
			addnav("U?Use a Chronos Gem","runmodule.php?module=chronosgem&op=usegem");
		}
		break;
	}

	return $args;
}

function chronosgem_run(){
	global $session;
	page_header("Hunter's Lodge");
	$cost = get_module_setting("cost");
      $hasgem = get_module_pref("hasgem");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost){
				output("`^J. C. Petersen reaches up and blows the thick layer of dust off a small, silver box.  Within the box are tiny rubies outlined with silver..`n`n");
				output("`\$These are `^Chronos Gems`\$.  When held to the light of the sun, they bring a day anew to the bearer.  Do you wish to purchase one?");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=chronosgem&op=yes");
				addnav("No","runmodule.php?module=chronosgem&op=no");
			} else {
				output("`^J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
                        addnav("Return");
	                  addnav("L?Return to the Lodge","lodge.php");
			}
			break;
		case "yes":
			output("`^J. C. Petersen smiles, and drops one of the gems into your hand.`n`n");
			output("`^You place the gem into a pocket, making sure not to forget that it is there.`n`n");
			output("J. C. Petersen tells you, `\$'When using the `^Chronos Gem`\$, you get all the benefits of a newday.. use it wisely!'");
			$session['user']['donationspent'] += $cost;
                  $hasgem++;
			set_module_pref("hasgem",$hasgem);
                  addnav("Return");
	            addnav("L?Return to the Lodge","lodge.php");
			break;
		case "no":
			output("`^J. C. Petersen glances up at you from the box.`n`n");
			output("`\$This is no ordinary gem.. much more valuable than others.  You would be wise to purchase one at some point.`^");
                  addnav("Return");
	            addnav("L?Return to the Lodge","lodge.php");
			break;
           case "usegem":
                  page_header("Chronos Gem");
                  $hasgem--;
                  set_module_pref("hasgem",$hasgem);
                  $location = $session['user']['location'];
                  debuglog("Used a Chronos Gem.");
                  output("`n`^Lifting the ruby gem into the vast light of the sun, it begins to glow and warm in your hand!  ");
                  output("The surrounding area begins to grow brighter and brighter, until you cannot bear it anymore! You drop the gem and raise your hand to cover your eyes..`n`n");
                  output("A while later, the light dies down, and you are able to unshield your eyes.  Looking around you, you notice the sun above %s is not in quite the same position as before..`0", $location);
                  addnav("C?Continue","newday.php");
                  break;
	}
	page_footer();
}	
?>