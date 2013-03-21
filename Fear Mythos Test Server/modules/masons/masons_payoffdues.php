<?php
function masons_payoffdues(){
	global $session;
	addnav("The Order");
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['duespaid']<$golddues) addnav("`&Pay More Dues","runmodule.php?module=masons&op=duestopay");
	addnav("`&Narmyan's Office","runmodule.php?module=masons&op=office");
	addnav("`&The Lounge","runmodule.php?module=masons&op=enter");
	addnav("Village");
	addnav("Return to Village","village.php");
	$golddues=get_module_setting("dues");
	$dues = httppost('duesp');
	$max = $session['user']['gold'];
	$amountdue =$golddues-$allprefs['duespaid'];
	if ($dues < 0) $dues = 0;
	if ($dues >= $max) $dues = $max;
	if ($max < $dues) output("`&The `&T`)reasurer `&looks at you bewildered. `7'You know, you don't have that much gold!'`n`n");
	elseif ($dues>$amountdue) {
		output("`7'Actually, you don't need to pay that much to us at this time.");
		output("All you need to contribute is `^%s gold`7.'`n`n",$amountdue);
		output("`&The T`)reasurer`& gladly accepts the rest of your dues.");
		output("`n`n  You will not have to pay any more dues for another`b`& %s days`b`7.",$allprefs['duestime']);
		$session['user']['gold']-=$amountdue;
		$allprefs['duespaid']=$allprefs['duespaid']+$amountdue;
		set_module_pref('allprefs',serialize($allprefs));
		blocknav("runmodule.php?module=masons&op=duestopay");
	}elseif ($dues==$amountdue) {
		output("`&The T`)reasurer`& gladly accepts the rest of your dues and notes that you are paid in full.");
		output("`n`nYou will not have to pay any more dues for another `b`&%s days`b`7.",$allprefs['duestime']);
		$session['user']['gold']-=$dues;
		$allprefs['duespaid']=$allprefs['duespaid']+$dues;
		set_module_pref('allprefs',serialize($allprefs));
		debuglog("paid Masons Dues in full.");
	}elseif ($dues==0){
		output("`7'Umm, well, thanks for nothing.");
		output("But how would you like to actually contribute some `b`^gold`b`7?'");
	}else{
		$allprefs['duespaid']=$allprefs['duespaid']+$dues;
		set_module_pref('allprefs',serialize($allprefs));
		$amountdue =$golddues-$allprefs['duespaid'];
		output("`&The `&T`)reasurer `&gladly accepts your payment of `^%s gold`7.",$dues);
		output("`n`n`7 'You only owe`^ %s gold `7more.'",$amountdue);
		output("`n`n'You have`&`b %s days`b`7 to pay this amount in full.'",$allprefs['duestime']);
		$session['user']['gold']-=$dues;
	}
}
?>