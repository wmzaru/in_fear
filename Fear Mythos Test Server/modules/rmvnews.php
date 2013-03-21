<?php

function rmvnews_getmoduleinfo(){
	$info = array(
		"name"=>"Remove News",
		"author"=>"Chris Vorndran",
		"version"=>"0.11",
		"category"=>"Administrative",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=60",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"Places a link in a person's bio, that will allow a user to be able to remove all associated News entries for that user.",
		);
	return $info;
}
function rmvnews_install(){
	module_addhook("bioinfo");
	return true;
	}
function rmvnews_uninstall(){
	return true;
}
function rmvnews_dohook($hookname,$args){
	global $session,$target;
	switch ($hookname){
		case "bioinfo":
			$id = $target['acctid'];
			if ($session['user']['superuser'] & SU_EDIT_USERS){
			addnav("Superuser");
			addnav("Remove News","runmodule.php?module=rmvnews&op=ask&id=$id");
			}
			break;
		}
	return $args;
}
function rmvnews_run(){
	global $session;
	$op = httpget('op');
	$id = httpget('id');
	page_header("Remove News");

	switch ($op){
		case "ask":
			output("`#Are you sure that you wish to remove all news entries for this character?");
			addnav("Yes","runmodule.php?module=rmvnews&op=yes&id=$id");
			break;
		case "yes":
			output("`#All news entries for this character are being removed from the Database.");
			$sql = "DELETE FROM ".db_prefix("news")." WHERE accountid='$id'";
			db_query($sql);
			debug($sql);
			rawoutput("<big>");
			output("`n`n`b`^All news entries, for this character, deleted.`b");
			rawoutput("</big>");
			break;
	}
	villagenav();
page_footer();
}
?>