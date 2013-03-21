<?php
	global $session;
	global $badguy;
	//most of this is modification of the abandoned castle module by Lonnyl,
	//and I couldn't have coded this without using his work as a template.
	$op = httpget('op');
	$locale = httpget('loc');
	$skill = httpget('skill');
	$knownmonsters = array('door','orc','orcs','ant','spider','rangers','scribe','kobolds','dripslimes','bantir');
	if (in_array($op, $knownmonsters) || $op == "fight" || $op == "run") {
		signetd1_fight($op);
		die;
	}
	$misc= array ('superuser','939','973','1008','1009','1006','1005','1011','1216','1287','1287b','899','899b','891','891b','1136','1197','1197b','1199','1199b','685','685b','685c','759','759b','677','677b','673','593','465','333','333b','scroll2b','303','303b','1133','1133b','1133c','494','256','54','411','411b','351','113','113b','113c','scroll3b','scroll2','scroll3','scroll1b','exits1','exits2','exits3');
	if (in_array($op,$misc)){
		signetd1_misc($op);
	}
	page_header("Aria Dungeon");
	//villagenav();
	if ($session['user']['hitpoints'] <= 0) redirect("shades.php");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$umaze = get_module_pref('maze');
	$umazeturn = $allprefs['mazeturn'];
	$upqtemp = get_module_pref('pqtemp');
	if ($op == "" && $locale == "") {
		if (get_module_setting("exitsave")==0 || $allprefs['startloc']=="") $allprefs['startloc']=1279;
		//to Complete: Gather Scroll 2, Gather Scroll 3, Obtain Air Signet, Turn off Flood
		if ($allprefs['scroll2']==1 && $allprefs['scroll3']==1 && $allprefs['airsignet']==1){
			output("`c`b`&Aria Elemental Dungeon`0`b`c`n");
			output("`\$You have completed this dungeon and this will be your last visit to it.`0");
			$allprefs['complete']=1;
			$allprefssale=unserialize(get_module_pref('allprefssale',"signetsale"));
			set_module_pref("hoftemp",1300+$allprefssale['completednum'],"signetsale");
		}elseif ($allprefs['startloc']==1279){
			$allprefs['header']=0;
			output("`c`b`&Aria Elemental Dungeon`0`b`c`n");
			output("`2You enter the Dungeon and let your eyes adjust to the darkness. You have a new appreciation for the word dank.  This is a foul smelling pit of despair.`n`n");
			output("Was this the abandoned home of dwarves?  What creatures live here now?");
		}
		$locale=$allprefs['startloc'];
		$umazeturn = 0;
		$allprefs['mazeturn']=0;
		if (!isset($maze)){
			$maze = array(16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,6,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,10,7,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,6,13,7,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,6,10,10,4,16,16,16,5,16,16,16,16,16,16,11,15,12,16,16,16,16,16,16,16,16,5,16,16,16,16,16,6,10,10,9,16,16,16,16,16,16,5,16,16,16,16,16,16,8,15,9,16,16,16,16,16,16,16,16,5,16,16,16,16,6,9,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,5,16,6,10,10,9,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,16,16,16,16,16,6,10,10,15,10,9,16,16,16,16,16,16,16,1,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,16,16,16,16,6,9,16,16,5,16,16,16,16,16,6,13,7,16,5,16,16,16,16,16,5,16,16,16,16,16,16,16,11,21,16,16,16,6,9,16,16,16,5,16,16,16,16,16,11,15,15,10,12,16,16,16,16,16,5,16,16,6,10,10,10,10,15,14,10,10,10,9,16,16,16,6,15,7,16,16,16,16,8,14,9,16,5,16,16,16,16,16,5,16,6,9,16,16,16,16,5,16,16,16,16,16,16,16,16,8,15,9,16,16,16,16,16,16,16,16,5,16,16,16,16,16,2,16,5,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,6,15,7,16,16,16,16,16,16,5,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,11,10,10,13,13,13,10,10,10,15,15,12,16,16,16,16,3,10,9,16,16,16,16,16,5,16,16,16,6,13,13,10,10,10,12,16,16,11,15,12,16,16,16,8,14,9,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,11,15,12,16,16,16,5,16,16,8,14,9,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,11,14,9,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,5,16,16,16,16,16,5,16,16,16,16,16,16,16,6,13,13,7,16,16,16,16,16,16,16,16,16,16,16,16,5,16,6,13,12,16,16,16,6,13,15,13,7,16,16,16,20,17,14,14,15,9,16,16,16,16,16,16,16,16,16,16,16,16,5,16,11,15,12,16,16,16,11,14,15,14,12,16,16,16,19,16,16,16,5,16,16,16,16,16,16,16,6,13,13,13,10,10,12,16,8,14,9,16,16,16,5,16,5,16,5,16,16,16,18,17,17,17,12,16,16,16,16,16,16,16,11,15,15,12,16,16,5,16,16,16,16,16,16,16,11,13,15,13,12,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,11,14,14,9,16,16,11,10,10,10,10,10,10,10,15,14,15,14,15,10,10,10,10,10,10,10,12,16,16,16,16,16,16,16,5,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,5,16,5,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,16,16,16,16,5,16,16,16,16,16,16,16,11,13,15,13,12,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,6,13,7,16,5,16,6,13,7,16,16,16,8,14,15,14,9,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,11,15,15,10,15,10,15,15,12,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,8,14,9,16,5,16,8,14,9,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,5,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,11,10,10,10,10,10,14,10,10,10,10,10,13,10,10,10,15,10,10,10,13,10,10,10,10,13,15,7,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,5,16,16,16,5,16,16,16,16,11,15,12,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,5,16,16,16,5,16,16,16,16,11,15,12,16,16,16,16,16,16,11,10,10,10,7,16,6,13,7,16,16,16,5,16,16,16,5,16,16,16,5,16,16,16,16,11,14,9,16,16,16,16,16,16,5,16,16,16,5,16,11,15,15,10,10,10,12,16,16,16,5,16,16,16,5,16,16,16,16,2,16,16,16,16,16,16,16,16,5,16,16,16,5,16,8,14,9,16,16,16,5,16,16,6,15,7,16,16,5,16,6,7,16,16,16,16,16,16,16,16,16,16,11,13,7,16,2,16,16,16,16,16,16,16,5,16,16,8,15,9,16,16,8,10,15,15,7,16,16,16,16,16,16,16,16,16,11,15,12,16,16,16,16,16,16,16,16,16,5,16,16,16,5,16,16,16,16,16,11,15,12,16,16,16,16,16,16,16,16,16,8,14,14,10,10,10,10,10,10,10,10,10,9,16,16,16,2,16,16,16,16,16,8,14,9,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16);
			$umaze = implode($maze,",");
			set_module_pref("maze", $umaze);
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op <> ""){
		if ($op == "n") {
			$locale+=34;
			redirect("runmodule.php?module=signetd1&loc=$locale");
		}
		if ($op == "s"){
			$locale-=34;
			redirect("runmodule.php?module=signetd1&loc=$locale");
		}
		if ($op == "w"){
			$locale-=1;
			redirect("runmodule.php?module=signetd1&loc=$locale");
		}
		if ($op == "e"){
			$locale+=1;
			redirect("runmodule.php?module=signetd1&loc=$locale");
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
				if (get_module_pref("super","signetsale")==1 || $locale=="1279" || $allprefs['complete']==1) villagenav();

				if ($locale=="963" ||$locale=="991" ||$locale=="215" ||$locale=="317" ||$locale=="689" ||$locale=="787" ||$locale=="1093" ||$locale=="1161"||$locale=="896" ||$locale=="894" ||$locale=="1216" ||$locale=="1015" ||$locale=="711" ||$locale=="599" ||$locale=="1143" || $locale=="983" || $locale=="772" || $locale=="766" || $locale=="905" || $locale=="497" || $locale=="463") $allprefs['header']=1;
				elseif ($locale=="997" ||$locale=="992" ||$locale=="1266"||$locale=="1136" ||$locale=="1006" || $locale=="469" || $locale=="405" || $locale=="471" || $locale=="337") $allprefs['header']=2;
				elseif ($locale=="468" || $locale=="1016" || $locale=="1017" || $locale=="1217" || $locale=="1265" || $locale=="1195" || $locale=="466" || $locale=="455") $allprefs['header']=3;
				elseif ($locale=="1094") $allprefs['header']=4;
				elseif ($locale=="688" ||$locale=="753" ||$locale=="893" ||  $locale=="472" ||  $locale=="439") $allprefs['header']=5;
				elseif ($locale=="643"|| $locale=="641") $allprefs['header']=6;
				elseif ($locale=="640" || $locale=="710") $allprefs['header']=7;
				elseif ($locale=="871" || $locale=="771" || $locale=="767" || $locale=="633") $allprefs['header']=8;
				elseif ($locale=="493"||$locale=="593") $allprefs['header']=9;
				elseif ($locale=="627") $allprefs['header']=10;
				elseif ($locale=="335") $allprefs['header']=11;
				elseif ($locale=="269") $allprefs['header']=12;
				elseif ($locale=="897" || $locale=="1211") $allprefs['header']=13;
				elseif ($locale=="53" || $locale=="225") $allprefs['header']=14;
				elseif ($locale=="259"||$locale=="351"||$locale=="134"||$locale=="480") $allprefs['header']=15;
				elseif ($locale=="293" || $locale=="385") $allprefs['header']=16;
				elseif ($locale=="135") $allprefs['header']=17;
				elseif ($locale=="479") $allprefs['header']=18;
				elseif ($locale=="1135"||$locale=="181") $allprefs['header']=19;
				elseif ($locale=="494" ||  $locale=="496" || $locale=="464" || $locale=="465" || $locale=="327" || $locale=="395" || $locale=="494" || $locale=="494" || $locale=="494" || $locale=="494" || $locale=="494") $allprefs['header']=0;
				$title=array("","A Passage with Torches on the Wall","An Old Crumbling Stone Hallway","A Messy Bedroom with Old Cots","A Smelly and Dark Hallway","A Plain Empty Stone Room","A Storage Room with Little of Value","A Dark Passage","A Large Hall with Great Columns","A Kitchen with Crude Utensils","Storage Room with Rotten food","Library filled with Scrolls","An Empty Closet","An Abandoned Guard Room","A Damp Dark Stone Tunnel","A Natural Cavern with Murky Water","The Passage Crosses a Cavern Which Contains a Murky Stream.","The Cavern stops here except for a thin crack through which the water exits.","The Cavern stops here.  The water is bubbling through a small hole in the floor.","A Small Dark Cell");
				$header=$allprefs['header'];
				output_notl("`b`^`c%s`b`c`0", translate_inline($title[$header]));
				if ($header==0) output("`n");
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($locale=="1007") output("You are at the intersection of three doorways.`n");
				elseif ($locale=="643") output("You see some scribbling on the wall to the west.`n");
				elseif ($locale=="147") output("You see some strange writing on the wall to the west.`n");
				elseif ($locale=="181") output("The door is carved with the phrase `#'Kilmor was held prisoner here'`0.`n");
				elseif ($locale=="146") output("On the wall is written `@'Nifle Scro'`0.`n");
				elseif ($locale=="1286" && $allprefs['loc1287']==0) output("You see something shiny in the corner to the East.`n");
				elseif ($locale=="1253" && $allprefs['loc1287']==0) output("You see something shiny in the corner to the North.`n");
				elseif ($locale=="933" && $allprefs['loc899']==0) output("You see a lever on the wall to the South.`n");
				elseif ($locale=="898" && $allprefs['loc899']==0) output("You see a lever on the wall to the East.`n");
				elseif ($locale=="865" && $allprefs['loc899']==0) output("You see a lever on the wall to the North.`n");
				elseif ($locale=="925" && $allprefs['loc891']==0) output("You see a button on the wall to the South.`n");
				elseif ($locale=="892" && $allprefs['loc891']==0) output("You see a button on the wall to the West.`n");
				elseif ($locale=="857" && $allprefs['loc891']==0) output("You see a button on the wall to the North.`n");
				elseif ($locale=="745" && $allprefs['loc673']==0) output("To the Southwest you hear orc voices.`n");
				elseif ($locale=="639" && $allprefs['loc673']==0) output("You see movement to the North!`n");
				elseif ($locale=="707" && $allprefs['loc673']==0) output("You see movement to the South!`n");
				elseif ($locale=="641") output("Written in sloppy orc handwriting are the words `#'Shhhhh... Secret door to the west'`0.`n");
				elseif ((($locale=="753" || $locale=="821") && $allprefs['loc787']==0) ||(($locale=="690" || $locale=="688") && $allprefs['loc689']==0) || (($locale=="949" || $locale=="1017") && $allprefs['loc983']==0) || (($locale=="1016" || $locale=="1014") && $allprefs['loc1015']==0)) output("You see a stubborn door ahead of you.`n");
				else output("`n");
				
				if (($locale=="1263"||$locale=="759"||$locale=="479"||$locale=="135"||$locale=="508"||$locale=="779") && $allprefs['complete']==0) {
					if (get_module_setting("exitsave")>=1) addnav("Return to Village","runmodule.php?module=signetd1&op=exits1");
					elseif (get_module_pref("super","signetsale")==0 && $allprefs['complete']==0) villagenav();
				}
				if($locale=="1006" && $allprefs['loc1006']==0) redirect("runmodule.php?module=signetd1&op=1006");
				elseif($locale=="1005" && $allprefs['loc1006']==0) redirect("runmodule.php?module=signetd1&op=1005");
				elseif($locale=="1008" && $allprefs['loc1008']==0) redirect("runmodule.php?module=signetd1&op=1008");
				elseif($locale=="973" && $allprefs['loc973']==0) redirect("runmodule.php?module=signetd1&op=973");
				elseif($locale=="939" && $allprefs['loc973']==0) redirect("runmodule.php?module=signetd1&op=939");				
				elseif($locale=="1009" && $allprefs['loc1009']==0) redirect("runmodule.php?module=signetd1&op=orc");
				elseif($locale=="1011" && $allprefs['loc1011']==0) redirect("runmodule.php?module=signetd1&op=1011");
				elseif($locale=="1216" && $allprefs['loc1216']==0) redirect("runmodule.php?module=signetd1&op=1216");				
				elseif($locale=="1287"&& $allprefs['loc1287']==0) redirect("runmodule.php?module=signetd1&op=1287");	
				elseif($locale=="899" && $allprefs['loc899']==0) redirect("runmodule.php?module=signetd1&op=899");
				elseif($locale=="891" && $allprefs['loc891']==0) redirect("runmodule.php?module=signetd1&op=891");
				elseif($locale=="1136" && $allprefs['loc891']==0) redirect("runmodule.php?module=signetd1&op=1136");
				elseif($locale=="1197" && $allprefs['loc1197']==0) redirect("runmodule.php?module=signetd1&op=1197");
				elseif($locale=="1199" && $allprefs['loc1199']==0) redirect("runmodule.php?module=signetd1&op=1199");
				elseif($locale=="685" && $allprefs['loc685']==0) redirect("runmodule.php?module=signetd1&op=685");
				elseif(($locale=="787" && $allprefs['loc787']==0) || ($locale=="689" && $allprefs['loc689']==0) || ($locale=="983" && $allprefs['loc983']==0) || ($locale=="1015" && $allprefs['loc1015']==0)) redirect("runmodule.php?module=signetd1&op=door");
				elseif(($locale=="793" || $locale=="760" ||$locale=="725") && $allprefs['loc759']==0) redirect("runmodule.php?module=signetd1&op=759");
				elseif($locale=="1152" && $allprefs['loc1152']==0) redirect("runmodule.php?module=signetd1&op=spider");
				elseif($locale=="677" && $allprefs['loc677']==0) redirect("runmodule.php?module=signetd1&op=677");
				elseif($locale=="673" && $allprefs['loc673']==0) redirect("runmodule.php?module=signetd1&op=673");
				elseif($locale=="494" && $allprefs['loc494']==0) redirect("runmodule.php?module=signetd1&op=494");
				elseif($locale=="593" && $allprefs['loc593']==0) redirect("runmodule.php?module=signetd1&op=593");
				elseif($locale=="465" && $allprefs['loc465']==0) redirect("runmodule.php?module=signetd1&op=465");
				elseif($locale=="333" && $allprefs['loc333']==0) redirect("runmodule.php?module=signetd1&op=333");
				elseif($locale=="303" && $allprefs['loc303']==0) redirect("runmodule.php?module=signetd1&op=303");
				elseif($locale=="1133" && $allprefs['loc1133']==0) redirect("runmodule.php?module=signetd1&op=1133");
				elseif($locale=="256" && $allprefs['loc411']==0) redirect("runmodule.php?module=signetd1&op=256");
				elseif($locale=="54" && $allprefs['loc54']==0) redirect("runmodule.php?module=signetd1&op=54");
				elseif($locale=="411" && $allprefs['loc411']==0) redirect("runmodule.php?module=signetd1&op=411");
				elseif($locale=="623" && $allprefs['loc623']==0) redirect("runmodule.php?module=signetd1&op=orcs");
				elseif($locale=="521" && $allprefs['loc521']==0) redirect("runmodule.php?module=signetd1&op=ant");
				elseif($locale=="453" && $allprefs['loc453']==0) redirect("runmodule.php?module=signetd1&op=bantir");
				elseif($locale=="351" && $allprefs['loc411']==0) redirect("runmodule.php?module=signetd1&op=351");
				elseif($locale=="113" && $allprefs['loc113']==0) redirect("runmodule.php?module=signetd1&op=113");
				elseif($locale=="113" && $allprefs['loc113']==1 && $allprefs['loc113b']==0) redirect("runmodule.php?module=signetd1&op=113c");
				elseif($locale=="711"){
					$allprefs['loc711']=1;
					set_module_pref('allprefs',serialize($allprefs));
				}elseif($locale=="643"){
					$allprefs['loc711']=0;
					set_module_pref('allprefs',serialize($allprefs));
				//Random monsters
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
									switch(e_rand(1,9)){
										case 1:
											redirect("runmodule.php?module=signetd1&op=orc");
										break;
										case 2:
											redirect("runmodule.php?module=signetd1&op=bantir");
										break;
										case 3:
											redirect("runmodule.php?module=signetd1&op=ant");
										break;
										case 4:
											redirect("runmodule.php?module=signetd1&op=spider");
										break;
										case 5:
											redirect("runmodule.php?module=signetd1&op=orcs");
										break;
										case 6:
											redirect("runmodule.php?module=signetd1&op=dripslimes");
										break;
										case 7:
											redirect("runmodule.php?module=signetd1&op=rangers");	
										break;
										case 8:
											redirect("runmodule.php?module=signetd1&op=scribe");
										break;
										case 9:
											redirect("runmodule.php?module=signetd1&op=kobolds");
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
				$allprefs=unserialize(get_module_pref('allprefs'));
				addnav("Scrolls");
				addnav("1?Read Scroll 1","runmodule.php?module=signetd1&op=scroll1b");
				if ($allprefs['scroll2']==1) addnav("2?Read Scroll 2","runmodule.php?module=signetd1&op=scroll2b");
				if ($allprefs['scroll3']==1) addnav("3?Read Scroll 3","runmodule.php?module=signetd1&op=scroll3b");
				addnav("Directions");
				if($locale=="1279"){
					output("`nYou are at the entrance with Passages to the");
				}else{
					output("`nYou may go");
				}
				$umazeturn++;
				$allprefs['mazeturn']=$umazeturn;
				set_module_pref('allprefs',serialize($allprefs));
				$navcount = 0;
				$north=translate_inline("North");
				$south=translate_inline("South");
				$east=translate_inline("East");
				$west=translate_inline("West");
				$directions="";
				if ($navigate=="1" or $navigate=="5" or $navigate=="6"or $navigate=="7" or $navigate=="11" or $navigate=="12"or $navigate=="13" or $navigate=="15" or $navigate=="19"or $navigate=="20" or $navigate=="21") {
					addnav("North","runmodule.php?module=signetd1&op=n&loc=$locale");
					if ($allprefs['loc677']==1  && $locale=="643") {
						blocknav("runmodule.php?module=signetd1&op=n&loc=$locale");
					}else{
						$directions.=" $north";
						$navcount++;
					}
				}
				if ($navigate=="2" or $navigate=="5" or $navigate=="8"or $navigate=="9" or $navigate=="11" or $navigate=="12"or $navigate=="14" or $navigate=="15" or $navigate=="18" or $navigate=="19") {
					addnav("South","runmodule.php?module=signetd1&op=s&loc=$locale");
					if ($allprefs['loc677']==1  && $locale=="711") {
						blocknav("runmodule.php?module=signetd1&op=s&loc=$locale");
					}else{
						$navcount++;
						if ($navcount > 1) $directions.=",";
						$directions.=" $south";						
					}
				}
				if ($navigate=="4" or $navigate=="7" or $navigate=="9"or $navigate=="10" or $navigate=="12" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="21") {
					addnav("West","runmodule.php?module=signetd1&op=w&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $west";
				}
				if ($navigate=="3" or $navigate=="6" or $navigate=="8"or $navigate=="10" or $navigate=="11" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="18" or $navigate=="20") {
					addnav("East","runmodule.php?module=signetd1&op=e&loc=$locale");
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
			rawoutput(" = <img src=\"./modules/signetimg/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\"> ");
			output("`7Emergency Exit = E");
			//rawoutput(" = <img src=\"./modules/signetimg/mexit.gif\" title=\"\" alt=\"\" style=\"width: 5px; height: 5px;\"><big>");
			//$mapkey2="<table style=\"height: 130px; width: 110px; text-align: left;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td style=\"vertical-align: top;\">";
			rawoutput("<table style=\"height: 130px; width: 350px; text-align: absmiddle; line-height: 25px; font-size: 25px;\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td colspan=\"34\"></td>");
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
					if ($i==1278){
						//$mapkey.="<img src=\"./modules/signetimg/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px; background-color: 26FF00;\">&nbsp;</td>";
					//door Up Down
					}elseif (($i==53 && ($locale=="53" || $locale=="55")) || ($i==335 && ($locale=="337" || $locale=="335")) || ($i==464 && $allprefs['loc465']==0 && $locale=="464") || ($i==493 && $allprefs['loc494']==0 && $locale=="495") || ($i==688 && $allprefs['loc689']==0 && ($locale=="688" || $locale=="690")) || ($i==1005 && $allprefs['loc1006']==0 && ($locale=="1005" || $locale=="1007")) || ($i==1007 && $allprefs['loc1008']==0 && ($locale=="1009" || $locale=="1007")) || ($i==1014 && $allprefs['loc1015']==0 && ($locale=="1014" || $locale=="1016")) || ($i==1135 && $allprefs['loc891']==0 && $locale=="1137") || ($i==1215 && $allprefs['loc1216']==0 && $locale=="1215")) {
						//$mapkey.="<img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//lr door
					}elseif (($i==214 && ($locale=="249" || $locale=="181")) || ($i==302 && $allprefs['loc303']==0 && $locale=="337") || ($i==326 && ($locale=="361" || $locale=="293")) || ($i==428 && ($locale=="463" || $locale=="395")) || ($i==592 && $allprefs['loc593']==0 && $locale=="559") || ($i==676 && $allprefs['loc677']==0 && ($locale=="711" || $locale=="643")) || ($i==786 && $allprefs['loc787']==0 && ($locale=="821" || $locale=="753")) || ($i==972 && $allprefs['loc973']==0 && ($locale=="939" || $locale=="1007")) || ($i==982 && $allprefs['loc983']==0 && ($locale=="1017" || $locale=="949"))){
						//$mapkey.="<img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//red
					}elseif (($i==112 && $allprefs['loc113b']==0 && ($locale=="146" || $locale=="147" || $locale=="148" || $locale=="112" || $locale=="114" )) || ($i==255 && $allprefs['loc411']==0 && $locale=="257") || ($i==332 && $allprefs['loc333']==0 && ($locale=="334" || $locale=="367" || $locale=="368" || $locale=="299" || $locale=="300")) || ($i==350 && $allprefs['loc411']==0 && ($locale=="385" || $locale=="318" || $locale=="317")) || ($i==410 && $allprefs['loc411']==0 && $locale=="377") || ($i==684 && $allprefs['loc685']==0 && ($locale=="686" || $locale=="719" || $locale=="720"))){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//red
					}elseif (($i==898 && $allprefs['loc899']==0 && ($locale=="898" || $locale=="932" || $locale=="933" || $locale=="864" || $locale=="865")) || ($i==890 && $allprefs['loc891']==0 && ($locale=="892" || $locale=="926" || $locale=="925" || $locale=="858" || $locale=="857")) || ($i==1132 && $allprefs['loc1133']==0 && ($locale=="1134" || $locale=="1167" || $locale=="1168" || $locale=="1099" || $locale=="1100")) || ($i==1196 && $allprefs['loc1197']==0 && ($locale=="1196" || $locale=="1230" || $locale=="1231")) || ($i==1286 && $allprefs['loc1287']==0 && ($locale=="1286" || $locale=="1252" || $locale=="1253"))){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//trap closing door
					}elseif ($i==676 && $allprefs['loc677']==1){
						//$mapkey.="<img src=\"./modules/signetimg/20maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/20maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//emergency exits
					}elseif ($i==1262 || $i==758 || $i==478 || $i== 134 || $i== 507 || $i== 778){
						//$mapkey.="<img src=\"./modules/signetimg/mexit.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\">E</td>";
					//main map production
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
function signetd1_runevent($type){
	global $session;
}
?>