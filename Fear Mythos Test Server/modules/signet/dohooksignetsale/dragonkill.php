<?php
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['dksince']=$allprefs['dksince']+1;
	set_module_pref('allprefs',serialize($allprefs));
	$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
	$allprefsd2=unserialize(get_module_pref('allprefs','signetd2'));
	$allprefsd3=unserialize(get_module_pref('allprefs','signetd3'));
	$allprefsd4=unserialize(get_module_pref('allprefs','signetd4'));
	$allprefsd5=unserialize(get_module_pref('allprefs','signetd5'));
	if ($allprefsd5['complete']==1  && $allprefs['dksince']>=get_module_setting("dksincewin") && get_module_setting("dksincewin")>=0){
		set_module_pref("hoftemp",0);
		$allprefs['dksince']="";
		$allprefs['nodkopen']="";
		$allprefs['scroll1']="";
		$allprefs['incomplete']="";
		for ($i=1;$i<=5;$i++) {
			$allprefs['dkopen'.$i]="";
			$allprefs['paidturn'.$i]="";
			$allprefs['paidgold'.$i]="";
			$allprefs['paidgem'.$i]="";
			$allprefs['sigmap'.$i]="";
		}
		$allprefsd1["airsignet"]="";
		$allprefsd1["complete"]="";
		$allprefsd1["reset"]="";
		$allprefsd2["earthsignet"]="";
		$allprefsd2["complete"]="";
		$allprefsd2["reset"]="";
		$allprefsd3["watersignet"]="";
		$allprefsd3["complete"]="";
		$allprefsd3["reset"]="";
		$allprefsd4["firesignet"]="";
		$allprefsd4["complete"]="";
		$allprefsd4["reset"]="";
		$allprefsd5["powersignet"]="";
		$allprefsd5["complete"]="";
		$allprefsd5["reset"]="";
		$allprefsd5["darkdead"]="";
		set_module_pref('allprefs',serialize($allprefs));
		set_module_pref('allprefs',serialize($allprefsd1),'signetd1');
		set_module_pref('allprefs',serialize($allprefsd2),'signetd2');
		set_module_pref('allprefs',serialize($allprefsd3),'signetd3');
		set_module_pref('allprefs',serialize($allprefsd4),'signetd4');
		set_module_pref('allprefs',serialize($allprefsd5),'signetd5');
		require_once("lib/systemmail.php");
		$name = $session['user']['name'];
		$staff= get_module_setting("frwhosend","signetd5");
		$id = $session['user']['acctid'];
		$subj = sprintf("`4Warning! `^The Return of Mierscri");
		$body = sprintf("`&Dear %s`&,`n`nIt is with great saddness that I write to inform you that the evil Mierscri has returned to terrorize our kingdom.  Please help stop him from destroying the tranquility we have come to treasure.`n`nSincerely,`n`n%s",$name,$staff);
		systemmail($id,$subj,$body);
	}
?>