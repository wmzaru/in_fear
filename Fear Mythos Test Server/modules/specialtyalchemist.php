<?php

function specialtyalchemist_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Alchemist",
		"author" => "Ignatus / Kyle Spence, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Alchemist Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|15",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|30",
			"cost"=>"Cost of Specialty in Lodge Points,int|50",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|8",
		),
		"prefs" => array(
			"Specialty - Alchemist User Prefs,title",
			"skill"=>"Skill points in Alchemy:,int|0",
			"uses"=>"Uses of Alchemy allowed,int|0",
			"bought"=>"Has Alchemist Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtyalchemist_install(){
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

function specialtyalchemist_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='XX'";
	db_query($sql);
	return true;
}

function specialtyalchemist_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "XX";
	$name = "Alchemist";
	$ccode = "`4";
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
		$str = translate("The Alchemist Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0'){
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Combining Elements");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;
		
	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Alchemy (%s points)",$cost),
					"runmodule.php?module=specialtyalchemist&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("Ever since a boy you have been trained by the great Zosimos who first mention the Philosopher's Stone.");
			output("Now since your great master has left all you know is that you need to show what alchemy is really about to the world.");
			output("Alchemy is an early protoscientific practice combining elements of chemistry, physics, astrology, art, semiotics, metallurgy, medicine,and mysticism.");
			output("Now you must seek out the philospher stone to reach your goals");
		}
		break;

	case "specialtycolor":
		$args[$spec] = $ccode;
		break;

	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
		$args[$spec] = "specialtyalchemist";
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
				}else{
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
				output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n", $ccode, $name, $ccode, $name);
			}else{
				output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n", $ccode, $name, $bonus, $ccode, $name);
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
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode • Transmutation`7 (%s)`0", 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode • Animal Transmutation`7 (%s)`0", 2), $script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode • Panacea`7 (%s)`0", 3), $script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode • Philospher Stone`7 (%s)`0", 5), $script."op=fight&skill=$spec&l=5",true);
		}
		break;

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
					case 1:
						apply_buff('ts1',array(
							"startmsg"=>"You get ready for a rough battle and you start your transmutation",
							"name"=>"Rock Transmutation",
							"rounds"=>5,
							"wearoff"=>"You run out of ideas on what you should change",
							"roundmsg"=>"You transmute a rock to a iron rock and throw it at the enemy",
							"atkmod"=>1.75,
							"badguydefmod"=>1.9,
							"schema"=>"module-specialtyalchemist"
						));
						break;
					case 2:
						apply_buff('ts2',array(
							"startmsg"=>"You look around and find a few small dogs and transmute them into a big hair Troll to fight for you",
							"name"=>"Animal Transmutation",
							"rounds"=>5,
							"minioncount"=>3,
							"minbadguydamage"=>3,
							"maxbadguydamage"=>8,
							"effectnodmgmsg"=>"The effects wear off on the dog and it runs away",
							"schema"=>"module-specialtyalchemist"
						));
						break;
					case 3:
						apply_buff('ts3', array(
							"startmsg"=>"You try to make one of the alchemist dreams a universal Panacea but you failed and it only heals for a short while",
							"name"=>"Panacea",
							"rounds"=>4,
							"wearoff"=>"The effects of the healing panacea wears off",
							"regen"=>10,
							"schema"=>"module-specialtyalchemist"
						));
						break;
					case 5:
						apply_buff('ts5',array(
							"startmsg"=>"Feeling the power of the Philospher Stone you transmute you slef to increase you power",
							"name"=>"Philospher Stone",
							"rounds"=>13,
							"wearoff"=>"You drop the Philospher Stone and lost it",
							"atkmod"=>1.8,
							"defmod"=>2,
							"schema"=>"module-specialtyalchemist"
						));
						break;
					}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('ts0', array(
					"startmsg"=>"You can't find anything to transform.",
					"rounds"=>1,
					"schema"=>"module-specialtyalchemist"
				));
			}
		}
		break;
	}
	return $args;
}
function specialtyalchemist_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'XX';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Alchemist`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtyalchemist&op=yes");
				addnav("No","runmodule.php?module=specialtyalchemist&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Alchemist`\$.");
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