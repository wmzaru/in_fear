<?php

function bulletin_getmoduleinfo(){
	$info = array(
		"name"=>"Clan Bulletins",
		"author"=>"Chris Vorndran",
		"version"=>"1.51",
		"category"=>"Clan",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=175",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"description"=>"This module allows for the Leaders (or Officers) of a clan, to send out a bulletin to all of their clan members.",
		"settings"=>array(
			"Clan Bulletin Settings,title",
			"subject"=>"Subject for the Clan Bulletines,text|Clan Matters",
			"pp"=>"How many to display per page in Mailing List,int|50",
			"rec"=>"How many entries in recent bulletin's table,int|5",
		),
		"prefs"=>array(
			"Clan Bulletin Prefs,title",
			"onm"=>"Is this user on the Clan Mailing List,bool|0",
			),
		"prefs-clans"=>array(
			"bulletins"=>"Bulletins,viewonly|",
			"eno"=>"Enabled for Officers,bool|0",
			),
		);
	return $info;
}
function bulletin_install(){
    if (db_table_exists(db_prefix("clanbullet"))){
		$sql = "DROP TABLE ".db_prefix("clanbullet")."";
		db_query ($sql);
	}
	module_addhook("footer-clan");
	return true;
}
function bulletin_uninstall(){
	return true;
}
function bulletin_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "footer-clan":
			if (httpget('op') == "" && $session['user']['clanrank'] >= CLAN_MEMBER){	
					addnav("~");
					addnav("Clan Bulletin","runmodule.php?module=bulletin&op=enter");
			}
			break;
	}
	return $args;
}
function bulletin_run(){
	global $session;
	page_header("Clan Bulletins");
	$id = $session['user']['clanid'];
	$op = httpget('op');
	$op2 = httpget('op2');
	$subject = get_module_setting("subject");
	$body = httppost('body');
	$rec = get_module_setting("rec");
	$ranks = array(CLAN_APPLICANT=>"`!Applicant`0",CLAN_MEMBER=>"`#Member`0",CLAN_OFFICER=>"`^Officer`0",CLAN_LEADER=>"`&Leader`0", CLAN_FOUNDER=>"`\$Founder");
	$args = modulehook("clanranks", array("ranks"=>$ranks, "clanid"=>$session['user']['clanid']));
	$ranks = translate_inline($args['ranks'], "clan");
	$obj = get_module_objpref("clans",$id,"bulletins");
	$enable_officers = get_module_objpref("clans",$id,"eno");
	$bullet = unserialize($obj);
	if (!is_array($bullet)) {
		$bullet = array();
		set_module_objpref("clans",$id,"bulletins",serialize($bullet));
	}
	$mu = db_prefix("module_userprefs");
	$ac = db_prefix("accounts");

	switch ($op){
		case "enter":
			switch (httpget('op2')){
				case "on":
					set_module_objpref("clans",$id,"eno",1);
					break;
				case "off":
					set_module_objpref("clans",$id,"eno",0);
					break;
			}
			if ($session['user']['clanrank'] >= CLAN_LEADER || 
				($enable_officers && $session['user']['clanrank'] == CLAN_OFFICER)){
				output("`QYou wander into a small hallway, noting the large paintings of past Clan Events.");
				output("You reach a large oak desk, and pull out a small Quill.");
				output("A butler walks forth, \"`#M'lord, is it time to send out the Newsletter?`Q\"");
				addnav("Options");
				if ($session['user']['clanrank'] >= CLAN_LEADER){
					if ($enable_officers){
						addnav("Turn off Officer Sending","runmodule.php?module=bulletin&op=enter&op2=off");
					}else{
						addnav("Turn on Officer Sending","runmodule.php?module=bulletin&op=enter&op2=on");
					}
				}
			}else{
				output("`QYou can see that a butler is standing in the corner, eagerly awaiting something.");
			}
			break;
		case "send":
			if ($body == ""){
				rawoutput("<form action='runmodule.php?module=bulletin&op=send' method='POST'>");
				output("`n`^Message to All on Mailing List:`n`n");
				rawoutput("<textarea name=\"body\" rows=\"10\" cols=\"60\" class=\"input\"></textarea>");
				rawoutput("<input type='submit' class='button' value='".translate_inline("Send")."'></form>");
				rawoutput("</form>");				
			}else{
				$sql = "SELECT acctid FROM $ac INNER JOIN $mu ON acctid=userid WHERE setting='onm' AND modulename='bulletin' AND value='1' AND clanid='$id'";
				$result = db_query($sql);
				while($row = db_fetch_assoc($result)){
					require_once("lib/systemmail.php");
					systemmail($row['acctid'],$subject,$body);
				}
				output("Message has been sent.");
				$new_bulletin = array(
					"id"=>$session['user']['acctid'],
					"rank"=>$session['user']['clanrank'],
					"body"=>$body
					);
				$bullet[] = $new_bulletin;
				if (count($bullet) > get_module_setting("rec")) array_shift($bullet);
				set_module_objpref("clans",$id,"bulletins",serialize($bullet));
			}
			addnav("","runmodule.php?module=bulletin&op=send");
	        break;
		case "recent":
			rawoutput("<big>");
			output("`c`^`b%s Most Recent Bulletins`b`c`n`0",$rec);
			rawoutput("</big>");
			$author = translate_inline("Author");
			$rank = translate_inline("Clan Rank");
			$but = translate_inline("Bulletin");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td>$author</td><td>$rank</td><td>$but</td></tr>");
			$bulletins = unserialize(get_module_objpref("clans",$id,"bulletins"));
			$bulletins = array_reverse($bulletins);
			for($i = 0; $i < count($bulletins); $i++) {
				rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td align='center'>");
				output_notl("`^%s`0",getname($bulletins[$i]['id']));
				rawoutput("</td><td align='center'>");
				output_notl("%s`0",$ranks[$bulletins[$i]['rank']]);
				rawoutput("</td><td align='center'>");
				require_once("lib/nltoappon.php");
				output_notl("`@%s`0",nltoappon($bulletins[$i]['body']));
				rawoutput("</td></tr>");
			}
			rawoutput("</table>");
			break;
		case "list":
			page_header("Clan Bulletins: Mailing List");
			switch (httpget('op2')){
				case "add":
					set_module_pref("onm",1);
					break;
				case "del":
					set_module_pref("onm",0);
					break;
			}
			if (!get_module_pref("onm")){
				addnav("Join Mailing List","runmodule.php?module=bulletin&op=list&op2=add");
			}else{
				addnav("Unsubscribe","runmodule.php?module=bulletin&op=list&op2=del");
			}
			$pp = get_module_setting("pp");
			$page = httpget('page');
			$pageoffset = (int)$page;
			if ($pageoffset > 0) $pageoffset--;
			$pageoffset *= $pp;
			$from = $pageoffset+1;
			$limit = "LIMIT $pageoffset,$pp";
			$sql = "SELECT COUNT(userid) AS c FROM $mu INNER JOIN $ac ON userid=acctid WHERE clanid='$id' AND modulename = 'bulletin' AND setting = 'onm' AND value='1'";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$total = $row['c'];
			$count = db_num_rows($result);
			if ($from + $pp < $total){
				$cond = $pageoffset + $pp;
			}else{
				$cond = $total;
			}
			$sql = "SELECT $mu.value, $ac.name, $ac.clanrank FROM $mu , $ac WHERE acctid = userid AND modulename = 'bulletin' AND setting = 'onm' AND value = '1' AND clanid='$id' ORDER BY clanrank DESC $limit";
			$result = db_query($sql);
			$num = translate_inline("Number");
			$name = translate_inline("Name");
			$rank = translate_inline("Clan Rank");
			rawoutput("<big>");
			output("`c`b`^Clan Mailing List`b`c`0`n");
			rawoutput("</big>");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td>$num</td><td>$name</td><td>$rank</td></tr>");
			if (db_num_rows($result)>0){
				$i = 0;
				while($row = db_fetch_assoc($result)){
					$i++;
					if ($row['name']==$session['user']['name']){
						rawoutput("<tr class='trhilight'><td>");
					} else {
						rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
					}
					output_notl("$i.");
					rawoutput("</td><td>");
					output_notl("`&%s`0",$row['name']);
					rawoutput("</td><td>");
					output_notl("`c`@%s`c`0",$ranks[$row['clanrank']]);
					rawoutput("</td></tr>");
				}
			}
			rawoutput("</table>");
		if ($total>$pp){
			addnav("Pages");
			for ($p=0;$p<$total;$p+=$pp){
				addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=bulletin&op=list&page=".($p/$pp+1));
			}
		}
		break;
}
addnav("Options");
if ($session['user']['clanrank'] >= CLAN_LEADER 
	|| ($enable_officers && $session['user']['clanrank'] == CLAN_OFFICER)){
	if ($op != "send") addnav("Send Newsletter","runmodule.php?module=bulletin&op=send");
}
addnav("Functions");
if ($op != "list") addnav("Mailing List","runmodule.php?module=bulletin&op=list");
if ($op != "recent") addnav("Recent Bulletins","runmodule.php?module=bulletin&op=recent");
addnav("Leave");
addnav("Return to the Clan Hall","clan.php");	
page_footer();
}
function getname($id){
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=$id";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	return $row['name'];
}
?>