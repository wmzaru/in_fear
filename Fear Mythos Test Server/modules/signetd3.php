<?php
//Date:  March 8, 2006
function signetd3_getmoduleinfo(){
	$info = array(
		"name"=>"Signet Maze 3: `!Wasser's Castle",
		"version"=>"5.22",
		"author"=>"DaveS",
		"category"=>"Signet Series",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		"settings"=>array(
			"Wasser's Castle Settings,title",
			"random"=>"How many random monsters can be encountered in the castle?,int|5",
			"randenc"=>"Likelihood of random encounter,enum,1,Common,5,Uncommon,10,Rare,15,Very Rare,20,Extremely Rare|10",
			"healing"=>"Allow for players to have a chance to find a partial healing potion after fights?,bool|1",
			"watermaploc"=>"Where does the Water Castle appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"exitsave"=>"Allow users to return to the dungeon from an emergency exit?,enum,0,No,1,Yes,2,Require|0",
			"`\$Note: If you chose 'Require' then players MUST come back in at the same location that they leave from; otherwise players will have a choice to come back through the main entrance or the exit location,note",
		),
		"prefs"=>array(
			"Wasser's Castle Allprefs,title",
			"allprefs"=>"Allprefs for Wasser's Castle:,textarea|",
			"Wasser's Castle Map,title",
			"maze"=>"Maze,viewonly",
			"pqtemp"=>"Temporary Information,int|",
		),
		"requires"=>array(
			"signetsale" => "5.01| by DaveS, http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		),
	);
	return $info;
}
function signetd3_install(){
	module_addhook("village");
	module_addhook("scrolls-signetsale");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	return true;
}
function signetd3_uninstall(){
	return true;
}
function signetd3_dohook($hookname,$args){
	global $session;
	$userid = $session['user']['acctid'];
	switch($hookname){
		case "village":
			$allprefs=unserialize(get_module_pref('allprefs'));
			$allprefss=unserialize(get_module_pref('allprefs','signetsale'));
			if ($session['user']['location'] == get_module_setting("watermaploc") && (($allprefss['sigmap3']==1 && $allprefs['complete']==0)||get_module_pref("super","signetsale")==1)){
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav("Wasser's Castle","runmodule.php?module=signetd3");
			}
		break;
		case "scrolls-signetsale":
			$userid2 = httpget("user");
			if ($userid2==$userid){
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($allprefs['scroll5']==1) addnav("Scroll 5","runmodule.php?module=signetd3&op=scroll5&user=$userid");		
			}
		break;
		case "allprefs": case "allprefnavs":
			if ($session['user']['superuser'] & SU_EDIT_USERS){
				$id=httpget('userid');
				addnav("Signet Series");
				addnav("Signet Maze 3: `!Wasser's Castle","runmodule.php?module=signetd3&op=superuser&subop=edit&userid=$id");
			}
	}
	return $args;
}
function signetd3_run(){
	require_once("modules/signet/signetd3_func.php");
	include("modules/signet/signetd3.php");
}
?>