<?php
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['jobworked']==0 && $allprefs['vacation']==0) $allprefs['dayssince']=$allprefs['dayssince']+1;
	elseif ($allprefs['jobworked']==1) $allprefs['dayssince']=0;
	set_module_pref('allprefs',serialize($allprefs));
	$allprefs=unserialize(get_module_pref('allprefs'));
	$exp=array(0,250,750,1500,2500,4500,7500,10000,15000,20000,25000);
	if (get_module_setting("fire")==1 && $allprefs['dayssince']>=get_module_setting("xdays") && $allprefs['job']>0){
		if(get_module_setting("vacation")==1) output("You have been fired for not showing up to work for too long! Next time you should apply for vacation at the Job Services Office if you will be gone for a long time!`n`n");
		else output("You have been fired for not showing up to work for too long!`n`n");
		$allprefs['dayssince']=0;
		//manager positions
		if (round($allprefs['job']/2-floor($allprefs['job']/2))==0) $allprefs['jobexp']=$exp[10];
		//first job
		elseif ($allprefs['job']==1){
			$allprefs['jobexp']-=15;
			if ($allprefs['jobexp']<0) $allprefs['jobexp']=0;
		//all others
		}else $allprefs['jobexp']=$exp[$allprefs['job']-1]+1;
		$allprefs['lastworked']=date("Y-m-d H:i:s");
		$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$allprefsj=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
			if ($allprefsj['job']==2 && $allprefs['job']==1){
				$name = $row['name'];
				$id = $row['acctid'];
			}
			if ($allprefsj['job']==4 && $allprefs['job']==3){
				$name = $row['name'];
				$id = $row['acctid'];
			}
			if ($allprefsj['job']==6 && $allprefs['job']==5){
				$name = $row['name'];
				$id = $row['acctid'];
			}
			if ($allprefsj['job']==8 && $allprefs['job']==7){
				$name = $row['name'];
				$id = $row['acctid'];
			}
			if ($allprefsj['job']==10 && $allprefs['job']==9){
				$name = $row['name'];
				$id = $row['acctid'];
			}
			if ($allprefsj['job']==12 && $allprefs['job']==11){
				$name = $row['name'];
				$id = $row['acctid'];
			}
			if ($allprefsj['job']==14 && $allprefs['job']==13){
				$name = $row['name'];
				$id = $row['acctid'];
			}
			if ($allprefsj['job']==16 && $allprefs['job']==15){
				$name = $row['name'];
				$id = $row['acctid'];
			}
			if ($allprefsj['job']==18 && $allprefs['job']==17){
				$name = $row['name'];
				$id = $row['acctid'];
			}
			if ($allprefsj['job']==20 && $allprefs['job']==19){
				$name = $row['name'];
				$id = $row['acctid'];
			}
		}
		if (get_module_pref("user_stat","jobs",$id)==1) {
			require_once("lib/systemmail.php");
			$location=get_module_setting("type".$allprefs['job']);
			$subj = sprintf("`2Delinquent Employee %s `2at the %s",$session['user']['name'],$location);
			$body = sprintf("`&Dear %s`&,`n`nAs the manager of the %s, I thought you should know that %s`& has been fired for failure to show up to work.`n`nSincerely,`nThe Foreman",$name,$location,$session['user']['name']);
			systemmail($id,$subj,$body);
		}
		$allprefs['job']=0;
	}
	if (get_module_setting("reset")==1 || (get_module_setting("reset")==2 && $allprefs['jobexp']<get_module_setting("resetx"))) $allprefs['jobworked']=0;
	set_module_pref('allprefs',serialize($allprefs));
?>