<?php

function specialtydaemon_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Daemonic Powers",
		"author" => "Chris Vorndran, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Daemon Powers Settings,title",
			"loss"=>"How much Alignment is lost when specialty is chosen,int|40",
			"mindk"=>"How many dks do you need before the specialty is available?,int|9",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|29",
			"cost"=>"Cost of Specialty in Lodge Points,int|30",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|6",
		),
		"prefs" => array(
			"Specialty - Daemon Powers User Prefs,title",
			"skill"=>"Skill points in Daemon Powers,int|0",
			"uses"=>"Uses of Daemon Powers allowed,int|0",
			"bought"=>"Has Daemonic Powers Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtydaemon_install(){
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

function specialtydaemon_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='DP'";
	db_query($sql);
	return true;
}

function specialtydaemon_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");
	
	$spec = "DP";
	$name = "Daemonic Powers";
	$ccode = "`)";
	$loss = get_module_setting("loss");
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
		$str = translate("The Daemon Powers Specialty is availiable for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
			$t1 = translate_inline("From the depths of the Netherworld, Daemons have ruled supreme.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Daemonic Powers (%s points)",$cost),
					"runmodule.php?module=specialtydaemon&op=start");
		}
		break;

	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`)The Kings of old have passed down the tales of the Daemon.");
			output(" You accepted them as fables, until the one day came, and you learned that you were a Daemon.");
			output(" You could feel the newfound power coursing in your veins.");
			output(" The ability to manipulate darkness, to aide you in battle.");
			output(" Little did you know, that you were destined for so much more...");
			if (is_module_active('alignment')) increment_module_pref('alignment',- $loss,'alignment');
		}
		break;
	
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
		
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
	    $args[$spec] = "specialtydaemon";
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
		if (is_module_active('alignment') && $session['user']['specialty'] == 'DP') {
				output("`nYour Daemonic Tendencies have lowered your alignment.`n");
				increment_module_pref("alignment",-1,"alignment");
		}
		break;

	case "fightnav-specialties":
		$uses = get_module_pref("uses");
		$script = $args['script'];
		if ($uses > 0) {
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode &#149; Bloodlust`7 (%s)`0", 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Daemon Summoning`7 (%s)`0", 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Winged Slayer`7 (%s)`0", 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Overlord's Wrath`7 (%s)`0", 5), $script."op=fight&skill=$spec&l=5",true);
		}
		break;
		
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
					case 1:
						apply_buff('dp1',
							array(
								"startmsg"=>"`)You strike forth, a new rage about you. Your muscles grow at an exponential rate...",
								"name"=>"`)Bloodlust",
								"rounds"=>5,
								"wearoff"=>"The blood dissappates from your eyes, and you see {badguy} clearly.",
								"atkmod"=>1.75,
								"defmod"=>.1,
								"schema"=>"module-specialtydaemon"
							)
						);
						break;
						
					case 2:
						apply_buff('dp2',
							array(
								"startmsg"=>"`)You pop your neck, and slowly summon many minions from the depths of the Netherworld!",
								"name"=>"`)Daemon Summoning",
								"rounds"=>5,
								"wearoff"=>"`)Your minions decide that there is no more `^gold `)that can be earned, so they leave",
								"minioncount"=>round(get_module_pref("skill")/3),
								"minbadguydamage"=>round($session['user']['level']/4),
								"maxbadguydamage"=>round($session['user']['level']/2),
								"effectmsg"=>"`)A Daemon strikes {badguy}`) for `^{damage}`) damage.",
								"effectnodmgmsg"=>"`)When your Daemon lashes out to hit {badguy},`) it`\$MISSES`)!",
								"schema"=>"module-specialtydaemon"
							)
						);
						break;
						
					case 3:
						apply_buff('dp3'
							,array(
								"startmsg"=>"`)You mutter a deep and dark charm, and a gigantic shadow appears overhead!",
								"name"=>"`)Winged Slayer",
								"rounds"=>5,
								"wearoff"=>"The rune fades from your hands, and you feel less powerful.",
								"minioncount"=>1,
								"minbadguydamage"=>round($session['user']['level']/2,0),
								"maxbadguydamage"=>round($session['user']['level']*1.5),
								"effectmsg"=>"`)The Winged Slayer strikes {badguy}`) for `^{damage}`) damage.",
								"effectnodmgmsg"=>"`)When the Winged Slayer lashes out to hit {badguy},`) it`\$MISSES`)!",
								"schema"=>"module-specialtydaemon"
							)
						);
					break;
					case 5:
						apply_buff('dp5'
							,array(
								"startmsg"=>"`)You feel the Royal Blood coursing in your veins, and your body begins to grow!",
								"name"=>"`)Overlord's Wrath",
								"rounds"=>5,
								"wearoff"=>"The Royal Effect slowly dies off... growing dormant for another day.",
								"lifetap"=>1,
								"atkmod"=>1.75,
								"roundmsg"=>"You begin to feel power being returned, as you strike {badguy} blow for blow!",
								"schema"=>"specialtydaemon"
							)
						);
					break;
				}
				set_module_pref("uses", get_module_pref("uses") - $l);
			}else {
				apply_buff('ar0',
					array(
						"startmsg"=>"You can not feel the Daemonic Blood in your veins. This may be due to high cholesterol!",
						"rounds"=>1,
						"schema"=>"specialtydaemon"
					)
				);
			}
		}
		break;
	}
	return $args;
}

function specialtydaemon_run() {
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'DP';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Daemon Powers`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtydaemon&op=yes");
				addnav("No","runmodule.php?module=specialtydaemon&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Daemon Powers`\$.");
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
