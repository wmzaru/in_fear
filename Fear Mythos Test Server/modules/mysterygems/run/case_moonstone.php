<?php
$session['user']['gold'] -= $moc;
require_once("modules/mysterygems/run/case_not_enter.php");
output("Gem reaches for the moonstone and then hands it to you with a chuckle.");
if (!$mgresult){
	$gemsgained = e_rand(1,3);
	$session['user']['gems'] += $gemsgained;
	output("`n`nThe moonstone illuminates the room in a brilliant luminesence. ");
	output("The light allows you to spy several gems within the room that others missed.");
	output("`n`n`@You have gained %s gems!", $gemsgained);
	debuglog("gained $gemsgained hit points at G.E.M");
}
elseif ($mgresult == 1){
	$gemslost = e_rand(3,6);
	$session['user']['gems'] -= $gemslost;
	$output = 1;
}
else{
	$gemslost = e_rand(10,15);
	$session['user']['gems'] -= $gemslost;
	$output = 1;
}
if ($output){
	output("`n`nThe moonstone trembles in your hand. ");
	output("Before you know it, your entire body quivers, shaking free several gems unto the ground, which seemingly fade.");
	output("`n`n`@You have lost %s gems!", $gemslost);
}
?>