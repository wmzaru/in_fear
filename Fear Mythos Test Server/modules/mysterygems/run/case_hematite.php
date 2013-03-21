<?php
$session['user']['gold'] -= $hc;
require_once("modules/mysterygems/run/case_not_enter.php");
output("Gem winks at you as you feel your body swirl, but not move at all. ");
output("Your head spins and your eyes roll to the back of your head.");
output("`n`nWhen you come out of the trance, ");
if (!$mgresult){
	apply_buff('mysgems', array(
		"startmsg"		=> "`\$Your hematite health kicks in...",
		"name"		=> "`\$Hematite Health",
		"rounds"		=> 20,
		"wearoff"		=> "Your hematite health has faded...",
		"regen"		=> ceil($session['user']['maxhitpoints'] * $ul / 150),
		"effectmsg"		=> "Your hematite glows and you regenerate for {damage} health.",
		"effectnodmgmsg"	=> "Your hematite glows, but you have no wounds to regenerate.",
		"schema"		=> "module-mysterygems")
	);
	output("you feel invigorated!`n`n`@You have gained Hematite Health!");
}
elseif ($mgresult == 1){
	$turnslost = e_rand(3,7);
	$session['user']['turns'] -= $turnslost;
	output("you feel dizzy and sick!`n`n`@You have lost %s turns!", $turnslost);
}
else{
	$session['user']['turns'] = 0;
	output("you feel like you're going to puke!");
	output("`n`n`@You have lost all of your turns for the day!");
}
?>