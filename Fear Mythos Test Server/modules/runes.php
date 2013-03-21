<?php

function runes_getmoduleinfo(){
	$info = array(
		"name"=>"Rune Scavenging",
		"author"=>"Chris Vorndran",
		"version"=>"1.32",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=28",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Users can collect runes, and then be able to trade them in to gain certain advantages over other players.",
		"settings"=>array(
			"Rune Scavenging Settings,title",
			"max"=>"How many times may this be used,int|2",
			"total"=>"How many Runes may a person carry,int|25",
			"Rune Names,title",
			"first"=>"Name of first Rune,text|Uruz",
			"second"=>"Name of second Rune,text|Ansuz",
			"third"=>"Name of third Rune,text|Raidho",
			"fourth"=>"Name of fourth Rune,text|Wunjo",
			"Village Shoppe Location,title",
			"mindk"=>"What is the minimum DK before this shop will appear to a user?,int|0",
			"runeloc"=>"Where does the Rune Shoppe appear,location|".getsetting("villagename", LOCATION_FIELDS)
		),
		"prefs"=>array(
			"Rune Scavenging Prefences,title",
			"amnt1"=>"Amount of the First Rune,int|0",
			"amnt2"=>"Amount of the Second Rune,int|0",
			"amnt3"=>"Amount of the Third Rune,int|0",
			"amnt4"=>"Amount of the Fourth Rune,int|0",
			"user_showrune"=>"Do you wish to see your runes?,bool|0",
			"times"=>"How many times has this been done today,int|0",
			"hpinc"=>"Has hp increase been gained,bool|0",
			"atkinc"=>"Has attack increase been gained,bool|0",
			"definc"=>"Has defense increase been gained,bool|0",
			"fullinc"=>"Has Combine all been used,bool|0",
		)
		);
	return $info;
}
function runes_install(){
	module_addeventhook("forest", "return 100;");
	module_addeventhook("graveyard", "return 100;");
	module_addhook("newday");
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("dragonkilltext");
	module_addhook("charstats");
	return true;
}
function runes_uninstall(){
	return true;
}
function runes_dohook($hookname,$args){
	global $session;
	$atkinc = get_module_pref("atkinc");
	$definc = get_module_pref("definc");
	$hpinc = get_module_pref("hpinc");
	$fullinc = get_module_pref("fullinc");
	$amnt1 = get_module_pref("amnt1");
	$amnt2 = get_module_pref("amnt2");
	$amnt3 = get_module_pref("amnt3");
	$amnt4 = get_module_pref("amnt4");
	switch ($hookname){
		case "changesetting":
		    if ($args['setting'] == "villagename") {
				if ($args['old'] == get_module_setting("runeloc")) {
						set_module_setting("runeloc", $args['new']);
					}
				}
		    break;
  		case "village":
			if ($session['user']['location'] == get_module_setting("runeloc")
			&& $session['user']['dragonkills'] >= get_module_setting("mindk")) {
				tlschema($args['schemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				addnav("Jezebelle's Runes","runmodule.php?module=runes&op=enter");
			}
	        break;
		case "charstats":
			if (get_module_pref("user_showrune")){
				$title = translate_inline("Extra Info");
				$nameone = get_module_setting("first");
				$nametwo = get_module_setting("second");
				$namethree = get_module_setting("third");
				$namefour = get_module_setting("fourth");
				$name = translate_inline(" Runes");
				setcharstat($title,$nameone . $name,$amnt1);
				setcharstat($title,$nametwo . $name,$amnt2);
				setcharstat($title,$namethree . $name,$amnt3);
				setcharstat($title,$namefour . $name,$amnt4);
		}
				break;
		case "newday":
			if ($amnt1 <= 0) set_module_pref("amnt1",0);
			if ($amnt2 <= 0) set_module_pref("amnt2",0);
			if ($amnt3 <= 0) set_module_pref("amnt3",0);
			if ($amnt4 <= 0) set_module_pref("amnt4",0);
			set_module_pref("times",0);
			break;
		case "dragonkilltext":
			if (get_module_setting("dktake") == 1){
			output("`n`n`%Jezebelle `@watches the Dragon fall and looks as your body is wiped all all things.");
				output(" She walks over and picks up all of your runes, \"`3You shan't be needing these anymore...`@\"");
				set_module_pref("amnt1",0);
				set_module_pref("amnt2",0);
				set_module_pref("amnt3",0);
				set_module_pref("amnt4",0);
				if ($atkinc == 1 || $definc == 1 || $hpinc == 1 || $fullinc == 1){
				output("`@You look underneath the Dragon, and find some small runes.");
				output(" `%Jezebelle `@strides in and takes them, smiling happily.");
				set_module_pref("atkinc",0);
				set_module_pref("definc",0);
				set_module_pref("fullinc",0);
				set_module_pref("hpinc",0);
			}
		}else{
			if ($atkinc == 1 || $definc == 1 || $hpinc == 1 || $fullinc == 1){
				output("`@You look underneath the Dragon, and find some small runes.");
				output(" `%Jezebelle `@strides in and takes them, smiling happily.");
				set_module_pref("atkinc",0);
				set_module_pref("definc",0);
				set_module_pref("fullinc",0);
				set_module_pref("hpinc",0);
			}
		}
	}
	return $args;
}
function runes_runevent($type){
	global $session;
	$specialty = modulehook("specialtynames");
	$color = modulehook("specialtycolor");
	$spec = $specialty[$session['user']['specialty']];
	$ccode = $color[$session['user']['specialty']];
	$op = httpget('op');

	$amnt1 = get_module_pref("amnt1");
	$amnt2 = get_module_pref("amnt2");
	$amnt3 = get_module_pref("amnt3");
	$amnt4 = get_module_pref("amnt4");
	$first = get_module_setting("first");
	$second = get_module_setting("second");
	$third = get_module_setting("third");
	$fourth = get_module_setting("fourth");
	$total = get_module_setting("total");

	$runes = array(1=>"$first",2=>"$second",3=>"$third",4=>"$fourth");
	$add = e_rand(1,4);
	$final = $runes[$add];
	$session['user']['specialinc'] = "module:runes";

	switch ($type){
		case "graveyard":
		if ($amnt1+$amnt2+$amnt3+$amnt4 >= $total){
			output("`)You wander about the graveyard, and notice an eerie rippling of energy.");
			output(" `)You find the epicenter of these emanations and discover a small stone.");
			output(" You notice it is just a normal stone.");
			output(" You see that it is pretty ... but your satchel can only hold `^%s `)stones, and you can not part with your runes.",$total);
			output(" You toss it aside.");
		}else{
			output("`)You wander about the graveyard, and notice an eerie rippling of energy.");
			output(" `)You find the epicenter of these emanations and discover a small stone.");
			output(" `)You dust off the stone and see a glowing `\$%s `)rune on there.",$final);
			output(" `^You acquired an Arcane %s rune!`0",$final);
		switch ($add){
			case 1:
				$amnt1++;
				set_module_pref("amnt1",$amnt1);
				break;
			case 2:
				$amnt2++;
				set_module_pref("amnt2",$amnt2);
				break;
			case 3:
				$amnt3++;
				set_module_pref("amnt3",$amnt3);
				break;
			case 4:
				$amnt4++;
				set_module_pref("amnt4",$amnt4);
				break;
			}
			if ($session['user']['specialty'] == "AR"){
				require_once("lib/increment_specialty.php");
				increment_specialty("`^");
			}
		}
		break;
	case "forest":
		if ($amnt1+$amnt2+$amnt3+$amnt4 >= $total){
			output("`2You wander about the forest, and notice an eerie rippling of energy.");
			output(" `2You find the epicenter of these emanations and discover a small stone.");
			output(" You notice it is just a normal stone.");
			output(" You see that it is pretty ... but your satchel can only hold `^%s `2stones, and you can not part with your runes.",$total);
			output(" You toss it aside.");
		}else{
			output("`2You wander about the forest, and notice an eerie rippling of energy.");
			output(" `2You find the epicenter of these emanations and discover a small stone.");
			output(" `2You dust off the stone and see a glowing `\$%s `2rune on there.",$final);
			output(" `^You acquired an Arcane %s rune!`0",$final);
		switch ($add){
			case 1:
				$amnt1++;
				set_module_pref("amnt1",$amnt1);
				break;
			case 2:
				$amnt2++;
				set_module_pref("amnt2",$amnt2);
				break;
			case 3:
				$amnt3++;
				set_module_pref("amnt3",$amnt3);
				break;
			case 4:
				$amnt4++;
				set_module_pref("amnt4",$amnt4);
				break;
		}
			if ($session['user']['specialty'] == "AR"){
				require_once("lib/increment_specialty.php");
				increment_specialty("`^");
			}
		}
		break;
}
}
function runes_run(){
	global $session;
	$times = get_module_pref("times");
	$max = get_module_setting("max");

	$amnt1 = get_module_pref("amnt1");
	$amnt2 = get_module_pref("amnt2");
	$amnt3 = get_module_pref("amnt3");
	$amnt4 = get_module_pref("amnt4");
	
	$atkinc = get_module_pref("atkinc");
	$definc = get_module_pref("definc");
	$hpinc = get_module_pref("hpinc");
	$fullinc = get_module_pref("fullinc");

	$first = get_module_setting("first");
	$second = get_module_setting("second");
	$third = get_module_setting("third");
	$fourth = get_module_setting("fourth");
	$total = get_module_setting("total");
	
	$op = httpget('op');
	page_header("Jezebelle's Runes");

	switch ($op){
		case "enter":
			if ($times < $max){
			if ($amnt1 > 0 || $amnt2 > 0 || $amnt3 > 0 || $amnt4 > 0){
				output("`3You walk into a very small shoppe.");
				output(" All around, you see many glowing Runes.");
				output(" A tall gaunt Human walks over to you and smirks, looking at the runes you have.`n`n");
				output("\"`@Hello there, my name is Jezebelle ... how may I be of service?`3\" she asks in a quavering voice.");
				output(" You look through all the runes you have and deduce:`n`n");
				if ($amnt1 > 0) output("`c`^%s `#of the `&%s `#Rune`n`c`0",$amnt1,$first);
				if ($amnt2 > 0) output("`c`^%s `#of the `&%s `#Rune`n`c`0",$amnt2,$second);
				if ($amnt3 > 0) output("`c`^%s `#of the `&%s `#Rune`n`c`0",$amnt3,$third);
				if ($amnt4 > 0) output("`c`^%s `#of the `&%s `#Rune`n`c`0",$amnt4,$fourth);
				addnav("Rune Options");
				if ($amnt1 > 0) addnav(array("%s Rune",$first),"runmodule.php?module=runes&op=one");
				if ($amnt2 > 0) addnav(array("%s Rune",$second),"runmodule.php?module=runes&op=two");
				if ($amnt3 > 0) addnav(array("%s Rune",$third),"runmodule.php?module=runes&op=three");
				if ($amnt4 > 0) addnav(array("%s Rune",$fourth),"runmodule.php?module=runes&op=four");
				addnav("Combinations");
				if ($amnt1 > 0 && $amnt2 > 0)
					addnav(array("Combine %s and %s",$first,$second),"runmodule.php?module=runes&op=combo1");
				if ($amnt2 > 0 && $amnt3 > 0)
					addnav(array("Combine %s and %s",$second,$third),"runmodule.php?module=runes&op=combo2");
				if ($amnt3 > 0 && $amnt4 > 0) addnav(array("Combine %s and %s",$third,$fourth),"runmodule.php?module=runes&op=combo3");
				addnav("Extreme");
				if ($amnt1 > 0 && $amnt2 > 0 && $amnt3 > 0 && $amnt4 > 0)
					addnav("Combine All","runmodule.php?module=runes&op=comboall");
			}else{
				output("`%Jezebelle `3looks at your aura, and sees a strange resonance.");
				output(" `3She places a finger on her lips and points slowly towards the door.");
				output("`3\"`@Might I suggest the `)Graveyard `@... or perhaps, the `2Forest`@?`3\" You run out, knowing she is quite furious.");
				}
			}else{
				output("`%Jezebelle `3looks at you, noticing the traces of an earlier encounter.");
				output(" She shoos you out, shouting at you, \"`@Come back tomorrow, imbecile!`3\"");
			}
			blocknav("runmodule.php?module=runes&op=enter");
			break;
		case "one":
			output("`%Jezebelle `3looks upon you and sees your %s rune.",$first);
			runes_outcome();
			$amnt1--;
			set_module_pref("amnt1",$amnt1);
			$times++;
			set_module_pref("times",$times);
			break;
		case "two":
			output("`%Jezebelle `3looks upon you and sees your %s rune.",$second);
			runes_outcome();
			$amnt2--;
			set_module_pref("amnt2",$amnt2);
			$times++;
			set_module_pref("times",$times);
			break;
		case "three":
			output("`%Jezebelle `3looks upon you and sees your %s rune.",$third);
			runes_outcome();
			$amnt3--;
			set_module_pref("amnt3",$amnt3);
			$times++;
			set_module_pref("times",$times);
			break;
		case "four":
			output("`%Jezebelle `3looks upon you and sees your %s rune.",$fourth);
			runes_outcome();
			$amnt4--;
			set_module_pref("amnt4",$amnt4);
			$times++;
			set_module_pref("times",$times);
			break;
		case "combo1":
			if (!$atkinc){
				output("`%Jezebelle `3looks upon you and sees your %s and %s runes.",$first,$second);
				output(" She places the runes together, and notices the rune for `&Strength `3appears.");
				output(" She backs away, dropping it to the floor.");
				output(" You walk over and pick it up, seeing nothing wrong.");
				output(" A sharp pain hits you in all of your muscles, and you fall over.");
				output(" You wake up hours later...");
				output("`n`n`^You gain 1 attack.");
				$session['user']['attack']++;
				$amnt1--;
				set_module_pref("amnt1",$amnt1);
				$amnt2--;
				set_module_pref("amnt2",$amnt2);
				$times++;
				set_module_pref("times",$times);
				set_module_pref("atkinc",1);
			}else{
				output("`%Jezebelle `3turns to you and grins, \"`@I am sorry, but you are unable to use this combination any more.");
				output(" This is due to my lost in interest... I am hoping to see some new `2Dragon Runes `@in here...`3\"");
			}
			break;
		case "combo2":
			if (!$definc){
				output("`%Jezebelle `3looks upon you and sees your %s and %s runes.",$second,$third);
				output(" She places the runes together, and notices the rune for `&Defense `3appears.");
				output(" She backs away, dropping it to the floor.");
				output(" You walk over and pick it up, seeing nothing wrong.");
				output(" A sharp pain hits you in all of your muscles, and you fall over.");
				output(" You wake up hours later...");
				output("`n`n`^You gain 1 Defense.");
				$session['user']['defense']++;
				$amnt2--;
				set_module_pref("amnt2",$amnt2);
				$amnt3--;
				set_module_pref("amnt3",$amnt3);
				$times++;
				set_module_pref("times",$times);
				set_module_pref("definc",1);
			}else{
				output("`%Jezebelle `3turns to you and grins, \"`@I am sorry, but you are unable to use this combination any more.");
				output(" This is due to my lost in interest... I am hoping to see some new `2Dragon Runes `@in here...`3\"");
			}
			break;
		case "combo3":
			if (!$hpinc){
				output("`%Jezebelle `3looks upon you and sees your %s and %s runes.",$third,$fourth);
				output(" She places the runes together, and notices the rune for `&Fortitude `3appears.");
				output(" She backs away, dropping it to the floor.");
				output(" You walk over and pick it up, seeing nothing wrong.");
				output(" A sharp pain hits you in all of your muscles, and you fall over.");
				output(" You wake up hours later...");
				output("`n`n`^You gain 5 Hitpoints.");
				$session['user']['maxhitpoints']+=5;
				$amnt3--;
				set_module_pref("amnt3",$amnt3);
				$amnt4--;
				set_module_pref("amnt4",$amnt4);
				$times++;
				set_module_pref("times",$times);
				set_module_pref("hpinc",1);
			}else{
				output("`%Jezebelle `3turns to you and grins, \"`@I am sorry, but you are unable to use this combination any more.");
				output(" This is due to my lost in interest... I am hoping to see some new `2Dragon Runes `@in here...`3\"");
			}
			break;
		case "comboall":
			if (!$fullinc){
				output("`3She places all the runes together, and notices the rune for `&Ultima `3appears.");
				output(" She backs away, dropping it to the floor, rather frightened.");
				output(" She sees you proceed over, and she shouts, \"`@No, don't! You stupid fool...`3\"");
				output(" You walk over and pick it up, seeing nothing wrong.");
				output(" A sharp pain strikes you, and you are thrown across the room.");
				output(" You wake up hours later...");
				output("`n`n`^You feel a lot stronger.");
				$session['user']['defense']++;
				$session['user']['attack']++;
				$session['user']['maxhitpoints']+=5;
				$amnt1--;
				set_module_pref("amnt1",$amnt1);
				$amnt2--;
				set_module_pref("amnt2",$amnt2);
				$amnt3--;
				set_module_pref("amnt3",$amnt3);
				$amnt4--;
				set_module_pref("amnt4",$amnt4);
				$times++;
				set_module_pref("times",$times);
				set_module_pref("fullinc",1);
			}else{
				output("`%Jezebelle `3turns to you and grins, \"`@I am sorry, but you are unable to use this combination any more.");
				output(" This is due to my lost in interest... I am hoping to see some new `2Dragon Runes `@in here...`3\"");
			}
			break;
	}
		addnav("Leave");
		if ($times < $max) addnav("Return to Jezebelle","runmodule.php?module=runes&op=enter");
		villagenav();
page_footer();
}
function runes_outcome(){
	global $session;
	$amnt1 = get_module_pref("amnt1");
	$amnt2 = get_module_pref("amnt2");
	$amnt3 = get_module_pref("amnt3");
	$amnt4 = get_module_pref("amnt4");
	$first = get_module_setting("first");
	$second = get_module_setting("second");
	$third = get_module_setting("third");
	$fourth = get_module_setting("fourth");

	switch (e_rand(1,4)){
		case 1:
			$expgain = round($session['user']['experience']*.01);
			output("`%Jezebelle `3makes you put the goods into a pot.");
			output(" She mixes it coarsely, but with a whipped texture.");
			output(" She throws you down and busts open your mouth, poring the solution into your throat.");
			output("`n`n`^You gain %s experience!",$expgain);
			$session['user']['experience']+=$expgain;
			break;
		case 2:
			$goldgain = $session['user']['level']*$session['user']['gems'];
			output("`%Jezebelle `3puts the goods into a small cauldron.");
			output(" She begins to stir it, making sure all is nice and creamy.");
			output(" She pushes you to the ground and smears the solution all over your eyes.");
			output(" You begin to see tiny glints of gold.");
			output("`n`n`^You are able to grab %s gold!",$goldgain);
			$session['user']['gold']+=$goldgain;
			break;
		case 3:
			$gemgain = e_rand(1,3);
			output("`%Jezebelle `3has you put the goods into a cauldron.");
			output(" She stirs it quickly, and you notice that not all is getting mixed.");
			output(" She ties you to the chair and pours it into your mouth.");
			output(" You begin to cough and sputter, and then you spit into your lap.");
			output("`n`n`^You see %s %s in your lap!",$gemgain,translate_inline($gemgain>1?"gems":"gem"));
			$session['user']['gems']+=$gemgain;
			break;
		case 4:
			$hpgain = e_rand(1,3);
			output("`%Jezebelle `3takes your goods, and puts them into the pot.");
			output(" She stirs it rather slowly, you are almost entranced by the swirls.");
			output(" She pushes the chair over and splashes the liquid all over you.");
			output(" You feel your skin harden a bit...");
			output("`n`n`^You gain %s %s!",$hpgain,translate_inline($hpgain>1?"hitpoints":"hitpoint"));
			$session['user']['maxhitpoints']+=$hpgain;
			break;
	}
}
?>