<?php
if (!array_key_exists("dopyramid",$args)) $args['dopyramid'] = false;
if($time>$timeout)	$args['dopyramid'] = false;
if ($args['dopyramid']) {
	tlschema($args['schemas']['gatenav']);
	addnav($args["gatenav"]);
	tlschema();
	addnav("`b`!Forbidden Pyramid`b","runmodule.php?module=clanpyramid&op=enter");
}
clear_module_pref("square");
clear_module_pref("defender");
?>