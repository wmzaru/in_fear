<?php

function gembank_getmoduleinfo(){
	$info = array(
		"name"=>"Gems Inbank",
		"version"=>"1.0",
		"author"=>"Haku/CHicu",
		"category"=>"Stat Display",
        "download"=>"http://dragonprime.net/users/chicu/gembank.zip",
        "requires"=>array(
             "bankmod" => "1.1|Spider, http://dragonprime.net/users/Spider/bankmod.zip",
        ),
    );
	return $info;
}

function gembank_install(){
	module_addhook("charstats");
    return true;
}

function gembank_uninstall(){
	return true;
}

function gembank_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "charstats":
		if ($session['user']['alive']) {
			$old = getcharstat("Personal Info", "Gems");
			$new = $old;
			if (get_module_pref("gemsinbank","bankmod") > 0) $new .= " `7(" . get_module_pref("gemsinbank","bankmod") . ")";
			setcharstat("Personal Info", "Gems", $new);
	}
		break;
	}
	return $args;
}

function gembank_run(){
}
?>