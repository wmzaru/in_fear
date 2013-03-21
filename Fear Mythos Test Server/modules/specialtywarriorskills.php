<?php

function specialtywarriorskills_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Warrior Skills",
		"author" => "Daniel Cannon, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Warrior Skills Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|1",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|21",
			"cost"=>"Cost of Specialty in Lodge Points,int|25",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|5",
		),
		"prefs" => array(
			"Specialty - Warrior Skills User Prefs,title",
			"skill"=>"Skill points in Warrior Skills,int|0",
			"uses"=>"Uses of Warrior Skills allowed,int|0",
			"bought"=>"Has Warrior Skills Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtywarriorskills_install(){
	module_addhook("choose-specialty");
	module_addhook("set-specialty");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addhook("incrementspecialty");
	module_addhook("specialtynames");
	module_addhook("specialtycolor");
	module_addhook("specialtymodules");
	module_addhook("dragonkill");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	return true;
}

function specialtywarriorskills_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='WS'";
	db_query($sql);
	return true;
}

function specialtywarriorskills_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "WS";
	$name = "Warrior Skills";
	$ccode = "`^";
	$bought = get_module_pref("bought");
	$cost = get_module_setting("cost");
	switch ($hookname) {

	case "dragonkill":
		if ($bought==1) {
			increment_module_pref("dksince",1);
			if (get_module_pref("dksince")>=get_module_setting("dklast")){
				set_module_pref("bought",0);
				set_module_pref("dksince",0);
			}
		}
		set_module_pref("uses", 0);
		set_module_pref("skill", 0);
		break;

	case "pointsdesc":
		$args['count']++;
		$format = $args['format'];
		$str = translate("The Warrior SKills is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=$spec$resline");
			$t1 = translate_inline("Killing a lot of nasty and venomous creatures.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Warrior Skills (%s points)",$cost),
					"runmodule.php?module=specialtywarriorskills&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`5Growing up, you recall killing many nasty and venomous creatures, insisting that they were plotting against you.");
			output("Your parents, concerned that you had taken to killing the creatures barehanded, bought you your very first pointy twig.");
			output("It wasn't until your teenage years that you began performing dark rituals with the creatures, disappearing into the forest for days on end, no one quite knowing where those sounds came from.");
		}
		break;

	case "specialtycolor":
		$args[$spec] = $ccode;
		break;

	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
		$args[$spec] = "specialtywarriorskills";
		break;

	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$name = translate_inline($name);
			$c = $args['color'];
			output("`n%sYou gain a level in `&%s%s to `#%s%s!", $c, $name, $c, $new, $c);
			$x = $new % 3;
			if ($x == 0){
				output("`n`^You gain an extra use point!`n");
				set_module_pref("uses", get_module_pref("uses") + 1);
			}else{
				if (3-$x == 1) {
					output("`n`^Only 1 more skill level until you gain an extra use point!`n");
				} else {
					output("`n`^Only %s more skill levels until you gain an extra use point!`n", (3-$x));
				}
			}
			output_notl("`0");
		}
		break;

	case "newday":
		$bonus = getsetting("specialtybonus", 1);
		if($session['user']['specialty'] == $spec) {
			$name = translate_inline($name);
			if ($bonus == 2) {
				output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode, $name, $ccode, $name);
			} else {
				output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode, $name,$bonus, $ccode,$name);
			}
		}
		$amt = (int)(get_module_pref("skill") / 3);
		if ($session['user']['specialty'] == $spec) $amt = $amt + $bonus;
		set_module_pref("uses", $amt);
		break;

	case "fightnav-specialties":
		$uses = get_module_pref("uses");
		$script = $args['script'];
		if ($uses > 0) {
			addnav(array("$ccode$name (%s points)`0", $uses),"");
			addnav(array("$ccode &#149; Skeleton Crew`7 (%s)`0", 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Voodoo`7 (%s)`0", 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Curse Spirit`7 (%s)`0", 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Wither Soul`7 (%s)`0", 5),$script."op=fight&skill=$spec&l=5",true);
		}
		break;

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('da1',array(
						"startmsg"=>"`\$You call on the Gods of Klingdum, and huge metal blades claw at {badguy} from thin air.",
						"name"=>"`\$Metal Blades",
						"rounds"=>5,
						"wearoff"=>"Your huge metal blades vanish into thin air.",
						"minioncount"=>round($session['user']['level']/3)+1,
						"maxbadguydamage"=>round($session['user']['level']/2,0)+1,
						"effectmsg"=>"`)An blade hits {badguy}`) for `^{damage}`) damage.",
						"effectnodmgmsg"=>"`)An blade tries to hit {badguy}`) but `\$MISSES`)!",
						"schema"=>"specialtywarriorskills"
					));
					break;
				case 2:
					apply_buff('da2',array(
						"startmsg"=>"`\$You pull out a big shiny red button which has a picture of {badguy}'s face on it.",
						"effectmsg"=>"You press the red button and {badguy} gets hurt for `^{damage}`) points!",
						"minioncount"=>1,
						"maxbadguydamage"=>round($session['user']['attack']*3,0),
						"minbadguydamage"=>round($session['user']['attack']*1.5,0),
						"schema"=>"specialtywarriorskills"
					));
					break;
				case 3:
					apply_buff('da3',array(
						"startmsg"=>"`\$You go back in time and blow up {badguy}'s ancestors.",
						"name"=>"`\$Time Machine",
						"rounds"=>5,
						"wearoff"=>"Your enemy's family does not die as planned.",
						"badguydmgmod"=>0.5,
						"roundmsg"=>"{badguy} starts to fade and then reappears again, and deals only half damage.",
						"schema"=>"specialtywarriorskills"
					));
					break;
				case 5:
					apply_buff('da5',array(
						"startmsg"=>"`\$You remove from your pocket a mini marshmallow and flick it straight into {badguy}'s mouth and {badguy} begins to choke.",
						"name"=>"`\$Choking",
						"rounds"=>5,
						"wearoff"=>"Your victim vomits on the ground and the mini marshmallow flys out from it mouth.",
						"badguyatkmod"=>0,
						"badguydefmod"=>0,
						"roundmsg"=>"{badguy} claws at its throat, trying to breathe properly, and cannot attack or defend.",
						"schema"=>"specialtywarriorskills"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('da0', array(
					"startmsg"=>"Exhausted, you try your darkest tactical manouvre, a staring contest.  {badguy} looks at you for a minute and finally blinks.  Eyes watering, it swings at you again.",
					"rounds"=>1,
					"schema"=>"specialtywarriorskills"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtywarriorskills_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'WS';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Warrior Skills`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtywarriorskills&op=yes");
				addnav("No","runmodule.php?module=specialtywarriroskills&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Warrior Skills`\$.");
			output("Now, drink it all up!`3\"`n`n");
			output("You double over, spasming on the ground.");
			output("J. C. Petersen grins, \"`\$That's a powerful change you just went through. I suggest you rest.`3\"");
			$session['user']['specialty']=$spec;
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