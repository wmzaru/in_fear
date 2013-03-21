<?php
echo $output;
$session['user']['gold'] -= $tc;
require_once("modules/mysterygems/run/case_not_enter.php");
output("Gem reaches into the cabinet and removes the polished piece of turquoise. ");
output("He wryly smiles and hands it to you.");
if (!$mgresult){
	$buff = unserialize($playermount['mountbuff']);
	if ($buff['schema'] == "") $buff['schema'] = "mounts";
	apply_buff('mount', $buff);
	output("The turquoise radiates with a warmth that engulfs your hand. ");
	output("You hear your mount outside shift about. ");
	output("Turning toward Gem, you see him gleefully grinning.");
	output("`!`n`nYour mount is recharged and ready to fight!");
}
elseif ($mgresult == 1){	
	$lostmountdaysrand = e_rand(2,3);
	set_module_pref('lostmount', 1);
	set_module_pref('lostmountdays', $lostmountdaysrand);
	set_module_pref('mountid', $session['user']['hashorse']);
	$session['user']['hashorse'] = 0;
	$buff = unserialize($playermount['mountbuff']);
	if ($buff['schema'] == "") $buff['schema'] = "mounts";
	strip_buff('mount', $buff);
	output("The turquoise resonates in your palm and then with a sudden flash of light, leaps from your grasp. ");
	output("Crashing through the window, it scares the willies out of your mount!");
	output("`n`n`@You've lost your mount for %s game days!", $lostmountdaysrand);
}
else{
	$session['user']['hashorse'] = 0;
	$buff = unserialize($playermount['mountbuff']);
	if ($buff['schema'] == "") $buff['schema'] = "mounts";
	strip_buff('mount', $buff);
	output("Gem grimaces as a strange turquoise hue emanates from the stone, engulfs the room and then moves outside. ");
	output("You here the thud of your mount collapse like a sack of potatoes. ");
	output("`@OH NO! Your mount has died from the magic of the stone!");
}
?>