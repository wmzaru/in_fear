<?php
function rpalign_create($name,$id=''){
	if ($name != ""){
		if ($id == ""){
			$sql = "INSERT INTO `".db_prefix("rpalignment")."` (name) VALUES ('$name')";
			db_query($sql);
		}else{
			$sql = "INSERT INTO `".db_prefix("rpalignment")."` (name, id) VALUES ('$name', $id)";
			db_query($sql);
		}
	}
}

function rpalign_update($name,$id){
	if ($id == "") return;
	$sql = "UPDATE `".db_prefix("rpalignment")."` SET name='$name' WHERE id=$id";
	db_query($sql);
}

function rpalign_delete($id){
	if ($id == "") return;
	$sql = "DELETE FROM `".db_prefix("rpalignment")."` WHERE id=$id";
	db_query($sql);
	output("`^Alignment deleted. I would suggest making sure that all users who were following this alignment are edited, otherwise they will see blank info in their character stats column.`0`n");
	$sql = "SELECT userid FROM ".db_prefix("module_userprefs")." WHERE modulename = 'rpalignment' AND setting = 'alignfollow' AND value='$id'"; 
	$res = db_query($sql);
	while ($row = db_fetch_assoc($res)){
		$sql1 = "UPDATE ".db_prefix("module_userprefs")." SET value=1 WHERE modulename = 'rpalignment' AND setting = 'aligndelete' AND userid={$row['userid']}"; 
		$res1 = db_query($sql1);
	}
	$sql2 = "UPDATE ".db_prefix("module_userprefs")." SET value=0 WHERE modulename = 'rpalignment' AND setting = 'alignfollow' AND value='$id'"; 
	$res2 = db_query($sql2);
	output("`nAll users with this alignment have been set to un-aligned.");
	
	$sql3 = "SELECT name,id FROM ".db_prefix("rpalignment");
	$res3 = db_query($sql3);
	$row3 = db_fetch_assoc($res3);
	
	$aa = 0;
	if(($row3['id'] == 1) && ($row3['name'] == "Good")){
		$aa = 1;
	}
	if(($row3['id'] == 2) && ($row3['name'] == "Neutral")){
		$aa = 1;
	}
	if(($row3['id'] == 3) && ($row3['name'] == "Evil")){
		$aa = 1;
	}
	if ($aa == 0){
		if (get_module_setting("preset") == 1){
			output("`n`n`!All preset alignments have been deleted.");
		}
		set_module_setting("preset",0);
	}
}

function rpalign_info($id){
	if ($id == "") return;
	$sql = "SELECT name FROM `".db_prefix("rpalignment")."` WHERE id=$id";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$ret = array(
		"name"=>$row['name'],
	);
	return $ret;
}

function rpalign_list(){
	$sql = "SELECT name,id FROM `".db_prefix("rpalignment");
	$res = db_query($sql);
	rawoutput("<table border=0><tr class='trhead'><td>Alignment</td><td>Followers</td><td>Members</td></tr>");
	while($row = db_fetch_assoc($res)){
		rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='runmodule.php?module=rpalignment&case=palace&op=view&id=".$row['id']."'>".$row['name']."</a></td><td>".rpalign_followcount($row['id'])."</td><td><a href='runmodule.php?module=rpalignment&case=palace&op=members&id=".$row['id']."'>View Members</a></td></tr>");
		addnav("","runmodule.php?module=rpalignment&case=palace&op=view&id=".$row['id']);
		addnav("","runmodule.php?module=rpalignment&case=palace&op=members&id=".$row['id']);
	}
	rawoutput("</table>");
}

function rpalign_members($id){
	$sql = "SELECT userid FROM `".db_prefix("module_userprefs")."` WHERE modulename='rpalignment' AND setting='alignfollow' AND value='$id'";
	$res = db_query($sql);
	rawoutput("<table border=0><tr class='trhead'><td>Followers</td><td>Gender</td><td>Awake</td></tr>");
	while($row = db_fetch_assoc($res)){
		$sql = "select name,sex,loggedin,laston from ".db_prefix("accounts")." where acctid={$row['userid']}";
	    $r2 = db_query($sql);
		$rr2 = db_fetch_assoc($r2);
		
		$pname = $rr2['name'];
			
		$psex2 = translate_inline($rr2['sex']?"`%Female`0":"`!Male`0");
		
		$loggedin=(date("U") - strtotime($rr2['laston']) < getsetting("LOGINTIMEOUT",900) && $rr2['loggedin']);
		$plog2=translate_inline($loggedin?"`@Yes`0":"`\$No`0");
		
		rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
		if (get_module_setting("linkbio")){
			$link = "bio.php?char=".rawurlencode($row['userid'])."&ret=".urlencode($_SERVER['REQUEST_URI']);
			rawoutput("<a href='$link'>");
			addnav("","$link");
		}
		output_notl("%s`0",$pname);
		if (get_module_setting("linkbio")){
			rawoutput("</a>");
		}
		rawoutput("</td><td>");
		output_notl("%s",$psex2);
		rawoutput("</td><td align='center'>");
		output_notl("%s",$plog2);
		rawoutput("</td></tr>");
		
	}
	if (db_num_rows($res) == 0){
		$none = translate_inline("`i`&None`i");
		rawoutput("<tr class='trdark'><td></td><td>");
		output("%s`0",$none);
		rawoutput("</td><td></td></tr>");
	}
	rawoutput("</table>");
}

function rpalign_followcount($id){
	if ($id == "" || $id <= 0) return;
	$sql = "SELECT userid FROM `".db_prefix("module_userprefs")."` WHERE setting='alignfollow' AND value='$id'";
	$res = db_query($sql);
	$row = db_num_rows($res);
	return $row;
}