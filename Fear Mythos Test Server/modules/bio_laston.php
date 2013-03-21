<?php

function bio_laston_getmoduleinfo(){
	$info = array(
		"name"=>"Bio: Last On",
		"author"=>"Chris Vorndran<br>`6Idea: `2Robert",
		"version"=>"1.0",
		"category"=>"General",
	);
	return $info;
}
function bio_laston_install(){
	module_addhook("biostat");
	return true;
}
function bio_laston_uninstall(){
	return true;
}
function bio_laston_dohook($hookname,$args){
	global $session, $target;
	switch ($hookname){
		case "biostat":
			require_once("lib/datetime.php");
			$laston = relativedate($target['laston']);
			output("`^Last On: `@%s`n",$laston);
			break;
		}
	return $args;
}

?>	