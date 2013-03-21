<?php
function doppleganger_super1(){
	//Based on Lonny's Job Application
	$allprefs=unserialize(get_module_pref('allprefs'));
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$id = httpget('id');
	$ap = httpget('ap');
	addnav("Navigation");
	addnav("Return to the Grotto","superuser.php");
	villagenav();
	page_header("Doppleganger");
	output("`c`b`&Doppleganger Phrase %s Approval`0`b`c`n",$ap);
	if ($op2 == ""){
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		output("<table border='0' cellpadding='5' cellspacing='0'>",true);
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$id=$row['acctid'];
			$allprefss=unserialize(get_module_pref('allprefs','doppleganger',$id));
			if ($allprefss['approve'.$ap] ==3){
				$j++;
				output("<tr class='".($j%2?"trlight":"trdark")."'>",true);
				output("<td>",true);
				$name=$row['name'];
				$reason=$allprefss['phrase'.$ap];
				output("`^<a href=\"runmodule.php?module=doppleganger&op=super1&op2=process&op3=$id&ap=$ap\">`^ From: `0$name`0 -> `2Phrase: `#$reason</a>",true);
				output("</tr>",true);
				addnav("","runmodule.php?module=doppleganger&op=super1&op2=process&op3=$id&ap=$ap");
			}
		}
		output("</table>",true);
	}
	if ($op2 == "process"){
		addnav("Actions");
		$sql = "SELECT acctid,name,login FROM ".db_prefix("accounts")." WHERE acctid=".$op3;
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$name=$row['name'];
		addnav("Approve","runmodule.php?module=doppleganger&op=super1&op2=approve&op3=$op3&ap=$ap");
		addnav("Deny","runmodule.php?module=doppleganger&op=super1&op2=deny&op3=$op3&ap=$ap");
		output("`^<a href=\"runmodule.php?module=doppleganger&op=super1&op2=approve&op3=$op3&ap=$ap\">`@Approve</a> `0or",true);
		addnav("","runmodule.php?module=doppleganger&op=super1&op2=approve&op3=$op3&ap=$ap");
		output("`^<a href=\"runmodule.php?module=doppleganger&op=super1&op2=deny&op3=$op3&ap=$ap\">`\$Deny</a>`0 Doppleganger Phrase ".$ap." for ".$name."`7?",true);
		addnav("","runmodule.php?module=doppleganger&op=super1&op2=deny&op3=$op3&ap=$ap");			
	}
	if ($op2 == "approve"){
		$sql = "SELECT acctid,name,login FROM ".db_prefix("accounts")." WHERE acctid=".$op3;
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$name=$row['name'];
		$allprefsa=unserialize(get_module_pref('allprefs','doppleganger',$op3));
		$allprefsa['approve'.$ap]=1;
		set_module_pref('allprefs',serialize($allprefsa),'doppleganger',$op3);
		require_once("lib/systemmail.php");
		$subj = sprintf("`@Doppleganger `&Phrase %s Approval",$ap);
		$body = sprintf("`^Congratulations.  Your submission for your Doppleganger Phrase %s:`n`n`@%s`n`n`^has been approved.",$ap,$allprefsa['phrase'.$ap]);
		systemmail($op3,$subj,$body);
		output("Doppleganger Phrase %s approved for %s.`n",$ap,$name);
	}
	if ($op2 == "deny"){
		$subop = httpget('subop');
		$submit= httppost('submit');
		if ($subop!="submit"){
			output("Please give a reason.`n");
			$submit = translate_inline("Submit");
			output("<form action='runmodule.php?module=doppleganger&op=super1&op2=deny&op3=$op3&ap=$ap&subop=submit' method='POST'><input name='submit' id='submit'><input type='submit' class='button' value='$submit'></form>",true); 
			addnav("","runmodule.php?module=doppleganger&op=super1&op2=deny&op3=$op3&ap=$ap&subop=submit");
		}else{
			$sql = "SELECT acctid,name,login FROM ".db_prefix("accounts")." WHERE acctid=".$op3;
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$name=$row['name'];
			$allprefsd=unserialize(get_module_pref('allprefs','doppleganger',$op3));
			require_once("lib/systemmail.php");
			$subj = sprintf("`@Doppleganger `&Phrase %s Denied",$ap);
			$body = sprintf("`@Unfortunately your requested phrase for the doppleganger was denied for the following reason:`n`n`#%s`n`n`@You will have a chance to submit another phrase at the next newday.",$submit);
			systemmail($op3,$subj,$body);
			output("`@Doppleganger Phrase`0 %s `@denied for `^%s`n",$allprefs['phrase'.$ap],$name);
			$allprefsd['phrase'.$ap]="";
			$allprefsd['approve'.$ap]=0;
			set_module_pref('allprefs',serialize($allprefsd),'doppleganger',$op3);
		}
	}
	addnav("Process");
	$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	$countapps1=0;
	$countapps2=0;
	$countapps3=0;
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		$allprefsc=unserialize(get_module_pref('allprefs','doppleganger',$row['acctid']));
		if ($allprefsc['approve1']==3) $countapps1=$countapps1+1;
		if ($allprefsc['approve2']==3) $countapps2=$countapps2+1;
		if ($allprefsc['approve3']==3) $countapps3=$countapps3+1;
	}
	if ($countapps1>0) addnav(array("Process Doppleganger Phrase 1 `4(%s)",$countapps1),"runmodule.php?module=doppleganger&op=super1&ap=1");
	if ($countapps2>0) addnav(array("Process Doppleganger Phrase 2 `4(%s)",$countapps2),"runmodule.php?module=doppleganger&op=super1&ap=2");
	if ($countapps3>0) addnav(array("Process Doppleganger Phrase 3 `4(%s)",$countapps3),"runmodule.php?module=doppleganger&op=super1&ap=3");
}
?>