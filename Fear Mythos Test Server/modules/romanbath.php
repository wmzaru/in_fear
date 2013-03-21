<?php
function romanbath_getmoduleinfo(){
		$info = array(
			"name"=>"Roman Bath",
			"author"=>"<a href='http://www.joshuadhall.com' target=_new>Sixf00t4</a>, edited by `2Oliver Brendel",
			"version"=>"20071206",
			"description"=>"Gives Top DKers an esoteric hangout",
			"category"=>"Village",
			"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1196",
			"vertxtloc"=>"http://www.legendofsix.com/",
			"settings"=>array(
				"Roman Bath Settings,title",
				"dkers"=>"Base access on being a top dragon killer?,bool|1",
				"howmany"=>"<i>if yes-</i> How many DKers are allowed access,int|10",
				"village"=>"Show link in gardens?<i> will show in the inn if no</i>,bool|1",
				"allvil"=>"<i>if shown in gardens is true-</i> Show link in all villages?,bool|1",
				"bathloc"=>"<i>if single village-</i> Where does the bath appear?,location|".getsetting("villagename", LOCATION_FIELDS),
				"allowed"=>"What people are allowed in today - without overrides who always can,viewonly",
				),
			"prefs"=>array(
				"Roman Bath Settings,title",
				"bathoverride"=>"override settings to always have access to the bath?,bool|0",
				),
		);
	return $info;
}
function romanbath_install(){
	$sql="Select acctid from ".db_prefix("accounts")." where dragonkills>0 and locked=0 and superuser=0 order by dragonkills DESC limit ".get_module_setting("howmany","romanbath")."";
	$result=db_query($sql);
	$add=array();
	while ($row = db_fetch_assoc($result)) {
		$add[]=$row['acctid'];
	}
	set_module_setting('allowed',implode(",",$add),'romanbath');
	module_addhook("changesetting");
	module_addhook("gardens");
	module_addhook("newday-runonce");
	module_addhook("inn");
	return true;
}
function romanbath_uninstall(){
	return true;
}
function romanbath_dohook($hookname,$args){
	global $session;
	$list=explode(",",get_module_setting('allowed','romanbath'));
	switch ($hookname){
		case "newday-runonce":
			romanbath_access();
			break;
		case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("bathloc")) {
				set_module_setting("bathloc", $args['new']);
			}
		}
		break;
		case "inn":
			if(get_module_setting("village","romanbath")==0) {
				if (in_array($session['user']['acctid'],$list) || get_module_pref("bathoverride","romanbath")){
					addnav("Special");
					addnav("Roman Bath","runmodule.php?module=romanbath");
				}
			}	
		break;
		case "gardens":
			if(get_module_setting("village","romanbath")){
				if(get_module_setting("allvil","romanbath")){
					if (in_array($session['user']['acctid'],$list) || get_module_pref("bathoverride","romanbath")){
					addnav("Special");
					addnav("Roman Bath","runmodule.php?module=romanbath");
					}
				}else if ($session['user']['location'] == get_module_setting("bathloc","romanbath")) {
					if (in_array($session['user']['acctid'],$list) || get_module_pref("bathoverride","romanbath")){
					addnav("Special");
					addnav("Roman Bath","runmodule.php?module=romanbath");
					}
				}	
			}		
			break;
		}
	return $args;
}
function romanbath_run(){
	global $session;
	page_header("Roman Bath");
	require_once("lib/commentary.php");
	output("`n You walk into a grandiose building, with white marble floors, vines climbing the walls, and 16 massive ionic columns surrounding a pool of clear blue water, bubbling at a nice warm temperature.  To the side of the pool, you see a few lounge chairs, made of the finest silks.`n"); 
	output("On the walls, you see framed pictures of the following people:`n`n");
	$accounts = db_prefix("accounts");
	$list=get_module_setting('allowed','romanbath');
	$sql = "SELECT $accounts.acctid, $accounts.name AS name FROM $accounts WHERE acctid IN ($list) ORDER BY $accounts.dragonkills DESC";
	$result=db_query_cached($sql,"romanbath-names");
	output_notl("`c");
	while ($row = db_fetch_assoc($result)) {
		output_notl("`^".$row['name']."`n`0");
	}
	output_notl("`c");
	output("`n`n");
	modulehook ("romanbath");
	addcommentary();
	viewcommentary("roman-bath","Sit and eat grapes",30,"says");		
	addnav("Back to the Mundane","village.php");
	page_footer();
}

function romanbath_access() {
	$sql="Select acctid from ".db_prefix("accounts")." where dragonkills>0 and locked=0 and superuser=0 order by dragonkills DESC limit ".get_module_setting("howmany","romanbath")."";
	$result=db_query($sql);
	invalidatedatacache("romanbath-names");
	$add=array();
	while ($row=db_fetch_assoc($result)) {
		$add[]=$row['acctid'];
	}
	set_module_setting('allowed',implode(",",$add),'romanbath');
	return;
}
?>