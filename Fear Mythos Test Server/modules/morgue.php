<?php
function morgue_getmoduleinfo(){
	$info = array(
		"name"=>"Morgue",
		"version"=>"1.1",
		"author"=>"`#Lonny Luberts",
		"category"=>"PQcomp",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=91",
		"vertxtloc"=>"http://www.pqcomp.com/",
		"prefs"=>array(
			"Morgue Module User Prefs,title",
            "died"=>"How user died message,text|",
            "killdate"=>"Date Killed,text|",
        ),
        "settings"=>array(
			"Morgue Module Settings,title",
			"mourgeloc"=>"Where does the Morgue appear,location|".getsetting("villagename", LOCATION_FIELDS),
		),
	);
	return $info;
}

function morgue_install(){
	if (!is_module_active('morgue')){
		output("`4Installing Morgue Module.`n");
	}else{
		output("`4Updating Morgue Module.`n");
	}
	module_addhook("newday");
	module_addhook("village");
	module_addhook("battle-defeat");
	module_addhook("battle-victory");
	module_addhook("news-intercept");
	module_addhook("shades");
	return true;
}

function morgue_uninstall(){
	output("`4Un-Installing Morgue Module.`n");
	return true;
}

function morgue_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "village":
		if ($session['user']['location'] == get_module_setting("mourgeloc")){
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav("Ye Olde Morgue","runmodule.php?module=morgue");
		}
	break;
	case "battle-defeat":
		global $badguy;
		$diedhow = translate_inline("`# - Killed by ");
		$diedhow .= $badguy['creaturename'];
		$diedhow .= translate_inline("`# in ");
		$diedhow .= $session['user']['location'];
		$diedhow .= "`#.";
		set_module_pref("died",$diedhow);
		set_module_pref("killdate",date("Y-m-d h:m:s"));
	break;
	case "battle-victory":
		global $badguy;
		if ($badguy['type'] == 'pvp'){
			$diedhow = translate_inline("`# - Killed by ");
			$diedhow .= $session['user']['name'];
			$diedhow .= translate_inline("`# in ");
			$diedhow .= $session['user']['location'];
			$diedhow .= "`#.";
			set_module_pref("died",$diedhow,"morgue",$badguy['acctid']);
			set_module_pref("killdate",date("Y-m-d h:m:s"),"morgue",$badguy['acctid']);
		}
	break;
	case "newday":
		clear_module_pref("died");
		clear_module_pref("killdate");
	break;
	case "shades":
	case "news-intercept":
		if (!get_module_pref("killdate")) set_module_pref("killdate",date("Y-m-d h:m:s"));
	break;
	}
	return $args;
}

function morgue__runevent($type){
	
}

function morgue_run(){
global $session;
$op = httpget('op');
page_header("Ye Olde Morgue");
output("`@You step into the morgue and the stench almost kills you!`n");
output("`2There are bodies lying everywhere with tags hanging from their toes.`n");
output("`2You browse the tags hanging from the toes of the dead.`n`n");
$sql = "SELECT name,acctid,location FROM ".db_prefix("accounts")." INNER JOIN ".db_prefix("module_userprefs")." ON userid=acctid where alive = 0 AND modulename = 'morgue' AND setting = 'killdate' AND value > 0 ORDER BY value DESC LIMIT 50";
$result = db_query($sql);
if (db_num_rows($result) > 49) output("`2The morgue can only hold 50 bodies, so only the 50 most recent dead remain unburied.`n");
for ($i=1;$i<db_num_rows($result)+1;$i++){
	$row = db_fetch_assoc($result);
	$diedhow = get_module_pref("died","morgue",$row['acctid']);
	if ($diedhow == ""){
		$diedhow = translate_inline(" - was found in ");
		$diedhow .= $row['location'];
		$diedhow .=translate_inline(" dead of unknown causes.");
		
	}
	output_notl(($i%2?"`3":"`#").$i.") ".$row['name'].($i%2?"`3":"`#").$diedhow."`n`0");
}
if (db_num_rows($result) > 49){
	//bury the dead ones
	$sql2 = "SELECT acctid,name FROM ".db_prefix("accounts")." INNER JOIN ".db_prefix("module_userprefs")." ON userid=acctid where alive = 0 AND modulename = 'morgue' AND setting = 'killdate' AND value > 0 ORDER BY value DESC LIMIT 51,18446744073709551615";
	$result2 = db_query($sql2);
	for ($i=0;$i<db_num_rows($result2);$i++){
		$row2 = db_fetch_assoc($result2);
		clear_module_pref("died","morgue",$row2['acctid']);
		clear_module_pref("killdate","morgue",$row2['acctid']);
		//need more messages
		$randmess = array(
			1=>translate_inline("`^The worms get another meal!"),
			2=>translate_inline("`^More food for the worms!"),
			3=>translate_inline("`^Another warrior six feet under."),
			4=>translate_inline("`^The worms feast again!"),
			5=>translate_inline("`^Gone but not forgotten.  Yet!"),
			6=>translate_inline("`^The grass died all around the grave. Maybe ").$row2['name'].translate_inline("`^ should have bathed more often"),
			7=>$row2['name'].translate_inline("`^ was heard to say `3\"I don't mind dying, the trouble is you feel so bloody stiff the next day.\""),
			8=>$row2['name'].translate_inline("`^ is one of those people who is enormously improved by death."),
			9=>translate_inline("`^R.I.P."),
		);
		$m = e_rand(1,9);
		addnews("`^The Mortician buried the body of %s `^today! %s",$row2['name'],$randmess[$m]);
	}
}
villagenav();
page_footer();
}
?>