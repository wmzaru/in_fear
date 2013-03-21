<?php
function deckofmanythings_deckoracle(){
	global $session;
	$who = httpget('who');
	if ($who==""){
		output("`n`^'Who do you want to know about?'`n`n");
		$subop = httpget('subop');
		if ($subop!="search"){
			$search = translate_inline("Search");
			rawoutput("<form action='runmodule.php?module=deckofmanythings&op=deckoracle&subop=search' method='POST'><input name='name' id='name'><input type='submit' class='button' value='$search'></form>");
			addnav("","runmodule.php?module=deckofmanythings&op=deckoracle&subop=search");
			rawoutput("<script language='JavaScript'>document.getElementById('name').focus();</script>");
			addnav("Ask Again","runmodule.php?module=deckofmanythings&op=deckoracle");
		}else{
			addnav("Search Again","runmodule.php?module=deckofmanythings&op=deckoracle");
			$search = "%";
			$name = httppost('name');
			for ($i=0;$i<strlen($name);$i++){
			$search.=substr($name,$i,1)."%";
		}
		$sql = "SELECT name,alive,location,sex,level,laston,loggedin,login FROM " . db_prefix("accounts") . " WHERE (locked=0 AND name LIKE '$search') ORDER BY level DESC";
		$result = db_query($sql);
		$max = db_num_rows($result);
		if ($max > 100) {
			output("`n`n`7No.  That's too many names to pick from.  I'll let you choose from the first couple...`n");
			$max = 100;
		}
		$n = translate_inline("Name");
		$lev = translate_inline("Level");
		rawoutput("<table border=0 cellpadding=0><tr><td>$n</td><td>$lev</td></tr>");
		for ($i=0;$i<$max;$i++){
			$row = db_fetch_assoc($result);
			rawoutput("<tr><td><a href='runmodule.php?module=deckofmanythings&op=deckoracle&who=".rawurlencode($row['login'])."'>");
			output_notl("%s", $row['name']);
			rawoutput("</a></td><td>{$row['level']}</td></tr>");
			addnav("","runmodule.php?module=deckofmanythings&op=deckoracle&who=".rawurlencode($row['login']));
		}
		rawoutput("</table>");
		}
	}else{
		$sql = "SELECT name,acctid,alive,location,maxhitpoints,gold,gems,sex,level,weapon,armor,attack,race,defense FROM " . db_prefix("accounts") . " WHERE login='$who'";
		$result = db_query($sql);
		if (db_num_rows($result)>0){
			$row = db_fetch_assoc($result);
			$row = modulehook("adjuststats", $row);
			$name = $row['name'];
			output("`^The Oracle closes her eyes and starts to recite the following information:`n`n");
			output("`^`bName:`b`\$ %s`n", $row['name']);
			output("`^`bRace:`b`\$ %s`n",  translate_inline($row['race']));
			output("`^`bLevel:`b`\$ %s`n", $row['level']);
			output("`^`bHitpoints:`b`\$ %s`n", $row['maxhitpoints']);
			output("`^`bGold:`b`\$ %s`n", $row['gold']);
			output("`^`bGems:`b`\$ %s`n", $row['gems']);
			output("`^`bWeapon:`b`\$ %s`n", $row['weapon']);
			output("`^`bArmor:`b`\$ %s`n", $row['armor']);
			output("`^`bAttack:`b`\$ %s`n", $row['attack']);
			output("`^`bDefense:`b`\$ %s`n", $row['defense']);
			output("`n`n`#The `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`# nods and pushes you back to the forest with her `qstick`#.  You turn to protest and suddenly she's gone.`n`n`n");
			debuglog("chose to learn about $name from the Oracle from the Deck of Many Things.");
			addnav("Back to the Forest","forest.php");
			$session['user']['specialinc']="";	
		}else{
			output("'`7Heh...  I don't know anyone named that.'");
			addnav("Back to the Forest","forest.php");
			$session['user']['specialinc']="";	
		}
	}
}
?>