<?php
//addnews ready
//mail ready
//translator ready

/* Lightbringer's Lament */
/* Version 1.0 by Lightbringer (lotgd.xen23.net@gmail.com) */
/* Based on Sichae's Apple Stand by Chris Vorndran */
/* 13th April 2005 */

require_once("lib/villagenav.php");
require_once("lib/http.php");

function lament_getmoduleinfo(){
  $info = array(
    "name"=>"Lightbringer's Lament",
    "version"=>"1.1",
    "author"=>"Lightbringer, debugged by DaveS",
    "category"=>"Village",
    "download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=696",
    "settings"=>array(
        "Lightbringer's Lament - Admin Settings,title",
        "shrineallowed"=>"How many times may the player visit the shrine?,int|3",
        "cost"=>"Price to visit the shrine?,int|100",
        "shrineloc"=>"Where is the shrine located?,location|".getsetting("villagename", LOCATION_FIELDS)
                     ),
    "prefs"=>array(
        "Lightbringer's Lament - User Settings,title",
        "shrinetoday"=>"How many times has the player visited the shrine today?,int|0",
                  )
        );
  return $info;
  }
  
function lament_install(){
  module_addhook("changesetting");
  module_addhook("newday");
  module_addhook("village");
    return true;
  }

function lament_uninstall(){
    return true;
  }
  
function lament_dohook($hookname,$args){
  global $session;
  switch($hookname){
  case "changesetting":
    if ($args['setting'] == "villagename") {
      if ($args['old'] == get_module_setting("shrineloc")) {
        set_module_setting("shrineloc", $args['new']);
      }
    }
  break;
  
  case "newday":
    set_module_pref("shrinetoday",0);
  break;
  
  case "village":
    if ($session['user']['location'] == get_module_setting("shrineloc")) {
        tlschema($args['schemas']['marketnav']);
    addnav($args['marketnav']);
        tlschema();
    addnav("L?Lightbringer's Shrine","runmodule.php?module=lament");
      }
  break;
    }
        return $args;
  }
  
function lament_run(){
	global $session;
	$op = httpget(op);
	$cost=get_module_setting("cost");	
	$shrineallowed=get_module_setting("shrineallowed");
	$shrinetoday=get_module_pref("shrinetoday");
	page_header("Lightbringer's Shrine");
	output("`&`c`bShrine of Lament`b`c");
	if ($shrinetoday>=$shrineallowed) {
		output("`^You tentatively reach a fingertip to the shrine, however, you sense its power has diminished for today.");
		addnav("L?Leave","village.php");
	}elseif ($session['user']['gold']<$cost){
		output("`^You feel the awesome power of the shrine - but are unable to adequately appease the gods' greedy price.");
		addnav("L?Leave","village.php");
	}elseif ($op==""){
		output("`^You walk towards the shrine and gaze in wonder at its majesty.");
		output("It appears to be suffused in glowing auras of various hues - red, green, and blue...");
		output("Lightbringer stands before the shrine and smiles briefly before addressing you...`n`n");
		output("`&\"Excellent! A brave soul willing to approach the shrine!");
		output("You are determined to test your resolve and chances, are you not?");
		output("Perhaps you may be lucky...\"`n`n");
		output("His silver eyes regard you curiously.");
		output("He sweeps his hand over the shrine grandly and flashes a disarming smile.");
		output("`&\"%s gold to appease the gods and receive their 'gift'...",$cost);
		output("By the by. One of the gifts is wondrous indeed...\"");
		addnav(array("A?Approach the Shrine (%s gold)",$cost),"runmodule.php?module=lament&op=approach");
		addnav("L?Leave","village.php");
	}elseif ($op="approach"){
		$shrinetoday++;
		set_module_pref("shrinetoday",$shrinetoday);
		$session['user']['gold']-=$cost;
		debuglog("spent $cost gold to appease the gods.");
		output("You hand Lightbringer the requisite %s gold, and place a hand on the shrine...",$cost);
		output("You grit your teeth as raw, unbridled power from the gods suffuses your very being...`n`n");
		output("Lightbringer grins..");
		$aurachance=(e_rand(1,10));
		$colour=(e_rand(1,4));
		if ($colour==1) $colour="`4Red";
		if ($colour==2) $colour="`2Green";
		if ($colour==3) $colour="`#Blue";
		if ($aurachance==1){
			output("\"Astounding! You are very much favoured in the eyes of the gods! 'Tis not often that the silver aura is attained...");
			output("I am rather jealous...Enjoy your newfound power whilst it still be available to you..");
			output("To the forest with you - and hastily!\"`n`n");
			output("A warm glow awakens an incredible boost of strength and evasion.`n`n");
			output("You feel `5favoured!");
			apply_buff('lightbringer',array("name"=>"`!Favour of the Gods","rounds"=>25,"defmod"=>1.10,"atkmod"=>1.03,"roundmsg"=>"`!The silver aura of those which are held in highest regard of the gods pervades your very essence!"));
		}elseif ($colour==4){
			output("`n`n`7You KNEW the grin was malevolent...");
			output("Something doesn't feel quite right...something is definitely amiss");
			output("A feeling of unequivocable cowardice fills you...");
			output("You flee from the evil shrine and curse your fell luck!");
			set_module_pref("shrinetoday",$shrineallowed);
			apply_buff('lightbringer',array("name"=>"`^Cowardice Aura","rounds"=>25,"defmod"=>0.95,"roundmsg"=>"`^The curse of cowardice affects you greatly"));
			blocknav("runmodule.php?module=lament&op=approach");
		}else{
			output("`n`nYou bask in the %s aura's power.",$colour);
			if ($session['user']['hitpoints'] <= $session['user']['maxhitpoints']*1.1) {
				$session['user']['hitpoints']*=1.05;
				output("`@You feel refreshed");
			}
		}
		addnav(array("T?Try Again (%s gold)",$cost),"runmodule.php?module=lament&op=approach");
		addnav("L?Leave","village.php");
	}
page_footer();
}
?>
     

