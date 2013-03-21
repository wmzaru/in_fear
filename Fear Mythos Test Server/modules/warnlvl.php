<?php

require_once("lib/systemmail.php");

function warnlvl_getmoduleinfo(){
		$info = array(
		"name"=>"Warning Level and Bans",
		"author"=>"`&S`7ephiroth`3",
		"category"=>"Administrative",
		"download"=>"",
		"version"=>"1.0",
		"settings"=>array(
			"Warning Level and Ban Settings,title",
			//"warning"=>"Show warning with warnlvl?,bool|1",
			"warns"=>"How many warnings does player get before being banned?,range,1,25,1|1",
			"bans"=>"How long is ban after being warned? (Days),range,1,50,1|1",
		),
		"prefs"=>array(
		    "Warning Level and Ban User Preferences,title",
			"warnings"=>"Number of Warnings Player has,int|0",
		),
	);
	return $info;
}

function warnlvl_install(){
	if (!is_module_active('warnlvl')){
		output("`c`b`QInstalling Warning Level and Bans Module.`b`n`c");
	}else{
		output("`c`b`QUpdating Warning Level and Bans Module.`b`n`c");
	}
	module_addhook("superuser");
	module_addhook("biotop");
	module_addhook("everyfooter");
	return true;
}

function warnlvl_uninstall(){
	output("`n`c`b`QWarning Level and Bans Module Uninstalled`0`b`c");
	return true;
}

function warnlvl_dohook($hookname, $args){
	global $session;
	switch ($hookname){
		case "superuser":
			tlschema($args['schemas']['marketnav']);
        addnav($args["marketnav"]);
        tlschema();
        addnav("Warning Editor","runmodule.php?module=warnlvl");
		break;
		case "biotop":
			addnav("Superuser");
		$id=$args['acctid'];
		if ($session['user']['superuser'] & SU_EDIT_USERS){
        addnav("Warn Player","runmodule.php?module=warnlvl&op=warn&id=".$id."");
		}
		break;
		
	}
	return $args;
}

function warnlvl_run(){
	page_header("Warning Level and Ban");
	villagenav();
	$op = httpget("op");

	if($op==""){

	$a = "".db_prefix('accounts')."";
	$b = "".db_prefix('module_userprefs')."";
	$sql = "SELECT $a.name, $b.value FROM $a,$b WHERE modulename='warnlvl' AND userid=acctid AND setting='warnings'";
	$res = db_query($sql);
	//Can't figure out how to display get_module_pref in a table..
	$warnings = translate_inline("Warnings");
	$name = translate_inline("Name");

	rawoutput("<table cellspacing='0' cellpadding='2' align='center'>");
	rawoutput("<tr class='trhead'>");
	output_notl("<td>`b$name`b</td><td>`b$warnings`b</td>", true);
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
   if (db_num_rows($res)==0){
		output("<td>None</td><td>0</td>");
   }else{
	output_notl("<tr><td>%s</td><td>`&`c%s`c`0</td>", $row['name'], $row['value'], true);
	 }
   }
	rawoutput("</tr>");	
	rawoutput("</table>");
	addnav("Grotto","superuser.php");
	}elseif($op=="warn"){

	$id = httpget('id');
	$sql="SELECT * FROM ".db_prefix("accounts")." WHERE acctid='".$id."'";
	$result=db_query($sql);
	$row=db_fetch_assoc($result);

	output("`b`\$Warning!`b You are about to warn %s, are you user you want to do this?",$row[name]);
	addnav("Yes","runmodule.php?module=warnlvl&op=yes&id=$row[acctid]");
	addnav("No","village.php");
	
	}elseif($op=="yes"){
	$id = httpget('id');
	$sql="SELECT * FROM ".db_prefix("accounts")." WHERE acctid='".$id."'";
	$result=db_query($sql);
	$row=db_fetch_assoc($result);
	$user=$row[acctid];
	set_module_pref('warnings',get_module_pref('warnings','warnlvl') + 1,'warnlvl',$user);
    
	output("%s `#was warned, this was there `^%s `#warning.",$row[name],get_module_pref('warnings','warnlvl',$user));
	addnews("%s `#was warned, this was there `^%s `#warning.",$row[name],get_module_pref('warnings','warnlvl',$user));
	$subject=translate_inline("Warned");
	$left=get_module_pref('warnings','warnlvl',$user)-get_module_setting('warns','warnlvl');
	$body=translate_inline("Hello ".$row[name].", I'm sorry to inform you that you have been warned for not following one or more of the site rules.  This is your ".get_module_pref('warnings','warnlvl',$user)." warning. You have ".$left." warnings left until you will be banned for ".get_module_setting('bans','warnlvl')." days.  Next time please follow our rules.`nThank you.`nThe Staff");
	systemmail($id,$subject,$body);

				$reason=translate_inline("Banned from Multiple Warnings");
			if(get_module_pref('warnings','warnlvl',$user)>=get_module_setting('warns','warnlvl')){
			$subject=translate_inline("Banned");
	$body=translate_inline("Hello ".$row[name].", I'm sorry to inform you that you have been banned for ".get_module_setting('bans','warnlvl')." days.  You we're warned ".get_module_pref('warnings','warnlvl')." times and you failed to straighten up your act.  While you are banned please think about what you have done.  When you get back please be more compliant to the rules or the ban will be even longer, or permanent.`nThank you.`nThe Staff");
	systemmail($id,$subject,$body);
			addnews("%s`#, was banned because they were warned `^%s`# times.",$row[name],get_module_setting('warns','warnlvl',$user));
			$sql2="INSERT INTO ".db_prefix("bans")." (ipfilter,banexpire,banreason,banner,lasthit) VALUES ('$row[lastip]','".date("Y-m-d H:i:s")."','".$reason."','System','".$row['lasthit']."')";
			$result2=db_query($sql2);
			$row2=db_fetch_assoc($result2);
			}
	}
	page_footer();
}
?>