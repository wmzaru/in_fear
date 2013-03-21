<?php
$mailmessage=$_POST['mailmessage'];
$op2 = httpget('op2');
$op3 = httpget('op3');
page_header("Jobs");
output("`c`b`&Job Applications`0`b`c`n`n");
if ($op == ""){
	$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	output("<table border='0' cellpadding='5' cellspacing='0'>",true);
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		$array = unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
		$jobapp=$array['jobapp'];
		if ($jobapp>0){
			$j++;
			output("<tr class='".($j%2?"trlight":"trdark")."'>",true);
			output("<td>",true);
			$acctid=$row['acctid'];
			$reason=$array['reason'];
			$process=translate_inline("`@Process`& ->`0");
			output("`^<a href=\"runmodule.php?module=jobs&place=super&op=process&op2=$acctid&op3=$jobapp\">$process $reason</a>",true);
			output("</tr>",true);
			addnav("","runmodule.php?module=jobs&place=super&op=process&op2=$acctid&op3=$jobapp");
		}
	}
	output("</table>",true);
}
if ($op == "process"){
	$acctid=$op2;
	addnav("`@Approve","runmodule.php?module=jobs&place=super&op=approve&op2=$acctid&op3=$op3");
	addnav("`\$Deny","runmodule.php?module=jobs&place=super&op=deny&op2=$acctid&op3=$op3");
	$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		if ($row['acctid'] == $acctid){
			output("`^<a href=\"runmodule.php?module=jobs&place=super&op=approve&op2=$acctid&op3=$op3\">`@Approve</a> `0or",true);
			addnav("","runmodule.php?module=jobs&place=super&op=approve&op2=$acctid&op3=$op3");
			output("`^<a href=\"runmodule.php?module=jobs&place=super&op=deny&op2=$acctid&op3=$op3\">`\$Deny</a>`0 Job application for ".$row['name']."`7?",true);
			addnav("","runmodule.php?module=jobs&place=super&op=deny&op2=$acctid&op3=$op3");	
		}
	}
}

if ($op == "approve"){
	$acctid=$op2;
	$sql = "SELECT acctid,name,login FROM ".db_prefix("accounts")." WHERE acctid=".$acctid;
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$name=$row['name'];
	$login=$row['login'];
	$allprefs=unserialize(get_module_pref('allprefs','jobs',$acctid));
	$job=$allprefs['jobapp'];
	$filled=0;
	$oddeven=round($op3/2-floor($op3/2));
	if ($op3==0) $oddeven=1;
	if ($oddeven==0){
		$sql2 = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res2 = db_query($sql);
		$row2 = db_fetch_assoc($res2);
		for ($i=0;$i<db_num_rows($res2);$i++){
			$allprefsj=unserialize(get_module_pref('allprefs','jobs',$row2['acctid']));
			if ($allprefsj['job']==$op3) $filled=1;
		}
	}
	if ($filled== 0){
		$typeloc=round($job/2);
		$jobname=get_module_setting("type".$typeloc);
		$mailmessage=translate_inline(array("`7Congratulations %s`7!`n`nYou may start your job at the %s immediately.",$name,$jobname));
		$allprefs=unserialize(get_module_pref('allprefs','jobs',$acctid));
		$allprefs['job']=$job;
		$allprefs['jobapp']=0;
		$allprefs['jobworked']=0;
		$allprefs['lastworked']=date("Y-m-d H:i:s");
		set_module_pref('allprefs',serialize($allprefs),'jobs',$acctid);
		require_once("lib/systemmail.php");
		systemmail($acctid,translate_inline("`2Job Application Approved`2"),$mailmessage);
		addnews_for_user($acctid,"%s`7 got a job at the %s.",$name,$jobname);
		output("Job application for %s approved.`n",$name,get_module_setting("type".$typeloc));
	}else{
		output("Management Job already filled!");
		$op = "deny";
		$op3="filled";
		$op2=$acctid;
	}
}
if ($op == "deny"){
	$acctid=$op2;
	if ($op3=="filled") $mailmessage=translate_inline("The management position for that facility is already filled.");
	if ($mailmessage==""){
		output("Please give a reason.`n");
		output("<form action='runmodule.php?module=jobs&place=super&op=deny&op2=$acctid' method='POST'><input name='mailmessage' id='mailmessage'><input type='submit' class='button' value='submit'></form>",true); 
		output("<script language='JavaScript'>document.getElementById('mailmessage').focus();</script>",true); 
		addnav("","runmodule.php?module=jobs&place=super&op=deny&op2=$acctid");
	}else{
		$sql = "SELECT acctid,name,login FROM ".db_prefix("accounts")." WHERE acctid=".$acctid;
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$name=$row['name'];
		$allprefs=unserialize(get_module_pref('allprefs','jobs',$acctid));
		$allprefs['jobapp']=0;
		set_module_pref('allprefs',serialize($allprefs),'jobs',$acctid);
		require_once("lib/systemmail.php");
		systemmail($acctid,translate_inline("`2Job Application Denied`2"),$mailmessage);
		output("Job application for %s denied.`n",$name);
	}
}
$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
$res = db_query($sql);
$countapps=0;
for ($i=0;$i<db_num_rows($res);$i++){
	$row = db_fetch_assoc($res);
	$allprefsc=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
	if ($allprefsc['jobapp']>0) $countapps=$countapps+1;
}
if ($countapps>0) addnav(array("Process More Job Applications `^(%s)",$countapps),"runmodule.php?module=jobs&place=super");
addnav("Return the the Grotto","superuser.php");
page_footer();
?>