<?php

/*
 * In the spirit of LoRD, I wrote a system to track lays.
 * So far, I've patched the lovers.php module for this, you can get it at htt://dragonprime.net/users/Hex/
 *
 * TODO:
 *  * HoF.. ranks people with most lays  - DONE  (might not be perfect, lemme know if there's any problems..)
 *  - add sorting feature to HoF listings...
 *  - add multiple page support to HoF listings..
 *  * add pimp of the realm stuff..  - DONE (should be working properly.. top pimp will be displayed)
 *  - update top pimp code to show top female AND male
 *
 * As of version 2.0:  This module now uses moduleprefs again, no need for the 'lays' field in the accounts table..
 * NOTE TO 1.0 USERS: UNINSTALL 1.0 BEFORE YOU INSTALL THIS MODULE. While I don't think it will break anything,
 * It will at least take the unused lays field out of your accounts table.
 *
 * Thanks to Sichae, Kendaer, Dopple, and Rowne for their help and support on this project.
 *
 * Changelog:
 *		v1.0 - 12/05/04
 *		- Initial release. (moduleprefs variant)
 *
 *		v1.1 - 12/06/04
 *		- Changed over to use accounts table
 *		- Patched HoF to display most/least lays
 *
 *		v2.0 - 12/06/04
 *		- Changed back to moduleprefs variant
 *
 *		v2.1 - 12/06/04
 *		- Built-in HoF page. Ranks users with the most lays
 *      - Added admin configurable options
 *
 *		v2.2 - 12/07/04
 *		- Added server pimp stuff
 *		- admin configurable titles for males AND females
 *		- option to display top pimp on login page
 *		- added option to change player titles
 *		- fixed top pimp code to display the real top pimp <g>
 *		- Added ability to turn on/off HoF (why the HELL would you want to do that?!)
 *		- added 'howmany' pref. for future plans
 *		- made 'show lays in info bar' per user, not systemwide anymore.
 *
 */

function lays_getmoduleinfo(){
	$info = array(
		"name"=>"Lays",
		"version"=>"2.2",
		"author"=>"`!Hex",
		"category"=>"General",
		"download"=>"http://dragonprime.net/users/Hex/lays.zip",
		"settings"=>array(
			"Lays Global Settings,title",
			"usehof"=>"Use Hall of Fame?,bool|1",
			"toplist"=>"Display how many in the Lays HoF?,range,0,150,10|0",
			"allowsu"=>"Show Admins/Moderators in Lays HoF?,bool|1",
			"Server Pimp Stuff,title",
			"showpimp"=>"Want to show Top Pimp?,bool|1",
			"allowpimp"=>"Allow title changes?,bool|1",
			"bepimped"=>"Min lays per DK to be pimped?,int|30",
			"femtitle"=>"Title for females?,text|Vixen",
			"guytitle"=>"Title for males?,text|Pimp",
			"The titles are needed so do NOT set them blank.,note",
		),
		"prefs"=>array(
			"Lays System Preferences,title",
			"lays"=>"How many times has player got laid?,int|0",
			"pimped"=>"Has user been pimped this DK?,bool|0",
			"howmany"=>"How many times has user been pimped?,int|0",
			"user_stat"=>"Display no. of lays in Stat bar?,bool|1",
		),
  	);
  	return $info;
}

function lays_install(){
	output("Installing Lays module.`n");
	module_addhook("loverlay");
	module_addhook("charstats");
	module_addhook("footer-hof");
	module_addhook("dragonkill");
	module_addhook("index");
  	return true;
}

function lays_uninstall(){
	output("Uninstalling Lays module.`n");
  	return true;
}

function lays_dohook($hookname,$args){
	global $session;
	
	$lays = get_module_pref("lays");
	$howmany = get_module_pref('howmany');
	
	switch($hookname){
		
		case "loverlay":
			$lays++;
			set_module_pref("lays",$lays);
			
			if(get_module_setting('allowpimp')){
				require_once("lib/names.php");
				$needlays = get_module_setting("bepimped") * $session['user']['dragonkills'];
			
				if($session['user']['sex']==0){
					if($lays>=$needlays && !get_module_pref("pimped") && $session['user']['dragonkills']>0){
						$newtitle=get_module_setting('guytitle');
						output("`6You have earned the title of `%%s`6! for getting laid %s times!", $newtitle, $lays);
						$name=$session['user']['name'];
						addnews("Villagers are talking. They're calling `@%s`0 a `%%s`0. Watch out ladies!",$name,$newtitle);
						$newname = change_player_title($newtitle);
						$session['user']['title'] = $newtitle;
						$session['user']['name'] = $newname;
						set_module_pref('pimped',1);
						$howmany++;
						set_module_pref('howmany',$howmany);
					}
				}else{
					if($lays>=$needlays && !get_module_pref("pimped") && $session['user']['dragonkills']>0){
						$newtitle=get_module_setting('femtitle');
						output("`6You have earned the title of `%%s`6! for getting laid %s times!", $newtitle,$lays);
						$name=$session['user']['name'];
						addnews("Villagers are talking. They're calling `@%s a `%%s. Men beware!",$name,$newtitle);
						$newname = change_player_title($newtitle);
						$session['user']['title'] = $newtitle;
						$session['user']['name'] = $newname;
						set_module_pref('pimped',1);
						$howmany++;
						set_module_pref('howmany',$howmany);
					}
				}
			}
		break;
		
		case "charstats":
			if(get_module_pref("user_stat")){
				if($lays>0){
					setcharstat("Personal Info", "Lays", "`^" . $lays);
				}
			}
		break;
		
		case "footer-hof":
			if(get_module_setting('usehof')){
				addnav("Warrior Rankings");
				addnav("Lays", "runmodule.php?module=lays");
			}
		break;
		
		case "dragonkill":
			set_module_pref("pimped",0);
		break;
		
		case "index":
			if(get_module_setting('showpimp')){
				$sql = "SELECT prefs.userid, (prefs.value+0) AS lays, users.name, users.sex FROM " . db_prefix("module_userprefs") . " AS prefs,  " . db_prefix("accounts") . " AS users WHERE prefs.setting='lays' AND prefs.value>0 AND prefs.modulename='lays' AND prefs.userid=users.acctid ORDER BY (prefs.value+0) DESC LIMIT 1";
				$result = db_query($sql);
		    	$row = db_fetch_assoc($result);
				$pimp=$row['name'];
				$sex=$row['sex'];
				if ($pimp <> ""){
					if($sex==0){
						output("`5The `%%s`5 of this Realm is: `2%s`5.`n",get_module_setting('guytitle'),$pimp);
					}else{
						output("`5The `%%s`5 of this Realm is: `2%s`5.`n",get_module_setting('femtitle'),$pimp);
					}
				}
			}
		break;

	}
  	return $args;
}

function lays_runevent($type){
  	return $args;
}

function lays_run(){
	global $session;
	
	page_header("Hall of Fame");
	
	if(get_module_setting('toplist') > 0){
		$toplist = " LIMIT 0, " . get_module_setting('toplist');
	}else{
		$toplist = "";
	}
	
	$sex=array(
		"0"=>"`!Male",
		"1"=>"`%Female",
	);
	
	if(!get_module_setting('allowsu')){
		$allowsu = " AND superuser = 0";
	}else{
		$allowsu = "";
	}
	
	output("`c`b`^Players with the most lays in the land`b`c`n");
	
	$sql= "SELECT COUNT(*) AS c FROM " . db_prefix("module_userprefs") . " WHERE modulename='lays' and value > 0 and setting='lays'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$ranked = $row['c'];
	
	$sql = "SELECT prefs.userid, (prefs.value+0) AS lays, users.name, users.sex, users.race FROM " . db_prefix("module_userprefs") . " AS prefs,  " . db_prefix("accounts") . " AS users WHERE prefs.setting='lays' AND prefs.value>0 AND prefs.modulename='lays' AND prefs.userid=users.acctid $allowsu ORDER BY (prefs.value+0) DESC, prefs.userid ASC $toplist";
	$result = db_query($sql);
	$max = db_num_rows($result);
	
	$rank = translate_inline("Rank");
	$name = translate_inline("Name");
	$gender = translate_inline("Gender");
	$race = translate_inline("Race");
	$lays = translate_inline("Lays");
	
	rawoutput("<table border='0' cellpadding='3' cellspacing='0' align='center'>");
	output_notl("<tr class='trhead'><td>`b$rank`b</td><td>`b$name`b</td><td>`b$gender`b</td><td>`b$race`b</td><td>`b$lays`b</td>",true);
	
	for($i=0;$i<$max;$i++){
		$row = db_fetch_assoc($result);
		
		if ($row['name']==$session['user']['name']){
			rawoutput("<tr class='trhilight'><td>");
		} else {
			rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
		}
		
		$rnk=$i+1;
		output_notl("$rnk");
		
		rawoutput("</td><td align=\"center\">");
		output_notl("`&%s`0", $row['name']);
		rawoutput("</td><td>");
		output_notl("%s`0", ($sex[$row['sex']]));
		rawoutput("</td><td>");
		output_notl("`#%s`0", $row['race']);
		rawoutput("</td><td align=\"right\">");
		output_notl("`&%s`0", $row['lays']);
		rawoutput("</td></tr>");
		
	}
	
	rawoutput("</table>");
	
	if($max == 0){
		output("`&`cCan ya believe it, no one has gotten laid yet!`c`n");
	}
	
	addnav("Back to HoF", "hof.php");
	villagenav();
	
	page_footer();
}

?>