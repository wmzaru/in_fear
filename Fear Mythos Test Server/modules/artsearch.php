<?php

//addnews ready
//mail ready
//translator ready

require_once("lib/http.php");
require_once("lib/villagenav.php");

/*
Artifact Search Quest
Version 1.0
Author  Lightbringer
Date    14/04/2005
Desc    Simple quest for an ancient artifact - several conditions are to be met
*/

function artsearch_getmoduleinfo(){

	$info = array(
		"name"=>"Artifact Quest",
		"version"=>"1.11",
		"author"=>"Lightbringer, Debugged by DaveS",
		"category"=>"Quest",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=697",

                "settings"=>array(

            "Artifact Quest Settings,title",
			"artrewardgold"=>"What is the gold reward for the Artifact Quest?,int|1000",
			"artrewardgems"=>"What is the gem reward for the Artifact Quest?,int|2",
			"artexperience"=>"What is the quest experience multiplier for the Artifact Quest?,floatrange,1.01,1.1,0.01|1.04",
			"artminlevel"=>"What is the minimum level for this quest?,range,1,15|5",
			"artmaxlevel"=>"What is the maximum level for this quest?,range,1,15|9",
            "artname"=>"What is the name of the artifact?,text|Lightbringer's Lance",
            "artatkmod"=>"What is the attack multiplier of the artifact?,floatrange,1.01,1.1,0.01|1.02",
            "artdefmod"=>"What is the defence multiplier of the artifact?,floatrange,1.01,1.1,0.01|1.01",
            "cansell"=>"Is the artifact sellable?,bool|0",
            "sellgold"=>"How much gold does the player get on sale?,int|2000",
            "sellgems"=>"How many gems does the player get on sale?,int|10",
//            "cangive"=>"Can the player give the item to another player?,bool|0",
            ),

                "prefs"=>array(

            "artstatus"=>"How far in the Artifact Quest has the player got?,int|0",
            "hasmap"=>"Does the player have a map to the location?,bool|0",
            ),
            
            "requires"=>array(
			"lament" => "1.1|Lightbringer<br> `4Brian Parker<br>, http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=696",
		),
	);
	return $info;}

function artsearch_install(){
	module_addhook("village");
	module_addhook("dragonkilltext");
	module_addhook("newday");
	module_addhook("footer-runmodule");
	return true;
}

function artsearch_uninstall(){
	return true;
}

function artsearch_dohook($hookname,$args){
	global $session;
	switch ($hookname) {

      case "village":
		if ($session['user']['location']==
				getsetting("villagename", LOCATION_FIELDS)) {
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			if (get_module_pref("artstatus")==1 &&
				$session['user']['turns'] >= 1) {
				addnav("Explore the Dungeons (1 turn)",
				"runmodule.php?module=artsearch&op=search");
                }
           	}
		break;
		
      case "dragonkilltext":
        set_module_pref("artstatus",0);
	break;
	
       case "newday":
            if (get_module_pref("artstatus")==1 &&
		    $session['user']['level']>get_module_setting("artmaxlevel")) {
			set_module_pref("artstatus",4);
			$artname=get_module_setting("artname");
			output("`n`6You discover that another adventurer has uncovered the %s!.`0`n",$artname);
		}
	break;
	
       case "footer-runmodule":
		$op=httpget("op");
		$module=httpget("module");
		$artname=get_module_setting("artname");
		if (!$op &&  $module=="lament" && httpget("manage")!="true" && get_module_pref("artstatus")==0) {
			addnav(array("Enquire about the %s",$artname),"runmodule.php?module=artsearch&op=askart");
		}
		break;
	}
	return $args;
}

function artsearch_runevent($type) {
}

function artsearch_run(){
    global $session;
    $op = httpget('op');
	switch($op){

      case "askart":
      page_header("Lightbringer's Shrine");
      $artmin=get_module_setting("artminlevel");
      $artmax=get_module_setting("artmaxlevel");
      $artname=get_module_setting("artname");
      $cansell=get_module_setting("cansell");
      $sellgold=get_module_setting("sellgold");
      $sellgems=get_module_setting("sellgems");
      output("`3You enquire of Lightbringer as to whether he has anything for you to do.");
      if ($session['user']['level']<$artmin) {
        output("He smiles warmly but shakes his head firmly before informing you that you do not have the requisite experiences.");
        } elseif ($artmin<=$session['user']['level'] &&
				$session['user']['level']<=$artmax &&
				!get_module_pref("artstatus")) {
                  output("Lightbringer nods his head and grins.`n`n");
			      output("There is rumoured to exist a wondrous artifact - by the name of %s...Perhaps you have what it takes to claim it as your own!`n`n",$artname);
                  output("You may find it near the old cemetary - in a deep dungeon.");
			      output("It shouldn't be too difficult to locate. However...It is more than likely guarded");
			      addnav("Sally forth!","runmodule.php?module=artsearch&op=arttake");
		} else {
			output("Lightbringer shakes his head, and mentions that there are no artifacts to be found at the moment.");
		}
		addnav("S?Return to the Shrine","runmodule.php?module=lament");
		break;
		
	case "arttake":
	page_header("Lightbringer's Shrine");
	output("`3Lightbringer hands you a map to the location in question and wishes you luck in your endeavours.");
	set_module_pref("hasmap",1);
	output("You leave the questing hall and use the map to find the entrance to the dungeons.");
	set_module_pref("artstatus",1);
	addnav("S?Return to the Shrine","runmodule.php?module=lament");
    break;
    
    case "search":
    page_header("The Dungeons");
    $artname=get_module_setting("artname");
    output("`2You travel ,using the map as a guide, to the dungeons that are said to house the legendary artifact - %s.`n`n",$artname);
		$session['user']['turns']--;
        $rand=e_rand(1,10);
		switch($rand){
                case 1:
                case 2:
			output("You search through the dungeons for the artifact for what seems like an eternity, finding nothing but rubble.");
			output("Utterly dejected, you head back to the village.");
			apply_buff('lightbringer',array("name"=>"`^Dejectedness","rounds"=>15,"defmod"=>0.95,"roundmsg"=>"`^Your morale is way too low to care greatly about your well-being"));
			villagenav();
			break;
                case 3:
                case 4:
			output("A piercing scream fills the dungeon then is silent...The only sounds remaining are the pounding of your heart and a dripping noise of water departing the tip of a nearby stalagtite.");
			output("You rush over to the general vicinity of the scream, and find a mortally wounded man in his early thirties. Massive clawmarks mar his chest and the blood is everywhere.");
			output("You hurry back to town, watching your back for whatever attacked the traveller.");
			villagenav();
			break;
                case 5:
			output("You head into a dark cavern and notice a glint emanating from a small onyx pedestal.");
			output("Could it be possible that you have managed to locate the %s?",$artname);
			output("However, upon closer inspection, you discover that you have merely located a gem. You pry it out and keep it as a souvenir before returning to town.");
			debuglog("gained a gem from the dungeons");
			$session['user']['gems']++;
			villagenav();
			break;
                case 6:
			output("You explore the dungeons in the hopes of finding the %s...However - you hear a low growl emanating from behind you!");
			output("You wheel around just in time to notice that a ferocious minotaur has detected you!");
			output("You have nowhere to run to, so you ready your %s`2 to fight!",$session['user']['weapon']);
			addnav("F?Fight the Minotaur","runmodule.php?module=artsearch&fight=minotaurfight");
			break;
                case 7:
		        case 8:
		        case 9:
		        case 10:
			output("Intuition tells you that you are very close to finding what you seek - besides - the raw power emanating from a nearby room kind of gives it away...");
			output("Something else is there though. You can smell a hideously foul stench in the same area...Lightbringer was right about it being guarded...");
			output("An enormous flesh golem stands in front of a marble pedeztal - partially blocking your view of what it holds. You ready your %s and prepare to engage the foe!",$session['user']['weapon']);
			addnav("F?Fight the Golem","runmodule.php?module=artsearch&fight=golemfight");
			break;
		}
		break;
    }
    
    $fight=httpget("fight");
	switch($fight){

        case "minotaurfight":
                $badguy = array(
			"creaturename"=>"Minotaur",
			"creaturelevel"=>$session['user']['level']-1,
			"creatureweapon"=>"Natural Weapons",
			"creatureattack"=>$session['user']['attack'],
			"creaturedefense"=>round($session['user']['defense']*0.8, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*0.9, 0),
			"diddamage"=>0,
			"type"=>"quest"
		);
		$session['user']['badguy']=createstring($badguy);
		$battle=true;

	   case "minotaurfighting":
            page_header("The Dungeons");
            require_once("lib/fightnav.php");
            include("battle.php");
                if ($victory) {
			output("`2The minotaur collapses solidly to the ground, bleeding profusely from its wounds.");
			output("You quickly leave the area, praying that there are no more of ITS kind around here.");
			$expgain=round($session['user']['experience']*(e_rand(1,2)*0.01));
			$session['user']['experience']+=$expgain;
            debuglog("gained %s experience from the dungeons",$expgain);
            output("`n`n`&You gain %s experience from this fight!",$expgain);
			output("`2You return to town, shaken by your experience.");
			villagenav();
                } elseif ($defeat) {
			output("`2Your vision blacks out as the fatally gores you with its massive horns.");
			output("`n`n`%You have died! You lose 10% of your experience, and your gold is devoured by the minotaur - no accounting for taste...!");
			output("Your soul drifts to the shades.");
			debuglog("was killed by a minotaur and lost ".$session['user']['gold']." gold.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive'] = false;
			addnews("%s was slain by a Minotaur in the Dungeons!",$session['user']['name']);
			addnav("Return to the News","news.php");
		} else {
			fightnav(true,true,"runmodule.php?module=artsearch&fight=minotaurfighting");
		}
		break;

        case "golemfight":
                $badguy = array(
			"creaturename"=>"Flesh Golem",
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>"Pounding fists",
			"creatureattack"=>round($session['user']['attack']*1.15, 0),
			"creaturedefense"=>round($session['user']['defense']*0.9, 0),
			"creaturehealth"=>round($session['user']['maxhitpoints']*1.2, 0),
			"diddamage"=>0,
			"type"=>"quest"
		);

        apply_buff('golempound',array(
			"name"=>"`\$Flailing Fists",
			"roundmsg"=>"The golem wildly throws devastating melee attacks at you!",
			"effectmsg"=>"You are hit by one of the flailing fists for `4{damage}`) points!",
			"effectnodmgmsg"=>"You dodge one of the attacks!",
			"rounds"=>20,
			"wearoff"=>"The monster runs out of steam!",
			"minioncount"=>3,
			"maxgoodguydamage"=>$session['user']['level'],
			"schema"=>"artsearch"
		));
        $session['user']['badguy']=createstring($badguy);
		$battle=true;

    	case "golemfighting":
             page_header("The Dungeons");
             require_once("lib/fightnav.php");
             include("battle.php");

                if ($victory) {
			output("`2The flesh golem collapses - defeated at last!");
			$artname=get_module_setting("artname");
			output("You approach the pedestal and gasp in wonder at the %s cradled there!",$artname);
			$expgain=round($session['user']['experience']*(get_module_setting("artexperience")-1), 0);
			$session['user']['experience']+=$expgain;
			output("`n`n`&You gain %s experience from this fight!",$expgain);
			$goldgain=get_module_setting("artrewardgold");
			$gemgain=get_module_setting("artrewardgems");
			$session['user']['gold']+=$goldgain;
			$session['user']['gems']+=$gemgain;
            $session['user']['weapon'] = $artname;
            $session['user']['atkmod']+=$artatkmod;
            $session['user']['defmod']+=$artdefmod;
            debuglog("got a reward of $goldgain gold and $gemgain gems for slaying a minotaur. Artifact grants the wielder $artatkmod attack multiplier and $artdefmod defence multiplier");
			if ($goldgain && $gemgain) 
            {
				output("`2You return to the shrine, wielding the artifact with glee. Lightbringer pays you the bounty of `^%s gold`2 and a pouch of `%%s %s`2!",$goldgain,$gemgain,translate_inline(($gemgain==1)?"gems":"gem"));
			} elseif ($gemgain) {
				output("`2You return to the shrine carrying the artifact, and Lightbringer pays you the reward of a pouch of `%%s %s`2!",$gemgain,translate_inline(($gemgain==1)?"gems":"gem"));
			} elseif ($goldgain) {
				output("`2You return to the shrine carrying the artifact, and Lightbringer pays you the reward of `^%s gold`2!",$goldgain);
			} elseif ($cansell){
                output("`2You return to the shrine with the artifact! Lightbringer gives you $sellgold gold and $sellgems gems in exchange for your artifact!");
           	} else {
				output("`2You return to the shrine carrying the artifact, but Lightbringer cannot find the reward to pay you!");
			}
            set_module_pref("artstatus",2);
			addnews("%s defeated the flesh golem in the dungeons! The %s has been recovered!",$session['user']['name'],$artname);
			addnav("S?Return to the Shrine","runmodule.php?module=lament");
                strip_buff("golempound");
                } elseif ($defeat) {
			output("`2Your vision blacks out as the flesh golem clubs you to the ground.");
			output("You have failed your task to retrieve the %s!",$artname);
			output("`n`n`%You have died! You lose 10% of your experience, and your gold is yoinked by the flesh golem!");
			output("Your soul drifts to the shades.");
			debuglog("was killed by a flesh golem in the Dungeons and lost ".$session['user']['gold']." gold.");
			$session['user']['gold']=0;
			$session['user']['experience']*=0.9;
			$session['user']['alive'] = false;
                        
            set_module_pref("artstatus",3);
			addnews("%s was slain by a Flesh Golem in the Dungeons!",$session['user']['name']);
			addnav("Return to the News","news.php");
                strip_buff("golempound");
        } else {
			fightnav(true,true,"runmodule.php?module=artsearch&fight=golemfighting");
		}
		break;
	}
	page_footer();
}
?>
