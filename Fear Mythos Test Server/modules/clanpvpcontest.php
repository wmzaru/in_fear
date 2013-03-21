<?php


function clanpvpcontest_getmoduleinfo(){
	$info = array(
		"name"=>"Clan PvP Contest",
		"author"=>"`!Q`)wyxzl`0",
		"version"=>"1.0",
		"category"=>"Clan",
		"download"=>"http://dragonprime.net/index.php?topic=8789.0",
		"settings"=>array(
			"Clan PvP Contest Settings,title",
			"NOTE that if you shorten these numbers to less than the currnet number of days left then the contest will switch at the start of the next day.,note|",
			"duration"=>"How long should the contest run in game days?,int|28",
			"truce"=>"How many game days between contests?,int|4",
			"NOTE that these rewards are only given if the Clan Vault module is active.,note|",
			"goldreward"=>"How much gold do the winners get?,int|1000",
			"gemreward"=>"How many gems do the winners get?,int|25",
			"allowsame"=>"Allow wins for attacking clanmates?,bool|0",
			"allownon"=>"Allow wins for attacking people with no clans?,bool|1",
			"Display Options,title",
			"display"=>"Display the number of days till next switch?,bool|1",
			"where"=>"Where to display winner?,location|".getsetting("villagename", LOCATION_FIELDS),
			"hofnum"=>"Display how many results in the HoF page?,int|50",
			"clannum"=>"Display how many results in the clan page?,int|25",
			"Information ONLY,title",
			"howlong"=>"How many days are we into the contest or the rest in between?,viewonly|0",
			"hazcontest"=>"True for during contest and false for rest period.,viewonly|0",
			"first"=>"Are we getting ready for the first contest?,viewonly|1"
		),
		"prefs"=>array(
			"Clan PvP Contest Prefs,title",
			"founded"=>"Did the player just found a clan?,bool|0"
		)
	);
	return $info;
}


function clanpvpcontest_install(){
	module_addhook("index");
	module_addhook("newday-runonce");
	module_addhook("pvpwin");
	module_addhook("pvploss");
	module_addhook("clan-setrank");
	module_addhook("process-createclan");
	module_addhook("footer-clan");
	module_addhook("footer-hof");
	module_addhook("village-desc");
	return true;
}


function clanpvpcontest_uninstall(){
	clanpvpcontest_drop_table();
	return true;
}


function clanpvpcontest_dohook($hookname, $args){	
	if(get_module_setting("first")){
		switch($hookname){
			case "newday-runonce":
				increment_module_setting("howlong");
				$length = max(1, get_module_setting("truce"));
				if(get_module_setting("howlong") >= $length){
					set_module_setting("howlong", 0);
					set_module_setting("first", false);
					clanpvpcontest_add_table();
					set_module_setting("hazcontest", true);
				}
			break;
			case "index":
				if(get_module_setting("display")){
					$day = max(1, get_module_setting("truce") - get_module_setting("howlong"));
					if($day == 1){
						$s = translate_inline("");
					}else{
						$s = translate_inline("s");
					}
					output("`n`b`!The Clan PvP Contest begins in %s day%s!`b`n`n`0", $day, $s);
				}
			break;
		}
		return $args;
	}
	global $session;
	$clans = db_prefix("clans");
	$stats = db_prefix("clanpvpcontest");
	$accounts = db_prefix("accounts");
	
	switch($hookname){
		case "index":;
			$row = clanpvpcontest_leader_row();
			$clan = $row['clanname'];
			$num = $row['total'];
			if($num == 1){
				$s = translate_inline("");
			}else{
				$s = translate_inline("s");
			}
			if(get_module_setting("hazcontest")){
				$state = translate_inline("leader");
				$time = translate_inline("current contest ends");
				$day = max(1, get_module_setting("duration") - get_module_setting("howlong"));
			}else{
				$state = translate_inline("winner");
				$time = translate_inline("next contest begins");
				$day = max(1, get_module_setting("truce") - get_module_setting("howlong"));
			}
			output("`n`b`!The current %s of the Clan PvP Contest is Clan %s with %s win%s!`b`n", $state, $clan, $num, $s);
			if(get_module_setting("display")){
				if($day == 1){
					$s2 = translate_inline("");
				}else{
					$s2 = translate_inline("s");
				}
				output("`b`!The %s in %s day%s.`0`b`n`n", $time, $day, $s2);
			}
		break;
		case "newday-runonce":
			increment_module_setting("howlong");
			if(get_module_setting("hazcontest")){
				$length = max(1, get_module_setting("duration"));
				if(get_module_setting("howlong") >= $length){
					set_module_setting("howlong", 0);
					set_module_setting("hazcontest", 0);
					clanpvpcontest_winner();
				}
			}else{
				$length = max(1, get_module_setting("truce"));
				if(get_module_setting("howlong") >= $length){
					set_module_setting("howlong", 0);
					clanpvpcontest_drop_table();
					clanpvpcontest_add_table();
					set_module_setting("hazcontest", 1);
				}
			}
		break;
		case "pvpwin":
			$myclan = $session['user']['clanid'];
			$myid = $session['user']['acctid'];
			$theirid = $args['badguy']['acctid'];
			$sql = "SELECT clanid FROM $accounts WHERE acctid=$theirid";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$theirclan = $row['clanid'];
			if(clanpvpcontest_can_haz_win($myclan, $theirclan)){
				$sql = "UPDATE $stats SET wins=wins+1 WHERE acctid=$myid AND clanid=$myclan";
				db_query($sql);
			}
		break;
		case "pvploss":
			$myclan = $session['user']['clanid'];
			$myid = $session['user']['acctid'];
			$theirid = $args['badguy']['acctid'];
			$sql = "SELECT clanid FROM $accounts WHERE acctid=$theirid";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$theirclan = $row['clanid'];
			if(clanpvpcontest_can_haz_win($theirclan, $myclan)){
				$sql = "UPDATE $stats SET wins=wins+1 WHERE acctid=$theirid AND clanid=$theirclan";
				db_query($sql);
			}
		break;
		case "clan-setrank":
			if($args['oldrank'] == CLAN_APPLICANT && $args['setrank'] > CLAN_APPLICANT){
				$id = $args['acctid'];
				$clan = $args['clanid'];
				$sql = "INSERT INTO $stats (acctid, clanid) VALUES ($id, $clan)";
				db_query($sql);
			}
		break;
		case "process-createclan":
			if(get_module_setting("hazcontest")){
				set_module_pref("founded", 1);
			}
		break;
		case "footer-clan":
			if($session['user']['clanid'] != 0){
				$founded = get_module_pref("founded");
				if($founded && get_module_setting("hazcontest")){
					set_module_pref("founded", 0);
					$id = $session['user']['acctid'];
					$clan = $session['user']['clanid'];
					$sql = "INSERT INTO $stats (acctid, clanid) VALUES ($id, $clan)";
					db_query($sql);
				}
				if($session['user']['clanrank'] > CLAN_APPLICANT){
					addnav("PvP Information");
					addnav("PvP Rankings","runmodule.php?module=clanpvpcontest&op=clan");
				}
			}
		break;
		case "footer-hof":
			addnav("Warrior Rankings");
			addnav("PvP wins by Clan","runmodule.php?module=clanpvpcontest&op=hof");
		break;
		case "village-desc":
			if ($session['user']['location'] == get_module_setting("where") && !get_module_setting("hazcontest")){
				$row = clanpvpcontest_leader_row();
				$clan = $row['clanname'];
				output("`n`b`!%s won the Clan PvP Contest!!!`0`b`n", $clan);
			}
		break;
		}
	return $args;
}


function clanpvpcontest_run(){
	global $session;
	$op = httpget('op');
	$page = httpget('page');
	$clans = db_prefix("clans");
	$stats = db_prefix("clanpvpcontest");
	$accounts = db_prefix("accounts");
	$mask = SU_HIDE_FROM_LEADERBOARD;
	$nothide = "(locked = 0 AND (superuser & $mask) = 0)";
	$pageoffset = (int)$page;
	$id = $session['user']['clanid'];
	
	if($pageoffset > 0){ 
		$pageoffset--;
	}
	if($op == "hof"){
		$numdisp = get_module_setting("hofnum");
	}elseif ($op == "clan"){
		$numdisp = get_module_setting("clannum");
	}
	$numdisp = max(1, $numdisp);
	$pageoffset *= $numdisp;
	$limit = "LIMIT $pageoffset, $numdisp";
	
	switch ($op){
		case "hof":
			$sql = "SELECT DISTINCT $stats.clanid 
						FROM $stats, $accounts
						WHERE $stats.clanid = $accounts.clanid
						AND $nothide";
			$result = db_query($sql);
			$total = db_num_rows($result);
			$sql = "SELECT clanname FROM $clans WHERE clanid = $id";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$myclan = $row['clanname'];
			$sql = "SELECT clanname, SUM(wins) AS total, COUNT($stats.clanid) AS nummembers
					FROM $clans, $stats
					WHERE $stats.clanid = $clans.clanid
					AND $stats.acctid IN (SELECT acctid
												FROM $accounts
												WHERE $nothide
											)
					GROUP BY $clans.clanid
					ORDER BY total DESC, nummembers ASC, clanname ASC
                  		$limit";
			$result = db_query($sql);
			$rank = translate_inline("Rank");
			$name = translate_inline("Name");
			$pk = translate_inline("PvP Wins");
			page_header("Hall of Fame");
			rawoutput("<big>");
			output("`c`b`^Fiercest Clans in the Land`b`c`0`n");
			rawoutput("</big>");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$pk</td></tr>");
			if($total > 0){
				$i = 0;
				while($row = db_fetch_assoc($result)){
					$i++;
					if($row['clanname'] == $myclan){
						rawoutput("<tr class='trhilight'><td>");
					}else{
						rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
					}
					$num = $pageoffset + $i;
					output_notl("$num.");
					rawoutput("</td><td>");
					output_notl("`&%s`0",$row['clanname']);
					rawoutput("</td><td>");
					output_notl("`c`@%s`c`0",$row['total']);
					rawoutput("</td></tr>");
				}
			}
			rawoutput("</table>");
			if($total > $numdisp){
				addnav("Pages");
				for ($p = 0; $p < $total; $p += $numdisp){
					$page = ($p / $numdisp + 1);
					addnav(array("Page %s (%s-%s)", $page, ($p + 1), min($p + $numdisp, $total)), "runmodule.php?module=clanpvpcontest&op=hof$subop=$subop&page=".$page);
				}
			}
			addnav("Back");
			addnav("Back to HoF", "hof.php");
			page_footer();
		break;
		case "clan":
			$sql = "SELECT $stats.clanid as clanid, clanname, SUM(wins) AS total, COUNT($stats.clanid) AS nummembers
					FROM $clans, $stats
					WHERE $stats.clanid = $clans.clanid 
					AND $stats.acctid IN (SELECT acctid
												FROM $accounts
												WHERE $nothide
											)
					GROUP BY $clans.clanid 
					ORDER BY total DESC, nummembers ASC, clanname ASC
                  		$limit";
			$result = db_query($sql);
			$place = 0;
			while($row = db_fetch_assoc($result)){
				$place++;
				if($row['clanid'] == $session['user']['clanid']){
					break;
				}
			}
			if($row['clanid'] != $session['user']['clanid']){
					$place = 0;
				}
			$sql = "SELECT SUM(wins) AS numkills, COUNT(wins) AS total
					FROM $stats
					WHERE clanid = $id
					AND acctid IN (SELECT acctid
										FROM $accounts
										WHERE $nothide
									)";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$total = $row['total'];
			if($total == ""){
				$total = 0;
			}
			$numkills = $row['numkills'];
			if($numkills == ""){
				$numkills = 0;
			}
			$myname = $session['user']['name'];
			$sql = "SELECT name, dragonkills, wins  
					FROM $accounts, $stats
					WHERE $accounts.clanid = $id  
					AND $accounts.acctid = $stats.acctid
					AND $nothide
					ORDER BY wins DESC, dragonkills ASC, name ASC
					$limit";
			$result = db_query($sql);
			$rank = translate_inline("Rank");
			$name = translate_inline("Name");
			$pk = translate_inline("PvP wins");
			page_header("Clan PvP Information");
			rawoutput("<big>");
			output("`!`b`cTotal PvP wins for the Clan: %s`c`b`n", $numkills);
			if($place != 0){
				output("`!`b`cWe are ranked #%s`c`b`n", $place);
			}else{
				output("`!`b`cWe are not ranked!`c`b`n");
			}
			output("`c`b`^Fiercest Warriors in the Clan`b`c`0`n");
			rawoutput("</big>");
			rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
			rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$pk</td></tr>");
			if($total > 0){
				$i = 0;
				while($row = db_fetch_assoc($result)){
					$i++;
					if($row['name'] == $myname){
						rawoutput("<tr class='trhilight'><td>");
					}else{
						rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
					}
					$num = $pageoffset + $i;
					output_notl("$num.");
					rawoutput("</td><td>");
					output_notl("`&%s`0",$row['name']);
					rawoutput("</td><td>");
					output_notl("`c`@%s`c`0",$row['wins']);
					rawoutput("</td></tr>");
				}
			}
			rawoutput("</table>");
			if($total > $numdisp){
				addnav("Pages");
				for ($p = 0; $p < $total; $p += $numdisp){
					$page = ($p / $numdisp + 1);
					addnav(array("Page %s (%s-%s)", $page, ($p + 1), min($p + $numdisp, $total)), "runmodule.php?module=clanpvpcontest&op=hof&page=".$page);
				}
			}
			addnav("Back");
			addnav("Back to clan halls", "clan.php");
			page_footer();
		break;
		}
}


function clanpvpcontest_can_haz_win($winner, $loser){
	if($winner == 0){
		return false;
	}
	if($loser == 0 && !get_module_setting("allownon")){
		return false;
	}
	if($winner == $loser && !get_module_setting("allowsame")){
		return false;			
	}
	return true;
}


function clanpvpcontest_drop_table(){
	$sql = "DROP TABLE " . db_prefix("clanpvpcontest");
	db_query($sql);
}


function clanpvpcontest_add_table(){
	$sql = "CREATE TABLE " . db_prefix("clanpvpcontest") . "(
		acctid int(11),
		clanid int(11),
		wins int(11) NOT NULL DEFAULT 0,
		PRIMARY KEY(acctid, clanid)
	)";
	db_query($sql);
	$applicant = CLAN_APPLICANT;
	$sql = "SELECT acctid, clanid FROM " . db_prefix("accounts") . " WHERE clanid != 0 AND clanrank != $applicant";
	$result = db_query($sql);
	while($row = db_fetch_assoc($result)){
		$acctid = $row['acctid'];
		$clanid = $row['clanid'];
		$sql = "INSERT INTO " . db_prefix("clanpvpcontest") . " (acctid, clanid) VALUES ($acctid, $clanid)";
		db_query($sql);
	}
}


function clanpvpcontest_leader_row(){
	$stats = db_prefix("clanpvpcontest");
	$clans = db_prefix("clans");
	$accounts = db_prefix("accounts");
	$mask = SU_HIDE_FROM_LEADERBOARD;
	$nothide = "(locked = 0 AND (superuser & $mask) = 0)";
	$sql = "SELECT clanname, $clans.clanid AS clanid, SUM(wins) AS total, COUNT($stats.clanid) AS nummembers
					FROM $clans, $stats
					WHERE $stats.clanid = $clans.clanid
					AND $stats.acctid IN (SELECT acctid
												FROM $accounts
												WHERE $nothide
											)
					GROUP BY $clans.clanid
					ORDER BY total DESC, nummembers ASC, clanname ASC";
	$result = db_query($sql);
	return db_fetch_assoc($result);	
}


function clanpvpcontest_winner(){
	if(is_module_active("clanvault")){
		$row = clanpvpcontest_leader_row();
		$id = $row['clanid'];
		if(get_module_setting("allowgoldinvault", "clanvault")){
			$goldreward = max(0, get_module_setting("goldreward"));
			$havegold = get_module_objpref("clans", $id, "vaultgold", "clanvault");
			$maxgold = get_module_setting("maxgoldinvault", "clanvault");
			$space = $maxgold - $havegold;
			$donation = min($goldreward, $space);
			set_module_objpref("clans", $id, "vaultgold", $havegold + $donation, "clanvault");


		}
		if(get_module_setting("clanvault", "allowgoldinvault")){
			$gemreward = max(0, get_module_setting("gemreward", "clanvault"));	
			$havegem = get_module_objpref('clans', $id, 'vaultgems', 'clanvault');
			$maxgem = get_module_setting("maxgemsinvault", "clanvault");
			$space = $maxgem - $havegem;
			$donation = min($gemreward, $space);
			set_module_objpref("clans", $id, "vaultgems", $havegem + $donation, "clanvault");
		}
	}
}


?>