<?php
function masons_suwarning(){
	masons_masonnav1();
	masons_superdiscipline();
	$who = httpget('who');
	if ($who==""){
		output("`^Which `&M`)ason`^ would you like to discipline?`n`n");
		$subop = httpget('subop');
		if ($subop!="search"){
			$search = translate_inline("Search");
			rawoutput("<form action='runmodule.php?module=masons&op=superuserwarning&subop=search' method='POST'><input name='name' id='name'><input type='submit' class='button' value='$search'></form>");
			addnav("","runmodule.php?module=masons&op=superuserwarning&subop=search");
			addnav("Warning");
			addnav("Search Again","runmodule.php?module=masons&op=superuserwarning");
			rawoutput("<script language='JavaScript'>document.getElementById('name').focus();</script>");
		}else{
			addnav("Discipline");
			addnav("Search Again","runmodule.php?module=masons&op=superuserwarning");
			$search = "%";
			$name = httppost('name');
			for ($i=0;$i<strlen($name);$i++){
				$search.=substr($name,$i,1)."%";
			}
			$sql = "SELECT ".db_prefix("module_userprefs").".value, ".db_prefix("accounts").".name,login FROM " . db_prefix("module_userprefs") . "," . db_prefix("accounts") . " WHERE (locked=0 AND name LIKE '$search') AND acctid = userid AND modulename = 'masons' AND setting = 'masonnumber' AND value > 0 ORDER BY (0-value)";
			$result = db_query($sql);
			$max = db_num_rows($result);
			if ($max > 100) {
				output("`n`n`&There are too many `&M`)asons `&to pick from.");
				output("Please choose from the first couple...`n");
				$max = 100;
			}
			if ($max < 1) output("`&There are no `&M`)asons`& by that name. Please try to search again.`n`n");
			if ($max >0) $n = translate_inline("");
				rawoutput("<table border=0 cellpadding=0><tr><td>$n</td><td>$lev</td></tr>");
				for ($i=0;$i<$max;$i++){
					$row = db_fetch_assoc($result);
					rawoutput("<tr><td><a href='runmodule.php?module=masons&op=superuserwarning&who=".rawurlencode($row['login'])."'>");
					output_notl("%s", $row['name']);
					rawoutput("</a></td><td>{$row['level']}</td></tr>");
					addnav("","runmodule.php?module=masons&op=superuserwarning&who=".rawurlencode($row['login']));
				}
			rawoutput("</table>");
		}
	}else{
		$sql = "SELECT name,acctid FROM " . db_prefix("accounts") . " WHERE login='$who'";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$id = $row['acctid'];
		$name = $row['name'];
		output("`^Which Discipline will you administer to %s`^?",$name);
		addnav("Masons To Discipline");
		addnav("Search Again","runmodule.php?module=masons&op=superuserwarning");
		addnav("Discipline Actions");
		addnav("1. Warning for Sharing","runmodule.php?module=masons&op=warningsharing");
		addnav("2. Warning Nonspecific","runmodule.php?module=masons&op=nonspecwarning");
		addnav("3. Dismissal for Sharing","runmodule.php?module=masons&op=dismisssharing");
		addnav("4. Dismissal Nonspecific","runmodule.php?module=masons&op=nonspecdismiss");
		set_module_setting("disciplinename",$name);
		set_module_setting("disciplineid",$id);
	}
}
?>