<?php

function specialtybeastcall_getmoduleinfo(){
	$info = array(
		"name" => "Specialty - Beast Calling",
		"author" => "Chris Vorndran, DaveS Modifications",
		"version" => "5.0",
		"category" => "Specialties",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1132",
		"settings"=> array(
			"Specialty - Beast Calling Settings,title",
			"mindk"=>"How many dks do you need before the specialty is available?,int|30",
			"maxdk"=>"Maximum dks for which this specialty is available?,int|50",
			"cost"=>"Cost of Specialty in Lodge Points,int|75",
			"dklast"=>"After purchase in the lodge this specialty wears off after this many dks:,int|10",
      ),
      "prefs" => array(
			"Specialty - Beast Calling User Prefs,title",
			"skill"=>"Skill points in Beast Calling,int|0",
			"uses"=>"Uses of Beast Calling allowed,int|0",
			"bought"=>"Has Beast Calling Specialty been bought,bool|0",
			"dksince"=>"How many dks has it been since the player purchased the specialty?,int|0",
		),
	);
	return $info;
}

function specialtybeastcall_install(){
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

function specialtybeastcall_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET specialty='' WHERE specialty='AR' OR specialty='BC'";
	db_query($sql);
	return true;
}

function specialtybeastcall_dohook($hookname,$args){
	global $session,$resline;
	tlschema("fightnav");
	
	$spec = "BC";
	$name = "Beast Calling";
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
		$dks=get_module_setting("dklast");
		$str = translate("The Beast Calling Specialty is availiable for %s Lodge points and lasts for %s dragon kills");
		$str = sprintf($str, get_module_setting("cost"),get_module_setting("dklast"));
		if ($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) output($format, $str, true);
		break;

	case "choose-specialty":
		if (($session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk"))&&$bought==0)
			break;
		if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0') {
			addnav("$ccode$name`0","newday.php?setspecialty=$spec$resline");
			$t1 = translate_inline("For years before you were here, many folk of the woods, have adapted the art of Beast Calling.");
			$t2 = appoencode(translate_inline("$ccode$name`0"));
			rawoutput("<a href='newday.php?setspecialty=$spec$resline'>$t1 ($t2)</a><br>");
			addnav("","newday.php?setspecialty=$spec$resline");
		}
		break;

	case "lodge":
		if (!$bought && $session['user']['dragonkills'] < get_module_setting("mindk") || $session['user']['dragonkills'] >= get_module_setting("maxdk")) {
			addnav(array("Study Beast Calling (%s points)",$cost),
					"runmodule.php?module=specialtybeastcall&op=start");
		}
		break;
		
	case "set-specialty":
		if($session['user']['specialty'] == $spec) {
			page_header($name);
			output("`@All along the river you used to walk, noticing the bears, and the deer, that came there to drink. ");
			output("Over the years, your senses became more tuned to listen on their subtle vibrations and noises. ");
			output("Whislt you spent more and more time with them, you learned how to talk back.");
			output("Talking back to them, allowed you to call them at any point, any time. ");
			output("Since that event, your life has never been the same... ");
		}
		break;
		
	case "specialtycolor":
		$args[$spec] = $ccode;
		break;
		
	case "specialtynames":
		$args[$spec] = translate_inline($name);
		break;

	case "specialtymodules":
	    $args[$spec] = "specialtybeastcall";
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
		if ($session['user']['specialty'] == $spec) $amt++;
		set_module_pref("uses", $amt);
		break;
	
	case "fightnav-specialties":
		$uses = get_module_pref("uses");
		$script = $args['script'];
		if ($uses > 0) {
			addnav(array("$ccode$name (%s points)`0", $uses), "");
			addnav(array("$ccode &#149; Swarm of Flies`7 (%s)`0", 1), $script."op=fight&skill=$spec&l=1", true);
		}
		if ($uses > 1) {
			addnav(array("$ccode &#149; Phoenix`7 (%s)`0", 2),$script."op=fight&skill=$spec&l=2",true);
		}
		if ($uses > 2) {
			addnav(array("$ccode &#149; Spirit Wolf`7 (%s)`0", 3),$script."op=fight&skill=$spec&l=3",true);
		}
		if ($uses > 4) {
			addnav(array("$ccode &#149; Dragon`7 (%s)`0", 5), $script."op=fight&skill=$spec&l=5",true);
		}
		break;
		
	case "apply-specialties":
		$skill = httpget('skill');
		$l = httpget('l');
		if ($skill==$spec){
			if (get_module_pref("uses") >= $l){
				switch($l){
					case 1:
						apply_buff('bc1',
							array(
								"startmsg"=>"`@You close your eyes, shreiking a high-pitched wail!",
								"name"=>"`@Swarm of Flies",
								"rounds"=>5,
								"wearoff"=>"`2The cloud of flies vanishes ... you can see through them to the `\${badguy}`2.",
								"minioncount"=>round(get_module_pref("skill")/3),
								"minbadguydamage"=>1,
								"maxbadguydamage"=>2,
								"effectmsg"=>"`2A fly hits {badguy}`2 for `^{damage}`2 damage.",
								"effectnodmgmsg"=>"`2A fly tries to hit {badguy}`2 but `\$MISSES`2!",
								"schema"=>"specialtybeastcall"
							)
						);
						break;
						
					case 2:
						apply_buff('bc2',
							array(
								"startmsg"=>"`2You close your eyes, humming a high, melodic tune...",
								"name"=>"`@Phoenix",
								"rounds"=>5,
								"wearoff"=>"You have stopped being healed from the `@Phoenix",
								"regen"=>$session['user']['level'],
								"effectmsg"=>"`2You regenerate for {damage} health, because of the `@Phoenix.",
								"effectnodmgmsg"=>"`2You have no wounds to regenerate.",
								"schema"=>"specialtybeastcall"
							)
						);
						break;
						
					case 3:
						apply_buff('bc3'
							,array(
								"startmsg"=>"`2You close your eyes, humming a low, dulcet tune...",
								"name"=>"`@Spirit Wolf",
								"rounds"=>5,
								"wearoff"=>"The vines begin to disappear, leaving {badguy} quite angered.",
								"atkmod"=>(e_rand(2,3,4)/2),
								"defmod"=>(e_rand(2,3,4)/2),
								"roundmsg"=>"`2You strike {badguy}, knowing your `@Spirit Wolf `2is backing you!", 
								"schema"=>"specialtybeastcall"
							)
						);
					break;
						
					case 5:
						apply_buff('bc5'
							,array(
								"startmsg"=>"`2You close your eyes, then emit a powerful roar!",
								"name"=>"`@Dragon",
								"rounds"=>4,
								"wearoff"=>"The Dragon disappears ... flying off into the night...",
								"minion"=>1,
								"invulnerable"=>1,
								"roundmsg"=>"The Dragon defends you with it's Mighty Strength!",
								"schema"=>"specialtybeastcall"
							)
						);
					break;
				}
				
				set_module_pref("uses", get_module_pref("uses") - $l);
			}
			
			else {
				apply_buff('ar0',
					array(
						"startmsg"=>"You are unable to find your Arcane Training Kit and therefore, can not draw any runes!",
						"rounds"=>1,
						"schema"=>"specialtybeastcall"
					)
				);
			}
		}
		break;
	}
	return $args;
}

function specialtybeastcall_run() {
	global $session;
	page_header("Hunter's Lodge");
	$spec = 'BC';
	$cost = get_module_setting("cost");
	$bought = get_module_pref("bought");
	$op = httpget('op');

	switch ($op){
		case "start":
			$pointsavailable = $session['user']['donation'] -
			$session['user']['donationspent'];
			if ($pointsavailable >= $cost && $bought == 0){
				output("`3J. C. Petersen looks upon you with a caustic grin.`n`n");
				output("\"`\$So, you wish to switch your specialty to Beast Calling`\$?`3\" he says with a smile.");
				addnav("Choices");
				addnav("Yes","runmodule.php?module=specialtybeastcall&op=yes");
				addnav("No","runmodule.php?module=specialtybeastcall&op=no");
			} else {
				output("`3J. C. Petersen stares at you for a moment then looks away as you realize that you don't have enough points to purchase this item.");
			}
			break;
		case "yes":
			output("`3J. C. Petersen hands you a tiny vial, with a shimmering blue liquid in it.`n`n");
			output("\"`\$That potion will change your specialty to Beast Calling`\$.");
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