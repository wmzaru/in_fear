<?php
//modifications suggested by sixf00t4
function staffnote_getmoduleinfo(){
	$info = array(
		"name"=>"Staff Notes",
		"author"=>"DaveS",
		"version"=>"3.03",
		"category"=>"Administrative",
		"download"=>"",
		"prefs"=>array(
			"staffedit"=>"Allow user to edit the message if they don't have EDIT USER permission?,bool|0",
			"staffsee"=>"Allows user to see the message if they don't have EDIT USER permission?,bool|0",
			"staffnote"=>"What notes do you have on this player?,text|",
		),
	);
	return $info;
}
function staffnote_install(){
	module_addhook("biostat");
	return true;
}
function staffnote_uninstall(){
	return true;
}
function staffnote_dohook($hookname, $args){
	global $session, $REQUEST_URI;
	$userid = $session[user][acctid];
	$char = httpget('char');
	$sql = "SELECT acctid FROM ".db_prefix("accounts")." WHERE acctid='$char'";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$id = $row['acctid'];
	switch($hookname){
		case "biostat":
			if ((($session['user']['superuser'] & SU_EDIT_USERS)||get_module_pref("staffsee")==1) && get_module_pref("staffnote","staffnote",$args['acctid'])!="") output("`\$`bStaff Note:`b %s`n",get_module_pref("staffnote","staffnote",$args['acctid']));
			if (($session['user']['superuser'] & SU_EDIT_USERS)|| get_module_pref("staffedit")==1){
				addnav("Admin Functions");
				addnav("Edit Staff Note","runmodule.php?module=staffnote&op=note&id=$id&user=$userid&return=".URLencode($_SERVER['REQUEST_URI']));
			}
		break;
	}
	return $args;
}
function staffnote_run(){
	global $session;
	$op = httpget('op');
	$name = httpget('name');
	$id = httpget('id');
	$subop = httpget('subop');
	$submit1= httppost('submit1');
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid='$id'";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$name = $row['name'];
	$pageheader= color_sanitize($name);
	page_header("Add Staff Note on %s",$pageheader);

if ($op=="note"){
	$return = httpget('return');
	$return = cmd_sanitize($return);
	$return = substr($return,strrpos($return,"/")+1);
	tlschema("nav");
	addnav("Return whence you came",$return);
	tlschema();
	$userid = httpget("user");
	$strike = false;
	if ($subop!="submit1"){
		$submit1 = translate_inline("Note");
		if (get_module_pref("staffnote","staffnote",$id)!="") output("Current Note on `^%s`0:`\$`n%s`0",$name,get_module_pref("staffnote","staffnote",$id));
		else output("Add note on `^%s`0:",$name);
		output_notl("`n`n");
		rawoutput("<form action='runmodule.php?module=staffnote&op=note&subop=submit1&id=$id&name=$name' method='POST'><input name='submit1' id='submit1'><input type='submit' class='button' value='$submit1'></form>");
		addnav("","runmodule.php?module=staffnote&op=note&subop=submit1&id=$id&name=$name");	
	}else{
		set_module_pref("staffnote",$submit1,"staffnote",$id);
		output("`^%s`0 note changed to:`n`n`\$%s`0",$name,$submit1);
	}
}
villagenav();
page_footer();
}
?>