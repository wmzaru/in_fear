<?php
//Date:  April 2, 2006
function signetd4_getmoduleinfo(){
	$info = array(
		"name"=>"Signet Maze 4: `\$Fiamma's Fortress",
		"version"=>"5.22",
		"author"=>"DaveS",
		"category"=>"Signet Series",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		"settings"=>array(
			"Fiamma's Fortress Settings,title",
			"random"=>"How many random monsters can be encountered in the fortress?,range,0,40,1|5",
			"randenc"=>"Likelihood of random encounter,enum,1,Common,5,Uncommon,10,Rare,15,Very Rare,20,Extremely Rare|10",
			"healing"=>"Allow for players to have a chance to find a partial healing potion after fights?,bool|1",
			"firemaploc"=>"Where does the Fire Fortress appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"exitsave"=>"Allow users to return to the dungeon from an emergency exit?,enum,0,No,1,Yes,2,Require|0",
			"`\$Note: If you chose 'Require' then players MUST come back in at the same location that they leave from; otherwise players will have a choice to come back through the main entrance or the exit location,note",
		),
		"prefs"=>array(
			"Fiamma's Fortress Allprefs,title",
			"allprefs"=>"Allprefs for Fiamma's Fortress:,textarea|",
			"Fiamma's Fortress Map,title",
			"maze"=>"Maze,viewonly",
			"pqtemp"=>"Temporary Information,int|",
		),
		"requires"=>array(
			"signetsale" => "5.01| by DaveS, http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		),
	);
	return $info;
}
function signetd4_install(){
	module_addhook("village");
	module_addhook("scrolls-signetsale");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	return true;
}
function signetd4_uninstall(){
	return true;
}
function signetd4_dohook($hookname,$args){
	global $session;
	$userid = $session['user']['acctid'];
	switch($hookname){
		case "village":
			$allprefs=unserialize(get_module_pref('allprefs'));
			$allprefss=unserialize(get_module_pref('allprefs','signetsale'));
			if ($session['user']['location'] == get_module_setting("firemaploc")  && (($allprefss['sigmap4']==1 && $allprefs['complete']==0)||get_module_pref("super","signetsale")==1)){
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav("Fiamma's Fortress","runmodule.php?module=signetd4");
			}
		break;
		case "scrolls-signetsale":
			$userid2 = httpget("user");
			if ($userid2==$userid){
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($allprefs['scroll6']==1) addnav("Scroll 6","runmodule.php?module=signetd4&op=scroll6&user=$userid");		
				if ($allprefs['scroll7']==1) addnav("Scroll 7","runmodule.php?module=signetd4&op=scroll7&user=$userid");		
			}
		break;
		case "allprefs": case "allprefnavs":
			if ($session['user']['superuser'] & SU_EDIT_USERS){
				$id=httpget('userid');
				addnav("Signet Series");
				addnav("Signet Maze 4: `\$Fiamma's Fortress","runmodule.php?module=signetd4&op=superuser&subop=edit&userid=$id");
			}
		break;
	}
	return $args;
}
function signetd4_run(){
	require_once("modules/signet/signetd4_func.php");
	include("modules/signet/signetd4.php");
}
?>