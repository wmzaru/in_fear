<?php
$j = httpget('place');
$jobname=get_module_setting("type".$j);
$header = color_sanitize($jobname);
page_header("%s",$header);
output_notl("`c`b`&%s`0`b`c`n",$jobname);
$allprefs=unserialize(get_module_pref('allprefs'));
$exp=array(0,250,750,1500,2500,4500,7500,10000,15000,20000,25000);
$oddeven=round($allprefs['job']/2-floor($allprefs['job']/2));
if ($allprefs['job']==0) $oddeven=1;
if (get_module_setting("fire")==0 && $allprefs['lastworked'] < date("Y-m-d H:i:s",strtotime("-864000 seconds")) && floor($allprefs['job']/2)==$j){
	output("You have been fired for not showing up to work for too long!`n`n");
	if(get_module_setting("vacation")==1) output("Next time you should apply for vacation at the Job Services Office if you will be gone for a long time!`n`n"); 
	//manager positions
	if ($oddeven==0) $allprefs['jobexp']=$exp[10];
	//first job
	elseif ($allprefs['job']==1){
		$allprefs['jobexp']-=15;
		if ($allprefs['jobexp']<0) $allprefs['jobexp']=0;
	//all others
	}else $allprefs['jobexp']=$exp[$allprefs['job']-1]+1;
	$allprefs['ffspent']=0;
	$allprefs['type']=0;
	$allprefs=unserialize(get_module_pref('allprefs'));
	$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		$allprefsj=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
		if ($allprefsj['job']==$allprefs['job']+1 && get_module_pref("user_stat","jobs",$row['acctid'])==1){
			require_once("lib/systemmail.php");
			$subj = translate_inline(array("`2Delinquent Employee %s `2at the %s",$session['user']['name'],$jobname));
			$body = translate_inline(array("`&Dear %s`&,`n`nAs the manager of the %s, I thought you should know that %s`& has been fired for failure to show up to work.`n`nSincerely,`nThe Foreman",$row['name'],$jobname,$session['user']['name']));
			systemmail($row['acctid'],$subj,$body);
		}
	}
	$allprefs['job']=0;
	set_module_pref('allprefs',serialize($allprefs));
}
if ($op == ""){
	output("This is a %s: %s`n`n",$jobname,get_module_setting("desc".$j));
	$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	$new_array = array();
	while($row = db_fetch_assoc($res)){
		$array = unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
		if ($array['job']==$j*2-1) $new_array[$row['name']] = $array['jobexp'];
	}
	$n=0;
	arsort($new_array);
	foreach($new_array AS $name => $value){
		$n=$n+1;
		if ($n==1) output("`7`cOn a Plaque it says:`n`bOur Top Employee: %s`0`c`b`n",$name);
	}
}
if ($allprefs['jobapp'] ==$j*2-1) output("You wonder if you will get the job here that you applied for.`n");
//  if   Your job is less than this one and you didn't quit and you are not a manager and you're not already applying for this job and you just entered this area...
elseif ($allprefs['job'] < ($j*2-1) && $allprefs['job'] >=0 && $oddeven==1 && $allprefs['jobapp'] <> $j*2-1 && $op == ""){
	output("You see a sign on the outside that says 'Help Wanted'.  You realize that a job would be a great way to earn some gold.  Good hard work can cleanse the soul.`n`n");
	addnav("Apply for a Job","runmodule.php?module=jobs&place=$j&op=apply");
}elseif ($allprefs['job'] == $j*2-1){
	if ($allprefs['jobworked']==0 && $op == ""){
		output("You come back to the %s.  As you are employed here it would be good to work at least one shift if you intend to keep your job.`n",$jobname);
		if ($session['user']['turns'] > 0) addnav("Work","runmodule.php?module=jobs&place=$j&op=work");
	}elseif ($allprefs['jobworked']==1  && $op!=="finish" && $op!="quit" && $op!=="yesquit") output("You have already worked today, come back again tommorow.`n");
	addnav("Quit your job","runmodule.php?module=jobs&place=$j&op=quit");
}elseif ($allprefs['job'] == $j*2){
	if ($allprefs['jobworked']==0 && $op == ""){
		output("You come back to the %s.  As you are a manager here it would be good to work at least one shift if you intend to keep your job.`n",$jobname);
		if ($session['user']['turns'] > 0) addnav("Work","runmodule.php?module=jobs&place=$j&op=work");
	}elseif ($allprefs['jobworked']==1 && $op!=="finish") output("You have already worked today, come back again tommorow.`n");
	addnav("Quit your job","runmodule.php?module=jobs&place=$j&op=quit");
}elseif ($allprefs['job'] > $j*2 || $oddeven==0) output("You have fond memories of many a hard day working on the %s.  Hard work like that helped to build character and make you the successful person you are today.`n",$jobname);
	
if ($op == "quit"){
	output("Are you sure you want to quit your job?");
	addnav("Yes","runmodule.php?module=jobs&place=$j&op=yesquit");
	addnav("No","runmodule.php?module=jobs&place=$j");
	blocknav("runmodule.php?module=jobs&place=$j&op=quit");
	blocknav("runmodule.php?module=jobs&place=industrialpark");
	blocknav("village.php");
}
if ($op == "yesquit"){
	output("Your letter of resignation has been submitted, you no longer work here. You won't be able to get another job until the next system new day.");
	if ($allprefs['job']==$j*2-1) {
		addnews("%s`7 quit their job at the %s today.",$session['user']['name'],$jobname);
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$allprefsj=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
			if ($allprefsj['job']==$allprefs['job']+1 && get_module_pref("user_stat","jobs",$row['acctid'])==1){
				require_once("lib/systemmail.php");
				$subj = translate_inline(array("`2Employee %s `2Quit the %s",$session['user']['name'],$jobname));
				$body = translate_inline(array("`&Dear %s`&,`n`nAs the manager at the %s, I thought you should know that %s`& has quit the %s.`n`nSincerely,`nThe Foreman",$row['name'],$jobname,$session['user']['name'],$jobname));
				systemmail($row['acctid'],$subj,$body);
			}
		}
	}else addnews("%s`7 quit their job as %s manager today.",$session['user']['name'],$jobname);
	if ($allprefs['job']==6){
		$allprefs['ffspent']=0;
		$allprefs['type']=0;
	}
	$allprefs['job']=-1;
	set_module_pref('allprefs',serialize($allprefs));
	debuglog("quit their job at the $jobname today.");
	blocknav("runmodule.php?module=jobs&place=$j&op=quit");
}
if ($op == "apply"){
	output("<big><big><span style=\"font-weight: bold;\">Job Application<br></span><small><small>",true);
	output("`n`7Name: %s`7.`n",$session['user']['name']);
	output("`7Position Applied for: %s`n",$jobname);
	output("<form action='runmodule.php?module=jobs&place=$j&op=applied' method='POST'>",true);
	output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true); 
	output("<p>Are you currently Employed? <input type=\"radio\" name='C1' value='2' checked>No",true);
	output("<input type='radio' name='C1' value='1'>Yes</p>",true);
	output("<p>Are you currently wanted for any crimes? <input type=\"radio\" name='C2' value='2' checked>No",true);
	output("<input type='radio' name='C2' value='1'>Yes</p>",true);
	output("<p>Please give a short reason why you would be qualified for this job: <input type=\"text\" name=\"T1\" size=\"37\"></p>",true);
	output("<p><input type=\"submit\" value=\"Submit\" name=\"B1\"><input type=\"reset\" value=\"Reset\" name=\"B2\"></p>",true);
	output("</form>",true);
	addnav("","runmodule.php?module=jobs&place=$j&op=applied");
}
if ($op == "applied"){
	if ($allprefs['jobexp']>=$exp[$j-1] || ($allprefs['jobexp']<0 && $j==1)){
		if (get_module_setting("requireapp")==1){
			$allprefs['jobapp']=$j*2-1;
			$mailmessage=$session['user']['name'];
			$mailmessage.=translate_inline(" has applied for a job at the ");
			$mailmessage.=$jobname;
			if ($C1 == 1) $mailmessage.=translate_inline(".`nThey are currently employed. ");
			else $mailmessage.=translate_inline(".`nThey are NOT currently employed. ");
			if ($C2 == 1) $mailmessage.=translate_inline("`nThey say that they `4ARE`0 currently wanted for crimes against society. ");
			else $mailmessage.=translate_inline("`nThey say that they are currently NOT wanted for any crimes. ");
			$mailmessage.=translate_inline(" `nThey comment:`n");
			$mailmessage.=$T1;
			$allprefs['reason']=stripslashes($mailmessage);
			set_module_pref('allprefs',serialize($allprefs));
			$sql = "SELECT acctid,value FROM ".db_prefix('module_userprefs')." LEFT JOIN ".db_prefix('accounts')." ON (acctid = userid) WHERE modulename='jobs' and setting='email' and value ='1'";
			$result = db_query($sql);
			for ($i=0;$i<db_num_rows($result);$i++){
				$row = db_fetch_assoc($result);
				require_once("lib/systemmail.php");
				if ($row['value'] == 1 && get_module_pref("super","jobs",$row['acctid'])==1) systemmail($row['acctid'],translate_inline("`2Job Application`2"),$mailmessage);  
			}
			output("Job application sent to Human Resources.");
		}else{
			output("Your application has been reviewed.  Congratulations! You now have a job at the %s.",$jobname);
			$allprefs['job']=$j*2-1;
			$allprefs['jobapp']=0;
			$allprefs['dayssince']=0;
			$allprefs['jobworked']=0;
			$allprefs['lastworked']=date("Y-m-d H:i:s");
			set_module_pref('allprefs',serialize($allprefs));
			addnews("%s got a job at the %s.",$session['user']['name'],$jobname);
			addnav(array("Back to the %s", $jobname),"runmodule.php?module=jobs&place=$j");
		}
	}else{
		output("`7The lady looks at your application and shakes her head.  She says \"I am sorry %s`7 but you do not have enough job experience to work here.",$session['user']['name']);
		output("I suggest you work somewhere a little easier for a while and get more work experience.  We only hire more experienced workers here.\"");
		output("You feel a little dejected but think maybe a little more job experience would be good.`n");
	}
}
if ($op == "custom"){
	output("Here are your options for custom furniture:`n`n");
	$fname=translate_inline("`bType of Furniture`b");
	$fturn=translate_inline("`bTurns to Complete`b");
	$fstone=translate_inline("`bStone Needed`b");
	$fwood=translate_inline("`bWood Needed`b");
	$fpgold=translate_inline("`bBonus Gold Pay`b");
	$fpgem=translate_inline("`bBonus Gem Pay`b");
	//table heading
	rawoutput("<table border='0' cellpadding='0'>");
	rawoutput("<tr class='trhead'><td>");
	output_notl($fname);
	rawoutput("</td><td><center>");
	output_notl($fturn);
	if (is_module_active("quarry")){
		rawoutput("</center></td><td><center>");
		output_notl($fstone);
	}
	if (is_module_active("lumberyard")){
		rawoutput("</center></td><td><center>");
		output_notl($fwood);
	}
	rawoutput("</center></td><td><center>");
	output_notl($fpgold);
	rawoutput("</center></td><td><center>");
	output_notl($fpgem);
	rawoutput("</center></td></tr>");
	addnav("Custom Work");
	for ($i=1;$i<7;$i++) {
		rawoutput("<tr class='trlight'><td>");
		rawoutput("<a href='runmodule.php?module=jobs&place=10&op=choosef&custchoi=$i'>");
		addnav("","runmodule.php?module=jobs&place=10&op=choosef&custchoi=$i");
		if ($i==1) $line=translate_inline("Standard Chair");
		elseif ($i==2) $line=translate_inline("Heirloom Chair");
		elseif ($i==3) $line=translate_inline("Standard Table");
		elseif ($i==4) $line=translate_inline("Heirloom Table");
		elseif ($i==5) $line=translate_inline("Standard Bed");
		elseif ($i==6) $line=translate_inline("Heirloom Bed");
		output("%s",$line);
		rawoutput("</td><td><center>");
		output_notl("%s",get_module_setting("cust".$i));
		if (is_module_active("quarry")){
			rawoutput("</center></td><td><center>");
			output_notl("`)%s",get_module_setting("cstone".$i));
		}
		if (is_module_active("lumberyard")){
			rawoutput("</center></td><td><center>");
			output_notl("`&%s",get_module_setting("cwood".$i));
		}
		rawoutput("</center></td><td><center>");
		output_notl("`^%s",get_module_setting("cgold".$i));
		rawoutput("</center></td><td><center>");
		output_notl("`%%s",get_module_setting("cgems".$i));
		rawoutput("</a></center></td></tr>");
		addnav(array("%s", $line),"runmodule.php?module=jobs&place=10&op=choosef&custchoi=$i");
	}
	//End Table
	rawoutput("</table>");
	addnav("Other");
	addnav("Return to Wood Shop","runmodule.php?module=jobs&place=10&op=work");
	blocknav("runmodule.php?module=jobs&place=10&op=quit");
}
if ($op == "choosef"){
	$allprefs=unserialize(get_module_pref('allprefs'));
	$t = httpget('custchoi');
	$confirm= httpget('confirm');
	$tarray=translate_inline(array("Basic Furniture","Customized Standard Chair","Customized Heirloom-Quality Chair","Customized Standard Table","Customized Heirloom-Quality Table","Customized Standard Bed","Customized Heirloom-Quality Bed"));
	$allprefsl=unserialize(get_module_pref("allprefs","lumberyard"));
	$squares=$allprefsl["squares"];
	$allprefsq=unserialize(get_module_pref("allprefs","quarry"));
	$blocks=$allprefsq["blocks"];
	if ($blocks=="") $blocks=0;
	if ($squares=="") $squares=0;
	$costwood=get_module_setting("cwood".$t);
	$coststone=get_module_setting("cstone".$t);
	if ($costwood>$squares || $coststone>$blocks){
		output("You will need to go to the ");
		if ($costwood>$squares) output("lumberyard to get some more wood");
		if ($costwood>$squares && $coststone>$blocks) output("and the");
		if ($coststone>$blocks) output("quarry to get some more stone");
		output("to make a %s.",$tarray[$t]);
	}elseif ($allprefs['cust'.$t]>0 && $confirm <> 1){
		output("You have finished a %s already. If you decide to make a new one, your old one will be replaced with this piece of furniture.",$tarray[$t]);
		output("`n`nWould you like to reconsider?");
		addnav("Make this Furniture","runmodule.php?module=jobs&place=10&op=choosef&custchoi=$t&confirm=1");
		addnav("Choose Different Furniture","runmodule.php?module=jobs&place=10&op=custom");
		blocknav("runmodule.php?module=jobs&place=$j&op=quit");
		blocknav("village.php");
		blocknav("runmodule.php?module=jobs&place=$j&op=work");
	}else{
		if ($costwood>0 || $coststone>0){
			output("You give");
			if ($costwood>0) {
				output("%s wood",$costwood);
				$allprefsl['squares']=$allprefsl['squares']-$costwood;
				set_module_pref('allprefs',serialize($allprefsl),'lumberyard');
				if ($coststone>0) output("and");
			}
			if ($coststone>0) {
				output("%s stone",$coststone);
				increment_module_pref("blocks",-$coststone,"quarry");
				$allprefsq['blocks']=$allprefsq['blocks']-$costwood;
				set_module_pref('allprefs',serialize($allprefsq),'quarry');
			}
			output("for the project.");
			debuglog("gave up $costwood wood and $coststone stone to start making furniture at the Wood Shop.");
		}
		output("You're ready to start working on your %s.",$tarray[$t]);
		$allprefs['type']=$t;
		$allprefs['ffspent']=0;
		set_module_pref('allprefs',serialize($allprefs));
		$allprefs=unserialize(get_module_pref('allprefs'));
		if ($costwood>0 || $coststone>0) output("`n`nJust remember, if you get fired before you finish your %s, you will not be able to get back your materials from the Wood Shop.",$tarray[$t]);
	}
	addnav("Return to Wood Shop","runmodule.php?module=jobs&place=10&op=work");
}
if ($op == "rules"){
	output("`6Working at the `qWood Shop`6 is different than working at other jobs.  Your job here is to make basic furniture for the furniture store at the dwellings.");
	output("`n`nIf you would like to take your job a step further, you can make `icustom furniture`i.  There are two levels of custom furniture:  `^Standard`6 and `%Heirloom`6 Quality.");
	if (is_module_active("quarry") || is_module_active("lumberyard")){
		output("In order to make the custom furniture you may need to provide materials such as");
		if (is_module_active("quarry")) output("`)stone`6 from the quarry");
		if (is_module_active("quarry") && is_module_active("lumberyard")) output("and");
		if (is_module_active("quarry")) output("`&wood`6 from the lumberyard");
		output("from your personal stock.");
	}
	output("`n`nOf course, you may name your custom furniture whatever you would like to name it. Other villagers will be able to purchase your furniture and place it in their dwellings.");
	output("Through this method, you can spread your name across the kingdom as a master artisan.");
	if ($allprefs['jobworked']==0) addnav("Return to Wood Shop","runmodule.php?module=jobs&place=10&op=work");
}
if ($op == "finish"){
	$allprefs=unserialize(get_module_pref('allprefs'));
	$subop = httpget('subop');
	$submit1= httppost('submit1');
	$type=$allprefs['type'];
	$furn=array("","chair","chair","table","table","bed","bed");
	$dks=$session['user']['dragonkills'];
	$storename=get_module_setting("storename","furniture");
	$approve=get_module_setting("woodapprove");
	$tarray=translate_inline(array("Basic Furniture","Customized Standard Chair","Customized Heirloom-Quality Chair","Customized Standard Table","Customized Heirloom-Quality Table","Customized Standard Bed","Customized Heirloom-Quality Bed"));
	if ($submit1==""){
		output("`6You put the finishing touches on your `^%s`6. Now, you have the option of giving it a name.`n`nIf you would prefer to offer no name for your furniture, it will be known simply as a `^%s`6.`n`n",$tarray[$type],$tarray[$type]);
		if ($approve==1) output("Custom named furniture will have to be approved by the Quality Assurance Team before it will be available for sale in the %s`6.`n`n",$storename);
		if ((($type==1 || $type==2) && $dks<get_module_setting("dkenter","furniture")) || (($type==3 || $type==4) && $dks<get_module_setting("dktable","furniture")) ||(($type==5 || $type==6) && $dks<get_module_setting("dkbed","furniture"))) output("`\$*Note: You may not be able to purchase a %s at this time because you have not shown enough expertise against the `@Green Dragon`\$ yet.`n`n",$furn[$type]);
		addnav("Default Name","runmodule.php?module=jobs&place=$j&op=default");
		blocknav("runmodule.php?module=jobs&place=industrialpark");
		blocknav("runmodule.php?module=jobs&place=$j&op=quit");
		blocknav("village.php");
		$submit1 = translate_inline("Submit");
		rawoutput("<form action='runmodule.php?module=jobs&place=$j&op=finish&subop=submit1' method='POST'><input name='submit1' id='submit1'><input type='submit' class='button' value='$submit1'></form>");
		addnav("","runmodule.php?module=jobs&place=$j&op=finish&subop=submit1");	
	}else{
		$submit1=stripslashes($submit1);
		$allprefs['name'.$type]=$submit1;
		set_module_pref('allprefs',serialize($allprefs));
		$allprefs=unserialize(get_module_pref('allprefs'));
		output("`6Your %s`6 will be available at the %s Store`6%s.`n`n",$submit1,$storename,translate_inline($approve==1?" pending approval":""));
		if ($approve==1){
			$sql = "SELECT acctid,value FROM ".db_prefix('module_userprefs')." LEFT JOIN ".db_prefix('accounts')." ON (acctid = userid) WHERE modulename='jobs' and setting='email' and value ='1'";
			$result = db_query($sql);
			for ($i=0;$i<db_num_rows($result);$i++){
				$row = db_fetch_assoc($result);
				$mailmessage=translate_inline(array("%s `6 has completed a piece of furniture and has named it %s`6. Please review the furniture for approval.",$session['user']['name'],$submit1));
				require_once("lib/systemmail.php");
				if ($row['value'] == 1 && get_module_pref("super","jobs",$row['acctid'])==1) systemmail($row['acctid'],translate_inline("`2Furniture Approval"),$mailmessage);
			}
			$allprefs['cust'.$type]=2;
			addnews("%s`6 completed a %s`6 which will be available in the %s`6 pending quality assurance review.",$session['user']['name'],$tarray[$type],$storename);
		}else{
			$allprefs['cust'.$type]=1;
			addnews("`6A new piece of furniture named %s`6 was completed by %s`6.  It is now available in the %s`6.",$submit1,$session['user']['name'],$storename);
			debuglog("completed a piece of furniture named $submit1 at the Wood Shop.");
			if ($approve==2){
				$sql = "SELECT acctid,value FROM ".db_prefix('module_userprefs')." LEFT JOIN ".db_prefix('accounts')." ON (acctid = userid) WHERE modulename='jobs' and setting='email' and value ='1'";
				$result = db_query($sql);
				for ($i=0;$i<db_num_rows($result);$i++){
					$row = db_fetch_assoc($result);
					$mailmessage=translate_inline(array("%s `6 has completed a piece of furniture and has named it:`0`n`n`c%s`c`n`6 If this is inappropriate please edit the user's allpref regarding this piece of furniture.",$session['user']['name'],$submit1));
					require_once("lib/systemmail.php");
					if ($row['value'] == 1 && get_module_pref("super","jobs",$row['acctid'])==1) systemmail($row['acctid'],translate_inline("`2Furniture Completion Notice"),$mailmessage);
				}
			}
		}
		$gold=get_module_setting("cgold".$type);
		$gems=get_module_setting("cgems".$type);
		if ($gold>0||$gems>0){
			output("`6You receive your bonus of");
			if ($gold>0) output("`^%s gold`6",$gold);
			if ($gold>0 && $gems>0) output("and");
			if ($gems>0) output("`%%s %s`6",$gems,translate_inline($gems>1?"gems":"gem"));
			$session['user']['gems']+=$gems;
			$session['user']['gold']+=$gold;
			output("for completing your furniture.`n`n");
		}
		output("Congratulations!");
		$allprefs['ffspent']=0;
		$allprefs['type']=0;
		set_module_pref('allprefs',serialize($allprefs));
	}
}
if ($op == "default"){
	$storename=get_module_setting("storename","furniture");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$type=$allprefs['type'];
	$tarray=translate_inline(array("Basic Furniture","Customized Standard Chair","Customized Heirloom-Quality Chair","Customized Standard Table","Customized Heirloom-Quality Table","Customized Standard Bed","Customized Heirloom-Quality Bed"));
	$allprefs['name'.$type]=$tarray[$type];
	addnews("%s`6 completed a %s`6 which is now available in the %s`6.",$session['user']['name'],$tarray[$type],$storename);
	$gold=get_module_setting("cgold".$type);
	$gems=get_module_setting("cgems".$type);
	$allprefs['cust'.$type]=1;
	if ($gold>0||$gems>0){
		output("`6You receive your bonus of");
		if ($gold>0) output("`^%s gold`6",$gold);
		if ($gold>0 && $gems>0) output("and");
		if ($gems>0) output("`%%s %s`6",$gems,translate_inline($gems>1?"gems":"gem"));
		$session['user']['gems']+=$gems;
		$session['user']['gold']+=$gold;
		output("for completing your furniture.`n`n");
	}
	$allprefs['ffspent']=0;
	$allprefs['type']=0;
	set_module_pref('allprefs',serialize($allprefs));
}
if ($op == "work"){
	require_once("lib/showform.php");
	$shifts = httppost('shifts');
	$type=$allprefs['type'];
	$tarray=translate_inline(array("Basic Furniture","Customized Standard Chair","Customized Heirloom-Quality Chair","Customized Standard Table","Customized Heirloom-Quality Table","Customized Standard Bed","Customized Heirloom-Quality Bed"));
	if ($allprefs['vacation']>0){
		output("The foreman looks at the vacation log and notices that you're not supposed to be here.");
		output("`n`n`6'You need to go to Job Services and let them know that you're back from vacation if you want to work today.'");
	}elseif (is_module_active("drinks") && get_module_pref('drunkeness','drinks')>65 && floor($allprefs['job']/2)==$j){
		$session['user']['experience']-=3;
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$allprefsj=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
			if ($allprefsj['job']==$j*2){
				$name = $row['name'];
				$id = $row['acctid'];
			}
		}
		output("`6The Foreman stops you...... \"How dare you show up to work in this condition!\"`n`n");
		if ($name <> "" &&  get_module_pref("user_stat","jobs",$id)==1 && $allprefs['job']==$j*2-1) {
			require_once("lib/systemmail.php");
			output("\"Go sober up! I'm sending a letter to Manager %s`6 about this.\"",$name);
			$subj = translate_inline(array("Drunken Employee seen at the %s",$jobname));
			$body = translate_inline(array("`&Dear %s`&,`n`nAs a manager at the %s, I thought you should know that %s came to work drunk.  Please let them know that this is not appropriate.`n`nThank you,`nThe Foreman",$name,$session['user']['name'],$jobname));
			systemmail($id,$subj,$body);
		}elseif ($allprefs['job']==$j*2){
			output("\"You're the manager! You're supposed to be the model employee! You're fired!\"");
			$maxnum=get_module_setting("maxnum");
			$allprefs['jobexp']=$exp[$maxnum];
			$allprefs['ffspent']=0;
			$allprefs['type']=0;
			$allprefs['job']=0;
			set_module_pref('allprefs',serialize($allprefs));
			$allprefs=unserialize(get_module_pref('allprefs'));
			addnews("%s`7 was fired from their job as %s manager today because of showing up to work drunk.",$session['user']['name'],$jobname);
		}else output("\"Go sober up!\"");
	}else{
		if ($shifts < 1){
			if ($j==10){
				if ($type==0){
					output("`6Working at the `&Wood Shop`6 is different than working at other jobs.  Your job here is to make basic furniture for the furniture store at the dwellings.");
					output("`n`nIf you would like to take your job a step further, you can make `icustom furniture`i.  There are two levels of custom furniture:  `^Standard`6 and `%Heirloom`6 Quality.");
					if (is_module_active("quarry") || is_module_active("lumberyard")){
						output("In order to make the custom furniture you may need to provide materials such as");
						if (is_module_active("quarry")) output("`)stone`6 from the quarry");
						if (is_module_active("quarry") && is_module_active("lumberyard")) output("and");
						if (is_module_active("quarry")) output("`&wood`6 from the lumberyard");
						output("from your personal stock.");
					}
					output("`n`nOf course, you may name your custom furniture whatever you would like to name it. Other villagers will be able to purchase your furniture and place it in their dwellings.");
					output("Through this method, you can spread your name across the kingdom as a master artisan.");
				}
				if ($type==0) $type="0";
				output("`6Currently, you are working on making %s `^%s`6.",translate_inline($type>0?"a":""),$tarray[$type]);
				$left=get_module_setting("cust".$type)-$allprefs['ffspent'];
				if ($type>0){
					if ($left<=0) {
						output("`6You recently finished your `^%s`6.  You will need to add the finishing touches to it.",$tarray[$type]);
						addnav("Finish Furniture","runmodule.php?module=jobs&place=10&op=finish");
					}else output("You will need to work %s %s to finish your furniture.",$left,translate_inline($left>1?"turns":"turn"));
				}else{
					output("If you'd like to work on a custom piece of furniture, please make your choice as to what you would like to make.");
					addnav("Choose Custom Furniture","runmodule.php?module=jobs&place=10&op=custom");
				}
				rawoutput("<form action='runmodule.php?module=jobs&place=10&op=work' method='post'>");
				$stuff = translate_inline(array("shifts"=>"How many shifts (turns) would you like to work?,range,1,5,1|1",));
				if ($type>0 && $left==4) $stuff = translate_inline(array("shifts"=>"How many shifts (turns) would you like to work?,range,1,4,1|1",));
				elseif ($type>0 && $left==3) $stuff = translate_inline(array("shifts"=>"How many shifts (turns) would you like to work?,range,1,3,1|1",));
				elseif ($type>0 && $left==2) $stuff = translate_inline(array("shifts"=>"How many shifts (turns) would you like to work?,range,1,2,1|1",));
				elseif ($type>0 && $left==1) $stuff = translate_inline(array("shifts"=>"How many shifts (turns) would you like to work?,range,1,1,1|1",));
				$b = array(
					"shifts"=>1,
				);
				showform($stuff,$b,true);
				$b = translate_inline("Work");
				rawoutput("<input type='submit' class='button' value='$b'></form>");
				addnav("","runmodule.php?module=jobs&place=10&op=work"); 
			}else{
				rawoutput("<form action='runmodule.php?module=jobs&place=$j&op=work' method='post'>");
				$stuff = translate_inline(array(
					"shifts"=>"How many shifts (turns) would you like to work?,range,1,5,1|1",
				));
				$b = array(
					"shifts"=>1,
				);
				showform($stuff,$b,true);
				$b = translate_inline("Work");
				rawoutput("<input type='submit' class='button' value='$b'></form>");
				addnav("","runmodule.php?module=jobs&place=$j&op=work");
			}
		}elseif ($shifts > $session['user']['turns']){
			output("You can only work %s %s!",$session['user']['turns'],translate_inline($session['user']['turns']>1?"shifts":"shift"));
			addnav("Continue","runmodule.php?module=jobs&place=$j&op=work");  
		}else{
			output("You put your time in and work for %s %s.`n",$shifts,translate_inline($shifts>1?"shifts":"shift"));
			for ($i=1;$i<$shifts+1;$i++){
				output("`nShift %s: ",$i);
				$a=1;
				if (is_module_active("drinks")){
					if (get_module_pref('drunkeness','drinks')>32 && e_rand(1,2)==2) $a=9;
				}
				//Managers
				if ($allprefs['job']==$j*2) $comment=translate_inline(array("","You watch the workers.","You tell the workers to get back to work.","You pretend to do some paperwork.","You try to look important.","You look for people slacking off.","You try to help but get in the way.","You clean up a little.","You tell workers to work harder.","You slack off for a while."));
				//1 Farm
				elseif ($j==1) $comment=translate_inline(array("","You clean pig pens.","You work in the Wheat field.","You work feed pigs.","You work in the Hops field.","You milk cows.","You clean cow stalls.","You clean the manure from the barn.","You make hay.","You slack off for a while."));
				//2 Mill
				elseif ($j==2) $comment=translate_inline(array("","You make flour.","You clean the machinery.","You sweep the floor.","You fix machinery.","You bag flour.","You pick bugs out of the wheat.","You carry wheat into the mill.","You load flour onto wagons.","You slack off for a while."));
				//3 Brewery
				elseif ($j==3) $comment=translate_inline(array("","You make ale.","You clean the machinery.","You sweep the floor.","You fix machinery.","You unload hops.","You bottle ale.","You work the line.","You load bottles onto wagons.","You slack off for a while."));
				//4 Textile Mill
				elseif ($j==4) $comment=translate_inline(array("","You make cloth.","You clean the machinery.","You sweep the floor.","You fix machinery.","You make leather.","You bundle cloth.","You bundle leather.","You load cloth and leather onto wagons.","You slack off for a while."));
				//5 Foundry
				elseif ($j==5) $comment=translate_inline(array("","You make weapons.","You clean the machinery.","You sweep the floor.","You fix machinery.","You unload ore.","You polish weapons.","You make armor.","You load weapons and armor onto wagons.","You slack off for a while."));
				//6 Guard Post
				elseif ($j==6) $comment=translate_inline(array("","You watch the furniture.","You watch the entrance.","You say 'Who goes there?' to an old lady.","You watch the door.","You lower the drawbridge.","You feed the moat monster","You hold a spear menacingly.","You taunt invaders.","You slack off for a while."));
				//7 Forest Cleaning
				elseif ($j==7) $comment=translate_inline(array("","You collect dead bunnies.","You sweep up leaves.","You collect old armor.","You discard bones.","You bury a dead orc.","You discard old weapons.","You carry away sticks.","You move some rocks.","You slack off for a while."));
				//8 Coal Factory
				elseif ($j==8) $comment=translate_inline(array("","You shovel some coal.","You wash some clothes.","You sweep the floor.","You unload a cart.","You stoke the fire.","You cart some coal.","You fix a shovel.","You tend to the fire.","You slack off for a while."));
				//9 Manger's Training Center
				elseif ($j==9) $comment=translate_inline(array("","You push a pencil.","You write some papers.","You scribble orders.","You study motivational phrases.","You organize some papers.","You practice telling people what to do.","You practice looking important.","You study management texts.","You slack off for a while."));
				//10 Wood Shop
				elseif ($j==10) $comment=translate_inline(array("","You put wood in the lathe machine.","You sand the wood.","You sweep the floor.","You cut wood on the saw.","You clean up sawdust.","You assemble the furniture.","You apply stain to the wood.","You drill holes in the wood.","You slack off for a while."));
				else $comment=translate_inline(array("","You work hard.","You work hard.","You work hard.","You work hard.","You work hard.","You work hard.","You work hard.","You work hard.","You slack off for a while."));
				$rand=e_rand(1,9);
				output_notl("%s",$comment[$rand]);
				if ($rand==9){
					$allprefs['jobexp']-=e_rand(1,4);
					if ($allprefs['jobexp']<0) $allprefs['jobexp']=0;
					set_module_pref('allprefs',serialize($allprefs));
					$allprefs=unserialize(get_module_pref('allprefs'));
				}
			}
			if ($shifts == 1) output("`n`n`)You hardly worked at all.");
			elseif ($shifts == 2) output("`n`n`0You worked for a little while, you will never be the top employee that way.");
			elseif ($shifts == 3) output("`n`n`&You worked a decent amount of time, keep up the good work.");
			elseif ($shifts == 4) output("`n`n`^You worked for quite a while!  You are a hard worker, keep it up!");
			else output("`n`n`@You are a workhorse!  Keep it up and you will be moving on up!");
			if ($oddeven==1){
				//Worker pay for level 1 worker for 5 turns at defaults (50*5)=250
				//Worker pay for level 10 worker for 5 turns at defaults (50+10*9)*5=700
				$pay=(get_module_setting("pay")+(get_module_setting("incr")*($j-1)))*$shifts;
			}else{
				//Manager pay for level 1 manager for 5 turns at defaults ((50+(10*10))+(5))*5=775
				//Manager pay for level 10 manager for 5 turns at defaults ((50+(10*10))+(5*10))*5=1000
				$pay=((get_module_setting("pay")+get_module_setting("incr")*10)+(round(get_module_setting("incr")/2*$j)))*$shifts;
			}
			$session['user']['gold']+=$pay;
			output("`n`n`2You are paid `^%s gold`2 for working today.`n",$pay);
			$session['user']['turns']-=$shifts;
			$rand=$j*e_rand(15,30);
			$experience=($rand*$shifts)+round($session['user']['level']/5);
			if ($oddeven==0) $experience+=(e_rand(130,180)*$shifts)+round($session['user']['level']/5);
			$allprefs['jobexp']=$allprefs['jobexp']+$experience+$shifts;
			$allprefs['jobworked']=1;
			debuglog("Worked for $shifts turns at the $jobname and received $pay gold and gained $experience Job Experience");
			if (is_module_active('odor')) increment_module_pref('odor',3,'odor');
			if (is_module_active('usechow')) increment_module_pref('hunger',$shifts,'usechow');
			$allprefs['lastworked']=date("Y-m-d H:i:s");
			set_module_pref('allprefs',serialize($allprefs));
			$allprefs=unserialize(get_module_pref('allprefs'));
			if ($type>0 && $j==10) {
				$allprefs['ffspent']=$allprefs['ffspent']+$shifts;
				$left=get_module_setting("cust".$type)-$allprefs['ffspent'];
				set_module_pref('allprefs',serialize($allprefs));
				$allprefs=unserialize(get_module_pref('allprefs'));
				if ($left<=0) {
					output("`n`6You recently finished your `^%s`6.  You will need to add the finishing touches to it.",$tarray[$type]);
					addnav("Finish Furniture","runmodule.php?module=jobs&place=10&op=finish");
					blocknav("runmodule.php?module=jobs&place=industrialpark");
					blocknav("runmodule.php?module=jobs&place=10&op=quit");
					blocknav("village.php");
				}
			}
			increment_module_setting("shifts".$j,$shifts);
			if ($shifts>4){
				$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
				$res = db_query($sql);
				$new_array = array();
				while($row = db_fetch_assoc($res)){
					$array = unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
					if ($array['job']==$j) $new_array[$row['name']] = $array['jobexp'];
				}
				$n=0;
				arsort($new_array);
				foreach($new_array AS $name => $value){
					$n=$n+1;
					if ($n==1 && $session['user']['name']==$name){
						output("`n`6You are the Top Employee! For working as hard as you did today you have `4Worker's Fury`6!");
						apply_buff('workfury',array(
							"name"=>translate_inline("`4Worker's Fury"),
							"rounds"=>8 + $j*2,
							"wearoff"=>translate_inline("`4Your worker's fury fades."),
							"atkmod"=>1.25,
							"roundmsg"=>translate_inline("`4You've got a worker's fury going, your attack is amazing!."),
							"activate"=>"offense"
						));
						debuglog("received the Worker's Fury buff for being the best worke at the $jobname");
					}
				}
			}
		}
	}
	if ($type>0 && $j==10 && $allprefs['jobworked']==0) addnav("Wood Shop Rules","runmodule.php?module=jobs&place=10&op=rules");
}
$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
$res = db_query($sql);
for ($i=0;$i<db_num_rows($res);$i++){
	$row = db_fetch_assoc($res);
	$allprefsm=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
	if ($allprefsm['job']==$j*2){
		output("`n`c`n`b`#Manager:`^`b %s`c",$row['name']);
	}
}
$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
$res = db_query($sql);
$n=0;
for ($i=0;$i<db_num_rows($res);$i++){
	$row = db_fetch_assoc($res);
	$allprefsj=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
	if ($allprefsj['job']==$j*2-1){
		$n=$n+1;
		if ($n==1) output("`c`n`b`2Current Employees`b`n`c`2");
		if ($n>0) output("`c- `^%s `2-`n`c",$row['name']);
	}
}
if (get_module_setting("industrialpark")==1) addnav("Return to Industrial Park","runmodule.php?module=jobs&place=industrialpark");
villagenav();
page_footer();
?>