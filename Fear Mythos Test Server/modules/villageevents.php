<?php
function villageevents_getmoduleinfo(){
	$info = array(
		"name"=>"Village Events",
		"version"=>"20070207",
		"author"=>"<a href='http://www.sixf00t4.com' target=_new>Sixf00t4</a>",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1094",
		"description"=>"Random events in the commentary to add to the RP atmosphere.",
		"vertxtloc"=>"http://www.legendofsix.com/",
		"settings"=>array(
			"village events Settings,title",
			"npcloc"=>"Where does village events Hang Out?,location|".getsetting("villagename", LOCATION_FIELDS),
			"howmuch"=>"How often do events occur?,enum,1500,A Lot,2000,Quite a Bit,2500,Less,3000,Seldom",
			"basevalue"=>"base value to compare 'howmuch' < basevalue ,int|12",
			"comment1"=>"Custom Commentary 1,text|::A dark shadow is cast on the ground as a dragon flies overhead",
			"comment2"=>"Custom Commentary 2,text|::An imp scuttles across the village square.",
			"comment3"=>"Custom Commentary 3,text|::A loud cry is heard coming from the forest.",
			"npcid"=>" User id,int",
			"name"=>"NPC Name,text|`&",
		),
	);
	return $info;
}

function villageevents_install(){
	$password=$_POST['pw'];
	if (!is_module_active('villageevents')){
		output("`4Installing village events Module.`n");
		if ($password){
		$sql = "INSERT INTO ".db_prefix("accounts")." (login,name,sex,specialty,level,defense,attack,alive,laston,hitpoints,maxhitpoints,gems,password,emailvalidation,title,weapon,armor,race) VALUES ('villageevents','`&','0','1','15','1000','1000','1','".date("Y-m-d H:i:s")."','1000','1000','10','".md5(md5("$password"))."','','','`#','`!','')";
		db_query($sql) or die(db_error(LINK));
			if (db_affected_rows(LINK)>0){
				output("`2Installed village events!`n");
			}else{
				output("`4village events install failed!`n");
			}
			$sql = "SELECT acctid FROM ".db_prefix("accounts")." where login = 'villageevents'";
			$result = mysql_query($sql) or die(db_error(LINK));
			$row = db_fetch_assoc($result);
			if ($row['acctid'] > 0){
				set_module_setting("npcid",$row['acctid']);
				output("`2Set Accout ID for village events to ".$row['acctid'].".`n");
			}else{
				output("`4Failed to Set Account ID for village events!`n");
			}
		}else{
			$sqlz = "SELECT acctid FROM ".db_prefix("accounts")." where login = 'villageevents'";
			$resultz = mysql_query($sqlz) or die(db_error(LINK));
			$rowz = db_fetch_assoc($resultz);
			if ($rowz['acctid'] > 0){
			}else{
				output("village events's Login will be villageevents.`n");
				output("What would you like the password for village events's account to be?`n");
				$linkcode="<form action='modules.php?op=install&module=villageevents' method='POST'>";
				output("%s",$linkcode,true);
				$linkcode="<p><input type=\"text\" name=\"pw\" size=\"37\"></p>";
				output("%s",$linkcode,true);
				$linkcode="<p><input type=\"submit\" value=\"Submit\" name=\"B1\"><input type=\"reset\" value=\"Reset\" name=\"B2\"></p>";
				output("%s",$linkcode,true);
				$linkcode="</form>";
				output("%s",$linkcode,true);
				addnav("","modules.php?op=install&module=villageevents");
			}
		}
	}else{
		output("`4Updating village events Module.`n");
	}
	module_addhook("village");
	return true;
}

function villageevents_uninstall(){
	output("`4Un-Installing village events Module.`n");
	$sql = "DELETE FROM ".db_prefix("accounts")." where acctid='".get_module_setting('npcid')."'";
	mysql_query($sql);
	output("village events deleted.`n");
	return true;
}

function villageevents_dohook($hookname,$args){
		global $session,$texts;
			if (get_module_setting('lastupdate') < date("Y-m-d H:i:s")){
				set_module_setting('lastupdate',date("Y-m-d H:i:s",strtotime("- 5300 seconds")));
				$sqlz2="UPDATE ".db_prefix("accounts")." SET laston = '".date("Y-m-d H:i:s",strtotime("- 5300 seconds"))."', alive = '1', lasthit = '".date("Y-m-d H:i:s",strtotime("- 5300 seconds"))."', location = '".get_module_setting('npcloc')."', hitpoints = '1000' WHERE acctid = '".get_module_setting('npcid')."'";
				mysql_query($sqlz2) or die(db_error(LINK));
				$sqlz2 = "DELETE FROM ".db_prefix("mail")." WHERE msgto='".get_module_setting('npcid')."'";
				mysql_query($sqlz2) or die(db_error(LINK));
			}
			//now lets say something
			$howmuch = e_rand(1,get_module_setting('howmuch'));
			$basevalue = get_module_setting("basevalue","villageevents");            

            if ($howmuch < $basevalue){
					
			//setup commentary array
			$k = e_rand(1,14);
			$sayit = array(
						1=>get_module_setting('comment1'),
						2=>"::A dark shadow is cast on the ground as a dragon flies overhead",
						3=>get_module_setting('comment2'),
						4=>"::An imp scuttles across the village square.",
						5=>get_module_setting('comment3'),
						6=>"::A loud cry is heard coming from the forest.",
						7=>"::Someone's goat comes over and starts to nibble on ".$session['user']['name']."'s clothing.",
						8=>"::a devilish imp scuttles away from the forest and hides behind ".$session['user']['name']."'s leg.",
						9=>"::A hush falls over the masses as a mysterious breeze flows through the village.",
						10=>"::the village is full of talk about the latest dragon slayer.",
						11=>"::A dragon can be seen circling above the village, taunting those below",
						12=>"::A cold breeze sweeps through the village as another villager becomes a meal to the dragon",
						13=>"::An eagle soaring by drops some love in ".$session['user']['name']."'s eye.",
						14=>"::A drunk villager stumbles from the inn muttering uninteligably.",
						);
			//end setup commentary array
				mysql_query("INSERT INTO ".db_prefix("commentary")." (postdate,section,author,comment) VALUES (now(),'".$texts['section']."','".get_module_setting('npcid')."',\"".$sayit[$k]."\")");
			}
return $args;    
}
?>
