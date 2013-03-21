<?php

function biocharm_getmoduleinfo() {
	$info = array (
		"name" => "Display charm in Bio",
		"author" => "moi",
		"version" => "1.0",
		"category" => "Moi",
	);
	return $info;
}

function biocharm_install() {
	module_addhook("biostat");
	return true;
}

function biocharm_uninstall() {
	return true;
}

function biocharm_dohook($hookname,$args) {
	global $session;
	if ($hookname == "biostat") {
		$charm = $session['user']['charm'];
		output("`n`^Charm: `@%s`n",$charm);
	}
	return $args;
}

function biocharm_run() {
}
?>
	