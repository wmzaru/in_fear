<?php

function specialtycleric_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Cleric",
		"author" => "`!Enderandrew, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Cleric Settings,title",
			"mindk"=>"How many DKs do you need before the specialty is available?,int|12",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|32",
			"cost"=>"Cost of Specialty in Lodge Points,int|50",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|8",
		),
		"prefs" => array(
			"Specialty - Cleric User Prefs,title",
			"skill"=>"Skill points in Cleric Spells,int|0",
			"uses"=>"Uses of Cleric Spells allowed,int|0",
			"bought"=>"Has Cleric Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtycleric_install(){
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

function specialtycleric_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='CL'";
	db_query($sql);
	return true;
}

function specialtycleric_dohook($hookname,$afis){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "CL";
	$name = "Cleric Spells";
	$ccode = "`1";
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
		$afis['count']++;
		$format = $afis['format'];
		$dks=get_module_setting("dklast");
		$str = translate("The Cleric Specialty is availiable for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Going to Seminary school");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Cleric (%s points)",$cost),
					"runmodule.php?module=specialtycleric&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`1These have been dark times to grow up as a child, what with `@Dragons`1 running about and all. ");
			output("Many grabbed weapons, and trained themselves to defend themselves and others.  They often ");
			output("did so out of fear for their own life.  The `@Dragon`1 is mighty, and many have fallen prey to ");
			output("the `@Dragon`1's power.  For those with no Faith, fear of death is quite natural.  You, well ");
			output("you were different.  Everyone could sense a certain internal strength about you.  You have ");
			output("Faith, and that Faith will not only guide you, but it will save this land from the `@Dragon`2.`n`n");
			output("Well, you were also a rotten kid that acted very naughty, so maybe you're trying to earn some ");
			output("brownie points with your deity now...`n`n");
		}
		break;

	case "specialtycolor":
		$afis[$spec] = $ccode;
		break;

	case "specialtynames":
		$afis[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
		$afis[$spec] = "specialtycleric";
		break;

	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$name = translate_inline($name);
			$c = $afis['color'];
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
		if ($session['user']['specialty'] == $spec) $amt++;
		set_module_pref("uses", $amt);
		break;
	
	case "fightnav-specialties":
		$uses = get_module_pref("uses");
		$script = $afis['script'];
		if ($uses > 0) {
			addnav(array("%s%s (%s points)`0", $ccode, $name, $uses), "");
			addnav(array("%s &#149; Cure Light Wounds`7 (%s)`0", $ccode, 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("%s &#149; Hold Person`7 (%s)`0", $ccode, 2), $script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("%s &#149; Searing Light`7 (%s)`0", $ccode, 3), $script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("%s &#149; Regenerate`7 (%s)`0", $ccode, 5), $script."op=fight&skill=$spec&l=5",true);
		}
		break;
	
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('cl1', array(
						"startmsg"=>"`1You call upon your deity's powers and cast `!Cure Light Wounds`1.",
						"name"=>"`!Cure Light Wounds",
						"rounds"=>5,
						"wearoff"=>"Your spell begins to wear off!",
						"regen"=>$session['user']['level'],
						"effectmsg"=>"You cast Cure Light Wounds and cure {damage} health.",
						"effectnodmgmsg"=>"You have no wounds to heal.",
						"schema"=>"specialtycleric"
					));
					break;
				case 2:
					apply_buff('cl2', array(
						"startmsg"=>"`1You grip your holy symbol tight and outstretch a palm towards {badguy} as you cast `!Hold Person`1!",
						"name"=>"`!Hold Person",
						"rounds"=>5,
						"wearoff"=>"{badguy} wriggles free of the spell's effects.",
						"badguyatkmod"=>0.4,
						"schema"=>"specialtycleric"
					));
					break;
				case 3:
					apply_buff('cl3', array(
						"startmsg"=>"`1You shove your holy symbol in {badguy}'s face and blast them with divine fury as you cast `!Searing Light`1!",
						"name"=>"`!Searing Light",
						"rounds"=>5,
						"wearoff"=>"Your spiritual reserves are tapped.",
						"effectmsg"=>"`!Burning, divine light blasts {badguy} for {damage} damage.",
						"effectnodmgmsg"=>"`!Your beam of divine light blasts right past {badguy}, missing completely!",
						"atkmod"=>2.5,
						"schema"=>"specialtycleric"
					));
					break;
				case 5:
					apply_buff('cl5', array(
						"startmsg"=>"`1Your god shines down brightly on you, allowing you to heal the most serious of wounds.",
						"name"=>"`!Regeneration",
						"rounds"=>5,
						"wearoff"=>"You have stopped regenerating",
						"regen"=>round($session['user']['level']*3.5),
						"effectmsg"=>"Divine strength fills you, healing {damage} health.",
						"effectnodmgmsg"=>"You have no wounds to regenerate.",
						"schema"=>"specialtycleric"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('cl0', array(
					"startmsg"=>"`!You reach for your holy symbol, but seem to have misplaced it.",
					"rounds"=>1,
					"schema"=>"specialtycleric"
				));
			}
		}
		break;
	}
	return $afis;
}
function specialtycleric_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'CL';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Cleric`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtycleric&op=yes");
				addnav("No","runmodule.php?module=specialtycleric&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Cleric`\$.");
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