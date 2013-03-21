<?php
$mailmessage=$_POST['mailmessage'];
$furn = httpget('furn');
page_header("Furniture");
output("`c`b`&Furniture Approval`0`b`c`n");
$op2 = httpget('op2');
if ($op == ""){
	$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
	$result = db_query($sql);
	output("<table border='0' cellpadding='5' cellspacing='0'>",true);
	for ($i=0;$i<db_num_rows($result);$i++){
		$row = db_fetch_assoc($result);
		$id=$row['acctid'];
		$name=$row['name'];
		$allprefs=unserialize(get_module_pref('allprefs','jobs',$id));
		for ($h=1;$h<7;$h++) {
			if ($allprefs['cust'.$h]==2) {
				$j++;
				output("<tr class='".($j%2?"trlight":"trdark")."'>",true);
				output("<td>",true);
				$cname1=$allprefs['name'.$h];
				output("`^<a href=\"runmodule.php?module=jobs&place=supername&op=process&op2=$id&furn=$h\">`@Process`& ->`0 $cname1 `@by`^ $name</a>",true);
				output("</tr>",true);
				addnav("","runmodule.php?module=jobs&place=supername&op=process&op2=$id&furn=$h");
			}
		}
	}
	output("</table>",true);
}
if ($op == "process"){
	$tarray=translate_inline(array("Basic Furniture","Customized Standard Chair","Customized Heirloom-Quality Chair","Customized Standard Table","Customized Heirloom-Quality Table","Customized Standard Bed","Customized Heirloom-Quality Bed"));
	addnav("`@Approve","runmodule.php?module=jobs&place=supername&op=approve&op2=$op2&furn=$furn");
	addnav("`\$Deny","runmodule.php?module=jobs&place=supername&op=deny&op2=$op2&furn=$furn");
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=".$op2."";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$name=$row['name'];
	$allprefs=unserialize(get_module_pref('allprefs','jobs',$op2));
	$cname=$allprefs['name'.$furn];
	output("`^<a href=\"runmodule.php?module=jobs&place=supername&op=approve&op2=$op2&furn=$furn\">`@Approve</a> `6or",true);
	addnav("","runmodule.php?module=jobs&place=supername&op=approve&op2=$op2&furn=$furn");
	output("`^<a href=\"runmodule.php?module=jobs&place=supername&op=deny&op2=$op2&furn=$furn\">`\$Deny</a>`6 ".$tarray[$furn]." `6Name:`0 ".$cname." `6by ".$name."`6?",true);
	addnav("","runmodule.php?module=jobs&place=supername&op=deny&op2=$op2&furn=$furn");	
}
if ($op == "approve"){
	$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=".$op2."";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$name=$row['name']; 
	$allprefs=unserialize(get_module_pref('allprefs','jobs',$op2));
	$allprefs['cust'.$furn]=1;
	$custname=$allprefs['name'.$furn];
	set_module_pref('allprefs',serialize($allprefs),'jobs',$op2);
	$storename=get_module_setting("storename","furniture");
	$subj = translate_inline(array("`2Custom Furniture Approved"));
	$body = translate_inline(array("`&Dear %s`&,`n`nThe custom furniture you completed that you have named `6%s`& has been reviewed and is certified as 'Excellent Condition'. It is now available for sale at the %s Store`&. `n`nCongratulations!",$name,$custname,$storename));
	require_once("lib/systemmail.php");
	systemmail($op2,$subj,$body);
	output("Custom Furniture Name by `^%s`0 approved.`n",$name);
}
if ($op == "deny"){
	if ($mailmessage==""){
		output("Please give a reason.`n");
		output("<form action='runmodule.php?module=jobs&place=supername&op=deny&op2=$op2&furn=$furn' method='POST'><input name='mailmessage' id='mailmessage'><input type='submit' class='button' value='submit'></form>",true); 
		output("<script language='JavaScript'>document.getElementById('mailmessage').focus();</script>",true); 
		addnav("","runmodule.php?module=jobs&place=supername&op=deny&op2=$op2&furn=$furn");
	}else{
		$sql = "SELECT acctid,name,login FROM ".db_prefix("accounts")." WHERE acctid=".$op2;
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$name=$row['name'];
		$allprefs=unserialize(get_module_pref('allprefs','jobs',$op2));
		$allprefs['cust'.$furn]=0;
		$allprefs['name'.$furn]="";
		set_module_pref('allprefs',serialize($allprefs),'jobs',$op2);
		require_once("lib/systemmail.php");
		systemmail($op2,translate_inline("`2Custom Furniture Application Denied`2"),$mailmessage);
		output("Custom Furniture Name by %s denied.`n",$name);
	}
}
$sql = "SELECT acctid FROM ".db_prefix("accounts")."";
$res = db_query($sql);
for ($i=0;$i<db_num_rows($res);$i++){
	$row = db_fetch_assoc($res);
	$allprefs=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
	if ($allprefs['cust1']==2) $furncount++;
	if ($allprefs['cust2']==2) $furncount++;
	if ($allprefs['cust3']==2) $furncount++;
	if ($allprefs['cust4']==2) $furncount++;
	if ($allprefs['cust5']==2) $furncount++;
	if ($allprefs['cust6']==2) $furncount++;
}
if ($furncount>0) addnav(array("Process More Furniture Names `@(%s)",$furncount),"runmodule.php?module=jobs&place=supername");
addnav("Return the the Grotto","superuser.php");
page_footer();
?>