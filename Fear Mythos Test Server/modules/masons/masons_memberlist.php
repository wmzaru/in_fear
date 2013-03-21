<?php
function masons_memberlist(){
	global $session;
	$pp = get_module_setting("perpage");
	$page = httpget('page');
	$pageoffset = (int)$page;
	if ($pageoffset > 0) $pageoffset--;
	$pageoffset *= $pp;
	$limit = "LIMIT $pageoffset,$pp";
	$sql = "SELECT COUNT(*) AS c FROM " . db_prefix("module_userprefs") . " WHERE modulename = 'masons' AND setting = 'masonnumber' AND value > 0";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$total = $row['c'];
	$count = db_num_rows($result);
	if (($pageoffset + $pp) < $total){
		$cond = $pageoffset + $pp;
	}else{
		$cond = $total;
	}
	$sql = "SELECT ".db_prefix("module_userprefs").".value, ".db_prefix("accounts").".name FROM " . db_prefix("module_userprefs") . "," . db_prefix("accounts") . " WHERE acctid = userid AND modulename = 'masons' AND setting = 'masonnumber' AND value > 0 ORDER BY (0-value) DESC $limit";
	$result = db_query($sql);
	$rank = translate_inline("Number");
	$name = translate_inline("Name");
	$none = translate_inline("No Members");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td></tr>");
	if (db_num_rows($result)==0) output_notl("<tr class='trlight'><td colspan='3' align='center'>`&$none`0</td></tr>",true);
	else{
		for($i = $pageoffset; $i < $cond && $count; $i++) {
			$row = db_fetch_assoc($result);
			if ($row['name']==$session['user']['name']){
			rawoutput("<tr class='trhilight'><td>");
		}else{
			rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
		}
		$j=$i+1;
		output_notl("$j.");
		rawoutput("</td><td>");
		output_notl("`&%s`0",$row['name']);
		rawoutput("</td></tr>");
		}
	}
	rawoutput("</table>");
	if ($total>$pp){
		addnav("Pages");
		for ($p=0;$p<$total;$p+=$pp){
			addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=masons&op=memberlist&page=".($p/$pp+1));
		}
	}
	masons_masonnav1();
}
?>