<?php
$session['user']['gold'] -= $dc;
require_once("modules/mysterygems/run/case_not_enter.php");
output("Gem pulls out your diamond and hands it to you with a smile telling you to come back and visit another time.");
output("`n`nHe then dives behind the counter as the room rumbles in a dusty downfall of shakes. ");
if (!$mgresult){
	$favorgained = e_rand(10,100);
	$session['user']['deathpower'] += $favorgained;
	output("You could have sworn you saw Death for a moment. The experience leaves you in favor with the Underworld.");
	output("`n`n`@You have gained %s Favor!", $favorgained);
	debuglog("received $favorgained favor at G.E.M.");
}
elseif ($mgresult == 1){
	$favorlost = e_rand(100,150);
	$session['user']['deathpower'] -= $favorlost;
	output("The fear of death overwhelms you as you run screaming around the room.");
	output("`n`n`@You have lost %s Favor!", $favorlost);
}
else{
	$favorlost = e_rand(50,150);
	$session['user']['deathpower'] -= $favorlost;
	$session['user']['hitpoints']	= 0;
	$session['user']['alive'] = 0;
	$goldlost = round(get_module_setting('goldlost') *.01 * $session['user']['gold']);
	$session['user']['gold'] -= $goldlost;
	$gemslost = round(get_module_setting('gemslost') *.01 * $session['user']['gems']);
	$session['user']['gems'] -= $gemslost;
	output("The Reaper looks ticked that you bothered  him and raises his sickle. ");
	output("With one fell swoop, he puts an end to your misery and you fade into the shades.");
	output("`n`n`!You have DIED, lost %s Favor, and %s gems and %s gold!", $favorlost, $gemslost, $goldlost);
	blocknav("runmodule.php?module=mysterygems&op=enter");
	blocknav("village.php");
	addnav("To the Shades", "shades.php");
	debuglog("Died, lost $favorlost favor, $gemslost gems and $goldlost gold at G.E.M.");
}
?>