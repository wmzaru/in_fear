<?php
	global $session;
	global $badguy;
	//most of this is modification of the abandoned castle module by Lonnyl,
	//and I couldn't have coded this without using his work as a template.
	$op = httpget('op');
	$locale = httpget('loc');
	$skill = httpget('skill');
	$knownmonsters = array('oldman','soldiers','mages','door','hornet','guard','asp','troll','bantir','wraith','skeleton','spider','waterelemental','dripslime','ranger','ogres');
	if (in_array($op, $knownmonsters) || $op == "fight" || $op == "run") {
		signetd3_fight($op);
		die;
	}
	$misc= array ('superuser','1097','scroll1b','scroll2b','scroll3b','scroll4b','scroll5b','scroll5','931','931b','619','483','fall','221','11','11b','11c','prisondoor','pick','bash','48','53','157','159','331','331b','635','537','537b','745','746','839','839b','352','352b','354','354b','1079','1079b','625','reset','exits1','exits2','exits3');
	if (in_array($op,$misc)){
		signetd3_misc($op);
	}
	page_header("Wasser's Castle");
	if ($session['user']['hitpoints'] <=0) redirect("shades.php");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$umaze = get_module_pref('maze');
	$umazeturn = $allprefs['mazeturn'];
	$upqtemp = get_module_pref('pqtemp');
	if ($op == "" && $locale == "") {
		if (get_module_setting("exitsave")==0 || $allprefs['startloc']=="") $allprefs['startloc']=1275;
		//to complete:  Gather scroll, talk to Wasser's Aid, Collect the Water Signet
		if ($allprefs['scroll5']==1 && $allprefs['loc159']==1 && $allprefs['watersignet']==1){
			output("`c`b`&Wasser's Castle`0`b`c`n");
			output("`\$You have completed this dungeon and this will be your last visit to it. You may now leave at any time.`0`n");
			$allprefs['complete']=1;
			$allprefssale=unserialize(get_module_pref('allprefssale',"signetsale"));
			set_module_pref("hoftemp",3300+$allprefssale['completednum'],"signetsale");
		}elseif ($allprefs['startloc']==1275){
			$allprefs['header']=0;
			output("`c`b`&Wasser's Castle`0`b`c`n");
			output("`2You have a bad feeling about this castle. It seems that it may have been overrun and Wasser is no longer in control. You better be extra careful.");
		}
		$locale=$allprefs['startloc'];
		$umazeturn = 0;
		$allprefs['mazeturn']=0;
		if (!isset($maze)){
			$maze = array(6,13,13,13,13,13,13,7,16,6,7,16,6,13,7,16,16,6,13,7,16,16,16,16,16,3,7,16,1,16,16,16,16,16,11,15,15,15,15,15,15,15,17,14,9,16,8,15,9,16,16,8,15,9,16,16,16,16,16,16,11,13,15,13,10,7,16,16,11,15,15,15,15,15,15,12,16,16,16,16,16,5,16,16,16,16,5,16,16,16,16,16,16,3,15,15,14,12,16,2,16,16,11,15,15,15,15,15,15,12,16,6,7,16,6,15,13,13,13,13,15,7,16,6,7,16,16,16,8,12,16,5,16,16,16,16,11,15,15,15,15,15,15,12,16,11,15,10,15,15,15,15,15,15,15,15,10,15,12,16,16,16,16,5,16,2,16,16,16,16,11,15,15,15,15,15,15,12,16,8,9,16,8,14,14,14,15,14,14,9,16,8,9,16,16,16,16,19,16,16,16,16,16,16,8,14,14,14,15,14,14,9,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,11,10,10,10,10,10,10,10,10,10,10,10,15,10,10,10,10,10,10,10,13,10,10,14,7,16,16,16,16,16,16,16,20,17,12,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,16,16,5,16,16,16,16,16,16,16,19,16,5,16,16,16,16,16,16,6,13,7,16,6,15,7,16,16,16,16,6,13,15,13,7,16,5,16,16,16,16,16,16,16,19,16,5,16,16,16,16,16,16,8,15,9,16,8,14,9,16,16,16,16,11,15,15,15,12,16,5,16,16,16,16,16,16,16,19,16,5,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,11,15,15,15,12,16,5,16,16,16,16,16,16,16,23,16,5,16,6,13,13,13,7,16,5,16,6,13,13,13,7,16,16,16,11,15,14,14,9,16,5,16,16,16,16,16,16,16,16,16,11,10,15,15,15,15,12,16,5,16,11,15,15,15,12,16,16,16,11,12,16,16,16,16,5,16,16,16,16,16,16,16,16,16,5,16,8,14,14,14,9,16,5,16,8,14,15,14,9,16,16,16,8,9,16,6,4,16,5,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,16,16,5,16,16,16,16,16,16,16,16,5,16,16,5,16,16,16,16,16,16,16,16,16,5,16,6,13,13,13,7,16,5,16,6,13,15,13,7,16,16,16,6,13,13,15,7,16,5,16,16,16,16,16,16,16,16,16,11,10,15,15,15,15,12,16,8,17,15,14,15,14,12,16,16,16,11,15,15,15,15,13,12,16,16,16,16,16,16,16,16,16,5,16,8,14,14,14,9,16,16,16,5,16,5,16,5,16,16,16,11,15,15,15,15,14,12,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,11,13,15,13,12,16,16,16,8,14,14,14,9,16,5,16,6,7,16,16,16,16,16,16,5,16,6,13,7,16,6,10,4,16,11,15,15,15,12,16,16,16,16,16,16,16,16,16,11,10,15,12,16,16,16,16,16,16,5,16,11,15,15,10,12,16,16,16,11,14,15,14,12,16,16,16,6,13,13,13,7,16,5,16,8,9,16,16,16,16,16,16,11,10,15,15,12,16,5,16,16,16,5,16,5,16,5,16,16,16,11,15,15,15,15,10,12,16,16,16,16,16,16,16,16,16,5,16,8,14,9,16,8,17,21,16,11,13,15,13,12,16,16,16,8,14,14,14,9,16,5,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,19,16,8,14,15,14,9,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,5,16,6,13,13,13,7,16,19,16,16,16,5,16,16,16,6,13,13,13,13,13,7,16,5,16,16,16,16,16,16,16,16,16,5,16,11,15,15,15,12,16,23,16,6,13,15,13,7,16,11,15,15,15,15,15,15,10,12,16,16,16,16,16,16,16,16,16,5,16,8,14,15,14,9,16,16,16,11,15,15,15,12,16,8,14,14,15,14,14,9,16,5,16,16,16,16,16,16,16,16,16,5,16,16,16,5,16,16,16,16,16,11,15,15,15,12,16,16,16,16,5,16,16,16,16,5,16,16,16,16,16,16,16,16,16,8,10,10,10,15,10,10,10,10,10,15,15,15,15,15,10,10,10,10,14,13,10,10,10,9,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,8,14,15,14,9,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,6,13,15,13,7,16,16,16,16,16,5,16,16,16,16,6,13,13,15,13,7,16,16,16,16,16,16,16,16,16,16,16,16,16,11,15,15,15,12,16,16,16,16,6,15,7,16,16,16,11,15,15,15,15,12,16,16,16,16,16,16,16,16,16,16,16,16,16,8,14,14,14,9,16,16,16,16,11,15,12,16,16,16,8,14,14,14,14,9,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,11,15,12,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,11,15,12,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,8,14,9,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16);
			$umaze = implode($maze,",");
			set_module_pref("maze", $umaze);
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op <> ""){
		if ($op == "n") {
			$locale+=34;
			redirect("runmodule.php?module=signetd3&loc=$locale");
		}
		if ($op == "s"){
			$locale-=34;
			redirect("runmodule.php?module=signetd3&loc=$locale");
		}
		if ($op == "w"){
			$locale-=1;
			redirect("runmodule.php?module=signetd3&loc=$locale");
		}
		if ($op == "e"){
			$locale+=1;
			redirect("runmodule.php?module=signetd3&loc=$locale");
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
				if (get_module_pref("super","signetsale")==1 || $locale=="1275" || $allprefs['complete']==1) villagenav();

				if ($locale=="1010" ||$locale=="1079" ||$locale=="946" ||$locale=="810" ||$locale=="744" ||$locale=="640" ||$locale=="674" ||$locale=="300" ||$locale=="331" ||$locale=="221" ||$locale=="323" ||$locale=="311" ||$locale=="243" ||$locale=="482" ||$locale=="618" || $locale=="788" || $locale=="1029" || $locale=="1034" || $locale=="1040") $allprefs['header']=1;
				elseif ($locale=="1139" || $locale=="1241" || $locale=="1274" || $locale=="1276") $allprefs['header']=2;
				elseif ($locale=="935" || $locale=="1039" || $locale=="1105" || $locale=="1035") $allprefs['header']=3;
				elseif ($locale=="1063") $allprefs['header']=4;
				elseif ($locale=="995") $allprefs['header']=5;
				elseif ($locale=="758"||$locale=="789") $allprefs['header']=6;
				elseif ($locale=="827"||$locale=="759") $allprefs['header']=7;
				elseif ($locale=="828") $allprefs['header']=8;
				elseif ($locale=="310" ||$locale=="445") $allprefs['header']=9;
				elseif ($locale=="357") $allprefs['header']=10;
				elseif ($locale=="561") $allprefs['header']=11;
				elseif ($locale=="421"||$locale=="625"||$locale=="455") $allprefs['header']=12;
				elseif ($locale=="157"||$locale=="82"||$locale=="148"||$locale=="187") $allprefs['header']=13;
				elseif ($locale=="44"||$locale=="387"||$locale=="187"||$locale=="809"||$locale=="1113") $allprefs['header']=14;
				elseif ($locale=="365") $allprefs['header']=15;
				elseif ($locale=="266") $allprefs['header']=16;
				elseif ($locale=="745") $allprefs['header']=17;
				elseif ($locale=="570" ||$locale=="639" || $locale=="673") $allprefs['header']=18;
				elseif ($locale=="945" || $locale=="976") $allprefs['header']=19;
				elseif ($locale=="147" || $locale=="53" || $locale=="48" || $locale=="158" ) $allprefs['header']=20;
				elseif ($locale=="619" || $locale=="483") $allprefs['header']=21;
				elseif ($locale=="901" || $locale=="595" || $locale=="627") $allprefs['header']=22;
				elseif ($locale=="43" ||$locale=="626") $allprefs['header']=23;
				elseif ($locale=="42" ||$locale=="209") $allprefs['header']=24;
				elseif ($locale=="536") $allprefs['header']=25;
				elseif ($locale=="1275") $allprefs['header']=0;
				$title=array("","An Elegant Marble Hallway","Outside a Large Iron Door","The Main Entrance","The Captain's Room","An  Empty Messed-Up Room","Lord Wasser's Room","A Large and Dark Closet","A Dark Hallway","A Smelly Pit","Large Dark Closet","Ordinary Kitchen","Simple Stone Hallway","Detention Area with Four Jail Cells","A Storage Room","Room Filled with Smelly Animals","A Dirty and Slimy Garbage Pit","The Magic Pool Keeper's Room","The Barracks","Messy Library with Scrolls Everywhere","Small Cell","Guest Bedroom","A Large Hall with Great Columns","A Secret Passageway","The Tile Room","A Little Closet");
				$header=$allprefs['header'];
				output_notl("`b`^`c%s`b`c`0",translate_inline($title[$header]));
				if ($header==0) output("`n");
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($locale=="1206" && $allprefs['loc1206']==0){
					output("An old man sitting here tells you `#'All is not well within.'`0`n");
					$allprefs['loc1206']=1;
				}elseif ($locale=="1071" && $allprefs['loc1071']==0){
					output("A butler greets you saying `#'Welcome to the castle of Lord Wasser. His  Lordship is not in but you may wander around and make yourself at home. Emergency exits are located at the four corners of the castle.'`0");
					$allprefs['loc1071']=1;
				}elseif ($locale=="727") output("A Message is engraved on the wall: `@SWWSSESSWWWSSEEEEENEEE`0`n");
				elseif ($locale=="623") output("An inscription scratched on the wall here reads `@'Fiamma Was Here'`0.`n");
				elseif ($locale=="487") output("A plaque on the wall reads `@'Welcome to our humble castle'`0.`n");
				elseif ($locale=="243") output("`#Bright lights emanate from the room in front of you.  The  tiles on the floor seem to be precariously balancing on poles.`0");
				elseif ($locale=="743") output("A sign in the middle of the hall reads `@'The Magic Pools'`0.`n");
				else output("`n");
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if (($locale=="301" || $locale=="277" || $locale=="1025" || $locale=="1049") && $allprefs['complete']==0) {
					if (get_module_setting("exitsave")>=1) addnav("Return to Village","runmodule.php?module=signetd3&op=exits1");
					elseif (get_module_pref("super","signetsale")==0 && $allprefs['complete']==0) villagenav();
				}
				$allprefsd2=unserialize(get_module_pref('allprefs','signetd2'));
				if($locale=="537") redirect("runmodule.php?module=signetd3&op=537");
				elseif ($allprefsd2['complete']==1 && $allprefsd2['reset']==0) redirect("runmodule.php?module=signetd3&op=reset");
				elseif($locale=="1097" && $allprefs['loc1097']==0) redirect("runmodule.php?module=signetd3&op=1097");
				elseif($locale=="931" && $allprefs['loc931']==0) redirect("runmodule.php?module=signetd3&op=931");
				elseif($locale=="619" && $allprefs['loc619']==0) redirect("runmodule.php?module=signetd3&op=619");
				elseif($locale=="483" && $allprefs['loc483']==0) redirect("runmodule.php?module=signetd3&op=483");
				elseif($locale=="221" && $allprefs['loc221']==0) redirect("runmodule.php?module=signetd3&op=221");
				elseif($locale=="358" && $allprefs['loc358']==0) redirect("runmodule.php?module=signetd3&op=hornet");
				elseif($locale=="11" && $allprefs['loc11']==0) redirect("runmodule.php?module=signetd3&op=11");
				elseif(($locale=="148" && $allprefs['loc148']==0)||($locale=="82" && $allprefs['loc82']==0)||($locale=="87" && $allprefs['loc87']==0)) redirect("runmodule.php?module=signetd3&op=prisondoor");
				elseif($locale=="48" && $allprefs['loc48']==0) redirect("runmodule.php?module=signetd3&op=48");
				elseif($locale=="53" && $allprefs['loc53']==0) redirect("runmodule.php?module=signetd3&op=53");
				elseif($locale=="157" && $allprefs['loc352']==0) redirect("runmodule.php?module=signetd3&op=157");
				elseif($locale=="157" && $allprefs['loc157']==0) redirect("runmodule.php?module=signetd3&op=157");
				elseif($locale=="159" && $allprefs['loc159']==0) redirect("runmodule.php?module=signetd3&op=159");
				elseif($locale=="331" && $allprefs['loc331']==0) redirect("runmodule.php?module=signetd3&op=331");
				elseif($locale=="499" && $allprefs['loc499']==0) redirect("runmodule.php?module=signetd3&op=hornet");
				elseif($locale=="401" && $allprefs['loc401']==0) redirect("runmodule.php?module=signetd3&op=asp");
				elseif($locale=="164" && $allprefs['loc164']==0) redirect("runmodule.php?module=signetd3&op=troll");
				elseif($locale=="94" && $allprefs['loc94']==0) redirect("runmodule.php?module=signetd3&op=bantir");
				elseif($locale=="29" && $allprefs['loc29']==0) redirect("runmodule.php?module=signetd3&op=wraith");
				elseif($locale=="65" && $allprefs['loc65']==0) redirect("runmodule.php?module=signetd3&op=skeleton");
				elseif($locale=="66" && $allprefs['loc66']==0) redirect("runmodule.php?module=signetd3&op=spider");
				elseif($locale=="635" && $allprefs['loc635']==0) redirect("runmodule.php?module=signetd3&op=635");
				elseif($locale=="745" && $allprefs['loc745']==0) redirect("runmodule.php?module=signetd3&op=745");
				elseif($locale=="746" && $allprefs['loc746']==0) redirect("runmodule.php?module=signetd3&op=746");
				elseif($locale=="839" && $allprefs['loc839']==0) redirect("runmodule.php?module=signetd3&op=839");
				elseif($locale=="939" && $allprefs['loc939']==0) redirect("runmodule.php?module=signetd3&op=dripslime");
				elseif($locale=="625" && $allprefs['loc625']==0) redirect("runmodule.php?module=signetd3&op=625");
				elseif($locale=="591" && $allprefs['loc591']==0) redirect("runmodule.php?module=signetd3&op=ranger");
				elseif($locale=="352" && $allprefs['loc352']==0) redirect("runmodule.php?module=signetd3&op=352");
				elseif($locale=="354" && $allprefs['loc354']==0) redirect("runmodule.php?module=signetd3&op=354");
				elseif($locale=="1079" && $allprefs['loc1079']==0) redirect("runmodule.php?module=signetd3&op=1079");
				//falling in the trap room
				elseif ($locale=="210" || $locale=="175" || $locale=="174" || $locale=="206" || $locale=="172" || $locale=="138" || $locale=="105" || $locale=="141" || $locale=="107" || $locale=="38" || $locale=="73" || $locale=="105" || $locale=="37" || $locale=="104" || $locale=="36" || $locale=="103" || $locale=="39" || $locale=="7" || $locale=="74" || $locale=="8" || $locale=="75" || $locale=="76") redirect("runmodule.php?module=signetd3&op=fall");
				//Random monsters
				elseif ($allprefs['randomp']<=get_module_setting("random")){
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
									switch(e_rand(1,9)){
										case 1:
											redirect("runmodule.php?module=signetd3&op=soldiers");
										break;
										case 2:
											redirect("runmodule.php?module=signetd3&op=mages");
										break;
										case 3:
											redirect("runmodule.php?module=signetd3&op=ranger");
										break;
										case 4:
											redirect("runmodule.php?module=signetd3&op=hornet");
										break;
										case 5:
											redirect("runmodule.php?module=signetd3&op=asp");
										break;
										case 6:
											redirect("runmodule.php?module=signetd3&op=troll");
										break;
										case 7:
											redirect("runmodule.php?module=signetd3&op=wraith");	
										break;
										case 8:
											redirect("runmodule.php?module=signetd3&op=skeleton");
										break;
										case 9:
											redirect("runmodule.php?module=signetd3&op=spider");
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
				//Scrolls (Keep the old, add the new)
				addnav("Scrolls");
				addnav("1?Read Scroll 1","runmodule.php?module=signetd3&op=scroll1b");
				$allprefsd2=unserialize(get_module_pref('allprefs','signetd2'));
				$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($allprefsd1['scroll2']==1) addnav("2?Read Scroll 2","runmodule.php?module=signetd3&op=scroll2b");
				if ($allprefsd1['scroll3']==1) addnav("3?Read Scroll 3","runmodule.php?module=signetd3&op=scroll3b");
				if ($allprefsd2['scroll4']==1) addnav("4?Read Scroll 4","runmodule.php?module=signetd3&op=scroll4b");
				if ($allprefs['scroll5']==1) addnav("5?Read Scroll 5","runmodule.php?module=signetd3&op=scroll5b");
				addnav("Directions");
				if($locale=="1275"){
					output("`nYou are at the entrance with Passages to the");
				}else{
					output("`nYou may go");
				}
				$umazeturn++;
				$allprefs['mazeturn']=$umazeturn;
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($navigate=="1" or $navigate=="5" or $navigate=="6"or $navigate=="7" or $navigate=="11" or $navigate=="12"or $navigate=="13" or $navigate=="15" or $navigate=="19"or $navigate=="20" or $navigate=="21") {
					addnav("North","runmodule.php?module=signetd3&op=n&loc=$locale");
					$directions.=" $north";
					$navcount++;
				}
				if ($navigate=="2" or $navigate=="5" or $navigate=="8"or $navigate=="9" or $navigate=="11" or $navigate=="12"or $navigate=="14" or $navigate=="15" or $navigate=="18" or $navigate=="19" or $navigate=="22" or $navigate=="23") {
					addnav("South","runmodule.php?module=signetd3&op=s&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $south";
				}
				if ($navigate=="4" or $navigate=="7" or $navigate=="9"or $navigate=="10" or $navigate=="12" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="21" or $navigate=="22") {
					addnav("West","runmodule.php?module=signetd3&op=w&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $west";
				}
				if ($navigate=="3" or $navigate=="6" or $navigate=="8"or $navigate=="10" or $navigate=="11" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="18" or $navigate=="20") {
					addnav("East","runmodule.php?module=signetd3&op=e&loc=$locale");
					if ($allprefs['loc11c']==0  && $locale=="743") {
						blocknav("runmodule.php?module=signetd3&op=e&loc=$locale");
					}else{
						$navcount++;
						if ($navcount > 1) $directions.=",";
						$directions.=" $east";					
					}
				}
				output_notl($directions);				
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
			rawoutput(" = <img src=\"./modules/signetimg/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\"> ");
			output("`7Emergency Exit = E");
			//rawoutput(" = <img src=\"./modules/signetimg/mexit.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\"><big>");
			rawoutput("<table style=\"height: 130px; width: 350px; text-align: absmiddle; line-height: 10px; font-size: 8px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td colspan=\"34\"></td>");
			//$mapkey2="<table style=\"height: 130px; width: 110px; text-align: left;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td style=\"vertical-align: top;\">";
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
					$allprefs=unserialize(get_module_pref('allprefs'));
					if ($i==1274){
						//$mapkey.="<img src=\"./modules/signetimg/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px; background-color: 26FF00;\">&nbsp;</td>";
					//door Up Down
					}elseif (($i==787 && ($locale=="787" || $locale=="789")) ||($i==757 && ($locale=="757" || $locale=="759"))||($i==725 && ($locale=="725" || $locale=="727"))||($i==617 && ($locale=="617" || $locale=="619"))||($i==481 && ($locale=="481" || $locale=="483"))||($i==147 && ($locale=="149"||$locale=="147"))||($i==156 && ($locale=="156"||$locale=="158"))||($i==809 && ($locale=="809"||$locale=="811"))||($i==945 && ($locale=="945"||$locale=="947"))||($i==639 && ($locale=="639"||$locale=="674"||$locale=="641"||$locale=="673"||$locale=="675"))||($i==673 && ($locale=="639"||$locale=="640"||$locale=="641"||$locale=="673"||$locale=="675"))){
						//$mapkey.="<img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//Red Dot
					}elseif (($i==1205 && $allprefs['loc1206']==0 && ($locale=="1240" || $locale=="1241" || $locale=="1207" || $locale=="1172" || $locale=="1173" ))||($i==1070 && $allprefs['loc1071']==0 && $locale=="1105")||($i==1096 && $allprefs['loc1097']==0 && $locale=="1063")||($i==618 && $allprefs['loc619']==0 && $locale=="618")||($i==482 && $allprefs['loc483']==0 && $locale=="482")||($i==351 && $allprefs['loc352']==0 && ($locale=="386" || $locale=="387" || $locale=="353"))||($i==353 && $allprefs['loc354']==0 && ($locale=="388" || $locale=="387" || $locale=="353"))||($i==10 && $allprefs['loc11']==0 && ($locale=="10" || $locale=="44"|| $locale=="45"))){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					}elseif (($i==158 && $allprefs['loc159']==0 && ($locale=="158" || $locale=="192" || $locale=="193" || $locale=="124" || $locale=="125"))|| ($i==745 && $allprefs['loc746']==0 && ($locale=="745" || $locale=="780" || $locale=="779" || $locale=="712" || $locale=="711"))||($i==634 && $allprefs['loc635']==0 && ($locale=="636" || $locale=="669" || $locale=="670" || $locale=="601" || $locale=="602"))||($i==838 && $allprefs['loc839']==0 && ($locale=="805"||$locale=="806"||$locale=="840"))){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//Trap Room
					}elseif (($i==0 || $i==1 || $i==2 || $i==3 || $i==4 || $i==5 || $i==6 || $i==7 ||$i==34 || $i==35 || $i==36 || $i==37 || $i==38 || $i==39 || $i==40 || $i==41 ||$i==68 || $i==69 || $i==70 || $i==71 || $i==72 || $i==73 || $i==74 || $i==75 ||$i==102 || $i==103 || $i==104 || $i==105 || $i==106 || $i==107 || $i==108 || $i==109 ||$i==136 || $i==137 || $i==138 || $i==139 || $i==140 || $i==141 || $i==142 || $i==143 ||$i==170 || $i==171 || $i==172 || $i==173 || $i==174 || $i==175 || $i==176 || $i==177 ||$i==204 || $i==205 || $i==206 || $i==207 || $i==208 || $i==209 || $i==210 || $i==211) && ($locale=="209" || $locale=="208" || $locale=="207" || $locale=="173" || $locale=="139" || $locale=="140" || $locale=="106" || $locale=="72" || $locale=="71" || $locale=="70" || $locale=="69" || $locale=="35" || $locale=="1" || $locale=="2" || $locale=="3" || $locale=="4" || $locale=="5" || $locale=="6" || $locale=="40" || $locale=="41" || $locale=="42" || $locale=="43" ||$locale=="243")){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//Red dot on black
					}elseif ($i==930 && $allprefs['loc931']==0 && $locale=="897"){
						//$mapkey.="<img src=\"./modules/signetimg/mredb.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mredb.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//door Left Right
					}elseif (($i==1104 && ($locale=="1139" || $locale=="1071"))||($i==560 && $allprefs['loc561']==0 && ($locale=="595" || $locale=="527"))||($i==994 && ($locale=="1029" || $locale=="961"))||($i==1062 && ($locale=="1029" || $locale=="1097"))||($i==220 && $locale=="255" && $allprefs['loc221']==0)||($i==322 && ($locale=="289" || $locale=="357"))||($i==81 && ($locale=="116" || $locale=="48"))||($i==86 && ($locale=="121" || $locale=="53"))||($i==330 && ($locale=="297" || $locale=="365"))||($i==569 && ($locale=="604" || $locale=="536"))||($i==1009 && ($locale=="976" || $locale=="1044"))|| ($i==1078 && ($locale=="1045"||$locale=="1113"))){
						//$mapkey.="<img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//magic pool entrance
					}elseif ($i==743 && $allprefs['loc11c']==0){
						//$mapkey.="<img src=\"./modules/signetimg/16maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/16maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//emergency exits
					}elseif ($i==300 || $i==276 || $i==1024 || $i== 1048){
						//$mapkey.="<img src=\"./modules/signetimg/mexit.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\">E</td>";
					//Finishing the map
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
function signetd3_runevent($type){
	global $session;
}
?>