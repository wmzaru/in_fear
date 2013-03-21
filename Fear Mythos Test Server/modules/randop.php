<?php

function randop_getmoduleinfo(){
	$info = array(
		"name"=>"Random Doppleganger",
		"author"=>"Chris Vorndran",
		"version"=>"0.1",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=96",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Player can encounter a doppelganger of another player, and get their weaponname switched.",
		);
	return $info;
}
function randop_install(){
	module_addeventhook("forest","return 100;");
	return true;
}
function randop_uninstall(){
	return true;
}
function randop_runevent($type){
	global $session;
	$op = httpget('op');
	$from = "forest.php";
	$session['user']['specialinc'] = "module:randop";

	if ($op == "" || $op == "search"){
		output("`@Walking down a narrow path of the forest, you notice a rustling noise in the bushes.");
		output(" You have heard tales of a Demon that has been haunting the woods lately.");
		output(" It has also been sucking out the souls of those that are evil.");
		output(" Will you be the Hero?");
		addnav("Be a Hero", $from."?op=hero");
		addnav("Wuss Out", $from."?op=leave");
	}elseif ($op == "hero"){
		$id = $session['user']['acctid'];
		$sql = "SELECT acctid,name,sex,weapon FROM ".db_prefix("accounts")." WHERE acctid<>".$id." ORDER BY RAND(".e_rand().") LIMIT 1";
		$res = db_query($sql);
		$row = db_fetch_assoc($res);
		$name = $row['name'];
		$sex = $row['sex'];
		$weapon = $row['weapon'];
		output("`@You puff out your chest and hustle over to the bush.");
		output(" You poke your %s`@ in there, and then hear a loud yelp.",$session['user']['weapon']);
		output(" Out from the bushes, springs `3%s`@.",$name);
		output(" %s rubs %s sore bottom and then charges at you.",translate_inline($sex==1?"She":"He"), translate_inline($sex==1?"her":"his"));
		output(" %s `@hits you head on and drops %s %s`@.",$name,translate_inline($sex==1?"her":"his"),$weapon);
		output(" You pick up %s %s`@ and run off into the woods, cackling madly.",translate_inline($sex==1?"her":"his"),$weapon);
		$session['user']['weapon'] = $weapon;
		addnews("%s`3 encountered a doppelganger of %s`3 and made off with %s %s.",$session['user']['name'],$name,translate_inline($sex==1?"her":"his"),$weapon);
		$session['user']['specialinc']="";
	}elseif($op == "leave"){
		output("`@You wander away, thinking better than to mess with the Demon of the Woods.");
		output(" Strangely, you feel wiser.");
		output(" You gain One Experience Point!");
		$session['user']['experience']++;
		$session['user']['specialinc']="";
	}
}
?>