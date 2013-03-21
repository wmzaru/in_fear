<?php
$session['user']['gold'] -= $sc;
$uexp = $session['user']['experience'];
require_once("modules/mysterygems/run/case_not_enter.php");
output("'Oh, aren't you fancy?! I like that!', proclaims Gem as he hands you the Star Sapphire. ");
if (!$mgresult){
	$expgained = round(e_rand(1,5) / 100 * $uexp) + 1;
	$session['user']['experience'] += $expgained;
	output("He grins from ear to ear as you feel the star sapphire take it's hold.");
	output("`n`n`#Just as you thought you would pass out, your mind clicks and you gain %s experience!", $expgained);
	debuglog("received $expgained experience at G.E.M.");
}
elseif ($mgresult == 1){
	$explost = round(e_rand(1,10) / 100 * $uexp) + 1;
	$session['user']['experience'] -= $explost;
	$output = 1;
}
else{
	$explost = round(e_rand(10,34) / 100 * $uexp) + 1;
	$session['user']['experience'] -= $explost;
	$output = 1;
}
if ($output){
	output("He wrinkles his nose as you feel the star sapphire take it's hold.");
	output("`n`n`#Just as you thought you would pass out, your mind crashes and you lose %s experience!", $explost);
}
?>