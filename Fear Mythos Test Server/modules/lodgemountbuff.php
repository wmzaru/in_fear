<?php
function lodgemountbuff_getmoduleinfo(){
	$info = array(
		"name" => "Extended Mount Buff Rounds",
		"author" => "Iori, using Extra Forest Fights as a template",
		"version" => "1.01",
		"category" => "Lodge",
		"download" => "",
		"settings" => array(
			"Extended Mount Buff Rounds Module Settings,title",
			"points" => "How many points per purchase?,int|200",
			"percent" => "percentage increase in buff rounds,range,5,200,5|50",
			"length" => "How many game days do purchase last for?,int|20",
		),
		"prefs" => array(
			"Extended Mount Buff Rounds User Preferences,title",
			"left" => "Days left on purchase,int",
		),
	);
	return $info;
}
function lodgemountbuff_install(){
	module_addhook("lodge");
	module_addhook("pointsdesc");
	module_addhook("modify-buff");
	module_addhook("newday");
	return true;
}
function lodgemountbuff_uninstall(){
	return true;
}
function lodgemountbuff_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "modify-buff":
		if ($args['buff']['rounds'] < 0) break;
		if ($args['name'] == "mount") {
			if (get_module_pref("left") > 0) {
				$args['buff']['rounds'] += ceil($args['buff']['rounds'] * get_module_setting("percent")/100);
			}
		}
		break;
	case "newday":
		$left = get_module_pref("left");
		if ($left > 1) {
			$remain = $left - 1;
			increment_module_pref("left",-1);
			output("You have `^%s`@ day%s left on your mount buff rounds purchase.`n", $remain, ($remain==1?"":"s"));
		} elseif ($left == 1) {
			increment_module_pref("left",-1);
			output("This purchase has expired.`n");
		}
		break;
	case "pointsdesc":
		$args['count']++;
		$format = $args['format'];
		$str = translate("Giving your mount a %s percent longer buff for %s days costs %s points. The bonus starts the game day after you purchase them.");
		$str = sprintf($str, get_module_setting("percent"), get_module_setting("length"), get_module_setting("points"));
		output($format, $str, true);
		break;
	case "lodge":
		$cost = get_module_setting("points");
		addnav(array("Extended Mount Buff Rounds (%s points)", $cost), "runmodule.php?module=lodgemountbuff&op=buy");
		break;
	}
	return $args;
}
function lodgemountbuff_run(){
	global $session;
	$cost = get_module_setting("points");
	$left = get_module_pref("left");
	$percent = get_module_setting("percent");
	$length = get_module_setting("length");
	$op = httpget("op");
	page_header("Hunter's Lodge");
	addnav("Lodge");
	addnav("L?Return to the Lodge","lodge.php");
	if ($op=="buy"){
		output("`7J. C. Petersen looks at you carefully, \"`&Purchasing extra mount buff rounds will cost you %s points and will provide your mount with a %s percent longer buff each time its buff is refreshed, for the next %s game days you log on.`7\"`n`n", $cost, $percent, $length);
		if ($left > 0) {
			output("`7He flips through a small book for a moment. \"`&Ah yes, here we go. There are currently %s days left on your purchase.`7\"`n`n",$left);
		} else {
			$pointsavailable=$session['user']['donation'] - $session['user']['donationspent'];
			if ($pointsavailable < $cost) {
				output("`7He then smiles regretfully, \"`&I'm sorry, but purchasing extended mount buff rounds costs %s points, which you do not seem to have.`7\"`n", $cost);
			} else {
				output("`7\"`&Are you sure you wish to spend %s points on extended mount buff rounds?`7\" he asks.`n", $cost);
				addnav("Buy Extended Mount Buffs");
				addnav("Yes", "runmodule.php?module=lodgemountbuff&op=confirm");
				addnav("No", "lodge.php");
			}
		}
	} elseif ($op=="confirm") {
		$session['user']['donationspent'] += $cost;
		set_module_pref("left", $length);
		output("`7J. C. Petersen nods and hopes you enjoy your extended mount buff rounds.");
	}
	page_footer();
}
?>