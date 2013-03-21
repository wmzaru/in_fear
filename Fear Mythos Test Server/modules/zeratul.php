<?php

function zeratul_getmoduleinfo(){
	$info = array(
		"name"=>"Zeratul's Hideout",
		"author"=>"Chris Vorndran",
		"version"=>"1.2",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=36",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"User will encounter Zeratul, a fugitive, in the forest and have a battle with him. If successful, they shall obtain a crystal. Upon finding crystals, a user can see his hideout and be able to trade crystals in for certain things.",
		"settings"=>array(
			"Zeratul's Augmentation Settings,title",
				"aug"=>"Are users allowed to augment their armor/weapon,bool|1",
				"auggold"=>"Gold Cost to Augment,int|5000",
				"auggems"=>"Gem Cost to Augment,int|10",
			"Zeratul's Creation Settings,title",
				"create"=>"Are users allowed to create equipment,bool|1",
				"cregold"=>"Gold Cost to Create,int|10000",
				"cregems"=>"Gem Cost to Create,int|50",
			"Zeratul's Stats,title",
				"atk"=>"Multiplier of attacker's attack to get Creature Attack,floatrange,0,2,.02|1.3",
				"def"=>"Multiplier of attacker's defense to get Creature Defense,floatrange,0,2,.02|1.3",
				"hp"=>"Multiplier of attacker's maxHP to get Creature Hitpoints,floatrange,0,2,.02|1.2",
				"weap"=>"Zeratul's Weapon's Name,text|Dark Archon Blade",
			"Zeratul - Extra,title",
				"chance"=>"Chance that a player will lose all crystals upon DK?,range,0,100,5|20",
				"zeraloc"=>"Where is Zeratul's hideout located,location|".getsetting("villagename", LOCATION_FIELDS),
			),
		"prefs"=>array(
			"Zeratul's Equipment Augmentation,title",
				"augarm"=>"Has the user augmented their armor,bool|0",
				"augweap"=>"Has the user augmented their weapon,bool|0",
			"Zeratul's Equipment Creation,title",
				"weapon"=>"Did the user use their crystals for a Weapon,bool|0",
				"armor"=>"Did the user use their crystals for Armor,bool|0",
				"extra"=>"Did the user use their crystals for an Accessory,bool|0",
			"Zeratul's Crystal Holdings,title",
				"red"=>"Does this user have the Red Crystal,bool|0",
				"blue"=>"Does this user have the Blue Crystal,bool|0",
				"green"=>"Does this user have the Green Crystal,bool|0",
				"white"=>"Does this user have the White Crystal,bool|0",
				"black"=>"Does this user have the Black Crystal,bool|0",
		),
		);
	return $info;
}
function zeratul_install(){
	module_addhook("newday");
	module_addhook("village");
	module_addhook("changesetting");
	module_addhook("dragonkilltext");
	module_addeventhook("forest","return 100;");
	return true;
}
function zeratul_uninstall(){
	return true;
}
function zeratul_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "village":
			if ($session['user']['location'] == get_module_setting("zeraloc")) {
				tlschema($args['schemas']['marketnav']);
				addnav($args['marketnav']);
				tlschema();
				if (get_module_pref("red")
					|| get_module_pref("blue")
					|| get_module_pref("green")
					|| get_module_pref("white")
					|| get_module_pref("black")) addnav("Zeratul's Hideout","runmodule.php?module=zeratul&op=enter");
			}
			break;
		case "changesetting":
			if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("zeraloc")) {
			   set_module_setting("zeraloc", $args['new']);
		   }
		}
		break;
		case "dragonkilltext":
			if (get_module_pref("red")
				|| get_module_pref("blue")
				|| get_module_pref("green")
				|| get_module_pref("white")
				|| get_module_pref("black")){
				output("`n`)A large portal opens, and `i`@Zeratul`i`) marches through.");
				if (e_rand(1,100) <= get_module_setting("chance")){
					output("He collects any of your remaining crystals");
					set_module_pref("red",0);
					set_module_pref("blue",0);
					set_module_pref("green",0);
					set_module_pref("black",0);
					set_module_pref("white",0);
				}
			}
			set_module_pref("augarm",0);
			set_module_pref("augweap",0);
			set_module_pref("weapon",0);
			set_module_pref("armor",0);
			set_module_pref("extra",0);
			break;
		case "newday":
			if (get_module_pref("extra") == 1){
				apply_buff("zeratul", array(
					"name"=>"`i`@Zeratul's`i`) Talisman`0",
					"atkmod"=>1.05,
					"defmod"=>1.05,
					"rounds"=>-1,
					"allowinpvp"=>1,
					"schema"=>"module-zeratul",
					)
				);
			}
			break;
		}
	return $args;
}
function zeratul_run(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$crys = httpget('crys');
	$weap = httpget('weap');
	$arm = httpget('arm');
	$ext = httpget('ext');

	$auggold = get_module_setting("auggold");
	$auggems = get_module_setting("auggems");
	$cregold = get_module_setting("cregold");
	$cregems = get_module_setting("cregems");

	page_header("Zeratul's Hideout");
	
	require_once("modules/zeratul/run/case_$op.php");
	
	addnav("Return");
	if ($op != "enter") addnav("Return to Entrance","runmodule.php?module=zeratul&op=enter");
	villagenav();
page_footer();
}
function zeratul_runevent($type){
	global $session;
	$op = httpget('op');

	$atk = get_module_setting("atk");
	$def = get_module_setting("def");
	$hp = get_module_setting("hp");
	$session['user']['specialinc'] = "module:zeratul";

	if ($op != "final" && $op != "fight" && $op != "run") require_once("modules/zeratul/runevent/case_$op.php");
	
	if ($op == "run"){
		output("You can not run from your fears!");
		$op = "fight";
		httpset('op',$op);
	}
	if ($op == "final"){
		$session['user']['specialinc'] = "module:zeratul";
		output("`i`@Zeratul`i`) nods and then reveals his entire body.");
		output(" He is standing on the balls of his feet, a large blade extends to his side.");
		output(" `i`@Zeratul `i`)winks at you, \"`i`@Draw your weapon! We duel now!`i`)\"");
		$badguy = array(
			"creaturename"=>"Zeratul, the Dark Templar",
			"creatureweapon"=>get_module_setting("weap"),
			"creaturelevel"=>$session['user']['level']+1,
			"creatureattack"=>round($session['user']['attack']*$atk),
			"creaturedefense"=>round($session['user']['defense']*$def),
			"creaturehealth"=>round($session['user']['maxhitpoints']*$hp), 
			"diddamage"=>0);
		$session['user']['badguy'] = createstring($badguy);
		$op = "fight";
		httpset('op',$op);
	}
	if ($op == "fight"){
		switch (e_rand(1,5)){
			case 1:
				rawoutput("<big>");
				output("`c`i`@Is that all you got?!`i`c");
				rawoutput("</big>");
				break;
			case 2:
				rawoutput("<big>");
				output("`c`i`@You can not beat me!`i`c");
				rawoutput("</big>");
				break;
			case 3:
				rawoutput("<big>");
				output("`c`i`@You are weak... why are you even trying?`i`c");
				rawoutput("</big>");
				break;
			case 4:
				rawoutput("<big>");
				output("`c`i`@That hurt... not!`i`c");
				rawoutput("</big>");
				break;
			case 5:
				rawoutput("<big>");
				output("`c`i`@Put some more muscle into it!`i`c");
				rawoutput("</big>");
				break;
			}		
		$battle = true;
	}
	if ($battle){
		include("battle.php");
			if ($victory){
			$session['user']['specialinc'] = "";
			$expgain = round($session['user']['experience']*.05);
			$session['user']['experience']+=$expgain;
			output("`n`c`b`)You gained `^%s `)experience from the battle.`b`c`n",$expgain);
			output("`i`@Zeratul `i`)looks at you and smiles.");
			output(" \"`i`@I am glad that ye have bested me...");
			output(" Here is what I promised you...`i`)\"");
			switch (e_rand(1,5)){
				case 1:
					if (get_module_pref("red") == 0){
						output("`n`n He reaches out and hands you a Red Crystal.");
						set_module_pref("red",1);
						output("`n`n`i`@Zeratul `i`)looks at you.");
						output(" \"`i`@I am proud of you.");
						output(" I did not think that you would be able to beat me... but you did.");
						output(" Please, take this crystal to my shoppe... and I will be able to make you something special.`i`)\"`0");
					}else{
						output("`n`n\"`i`@I am sorry... but I must depart.`i`)\"");
						output("He vanishes quickly, yet you see a tiny stone hit the ground.");
						output("You bend over and pick it up... upon further examination, you have just picked up a gem.");
						$session['user']['gems']++;
					}
					break;
				case 2:
					if (get_module_pref("blue") == 0){
						output("`n`n He reaches out and hands you a Blue Crystal.");
						set_module_pref("blue",1);
						output("`n`n`i`@Zeratul `i`)looks at you.");
						output(" \"`i`@I am proud of you.");
						output(" I did not think that you would be able to beat me... but you did.");
						output(" Please, take this crystal to my shoppe... and I will be able to make you something special.`i`)\"`0");
					}else{
						output("`n`n\"`i`@I am sorry... but I must depart.`i`)\"");
						output("He vanishes quickly, yet you see a tiny stone hit the ground.");
						output("You bend over and pick it up... upon further examination, you have just picked up a gem.");
						$session['user']['gems']++;
					}
					break;
				case 3:
					if (get_module_pref("green") == 0){
						output("`n`n He reaches out and hands you a Green Crystal.");
						set_module_pref("green",1);
						output("`n`n`i`@Zeratul `i`)looks at you.");
						output(" \"`i`@I am proud of you.");
						output(" I did not think that you would be able to beat me... but you did.");
						output(" Please, take this crystal to my shoppe... and I will be able to make you something special.`i`)\"`0");
					}else{
						output("`n`n\"`i`@I am sorry... but I must depart.`i`)\"");
						output("He vanishes quickly, yet you see a tiny stone hit the ground.");
						output("You bend over and pick it up... upon further examination, you have just picked up a gem.");
						$session['user']['gems']++;
					}
					break;
				case 4:
					if (get_module_pref("white") == 0){
						output("`n`n He reaches out and hands you a White Crystal.");
						set_module_pref("white",1);
						output("`n`n`i`@Zeratul `i`)looks at you.");
						output(" \"`i`@I am proud of you.");
						output(" I did not think that you would be able to beat me... but you did.");
						output(" Please, take this crystal to my shoppe... and I will be able to make you something special.`i`)\"`0");
					}else{
						output("`n`n\"`i`@I am sorry... but I must depart.`i`)\"");
						output("He vanishes quickly, yet you see a tiny stone hit the ground.");
						output("You bend over and pick it up... upon further examination, you have just picked up a gem.");
						$session['user']['gems']++;
					}
					break;
				case 5:
					if (get_module_pref("black") == 0){
						output("`n`n He reaches out and hands you a Black Crystal.");
						set_module_pref("black",1);
						output("`n`n`i`@Zeratul `i`)looks at you.");
						output(" \"`i`@I am proud of you.");
						output(" I did not think that you would be able to beat me... but you did.");
						output(" Please, take this crystal to my shoppe... and I will be able to make you something special.`i`)\"`0");
					}else{
						output("`n`n\"`i`@I am sorry... but I must depart.`i`)\"");
						output("He vanishes quickly, yet you see a tiny stone hit the ground.");
						output("You bend over and pick it up... upon further examination, you have just picked up a gem.");
						$session['user']['gems']++;
					}
					break;
			}
		}elseif($defeat){
			$session['user']['specialinc'] = "";
			$exploss = round($session['user']['experience']*.1);
			output("`c`n`n`)`bThe templar strikes down one final blow and knocks you out cold.");
			output(" You lose `^%s `)experience.`b`c`0",$exploss);
			$session['user']['experience']-=$exploss;
			$session['user']['alive'] = false;
			$session['user']['hitpoints'] = 0;
			debuglog("lost $exploss experience to Zeratul.");
			addnews("%s was destroyed by Zeratul in the Forest.",$session['user']['name']);
			addnav("Return");
			addnav("Return to the Shades","shades.php");
		}else{
			fightnav(true,true);
		}
	}
}
?>