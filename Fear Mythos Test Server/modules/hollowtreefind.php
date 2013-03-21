<?php
function hollowtreefind_getmoduleinfo() {
	$info = array (
		"name"=>"Hollow Tree Finds",
		"version"=>"1.0",
		"author"=>"Jeffrey Riddle, fixes by `%Tela`0",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=156",
		"settings"=>array (
			"Hollow Tree Finds Event Settings,title",
			"gemsgain"=>"Chance to gain a gem (otherwise lose turn or gain gold),range,0,100,1|25",
			"gemsget"=>"Amount of gems to recieve on event,range,1,25,1|3",
			"turnlose"=>"Amount of turns to lose on event,range,1,5,1|2",
			"mingold"=>"Minimum gold to find (multiplied by level),range,0,50,1|10",
			"maxgold"=>"Maximum gold to find (multiplied by level),range,20,150,1|50"
		),
	);
	return $info;
}

function hollowtreefind_install() {
	module_addeventhook("forest", "return 100;");
	return true;
}

function hollowtreefind_uninstall() {
	return true;
}

function hollowtreefind_dohook($hookname,$args) {
	return $args;
}

function hollowtreefind_runevent($type) {
	global $session;
	$chance     = get_module_setting("gemsgain");
	$gemsget    = get_module_setting("gemsget");
	$roll       = e_rand(1, 100);
	$roll2      = e_rand(1, 100);
	$gem1       = translate_inline("gem");
	$gem2       = translate_inline("gems");
	$turnlose   = get_module_setting("turnlose");
	$turn1      = translate_inline("turn");
	$turn2      = translate_inline("turns");
	if ($roll <= $chance) {
		output("`^You discover a hole in a hollow tree.");
		output("As you feel around the tree, you touch something hard.`n`n`n");
		output("You pull your hand out to discover...");
		output("You are holding %s shiny `%%s!`0", $gemsget, $gemsget==1?$gem1:$gem2);
		$session['user']['gems']+=$gemsget;
	}
	elseif ($roll > $chance
	&&      $roll2 <= 50) {
		output("`^Walking along a path in the forest, you come upon a hollow tree.");
		output("Curious as to what might be in it, you decide to stick your hand inside the hole in the tree.`n`n");
		output("`\$Suddenly you hear a hiss, and realize a poisonous snake is in the tree!");
		output("`^The snake bites your hand before you pull it out, and sinks its venom into you.");
		output("You suddenly collapse to the forest ground, asleep. Lucky for you, another adventurer seems to have killed the snake before it killed you, as it is nowhere to be found when you awake.`n`n");
    if ($session['user']['turns'] < $turnlose) {
      output("You have lost all of your remaining `^turns!`0");
      $session['user']['turns']==0;
    }
    else
    output("You lose %s `^%s!`0", $turnlose, $turnlose==1?$turn1:$turn2);
		$session['user']['turns']-=$turnlose;
	}
	elseif ($roll > $chance
	&&    $roll2 >= 51) {
	$min = $session['user']['level']*get_module_setting("mingold");
	$max = $session['user']['level']*get_module_setting("maxgold");
	$gold = e_rand($min, $max);
	output("`^Walking along in the forest, you come upon a hollow tree. Curious to what is in it, you reach inside. You hear the clanking of something metal. You pull your hand out to discover...%s gold!`0", $gold);
	$session['user']['gold']+=$gold;
}
//Generic outcome just in case something goes wrong//
    else
    output("`^The strange encounter is over, and you're not quite sure what just happened.");
}
function hollowtreefind_run() {
}
?>