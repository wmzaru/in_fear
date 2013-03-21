<?php

function specialtyseductiveskills_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Seductive Skills",
		"author" => "`3Lonny Luberts<br>`^based on work by Sixf00t4, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download" => "http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Seductive Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|22",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|42",
			"cost"=>"Cost of Specialty in Lodge Points,int|75",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|10",
		),
		"prefs" => array(
			"Specialty - Seductive Skills User Prefs,title",
			"skill"=>"Skill points in Seductive Skills,int|0",
			"uses"=>"Uses of Seductive Skills allowed,int|0",
			"bought"=>"Has Seductive Skills Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtyseductiveskills_install(){
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

function specialtyseductiveskills_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='SE'";
	db_query($sql);
	return true;
}

function specialtyseductiveskills_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");

	$spec = "SE";
	$name = "Seductive Skills";
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
		$str = translate("The Seductive Skills Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("Flirting");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Seductive Skills (%s points)",$cost),
					"runmodule.php?module=specialtyseductiveskills&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`6Growing up, you recall discovering about love and the art of seduction ");
			output("Your charm has grown so that you can easily make anyone fall in love with you ");
			output("Everyone wants you.");	}
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
		$script = $args['script'];
		if ($uses > 0) {
			addnav(array("%s%s (%s points)`0", $ccode, $name, $uses), "");
			addnav(array("%s &#149; Sirens`7 (%s)`0", $ccode, 1),$script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("%s &#149; Dancing`7 (%s)`0", $ccode, 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("%s &#149; Spellbound`7 (%s)`0", $ccode, 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("%s &#149; Sleep`7 (%s)`0", $ccode, 5), $script."op=fight&skill=$spec&l=5",true);
		}
		break;

	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
				case 1:
					apply_buff('my1',array(
						 "startmsg"=>"`n`^Sirens Scream!`n`n",
                   		 "name"=>"`%Sirens",
                  		 "rounds"=>3,
                   		 "wearoff"=>"The screaming stops",
                   		 "regen"=>$session['user']['level'],
                  		 "effectmsg"=>"You regenerate for {damage} health.",
                   		 "effectnodmgmsg"=>"You have no wounds to regenerate.",
			 			 "minbadguydamage"=>1,
                   		 "maxbadguydamage"=>$session['user']['level']*2,
						 "schema"=>"specialtyseductiveskills"
					));
					break;
				case 2:
					apply_buff('my2',array(
						 "startmsg"=>"`n`^{badguy}`% is distracted as you begin to dance around him!`n`n",
                    	 "name"=>"`%Dancing",
                    	 "rounds"=>5,
                     	 "wearoff"=>"You stop dancing.",
                    	 "effectmsg"=>"{badguy} stumbles in dizziness for `^{damage}`) points.",
                    	 "minbadguydamage"=>1,
                    	 "maxbadguydamage"=>$session['user']['level']*3,
						 "schema"=>"specialtyseductiveskills"
					));
					break;
				case 3:
					apply_buff('my3', array(
						"startmsg"=>"`n`^{badguy} is spellbound with you`n`n",
                    	"name"=>"`%Spellbound",
                    	"rounds"=>5,
                    	"wearoff"=>"{badguy} snaps out of it.",
                    	"badguyatkmod"=>0,
						"schema"=>"specialtyseductiveskills"
					));
					break;
				case 5:
					apply_buff('my5',array(
						"startmsg"=>"`n`^The enemy falls asleep.`n`n",
                    	"name"=>"`%Sleep",
                    	"rounds"=>6,
                    	"wearoff"=>"they wake up.",
                   		"badguyatkmod"=>0,
						"schema"=>"specialtyseductiveskills"
					));
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else{
				apply_buff('my0', array(
					"startmsg"=>"You try to attack {badguy} by putting your best seductive skills into practice, but instead, you trip over your feet.",
					"rounds"=>1,
					"schema"=>"specialtyseductiveskills"
				));
			}
		}
		break;
	}
	return $args;
}

function specialtyseductiveskills_run(){
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'SE';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Seductive Skills`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtyseductiveskills&op=yes");
				addnav("No","runmodule.php?module=specialtyseductiveskills&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Seductive Skills`\$.");
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