<?php
	global $session;
	global $badguy;
	//most of this is modification of the abandoned castle module by Lonnyl,
	//and I couldn't have coded this without using his work as a template.
	$op = httpget('op');
	$locale = httpget('loc');
	$skill = httpget('skill');
	$knownmonsters = array('random','door','blackwarlock','blackwarlocks','waterelemental','lostspirit','mierscri');
	if (in_array($op, $knownmonsters) || $op == "fight" || $op == "run") {
		signetd5_fight($op);
		die;
	}
	$misc= array ('superuser','scroll1b','scroll2b','scroll3b','scroll4b','scroll5b','scroll6b','scroll7b','1172','1138','1002','898','902','895','905','1104','724','736','792','804','687','691','701','705','pickdoor','860','872','fiamma','evad','william','niflescro','kilmor','niscosnat','arandee','wasser','sig1','sig2','sig3','sig4','sig5','sig6','sig7','sig8','warlockguard','mapfix','traphall','transporter','tohalls','tovill','335','503','503b','503c','503d','505','505b','505c','507','507b','507c','777','774','1012','1016','1016b','1220','1220b','1220c','1220d','deathbywand','834','766','766b','766c','948','849','1257','endgame','eg1','eg2','eg3','eg4','eg5','eg6','eg7','reset','eg1b','exits2','exits3');
	if (in_array($op,$misc)){
		signetd5_misc($op);
	}
	page_header("Mierscri's Lair");
	if ($session['user']['hitpoints'] <=0) redirect("shades.php");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$umaze = get_module_pref('maze');
	$umazeturn = $allprefs['mazeturn'];
	$upqtemp = get_module_pref('pqtemp');
	if ($op == "" && $locale == "") {
		output("`c`b`&Myerscri's Lair`0`b`c`n");
		if ($allprefs['startloc']==1274 || $allprefs['startloc']==""){
			output("`\$You only have two choices: Face your fears or run from them. You stand at the entrance to the most foul Lair you have ever encountered.  Which choice will you make?`0");
			$allprefs['startloc']=1274;
		}elseif ($allprefs['startloc']==796) output("`\$You enter `QTellusa's Chamber`\$. Are you ready to confront Mierscri?`0`n`n");
		$locale=$allprefs['startloc'];
		$umazeturn = 0;
		$allprefs['mazeturn']=0;
		if (!isset($maze)){
			$maze = array(16,16,16,16,16,16,27,16,16,16,27,16,27,16,27,16,27,16,27,16,27,16,16,16,27,16,16,16,27,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,19,16,19,16,19,16,19,16,19,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,19,16,19,16,19,16,19,16,19,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,19,16,19,16,19,16,19,16,19,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,19,16,19,16,19,16,19,16,19,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,19,16,19,16,19,16,19,16,19,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,19,16,19,16,19,16,19,16,19,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,19,16,19,16,19,16,19,16,19,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,19,16,19,16,19,16,19,16,19,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,19,16,19,16,19,16,19,16,19,16,16,16,19,16,6,13,15,13,7,16,16,16,16,16,16,16,16,16,5,26,26,26,5,16,19,16,19,16,19,16,19,16,5,26,26,26,5,16,11,15,15,15,12,16,16,16,16,16,16,16,16,16,5,16,16,16,5,16,19,16,19,16,19,16,19,16,5,16,16,16,5,16,11,15,15,15,12,16,16,16,16,16,16,16,16,16,5,16,16,16,5,16,5,26,5,16,5,26,5,16,5,16,16,16,5,16,11,15,15,15,12,16,16,16,16,16,16,16,16,16,5,16,16,16,5,16,5,16,5,16,5,16,5,16,5,16,16,16,5,16,11,15,15,15,12,16,16,16,16,16,16,16,16,16,8,10,13,10,9,16,8,13,9,16,8,13,9,16,8,10,13,10,9,16,8,14,15,14,9,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,19,16,16,16,19,16,16,16,16,19,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,20,17,17,17,22,16,16,16,16,19,16,16,16,19,16,16,16,16,18,17,17,17,21,16,19,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,19,16,19,16,16,16,16,16,16,16,16,16,19,16,16,6,13,7,16,16,16,19,16,16,16,19,16,16,16,6,13,7,16,16,19,16,19,16,16,16,16,16,16,16,16,16,19,16,16,11,15,12,16,16,16,19,16,16,16,19,16,16,16,11,15,12,16,16,19,16,19,16,16,16,16,16,16,16,16,16,18,17,17,14,14,15,17,17,17,22,16,16,16,18,17,17,17,15,14,14,17,17,22,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,6,13,13,13,7,16,16,16,19,16,16,16,20,17,17,22,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,11,15,15,15,12,16,16,16,19,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,8,14,14,14,9,16,16,16,19,16,16,16,19,16,16,16,20,17,17,21,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,19,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,6,13,15,17,17,17,17,13,13,13,17,17,17,17,15,13,7,16,19,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,11,15,12,16,16,16,16,11,15,12,16,16,16,16,11,15,12,16,19,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,8,14,9,16,16,16,16,8,15,9,16,16,16,16,8,14,9,16,19,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,18,17,17,17,24,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,6,15,7,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,11,15,12,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,8,15,9,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,23,16,16,19,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,2,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,23,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16,16);
			$umaze = implode($maze,",");
			set_module_pref("maze", $umaze);
		}
		set_module_pref('allprefs',serialize($allprefs));
	}
	if ($op <> ""){
		if ($op == "n") {
			$locale+=34;
			redirect("runmodule.php?module=signetd5&loc=$locale");
		}
		if ($op == "s"){
			$locale-=34;
			redirect("runmodule.php?module=signetd5&loc=$locale");
		}
		if ($op == "w"){
			$locale-=1;
			redirect("runmodule.php?module=signetd5&loc=$locale");
		}
		if ($op == "e"){
			$locale+=1;
			redirect("runmodule.php?module=signetd5&loc=$locale");
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
				if (get_module_pref("super","signetsale")==1 || $locale=="1274") villagenav();
				//This is to prevent players from getting 250 gems and timing out or cheating.
				if ($allprefs['loc503b']==1){
					output("You have been found in possession of stolen goods.  All of your `%gems`0, `^gold`0, and `^gold in the bank`0 has been confiscated.");
					$session['user']['gold']=0;
					$session['user']['gems']=0;
					$session['user']['goldinbank']=0;
					$allprefs['loc503b']=0;
				}
				if ($locale=="301"||$locale=="533"||$locale=="701"||$locale=="705"||$locale=="736"||$locale=="872"||$locale=="896"||$locale=="524"||$locale=="528"||$locale=="331"||$locale=="327"||$locale=="313"||$locale=="317"||$locale=="387"||$locale=="389"||$locale=="391"||$locale=="393"||$locale=="519"||$locale=="687"||$locale=="691"||$locale=="724"||$locale=="860"||$locale=="898"||$locale=="902"||$locale=="1138"||$locale=="1240") $allprefs['header']=1;
				elseif ($locale=="1002"||$locale=="1104") $allprefs['header']=2;
				elseif ($locale=="704"||$locale=="702"||$locale=="906"||$locale=="688"||$locale=="690"||$locale=="894"||$locale=="901"||$locale=="899"||$locale=="968") $allprefs['header']=3;
				elseif ($locale=="361"||$locale=="365"||$locale=="499"||$locale=="494"||$locale=="490"||$locale=="425"||$locale=="427"||$locale=="421"||$locale=="423"||$locale=="347"||$locale=="351"||$locale=="485") $allprefs['header']=4;
				elseif ($locale=="505"||$locale=="335") $allprefs['header']=5;
				elseif ($locale=="848"||$locale=="948"||$locale=="539") $allprefs['header']=6;
				elseif ($locale=="796") $allprefs['header']=7;
				elseif ($locale=="849") $allprefs['header']=8;
				elseif ($locale=="1274") $allprefs['header']=0;
				$title=array("","A Very Dark, Foul-Smelling Hallway","An Entrance Hall Carved Out of Black Rock","A Guard Room","A Hallway Lit With Torches","A Frightening Throne Room","A Dark, Narrow Cavern","A Peaceful Alcove","A Hallway with an Eerie Glow");
				$header=$allprefs['header'];
				output_notl("`b`^`c%s`b`c`0",translate_inline($title[$header]));
				if ($header==0) output("`n");
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($locale=="743" && $allprefs['loc777']==0) output("`0The `)Dark Lord`0 stands before you.`n");
				else output("`n");
				
				//Set the transporter
				$exit=$allprefs['loc109']+$allprefs['loc113']+$allprefs['loc115']+$allprefs['loc117']+$allprefs['loc119']+$allprefs['loc121']+$allprefs['loc123']+$allprefs['loc127'];
				if($locale=="109" && $allprefs['loc109']==0){
					$allprefs['loc109']=1;
					if ($exit>=4) $allprefs['transport']=1;
				}elseif($locale=="113" && $allprefs['loc113']==0){
					$allprefs['loc113']=1;
					if ($exit>=4) $allprefs['transport']=2;
				}elseif($locale=="115" && $allprefs['loc115']==0){
					$allprefs['loc115']=1;
					if ($exit>=4) $allprefs['transport']=3;
				}elseif($locale=="117" && $allprefs['loc117']==0){
					$allprefs['loc117']=1;
					if ($exit>=4) $allprefs['transport']=4;
				}elseif($locale=="119" && $allprefs['loc119']==0){
					$allprefs['loc119']=1;
					if ($exit>=4) $allprefs['transport']=5;
				}elseif($locale=="121" && $allprefs['loc121']==0){
					$allprefs['loc121']=1;
					if ($exit>=4) $allprefs['transport']=6;
				}elseif($locale=="123" && $allprefs['loc123']==0){
					$allprefs['loc123']=1;
					if ($exit>=4) $allprefs['transport']=7;
				}elseif($locale=="127" && $allprefs['loc127']==0){
					$allprefs['loc127']=1;
					if ($exit>=4) $allprefs['transport']=8;
				}
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				$allprefsd4=unserialize(get_module_pref('allprefs','signetd4'));
				if ($allprefsd4['complete']==1 && $allprefsd4['reset']==0) redirect("runmodule.php?module=signetd5&op=reset");
				elseif($locale=="1172" && $allprefs['loc1172']==0) redirect("runmodule.php?module=signetd5&op=1172");
				elseif($locale=="1138" && $allprefs['loc1138']==0) redirect("runmodule.php?module=signetd5&op=1138");
				elseif($locale=="1002" && $allprefs['loc1002']==0) redirect("runmodule.php?module=signetd5&op=1002");
				elseif($locale=="898" && $allprefs['loc898']==0) redirect("runmodule.php?module=signetd5&op=898");
				elseif($locale=="902" && $allprefs['loc902']==0) redirect("runmodule.php?module=signetd5&op=902");
				elseif($locale=="895" && $allprefs['loc895']==0) redirect("runmodule.php?module=signetd5&op=895");
				elseif($locale=="905" && $allprefs['loc905']==0) redirect("runmodule.php?module=signetd5&op=905");
				elseif($locale=="1104" && $allprefs['loc1104b']==0) redirect("runmodule.php?module=signetd5&op=1104");
				elseif($locale=="792" && $allprefs['loc792']==0) redirect("runmodule.php?module=signetd5&op=792");
				elseif($locale=="724" && $allprefs['loc724']==0) redirect("runmodule.php?module=signetd5&op=724");
				elseif($locale=="687" && $allprefs['loc687']==0) redirect("runmodule.php?module=signetd5&op=687");
				elseif($locale=="691" && $allprefs['loc691']==0) redirect("runmodule.php?module=signetd5&op=691");
				elseif($locale=="701" && $allprefs['loc701']==0) redirect("runmodule.php?module=signetd5&op=701");
				elseif($locale=="705" && $allprefs['loc705']==0) redirect("runmodule.php?module=signetd5&op=705");
				elseif($locale=="860" && $allprefs['loc860']==0) redirect("runmodule.php?module=signetd5&op=860");
				elseif($locale=="872" && $allprefs['loc872']==0) redirect("runmodule.php?module=signetd5&op=872");
				elseif($locale=="736" && $allprefs['loc736']==0) redirect("runmodule.php?module=signetd5&op=736");
				elseif($locale=="804" && $allprefs['loc804']==0) redirect("runmodule.php?module=signetd5&op=804");
				elseif(($locale=="685" && $allprefs['loc685']==0)||($locale=="694" && $allprefs['loc694']==0)||($locale=="698" && $allprefs['loc698']==0)||($locale=="707" && $allprefs['loc707']==0)) redirect("runmodule.php?module=signetd5&op=warlockguard");
				elseif(($locale=="1104" && $allprefs['loc1104']==0)||($locale=="519" && $allprefs['loc519']==0)||($locale=="524" && $allprefs['loc524']==0)||($locale=="528" && $allprefs['loc528']==0)||($locale=="533" && $allprefs['loc533']==0)) redirect("runmodule.php?module=signetd5&op=blackwarlock");
				elseif ((($locale=="421" || $locale=="423") && $allprefs['loc421']==0)||(($locale=="425" || $locale=="427") && $allprefs['loc425']==0)||(($locale=="347" || $locale=="351") && $allprefs['loc347']==0)||(($locale=="361" || $locale=="365") && $allprefs['loc361']==0)) redirect("runmodule.php?module=signetd5&op=mapfix");
				elseif(($locale=="381" && $allprefs['loc381']==0)||($locale=="385" && $allprefs['loc385']==0)||($locale=="387" && $allprefs['loc387']==0)||($locale=="389" && $allprefs['loc389']==0)||($locale=="391" && $allprefs['loc391']==0)||($locale=="393" && $allprefs['loc393']==0)||($locale=="395" && $allprefs['loc395']==0)||($locale=="399" && $allprefs['loc399']==0)) redirect("runmodule.php?module=signetd5&op=waterelemental");
				elseif(($locale=="279" && $allprefs['loc279']==0)||($locale=="283" && $allprefs['loc283']==0)||($locale=="285" && $allprefs['loc285']==0)||($locale=="287" && $allprefs['loc287']==0)||($locale=="289" && $allprefs['loc289']==0)||($locale=="291" && $allprefs['loc291']==0)||($locale=="293" && $allprefs['loc293']==0)||($locale=="297" && $allprefs['loc297']==0)) redirect("runmodule.php?module=signetd5&op=traphall");
				elseif(($locale=="143" && $allprefs['loc143']==0)||($locale=="147" && $allprefs['loc147']==0)||($locale=="149" && $allprefs['loc149']==0)||($locale=="151" && $allprefs['loc151']==0)||($locale=="153" && $allprefs['loc153']==0)||($locale=="155" && $allprefs['loc155']==0)||($locale=="157" && $allprefs['loc157']==0)||($locale=="161" && $allprefs['loc161']==0)) redirect("runmodule.php?module=signetd5&op=lostspirit");
				elseif($locale=="7" && $allprefs['transport']==1) redirect("runmodule.php?module=signetd5&op=transporter");
				elseif($locale=="7") redirect("runmodule.php?module=signetd5&op=tovill");
				elseif($locale=="11" && $allprefs['transport']==2) redirect("runmodule.php?module=signetd5&op=transporter");
				elseif($locale=="11") redirect("runmodule.php?module=signetd5&op=tovill");
				elseif($locale=="13" && $allprefs['transport']==3) redirect("runmodule.php?module=signetd5&op=transporter");
				elseif($locale=="13") redirect("runmodule.php?module=signetd5&op=tovill");
				elseif($locale=="15" &&$allprefs['transport']==4) redirect("runmodule.php?module=signetd5&op=transporter");
				elseif($locale=="15") redirect("runmodule.php?module=signetd5&op=tovill");
				elseif($locale=="17" && $allprefs['transport']==5) redirect("runmodule.php?module=signetd5&op=transporter");
				elseif($locale=="17") redirect("runmodule.php?module=signetd5&op=tovill");
				elseif($locale=="19" && $allprefs['transport']==6) redirect("runmodule.php?module=signetd5&op=transporter");
				elseif($locale=="19") redirect("runmodule.php?module=signetd5&op=tovill");
				elseif($locale=="21" && $allprefs['transport']==7) redirect("runmodule.php?module=signetd5&op=transporter");
				elseif($locale=="21") redirect("runmodule.php?module=signetd5&op=tovill");
				elseif($locale=="25" && $allprefs['transport']==8) redirect("runmodule.php?module=signetd5&op=transporter");
				elseif($locale=="25") redirect("runmodule.php?module=signetd5&op=tovill");
				elseif($locale=="29") redirect("runmodule.php?module=signetd5&op=tohalls");
				elseif($locale=="335" && $allprefs['loc335']==0) redirect("runmodule.php?module=signetd5&op=335");
				elseif($locale=="503" && $allprefs['loc503']==0) redirect("runmodule.php?module=signetd5&op=503");
				elseif($locale=="505" && $allprefs['loc505']==0) redirect("runmodule.php?module=signetd5&op=505");
				elseif($locale=="507" && $allprefs['loc507']==0) redirect("runmodule.php?module=signetd5&op=507");
				elseif($locale=="777" && $allprefs['loc777']==0) redirect("runmodule.php?module=signetd5&op=777");
				elseif($locale=="774" && $allprefs['loc774']==0) redirect("runmodule.php?module=signetd5&op=774");
				elseif($locale=="1012" && $allprefs['loc1012']==0) redirect("runmodule.php?module=signetd5&op=1012");
				elseif($locale=="1016" && $allprefs['loc1016']==0) redirect("runmodule.php?module=signetd5&op=1016");
				elseif($locale=="1016") redirect("runmodule.php?module=signetd5&op=1016b");
				elseif($locale=="1220") redirect("runmodule.php?module=signetd5&op=1220");
				elseif($locale=="766" && $allprefs['loc766']==0) redirect("runmodule.php?module=signetd5&op=766");
				elseif($locale=="766") redirect("runmodule.php?module=signetd5&op=766b");
				elseif($locale=="834" && $allprefs['loc834']==0) redirect("runmodule.php?module=signetd5&op=834");
				elseif($locale=="849" && $allprefs['loc849']==0) redirect("runmodule.php?module=signetd5&op=849");
				elseif($locale=="1257") redirect("runmodule.php?module=signetd5&op=1257");
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
									switch(e_rand(1,7)){
										case 1:
											redirect("runmodule.php?module=signetd5&op=blackwarlock");
										break;
										case 2:
											redirect("runmodule.php?module=signetd5&op=waterelemental");
										break;
										case 3:
											redirect("runmodule.php?module=signetd5&op=lostspirit");
										break;
										case 4: case 5: case 6: case 7:
											redirect("runmodule.php?module=signetd5&op=random");
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
				$north=translate_inline("North");
				$south=translate_inline("South");
				$east=translate_inline("East");
				$west=translate_inline("West");
				$directions="";
				//Scrolls (Keep the old, add the new)
				addnav("Scrolls");
				addnav("1?Read Scroll 1","runmodule.php?module=signetd5&op=scroll1b");
				$allprefsd4=unserialize(get_module_pref('allprefs','signetd4'));
				$allprefsd3=unserialize(get_module_pref('allprefs','signetd3'));
				$allprefsd2=unserialize(get_module_pref('allprefs','signetd2'));
				$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($allprefsd1['scroll2']==1) addnav("2?Read Scroll 2","runmodule.php?module=signetd5&op=scroll2b");
				if ($allprefsd1['scroll3']==1) addnav("3?Read Scroll 3","runmodule.php?module=signetd5&op=scroll3b");
				if ($allprefsd2['scroll4']==1) addnav("4?Read Scroll 4","runmodule.php?module=signetd5&op=scroll4b");
				if ($allprefsd3['scroll5']==1) addnav("5?Read Scroll 5","runmodule.php?module=signetd5&op=scroll5b");
				if ($allprefsd4['scroll6']==1) addnav("6?Read Scroll 6","runmodule.php?module=signetd5&op=scroll6b");
				if ($allprefsd4['scroll7']==1) addnav("7?Read Scroll 7","runmodule.php?module=signetd5&op=scroll7b");
				addnav("Directions");
				if($locale=="1274"){
					output("`nYou are at the entrance with passages to the");
				}else{
					output("`nYou may go");
				}
				$umazeturn++;
				$allprefs['mazeturn']=$umazeturn;
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($navigate=="1" or $navigate=="5" or $navigate=="6"or $navigate=="7" or $navigate=="11" or $navigate=="12"or $navigate=="13" or $navigate=="15" or $navigate=="19"or $navigate=="20" or $navigate=="21" or $navigate=="24" or $navigate=="25" or $navigate=="27") {
					if ($locale=="948"){
						blocknav("runmodule.php?module=signetd5&op=n&loc=$locale");
					}else{
						addnav("North","runmodule.php?module=signetd5&op=n&loc=$locale");
						$directions.=" $north";
						$navcount++;
					}
				}
				if ($navigate=="2" or $navigate=="5" or $navigate=="8"or $navigate=="9" or $navigate=="11" or $navigate=="12"or $navigate=="14" or $navigate=="15" or $navigate=="18" or $navigate=="19" or $navigate=="22" or $navigate=="23" or $navigate=="24" or $navigate=="25") {
					addnav("South","runmodule.php?module=signetd5&op=s&loc=$locale");
					$navcount++;
					if ($navcount > 1) $directions.=",";
					$directions.=" $south";
				}
				if ($navigate=="4" or $navigate=="7" or $navigate=="9"or $navigate=="10" or $navigate=="12" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="21" or $navigate=="22" or $navigate=="24" or $navigate=="25") {
					addnav("West","runmodule.php?module=signetd5&op=w&loc=$locale");
					if ($locale=="1016") {
						blocknav("runmodule.php?module=signetd5&op=w&loc=$locale");
					}else{
						$navcount++;
						if ($navcount > 1) $directions.=",";
						$directions.=" $west";						
					}
				}
				if ($navigate=="3" or $navigate=="6" or $navigate=="8"or $navigate=="10" or $navigate=="11" or $navigate=="13"or $navigate=="14" or $navigate=="15" or $navigate=="17" or $navigate=="18" or $navigate=="20" or $navigate=="25") {
					addnav("East","runmodule.php?module=signetd5&op=e&loc=$locale");
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
					if ($i==1273){
						//$mapkey.="<img src=\"./modules/signetimg/mgreen.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px; background-color: 26FF00;\">&nbsp;</td>";
					//door Left Right
					}elseif (($i==859 && ($locale=="894" || $locale=="826"))||($i==871 && ($locale=="906" || $locale=="838"))||($i==1137 && ($locale=="1172" || $locale=="1104"))||($i==1001 && ($locale=="1036" || $locale=="1002"))||($i==735 && ($locale=="770" || $locale=="702"))||($i==723 && ($locale=="758" || $locale=="690"))){
						//$mapkey.="<img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/lrdoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//door Up Down
					}elseif (($i==704 && ($locale=="704" || $locale=="706"))||($i==700 && ($locale=="700" || $locale=="702"))||($i==686 && ($locale=="686" || $locale=="688"))||($i==690 && ($locale=="690" || $locale=="692"))||($i==897 && ($locale=="897" || $locale=="899"))||($i==894 && ($locale=="894" || $locale=="896"))||($i==904 && ($locale=="904" || $locale=="906"))||($i==901 && ($locale=="901" || $locale=="903"))){
						//$mapkey.="<img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/uddoor.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//Wand
					}elseif ($i==1219 && $locale=="1186"){
						//$mapkey.="<img src=\"./modules/signetimg/wand.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/wand.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//Dark Lord
					}elseif (($i==776 && $locale=="743" && $allprefs['loc777']==0)||($i==773 && $locale=="775" && $allprefs['loc774']==0)||($i==1011 && $locale=="978" && $allprefs['loc1012']==0)||($i==1015 && $locale=="1015" && $allprefs['loc1016']==0)){
						//$mapkey.="<img src=\"./modules/signetimg/dl.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/dl.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//Dark Lord Hallway
					}elseif ((($i==848 || $i==882 || $i==916 || $i==950 || $i==984 || $i==1018 || $i==1052 || $i==1086 || $i==1120 || $i==1154 || $i==1188 || $i==1222) && $allprefs['loc849']==1)){
						//$mapkey.="<img src=\"./modules/signetimg/darkhall.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/darkhall.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					}elseif($i==1256 && $allprefs['loc849']==1){
						//$mapkey.="<img src=\"./modules/signetimg/dl2.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/dl2.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//Red Dot
					}elseif (($i==502 && $allprefs['loc503']==0 && ($locale=="469" || $locale=="470" ||$locale=="504"))||($i==504 && $allprefs['loc505']==0 && ($locale=="506" || $locale=="470" ||$locale=="504" ||$locale=="471" ||$locale=="472"))||($i==506 && $allprefs['loc507']==0 && ($locale=="472" || $locale=="473" ||$locale=="506"))||($i==833 && $allprefs['loc834']==0 && ($locale=="833" || $locale=="799" ||$locale=="800"))||($i==765 && ($locale=="799" || $locale=="800" ||$locale=="765"))){
						//$mapkey.="<img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mred.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//Red Dot on Black
					}elseif (($i==6 && $locale=="41" && $allprefs['transport']==1)||($i==10 && $locale=="45" && $allprefs['transport']==2)||($i==12 && $locale=="47" && $allprefs['transport']==3)||($i==14 && $locale=="49" && $allprefs['transport']==4)||($i==16 && $locale=="51" && $allprefs['transport']==5)||($i==18 && $locale=="53" && $allprefs['transport']==6)||($i==20 && $locale=="55" && $allprefs['transport']==7)||($i==24 && $locale=="59" && $allprefs['transport']==8)||($i==28 && $locale=="63")){
						//$mapkey.="<img src=\"./modules/signetimg/mredb.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/mredb.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
					//proximity emergency exits
					}elseif (($i==6 && $locale=="41" && $allprefs['transport']<>1)||($i==10 && $locale=="45" && $allprefs['transport']<>2)||($i==12 && $locale=="47" && $allprefs['transport']<>3)||($i==14 && $locale=="49" && $allprefs['transport']<>4)||($i==16 && $locale=="51" && $allprefs['transport']<>5)||($i==18 && $locale=="53" && $allprefs['transport']<>6)||($i==20 && $locale=="55" && $allprefs['transport']<>7)||($i==24 && $locale=="59" && $allprefs['transport']<>8)){
						//$mapkey.="<img src=\"./modules/signetimg/mexit.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";					
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\">E</td>";
					//Corrected Map
					}elseif (($allprefs['loc421']==1 && $i==421)||($allprefs['loc425']==1 && $i==425)||($allprefs['loc347']==1 && ($i==347||$i==348||$i==349))||($allprefs['loc361']==1 && ($i==361||$i==362||$i==363))){
						//$mapkey.="<img src=\"./modules/signetimg/16maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\">";
						$mapkey.="<td style=\"width: 10px; height: 10px; padding-right: 0px;\"><img src=\"./modules/signetimg/16maze.gif\" title=\"\" alt=\"\" style=\"width: 10px; height: 10px;\"></td>";					
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
function signetd5_runevent($type){
	global $session;
}
?>