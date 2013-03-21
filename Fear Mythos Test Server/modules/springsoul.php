<?php
/**************
Name: Spring a Soul
Author: Eth - ethstavern(at)gmail(dot)com 
Version: 1.0
Release Date: 01-10-2006
About: A small addon for the gypsy that allows a player to grant favor
	   to another dead player, springing him/her from the underworld.	 
Translation compatible. No, seriously!
*****************/
require_once("common.php");
require_once("lib/http.php");
function springsoul_getmoduleinfo(){
	$info = array(
		"name"=>"Gypsy Addon - Spring a Soul",
		"version"=>"1.0",
		"author"=>"Eth",
		"category"=>"Gypsy",
		"download"=>"http://dragonprime.net/users/Eth/springsoul.zip",
		"settings"=>array(
            "Spring a Soul - Settings,title",
			"cost"=>"Chance to appear?,int|2",
			"dkreq"=>"DK's needed to ask gypsy for favor?,int|1",			
        ),
        "prefs"=>array(
            "Spring a Soul - User Preferences,title",
			"sprung"=>"Has the user seem the horse already?,bool|0",
        )
	);
	return $info;
}

function springsoul_install(){
	module_addhook("newday");
	module_addhook("gypsy");	
	return true;
}

function springsoul_uninstall(){
	return true;
}

function springsoul_dohook($hookname,$args){
	global $session;
	$from = "runmodule.php?module=springsoul&";
	switch($hookname){		
		case "newday":
		if (get_module_pref("sprung") == 1){
			set_module_pref("sprung",0);
		}
		break;
		case "gypsy":
		if (get_module_pref("sprung") == 0 && $session['user']['dragonkills']>=get_module_setting("dkreq")){
			addnav("Seance");
			addnav("Free a Soul", $from."op=free&what=ask");
		}else{
			output("`n`n`3You have already paid to have a soul sprung from the underworld. You must wait another day.`n`n");
		}
		break;
	}
	return $args;
}

function springsoul_runevent($type,$link){}
function springsoul_run(){
	global $session;
	page_header("Spring a Soul from Purgatory");
	$from = "runmodule.php?module=springsoul&";
	$op = httpget('op');
	$cost = get_module_setting("cost");
	if ($op == "free"){		
		switch (httpget('what')){
			case "ask":			
			output("`5The old woman grows a sinister grin.`n`n");
			output("`5\"Yes, my magic can be worked to spring a soul from the underworld,\" she says.");			
			output("`5\"But such a thing shall cost you `%%s gems`5.\"`n`n", $cost);
			if ($session['user']['gems']<$cost){
				output("`3You are ashamed to find you cannot afford the old woman's price.`n`n");
			}else{
				addnav("Agreed",$from."op=free&what=find");
			}			
			break;
			case "find":
			output("`5The old gypsy asks you who it is you wish to find in the underworld.`n`n");						
			rawoutput("<form action='runmodule.php?module=springsoul&op=free&what=list' method='POST'>");
			rawoutput("<input type='text' name='whom' id='gift' size='25'><br>");
			rawoutput("<input type='submit' value='Search Players'>");
			rawoutput("</form>");
			addnav("","runmodule.php?module=springsoul&op=free&what=list");				
			break;
			case "list":
			$sql = "SELECT acctid,login,name,level,deathpower,sex FROM ".db_prefix("accounts")." WHERE name OR login LIKE '%".$_POST['whom']."%' AND acctid <> ".$session['user']['acctid']." AND locked=0 AND alive=0 ORDER BY level,login";
			$result = db_query($sql);
			$count = db_num_rows($result);			 			
			//unable to find a player
			if ($count == 0){
			  	 output("`3Couldn't find a user by that name. Try again.`n`n"); 		
			}else{	
				rawoutput("<table cellpadding='3' width='500' cellspacing='0' border='0'><tr class='trhead'>");							
				for ($i=0;$i<db_num_rows($result);$i++){			 
					$row = db_fetch_assoc($result);		  	 
					$playerid = $row['acctid'];
					$playername = $row['name'];
					//if selected player(s) meet the requirements, let's find 'em and list 'em 
				  	rawoutput("<td>Name</td><td>Level</td><td>Soul Points</td></tr>");	
					rawoutput("<tr class='".($i%2?"trlight":"trdark")."'>");
					output("<td><a href=\"runmodule.php?module=springsoul&op=free&what=done&playerid=$playerid\">$playername</a></td>", true);				
					addnav("","runmodule.php?module=springsoul&op=free&what=done&playerid=$playerid");
					output("<td>%s</td>",$row['level'],true);
					output("<td>%s</td>",$row['deathpower'],true);					
					output("</tr><tr colspan='5'><td colspan='5'>`3Click on player's name to .</td>",true);								
				}
			}			
			output("</tr></table>",true);
			break;
			case "done":
			$session['user']['gems']-=$cost;
			set_module_pref("sprung",1);
			$playerid = httpget('playerid');
			$sql = "SELECT acctid,login,name,sex,deathpower FROM ".db_prefix("accounts")." WHERE acctid = '$playerid'";
			$result = db_query($sql);
		 	$row = db_fetch_assoc($result);	
		 	$usersex = translate_inline($row['sex']?"her":"him");		
			output("`5Pocketing your gems, the old woman's crystal ball suddenly flares to life with a brilliant show of color as she begins to whisper arcane phrases in a hushed tone.");
			output(" `5A moment later, she looks up to you.`n`n");			
			output("\"`5It is done, %s,\" she rasps. \"I have seen to it that the Lord Ramius looks favorably upon `3%s.`n`n", translate_inline($session['user']['sex']?"my pretty":"my handsome"), $row['login']);
			output("`^A mail has been dispatched to %s informing %s of this!`5`n`n", $row['name'],$usersex);
			$favoradd = ($row['deathpower']+100);
			$sql = "UPDATE " . db_prefix("accounts") . " SET deathpower=$favoradd WHERE acctid='$playerid'";
			db_query($sql);													
			//send a mail(for the clueless)
			require_once("lib/systemmail.php");
			$subject = translate_inline("You have been granted Extra Favor!");
			$mailmessage="".$session['user']['name']." `2has paid the gypsy to grant you enough favor with `\$Ramius `2to be resurrected!`2";			
			$message = translate_inline($mailmessage);
			systemmail($row['acctid'],$subject,$message);	
			break;
		}
		addnav("Go Back", "gypsy.php");		
	}
	page_footer();
}	
?>