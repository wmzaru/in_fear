<?php

function specialtyelementalistskills_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Elementalist Skills",
		"author" => "Billie Kennedy, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Elementalist Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|38",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|58",
			"cost"=>"Cost of Specialty in Lodge Points,int|100",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|12",
		),
		"prefs" => array(
			"Specialty - Elementalist Skills User Prefs,title",
			"skill"=>"Skill points in Elementalist Skills,int|0",
			"uses"=>"Uses of Elementalist Skills allowed,int|0",
			"bought"=>"Has Arcane Runes Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtyelementalistskills_install(){
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

function specialtyelementalistskills_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='EL'";
	db_query($sql);
	return true;
}

function specialtyelementalistskills_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "EL";
	$name = "Elementalist Skills";
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
		$str = translate("The Elementalist Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Playing with the different elemnts of the earth");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Elementalist Skills (%s points)",$cost),
					"runmodule.php?module=specialtyselementalist&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`3Growing up, you remember diging holes in the ground, swimming, starting fires and trying to fly like a bird.");
			output("You learned the basics of the elements of the earth.  This stuff was really facinating.  Realizing that if you could just harness these elements you could use them as weapons.  Time to train.");
			output("Over time, you began to control the ground, you could shape water, create powerful gusts of wind and create fire where others couldn't.");
			output("To your delight, it could also be used as a weapon against your foes.");
		}
		break;

	case "specialtycolor":
		$args[$spec] = $ccode;
		break;

	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
		$args[$spec] = "specialtyelementalistskills";
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
			addnav(array("$ccode$name (%s points)`0", $uses),"");
			addnav(array("$ccode &#149; Air Elemental`7 (%s)`0", 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Water Elemental`7 (%s)`0", 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Earth Elemental`7 (%s)`0", 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Fire Elemental`7 (%s)`0", 5),$script."op=fight&skill=$spec&l=5",true);
		}
		break;

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('el1',array(
						"startmsg"=>"`^You Summon an Air Elemental to create a gale force wind.",
						"name"=>"`^Wind",
						"rounds"=>5,
						"wearoff"=>"Your victim is no longer held at bay by the wind.",
						"roundmsg"=>"{badguy} is pushed back by the wind and can't fight as well.",
						"badguyatkmod"=>0.7,
						"schema"=>"specialtyelementalistskills"
					));
					break;
				case 2:
					apply_buff('el2',array(
						"startmsg"=>"`^You Summon a Water Elemental to create a large pool of water under {badguy}.",
						"name"=>"`^Pool",
						"rounds"=>5,
						"wearoff"=>"Your victim struggles above the water.",
						"atkmod"=>2,
						"roundmsg"=>"{badguy} doesn't realize they can get up out of the water.  Allowing you a better attack!", 
						"schema"=>"specialtyelementalistskills"
					));
					break;
				case 3:
					apply_buff('el3', array(
						"startmsg"=>"`^Several small stone golems rise from the ground and begin to throw stones at {badguy}.",
						"name"=>"`^Stone golems",
						"rounds"=>5,
						"wearoff"=>"The golems crumble into dust.",
						"minioncount"=>round($session['user']['level']/2.5)+1,
						"maxbadguydamage"=>round($session['user']['level']/1.8,0)+1,
						"effectmsg"=>"`)A golem throws a stone and hits {badguy}`) for `^{damage}`) damage.",
						"effectnodmgmsg"=>"`)A golem throws a stone and tries to hit {badguy}`) but `\$MISSES`)!",
						"schema"=>"specialtyelementalistskills"
					));
					break;
				case 5:
					apply_buff('el5',array(
						"startmsg"=>"`^Concentrating deeply, you summon a small flame in the palm of your hand.  It jumps from your hand and quickly increases in size. The flame leaps and envelopes {badguy}!",
                        "name"=>"`^Fire Elemental",
						"rounds"=>5,
						"wearoff"=>"Smoke rises from your victim as the elemental disapates!",
						"minioncount"=>1,
						"maxbadguydamage"=>round($session['user']['attack']*3.5,0),
						"minbadguydamage"=>round($session['user']['attack']*1.75,0),
						"roundmsg"=>"{badguy} is envolped in flames as the fire elemental surrounds them!",
						"schema"=>"specialtyelementalistskills"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('el0', array(
					"startmsg"=>"You try to summon an elemental friend but end up only making a mud pie.  {badguy} has a good chuckle watching you.",
					"rounds"=>1,
					"schema"=>"specialtyelementalistskills"
				));
			}
		}
		break;
	}
	return $args;
}
function specialtyelementalistskills_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'EL';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Elementalist Skills`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtyelementalistskills&op=yes");
				addnav("No","runmodule.php?module=specialtyelementalistskills&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Arcane Runes`\$.");
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