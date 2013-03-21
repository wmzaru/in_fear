<?php

function specialtymagician_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Magician",
		"author" => "Crazed Lady, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Magician Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|18",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|38",
			"cost"=>"Cost of Specialty in Lodge Points,int|50",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|8",
		),
		"prefs" => array(
			"Specialty - Magician User Prefs,title",
			"skill"=>"Skill points in Magician,int|0",
			"uses"=>"Uses of Magician allowed,int|0",
			"bought"=>"Has Magician Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtymagician_install(){
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

function specialtymagician_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='MP'";
	db_query($sql);
	return true;
}

function specialtymagician_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "MA";
	$name = "Magician";
	$ccode = "`#";
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
		$str = translate("The Magician Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;	
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Using your magician skills");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Arcane Runes (%s points)",$cost),
					"runmodule.php?module=specialtyarcanerunes&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`3Wanting to follow in your parents footsteps, you feel it is time to take out your magic hat and use it.");
		}
		break;

	case "specialtycolor":
		$args[$spec] = $ccode;
		break;

	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
		$args[$spec] = "specialtymagician";
		break;

	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$name = translate_inline($name);
			$c = $args['color'];
			output("`n%sYou gain a level in `&%s%s to `#%s%s!",$c, $name, $c, $new, $c);
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
			if ($bonus == 1) {
				output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode,$name,$ccode,$name);
			} else {
				output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode,$name,$bonus,$ccode,$name);
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
			addnav(array("$ccode2$name (%s points)`0", $uses), "");
			addnav(array("e?$ccode2 &#149; `#Quick Heal`7 (%s)`0", 1),$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode2 &#149; `#Cheap Trick`7 (%s)`0", 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode2 &#149; `#Rabbit Pummel`7 (%s)`0", 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("g?$ccode2 &#149; `#Pet Thumper`7 (%s)`0", 5),$script."op=fight&skill=$spec&l=5",true);
		}
		break;

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('mp1', array(
						"startmsg"=>"`^You start to heal quickly!",
						"name"=>"`#Quick Heal",
						"rounds"=>4,
						"wearoff"=>"Your healing trickery has stopped!",
						"regen"=>$session['user']['level'],
						"effectmsg"=>"You heal yourself for {damage}.",
						"effectnodmgmsg"=>"You have no more wounds to heal.",
						"schema"=>"specialtymagician"
					));
					break;
				case 2:
					apply_buff('mp2', array(
						"startmsg"=>"`^You pull a cheap trick!",
						"name"=>"`#Cheap Trick",
						"rounds"=>5,
						"wearoff"=>"Your cheap trick ends with a bang.",
						"minioncount"=>1,
						"effectmsg"=>"Your cheap trick hurts {badguy} for `^{damage}`) points.",
						"minbadguydamage"=>1,
						"maxbadguydamage"=>$session['user']['level']*3,
						"schema"=>"specialtymagician"
					));
					break;
				case 3:
					apply_buff('mp3', array(
						"startmsg"=>"`^You pull a rabbit out of your hat.",
						"name"=>"`#Rabbit Pummel",
						"rounds"=>6,
						"wearoff"=>"You place your rabbit back in your hat.",
						"minioncount"=>3,
						"effectmsg"=>"Your rabbit pummels {badguy} for {damage} damage.",
						"effectfailmsg"=>"Your rabbit misses {badguy}.",
						"schema"=>"specialtymagician"
					));
					break;
				case 5:
					apply_buff('mp5', array(
						"startmsg"=>"`^You hear the rumble of your pet rabbit in the distance",
						"name"=>"`#Pet Thumper",
						"rounds"=>7,
						"wearoff"=>"Your pet bounces back into the forest.",
						"damageshield"=>2,
						"effectmsg"=>"Your rabbit over shadows {badguy} and scared them for `^{damage}`) damage.",
						"effectnodmg"=>"{badguy} is not intimidated by the rabbit.",
						"effectfailmsg"=>"{badguy} just laughs at the attempts of your rabbit.",
						"schema"=>"specialtymagician"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('mp0', array(
					"startmsg"=>"You call on your trusty sidekick rabbits to aid you. {badguy} laughs at them before he starts to lunge at you again.",
					"rounds"=>1,
					"schema"=>"specialtymagician"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtymagician_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'MA';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Magician`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtymagician&op=yes");
				addnav("No","runmodule.php?module=specialtymagician&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Magician`\$.");
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