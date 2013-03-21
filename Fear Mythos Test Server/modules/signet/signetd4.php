<?php
	global $session;
	global $badguy;
	//most of this is modification of the abandoned castle module by Lonnyl,
	//and I couldn't have coded this without using his work as a template.
	$op = httpget('op');
	$locale = httpget('loc');
	$skill = httpget('skill');
	$knownmonsters = array('door','ooze','fiamma','guards','zombie','barbarian','hornets','torturer','warrior','troll','drunkendwarf','random');
	if (in_array($op, $knownmonsters) || $op == "fight" || $op == "run") {
		signetd4_fight($op);
		die;
	}
	$misc= array ('superuser','scroll1b','scroll2b','scroll3b','scroll4b','scroll5b','scroll6b','scroll7b','1177','1099','1059','1059b','1059c','717','717b','717c','717d','377','377b','178','178b','138','138b','138c','138d','182','182b','182c','182d','182e','114','250','152','152b','83','83b','scroll6','12','12b','12c','394','383','726','485','picklock','362','362b','93','93a','93b','93c','27','198','931','931b','1105','934','934b','1143','1143b','868','874','874b','635','635b','scroll7','reset','exits1','exits2','exits3');
	if (in_array($op,$misc)){
		signetd4_misc($op);
	}
	page_header("Fiamma's Fortress");
	if ($session['user']['hitpoints'] <=0) redirect("shades.php");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$umaze = get_module_pref('maze');
	$umazeturn = $allprefs['mazeturn'];
	$upqtemp = get_module_pref('pqtemp');
	if ($op == "" && $locale == "") {
		if (get_module_setting("exitsave")==0 || $allprefs['startloc']=="") $allprefs['startloc']=1279;
		//to complete:  Collect the Fire Signet, Defeat Fiamma, find Scroll 7
		if ($allprefs['firesignet']==1 && $allprefs['182']==1 && $allprefs['scroll7']==1){
			output("`c`b`&Fiamma's Fortress`0`b`c`n");
			output("`\$You have completed this dungeon and this will be your last visit to it. You may now leave at any time.`0`n`n");
			$allprefs['complete']=1;
			$allprefssale=unserialize(get_module_pref('allprefssale',"signetsale"));
			set_module_pref("hoftemp",4300+$allprefssale['completednum'],"signetsale");
		}elseif ($allprefs['startloc']==1279){
			$allprefs['header']=0;
			output("`c`b`&Fiamma's Fortress`0`b`c`n");
			output("`2You find yourself in a forbidding entranceway.  This may be more dangerous than you initially thought it was going to be.");
		}
		$locale=$allprefs['startloc'];
		$umazeturn = 0;
		$allprefs['mazeturn']=0;
		if (!isset($maze)){
			$maze = array(16,16,16,16,16,16,16,16,16,16,16,6,13,13,13,7,16,3,10,13,13,13,10,10,10,10,10,10,7,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,8,14,14,15,9,16,16,16,11,14,12,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,3,9,16,2,16,16,6,13,13,17,22,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,6,13,13,15,7,16,16,16,16,16,16,16,16,11,15,12,16,16,16,16,16,16,16,16,6,13,7,16,16,16,16,16,16,16,11,15,15,15,12,16,16,6,13,13,10,10,10,15,15,12,16,6,13,7,16,16,16,16,11,15,15,17,17,17,17,17,17,17,15,14,14,14,9,16,16,11,15,12,16,16,16,8,14,14,10,15,15,12,16,16,16,16,8,15,9,16,16,16,16,16,16,16,5,16,16,16,16,16,16,11,15,12,16,16,16,16,16,16,16,8,14,9,16,16,16,16,16,19,16,16,16,16,16,16,16,16,11,10,10,10,10,10,10,14,14,9,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,6,13,7,16,16,16,16,16,19,16,16,6,10,10,10,10,10,14,10,10,10,10,10,10,10,10,10,13,10,10,10,10,10,10,15,15,12,16,16,16,16,16,19,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,11,15,12,16,16,16,16,16,19,16,16,5,16,16,20,17,17,17,17,17,17,17,13,13,13,10,13,15,13,10,13,7,16,16,8,15,9,16,16,16,16,16,19,16,16,5,16,16,19,16,16,16,16,16,16,16,11,15,12,16,11,15,12,16,11,12,16,16,16,5,16,16,16,16,16,16,19,16,16,5,16,16,19,16,16,16,16,16,16,16,8,14,9,16,11,14,9,16,8,9,16,16,16,5,16,16,16,16,16,16,19,16,16,5,16,16,19,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,19,16,6,15,7,16,19,16,6,13,7,16,16,16,6,13,13,13,15,13,13,13,13,7,16,16,16,5,16,16,16,16,16,16,19,16,11,15,9,16,19,16,8,15,12,16,6,10,14,14,14,14,14,14,14,14,14,14,10,10,10,12,16,16,16,16,16,16,19,16,11,9,16,6,25,7,16,8,12,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,19,16,5,16,3,14,15,14,4,16,5,16,5,16,6,13,13,7,16,16,6,13,13,7,16,16,16,5,16,16,16,16,16,16,19,16,11,4,16,16,5,16,16,3,12,16,5,16,11,15,15,12,16,16,11,15,15,12,16,16,6,12,16,16,16,16,16,16,19,16,5,16,3,13,15,13,7,16,5,16,5,16,11,15,15,12,16,16,11,15,15,12,16,16,11,12,16,16,16,16,16,16,19,16,11,4,16,11,15,15,15,10,15,10,9,16,11,15,15,12,16,16,11,15,15,12,16,16,11,12,16,16,16,16,16,16,19,16,5,16,3,15,15,15,9,16,5,16,16,16,11,15,15,12,16,16,11,15,15,12,16,16,11,12,16,16,16,16,16,16,19,16,11,7,16,8,15,9,16,6,12,16,16,16,11,15,15,12,16,16,11,15,15,12,16,16,11,12,16,16,16,16,16,16,19,16,11,15,7,16,5,16,6,15,12,16,16,16,8,15,14,9,16,16,8,15,14,14,17,17,15,12,16,16,16,16,16,16,19,16,8,14,9,16,5,16,8,14,12,16,16,16,16,5,16,16,16,16,16,5,16,16,16,16,11,12,16,16,16,16,16,16,19,16,16,16,16,16,19,16,16,16,5,16,16,16,16,5,16,6,13,7,16,5,16,16,16,16,11,12,16,16,16,16,16,16,19,16,16,16,16,16,19,16,16,16,11,13,13,7,16,5,16,8,15,9,16,5,16,16,16,16,8,12,16,16,16,16,16,16,18,17,17,17,17,17,24,16,16,16,8,14,15,9,16,5,16,16,5,16,16,5,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,5,16,16,5,16,6,15,7,16,5,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,6,15,7,16,8,10,15,15,15,10,9,16,16,16,6,13,12,16,16,16,16,16,16,6,13,7,16,6,13,15,7,16,16,16,11,15,12,16,16,16,11,15,12,16,16,16,16,16,11,15,12,16,16,16,16,16,16,11,15,12,16,11,15,15,15,10,10,10,15,15,15,10,10,10,14,15,14,10,10,10,10,10,14,14,9,16,16,16,16,16,16,8,15,9,16,8,14,14,9,16,16,16,8,15,9,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,16,16,16,16,16,16,5,16,16,16,16,6,15,7,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,8,10,10,10,10,10,10,10,10,10,10,9,16,16,16,16,11,15,12,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,11,15,12,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,8,14,9,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16);
			$umaze = implode($maze,",");
			set_module_pref("maze", $umaze);
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op <> ""){
		if ($op == "n") {
			$locale+=34;
			redirect("runmodule.php?module=signetd4&loc=$locale");
		}
		if ($op == "s"){
			$locale-=34;
			redirect("runmodule.php?module=signetd4&loc=$locale");
		}
		if ($op == "w"){
			$locale-=1;
			redirect("runmodule.php?module=signetd4&loc=$locale");
		}
		if ($op == "e"){
			$locale+=1;
			redirect("runmodule.php?module=signetd4&loc=$locale");
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
				
				if ($locale=="868" ||$locale=="874" ||$locale=="1114" ||$locale=="1016" ||$locale=="982" ||$locale=="642" ||$locale=="1160" ||$locale=="1106" ||$locale=="1107" ||$locale=="1100" ||$locale=="1101" ||$locale=="334" ||$locale=="160" ||$locale=="256" ||$locale=="216" ||$locale=="482" ||$locale=="328" ||$locale=="893" || $locale=="1171" || $locale=="1029"|| $locale=="241" || $locale=="175" || $locale=="181" || $locale=="438" || $locale=="571" || $locale=="728" || $locale=="159"||$locale=="1039"||$locale=="1043"||$locale=="1111") $allprefs['header']=1;
				elseif ($locale=="1099"||$locale=="1097"|| $locale=="1063") $allprefs['header']=2;
				elseif ($locale=="931" ||$locale=="1001" ||$locale=="1126") $allprefs['header']=3;
				elseif ($locale=="207" || $locale=="174") $allprefs['header']=4;
				elseif ($locale=="83" ||$locale=="182") $allprefs['header']=5;
				elseif ($locale=="49"||$locale=="939") $allprefs['header']=6;
				elseif ($locale=="395"||$locale=="362") $allprefs['header']=7;
				elseif ($locale=="404"||$locale=="335") $allprefs['header']=8;
				elseif ($locale=="257"||$locale=="158") $allprefs['header']=9;
				elseif ($locale=="95"||$locale=="197"||$locale=="161") $allprefs['header']=10;
				elseif ($locale=="96") $allprefs['header']=11;
				elseif ($locale=="390"||$locale=="553") $allprefs['header']=12;
				elseif ($locale=="198") $allprefs['header']=13;
				elseif ($locale=="1105"||$locale=="1137"||$locale=="1102"||$locale=="1035"||$locale=="1116"||$locale=="1050") $allprefs['header']=14;
				elseif ($locale=="1111") $allprefs['header']=15;
				elseif ($locale=="834") $allprefs['header']=16;
				elseif ($locale=="840") $allprefs['header']=17;
				elseif ($locale=="948" ||$locale=="570" ||$locale=="676"||$locale=="561") $allprefs['header']=18;
				elseif ($locale=="863" ||$locale=="516" || $locale=="727") $allprefs['header']=19;
				elseif ($locale=="726" ||$locale=="588" ||$locale=="586" ||$locale=="621" || $locale=="859" || $locale=="725" || $locale=="621") $allprefs['header']=20;
				elseif ($locale=="1177" ||$locale=="1278" || $locale=="1280" || $locale=="1245") $allprefs['header']=21;
				elseif ($locale=="587") $allprefs['header']=22;
				elseif ($locale=="391" ||$locale=="394") $allprefs['header']=23;
				elseif ($locale=="973" ||$locale=="1040" ||$locale=="1042" ||$locale=="1108" ||$locale=="1109" ||$locale=="1110") $allprefs['header']=24;
				elseif ($locale=="897") $allprefs['header']=0;
				$title=array("","A Dirty Stone Hallway","Jail Cell","A Storage Room","Secret Control Room","Fiamma's Room","Dark Messy Closet","Monster Storage Area","A Dirty Smelly Barracks","A Trophy Room with Human and Elven Heads","Armor Storage Area","A Damp Cavern","A Secret Passageway","A Complete Torture Chamber","Guard Room","Main Entrance","The Great Hall","A Musty Library","Smelly Dirty Barracks","Arena Viewing Area","A Bloody Fighting Arena","A Forbidding Entranceway","A Loose Boulder","Storage Room","Atrium");
				$header=$allprefs['header'];
				output_notl("`b`^`c%s`b`c`0",translate_inline($title[$header]));
				if ($header==0) output("`n");
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				//Let's give characters with infravision/night vision a chance to "see" the secret door
				if ($locale=="961" && $session['user']['race'] == 'Elf' && e_rand(1,3)==1) output("`\$Your Elf eyes notice a secret passage to the west.`0`n");
				elseif ($locale=="961" && $session['user']['race'] == 'Dwarf' && e_rand(1,4)==1) output("`\$Your Dwarf eyes notice a secret passage to the west.`0`n");
				elseif ($locale=="961" && $session['user']['race'] == 'Felyne' && e_rand(1,5)==1) output("`\$Your Felyne eyes notice a secret passage to the west.`0`n");
				elseif ($locale=="791") output("You are in a fighting arena with menacing monsters to the south!`n");
				elseif ($locale=="1095") output("Someone seems to have dug an escape tunnel here.`n");
				elseif ($locale=="722" || $locale=="723" || $locale=="724") output("You see a crowd of nasty monsters watching you from above.  Someone shouts `#'Let the Fight Begin!'`0");
				elseif ($locale=="968" || $locale=="629") output("You find a tunnel that leads out of the fortress. This may come in handy!`n");
				elseif ($locale=="621" && $allprefs['loc587']==0) output("Something seems wrong with the wall to the south.`n");
				else output("`n");

				if (($locale=="1095" || $locale=="172" || $locale=="312" || $locale=="1118" || $locale=="303" || $locale=="968"||$locale=="629") && $allprefs['complete']==0) {
					if (get_module_setting("exitsave")>=1) addnav("Return to Village","runmodule.php?module=signetd4&op=exits1");
					elseif (get_module_pref("super","signetsale")==0 && $allprefs['complete']==0) villagenav();
				}
				$allprefsd3=unserialize(get_module_pref('allprefs','signetd3'));
				if ($allprefsd3['complete']==1 && $allprefsd3['reset']==0) redirect("runmodule.php?module=signetd4&op=reset");
				elseif($locale=="1177" && $allprefs['loc1143']==0) redirect("runmodule.php?module=signetd4&op=1177");
				elseif($locale=="1099" && $allprefs['loc1099']==0) redirect("runmodule.php?module=signetd4&op=1099");
				elseif($locale=="1059" && $allprefs['loc1059']==0) redirect("runmodule.php?module=signetd4&op=1059");
				elseif($locale=="717" && $allprefs['loc717']==0) redirect("runmodule.php?module=signetd4&op=717");			
				elseif($locale=="250" && $allprefs['loc250']==1) redirect("runmodule.php?module=signetd4&op=250");
				elseif($locale=="152" && $allprefs['loc152']==0) redirect("runmodule.php?module=signetd4&op=152");
				elseif($locale=="83" && $allprefs['loc83']==0) redirect("runmodule.php?module=signetd4&op=83");
				elseif($locale=="12" && $allprefs['loc12']==0) redirect("runmodule.php?module=signetd4&op=12");
				elseif($locale=="394" && $allprefs['loc394']==0) redirect("runmodule.php?module=signetd4&op=394");
				elseif($locale=="383" && $allprefs['loc383']==0) redirect("runmodule.php?module=signetd4&op=383");
				elseif($locale=="726" && $allprefs['loc726']==0) redirect("runmodule.php?module=signetd4&op=726");
				elseif($locale=="485" && $allprefs['loc485']==0) redirect("runmodule.php?module=signetd4&op=485");
				elseif($locale=="386" && $allprefs['loc386']==0) redirect("runmodule.php?module=signetd4&op=barbarian");
				elseif($locale=="459" && $allprefs['loc459']==0) redirect("runmodule.php?module=signetd4&op=hornets");
				elseif($locale=="312" && $allprefs['loc312']==0) redirect("runmodule.php?module=signetd4&op=troll");
				elseif($locale=="655" && $allprefs['loc655']==0) redirect("runmodule.php?module=signetd4&op=troll");
				elseif($locale=="689" && $allprefs['loc689']==0) redirect("runmodule.php?module=signetd4&op=warrior");
				elseif(($locale=="1057" && $allprefs['loc1057']==0)||($locale=="467" && $allprefs['loc467']==0)||($locale=="29" && $allprefs['loc29']==0)||($locale=="90" && $allprefs['loc90']==0)||($locale=="18" && $allprefs['loc18']==0)||($locale=="87" && $allprefs['loc87']==0)) redirect("runmodule.php?module=signetd4&op=ooze");
				elseif($locale=="93" && $allprefs['loc93']==0) redirect("runmodule.php?module=signetd4&op=93");
				elseif($locale=="27" && $allprefs['loc27']==0) redirect("runmodule.php?module=signetd4&op=27");
				elseif(($locale=="853" && $allprefs['loc853']==0)||($locale=="513" && $allprefs['loc513']==0)) redirect("runmodule.php?module=signetd4&op=zombie");
				elseif($locale=="198" && $allprefs['loc198']==0) redirect("runmodule.php?module=signetd4&op=198");
				elseif($locale=="362" && $allprefs['loc362']==0) redirect("runmodule.php?module=signetd4&op=362");
				elseif($locale=="931" && $allprefs['loc931']==0 && $allprefs['loc931b']==0) redirect("runmodule.php?module=signetd4&op=931");
				elseif($locale=="934" && $allprefs['loc934']==0 && $allprefs['loc934b']==0) redirect("runmodule.php?module=signetd4&op=934");
				elseif($locale=="377" && $allprefs['loc377']==0) redirect("runmodule.php?module=signetd4&op=377");
				elseif($locale=="178" && $allprefs['loc178']==0) redirect("runmodule.php?module=signetd4&op=178");
				elseif($locale=="138" && $allprefs['loc138']==0) redirect("runmodule.php?module=signetd4&op=138");
				elseif($locale=="182" && $allprefs['loc182']==0) redirect("runmodule.php?module=signetd4&op=182");
				elseif($locale=="868" && $allprefs['loc868']==0) redirect("runmodule.php?module=signetd4&op=868");
				elseif($locale=="635" && $allprefs['loc635']==0) redirect("runmodule.php?module=signetd4&op=635");
				elseif($locale=="948" && $allprefs['loc948']==0) redirect("runmodule.php?module=signetd4&op=warrior");
				elseif($locale=="561" && $allprefs['loc561']==0) redirect("runmodule.php?module=signetd4&op=troll");
				elseif($locale=="1143" && $allprefs['loc1143']==0 && $allprefs['loc1059']==1) redirect("runmodule.php?module=signetd4&op=1143");				
				elseif($locale=="1143" && $allprefs['loc1143']==0 && $allprefs['loc1059']==0) redirect("runmodule.php?module=signetd4&op=1143b");				
				elseif($locale=="874" && $allprefs['loc874']==0 && $allprefs['loc840']==0) redirect("runmodule.php?module=signetd4&op=874");				
				elseif($locale=="874" && $allprefs['loc874']==0 && $allprefs['loc840']==1) redirect("runmodule.php?module=signetd4&op=874b");				
				elseif($locale=="497" || $locale=="540") redirect("runmodule.php?module=signetd4&op=picklock");
				elseif($locale=="1105") redirect("runmodule.php?module=signetd4&op=1105");
				elseif($locale=="897" || $locale=="965" || $locale=="932") $allprefs['loc931b']=0;
				elseif($locale=="933" || $locale=="968") $allprefs['loc934b']=0;
				elseif($locale=="840") $allprefs['loc840']=0;
				elseif($locale=="908") $allprefs['loc840']=0;
				elseif($locale=="1104") $allprefs['loc1104']=1;
				elseif($locale=="1106") $allprefs['loc1104']=0;
				elseif($locale=="396") $allprefs['loc328']=0;
				elseif($locale=="328") $allprefs['loc328']=1;
				elseif($locale=="463") $allprefs['loc463']=1;
				elseif($locale=="531") $allprefs['loc463']=0;
				elseif($locale=="506") $allprefs['loc506']=1;
				elseif($locale=="574") $allprefs['loc506']=0;
				elseif($locale=="683") $allprefs['loc683']=1;
				elseif($locale=="751") $allprefs['loc683']=0;
				elseif($locale=="411") $allprefs['loc343']=0;
				elseif($locale=="343") $allprefs['loc343']=1;
				elseif($locale=="177") $allprefs['loc177']=1;
				elseif($locale=="179") $allprefs['loc177']=0;
				elseif($locale=="840") $allprefs['loc840']=1;
				elseif($locale=="908") $allprefs['loc840']=0;
				elseif($locale=="587") $allprefs['loc587']=1;
				//Random monsters
				elseif ($allprefs['randomp']<get_module_setting("random")){
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
											redirect("runmodule.php?module=signetd4&op=ooze");
										break;
										case 2:
											redirect("runmodule.php?module=signetd4&op=zombie");
										break;
										case 3:
											redirect("runmodule.php?module=signetd4&op=guards");
										break;
										case 4:
											redirect("runmodule.php?module=signetd4&op=barbarian");
										break;
										case 5:
											redirect("runmodule.php?module=signetd4&op=hornets");
										break;
										case 6:
											redirect("runmodule.php?module=signetd4&op=troll");
										break;
										case 7:
											redirect("runmodule.php?module=signetd4&op=warrior");	
										break;
										case 8: case 9: case 10: case 11:
											redirect("runmodule.php?module=signetd4&op=random");
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
				set_module_pref('allprefs',serialize($allprefs));
				$navcount = 0;
				$north=translate_inline("North");
				$south=translate_inline("South");
				$east=translate_inline("East");
				$west=translate_inline("West");
				$directions="";
				//Scrolls (Keep the old, add the new)
				addnav("Scrolls");
				addnav("1?Read Scroll 1","runmodule.php?module=signetd4&op=scroll1b");
				$allprefsd3=unserialize(get_module_pref('allprefs','signetd3'));
				$allprefsd2=unserialize(get_module_pref('allprefs','signetd2'));
				$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($allprefsd1['scroll2']==1) addnav("2?Read Scroll 2","runmodule.php?module=signetd4&op=scroll2b");
				if ($allprefsd1['scroll3']==1) addnav("3?Read Scroll 3","runmodule.php?module=signetd4&op=scroll3b");
				if ($allprefsd2['scroll4']==1) addnav("4?Read Scroll 4","runmodule.php?module=signetd4&op=scroll4b");
				if ($allprefsd3['scroll5']==1) addnav("5?Read Scroll 5","runmodule.php?module=signetd4&op=scroll5b");
				if ($allprefs['scroll6']==1) addnav("6?Read Scroll 6","runmodule.php?module=signetd4&op=scroll6b");
				if ($allprefs['scroll7']==1) addnav("7?Read Scroll 7","runmodule.php?module=signetd4&op=scroll7b");
				addnav("Directions");
				if($locale=="1279"){
					output("`nYou are at the entrance with passages to the");
				}else{
					output("`nYou may go");
				}
				$umazeturn++;
				$allprefs['mazeturn']=$umazeturn;
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($navigate=="1" or $navigate=="5" or $navigate=="6"or $navigate=="7" or $navigate=="11" or $navigate=="12"or $navigate=="13" or $navigate=="15" or $navigate=="19"or $navigate=="20" or $navigate=="21" or $navigate=="24" or $navigate=="25") {
					addnav("North","runmodule.php?module=signetd4&op=n&loc=$locale");
					$directions.=" $north";
					$navcount++;
				}
				if ($navigate=="2" or $navigate=="5" or $navigate=="8"or $navigate=="9" or $navigate=="11" or $navigate=="12"or $navigate=="14" or $navigate=="15" or $navigate=="18" or $navigate=="19" or $navigate=="22" or $navigate=="23" or $navigate=="24" or $navigate=="25") {
					addnav("South","runmodule.php?module=signetd4&op=s&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $south";
				}
				if ($navigate=="4" or $navigate=="7" or $navigate=="9"or $navigate=="10" or $navigate=="12" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="21" or $navigate=="22" or $navigate=="24" or $navigate=="25") {
					addnav("West","runmodule.php?module=signetd4&op=w&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $west";
				}
				if ($navigate=="3" or $navigate=="6" or $navigate=="8"or $navigate=="10" or $navigate=="11" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="18" or $navigate=="20" or $navigate=="25") {
					addnav("East","runmodule.php?module=signetd4&op=e&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $east";					
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
					$allprefs=unserialize(get_module_pref('allprefs'));
					if ($i==1278){
						//$mapkey.="<img src=\"./modules/signetimg/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px; background-color: 26FF00;\">&nbsp;</td>";
					//door Up Down
					}elseif (($i==1114 && ($locale=="1116" || $locale=="1114"))||($i==1112 && ($locale=="1112" || $locale=="1114"))||($i==1104 && ($locale=="1104" || $locale=="1106"))||($i==157 && ($locale=="157" || $locale=="159"))||($i==159 && ($locale=="159" || $locale=="161"))||($i==1098 && ($locale=="1098" || $locale=="1100"))||($i==255 && ($locale=="255" || $locale=="257"))||($i==333 && ($locale=="333" || $locale=="335"))||($i==725 && ($locale=="725" || $locale=="727"))||($i==197 && ($locale=="197" || $locale=="199"))){
						//$mapkey.="<img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//Red Dot
					}elseif (($i==1176 && $allprefs['loc1143']==0 && ($locale=="1210"||$locale=="1211" ||$locale=="1212" ||$locale=="1176" ||$locale=="1178"))||($i==930 && $allprefs['loc931']==0 && ($locale=="897"||$locale=="932" ||$locale=="965" ||$locale=="966"))||($i==151 && $allprefs['loc152']==0 && ($locale=="151"||$locale=="185" ||$locale=="186" ||$locale=="117" ||$locale=="118"))||($i==933 && $allprefs['loc934']==0 && ($locale=="933"||$locale=="967" ||$locale=="968"))||($i==1058 && $allprefs['loc1059']==0 && ($locale=="1058"||$locale=="1092" ||$locale=="1093"))){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					}elseif (($i==137 && $allprefs['loc138']==0 && ($locale=="172"||$locale=="173" ||$locale=="139"))||($i==11 && $allprefs['loc12']==0 && ($locale=="46"||$locale=="47" ||$locale=="13"))||($i==634 && $allprefs['loc635']==0 && ($locale=="636"||$locale=="669" ||$locale=="670"))||($i==92 && $allprefs['loc93']==0 && ($locale=="94"||$locale=="127" ||$locale=="128"))){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//door Left Right
					}elseif (($i==215 && ($locale=="182" || $locale=="250"))||($i==1015 && ($locale=="982" || $locale=="1050"))||($i==867 && ($locale=="902" || $locale=="834"))||($i==873 && ($locale=="908" || $locale=="840"))||($i==1142 && ($locale=="1177" || $locale=="1109"))||($i==1000 && ($locale=="967" || $locale=="1035"))||($i==361 && ($locale=="328" || $locale=="396"))||($i==82 && ($locale=="117" || $locale=="49"))||($i==496 && ($locale=="531" || $locale=="463"))||($i==539 && ($locale=="506" || $locale=="574"))||($i==972 && ($locale=="1007" || $locale=="939"))){
						//$mapkey.="<img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//emergency exits
					}elseif ($i==1094 || $i==311 || $i==302 || $i==1117 || $i==171){
						//$mapkey.="<img src=\"./modules/signetimg/mexit.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\">E</td>";
					//proximity emergency exits
					}elseif (($i==967 && ($locale=="967"||$locale=="933"||$locale=="934"))||($i==628 && ($locale=="630"||$locale=="663"||$locale=="664"))){
						//$mapkey.="<img src=\"./modules/signetimg/mexit.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\">E</td>";
					//Iluminated Hallway
					}elseif (($allprefs['loc394']==1 && ($i==382 || $i==383 || $i==384 || $i==385 || $i==386 || $i==387 || $i==388 || $i==389))||($allprefs['loc383']==1 && ($i==416 || $i==450 || $i==484 || $i==518 || $i==552))){
						//$mapkey.="<img src=\"./modules/signetimg/1maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/1maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
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
function signetd4_runevent($type){
	global $session;
}
?>