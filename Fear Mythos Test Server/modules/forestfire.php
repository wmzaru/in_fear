<?php
// Robert of maddrio.com
function forestfire_getmoduleinfo(){
	$info = array(
		"name"=>"Forest Fire",
		"version"=>"1.0",
		"author"=>"`2Robert",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?topic=2215.0",
	);
	return $info;
}

function forestfire_install(){
	if (!is_module_active('forestfire')){
		output("`Q Installing Forest Fire - a Forest event `0");
	}else{
		output("`Q Up Dating Forest Fire  `n`0");
	}
	module_addeventhook("forest","return 10;");
	return true;
}

function forestfire_uninstall(){
	output("`Q Un-installing Forest Fire - a Forest event `0");
	return true;
}

function forestfire_dohook($hookname,$args){
	return $args;
}

function forestfire_runevent($type,$link){
	global $session;
//  IF you have an image of a forest fire you want to use then change the next line to suite
#	output("`c<img src='images/forestfire.gif' width='200' height='100' alt='forest fire' align='center'><br>`c",true);
	rawoutput("<font size='+3'>");
	output("`c`b`\$ F I R E !`0`b`c");
	rawoutput("</font>");
	output("`n`n`2`c The forest is on Fire! Run for your life! `b`c`0");
	if ($session['user']['turns']>=1){
		output("`n`n`2`c You `%lose one `2turn!`c`0");
		$session['user']['turns']-=1;
	}
$session['user']['specialinc']="";
}

function forestfire_run(){
}
?>