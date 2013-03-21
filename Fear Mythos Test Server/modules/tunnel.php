<?php
/**
	Previous version - 20070509

	Modified by MarcTheSlayer
	v20130315 - 15/03/2013
	+ Made 4 addnews() translator ready as suggested by Sook in 2008. :D
	+ Other small tweaks.
*/
function tunnel_getmoduleinfo()
{
	$info = array(
		"name"=>"Tunnel",
		"description"=>"Another way to escape the underworld",
		"version"=>"20130315",
		"author"=>"<a href='http://www.sixf00t4.com' target=_new>Sixf00t4</a> - enhanced by enderwiggin and eth",
		"category"=>"Shades",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1098",
		"settings"=> Array(
			"Tunnel - Settings,title",
			"gemchance"=>"Chance to find gems on success?,int|33",
			"attempts"=>"How many times are they allowed to try the tunnel per day?,int|1",
			"gems"=>"How many gems to give on success (only based on above setting)?,int|1",
			"gf"=>"How many grave fights does it cost to try the tunnel?,int|5",
			"hades"=>"Who runs the tunnel?,|`4Hades",
		),
		"prefs"=> Array(
			"Tunnel - User Settings,title",
			"escaped"=>"Has user escaped?,bool|0",
			"attempts"=>"How many times have they tried the tunnel this day?,int|0",
		),
	);
	return $info;
}

function tunnel_install(){
        if (!is_module_active('tunnel'))
        {
                output("`4Installing Tunnel Module.`n");
        }else{
                output("`4Updating Tunnel Module.`n");
        }
        module_addhook("footer-shades");
        module_addhook("newday");
        return true;
}

function tunnel_uninstall()
{
	output("`4Un-Installing Tunnel Module.`n");
	return true;
}

function tunnel_dohook($hookname,$args)
{
	global $session;
	
	switch($hookname){
		case "footer-shades":
			if(get_module_setting("attempts")>get_module_pref("attempts"))
			{
				addnav("Things to do");
				addnav("T?Take the tunnel","runmodule.php?module=tunnel");
			}
		break;
		
		case "newday":
			if(get_module_pref("escaped"))
			{
				$session['user']['deathpower']+=100;
				clear_module_pref("escaped");
			}
			clear_module_pref("attempts");
		break;
	}
return $args;    
}

function tunnel_run()
{
	global $session;

	page_header("The Tunnel");

	$ram=getsetting('deathoverlord','`$Ramius');
	$hades=get_module_setting("hades");

	require_once("lib/partner.php");
	$lover = get_partner(TRUE);

	$op = httpget('op');
	if ($op==""){
		output("`7You approach %s `7 who stands before the mouth of the tunnels.`n",$hades);
		output(" You are lonely, and miss %s, `7you ask if there is anyway to return to the surface.`n",$lover);
		output(" %s `7will grant you `&Life `7but you must take the long way through the tunnels back to the surface. `n",$hades);
		output(" You must not look back or you will lose %s torments.`n",get_module_setting("gf"));
		output(" The choice is yours. `n");
		addnav("T?Take the tunnel","runmodule.php?module=tunnel&op=agree");
		addnav("N?Never mind","graveyard.php");
	}else if ($op=="agree"){
        if ($session['user']['gravefights']<get_module_setting("gf")) {
			output("You do not have enough torments left.  You need at least %s.",get_module_setting("gf"));              
			addnav("G?Return to the Graveyard","graveyard.php");              
        }else{
			increment_module_pref("attempts");
			$session['user']['gravefights'] -= get_module_setting("gf");
			switch(e_rand(1,10)){
				case 1:
					output("`2With grim determination, you start climbing up the tunnel.  Behind you, you hear souls in ");
					output("torment, and ahead of you lies another chance at life.  You keep climbing towards a small speck ");
					output("of light, envisioning it as %s, or perhaps a guardian angel.  However, the light begins to ",$lover);
					output("move. That light at the end of the tunnel is nothing more than a hideous monster known as a Cthonian, ");
					output("swimming through the Underworld's crust like water!`n");
					output("`2%s has told tales of these creatures, servants of the Deep Ones, and the Elder Gods. But those ", getsetting("bard", "`^Seth"));
					output("were simple horror stories and couldn't possibly be real.  You shuddered as you considered if those ");
					output("stories could possibly be true.  What if Cthulu himself rested in these Underworld tombs, waiting to ");
					output("rise and consume both your flesh and soul?`n");
					output("The Cthonian shrieks as it rapidly approaches you, and you panic in pure horror as it telepathically ");
					output("files your brain with images of R'lyeh, where the Ancient Ones prepare to feast upon the unwitting ");
					output("population on the surface.  Your friends and family beg for death as the world turns into nightmare ");
					output("unending.  You reach to claw at your eyes, trying desperately hoping to erase these fiendish images, ");
					output("and you end up falling through the tunnels, back into the Underworld.`n");
					output("`3Where the nightmares a vision of what's to come, or was it merely an impish trick?`n");
					addnav("G?Return to the Graveyard","graveyard.php");
				break;
				
				case 2:
					output("`3As you enter the tunnel, you hear an imp running to his master, %s. `3The imp explains ",$ram);
					output("that if you were allowed to escape, %s`3 would lose power.  Your escape would inspire souls ",$ram);
					output("to rebel, instead of fearing and respecting %s`3' control over the Underworld.  While initially ",$ram);
					output("%s`3 enjoyed playing this game with you, he is no longer amused.  You feel %s's wrath descend ",$ram,$ram);
					output("upon you!  You are immediately cast out of the tunnel and back into the graveyard. `n");
					output("%s `3tells an imp to keep a close eye on you from now on!`n",$ram);
					output("You have lost all favor with %s`3!`n",$ram);
					$session['user']['deathpower'] = 0;
					addnav("G?Return to the Graveyard","graveyard.php");
				break;
				
				case 3:            
					output("`2With grim determination, you start climbing up the tunnel.  Behind you, you hear souls in ");
					output("torment, and ahead of you lies another chance at life.  You keep climbing towards a small speck ");
					output("of light, envisioning it as %s, or perhaps a guardian angel.  However, the tunnel begins to ",$lover);
					output("get more narrow, until you find yourself stuck!`n");
					output("You try to move but you can't.  You can hear %s `2laughing at you from down below `n",$hades);
					output("%s `7grabs you by your legs and pulls you out and tells you to get lost.`n",$hades);
					addnav("G?Return to the Graveyard","graveyard.php");
				break;
				
				case 4:
					output("`2With grim determination, you start climbing up the tunnel.  Behind you, you hear souls in ");
					output("torment, and ahead of you lies another chance at life.  You keep climbing towards a small speck ");
					output("of light, envisioning it as %s, or perhaps a guardian angel.  However, the light fades, and ",$lover);
					output("you hear %s's voice behind you!  You turn to catch a reassuring sight of your love, but see only",$lover);
					output("the twisted visage of a devilish imp.  You have been tricked!`n");
					output("You recall %s`2 telling you that you must not look back.  The tunnel closes, and the imp drags ",$hades);
					output("you back down into the depths of the Underworld.  What you had truly believed to be your beacon of ");
					output("hope and inspiration was actually used against you.  %s`2 cackles, fully enjoying his devilish ",$hades);
					output("machinations.  To %s, souls are but toys.",$hades);
					addnav("G?Return to the Graveyard","graveyard.php");
				break;
				
				case 5:
					output("`2With grim determination, you start climbing up the tunnel.  Behind you, you hear souls in ");
					output("torment, and ahead of you lies another chance at life.  You keep climbing towards a small speck ");
					output("of light, envisioning it as %s, or perhaps a guardian angel.  However, the light fades, and ",$lover);
					output("you hear %s's voice behind you!  You want nothing more than to turn around and see %s's ",$lover,$lover);
					output("face again, but you hear something else in the distance!  It the sound of a Lyre being played!`n");
					output("You remember a tale %s often told of a fellow named Orpheus, born of the God Apollo, and the ", getsetting("bard", "`^Seth"));
					output("Muse Callipoe.  He entered the Underworld and faced death to rescue his true love.  His songs ");
					output("were powerful enough to convince %s`2, and his determination moving.  Yet, he failed his love ",$hades);
					output("because he turned back to look.  Heeding this lesson, you refuse to look back!`n");
					output("You hear %s`2 cursing the imps for failing to trick you, yet you climb free of the Underworld! ",$hades);
					set_module_pref("escaped",1);
					debuglog("used the tunnel to escape.");
					addnews("%s `2escaped the Underworld by taking a tunnel to the surface!`0",$session['user']['name']);
					addnav("E?Exit the tunnel","newday.php?resurrection=true");
				break;
				
				case 6:
					output("As you reach the opening on the other end, you begin to wonder how long the tunnel really is `n");
					output("You begin to turn around to look back down the tunnel when you notice the remains of a farmboy`n");
					output("who had looked back.  It's a good thing you didn't fully turn around!`n");
					$chance = e_rand(0,100);
					if (get_module_setting('gemchance') <= $chance)
					{
							$gems = get_module_setting('gems');
							output("You search his bones and find`% %s %s`0!`n",$gems, translate_inline($gems==1?"a gem":"gems"));
							$session['user']['gems'] += $gems;
					}
					set_module_pref("escaped",1);
					debuglog("used the tunnel to escape.");
					addnews("%s `2escaped the Underworld by taking a tunnel to the surface!`0",$session['user']['name']);
					addnav("E?Exit the tunnel","newday.php?resurrection=true");
				break;
				
				case 7:
					//fight cerberus        
					output("`2With grim determination, you start climbing up the tunnel.  Behind you, you hear souls in ");
					output("torment, and ahead of you lies another chance at life.  You keep climbing towards a small speck ");
					output("of light, envisioning it as %s, or perhaps a guardian angel.  After a lengthy climb, you ",$lover);
					output("realize that neither %s`2, nor %s`2 like to let people get off that easy!`n`n",$hades,$ram);
					output("`2You reach the exit, which is guarded by `1Cerberus`2!`n`n");
					output("%s`2 tricked you!  Now you are going to have to face `1Cerberus`2!  Yet, if you defeat him, ",$hades);
					output("perhaps you will be free!`n");          
					addnav("F?Fight the Cerberus","runmodule.php?module=tunnel&op=prefight&who=cerberus");
				break;
				
				case 8:
					//fight minotaur        
					output("`2With grim determination, you start climbing up the tunnel.  Behind you, you hear souls in ");
					output("torment, and ahead of you lies another chance at life.  You keep climbing towards a small speck ");
					output("of light, envisioning it as %s, or perhaps a guardian angel.  After a lengthy climb, your ",$lover);
					output("hands grab what appears to be the surface above, and you pull yourself up.  However, this isn't ");
					output("the land you know.  Instead, you find yourself facing a giant labyrinth.  You've come this far, ");
					output("and your determined to reach the surface.  Four hours on end, you stumble through the maze, and ");
					output("you finally reach the center.  Waiting for you is the fabled `4Minotaur`2!`n`n");
					output("%s`2 tricked you!  Now you are going to be fodder for the `4Minotaur`2!  Yet, if you defeat him, ",$hades);
					output("perhaps you will be free!`n");
					addnav("F?Fight the Minotaur","runmodule.php?module=tunnel&op=prefight&who=minotaur");     
				break;
				
				case 9:
					//fight yama        
					output("`2With grim determination, you start climbing up the tunnel.  Behind you, you hear souls in ");
					output("torment, and ahead of you lies another chance at life.  You keep climbing towards a small speck ");
					output("of light, envisioning it as %s, or perhaps a guardian angel.  You begin to reach the light ",$lover);
					output("only to find that it's a magical portal!`n");
					output("`1You find yourself in an alien and bizarre land that you've only heard stories about from ");
					output("travelers to the Far East!  This land appears to be `4Yomi`1, the land of 1000 Hells!`n");
					output("`1And coming right for you is a particularly nasty-looking `4Yama King`1!`n`n");
					output("`1His skin is black and chitinous.  All over his body there are series of hooks, and beaks, as ");
					output("if his entire body is a series of small mouths to tear your flesh asunder and devour it! There ");
					output("is no place to run, but there are stories of people escaping `4Yomi`1, so if you defeat this beast, ");
					output("there may be a way out of here!`n");              
					addnav("F?Fight the Demon","runmodule.php?module=tunnel&op=prefight&who=yama");
				break;
				
				case 10:
					if (is_module_active('alignment')) {
						require_once("./modules/alignment/func.php");
						$al = get_align();
					}else{
						$al = e_rand(1,100);
					}
					if ($al > 50) {
						output("`2With grim determination, you start climbing up the tunnel.  Behind you, you hear souls in ");
						output("torment, and ahead of you lies another chance at life.  You keep climbing towards a small speck ");
						output("of light, envisioning it as %s, or perhaps a guardian angel.  And as it just so turns out, ",$lover);
						output("that's exactly what this is!  The `!Angel`2 descends, and lifts you up out of the Underworld!`n");
						set_module_pref("escaped",1);
						addnews("%s `&was rescued from the Underworld by an Angel!`0",$session['user']['name']);
						addnav("E?Exit the tunnel","newday.php?resurrection=true");
       					debuglog("used the tunnel to escape.");
					}else{
						output("`2With grim determination, you start climbing up the tunnel.  Behind you, you hear souls in ");
						output("torment, and ahead of you lies another chance at life.  You keep climbing towards a small speck ");
						output("of light, envisioning it as %s, or perhaps a guardian angel.  However, what descends upon ",$lover);
						output("you is far from an Angel!  It is %s`2, and that is not a happy face!`n",$ram);
						output("%s`2 explains that you have been wicked, and your soul deserves to be in the Underworld!`n",$ram);
						addnews("%s `%was dragged back into Underworld for being wicked!`0",$session['user']['name']);
						addnav("G?Return to the Graveyard","graveyard.php");
					}      
				break; 
			}     
		}
	}elseif ($op=="prefight"){        
		$who=httpget('who');        
		if ($who=="yama"){
			$attack = array(1,3,4,6,7,8,9,11,13,14,16,18,20,21,22);
			$attack = $attack[$session['user']['level']];
			$defense = array(1,3,5,7,9,11,13,15,17,19,21,23,25,27,29);
			$defense = $defense[$session['user']['level']];
			$badguy = array(
				"creaturename"=>"`4Yama King",
				"creaturelevel"=>$session['user']['level']+1,
				"creatureweapon"=>"`4Feral Claws",
				"creatureattack"=>$attack,
				"creaturedefense"=>$defense,
				"creaturehealth"=>$session['user']['maxhitpoints']+(10*$session['user']['level']),                                                        
				"diddamage"=>0,
				"type"=>"demon");
			$session['user']['badguy']=createstring($badguy);
         
		}elseif ($who=="minotaur"){
			$attack = array(1,3,5,7,9,11,13,15,17,19,21,23,25,27,29);
			$attack = $attack[$session['user']['level']+1];
			$defense = array(1,3,4,6,7,8,9,11,13,14,16,18,20,21,22);
			$defense = $defense[$session['user']['level']];
			$badguy = array(
				"creaturename"=>"`@The Minotaur",
				"creaturelevel"=>$session['user']['level'],
				"creatureweapon"=>"`@The Minotaur's Axe",
				"creatureattack"=>$attack,
				"creaturedefense"=>$defense,
				"creaturehealth"=>$session['user']['maxhitpoints'],                                                        
				"diddamage"=>0,
				"type"=>"minotaur");
			$session['user']['badguy']=createstring($badguy);  
			
		}elseif ($who=="cerberus"){
			$attack = array(1,2,4,6,7,8,9,11,13,15,17,19,21,23,25);
			$attack = $attack[$session['user']['level']];
			$defense = array(1,2,4,6,7,8,9,11,13,15,17,19,21,23,25);
			$defense = $defense[$session['user']['level']+1];
			$badguy = array(
				"creaturename"=>"`1Cerberus",
				"creaturelevel"=>$session['user']['level'],
				"creatureweapon"=>"`1Three Heads",
				"creatureattack"=>$attack,
				"creaturedefense"=>$defense,
				"creaturehealth"=>$session['user']['maxhitpoints'],                                                        
				"diddamage"=>0,
				"type"=>"cerberus");
		}
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
		httpset('op', "fight");       
	}
	if ($op=="run"){
		output("`4Trying desperately to find a way to escape, %s `4blocks your path!`0`n`n", $badguy['creaturename']);
		$op="fight";
		httpset('op', "fight");
	}
    $battle=($op=="fight")?true:false;
	if ($battle){                
		$originalhitpoints = $session['user']['hitpoints'];
		$session['user']['hitpoints'] = $session['user']['soulpoints'];
		include("battle.php");
		$session['user']['soulpoints'] = $session['user']['hitpoints'];
		$session['user']['hitpoints'] = $originalhitpoints;
		if ($victory){        
			$exp = array(1=>14,2=>24,3=>24,4=>45,5=>55,6=>66,7=>77,8=>88,9=>99,10=>101,11=>114,12=>127,13=>141,14=>156,15=>172);
			$expbonus = $exp[$session['user']['level']];
			output("`4It almost doesn't seem possible, but you vanquish your powerful foe!");         
			output("`3You gain `#%s experience `3from this battle.`n`n", $expbonus);                        
			$session['user']['experience']+=$expbonus;
			set_module_pref("escaped",1);
			debuglog("fought a creature to escape through the tunnel.");
			addnews("%s `2escaped the Underworld`2 by slaying a powerful and vile fiend!",$session['user']['name']);
			addnav("E?Exit the tunnel","newday.php?resurrection=true");
		}else if ($defeat){
			require_once("lib/taunt.php");                
			$taunt = select_taunt_array();
			addnews("`)%s`) has been defeated in the Underworld tunnels by %s`n%s",$session['user']['name'],$badguy['creaturename'],$taunt);
			output("`b`&You have been defeated by `%%s`&!!!`n", $badguy['creaturename']);
			output("You may not torment any more souls today.");
			$session['user']['gravefights']=0;
			addnav("G?Return to the Graveyard","graveyard.php");
		}else{
			require_once("lib/fightnav.php");
			fightnav(true,false, "runmodule.php?module=tunnel");
		}
	}                
page_footer();
}
?>