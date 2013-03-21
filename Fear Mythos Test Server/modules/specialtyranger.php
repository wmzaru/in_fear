<?php

function specialtyranger_getmoduleinfo()  {
	$info = array(
		"name"=>"Specialty - Ranger",
		"author"=>"`@CortalUX `&-`# Buffs fixed by XChrisX, DaveS Modifications",
		"version"=>"5.0",
		"category"=>"Specialties",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Specialty - Ranger Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|4",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|24",
			"cost"=>"Cost of Specialty in Lodge Points,int|25",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|5",
		),
		"prefs"=>array(
			"Specialty - Ranger User Prefs,title",
			"skill"=>"Skill points in Ranger Senses,int|0",
			"uses"=>"Uses of Ranger Senses allowed,int|0",
			"bought"=>"Has Ranger Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtyranger_install(){
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

function specialtyranger_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='RS'";
	db_query($sql);
	return true;
}

function specialtyranger_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");
	
	$spec = "RS";
	$name = "Ranger Senses";
	$ccode = "`@";
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
		$str = translate("The Ranger Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;	
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Living in tune with nature");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Ranger (%s points)",$cost),
					"runmodule.php?module=specialtyranger&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`@Your `&mind`@ was covered with a great `7cloud`@ until you matured.");
			output("You seemed to `!awake`@ one day, and you looked around you, to see Wolven brethren looking solemnly at you.");
			output("You lived for a time among them, but your heart yearned for more, so you went to search for your own kind, but upon meeting them, you repudiated them, for they were fighting with nature and this alarmed you.");
			output("You set yourself apart, and became `%other`@. ");
			output("`^A Ranger.`@ Running to a secluded part of a nearby forest, you wept into a grassy meadow, and gasped as others like you walked up, and howled like wolves.");
			output("From then onwards, you all hunted as one, and you learnt the ways of the Ranger.`n`n");
			output("`@You `^lost `\$rancor`@ for your `%blood`@ kind, and decided that if you could not accept the world, you would change it.");
			output("Into a hidden, pure glade you wandered, stepping through a mighty waterfall, to enter the world once more...");
		}
		break;

	case "specialtycolor":
		$args[$spec] = $ccode;
		break;

	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
		$args[$spec] = "specialtyranger";
		break;

	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$specialties = modulehook("specialtynames",	array(""=>translate_inline("Unspecified")));
			$c = $args['color'];
			output("`n%sYou gain a level in `&%s%s to `#%s%s!",$c, $specialties[$session['user']['specialty']], $c, $new, $c);
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
				output("`n`3For being interested in %s%s`3, you gain `^1`3 extra use of `&%s%s`3 for today.`n",$ccode,$name,$ccode,$name);
			}else{
				output("`n`3For being interested in %s%s`3, you gain `^%s`3 extra uses of `&%s%s`3 for today.`n",$ccode,$name,$bonus,$ccode,$name);
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
			addnav(array("%s &#149; Bear Necessities`7 (%s)`0", $ccode, 1),$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("%s &#149; Wolf Call`7 (%s)`0", $ccode, 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("%s &#149; Tree Growth`7 (%s)`0", $ccode, 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("%s &#149; Singing Bow`7 (%s)`0", $ccode, 5),$script."op=fight&skill=$spec&l=5",true);
		}
		break;
		case "apply-specialties":
			$skill = httpget('skill');
			$l = httpget('l');
			if ($skill==$spec) {
				if (get_module_pref("uses") >= $l) {
					switch($l){
						case 1:
							apply_buff('rs1', array(
								"startmsg"=>"`^A Bear appears!",
								"name"=>"`@Bear Necessities",
								"rounds"=>5,
								"wearoff"=>"Your Bear disappears into the undergrowth.",
								"minioncount"=>1,
								"effectmsg"=>"A gigantic Bear chases {badguy} for `^{damage}`) points.",
								"minbadguydamage"=>1,
								"maxbadguydamage"=>$session['user']['level']*2,
								"schema"=>"specialtyranger"
							));
						break;
						case 2:
							apply_buff('rs2', array(
								"startmsg"=>"`@You stare at `^{badguy}`@ and howl like a Wolf!",
								"name"=>"`@Wolf Call",
								"rounds"=>1,
								"wearoff"=>"You stop howling...",
								"minioncount"=>1,
								"effectmsg"=>"`^A gigantic Wolf appears, and shakes {badguy} like a doll, incurring `@{damage}`^ points.`)",
								"minbadguydamage"=>30,
								"maxbadguydamage"=>$session['user']['dragonkills']*3,
								"schema"=>"specialtyranger"
							));
						break;
						case 3:
							apply_buff('rs3', array(
								"startmsg"=>"`@You extend your thought into the land, and become one with it, and Trees begin to shield you!",
								"name"=>"`@Tree Growth",
								"rounds"=>5,
								"wearoff"=>"`^The Trees wither and die, as {badguy} strikes your senses down!`)",
								"lifetap"=>2,
								"atkmod"=>0,
								"defmod"=>2,
								"effectmsg"=>"`%The Trees seem to heal you by feeding you magical sap.`)",
								"effectnodmgmsg"=>"You feel a tingle as the Trees try to heal your already healthy body.",
								"effectfailmsg"=>"The Trees wail in anger at {badguy}.",
								"schema"=>"specialtyranger"
							));
						break;
						case 5:
							apply_buff('rs5', array(
								"startmsg"=>"`^You take out your Singing Bow, which emits a piercing shriek, breaking all glass objects within a five mile radius. You consider the wine at `@".getsetting("innname", LOCATION_INN)."`^... and hope it's in a barrel.",
								"name"=>"`@Singing Bow",
								"rounds"=>5,
								"wearoff"=>"{badguy}`^ throws a sheet of bad music lyrics, stopping your Singing Bow dead. You really should take some lessons yourself...`)",
								"damageshield"=>1.5,
								"minbadguydamage"=>round($session['user']['level']/5),
								"minbadguydamage"=>$session['user']['level'],
								"effectmsg"=>"{badguy}`% rolls about moaning on the ground, as tantalizing siren songs sound in it's head. {badguy}`% yells for `^{damage}`% damage.",
								"effectnodmg"=>"{badguy}`& is slightly amused by your Singing Bow, but otherwise unharmed. \"`^What a rubbish trinket,`&\" you hear through your senses.",
								"effectfailmsg"=>"{badguy}`& is slightly amused by your Singing Bow, but otherwise unharmed. \"`^What a rubbish trinket,`&\" you hear through your senses.",
								"schema"=>"specialtyranger"
							));
						break;
					}
					set_module_pref("uses", get_module_pref("uses") - $l);
				} else {
					apply_buff('rs0', array(
						"startmsg"=>"You send your mind into the land and collect your senses, but you just sense a void. You attempt again, but Bambi jumps from the bushes- {badguy} yells \"booooOOOOOOOOO!!!\", giggling evilly before swinging at you again.",
						"rounds"=>1,
						"schema"=>"specialtyranger"
					));
				}
			}
		break;
	}
	return $args;
}

function specialtyranger_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'RS';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Ranger`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtyranger&op=yes");
				addnav("No","runmodule.php?module=specialtyranger&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Ranger`\$.");
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
