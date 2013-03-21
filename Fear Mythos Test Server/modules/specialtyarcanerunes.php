<?php

function specialtyarcanerunes_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Arcane Runes",
		"author" => "Chris Vorndran, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Arcane Runes Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|26",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|46",
			"cost"=>"Cost of Specialty in Lodge Points,int|75",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|10",
		),
		"prefs" => array(
			"Specialty - Arcane Runes User Prefs,title",
			"skill"=>"Skill points in Arcane Runes,int|0",
			"uses"=>"Uses of Arcane Runes allowed,int|0",
			"bought"=>"Has Arcane Runes Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtyarcanerunes_install(){
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

function specialtyarcanerunes_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='AR' OR specialty='AS'";
	db_query($sql);
	return true;
}

function specialtyarcanerunes_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");
	
	$spec = "AR";
	$name = "Arcane Runes";
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
		$str = translate("The Arcane Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("From the beginning of time, masters of the Arcane Runes, have had utter control of all.");
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
			output("`6Amongst the toys of childhood, you had found a small rune, with some mysterious markings upon them. ");
			output("After a while, you began to see the power of these stones and were able to use them for your own benefit. ");
			output("During a great battle of your youth, you aquired the ability to create and mark your own Runes. ");
			output("You saw the most wonderous things you had ever seen, when you watched as your army and friends were able to best your opposition. ");
			output("Since that battle, your life has never been the same... ");
		}
		break;
		
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
		
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
	    $args[$spec] = "specialtyarcanerunes";
		break;
		
	case "incrementspecialty":
		if($session['user']['specialty'] == $spec) {
			$new = get_module_pref("skill") + 1;
			set_module_pref("skill", $new);
			$name = translate_inline($name);
			$c = $args['color'];
			output("`n%sYou gain a level in `&%s%s to `#%s%s!", $c, $name, $c, $new, $c);
			$x = $new % 3;
			if ($x == 0) {
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
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode &#149; Odin's Illusion Rune`7 (%s)`0", 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Binding Rune`7 (%s)`0", 2), $script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Ottastafur Rune`7 (%s)`0", 3), $script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; End of Strife Rune`7 (%s)`0", 5), $script."op=fight&skill=$spec&l=5",true);
		}
		break;
		
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
					case 1:
						apply_buff('ar1',array(
							"startmsg"=>"`6You draw out a small rune upon a rock, and watch as an illusion of yourself appears.",
							"name"=>"`6Odin's Illusion Rune",
							"rounds"=>5,
							"wearoff"=>"Your illusion disappates ... you can see through it to {badguy}.",
							"minioncount"=>1,
							"minbadguydamage"=>round($session['user']['level']/3,0),
							"maxbadguydamage"=>round($session['user']['level']/2,0)+10,
							"effectmsg"=>"`)Your illusion hits {badguy}`) for `^{damage}`) damage.",
							"effectnodmgmsg"=>"`)Your illusion tries to hit {badguy}`) but `\$MISSES`)!",
							"schema"=>"specialtyarcanerunes"
						));
						break;
					case 2:
						apply_buff('ar2',array(
							"startmsg"=>"`6You quickly draw out a rune, and watch as 4 vines pass around {badguy} and hold him tight!",
							"name"=>"`6Binding Rune",
							"rounds"=>5,
							"wearoff"=>"The vines begin to disappear, leaving {badguy} quite angered.",
							"badguydefmod"=>0.3,
							"badguyatkmod"=>0.3,
							"roundmsg"=>"You strike {badguy}, knowing that it can't strike back!", 
							"schema"=>"specialtyarcanerunes"
						));
						break;
					case 3:
						apply_buff('ar3',array(
							"startmsg"=>"`6Your rune carving skills are put to the test, as you begin to carve out a difficult rune into your hand!",
							"name"=>"`6Ottastafur Rune",
							"rounds"=>5,
							"wearoff"=>"The rune fades from your hands, and you feel less powerful.",
							"atkmod"=>1.75,
							"defmod"=>1.75,
							"roundmsg"=>"The rune burns into your hand, bolstering your attack and defense!",
							"schema"=>"specialtyarcanerunes"
						));
						break;
					case 5:
						apply_buff('ar5',array(
							"startmsg"=>"`6Your rune carving skills are put to the test, as you begin to carve out the most difficult rune into your hand!",
							"name"=>"`6End of Strife Rune",
							"rounds"=>5,
							"wearoff"=>"The rune fades from your hands, and you feel less powerful.",
							"lifetap"=>1.75,
							"roundmsg"=>"You begin to feel power being returned, as you strike {badguy} blow for blow!",
							"schema"=>"specialtyarcanerunes"
						));
						break;
					}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('ar0', array(
					"startmsg"=>"You are unable to find your Arcane Training Kit and therefore, cannot draw any runes!",
					"rounds"=>1,
					"schema"=>"module-specialtyarcanerunes"
				));
			}
		}
		break;
	}
	return $args;
}
function specialtyarcanerunes_run() {
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'AR';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Arcane Runes`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtyarcanerunes&op=yes");
				addnav("No","runmodule.php?module=specialtyarcanerunes&op=no");
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