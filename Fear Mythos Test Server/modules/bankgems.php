<?php

function bankgems_getmoduleinfo(){
	$info = array(
		"name"=>"Bank w/Gem interest",
		"version"=>"1.0",
		"author"=>"`6Harry Balzitch",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;catd=11",
		"settings"=>array(
			"Bank w/Gem interest - Settings,title",
			"goldint"=>"How much xtra interest for gold?,int|2",
			"gemint"=>"How much daily interest for gems?,int|2",
		),
		"prefs"=>array(
			"Bank w/Gem interest - user Prefs,title",
			"bankgems"=>"How many gems are in the bank?,int|0",
		)
	);
	return $info;
}

function bankgems_install(){
	module_addhook("footer-bank");
	module_addhook("newday");
	return true;
}

function bankgems_uninstall(){
	return true;
}

function bankgems_dohook($hookname,$args){
	global $session;
	$from = "runmodule.php?module=bankgems&";
	switch($hookname){
	case "footer-bank":
		addnav("Gems");
		addnav("Withdraw Gems",$from."op=withdraw");
		addnav("Deposit Gems",$from."op=deposit");
		if (httpget('op')=="" && get_module_pref("bankgems")!=0){
		output("`n`n`3 You have `^ %s gems `3 in the bank. ",get_module_pref("bankgems"));
		}
	break;
	case "newday":
	$level=$session['user']['level'];
	$gold=$session['user']['gold'];
	$bankgold=$session['user']['goldinbank'];
	$gems=$session['user']['gems'];
	$goldint=get_module_setting("goldint");
	$gemint=get_module_setting("gemint");
	$bankgems=get_module_pref("bankgems");
	$goldpay=round($level*$goldint);
	$gempay=round($level*$gemint);
	if ($bankgold >=1){
		output("`n You earn an extra %s gold in bank interest. ",$goldpay);
		$session['user']['goldinbank']+=$goldpay;
	}if ($bankgems >=1){
		if($gempay>$bankgems){ $gempay=$bankgems;}
		output("`n You earn  %s gems in bank interest. ",$gempay);
		increment_module_pref("bankgems",$gempay);
	}
	break;
	}
	return $args;
}

function bankgems_run(){
	global $session;
	page_header("Ye Olde Bank");
	$from = "runmodule.php?module=bankgems&";
	$bankgems=get_module_pref("bankgems");
	$gems=$session['user']['gems'];
	$op = httpget('op');
	if($op=="deposit"){
		output("`0");
		rawoutput("<form action='runmodule.php?module=bankgems&op=depositfinish' method='POST'>");
		output("`6 Elessa says, \"`3You have a total of `^%s`3 gems in the bank.`6\" ",$bankgems);
		output(" Searching through all your pockets and pouches, you find only `^%s`6 gems on hand.`n`n",$gems);
		output("`^Deposit how many?");
		$dep = translate_inline("Deposit Gems");
		rawoutput(" <input id='input' name='amount' width=5 > <input type='submit' class='button' value='$dep'>");
		output("`n`iEnter 0 or nothing to deposit all of your gems`i");
		rawoutput("</form>");
		rawoutput("<script language='javascript'>document.getElementById('input').focus();</script>",true);
	  addnav("",$from."op=depositfinish");
	}elseif($op=="depositfinish"){
		$amount = abs((int)httppost('amount'));
		if ($amount==0){
			$amount=$session['user']['gems'];
		}
		$notenough = translate_inline("`\$ERROR: Not enough gems in hand to deposit.`n`n`^You plunk your `&%s`^ gems on the counter and declare that you would like to deposit all `&%s`^ gems of them.`n`n`6Elessa stares at you, then you become embarrassed when realizing your mistake.");
		$depositbalance= translate_inline("`6Elessa records your deposit of `^%s `6gems in her ledger. \"`3Thank you, `&%s`3.  You now have a total of `^%s`3 gems in the bank and `^%s`3 gems in hand.`6\"");
		if ($amount>$session['user']['gems']){
			output($notenough,$gems,$amount);
		}else{
			debuglog("deposited " . $amount . " gems in the bank");
			$bankgems = get_module_pref("bankgems");
			$bankgems+=$amount;
			set_module_pref("bankgems",$bankgems);
			$session['user']['gems']-=$amount;
			output($depositbalance,$amount,$session['user']['name'], abs($bankgems),$gems);
		}
	}if($op=="withdraw"){
		$withdraw = translate_inline("Withdraw Gems");
		rawoutput("<form action='runmodule.php?module=bankgems&op=withdrawfinish' method='POST'>");
		output("`6 Elessa scans through her ledger, \"`3You have a total of `^%s`3 gems in the bank.`6\"`n",$bankgems);
		output("`6\"`3 How many gems would you like to withdraw `0 %s`3?\"`n`n",$session['user']['name']);
		rawoutput("<input id='input' name='amount' width=5 > <input type='submit' class='button' value='$withdraw'>");
		output("`n`iEnter 0 or nothing to withdraw all of your gems`i");
		rawoutput("</form>");
		rawoutput("<script language='javascript'>document.getElementById('input').focus();</script>");
		addnav("",$from."op=withdrawfinish");
	}elseif($op=="withdrawfinish"){
		$amount=abs((int)httppost('amount'));
		if ($amount==0){
			$amount=abs($bankgems);
		}
		if ($amount>$bankgems) {
			output("`\$ERROR: Not enough gems in the bank to withdraw.`^`n`n");
			output("`6 Having been informed that you have `^%s`6 gems in your account, you declare that you would like to withdraw all `^%s`6 of them.`n`n", $bankgems, $amount);
			output("Elessa stares at you, then advises you to take basic arithmetic.  You realize your folly and think you should try again.");
		}else{
			$bankgems = get_module_pref("bankgems");
			$bankgems-=$amount;
			set_module_pref("bankgems",$bankgems);
			$session['user']['gems']+=$amount;
			debuglog("withdrew $amount gems from the bank");
			output("`6 Elessa records your withdrawal of `^%s `6gems in her ledger. \"`3Thank you, `&%s`3.  You now have a total of `^%s`3 gems in the bank and `^%s`3 gems in hand.`6\"", $amount,$session['user']['name'], abs($bankgems),$gems);
		}
	}
	require_once("lib/villagenav.php");
	villagenav();
	addnav("Gold");
	if ($session['user']['goldinbank']>=0){
		addnav("W?Withdraw","bank.php?op=withdraw");
		addnav("D?Deposit","bank.php?op=deposit");
		if (getsetting("borrowperlevel",20)) addnav("L?Take out a Loan","bank.php?op=borrow");
	}else{
		addnav("D?Pay off Debt","bank.php?op=deposit");
		if (getsetting("borrowperlevel",20)) addnav("B?Borrow More","bank.php?op=borrow");
	}
	if (getsetting("allowgoldtransfer",1)){
		if ($session['user']['level']>=getsetting("mintransferlev",3) || $session['user']['dragonkills']>0){
		addnav("M?Transfer Money","bank.php?op=transfer");
		}
	}
		addnav("Gems");
		addnav("Withdraw Gems",$from."op=withdraw");
		addnav("Deposit Gems",$from."op=deposit");
	page_footer(); 
}
?>