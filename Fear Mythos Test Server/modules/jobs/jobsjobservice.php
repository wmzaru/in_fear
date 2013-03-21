<?php
	page_header("Job Services");
	$op3 = httpget('op3');
	if ($op!="hof") output("`c`b`&Job Services`0`b`c`n");
	global $session;
if ($op == ""){
	output("You step into the dimly lit Job Services Office.`n`n");
	output("At the desk you see a haggard wrinkled old woman.  She looks up at you then goes back");
	output("to what she was doing without so much as even acknowledging that you are there.  On the");
	output("wall you see a poster labeled 'Top Employees'.  You can check your job");
	output("experience, apply for jobs, and check status on Job Applications. Remember, you can only apply for a job if you haven't worked already today.`n`n`n");
	output("`&`c`bTop Employees:`c`b`n");
	$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	$new_array1 = array();
	$new_array2 = array();
	$new_array3 = array();
	$new_array4 = array();
	$new_array5 = array();
	$new_array6 = array();
	$new_array7 = array();
	$new_array8 = array();
	$new_array9 = array();
	$new_array10 = array();
	while($row = db_fetch_assoc($res)){
		$array = unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
		if ($array['job']==1) $new_array1[$row['name']] = $array['jobexp'];
		elseif ($array['job']==3) $new_array2[$row['name']] = $array['jobexp'];
		elseif ($array['job']==5) $new_array3[$row['name']] = $array['jobexp'];
		elseif ($array['job']==7) $new_array4[$row['name']] = $array['jobexp'];
		elseif ($array['job']==9) $new_array5[$row['name']] = $array['jobexp'];
		elseif ($array['job']==11) $new_array6[$row['name']] = $array['jobexp'];
		elseif ($array['job']==13) $new_array7[$row['name']] = $array['jobexp'];
		elseif ($array['job']==15) $new_array8[$row['name']] = $array['jobexp'];
		elseif ($array['job']==17) $new_array9[$row['name']] = $array['jobexp'];
		elseif ($array['job']==19) $new_array10[$row['name']] = $array['jobexp'];
		//output("`nJob= %s, Name =%s, Jobexp=%s",$array['job'],$row['name'],$array['jobexp']);
	}
	$a=0;
	arsort($new_array1);
	foreach($new_array1 AS $name => $value){
		$a=$a+1;
		if ($a==1) output_notl("`Q`c`b%s: `^%s`0`c`b`n",get_module_setting("type1"),$name);
	}
	$b=0;
	arsort($new_array2);
	foreach($new_array2 AS $name => $value){
		$b=$b+1;
		if ($b==1) output_notl("`^`c`b%s: `^%s`0`c`b`n",get_module_setting("type2"),$name);
	}
	$c=0;
	arsort($new_array3);
	foreach($new_array3 AS $name => $value){
		$c=$c+1;
		if ($c==1) output_notl("`\$`c`b%s: `^%s`0`c`b`n",get_module_setting("type3"),$name);
	}
	$d=0;
	arsort($new_array4);
	foreach($new_array4 AS $name => $value){
		$d=$d+1;
		if ($d==1) output_notl("`6`c`b%s: `^%s`0`c`b`n",get_module_setting("type4"),$name);
	}
	$e=0;
	arsort($new_array5);
	foreach($new_array5 AS $name => $value){
		$e=$e+1;
		if ($e==1) output_notl("`c`b`)%s: `^%s`0`c`b`n",get_module_setting("type5"),$name);
	}
	$f=0;
	arsort($new_array6);
	foreach($new_array6 AS $name => $value){
		$f=$f+1;
		if ($f==1) output_notl("`c`b`q%s: `^%s`0`c`b`n",get_module_setting("type6"),$name);
	}
	$g=0;
	arsort($new_array7);
	foreach($new_array7 AS $name => $value){
		$g=$g+1;
		if ($g==1) output_notl("`c`b`q%s: `^%s`0`c`b`n",get_module_setting("type7"),$name);
	}
	$h=0;
	arsort($new_array8);
	foreach($new_array8 AS $name => $value){
		$h=$h+1;
		if ($h==1) output_notl("`c`b`q%s: `^%s`0`c`b`n",get_module_setting("type8"),$name);
	}
	$j=0;
	arsort($new_array9);
	foreach($new_array9 AS $name => $value){
		$j=$j+1;
		if ($j==1) output_notl("`c`b`q%s: `^%s`0`c`b`n",get_module_setting("type9"),$name);
	}
	$k=0;
	arsort($new_array10);
	foreach($new_array10 AS $name => $value){
		$k=$k+1;
		if ($k==1) output_notl("`c`b`q%s: `^%s`0`c`b`n",get_module_setting("type10"),$name);
	}

	if ($a==0 && $b==0 && $c==0 && $d==0 && $e==0 && $f==0 && $g==0 && $h==0 && $j==0 && $k==0) output ("`c`^No Work Completed Yet by Non-Management`c");
	$allprefs=unserialize(get_module_pref('allprefs'));
	addnav("Check your Experience","runmodule.php?module=jobs&place=jobservice&op=exp");
	if (get_module_setting("vacation")==1 && $allprefs['job']>0){
		if ($allprefs['vacation']>0) addnav("Change Vacation Request","runmodule.php?module=jobs&place=jobservice&op=vacation");
		else addnav("Request Vacation","runmodule.php?module=jobs&place=jobservice&op=vacation");
	}
	if ($allprefs['vacation']>0) addnav("Return from Vacation Early","runmodule.php?module=jobs&place=jobservice&op=return");
	if ($allprefs['jobapp'] == 0 && $allprefs['jobworked']==0 && $allprefs['job']>=0) addnav("Apply for a Job","runmodule.php?module=jobs&place=jobservice&op=apply");
	elseif ($allprefs['jobapp']>0) addnav("Application Status","runmodule.php?module=jobs&place=jobservice&op=status");
	addnav("Help","runmodule.php?module=jobs&place=jobservice&op=help");
}
if ($op == "return"){
	output("Welcome back to work. You may want to go put some shifts in at your job.");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['vacation']=0;
	$allprefs['dayssince']=0;
	$allprefs['lastworked']=date("Y-m-d H:i:s");
	set_module_pref('allprefs',serialize($allprefs));
}
if ($op == "vacation"){
	require_once("lib/showform.php");
	$shifts = httppost('shifts');
	$allprefs=unserialize(get_module_pref('allprefs'));
	if(round($allprefs['job']/2-floor($allprefs['job']/2))==0){
		output("Unfortunately, management cannot take vacation.");
	}else{
		if ($shifts==0){
			if ($allprefs['vacation']>0) output("You will need to re-apply if you want to change the amount of vacation you are taking.  Currently, you are on vacation for `^%s`0 real life days.`n`n",$allprefs['vacation']);
			output("Please apply for the number of REAL LIFE days you will be away.");
			rawoutput("<form action='runmodule.php?module=jobs&place=jobservice&op=vacation' method='post'>");
			$stuff = array(
				"shifts"=>"How many REAL LIFE days do you need to take vacation?,range,1,30,1|1",
			);
			$b = array(
				"shifts"=>1,
			);
			showform($stuff,$b,true);
			$b = translate_inline("Vacation");
			rawoutput("<input type='submit' class='button' value='$b'></form>");
			addnav("","runmodule.php?module=jobs&place=jobservice&op=vacation");
		}else{
			$allprefs['vacation']=$shifts*getsetting("daysperday",4);
			set_module_pref('allprefs',serialize($allprefs));
			output("You are now approved for `^%s`0 Real-Life %s of vacation.  If you need a further extension, please re-apply for more time off.",$shifts,translate_inline($shifts>1?"Days":"Day"));
		}
	}
	addnav("Return to Job Services","runmodule.php?module=jobs&place=jobservice");
}
if ($op == "exp"){
	$allprefs=unserialize(get_module_pref('allprefs'));
	output("`7You walk up to the old woman to ask her to evaluate your job experience.  She grumbles ");
	output("something under her breath that you can't quite make out.  She looks up at you and says ");
	if ($allprefs['jobexp']==0) output("\"`7Gee, let's see... you have exactly ZERO experience.\"");
	else output("\"%s`7, you have `^%s`7 job experience.\"`n",$session['user']['name'],$allprefs['jobexp']);
	addnav("Continue","runmodule.php?module=jobs&place=jobservice");
}
if ($op == "apply"){
	$allprefs=unserialize(get_module_pref('allprefs'));
	$job=$allprefs['job'];
	$jobexp=$allprefs['jobexp'];
	output("You walk up to the old woman and ask to apply for a job.  She gives you a quick glance");
	output("and looks back down at what she is working on and mumbles"); 
	if ($job==1 && $jobexp<250) output("\"You already have a job at the %s but you don't have enough experience to get a different job. Come back after you've worked a little.\"`n",get_module_setting("type1"));
	else{
		$maxnum=get_module_setting("maxnum");
		output("\"You are experienced enough to work at the following places.  What application would you like to fill out?\"`n");
		addnav("Workers Needed");
		if ($job <> 1) addnav(array("%s", get_module_setting("type1")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=1");
		if ($jobexp > 249 && $job <> 3 && $maxnum>=2) addnav(array("%s", get_module_setting("type2")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=3");
		if ($jobexp > 750 && $job <> 5 && $maxnum>=3) addnav(array("%s", get_module_setting("type3")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=5");
		if ($jobexp > 1500 && $job <> 7 && $maxnum>=4) addnav(array("%s", get_module_setting("type4")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=7");
		if ($jobexp > 2500 && $job <> 9 && $maxnum>=5) addnav(array("%s", get_module_setting("type5")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=9");
		if ($jobexp > 4500 && $job <> 11 && $maxnum>=6) addnav(array("%s", get_module_setting("type6")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=11");
		if ($jobexp > 7500 && $job <> 13 && $maxnum>=7) addnav(array("%s", get_module_setting("type7")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=13");
		if ($jobexp > 10000 && $job <> 15 && $maxnum>=8) addnav(array("%s", get_module_setting("type8")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=15");
		if ($jobexp > 15000 && $job <> 17 && $maxnum>=9) addnav(array("%s", get_module_setting("type9")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=17");
		if ($jobexp > 20000 && $job <> 19 && $maxnum>=10 && is_module_active("furniture")) addnav(array("%s", get_module_setting("type10")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=19");
		$jobm2=0;
		$jobm4=0;
		$jobm6=0;
		$jobm8=0;
		$jobm10=0;
		$jobm12=0;
		$jobm14=0;
		$jobm16=0;
		$jobm18=0;
		$jobm20=0;
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$allprefsm=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
			if ($allprefsm['job']==2) $jobm2=1;
			if ($allprefsm['job']==4) $jobm4=1;
			if ($allprefsm['job']==6) $jobm6=1;
			if ($allprefsm['job']==8) $jobm8=1;
			if ($allprefsm['job']==10) $jobm10=1;
			if ($allprefsm['job']==12) $jobm12=1;
			if ($allprefsm['job']==14) $jobm14=1;
			if ($allprefsm['job']==16) $jobm16=1;
			if ($allprefsm['job']==18) $jobm18=1;
			if ($allprefsm['job']==20) $jobm20=1;
		}
		addnav("Management Needed");
		if ($maxnum==1) $array=array(0,1000);
		elseif ($maxnum==2) $array=array(0,1000,2000,0,0,0,0,0,0,0,0);
		elseif ($maxnum==3) $array=array(0,1500,2500,45000,0,0,0,0,0,0);
		elseif ($maxnum==4) $array=array(0,2500,4500,7500,10000,0,0,0,0,0,0);
		elseif ($maxnum==5) $array=array(0,4500,7500,10000,15000,20000,0,0,0,0,0);
		elseif ($maxnum==6) $array=array(0,7500,10000,15000,20000,25000,30000,0,0,0,0);
		elseif ($maxnum==7) $array=array(0,10000,15000,20000,25000,30000,35000,40000,0,0,0);
		elseif ($maxnum==8) $array=array(0,15000,20000,25000,30000,35000,40000,45000,50000,0,0);
		elseif ($maxnum==9) $array=array(0,20000,25000,30000,35000,40000,45000,50000,55000,60000,0);
		elseif ($maxnum==10) $array=array(0,25000,30000,35000,40000,45000,50000,55000,60000,65000,70000);
		if ($jobm2==0 && $jobexp > $array[1] && $job <> 2) addnav(array("%s Management", get_module_setting("type1")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=2");
		if ($jobm4==0 && $jobexp > $array[2] && $job <> 4 && $maxnum>=2) addnav(array("%s Management", get_module_setting("type2")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=4");
		if ($jobm6==0 && $jobexp > $array[3] && $job <> 6 && $maxnum>=3) addnav(array("%s Management", get_module_setting("type3")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=6");
		if ($jobm8==0 && $jobexp > $array[4] && $job <> 8 && $maxnum>=4) addnav(array("%s Management", get_module_setting("type4")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=8");
		if ($jobm10==0 && $jobexp > $array[5] && $job <> 10 && $maxnum>=5) addnav(array("%s Management", get_module_setting("type5")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=10");
		if ($jobm12==0 && $jobexp > $array[6] && $job <> 12 && $maxnum>=6) addnav(array("%s Management", get_module_setting("type6")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=12");
		if ($jobm14==0 && $jobexp > $array[7] && $job <> 14 && $maxnum>=7) addnav(array("%s Management", get_module_setting("type7")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=14");
		if ($jobm16==0 && $jobexp > $array[8] && $job <> 16 && $maxnum>=8) addnav(array("%s Management", get_module_setting("type8")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=16");
		if ($jobm18==0 && $jobexp > $array[9] && $job <> 18 && $maxnum>=9) addnav(array("%s Management", get_module_setting("type9")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=18");
		if ($jobm20==0 && $jobexp > $array[10] && $job <> 20 && $maxnum>=10 && is_module_active("furniture")) addnav(array("%s Management", get_module_setting("type10")),"runmodule.php?module=jobs&place=jobservice&op=app&op3=20");
		//output("`n`nArray(maxnum)=%s`n`n",$array[$maxnum]);
	}
	addnav("Other");
	addnav("Continue","runmodule.php?module=jobs&place=jobservice");
}
if ($op == "app"){
	if ($op3==1 || $op3==2) $jobname=get_module_setting("type1");
	if ($op3==3 || $op3==4) $jobname=get_module_setting("type2");
	if ($op3==5 || $op3==6) $jobname=get_module_setting("type3");
	if ($op3==7 || $op3==8) $jobname=get_module_setting("type4");
	if ($op3==9 || $op3==10) $jobname=get_module_setting("type5");
	if ($op3==11 || $op3==12) $jobname=get_module_setting("type6");
	if ($op3==13 || $op3==14) $jobname=get_module_setting("type7");
	if ($op3==15 || $op3==16) $jobname=get_module_setting("type8");
	if ($op3==17 || $op3==18) $jobname=get_module_setting("type9");
	if ($op3==19 || $op3==20) $jobname=get_module_setting("type10");
	if ($op2 == ""){
		output("<big><big><big><span style=\"font-weight: bold;\">Job Application<br></span><small><small><small>",true);
		output("`n`7Name: %s`7`n",$session['user']['name']);
		output("`7Postion Applied for: %s`n",$position);
		output("<form action='runmodule.php?module=jobs&place=jobservice&op=app&op2=applied&op3=$op3' method='POST'>",true);
		job_submit();
		addnav("","runmodule.php?module=jobs&place=jobservice&op=app&op2=applied&op3=$op3");
	}
	if ($op2 == "applied"){
		$allprefs=unserialize(get_module_pref('allprefs'));
		$allprefs['jobapp']=$op3;
		if (get_module_setting("requireapp")==1){
			$mailmessage=$session['user']['name'];
			$mailmessage.=translate_inline(" has applied for a job at the ");
			$mailmessage.=$jobname;
			if ($C1 == 1) $mailmessage.=translate_inline("`nThey are currently employed. ");
			else $mailmessage.=translate_inline("`nThey are NOT currently employed. ");
			if ($C2 == 1) $mailmessage.=translate_inline("`nThey say that they `4ARE`0 currently wanted for crimes against society. ");
			else $mailmessage.=translate_inline("`nThey say that they are currently NOT wanted for any crimes. ");
			$mailmessage.=translate_inline("`nThey comment:`n");
			$mailmessage.=$T1;
			$allprefs['reason']=$mailmessage;
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
			$allprefs['job']=$op3;
			$allprefs['jobapp']=0;
			$allprefs['jobworked']=0;
			$allprefs['dayssince']=0;
			$allprefs['lastworked']=date("Y-m-d H:i:s");
			set_module_pref('allprefs',serialize($allprefs));
			addnews("%s got a job at the %s.",$session['user']['name'],$jobname);
		}
		addnav("Continue","runmodule.php?module=jobs&place=jobservice");
	}
}
if ($op == "status"){
	output("You nervously approach the old woman at the desk and ask the status of your job application.");
	output("  The old woman looks up at you with an angry glare and says \"Listen if you had gotten the");
	output(" job you would know!  I suggest a little patience, as mine is wearing thin with you!\"`n");
	addnav("Continue","runmodule.php?module=jobs&place=jobservice");
}
if ($op == "help"){
	output("You pick up a piece of paper that says 'Help with Jobs'.`n`n");
	output("`c`b`&Job Help`0`b`c`n");
	output("`@1. You get paid by the shift for work that you do.`n");
	output("`22. You may work up to 5 shifts a day.`n");
	output("`@3. For each shift, you gain job experience.`n");
	output("`24. Job experience is needed to get a better job.`n");
	output("`@5. Low level jobs (farm,mill) pay poorly.`n");
	output("`26. The better the job, the better the pay, the better the benefits.`n");
	output("`@7. As working builds muscle and character you gain job experience while working.`n");
	output("`28. The top employee gets a special bonus each day that he/she puts in a solid day's work.`n");
	output("`@9. The products at the market come as a direct result of working.`n");
	output("`210. The products that you produce while working provide bonuses for those who consume them.`n");
	output("`@11. You must apply for a job first before you can work.`n");
	output("`212. You will get a letter telling you when you have gotten your job.`n");
	output("`@13. If you do not work for too many days you will be fired from your job.`n");
	output("`214. If you are fired or quit your job, you loose work experience.`n");
	output("`@15. You will not be hired if you are a wanted criminal.`n");
	output("`216. You may quit your job at any time.`n");
	output("`@17. Getting a new job will automatically terminate your previous employment.`n");
	output("`218. After getting your job, you should immediately put in at least one shift.`n");
	if (get_module_setting("vacation")==1) output("`@19. If you will be away for a prolonged period of time, please apply for vacation to avoid losing your job. Management may NOT take a vacation.`n");
	addnav("Continue","runmodule.php?module=jobs&place=jobservice");
}
if (get_module_setting("industrialpark")==1) addnav("Return to Industrial Park","runmodule.php?module=jobs&place=industrialpark");

if ($op == "hof") {
	page_header("Hall of Fame");
	$perpage =get_module_setting("pp");
	if ($perpage==0) $perpage=40;
    $jobarray = array(
		-1=>translate_inline("Quit"),
		0=>translate_inline("Unemployed"),
		1=>get_module_setting("type1"),
		2=>get_module_setting("type1").translate_inline(" Management"),
		3=>get_module_setting("type2"), 
		4=>get_module_setting("type2").translate_inline(" Management"),
		5=>get_module_setting("type3"),
		6=>get_module_setting("type3").translate_inline(" Management"),
		7=>get_module_setting("type4"), 
		8=>get_module_setting("type4").translate_inline(" Management"),
		9=>get_module_setting("type5"),
		10=>get_module_setting("type5").translate_inline(" Management"), 
		11=>get_module_setting("type6"),
		12=>get_module_setting("type6").translate_inline(" Management"),
		13=>get_module_setting("type7"),
		14=>get_module_setting("type7").translate_inline(" Management"),
		15=>get_module_setting("type8"),
		16=>get_module_setting("type8").translate_inline(" Management"),
		17=>get_module_setting("type9"),
		18=>get_module_setting("type9").translate_inline(" Management"),
		19=>get_module_setting("type10"),
		20=>get_module_setting("type10").translate_inline(" Management"),
	);
	$subop = httpget('subop');
	if ($subop=="") $subop=1;
	$min = (($subop-1)*$perpage);
	$max = $perpage*$subop;
	//This unserializes the pref to count the number of players with jobs so we can set up pages, thanks to Danbi for helping with it
	if (get_module_setting("nosuper")==1){
		//Thanks to Laroux for this		
		$superusermask = SU_HIDE_FROM_LEADERBOARD;
		$standardwhere = "(locked=0 AND (superuser & $superusermask) = 0)";
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")." WHERE $standardwhere";
	}else $sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	$number=0;
	$new_array = array();
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		$array=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
		if ($array['jobexp']>0){
			$number=$number+1;
			$new_array[$row['acctid']] = $array['jobexp'];
		}
	}
	$totalpages=ceil($number/$perpage);
	if ($totalpages>1){
		addnav("Pages");
		for($i = 0; $i < $totalpages; $i++) {
			$j=$i+1;
			$minpage = (($j-1)*$perpage)+1;
			$maxpage = $perpage*$j;
			if ($maxpage>$number) $maxpage=$number;
			if ($maxpage==$minpage) addnav(array("Page %s (%s)", $j, $minpage), "runmodule.php?module=jobs&place=jobservice&op=hof&subop=$j");
			else addnav(array("Page %s (%s-%s)", $j, $minpage, $maxpage), "runmodule.php?module=jobs&place=jobservice&op=hof&subop=$j");
		}
	}
	$rank = translate_inline("Rank");
	$name = translate_inline("Name");
	$exp = translate_inline("Experience");
	$job = translate_inline("Job"); 
	$none = translate_inline("No Workers Yet");
	output("`b`c`@Hardest Workers in the Kingdom`c`b`n");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td><td>$exp</td><td>$job</td></tr>");
	$n=0;
	if ($number==0) output_notl("<tr class='trlight'><td colspan='4' align='center'>`&$none`0</td></tr>",true);
	else{
		//Thanks to Sichae for the next lines
		arsort($new_array);
		foreach($new_array AS $id => $value){
			$n=$n+1;
			if ($n>$min && $n<=$max){
				$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid='$id'";
				$res = db_query($sql);
				$row = db_fetch_assoc($res);
				$name = $row['name'];
				$allprefs=unserialize(get_module_pref('allprefs','jobs',$id));
				if ($name==$session['user']['name']) rawoutput("<tr class='trhilight'><td>");
				else rawoutput("<tr class='".($n%2?"trdark":"trlight")."'><td>");
				output_notl("%s.",$n);
				rawoutput("</td><td>");
				output_notl("`&%s`0",$name);
				rawoutput("</td><td>");
				output_notl("`c`b`Q%s`c`b`0",$value);
				rawoutput("</td><td>"); 
				output_notl("`c`b`Q%s`c`b`0",$jobarray[$allprefs['job']]); 
				rawoutput("</td></tr>");
			}
        }
	}
	rawoutput("</table>");
	addnav("Other");
	addnav("Back to HoF", "hof.php");
	blocknav("runmodule.php?module=jobs&place=industrialpark");
}
villagenav();
page_footer();

function job_submit(){
	output("<script language='JavaScript'>document.getElementById('bet').focus();</script>",true); 
	output("<p>Are you currently Employed? <input type=\"radio\" name='C1' value='2' checked>No",true);
	output("<input type='radio' name='C1' value='1'>Yes</p>",true);
	output("<p>Are you currently wanted for any crimes? <input type=\"radio\" name='C2' value='2' checked>No",true);
	output("<input type='radio' name='C2' value='1'>Yes</p>",true);
	output("<p>Please give a short reason why you would be qualified for this job: <input type=\"text\" name=\"T1\" size=\"37\"></p>",true);
	output("<p><input type=\"submit\" value=\"Submit\" name=\"B1\"><input type=\"reset\" value=\"Reset\" name=\"B2\"></p>",true);
	output("</form>",true);
}
?>