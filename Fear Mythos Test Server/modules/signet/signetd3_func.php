<?php
function signetd3_fight($op) {
	global $session,$badguy;
	$temp=get_module_pref("pqtemp");
	page_header("Castle Fight");
	//for the doors
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($op=="door"){
		if ($temp==221 && $allprefs['loc221']==0) {
			$allprefs['loc221']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("`Qa Heavy Door`0"),
			"creaturelevel"=>6,
			"creatureweapon"=>translate_inline("sharp splinters"),
			"creatureattack"=>5,
			"creaturedefense"=>6,
			"creaturehealth"=>40,
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	//for the monsters
	if ($op=="oldman"){
		if ($temp==1097 && $allprefs['loc1097']==0){
			$allprefs['loc1097']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("An Old Man"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("his ugly stick"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.8,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.85,
			"creaturehealth"=>round($session['user']['hitpoints']*.92),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="soldiers"){
		if ($temp==619 && $allprefs['loc619']==0){
			$allprefs['loc619']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Rowdy Soldiers"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("short swords"),
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*.8,
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4))*.85,
			"creaturehealth"=>round($session['user']['hitpoints']*.93),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="mages"){
		if ($temp==483 && $allprefs['loc483']==0) {
			$allprefs['loc483']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Mages"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("magic missile spells"),
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*.9,
			"creaturedefense"=>($session['user']['attack']+e_rand(0,1))*.7,
			"creaturehealth"=>round($session['user']['hitpoints']*.7),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="hornet"){
		if ($temp==358 && $allprefs['loc358']==0) $allprefs['loc358']=2;
		elseif ($temp==499 && $allprefs['loc499']==0) $allprefs['loc499']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("A Giant Hornet"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("Venom-filled Stinger"),
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*.8,
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.8),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="guard"){
		if ($temp==148 && $allprefs['loc148']==0) $allprefs['loc148']=2;
		elseif ($temp==82 && $allprefs['loc82']==0) $allprefs['loc82']=2;
		elseif ($temp==87 && $allprefs['loc87']==0) $allprefs['loc87']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>"Prison Guard",
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>"very sharp keys",
			"creatureattack"=>($session['user']['attack']+e_rand(1,2))*.7,
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4))*.9,
			"creaturehealth"=>round($session['user']['hitpoints']*.92),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="asp"){
		if ($temp==401 && $allprefs['loc401']==0){
			$allprefs['loc401']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("A Dangerous Asp"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("fangs"),
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*1.1,
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4))*.6,
			"creaturehealth"=>round($session['user']['hitpoints']*.7),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="troll"){
		if ($temp==164 && $allprefs['loc164']==0){	
			$allprefs['loc164']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Troll"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("dirty claws"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2))*.8,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,2))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.8),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="bantir"){
		if ($temp==94 && $allprefs['loc94']==0){
			$allprefs['loc94']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("A Bantir"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("stringy appendages"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.76,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.7),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="wraith"){
		if ($temp==29 && $allprefs['loc29']==0){
			$allprefs['loc29']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$level=$session['user']['level']+1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Wratih"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("withered claws"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2)),
			"creaturedefense"=>($session['user']['attack']+e_rand(1,2))*.9,
			"creaturehealth"=>round($session['user']['hitpoints']*.95),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="skeleton"){
		if ($temp==65 && $allprefs['loc65']==0){
			$allprefs['loc65']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Skeleton"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("rusty sabre"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2))*1.5,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,2))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.6),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="spider"){
		if ($temp==66 && $allprefs['loc66']==0){
			$allprefs['loc66']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("A Giant Spider"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("Venom-filled Fangs"),
			"creatureattack"=>($session['user']['attack']+e_rand(2,4))*.86,
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.86),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="waterelemental"){
		if ($temp==745 && $allprefs['loc745']==0){
			$allprefs['loc745']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Guardian Water Elemental"),
			"creaturelevel"=>$session['user']['level']+1,
			"creatureweapon"=>translate_inline("powerful blasts of water"),
			"creatureattack"=>($session['user']['attack']+e_rand(2,4)),
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4)),
			"creaturehealth"=>round($session['user']['maxhitpoints']*.96),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="ranger"){
		if ($temp==591 && $allprefs['loc591']==0){
			$allprefs['loc591']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Ranger"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("morning star"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2))*.8,
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4))*.87,
			"creaturehealth"=>round($session['user']['hitpoints']*.92),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="ogres"){
		if ($temp==625 && $allprefs['loc625']==0){
			$allprefs['loc625']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Ogres"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("primitive maces"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,2))*.8,
			"creaturedefense"=>($session['user']['attack']+e_rand(2,4))*.87,
			"creaturehealth"=>round($session['user']['hitpoints']*.82),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	if ($op=="dripslime"){
		if ($temp==939 && $allprefs['loc939']==0){
			$allprefs['loc939']=2;
			set_module_pref('allprefs',serialize($allprefs));
		}
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("A Bantir"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("stringy appendages"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.76,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.7,
			"creaturehealth"=>round($session['user']['hitpoints']*.7),
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
    
	    if ($victory){
       			addnav("Continue","runmodule.php?module=signetd3&loc=".get_module_pref('pqtemp'));
       			$allprefs=unserialize(get_module_pref('allprefs'));
				//opening doors
				if ($temp==221 && $allprefs['loc221']==2) {
					output("`^`n`bYou have opened the door safely.`b`n");
					$experience=$session['user']['level']*e_rand(4,7);
					output("`#You receive `6%s `#experience.`n",$experience);
					$session['user']['experience']+=$experience;
					$allprefs['loc221']=1;
				//special monsters
				}elseif($temp==745 && $allprefs['loc745']==2){
					$allprefs['loc745']=1;
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$session['user']['gems']++;
					$expmultiply = e_rand(12,25);
					$expbonus=$session['user']['dragonkills']*3;
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@The Water Elemental dissipates.  In its place remains a solitary `%gem`@.");
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
				//monsters
				}elseif (($temp==1097 && $allprefs['loc1097']==2) || ($temp==619 && $allprefs['loc619']==2) || ($temp==483 && $allprefs['loc483']==2) || ($temp==358 && $allprefs['loc358']==2) || ($temp==148 && $allprefs['loc148']==2) || ($temp==82 && $allprefs['loc82']==2) || ($temp==87 && $allprefs['loc87']==2) || ($temp==499 && $allprefs['loc499']==2) || ($temp==401 && $allprefs['loc401']==2) || ($temp==164 && $allprefs['loc164']==2) || ($temp==94 && $allprefs['loc94']==2) || ($temp==29 && $allprefs['loc29']==2) || ($temp==65 && $allprefs['loc65']==2) || ($temp==66 && $allprefs['loc66']==2) || ($temp==939 && $allprefs['loc939']==2) || ($temp==625 && $allprefs['loc625']==2) || ($temp==591 && $allprefs['loc591']==2)){
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$gold=e_rand(80,200);
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(10,20);
					$expbonus=$session['user']['dragonkills']*1.5;
					$expgain =round($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You search through the smelly corpse and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					if ($allprefs['loc148']==2 || $allprefs['loc82']==2 || $allprefs['loc87']==2){
						output("`nYou search through the prison guard's keys and find one that unlocks the cell door.");
						$allprefs['prisonkey']=1;
					}
					if ($allprefs['loc1097']==2) $allprefs['loc1097']=1;
					elseif ($allprefs['loc619']==2) $allprefs['loc619']=1;
					elseif ($allprefs['loc483']==2) $allprefs['loc483']=1;
					elseif ($allprefs['loc358']==2) $allprefs['loc358']=1;
					elseif ($allprefs['loc148']==2) $allprefs['loc148']=1;
					elseif ($allprefs['loc82']==2) $allprefs['loc82']=1;
					elseif ($allprefs['loc87']==2) $allprefs['loc87']=1;
					elseif ($allprefs['loc499']==2) $allprefs['loc499']=1;
					elseif ($allprefs['loc401']==2) $allprefs['loc401']=1;
					elseif ($allprefs['loc164']==2) $allprefs['loc164']=1;
					elseif ($allprefs['loc94']==2) $allprefs['loc94']=1;
					elseif ($allprefs['loc29']==2) $allprefs['loc29']=1;
					elseif ($allprefs['loc65']==2) $allprefs['loc65']=1;
					elseif ($allprefs['loc66']==2) $allprefs['loc66']=1;
					elseif ($allprefs['loc625']==2) $allprefs['loc625']=1;
					elseif ($allprefs['loc591']==2) $allprefs['loc591']=1;
					elseif ($allprefs['loc939']==2) $allprefs['loc939']=1;
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
				if (get_module_setting("healing")==1 && ($session['user']['hitpoints']<$session['user']['maxhitpoints']*.6)){
					$hpdown=$session['user']['maxhitpoints']-$session['user']['hitpoints'];
					switch(e_rand(1,14)){
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
							elseif ($hpheal>120) $hpheal=120;
							output("`n`nYou gain `b%s hitpoints`b!",$hpheal);
							$session['user']['hitpoints']+=$hpheal;
						break;
						case 4:
						case 5:
						case 6:
							output("`n`@You notice the remnants of a small healing potion.  You have a chance to take a quick drink!");;
							$hpheal=round(e_rand($hpdown*.1,$hpdown*.4));
							if ($hpheal<=1) $hpheal=2;
							elseif ($hpheal>50) $hpheal=50;
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
						case 14:
						break;
					}
				}
				$badguy=array();
				$session['user']['badguy']="";
			}elseif ($defeat){
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($temp==1005 && $allprefs['loc221']==2){
					$allprefs['loc221']=0;
					output("A splinter severs a major blood vessel and you die.`n");
					addnews("`% %s`5 has been slain trying to break down a door.",$session['user']['name']);
				}
				elseif (($temp==1097 && $allprefs['loc1097']==2) || ($temp==619 && $allprefs['loc619']==2) || ($temp==483 && $allprefs['loc483']==2) || ($temp==358 && $allprefs['loc358']==2) || ($temp==148 && $allprefs['loc148']==2) || ($temp==82 && $allprefs['loc82']==2) || ($temp==87 && $allprefs['loc87']==2) || ($temp==499 && $allprefs['loc499']==2) || ($temp==401 && $allprefs['loc401']==2) || ($temp==164 && $allprefs['loc164']==2) || ($temp==94 && $allprefs['loc94']==2) || ($temp==29 && $allprefs['loc29']==2) || ($temp==65 && $allprefs['loc65']==2) || ($temp==66 && $allprefs['loc66']==2) || ($temp==745 && $allprefs['loc745']==2) || ($temp==939 && $allprefs['loc939']==2) || ($temp==625 && $allprefs['loc625']==2) || ($temp==591 && $allprefs['loc591']==2)){
					if ($allprefs['loc1097']==2) $allprefs['loc1097']=0;
					elseif ($allprefs['loc619']==2) $allprefs['loc619']=0;
					elseif ($allprefs['loc483']==2) $allprefs['loc483']=0;
					elseif ($allprefs['loc358']==2) $allprefs['loc358']=0;
					elseif ($allprefs['loc148']==2) $allprefs['loc148']=0;
					elseif ($allprefs['loc82']==2) $allprefs['loc82']=0;
					elseif ($allprefs['loc87']==2) $allprefs['loc87']=0;
					elseif ($allprefs['loc499']==2) $allprefs['loc499']=0;
					elseif ($allprefs['loc401']==2) $allprefs['loc401']-0;
					elseif ($allprefs['loc164']==2) $allprefs['loc164']=0;
					elseif ($allprefs['loc94']==2) $allprefs['loc94']=0;
					elseif ($allprefs['loc29']==2) $allprefs['loc29']=0;
					elseif ($allprefs['loc65']==2) $allprefs['loc65']=0;
					elseif ($allprefs['loc66']==2) $allprefs['loc66']=0;
					elseif ($allprefs['loc745']==2) $allprefs['loc745']=0;
					elseif ($allprefs['loc625']==2) $allprefs['loc625']=0;
					elseif ($allprefs['loc939']==2) $allprefs['loc939']=0;
					elseif ($allprefs['loc591']==2) $allprefs['loc591']=0;
					output("As you hit the ground the `^%s runs away.",$badguy['creaturename']);
					addnews("`%%s`5 has been slain by an evil %s in a dungeon.",$session['user']['name'],$badguy['creaturename']);
				//Next lines are for random encounters
				}else{
					output("As you hit the ground the `^%s`^ runs away.",$badguy['creaturename']);
					addnews("`%%s`5 has been slain by an evil %s`5 in a dungeon.",$session['user']['name'],$badguy['creaturename']);
				}
				set_module_pref('allprefs',serialize($allprefs));
		        $badguy=array();
		        $session['user']['badguy']="";  
		        $session['user']['hitpoints']=0;
		        $session['user']['alive']=false;
		        addnav("Continue","shades.php");
			}else{
				require_once("lib/fightnav.php");
				fightnav(true,false,"runmodule.php?module=signetd3");
			}
		}else{
			redirect("runmodule.php?module=signetd3&loc=".get_module_pref('pqtemp'));	
		}
	}
	page_footer();
}
function signetd3_misc($op) {
	$temp=get_module_pref("pqtemp");
	page_header("Wasser's Castle");
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
		$allprefse=unserialize(get_module_pref('allprefs',"signetd3",$id));
		$allprefse['randomp']= httppost('randomp');
		$allprefse['mazeturn']= httppost('mazeturn');
		$allprefse['startloc']= 1275;
		set_module_pref('allprefs',serialize($allprefse),'signetd3',$id);
		if ($subop!='edit'){
			$allprefse=unserialize(get_module_pref('allprefs',"signetd3",$id));
			$allprefse['complete']= httppost('complete');
			$allprefse['reset']= httppost('reset');
			$allprefse['randomp']= httppost('randomp');
			$allprefse['watersignet']= httppost('watersignet');
			$allprefse['scroll5']= httppost('scroll5');
			$allprefse['loc159']= httppost('loc159');
			$allprefse['prisonkey']= httppost('prisonkey');
			$allprefse['loc11']= httppost('loc11');
			$allprefse['loc11b']= httppost('loc11b');
			$allprefse['loc11c']= httppost('loc11c');
			$allprefse['loc29']= httppost('loc29');
			$allprefse['loc48']= httppost('loc48');
			$allprefse['loc53']= httppost('loc53');
			$allprefse['loc65']= httppost('loc65');
			$allprefse['loc66']= httppost('loc66');
			$allprefse['loc82']= httppost('loc82');
			$allprefse['loc87']= httppost('loc87');
			$allprefse['loc94']= httppost('loc94');
			$allprefse['loc148']= httppost('loc148');
			$allprefse['loc157']= httppost('loc157');
			$allprefse['loc164']= httppost('loc164');
			$allprefse['loc221']= httppost('loc221');
			$allprefse['loc331']= httppost('loc331');
			$allprefse['loc352']= httppost('loc352');
			$allprefse['loc354']= httppost('loc354');
			$allprefse['loc358']= httppost('loc358');
			$allprefse['loc401']= httppost('loc401');
			$allprefse['loc483']= httppost('loc483');
			$allprefse['loc499']= httppost('loc499');
			$allprefse['loc537']= httppost('loc537');
			$allprefse['loc561']= httppost('loc561');
			$allprefse['loc591']= httppost('loc591');
			$allprefse['loc619']= httppost('loc619');
			$allprefse['loc625']= httppost('loc625');
			$allprefse['loc635']= httppost('loc635');
			$allprefse['loc745']= httppost('loc745');
			$allprefse['loc746']= httppost('loc746');
			$allprefse['loc839']= httppost('loc839');
			$allprefse['loc931']= httppost('loc931');
			$allprefse['loc939']= httppost('loc939');
			$allprefse['loc1071']= httppost('loc1071');
			$allprefse['loc1079']= httppost('loc1079');
			$allprefse['loc1097']= httppost('loc1097');
			$allprefse['loc1206']= httppost('loc1206');
			$allprefse['mazeturn']= httppost('mazeturn');
			$allprefse['startloc']= httppost('startloc');
			$allprefse['header']= httppost('header');
			set_module_pref('allprefs',serialize($allprefse),'signetd3',$id);
			output("Allprefs Updated`n");
			$subop="edit";
		}
		if ($subop=="edit"){
			require_once("lib/showform.php");
			$form = array(
				"Wasser's Castle,title",
				"complete"=>"Has player completed the castle?,bool",
				"reset"=>"Have the preferences been reset by visiting Fiamma's Fortress?,bool",
				"Encounters,title",
				"randomp"=>"How many random monsters has player encountered so far?,int",
				"watersignet"=>"*`!Received the Water Signet?,bool",
				"scroll5"=>"*Found Scroll 5?,bool",
				"loc159"=>"*Passed Location 159?,bool",
				"* Finish these points and the castle will be closed to this player,note",
				"prisonkey"=>"Does the player have the prison key?,bool",
				"loc11"=>"Passed Location 11?,bool",
				"loc11b"=>"Passed Location 11b?,bool",
				"loc11c"=>"Passed Location 11c?,bool",
				"loc29"=>"Passed Location 29?,enum,0,No,1,Yes,2,In Process",
				"loc48"=>"Passed Location 48?,bool",
				"loc53"=>"Passed Location 53?,bool",
				"loc65"=>"Passed Location 65?,enum,0,No,1,Yes,2,In Process",
				"loc66"=>"Passed Location 66?,enum,0,No,1,Yes,2,In Process",
				"loc82"=>"Passed Location 82?,enum,0,No,1,Yes,2,In Process",
				"loc87"=>"Passed Location 87?,enum,0,No,1,Yes,2,In Process",
				"loc94"=>"Passed Location 94?,enum,0,No,1,Yes,2,In Process",
				"loc148"=>"Passed Location 148?,enum,0,No,1,Yes,2,In Process",
				"loc157"=>"Passed Location 157?,bool",
				"loc164"=>"Passed Location 164?,enum,0,No,1,Yes,2,In Process",
				"loc221"=>"Passed Location 221?,enum,0,No,1,Yes,2,In Process",
				"loc331"=>"Passed Location 331?,bool",
				"loc352"=>"Passed Location 352?,bool",
				"loc354"=>"Passed Location 354?,bool",
				"loc358"=>"Passed Location 358?,enum,0,No,1,Yes,2,In Process",
				"loc401"=>"Passed Location 401?,enum,0,No,1,Yes,2,In Process",
				"loc483"=>"Passed Location 483?,enum,0,No,1,Yes,2,In Process",
				"loc499"=>"Passed Location 499?,enum,0,No,1,Yes,2,In Process",
				"loc537"=>"Passed Location 537?,int",
				"loc561"=>"Passed Location 561?,bool",
				"loc591"=>"Passed Location 591?,enum,0,No,1,Yes,2,In Process",
				"loc619"=>"Passed Location 619?,enum,0,No,1,Yes,2,In Process",
				"loc625"=>"Passed Location 625?,enum,0,No,1,Yes,2,In Process",
				"loc635"=>"Passed Location 635?,bool",
				"loc745"=>"Passed Location 745?,enum,0,No,1,Yes,2,In Process",
				"loc746"=>"Passed Location 746?,bool",
				"loc839"=>"Passed Location 839?,bool",
				"loc931"=>"Passed Location 931?,bool",
				"loc939"=>"Passed Location 939?,enum,0,No,1,Yes,2,In Process",
				"loc1071"=>"Passed Location 1071?,bool",
				"loc1079"=>"Passed Location 1079?,bool",
				"loc1097"=>"Passed Location 1097?,enum,0,No,1,Yes,2,In Process",
				"loc1206"=>"Passed Location 1206?,bool",
				"Maze,title",
				"mazeturn"=>"Maze Return,int",
				"startloc"=>"Starting location,int",
				"header"=>"Which header array is the player at?,range,0,25,1",
			);
			$allprefse=unserialize(get_module_pref('allprefs',"signetd3",$id));
			rawoutput("<form action='runmodule.php?module=signetd3&op=superuser&userid=$id' method='POST'>");
			showform($form,$allprefse,true);
			$click = translate_inline("Save");
			rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
			rawoutput("</form>");
			addnav("","runmodule.php?module=signetd3&op=superuser&userid=$id");
		}
	}
	if ($op=="exits1"){
		output("You have found an `\$Emergency Exit`0.");
		output("You have the option of leaving from this location at this time.");
		if (get_module_setting("exitsave")==1){
			output("`n`nIf you leave now, you may return to the dungeon at this location or enter at the main entrance.");
			addnav("`\$Take Exit`0");
			addnav("Main Entrance","runmodule.php?module=signetd3&op=exits2");
			addnav("This Location","runmodule.php?module=signetd3&op=exits3");
			addnav("Continue");
		}else{
			output("`n`nIf you leave now, you will re-enter the dungeon from this location.");
			addnav("`\$Take Exit","runmodule.php?module=signetd3&op=exits3");
		}
		addnav("Return to the Dungeon","runmodule.php?module=signetd3&loc=".$temp);
	}
	if ($op=="exits2"){
		output("You will re-enter the dungeon from the main exit.");
		$allprefs['startloc']=1275;
		set_module_pref('allprefs',serialize($allprefs));
		villagenav();
	}
	if ($op=="exits3"){
		output("You will re-enter the dungeon at this location.");
		$allprefs['startloc']=get_module_pref("pqtemp");
		set_module_pref('allprefs',serialize($allprefs));
		villagenav();
	}
	if ($op=="reset"){	
		$allprefsd2=unserialize(get_module_pref('allprefs','signetd2'));
		$allprefsd2['loc334']="";
		$allprefsd2['loc685']="";
		$allprefsd2['loc1098']="";
		$allprefsd2['donated']="";
		$allprefsd2['donatenum']="";
		$allprefsd2['loc55']="";
		$allprefsd2['loc59']="";
		$allprefsd2['loc82']="";
		$allprefsd2['loc109']="";
		$allprefsd2['loc109b']="";
		$allprefsd2['loc163']="";
		$allprefsd2['loc279']="";
		$allprefsd2['loc327']="";
		$allprefsd2['loc334b']="";
		$allprefsd2['loc381']="";
		$allprefsd2['loc386']="";
		$allprefsd2['loc496']="";
		$allprefsd2['loc537']="";
		$allprefsd2['loc537b']="";
		$allprefsd2['loc556']="";
		$allprefsd2['loc776']="";
		$allprefsd2['loc822']="";
		$allprefsd2['loc841']="";
		$allprefsd2['loc843']="";
		$allprefsd2['loc865']="";
		$allprefsd2['loc890']="";
		$allprefsd2['loc946']="";
		$allprefsd2['loc947']="";
		$allprefsd2['loc956']="";
		$allprefsd2['loc970']="";
		$allprefsd2['loc1010']="";
		$allprefsd2['loc1010b']="";
		$allprefsd2['loc1012']="";
		$allprefsd2['loc1026']="";
		$allprefsd2['loc1082']="";
		$allprefsd2['loc1098']="";
		$allprefsd2['loc1147']="";
		$allprefsd2['loc1148']="";
		$allprefsd2['mazeturn']="";
		$allprefsd2['randomp']="";
		$allprefsd2['header']="";
		$allprefsd2['reset']=1;
		set_module_pref('allprefs',serialize($allprefsd2),'signetd2');
		clear_module_pref("maze","signetd2");
		clear_module_pref("pqtemp","signetd2");
		$allprefsd5=unserialize(get_module_pref('allprefs','signetd5'));
		if ($allprefsd5['powersignet']==1) redirect("runmodule.php?module=signetd5&op=eg1b");
		else redirect("runmodule.php?module=signetd3&loc=".$temp);
	}
	if ($op=="1097"){	
		output("A mean looking old man says `#'Leave!'");
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-68));
		addnav("Fight the Old Man","runmodule.php?module=signetd3&op=oldman");		
	}
	if ($op=="931"){
		output("You see an ornate treasure chest.");
		addnav("Open It","runmodule.php?module=signetd3&op=931b");
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-34));
	}
	if ($op=="931b"){
		output("You find a scroll!");
		$allprefs['loc931']=1;
		$allprefs['scroll5']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
		addnav("Read Scroll 5","runmodule.php?module=signetd3&op=scroll5b");
	}
	if ($op=="619"){	
		output("A bunch of rowdy soldiers yell `#'Go away punk!'`0");
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-2));
		addnav("Stay","runmodule.php?module=signetd3&op=soldiers");
	}
	if ($op=="483"){	
		output("A group of men in robes say `#'You are in the wrong room.'`0");
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-2));
		addnav("Stay","runmodule.php?module=signetd3&op=mages");
	}
	if ($op=="fall"){	
		output("The floor opens and you fall to a stone surface and slide into a small room.`n`n");
		$loss=round($session['user']['maxhitpoints']*e_rand(2,10)/100);
		$session['user']['hitpoints']-=$loss;
		if ($session['user']['hitpoints']<=0){
			output("You have `\$died`0 from the fall.  You lose all your `^gold`0 and `#10% of your experience`0.");
			$session['user']['hitpoints']=0;
			$session['user']['gold']=0;
			$session['user']['experience']*=.9;
			$session['user']['alive']=false;
			addnav("Continue","shades.php");			
		}else{
			output("You have suffered `\$%s hitpoints`0 of damage from the fall.",$loss);
			set_module_pref("pqtemp",445);
			addnav("Continue","runmodule.php?module=signetd3&loc=".get_module_pref('pqtemp'));	
		}
	}
	if ($op=="221"){
		output("A large door blocks your way. Smash it down?");
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp+34));
		addnav("Smash Door","runmodule.php?module=signetd3&op=door");
	}
	if ($op=="11"){
		output("You see a lever");
		if ($allprefs['loc11c']==0) {
			addnav("Pull Lever","runmodule.php?module=signetd3&op=11c");
			output("on the wall and");
		}else{
			output("that was pulled and");
		}
		if ($allprefs['loc11b']==0) {
			addnav("Search the Box","runmodule.php?module=signetd3&op=11b");
			output("a box on the floor.");
		}else{
			output("the empty box on the floor.");
		}
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-1));
	}
	if ($op=="11b"){
		$gold=e_rand(500,1500);
		$gems=e_rand(2,5);
		$session['user']['gold']+=$gold;
		$session['user']['gems']+=$gems;
		output("You search through the box and find a very generous treasure.");
		output("`n`nYou find `^%s gold`0 and `%%s gems`0!",$gold,$gems);
		$allprefs['loc11b']=1;
		if ($allprefs['loc11c']==1) $allprefs['loc11']=1;
		else addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-1));
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="11c"){
		output("You pull the lever and hear the sound of masonry falling in the far distance.");
		output("`n`nDoesn't it make you wonder what just happened?");
		$allprefs['loc11c']=1;
		if ($allprefs['loc11b']==1) $allprefs['loc11']=1;
		else addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-1));
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="prisondoor"){
		if ($allprefs['prisonkey']==1){
			output("You use the key from the prison guard to unlock the cell.");
			if ($temp==148) $allprefs['loc148']=1;
			elseif ($temp==82) $allprefs['loc82']=1;
			elseif ($temp==87) $allprefs['loc87']=1;
			set_module_pref('allprefs',serialize($allprefs));
			addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
		}else{
			output("The prison cell door is locked.  Would you like to pick the lock or bash down the door?");
			addnav("Bash Door","runmodule.php?module=signetd3&op=bash");
			addnav("Pick Lock","runmodule.php?module=signetd3&op=pick");
			if ($temp==148) addnav("Leave","runmodule.php?module=signetd3&loc=".($temp+1));
			elseif ($temp==82 || $temp==87 ) addnav("Leave","runmodule.php?module=signetd3&loc=".($temp+34));
		}
	}
	if ($op=="bash"){
		output("You start banging on the prison door.");
		output("`n`nFor 10 minutes you hit your shoulder against the door and finally... well, finally, nothing happens.");
		output("`n`nThis is, after all, a prison cell door.");
		output("`n`nYou feel a tap on your shoulder and realize that you've gotten the attention of the prison guard.");
		addnav("Fight Guard","runmodule.php?module=signetd3&op=guard");
	}
	if ($op=="pick"){
		output("You start working on picking the lock of the prison door.");
		if (get_module_pref("skill","specialtythiefskills")>0) {
			$expmultiply = e_rand(10,20);
			$expbonus=$session['user']['dragonkills']*1.5;
			$expgain =round($session['user']['level']*$expmultiply+$expbonus);
			$session['user']['experience']+=$expgain;			
			output("Using your special thieving skills, you are able to pick the lock.");
			output("`n`nYou `#gain %s experience`0 by using your skills.",$expgain);
			addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
			if ($temp==148) $allprefs['loc148']=1;
			elseif ($temp==82) $allprefs['loc82']=1;
			elseif ($temp==87) $allprefs['loc87']=1;
			set_module_pref('allprefs',serialize($allprefs));
		}else{
			output("Not being a very good thief, you fail at picking the lock.");
			if ($session['user']['turns']>0){
				output("You `@lose one turn`0 trying to open the door.");
				$session['user']['turns']--;
			}
			output("`n`nAfter a frustrating amount of wasted time, you feel a tap on your shoulder.  It seems like the prison guard has been watching you.");
			addnav("Fight Guard","runmodule.php?module=signetd3&op=guard");
		}
	}
	if ($op=="48"){
		output("Two elves in this cell tell you `#'The key to the other cell is west of the Great Hall.'`0");
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
		$allprefs['loc48']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="53"){
		output("A Goblin gratefully thanks you for his freedom.");
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
		$allprefs['loc53']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="157"){
		if ($allprefs['loc352']==0){
			output("No matter what you do you can't open this door.  It seems like you'll have to find a key somewhere.");
			addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-1));
		}else{
			output("Your key opens the cell door.");
			$allprefs['loc157']=1;
			addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="159"){
		output("The old man awakens and says `#'Thank you brave adventurers.");
		output("I am Lord Wasser's aid and the castle has been overtaken by Fiamma.'");
		output("`n`n`0He Continues `#'You should go to the fortress of Fiamma to get the `QFire Signet`#.");
		output("To aid you I give you this,'`0 he says handing you a bag full of gems.`n`n `#'Remember'`0 he adds, `#'The fortress is well guarded.'`0");
		output("`n`nYou gain `%3 gems`0!");
		$session['user']['gems']+=3;
		$allprefs['loc159']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);		
	}
	if ($op=="331"){
		output("The door is locked.  Would you like to try to pick the lock?");
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-34));
		addnav("Pick Lock","runmodule.php?module=signetd3&op=331b");	
	}
	if ($op=="331b"){
		if($session['user']['level']>13) $trap=4;
		elseif($session['user']['level']>10) $trap=3;
		elseif($session['user']['level']>7) $trap=2;
		elseif($session['user']['level']>4) $trap=1;
		else $trap=1;
		if (get_module_pref("skill","specialtythiefskills")>0){
			output("You're thieving skills help with your attempt...`n`n");
			$trap+=1;
		}
		switch(e_rand($trap,10)){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				output("You're unable to pick the lock... in fact it appears to be trapped!");
				$hitpoints=round($session['user']['maxhitpoints']*.08);
				if ($hitpoints>0) output("`n`nA cloud of `@poison gas`0 explodes from the trap.  You struggle to breath as you feel a burn in your lungs. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				$session['user']['hitpoints']-=$hitpoints;
				increment_module_pref("pqtemp",-34);
				if ($session['user']['hitpoints']<=0){
					output("You have `\$died`0 from the gas.  You lose all your `^gold`0 and `#10% of your experience`0.");
					$session['user']['hitpoints']=0;
					$session['user']['gold']=0;
					$session['user']['experience']*=.9;
					$session['user']['alive']=false;
					addnav("Continue","shades.php");
					blocknav("runmodule.php?module=signetd3&loc=".get_module_pref("pqtemp"));
				}
			break;
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
				output("You successfully unlock the door.`n`n");
				$allprefs['loc331']=1;
				set_module_pref('allprefs',serialize($allprefs));
			break;
		}
		addnav("Continue","runmodule.php?module=signetd3&loc=".get_module_pref("pqtemp"));
	}
	if ($op=="635"){
		$gems=e_rand(2,3);
		$session['user']['gems']+=$gems;
		output("You look under the bed and find `%%s gems`0.",$gems);
		$allprefs['loc635']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
	}
	if ($op=="537"){
		output("You see some graffiti on the wall.  Would you like to read it?");
		addnav("Read Graffiti","runmodule.php?module=signetd3&op=537b");	
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-1));
	}
	if ($op=="537b"){
		$loc537=$allprefs['loc537'];
		if ($loc537==0) output("The graffiti on the wall says `#'I bet you thought we'd leave something valuable in the closet, didn't you?'");
		elseif ($loc537==1) output("Below, more graffiti reads `#'Seriously, did you think there would be a hidden shelf?'");
		elseif ($loc537==2) output("Below, more graffiti reads `#'Okay, there is no hidden shelf.  Go away.'");
		elseif ($loc537==3) output("Below, more graffiti reads `#'GO AWAY!'");
		elseif ($loc537==4) output("Below, more graffiti reads `#'You're getting annoying.'");
		elseif ($loc537==5) output("Below, more graffiti reads `#'Fine, do you want me to tell you a story?  There was an adventurer that kept reading walls.  Nothing happened.  Now go away.'");
		elseif ($loc537==6) output("Below, more graffiti reads `#'Look out! It's a trap!!!!'`n`n`n`n`n`n`n`n`n`n`n`n`n`n`n'Just kidding!'");
		elseif ($loc537==7) output("Below, more graffiti reads `#'You're a persistent little bugger, aren't you?'");
		elseif ($loc537==8) {
			output("Below, more graffiti reads `#'Fine, I give up.  You can have a `%gem`# if you'll go away.'");
			$session['user']['gems']++;
			output("`n`n`0You find a `%gem`0.");
		}elseif ($loc537==9) {
			output("Below, more graffiti reads `#'I told you that you could have the `%gem`# only if you went away.  Now I'm taking your `%gem`# back.'");
			$session['user']['gems']--;
			output("`n`n`0You look at the wall and laugh, but drop the `%gem`0 you just found.  Oh my, you've lost your `%gem`0!");
		}elseif ($loc537>=10) output("There's no more graffiti to read.");
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-1));
		$allprefs['loc537']=$allprefs['loc537']+1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="745"){
		output("You enter the room of `!Magic Pools`0 and you're attacked by a very powerful `!Guardian Water Elemental`0.");
		addnav("Fight Water Elemental","runmodule.php?module=signetd3&op=waterelemental");	
		addnav("Run Away!","runmodule.php?module=signetd3&loc=".($temp-1));
	}
	if ($op=="746"){
		output("`&William the Wise`0 is standing here.  He is the keeper of the `!Water Signet`0.");
		output("`n`nHe says `#'I give thee the `!Water Signet`#; the most sacred of the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signets`#.'");
		$allprefs['watersignet']=1;
		$allprefs['loc746']=1;
		set_module_pref('allprefs',serialize($allprefs));
		$allprefssale=unserialize(get_module_pref('allprefssale',"signetsale"));
		set_module_pref("hoftemp",3200+$allprefssale['completednum'],"signetsale");
		addnews("`@%s`@ was marked with the `!Water Signet`@ in `^Wasser's Castle`@.",$session['user']['name']);
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
	}
	if ($op=="839"){
		output("Here is a large wooden box.  What would you like to do?");
		addnav("Open the Box","runmodule.php?module=signetd3&op=839b");	
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp+1));
	}
	if ($op=="839b"){
		output("You carefully open the box to find...`n`n");
		$gold=e_rand(500,1000);
		if ($session['user']['gold']>1000) {
			$session['user']['gold']+=$gold*2;
			output("`^%s gold`0 and",$gold*2);
		}else{
			output("`^%s gold`0 and",$gold);
			$session['user']['gold']+=$gold;
		}
		$gems=e_rand(2,4);
		$session['user']['gems']+=$gems;
		output("`%%s gems`0!",$gems);
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
		$allprefs['loc839']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="352"){
		output("Here is a treaure chest.  What would you like to do?");
		addnav("Open the Chest","runmodule.php?module=signetd3&op=352b");	
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp+1));
	}
	if ($op=="352b"){
		output("You find a large key.  There's got to be some use for this key!");
		$allprefs['loc352']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
	}
	if ($op=="354"){
		output("Here is a treasure chest here but it appears to be trapped.  What would you like to do?");
		addnav("Disarm the Trap","runmodule.php?module=signetd3&op=354b");	
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-1));
	}
	if ($op=="354b"){
		$gold=e_rand(250,1000);
		$session['user']['gold']+=$gold;
		$gems=e_rand(2,4);
		$session['user']['gems']+=$gems;
		output("You were mistaken.  There weren't any traps.`n`nYou carefully open the box to find `^%s gold`0 and `%%s gems`0!",$gold,$gems);
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
		$allprefs['loc354']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="1079"){
		output("The door is locked.  Would you like to try to pick the lock?");
		addnav("Leave","runmodule.php?module=signetd3&loc=".($temp-34));
		addnav("Pick Lock","runmodule.php?module=signetd3&op=1079b");	
	}
	if ($op=="1079b"){
		if($session['user']['level']>13) $trap=4;
		elseif($session['user']['level']>10) $trap=3;
		elseif($session['user']['level']>7) $trap=2;
		elseif($session['user']['level']>4) $trap=1;
		else $trap=1;
		if (get_module_pref("skill","specialtythiefskills")>0){
			output("You're thieving skills help with your attempt...`n`n");
			$trap+=1;
		}
		switch(e_rand($trap,10)){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				output("You're unable to pick the door lock... and it looks like it was trapped!");
				$hitpoints=round($session['user']['maxhitpoints']*.08);
				if ($hitpoints>0) output("`n`nA cloud of `@poison gas`0 explodes from the trap.  You struggle to breath as you feel a burn in your lungs. You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				$session['user']['hitpoints']-=$hitpoints;
				increment_module_pref("pqtemp",-34);
				if ($session['user']['hitpoints']<=0){
					output("You have `\$died`0 from the gas.  You lose all your `^gold`0 and `#10% of your experience`0.");
					$session['user']['hitpoints']=0;
					$session['user']['gold']=0;
					$session['user']['experience']*=.9;
					$session['user']['alive']=false;
					addnav("Continue","shades.php");
					blocknav("runmodule.php?module=signetd3&loc=".get_module_pref("pqtemp"));
				}
			break;
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
				output("You successfully pick the lock.`n`n");
				$allprefs['loc1079']=1;
				set_module_pref('allprefs',serialize($allprefs));
			break;
		}
		addnav("Continue","runmodule.php?module=signetd3&loc=".get_module_pref("pqtemp"));
	}
	if ($op=="625"){
		output("Down the hall you see several ogres having a meeting.  They see you and run up the hall.");
		addnav("Fight Ogres","runmodule.php?module=signetd3&op=ogres");	
	}
	//the scrolls
	if ($op=="scroll1b"){
		output("`c`b`^The Aria Dungeon`0`c`b`n");
		output("The Aria Dungeon was once the center of a small community of dwarves.  About 15 years ago, a band of orcs invaded the community and defeated the dwarves.");
		output("`n`nIt is said that only the leader of the dwarves, Kilmor, and a few others escaped from the Orcs.");
		addnav("Return","runmodule.php?module=signetd3&loc=".$temp);
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
		addnav("Return","runmodule.php?module=signetd3&loc=".$temp);
	}
	if ($op=="scroll3b"){
		output("`c`b`^Prison Notes by Kilmor`0`c`b`n");
		output("The Orcs are less than wonderful captors. I hope to be able to break free soon.");
		output("My first journey will be to the `^Temple of the Aarde Priests`0.  It is rumored that the high priest of the temple has the power of prophesy, but few have been allowed to see him.");
		addnav("Return","runmodule.php?module=signetd3&loc=".$temp);
	}
	if ($op=="scroll4b"){
		output("`c`b`^Report from Evad the Sage`c`b");
		output("`n`0In `\$Fiamma's Fortress`0 there is a secret control room which can be accessed from one of two secret passages.");
		output("The first starts in `\$Fiamma's`0 room.  The other starts between the arena and the jail cell.");
		output("From this room various gates around the castle can be operated.");
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
	}
	if ($op=="scroll5b"){
		output("`c`b`^The Story of Fiamma Fortress`c`b");
		output("`n`0Many years ago when Mierscri was invading the land, the men and beasts put aside their differences and joined together to fight him.");
		output("`n`nThe lowly Fiamma, who was an officer in this combined army, told Mierscri of the plan; thus it failed.");
		output("`n`nAs a reward for this action, Fiamma was given a great fortress and given the Fire Signet to guard over.");
		addnav("Continue","runmodule.php?module=signetd3&loc=".$temp);
	}
	//Scrolls for in the bio start here

	if ($op=="scroll5"){
		$userid = httpget("user");
		output("`c`b`^The Story of Fiamma Fortress`c`b");
		output("`n`0Many years ago when Mierscri was invading the land, the men and beasts put aside their differences and joined together to fight him.");
		output("`n`nThe lowly Fiamma, who was an officer in this combined army, told Mierscri of the plan; thus it failed.");
		output("`n`nAs a reward for this action, Fiamma was given a great fortress and given the Fire Signet to guard over.");
		addnav("Return to Signets","runmodule.php?module=signetsale&op=signetnotes&user=$userid");
	}
	page_footer();
}
?>