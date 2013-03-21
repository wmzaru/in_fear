<?php
 	for ($i = 10; $i < 13; $i++){
		$who=get_module_setting("prankon".$i);
		//check to see if anyone had the weapon prank pulled on them
		if ($who>0){
			$sql = "SELECT weapon FROM " . db_prefix("accounts") . " WHERE acctid='$who'";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$weapon=$row['weapon'];
			$oldweapon=get_module_setting("weapon".$i);
			$whichprank=get_module_setting("result".$i);
			$prankweapon=get_module_setting($whichprank."prank".$i);
			//if the current weapon is the same as the prank weapon, then update with the old weapon
			if ($weapon==$prankweapon){
				$sql = "UPDATE " . db_prefix("accounts") . " SET weapon='".addslashes($oldweapon)."' WHERE acctid=$who";
				db_query($sql);
			}
			set_module_setting("weapon".$i,"");
		}
	}
 	for ($i = 16; $i < 19; $i++){
		$who=get_module_setting("prankon".$i);
		//check to see if anyone had the title prank pulled on them
		if ($who>0){
			$sql = "SELECT name,title,login,ctitle FROM " . db_prefix("accounts") . " WHERE acctid='$who'";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$name = $row['name'];
			$title= $row['title'];
			$whichprank=get_module_setting("result".$i);
			//change the title only if the current title is the same as the prank title
			if ($title==get_module_setting($whichprank."prank".$i)){
				//From Lonny's code in Lonny's Castle
				require_once("lib/names.php");
				require_once("lib/titles.php");
				$newtitle=get_module_setting("title".$i);
				$newname = str_replace($title,$newtitle,$row['name']);
				$newtitle = str_replace($title,$newtitle,$row['title']);
				$sql2 = ("UPDATE ".db_prefix("accounts")." SET title=\"$newtitle\" WHERE login = '".$row['login']."'");
				db_query($sql2);
				if ($row['ctitle'] == ""){
					$sql2 = ("UPDATE ".db_prefix("accounts")." SET name=\"$newname\" WHERE login = '".$row['login']."'");
					db_query($sql2);
				}
			}
			set_module_setting("title".$i,"");
		}
	}
 	$sql = "update ".db_prefix("module_userprefs")." set value=19 where value<>0 and setting='pranker' and modulename='prankstore'";
 	db_query($sql);		
 	for ($i = 1; $i < 19; $i++){
		set_module_setting("prankon".$i,0);
		set_module_setting("result".$i,0);
		$random=e_rand(1,100);
		if ($random<=get_module_setting("percent".$i)){
			$type=e_rand(1,3);
			set_module_setting("result".$i,$type);
		}
	}
?>