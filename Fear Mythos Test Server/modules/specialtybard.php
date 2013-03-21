<?php

function specialtybard_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Bard",
		"author" => "`!Enderandrew, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Bard Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|75",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|95",
			"cost"=>"Cost of Specialty in Lodge Points,int|125",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|14",
		),
		"prefs" => array(
			"Specialty - Bard User Prefs,title",
			"skill"=>"Skill points in Bard Songs,int|0",
			"uses"=>"Uses of Bard Songs allowed,int|0",
			"bought"=>"Has this Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtybard_install(){
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

function specialtybard_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='BA'";
	db_query($sql);
	return true;
}

function specialtybard_dohook($hookname,$afis){
	global $session,$resline;
	tlschema("fightnav");
	$op69 = httpget('op69');
	$spec = "BA";
	$name = "Bard Songs";
	$ccode = "`5";
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
		$str = translate("The Bard Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Telling the world what a great artist you were");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Bard Song (%s points)",$cost),
					"runmodule.php?module=specialtybard&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			$session['user']['donationspent'] = $session['user']['donationspent'] + $cost;
			output("`5As a small child, you knew that you were special, and better than everyone else.  You were ");
			output("an artist.  Your parents said you were a jobless bum.  But you felt that hedonism went a ");
			output("long way, and artists are so misunderstood.  Then the Dragon came around and started to ");
			output("kill everyone in sight.  That really killed your buzz and disrupted your artistic vision. ");
			output("The whole reason you became an artist--besides avoiding a real job--was to woo members of ");
			output("the opposite sex.  And if everyone is likely to die anyways, why not pursue a bit of wine, ");
			output("romance and song?  But to compose a really heroic ballad, there needs to be a hero worth you ");
			output("singing about.  It looks like you'll have to do everything yourself...`n`n");
		}
		break;

	case "specialtycolor":
		$afis[$spec] = $ccode;
		break;

	case "specialtynames":
		$afis[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
		$afis[$spec] = "specialtybard";
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
		if($session['user']['specialty'] == $spec) {
			if($session['user']['charm'] > 0) {
				$bonus = getsetting("specialtybonus", 1);
					if ($bonus == 1) {
						output("`n`2For being interested in %s%s`2, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode,$name,$ccode,$name);
					} else {
						output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra `&%s%s`2 uses for today.`n",$ccode,$name,$bonus,$ccode,$name);
					}
				$amt = (int)(get_module_pref("skill") / 3);
				$charmbonus = (int)($session['user']['charm'] / 10);
				if($charmbonus > 0) {
					output("`n`2For being such a charmer, you receive `^1`2 extra `&%s%s`2 use for today.`n",$ccode,$name);
					$amt += $charmbonus;
				}
				if ($session['user']['specialty'] == $spec) $amt++;
				set_module_pref("uses", $amt);
			}else{
				$bonus = 0;
				output("`n`2It's pretty hard to seduce people with no charm so, you receive no `&%s%s`2 uses for today.`n",$ccode,$name);
			}
		}
		break;

	case "fightnav-specialties":
		$uses = get_module_pref("uses");
		$script = $afis['script'];
		if ($uses > 0) {
			addnav(array("%s%s (%s points)`0", $ccode, $name, $uses), "");
			addnav(array("%s &#149; Fascinate`7 (%s)`0", $ccode, 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("%s &#149; Countersong`7 (%s)`0", $ccode, 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("%s &#149; Inspire Greatness`7 (%s)`0", $ccode, 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("%s &#149; Mass Suggestion`7 (%s)`0", $ccode, 5),$script."op=fight&skill=$spec&l=5",true);
		}
		break;

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				$rounds = (4+(int)($session['user']['charm'] / 20));
				if ($rounds > 9) $rounds=9;
				switch($l){
				case 1:
					apply_buff('ba1', array(
						"startmsg"=>"`5You sing a song of your plight as a daring adventurer!  {badguy} loses motivation!",
						"name"=>"`%Fascinate",
						"rounds"=>$rounds,
						"wearoff"=>"Your voice is getting tired...",
						"badguyatkmod"=>0.5,
						"schema"=>"specialtybard"
					));
					break;
				case 2:
					apply_buff('ba2', array(
						"startmsg"=>"`5{badguy} doesn't want to hurt you.  {badguy} wants to hurt {badguy}!",
						"name"=>"`%Countersong",
						"rounds"=>$rounds,
						"damageshield"=>0.5,
						"wearoff"=>"Your voice is getting tired...",
						"schema"=>"specialtybard"
					));
					break;
				case 3:
					apply_buff('ba3', array(
						"startmsg"=>"`5You get wrapped up in your own greatness by listening to your own song.",
						"name"=>"`%Inspire Greatness",
						"rounds"=>$rounds,
						"effectmsg"=>"`%You thwap {badguy} for {damage} damage.",
						"effectnodmgmsg"=>"`%Despite your immense greatness, of which you're singing about, you MISS!",
						"atkmod"=>1.5,
						"defmod"=>1.5,
						"wearoff"=>"Your voice is getting tired...",
						"schema"=>"specialtybard"
					));
					break;
				case 5:
					apply_buff('ba5', array(
						"startmsg"=>"`5You charm several nearby creatures to come to your aid!",
						"name"=>"`%Mass Suggestion",
						"rounds"=>$rounds,
						"minioncount"=>round($session['user']['level']/3),
						"minbadguydamage"=>1,
						"maxbadguydamage"=>$session['user']['level']*2,
						"effectmsg"=>"`%A charmed creature hits {badguy} for {damage} damage!",
						"effectnodmgmsg"=>"`%Your charmed creature misses {badguy} completely!  It's hard to get good help these days.",
						"wearoff"=>"Your voice is getting tired...",
						"schema"=>"specialtybard"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('ba0', array(
					"startmsg"=>"`%You try to sing, but you lack the proper motivation.  Alas, no one understands an artist.",
					"rounds"=>1,
					"schema"=>"specialtybard"
				));
			}
		}
		break;
	}
	return $afis;
}

function specialtybard_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'BA';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Bard`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtybard&op=yes");
				addnav("No","runmodule.php?module=specialtybard&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Bard`\$.");
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