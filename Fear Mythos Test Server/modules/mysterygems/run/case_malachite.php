<?php
$session['user']['gold'] -= $mac;
$umhp = $session['user']['maxhitpoints'];
require_once("modules/mysterygems/run/case_not_enter.php");
output("`!Malachite, beautiful stone!`0, Gem spurts out in a rush as he hands you the malachite.");
output("`n`nThe malachite melds into your palm making you feel ");
if (!$mgresult){
	$hpgain = round($umhp * e_rand(7,17) / 100);
	$session['user']['hitpoints'] += $hpgain;
	output("healthy.");
	output("`n`n`@You have gained %s hit points!", $hpgain);
	debuglog("gained $hpgain hit points at G.E.M");
}
elseif ($mgresult == 1){
	$loss = round($umhp * e_rand(24,34) / 100);
	$session['user']['hitpoints'] -= $loss;
	output("sick and feeble.");
	output("`n`n`@You have lost %s hit points!", $loss);
}
else{
	$loss = round($umhp * e_rand(35,75) / 100);
	$session['user']['hitpoints'] -= $loss;
	output("terrible!");
	output("`n`n`@You have lost %s hit points!", $loss);
}
?>