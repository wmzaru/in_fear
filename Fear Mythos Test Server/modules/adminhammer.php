<?php
require_once("lib/villagenav.php");
function adminhammer_getmoduleinfo(){
	$info = array(
		"name" => "Admin Hammer",
		"author" => "`b`&Ka`6laza`&ar`b `#from an idea by `!WheezingWeasel",
		"version" => "1.0",
		"download" => "http://dragonprime.net/index.php?module=Downloads;catd=9",
		"category" => "Administrative",
		"description" => "Hammer for annoying players, strips hp then gold then gems",
		"settings"=>array(
			"Hammer Settings,title",
				"hp"=>"amount of hp to strip,int|1",
				"gold"=>"amount of gold to strip,int|500",
				"gems"=>"amount of gems to strip,int|5",
		),
		"prefs" => array(
			"used"=>"times hit,int|0",
		),
		);
	return $info;
}
function adminhammer_install(){
	module_addhook("bioinfo");
	return true;
}
function adminhammer_uninstall(){
	return true;
}
function adminhammer_dohook($hookname,$args){
	global $session;
	$op = httpget('op');
	switch ($hookname){
		case "bioinfo":
			$char = httpget('char');
			$id = $args['acctid'];
				if ($session['user']['superuser'] & SU_EDIT_USERS) {
				addnav("Superuser");
				addnav("Hammer", "runmodule.php?module=adminhammer&op=hammer&id=$id");
			}
		break;
	}
	return $args;
}
function adminhammer_run(){
	global $session;
	page_header("The Hammer");
	$op = httpget('op');
	$id=httpget('id');
	$hp=get_module_setting("hp");
	$g = get_module_setting("gold");
	$ge=get_module_setting("gems");
	$used=get_module_pref("used");
	require_once("lib/systemmail.php");
		$sql="SELECT * FROM " .db_prefix("accounts"). " WHERE acctid = '$id'";
		$res = db_query($sql);
		$row=db_fetch_assoc($res);
	if ($op=="hammer"){
		if ($used<=2){
			output("This will strip %s hp.  Are you sure?",$hp);
			addnav("Yes","runmodule.php?module=adminhammer&op=hammer1&id=$id");
			addnav("No","village.php");
		}elseif ($used>2 && $used<=4){
			output("This will strip %s gold.  Are you sure?",$g);
			addnav("Yes","runmodule.php?module=adminhammer&op=hammer2&id=$id");
			addnav("No","village.php");
		}elseif ($used>4 && $used<=6){
			output("This will strip %s gems.  Are you sure?",$ge);
			addnav("Yes","runmodule.php?module=adminhammer&op=hammer3&id=$id");
			addnav("No","village.php");
		}elseif ($used>6){
			output("This will strip %s hp, %s gold and %s gems. Are you sure?",$hp,$g,$ge);
			addnav("Yes","runmodule.php?module=adminhammer&op=hammer4&id=$id");
			addnav("No","village.php");
		}
	}
	if ($op=="hammer1"){
		output("%s hp has been stripped",$hp);
		$n=$used+1;
		set_module_pref("used",$n);
		systemmail($id,"`^Warning!`0",array("`^You have been hit with a special hammer.  This has lost you %s hp.  Stop what you have been doing.",$hp));
		$hpn=$row['hitpoints']-=$hp;
		if ($hpn<=0){
			$hpn=1;
		}
		db_query("UPDATE " .db_prefix("accounts"). " SET hitpoints = '$hpn' WHERE acctid = '$id'");
		villagenav();
	}
		if ($op=="hammer2"){
		output("%s gold has been stripped",$g);
		$n=$used+1;
		set_module_pref("used",$n);
		systemmail($id,"`^Warning!`0",array("`^You have been hit with a special hammer.  This has lost you %s gold.  Stop what you have been doing.",$g));
		$gn=$row['gold']-=$g;
		if ($gn<0){
			$gn=0;
		}
		db_query("UPDATE " .db_prefix("accounts"). " SET gold = '$gn' WHERE acctid = '$id'");
		villagenav();
	}
	if ($op=="hammer3"){
		output("%s gems has been stripped",$ge);
		$n=$used+1;
		set_module_pref("used",$n);
		systemmail($id,"`^Warning!`0",array("`^You have been hit with a special hammer.  This has lost you %s gems.  Stop what you have been doing.",$ge));
		$gen=$row['gems']-=$ge;
		if ($gen<0){
			$gen=0;
		}
		db_query("UPDATE " .db_prefix("accounts"). " SET gems = '$gen' WHERE acctid = '$id'");
		villagenav();
	}
	if ($op=="hammer4"){
		output("%s hp, %s gold and %s gems have been stripped",$hp,$g,$ge);
		$n=$used+1;
		set_module_pref("used",$n);
		systemmail($id,"`^Warning!`0",array("`^You have been hit with a special hammer.  This has lost you %s hp, %s gold and %s gems.  Stop what you have been doing.",$hp,$g,$ge));
		$hpn=$row['hitpoints']-=$hp;
		$gn = $row['gold']-=$g;
		$gen=$row['gems']-=$ge;
		if ($hpn<=0){
			$hpn=1;
		}
		if ($gn<0){
			$gn=0;
		}
		if ($gen<0){
			$gen=0;
		}
		db_query("UPDATE " .db_prefix("accounts"). " SET hitpoints = '$hpn' WHERE acctid = '$id'");
		db_query("UPDATE " .db_prefix("accounts"). " SET gold = '$gn' WHERE acctid = '$id'");
		db_query("UPDATE " .db_prefix("accounts"). " SET gems = '$gen' WHERE acctid = '$id'");
		villagenav();
	}
	page_footer();
}

?>

