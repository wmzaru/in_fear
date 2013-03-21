<?php

function gotlost_getmoduleinfo(){
        $info = array(
                "name"=>"Getting Lost",
                "version"=>"1.1",
                "author"=>"Kevin Hatfield - Arune",
                "category"=>"Forest Specials",
				"vertxtloc"=>"http://www.dragonprime.net/users/khatfield/",
				"download"=>"http://dragonprime.net/users/khatfield/gotlost98.zip",
);
        return $info;
}

function gotlost_install(){
        module_addeventhook("forest", "return 100;");
        return true;
}

function gotlost_uninstall(){
        return true;
}

function gotlost_dohook($hookname,$args){
        return $args;
}

function gotlost_runevent($type){
global $session;
output("`^After walking for what seems like forever you have gotten lost.`nYou have lost `$1 turn `^finding your way out!`0"); 
$session['user']['turns']--;
debuglog("lost 1 turn in the forest");
}
function gotlost_run(){
}
?>
