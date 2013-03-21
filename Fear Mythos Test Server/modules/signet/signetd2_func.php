<?php
function signetd2_fight($op) {
	global $session,$badguy;
	$temp=get_module_pref("pqtemp");
	page_header("Temple Fight");
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($op=="door"){
		if ($temp==970 && $allprefs['loc970']==0) $allprefs['loc970']=2;
		elseif ($temp==865 && $allprefs['loc865']==0) $allprefs['loc865']=2;
		elseif ($temp==946 && $allprefs['loc946']==0) $allprefs['loc946']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("`Qa Stubborn Door`0"),
			"creaturelevel"=>2,
			"creatureweapon"=>translate_inline("sharp splinters"),
			"creatureattack"=>2,
			"creaturedefense"=>3,
			"creaturehealth"=>25,
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="trapdoor"){
		if ($temp==1012 && $allprefs['loc1012']==0) $allprefs['loc1012']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("`QTricky Traps`0"),
			"creaturelevel"=>3,
			"creatureweapon"=>translate_inline("sneaky needles"),
			"creatureattack"=>4,
			"creaturedefense"=>4,
			"creaturehealth"=>45,
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="mgoblins"){
		if ($temp==1147 && $allprefs['loc1147']==0) $allprefs['loc1147']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Manacled Goblins"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"rusty weapons",
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.85,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.80,
			"creaturehealth"=>round($session['user']['hitpoints']*.8),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="orcs"){
		if ($temp==1010 && $allprefs['loc1010']==0) $allprefs['loc1010']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("A Group of Orcs"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("rusty swords"),
			"creatureattack"=>($session['user']['attack']+e_rand(2,4)),
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4)),
			"creaturehealth"=>round($session['user']['hitpoints']*.97),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="barbarian"){
		if ($temp==947 && $allprefs['loc947']==0) $allprefs['loc947']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$level=$session['user']['level']-1;
		if ($level==0) $level=1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Barbarian"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("big wooden club"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,4)),
			"creaturedefense"=>($session['user']['attack']+e_rand(1,4)),
			"creaturehealth"=>round($session['user']['hitpoints']*.9),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="troll"){
		if ($temp==776 && $allprefs['loc776']==0) $allprefs['loc776']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$level=$session['user']['level']-1;
		if ($level==0) $level=1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Troll"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("shackled claws"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2))*.7,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,2))*.7,
			"creaturehealth"=>round($session['user']['hitpoints']*.7),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="oldman"){
		if ($temp==386 && $allprefs['loc386']==0) $allprefs['loc386']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$level=$session['user']['level']+3;
		if ($level>=15) $level=15;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Old Man"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("vicious walking stalk"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2))*1.2,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,2))*1.5,
			"creaturehealth"=>round($session['user']['hitpoints']*1.5),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="beetle"){
		if ($temp==947 && $allprefs['loc947']==0) $allprefs['loc947']=2;
		elseif ($temp==82 && $allprefs['loc82']==0) $allprefs['loc82']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$level=$session['user']['level']-1;
		if ($level==0) $level=1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Huge Beetle"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("very sharp pincers"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3)),
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3)),
			"creaturehealth"=>round($session['user']['hitpoints']*.65),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="dingo"){
		if ($temp==947 && $allprefs['loc947']==0) $allprefs['loc947']=2;
		elseif ($temp==82 && $allprefs['loc82']==0) $allprefs['loc82']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$level=$session['user']['level']-1;
		if ($level==0) $level=1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Dingo"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("teeth and claws"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3)*.7),
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3)*.8),
			"creaturehealth"=>round($session['user']['hitpoints']*.75),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="centipede"){
		if ($temp==59 && $allprefs['loc59']==0) $allprefs['loc59']=2;
		elseif ($temp==327 && $allprefs['loc327']==0) $allprefs['loc327']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$level=$session['user']['level']-1;
		if ($level==0) $level=1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Centipede"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("many little legs"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3)),
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3)),
			"creaturehealth"=>round($session['user']['hitpoints']*.8),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="bee"){
		if ($temp==55 && $allprefs['loc55']==0) $allprefs['loc55']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$level=$session['user']['level'];
		if ($level==0) $level=1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Deadly Bee"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("poisonous stinger"),
			"creatureattack"=>($session['user']['attack']+e_rand(2,3)),
			"creaturedefense"=>($session['user']['attack']+e_rand(2,3)),
			"creaturehealth"=>round($session['user']['hitpoints']*.75),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="rat"){
		if ($temp==537 && $allprefs['loc537']==0) $allprefs['loc537']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$level=$session['user']['level']-2;
		if ($level<=0) $level=1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("a Huge Rat"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("gnarled teeth"),
			"creatureattack"=>($session['user']['attack']+e_rand(0,1)),
			"creaturedefense"=>($session['user']['attack']+e_rand(0,1)),
			"creaturehealth"=>round($session['user']['hitpoints']*.6),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="guards"){
		if ($temp==279 && $allprefs['loc279']==0) $allprefs['loc279']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("High Priest Guards"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("magic missile spells"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2))*.8,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,2))*.85,
			"creaturehealth"=>round($session['user']['hitpoints']*.87),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="vampire"){
		if ($temp==956 && $allprefs['loc956']==0) $allprefs['loc956']=2;
		elseif ($temp==1026 && $allprefs['loc1026']==0) $allprefs['loc1026']=2;
		elseif ($temp==890 && $allprefs['loc890']==0) $allprefs['loc890']=2;
		elseif ($temp==822 && $allprefs['loc822']==0) $allprefs['loc822']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Vampire"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("fangs"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2))*.8,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,2))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.8),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="bvampire"){
		if ($temp==685 && $allprefs['loc685']==0) $allprefs['loc685']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Huge Vampire"),
			"creaturelevel"=>15,
			"creatureweapon"=>translate_inline("fangs and dirty finger nails"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2)),
			"creaturedefense"=>($session['user']['attack']+e_rand(1,2)),
			"creaturehealth"=>round($session['user']['hitpoints']*1.05),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="scribe"){
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("An Evil Scribe"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("simple attack spells"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.62,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.52,
			"creaturehealth"=>round($session['user']['hitpoints']*.78),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="apdevil"){
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("An Apprentice Devil"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("Evil Eye"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.67,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.72,
			"creaturehealth"=>round($session['user']['hitpoints']*.88),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="fight" or $op=="run"){
		global $badguy;
		$battle=true;
		$fight=true;
		if ($battle){
			require_once("battle.php");
		$allprefs=unserialize(get_module_pref('allprefs'));
	    if ($victory){
				//opening doors
				if (($temp==970 && $allprefs['loc970']==2) || ($temp==865 && $allprefs['loc865']==2) || ($temp==1012 && $allprefs['loc1012']==2) || ($temp==946 && $allprefs['loc946']==2)) {
					output("`^`n`bYou have opened the door safely.`b`n");
					$experience=$session['user']['level']*e_rand(3,6);
					output("`#You receive `6%s `#experience.`n",$experience);
					$session['user']['experience']+=$experience;
					if ($allprefs['loc970']==2) $allprefs['loc970']=1;
					elseif ($allprefs['loc865']==2) $allprefs['loc865']=1;
					elseif ($allprefs['loc1012']==2) $allprefs['loc1012']=1;
					elseif ($allprefs['loc946']==2) $allprefs['loc946']=1;
					set_module_pref('allprefs',serialize($allprefs));
				//monsters
				}elseif (($temp==1147 && $allprefs['loc1147']==2) || ($temp==1010 && $allprefs['loc1010']==2) || ($temp==947 && $allprefs['loc947']==2) || ($temp==776 && $allprefs['loc776']==2) || ($temp==386 && $allprefs['loc386']==2) || ($temp==82 && $allprefs['loc82']==2) || ($temp==55 && $allprefs['loc55']==2) || ($temp==59 && $allprefs['loc59']==2) || ($temp==327 && $allprefs['loc327']==2) || ($temp==537 && $allprefs['loc537']==2) || ($temp==956 && $allprefs['loc956']==2) || ($temp==1026 && $allprefs['loc1026']==2) || ($temp==890 && $allprefs['loc890']==2) || ($temp==822 && $allprefs['loc822']==2) || ($temp==685 && $allprefs['loc685']==2)){
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$gold=e_rand(50,150);
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(8,17);
					$expbonus=round($session['user']['dragonkills']*1.5);
					$expgain =($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You search through the smelly corpse and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					if ($allprefs['loc1147']==2) $allprefs['loc1147']=1;
					elseif ($allprefs['loc1010']==2) $allprefs['loc1010']=1;
					elseif ($allprefs['loc947']==2) $allprefs['loc947']=1;
					elseif ($allprefs['loc776']==2){
						$allprefs['loc776']=1;
						$allprefs['scroll4']=1;
						output("`n`&You continue to search through the troll's corpse and find a torn piece of paper.  It looks pretty worthless until you study it briefly.");
						addnav("Read Scroll 4","runmodule.php?module=signetd2&op=scroll4b");
					}elseif ($allprefs['loc82']==2) $allprefs['loc82']=1;
					elseif ($allprefs['loc55']==2) $allprefs['loc55']=1;
					elseif ($allprefs['loc59']==2) $allprefs['loc59']=1;
					elseif ($allprefs['loc537']==2) $allprefs['loc537']=1;
					elseif ($allprefs['loc327']==2) $allprefs['loc327']=1;
					elseif ($allprefs['loc685']==2){
						set_module_pref('pqtemp',719);
						$allprefs['loc685']=1;
						output("`n`0You also find `%2 gems`0!");
						$session['user']['gems']+=2;
					}elseif ($allprefs['loc956']==2){
						set_module_pref('pqtemp',957);
						$allprefs['loc956']=1;
					}elseif ($allprefs['loc1026']==2){
						set_module_pref('pqtemp',1025);
						$allprefs['loc1026']=1;
					}elseif ($allprefs['loc890']==2){
						set_module_pref('pqtemp',889);
						$allprefs['loc890']=1;
					}elseif ($allprefs['loc822']==2){
						set_module_pref('pqtemp',821);
						$allprefs['loc822']=1;
					}elseif ($allprefs['loc386']==2){
						output("`n`b`&You find keys to a hidden passageway on the southern wall.`b");
						$allprefs['loc386']=1;
					}
					set_module_pref('allprefs',serialize($allprefs));
				//random encounters
				}elseif ($temp==279 && $allprefs['loc279']==2){
					$allprefs['loc279']=1;
					set_module_pref('allprefs',serialize($allprefs));
					output("`n`n`&Before your final blow, the priest guards cast a spell of immobility on you.");
					output("`n`n`#'Ah, yes, you are worthy of seeing the High Priest. Please feel free to enter.'");
				}else{
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$gold=e_rand(50,150);
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(8,17);
					$expbonus=$session['user']['dragonkills'];
					$expgain =($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You search through the smelly corpse and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);				
				}
				if ((get_module_setting("healing")==1) && ($session['user']['hitpoints']<$session['user']['maxhitpoints']*.6)){
					$hpdown=$session['user']['maxhitpoints']-$session['user']['hitpoints'];
					switch(e_rand(1,13)){
						case 1:
							if ($hpdown>200) $hpdown=200;
							output("`n`@You find a healing potion and take a deep drink.  You feel your strength return.");
							output("`n`nYou gain `b%s hitpoints`b!",$hpdown);
							$session['user']['hitpoints']+=$hpdown;
						break;
						case 2:
						case 3:
							output("`n`@You notice the remnants of a healing potion.  You have a chance to take a drink!");
							$hpheal=round(e_rand($hpdown*.3,$hpdown*.7));
							if ($hpheal<=1) $hpheal=2;
							if ($hpheal>120) $hpheal=120;
							output("`n`nYou gain `b%s hitpoints`b!",$hpheal);
							$session['user']['hitpoints']+=$hpheal;
						break;
						case 4:
						case 5:
						case 6:
							output("`n`@You notice the remnants of a small healing potion.  You have a chance to take a quick drink!");;
							$hpheal=round(e_rand($hpdown*.1,$hpdown*.4));
							if ($hpheal<=1) $hpheal=2;
							if ($hpheal>50) $hpheal=50;
							output("`n`nYou gain `b%s hitpoints`b!",$hpheal);
							$session['user']['hitpoints']+=$hpheal;
						break;
						case 7:
							$hpleft=$session['user']['hitpoints'];
							$hphurt=round(e_rand($hpleft*.1,$hpleft*.4));
							if ($hphurt>50) $hphurt=50;
							if ($hphurt>1  && $hpleft>5){
								output("`n`@You find a healing potion and drink it down.  `\$Oh no! It was poisoned!");
								output("`n`n`@You lose `b`\$%s hitpoints`b`@!",$hphurt);
								$session['user']['hitpoints']-=$hphurt;
							}
						break;
						case 8:
						case 9:
						case 10:
						case 11:
						case 12:
						case 13:
						break;
					}
				}
       			addnav("Continue","runmodule.php?module=signetd2&loc=".get_module_pref('pqtemp'));
				$badguy=array();
				$session['user']['badguy']="";
			}elseif ($defeat){
				//doors
				if (($temp==970 && $allprefs['loc970']==2) ||($temp==865 && $allprefs['loc865']==2) || ($temp==1012 && $allprefs['loc1012']==2) || ($temp==946 && $allprefs['loc946']==2)) {
					output("A splinter severs a major blood vessel and you die.`n");
					if ($allprefs['loc970']==2) $allprefs['loc970']=0;
					elseif ($allprefs['loc865']==2) $allprefs['loc865']=0;
					elseif ($allprefs['loc946']==2) $allprefs['loc946']=0;
					if ($allprefs['loc1012']==2) {
						$allprefs['loc1012']=0;
						addnews("`% %s`5 has been slain trying to pick a lock on a door.",$session['user']['name']);
					}else{
						addnews("`% %s`5 has been slain trying to break down a door.",$session['user']['name']);
					}
					set_module_pref('allprefs',serialize($allprefs));
				//monsters
				}elseif (($temp==279 && $allprefs['loc279']==2)||($temp==1147 && $allprefs['loc1147']==2) || ($temp==1010 && $allprefs['loc1010']==2) || ($temp==947 && $allprefs['loc947']==2) || ($temp==776 && $allprefs['loc776']==2) || ($temp==386 && $allprefs['loc386']==2) || ($temp==82 && $allprefs['loc82']==2) || ($temp==55 && $allprefs['loc55']==2) || ($temp==59 && $allprefs['loc59']==2) || ($temp==327 && $allprefs['loc327']==2) || ($temp==537 && $allprefs['loc537']==2) || ($temp==956 && get_module_pref("loc956")==2) || ($temp==1026 && get_module_pref("loc1026")==2) || ($temp==890 && get_module_pref("loc890")==2) || ($temp==822 && get_module_pref("loc822")==2) || ($temp==685 && get_module_pref("loc685")==2)){
					if ($allprefs['loc1147']==2) $allprefs['loc1147']=0;
					elseif ($allprefs['loc1010']==2) $allprefs['loc1010']=0;
					elseif ($allprefs['loc947']==2) $allprefs['loc947']=0;
					elseif ($allprefs['loc776']==2) $allprefs['loc776']=0;
					elseif ($allprefs['loc386']==2) $allprefs['loc386']=0;
					elseif ($allprefs['loc82']==2) $allprefs['loc82']=0;
					elseif ($allprefs['loc55']==2) $allprefs['loc55']=0;
					elseif ($allprefs['loc59']==2) $allprefs['loc59']=0;
					elseif ($allprefs['loc327']==2) $allprefs['loc327']=0;
					elseif ($allprefs['loc537']==2) $allprefs['loc537']=0;
					elseif ($allprefs['loc279']==2) $allprefs['loc279']=0;
					elseif ($allprefs['loc956']==2) $allprefs['loc956']=0;
					elseif ($allprefs['loc1026']==2) $allprefs['loc1026']=0;
					elseif ($allprefs['loc890']==2) $allprefs['loc890']=0;
					elseif ($allprefs['loc822']==2) $allprefs['loc822']=0;
					elseif ($allprefs['loc685']==2) $allprefs['loc685']=0;
					set_module_pref('allprefs',serialize($allprefs));
					output("As you hit the ground the `^%s`4 runs away.",$badguy['creaturename']);
					output("`n`nYou `^lose 25% of your gold`3 on hand.");
					$gold=round($session['user']['gold']*.75);
					$session['user']['gold']=$gold; 
					addnews("`%%s`5 has been slain by an evil %s in a dungeon.",$session['user']['name'],$badguy['creaturename']);
				//random encounters
				}else{
					output("As you hit the ground the `^%s`3 runs away.",$badguy['creaturename']);
					addnews("`%%s`5 has been slain by an evil %s in a dungeon.",$session['user']['name'],$badguy['creaturename']);
					output("`n`nYou `^lose 25% of your gold`3 on hand.");
					$gold=round($session['user']['gold']*.75);
					$session['user']['gold']=$gold;
				}
		        $badguy=array();
				$session['user']['badguy']="";  
				$session['user']['hitpoints']=0;
				$session['user']['alive']=false;
				addnav("Continue","shades.php");
			}else{
					require_once("lib/fightnav.php");
					fightnav(true,false,"runmodule.php?module=signetd2");
			}
		}else{
			redirect("runmodule.php?module=signetd2&loc=".get_module_pref('pqtemp'));	
		}
	}
	page_footer();
}
function signetd2_misc($op) {
	$temp=get_module_pref("pqtemp");
	page_header("Aarde Temple");
	global $session;
	$op = httpget('op');
	$gold = httpget('goldp');
	$costalign=get_module_setting("costalign");
	$temp=get_module_pref("pqtemp");
	$level=$session['user']['level'];
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($op=="superuser"){
		require_once("modules/allprefseditor.php");
		allprefseditor_search();
		page_header("Allprefs Editor");
		$subop=httpget('subop');
		$id=httpget('userid');
		addnav("Navigation");
		addnav("Return to the Grotto","superuser.php");
		villagenav();
		addnav("Edit user","user.php?op=edit&userid=$id");
		modulehook('allprefnavs');
		$allprefse=unserialize(get_module_pref('allprefs',"signetd2",$id));
		if ($allprefse['randomp']=="") $allprefse['randomp']= 0;
		if ($allprefse['donated']=="") $allprefse['donated']= 0;
		if ($allprefse['donatenum']=="") $allprefse['donatenum']= 0;
		if ($allprefse['mazeturn']=="") $allprefse['mazeturn']= 0;
		set_module_pref('allprefs',serialize($allprefse),'signetd2',$id);
		if ($subop!='edit'){
			$allprefse=unserialize(get_module_pref('allprefs',"signetd2",$id));
			$allprefse['complete']= httppost('complete');
			$allprefse['reset']= httppost('reset');
			$allprefse['randomp']= httppost('randomp');
			$allprefse['earthsignet']= httppost('earthsignet');
			$allprefse['scroll4']= httppost('scroll4');
			$allprefse['loc334']= httppost('loc334');
			$allprefse['loc685']= httppost('loc685');
			$allprefse['loc1098']= httppost('loc1098');
			$allprefse['donated']= httppost('donated');
			$allprefse['donatenum']= httppost('donatenum');
			$allprefse['loc55']= httppost('loc55');
			$allprefse['loc59']= httppost('loc59');
			$allprefse['loc82']= httppost('loc82');
			$allprefse['loc109']= httppost('loc109');
			$allprefse['loc109b']= httppost('loc109b');
			$allprefse['loc163']= httppost('loc163');
			$allprefse['loc279']= httppost('loc279');
			$allprefse['loc327']= httppost('loc327');
			$allprefse['loc334b']= httppost('loc334b');
			$allprefse['loc381']= httppost('loc381');
			$allprefse['loc386']= httppost('loc386');
			$allprefse['loc496']= httppost('loc496');
			$allprefse['loc537']= httppost('loc537');
			$allprefse['loc537b']= httppost('loc537b');
			$allprefse['loc556']= httppost('loc556');
			$allprefse['loc776']= httppost('loc776');
			$allprefse['loc822']= httppost('loc822');
			$allprefse['loc841']= httppost('loc841');
			$allprefse['loc843']= httppost('loc843');
			$allprefse['loc865']= httppost('loc865');
			$allprefse['loc890']= httppost('loc890');
			$allprefse['loc946']= httppost('loc946');
			$allprefse['loc947']= httppost('loc947');
			$allprefse['loc956']= httppost('loc956');
			$allprefse['loc970']= httppost('loc970');
			$allprefse['loc1010']= httppost('loc1010');
			$allprefse['loc1010b']= httppost('loc1010b');
			$allprefse['loc1012']= httppost('loc1012');
			$allprefse['loc1026']= httppost('loc1026');
			$allprefse['loc1082']= httppost('loc1082');
			$allprefse['loc1098']= httppost('loc1098');
			$allprefse['loc1147']= httppost('loc1147');
			$allprefse['loc1148']= httppost('loc1148');
			$allprefse['mazeturn']= httppost('mazeturn');
			$allprefse['header']= httppost('header');
			set_module_pref('allprefs',serialize($allprefse),'signetd2',$id);
			output("Allprefs Updated`n");
			$subop="edit";
		}
		if ($subop=="edit"){
			require_once("lib/showform.php");
			$form = array(
				"Aarde Temple,title",
				"complete"=>"Has player completed the temple?,bool",
				"reset"=>"Have the preferences been reset by visiting Wasser's Castle?,bool",
				"Encounters,title",
				"randomp"=>"How many random monsters has player encountered so far?,int",
				"earthsignet"=>"*`QReceived the Earth Signet?,bool",
				"scroll4"=>"*Found Scroll 4?,bool",
				"loc334"=>"*Passed Location 334?,bool",
				"loc685"=>"*Passed Location 685?,enum,0,No,1,Yes,2,In Process",
				"* Finish these points and the temple will be closed to this player,note",
				"loc1098"=>"Ready to see the High Priest?,bool",
				"donated"=>"How much has the player donated?,int",
				"donatenum"=>"How many times has the player donated?,int",
				"loc55"=>"Passed Location 55?,enum,0,No,1,Yes,2,In Process",
				"loc59"=>"Passed Location 59?,enum,0,No,1,Yes,2,In Process",
				"loc82"=>"Passed Location 82?,enum,0,No,1,Yes,2,In Process",
				"loc109"=>"Passed Location 109?,bool",
				"loc109b"=>"Passed Location 109b?,bool",
				"loc163"=>"Passed Location 163?,bool",
				"loc279"=>"Passed Location 279?,enum,0,No,1,Yes,2,In Process",
				"loc327"=>"Passed Location 327?,enum,0,No,1,Yes,2,In Process",
				"loc334b"=>"Passed Location 334b?,bool",
				"loc381"=>"Passed Location 381?,bool",
				"loc386"=>"Passed Location 386?,enum,0,No,1,Yes,2,In Process",
				"loc496"=>"Passed Location 496?,bool",
				"loc537"=>"Passed Location 537?,enum,0,No,1,Yes,2,In Process",
				"loc537b"=>"Passed Location 537b?,bool",
				"loc556"=>"Passed Location 556?,bool",
				"loc776"=>"Passed Location 776?,bool",
				"loc822"=>"Passed Location 822?,enum,0,No,1,Yes,2,In Process",
				"loc841"=>"Passed Location 841?,bool",
				"loc843"=>"Passed Location 843?,bool",
				"loc865"=>"Passed Location 865?,enum,0,No,1,Yes,2,In Process",
				"loc890"=>"Passed Location 890?,enum,0,No,1,Yes,2,In Process",
				"loc946"=>"Passed Location 946?,enum,0,No,1,Yes,2,In Process",
				"loc947"=>"Passed Location 947?,enum,0,No,1,Yes,2,In Process",
				"loc956"=>"Passed Location 956?,enum,0,No,1,Yes,2,In Process",
				"loc970"=>"Passed Location 970?,enum,0,No,1,Yes,2,In Process",
				"loc1010"=>"Passed Location 1010 without key?,enum,0,No,1,Yes,2,In Process",
				"loc1010b"=>"Passed Location 1010b with key?,bool",
				"loc1012"=>"Passed Location 1012?,enum,0,No,1,Yes,2,In Process",
				"loc1026"=>"Passed Location 1026?,enum,0,No,1,Yes,2,In Process",
				"loc1082"=>"Passed Location 1082?,bool",
				"loc1098"=>"Passed Location 1082?,bool",
				"loc1147"=>"Passed Location 1147?,enum,0,No,1,Yes,2,In Process",
				"loc1148"=>"Passed Location 1148?,bool",
				"Maze,title",
				"mazeturn"=>"Maze Return,int",
				"header"=>"Which header array is the player at?,range,0,20,1",
			);
			$allprefse=unserialize(get_module_pref('allprefs',"signetd2",$id));
			rawoutput("<form action='runmodule.php?module=signetd2&op=superuser&userid=$id' method='POST'>");
			showform($form,$allprefse,true);
			$click = translate_inline("Save");
			rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
			rawoutput("</form>");
			addnav("","runmodule.php?module=signetd2&op=superuser&userid=$id");
		}
	}
	if ($op=="reset"){
		$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
		$allprefsd1['loc54']="";
		$allprefsd1['loc113']="";
		$allprefsd1['loc113b']="";
		$allprefsd1['loc303']="";
		$allprefsd1['loc333']="";
		$allprefsd1['loc453']="";
		$allprefsd1['loc465']="";
		$allprefsd1['loc494']="";
		$allprefsd1['loc521']="";
		$allprefsd1['loc593']="";
		$allprefsd1['loc623']="";
		$allprefsd1['loc673']="";
		$allprefsd1['loc677']="";
		$allprefsd1['loc685']="";
		$allprefsd1['loc689']="";
		$allprefsd1['loc711']="";
		$allprefsd1['loc759']="";
		$allprefsd1['loc787']="";
		$allprefsd1['loc891']="";
		$allprefsd1['loc899']="";
		$allprefsd1['loc973']="";
		$allprefsd1['loc983']="";
		$allprefsd1['loc1006']="";
		$allprefsd1['loc1008']="";
		$allprefsd1['loc1009']="";
		$allprefsd1['loc1011']="";
		$allprefsd1['loc1015']="";
		$allprefsd1['loc1133']="";
		$allprefsd1['loc1152']="";
		$allprefsd1['loc1197']="";
		$allprefsd1['loc1199']="";
		$allprefsd1['loc1216']="";
		$allprefsd1['loc1133b']="";
		$allprefsd1['mazeturn']="";
		$allprefsd1['loc411']="";
		$allprefsd1['loc1287']="";
		$allprefsd1['randomp']="";
		$allprefsd1['header']="";
		$allprefsd1['reset']=1;
		set_module_pref('allprefs',serialize($allprefsd1),'signetd1');
		clear_module_pref("maze","signetd1");
		clear_module_pref("pqtemp","signetd1");
		$allprefsd5=unserialize(get_module_pref('allprefs','signetd5'));
		if ($allprefsd5['powersignet']==1) redirect("runmodule.php?module=signetd5&op=eg1b");
		else redirect("runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="1098"){
		output("A priest sitting here says `#'You must be clean to see the High Priest.'`n`n");
		output("`#'Would you give a small offering?'`0`n`n");
		if (is_module_active('alignment') && get_module_setting("impalign")==1){
			addnav(array("Donate `^%s Gold",$costalign),"runmodule.php?module=signetd2&op=1098b");
		}else{
			addnav("Donate `^5000 Gold","runmodule.php?module=signetd2&op=1098d");
			addnav("Donate `^1000 Gold","runmodule.php?module=signetd2&op=1098e");
			addnav("Donate `^500 Gold","runmodule.php?module=signetd2&op=1098f");
			addnav("Donate `^100 Gold","runmodule.php?module=signetd2&op=1098g");
		}
		addnav("Donate `%Pocket Lint","runmodule.php?module=signetd2&op=1098c");
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-34));
	}
	if ($op=="1098b"){
		if ($session['user']['gold']<$costalign){
			output("`#'Well, I don't think you have that much gold to give our temple.  Please come back when you're funds are a little higher.'");
		}else{
			$allprefs['donatenum']=$allprefs['donatenum']+1;
			$allprefs['donated']=$allprefs['donated']+$costalign;
			$session['user']['gold']-=$costalign;
			output("`#'Thank you for your generous donation.'`n`n`0You feel pretty good about yourself.");
			if ($allprefs['donatenum']<get_module_setting("alignmax")) increment_module_pref("alignment",1,"alignment");			
			//donated big amount more than 5 times or they are good alignement
			if ($allprefs['donatenum']>5 || get_module_pref("alignment","alignment")>get_module_setting("goodalign","alignment")){
				output("`n`n`#'Your generosity proves that you are worthy of meeting the High Priest.'");
				$allprefs['loc1098']=1;
			}
			set_module_pref('allprefs',serialize($allprefs));
		}
		if ($session['user']['gold']>=get_module_setting("costalign")){
			output("`#'Would you like to donate some more?'");
			addnav("Donate More","runmodule.php?module=signetd2&op=1098");
		}
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-34));
	}
	if ($op=="1098c"){
		increment_module_setting("nodonate",1);
		$lint=get_module_setting("nodonate")/10;
		output("You hand the priest some of your pocket lint and look at him expectantly for some kind of blessing.");
		output("`#`n`n'Thank you very much for the pocket lint.'");
		output("`n`n`0The priest puts the lint in a pile next to him which is");
		if ($lint==1) output("one inch tall."); 
		elseif ($lint<=4) output(" %s inches tall.",$lint);
		else output("shockingly over %s inches tall.",$lint);
		if ($lint>=24){
			output("`n`nThe priest looks at the towering pile of pocket lint and takes out a match.");
			output("He tosses the match on the pile and a huge `\$f`Ql`^a`Qm`\$e`0 erupts.  He smiles happily at the sight.");
			set_module_setting("nodonate",0);
		}
		addnav("Donate Some `^Gold","runmodule.php?module=signetd2&op=1098");
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-34));		
	}
	if ($op=="1098d"){
		if ($session['user']['gold']<5000 && $session['user']['gold']>=100 ){
			output("`#'Well, I don't think you have that much money to give me.  Please consider an amount more within your means.'");
		}elseif ($session['user']['gold']<5000){
			output("`#'Well, I don't think you have that much gold to give our temple.  Please come back when you're funds are a little higher.'");		
			addnav("Donate","runmodule.php?module=signetd2&op=1098");
		}else{
			output("He takes your `^5000 gold`0.`n`n`#'Thank you for your very generous donation.'");
			output("`n`n`#'Your generosity proves that you are worthy of meeting the High Priest.'");
			$allprefs['loc1098']=1;			
			addnav("Donate Some More","runmodule.php?module=signetd2&op=1098");
			$session['user']['gold']-=5000;
			$allprefs['donated']=$allprefs['donated']+5000;
			$allprefs['donatenum']=$allprefs['donatenum']+1;
			set_module_pref('allprefs',serialize($allprefs));
		}
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-34));
	}
	if ($op=="1098e"){
		if ($session['user']['gold']<1000 && $session['user']['gold']>=100 ){
			output("`#'Well, I don't think you have that much money to give me.  Please consider an amount more within your means.'");
			addnav("Donate","runmodule.php?module=signetd2&op=1098");
		}elseif ($session['user']['gold']<1000){
			output("`#'Well, I don't think you have that much gold to give our temple.  Please come back when you're funds are a little higher.'");		
		}else{
			addnav("Donate Some More","runmodule.php?module=signetd2&op=1098");
			output("He takes your `^1000 gold`0.`n`n`#'Thank you for your generous donation.'");
			$session['user']['gold']-=1000;
			$allprefs['donated']=$allprefs['donated']+1000;
			$allprefs['donatenum']=$allprefs['donatenum']+1;
			if ($allprefs['donated']>=3000){
				output("`n`n`#'Your generosity proves that you are worthy of meeting the High Priest.'");
				$allprefs['loc1098']=1;	
			}else{
				output("`n`n`#'Your kind donations show you are on your way to becoming worthy of meeting the High Priest.");
				output("Perhaps you would like to give some more?'");
			}
			set_module_pref('allprefs',serialize($allprefs));
		}
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-34));
	}
	if ($op=="1098f"){
		if ($session['user']['gold']<500 && $session['user']['gold']>=100 ){
			output("`#'Well, I don't think you have that much money to give me.  Please consider an amount more within your means.'");
			addnav("Donate","runmodule.php?module=signetd2&op=1098");
		}elseif ($session['user']['gold']<500){
			output("`#'Well, I don't think you have that much gold to give our temple.  Please come back when you're funds are a little higher.'");		
		}else{
			addnav("Donate Some More","runmodule.php?module=signetd2&op=1098");
			output("He takes your `^500 gold`0.`n`n`#'Thank you for your nice donation.'");
			$allprefs['donated']=$allprefs['donated']+500;
			$allprefs['donatenum']=$allprefs['donatenum']+1;
			$session['user']['gold']-=500;
			if ($allprefs['donated']>=3000){
				output("`n`n`#'Your generosity proves that you are worthy of meeting the High Priest.'");
				$allprefs['loc1098']=1;	
			}else{
				output("`n`n`#'Your kind donations show you are on your way to becoming worthy of meeting the High Priest.");
				output("Perhaps you would like to give some more?'");
			}
			set_module_pref('allprefs',serialize($allprefs));
		}
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-34));
	}
	if ($op=="1098g"){
		if ($session['user']['gold']<100){
			output("`#'Well, I don't think you have that much gold to give our temple.  Please come back when you're funds are a little higher.'");		
		}else{
			addnav("Donate Some More","runmodule.php?module=signetd2&op=1098");
			output("He takes your `^100 gold`0.`n`n`#'Thank you for your donation.'");
			$session['user']['gold']-=100;
			$allprefs['donated']=$allprefs['donated']+100;
			$allprefs['donatenum']=$allprefs['donatenum']+1;
			if ($allprefs['donated']>=3000){
				output("`n`n`#'Your generosity proves that you are worthy of meeting the High Priest.'");
				$allprefs['loc1098']=1;	
			}else{
				output("`n`n`#'Your kind donations show you are on your way to becoming worthy of meeting the High Priest.");
				output("Perhaps you would like to give some more?'");
			}
			set_module_pref('allprefs',serialize($allprefs));
		}
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-34));
	}
	if ($op=="1148" || $op=="1082" || $op=="1012" || $op=="841"){
		output("The door ahead is trapped.  Would you like to disarm it?");
		if ($temp==1148) {
			addnav("Disarm","runmodule.php?module=signetd2&op=1148b");
			addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+1));
		}elseif ($temp==1082){
			addnav("Disarm","runmodule.php?module=signetd2&op=1082b");
			addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
		}elseif ($temp==1012){
			addnav("Disarm","runmodule.php?module=signetd2&op=trapdoor");
			addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+1));
		}elseif ($temp==841){
			addnav("Disarm","runmodule.php?module=signetd2&op=841b");
			addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+34));
		}
	}
	if ($op=="1148b"){
		if($level>13) $trap=4;
		elseif($level>10) $trap=3;
		elseif($level>7) $trap=2;
		elseif($level>4) $trap=1;
		else $trap=1;
		switch(e_rand($trap,10)){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				output("You're unable to disarm the trap.");
				$hitpoints=round($session['user']['hitpoints']*.08);
				if ($hitpoints>0) output("`n`nA cloud of `@poison gas`0 explodes from the trap.  You struggle to breath as you feel a burn in your lungs. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				$session['user']['hitpoints']-=$hitpoints;
				increment_module_pref("pqtemp",+1);
			break;
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
				output("You successfully disarm the trap.`n`n");
				$allprefs['loc1148']=1;
				set_module_pref('allprefs',serialize($allprefs));
			break;
		}
		addnav("Continue","runmodule.php?module=signetd2&loc=".get_module_pref('pqtemp'));
	}
	if ($op=="1082b"){
		if($level>13) $trap=4;
		elseif($level>10) $trap=3;
		elseif($level>7) $trap=2;
		elseif($level>4) $trap=1;
		else $trap=1;
		switch(e_rand($trap,10)){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				switch(e_rand(1,3)){
					case 1:
						output("You're unable to disarm the trap.");
						$hitpoints=round($session['user']['hitpoints']*.1);
						if ($hitpoints>0) output("`n`nA cloud of `@poison gas`0 explodes from the trap.  You struggle to breath as you feel a burn in your lungs. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
						$session['user']['hitpoints']-=$hitpoints;
						increment_module_pref("pqtemp",-1);
					break;
					case 2:
						output("Although you fail to disarm the trap, it turns out to be a dud and you're able to open the door.");
						$allprefs['loc1082']=1;
					break;
					case 3:
						output("You fail to disarm the trap.");
						increment_module_pref("pqtemp",-1);
					break;
				}
			break;
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
				output("You successfully disarm the trap.`n`n");
				$allprefs['loc1082']=1;
			break;
		}
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd2&loc=".get_module_pref('pqtemp'));
	}
	if ($op=="1010"){
		output("Some sly looking orcs ask `3'Would you like to buy a secret?'`0");
		addnav("Attack Them","runmodule.php?module=signetd2&op=orcs");
		addnav("Talk Some More","runmodule.php?module=signetd2&op=1010b");
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+1));
	}
	if ($op=="1010b"){
		output("`3'It's going to cost you `^85 gold`3 for our secret.'`n`n");
		if ($session['user']['gold']<85) {
			output("'We can wait until you get some more gold.'");
		}else{
			output("`0Would you like to buy their secret?");
			addnav("Buy Their Secret","runmodule.php?module=signetd2&op=1010c");
		}
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+1));
	}
	if ($op=="1010c"){
		output("`3'Here you go!'");
		output("`n`n`0The Orcs hand you a `%key`0 and wander off.");
		$allprefs['loc1010b']=$allprefs['loc1010b']+1;
		set_module_pref('allprefs',serialize($allprefs));
		$session['user']['gold']-=85;
		addnav("Leave","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="843"){
		output("You try to open the door but no matter what you do you can't get it open.");
		addnav("Continue","runmodule.php?module=signetd2&loc=".($temp+34));
	}
	if ($op=="843b"){
		output("The key the orcs sold to you opens the door!");
		$allprefs['loc843']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="776"){
		output("A troll chained to the wall says `@'If you let me go I will give you a valuable scroll.'`0");
		addnav("Attack Him","runmodule.php?module=signetd2&op=troll");
		addnav("Let Him Go","runmodule.php?module=signetd2&op=776b");
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="776b"){
		output("You release the troll and he hands you a torn piece of paper.  It looks pretty worthless until you study it briefly.");
		$allprefs['loc776']=1;
		$allprefs['scroll4']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);
		addnav("Read Scroll 4","runmodule.php?module=signetd2&op=scroll4b");
	}
	if ($op=="841b"){
		if($level>13) $trap=4;
		elseif($level>10) $trap=3;
		elseif($level>7) $trap=2;
		elseif($level>4) $trap=1;
		else $trap=1;
		switch(e_rand($trap,10)){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				switch(e_rand(1,6)){
					case 1:
					case 2:
					case 3:
						output("You disarm the trap and find the lock mechanism is made of gold and is worth `^45 gold`0!");
						$session['user']['gold']+=45;
					break;
					case 4:
					case 5:
						output("AYou disarm the trap.  The adrenaline gives you an `@extra turn`0!");
						$session['user']['turns']++;
					break;
					case 6:
						output("It turns out it wasn't a trap, but rather a `%gem`0 jamming the door!");
						$session['user']['gems']++;
					break;
				}
			break;
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
				output("You successfully disarm the trap.`n`n");
			break;
		}
		$allprefs['loc841']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="799"){
		output("You try to jump into the telporter but nothing happens.  You look around and notice a sign.");
		output("`n`n`c`b`%Only the worthy can see the High Priest`b`c`n`0");
		output("Oh well...  Seems like you're not quite worthy yet.");
		addnav("Continue","runmodule.php?module=signetd2&loc=".($temp-34));
	}
	if ($op=="556"){	
		output("The room is empty except for a locked box.");
		output("`n`nWhat would you like to do?");
		addnav("Pick Lock","runmodule.php?module=signetd2&op=556b");
		addnav("Burn Box","runmodule.php?module=signetd2&op=556c");
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-34));
	}
	if ($op=="556b"){
		output("No matter how hard you try, you cannot pick the lock.");
		addnav("Continue","runmodule.php?module=signetd2&loc=".($temp-34));
	}
	if ($op=="556c"){
		if ($allprefs['loc109']==0){
			output("The box will not burn.");
			addnav("Continue","runmodule.php?module=signetd2&loc=".($temp-34));
		}else{
			output("You take out the ember from the High Priest and place it on top of the box.");
			output("`n`nSoon enough, the box burns away leaving just ashes.");
			output("When you sift through the ashes, you feel a burning sensation on your wrist.");
			output("`n`nYou look at your wrist and notice that you now bear the `QEarth Signet`0.");
			output("`n`n`c`^Congratulations.  You have received the second of the `b`3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signets`b.`c");
			$allprefs['loc556']=1;
			$allprefs['earthsignet']=1;
			set_module_pref('allprefs',serialize($allprefs));
			$allprefssale=unserialize(get_module_pref('allprefssale',"signetsale"));
			set_module_pref("hoftemp",2200+$allprefssale['completednum'],"signetsale");
			addnews("`@%s`@ was marked with the `QEarth Signet`@ in the `^Aarde Temple`@.",$session['user']['name']);
			addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);	
		}
	}
	if ($op=="496"){
		output("You see a dresser full of clothing.  Would you like to search through it?");
		addnav("Search","runmodule.php?module=signetd2&op=496b");
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="496b"){
		if (is_module_active('alignment')){
			output("You feel a twinge of `\$Evil`0 for going through the maid's clothing drawer.`n`n");
			increment_module_pref("alignment",-3,"alignment");
		}
		$allprefs['loc496']=1;
		set_module_pref('allprefs',serialize($allprefs));
		switch(e_rand(1,6)){
			case 1:
			case 2:
			case 3:
				output("You search through the drawer and find a little purse holding `^125 gold`0!");
				$session['user']['gold']+=125;
			break;
			case 4:
			case 5:
				output("You search through the drawer and find a shiny little `%gem`0!");
				$session['user']['gems']++;
			break;
			case 6:
				output("The maid walks in and sees you! She slaps you and tells you to leave.`n`n");
				if ($session['user']['turns']>0){
					output("You stand in stunned silence and embarrassment and `@lose a turn`0.");
					$session['user']['turns']--;
				}else{
					$hps=round($session['user']['hitpoints']*.9);
					output("You feel the sting and rub your face.  You lose `\$%s hitpoints`0.",$hps);
					$session['user']['hitpoints']-=$hps;
				}
			break;
		}
		addnav("Leave","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="386"){
		output("An old man is sweeping with a broom.`n`nWhat will you do?");
		addnav("Attack Him","runmodule.php?module=signetd2&op=oldman");
		addnav("Ignore Him","runmodule.php?module=signetd2&op=386b");
	}
	if ($op=="386b"){
		output("He unlocks a secret door on the southern wall and disappears.");
		$allprefs['loc386']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="163"){
		output("You see a key on the wall.  Would you like to take it?");
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-34));
		addnav("Take the Key","runmodule.php?module=signetd2&op=163b");
	}
	if ($op=="163b"){
		output("Being attracted to shiny objects, you toss the key in your pocket.");
		$allprefs['loc163']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="537"){
		output("You stand before a very nice");
		if ($allprefs['loc537']==0) output("sink and ");
		output("toilet.  What would you like to do?");
		addnav("Use Toilet","runmodule.php?module=signetd2&op=537b");
		if ($allprefs['loc537']==0) addnav("Kick the Sink","runmodule.php?module=signetd2&op=537c");
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="537b"){
		$going=e_rand(1,100);
		if ($allprefs['loc537b']==1 || $going==1){
			output("Okay, enough punishing this poor toilet for the day.");
			$allprefs['loc537b']=1;
		}elseif ($going<90){
			output("You don't have to go.");
		}else{
			if (is_module_active("drinks")){
				if (get_module_pref("drunkeness","drinks")>0){
					$args = array(
						'soberval'=>0.9,
						'sobermsg'=>"You soberup a little bit.",
						'schema'=>"module-signetd2",
					);
					modulehook("soberup", $args);
					$allprefs['loc537b']=1;
					output_notl("`n`n");
				}
			}
			output("You decide to pass the time and read some of the grafitti:`n`n");
			switch(e_rand(1,6)){
				case 1:
				case 2:
				case 3:
					output("`c`\$'The `@Green Dragon's`\$ momma sniffs faerie dust'`c");
				break;
				case 4:
				case 5:
					output("`c`#'For a good time, get off the toilet and go get a life.'`c");
				break;
				case 6:
					output("`c`%'Do you realize the odds of reading this graffiti is really small?'`c");
				break;
			}
		}
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="537c"){
		output("You wind up and give the sink a big kick...`n`n");
		switch(e_rand(1,6)){
			case 1:
			case 2:
			case 3:
				output("and the sink breaks!  You see a pretty `%gem`0 on the ground.");
				$session['user']['gems']++;
				$allprefs['loc537']=1;
				addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);
			break;
			case 4:
			case 5:
				output("and you disturb a rat!  Looks like you're in for a fight...");
				addnav("Rat `\$Fight","runmodule.php?module=signetd2&op=rat");
			break;
			case 6:
				addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);
				$allprefs['loc537']=1;
				output("`QOuch!!!`0  Turns out this is one of those high quality sinks.`n`n");
				if ($session['user']['turns']>0){
					output("You rub your big toe for a `@turn`0.");
					$session['user']['turns']--;
				}else{
					$hps=round($session['user']['hitpoints']*.9);
					if ($hps>0)output("You feel like you broke a toe.  You lose `\$%s hitpoints`0.",$hps);
					else output("I hope you've learned your lesson.");
					$session['user']['hitpoints']-=$hps;
				}
			break;	
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="334"){
		if ($allprefs['loc334b']==0){
			output("An old gnome cooking here says `#'I like Adventurers.  Do you know the Great Kilmor?'");
			addnav("Yes","runmodule.php?module=signetd2&op=334b");
			addnav("No","runmodule.php?module=signetd2&op=334c");
			addnav("Ilfin Rocs","runmodule.php?module=signetd2&op=334d");
			addnav("Nifle Scro","runmodule.php?module=signetd2&op=334e");
			addnav("Finle Cors","runmodule.php?module=signetd2&op=334f");
			addnav("Fline Sroc","runmodule.php?module=signetd2&op=334g");
			addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
		}else{
			output("The old gnome turns to you with an annoyed look.");
			output("`n`n`#'Listen, I don't have time for these games. If you want to talk to me it's going to cost you `^100 gold`#.'");
			output("`0`n`nWhat would you like to do?");
			addnav("Pay `^100 Gold","runmodule.php?module=signetd2&op=334h");
			addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
		}
	}
	if ($op=="334h"){
		if ($session['user']['gold']>=100){
			output("The Gnome takes your gold and looks at you and asks you again `#'Do you know the Great Kilmor?'");
			addnav("Yes","runmodule.php?module=signetd2&op=334b");
			addnav("No","runmodule.php?module=signetd2&op=334c");
			addnav("Ilfin Rocs","runmodule.php?module=signetd2&op=334d");
			addnav("Nifle Scro","runmodule.php?module=signetd2&op=334e");
			addnav("Finle Cors","runmodule.php?module=signetd2&op=334f");
			addnav("Fline Sroc","runmodule.php?module=signetd2&op=334g");
			$session['user']['gold']-=100;
		}else{
			output("Since you don't have enough gold, the Gnome continues to ignore you.");
		}
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="334b"){
		output("`#'Isn't he wonderful?'`0 he says.");
		$allprefs['loc334b']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="334c"){
		output("`#'You should meet him,'`0 he says.");
		$allprefs['loc334b']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="334d" || $op=="334f" || $op=="334g"){
		output("The gnome ignores you.");
		$allprefs['loc334b']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
	}	
	if ($op=="334e"){
		output("`#'Tell him that Niscosnat said `3'Hello'`#, okay?'`0 he says.");
		$allprefs['loc334']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Leave","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="381"){
		if ($allprefs['loc163']==0){
			output("The door is locked and cannot be opened.  If only you had a key that fit the lock...");
			addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+34));
		}else{
			output("The door is locked but your shiny key from the natural cavern opens it!");
			$allprefs['loc381']=1;
			set_module_pref('allprefs',serialize($allprefs));
			addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);
		}
	}
	if ($op=="279"){
		output("This door is guarded by two robed men.  What do you do?");
		if ($session['user']['gold']>0) addnav("Bribe Them with `^Gold","runmodule.php?module=signetd2&op=279b");
		elseif ($session['user']['gems']>0) addnav("Bribe Them with a `%Gem","runmodule.php?module=signetd2&op=279b");
		else addnav("Bribe Them","runmodule.php?module=signetd2&op=279b");
		addnav("Attack Them","runmodule.php?module=signetd2&op=guards");
		addnav("Request Audience","runmodule.php?module=signetd2&op=279d");
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+34));
	}
	if ($op=="279b"){
		$hps=round($session['user']['hitpoints']*.1);
		$session['user']['hitpoints']*=.9;
		output("The guards look at your paltry bribe and laugh.`n`n`#'Do you think the guards of the High Priest can be so easily bribed?!?'");
		output("`n`n'Why don't you go and think about how petty that is?'");
		output("`n`n`0The guards cast a spell of teleporation.  You feel a sting as they cast the spell.");
		output("`n`nYou lose `\$%s hitpoints`0.",$hps);
		addnav("Continue","runmodule.php?module=signetd2&loc=1273");
	}
	if ($op=="279c"){
		if ($allprefs['loc109']==0){
			output("The guards insist that you finish your meeting with the High Priest before you leave.");
			addnav("Continue","runmodule.php?module=signetd2&loc=".($temp-34));
		}else{
			output("The guards prevent anyone from entering to speak to the High Priest without a word.");
			$allprefs['loc279']=1;
			set_module_pref('allprefs',serialize($allprefs));
			addnav("Continue","runmodule.php?module=signetd2&loc=".($temp+34));
		}
	}
	if ($op=="279d"){
		output("You ask most eloquently for audience with the High Priest.");
		output("`n`nThe guards ignore you.");
		output("`n`nHa! I bet you thought that would work, didn't you?");
		addnav("Continue","runmodule.php?module=signetd2&op=279");
	}
	if ($op=="109"){
		if ($allprefs['loc109']==1) {
			output("The High Priest does not take notice of you anymore.");
		}else{
			output("There is a distinguised priest sitting here.");
			if ($allprefs['loc109b']==0) addnav("Attack Him","runmodule.php?module=signetd2&op=109b");
			addnav("Talk to Him","runmodule.php?module=signetd2&op=109c");		
		}
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+34));
	}
	if ($op=="109b"){
		if (is_module_active('alignment')) increment_module_pref("alignment",-5,"alignment");	
		output("The priest barely takes notice of you.  He raises a hand slightly and suddenly you're held immobilized.");
		output("`n`nClearly you're in front of a great power.  Perhaps you should reconsider your options...");
		$allprefs['loc109b']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Talk to Him","runmodule.php?module=signetd2&op=109c");		
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+34));	
	}
	if ($op=="109c"){
		output("The High Priest speaks to you.");
		output("`n`n`&'I know you have worked hard for this meeting with me.");
		output("I also know about your desire to acquire all of the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signets`& in order to defeat Mierscri.'");
		output("`n`n`0A slight shiver runs through his body at the mention of such evil.");
		output("`n`n`&'I believe you will need this to help you accomplish your goals.'");
		output("`n`n`0The High Priest hands you a package.");
		addnav("Continue","runmodule.php?module=signetd2&op=109d");
	}
	if ($op=="109d"){
		output("You accept the package and look at the High Priest inquisitively.");
		output("`n`n`&'That is an ember taken from the charred remains of Tellusa's Staff; the Mage that created the `QEarth Signet`&.");
		output("I believe it will help you.  Good Luck.'");
		$allprefs['loc109']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd2&loc=".($temp+34));
	}
	if ($op=="lcoff"){
		output("There is a coffin here.");
		addnav("Open It","runmodule.php?module=signetd2&op=lcoffb");	
		addnav("Leave It","runmodule.php?module=signetd2&loc=".($temp+1));
	}
	if ($op=="lcoffb"){
		output("The Coffin is empty.");	
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+1));
	}
	if ($op=="rcoff"){
		output("There is a coffin here.");
		addnav("Open It","runmodule.php?module=signetd2&op=rcoffb");	
		addnav("Leave It","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="rcoffb"){
		output("The Coffin is empty.");	
		addnav("Leave","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="vamp"){
		output("There is a coffin here.");
		addnav("Open It","runmodule.php?module=signetd2&op=vampb");	
		if ($temp==956) addnav("Leave It","runmodule.php?module=signetd2&loc=".($temp+1));
		elseif ($temp==1026 || $temp==890|| $temp==822) addnav("Leave It","runmodule.php?module=signetd2&loc=".($temp-1));
	}
	if ($op=="vampb"){
		if (get_module_pref("visitnum","strigoitower")>=2){
			if ($temp==956) $allprefs['loc956']=1;
			elseif ($temp==1026) $allprefs['loc1026']=1;
			elseif ($temp==890) $allprefs['loc890']=1;
			elseif ($temp==822) $allprefs['loc822']=1;
			set_module_pref('allprefs',serialize($allprefs));
			if ($temp==956) addnav("Continue","runmodule.php?module=signetd2&loc=".($temp+1));
			elseif ($temp==1026 || $temp==890|| $temp==822) addnav("Continue","runmodule.php?module=signetd2&loc=".($temp-1));
			output("You prepare one of the stakes that you found in the forest from `\$Strigoi's Tower`0.");
			output("`n`nYou open the coffin and drive the stake into the heart of the vampire making quick work of it!");
			output("`n`nWith a quick search of the coffin and you find");
			switch(e_rand(1,8)){
				case 1:
				case 2:
				case 3:
					output("a `%gem`0.");
					$session['user']['gems']++;
				break;
				case 4:
				case 5:
					$gold=e_rand(200,300);
					output("`^%s gold`0.",$gold);
					$session['user']['gold']+=$gold;
				break;
				case 6:
				case 7:
				case 8:
					output("nothing.");
				break;				
			}
		}else{
			output("A vampire attacks you!");
			addnav("Vampire `\$Fight","runmodule.php?module=signetd2&op=vampire");
		}			
	}
	if ($op=="685"){
		output("There is a coffin here.");
		addnav("Open It","runmodule.php?module=signetd2&op=685b");	
		addnav("Leave It","runmodule.php?module=signetd2&loc=".($temp+34));
	}
	if ($op=="685b"){
		if ($allprefs['loc685']==0){
			$gold=e_rand(800,1200);
			$session['user']['gold']+=$gold;
			$gems=e_rand(2,5);
			$session['user']['gems']+=$gems;
			output("You find `^%s gold`0 and `%%s gems`0!",$gold,$gems);
			output("`n`nAs you count all your wonderful treasure, you are attacked by a `\$Huge Vampire`0!!!");
			addnav("Huge Vampire `\$Fight","runmodule.php?module=signetd2&op=bvampire");
		}else{
			output("The Coffin is empty.");	
			addnav("Leave","runmodule.php?module=signetd2&loc=".($temp+34));		
		}
	}
	if ($op=="scroll1b"){
		output("`c`b`^The Aria Dungeon`0`c`b`n");
		output("The Aria Dungeon was once the center of a small community of dwarves.  About 15 years ago, a band of orcs invaded the community and defeated the dwarves.");
		output("`n`nIt is said that only the leader of the dwarves, Kilmor, and a few others escaped from the Orcs.");
		addnav("Return","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="scroll2b"){
		output("`c`b`^The Historical Scrolls of the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signets`0`c`b`n");
		output("Although there had until recently been general peace across the land, the evil sorcerer Mierscri has brought his great army of men and beasts to terrorize the land.");
		output("This great force laid waste to the land and its people. Soon, the battle would be over.");
		output("`n`nHowever, before this could happen, a coalition of the four greatest wizards of the land came together to end the reign of evil.");
		output("The wizards created 4 signets; each representing the great forces of nature:  `3Air`0, `QEarth`0, `!Water`0, and `\$Fire`0.");
		output("When one warrior was able to harness the power of these four signets, then the evil could be stopped.");
		output("`n`nMierscri learned of the plan and captured the four wizards before a warrior could be chosen to receive the signets.  His evil spells turned the wizards into Warlocks of great power but completely under his control.");
		output("`n`nPerhaps one day a warrior will be able to gather the four signets in order to finally defeat the evil Mierscri.");
		addnav("Return","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="scroll3b"){
		output("`c`b`^Prison Notes by Kilmor`0`c`b`n");
		output("The Orcs are less than wonderful captors. I hope to be able to break free soon.");
		output("My first journey will be to the `^Temple of the Aarde Priests`0.  It is rumored that the high priest of the temple has the power of prophesy, but few have been allowed to see him.");
		addnav("Return","runmodule.php?module=signetd2&loc=".$temp);
	}
	if ($op=="scroll4b"){
		output("`c`b`^Report from Evad the Sage`c`b");
		output("`n`0In `\$Fiamma's Fortress`0 there is a secret control room which can be accessed from one of two secret passages.");
		output("The first starts in `\$Fiamma's`0 room.  The other starts between the arena and the jail cell.");
		output("From this room various gates around the castle can be operated.");
		addnav("Continue","runmodule.php?module=signetd2&loc=".$temp);
	}
	//bio scroll
	if ($op=="scroll4"){
		$userid = httpget("user");
		output("`c`b`^Report from Evad the Sage`c`b");
		output("`n`0In `\$Fiamma's Fortress`0 there is a secret control room which can be accessed from one of two secret passages.");
		output("The first starts in `\$Fiamma's`0 room.  The other starts between the arena and the jail cell.");
		output("From this room various gates around the castle can be operated.");
		addnav("Return to Signets","runmodule.php?module=signetsale&op=signetnotes&user=$userid");
	}
	page_footer();
}
?>