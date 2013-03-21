<?php
function rpalignment_getmoduleinfo(){
	$info = array(
		"name"=>"Roleplay Alignment",
		"version"=>"1.1",
		"author"=>"Based on `2Shane`0's religion module, modified by `i`)Ae`7ol`&us`i`0, using code from `&Stephen Kise`0's rlage module.",
		"category"=>"Roleplay",
		"download"=>"http://dragonprime.net/index.php?topic=12150.0",
		"settings"=>array(
			"Roleplay Alignment Settings,title",
			"priest"=>"Name of priest in charge of Alignment Palace,text|Razell",
			"rdeadtime"=>"How many game days does player remain dead once leaving or switching sides?,int|10",
			"preset"=>"Used preset values?,bool|0",
			"linkbio"=>"Link names in member list to bios?,bool|1",
		),
		"prefs"=>array(
			"Roleplay Alignment Prefs,title",
			"alignfollow"=>"What alignment is user following?,int|0",
			"aligndelete"=>"Was this player's alignment deleted?,int|0",
			"alignremove"=>"Did this player leave their alignment?,bool|0",
			"edit"=>"Can this user access Alignment Editor in Grotto,bool|0",
			"rdead"=>"Is this player dead via this module?,bool|0",
			"rdeadleft"=>"If above pref is YES how long for?,int|0",
		),
	);
	return $info;
}

function rpalignment_install(){
	include_once("modules/rpalignment/install.php");
	return true;
}

function rpalignment_uninstall(){
	include_once("modules/rpalignment/uninstall.php");
	return true;
}

function rpalignment_dohook($hook,$args){
	global $session;
	switch($hook){
		case "biostat":
			if (get_module_pref("alignfollow") >= 1){
				
				$char = httpget('char');
				if (!is_numeric($char)){
					$sql = db_query("SELECT acctid FROM " . db_prefix("accounts") . " WHERE login = '$char'");
					$row = db_fetch_assoc($sql);
					$char = $row['acctid'];
				}
	
				require_once("modules/rpalignment/functions.php");
				$alignfollow = get_module_pref("alignfollow","rpalignment",$char);
				$preset = get_module_setting("preset");
				$info = rpalign_info($alignfollow);
				$iname = $info['name'];
				if ($preset){
					if ($iname == "Good"){
						$colour = "`@";
					} else if ($iname == "Neutral"){
						$colour = "`6";
					} else if ($iname == "Evil"){
						$colour = "`4";
					} else {
						$colour = "`&";
					}
				} else {
					$colour = "`&";
				}
				output("`^Roleplay Alignment: `b%s%s`b`0`n",$colour,$iname);
			}else{
				output("`^Roleplay Alignment: `b`\$NONE`b`0`n");
			}
		break;
		case "village":
			tlschema($args['schemas']['marketnav']);
			addnav($args['marketnav']);
			tlschema();
			addnav("<?Alignment Palace","runmodule.php?module=rpalignment&case=palace&op=enter");
		break;
		case "shades":
			if ($session['user']['superuser'] & SU_MEGAUSER){
				if (get_module_pref("rdead") == 1){
					addnav("Superuser");
					addnav("Remove Alignment Death","runmodule.php?module=rpalignment&case=palace&op=su");
				}
			}
		break;
		case "superuser":
			if (get_module_pref("edit") == 1 || $session['user']['superuser'] & SU_MEGAUSER){
				addnav("Roleplay");
				addnav("Roleplay Alignment Editor","runmodule.php?module=rpalignment&case=superuser&op=enter");
			}
		break;
		case "charstats":
			if (get_module_pref("alignfollow") >= 1){
				require_once("modules/rpalignment/functions.php");
				$alignfollow = get_module_pref("alignfollow");
				$preset = get_module_setting("preset");
				$info = rpalign_info($alignfollow);
				$iname = $info['name'];
				if ($preset){
					if ($iname == "Good"){
						$colour = "`@";
					} else if ($iname == "Neutral"){
						$colour = "`6";
					} else if ($iname == "Evil"){
						$colour = "`4";
					} else {
						$colour = "`&";
					}
				} else {
					$colour = "`&";
				}
				addcharstat("Vital Info");
				addcharstat("Roleplay Alignment", "$colour$iname");
			}else{
				addcharstat("Vital Info");
				addcharstat("Roleplay Alignment", "`\$NONE");
			}
		break;
		case "header-village":
			$sql = "SELECT name,id FROM `".db_prefix("rpalignment");
			$res = db_query($sql);
			$num = db_num_rows($res);
			
			if (get_module_pref('alignfollow') == 0 & get_module_pref("rdead") == 0 & $num > 0){
				redirect("runmodule.php?module=rpalignment&case=palace&op=go");
			}
		break;
		case "newday":
			$rdead = get_module_pref("rdead");
			$rdeadleft = get_module_pref("rdeadleft");
			$rdeadtime = get_module_setting("rdeadtime");
			
			if ($rdead){
				if ($rdeadleft <= $rdeadtime){
					$timeleft = $rdeadtime - $rdeadleft;
					output("`n`n`4You are still dead from your action of leaving your alignment. You have %s game days left before you live.`n`n",$timeleft);
					$session['user']['alive'] = 0;
					$session['user']['hitpoints'] = 0;
					$rdeadleft++;
					set_module_pref("rdeadleft",$rdeadleft);
				} else if ($rdeadleft > $rdeadtime){
					output("`n`n`4You are now free to join the alignments again!`n`n");
					set_module_pref("rdead",0);
					set_module_pref("rdeadleft",0);
				}
			}
		break;
	}
	return $args;
}

function rpalignment_run(){
	global $session;
	$case = httpget('case');
	$select=httppost('select');
	require_once("modules/rpalignment/functions.php");
	include_once("modules/rpalignment/case_$case.php");
}
?>