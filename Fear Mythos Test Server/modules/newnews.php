<?php

/*****************************************

Author: Jason Still
Contact: jstill@bluegrass.net
Created: 10/01/04
Last Update: 10/01/04
Purpose: Enter news items, either for today, the future, or in the past (why in the world would you do that?!)
Notes:  I based this off of the riddle editor, since it had an edit form like I wanted for this.  This was my
first module, so I had to go off something to figure out how to do anything.  There may be some extraneous stuff
floating around in this code from the original file (because I wasn't sure what it was doing, so I left it to be safe).

******************************************/

function newnews_getmoduleinfo(){
	$info = array(
		"name"=>"Admin News Item Creator",
		"version"=>"1.0",
		"author"=>"Jason Still",
		"category"=>"Administrative",
		"download"=>"http://dragonprime.net/users/Tholgare/newnews10.zip",
	);
	return $info;
}

function newnews_install(){
	module_addhook("superuser");
	return true;
}

function newnews_uninstall(){
	return true;
}

function newnews_dohook($hookname,$args){
	global $session;
	switch($hookname) {
	case "superuser":
		if ($session['user']['superuser']) {
			addnav("Actions");
			addnav("Add News Item","runmodule.php?module=newnews&act=add");
		}
		break;
	}
	return $args;
}



function newnews_run(){
	$act = httpget("act");
	if ($act=="add") newnews_editor();
}

function newnews_editor() {
	global $session;
	require_once("lib/nltoappon.php");



	$op = httpget('op');
	$id = httpget('id');
	$act = httpget('act');

	page_header("News Item Editor");
	require_once("lib/superusernav.php");
	superusernav();
	addnav("News Editor");
	addnav("Add a News Item","runmodule.php?module=newnews&act=add");
	if ($op=="save"){
		$newsitem = httppost('newsitem');
		$date = httppost('date');
		$preview = httppost('preview');
		
		
		//if its a preview, show the message and skip the insert
		if($preview[2]==1){
			output("This is the news item that will be posted:");
			rawoutput("<br><br>");
			output(nltoappon($newsitem) . "`0");
		}
		else{
			//convert the date
			if($date=="")
				$insertdate = date("Y-m-d");
			else
				$insertdate = date("Y-m-d",strtotime($date));
				
			$sql = "INSERT INTO " . db_prefix("news") . " (newstext,newsdate) VALUES('".nltoappon($newsitem)."','$insertdate')";

			db_query($sql);
			if (db_affected_rows()>0){
				$op = "";
				httpset("op", "");
				//I used another mod as a template, and I'm not sure what the above 2 lines were for
				//so just in case, I'm leaving that, and setting another var to keep from showing
				//the editor again
				$dontshowagain = 1;
				output("The following news item was saved:");
				rawoutput("<br><br>");
				output(nltoappon($newsitem) . "`0");
			}else{
				output("The query was not executed for some reason.  Try again, perhaps?");
			}
		}//end if preview
	}
	
	//if no op or if preview, need to reshow this part
	if(($op=="" || $preview[2]==1) && $dontshowagain!=1){
		output("<form action='runmodule.php?module=newnews&act=add&op=save' method='POST'>",true);
		addnav("","runmodule.php?module=newnews&act=add&op=save");
		output("`bAdd a News Item`b`n");
		if($preview[2]!=1){
                	$row = array(
                		"newsitem"=>"",
                		"date"=>"",
                		"preview"=>0
                	        );
         	}
         	else{
                	$row = array(
                		"newsitem"=>$newsitem,
                		"date"=>$date,
                		"preview"=>0
                	        );
         	}

                $form = array(
                        "News,title",
                        "newsitem"=>"News text,textarea",
                        "date"=>"Date",
                        "preview"=>"Preview,bitfield," . 0xffffffff . "," . 2 . ","
                );
                require_once("lib/showform.php");
                showform($form, $row);
		output("</form>",true);
		rawoutput("<br><br>");
		output("`$`bNOTE:`b`0`nIf no date is entered, today's date will be used.`nYou may enter the date in any format allowed by PHP's %s function.`nVisit %s to find out more about it.","strtotime()","www.php.net");
	}
	page_footer();
}
?>
