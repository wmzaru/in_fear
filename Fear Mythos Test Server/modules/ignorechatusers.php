<?php
function ignorechatusers_getmoduleinfo(){
	$info = array(
		"name" => "Ignore Chat Users",
		"author" => "`i`)Ae`7ol`&us`i`0, idea from HogwartsLive.com",
		"version" => "1.0",
		"category" => "Commentary",
		"settings" => array(
			"Ignore Chat Users Settings,title",
			"stafftobe" => "Allow Staff to be ignored?,bool|0",
			"stafftoig" => "Allow Staff to ignore users?,bool|0",
		),
		"prefs" => array(
			"Ignore Chat Users Prefs,title",
			"ignored" => "Users this player has ignored,text|",
			"return" => "Return link,hidden|",
		),
	);
	return $info;
}

function ignorechatusers_install(){
	module_addhook("everyfooter-loggedin");
	module_addhook("viewcommentary");
	return true;
}

function ignorechatusers_uninstall(){
	return true;
}

function ignorechatusers_dohook($hookname, $args){
	global $session;
	switch($hookname){
		case "everyfooter-loggedin":
			if (function_exists('addcommentary') || function_exists('commentdisplay') || function_exists('viewcommentary')) {
				if ((!$session['user']['superuser']) || ($session['user']['superuser'] && get_module_setting('stafftoig'))){
					addnav("Other");
					addnav("Ignore Chat Users","runmodule.php?module=ignorechatusers");
				}
			}
			if (get_module_pref('return') && !strstr($_SERVER['REQUEST_URI'], "ignorechatusers"))
				clear_module_pref('return');
		break;
		case "viewcommentary":
			if (get_module_pref("ignored")){
				$qn = db_query("SELECT name FROM ".db_prefix("accounts")." WHERE acctid IN (".get_module_pref("ignored").")");
				while ($nn = db_fetch_assoc($qn)){
					$name = str_replace("`0", "", $nn['name']);
					if (strstr(ignorechatusers_format($args['commentline']), $name)) $args['commentline'] = "";
				}
			}
		break;
	}
	return $args;
}

function ignorechatusers_format($q){
	$replace1 = array("<b>", "</b>", "<i>", "</i>", "<s>", "</s>", "<u>", "</u>", "<br>", "</span>");
	$replace2 = array("`b", "`b", "`i", "`i", "`s", "`s", "`u", "`u", "`n", "");
	$q = str_replace($replace1, $replace2, $q);
	// Add extra colours to the end of the array below
	$colors = array( "1" => "colDkBlue", "2" => "colDkGreen", "3" => "colDkCyan", "4" => "colDkRed", "5" => "colDkMagenta", "6" => "colDkYellow", "7" => "colDkWhite", "~" => "colBlack", "!" => "colLtBlue", "@" => "colLtGreen", "#" => "colLtCyan", "\$" => "colLtRed", "%" => "colLtMagenta", "^" => "colLtYellow", "&" => "colLtWhite", ")" => "colLtBlack", "e" => "colDkRust", "E" => "colLtRust", "g" => "colXLtGreen", "G" => "colXLtGreen", "j" => "colMdGrey", "J" => "colMdBlue", "k" => "colaquamarine", "K" => "coldarkseagreen", "l" => "colDkLinkBlue", "L" => "colLtLinkBlue", "m" => "colwheat", "M" => "coltan", "p" => "collightsalmon", "P" => "colsalmon", "q" => "colDkOrange", "Q" => "colLtOrange", "R" => "colRose", "T" => "colDkBrown", "t" => "colLtBrown", "V" => "colBlueViolet", "v" => "coliceviolet", "x" => "colburlywood", "X" => "colbeige", "y" => "colkhaki", "Y" => "coldarkkhaki" );
	foreach ($colors as $logd => $html){
		$q = str_replace("<span class='".$html."'>", "`".$logd, $q);
	}
	return $q;
}

function ignorechatusers_sanitize($in){
	$out = preg_replace("'[&?]c=[[:digit:]-]+'", "", $in);
	$out = substr($out,strrpos($out,"/")+1);
	return $out;
}

function ignorechatusers_run(){
	global $session;
	
	page_header("Ignore Chat Users");
	
	if (!get_module_pref('return')){
		set_module_pref('return', ignorechatusers_sanitize($_SERVER['HTTP_REFERER']));
	}
	
	addnav("Return");
	addnav("Return from whence you came", get_module_pref('return'));
	
	$op = httpget("op");
	$ignoredp = get_module_pref("ignored");
	$tsearch = translate_inline("Search");
	$renamed = translate_inline("Remove User");
	$ignoreuser = translate_inline("Ignore User");
	if ($ignoredp) $ignored = explode(",",$ignoredp);
	else $ignored = array();
	
	rawoutput("<form action='runmodule.php?module=ignorechatusers&op=search' method='post'>");
	addnav("","runmodule.php?module=ignorechatusers&op=search");
	output("Search by name");
	rawoutput("<input type='text' name='searchname' /> <input type='submit' class='button' value='$tsearch' /> </form>");
	
	output_notl("`c`n");
	
	if ($op == "submit"){
		$addusers = httppost("addusers");
		if (is_array($addusers)){
			foreach ($addusers as $theuser){
				$ignored[] = $theuser;
			}
			set_module_pref("ignored",implode(",",$ignored));
		}
	}
	
	if ($op == "search"){
		output_notl("<big>",TRUE);
		output("`7`bSearch Results`b`0");
		output_notl("</big>`n`n",TRUE);
		
		$acc = $ex = "";
		if (!get_module_setting('stafftobe')) $ex = "AND superuser = 0";
		$percname = implode("%",str_split(httppost("searchname")));
		if ($ignoredp) $acc = "AND acctid NOT IN (".$ignoredp.")";
		
		$sql = "SELECT name, acctid FROM ".db_prefix("accounts")." WHERE (name LIKE '%$percname%' OR login LIKE '%$percname%') AND acctid <> ".$session['user']['acctid']." $acc $ex";
		$res = db_query($sql);
		if (db_num_rows($res)){
			rawoutput("<form action='runmodule.php?module=ignorechatusers&op=submit' method='post'>");
			addnav("","runmodule.php?module=ignorechatusers&op=submit");
			$i = 1;
			rawoutput("<table border='1' width='50%' cellpadding='2'>");
			rawoutput("<tr align='center'><td width='5%'>#</td><td>Name</td><td width='5%'>&nbsp;</td></tr>");
			while ($row = db_fetch_assoc($res)){
				rawoutput("<tr><td>");
					output_notl("%s", $i);
				rawoutput("</td><td>");
					output("%s", $row['name']);
				rawoutput("</td><td>");
					rawoutput("<input type='checkbox' name='addusers[]' value='".$row['acctid']."' />");
				rawoutput("</td></tr>");
				$i++;
			}
			rawoutput("<tr align='center' rowspan='2'><td colspan='3'><input type='submit' class='button' value='$ignoreuser' /></td></tr>");
			rawoutput("</table></form>");
		} else {
			output("`7`bNo Users Found`b`0`n`n");
		}
		output_notl("`n");
	}
	
	if ($op == "remove"){
		$users = httppost("users");
		if (is_array($users)){
			foreach ($users as $duser){
				$key = array_search($duser, $ignored);
				unset($ignored[$key]);
			}
			set_module_pref("ignored",implode(",",$ignored));
		}
	}
	
	output_notl("<big>",TRUE);
	output("`7`bList of Ignored Users`b`0");
	output_notl("</big>`n`n",TRUE);
	
	if (is_array($ignored) && count($ignored)){
		rawoutput("<form action='runmodule.php?module=ignorechatusers&op=remove' method='post'>");
		addnav("","runmodule.php?module=ignorechatusers&op=remove");
		rawoutput("<table border='1' width='50%' cellpadding='2'>");
		rawoutput("<tr align='center'><td width='5%'>#</td><td>Name</td><td width='5%'>&nbsp;</td></tr>");
		foreach ($ignored as $key => $user){
			rawoutput("<tr><td>");
				output_notl("%s", $key+1);
			rawoutput("</td><td>");
				$sn = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid = $user";
				$nn = db_fetch_assoc(db_query($sn));
				output("%s", $nn['name']?$nn['name']:translate_inline("`7`i- Deleted User -`i`0"));
			rawoutput("</td><td>");
				rawoutput("<input type='checkbox' name='users[]' value='$user' />");
			rawoutput("</td></tr>");
		}
		rawoutput("<tr align='center' rowspan='2'><td colspan='3'><input type='submit' class='button' value='$renamed' /></td></tr>");
		rawoutput("</table></form>");
	} else {
		output_notl("`n");
		output("`7`bNo Users Ignored`b`0");
		output_notl("`n`n");
	}
	
	output_notl("`c");
	
	page_footer();
}
?>