<?php
function signetd5_fight($op) {
	global $session,$badguy;
	$temp=get_module_pref("pqtemp");
	page_header("Dark Lair Confrontation");
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($op=="door"){
		if ($temp==902 && $allprefs['loc902']==0) $allprefs['loc902']=2;
		elseif ($temp==898 && $allprefs['loc898']==0) $allprefs['loc898']=2;
		elseif ($temp==724 && $allprefs['loc724']==0) $allprefs['loc724']=2;
		elseif ($temp==736 && $allprefs['loc736']==0) $allprefs['loc736']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"`Qa Heavy Door`0",
			"creaturelevel"=>12,
			"creatureweapon"=>"sharp splinters",
			"creatureattack"=>20,
			"creaturedefense"=>20,
			"creaturehealth"=>100,
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="lostspirit"){
		if ($temp==143 && $allprefs['loc143']==0) $allprefs['loc143']=2;
		elseif ($temp==147 && $allprefs['loc147']==0) $allprefs['loc147']=2;
		elseif ($temp==149 && $allprefs['loc149']==0) $allprefs['loc149']=2;
		elseif ($temp==151 && $allprefs['loc151']==0) $allprefs['loc151']=2;
		elseif ($temp==153 && $allprefs['loc153']==0) $allprefs['loc153']=2;
		elseif ($temp==155 && $allprefs['loc155']==0) $allprefs['loc155']=2;
		elseif ($temp==157 && $allprefs['loc157']==0) $allprefs['loc157']=2;
		elseif ($temp==161 && $allprefs['loc161']==0) $allprefs['loc161']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$targetlevel=$session['user']['level']+1;
		$sql = "SELECT * FROM " . db_prefix("creatures") . " WHERE creaturelevel = $targetlevel AND forest=1 ORDER BY rand(".e_rand().") LIMIT 1";
		$result = db_query($sql);
		$badguy = db_fetch_assoc($result);
		$badguy = modulehook("buffbadguy", $badguy);
		$badguy['creaturename']="Lost Spirit";
		$badguy['creatureweapon']="Icy Cold Touch";
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
			$badguy['creaturename']="an Orc";
			$badguy['creatureweapon']="a Dagger";
		}elseif ($randmonster==2){
			$badguy['creaturename']="a Tiger";
			$badguy['creatureweapon']="deadly claws";
		}elseif ($randmonster==3){
			$badguy['creaturename']="a Death Fly";
			$badguy['creatureweapon']="a deadly buzz";
		}elseif ($randmonster==4){
			$badguy['creaturename']="an Ogre";
			$badguy['creatureweapon']="a Spiked Club";
		}
		$badguy['type']="";
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="waterelemental"){
		if ($temp==381 && $allprefs['loc381']==0) $allprefs['loc381']=2;
		elseif ($temp==385 && $allprefs['loc385']==0) $allprefs['loc385']=2;
		elseif ($temp==387 && $allprefs['loc387']==0) $allprefs['loc387']=2;
		elseif ($temp==389 && $allprefs['loc389']==0) $allprefs['loc389']=2;
		elseif ($temp==391 && $allprefs['loc391']==0) $allprefs['loc391']=2;
		elseif ($temp==393 && $allprefs['loc393']==0) $allprefs['loc393']=2;
		elseif ($temp==395 && $allprefs['loc395']==0) $allprefs['loc395']=2;
		elseif ($temp==399 && $allprefs['loc399']==0) $allprefs['loc399']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"Water Elemental",
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>"powerful blasts of water",
			"creatureattack"=>($session['user']['attack']+e_rand(2,4)),
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4)),
			"creaturehealth"=>round($session['user']['maxhitpoints']*.96),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="blackwarlock"){
		if ($temp==1104 && $allprefs['loc1104']==0) $allprefs['loc1104']=2;
		elseif ($temp==685 && $allprefs['loc685']==0) $allprefs['loc685']=2;
		elseif ($temp==694 && $allprefs['loc694']==0) $allprefs['loc694']=2;
		elseif ($temp==698 && $allprefs['loc698']==0) $allprefs['loc698']=2;
		elseif ($temp==707 && $allprefs['loc707']==0) $allprefs['loc707']=2;
		elseif ($temp==519 && $allprefs['loc519']==0) $allprefs['loc519']=2;
		elseif ($temp==524 && $allprefs['loc524']==0) $allprefs['loc524']=2;
		elseif ($temp==528 && $allprefs['loc528']==0) $allprefs['loc528']=2;
		elseif ($temp==533 && $allprefs['loc533']==0) $allprefs['loc533']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$targetlevel=$session['user']['level']+2;
		if ($session['user']['level']==15) $targetlevel=16;
		if ($session['user']['maxhitpoints']>$session['user']['hitpoints']) $hitpoints=$session['user']['maxhitpoints'];
		else $hitpoints=$session['user']['hitpoints'];
		if ($session['user']['attack']<25) $attack=25;
		else $attack=$session['user']['attack'];
		if ($session['user']['defense']<25) $attack=25;
		else $attack=$session['user']['defense'];
		$badguy = array(
			"type"=>"",
			"creaturename"=>"Black Warlock",
			"creaturelevel"=>$targetlevel,
			"creatureweapon"=>"a Spiked Morning Star",
			"creatureattack"=>($attack+e_rand(2,4))*.95,
			"creaturedefense"=>($defense+e_rand(1,2))*.95,
			"creaturehealth"=>round($hitpoints+e_rand(1,25)*.95),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="blackwarlocks"){
		if ($temp==335 && $allprefs['loc335']==0) $allprefs['loc335']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$targetlevel=$session['user']['level']+2;
		if ($session['user']['level']==15) $targetlevel=16;
		if ($session['user']['maxhitpoints']>$session['user']['hitpoints']) $hitpoints=$session['user']['maxhitpoints'];
		else $hitpoints=$session['user']['hitpoints'];
		if ($session['user']['attack']<25) $attack=25;
		else $attack=$session['user']['attack'];
		if ($session['user']['defense']<25) $attack=25;
		else $attack=$session['user']['defense'];
		apply_buff('secwarlock',array(
			"name"=>"`)Warlock Pain",
			"rounds"=>15,
			"minioncount"=>1,
			"mingoodguydamage"=>0,
			"maxgoodguydamage"=>$session['user']['level'],
			"wearoff"=>"`&The `)Second Black Warlock`& falls dead at your feet.",
			"roundmsg"=>"`&The `)Second Black Warlock`& swings at you...",
			"effectmsg"=>"`&You feel the pain from the attack as it does `\${damage} `&points of damage.`0",
			"effectnodmgmsg"=>"`&He misses!",
			"effectfailmsg"=>"`&He stumbles, unable to hit you.`0",
		));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"Black Warlocks",
			"creaturelevel"=>$targetlevel,
			"creatureweapon"=>"a Spiked Morning Star",
			"creatureattack"=>($attack+e_rand(2,4))*.95,
			"creaturedefense"=>($defense+e_rand(1,2))*.95,
			"creaturehealth"=>round($hitpoints+e_rand(1,25)*.95),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="mierscri"){
		$allprefs['darkdead']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$hitpoints=$session['user']['maxhitpoints'];
		if ($session['user']['attack']<38) $attack=38;
		else $attack=$session['user']['attack'];
		if ($session['user']['defense']<38) $attack=38;
		else $attack=$session['user']['defense'];
		$badguy = array(
			"type"=>"",
			"creaturename"=>"`b`)Mierscri`b",
			"creatureweapon"=>"the `b`)Black Slayer Sword`b",
			"creaturelevel"=>16,
			"creatureattack"=>($attack+e_rand(2,6))*get_module_setting("dlatt"),
			"creaturedefense"=>($defense+e_rand(2,6))*get_module_setting("dldef"),
			"creaturehealth"=>round($hitpoints*get_module_setting("dlhp")),
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
				$allprefs=unserialize(get_module_pref('allprefs'));
       			addnav("Continue","runmodule.php?module=signetd5&loc=".get_module_pref('pqtemp'));
				//opening doors
				if (($temp==902 && $allprefs['loc902']==2)||($temp==898 && $allprefs['loc898']==2) ||($temp==724 && $allprefs['loc724']==2) ||($temp==736 && $allprefs['loc736']==2)) {
					output("`^`n`bYou have opened the door safely.");
					$experience=$session['user']['level']*e_rand(6,9);
					output("`#You receive `6%s `#experience.`n",$experience);
					$session['user']['experience']+=$experience;
					if ($allprefs['loc902']==2) $allprefs['loc902']=1;
					elseif ($allprefs['loc898']==2) $allprefs['loc898']=1;	
					elseif ($allprefs['loc724']==2) $allprefs['loc724']=1;	
					elseif ($allprefs['loc736']==2) $allprefs['loc736']=1;
				//Mierscri
				}elseif($temp==1257 && $allprefs['darkdead']==2){
					$allprefs['darkdead']=1;
					output("`n`&Mierscri falls to the ground as you prepare to strike the final blow...");
					output("`n`n`b`)'Wait! Let me make you a proposition!'`b");
					addnav("Continue","runmodule.php?module=signetd5&op=endgame");
					blocknav("runmodule.php?module=signetd5&loc=".get_module_pref('pqtemp'));
				//Two Black Warlocks
				}elseif($temp==335 && $allprefs['loc335']==2){
					output("`b`4You have slain the `)Black Warlocks`4.`b");
					$expmultiply = e_rand(22,32);
					$expbonus=$session['user']['dragonkills']*3;
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`n`@The bodies disintegrate into dark fumes.");
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					debuglog("gained $expgain experience by killing 2 Black Warlocks in the 5th Signet Dungeon");
					apply_buff('secwarlock', array());
					if ($allprefs['loc335']==2) $allprefs['loc335']=1;
				//Black Warlocks
				}elseif (($temp==1104 && $allprefs['loc1104']==2)||($temp==685 && $allprefs['loc685']==2)||($temp==694 && $allprefs['loc694']==2)||($temp==698 && $allprefs['loc698']==2)||($temp==707 && $allprefs['loc707']==2)||($temp==519 && $allprefs['loc519']==2)||($temp==524 && $allprefs['loc524']==2)||($temp==528 && $allprefs['loc528']==2)||($temp==533 && $allprefs['loc533']==2)){
					output("`b`4You have slain the `)Black Warlock`4.`b");
					$expmultiply = e_rand(18,28);
					$expbonus=$session['user']['dragonkills']*2.7;
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`n`@His body disintegrates into dark fumes.  There is no treasure here for you.");
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					debuglog("gained $expgain experience killing a Black Warlock in the 5th Signet Dungeon");
					if ($allprefs['loc1104']==2) $allprefs['loc1104']=1;
					elseif ($allprefs['loc685']==2) $allprefs['loc685']=1;
					elseif ($allprefs['loc694']==2) $allprefs['loc694']=1;
					elseif ($allprefs['loc698']==2) $allprefs['loc698']=1;
					elseif ($allprefs['loc707']==2) $allprefs['loc707']=1;
					elseif ($allprefs['loc519']==2) $allprefs['loc519']=1;
					elseif ($allprefs['loc524']==2) $allprefs['loc524']=1;
					elseif ($allprefs['loc528']==2) $allprefs['loc528']=1;
					elseif ($allprefs['loc533']==2) $allprefs['loc533']=1;
					apply_buff('kblind', array());
				//Water Elemental	
				}elseif (($temp==381 && $allprefs['loc381']==2)||($temp==385 && $allprefs['loc385']==2)||($temp==387 && $allprefs['loc387']==2)||($temp==389 && $allprefs['loc389']==2)||($temp==391 && $allprefs['loc391']==2)||($temp==393 && $allprefs['loc393']==2)||($temp==395 && $allprefs['loc395']==2)||($temp==399 && $allprefs['loc399']==2)){
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$gold=e_rand(120,220);
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(13,23);
					$expbonus=$session['user']['dragonkills']*2.1;
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You search through the watery corpse and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					debuglog("gained $expgain experience and $gold gold by defeating a Water Elemental in the 5th Signet Dungeon");
					if ($allprefs['loc381']==2) $allprefs['loc381']=1;
					elseif ($allprefs['loc385']==2) $allprefs['loc385']=1;
					elseif ($allprefs['loc387']==2) $allprefs['loc387']=1;
					elseif ($allprefs['loc389']==2) $allprefs['loc389']=1;
					elseif ($allprefs['loc391']==2) $allprefs['loc391']=1;
					elseif ($allprefs['loc393']==2) $allprefs['loc393']=1;
					elseif ($allprefs['loc395']==2) $allprefs['loc395']=1;
					elseif ($allprefs['loc399']==2) $allprefs['loc399']=1;
				//Lost Spirit	
				}elseif (($temp==143 && $allprefs['loc143']==2)||($temp==147 && $allprefs['loc147']==2)||($temp==149 && $allprefs['loc149']==2)||($temp==151 && $allprefs['loc151']==2)||($temp==153 && $allprefs['loc153']==2)||($temp==155 && $allprefs['loc155']==2)||($temp==157 && $allprefs['loc157']==2)||($temp==161 && $allprefs['loc161']==2)){
					output("`b`4You have slain a `^Lost Spirit`4.`b`n");
					$gold=e_rand(100,200);
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(11,21);
					$expbonus=$session['user']['dragonkills']*1.9;
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You search through the tattered remains and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					debuglog("gained $expgain experience and $gold gold by defeating a Lost Spirit in the 5th Signet Dungeon");
					if ($allprefs['loc143']==2) $allprefs['loc143'];
					elseif ($allprefs['loc147']==2) $allprefs['loc147']=1;
					elseif ($allprefs['loc149']==2) $allprefs['loc149']=1;
					elseif ($allprefs['loc151']==2) $allprefs['loc151']=1;
					elseif ($allprefs['loc153']==2) $allprefs['loc153']=1;
					elseif ($allprefs['loc155']==2) $allprefs['loc155']=1;
					elseif ($allprefs['loc157']==2) $allprefs['loc157']=1;
					elseif ($allprefs['loc161']==2) $allprefs['loc161']=1;
				//next lines are for random encounters
				}else{
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$gold=e_rand(50,150);
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(10,20);
					$expbonus=$session['user']['dragonkills']*1.5;
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					debuglog("gained $expgain experience and $gold gold by defeating a random monster in the 5th Signet Dungeon");
					output("`n`@You search through the smelly corpse and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);				
				}
				set_module_pref('allprefs',serialize($allprefs));
				if ((get_module_setting("healing")==1) && ($session['user']['hitpoints']<$session['user']['maxhitpoints']*.5)){
					$hpdown=$session['user']['maxhitpoints']-$session['user']['hitpoints'];
					switch(e_rand(1,18)){
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
						case 8: case 9: case 10: case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18:
						break;
					}
				}
				$badguy=array();
				$session['user']['badguy']="";
			}elseif ($defeat){
				//door kills the player
				$allprefs=unserialize(get_module_pref('allprefs'));
				if (($temp==902 && $allprefs['loc902']==2)||($temp==898 && $allprefs['loc898']==2)||($temp==724 && $allprefs['loc724']==2) ||($temp==736 && $allprefs['loc736']==2)) {
					output("A splinter severs a major blood vessel and you die.`n");
					addnews("`% %s`5 has been slain trying to break down a door.",$session['user']['name']);
					if ($allprefs['loc902']==2) $allprefs['loc902']=0;
					elseif ($allprefs['loc898']==2) $allprefs['loc898']=0;	
					elseif ($allprefs['loc724']==2) $allprefs['loc724']=0;	
					elseif ($allprefs['loc736']==2) $allprefs['loc736']=0;
				//mierscri kills the player
				}elseif($temp==1257 && $allprefs['darkdead']==2){
					output("`n`#The Dark Lord towers over your corpse.");
					output("`b`)'I told you that your life would be mine.  Now I grow stronger with your death!`b");
					output("`n`n`)Mierscri`0 takes all your gold.");
					$exploss = round($session['user']['experience']*get_module_setting("perexpm")/100);
					if ($exploss>0) output("`n`n`4You lose `^%s `4experience.",$exploss);
					$session['user']['gold']=0;
					$session['user']['experience']-=$exploss;
					$allprefs['darkdead']=0;
					$allprefs['loc849']=0;
					debuglog("lost all gold and $exploss experience when killed by Mierscri in the 5th Signet Dungeon");
					addnews("`%%s`5 has been slain by the `)Dark Lord`5 in a dungeon.",$session['user']['name']);
				//Black Warlock kills the player
				}elseif (($temp==1104 && $allprefs['loc1104']==2)||($temp==685 && $allprefs['loc685']==2)||($temp==694 && $allprefs['loc694']==2)||($temp==698 && $allprefs['loc698']==2)||($temp==707 && $allprefs['loc707']==2)||($temp==519 && $allprefs['loc519']==2)||($temp==524 && $allprefs['loc524']==2)||($temp==528 && $allprefs['loc528']==2)||($temp==533 && $allprefs['loc533']==2)||($temp==335 && $allprefs['loc335']==2)){
					$exploss = round($session['user']['experience']*get_module_setting("perexpk")/100);
					output("`n`^As you die, you hear the dreadful hissing of the `)Black Warlock`^...");
					output("`n`n`)'The Massster will ussse your `^gold`) to make hissss army stronger!'");
					output("`n`n`0The `)Black Warlock`0 takes all your gold.");
					output("`n`nYou have died.  Perhaps you will be able to face such evil another day.  But not this one!");
					if ($exploss>0) output("`n`n`4You lose `^%s `4experience.",$exploss);
					debuglog("lost all gold and $exploss experience when killed by a Black Warlock in the 5th Signet Dungeon");
					$session['user']['gold']=0;
					$session['user']['experience']-=$exploss;
					if ($allprefs['loc1104']==2) $allprefs['loc1104']=0;
					elseif ($allprefs['loc685']==2) $allprefs['loc685']=0;
					elseif ($allprefs['loc694']==2) $allprefs['loc694']=0;
					elseif ($allprefs['loc698']==2) $allprefs['loc698']=0;
					elseif ($allprefs['loc707']==2) $allprefs['loc707']=0;
					elseif ($allprefs['loc519']==2) $allprefs['loc519']=0;
					elseif ($allprefs['loc524']==2) $allprefs['loc524']=0;
					elseif ($allprefs['loc528']==2) $allprefs['loc528']=0;
					elseif ($allprefs['loc533']==2) $allprefs['loc533']=0;
					elseif ($allprefs['loc335']==2) $allprefs['loc335']=0;
					apply_buff('kblind', array());
					apply_buff('secwarlock', array());
					addnews("`%%s`5 has been slain by a `)Black Warlock`5 in a dungeon.",$session['user']['name']);						
				//Other creature kills the player
				}elseif (($temp==381 && $allprefs['loc381']==2)||($temp==385 && $allprefs['loc385']==2)||($temp==387 && $allprefs['loc387']==2)||($temp==389 && $allprefs['loc389']==2)||($temp==391 && $allprefs['loc391']==2)||($temp==393 && $allprefs['loc393']==2)||($temp==395 && $allprefs['loc395']==2)||($temp==399 && $allprefs['loc399']==2)||($temp==143 && $allprefs['loc143']==2)||($temp==147 && $allprefs['loc147']==2)||($temp==149 && $allprefs['loc149']==2)||($temp==151 && $allprefs['loc151']==2)||($temp==153 && $allprefs['loc153']==2)||($temp==155 && $allprefs['loc155']==2)||($temp==157 && $allprefs['loc157']==2)||($temp==161 && $allprefs['loc161']==2)){
					$exploss = round($session['user']['experience']*get_module_setting("perexpr")/100);
					$session['user']['experience']-=$exploss;
					output("`n`^As you hit the ground the %s`^ runs away.",$badguy['creaturename']);
					if ($exploss>0) output("`n`n`4You lose `^%s `4experience.",$exploss);
					if ($allprefs['loc381']==2) $allprefs['loc381']=0;
					elseif ($allprefs['loc385']==2) $allprefs['loc385']=0;
					elseif ($allprefs['loc387']==2) $allprefs['loc387']=0;
					elseif ($allprefs['loc389']==2) $allprefs['loc389']=0;
					elseif ($allprefs['loc391']==2) $allprefs['loc391']=0;
					elseif ($allprefs['loc393']==2) $allprefs['loc393']=0;
					elseif ($allprefs['loc395']==2) $allprefs['loc395']=0;
					elseif ($allprefs['loc399']==2) $allprefs['loc399']=0;
					elseif ($allprefs['loc143']==2) $allprefs['loc143']=0;
					elseif ($allprefs['loc147']==2) $allprefs['loc147']=0;
					elseif ($allprefs['loc149']==2) $allprefs['loc149']=0;
					elseif ($allprefs['loc151']==2) $allprefs['loc151']=0;
					elseif ($allprefs['loc153']==2) $allprefs['loc153']=0;
					elseif ($allprefs['loc155']==2) $allprefs['loc155']=0;
					elseif ($allprefs['loc157']==2) $allprefs['loc157']=0;
					elseif ($allprefs['loc161']==2) $allprefs['loc161']=0;
					debuglog("lost $exploss experience when killed by a monster in the 5th Signet Dungeon");
					addnews("`%%s`5 has been slain by an evil %s in a dungeon.",$session['user']['name'],$badguy['creaturename']);
				//Next lines are for random encounters
				}else{
					output("`n`^As you hit the ground the %s`^ runs away.",$badguy['creaturename']);
					$exploss = round($session['user']['experience']*get_module_setting("perexpr")/100);
					if ($exploss>0) output("`n`n`4You lose `^%s `4experience.",$exploss);
					$session['user']['experience']-=$exploss;
					debuglog("lost $exploss exerpience when killed by a monster in the 5th Signet Dungeon");
					addnews("`%%s`5 has been slain by an evil %s in a dungeon.",$session['user']['name'],$badguy['creaturename']);
				}
				set_module_pref('allprefs',serialize($allprefs));
		        $badguy=array();
		        $session['user']['badguy']="";  
		        $session['user']['hitpoints']=0;
		        $session['user']['alive']=false;
		        addnav("Continue","shades.php");
			}else{
					require_once("lib/fightnav.php");
					fightnav(true,false,"runmodule.php?module=signetd5");
			}
		}else{
			redirect("runmodule.php?module=signetd5&loc=".get_module_pref('pqtemp'));	
		}
	}
	page_footer();
}
function signetd5_misc($op) {
	$temp=get_module_pref("pqtemp");
	page_header("Dark Lair");
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
		$allprefse=unserialize(get_module_pref('allprefs',"signetd5",$id));
		if ($allprefse['randomp']=="") $allprefse['randomp']= 0;
		if ($allprefse['loc1016b']=="") $allprefse['loc1016b']= 0;
		if ($allprefse['mazeturn']=="") $allprefse['mazeturn']= 0;
		if ($allprefse['startloc']=="") $allprefse['startloc']=1274;
		set_module_pref('allprefs',serialize($allprefse),'signetd5',$id);
		if ($subop!='edit'){
			$allprefse=unserialize(get_module_pref('allprefs',"signetd5",$id));
			$allprefse['complete']= httppost('complete');
			$allprefse['announce']= httppost('announce');
			$allprefse['randomp']= httppost('randomp');
			$allprefse['powersignet']= httppost('powersignet');
			$allprefse['darkdead']= httppost('darkdead');
			$allprefse['transport']= httppost('transport');
			$allprefse['usedtrans']= httppost('usedtrans');
			$allprefse['bankloss']= httppost('bankloss');
			$allprefse['loc109']= httppost('loc109');
			$allprefse['loc113']= httppost('loc113');
			$allprefse['loc115']= httppost('loc115');
			$allprefse['loc117']= httppost('loc117');
			$allprefse['loc119']= httppost('loc119');
			$allprefse['loc121']= httppost('loc121');
			$allprefse['loc123']= httppost('loc123');
			$allprefse['loc127']= httppost('loc127');
			$allprefse['loc143']= httppost('loc143');
			$allprefse['loc147']= httppost('loc147');
			$allprefse['loc149']= httppost('loc149');
			$allprefse['loc151']= httppost('loc151');
			$allprefse['loc153']= httppost('loc153');
			$allprefse['loc155']= httppost('loc155');
			$allprefse['loc157']= httppost('loc157');
			$allprefse['loc161']= httppost('loc161');
			$allprefse['loc279']= httppost('loc279');
			$allprefse['loc283']= httppost('loc283');
			$allprefse['loc285']= httppost('loc285');
			$allprefse['loc287']= httppost('loc287');
			$allprefse['loc289']= httppost('loc289');
			$allprefse['loc291']= httppost('loc291');
			$allprefse['loc293']= httppost('loc293');
			$allprefse['loc297']= httppost('loc297');
			$allprefse['loc335']= httppost('loc335');
			$allprefse['loc347']= httppost('loc347');
			$allprefse['loc361']= httppost('loc361');
			$allprefse['loc381']= httppost('loc381');
			$allprefse['loc385']= httppost('loc385');
			$allprefse['loc387']= httppost('loc387');
			$allprefse['loc389']= httppost('loc389');
			$allprefse['loc391']= httppost('loc391');
			$allprefse['loc393']= httppost('loc393');
			$allprefse['loc395']= httppost('loc395');
			$allprefse['loc399']= httppost('loc399');
			$allprefse['loc421']= httppost('loc421');
			$allprefse['loc425']= httppost('loc425');
			$allprefse['loc503']= httppost('loc503');
			$allprefse['loc503b']= httppost('loc503b');
			$allprefse['loc505']= httppost('loc505');
			$allprefse['loc505b']= httppost('loc505b');
			$allprefse['loc507']= httppost('loc507');
			$allprefse['loc519']= httppost('loc519');
			$allprefse['loc524']= httppost('loc524');
			$allprefse['loc528']= httppost('loc528');
			$allprefse['loc533']= httppost('loc533');
			$allprefse['loc685']= httppost('loc685');
			$allprefse['loc687']= httppost('loc687');
			$allprefse['loc691']= httppost('loc691');
			$allprefse['loc694']= httppost('loc694');
			$allprefse['loc698']= httppost('loc698');
			$allprefse['loc701']= httppost('loc701');
			$allprefse['loc705']= httppost('loc705');
			$allprefse['loc707']= httppost('loc707');
			$allprefse['loc724']= httppost('loc724');
			$allprefse['loc736']= httppost('loc736');
			$allprefse['loc766']= httppost('loc766');
			$allprefse['loc774']= httppost('loc774');
			$allprefse['loc777']= httppost('loc777');
			$allprefse['loc792']= httppost('loc792');
			$allprefse['loc804']= httppost('loc804');
			$allprefse['loc834']= httppost('loc834');
			$allprefse['loc849']= httppost('loc849');
			$allprefse['loc860']= httppost('loc860');
			$allprefse['loc872']= httppost('loc872');
			$allprefse['loc895']= httppost('loc895');
			$allprefse['loc898']= httppost('loc898');
			$allprefse['loc902']= httppost('loc902');
			$allprefse['loc905']= httppost('loc905');
			$allprefse['loc1002']= httppost('loc1002');
			$allprefse['loc1012']= httppost('loc1012');
			$allprefse['loc1016']= httppost('loc1016');
			$allprefse['loc1016b']= httppost('loc1016b');
			$allprefse['loc1104']= httppost('loc1104');
			$allprefse['loc1104b']= httppost('loc1104b');
			$allprefse['loc1138']= httppost('loc1138');
			$allprefse['loc1172']= httppost('loc1172');
			$allprefse['loc1257']= httppost('loc1257');
			$allprefse['mazeturn']= httppost('mazeturn');
			$allprefse['startloc']= httppost('startloc');
			$allprefse['header']= httppost('header');
			set_module_pref('allprefs',serialize($allprefse),'signetd5',$id);
			output("Allprefs Updated`n");
			$subop="edit";
		}
		if ($subop=="edit"){
			require_once("lib/showform.php");
			$form = array(
				"Dark Lair,title",
				"complete"=>"Has player completed the Lair?,bool",
				"super"=>"Does player have superuser access to the Lair?,bool",
				"announce"=>"Will they hear the announcement on the next newday?,bool",
				"Encounters,title",
				"randomp"=>"How many random monsters has player encountered so far?,int",
				"powersignet"=>"`%Received the Power Signet?,bool",
				"darkdead"=>"Defeated Mierscri?,enum,0,No,1,Yes,2,In Process",
				"transport"=>"Which Hall contains the transporter?,range,0,8,1",
				"usedtrans"=>"Has player used the transporter at least once?,bool",
				"bankloss"=>"Has player lost money from their bank?,bool",
				"loc109"=>"Passed Location 109?,bool",
				"loc113"=>"Passed Location 113?,bool",
				"loc115"=>"Passed Location 115?,bool",
				"loc117"=>"Passed Location 117?,bool",
				"loc119"=>"Passed Location 119?,bool",
				"loc121"=>"Passed Location 121?,bool",
				"loc123"=>"Passed Location 123?,bool",
				"loc127"=>"Passed Location 127?,bool",
				"loc143"=>"Passed Location 143?,enum,0,No,1,Yes,2,In Process",
				"loc147"=>"Passed Location 147?,enum,0,No,1,Yes,2,In Process",
				"loc149"=>"Passed Location 149?,enum,0,No,1,Yes,2,In Process",
				"loc151"=>"Passed Location 151?,enum,0,No,1,Yes,2,In Process",
				"loc153"=>"Passed Location 153?,enum,0,No,1,Yes,2,In Process",
				"loc155"=>"Passed Location 155?,enum,0,No,1,Yes,2,In Process",
				"loc157"=>"Passed Location 157?,enum,0,No,1,Yes,2,In Process",
				"loc161"=>"Passed Location 161?,enum,0,No,1,Yes,2,In Process",
				"loc279"=>"Passed Location 279?,bool",
				"loc283"=>"Passed Location 283?,bool",
				"loc285"=>"Passed Location 285?,bool",
				"loc287"=>"Passed Location 287?,bool",
				"loc289"=>"Passed Location 289?,bool",
				"loc291"=>"Passed Location 291?,bool",
				"loc293"=>"Passed Location 293?,bool",
				"loc297"=>"Passed Location 297?,bool",
				"loc335"=>"Passed Location 335?,enum,0,No,1,Yes,2,In Process",
				"loc347"=>"Passed Location 347/351?,bool",
				"loc361"=>"Passed Location 361/365?,bool",
				"loc381"=>"Passed Location 381?,enum,0,No,1,Yes,2,In Process",
				"loc385"=>"Passed Location 385?,enum,0,No,1,Yes,2,In Process",
				"loc387"=>"Passed Location 387?,enum,0,No,1,Yes,2,In Process",
				"loc389"=>"Passed Location 389?,enum,0,No,1,Yes,2,In Process",
				"loc391"=>"Passed Location 391?,enum,0,No,1,Yes,2,In Process",
				"loc393"=>"Passed Location 393?,enum,0,No,1,Yes,2,In Process",
				"loc395"=>"Passed Location 395?,enum,0,No,1,Yes,2,In Process",
				"loc399"=>"Passed Location 399?,enum,0,No,1,Yes,2,In Process",
				"loc421"=>"Passed Location 421/423?,bool",
				"loc425"=>"Passed Location 425/427?,bool",
				"loc503"=>"Passed Location 503?,bool",
				"loc503b"=>"Passed Location 503b?,bool",
				"loc505"=>"Passed Location 505?,bool",
				"loc505b"=>"Passed Location 505b?,bool",
				"loc507"=>"Passed Location 507?,bool",
				"loc519"=>"Passed Location 519?,enum,0,No,1,Yes,2,In Process",
				"loc524"=>"Passed Location 524?,enum,0,No,1,Yes,2,In Process",
				"loc528"=>"Passed Location 528?,enum,0,No,1,Yes,2,In Process",
				"loc533"=>"Passed Location 533?,enum,0,No,1,Yes,2,In Process",
				"loc685"=>"Passed Location 685?,enum,0,No,1,Yes,2,In Process",
				"loc687"=>"Passed Location 687?,bool",
				"loc691"=>"Passed Location 691?,bool",
				"loc694"=>"Passed Location 694?,enum,0,No,1,Yes,2,In Process",
				"loc698"=>"Passed Location 698?,enum,0,No,1,Yes,2,In Process",
				"loc701"=>"Passed Location 701?,bool",
				"loc705"=>"Passed Location 705?,bool",
				"loc707"=>"Passed Location 707?,enum,0,No,1,Yes,2,In Process",
				"loc724"=>"Passed Location 724?,enum,0,No,1,Yes,2,In Process",
				"loc736"=>"Passed Location 736?,enum,0,No,1,Yes,2,In Process",
				"loc766"=>"Passed Location 766?,bool",
				"loc774"=>"Passed Location 774?,bool",
				"loc777"=>"Passed Location 777?,bool",
				"loc792"=>"Passed Location 792?,bool",
				"loc804"=>"Passed Location 804?,bool",
				"loc834"=>"Passed Location 834?,bool",
				"loc849"=>"Passed Location 849?,bool",
				"loc860"=>"Passed Location 860?,bool",
				"loc872"=>"Passed Location 872?,bool",
				"loc895"=>"Passed Location 895?,bool",
				"loc898"=>"Passed Location 898?,enum,0,No,1,Yes,2,In Process",
				"loc902"=>"Passed Location 902?,enum,0,No,1,Yes,2,In Process",
				"loc905"=>"Passed Location 905?,bool",
				"loc1002"=>"Passed Location 1002?,bool",
				"loc1012"=>"Passed Location 1012?,bool",
				"loc1016"=>"Passed Location 1016?,bool",
				"loc1016b"=>"Number of Times Passed Location 1016b?,int",
				"loc1104"=>"Passed Location 1104?,enum,0,No,1,Yes,2,In Process",
				"loc1104b"=>"Passed Location 1104b?,bool",
				"loc1138"=>"Passed Location 1138?,bool",
				"loc1172"=>"Passed Location 1172?,bool",
				"loc1257"=>"Passed Location 1257?,bool",
				"Maze,title",
				"mazeturn"=>"Maze Return,int",
				"startloc"=>"Starting location,int",
				"header"=>"Which header array is the player at?,range,0,8,1",
			);
			$allprefse=unserialize(get_module_pref('allprefs',"signetd5",$id));
			rawoutput("<form action='runmodule.php?module=signetd5&op=superuser&userid=$id' method='POST'>");
			showform($form,$allprefse,true);
			$click = translate_inline("Save");
			rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
			rawoutput("</form>");
			addnav("","runmodule.php?module=signetd5&op=superuser&userid=$id");
		}
	}
	if ($op=="tovill"){
		output("You find yourself standing near an `\$Emergency Exit`0.");
		output("`n`nWill you take this chance to escape from this evil lair? Or perhaps you're brave enough to search a different route...");
		if (get_module_setting("exitsave")==1){
			output("`n`nIf you leave now, you may return to the dungeon at this location or enter at the main entrance.");
			addnav("`\$Take Exit`0");
			addnav("Main Entrance","runmodule.php?module=signetd5&op=exits2");
			addnav("This Location","runmodule.php?module=signetd5&op=exits3");
			addnav("Continue");
		}elseif (get_module_setting("exitsave")==0){
			output("`n`nIf you leave now, you will re-enter the dungeon from the main entrance.");
			villagenav();
		}else{
			output("`n`nIf you leave now, you will re-enter the dungeon from this location.");
			addnav("`\$Take Exit","runmodule.php?module=signetd5&op=exits3");
		}
		addnav("Return to the Dungeon","runmodule.php?module=signetd5&loc=".($temp+34));
	}
	if ($op=="exits2"){
		output("You will re-enter the dungeon from the main exit.");
		$allprefs['startloc']=1274;
		set_module_pref('allprefs',serialize($allprefs));
		villagenav();
	}
	if ($op=="exits3"){
		output("You will re-enter the dungeon at this location.");
		$allprefs['startloc']=$temp+34;
		set_module_pref('allprefs',serialize($allprefs));
		villagenav();
	}
	if ($op=="reset"){
		$allprefsd4=unserialize(get_module_pref('allprefs','signetd4'));
		$allprefsd4['mazeturn']=0;
		$allprefsd4['randomp']=0;
		$allprefsd4['header']=0;
		$allprefsd4['loc182']=0;
		$allprefsd4['loc12']=0;
		$allprefsd4['loc18']=0;
		$allprefsd4['loc27']=0;
		$allprefsd4['loc29']=0;
		$allprefsd4['loc83']=0;
		$allprefsd4['loc87']=0;
		$allprefsd4['loc90']=0;
		$allprefsd4['loc93']=0;
		$allprefsd4['loc138']=0;
		$allprefsd4['loc152']=0;
		$allprefsd4['loc177']=0;
		$allprefsd4['loc178']=0;
		$allprefsd4['loc198']=0;
		$allprefsd4['loc250']=0;
		$allprefsd4['loc312']=0;
		$allprefsd4['loc328']=0;
		$allprefsd4['loc343']=0;
		$allprefsd4['loc362']=0;
		$allprefsd4['loc377']=0;
		$allprefsd4['loc383']=0;
		$allprefsd4['loc386']=0;
		$allprefsd4['loc394']=0;
		$allprefsd4['loc459']=0;
		$allprefsd4['loc463']=0;
		$allprefsd4['loc467']=0;
		$allprefsd4['loc485']=0;
		$allprefsd4['loc506']=0;
		$allprefsd4['loc513']=0;
		$allprefsd4['loc561']=0;
		$allprefsd4['loc587']=0;
		$allprefsd4['loc635']=0;
		$allprefsd4['loc655']=0;
		$allprefsd4['loc683']=0;
		$allprefsd4['loc689']=0;
		$allprefsd4['loc717']=0;
		$allprefsd4['loc726']=0;
		$allprefsd4['loc840']=0;
		$allprefsd4['loc853']=0;
		$allprefsd4['loc868']=0;
		$allprefsd4['loc874']=0;
		$allprefsd4['loc931']=0;
		$allprefsd4['loc931b']=0;
		$allprefsd4['loc934']=0;
		$allprefsd4['loc934b']=0;
		$allprefsd4['loc948']=0;
		$allprefsd4['loc1057']=0;
		$allprefsd4['loc1059']=0;
		$allprefsd4['loc1099']=0;
		$allprefsd4['loc1104']=0;
		$allprefsd4['loc1143']=0;
		$allprefsd4['loc1177']=0;
		$allprefsd4['loc1177count']=0;
		$allprefsd4['reset']=1;
		set_module_pref('allprefs',serialize($allprefsd4),'signetd4');
		clear_module_pref("maze","signetd4");
		clear_module_pref("pqtemp","signetd4");
		if ($allprefs['powersignet']==1) redirect("runmodule.php?module=signetd5&op=eg1b");
		else redirect("runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="1172"){
		output("It is only with the greatest of strength that you are able to enter this dark place.");
		output("Never has an adventurer dared to go this far.  Perhaps that will help you catch Mierscri unaware...");
		output("`n`nPerhaps it won't.");
		output("`n`nOnly through the strength given to you by the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signets`0 are you able to place one foot in front of the next.");
		output("`n`nUsing the powers of the `3Air`0, `QEarth`0, `!Water`0, and `\$Fire`^ Signets`0 you approach the door ahead of you.");
		$allprefs['loc1172']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="1138"){
		output("You step forward through the door and you hear the whirling and clunking of a large machine.");
		output("`n`nYour hopes of this being a surprise assault diminish.  Your fears of the unknown increase.");
		$allprefs['loc1138']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="1002"){
		output("You start to deduce that the doors act as a guardian; with each one warning Mierscri of your approach.");
		$allprefs['loc1002']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="898" || $op=="902"){
		output("You approach the door and notice that it's sturdier than the doors you've encountered before.");
		output("`n`nIt's going to take more effort to break this door down.");
		if ($temp==898) addnav("Leave","runmodule.php?module=signetd5&loc=".($temp+1));
		elseif ($temp==902) addnav("Leave","runmodule.php?module=signetd5&loc=".($temp-1));
		addnav("Break Down the Door","runmodule.php?module=signetd5&op=door");		
	}
	if ($op=="895" || $op=="905"){
		output("You approach next door expecting something horrible... but when you push firmly against it... it opens easily.");
		output("`n`nThe lights flicker on instantly in the room.  An eerie feeling of apprehension and fear seeps into your blood.");
		output("`n`nThe conflicts are easier to face than these silent 'greetings'.");
		if ($temp==895) $allprefs['loc895']=1;
		elseif ($temp==905) $allprefs['loc905']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);	
	}
	if ($op=="1104"){
		output("You walk forward into the Lair and hear a deep hissing sound.");
		output("`)`n`n'Who thinksss to dissturb the Massster?'");
		output("`n`n`0Before you stands a warlock dressed in black armor.  In his hand is a terrifying Morning Star.");
		output("You cannot see his face as it is covered by a spiked helm, but you can see his eyes.  His eyes... those terrible blood red orbs");
		output("that lack white or black... You know you will see them forever on those nights when you try to sleep but instead find that your mind");
		output("has other plans... It is at those times that the redness of his eyes will wash across you leaving you tremoring in a cold sweat. `n`nThen again");
		output("you may not survive this encounter.  If that is the case, then you have nothing to fear about your future.");
		output("`n`n`)'The Massster doess not welcome foolsss!  Therefore, it isss your time to DIE!'");
		$allprefs['loc1104b']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Fight the `)Black Warlock","runmodule.php?module=signetd5&op=blackwarlock");
	}
	if ($op=="792" || $op=="804"){
		output("You've stumbled into a trap!");
		output("`n`nA bear trap grabs onto your leg...");
		if ($temp==792) $allprefs['loc792']=1;
		elseif ($temp==804) $allprefs['loc804']=1;
		set_module_pref('allprefs',serialize($allprefs));
		//Higher level characters will be more successful; thieves are more successful
		if($session['user']['level']>13) $trap=4;
		elseif($session['user']['level']>10) $trap=6;
		elseif($session['user']['level']>6) $trap=8;
		else $trap=11;
		if (get_module_pref("skill","specialtythiefskills")>0) $trap--;
		switch(e_rand(1,$trap)){
			case 1:
			case 2:
				output("Your reflexes prevent the trap from catching your foot.`n`n");
				$chance = e_rand(1,3);
				if ($session['user']['turns']>0 && $chance==1){
					output("You `@lose a turn`0 thinking about how lucky you were not to get hurt.");
					$session['user']['turns']--;
				}elseif ($session['user']['turns']==0 && $chance==1){
					output("You feel an adrenaline rush for avoiding the trap! Nice job!");
					apply_buff('adrenaline',array(
						"name"=>"`QAdrenaline Rush",
						"rounds"=>5,
						"wearoff"=>"`^The adrenaline rush ends.",
						"atkmod"=>1.25,
						"roundmsg"=>"`#Adrenaline rushes through your veins!",
					));
				}
			break;
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
				output("The trap grabs hold tightly on your leg.`n`n`@");
				switch(e_rand(1,4)){
				case 1:
				case 2:
				case 3:
					$hitpoints=round($session['user']['hitpoints']*.1);
					if ($hitpoints>0) output("You `\$lose %s %s`@ before you can free yourself.",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					else output("Luckily, your boot protects you from any injuries.");
					$session['user']['hitpoints']-=$hitpoints;
				break;
				case 4:
					output("You feel a poison course through your body.");
					$hitpoints=round($session['user']['hitpoints']*.15);
					$session['user']['hitpoints']-=$hitpoints;
					if ($session['user']['turns']>0){
						$session['user']['turns']--;
						output("You lose one turn trying to dislodge your foot.");
					}
					if ($hitpoints>0) output("You `\$lose %s %s`@ before you can free yourself.",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					else output("Luckily, your boot protects you from any injuries.");
				break;
				}
			break;
			case 8:
			case 9:
			case 10:
			case 11:
				output("The trap grabs your leg tightly!`n`n`@");
				if ($session['user']['gold']>318){
					$session['user']['gold']-=318;
					debuglog("lost 318 gold in a trap in the 5th Signet Dungeon");
					output("You struggle wildly to get out and your gold bag flies loose... `^you lose 318 gold`@.");
				}elseif ($session['user']['gold']>0){
					output("You struggle wildly to get out and your gold bag flies loose... `^you lose all of your gold`@.");
					$session['user']['gold']=0;
					debuglog("lost all their gold in a trap in the 5th Signet Dungeon");
				}else{
					if ($session['user']['turns']>0){
						output("You sit down and study the trap to discover a release mechanism.");
						output("`n`n`@You `blose one turn`b.");
						$session['user']['turns']--;
					}else{
						output("You are lucky to find the mechanism to release your foot; no harm done!");
					}
				}
			break;
		}
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="724" || $op=="736"){
		output("You will need to break down this door to get through it.");
		addnav("Leave","runmodule.php?module=signetd5&loc=".($temp+34));
		addnav("Break Down the Door","runmodule.php?module=signetd5&op=door");	
	}
	if ($op=="691" || $op=="687" || $op=="701" || $op=="705"){
		output("The door is locked. Would you like to try to pick the lock?");
		addnav("Pick Lock","runmodule.php?module=signetd5&op=pickdoor");
		if ($temp==687 || $temp==701) addnav("Leave","runmodule.php?module=signetd5&loc=".($temp+1));
		else addnav("Leave","runmodule.php?module=signetd5&loc=".($temp-1));	
	}
	if ($op=="pickdoor"){
		output("You approach the door and attempt to unpick the lock.`n`n");
		//Higher level characters will be more successful; thieves are more successful
		if($session['user']['level']>12) $trap=4;
		elseif($session['user']['level']>8) $trap=6;
		elseif($session['user']['level']>4) $trap=8;
		else $trap=11;
		if (get_module_pref("skill","specialtythiefskills")>0) $trap--;
		switch(e_rand(1,$trap)){
			case 1:
			case 2:
				if ($temp==691) $allprefs['loc691']=1;
				elseif ($temp==687) $allprefs['loc687']=1;
				elseif ($temp==701) $allprefs['loc701']=1;
				elseif ($temp==705) $allprefs['loc705']=1;
				set_module_pref('allprefs',serialize($allprefs));
				output("You cautiously pick the lock... Successfully!!`n`n");
				$chance = e_rand(1,3);
				//I wanted to reward people for coming here with at least one turn left
				if ($session['user']['turns']>0 && $chance==1){
					output("You get an adrenaline rush for unlocking the door. You gain an extra turn!");
					$session['user']['turns']++;
				}
				addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
			break;
			case 3:
			case 4:
			case 5:
				output("Clearly the locks are very complicated... You fail to pick the lock!`n`n`@");
				switch(e_rand(1,5)){
				case 1:
				case 2:
				case 3:
				case 4:
					$hitpoints=round($session['user']['hitpoints']*.07);
					if ($hitpoints>0) output("`n`nA large poison dart shoots out from a trap mechanism and hits you in the shoulder. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					$session['user']['hitpoints']-=$hitpoints;
				break;
				case 5:
					output("You feel a poison course through your body from a hidden needle embedded in the lock.");
					$hitpoints=round($session['user']['hitpoints']*.11);
					$session['user']['hitpoints']-=$hitpoints;
					if ($session['user']['turns']>0){
						$session['user']['turns']--;
						output("You lose one turn.");
					}
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				break;
				}
				if ($temp==687 || $temp==701) addnav("Leave","runmodule.php?module=signetd5&loc=".($temp+1));
				else addnav("Leave","runmodule.php?module=signetd5&loc=".($temp-1));	
			break;
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
				output("Oh no! You've failed to pick the lock!`n`n`@");
				$goldloss=e_rand(250,450);
				if ($session['user']['gold']>$goldloss){
					$session['user']['gold']-=$goldloss;
					debuglog("lost $goldloss gold in a trap in the 5th Signet Dungeon");
					output("A blade sweeps out from the door and almost kills you! Luckily, it only cuts open your gold sack and you `^lose %s gold`@.",$goldloss);
				}elseif ($session['user']['gold']>0){
					output("A blade sweeps out from the door and almost kills you! Luckily, it only cuts open your gold sack and you `^lose all of your gold`@.");
					$session['user']['gold']=0;
					debuglog("lost all their gold in a trap in the 5th Signet Dungeon");
				}else{
					if ($session['user']['turns']>0){
						output("A trap triggers and you get knocked back by a huge gust of wind.");
						output("`n`n`@You `blose one turn`b.");
						$session['user']['turns']--;
					}
				}
				if ($temp==687 || $temp==701) addnav("Leave","runmodule.php?module=signetd5&loc=".($temp+1));
				else addnav("Leave","runmodule.php?module=signetd5&loc=".($temp-1));	
			break;
		}	
	}
	if ($op=="860"){
		output("You approach the door and notice a key pad of numbers and letters.");
		output("It seems like you'll have to enter a code to get in.");
		output("`n`nAfter looking around, you see some words scribbled below the keypad. Upon examining it more closely, you can read the following:");
		output("`n`n`c`\$Password: Smelly Dwarf Ruler from the Dwarf Dungeon`c");
		output("`n`0What do you decide to enter on the keypad?");
		addnav("Leave","runmodule.php?module=signetd5&loc=".($temp+34));
		addnav("Keypad Entry:");
		addnav("Fiamma","runmodule.php?module=signetd5&op=fiamma");
		addnav("Evad","runmodule.php?module=signetd5&op=evad");
		addnav("William","runmodule.php?module=signetd5&op=william");
		addnav("Nifle Scro","runmodule.php?module=signetd5&op=niflescro");
		addnav("Kilmor","runmodule.php?module=signetd5&op=kilmor");
		addnav("Niscosnat","runmodule.php?module=signetd5&op=niscosnat");
		addnav("Arandee","runmodule.php?module=signetd5&op=arandee");
		addnav("Wasser","runmodule.php?module=signetd5&op=wasser");
	}
	if ($op=="872"){
		output("You approach the door and notice a key pad of numbers and letters.");
		output("It seems like you'll have to enter a code to get in.");
		output("`n`nAfter looking around, you see some words scribbled below the keypad. Upon examining it more closely, you can read the following:");
		output("`n`n`c`\$Password: Order of the Signet Elementals`c");
		output("`n`0What do you decide to enter on the keypad?");
		addnav("Leave","runmodule.php?module=signetd5&loc=".($temp+34));
		addnav("Keypad Entry:");
		addnav("1. `!Water`0, `\$Fire`0, `3Air`0, `QEarth","runmodule.php?module=signetd5&op=sig1");
		addnav("2. `QEarth`0, `\$Fire`0, `!Water`0, `3Air","runmodule.php?module=signetd5&op=sig2");
		addnav("3. `\$Fire`0, `3Air`0, `QEarth`0, `!Water","runmodule.php?module=signetd5&op=sig3");
		addnav("4. `3Air`0, `QEarth`0, `!Water`0, `\$Fire","runmodule.php?module=signetd5&op=sig4");
		addnav("5. `!Water`0, `3Air`0, `\$Fire`0, `QEarth","runmodule.php?module=signetd5&op=sig5");
		addnav("6. `\$Fire`0, `QEarth`0, `3Air`0, `!Water","runmodule.php?module=signetd5&op=sig6");
		addnav("7. `3Air`0, `!Water`0, `QEarth`0, `\$Fire","runmodule.php?module=signetd5&op=sig7");
		addnav("8. `QEarth`0,`!Water`0, `\$Fire`0, `3Air","runmodule.php?module=signetd5&op=sig8");
	}
	if ($op=="kilmor"){
		output("You enter Kilmor's name and the door opens.");
		$allprefs['loc860']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="sig4"){
		output("You enter the correct order of the signets and the door opens.");
		$allprefs['loc872']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="fiamma" || $op=="evad" || $op=="william" || $op=="niflescro" || $op=="niscosnat" || $op=="arandee" || $op=="wasser" ||$op=="sig1" || $op=="sig2" || $op=="sig3" || $op=="sig5" || $op=="sig6" || $op=="sig7" || $op=="sig8"){
		output("As soon as you key in your answer you realize that that wasn't the right one.`n`n");
		if ($allprefs['bankloss']==0){
			$decrease=get_module_setting("number");
			if (get_module_setting("losegold")==0) $decrease=round($session['user']['goldinbank']*get_module_setting("percentage")/100);
			else{
				$decrease=e_rand(get_module_setting("numberlow"),get_module_setting("numberhigh"));
				if ($decrease > $session['user']['goldinbank']) $decrease=$session['user']['goldinbank'];
				debuglog("lost $decrease gold from their bank in a trap in the 5th Signet Dungeon");
			}			
			output("You suddenly realize that you just keyed in your account number for the bank!");
			output("`n`nYou relax... there's no way anyone could access your account, could they?");
			$id = $session['user']['acctid'];
			$name = $session['user']['name'];	
			$subj = sprintf("`^Bank Notice");
			if ($session['user']['goldinbank']==0) $body = sprintf("`^Dear %s`^,`n`nThis letter is to inform you that someone appeared at the bank trying to withdraw funds using your bank account number.  We stopped them from accessing your account and your funds are safe.  We apologize for any incovenience. You will not need to take any further action.`n`nSincerely,`n`nBank Management",$name);
			elseif ($decrease==$session['user']['goldinbank']) $body = sprintf("`^Dear %s`^,`n`nThis is a receipt for your recent withdrawal from the bank.  One of your servants came to us with your account number and withdrew all of your gold.  We enjoy your business and hope to see you soon. `n`nSincerely,`n`nBank Management",$name);
			elseif($decrease<$session['user']['goldinbank']) $body = sprintf("`^Dear %s`^,`n`nThis is a receipt for your recent withdrawal from the bank.  One of your servants came to us with your account number and withdrew `b%s gold`b.  We enjoy your business and hope to see you soon.`n`nSincerely,`n`nBank Management",$name,$decrease);
			require_once("lib/systemmail.php");
			systemmail($id,$subj,$body);
			$allprefs['bankloss']=1;
			set_module_pref('allprefs',serialize($allprefs));
		}else{
			output("An electrical shock shoots out from the keypad!");
			$hitpoints=round($session['user']['hitpoints']*.3);
			$session['user']['hitpoints']-=$hitpoints;
			if ($session['user']['hitpoints']<=0) {
				$session['user']['hitpoints']=1;
				output("You `\$lose all your hitpoints except one`0.");
			}elseif ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
			else output("Luckily you aren't injured by the shock.");
		}
		addnav("Leave","runmodule.php?module=signetd5&loc=".($temp+34));
	}
	if ($op=="warlockguard"){
		output("You stumble into a `)Black Warlock`0!");
		output("`n`nThe darkness will be a slight disadvantage for you in the fight until your eyes can adjust.  Unfortunately, the `)Black Warlock`0 does not suffer from such a handicap.");
		apply_buff('kblind',array(
			"name"=>"`4Blindness",
			"rounds"=>3,
			"wearoff"=>"`@Your accuracy improves now that your eyes have adjusted to the darkness!",
			"atkmod"=>.96,
			"defmod"=>.96,
			"roundmsg"=>"`\$You stumble in the darkness.",
		));
		addnav("Fight the `)Black Warlock","runmodule.php?module=signetd5&op=blackwarlock");
	}
	if ($op=="mapfix"){
		output("It appears that the map is wrong.  There is no hallway to the %s.",translate_inline(($temp==423||$temp==427||$temp==351||$temp==365)?"east":"west"));
		output("You make a quick correction to the map.`n`n");
		output("You notice that the passageway continues to the south.");
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
		if ($temp==421 || $temp==423) $allprefs['loc421']=1;
		elseif ($temp==425 || $temp==427) $allprefs['loc425']=1;
		elseif ($temp==347 || $temp==351) $allprefs['loc347']=1;
		elseif ($temp==361 || $temp==365) $allprefs['loc361']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="traphall"){
		if ($temp==279) $allprefs['loc279']=1;
		elseif ($temp==283) $allprefs['loc283']=1;
		elseif ($temp==285) $allprefs['loc285']=1;
		elseif ($temp==287) $allprefs['loc287']=1;
		elseif ($temp==289) $allprefs['loc289']=1;
		elseif ($temp==291) $allprefs['loc291']=1;
		elseif ($temp==293) $allprefs['loc293']=1;
		elseif ($temp==297) $allprefs['loc297']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
		output("You walk down the darkened hall and hear the whisper of an arrow released from a trap.`n`n");
		//Higher level characters will be more successful
		if($session['user']['level']>12) $trap=4;
		elseif($session['user']['level']>8) $trap=6;
		elseif($session['user']['level']>4) $trap=8;
		else $trap=11;
		switch(e_rand(1,$trap)){
			case 1:
			case 2:
				output("You dodge the arrow before it can do any damage.`n`n");
				$chance = e_rand(1,3);
				//I wanted to reward people for coming here with at least one turn left
				if ($session['user']['turns']>0 && $chance==1){
					output("You get an adrenaline rush from dodging the arrow.");
					apply_buff('adrenaline',array(
						"name"=>"`^Adrenaline Rush",
						"rounds"=>5,
						"wearoff"=>"`@The adrenaline rush ends",
						"atkmod"=>1.3,
						"roundmsg"=>"`@You strike with great fury!",
					));
				}
			break;
			case 3:
			case 4:
			case 5:
				output("The arrow strikes you!`n`n`@");
				switch(e_rand(1,5)){
				case 1:
				case 2:
				case 3:
				case 4:
					$hitpoints=round($session['user']['hitpoints']*.07);
					output("`n`nThe arrow was poisoned!");
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					$session['user']['hitpoints']-=$hitpoints;
					apply_buff('poisonarrow',array(
						"name"=>"`^Poison",
						"rounds"=>5,
						"wearoff"=>"`!Your body clears the poison from your blood.",
						"atkmod"=>.94,
						"roundmsg"=>"`&The poison weakens you!",
					));
				break;
				case 5:
					output("The pain makes you shudder...`n`n");
					$hitpoints=round($session['user']['hitpoints']*.11);
					$session['user']['hitpoints']-=$hitpoints;
					if ($session['user']['turns']>0){
						$session['user']['turns']--;
						output("You lose one turn.");
					}
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				break;
				}
			break;
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
			case 11:
				output("The arrow grazes your skin and causes a flesh wound.`n`n`@");
				$charmhit=e_rand(1,2);
				if ($charmhit==1){
					output("It causes a nasty scar.  You `&Lose One Charm`0.");
					$session['user']['charm']--;
					debuglog("lost a charm point in a trap in the 5th Signet Dungeon");
				}else{
					output("It causes a cool scar.  You `&Gain One Charm`0.");
					$session['user']['charm']++;
					debuglog("gained a charm point in a trap in the 5th Signet Dungeon");
				}
			break;
		}
	}
	if ($op=="transporter"){
		output("You step into the teleporter and find yourself moving in a whirlwind...");
		if ($allprefs['usedtrans']==0){
			$allprefs['loc685']=1;
			$allprefs['loc694']=1;
			$allprefs['loc698']=1;
			$allprefs['loc707']=1;
			$allprefs['loc519']=1;
			$allprefs['loc524']=1;
			$allprefs['loc528']=1;
			$allprefs['loc533']=1;
			$allprefs['loc381']=1;
			$allprefs['loc385']=1;
			$allprefs['loc387']=1;
			$allprefs['loc389']=1;
			$allprefs['loc391']=1;
			$allprefs['loc393']=1;
			$allprefs['loc395']=1;
			$allprefs['loc399']=1;
			$allprefs['loc279']=1;
			$allprefs['loc283']=1;
			$allprefs['loc285']=1;
			$allprefs['loc287']=1;
			$allprefs['loc289']=1;
			$allprefs['loc291']=1;
			$allprefs['loc293']=1;
			$allprefs['loc297']=1;
			$allprefs['loc143']=1;
			$allprefs['loc147']=1;
			$allprefs['loc149']=1;
			$allprefs['loc151']=1;
			$allprefs['loc153']=1;
			$allprefs['loc155']=1;
			$allprefs['loc157']=1;
			$allprefs['loc161']=1;
			$allprefs['usedtrans']=1;
			set_module_pref('allprefs',serialize($allprefs));
			output("`n`nA deadly silence sweeps across the dungeon.  There is a palpable fear in the air.");
		}
		set_module_pref('pqtemp',63);
		addnav("Continue","runmodule.php?module=signetd5&loc=".get_module_pref("pqtemp"));
	}
	if ($op=="tohalls"){
		output("You step into the teleporter and find yourself moving in a whirlwind...");
		if ($allprefs['transport']==1) set_module_pref("pqtemp",41);
		elseif ($allprefs['transport']==2) set_module_pref("pqtemp",45);
		elseif ($allprefs['transport']==3) set_module_pref("pqtemp",47);
		elseif ($allprefs['transport']==4) set_module_pref("pqtemp",49);
		elseif ($allprefs['transport']==5) set_module_pref("pqtemp",51);
		elseif ($allprefs['transport']==6) set_module_pref("pqtemp",53);
		elseif ($allprefs['transport']==7) set_module_pref("pqtemp",55);
		elseif ($allprefs['transport']==8) set_module_pref("pqtemp",59);
		addnav("Continue","runmodule.php?module=signetd5&loc=".get_module_pref("pqtemp"));
	}
	if ($op=="335"){
		output("When you enter the room, you have a moment to survey your surroundings.");
		output("`n`nYou see a huge pile of `%gems`0 in the northwest corner and an equally impressive pile of `^gold`0 in the northeast corner.");
		output("`n`nA large figure sits in the back of the room on a gilded throne.  He see you, turns around, and escapes behind the throne through a  secret door.");
		output("`n`nSuddenly you are attacked by `)Two Black Warlocks`0.");
		addnav("Fight the `)Black Warlocks","runmodule.php?module=signetd5&op=blackwarlocks");
	}
	if ($op=="503"){
		output("You find the pile of `%gems`0 and try to do a quick inventory.");
		output("`n`nIt looks like there are at least 250!");
		output("`n`nWhat will you do?");
		addnav("Take the Gems","runmodule.php?module=signetd5&op=503b");
		addnav("Destroy the Gems","runmodule.php?module=signetd5&op=503c");
		addnav("Leave","runmodule.php?module=signetd5&loc=".($temp-34));
	}
	if ($op=="503b"){
		$allprefs['loc503']=1;
		$allprefs['loc503b']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("You grab the gems and add them to your pouch.");
		output("`n`nYou gain `%250 gems`0!");
		$session['user']['gems']+=250;
		addnav("Continue","runmodule.php?module=signetd5&op=503d");
	}
	if ($op=="503c"){
		output("You destroy all the `%gems`0 successfully.");
		$allprefs['loc503']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="503d"){
		$allprefs['loc503b']=0;
		set_module_pref('allprefs',serialize($allprefs));
		output("You hear a hissing sound from your gem pouch...");
		output("You look down to realize that the gems have melted and destroyed ALL of your gems!");
		$gems=$session['user']['gems'];
		$session['user']['gems']=0;
		debuglog("lost all $gems of their gems when they got greedy in the 5th Signet Dungeon");
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="507"){
		output("You find the pile of `^gold`0 and try to do a quick inventory.");
		output("`n`nIt looks like there's at least `^50,000 gold`0!");
		output("`n`nWhat will you do?");
		addnav("Take the Gold","runmodule.php?module=signetd5&op=507b");
		addnav("Destroy the Gold","runmodule.php?module=signetd5&op=507c");
		addnav("Leave","runmodule.php?module=signetd5&loc=".($temp-34));
	}
	if ($op=="507b"){
		$exploss = round($session['user']['experience']*.1);
		output("A terrible shock runs through your arm as soon as you touch the gold.");
		output("`n`nYou are killed by a `@poison`0 covering the gold. Mierscri takes all your `^gold`0 and adds it to the pile.");
		output("`n`n`b`\$You lose `#%s `\$experience points.",$exploss);
		$session['user']['experience']-=$exploss;
		$session['user']['hitpoints']=0;
		$session['user']['gold']=0;
		$session['user']['alive']=false;
		debuglog("died and lost all their gold when they got greedy in the 5th Signet Dungeon");
		addnav("Continue","shades.php");
	}
	if ($op=="507c"){
		output("You destroy all the `^gold`0 successfully.");
		$allprefs['loc507']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="505"){
		output("You approach the throne.  What will you do?");
		if ($allprefs['loc505b']==0) addnav("Sit on the Throne","runmodule.php?module=signetd5&op=505b");
		addnav("Destroy the Throne","runmodule.php?module=signetd5&op=505c");
		addnav("Leave","runmodule.php?module=signetd5&loc=".($temp-34));
	}
	if ($op=="505b"){
		if ($session['user']['turns']==0){
			$allprefs['loc505b']=1;
			set_module_pref('allprefs',serialize($allprefs));
			output("Yup, not much happening here. You're sitting on a throne.  Yup.");
		}else{
			switch(e_rand(1,16)){
				case 1:
					$gem=e_rand(1,6);
					output("You notice a gem encrusted in the arm of the throne.`n`n");
					if ($session['user']['gems']<10 || $gem==1){
						output("After `@spending a turn`0, you're able to dig it out!");
						output("`n`nYou `%gain one gem`0.");
						debuglog("found a gem sitting on a throne in the 5th Signet Dungeon");
						$session['user']['gems']++;
					}else output("You `@spend a turn`0 trying to dig out the `%gem`0 but fail.");
					$session['user']['turns']--;
				break;
				case 2: case 3: case 4: case 5:
					output("You notice some graffiti on the throne:`n`n`#");
					switch(e_rand(1,4)){
						case 1:
							output("`cThose dwarves smell like dirty socks`c");
						break;
						case 2:
							output("`cWhat do you call 100 dead elves? A good start!`c");
						break;
						case 3:
							output("`cMental Note: Cook Fiamma.  Add lots of spices.`c");
						break;
						case 4:
							output("Check list:`n");
							output("`\$`iDestroy dwarf home`i `4(Marked done)`n");
							output("`\$`iKill ruthlessly without mercy`i `4(Marked done)`n");
							output("`\$`iBuy mom flowers`i `4(Not marked done yet)`n");
							output("`\$`iDestroy flowers given to mom`i `4(Not marked done yet)`n");
						break;
					}
				break;
				 case 6: case 7: case 8: case 9:
					output("You sit and think for a while.");
				break;
				case 10: case 11: case 12:
					output("You have a moment of panic when you think you left the stove running in your home.");
					output("`n`nNo... you didn't. Don't worry.");
				break;
				case 13: case 14: 
					output("You spot a gold piece!  Lucky day, lucky day!");
					$session['user']['gold']++;
				break;
				case 15: case 16:
					output("You `@spend a turn`0 sitting on the throne thinking.");
					$session['user']['turns']--;
				break;
			}
			addnav("Sit on the Throne Some More","runmodule.php?module=signetd5&op=505b");
		}
		addnav("Destroy the Throne","runmodule.php?module=signetd5&op=505c");
		addnav("Leave","runmodule.php?module=signetd5&loc=".($temp-34));
	}
	if ($op=="505c"){
		output("You step back and bash the throne with your %s`0.",$session['user']['weapon']);
		output("`n`nAfter a while, you make short work of the throne.");
		if ($session['user']['turns']>0){
			$session['user']['turns']--;
			output("It only takes you one turn to destroy it.");
		}
		$allprefs['loc505']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("`n`nYou notice a secret door behind the throne.");
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="777"){
		output("The `)Dark Lord`0 casts a spell of `!L`&ightning `!B`&olt`0 at you...`n`n");
		$allprefs['loc777']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if ($allprefs['loc503']==1){
			output("but his spell fails.");
			output("`n`nIt seems like he had been drawing his power from the pile of `%gems`0 that you destroyed.");	
		}else{
			switch(e_rand(1,10)){
				case 1: case 2: case 3: case 4:
					$hitpoints=round($session['user']['hitpoints']*.02);
				break;
				case 5: case 6: case 7:
					$hitpoints=round($session['user']['hitpoints']*.04);
				break;
				case 8: case 9:
					$hitpoints=round($session['user']['hitpoints']*.07);
				break;
				case 10:
					$hitpoints=round($session['user']['hitpoints']*.10);
				break;
			}
			if ($hitpoints>0) output("and the `!l`&ightning`0 tears through your body. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
			else output("but you dodge the attack.");
			$session['user']['hitpoints']-=$hitpoints;
		}
		output("`n`n`@You pursue him farther down the hallway to the west.");
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="774"){
		output("The `)Dark Lord`0 casts a `\$F`Qire `\$B`Qall`0 at you...`n`n");
		$allprefs['loc774']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if ($allprefs['loc507']==1){
			output("but his spell fails.");
			output("`n`nIt seems like he had been drawing his power from the pile of `^gold`0 that you destroyed.");	
		}else{
			switch(e_rand(1,10)){
				case 1: case 2: case 3: case 4:
					$hitpoints=round($session['user']['hitpoints']*.03);
				break;
				case 5: case 6: case 7:
					$hitpoints=round($session['user']['hitpoints']*.05);
				break;
				case 8: case 9:
					$hitpoints=round($session['user']['hitpoints']*.08);
				break;
				case 10:
					$hitpoints=round($session['user']['hitpoints']*.11);
				break;
			}
			if ($hitpoints>0) output("and the `\$f`Qire`0 tears through your body. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
			else output("but you dodge the attack.");
			$session['user']['hitpoints']-=$hitpoints;
		}
		output("`n`n`@You pursue him farther down the hallway to the north.");
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="1012"){
		output("The `)Dark Lord`0 casts `!M`4agic `!M`4issle`0 at you...`n`n");
		$allprefs['loc1012']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if ($allprefs['loc507']==1 && $allprefs['loc503']==1){
			output("but his spell fails.");
			output("`n`nYou're becoming optimistic... having destroyed both his `%gems`0 and his `^gold`0, this will probably be an easy battle.");	
		}else{
			switch(e_rand(1,10)){
				case 1: case 2: case 3: case 4:
					$hitpoints=round($session['user']['hitpoints']*.03);
				break;
				case 5: case 6: case 7:
					$hitpoints=round($session['user']['hitpoints']*.06);
				break;
				case 8: case 9:
					$hitpoints=round($session['user']['hitpoints']*.08);
				break;
				case 10:
					$hitpoints=round($session['user']['hitpoints']*.12);
				break;
			}
			if ($hitpoints>0) output("and the `!m`4issle`0 tears through your body. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
			else output("but you dodge the attack.");
			$session['user']['hitpoints']-=$hitpoints;
		}
		output("`n`n`@You pursue him farther down the hallway to the east.");
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="1016"){
		if ($allprefs['loc1016']==0){
			output("The `)`bBlack Lord`b`0 is standing here waiting for your approach.");
			output("`n`nBefore you can attack, he waves his wand and casts a spell.  The earth trembles and splits into a north-south fissure.  He tosses his wand to the north and runs south.");
			output("`n`nThe Lair continues to tremble, and soon enough the way west is blocked.");
			$allprefs['loc1016']=1;
			set_module_pref('allprefs',serialize($allprefs));
			addnav("Go North to Retrieve the Wand","runmodule.php?module=signetd5&loc=".($temp+34));
		}else{
			output("You come back to the hallway.  The way to the west is still blocked.");
			addnav("Go North back to the Wand","runmodule.php?module=signetd5&loc=".($temp+34));			
		}
		output("`n`nWhat will you do?");
		addnav("Go South after Mierscri","runmodule.php?module=signetd5&op=1016b");	
	}
	if ($op=="1016b"){
		addnav("Go North to Retrieve the Wand","runmodule.php?module=signetd5&loc=".($temp+34));
		addnav("Try to go South Again","runmodule.php?module=signetd5&op=1016b");
		if ($allprefs['loc1016b']==0) output("You try to go south and find a rock has fallen and bars your way.");
		elseif ($allprefs['loc1016b']==1) output("No seriously, the way south is blocked. If you keep trying to go south you're going to get hurt.");
		elseif ($allprefs['loc1016b']==2) {
			output("Listen, the way south is blocked.  Now a rock is going to fall on you.`n`n");
			$session['user']['hitpoints']--;
			if ($session['user']['hitpoints']==0){
				output("You die from the rock hitting you.  All your gold is buried with you");
				$exploss = round($session['user']['experience']*.1);
				output("`n`n`b`\$You lose `#%s `\$experience points.",$exploss);
				$session['user']['experience']-=$exploss;
				$session['user']['gold']=0;
				$session['user']['alive']=false;
				debuglog("died and lost $exploss experience and all their gold trying to go south in the 5th Signet Dungeon");
				addnav("Continue","shades.php");
				blocknav("runmodule.php?module=signetd5&op=1016b");
				blocknav("runmodule.php?module=signetd5&loc=".($temp+34));
			}else{
				output("You `\$lose one hitpoint`0.  You better be careful.");
			}
		}elseif ($allprefs['loc1016b']==3){
			output("Since you're persisting on going south, another rock falls and hits you.`n`n");
			$session['user']['hitpoints']*=.5;
			if ($session['user']['hitpoints']==0){
				output("You die from the rock hitting you.  All your gold is buried with you");
				$exploss = round($session['user']['experience']*.1);
				output("`n`n`b`\$You lose `#%s `\$experience points.",$exploss);
				$session['user']['experience']-=$exploss;
				$session['user']['gold']=0;
				$session['user']['alive']=false;
				addnav("Continue","shades.php");
				debuglog("died and lost $exploss experience and all their gold trying to go south in the 5th Signet Dungeon");
				blocknav("runmodule.php?module=signetd5&op=1016b");
				blocknav("runmodule.php?module=signetd5&loc=".($temp+34));
			}else{
				output("You `\$lose half your hitpoints`0. `n`nJust so you know, if you try to go south again, you'll probably die.");
			}
		}elseif ($allprefs['loc1016b']==4){
			output("A large rock falls on you.`n`n");
			output("You die from the rock hitting you.  `n`nAll your `^gold`0 is buried with you");
			$exploss = round($session['user']['experience']*.1);
			output("`n`n`b`\$You lose `#%s `\$experience points.",$exploss);
			$session['user']['experience']-=$exploss;
			$session['user']['gold']=0;
			$session['user']['alive']=false;
			debuglog("died and lost $exploss experience and all their gold trying to go south in the 5th Signet Dungeon");
			addnav("Continue","shades.php");
			blocknav("runmodule.php?module=signetd5&op=1016b");
			blocknav("runmodule.php?module=signetd5&loc=".($temp+34));
		}elseif ($allprefs['loc1016b']>=5){
			output("A huge boulder has fallen and blocks the way to the south.");
			blocknav("runmodule.php?module=signetd5&op=1016b");
		}
		$allprefs['loc1016b']=$allprefs['loc1016b']+1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="1220"){
		output("You approach to find the `)W`\$and`) o`\$f `)M`\$ierscri`0.");
		addnav("Use the `)W`\$and","runmodule.php?module=signetd5&op=1220b");
		addnav("Destroy the `)W`\$and","runmodule.php?module=signetd5&op=1220c");
	}
	if ($op=="1220b"){
		output("You examine the wand carefully, feeling the power surging from it.");
		output("`n`nCarefully, you wave it in a large circle that encompasses your body.");
		addnav("Continue","runmodule.php?module=signetd5&op=deathbywand");
	}
	if ($op=="1220c"){
		output("Forgetting that you need the power of `#Four Grand Mages`0 to intentionally destroy the wand, you try to break it over your knee.");
		output("`n`nThe power surge knocks you back against the wall.");
		if ($session['user']['hitpoints']>1) output("`n`nYou `\$lose all your hitpoints except one`0.");
		$session['user']['hitpoints']=1;
		debuglog("lost all their hitpoints except one trying to destroy a wand in the 5th Signet Dungeon");
		addnav("Use the `)W`\$and","runmodule.php?module=signetd5&op=1220b");
		addnav("Try to  Destroy the `)W`\$and `0Again","runmodule.php?module=signetd5&op=1220d");
	}
	if ($op=="1220d"){
		output("Seriously, you forgot about the `#'Needing Four Grand Mages to Intentionally Destroy the Wand'`0 thing.");
		output("`n`nThis time, you die from the power surge.`n`nYou `^lose all your gold`0.");
		$exploss = round($session['user']['experience']*.1);
		output("`n`n`b`\$You lose `#%s `\$experience points.",$exploss);
		$session['user']['experience']-=$exploss;
		$session['user']['gold']=0;
		$session['user']['alive']=false;
		debuglog("died and lost $exploss experience and all their gold trying to break a wand in the 5th Signet Dungeon");
		addnav("Continue","shades.php");		
	}
	if ($op=="deathbywand"){
		set_module_pref('pqtemp',796);
		$allprefs['startloc']=796;
		set_module_pref('allprefs',serialize($allprefs));
		output("You feel as if your insides are being torn apart.  When the nausea ends, you look around to find that you've been transported to a different part of the dungeon.");
		output("`n`nYou notice that the wand has accidentally been destroyed by resonance waves from your teleportation spell.");
		addnav("Continue","runmodule.php?module=signetd5&loc=".get_module_pref("pqtemp"));
	}
	if ($op=="834"){
		$allprefs['loc834']=1;
		set_module_pref('allprefs',serialize($allprefs));
		$gemwand=get_module_setting("gemwand");
		$goldwand=get_module_setting("goldwand");
		if ($gemwand<1 && $goldwand<1) output("You find a pile of useless debri.");
		else{
			if ($gemwand>0) {
				output("You find `%%s %s`0",$gemwand,translate_inline($gemwand>1?"gems":"gem"));
				$session['user']['gems']+=$gemwand;
				debuglog("gained $gemwand gems in the 5th Signet Dungeon");
				if ($goldwand>0) output("and");
			}
			if ($goldwand>0){
				if ($gemwand==0) output("You find");
				output("`^%s gold`0",$goldwand);
				$session['user']['gold']+=$goldwand;
				debuglog("gained $goldwand gold in the 5th Signet Dungeon");
			}
			output("in a pile of debri in the corner.");
		}
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="766"){
		output("You see the figure of an old woman in the corner.  As you approach her, she rises slowly.");
		output("`n`nYou study her intently but before you have a chance to react, she lunges at you and she is holding your wrist in her hand.");
		output("`n`n`Q'Excellent. I see you bear the marks of the `3F`Qo`!u`\$r `^Signets`Q.  Finally, I believe there may be a chance to defeat Mierscri.'");
		output("`#`n`n'But who are you?'");
		output("`n`n`Q'My name is Tellusa. I am the Earth Mage; last of the great mages that created the `^Signets`Q.  Mierscri's power is not great enough to destroy me, but I am no longer powerful enough to defeat him.'");
		output("`n`n'I will be able to send you to confront Mierscri through this transportation vortex.  Also, I will grant you a blessing that will augment your powers.  It will be my last spell, for I will pass from this world after I cast it.'");
		output("`n`n'If you fail to defeat him, the next time you confront him will be without my help.'");
		addnav("Continue","runmodule.php?module=signetd5&op=766c");
		$allprefs['loc766']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="766b"){
		if ($allprefs['loc1257']==0){
			output("`Q'Welcome back. Are you ready to confront Mierscri?'");
		}else{
			output("`QTellusa`0 is no longer here, but the transporter to confront Mierscri is still active.");
		}
		addnav("Go to Fight Mierscri","runmodule.php?module=signetd5&op=948");
		villagenav();
	}
	if ($op=="766c"){
		output("`Q'I have created an exit vortex that will allow you to leave this dungeon and return to me when you are ready to confront Mierscri.");
		output("Please don't take too long though.  Each day you wait will make my blessing slightly weaker.'");
		output("`n`n'Are you ready to confront Mierscri?'");
		addnav("Go to Fight Mierscri","runmodule.php?module=signetd5&op=948");
		villagenav();
	}
	if ($op=="948"){
		if ($allprefs['loc1257']==0){
			apply_buff('tellbless',array(
				"name"=>"`QTellusa's Blessing",
				"rounds"=>6,
				"wearoff"=>"`QTellusa's protection fades",
				"atkmod"=>1.35,
				"defmod"=>1.35,
			));
		}
		redirect("runmodule.php?module=signetd5&loc=".($temp+182));
	}
	if ($op=="849"){
		output("As soon as you turn the corner the hallway illuminates with an eerie glow.  You see Mierscri standing at the end of the hallway waiting for you.");
		$allprefs['loc849']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="1257"){
		$allprefs['loc1257']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("You approach Mierscri.  You suddenly appreciate that this will be your greatest fight ever.");
		output("`n`nYou look over the Dark Lord.  He holds a `)black sword`0 that absorbs the light from the hall.");
		output("`n`nYou feel your courage weaken until you glance at the `^Signets`0.  Suddenly, you feel their power course through your body.  You are ready for this battle.");
		output("`n`nThe Dark Lord Mierscri looks you over and laughs.`n`n`b`)'Do you really think you have a chance at defeating me? No, you will not be able to.  I will relish your defeat.'");
		output("`n`n'Your death comes soon.'`b");
		addnav("Fight Mierscri","runmodule.php?module=signetd5&op=mierscri");
	}
	if ($op=="endgame"){
		$allprefsd4=unserialize(get_module_pref('allprefs','signetd4'));
		$allprefsd3=unserialize(get_module_pref('allprefs','signetd3'));
		$allprefsd2=unserialize(get_module_pref('allprefs','signetd2'));
		$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
		$allprefss=unserialize(get_module_pref('allprefs','signetsale'));
		$allprefss['completednum']=$allprefss['completednum']+1;
		$allprefss['dksince']="";
		$allprefss['scroll1']=0;
		$allprefs['complete']=1;
		$allprefsd1['complete']=1;
		$allprefsd2['complete']=1;
		$allprefsd3['complete']=1;
		$allprefsd4['complete']=1;
		$allprefss['scroll1']=0;
		$allprefsd1['scroll2']=0;
		$allprefsd1['scroll3']=0;
		$allprefsd2['scroll4']=0;
		$allprefsd3['scroll5']=0;
		$allprefsd4['scroll6']=0;
		$allprefsd4['scroll7']=0;
		$allprefs['powersignet']=1;
		set_module_pref("hoftemp",5200+$allprefss['completednum'],"signetsale");
		set_module_pref('allprefs',serialize($allprefs));
		set_module_pref('allprefs',serialize($allprefsd4),'signetd4');
		set_module_pref('allprefs',serialize($allprefsd3),'signetd3');
		set_module_pref('allprefs',serialize($allprefsd2),'signetd2');
		set_module_pref('allprefs',serialize($allprefsd1),'signetd1');
		set_module_pref('allprefs',serialize($allprefss),'signetsale');
		if (get_module_setting("frnewday")==1){
			$sql = "update ".db_prefix("module_userprefs")." set value=1 where value<>1 and setting='announce' and modulename='signetd5'";
			db_query($sql);
		}
		output("You stare down at the defeated Dark Lord, hesitant to give him a chance.");
		output("`n`nHe looks up at you and laughs. `b`)'I propose that you die!'`b");
		output("`n`n`0He makes a feeble last attempt to kill you, but you are prepared for these tricks.");
		output("`n`n`&`bYou strike the final blow and Mierscri falls under your %s`&! You have defeated the Dark Lord!`b",$session['user']['weapon']);
		addnav("Continue","runmodule.php?module=signetd5&op=eg1b");
	}
	if ($op=="eg1b"){
		$allprefsd4=unserialize(get_module_pref('allprefs','signetd4'));
		$allprefsd3=unserialize(get_module_pref('allprefs','signetd3'));
		$allprefsd2=unserialize(get_module_pref('allprefs','signetd2'));
		$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
		$allprefs=unserialize(get_module_pref('allprefs'));
		if ($allprefsd1['reset']==0)	redirect("runmodule.php?module=signetd2&op=reset");
		if ($allprefsd2['reset']==0)	redirect("runmodule.php?module=signetd3&op=reset");
		if ($allprefsd3['reset']==0)	redirect("runmodule.php?module=signetd4&op=reset");
		if ($allprefsd4['reset']==0)	redirect("runmodule.php?module=signetd5&op=reset");
		clear_module_pref("maze");
		clear_module_pref("pqtemp");
		$allprefs['mazeturn']="";
		$allprefs['header']="";
		$allprefs['randomp']="";
		$allprefs['transport']="";
		$allprefs['usedtrans']="";
		$allprefs['bankloss']="";
		$allprefs['loc109']="";
		$allprefs['loc113']="";
		$allprefs['loc115']="";
		$allprefs['loc117']="";
		$allprefs['loc119']="";
		$allprefs['loc121']="";
		$allprefs['loc123']="";
		$allprefs['loc127']="";
		$allprefs['loc143']="";
		$allprefs['loc147']="";
		$allprefs['loc149']="";
		$allprefs['loc151']="";
		$allprefs['loc153']="";
		$allprefs['loc155']="";
		$allprefs['loc157']="";
		$allprefs['loc161']="";
		$allprefs['loc279']="";
		$allprefs['loc283']="";
		$allprefs['loc285']="";
		$allprefs['loc287']="";
		$allprefs['loc289']="";
		$allprefs['loc291']="";
		$allprefs['loc293']="";
		$allprefs['loc297']="";
		$allprefs['loc335']="";
		$allprefs['loc347']="";
		$allprefs['loc361']="";
		$allprefs['loc381']="";
		$allprefs['loc385']="";
		$allprefs['loc387']="";
		$allprefs['loc389']="";
		$allprefs['loc391']="";
		$allprefs['loc393']="";
		$allprefs['loc395']="";
		$allprefs['loc399']="";
		$allprefs['loc421']="";
		$allprefs['loc425']="";
		$allprefs['loc503']="";
		$allprefs['loc503b']="";
		$allprefs['loc505']="";
		$allprefs['loc505b']="";
		$allprefs['loc507']="";
		$allprefs['loc519']="";
		$allprefs['loc524']="";
		$allprefs['loc528']="";
		$allprefs['loc533']="";
		$allprefs['loc685']="";
		$allprefs['loc687']="";
		$allprefs['loc691']="";
		$allprefs['loc694']="";
		$allprefs['loc698']="";
		$allprefs['loc701']="";
		$allprefs['loc705']="";
		$allprefs['loc707']="";
		$allprefs['loc724']="";
		$allprefs['loc736']="";
		$allprefs['loc766']="";
		$allprefs['loc774']="";
		$allprefs['loc777']="";
		$allprefs['loc792']="";
		$allprefs['loc804']="";
		$allprefs['loc834']="";
		$allprefs['loc849']="";
		$allprefs['loc860']="";
		$allprefs['loc872']="";
		$allprefs['loc895']="";
		$allprefs['loc898']="";
		$allprefs['loc902']="";
		$allprefs['loc905']="";
		$allprefs['loc1002']="";
		$allprefs['loc1012']="";
		$allprefs['loc1016']="";
		$allprefs['loc1016b']="";
		$allprefs['loc1104']="";
		$allprefs['loc1104b']="";
		$allprefs['loc1138']="";
		$allprefs['loc1172']="";
		$allprefs['loc1257']="";
		$allprefs['startloc']=1274;
		$allprefsd1['startloc']=1279;
		$allprefsd3['startloc']=1275;
		$allprefsd4['startloc']=1279;
		set_module_pref('allprefs',serialize($allprefs));
		set_module_pref('allprefs',serialize($allprefsd4),'signetd4');
		set_module_pref('allprefs',serialize($allprefsd3),'signetd3');
		set_module_pref('allprefs',serialize($allprefsd1),'signetd1');
		redirect("runmodule.php?module=signetd5&op=eg1");
	}
	if ($op=="eg1"){
		$expmultiply = get_module_setting("frexplvl");
		$expbonus=$session['user']['dragonkills']*get_module_setting("frexpdk");
		$expgain =round($session['user']['level']*$expmultiply+$expbonus);
		$session['user']['experience']+=$expgain;
		output("`@`bFor defeating the Evil Mierscri:`n`n");
		output("`c`#You receive `^%s`# experience!`b`c",$expgain);
		if ($session['user']['hitpoints']<$session['user']['maxhitpoints']){
			output("`n`n`c`2You find a healing potion that returns your hitpoints to full!`c");
			$session['user']['hitpoints']=$session['user']['maxhitpoints'];
		}
		debuglog("gained $expgain experience and was healed to full by killing Mierscri in the 5th Signet Dungeon");
		output("`n`n`@You escape the dungeon through an exit to the north, relishing the fresh air.  As you take a deep breath, you hear the collapse of Mierscri's Lair.");
		output("`n`n(Isn't it odd that the dungeons always collapse when the bad guy is killed?)");
		output("`n`n`b`c`&Congratulations!!!`c`b");
		output("`n`@You venture to the Kingdom's Castle to report your success.`n`nA quick glance at the signets reveals a new `^Signet`@!");
		output("`n`nYou have gained the `b`%Power Signet`b`@!");
		addnews("`%%s`5 has slain the `)Dark Lord Mierscri`5 and completed the `^Signet Series Dungeons`5!",$session['user']['name']);	
		addnav("Continue","runmodule.php?module=signetd5&op=eg2");
		if (get_module_setting("frsend")==1){
			$staff = get_module_setting("frwhosend");
			$id = $session['user']['acctid'];
			$name = $session['user']['name'];
			$subj = sprintf("`^Official Letter of `#Appreciation");
			$body = sprintf("`&Dear %s`&,`n`nIt is with great pride that I write this letter to you.  Your success against Mierscri has made our land safer and more peaceful.  In celebration, we are holding a ceremony of gratitude in your honor at the castle.  We look forward to seeing you.`n`nMost Gratefully Yours,`n`n%s",$name,$staff);
			require_once("lib/systemmail.php");
			systemmail($id,$subj,$body);
			output("`n`nOn your journey, you notice you have received a letter!");
			debuglog("received a letter from $staff for killing Mierscri in the 5th Signet Dungeon");
		}
	}
	if ($op=="eg2"){
		$frgold=get_module_setting("frgold");
		$frgems=get_module_setting("frgems");
		output("`@When you arrive at the castle, a huge celebration is happening! Word of your success has already spread throughout the kingdom.");
		output("`n`nA band of minstrels sings your praises!");
		output("`n`n`c`#Bravest of the brave! Our kingdom has been saved!`n");
		output("What would we do`n");
		output("Without you?`n");
		output("We thank you with our praise!`c");
		output("`n`@(Well, they aren't the best minstrels in the kingdom, but you feel quite honored nonetheless.)`n`n");
		if ($frgold>0 || $frgems>0){
			output("The royal coffers are brought before you and you are presented with:`n`n");
			if ($frgold>0) output("`c`^%s gold`c",$frgold);
			if ($frgems>0) output("`c`%%s %s`c",$frgems,translate_inline($frgems>1?"gems":"gem"));
			$session['user']['gold']+=$frgold;
			$session['user']['gems']+=$frgems;
			output("`n`@You happily collect the reward and enjoy the festivities.");
			debuglog("gained $frgold gold and $frgems gems for killing Mierscri in the 5th Signet Dungeon");
		}
		addnav("Continue","runmodule.php?module=signetd5&op=eg3");
	}
	if ($op=="eg3"){
		$frcharm=get_module_setting("frcharm");
		$fralign=get_module_setting("fralign");
		output("`@You glow and bask in the adoration of the people of the kingdom.`n");
		if ($frcharm>0 || $fralign>0){
			if($frcharm>0){
				output("`n`c`&Your charm increases by %s %s`c`n",$frcharm,translate_inline($frcharm>1?"points":"point"));
				$session['user']['charm']+=$frcharm;
				debuglog("gained $frcharm charm for killing Mierscri in the 5th Signet Dungeon");
			}
			if ($fralign>0 && is_module_active("alignment")){
				output("`@Your good deed is recorded in the history books.`n");
				output("`n`c`&Your alignment improves!`c");
				increment_module_pref("alignment",$fralign,"alignment");
				debuglog("improved alignment by $fralign for killing Mierscri in the 5th Signet Dungeon");
			}
		}
		addnav("Continue","runmodule.php?module=signetd5&op=eg4");
	}
	if ($op=="eg4"){
		output("`@You spend the night celebrating and dancing during the festivities.");
		$frdefense=get_module_setting("frdefense");
		$frattack=get_module_setting("frattack");
		if ($frattack>0 || $frdefense>0){
			if($frattack>0){
				output("`n`n`c`@You feel stronger!`c");
				output("`n`c`&Your attack improves by %s`c",$frattack);
				$session['user']['attack']+=$frattack;
				debuglog("gained $frattack attack for killing Mierscri in the 5th Signet Dungeon");
			}
			if($frdefense>0){
				output("`n`c`@You feel faster!`c");
				output("`n`c`&Your defense improves by %s`c",$frdefense);
				$session['user']['defense']+=$frdefense;
				debuglog("gained $frdefense defense for killing Mierscri in the 5th Signet Dungeon");
			}	
		}
		addnav("Continue","runmodule.php?module=signetd5&op=eg5");
	}
	if ($op=="eg5"){
			output("`@The Ruler of the Land comes before you, sword drawn...");
			output("`n`n`^'For service to the land in ridding our kingdom of `\$Evil`^,");
		if (get_module_setting("frtitleoff")>0){
			$allprefss=unserialize(get_module_pref('allprefs','signetsale'));
			if ($allprefss['completednum']==1) $miertitle=get_module_setting("frtitle");
			elseif ($allprefss['completednum']==2) $miertitle=get_module_setting("frtitle2");
			elseif ($allprefss['completednum']==3) $miertitle=get_module_setting("frtitle3");
			elseif ($allprefss['completednum']==4) $miertitle=get_module_setting("frtitle4");
			elseif ($allprefss['completednum']==5) $miertitle=get_module_setting("frtitle5");
			elseif ($allprefss['completednum']==6) $miertitle=get_module_setting("frtitle6");
			elseif ($allprefss['completednum']==7) $miertitle=get_module_setting("frtitle7");
			elseif ($allprefss['completednum']==8) $miertitle=get_module_setting("frtitle8");
			elseif ($allprefss['completednum']==9) $miertitle=get_module_setting("frtitle9");
			elseif ($allprefss['completednum']==10) $miertitle=get_module_setting("frtitle10");
			elseif ($allprefss['completednum']>10) $miertitle=get_module_setting("frtitle11");
			output("I offer you the title of %s`^.'",$miertitle);
			output("`n`n`@Will you accept the new title?");
			addnav("Accept with Honor","runmodule.php?module=signetd5&op=eg6");
			addnav("Graciously Decline","runmodule.php?module=signetd5&op=eg7");
		}else{
			output("you have my gratitude.'");
			addnav("Continue","runmodule.php?module=signetd5&op=eg7");
		}
	}
	if ($op=="eg6"){
		$allprefss=unserialize(get_module_pref('allprefs','signetsale'));
		if ($allprefss['completednum']==1) $miertitle=get_module_setting("frtitle");
		elseif ($allprefss['completednum']==2) $miertitle=get_module_setting("frtitle2");
		elseif ($allprefss['completednum']==3) $miertitle=get_module_setting("frtitle3");
		elseif ($allprefss['completednum']==4) $miertitle=get_module_setting("frtitle4");
		elseif ($allprefss['completednum']==5) $miertitle=get_module_setting("frtitle5");
		elseif ($allprefss['completednum']==6) $miertitle=get_module_setting("frtitle6");
		elseif ($allprefss['completednum']==7) $miertitle=get_module_setting("frtitle7");
		elseif ($allprefss['completednum']==8) $miertitle=get_module_setting("frtitle8");
		elseif ($allprefss['completednum']==9) $miertitle=get_module_setting("frtitle9");
		elseif ($allprefss['completednum']==10) $miertitle=get_module_setting("frtitle10");
		elseif ($allprefss['completednum']>10) $miertitle=get_module_setting("frtitle11");
		$newtitle = $miertitle;
		require_once("lib/titles.php");
		require_once("lib/names.php");
		$newname = change_player_title($newtitle);
		$session['user']['title'] = $newtitle;
		$session['user']['name'] = $newname;
		output("`^'Henceforth, you will be known as %s`^. I thank you once again for your service to the kingdom.'",$session['user']['name']);
		output("`n`n`n`2(Note:  The new title may not be issued if you currently hold a customized title.)");
		addnav("Continue","runmodule.php?module=signetd5&op=eg7");
		debuglog("changed their title to $miertitle for killing Mierscri in the 5th Signet Dungeon");
	}
	if ($op=="eg7"){
		output("`c`@You have ended a plague of evil.`c");
		output("`n`n`cCongratulations on completing the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signets`@ Dungeons!`c");
		if (get_module_setting("frhof")==1){
			output("`n`n`cYou may see your accomplishment recorded at the Hall of Fame!`c");
		}
		increment_module_setting("frhofnumb",1);
		set_module_pref("frhofnum",get_module_setting("frhofnumb"));
		set_module_setting("frlastone",$session['user']['name']);
		set_module_setting("frlastscroll",1);
		villagenav();
		if (get_module_setting("frsystemmail")==1){
			$sql = "SELECT acctid FROM " . db_prefix("accounts");
			$result = db_query($sql);
			$name = $session['user']['name'];
			$staff= get_module_setting("frwhosend");
			for ($i=0;$i<db_num_rows($result);$i++){
				$row = db_fetch_assoc($result);
				$id = $row['acctid'];	
				$subj = sprintf("`^Official Letter of `#Recognition");
				$body = sprintf("`#It is with great pride that I send this letter to all citizens in the kingdom.`n`nToday, a great `\$Evil`# has been eradicated from our kingdom by `b`@%s`#`b by defeating `b`)Mierscri`b`# in the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`& `^Dungeons`#.  Please participate in celebrating this accomplishment by sending your highest regards and showing respect and reverence to `@`b%s`#`b. Our kingdom is a better place now.`n`n%s",$name,$name,$staff);
				require_once("lib/systemmail.php");
				systemmail($id,$subj,$body);
            }
		}
	}
	//the scrolls
	if ($op=="scroll1b"){
		output("`c`b`^The Aria Dungeon`0`c`b`n");
		output("The Aria Dungeon was once the center of a small community of dwarves.  About 15 years ago, a band of orcs invaded the community and defeated the dwarves.");
		output("`n`nIt is said that only the leader of the dwarves, Kilmor, and a few others escaped from the Orcs.");
		addnav("Return","runmodule.php?module=signetd5&loc=".$temp);
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
		addnav("Return","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="scroll3b"){
		output("`c`b`^Prison Notes by Kilmor`0`c`b`n");
		output("The Orcs are less than wonderful captors. I hope to be able to break free soon.");
		output("My first journey will be to the `^Temple of the Aarde Priests`0.  It is rumored that the high priest of the temple has the power of prophesy, but few have been allowed to see him.");
		addnav("Return","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="scroll4b"){
		output("`c`b`^Report from Evad the Sage`c`b");
		output("`n`0In `\$Fiamma's Fortress`0 there is a secret control room which can be accessed from one of two secret passages.");
		output("The first starts in `\$Fiamma's`0 room.  The other starts between the arena and the jail cell.");
		output("From this room various gates around the castle can be operated.");
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="scroll5b"){
		output("`c`b`^The Story of Fiamma Fortress`c`b");
		output("`n`0Many years ago when Mierscri was invading the land, the men and beasts put aside their differences and joined together to fight him.");
		output("`n`nThe lowly Fiamma, who was an officer in this combined army, told Mierscri of the plan; thus it failed.");
		output("`n`nAs a reward for this action, Fiamma was given a great fortress and given the Fire Signet to guard over.");
		addnav("Continue","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="scroll6b"){
		output("`c`b`^Fiamma's Fortress`0`c`b`n");
		output("`\$My Fortress is impenetrable.  I feel safe knowing that the control room can only be accessed from two places.");
		output("I carefully guard the first entrance in my bedroom so I have no concern of it being discovered.");
		output("However, even I fear mentioning the second location.  That secret will not be revealed on paper.");
		addnav("Return","runmodule.php?module=signetd5&loc=".$temp);
	}
	if ($op=="scroll7b"){
		output("`c`b`^Fiamma's Plans`0`c`b`n");
		output("`\$I cannot be a servant of the `bMaster Mierscri`b for all my life.  I have plans!");
		output("`n`nHis `)Dark Warlocks `\$concern me greatly but I think I have finally found a chance to defeat him.");
		output("I was able to place a deadly trap in his treasure trove of `^gold`\$ and coated his pile of `%gems`\$ with a poison.  The next time he plans to count his plunder he will find his life shortened!");
		output("`n`nIf that fails, I have a plan to steal his wand and use it against him.  He is vulnerable when his magic is turned against him.");
		output("`n`nMy reign of terror will begin soon after!");
		addnav("Return","runmodule.php?module=signetd5&loc=".$temp);
	}
	page_footer();
}
?>