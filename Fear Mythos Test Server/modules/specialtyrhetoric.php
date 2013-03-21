<?php

function specialtyrhetoric_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Rhetoric",
		"author" => "Sixf00t4, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Rhetoric Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|46",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|66",
			"cost"=>"Cost of Specialty in Lodge Points,int|100",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|12",
		),
		"prefs" => array(
			"Specialty - Rhetoric User Prefs,title",
			"skill"=>"Skill points in Rhetoric,int|0",
			"uses"=>"Uses of Rhetoric left,int|0",
			"bought"=>"Has Rhetoric Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtyrhetoric_install(){
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

function specialtyrhetoric_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='RH'";
	db_query($sql);
	return true;
}

function specialtyrhetoric_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");
	
	$spec = "RH";
	$name = "Rhetoric Skills";
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
		$str = translate("The Rhetoric Specialty is available for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;	
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=$spec$resline");
			$t1 = translate_inline("Language is an ancient weapon that is as sharp as the mind.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Rhetoric (%s points)",$cost),
					"runmodule.php?module=specialtyrhetoric&op=start");
		}
		break;
		
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`\$While others were out playing rough, you were learning about Plato and Socrates.  Your mind is your best weapon.");
		}
		break;
		
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
		
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
	    $args[$spec] = "specialtyrhetoric";
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
			}else {
				if (3-$x == 1) {
					output("`n`^Only 1 more skill level until you gain an extra use point!`n");
				}else {
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
			}else {
				output("`n`3For being interested in %s%s`3, you gain `^%s`3 extra uses of `&%s%s`3 for today.`n",$ccode,$name,$bonus,$ccode,$name);
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
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode &#149; Dictionaries`7 (%s)`0", 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Large Words`7 (%s)`0", 2), $script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Tongue Twisters`7 (%s)`0", 3), $script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; A Long Speech`7 (%s)`0", 5), $script."op=fight&skill=$spec&l=5",true);
		}
		break;
		
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
					case 1:
						apply_buff('rh1',
							array(
								"startmsg"=>"`^You begin to throw dictionaries!",
								"name"=>"`%Dictionaries",
								"rounds"=>5,
								"wearoff"=>"`2You have stopped throwing dictionaries.`0",
								"minioncount"=>3,
                                "schema"=>"specialtyrhetoric"
							)
						);
						break;
						
					case 2:
						apply_buff('rh2',
							array(
								"startmsg"=>"`^{badguy}`% is confused when you use big words!",
								"name"=>"`%Big Words",
                                "rounds"=>5,
                                "badguyatkmod"=>0,
                                "wearoff"=>"they stop listenning to you.",
								"schema"=>"specialtyrhetoric"
							)
						);
						break;
						
					case 3:
						apply_buff('rh3'
							,array(
								"startmsg"=>"`^You begin saying tounge twisters.",
								"name"=>"`%Tongue Twisters",
								"rounds"=>5,
								"wearoff"=>"Your tongue is tied.",
								"atkmod"=>1.5,
								"schema"=>"specialtyrhetoric"
							)
						);
					break;
						
					case 5:
						apply_buff('rh5'
							,array(
								"startmsg"=>"`^You being a long speech, which lets you heal.",
								"name"=>"`%Long Speech",
								"rounds"=>9,
								"wearoff"=>"You have stopped healing.",
								"regen"=>$session['user']['level'],
                                "effectmsg"=>"You regenerate for {damage} health.",
                                "effectnodmgmsg"=>"You have no wounds to regenerate.",
                                "schema"=>"specialtyrhetoric"
							)
						);
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else {
				apply_buff('rh0',
					array(
						"startmsg"=>"You can't think of any words.",
						"rounds"=>1,
						"schema"=>"specialtyrhetoric"
					)
				);
			}
		}
		break;
	}
	return $args;
}

function specialtyrhetoric_run() {
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'RH';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Rhetoric`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtyrhetoric&op=yes");
				addnav("No","runmodule.php?module=specialtyrhetoric&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Rhetoric`\$.");
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
