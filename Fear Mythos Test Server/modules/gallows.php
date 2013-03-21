<?php
/**************
Name: Gallows
Author: Eth - ethstavern(at)gmail(dot)com
Version: 2.0
Release Date: 12-25-2005
About: Simple module to display annoying/banned players hanging from the gallows.
	   Not suitable for every server. Just something I wrote to publically embarrass
	   certain troublesome players I had. Decided to release it just for the hell of it.
Translation ready!
*****************/
require_once("lib/villagenav.php");
require_once("common.php");
require_once("lib/showform.php");
function gallows_getmoduleinfo(){
	$info = array(
		"name"=>"Gallows",
		"version"=>"2.0",
		"author"=>"Eth",
		"category"=>"Village Gallows",
		"download"=>"http://dragonprime.net/users/Eth/gallows.zip",	
		"vertxtloc"=>"http://dragonprime.net/users/Eth/",	
		"settings"=>array(
			"Settings,title",			
			"gallowslimit"=>"How many people to list?,int|5",		
			"gallowsloc"=>"Where do the gallows appear?,location|".getsetting("villagename", LOCATION_FIELDS),
												
		),
		"prefs"=>array(
			"Preferences,title",			
		),
	);
	return $info;
}
function gallows_install(){	
		if (db_table_exists(db_prefix("gallows"))) {
			output("");
		}else{
		$sql = array(
			"CREATE TABLE ".db_prefix("gallows")." (id INT( 11 ) NOT NULL AUTO_INCREMENT , name VARCHAR( 50 ) DEFAULT 'Unknown' NOT NULL ,	reason VARCHAR( 200 ) DEFAULT 'No reason' NOT NULL , PRIMARY KEY ( `id` )) TYPE = InnoDB;",
		);	
		foreach ($sql as $statement) {
		db_query($statement);	
		}
	}	
	module_addhook("village-desc");	
	module_addhook("changesetting");
	module_addhook("superuser");
	return true;
}
function gallows_uninstall(){
	return true;
}
function gallows_dohook($hookname,$args){
	global $session;
	switch ($hookname) {
	case "village-desc":
	if ($session['user']['location'] == get_module_setting("gallowsloc")) {					
			output("`n`2In the center of town, you happen to notice gallows have been erected.");
			output(" Would you like to");
			rawoutput(" <a href='runmodule.php?module=gallows&op=examine'>[take a closer look]</a>");
			addnav("","runmodule.php?module=gallows&op=examine");
			output(" `2at this morbid spectacle?`n");
			
	}
	break;
	case "changesetting":
		if ($args['setting'] == "villagename") {
			if ($args['old'] == get_module_setting("gallowsloc")) {
				set_module_setting("gallowsloc", $args['new']);
			}
		}
	break;
	case "superuser":
	$from = "runmodule.php?module=gallows&";
	$edit = translate_inline("Editors");
	$link = translate_inline("Gallows Editor");
	if ($session['user']['superuser'] & SU_EDIT_MOUNTS)addnav("$edit");	
	if ($session['user']['superuser'] & SU_EDIT_MOUNTS)addnav("$link", $from."op=editor&what=view");	
	break;
	}
	return $args;
}
function gallows_runevent($type){	
}
function gallows_run(){
	global $session;
	page_header("Village Gallows");
	$op = httpget('op');
	$from = "runmodule.php?module=gallows&";
	$limit = get_module_setting("gallowslimit");
	if ($op=="examine"){		
		$sql = "SELECT * FROM " . db_prefix("gallows") . " LIMIT $limit";	
		$result = db_query($sql);		
		$count = db_num_rows($result);								
		if ($count == 0){
			output("`2Thankfully for your stomach, no one appears to be swinging from the gallows today.`n`n");							
		}else{	
			$name = translate_inline("$name");
			output("<table cellspacing=0 cellpadding=2 width='100%' align='center'><tr><td>Hanging off the swinging bodies is a sign with their name and reason for execution.</td></tr></table>",true);    
			output("`n");
			output("<table cellspacing=0 cellpadding=2 width='100%' align='center'><tr><td>`b`c`2Below is a Listing of the Condemned`c`b</td></tr><tr><td>",true);    
			for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result); 		    	  	
			output("<tr class='".($i%2?"trlight":"trdark")."'>",true); 
			output("<td>%s</td></tr>",$row['name'],true);	
			output("<tr><td>%s</td></tr>",$row['reason'],true);
			output("<tr class='".($i%2?"trlight":"trdark")."'><td># # # # # # # # # # # # # # # # #</td></tr>",true);
			}						      
			output("</table>",true);
		}	
	villagenav();
	}else if ($op == "editor") {
		$id = httpget('id');
		$cat = httpget('cat');		
			$itemarray=array(
				"Item Properties,title",
				"id"=>"Item ID,hidden",					
				"name"=>"Name,Name|",
				"reason"=>"Reason,Reason|",
				);	
				switch (httpget('what')){
				case "view":							
				$edit = translate_inline("Edit");
				$del = translate_inline("Delete");				
				$delconfirm = translate_inline("Are you sure you wish to delete this item?");	
				$sql = "SELECT * FROM " . db_prefix("gallows") . " ORDER BY id";				
				$result = db_query($sql);
				$count = db_num_rows($result);								
				if ($count == 0){
				output("`6No Condemned on record yet. That may be a good thing.");				
				}else{				
    			output("<table cellspacing=0 cellpadding=2 width='100%' align='center'><tr><td>`bListing of the Condemned`b</td></tr>",true);      			
    			$result = db_query($sql);
    			for ($i=0;$i<db_num_rows($result);$i++){
				$row = db_fetch_assoc($result);
    			output("<tr class='".($i%2?"trlight":"trdark")."'>",true); 
    			rawoutput("<td>[<a href='runmodule.php?module=gallows&op=editor&what=edit&id={$row['id']}'>$edit</a>|<a href='runmodule.php?module=gallows&op=editor&what=delete&id={$row['id']}' onClick='return confirm(\"$delconfirm\");'>$del</a>]");   	   
    			addnav("","runmodule.php?module=gallows&op=editor&what=edit&id={$row['id']}");
				addnav("","runmodule.php?module=gallows&op=editor&what=delete&id={$row['id']}"); 
    			//output("<td>`6%s</td></tr>",$row['id'],true);    			
				output(" Name: %s</td>",$row['name'],true);
				output("</tr>",true);
				output("<tr>",true); 
    			output("<td>Reason: %s</td>",$row['reason'],true);
    			output("</tr>",true);    			
    			}    	
   				output("</table>",true);
				}
				break;

				case "add":
				$row=array(				
				"id"=>"",					
				"name"=>"",
				"reason"=>"",
				);															
				rawoutput("<form action='runmodule.php?module=gallows&op=editor&what=save&id=$id' method='POST'>");
				addnav("","runmodule.php?module=gallows&op=editor&what=save&id=$id");				
				showform($itemarray,$row);
				rawoutput("</form>");							
				break;
				case "edit":
				$sql = "SELECT * FROM " . db_prefix("gallows") . " WHERE id='$id'";				
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				rawoutput("<form action='runmodule.php?module=gallows&op=editor&what=save&id=$id' method='POST'>");
				addnav("","runmodule.php?module=gallows&op=editor&what=save&id=$id");				
				showform($itemarray,$row);
				rawoutput("</form>");			
				break;
				case "save":				
				$itemid = httppost('id');
				$name = httppost('name');
				$reason = httppost('reason');
				//
				if ($itemid>0){
				$sql = "UPDATE ".db_prefix("gallows")." SET name=\"$name\",reason=\"$reason\" WHERE id='$itemid'";
					output("`6The item \"`^$name`6\" has been successfully edited.`n`n");		
				}else{					
					$sql = "INSERT INTO ".db_prefix("gallows")." (name,reason) VALUES (\"$name\", \"$reason\")";					
				output("`6The item \"`^$name`6\" has been saved to the database.`n`n");				
				}
				db_query($sql);				  
				$op = "";
				httpset("op", $op);							
				break;
				case "delete":				
				$sql = "DELETE FROM " . db_prefix("gallows") . " WHERE id='$id'";
				db_query($sql);
				output("Item deleted!`n`n");
				redirect($from."op=editor&what=view");				
				$op = "";
				httpset("op", $op);
				break;				
				}
   				modulehook("gallows-editor", array());      
    			addnav("Functions");
    			addnav("Add a Name", $from."op=editor&what=add");     			
    			addnav("Refresh List", $from."op=editor&what=view");    			
    			addnav("Other");	
				addnav("Return to the Grotto", "superuser.php");				

			
	}
	page_footer();
}
?>