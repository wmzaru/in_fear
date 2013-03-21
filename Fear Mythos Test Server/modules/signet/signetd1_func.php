<?php
function signetd1_fight($op) {
	global $session,$badguy;
	$temp=get_module_pref("pqtemp");
	page_header("Dungeon Fight");
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($op=="door"){
		if (($temp==1006 || $temp==1005) && $allprefs['loc1006']==0) $allprefs['loc1006']=2;
		elseif ($temp==1008 && $allprefs['loc1008']==0) $allprefs['loc1008']=2;
		elseif (($temp==973 || $temp==939) && $allprefs['loc973']==0) $allprefs['loc973']=2;
		elseif ($temp==787 && $allprefs['loc787']==0) $allprefs['loc787']=2;
		elseif ($temp==689 && $allprefs['loc689']==0) $allprefs['loc689']=2;
		elseif ($temp==1015 && $allprefs['loc1015']==0) $allprefs['loc1015']=2;
		elseif ($temp==983 && $allprefs['loc983']==0) $allprefs['loc983']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("`Qa Stubborn Door`0"),
			"creaturelevel"=>1,
			"creatureweapon"=>translate_inline("sharp splinters"),
			"creatureattack"=>1,
			"creaturedefense"=>2,
			"creaturehealth"=>10,
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="orc"){
		if ($temp==1009 && $allprefs['loc1009']==0) $allprefs['loc1009']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("an Orc"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("a rusty axe"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.75,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.75,
			"creaturehealth"=>round($session['user']['hitpoints']/2),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="orcs"){
		if ($temp==1216 && $allprefs['loc1216']==0) $allprefs['loc1216']=2;
		elseif ($temp==899 && $allprefs['loc899']==0) $allprefs['loc899']=2;
		elseif ($temp==673 && $allprefs['loc673']==0) $allprefs['loc673']=2;
		elseif ($temp==623 && $allprefs['loc623']==0) $allprefs['loc623']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("A Group of Orcs"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("rusty swords"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.85,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.85,
			"creaturehealth"=>round($session['user']['hitpoints']/1.2),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="ant"){
		if ($temp==1197 && $allprefs['loc1197']==0) $allprefs['loc1197']=2;
		elseif ($temp==521 && $allprefs['loc521']==0) $allprefs['loc521']=2;
		set_module_pref('allprefs',serialize($allprefs));
		if ($session['user']['level']==1) $level=1;
		else $level=$session['user']['level']-1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("a Giant Ant"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("deadly pincers"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.75,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.82),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="spider"){
		if ($temp==1152 && $allprefs['loc1152']==0) $allprefs['loc1152']=2;
		set_module_pref('allprefs',serialize($allprefs));
		if ($session['user']['level']==1) $level=1;
		else $level=$session['user']['level']-1;
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("a Spider"),
			"creaturelevel"=>$level,
			"creatureweapon"=>translate_inline("huge fangs"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.72,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.86,
			"creaturehealth"=>round($session['user']['hitpoints']*.84),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="rangers"){
		if ($temp==494 && $allprefs['loc494']==0) $allprefs['loc494']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("a Band of Rangers"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("Long Swords"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.87,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.92,
			"creaturehealth"=>round($session['user']['hitpoints']*.94),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="scribe"){
		if ($temp==593 && $allprefs['loc593']==0) $allprefs['loc593']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("An Evil Scribe"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("simple attack spells"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.62,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.52,
			"creaturehealth"=>round($session['user']['hitpoints']*1.14),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="kobolds"){
		if ($temp==465 && $allprefs['loc465']==0) $allprefs['loc465']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Nasty Kobolds"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("dirty fingernails"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.70,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.70,
			"creaturehealth"=>round($session['user']['hitpoints']*.70),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="dripslimes"){
		if ($temp==54 && $allprefs['loc54']==0) $allprefs['loc54']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("Drip Slimes"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("shooting goo"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.5,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.5,
			"creaturehealth"=>round($session['user']['hitpoints']*1.3),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}elseif ($op=="bantir"){
		if ($temp==453 && $allprefs['loc453']==0) $allprefs['loc453']=2;
		set_module_pref('allprefs',serialize($allprefs));
		$badguy = array(
			"type"=>"",
			"creaturename"=>translate_inline("a Bantir"),
			"creaturelevel"=>$session['user']['level'],
			"creatureweapon"=>translate_inline("stringy appendages"),
			"creatureattack"=>($session['user']['attack']+e_rand(1,3))*.76,
			"creaturedefense"=>($session['user']['attack']+e_rand(1,3))*.8,
			"creaturehealth"=>round($session['user']['hitpoints']*.7),
			"diddamage"=>0);
		$session['user']['badguy']=createstring($badguy);
		$op="fight";
	}
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($op=="fight" or $op=="run"){
		global $badguy;
		$battle=true;
		$fight=true;
		if ($battle){
			require_once("battle.php");
    
	    if ($victory){
       			addnav("Continue","runmodule.php?module=signetd1&loc=".get_module_pref('pqtemp'));
				//opening doors
				if ((($temp==1005 ||$temp==1006) && $allprefs['loc1006']==2) || ($temp==1008 && $allprefs['loc1008']==2) || (($temp==973 || $temp==939) && $allprefs['loc973']==2) || ($temp==787 && $allprefs['loc787']==2) || ($temp==689 && $allprefs['loc689']==2) || ($temp==1015 && $allprefs['loc1015']==2) || ($temp==983 && $allprefs['loc983']==2)) {
					output("`^`n`bYou have opened the door safely.`b`n");
					$experience=$session['user']['level']*e_rand(2,5);
					output("`#You receive `6%s `#experience.`n",$experience);
					$session['user']['experience']+=$experience;
					if ($allprefs['loc1006']==2) $allprefs['loc1006']=1;
					elseif ($allprefs['loc1008']==2) $allprefs['loc1008']=1;
					elseif ($allprefs['loc973']==2) $allprefs['loc973']=1;
					elseif ($allprefs['loc787']==2) $allprefs['loc787']=1;
					elseif ($allprefs['loc689']==2) $allprefs['loc689']=1;
					elseif ($allprefs['loc1015']==2) $allprefs['loc1015']=1;
					elseif ($allprefs['loc983']==2) $allprefs['loc983']=1;
					set_module_pref('allprefs',serialize($allprefs));
				//monsters
				}elseif (($temp==1009 && $allprefs['loc1009']==2) || ($temp==1216 && $allprefs['loc1216']==2) || ($temp==899 && $allprefs['loc899']==2) || ($temp==1197 && $allprefs['loc1197']==2) || ($temp==1152 && $allprefs['loc1152']==2) || ($temp==673 && $allprefs['loc673']==2) || ($temp==494 && $allprefs['loc494']==2) || ($temp==593 && $allprefs['loc593']==2) || ($temp==465 && $allprefs['loc465']==2) || ($temp==54 && $allprefs['loc54']==2) || ($temp==623 && $allprefs['loc623']==2) || ($temp==521 && $allprefs['loc521']==2) || ($temp==453 && $allprefs['loc453']==2)){
					output("`b`4You have slain `^%s`4.`b`n",$badguy['creaturename']);
					$gold=e_rand(50,150);
					$session['user']['gold']+=$gold;
					$expmultiply = e_rand(8,17);
					$expbonus=$session['user']['dragonkills'];
					$expgain =($session['user']['level']*$expmultiply+$expbonus);
					$session['user']['experience']+=$expgain;
					output("`n`@You search through the smelly corpse and find `^%s gold`@.`n",$gold);
					output("`n`#You have gained `7%s `#experience.`n",$expgain);
					if ($allprefs['loc1009']==2) {
						$allprefs['loc1009']=1;
						if ($allprefs['loc1008']==0){
							$allprefs['loc1008']=1;
							output("`nYour fighting opens the door ahead of you.");
						}
					}
					if ($allprefs['loc1216']==2) $allprefs['loc1216']=1;
					elseif ($allprefs['loc899']==2) $allprefs['loc899']=1;
					elseif ($allprefs['loc1197']==2) $allprefs['loc1197']=1;
					elseif ($allprefs['loc1152']==2) $allprefs['loc1152']=1;
					elseif ($allprefs['loc673']==2) $allprefs['loc673']=1;
					elseif ($allprefs['loc494']==2) $allprefs['loc494']=1;
					elseif ($allprefs['loc593']==2) $allprefs['loc593']=1;
					elseif ($allprefs['loc465']==2) $allprefs['loc465']=1;
					elseif ($allprefs['loc54']==2) $allprefs['loc54']=1;
					elseif ($allprefs['loc623']==2) $allprefs['loc623']=1;
					elseif ($allprefs['loc521']==2) $allprefs['loc521']=1;
					elseif ($allprefs['loc453']==2) $allprefs['loc453']=1;
					set_module_pref('allprefs',serialize($allprefs));
				//next lines are for random encounters
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
					switch(e_rand(1,12)){
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
						break;
					}
				}
				$badguy=array();
				$session['user']['badguy']="";
			}elseif ($defeat){
				if ((($temp==1005 ||$temp==1006) && $allprefs['loc1006']==2) || ($temp==1008 && $allprefs['loc1008']==2) || (($temp==973 || $temp==939) && $allprefs['loc973']==2) || ($temp==787 && $allprefs['loc787']==2) || ($temp==689 && $allprefs['loc689']==2) || ($temp==1015 && $allprefs['loc1015']==2) || ($temp==983 && $allprefs['loc983']==2)) {
					if ($allprefs['loc1006']==2) $allprefs['loc1006']=0;
					elseif ($allprefs['loc1008']==2) $allprefs['loc1008']=0;
					elseif ($allprefs['loc973']==2) $allprefs['loc973']=0;
					elseif ($allprefs['loc787']==2) $allprefs['loc787']=0;
					elseif ($allprefs['loc689']==2) $allprefs['loc689']=0;
					elseif ($allprefs['loc1015']==2) $allprefs['loc1015']=0;
					elseif ($allprefs['loc983']==2) $allprefs['loc983']=0;
					output("A splinter severs a major blood vessel and you die.`n");
					addnews("`% %s`5 has been slain trying to break down a door.",$session['user']['name']);
					set_module_pref('allprefs',serialize($allprefs));
				}elseif (($temp==1009 && $allprefs['loc1009']==2) || ($temp==1216 && $allprefs['loc1216']==2) || ($temp==899 && $allprefs['loc899']==2) || ($temp==1197 && $allprefs['loc1197']==2) || ($temp==1152 && $allprefs['loc1152']==2) || ($temp==673 && $allprefs['loc673']==2) || ($temp==494 && $allprefs['loc494']==2) || ($temp==593 && $allprefs['loc593']==2) || ($temp==465 && $allprefs['loc465']==2) || ($temp==54 && $allprefs['loc54']==2) || ($temp==623 && $allprefs['loc623']==2) || ($temp==521 && $allprefs['loc521']==2) || ($temp==453 && $allprefs['loc453']==2)){
					if ($allprefs['loc1009']==2) $allprefs['loc1009']=0;
					elseif ($allprefs['loc1216']==2) $allprefs['loc1216']=0;
					elseif ($allprefs['loc899']==2) $allprefs['loc899']=0;
					elseif ($allprefs['loc1197']==2) $allprefs['loc1197']=0;
					elseif ($allprefs['loc1152']==2) $allprefs['loc1152']=0;
					elseif ($allprefs['loc673']==2) $allprefs['loc673']=0;
					elseif ($allprefs['loc494']==2) $allprefs['loc494']=0;
					elseif ($allprefs['loc593']==2) $allprefs['loc593']=0;
					elseif ($allprefs['loc465']==2) $allprefs['loc465']=0;
					elseif ($allprefs['loc54']==2) $allprefs['loc54']=0;
					elseif ($allprefs['loc623']==2) $allprefs['loc623']=0;
					elseif ($allprefs['loc521']==2) $allprefs['loc521']=0;
					elseif ($allprefs['loc453']==2) $allprefs['loc453']=0;
					set_module_pref('allprefs',serialize($allprefs));
					output("As you hit the ground the `^%s runs away.",$badguy['creaturename']);
					addnews("`%%s`5 has been slain by an evil %s in a dungeon.",$session['user']['name'],$badguy['creaturename']);
				//Next lines are for random encounters
				}else{
					output("As you hit the ground the `^%s runs away.",$badguy['creaturename']);
					addnews("`%%s`5 has been slain by an evil %s in a dungeon.",$session['user']['name'],$badguy['creaturename']);
				}
		        $badguy=array();
		        $session['user']['badguy']="";  
		        $session['user']['hitpoints']=0;
		        $session['user']['alive']=false;
		        addnav("Continue","shades.php");
			}else{
					require_once("lib/fightnav.php");
					fightnav(true,false,"runmodule.php?module=signetd1");
			}
		}else{
			redirect("runmodule.php?module=signetd1&loc=".get_module_pref('pqtemp'));	
		}
	}
	page_footer();
}
function signetd1_misc($op) {
	$temp=get_module_pref("pqtemp");
	page_header("Aria Dungeon");
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
		$allprefse=unserialize(get_module_pref('allprefs',"signetd1",$id));
		if ($allprefse['randomp']=="") $allprefse['randomp']= 0;
		if ($allprefse['mazeturn']=="") $allprefse['mazeturn']= 0;
		if ($allprefse['startloc']=="") $allprefse['startloc']= 1279;
		set_module_pref('allprefs',serialize($allprefse),'signetd1',$id);
		if ($subop!='edit'){
			$allprefse=unserialize(get_module_pref('allprefs',"signetd1",$id));
			$allprefse['complete']= httppost('complete');
			$allprefse['reset']= httppost('reset');
			$allprefse['randomp']= httppost('randomp');
			$allprefse['airsignet']= httppost('airsignet');
			$allprefse['scroll2']= httppost('scroll2');
			$allprefse['scroll3']= httppost('scroll3');
			$allprefse['loc411']= httppost('loc411');
			$allprefse['loc54']= httppost('loc54');
			$allprefse['loc113']= httppost('loc113');
			$allprefse['loc113b']= httppost('loc113b');
			$allprefse['loc303']= httppost('loc303');
			$allprefse['loc333']= httppost('loc333');
			$allprefse['loc453']= httppost('loc453');
			$allprefse['loc465']= httppost('loc465');
			$allprefse['loc494']= httppost('loc494');
			$allprefse['loc521']= httppost('loc521');
			$allprefse['loc593']= httppost('loc593');
			$allprefse['loc623']= httppost('loc623');
			$allprefse['loc673']= httppost('loc673');
			$allprefse['loc677']= httppost('loc677');
			$allprefse['loc685']= httppost('loc685');
			$allprefse['loc689']= httppost('loc689');
			$allprefse['loc711']= httppost('loc711');
			$allprefse['loc759']= httppost('loc759');
			$allprefse['loc787']= httppost('loc787');
			$allprefse['loc891']= httppost('loc891');
			$allprefse['loc899']= httppost('loc899');
			$allprefse['loc973']= httppost('loc973');
			$allprefse['loc983']= httppost('loc983');
			$allprefse['loc1006']= httppost('loc1006');
			$allprefse['loc1008']= httppost('loc1008');
			$allprefse['loc1009']= httppost('loc1009');
			$allprefse['loc1011']= httppost('loc1011');
			$allprefse['loc1015']= httppost('loc1015');
			$allprefse['loc1133']= httppost('loc1133');
			$allprefse['loc1152']= httppost('loc1152');
			$allprefse['loc1197']= httppost('loc1197');
			$allprefse['loc1199']= httppost('loc1199');
			$allprefse['loc1216']= httppost('loc1216');
			$allprefse['loc1287']= httppost('loc1287');
			$allprefse['loc1133b']= httppost('loc1133b');
			$allprefse['mazeturn']= httppost('mazeturn');
			$allprefse['startloc']= httppost('startloc');
			$allprefse['header']= httppost('header');
			set_module_pref('allprefs',serialize($allprefse),'signetd1',$id);
			output("Allprefs Updated`n");
			$subop="edit";
		}
		if ($subop=="edit"){
			require_once("lib/showform.php");
			$form = array(
				"Aria Dungeon,title",
				"complete"=>"Has player completed the dungeon?,bool",
				"reset"=>"Have the preferences been reset by visiting Aarde Temple?,bool",
				"Encounters,title",
				"randomp"=>"How many random monsters has player encountered so far?,int",
				"airsignet"=>"*`3Received the Air Signet?,bool",
				"scroll2"=>"*Found Scroll 2?,bool",
				"scroll3"=>"*Found Scroll 3?,bool",
				"loc411"=>"*Passed Location 411?,bool",
				"* Finish these points and the dungeon will be closed to this player,note",
				"loc54"=>"Passed Location 54?,enum,0,No,1,Yes,2,In Process",
				"loc113"=>"Passed Location 113?,bool",
				"loc113b"=>"Passed Location 113b?,bool",
				"loc303"=>"Passed Location 303?,bool",
				"loc333"=>"Passed Location 333?,bool",
				"loc453"=>"Passed Location 453?,enum,0,No,1,Yes,2,In Process",
				"loc465"=>"Passed Location 465?,enum,0,No,1,Yes,2,In Process",
				"loc494"=>"Passed Location 494?,enum,0,No,1,Yes,2,In Process",
				"loc521"=>"Passed Location 521?,enum,0,No,1,Yes,2,In Process",
				"loc593"=>"Passed Location 593?,enum,0,No,1,Yes,2,In Process",
				"loc623"=>"Passed Location 623?,enum,0,No,1,Yes,2,In Process",
				"loc673"=>"Passed Location 673?,enum,0,No,1,Yes,2,In Process",
				"loc677"=>"Passed Location 677?,bool",
				"loc685"=>"Passed Location 685?,bool",
				"loc689"=>"Passed Location 689?,enum,0,No,1,Yes,2,In Process",
				"loc711"=>"Passed Location 711?,bool",
				"loc759"=>"Passed Location 759?,bool",
				"loc787"=>"Passed Location 787?,enum,0,No,1,Yes,2,In Process",
				"loc891"=>"Passed Location 891?,bool",
				"loc899"=>"Passed Location 899?,enum,0,No,1,Yes,2,In Process",
				"loc973"=>"Passed Location 973?,enum,0,No,1,Yes,2,In Process",
				"loc983"=>"Passed Location 983?,enum,0,No,1,Yes,2,In Process",
				"loc1006"=>"Passed Location 1006?,enum,0,No,1,Yes,2,In Process",
				"loc1008"=>"Passed Location 1008?,enum,0,No,1,Yes,2,In Process",
				"loc1009"=>"Passed Location 1009?,enum,0,No,1,Yes,2,In Process",
				"loc1011"=>"Passed Location 1011?,bool",
				"loc1015"=>"Passed Location 1015?,enum,0,No,1,Yes,2,In Process",
				"loc1133"=>"Passed Location 1133?,bool",
				"loc1152"=>"Passed Location 1152?,enum,0,No,1,Yes,2,In Process",
				"loc1197"=>"Passed Location 1197?,enum,0,No,1,Yes,2,In Process",
				"loc1199"=>"Passed Location 1199?,bool",
				"loc1216"=>"Passed Location 1216?,enum,0,No,1,Yes,2,In Process",
				"loc1287"=>"Passed Location 1287?,bool",
				"loc1133b"=>"Attempted to attack the old man?,bool",
				"Maze,title",
				"mazeturn"=>"Maze Return,int",
				"startloc"=>"Starting location,int",
				"header"=>"Which header array is the player at?,range,0,19,1",
			);
			$allprefse=unserialize(get_module_pref('allprefs',"signetd1",$id));
			rawoutput("<form action='runmodule.php?module=signetd1&op=superuser&userid=$id' method='POST'>");
			showform($form,$allprefse,true);
			$click = translate_inline("Save");
			rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
			rawoutput("</form>");
			addnav("","runmodule.php?module=signetd1&op=superuser&userid=$id");
		}
	}
	if ($op=="exits1"){
		output("You have found an `\$Emergency Exit`0.");
		output("You have the option of leaving from this location at this time.");
		if (get_module_setting("exitsave")==1){
			output("`n`nIf you leave now, you may return to the dungeon at this location or enter at the main entrance.");
			addnav("`\$Take Exit`0");
			addnav("Main Entrance","runmodule.php?module=signetd1&op=exits2");
			addnav("This Location","runmodule.php?module=signetd1&op=exits3");
			addnav("Continue");
		}else{
			output("`n`nIf you leave now, you will re-enter the dungeon from this location.");
			addnav("`\$Take Exit","runmodule.php?module=signetd1&op=exits3");
		}
		addnav("Return to the Dungeon","runmodule.php?module=signetd1&loc=".$temp);
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
	if ($op=="973"){
		output("You will need to bash down the door to open it.");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+34));
		addnav("Open","runmodule.php?module=signetd1&op=door");
	}
	if ($op=="939"){
		output("You will need to bash down the door to open it.");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-34));
		addnav("Open","runmodule.php?module=signetd1&op=door");
	}
	if ($op=="1006"){
		output("You will need to bash down the door to open it.");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+1));
		addnav("Open","runmodule.php?module=signetd1&op=door");	
	}
	if ($op=="1005"){
		output("You will need to bash down the door to open it.");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+1));
		addnav("Open","runmodule.php?module=signetd1&op=door");	
	}
	if ($op=="1008"){
		output("You will need to bash down the door to open it.");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-1));
		addnav("Open","runmodule.php?module=signetd1&op=door");	
	}
	if ($op=="1011"){
		$allprefs['loc1011']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd1&loc=".$temp);
		output("You walk quickly to the intersection and feel your feet fall out from under you!");
		output("You've triggered a trap!`n`n");
		switch(e_rand(1,4)){
			case 1:
			case 2:
			case 3:
				output("Luckily, your quick reflexes save you.  You look down into the pit.  You kick a pebble into it.");
				output("`n`nAnd you wait...");
				output("`n`nAnd wait...");
				output("`n`nAnd wait...");
				output("`n`nAnd finally hear the stone hit bottom. You'll be very careful next time you come to this intersection.");
			break;
			case 4:
				output("You scramble and shuffle but you find yourself falling.  With a desperate lunge you grab for the wall...");
				switch(e_rand(1,8)){
				case 1:
				case 2:
				case 3:
				case 4:
					$hitpoints=round($session['user']['hitpoints']*.1);
					output("`n`nAnd catch the edge of the pit.  Feeling lucky, you crawl out to notice that one of the wooden spikes pierced your leg.");
					output("It's clearly not a mortal wound but it stings.`n`n");
					if ($hitpoints>0) output("You `\$lose %s %s`0.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					$session['user']['hitpoints']-=$hitpoints;
				case 5:
				case 6:
				case 7:
					if ($session['user']['gold']>250){
						$session['user']['gold']-=250;
						output("You watch as some of your `^gold`0 tumbles down into the pit.  A quick inventory reveals that you've lost `^250 gold`0.`n");
					}elseif ($session['user']['gold']>0){
						$session['user']['gold']=0;
						output("You recover but notice that your `^gold`0 pouch has fallen into the pit.  You've lost all your `^gold`0!");
					}else output("Luckily, nothing happens.");
				break;
				case 8:
					$hitpoints=round($session['user']['hitpoints']*.2);
					output("`n`nAnd fall for what seems like forever! You look up and realize you're only about 5 feet down, but the damage has been done.");
					if ($session['user']['turns']>0){
						output("You spend `@a turn`0 getting out of the pit.`n`n");
						$session['user']['turns']--;
					}
					$session['user']['hitpoints']-=$hitpoints;
					if ($hitpoints>0) output("You lose %s %s.",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				break;
			}
			break;
		}
	}
	if ($op=="1216"){	
		output("You come to the door and hear `\$monsters`0 on the other side.  Do you continue?");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-1));
		addnav("Continue","runmodule.php?module=signetd1&op=orcs");		
	}
	if ($op=="1287"){
		output("You find a `^t`%r`^e`%a`^s`%u`^r`%e`0 chest.  Would you like to open it?");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-1));
		addnav("Open","runmodule.php?module=signetd1&op=1287b");	
	}
	if ($op=="1287b"){
		output("You carefully open the `^t`%r`^e`%a`^s`%u`^r`%e`0 chest to find...`n`n");
		$gold=e_rand(350,550);
		if ($session['user']['gold']>550) {
			$session['user']['gold']+=$gold*2;
			output("`^%s gold`0 and",$gold*2);
		}else{
			output("`^%s gold`0 and",$gold);
			$session['user']['gold']+=$gold;
		}
		$gems=e_rand(1,3);
		$session['user']['gems']+=$gems;
		output("`%%s %s`0!",$gems,translate_inline($gems>1?"gems":"gem"));
		output("`n`nYou quickly pocket the loot and continue your explorations.");
		addnav("Continue","runmodule.php?module=signetd1&loc=".$temp);
		$allprefs['loc1287']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="899"){
		output("You see a `&lever`0 on the wall. Would you like to Pull it or Leave it?");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-1));
		addnav("Pull Lever","runmodule.php?module=signetd1&op=899b");
	}
	if ($op=="899b"){	
		output("A loud alarm begins ringing and you hear the approach of orcs!");
		addnav("Continue","runmodule.php?module=signetd1&op=orcs");		
	}
	if ($op=="891"){
		output("You see a `&button`0 on the wall. Would you like to Push it or Leave it?");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+1));
		addnav("Push Button","runmodule.php?module=signetd1&op=891b");
	}
	if ($op=="891b"){
		$allprefs['loc891']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("You hear a noise in the distance.");
		addnav("Continue","runmodule.php?module=signetd1&loc=".$temp);		
	}
	if ($op=="1136"){
		output("There is a `)gate`0 which you cannot open no matter what you try.");
		addnav("Continue","runmodule.php?module=signetd1&loc=".($temp+1));		
	}
	if ($op=="1197"){
		output("There are about `^250 gold`0 pieces here that appear to be coated with liquid.");
		addnav("Take Them","runmodule.php?module=signetd1&op=1197b");
		addnav("Leave Them","runmodule.php?module=signetd1&loc=".($temp+34));
	}
	if ($op=="1197b"){	
		output("As you approach the `^gold`0 it fades and you are attacked by a Giant Ant!");
		addnav("Continue","runmodule.php?module=signetd1&op=ant");		
	}
	if ($op=="1199"){	
		output("You find a trap.  Would you like to disarm it?");
		addnav("Disarm","runmodule.php?module=signetd1&op=1199b");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-34));	
	}
	if ($op=="1199b"){	
		output("You approach the trap and attempt to disarm it.`n`n");
		//Higher level characters will be more successful
		if($session['user']['level']>10) $trap=5;
		elseif($session['user']['level']>5) $trap=6;
		else $trap=7;
		switch(e_rand(1,$trap)){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				output("You make easy work of the trap and quickly disarm it.  You look around for your prize.");
				output("`n`nThen, you find a little note on the bottom of the trap:`n`n");
				output("`c`5`bPractice Traps by Grim's Thieving House`b`n");
				output("Need some practice safely disarming traps?  Visit our shop.  Bring a full wallet!`c");
				output("`0`n`nOkay, that was odd.");
				$allprefs['loc1199']=1;
				addnav("Continue","runmodule.php?module=signetd1&loc=".$temp);
			break;
			case 6:
				output("Oh no! You've failed to disarm the trap!`n`n`@");
				switch(e_rand(1,5)){
				case 1:
				case 2:
				case 3:
				case 4:
					$hitpoints=round($session['user']['hitpoints']*.05);
					output("A mechanical hand pops up and slaps you.  It doesn't really hurt much, but you feel kind of silly.");
					if ($hitpoints>0) output("`n`nYou `\$lose %s %s`@.",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
					$session['user']['hitpoints']-=$hitpoints;
				break;
				case 5:
					$hitpoints=round($session['user']['hitpoints']*.1);
					$session['user']['hitpoints']-=$hitpoints;
					output("A mechanical hand pops up and slaps you `#TWICE`@!  How insulting! Before you can grab it, the hands ducks back into the trap.");
					output("`n`nYou hear a strange mechanical sound; almost like something is laughing at you.`n`n");
					if ($session['user']['turns']>0){
						output("You `blose a turn`b shaking the box in frustration.`n`n");
						$session['user']['turns']--;
					}
					if ($hitpoints>0) output("You `\$lose %s %s`@.",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				break;
				}
				addnav("Continue","runmodule.php?module=signetd1&loc=".($temp-34));
			break;
			case 7:
				output("Oh no! You've failed to disarm the trap!`n`n`@");
				if ($session['user']['gold']>100){
					$session['user']['gold']-=100;
					output("You watch as a mechanical hand comes out of the box.  It finds your money pouch, `^steals 100 gold`@, and quickly disappears back into the box.`n");
				}elseif ($session['user']['gold']>0){
					$session['user']['gold']=0;
					output("A mechanical hand comes out of the box and steals all your gold.  Now THAT is an `\$evil`@ trap!");
				}else{
					output("`@You fail to disarm the trap.  You close your eyes in anticipation of the gruesome consequences.  Moments pass and nothing happens.");
					output("`n`nOkay, that was odd.");
					if ($session['user']['turns']>0){
						output("`n`n`@You sit in stunned silence trying to figure out what just happened.  You `blose one turn`b sitting in stunned silence.");
						$session['user']['turns']--;
					}
				}
				addnav("Continue","runmodule.php?module=signetd1&loc=".($temp-34));
			break;
		}
	}
	if ($op=="685"){	
		output("You see a `&letter`0 sitting on a table.  What would you like to do?");
		addnav("Read it","runmodule.php?module=signetd1&op=685c");
		addnav("Burn it","runmodule.php?module=signetd1&op=685b");
		addnav("Leave it","runmodule.php?module=signetd1&loc=".($temp+34));	
	}
	if ($op=="685c"){	
		output("You carefully open the letter with deep reverence. You try to work out the message, but at best you can figure out that it says something in Orcish.");
		output("`n`nYou can't make all of it out, but it says something about a 'valve'.");
		addnav("Continue","runmodule.php?module=signetd1&loc=".$temp);
		$allprefs['loc685']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="685b"){	
		output("Fearing that the regular old routine run-of-the-mill piece of paper has mystical powers, you `^b`Qu`\$r`Qn`0 it.`n`n");
		output("Upon reflection, you wonder what the paper said.  Perhaps it was important.  I guess you'll never know.");
		addnav("Continue","runmodule.php?module=signetd1&loc=".$temp);
		$allprefs['loc685']=1;
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op=="759"){
		output("You see a trap ahead.  Would you like to disarm it?");
		addnav("Disarm","runmodule.php?module=signetd1&op=759b");
		if ($temp==793) addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+34));	
		elseif ($temp==760) addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+1));	
		elseif ($temp==725) addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-34));	
	}
	if ($op=="759b"){
		$level=$session['user']['level'];
		$traph=10;
		$trapl=1;
		if($level>12) $trapl=4;
		elseif($level>9) $trapl=3;
		elseif($level>6) $trapl=2;
		elseif($level>3) $trapl=1;
		else $traph=9;
		switch(e_rand($trapl,$traph)){
			case 1:
				if ($session['user']['turns']>0){
					output("`@You hear scary noises behind you.  You look around, and your anxiety causes you to lose your concentration.  You `blose one turn`b sitting in stunned silence.`n`n");
					$session['user']['turns']--;
				}
			case 2:
				$hitpoints=round($session['user']['hitpoints']*.04);
				if ($hitpoints>0) output("`@A board falls from the wall and hits you on the head. You `\$lose %s %s`@.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				$session['user']['hitpoints']-=$hitpoints;
			case 3:
				if ($session['user']['gold']>1000){
					output("`@You scratch your head trying to figure out the trap and look around for a hint.  You find a sack full of gold!`n`n");
					output("You gain `^500 gold`@!`n`n");
					$session['user']['gold']+=500;
				}
			case 4:
				output("You're unable to disarm the trap.`n`n");
				$hitpoints=round($session['user']['hitpoints']*.06);
				if ($hitpoints>0) output("A cloud of poison gas explodes from the trap.  You struggle to breath as you feel a burn in your lungs. You `\$lose %s %s`@.`n`n",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				$session['user']['hitpoints']-=$hitpoints;
			break;
			case 5:
				if ($session['user']['turns']>5){
					output("`@Your concentration is amazing.  It makes you so efficient that you actually GAIN a turn doing a task!`n`n");
					$session['user']['turns']++;
				}	
			case 6:
			case 7:
			case 8:
			case 9:
				output("You successfully disarm the trap.`n`n");
				$allprefs['loc759']=1;
				set_module_pref('pqtemp',759);
			break;
			case 10:
				output("You successfully disarm the trap.`n`n");
				$allprefs['loc759']=1;
				set_module_pref('pqtemp',759);
			break;
		}
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd1&loc=".get_module_pref('pqtemp'));
	}
	if ($op=="677"){	
		output("There is a huge `)iron`0 door here.");
		addnav("Open it","runmodule.php?module=signetd1&op=677b");
		if ($allprefs['loc711']==1) addnav("Leave it","runmodule.php?module=signetd1&loc=".($temp+34));	
		else addnav("Leave it","runmodule.php?module=signetd1&loc=".($temp-34));	
	}
	if ($op=="677b"){
		$allprefs['loc677']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("The door pulls you south, closes, and then locks!");
		addnav("Continue","runmodule.php?module=signetd1&loc=".($temp-34));
	}
	if ($op=="673"){	
		output("You see some very surprised Orcs!");
		addnav("Continue","runmodule.php?module=signetd1&op=orcs");		
	}
	if ($op=="494"){	
		output("You come to the door and hear monsters on the other side.  Do you continue?");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+1));
		addnav("Continue","runmodule.php?module=signetd1&op=rangers");		
	}
	if ($op=="593"){	
		output("You come to the door and hear `\$monsters`0 on the other side.  Do you continue?");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-34));
		addnav("Continue","runmodule.php?module=signetd1&op=scribe");		
	}
	if ($op=="465"){	
		output("You come to the door and hear `\$monsters`0 on the other side.  Do you continue?");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-1));
		addnav("Continue","runmodule.php?module=signetd1&op=kobolds");		
	}
	if ($op=="333"){
		output("You find a scroll in the `@%s`0 language.",$session['user']['race']);
		output("Would you like to take it?");
		addnav("Take It","runmodule.php?module=signetd1&op=333b");
		addnav("Leave It","runmodule.php?module=signetd1&loc=".($temp+1));
	}
	if ($op=="333b"){
		$allprefs['scroll2']=1;
		$allprefs['loc333']=1;
		set_module_pref('allprefs',serialize($allprefs));
		redirect("runmodule.php?module=signetd1&loc=".$temp);
	}
	if ($op=="303"){
		output("You see a trap ahead.  Would you like to disarm it?");
		addnav("Disarm","runmodule.php?module=signetd1&op=303b");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+34));	
	}
	if ($op=="303b"){
		$level=$session['user']['level'];
		if($level>12) $trap=5;
		elseif($level>9) $trap=4;
		elseif($level>6) $trap=3;
		elseif($level>3) $trap=2;
		else $trap=1;
		switch(e_rand($trap,10)){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
				output("You're unable to disarm the trap.");
				$hitpoints=round($session['user']['hitpoints']*.06);
				if ($hitpoints>0) output("`n`nA cloud of `@poison gas`0 explodes from the trap.  You struggle to breath as you feel a burn in your lungs. You `\$lose %s %s`@.",$hitpoints,translate_inline($hitpoints>1?"hitpoints":"hitpoint"));
				$session['user']['hitpoints']-=$hitpoints;
				increment_module_pref("pqtemp",+34);
			break;
			case 6:
			case 7:
			case 8:
			case 9:
			case 10:
				output("You successfully disarm the trap.`n`n");
				$allprefs['loc303']=1;
				set_module_pref('allprefs',serialize($allprefs));
			break;
		}
		addnav("Continue","runmodule.php?module=signetd1&loc=".$temp);
	}
	if ($op=="1133"){
		output("There is an old man sitting here.");
		if ($allprefs['loc1133b']==0) addnav("Attack Him","runmodule.php?module=signetd1&op=1133b");
		addnav("Talk to Him","runmodule.php?module=signetd1&op=1133c");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+1));	
	}
	if ($op=="1133b"){
		output("You prepare to strike the unarmed old man but suddenly deep ancient magic restrains your attack.");
		output("`n`nHe looks up you sadly and shakes his head.  You feel disgraced for attempting to attack him.");
		output("`n`nYou lose `&2 charm`0.");
		$session['user']['charm']-=2;
		$allprefs['loc1133b']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Talk to Him","runmodule.php?module=signetd1&op=1133c");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+1));	
	}
	if ($op=="1133c"){
		output("`#'There are four kinds of signets:  `QEarth`#, `3Air`#, `!Water`#, and `\$Fire`#. The signets represent great power.'");
		output("`n`n'I am the keeper of the `3Air Signet`# which I now present to you.'");
		output("`n`n`0He casts a strange spell on you.  You feel a burning sensation on your wrist and notice a strange mark.");
		output("`n`n`c`^Congratulations.  You have received the first of the `b`3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signets`b.`c");
		$allprefs['loc1133']=1;
		$allprefs['airsignet']=1;
		set_module_pref('allprefs',serialize($allprefs));
		$allprefssale=unserialize(get_module_pref('allprefssale',"signetsale"));
		set_module_pref("hoftemp",1200+$allprefssale['completednum'],"signetsale");
		addnews("`@%s`@ was marked with the `3Air Signet`@ in the `^Aria Dungeon`@.",$session['user']['name']);
		addnav("Continue","runmodule.php?module=signetd1&loc=".$temp);	
	}
	if ($op=="256"){
		output("You are washed downstream by the heavy current.");
		addnav("Continue","runmodule.php?module=signetd1&loc=".($temp+1));	
	}
	if ($op=="54"){	
		output("You come to the door and hear `\$monsters`0 on the other side.  Do you continue?");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp+1));
		addnav("Continue","runmodule.php?module=signetd1&op=dripslimes");	
	}
	if ($op=="411"){
		output("There is a lever here. A sign reads:");
		output("`n`n`c`\$USE ONLY IN EMERGENCIES`c`n");
		addnav("Pull Lever","runmodule.php?module=signetd1&op=411b");
		addnav("Leave","runmodule.php?module=signetd1&loc=".($temp-34));
	}
	if ($op=="411b"){
		output("You hear a noise in the distance.");
		$allprefs['loc411']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("Continue","runmodule.php?module=signetd1&loc=".$temp);	
	}
	if ($op=="351"){
		output("You are washed downstream by the heavy current.");
		addnav("Continue","runmodule.php?module=signetd1&loc=".($temp+1));	
	}
	if ($op=="113"){
		output("You search through a desk and find `^126 gold`0 and `%a gem`0.`n`n");
		output("The only other item of interest is a scroll on a desk.");
		output("Would you like to take it?");
		$allprefs['loc113']=1;
		set_module_pref('allprefs',serialize($allprefs));
		$session['user']['gold']+=126;
		$session['user']['gems']++;
		addnav("Take It","runmodule.php?module=signetd1&op=113b");
		addnav("Leave It","runmodule.php?module=signetd1&loc=".($temp+34));
	}
	if ($op=="113c"){
		output("You see a scroll on the desk.");
		output("`n`nWould you like to take it?");
		addnav("Take It","runmodule.php?module=signetd1&op=113b");
		addnav("Leave It","runmodule.php?module=signetd1&loc=".($temp+34));
	}
	if ($op=="113b"){
		$allprefs['loc113b']=1;
		$allprefs['scroll3']=1;
		set_module_pref('allprefs',serialize($allprefs));
		redirect("runmodule.php?module=signetd1&loc=".$temp);
	}
	if ($op=="scroll1b"){
		output("`c`b`^The Aria Dungeon`0`c`b`n");
		output("The Aria Dungeon was once the center of a small community of dwarves.  About 15 years ago, a band of orcs invaded the community and defeated the dwarves.");
		output("`n`nIt is said that only the leader of the dwarves, Kilmor, and a few others escaped from the Orcs.");
		addnav("Return","runmodule.php?module=signetd1&loc=".$temp);
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
		addnav("Return","runmodule.php?module=signetd1&loc=".$temp);
	}
	if ($op=="scroll3b"){
		output("`c`b`^Prison Notes by Kilmor`0`c`b`n");
		output("The Orcs are less than wonderful captors. I hope to be able to break free soon.");
		output("My first journey will be to the `^Temple of the Aarde Priests`0.  It is rumored that the high priest of the temple has the power of prophesy, but few have been allowed to see him.");
		addnav("Return","runmodule.php?module=signetd1&loc=".$temp);
	}
	//Scrolls for in the bio start here
	if ($op=="scroll2"){
		$userid = httpget("user");
		output("`c`b`^The Historical Scrolls of the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signets`0`c`b`n");
		output("Although there had until recently been general peace across the land, the evil sorcerer Mierscri has brought his great army of men and beasts to terrorize the land.");
		output("This great force laid waste to the land and its people. Soon, the battle would be over.");
		output("`n`nHowever, before this could happen, a coalition of the four greatest wizards of the land came together to end the reign of evil.");
		output("The wizards created 4 signets; each representing the great forces of nature:  `3Air`0, `QEarth`0, `!Water`0, and `\$Fire`0.");
		output("When one warrior was able to harness the power of these four signets, then the evil could be stopped.");
		output("`n`nMierscri learned of the plan and captured the four wizards before a warrior could be chosen to receive the signets.  His evil spells turned the wizards into Warlocks of great power but completely under his control.");
		output("`n`nPerhaps one day a warrior will be able to gather the four signets in order to finally defeat the evil Mierscri.");
		addnav("Return to Signets","runmodule.php?module=signetsale&op=signetnotes&user=$userid");
	}
	if ($op=="scroll3"){
		$userid = httpget("user");
		output("`c`b`^Prison Notes by Kilmor`0`c`b`n");
		output("The Orcs are less than wonderful captors. I hope to be able to break free soon.");
		output("My first journey will be to the `^Temple of the Aarde Priests`0.  It is rumored that the high priest of the temple has the power of prophesy, but few have been allowed to see him.");
		addnav("Return to Signets","runmodule.php?module=signetsale&op=signetnotes&user=$userid");
	}
	page_footer();
}
?>