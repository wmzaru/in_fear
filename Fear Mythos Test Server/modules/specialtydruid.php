<?php

function specialtydruid_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Druid",
		"author" => "`!Enderandrew, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Druid Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|6",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|26",
			"cost"=>"Cost of Specialty in Lodge Points,int|30",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|6",
		),
		"prefs" => array(
			"Specialty - Druid User Prefs,title",
			"skill"=>"Skill points in Druid Spells,int|0",
			"uses"=>"Uses of Druid Spells allowed,int|0",
			"bought"=>"Has Druid Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtydruid_install(){
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

function specialtydruid_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='DR'";
	db_query($sql);
	return true;
}

function specialtydruid_dohook($hookname,$afis){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "DR";
	$name = "Druid Spells";
	$ccode = "`6";
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
		$str = translate("The Druid Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Playing with all the furry animals");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Druid (%s points)",$cost),
					"runmodule.php?module=specialtydruid&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("Growing up as a child, all your friends were training to become knights and warriors, while ");
			output("you felt there was more to life than swinging a a sword, or a rake, around.  Well, maybe ");
			output("these kids weren't so much your friends as bullies who beat you up.  But they did take the ");
			output("time to notice you and give you wedgies.  Eventually you realized your true friends were ");
			output("the animals you tended to.  You were especially close with the sheep, but that's another ");
			output("story for another day.`n`n");
			output("One day the animals started talking to you.  And either that means you are a crazy bastard, ");
			output("or you've become a powerful Druid...`n`n");
		}
		break;

	case "specialtycolor":
		$afis[$spec] = $ccode;
		break;

	case "specialtynames":
		$afis[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
		$afis[$spec] = "specialtydruid";
		break;

	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$name = translate_inline($name);
			$c = $afis['color'];
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
			addnav(array("%s &#149; Animal Companion`7 (%s)`0", $ccode, 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("%s &#149; Barkskin`7 (%s)`0", $ccode, 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("%s &#149; Wild Shape`7 (%s)`0", $ccode, 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("%s &#149; Elemental Swarm`7 (%s)`0", $ccode, 5),$script."op=fight&skill=$spec&l=5",true);
		}
		break;

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('dr1', array(
						"startmsg"=>"`6You summon the cute and fluffy animals around you, and ask them to fight to the death.",
						"name"=>"`^Animal Companion",
						"rounds"=>5,
						"wearoff"=>"The animals are distracted and scamper off.",
						"minioncount"=>1,
						"minbadguydamage"=>round(get_module_pref("skill")/3,0),
						"maxbadguydamage"=>round(get_module_pref("skill")/2,0)+3,
						"effectmsg"=>"Your animal companions nibble on {badguy} for {damage} damage!",
						"effectnodmgmsg"=>"Your animal was distracted and missed {badguy}!",
						"schema"=>"specialtydruid"
					));
					break;
				case 2:
					apply_buff('dr2', array(
						"startmsg"=>"`6You cast the `^Barkskin`6 spell, and your skin hardens, providing you with defense!",
						"name"=>"`^Barkskin",
						"rounds"=>5,
						"defmod"=>1.6,
						"schema"=>"specialtydruid"
					));
					break;
				case 3:
					apply_buff('dr3', array(
						"startmsg"=>"`6Your knowledge of nature spirits allows you to shape shift by casting `^Wild Shape`6!",
						"name"=>"`^Wild Shape",
						"rounds"=>5,
						"wearoff"=>"You slip back into your shape as the spirit leaves you.",
						"effectmsg"=>"`^Your animal claws strike {badguy} for {damage} damage.",
						"effectnodmgmsg"=>"`^You strike at {badguy}, but miss completely!",
						"atkmod"=>1.75,
						"defmod"=>1.75,
						"schema"=>"specialtydruid"
					));
					break;
				case 5:
					apply_buff('dr5', array(
						"startmsg"=>"`6You call upon the most powerful of nature spirits and summon `^Elementals`6!",
						"name"=>"`^Elemental Swarm",
						"rounds"=>5,
						"wearoff"=>"`6The `^Elementals`6 don't belong on this plane, and return to their planes.",
						"minioncount"=>3,
						"minbadguydamage"=>round(get_module_pref("skill")/3,0),
						"maxbadguydamage"=>round(get_module_pref("skill")/2,0)+3,
						"effectmsg"=>"`6The `^Elementals`6 smash {badguy} for {damage} damage!",
						"effectnodmgmsg"=>"`6The `^Elementals`6 completely miss!",
						"schema"=>"specialtydruid"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('dr0', array(
					"startmsg"=>"`^You reach out to the nature spirits and get a busy signal.",
					"rounds"=>1,
					"schema"=>"specialtydruid"
				));
			}
		}
		break;
	}
	return $afis;
}
function specialtydruid_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'DR';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Druid`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtydruid&op=yes");
				addnav("No","runmodule.php?module=specialtydruid&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Druid`\$.");
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