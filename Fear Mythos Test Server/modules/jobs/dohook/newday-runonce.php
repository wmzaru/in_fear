<?php
	$maxnum=get_module_setting("maxnum");
	$array=array(0,1000,1000,1500,2500,4500,7500,10000,15000,20000,25000);
	$sql = "SELECT acctid,name FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		$allprefs=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
		//Fire Managers
		if ($allprefs['lastworked']<date("Y-m-d H:i:s",strtotime("-259200 seconds")) && $allprefs['job']%2==0 && $allprefs['job']>0){
			$location=get_module_setting("type".floor($allprefs['job']/2));
			$allprefs['job']=0;
			$allprefs['jobexp']=$array[$maxnum];
			$allprefs['lastworked']=date("Y-m-d H:i:s");
			require_once("lib/systemmail.php");
			$subj = sprintf("Manager Termination at the %s",$location);
			$body = sprintf("You have been fired from your job as manager at the %s for not showing up to work for too long!`n",$location);
			systemmail($row['acctid'],$subj,$body);
			set_module_pref('allprefs',serialize($allprefs),'jobs',$row['acctid']);
			addnews ("%s `^ was fired from Management at the %s for not showing up to work. That means there's a job opening!",$row['name'],$location);
		}
		$allprefs=unserialize(get_module_pref('allprefs','jobs',$row['acctid']));
		if ($allprefs['job']==-1) $allprefs['job']=0;
		$allprefs['jobworked']=0;
		//Increment vacation down per game day
		if ($allprefs['vacation']>0){
			$allprefs['vacation']=$allprefs['vacation']-1;
			$allprefs['lastworked']=date("Y-m-d H:i:s");
		}
		set_module_pref('allprefs',serialize($allprefs),'jobs',$row['acctid']);
	}
?>