<?php

function specialtychickenmage_getmoduleinfo() {
	$info = array(
		"name"=>"Specialty - Chicken Mage",
		"author"=>"Ben Wong, DaveS Modifications",
		"version"=>"5.0",
		"category"=>"Specialties",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=>array(
			"Specialty - Chicken Mage Settings, title",
			"mindks"=>"Minimum DKs required before available,int|65",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|85",
			"reqmoons"=>"Link the availability to a full moon?,bool|1",
			"Note: That will require moons.php (1.0 by JT Traub in core) to be installed and active,note",
			"cost"=>"Cost of Specialty in Lodge Points,int|125",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|14",
		),
		"prefs" => array(
			"Specialty - Chicken Mage User Prefs,title",
			"skill"=>"Skill points in Chicken Mage:,int|0",
			"uses"=>"Uses of Chicken Mage allowed:,int|0",
			"bought"=>"Has Chicken Mage Specialty been bought?,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtychickenmage_install(){
	module_addhook("choose-specialty");
	module_addhook("set-specialty");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addhook("incrementspecialty");
	module_addhook("specialtynames");
	module_addhook("specialtymodules");
	module_addhook("specialtycolor");
	module_addhook("dragonkill");
	module_addhook("pointsdesc");
	module_addhook("lodge");
	return true;
}

function specialtychickenmage_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='CM'";
	db_query($sql);
	return true;
}

function specialtychickenmage_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "CM";
	$name = "Chicken Mage";
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
		$args['count']++;
		$format = $args['format'];
		$dks=get_module_setting("dklast");
		$str = translate("The Chicken Mage Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=$spec$resline");
			$t1 = translate_inline("Working on a farm with a lot of chickens");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Chicken Mage (%s points)",$cost),
					"runmodule.php?module=specialtychickenmage&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`6Back on the farm, you had discovered you had a certain affinity with chickens, tending to their needs day after day. ");
			output("You recall amusing your family with mediocre magic tricks, even though your subjects often broke and leaked through the hankerchief, or sometimes just ran off. ");
			output("Then came the day you discovered what that large tree stump behind the barn was used for when you caught your father setting a chicken down on it. ");
			output("Summoning all your resolve, you discovered a truly magical talent, rallying all the chickens on the farm to its aid. ");
			output("Though the chicken was saved, your family felt there was no place for you on the farm and sent you off to the city to seek your own fortune.");
		}
		break;

	case "specialtycolor":
		$args[$spec] = $ccode;
		break;

	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
		$args[$spec] = "specialtychickenmage";
		break;

	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$c = $args['color'];
			$name = translate_inline($name);
			output("`n%sYou gain a level as a `&%s%s to `#%s%s!",$c, $name, $c, $new, $c);
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
				output("`n`2For your interest in being a %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode, $name, $ccode, $name);
			} else {
				output("`n`2For your interest in being a %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode, $name,$bonus, $ccode,$name);
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
			addnav(array("$ccode &#149; Raw Eggs`7 (%s)`0", 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Swarm of Chicks`7 (%s)`0", 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Fluttering Hens`7 (%s)`0", 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Squawkin' Fightin' Rooster`7 (%s)`0", 5),$script."op=fight&skill=$spec&l=5",true);
		}
		break;

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('cm1', array(
						"startmsg"=>"`^You conjure up several raw eggs and toss them at {badguy}.",
						"name"=>"`^Raw Eggs",
						"rounds"=>5,
						"wearoff"=>"`^You run out of eggs.",
						"minioncount"=>ceil($session['user']['level']/3),
						"maxbadguydamage"=>2,
						"effectmsg"=>"`6An egg hits {badguy}`6 with enough force to do `^{damage}`6 damage.",
						"effectnodmgmsg"=>"`6An egg flies right by {badguy}`6!",
						"schema"=>"module-specialtychickenmage"
					));
					break;
				case 2:
					apply_buff('cm2', array(
						"startmsg"=>"`^You summon forth several small chicks and direct them at {badguy}.",
						"name"=>"`^Swarm of Chicks",
						"rounds"=>5,
						"wearoff"=>"`^The chicks scatter off in random directions.",
						"minioncount"=>ceil($session['user']['level']/4),
						"maxbadguydamage"=>ceil($session['user']['level']/4),
						"minbadguydamage"=>1,
						"effectmsg"=>"`6A chick pecks at the {badguy}`6's feet for `^{damage} `6damage!",
						"schema"=>"module-specialtychickenmage"
					));
					break;
				case 3:
					apply_buff('cm3', array(
						"startmsg"=>"`^You summon forth some hens and send them fluttering at {badguy}.",
						"name"=>"`^Fluttering Hens",
						"rounds"=>5,
						"wearoff"=>"`^The last blow sends the hens scurrying every which way.",
						"minioncount"=>ceil($session['user']['level']/7),
						"maxbadguydamage"=>round($session['user']['level']/2),
						"minbadguydamage"=>3,
						"badguyatkmod"=>0.8,
						"effectmsg"=>"`6A hen flaps wildly against {badguy}`6, hitting for `^{damage}`6 as well as disrupting its attack!",
						"schema"=>"module-specialtychickenmage"
					));
					break;
				case 5:
					apply_buff('cm5', array(
						"startmsg"=>"`^You summon forth a loudly squawking rooster and aim it at {badguy}.",
						"name"=>"`^Squawkin' Fightin' Rooster",
						"rounds"=>5,
						"wearoff"=>"`^Suddenly noticing the lack of fencing around him, the rooster quickly runs away.",
						"minioncount"=>1,
						"maxbadguydamage"=>$session['user']['level'],
						"minbadguydamage"=>round($session['user']['level']/2),
						"effectmsg"=>"`6The rooster lunges at {badguy}`6 and strikes for `^{damage}`6 damage!",
						"schema"=>"module-specialtychickenmage"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('cm0', array(
					"startmsg"=>"Exhausted, you try to summon somthing fowl. {badguy} flinches back, but seeing nothing materialize, goes back to fighting.",
					"rounds"=>1,
					"schema"=>"module-specialtychickenmage"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtychickenmage_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'CM';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Chicken  Mage`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtychickenmage&op=yes");
				addnav("No","runmodule.php?module=specialtychickenmage&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Chicken Mage`\$.");
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