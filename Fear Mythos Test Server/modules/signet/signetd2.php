<?php
	global $session;
	global $badguy;
	//most of this is modification of the abandoned castle module by Lonnyl,
	//and I couldn't have coded this without using his work as a template.
	$op = httpget('op');
	$locale = httpget('loc');
	$skill = httpget('skill');
	$knownmonsters = array('door','mgoblins','trapdoor','orcs','barbarian','troll','oldman','beetle','bee','centipede','rat','guards','vampire','bvampire','scribe','apdevil','dingo');
	if (in_array($op, $knownmonsters) || $op == "fight" || $op == "run") {
		signetd2_fight($op);
		die;
	}
	$misc= array ('superuser','scroll1b','scroll2b','scroll3b','1098','1098b','1098c','1098d','1098e','1098f','1098g','1148','1148b','1082','1082b','1012','1010','1010b','1010c','843','843b','776','776b','scroll4b','scroll4','841','841b','799','556','556b','556c','496','496b','386','386b','163','163b','537','537b','537c','334','334b','334c','334d','334e','334f','334g','334h','381','279','279b','279c','279d','109','109b','109c','109d','lcoff','lcoffb','rcoff','rcoffb','vamp','vampb','685','685b','reset');
	if (in_array($op,$misc)){
		signetd2_misc($op);
	}
	page_header("Aarde Temple");
	if ($session['user']['hitpoints'] <= 0) redirect("shades.php");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$umaze = get_module_pref('maze');
	$umazeturn = $allprefs['mazeturn'];
	$upqtemp = get_module_pref('pqtemp');
	if ($op == "" && $locale == "") {
		output("`c`b`&Aarde Priests Temple`0`b`c`n");
		//to complete:  Gather scroll, defeat the Huge Vampire, Collect the Earth Signet, and Talk to the Old Gnome
		if ($allprefs['scroll4']==1 && $allprefs['loc685']==1 && $allprefs['earthsignet']==1 && $allprefs['loc334']==1){
			output("`\$You have completed this dungeon and this will be your last visit to it. You may now leave at any time.`0");
			$allprefs['complete']=1;
			$allprefssale=unserialize(get_module_pref('allprefssale',"signetsale"));
			set_module_pref("hoftemp",2300+$allprefssale['completednum'],"signetsale");
		}else{
			$allprefs['header']=0;
			output("`2The Ancient Temple doesn't seem very busy right now, but you have a sense of awe and experience a mystical tranquility just by entering.");
		}
		$locale=1273;
		$umazeturn = 0;
		$allprefs['mazeturn']=0;
		if (!isset($maze)){
			$maze = array(16,16,16,16,16,16,16,16,16,16,16,16,16,1,16,16,16,1,16,16,6,10,10,10,7,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,11,10,10,10,15,7,16,5,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,6,12,16,16,16,8,15,10,14,7,16,16,11,13,7,16,16,16,16,16,16,16,16,16,16,16,6,13,13,13,7,16,16,16,11,9,16,16,16,16,2,16,16,5,16,16,8,14,15,4,16,16,16,16,16,16,16,16,16,16,11,15,15,15,12,16,16,16,5,16,16,16,16,16,16,16,16,8,4,16,16,16,2,16,16,16,16,16,16,16,16,16,16,16,8,14,15,14,12,16,16,6,9,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,19,16,16,5,16,16,16,6,10,10,10,10,10,10,10,13,10,10,10,4,16,16,16,16,16,16,16,16,16,16,16,16,5,16,19,16,16,8,7,16,16,5,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,19,16,16,16,19,16,16,5,16,16,16,16,16,16,16,5,16,6,13,7,16,16,16,16,16,16,16,16,16,16,16,6,15,13,22,16,16,16,19,16,16,5,16,6,13,13,4,16,16,5,16,11,15,12,16,16,16,16,16,16,16,16,16,16,16,8,15,9,16,16,16,16,19,16,16,5,16,11,15,12,16,16,16,11,10,15,15,12,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,6,15,7,16,11,10,15,15,12,16,16,16,5,16,8,14,9,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,11,15,15,10,12,16,8,14,9,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,8,14,9,16,5,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,6,15,7,16,16,16,16,16,16,16,11,10,13,13,7,16,16,16,11,10,13,7,16,16,16,16,16,16,16,16,16,16,16,3,15,15,15,4,16,16,6,13,10,10,12,16,11,15,12,16,16,16,5,16,11,12,16,16,16,16,16,16,16,16,16,16,16,16,8,14,9,16,16,16,8,9,16,16,2,16,8,14,9,16,16,16,2,16,8,9,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,6,13,7,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,8,15,9,16,16,16,16,16,16,1,16,1,16,1,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,20,10,15,10,15,10,15,10,21,16,16,16,16,6,7,16,6,7,16,16,16,16,16,16,16,16,16,6,15,7,16,16,16,16,19,16,2,16,5,16,2,16,19,16,16,16,16,8,12,16,11,9,16,16,16,16,16,16,16,16,16,11,15,12,16,16,16,16,19,16,16,16,5,16,16,16,19,16,16,16,16,16,5,16,5,16,16,16,16,16,16,16,16,16,16,11,15,12,16,16,16,16,19,16,16,16,5,16,16,16,19,16,16,20,17,17,15,13,12,16,16,16,16,16,16,16,16,16,16,11,15,12,16,16,16,6,12,16,6,13,14,13,7,16,11,7,16,19,16,16,8,14,12,16,6,7,16,16,16,16,16,16,16,11,15,12,16,16,16,11,12,16,11,12,16,11,12,16,11,15,17,22,16,16,16,16,11,10,15,12,16,16,16,16,16,16,16,11,15,12,16,16,16,8,14,10,15,15,13,15,15,10,14,9,16,16,16,6,7,16,5,16,8,9,16,16,16,16,16,16,16,11,15,12,16,16,16,16,16,16,11,15,15,15,12,16,16,16,16,16,16,11,15,10,12,16,16,16,16,16,16,16,16,16,16,11,15,12,16,16,16,6,13,10,15,15,14,15,15,10,13,7,16,16,16,8,9,16,5,16,6,7,16,16,16,16,16,16,16,8,15,9,16,16,16,11,12,16,11,12,16,11,12,16,11,12,16,16,16,16,16,16,11,10,15,12,16,16,16,16,16,16,16,16,5,16,16,16,16,8,9,16,8,14,13,14,9,16,8,9,16,16,16,6,7,16,5,16,8,9,16,16,16,16,16,16,16,6,15,7,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,11,15,10,12,16,16,16,16,16,16,16,16,16,3,15,15,15,4,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,8,9,16,5,16,16,16,16,16,16,16,16,16,16,8,14,9,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,2,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,2,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16);
			$umaze = implode($maze,",");
			set_module_pref("maze", $umaze);
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op <> ""){
		if ($op == "n") {
			$locale+=34;
			redirect("runmodule.php?module=signetd2&loc=$locale");
		}
		if ($op == "s"){
			$locale-=34;
			redirect("runmodule.php?module=signetd2&loc=$locale");
		}
		if ($op == "w"){
			$locale-=1;
			redirect("runmodule.php?module=signetd2&loc=$locale");
		}
		if ($op == "e"){
			$locale+=1;
			redirect("runmodule.php?module=signetd2&loc=$locale");
		}
	}else{
		if ($locale <> ""){
			$maze=explode(",", $umaze);
			if ($locale=="") $locale = $upqtemp;
			$upqtemp = $locale;
			set_module_pref("pqtemp", $upqtemp);
			for ($i=0;$i<$locale-1;$i++){
			}
			$navigate=ltrim($maze[$i]);
			output("`7");
			if ($session['user']['hitpoints'] > 0){
				if (get_module_pref("super","signetsale")==1 || $locale=="1273" || $allprefs['complete']==1) villagenav();
				
				if ($locale=="279"||$locale=="211"||$locale=="1239"|| $locale=="1137") $allprefs['header']=1;
				elseif ($locale=="899" ||$locale=="938" ||$locale=="1037" ||$locale=="969" ||$locale=="965" ||$locale=="1103" ||$locale=="1033") $allprefs['header']=2;
				elseif ($locale=="1038" ||  $locale=="1032" ||  $locale=="964" ||  $locale=="970") $allprefs['header']=3;
				elseif ($locale=="1148" || $locale=="1082" || $locale=="1012" || $locale=="946" || $locale=="843") $allprefs['header']=4;
				elseif ($locale=="519" ||$locale=="232" ||$locale=="763" || $locale=="1163" || $locale=="841") $allprefs['header']=5;
				elseif ($locale=="797") $allprefs['header']=6;
				elseif ($locale=="877" ||$locale=="875" ||$locale=="911") $allprefs['header']=7;
				elseif ($locale=="381" ||$locale=="449" ||$locale=="364" ||$locale=="500" ||$locale=="568" ||$locale=="731" ||$locale=="390" ||$locale=="424" ||$locale=="492" ||$locale=="526" ||$locale=="729" ||$locale=="727" ||$locale=="831" ||$locale=="865" ||$locale=="1251" ||$locale=="1149" ||$locale=="1081" ||$locale=="1013" ||$locale=="945" || $locale=="560") $allprefs['header']=8;
				elseif ($locale=="523") $allprefs['header']=9;
				elseif ($locale=="493") $allprefs['header']=10;
				elseif ($locale=="423"||$locale=="387") $allprefs['header']=11;
				elseif ($locale=="353") $allprefs['header']=12;
				elseif ($locale=="391") $allprefs['header']=13;
				elseif ($locale=="501") $allprefs['header']=14;
				elseif ($locale=="365") $allprefs['header']=15;
				elseif ($locale=="719") $allprefs['header']=16;
				elseif ($locale=="874" ||$locale=="939") $allprefs['header']=17;
				elseif ($locale=="753" ||$locale=="1059") $allprefs['header']=18;
				elseif ($locale=="347" ||$locale=="313") $allprefs['header']=19;
				elseif ($locale=="177") $allprefs['header']=20;
				elseif ($locale=="483" ||$locale=="799" ||$locale=="524" ||$locale=="525" ||$locale=="1093"||$locale=="795"||$locale=="1159") $allprefs['header']=0;
				$title=array("","An Elegant Marble Hallway","Small Elegant Temple of Marble","Priest's Room with Little of Value","Small Stone Cell","Teleportation Room","The Passages Ahead Contain Many `)Black`^ Doorways.","An Empty Stone Room","Hallway Carved from Stone","A Strange Misty Room","The Maid's Bedroom","The Janitor's Room","A Natural Cavern","A Storage Room with Little of Value","The Bathroom","A Small Neat Kitchen","A Small Crypt","A Secret Passageway","A Mausoleum","A Pleasant Atrium","The Sacred Chapel");
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				//$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
				$header=$allprefs['header'];
				output_notl("`b`^`c%s`b`c`0",translate_inline($title[$header]));
				if ($header==0) output("`n");

				if ($locale=="1093") output("To the south there is a large room with many coffins along the walls.`n");
				elseif ($locale=="1108") output("A Priest here says to you `#'Welcome to the temple of the Aarde Priests.  Use the teleporters to look around if you wish.'`0");
				elseif ($locale=="894") output("A priest sitting here says `#'The High priest does not speak to the unworthy.'`0`n");
				elseif ($locale=="904") output("A priest sitting here says `#'We have very few secrets.'`0`n");
				elseif ($locale=="525") output("A sign on the door reads `q'The Keeper of the Earth Rune'`0`n");
				elseif ((($locale=="969" || $locale=="971") && $allprefs['loc970']==0) || (($locale=="899" || $locale=="831") && $allprefs['loc865']==0)) output("You see a stubborn door in front of you.`n");
				else output("`n");

				if($locale=="1098") redirect("runmodule.php?module=signetd2&op=1098");
				elseif ($allprefsd1['complete']==1 && $allprefsd1['reset']==0) redirect("runmodule.php?module=signetd2&op=reset");
				elseif (($locale=="970" && $allprefs['loc970']==0) || ($locale=="865" && $allprefs['loc865']==0) || ($locale=="946" && $allprefs['loc946']==0)) redirect("runmodule.php?module=signetd2&op=door");
				elseif ($locale=="1148" && $allprefs['loc1148']==0) redirect("runmodule.php?module=signetd2&op=1148");
				elseif ($locale=="1082" && $allprefs['loc1082']==0) redirect("runmodule.php?module=signetd2&op=1082");
				elseif ($locale=="1012" && $allprefs['loc1012']==0) redirect("runmodule.php?module=signetd2&op=1012");
				elseif ($locale=="1010" && $allprefs['loc1010']==0 && $allprefs['loc1010b']==0) redirect("runmodule.php?module=signetd2&op=1010");
				elseif ($locale=="1147" && $allprefs['loc1147']==0) redirect("runmodule.php?module=signetd2&op=mgoblins");
				elseif ($locale=="947" && $allprefs['loc947']==0) redirect("runmodule.php?module=signetd2&op=barbarian");
				elseif ($locale=="843" && $allprefs['loc1010b']==1 && $allprefs['loc843']==0) redirect("runmodule.php?module=signetd2&op=843b");
				elseif ($locale=="843" && $allprefs['loc843']==0) redirect("runmodule.php?module=signetd2&op=843");
				elseif ($locale=="776" && $allprefs['loc776']==0) redirect("runmodule.php?module=signetd2&op=776");
				elseif ($locale=="841" && $allprefs['loc841']==0) redirect("runmodule.php?module=signetd2&op=841");
				elseif ($locale=="799" && $allprefs['loc1098']==0) redirect("runmodule.php?module=signetd2&op=799");
				elseif ($locale=="556" && $allprefs['loc556']==0) redirect("runmodule.php?module=signetd2&op=556");
				elseif ($locale=="496" && $allprefs['loc496']==0) redirect("runmodule.php?module=signetd2&op=496");
				elseif ($locale=="386" && $allprefs['loc386']==0) redirect("runmodule.php?module=signetd2&op=386");
				elseif ($locale=="82" && $allprefs['loc82']==0) redirect("runmodule.php?module=signetd2&op=beetle");
				elseif ($locale=="55" && $allprefs['loc55']==0) redirect("runmodule.php?module=signetd2&op=bee");
				elseif ($locale=="59" && $allprefs['loc59']==0) redirect("runmodule.php?module=signetd2&op=centipede");
				elseif ($locale=="327" && $allprefs['loc327']==0) redirect("runmodule.php?module=signetd2&op=centipede");
				elseif ($locale=="163" && $allprefs['loc163']==0) redirect("runmodule.php?module=signetd2&op=163");
				elseif ($locale=="537") redirect("runmodule.php?module=signetd2&op=537");
				elseif ($locale=="334" && $allprefs['loc334']==0) redirect("runmodule.php?module=signetd2&op=334");
				elseif ($locale=="381" && $allprefs['loc381']==0) redirect("runmodule.php?module=signetd2&op=381");
				elseif ($locale=="279" && $allprefs['loc279']==0 && $allprefs['loc109']==0) redirect("runmodule.php?module=signetd2&op=279");
				elseif ($locale=="279") redirect("runmodule.php?module=signetd2&op=279c");
				elseif ($locale=="109") redirect("runmodule.php?module=signetd2&op=109");
				elseif ($locale=="1024" || ($locale=="956" && $allprefs['loc956']==1) || $locale=="888" || $locale=="820") redirect("runmodule.php?module=signetd2&op=lcoff");
				elseif (($locale=="1026" && $allprefs['loc1026']==1) || $locale=="958" || ($locale=="890" && $allprefs['loc890']==1) || ($locale=="822" && $allprefs['loc822']==1)) redirect("runmodule.php?module=signetd2&op=rcoff");
				elseif (($locale=="956" && $allprefs['loc956']==0) || ($locale=="1026" && $allprefs['loc1026']==0) || ($locale=="890" && $allprefs['loc890']==0) || ($locale=="822" && $allprefs['loc822']==0))redirect("runmodule.php?module=signetd2&op=vamp");
				elseif ($locale=="685") redirect("runmodule.php?module=signetd2&op=685");
				elseif ($locale=="1163" || $locale=="773" || $locale=="795" || $locale=="727" || $locale=="729" || $locale=="799" || $locale=="519" || $locale=="731" || $locale=="232"){
					output("You are teleported.");
					if ($locale=="1163" || $locale=="773" || $locale=="519" || $locale=="232") $locale="763";
					if ($locale=="795") $locale="1159";
					if ($locale=="727") $locale="1251";
					if ($locale=="729") $locale="560";
					if ($locale=="799") $locale="515";
					if ($locale=="731") $locale="568";
					
					$upqtemp = $locale;
					set_module_pref("pqtemp", $upqtemp);
					for ($i=0;$i<$locale-1;$i++){
					}
					$navigate=ltrim($maze[$i]);
				}elseif ($allprefs['randomp']<=get_module_setting("random")){
					switch(e_rand(1,30)){
						case 1: case 2: case 3: case 4: case 5: case 6:  case 7: case 8: case 9: case 10:
						case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19: case 20:
						case 21: case 22: case 23: case 24: case 25: case 26: case 27: case 28: case 29:
						break;
						case 30:
							$randenc=get_module_setting("randenc");
							switch(e_rand(1,$randenc)){
								case 1:
									$allprefs['randomp']=$allprefs['randomp']+1;
									set_module_pref('allprefs',serialize($allprefs));
									switch(e_rand(1,11)){
										case 1:
											redirect("runmodule.php?module=signetd2&op=orcs");
										break;
										case 2:
											redirect("runmodule.php?module=signetd2&op=barbarian");
										break;
										case 3:
											redirect("runmodule.php?module=signetd2&op=troll");
										break;
										case 4:
											redirect("runmodule.php?module=signetd2&op=beetle");
										break;
										case 5:
											redirect("runmodule.php?module=signetd2&op=bee");
										break;
										case 6:
											redirect("runmodule.php?module=signetd2&op=centipede");
										break;
										case 7:
											redirect("runmodule.php?module=signetd2&op=rat");	
										break;
										case 8:
											redirect("runmodule.php?module=signetd2&op=vampire");
										break;
										case 9:
											redirect("runmodule.php?module=signetd2&op=scribe");
										break;
										case 10:
											redirect("runmodule.php?module=signetd2&op=apdevil");
										break;
										case 11:
											redirect("runmodule.php?module=signetd2&op=dingo");
										break;
									}
								break;
								case 2: case 3: case 4: case 5: case 6: case 7: case 8: case 9: case 10:
								case 11: case 12: case 13: case 14: case 15: case 16: case 17: case 18: case 19: case 20:
								break;
							}
						break;
					}
				}
				$navcount = 0;
				$north = translate_inline("North");
				$south = translate_inline("South");
				$east = translate_inline("East");
				$west = translate_inline("West");
				$directions="";
				$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
				$allprefs=unserialize(get_module_pref('allprefs'));
				addnav("Scrolls");
				addnav("1?Read Scroll 1","runmodule.php?module=signetd2&op=scroll1b");
				if ($allprefsd1['scroll2']==1) addnav("2?Read Scroll 2","runmodule.php?module=signetd2&op=scroll2b");
				if ($allprefsd1['scroll3']==1) addnav("3?Read Scroll 3","runmodule.php?module=signetd2&op=scroll3b");
				if ($allprefs['scroll4']==1) addnav("4?Read Scroll 4","runmodule.php?module=signetd2&op=scroll4b");
				addnav("Directions");
				if($locale=="1273"){
					output("`nYou are at the entrance with Passages to the");
				}else{
					output("`nYou may go");
				}
				$umazeturn++;
				$allprefs['mazeturn']=$umazeturn;
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($navigate=="1" or $navigate=="5" or $navigate=="6"or $navigate=="7" or $navigate=="11" or $navigate=="12"or $navigate=="13" or $navigate=="15" or $navigate=="19"or $navigate=="20" or $navigate=="21") {
					addnav("North","runmodule.php?module=signetd2&op=n&loc=$locale");
					$directions.=" $north";
					$navcount++;
				}
				if ($navigate=="2" or $navigate=="5" or $navigate=="8"or $navigate=="9" or $navigate=="11" or $navigate=="12"or $navigate=="14" or $navigate=="15" or $navigate=="18" or $navigate=="19" or $navigate=="22") {
					addnav("South","runmodule.php?module=signetd2&op=s&loc=$locale");
					if ($allprefs['loc386']==0  && $locale=="387") {
						blocknav("runmodule.php?module=signetd2&op=s&loc=$locale");
					}else{
						$navcount++;
						if ($navcount > 1) $directions.=",";
						$directions.=" $south";						
					}
				}
				if ($navigate=="4" or $navigate=="7" or $navigate=="9"or $navigate=="10" or $navigate=="12" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="21" or $navigate=="22") {
					addnav("West","runmodule.php?module=signetd2&op=w&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $west";
				}
				if ($navigate=="3" or $navigate=="6" or $navigate=="8"or $navigate=="10" or $navigate=="11" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="18" or $navigate=="20") {
					addnav("East","runmodule.php?module=signetd2&op=e&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $east";
				}
				output_notl(" %s.",$directions);			
			}else{
				addnav("Continue","shades.php");
			}
			$mazemap=$navigate;
			$mazemap.="maze.gif";
			output_notl("`n`c");
			rawoutput("<small>");
			output("`7You");
			rawoutput(" = <img src=\"./modules/signetimg/mcyan.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\"> ");
			output("`7Entrance");
			rawoutput(" = <img src=\"./modules/signetimg/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\">");
			//$mapkey2="<table style=\"height: 130px; width: 110px; text-align: left;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td style=\"vertical-align: top;\">";
			rawoutput("<table style=\"height: 130px; width: 350px; text-align: absmiddle; line-height: 10px; font-size: 8px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td colspan=\"34\"></td>");
			$mapkey="";
			for ($i=0;$i<1326;$i++){
				$keymap=ltrim($maze[$i]);
				$mazemap=$keymap;
				$mazemap.="maze.gif";
				if ($keymap < 16 or $keymap==26) $mazebg="CCCCCC";
				else $mazebg="000000";
				if ($i==$locale-1){
					//$mapkey.="<img src=\"./modules/signetimg/mcyan.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
					$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px; background-color: 00FFDD;\">&nbsp;</td>";
				}else{
					if ($i==1272){
						//$mapkey.="<img src=\"./modules/signetimg/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px; background-color: 26FF00;\">&nbsp;</td>";
					//ud door
					}elseif (($i==1136 && ($locale=="1138" || $locale=="1136")) || ($i==1037 && ($locale=="1037" || $locale=="1139")) || ($i==969 && $allprefs['loc970']==0 && ($locale=="969" || $locale=="971")) || ($i==963 && ($locale=="963" || $locale=="965")) || ($i==1031 && ($locale=="1031" || $locale=="1033"))||($i==492 && ($locale=="492"|| $locale=="494"))||($i==422 && ($locale=="422"|| $locale=="424"))){
						//$mapkey.="<img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//ud door
					}elseif (($i==1081 && ($locale=="1081" || $locale=="1083"))|| ($i==1147 && ($locale=="1149" || $locale=="1147"))|| ($i==1011 && ($locale=="1011" || $locale=="1013"))||($i==500 && ($locale=="500" || $locale=="502"))|| ($i==364 && ($locale=="364" || $locale=="366"))||($i==945 && $locale=="945" && $allprefs['loc946']==0)||($i==523 && ($locale=="523"|| $locale=="525"))||($i==390 && ($locale=="390"|| $locale=="392"))){
						//$mapkey.="<img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//lr door
					}elseif (($i==864 && ($locale=="899" || $locale=="831") && $allprefs['loc865']==0)||($i==842 && ($locale=="877" || $locale=="809"))||($i==840 && ($locale=="875" || $locale=="807"))||($i==380 && ($locale=="415" || $locale=="347"))||($i==278 && ($locale=="313" || $locale=="245"))||($i==752 && ($locale=="787" || $locale=="719"))){
						//$mapkey.="<img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//red
					}elseif (($i==1107 && ($locale=="1107" || $locale=="1073" || $locale=="1074")) || ($i==1097 && ($locale=="1099" || $locale=="1064" || $locale=="1065")) || ($i==893 && ($locale=="895" || $locale=="929" || $locale=="928")) || ($i==1107 && ($locale=="1107" || $locale=="1074" || $locale=="1073")) || ($i==903 && ($locale=="903" || $locale=="938" || $locale=="937")) || ($i==1162 && $locale=="1162")){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//red
					}elseif (($i==1009 && $allprefs['loc1010']==0 && $allprefs['loc1010b']==0 && ($locale=="1011" || $locale=="977" || $locale=="976" || $locale=="1045" || $locale=="1044"))|| ($i==333 && $allprefs['loc334']==0 && ($locale=="299" || $locale=="333" || $locale=="300" || $locale=="367" || $locale=="368"))||($i==728 && $locale=="763")||(($i==794 || $i==726) && $locale=="761") || (($i==798 || $i==730) && $locale=="765")){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//red
					}elseif (($i==775 && ($locale=="775" || $locale=="809" || $locale=="810") && $allprefs['loc776']==0)||($i==555 && ($locale=="557" || $locale=="522" || $locale=="523") && $allprefs['loc556']==0)||($i==108 && ($locale=="142" || $locale=="143" || $locale=="144" || $locale=="108" || $locale=="110"))||($i==772 && ($locale=="772" || $locale=="806" || $locale=="807"))||($i==495 && ($locale=="495" || $locale=="529" || $locale=="530") && $allprefs['loc496']==0)){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//red
					}elseif (($i==536 && ($locale=="536" || $locale=="570" || $locale=="571" || $locale=="502" || $locale=="503") && $allprefs['loc537']==0)||($i==385 && ($locale=="387" || $locale=="420" || $locale=="421") && $allprefs['loc386']==0)||($i==518 && $locale=="518")||($i==1023 && ($locale=="1058" || $locale=="1059" || $locale=="1025" || $locale=="990" || $locale=="991"))||($i==1025 && ($locale=="1060" || $locale=="1059" || $locale=="1025" || $locale=="992" || $locale=="991"))){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//red
					}elseif (($i==955 && ($locale=="990" || $locale=="991" || $locale=="957" || $locale=="922" || $locale=="923"))||($i==957 && ($locale=="992" || $locale=="991" || $locale=="957" || $locale=="924" || $locale=="923"))||($i==887 && ($locale=="922" || $locale=="923" || $locale=="889" || $locale=="854" || $locale=="855"))||($i==889 && ($locale=="924" || $locale=="923" || $locale=="889" || $locale=="856" || $locale=="855"))){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//red
					}elseif (($i==819 && ($locale=="854" || $locale=="855" || $locale=="821" || $locale=="786" || $locale=="787"))||($i==821 && ($locale=="856" || $locale=="855" || $locale=="821" || $locale=="788" || $locale=="787"))||($i==684 && ($locale=="718" || $locale=="719" || $locale=="720" || $locale=="684" || $locale=="686"))||($i==231 && $locale=="231")||($i==162 && $locale=="129" && $allprefs['loc163']==0)){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//gray
					}elseif (($i==352 || $i==318 || $i==284) && $allprefs['loc386']==1){
						//$mapkey.="<img src=\"./modules/signetimg/1maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/1maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//main map
					}else{
						//$mapkey.="<img src=\"./modules/signetimg/$mazemap\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px; background-color: #".$mazebg.";\">&nbsp;</td>";
					}
				}
				if ($i==33 or $i==67 or $i==101 or $i==135 or $i==169 or $i==203 or $i==237 or $i==271 or $i==305 or $i==339 or $i==373 or $i==407 or $i==441 or $i==475 or $i==509 or $i==543 or $i==577 or $i==611 or $i==645 or $i==679 or $i==713 or $i==747 or $i==781 or $i==815 or $i==849 or $i==883 or $i==917 or $i==951 or $i==985 or $i==1019 or $i==1053 or $i==1087 or $i==1121 or $i==1155 or $i==1189 or $i==1223 or $i==1257 or $i==1291 or $i==1325){
					//$mapkey="`n".$mapkey;
					$mapkey="</tr><tr>".$mapkey;
					$mapkey2=$mapkey.$mapkey2;
					$mapkey="";
				}
			}
			//$mapkey2.="</td></tr></tbody></table>";
			output_notl($mapkey2,true);
			output_notl("</table>",true);
			output_notl("`c");
		}
	}
page_footer();
function signetd2_runevent($type){
	global $session;
}
?>