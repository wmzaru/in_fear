<?php
function signetd4_fight($op) {
	global $session,$badguy;
	$temp=get_module_pref("pqtemp");
	page_header("Fortress Fight");
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($op=="door"){
		if ($temp==362 && $allprefs['loc362']==0) $allprefs['loc362']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"`Qa Heavy Door`0",
			"creaturelevel"=>8,
			"creatureweapon"=>"sharp splinters",
			"creatureattack"=>8,
			"creaturedefense"=>10,
			"creaturehealth"=>60,
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="ooze"){
		if ($temp==1057 && $allprefs['loc1057']==0) $allprefs['loc1057']=2;
		elseif ($temp==467 && $allprefs['loc467']==0) $allprefs['loc467']=2;
		elseif ($temp==29 && $allprefs['loc29']==0) $allprefs['loc29']=2;
		elseif ($temp==87 && $allprefs['loc87']==0) $allprefs['loc87']=2;
		elseif ($temp==90 && $allprefs['loc90']==0) $allprefs['loc90']=2;
		elseif ($temp==18 && $allprefs['loc18']==0) $allprefs['loc18']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$targetlevel=$session['user']['level'];
		$sql = "SELECT * FROM " . db_prefix("creatures") . " WHERE creaturelevel = $targetlevel AND forest=1 ORDER BY rand(".e_rand().") LIMIT 1";
		$result = db_query($sql);
		$badguy = db_fetch_assoc($result);
		$badguy = modulehook("buffbadguy", $badguy);
		$badguy['creaturename']="Blue Ooze";
		$badguy['creatureweapon']="slime";
		$badguy['type']="";
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="random"){
		$targetlevel=$session['user']['level'];
		$sql = "SELECT * FROM " . db_prefix("creatures") . " WHERE creaturelevel = $targetlevel AND forest=1 ORDER BY rand(".e_rand().") LIMIT 1";
		$result = db_query($sql);
		$badguy = db_fetch_assoc($result);
		$badguy = modulehook("buffbadguy", $badguy);
		$randmonster=e_rand(1,4);
		if ($randmonster==1){
			$badguy['creaturename']="Kobold";
			$badguy['creatureweapon']="a Dagger";
		}elseif ($randmonster==2){
			$badguy['creaturename']="Lizard Man";
			$badguy['creatureweapon']="a spear";
		}elseif ($randmonster==3){
			$badguy['creaturename']="Death Fly";
			$badguy['creatureweapon']="a deadly buzz";
		}else{
			$badguy['creaturename']="Ogre";
			$badguy['creatureweapon']="a Spiked Club";
		}
		$badguy['type']="";
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="zombie"){
		if ($temp==513 && $allprefs['loc513']==0) $allprefs['loc513']=2;
		elseif ($temp==853 && $allprefs['loc853']==0) $allprefs['loc853']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$targetlevel=$session['user']['level']+1;
		$sql = "SELECT * FROM " . db_prefix("creatures") . " WHERE creaturelevel = $targetlevel AND forest=1 ORDER BY rand(".e_rand().") LIMIT 1";
		$result = db_query($sql);
		$badguy = db_fetch_assoc($result);
		$badguy = modulehook("buffbadguy", $badguy);
		$badguy['creaturename']="Zombie";
		$badguy['creatureweapon']="gangrenous arms";
		$badguy['type']="";
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="drunkendwarf"){
		if ($temp==868 && $allprefs['loc868']==0) $allprefs['loc868']=2;
		set_module_pref('allprefs',serialize($allprefs));
		if ($session['user']['level']==15) $targetlevel=16;
		else $targetlevel=$session['user']['level']+2;
		$sql = "SELECT * FROM " . db_prefix("creatures") . " WHERE creaturelevel = $targetlevel AND forest=1 ORDER BY rand(".e_rand().") LIMIT 1";
		$result = db_query($sql);
		$badguy = db_fetch_assoc($result);
		$badguy = modulehook("buffbadguy", $badguy);
		$badguy['creaturename']="Drunken Dwarf";
		$badguy['creatureweapon']="an Empty Mead Mug";
		$badguy['type']="";
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="guards"){
		if ($temp==485 && $allprefs['loc485']==0) $allprefs['loc485']=2;
		elseif ($temp==874 && $allprefs['loc874']==0) $allprefs['loc874']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"Guards",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"Long Swords",
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*.84,
			"creaturedefense"=>($session['user']['defense']+e_rand(1,2))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.84),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="warrior"){
		if ($temp==689 && $allprefs['loc689']==0) $allprefs['loc689']=2;
		elseif ($temp==948 && $allprefs['loc948']==0) $allprefs['loc948']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"Warrior",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"a Two-handed Sword",
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*.94,
			"creaturedefense"=>($session['user']['defense']+e_rand(1,2))*.7,
			"creaturehealth"=>round($session['user']['hitpoints']*.87),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="troll"){
		if ($temp==655 && $allprefs['loc655']==0) $allprefs['loc655']=2;
		elseif ($temp==312 && $allprefs['loc312']==0) $allprefs['loc312']=2;
		elseif ($temp==561 && $allprefs['loc561']==0) $allprefs['loc561']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"Troll",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"Infected Claws",
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*.8,
			"creaturedefense"=>($session['user']['defense']+e_rand(1,2))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.8),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="torturer"){
		if ($temp==198 && $allprefs['loc198']==0) $allprefs['loc198']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"Torturer",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"Whips and Chains",
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*.94,
			"creaturedefense"=>($session['user']['defense']+e_rand(1,2))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.86),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="barbarian"){
		if ($temp==386 && $allprefs['loc386']==0) $allprefs['loc386']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"Barbarian",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"a Wooden Club",
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*.8,
			"creaturedefense"=>($session['user']['defense']+e_rand(1,2)),
			"creaturehealth"=>round($session['user']['hitpoints']*.8),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="hornets"){
		if ($temp==459 && $allprefs['loc459']==0) $allprefs['loc459']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"A Swarm of Hornets",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"Poisoned Stingers",
			"creatureattack"=>($session['user']['attack']+e_rand(2,4)*1.1),
			"creaturedefense"=>($session['user']['defense']+e_rand(1,2)*.7),
			"creaturehealth"=>round($session['user']['hitpoints']*.6),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="fiamma"){
		if ($temp==182 && $allprefs['loc182']==0) $allprefs['loc182']=2;
		set_module_pref('allprefs',serialize($allprefs));
		if ($session['user']['maxhitpoints']>$session['user']['hitpoints']) $hitpoints=$session['user']['maxhitpoints'];
		else $hitpoints=$session['user']['hitpoints'];
		$badguy = array(
			"type"=>"",
			"creaturename"=>"`QF`\$iamma",
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>"`qa Huge Mace",
			"creatureattack"=>($session['user']['defense']+e_rand(-1,2)),
			"creaturedefense"=>($session['user']['attack']+e_rand(-1,2)),
			"creaturehealth"=>round($hitpoints*1.1),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="fight"){
		global $badguy;
		$battle=true;
		$fight=true;
		if ($battle){
			require_once("battle.php");
	    if ($victory){
       			addnav("Continue","runmodule.php?module=signetd4&loc=".get_module_pref('pqtemp'));
				//opening doors
				if ($temp==362 && $allprefs['loc362']==2) {
					output("`^`n`bYou have opened the door safely.`b`n");
					$experience=$session['user']['level']*e_rand(6,9);
					output("`#You receive `6%s `#experience.`n",$experience);
					$session['user']['experience']+=$experience;
					if ($allprefs['loc362']==2) $allprefs['loc362']=1;
				//special monsters
				}elseif($temp==182 && $allprefs['loc182']==2){
					$allprefs['loc182']=1;
					output("`n`n`0You hit `QF`\$iamma`0 and his `QHuge Mace`0 flies against the west wall.  You've disarmed him!");
					output("`n`n`b`&You prepare the fatal blow against `QF`\$iamma`&, but with a dieing hand the coward begs for mercy!!`b`n");
					addnav("Continue","runmodule.php?module=signetd4&op=182b");
					blocknav("runmodule.php?module=signetd4&loc=".get_module_pref('pqtemp'));
				//Custom monsters
				}elseif (($temp==485 && $allprefs['loc485']==2) || ($temp==386 && $allprefs['loc386']==2) ||($temp==459 && $allprefs['loc459']==2)||($temp==198 && $allprefs['loc198']==2)||($temp==312 && $allprefs['loc312']==2)||($temp==689 && $allprefs['loc689']==2)||($temp==655 && $allprefs['loc655']==2)||($temp==874 && $allprefs['loc874']==2)||($temp==948 && $allprefs['loc948']==2)||($temp==561 && $allprefs['loc561']==2)){
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$gold=e_rand(80,200);
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(10,20);
					$expbonus=$session['user']['dragonkills']*1.5;
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You search through the smelly corpse and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					if ($allprefs['loc312']==2) $allprefs['loc312']=1;
					elseif ($allprefs['loc485']==2) $allprefs['loc485']=1;
					elseif ($allprefs['loc386']==2) $allprefs['loc386']=1;
					elseif ($allprefs['loc459']==2) $allprefs['loc459']=1;
					elseif ($allprefs['loc198']==2) $allprefs['loc198']=1;
					elseif ($allprefs['loc689']==2) $allprefs['loc689']=1;
					elseif ($allprefs['loc655']==2) $allprefs['loc655']=1;
					elseif ($allprefs['loc874']==2) $allprefs['loc874']=1;
					elseif ($allprefs['loc948']==2) $allprefs['loc948']=1;
					elseif ($allprefs['loc561']==2) $allprefs['loc561']=1;
				//Forest Based Coded monsters
				}elseif (($temp==1057 && $allprefs['loc1057']==2)||  ($temp==467 && $allprefs['loc467']==2) || ($temp==90 && $allprefs['loc90']==2) || ($temp==18 && $allprefs['loc18']==2)||($temp==29 && $allprefs['loc29']==2)|| ($temp==87 && $allprefs['loc87']==2)||($temp==513 && $allprefs['loc513']==2)|| ($temp==853 && $allprefs['loc853']==2)||($temp==868 && $allprefs['loc868']==2)){
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$gold=e_rand(10,20)*$session['user']['level'];
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(5,15);
					$expbonus=$session['user']['dragonkills'];
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You search through the smelly corpse and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					if ($allprefs['loc1057']==2) $allprefs['loc1057']=1;
					if ($allprefs['loc467']==2) $allprefs['loc467']=1;
					if ($allprefs['loc18']==2) $allprefs['loc18']=1;
					if ($allprefs['loc29']==2) $allprefs['loc29']=1;
					if ($allprefs['loc87']==2) $allprefs['loc87']=1;
					if ($allprefs['loc90']==2) $allprefs['loc90']=1;
					if ($allprefs['loc513']==2) $allprefs['loc513']=1;
					if ($allprefs['loc853']==2) $allprefs['loc853']=1;
					if ($allprefs['loc868']==2) $allprefs['loc868']=1;
				//next lines are for random encounters
				}else{
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$gold=e_rand(50,150);
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(10,20);
					$expbonus=$session['user']['dragonkills']*1.5;
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You search through the smelly corpse and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);				
				}
				set_module_pref('allprefs',serialize($allprefs));
				if ((get_module_setting("healing")==1) && ($session['user']['hitpoints']<$session['user']['maxhitpoints']*.6)){
					$hpdown=$session['user']['maxhitpoints']-$session['user']['hitpoints'];
					switch(e_rand(1,15)){
						case 1:
							if ($hpdown>200) $hpdown=200;
							output("`n`@You find a healing potion and take a deep drink.  You feel your strength return.");
							output("`n`nYou gain `b%s hitpoints`b!",$hpdown);
							$session['user']['hitpoints']+=$hpdown;
						break;
						case 2: case 3:
							output("`n`@You notice the remnants of a healing potion.  You have a chance to take a drink!");
							$hpheal=round(e_rand($hpdown*.3,$hpdown*.7));
							if ($hpheal<=1) $hpheal=2;
							if ($hpheal>120) $hpheal=120;
							output("`n`nYou gain `b%s hitpoints`b!",$hpheal);
							$session['user']['hitpoints']+=$hpheal;
						break;
						case 4: case 5: case 6:
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
						case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15:
						break;
					}
				}
				$badguy=array();
				$session['user']['badguy']="";
			}elseif ($defeat){
				if ($temp==362 && $allprefs['loc362']==2){
					if ($allprefs['loc362']==2) $allprefs['loc362']=0;
					output("A splinter severs a major blood vessel and you die.`n");
					addnews("`% %s`5 has been slain trying to break down a door.",$session['user']['name']);
				}elseif (($temp==1057 && $allprefs['loc1057']==2) || ($temp==182 && $allprefs['loc182']==2)||($temp==485 && $allprefs['loc485']==2)||($temp==386 && $allprefs['loc386']==2)||($temp==459 && $allprefs['loc459']==2)||($temp==467 && $allprefs['loc467']==2)||($temp==90 && $allprefs['loc90']==2)||($temp==18 && $allprefs['loc18']==2)||($temp==29 && $allprefs['loc29']==2)||($temp==87 && $allprefs['loc87']==2)||($temp==198 && $allprefs['loc198']==2)||($temp==513 && $allprefs['loc513']==2)||($temp==853 && $allprefs['loc853']==2)||($temp==689 && $allprefs['loc689']==2)||($temp==655 && $allprefs['loc655']==2)||($temp==312 && $allprefs['loc312']==2)||($temp==868 && $allprefs['loc868']==2)||($temp==874 && $allprefs['loc874']==2)||($temp==948 && $allprefs['loc948']==2)||($temp==561 && $allprefs['loc561']==2)){
					if ($allprefs['loc1057']==2) $allprefs['loc1057']=0;
					if ($allprefs['loc182']==2) $allprefs['loc182']=0;
					if ($allprefs['loc485']==2) $allprefs['loc485']=0;
					if ($allprefs['loc386']==2) $allprefs['loc386']=0;
					if ($allprefs['loc459']==2) $allprefs['loc459']=0;
					if ($allprefs['loc467']==2) $allprefs['loc467']=0;
					if ($allprefs['loc18']==2) $allprefs['loc18']=0;
					if ($allprefs['loc29']==2) $allprefs['loc29']=0;
					if ($allprefs['loc87']==2) $allprefs['loc87']=0;
					if ($allprefs['loc90']==2) $allprefs['loc90']=0;
					if ($allprefs['loc198']==2) $allprefs['loc198']=0;
					if ($allprefs['loc513']==2) $allprefs['loc513']=0;
					if ($allprefs['loc853']==2) $allprefs['loc853']=0;
					if ($allprefs['loc655']==2) $allprefs['loc655']=0;
					if ($allprefs['loc689']==2) $allprefs['loc689']=0;
					if ($allprefs['loc312']==2) $allprefs['loc312']=0;
					if ($allprefs['loc868']==2) $allprefs['loc868']=0;
					if ($allprefs['loc874']==2) $allprefs['loc874']=0;
					if ($allprefs['loc948']==2) $allprefs['loc948']=0;
					if ($allprefs['loc561']==2) $allprefs['loc561']=0;
					output("`nAs you hit the ground the `^%s runs away.",$badguy['creaturename']);
					addnews("`%%s`5 has been slain by an evil %s in a dungeon.",$session['user']['name'],$badguy['creaturename']);
				//Next lines are for random encounters
				}else{
					output("`nAs you hit the ground the `^%s runs away.",$badguy['creaturename']);
					addnews("`%%s`5 has been slain by an evil %s in a dungeon.",$session['user']['name'],$badguy['creaturename']);
				}
		        $badguy=array();
		        $session['user']['badguy']="";  
		        $session['user']['hitpoints']=0;
		        $session['user']['alive']=false;
		        addnav("Continue","shades.php");
			}else{
					require_once("lib/fightnav.php");
					fightnav(true,false,"runmodule.php?module=signetd4");
			}
		}else{
			redirect("runmodule.php?module=signetd4&loc=".get_module_pref('pqtemp'));	
		}
	}
	page_footer();
}
function signetd4_misc($op) {
	$temp=get_module_pref("pqtemp");
	page_header("Fiamma's Fortress");
	global $session;
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
		$allprefse=unserialize(get_module_pref('allprefs',"signetd4",$id));
		if ($allprefse['randomp']=="") $allprefse['randomp']= 0;
		if ($allprefse['loc1177count']=="") $allprefse['loc1177count']= 0;
		if ($allprefse['mazeturn']=="") $allprefse['mazeturn']= 0;
		if ($allprefse['startloc']=="") $allprefse['startloc']= 1279;
		set_module_pref('allprefs',serialize($allprefse),'signetd4',$id);
		if ($subop!='edit'){
			$allprefse=unserialize(get_module_pref('allprefs',"signetd4",$id));
			$allprefse['complete']= httppost('complete');
			$allprefse['reset']= httppost('reset');
			$allprefse['randomp']= httppost('randomp');
			$allprefse['firesignet']= httppost('firesignet');
			$allprefse['loc182']= httppost('loc182');
			$allprefse['scroll7']= httppost('scroll7');
			$allprefse['scroll6']= httppost('scroll6');
			$allprefse['loc12']= httppost('loc12');
			$allprefse['loc18']= httppost('loc18');
			$allprefse['loc27']= httppost('loc27');
			$allprefse['loc29']= httppost('loc29');
			$allprefse['loc83']= httppost('loc83');
			$allprefse['loc87']= httppost('loc87');
			$allprefse['loc90']= httppost('loc90');
			$allprefse['loc93']= httppost('loc93');
			$allprefse['loc138']= httppost('loc138');
			$allprefse['loc152']= httppost('loc152');
			$allprefse['loc177']= httppost('loc177');
			$allprefse['loc178']= httppost('loc178');
			$allprefse['loc198']= httppost('loc198');
			$allprefse['loc250']= httppost('loc250');
			$allprefse['loc312']= httppost('loc312');
			$allprefse['loc328']= httppost('loc328');
			$allprefse['loc343']= httppost('loc343');
			$allprefse['loc362']= httppost('loc362');
			$allprefse['loc377']= httppost('loc377');
			$allprefse['loc383']= httppost('loc383');
			$allprefse['loc386']= httppost('loc386');
			$allprefse['loc394']= httppost('loc394');
			$allprefse['loc459']= httppost('loc459');
			$allprefse['loc463']= httppost('loc463');
			$allprefse['loc467']= httppost('loc467');
			$allprefse['loc485']= httppost('loc485');
			$allprefse['loc506']= httppost('loc506');
			$allprefse['loc513']= httppost('loc513');
			$allprefse['loc561']= httppost('loc561');
			$allprefse['loc587']= httppost('loc587');
			$allprefse['loc635']= httppost('loc635');
			$allprefse['loc655']= httppost('loc655');
			$allprefse['loc683']= httppost('loc683');
			$allprefse['loc689']= httppost('loc689');
			$allprefse['loc717']= httppost('loc717');
			$allprefse['loc726']= httppost('loc726');
			$allprefse['loc840']= httppost('loc840');
			$allprefse['loc853']= httppost('loc853');
			$allprefse['loc868']= httppost('loc868');
			$allprefse['loc874']= httppost('loc874');
			$allprefse['loc931']= httppost('loc931');
			$allprefse['loc931b']= httppost('loc931b');
			$allprefse['loc934']= httppost('loc934');
			$allprefse['loc934b']= httppost('loc934b');
			$allprefse['loc948']= httppost('loc948');
			$allprefse['loc1057']= httppost('loc1057');
			$allprefse['loc1059']= httppost('loc1059');
			$allprefse['loc1099']= httppost('loc1099');
			$allprefse['loc1104']= httppost('loc1104');
			$allprefse['loc1143']= httppost('loc1143');
			$allprefse['loc1177']= httppost('loc1177');
			$allprefse['loc1177count']= httppost('loc1177count');
			$allprefse['mazeturn']= httppost('mazeturn');
			$allprefse['startloc']= httppost('startloc');
			$allprefse['header']= httppost('header');
			set_module_pref('allprefs',serialize($allprefse),'signetd4',$id);
			output("Allprefs Updated`n");
			$subop="edit";
		}
		if ($subop=="edit"){
			require_once("lib/showform.php");
			$form = array(
				"Fiamma's Fortress,title",
				"complete"=>"Has player completed the fortress?,bool",
				"reset"=>"Have the preferences been reset by visiting The Dark Lair?,bool",
				"Encounters,title",
				"randomp"=>"How many random monsters has player encountered so far?,int",
				"firesignet"=>"*`\$Received the Fire Signet?,bool",
				"loc182"=>"*Defeated Fiamma?,enum,0,No,1,Yes,2,In Process",
				"scroll7"=>"*Obtained scroll 7?,bool",
				"* Finish these points and the fortress will be closed to this player,note",
				"scroll6"=>"Obtained scroll 6?,bool",
				"loc12"=>"Passed Location 12?,bool",
				"loc18"=>"Passed Location 18?,enum,0,No,1,Yes,2,In Process",
				"loc27"=>"Passed Location 27?,bool",
				"loc29"=>"Passed Location 29?,enum,0,No,1,Yes,2,In Process",
				"loc83"=>"Passed Location 83?,bool",
				"loc87"=>"Passed Location 87?,enum,0,No,1,Yes,2,In Process",
				"loc90"=>"Passed Location 90?,enum,0,No,1,Yes,2,In Process",
				"loc93"=>"Passed Location 93?,bool",
				"loc138"=>"Passed Location 138?,bool",
				"loc152"=>"Passed Location 152?,bool",
				"loc177"=>"Passed Location 177?,bool",
				"loc178"=>"Passed Location 178?,bool",
				"loc198"=>"Passed Location 198?,enum,0,No,1,Yes,2,In Process",
				"loc250"=>"Passed Location 250?,bool",
				"loc312"=>"Passed Location 312?,enum,0,No,1,Yes,2,In Process",
				"loc328"=>"Passed Location 328?,bool",
				"loc343"=>"Passed Location 343?,bool",
				"loc362"=>"Passed Location 362?,enum,0,No,1,Yes,2,In Process",
				"loc377"=>"Passed Location 377?,bool",
				"loc383"=>"Passed Location 383?,bool",
				"loc386"=>"Passed Location 386?,enum,0,No,1,Yes,2,In Process",
				"loc394"=>"Passed Location 394?,bool",
				"loc459"=>"Passed Location 459?,enum,0,No,1,Yes,2,In Process",
				"loc463"=>"Passed Location 463?,bool",
				"loc467"=>"Passed Location 467?,enum,0,No,1,Yes,2,In Process",
				"loc485"=>"Passed Location 485?,enum,0,No,1,Yes,2,In Process",
				"loc506"=>"Passed Location 506?,bool",
				"loc513"=>"Passed Location 513?,enum,0,No,1,Yes,2,In Process",
				"loc561"=>"Passed Location 561?,enum,0,No,1,Yes,2,In Process",
				"loc587"=>"Passed Location 587?,bool",
				"loc635"=>"Passed Location 635?,bool",
				"loc655"=>"Passed Location 655?,enum,0,No,1,Yes,2,In Process",
				"loc683"=>"Passed Location 683?,bool",
				"loc689"=>"Passed Location 689?,enum,0,No,1,Yes,2,In Process",
				"loc717"=>"Passed Location 717?,bool",
				"loc726"=>"Passed Location 726?,bool",
				"loc840"=>"Passed Location 840?,bool",
				"loc853"=>"Passed Location 853?,enum,0,No,1,Yes,2,In Process",
				"loc868"=>"Passed Location 868?,enum,0,No,1,Yes,2,In Process",
				"loc874"=>"Passed Location 874?,enum,0,No,1,Yes,2,In Process",
				"loc931"=>"Passed Location 931?,bool",
				"loc931b"=>"Passed Location 931b?,bool",
				"loc934"=>"Passed Location 934?,bool",
				"loc934b"=>"Passed Location 934b?,bool",
				"loc948"=>"Passed Location 948?,enum,0,No,1,Yes,2,In Process",
				"loc1057"=>"Passed Location 1057?,enum,0,No,1,Yes,2,In Process",
				"loc1059"=>"Passed Location 1059?,bool",
				"loc1099"=>"Passed Location 1099?,bool",
				"loc1104"=>"Passed Location 1104?,bool",
				"loc1143"=>"Passed Location 1143?,bool",
				"loc1177"=>"Passed Location 1177?,bool",
				"loc1177count"=>"Number of times passed Location 1177?,int",
				"Maze,title",
				"mazeturn"=>"Maze Return,int",
				"startloc"=>"Starting location,int|1279",
				"header"=>"Which header array is the player at?,range,0,24,1",
			);
			$allprefse=unserialize(get_module_pref('allprefs',"signetd4",$id));
			rawoutput("<form action='runmodule.php?module=signetd4&op=superuser&userid=$id' method='POST'>");
			showform($form,$allprefse,true);
			$click = translate_inline("Save");
			rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
			rawoutput("</form>");
			addnav("","runmodule.php?module=signetd4&op=superuser&userid=$id");
		}
	}
	if ($op=="exits1"){
		output("You have found an `\$Emergency Exit`0.");
		output("You have the option of leaving from this location at this time.");
		if (get_module_setting("exitsave")==1){
			output("`n`nIf you leave now, you may return to the dungeon at this location or enter at the main entrance.");
			addnav("`\$Take Exit`0");
			addnav("Main Entrance","runmodule.php?module=signetd4&op=exits2");
			addnav("This Location","runmodule.php?module=signetd4&op=exits3");
			addnav("Continue");
		}else{
			output("`n`nIf you leave now, you will re-enter the dungeon from this location.");
			addnav("`\$Take Exit","runmodule.php?module=signetd4&op=exits3");
		}
		addnav("Return to the Dungeon","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="exits2"){
		output("You will re-enter the dungeon from the main exit.");
		$allprefs['startloc']=1279;
		set_module_pref('allprefs',serialize($allprefs));
		villagenav();
	}
	if ($op=="exits3"){
		output("You will re-enter the dungeon at this location.");
		$allprefs['startloc']=$temp;
		set_module_pref('allprefs',serialize($allprefs));
		villagenav();
	}
	if ($op=="reset"){
		$allprefsd3=unserialize(get_module_pref('allprefs','signetd3'));
		$allprefsd3['mazeturn']="";
		$allprefsd3['randomp']="";
		$allprefsd3['header']="";
		$allprefsd3['loc159']="";
		$allprefsd3['loc839']="";
		$allprefsd3['prisonkey']="";
		$allprefsd3['loc11']="";
		$allprefsd3['loc11b']="";
		$allprefsd3['loc11c']="";
		$allprefsd3['loc29']="";
		$allprefsd3['loc48']="";
		$allprefsd3['loc53']="";
		$allprefsd3['loc65']="";
		$allprefsd3['loc66']="";
		$allprefsd3['loc82']="";
		$allprefsd3['loc87']="";
		$allprefsd3['loc94']="";
		$allprefsd3['loc148']="";
		$allprefsd3['loc157']="";
		$allprefsd3['loc164']="";
		$allprefsd3['loc221']="";
		$allprefsd3['loc331']="";
		$allprefsd3['loc352']="";
		$allprefsd3['loc354']="";
		$allprefsd3['loc358']="";
		$allprefsd3['loc401']="";
		$allprefsd3['loc483']="";
		$allprefsd3['loc499']="";
		$allprefsd3['loc537']="";
		$allprefsd3['loc561']="";
		$allprefsd3['loc591']="";
		$allprefsd3['loc619']="";
		$allprefsd3['loc625']="";
		$allprefsd3['loc635']="";
		$allprefsd3['loc745']="";
		$allprefsd3['loc746']="";
		$allprefsd3['loc931']="";
		$allprefsd3['loc939']="";
		$allprefsd3['loc1071']="";
		$allprefsd3['loc1079']="";
		$allprefsd3['loc1097']="";
		$allprefsd3['loc1206']="";
		$allprefsd3['reset']=1;
		set_module_pref('allprefs',serialize($allprefsd3),'signetd3');
		clear_module_pref("maze","signetd3");
		clear_module_pref("pqtemp","signetd3");
		$allprefsd5=unserialize(get_module_pref('allprefs','signetd5'));
		if ($allprefsd5['powersignet']==1) redirect("runmodule.php?module=signetd5&op=eg1b");
		else redirect("runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="1177"){
		$loc1177count=$allprefs['loc1177count'];
		if ($loc1177count==2) output("You hold your breath and jump on the square.  This will work, won't it?`n`n");
		if ($loc1177count==0 || $loc1177count==1 || $loc1177count==2) output("A strange gas fills the air.  You faint.");
		if ($loc1177count==1) output("Before you pass out, you slap yourself on the forehead and realize you shouldn't have stepped into that spot again. Oh well.");
		elseif ($loc1177count==2) output("Nope. I guess not.");
		elseif ($loc1177count==3) output("This is getting a bit annoying.  You close your eyes, take a step forward, and hold your breath.  You feel a thump on your head and fall unconscious.");
		elseif ($loc1177count==4) output("You draw your weapon, cover your mouth with a sock so you don't breath in the poisonous gas, and pass out from the smell of your sock.");
		elseif ($loc1177count==5) output("You take a CLEAN sock out and cover your mouth.  You draw your weapon.  You step forward.  You trip on a rubber ducky and hit your head.");
		elseif ($loc1177count==6) output("You put a straw mannequin of yourself on the the poison gas plate and snicker.  Nothing happens.  You fall asleep waiting.");
		elseif ($loc1177count==7) output("Determined not to fall for this stupid trap, you start yelling `#'I will not fall for this stupid trap!!' `0 You hear a voice say `\$'Yes you will!'`0  You turn to see a frying pan hit you in the nose.");
		elseif ($loc1177count==8) output("You throw a duck at the square.  The duck quacks and walks forward.  You follow the duck and a strange gas fills the air.  You faint.");
		elseif ($loc1177count==9) output("You take out a can of paint and draw an entrance NEXT to the poison gas square.  You try to walk through the entrance that you just painted and hit your head and fall unconscious.");
		elseif ($loc1177count==10) output("Do you really think I'm going to sit here making up ways for you to get gassed?  Just face it, you're going to get gassed and sent to the cell.");
		elseif ($loc1177count==11) output("Fine, I'll make up some more. You spend time reading this text and notice the gas is filling in the air.  You faint.");
		elseif ($loc1177count==12) output("This time you figure out the trick.  You decide not to step on this square.  Oh wait, you.... zzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzzz");
		elseif ($loc1177count==13) output("You really aren't too bright, are you?  I mean, seriously.  Not bright at all.  You see the gas seeping up.");
		elseif ($loc1177count==14) output("You're not here to explore, are you? You're addicted to the poison gas!");
		elseif ($loc1177count==15) output("This time you come prepared and gas yourself before they get a chance to gas you! HA!");
		elseif ($loc1177count>15) output("You walk up and close your eyes.  A feeling of resignation sweeps through you.");
		output("`n`nYou wake up in a locked cell.");
		$allprefs['loc1177count']=$allprefs['loc1177count']+1;
		set_module_pref('allprefs',serialize($allprefs));
		set_module_pref("pqtemp",1097);
		addnav("Continue","runmodule.php?module=signetd4&loc=".get_module_pref('pqtemp'));
	}
	if ($op=="1099"){
		output("The door is locked and you are unable to break it down or pick it.");
		addnav("Continue","runmodule.php?module=signetd4&loc=".($temp-1));
	}
	if ($op=="1059"){
		output("There is a key hanging on the wall here.  Take it?");
		addnav("Take Key","runmodule.php?module=signetd4&op=1059b");
		addnav("Leave","runmodule.php?module=signetd4&op=1059c");
	}
	if ($op=="1059b"){
		output("You grab the key and take it with you.  But what is it for?");
		$allprefs['loc1059']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="1059c"){
		output("Now why wouldn't you take the key?  You know you're going to need it somewhere in this fortress.");
		output("`n`nAre you SURE you don't want to take the key?");	
		addnav("Take Key","runmodule.php?module=signetd4&op=1059b");
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-1));
	}
	if ($op=="377"){	
		output("You find a trap.  Would you like to disarm it?");
		addnav("Disarm","runmodule.php?module=signetd4&op=377b");
		if ($allprefs['loc343']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
		else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
	}
	if ($op=="377b"){	
		output("You approach the trap and attempt to disarm it.`n`n");
		//Higher level characters will be more successful; thieves are more successful
		if($session['user']['level']>12) $trap=3;
		elseif($session['user']['level']>8) $trap=5;
		elseif($session['user']['level']>4) $trap=7;
		else $trap=9;
		if (get_module_pref("skill","specialtythiefskills")>0) $trap--;
		switch(e_rand(1,$trap)){
			case 1:
			case 2:
				$allprefs['loc377']=1;
				set_module_pref('allprefs',serialize($allprefs));
				output("You cautiously disarm the trap... Successfully!!`n`n");
				$chance = e_rand(1,3);
				//I wanted to reward people for coming here with at least two turns left
				if ($session['user']['turns']==1 && $chance==1){
					output("You're a little too cautious about disarming the trap and you `@lose a turn`0.");
					$session['user']['turns']--;
				}elseif ($session['user']['turns']>1 && $chance==1){
					output("You get an adrenaline rush for disarming the trap! Nice job!");
					apply_buff('adrenaline',array(
						"name"=>"`QAdrenaline Rush",
						"rounds"=>8,
						"wearoff"=>"`^The adrenaline rush ends.",
						"atkmod"=>1.1,
						"roundmsg"=>"`#Adrenaline rushes through your veins!",
					));
				}
				addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
			break;
			case 3:
			case 4:
			case 5:
				output("Oh no! You've failed to disarm the trap!`n`n`@");
				switch(e_rand(1,5)){
				case 1:
				case 2:
				case 3:
				case 4:
					$hitpoints=round($session['user']['hitpoints']*.05);
					if ($hitpoints>0) output("A large poison dart shoots out and hits you in the shoulder. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					$session['user']['hitpoints']-=$hitpoints;
				break;
				case 5:
					output("You feel a poison course through your body.");
					$hitpoints=round($session['user']['hitpoints']*.1);
					$session['user']['hitpoints']-=$hitpoints;
					if ($session['user']['turns']>0){
						$session['user']['turns']--;
						output("You lose one turn.");
					}
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				break;
				}
				if ($allprefs['loc343']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
				else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
			break;
			case 6:
			case 7:
			case 8:
			case 9:
				output("Oh no! You've failed to disarm the trap!`n`n`@");
				if ($session['user']['gold']>100){
					$session['user']['gold']-=100;
					output("A blade sweeps out and almost kills you! Luckily, it only cuts open your gold sack and you `^lose 100 gold`@.");
				}elseif ($session['user']['gold']>0){
					$session['user']['gold']=0;
					output("A blade sweeps out and almost kills you! Luckily, it only cuts open your gold sack and you `^lose all of your gold`@.");
				}else{
					if ($session['user']['turns']>0){
						output("The trap triggers and you get knocked back by a huge gust of wind.");
						output("`n`n`@You `blose one turn`b.");
						$session['user']['turns']--;
					}
				}
				if ($allprefs['loc343']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
				else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
			break;
		}
	}
	if ($op=="717"){	
		output("You find a trap.  Would you like to disarm it?");
		addnav("Disarm","runmodule.php?module=signetd4&op=717b");
		if ($allprefs['loc683']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
		else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
	}
	if ($op=="717b"){
		output("You look closely at the trap and notice a small sign.");
		output("`c`b`n`#Pay `^200 gold`# to avoid this trap`b`c`n");
		output("`0What would you like to do?");
		addnav("Attempt to Disarm the Trap","runmodule.php?module=signetd4&op=717d");
		//Let's reward players for carrying some gold with them into the dungeon by making this easier on them
		addnav("Pay `^200 Gold","runmodule.php?module=signetd4&op=717c");
		if ($allprefs['loc683']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
		else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));	
	}
	if ($op=="717c"){
		if ($session['user']['gold']>=200){
			output("You put `^200 gold`0 into the tiny slot and hear some strange whirling noises.`n.`n.`n.`n.`n");
			output("The trap is disarmed!");
			$allprefs['loc717']=1;
			set_module_pref('allprefs',serialize($allprefs));
			addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
			$session['user']['gold']-=200;
		}else{
			output("You try to jam some round pieces of tin into the coin slot.  Somehow, the trap figures out your rouse.");
			output("`n`nThe coins get thrown out at you at great force!");
			$hitpoints=round($session['user']['hitpoints']*.1);
			$session['user']['hitpoints']-=$hitpoints;
			if ($session['user']['turns']>0){
				$session['user']['turns']--;
				output("You lose one turn.");
			}
			if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
			output("`n`n`@You obviously can't disarm the trap by trying to cheat it.");
			if ($allprefs['loc683']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
			else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));			
		}
		
	}
	if ($op=="717d"){
		output("You approach the trap and attempt to disarm it.`n`n");
		//Higher level characters will be more successful; thieves are more successful
		if($session['user']['level']>12) $trap=3;
		elseif($session['user']['level']>8) $trap=5;
		elseif($session['user']['level']>4) $trap=7;
		else $trap=9;
		if (get_module_pref("skill","specialtythiefskills")>0) $trap--;
		switch(e_rand(1,$trap)){
			case 1:
			case 2:
				$allprefs['loc717']=1;
				set_module_pref('allprefs',serialize($allprefs));
				output("You cautiously disarm the trap... Successfully!!`n`n");
				addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
			break;
			case 3:
			case 4:
			case 5:
				output("Oh no! You've failed to disarm the trap!`n`n`@");
				switch(e_rand(1,5)){
				case 1:
				case 2:
				case 3:
				case 4:
					$hitpoints=round($session['user']['hitpoints']*.05);
					if ($hitpoints>0) output("A large poison dart shoots out and hits you in the shoulder. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					$session['user']['hitpoints']-=$hitpoints;
				break;
				case 5:
					output("You feel a poison course through your body.");
					$hitpoints=round($session['user']['hitpoints']*.1);
					$session['user']['hitpoints']-=$hitpoints;
					if ($session['user']['turns']>0){
						$session['user']['turns']--;
						output("You lose one turn.");
					}
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				break;
				}
				if ($allprefs['loc683']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
				else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
			break;
			case 6:
			case 7:
			case 8:
			case 9:
				output("Oh no! You've failed to disarm the trap!`n`n`@");
				if ($session['user']['gold']>100){
					$session['user']['gold']-=100;
					output("A blade sweeps out and almost kills you! Luckily, it only cuts open your gold sack and you `^lose 100 gold`@.");
				}elseif ($session['user']['gold']>0){
					$session['user']['gold']=0;
					output("A blade sweeps out and almost kills you! Luckily, it only cuts open your gold sack and you `^lose all of your gold`@.");
				}else{
					if ($session['user']['turns']>0){
						output("The trap triggers and you get knocked back by a huge gust of wind.");
						output("`n`n`@You `blose one turn`b.");
						$session['user']['turns']--;
					}
				}
				if ($allprefs['loc683']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
				else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
			break;
		}
	}
	if ($op=="178"){	
		output("You find a trap.  Would you like to disarm it?");
		addnav("Disarm","runmodule.php?module=signetd4&op=178b");
		if ($allprefs['loc177']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-1));
		else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+1));
	}
	if ($op=="178b"){
		output("You approach the trap and attempt to disarm it.`n`n");
		//Higher level characters will be more successful; thieves are more successful
		if($session['user']['level']>12) $trap=3;
		elseif($session['user']['level']>8) $trap=5;
		elseif($session['user']['level']>4) $trap=7;
		else $trap=9;
		if (get_module_pref("skill","specialtythiefskills")>0) $trap--;
		switch(e_rand(1,$trap)){
			case 1:
			case 2:
				$allprefs['loc178']=1;
				set_module_pref('allprefs',serialize($allprefs));
				output("You cautiously disarm the trap... Successfully!!`n`n");
				$chance = e_rand(1,3);
				//I wanted to reward people for coming here with at least one turn left
				if ($session['user']['turns']>0 && $chance==1){
					output("You get an adrenaline rush for disarming the trap. You gain an extra turn!");
					$session['user']['turns']++;
				}
				addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
			break;
			case 3:
			case 4:
			case 5:
				output("Oh no! You've failed to disarm the trap!`n`n`@");
				switch(e_rand(1,5)){
				case 1:
				case 2:
				case 3:
				case 4:
					$hitpoints=round($session['user']['hitpoints']*.05);
					if ($hitpoints>0) output("A large poison dart shoots out and hits you in the shoulder. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					$session['user']['hitpoints']-=$hitpoints;
				break;
				case 5:
					output("You feel a poison course through your body.");
					$hitpoints=round($session['user']['hitpoints']*.1);
					$session['user']['hitpoints']-=$hitpoints;
					if ($session['user']['turns']>0){
						$session['user']['turns']--;
						output("You lose one turn.");
					}
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				break;
				}
				if ($allprefs['loc177']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-1));
				else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+1));
			break;
			case 6:
			case 7:
			case 8:
			case 9:
				output("Oh no! You've failed to disarm the trap!`n`n`@");
				if ($session['user']['gold']>100){
					$session['user']['gold']-=100;
					output("A blade sweeps out and almost kills you! Luckily, it only cuts open your gold sack and you `^lose 100 gold`@.");
				}elseif ($session['user']['gold']>0){
					output("A blade sweeps out and almost kills you! Luckily, it only cuts open your gold sack and you `^lose all of your gold`@.");
					$session['user']['gold']=0;
				}else{
					if ($session['user']['turns']>0){
						output("The trap triggers and you get knocked back by a huge gust of wind.");
						output("`n`n`@You `blose one turn`b.");
						$session['user']['turns']--;
					}
				}
				if ($allprefs['loc177']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-1));
				else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+1));
			break;
		}
	}
	if ($op=="138"){
		output("There are 3 levers here.`n`n");
		//lever 1 is for the initial prison cell
		if ($allprefs['loc1099']==1) output("The `@first lever`0 has already been pulled.`n");
		else addnav("Pull `@Lever 1","runmodule.php?module=signetd4&op=138b");
		//lever 2 is for the arena exit
		if ($allprefs['loc726']==1) output("The `^second lever`0 has already been pulled.`n");
		else addnav("Pull `^Lever 2","runmodule.php?module=signetd4&op=138c");
		//lever 3 is for Fiamma's Closet
		if ($allprefs['loc83']==1) output("The `\$third lever`0 has already been pulled.`n");
		else addnav("Pull `\$Lever 3","runmodule.php?module=signetd4&op=138d");
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
		if ($allprefs['loc1099']==1 && $allprefs['loc726']==1 && $allprefs['loc83']==1){
			$allprefs['loc138']=1;
			set_module_pref('allprefs',serialize($allprefs));
		}
	}
	if ($op=="138b"){
		$loc726=$allprefs['loc726'];
		$loc83=$allprefs['loc83'];
		$total=$loc726+$loc83;
		output("You pull the `@first lever`0 and hear a noise in the distance.");
		if ($total==1) output("`n`nThe ceiling starts to shake and rocks fall and almost hit you!");
		if ($total==2) output("`n`nThe ceiling collapses and destroys the levers!  You're lucky to escape unharmed.");
		$allprefs['loc1099']=1;
		if ($allprefs['loc726']==0 || $allprefs['loc83']==0) addnav("Pull another lever","runmodule.php?module=signetd4&op=138");
		if ($allprefs['loc726']==1 && $allprefs['loc83']==1){
			$allprefs['loc138']=1;
			addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
		}else{
			addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="138c"){
		$loc1099=$allprefs['loc1099'];
		$loc83=$allprefs['loc83'];
		$total=$loc1099+$loc83;
		output("You pull the `^second lever`0 and hear a noise in the distance.");
		if ($total==1) output("`n`nThe ceiling starts to shake and rocks fall and almost hit you!");
		if ($total==2) output("`n`nThe ceiling collapses and destroys the levers!  You're lucky to escape unharmed.");
		$allprefs['loc726']=1;	
		if ($allprefs['loc1099']==0 || $allprefs['loc83']==0) addnav("Pull another lever","runmodule.php?module=signetd4&op=138");
		if ($allprefs['loc1099']==1 && $allprefs['loc83']==1){
			$allprefs['loc138']=1;
			addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
		}else{
			addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="138d"){
		$loc1099=$allprefs['loc1099'];
		$loc726=$allprefs['loc726'];
		$total=$loc1099+$loc726;
		output("You pull the `\$third lever`0 and hear a noise in the distance.");
		if ($total==1) output("`n`nThe ceiling starts to shake and rocks fall and almost hit you!");
		if ($total==2) output("`n`nThe ceiling collapses and destroys the levers!  You're lucky to escape unharmed.");
		$allprefs['loc83']=1;
		if ($allprefs['loc1099']==0 || $allprefs['loc726']==0) addnav("Pull another lever","runmodule.php?module=signetd4&op=138");
		if ($allprefs['loc1099']==1 && $allprefs['loc726']==1){
			$allprefs['loc138']=1;
			addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
		}else{
			addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="182"){
		output("You find yourself standing face to face with `QF`\$iamma`0.`n`n  He picks up a huge mace and taps it against his hand.");
		output("`n`nWith a smile he starts to approach you.  You don't have a chance to escape.  I hope you're ready!");
		addnav("Fight `QF`\$iamma","runmodule.php?module=signetd4&op=fiamma");
	}
	if ($op=="182b"){
		if (is_module_active("alignment")){
			$alignment=get_module_pref("alignment","alignment");
			$good=get_module_setting("goodalign","alignment");
			$evil=get_module_setting("evilalign","alignment");
			$allprefs['loc182']=1;
			set_module_pref('allprefs',serialize($allprefs));
			if ($alignment<=$evil) {
				output("Being `\$Evil`0, you decide that `QF`\$iamma`0 is not worthy of your mercy.");
				addnav("Kill Fiamma","runmodule.php?module=signetd4&op=182d");
			}elseif($alignment>$evil && $alignment<$good) {
				output("Being `^Neutral`0, you hesitate for a second and decide to see if you can get anything more out of `QF`4iamma.");
				output("`n`n`#'What's in it for me to let you live, you evil fiend?'`0 you ask.");
				output("`n`n`Q'Well, I can tell you where I have hidden `%3 gems`Q in my bedroom if you let me live.  I promise you won't be able to find them otherwise.'");
				output("`n`n`0What would you like to  do?");
				addnav("`@Let `QF`\$iamma `@Go","runmodule.php?module=signetd4&op=182e");
				addnav("`^Accept `QF`\$iamma's`^ Offer","runmodule.php?module=signetd4&op=182c");
				addnav("`\$Kill `QF`\$iamma","runmodule.php?module=signetd4&op=182d");
			}elseif($alignment>=$good) {
				output("Being of `@Good Alignment`0, you decide to let `QF`\$iamma`0 go.");
				addnav("`@Let `QF`\$iamma `@Go","runmodule.php?module=signetd4&op=182e");
			}
		}else{
			output("You hesitate for a second and decide to see if you can get anything more out of `QF`4iamma.");
			output("`n`n`#'What's in it for me to let you live, you evil fiend?'`0 you ask.");
			output("`n`n`Q'Well, I can tell you where I have hidden `%3 gems`Q in my bedroom if you let me live.  I promise you won't be able to find them otherwise.'");
			output("`n`n`0What would you like to  do?");
			addnav("`@Let `QF`\$iamma `@Go","runmodule.php?module=signetd4&op=182e");
			addnav("`^Accept `QF`\$iamma's`^ Offer","runmodule.php?module=signetd4&op=182c");
			addnav("`\$Kill `QF`\$iamma","runmodule.php?module=signetd4&op=182d");
		}
	}
	if ($op=="182c"){
		output("You accept the offer.  `QF`\$iamma`0 reveals that there's a `%pouch of gems`0 hidden just south of you in the secret compartment in the wall.");
		output("`n`nYou head over to the southern wall and `QF`\$iamma`0  slithers away.");
		output("You chuckle to yourself realizing that he won't be able to survive the mortal wound that you inflicted on him.");
		$allprefs['loc250']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd4&op=114");
	}
	if ($op=="182d"){
		if (is_module_active("alignment")){
			if (get_module_pref("alignment","alignment")>get_module_setting("evilalign","alignment")){
				output("Your action makes you more `\$Evil`0.");
				increment_module_pref("alignment",-4,"alignment");
			}
		}
		output("You slay `QF`\$iamma`0 without mercy.");
		$expmultiply = e_rand(20,40);
		$expbonus=$session['user']['dragonkills']*3;
		$expgain =round($session['user']['level']*$expmultiply+$expbonus);
		$session['user']['experience']+=$expgain;
		output("`n`n`#You have gained `7%s `#experience.`n",$expgain);
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="182e"){
		if (is_module_active("alignment")){
			if (get_module_pref("alignment","alignment")<get_module_setting("goodalign","alignment")){
				output("Your action makes you more `@Good`0.");
				increment_module_pref("alignment",+4,"alignment");
			}
		}
		output("You send `QF`\$iamma`0 away, hoping that your thrashing taught him a lesson.");
		output("`n`n`QF`\$iamma`0 starts to slither away, but draws a dagger and lunges at you!!");
		output("However, you knew that he was going to try this trick and you dodge the lunge and `QF`\$iamma`0 crashes against the wall and dies.");
		$expmultiply = e_rand(20,40);
		$expbonus=$session['user']['dragonkills']*3;
		$expgain =round($session['user']['level']*$expmultiply+$expbonus);
		$session['user']['experience']+=$expgain;
		output("`n`n`#You have gained `7%s `#experience.`n",$expgain);
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="114"){
		set_module_pref("pqtemp",114);
		//less experience because they received gems
		$session['user']['gems']+=2;
		$expmultiply = e_rand(10,20);
		$expbonus=$session['user']['dragonkills']*3;
		$expgain =round($session['user']['level']*$expmultiply+$expbonus);
		$session['user']['experience']+=$expgain;
		output("You go to the corner wall and find the pouch of gems.  It turns out that `QF`\$iamma`0 was telling the truth.");
		output("`n`nYou collect `%3 gems`0!!");
		output("`n`nIn addition, you gain experience from the battle with `QF`\$iamma`0.");
		output("`n`n`#You have gained `7%s `#experience.`n",$expgain);
		addnav("Continue","runmodule.php?module=signetd4&loc=".get_module_pref('pqtemp'));
	}
	if ($op=="250"){
		output("You pass over the body of `QF`\$iamma`0 in the hallway.  It looks like he didn't get far.");
		$allprefs['loc250']=0;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="152"){
		$allprefs['loc152']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("You wander over to find `QF`\$iamma's `QMace `0on the ground.");
		output("`n`nYou look it over carefully and believe that it may be more powerful than your %s`0.",$session['user']['weapon']);
		output("`n`nWould you like to use `QF`\$iamma큦 `QMace`0?");
		addnav("Yes. I'll use the `QMace","runmodule.php?module=signetd4&op=152b");
		addnav("No. I want to Leave.","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="152b"){
		$oldweapon=$session['user']['weapon'];
		$oldattack=$session['user']['weapondmg'];
		$session['user']['weapon']="`QF`4iamma큦 `QMace";
		$session['user']['attack']++;
		$session['user']['weapondmg']++;
		$session['user']['weaponvalue']*=1.15;
		output("You decide to discard your %s`0 and pick up `QF`4iamma's `QMace`0. Yes, it seems like it's a better weapon than your old one.",$oldweapon);
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="83"){
		output("You try to open the door but despite your best efforts you can't get it open.`n`n");
		if ($allprefs['scroll6']==0){
			output("You find a scrap of paper on the floor that may help you.");
			addnav("Get Scroll","runmodule.php?module=signetd4&op=83b");
		}
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
	}
	if ($op=="83b"){
		increment_module_pref("pqtemp",34);
		$allprefs['scroll6']=1;
		set_module_pref('allprefs',serialize($allprefs));
		redirect("runmodule.php?module=signetd4&op=scroll6b");
	}
	if ($op=="12"){
		output("You see a small `Qflame`0 floating in the air in front of you.  You pass your hand over it and nothing happens.  It seems to generate no heat.");
		output("`n`nWhat would you like to do?");
		addnav("Step Into the Flame","runmodule.php?module=signetd4&op=12b");
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+1));
	}
	if ($op=="12b"){
		output("You take a step into the `Qflame`0 and feel excrutiating pain course through your body.");
		output("`n`nYou feel your strength leave you.");
		if ($session['user']['hitpoints']>1){
			$session['user']['hitpoints']=1;
			output("`n`n`\$Your hitpoints are reduced to one.");
		}
		addnav("Continue","runmodule.php?module=signetd4&op=12c");
	}
	if ($op=="12c"){
		output("Soon the pain starts to diminish and you feel a renewed strength.");
		output("`n`nYou now carry the mark of the `\$Fire Signet`0.");
		output("`n`nYour `@hitpoints are restored to full`0 as the energy of the `\$Fire Signet`0 courses through your body.");
		addnews("`@%s`@ was marked with the `QFire Signet`@ in `QFiamma's Fortress`@.",$session['user']['name']);
		$session['user']['hitpoints']=$session['user']['maxhitpoints'];
		$allprefs['loc12']=1;
		$allprefs['firesignet']=1;
		set_module_pref('allprefs',serialize($allprefs));
		$allprefssale=unserialize(get_module_pref('allprefssale',"signetsale"));
		set_module_pref("hoftemp",4200+$allprefssale['completednum'],"signetsale");
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="394"){
		output("You step on a pressure plate and suddenly the corridor around you fills with light.");
		$allprefs['loc394']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="383"){
		output("You step on a pressure plate and suddenly the corridor around you fills with light.");
		$allprefs['loc383']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="726"){
		output("You are unable to open this door.");
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-1));
	}
	if ($op=="485"){
		output("As you wander down the secret passageway, guards see you and attack!");
		addnav("Continue","runmodule.php?module=signetd4&op=guards");
	}
	if ($op=="picklock"){
		output("You cannot pick the lock and you can't break down the door.");
		if (($allprefs['loc463']==1 && $temp==497)||($allprefs['loc506']==1 && $temp==540)) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
		else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
	}
	if ($op=="362"){
		output("The door is locked. Would you like to try to break down the door or pick the lock?");
		addnav("Break Down Door","runmodule.php?module=signetd4&op=door");
		addnav("Pick Lock","runmodule.php?module=signetd4&op=362b");
		if ($allprefs['loc328']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
		else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));	
	}
	if ($op=="362b"){
		output("You approach the door and attempt to unpick the lock.`n`n");
		//Higher level characters will be more successful; thieves are more successful
		if($session['user']['level']>12) $trap=3;
		elseif($session['user']['level']>8) $trap=5;
		elseif($session['user']['level']>4) $trap=7;
		else $trap=9;
		if (get_module_pref("skill","specialtythiefskills")>0) $trap--;
		switch(e_rand(1,$trap)){
			case 1:
			case 2:
				$allprefs['loc362']=1;
				set_module_pref('allprefs',serialize($allprefs));
				output("You cautiously pick the lock... Successfully!!`n`n");
				$chance = e_rand(1,3);
				//I wanted to reward people for coming here with at least one turn left
				if ($session['user']['turns']>0 && $chance==1){
					output("You get an adrenaline rush for unlocking the door. You gain an extra turn!");
					$session['user']['turns']++;
				}
				addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
			break;
			case 3:
			case 4:
			case 5:
				output("Oh no! You've failed to unlock the door!`n`n`@");
				switch(e_rand(1,5)){
				case 1:
				case 2:
				case 3:
				case 4:
					$hitpoints=round($session['user']['hitpoints']*.05);
					if ($hitpoints>0) output("`n`nA large poison dart shoots out from a trap mechanism and hits you in the shoulder. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					$session['user']['hitpoints']-=$hitpoints;
				break;
				case 5:
					output("You feel a poison course through your body from a hidden needle embedded in the lock.");
					$hitpoints=round($session['user']['hitpoints']*.1);
					$session['user']['hitpoints']-=$hitpoints;
					if ($session['user']['turns']>0){
						$session['user']['turns']--;
						output("You lose one turn.");
					}
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				break;
				}
				if ($allprefs['loc328']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
				else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));	
			break;
			case 6:
			case 7:
			case 8:
			case 9:
				output("Oh no! You've failed to pick the lock!`n`n`@");
				if ($session['user']['gold']>100){
					$session['user']['gold']-=100;
					output("A blade sweeps out from the door and almost kills you! Luckily, it only cuts open your gold sack and you `^lose 100 gold`@.");
				}elseif ($session['user']['gold']>0){
					$session['user']['gold']=0;
					output("A blade sweeps out from the door and almost kills you! Luckily, it only cuts open your gold sack and you `^lose all of your gold`@.");
				}else{
					if ($session['user']['turns']>0){
						output("A trap triggers and you get knocked back by a huge gust of wind.");
						output("`n`n`@You `blose one turn`b.");
						$session['user']['turns']--;
					}
				}
				if ($allprefs['loc328']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));
				else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));
			break;
		}
	}
	if ($op=="93"){
		$allprefs['loc93']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("You see several suits of armor here. They all seem to be pretty equivalent to what you're wearing now.  Would you be interested in changing your armor?`n`n");
		if ($session['user']['armordef']<=2){
			addnav("`&Rice Paper Mail","runmodule.php?module=signetd4&op=93a");
			addnav("`#Papier Mache Shield","runmodule.php?module=signetd4&op=93b");
			output("`n`&Rice Paper Mail`c");
			output("`c`n`#Papier Mache Shield`n");
		}elseif ($session['user']['armordef']<=5){
			addnav("`)Raven큦 Leather","runmodule.php?module=signetd4&op=93a");
			addnav("`^Griffon큦 Shield","runmodule.php?module=signetd4&op=93b");
			output("`c`n`)Raven's Leather`n");
			output("`n`^Griffin's Shield`c");
		}elseif ($session['user']['armordef']<=8){
			addnav("`^Lion `0Chain Mail","runmodule.php?module=signetd4&op=93a");
			addnav("`@Shield of the `&Eagle","runmodule.php?module=signetd4&op=93b");
			output("`c`n`^Lion `0Chain Mail`n");
			output("`n`@Shield of the `&Eagle`c");
		}elseif ($session['user']['armordef']<=11){
			addnav("`&Crystal Scale Mail","runmodule.php?module=signetd4&op=93a");
			addnav("`4Chiron큦 Shield","runmodule.php?module=signetd4&op=93b");
			output("`c`n`&Crystal Scale Mail`n");
			output("`n`4Chiron's Shield`c");
		}elseif ($session['user']['armordef']<=14){
			addnav("`!Lightning Plate Mail","runmodule.php?module=signetd4&op=93a");
			addnav("`@Titan큦 Shield","runmodule.php?module=signetd4&op=93b");
			output("`c`n`!Lightning Plate Mail`n");
			output("`n`@Titan's Shield`c");
		}elseif ($session['user']['armordef']>14){
			addnav("`@`bGreen Dragon Scale Mail`b","runmodule.php?module=signetd4&op=93a");
			addnav("`b`4Shield `#of `QJubilex`b","runmodule.php?module=signetd4&op=93b");
			output("`c`n`@`bGreen Dragon Scale Mail`b`n");
			output("`n`b`4Shield `#of `QJubilex`b`c");
		}
		addnav("Leave","runmodule.php?module=signetd4&op=93c");
	}
	if ($op=="93a"){
		if ($session['user']['armordef']<=2) $session['user']['armor']="`&Rice Paper Mail";
		elseif ($session['user']['armordef']<=5) $session['user']['armor']="`)Raven큦 Leather";
		elseif ($session['user']['armordef']<=8) $session['user']['armor']="`^Lion `0Chain Mail";
		elseif ($session['user']['armordef']<=11) $session['user']['armor']="`&Crystal Scale Mail";
		elseif ($session['user']['armordef']<=14) $session['user']['armor']="`!Lightning Plate Mail";
		else $session['user']['armor']="`@`bGreen Dragon Scale Mail`b";
		output("Your %s`0 fits quite nicely.  Yes, this was a nice choice.",$session['user']['armor']);
		addnav("Leave","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="93b"){
		if ($session['user']['armordef']<=2) $session['user']['armor']="`#Papier Mache Shield";
		elseif ($session['user']['armordef']<=5) $session['user']['armor']="`^Griffin큦 Shield";
		elseif ($session['user']['armordef']<=8) $session['user']['armor']="`@Shield of the `&Eagle";
		elseif ($session['user']['armordef']<=11) $session['user']['armor']="`4Chiron큦 Shield";
		elseif ($session['user']['armordef']<=14) $session['user']['armor']="`@Titan큦 Shield";
		else $session['user']['armor']="`b`4Shield `#of `QJubilex`b";
		output("You pick up the %s`0.  This is quite a nice shield.  Good choice.",$session['user']['armor']);
		addnav("Leave","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="93c"){
		output("You decide that you're not interested in switching your armor and turn to leave.");
		output("A goblin that had been hiding in the shadows runs over and steals both pieces of armor, then quickly runs away.");
		output("`n`nOh well, on with the adventure.");
		addnav("Leave","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="198"){
		output("You hear monsters. Go back?");
		addnav("Fight Monsters","runmodule.php?module=signetd4&op=torturer");
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-1));
	}
	if ($op=="27"){
		output("The floor falls out from underneath you!`n`n");
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
		//Higher level characters will be more successful
		if($session['user']['level']>12) $trap=3;
		elseif($session['user']['level']>8) $trap=5;
		elseif($session['user']['level']>4) $trap=7;
		else $trap=9;
		$allprefs['loc27']=1;
		set_module_pref('allprefs',serialize($allprefs));
		switch(e_rand(1,$trap)){
			case 1:
			case 2:
				output("You nimbly avoid the trap!`n`n");
				$chance = e_rand(1,3);
				if ($session['user']['turns']>0 && $chance==1){
					output("However, you lose a turn catching your breath.");
					$session['user']['turns']--;
				}
			break;
			case 3:
			case 4:
			case 5:
				output("Oh no! You fall into the pit!`n`n`@");
				switch(e_rand(1,5)){
				case 1:
				case 2:
				case 3:
				case 4:
					$hitpoints=round($session['user']['hitpoints']*.05);
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					else output("Luckily, you don't lose any hitpoints.");
					$session['user']['hitpoints']-=$hitpoints;
				break;
				case 5:
					output("You fall onto a wooden spike!");
					$hitpoints=round($session['user']['hitpoints']*.1);
					$session['user']['hitpoints']-=$hitpoints;
					if ($session['user']['turns']>0){
						$session['user']['turns']--;
						output("You lose one turn.");
					}
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					else output("Luckily, it doesn't hurt you.");
				break;
				}
			break;
			case 6:
			case 7:
			case 8:
			case 9:
				output("You hit your head on the ground.`n`n`@");
				if ($session['user']['gold']>100){
					$session['user']['gold']-=100;
					output("You awaken to find that somehow you've `^lost 100 gold`@.");
				}elseif ($session['user']['gold']>0){
					$session['user']['gold']=0;
					output("You awaken to find that somehow you've `^lost all of your gold`@.");
				}else{
					if ($session['user']['turns']>0){
						output("You wake up after a short period.");
						output("`n`n`@You `blose one turn`b.");
						$session['user']['turns']--;
					}
				}
			break;
		}
	}
	if ($op=="931"){
		output("There is a large oak barrel in this corner.");
		$allprefs['loc931b']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Break Barrel","runmodule.php?module=signetd4&op=931b");
		addnav("Leave","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="931b"){
		output("You grab your %s`0 and take a swing at the barrel...",$session['user']['weapon']);
		output("`n`nIt explodes!!");
		$barrel=round($session['user']['hitpoints']*.2);
		$allprefs['loc931']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if ($barrel<=1) output("`n`nYou duck most of the debris and escape unharmed!");
		else{
			output("`n`nYou are injured and lose `\$%s hitpoints`0!",$barrel);
			$session['user']['hitpoints']-=$barrel;
		}
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="934"){
		output("There is a large oak barrel in this corner.");
		$allprefs['loc934b']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Break Barrel","runmodule.php?module=signetd4&op=934b");
		addnav("Leave","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="934b"){
		output("You grab your %s`0 and take a swing at the barrel...",$session['user']['weapon']);
		output("`n`nIt explodes!!");
		$barrel=round($session['user']['hitpoints']*.2);
		$allprefs['loc934']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if ($barrel<=1) output("`n`nYou duck most of the debris and escape unharmed!");
		else{
			output("`n`nYou are injured and lose `\$%s hitpoints`0!",$barrel);
			$session['user']['hitpoints']-=$barrel;
		}
		output("`n`nAfter the dust settles you look down and see `%2 gems`0 in the dust!");
		$session['user']['gems']+=2;
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="1105"){
		output("You cannot pick the lock or break down the door no matter what you try.");
		if ($allprefs['loc1104']==1) addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-1));
		else addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+1));	
	}
	if ($op=="1143"){
		output("You try to unlock the door but you can't.  Suddenly, you remember the key you found in that storage room!");
		output("`n`nYes! Aren't you glad you brought that with you? It opens the door!");
		$allprefs['loc1143']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);	
	}
	if ($op=="1143b"){
		output("You cannot pick the lock or break down the door no matter what you try.");
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp-34));	
	}
	if ($op=="868"){
		output("You hear monsters. Go back?");
		addnav("Fight Monsters","runmodule.php?module=signetd4&op=drunkendwarf");
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));	
	}
	if ($op=="874"){
		output("You hear monsters. Go back?");
		addnav("Fight Monsters","runmodule.php?module=signetd4&op=guards");
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));	
	}
	if ($op=="874b"){
		output("Several guards see you trying to leave the library and charge after you!");
		addnav("Fight Monsters","runmodule.php?module=signetd4&op=guards");
	}
	if ($op=="635"){
		output("You see a scroll on the desk.");
		addnav("Take the Scroll","runmodule.php?module=signetd4&op=635b");
		addnav("Leave","runmodule.php?module=signetd4&loc=".($temp+34));	
	}
	if ($op=="635b"){
		$allprefs['loc635']=1;
		$allprefs['scroll7']=1;
		set_module_pref('allprefs',serialize($allprefs));
		redirect("runmodule.php?module=signetd4&loc=".$temp);
	}
	//the scrolls
	if ($op=="scroll1b"){
		output("`c`b`^The Aria Dungeon`0`c`b`n");
		output("The Aria Dungeon was once the center of a small community of dwarves.  About 15 years ago, a band of orcs invaded the community and defeated the dwarves.");
		output("`n`nIt is said that only the leader of the dwarves, Kilmor, and a few others escaped from the Orcs.");
		addnav("Return","runmodule.php?module=signetd4&loc=".$temp);
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
		addnav("Return","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="scroll3b"){
		output("`c`b`^Prison Notes by Kilmor`0`c`b`n");
		output("The Orcs are less than wonderful captors. I hope to be able to break free soon.");
		output("My first journey will be to the `^Temple of the Aarde Priests`0.  It is rumored that the high priest of the temple has the power of prophesy, but few have been allowed to see him.");
		addnav("Return","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="scroll4b"){
		output("`c`b`^Report from Evad the Sage`c`b");
		output("`n`0In `\$Fiamma's Fortress`0 there is a secret control room which can be accessed from one of two secret passages.");
		output("The first starts in `\$Fiamma's`0 room.  The other starts between the arena and the jail cell.");
		output("From this room various gates around the castle can be operated.");
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="scroll5b"){
		output("`c`b`^The Story of Fiamma Fortress`c`b");
		output("`n`0Many years ago when Mierscri was invading the land, the men and beasts put aside their differences and joined together to fight him.");
		output("`n`nThe lowly Fiamma, who was an officer in this combined army, told Mierscri of the plan; thus it failed.");
		output("`n`nAs a reward for this action, Fiamma was given a great fortress and given the Fire Signet to guard over.");
		addnav("Continue","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="scroll6b"){
		output("`c`b`^Fiamma's Fortress`0`c`b`n");
		output("`\$My Fortress is impenetrable.  I feel safe knowing that the control room can only be accessed from two places.");
		output("I carefully guard the first entrance in my bedroom so I have no concern of it being discovered.");
		output("However, even I fear mentioning the second location.  That secret will not be revealed on paper.");
		addnav("Return","runmodule.php?module=signetd4&loc=".$temp);
	}
	if ($op=="scroll7b"){
		output("`c`b`^Fiamma's Plans`0`c`b`n");
		output("`\$I cannot be a servant of the `bMaster Mierscri`b for all my life.  I have plans!");
		output("`n`nHis `)Dark Warlocks `\$concern me greatly but I think I have finally found a chance to defeat him.");
		output("I was able to place a deadly trap in his treasure trove of `^gold`\$ and coated his pile of `%gems`\$ with a poison.  The next time he plans to count his plunder he will find his life shortened!");
		output("`n`nIf that fails, I have a plan to steal his wand and use it against him.  He is vulnerable when his magic is turned against him.");
		output("`n`nMy reign of terror will begin soon after!");
		addnav("Return","runmodule.php?module=signetd4&loc=".$temp);
	}
	//Scrolls for in the bio start here
	if ($op=="scroll6"){
		$userid = httpget("user");
		output("`c`b`^Fiamma's Fortress`0`c`b`n");
		output("`\$My Fortress is impenetrable.  I feel safe knowing that the control room can only be accessed from two places.");
		output("I carefully guard the first entrance in my bedroom so I have no fear of it being discovered.");
		output("However, even I fear mentioning the second location.  That secret will not be revealed on paper.");
		addnav("Return to Signets","runmodule.php?module=signetsale&op=signetnotes&user=$userid");
	}
	if ($op=="scroll7"){
		$userid = httpget("user");
		output("`c`b`^Fiamma's Plans`0`c`b`n");
		output("`\$I cannot be a servant of `bMaster Mierscri`b for all my life.  I have plans!");
		output("`n`nHis `)Dark Warlocks `\$concern me greatly but I think I have finally found a chance to defeat him.");
		output("I was able to place a deadly trap in his treasure trove of `^gold`\$ and coated his pile of `%gems`\$ with a poison.  The next time he plans to count his plunder he will find his life shortened!");
		output("`n`nIf that fails, I have a plan to steal his wand and use it against him.  He is vulnerable when his magic is turned against him.");
		output("`n`nMy reign of terror will begin soon after!");
		addnav("Return to Signets","runmodule.php?module=signetsale&op=signetnotes&user=$userid");
	}
	page_footer();
}
?>