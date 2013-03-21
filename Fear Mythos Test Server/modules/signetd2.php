<?php
//Date:  February 26, 2006
function signetd2_getmoduleinfo(){
	$info = array(
		"name"=>"Signet Maze 2: `QAarde Temple",
		"version"=>"5.22",
		"author"=>"DaveS",
		"category"=>"Signet Series",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		"settings"=>array(
			"Aarde Temple Settings,title",
			"random"=>"How many random monsters can be encountered in the temple?,int|5",
			"randenc"=>"Likelihood of random encounter:,enum,1,Common,5,Uncommon,10,Rare,15,Very Rare,20,Extremely Rare|10",
			"healing"=>"Allow for players to have a chance to find a partial healing potion after fights?,bool|1",
			"impalign"=>"Allow players to improve alignment by donating to temple?,bool|0",
			"costalign"=>"Cost in gold for each point to improve alignment if allowed?,int|1000",
			"Note:  Do not make this too expensive because it has other implications to finishing the module,note",
			"alignmax"=>"Max number of alignment points that can be improved?,int|20",
			"nodonate"=>"Number of times no donation has been offered:,int|0",
			"earthmaploc"=>"Where does the Aarde Temple appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"`\$Note: There are no Emergency Exits in the Aarde Temple due to the ease of use of teleporters,note",
		),
		"prefs"=>array(
			"Aarde Temple Allprefs,title",
			"allprefs"=>"Allprefs for Aarde Temple:,viewonly|",
			"Aarde Temple Map,title",
			"maze"=>"Maze,viewonly",
			"pqtemp"=>"Temporary Information,int|",
		),
		"requires"=>array(
			"signetsale" => "5.01| by DaveS, http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		),
	);
	return $info;
}
function signetd2_install(){
	module_addhook("village");
	module_addhook("scrolls-signetsale");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	return true;
}
function signetd2_uninstall(){
	return true;
}
function signetd2_dohook($hookname,$args){
	global $session;
	$userid = $session['user']['acctid'];
	switch($hookname){
		case "village":
			$allprefs=unserialize(get_module_pref('allprefs'));
			$allprefss=unserialize(get_module_pref('allprefs','signetsale'));
			if ($session['user']['location'] == get_module_setting("earthmaploc") && (($allprefss['sigmap2']==1 && $allprefs['complete']==0)||get_module_pref("super","signetsale")==1)){
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav("Aarde Temple","runmodule.php?module=signetd2");
			}
		break;
		case "scrolls-signetsale":
			$userid2 = httpget("user");
			if ($userid2==$userid){
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($allprefs['scroll4']==1) addnav("Scroll 4","runmodule.php?module=signetd2&op=scroll4&user=$userid");		
			}
		break;
		case "allprefs": case "allprefnavs":
			if ($session['user']['superuser'] & SU_EDIT_USERS){
				$id=httpget('userid');
				addnav("Signet Series");
				addnav("Signet Maze 2: `QAarde Temple","runmodule.php?module=signetd2&op=superuser&subop=edit&userid=$id");
			}
		break;
	}
	return $args;
}
function signetd2_run(){
	require_once("modules/signet/signetd2_func.php");
	include("modules/signet/signetd2.php");
}
?>