<?php

//Idea and programming
//Morpheus aka Apollon & Lilith
//2005 for logd.at(LoGD 0.9.7 +jt ext (GER) 3)
//First programming for 1.0.X BansheeElhayn, BUGfixed and overworked by Apollon
//Translation by Apollon
//Mail to Morpheus@magic.ms or Apollon@magic.ms
//Dedicated to my beloved little flower
//Thanks to Elessa for helping me with the translation

function castle_getmoduleinfo(){
	$info = array(
		"name"=>"The castle",
		"version"=>"1.2",
		"author"=>"Apollon",
		"category"=>"Castle",
		"download"=>"http://dragonprime.net",
		"settings"=>array(
			"Castle Settings,title",
			"castleloc"=>"Where does the castle appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"cade"=>"Which text should be shown in the village descrition,text|`nHigh above the city lies the castle, the home of the rulers of this world.`n",
			"cana"=>"What's the name of the castle,text|Thunderpoint",
		),
	);
	return $info;
}
function castle_install(){
		output("Installing the castle modul.");
		module_addhook("village");
		module_addhook("village-desc");
		module_addhook("moderate");
		return true;
}
function castle_uninstall(){
	output("Uninstalling castle modul.");
	return true;
}
function castle_dohook($hookname, $args){
	global $session;
	switch($hookname){

	case "village":
		$display = true;
		if ($session['user']['location'] == get_module_setting("castleloc")){
			addnav($args['fightnav']);
			addnav("Zur Burg", "runmodule.php?module=burg");
		}
		break;

		case ("village-desc"):
		if ($session['user']['location'] == get_module_setting("castleloc")){
			$loc = get_module_setting("castleloc");
			$cd = get_module_setting("cade");
			output("%s",$cd);
		}
		break;

		case "moderate":
		$args['castle'] = translate_inline("Castle");
		break;
	}
	return $args;
}
function castle_run(){
	global $session;
	$calo = get_module_setting("castleloc");
	$cn = get_module_setting("cana");
	$op = httpget('op');
	$what = httpget('what');
	require_once("lib/commentary.php");
	require_once("common.php");
	page_header("Castle %s",$cn);
	if ($op == ""){
		output("`3`nHigh above `6%s`3 on a mighty hill lies castle `6%s`3. From this stronghold the gods rule the lands.`n",$calo,$cn);
		output("`3A mighty gate guarded by two grim looking `4troll warriors `3leads into the yard cobbled with big hard cobblestones.");
		output("`3The high walls seem to be unconquerable and the big, old tower seems to reach high up in the sky. You must have a wonderful view from high up there.`n");
		modulehook("castle_desc");
		output("`n`3Beside the keep are two houses in which the gods of this land live.`n On the yard are standing some warriors in conversation:`n`n");
		addcommentary();
		viewcommentary("castle","`3Speak:`0",25,"says");
		addnav("The Castle");
		addnav("Climb the Keep","runmodule.php?module=castle&op=tower");
		addnav("To the Castle's Tavern","runmodule.php?module=castle&op=tavern");
		modulehook("castle");
		addnav("Before the Gate");
		villagenav();
	}
	if ($op=="tower"){
		output("`3`nYou climb up the stairs of the big keep and are quickly out of breath. ");
		output("`3As you walk beside a window you take a look and imagine how the view must be from high above.");
		switch(e_rand(1,12)){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 9:
			output("`n`n`3 Soon you reach the platform and enjoy the wonderful view over the hills and villages.");
			output("`3 You stay a moment in silence before returning to the yard.");
			addnav("Back");
			addnav("Back to the Yard","runmodule.php?module=castle");
			break;
			case 10:
			case 11:
			output("`n`n`3Soon you reach the platform and enjoy the wonderful view over the hills and villages. You can even see the bright blooms of flowers on the village square`n`n");
			output("`^It's all so inspiring that you gain a charm point.");
			$session['user']['charm']+=1;
			addnav("Back");
			addnav("Back to the Yard","runmodule.php?module=castle");
			break;
			case 12:
			output("`n`n`3You're near the platform when you step on a loose stone.");
			output("`3At the last moment you attempt to grab a joist near a window, but unfortunately it's also loose, and you fall out of the window.`n`n`4YOU ARE DEAD!");
			$session['user']['alive']=false;
			$session['user']['deathpower']+=15;
			$session['user']['hitpoints']=0;
			$session['user']['gold']=0;
			$session['user']['experience']*=0.97;
			addnews("`2%s `4fell deep and hit the ground hard.",$session['user']['name']);
			addnav("Daily News","news.php");
			break;
			}
	}
	if($op=="tavern"){
		page_header("The Castle's Tavern");
		if($what==""){
			output("`3`nAs you reach the Castle's Tavern, your mouth waters from the good smell of well-cooked meals. Behind the landlord hangs a big sign that reads:`n`n");
			output("`7`c- - -  MEALS  - - -`c`n");
			output("`6Mush:`n`3delicious mush of fresh maize with fresh fruit from the `7Castle's `2garden`3.`n`n");
			output("`qPartridge:`n`3Today hunted in the `2forest, with fresh selfmade `&dumplings`3.`n`n");
			output("`2Wild pig:`n`3Just delivered by the royal hunters and well done, with fresh bread.`n`n`n");
			output("`7`c- - -  DRINKS  - - -`c`n");
			output("`#Fresh spring water:`n`3directly from the Castle's own `!we`1ll`3, cool and refreshing.`n`n");
			output("`5Grape juice:`n`3from the last crop of the Castle's own `2V`5ine`2y`5ar`2d`3.`n`n");
			output("`&Fresh milk:`n`3directly from the Castle's own `2St`4abl`2es`3.`n`n");
			$mushcost=$session['user']['level']*11;
			$partridgecost=$session['user']['level']*25;
			$pigcost=$session['user']['level']*35;
			$watercost=$session['user']['level']*8;
			$juicecost=$session['user']['level']*10;
			$milkcost=$session['user']['level']*12;
			addnav("Back");
			addnav("Back to Castle","runmodule.php?module=castle");
			addnav("Meals");
			addnav("`6Mush `^($mushcost gold)","runmodule.php?module=castle&op=tavern&what=mush");
			addnav("`qPartridge `^($partridgecost gold)","runmodule.php?module=castle&op=tavern&what=roast");
			addnav("`4Wild pig `^($pigcost gold)","runmodule.php?module=castle&op=tavern&what=wild");
			addnav("Drinks");
			addnav("`#Spring water `^($watercost gold)","runmodule.php?module=castle&op=tavern&what=water");
			addnav("`5Grape juice `^($juicecost gold)","runmodule.php?module=castle&op=tavern&what=juice");
			addnav("`&Milk `^($milkcost gold)","runmodule.php?module=castle&op=tavern&what=milk");
		}
		if($what=="mush"){
			if ($session['user']['gold'] >= ($session['user']['level']*11) && $session['user']['turns']>0){
				switch(e_rand(1,3)){
					case 1:
					output("`n`3You enjoy your `6mush with fruit `3till the last bite.");
					output("`3Now you feel satisfied and content, with the energy to be able to kill another monster.`n`n"); 
					$session['user']['turns']+=1;
					$session['user']['gold']-=($session['user']['level']*11);
					break;
					
					case 2:
					output("`n`3You enjoy your `6mush with fruit `3till the last bite.");
					output("`3Now you feel satisfied and content, but also full and lose the time for one forest fight.`n`n");
					$session['user']['turns']-=1; 
					$session['user']['gold']-=($session['user']['level']*11);
					break;
					
					case 3:
					output("`n`3You enjoy your `6mush with fruit `3till the last bite.");
					output("`3That was really delicious!`n`n"); ; 
					$session['user']['gold']-=($session['user']['level']*11);
					break;
				}
		    }else if ($session['user']['turns']<=0){
					output("`n`3 The landlord takes a look at you and says `#\"It is nearly time for sleeping.  You shouldn't eat that much. You will only get nightmares from that.\"");
			}else{
					output("`n`3The landlord just looks at you and shakes his head `#\"I'm afraid you haven't got enough money.\"");
			}
		addnav("Back");
		addnav("Back","runmodule.php?module=castle&op=tavern");
		}
		if ($what=="roast"){
			if ($session['user']['gold'] >= ($session['user']['level']*25) && $session['user']['turns']>0){
				switch(e_rand(1,4)){
				case 1:
				case 2:
				output("`n`3You enjoy your `qpartridge with dumplings `3till the last bite.");
				output("`3Now you feel satisfied and content as your wounds begin to heal.`n`n"); 
				$session['user']['turns']+=1;
				$session['user']['gold']-=($session['user']['level']*25);
				$session['user']['hitpoints'] +=(2.5*($session['user']['level']));
				if ($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
				break;
				case 3:
				case 4:
				output("`n`3You enjoy your `qpartridge with dumplings `3till the last bite.");
				output("`3Now you feel satisfied and content, but also too full. You lose the time for one forest fight. But your wounds begin to heal.`n`n"); 
				$session['user']['turns']-=1; 
				$session['user']['gold']-=($session['user']['level']*25);
				$session['user']['hitpoints']+=(2.5*$session['user']['level']);
				if ($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
				break;
				}
		    }else if ($session['user']['turns']<=0){
					output("`n`3The landlord takes a look at you and says `#\"It is nearly time for sleeping.  You shouldn't eat that much. You will only get nightmares from that.\"");
			}else{
					output("`n`3The landlord just looks at you and shakes his head `#\"I'm afraid you haven't got enough money.\"");
			}
		addnav("Back");
		addnav("Back","runmodule.php?module=castle&op=tavern");
		}
		if($what=="wild"){
			if ($session['user']['gold'] >= ($session['user']['level']*25) && $session['user']['turns']>0){
				switch(e_rand(1,4)){
					case 1:
					output("`n`3You enjoy your `2 wild pig with bread `3till the last bite.");
					output("`3Now you feel satisfied and content, and will be able to kill another monster.`n`n"); 
					$session['user']['turns']+=1;
					$session['user']['gold']-=($session['user']['level']*35);
					$session['user']['hitpoints']=($session['user']['maxhitpoints']*1.02);
					break;
					case 2:
					case 3:
					case 4:
					output("`n`3You enjoy your `2 wild pig with bread `3till the last bite.");
					output("`3Now you feel satisfied and content, but also too full. You lose the time for one forest fight. Your wounds begin to heal and you feel reinvigorated.`n`n"); 
					$session['user']['turns']-=1; 
					$session['user']['gold']-=($session['user']['level']*35);
					$session['user']['hitpoints']=($session['user']['maxhitpoints']*=1.01);
					break;
					}
		    }else if ($session['user']['turns']<=0){
					output("`n`3The landlord takes a look at you and says `#\"It is nearly time for sleeping.  You shouldn't eat that much. You will only get nightmares from that.\"");
			}else{
					output("`n`3The landlord just looks at you and shakes his head `#\"I'm afraid you haven't got enough money.\"");
			}
		addnav("Back");
		addnav("Back","runmodule.php?module=castle&op=tavern");
		}
		if($what=="water"){
			if ($session['user']['gold'] >= ($session['user']['level']*8) && $session['user']['turns']>0){
				switch(e_rand(1,10)){
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					output("`n`3Aaaaah, the `#water `3really was cool and refreshing!");
					$session['user']['gold']-=($session['user']['level']*8);
					break;
					case 9:
					case 10:
					output("`n`3Aaaaah, the `#water `3really was cool and refreshing!");
					output("`3You feel fine and your wounds begin to heal."); 
					$session['user']['gold']-=($session['user']['level']*8);
					$session['user']['hitpoints'] += (2.5*$session['user']['level']);
					if ($session['user']['hitpoints'] > $session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
					break;
				}
		    }else if ($session['user']['turns']<=0){
					output("`n`3The landlord takes a look at you and says `#\"It is nearly time for sleeping.  You shouldn't drink something cold. You will only get nightmares from that.\"");
			}else{
					output("`n`3The landlord just looks at you and shakes his head `#\"I'm afraid you haven't got enough money.\"");
			}
		addnav("Back");
		addnav("Back","runmodule.php?module=castle&op=tavern");
		}
		if($what=="juice"){
			if ($session['user']['gold'] >= ($session['user']['level']*10) && $session['user']['turns']>0){
				switch(e_rand(1,10)){
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					output("`n`3Aaaaah, the `5grape juice `3really was cool and refreshing!");
					$session['user']['gold']-=($session['user']['level']*10);
					break;
					case 9:
					case 10:
					output("`n`3Aaaaah, the `5grape juice `3realy was cool and refreshing!");
					output("`3Your wounds begin to heal and you feel reinvigorated."); 
					$session['user']['gold']-=($session['user']['level']*10);
					$session['user']['hitpoints'] = ($session['user']['maxhitpoints']*=1.01);
					break;
				}
		    }else if ($session['user']['turns']<=0){
					output("`n`3The landlord takes a look at you and says `#\"It is nearly time for sleeping.  You shouldn't drink something cold. You will only get nightmares from that.\"");
			}else{
					output("`n`3The landlord just looks at you and shakes his head `#\"I'm afraid you haven't got enough money.\"");
			}
		addnav("Back");
		addnav("Back","runmodule.php?module=castle&op=tavern");
		}
		if($what=="milk"){
			if ($session['user']['gold'] >= ($session['user']['level']*12) && $session['user']['turns']>0){
				switch(e_rand(1,10)){
					case 1:
					case 2:
					case 3:
					case 4:
					case 5:
					case 6:
					case 7:
					case 8:
					output("`n`3Aaaaah, the `&milk `3really was cool and refreshing!");
					$session['user']['gold']-=($session['user']['level']*12);
					break;
					case 9:
					case 10:
					output("`n`3Aaaaah, the `&milk `3really was cool and refreshing!");
					output("`3You feel a little more sober!"); 
					$session['user']['gold']-=($session['user']['level']*12);
					$session['user']['drunkenness']-=5;
					break;
				}
		    }else if ($session['user']['turns']<=0){
					output("`n`3The landlord takes a look at you and says `#\"It is nearly time for sleeping.  You shouldn't drink something cold. You will only get nightmares from that.\"");
			}else{
					output("`n`3The landlord just looks at you and shakes his head `#\"I'm afraid you haven't got enough money.\"");
			}
		addnav("Back");
		addnav("Back","runmodule.php?module=castle&op=tavern");
		}
		page_footer();
	}
	page_footer();
}
?>