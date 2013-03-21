<?php
// I really don't know how to do this one for translations...

function myrrdin_getmoduleinfo(){
	$info = array(
		"name"=>"Myrrdin's House",
		"author"=>"Spider",
		"version"=>"1.1",
		"category"=>"Alley",
		"download"=>"http://dragonprime.net/users/Spider/darkalley.zip"
	);
	return $info;
}

function myrrdin_install(){
	if (!is_module_installed("darkalley")) {
    output("This module requires the Dark Alley module to be installed.");
    return false;
	}
	else {
		module_addhook("darkalley");
		return true;
	}
}

function myrrdin_uninstall(){
	return true;
}

function myrrdin_dohook($hookname, $args){
	global $session;
	switch($hookname){
	case "darkalley":
		addnav("Shady Houses");
		addnav("Myrrdin's House", "runmodule.php?module=myrrdin");
		break;
	}
	return $args;
}

function myrrdin_run(){
	global $session;
	require_once("lib/increment_specialty.php");
	require_once("lib/http.php");
	
	page_header("Myrrdin's House");

	output("`7`c`bMyrrdin's House`b`c`n");
	
	$op = httpget('op');
	$type = httpget('type');
	$specialties = modulehook("specialtynames");
	$colors = modulehook("specialtycolor");

	if ($op==""){
		output("Myrrdin's house is large and spacious, the main room is filled with shelves and shelves of books and strange looking instruments.  Myrrdin himself is sitting behind a large oak desk closely examining some metal contraption.  Unsure of what exactly it is, you don't get too close.`n");
		output("You discreetly clear your throat, trying to get Myrrdin's attention, it works.`n`n");
		if ($session['user']['specialty']==""){
			output("`3\"Oh, hello there, I'm sorry, I was rather engrossed.  Let me look at you.  Well now theres a thing, you aren't interested in anything.  I suppose I can still teach you, but it will cost you considerably more as you have little experience of these things.\"`n`n");
		}
		else{
			output("`3\"Oh, hello there, I'm sorry, I was rather engrossed.  Let me look at you.  Ah yes, I see that you are interested in ".$skills[$session[user][specialty]].", well I can certainly teach you a little about that, or something else if you desire.  Of course learning something you are unfamiliar with won't be so easy, and will cost you a little more.\"`n`n");
		}
		foreach ($specialties as $key=>$name) {
			$color = $colors[$key];
			if ($session['user']['specialty']==$key){
				output("`3\"I can teach you about %s%s`3 for `^5000 gold`3 and `%2 gems`3\"`n",$color,$name);
			}
			else{
				output("`3\"I can teach you about %s%s`3 for `^10000 gold`3 and `%4 gems`3\"`n",$color,$name);
			}
			addnav(array("Learn about %s",$name),"runmodule.php?module=myrrdin&op=learn&type=$key");
		}
	}

	else if ($op=="learn"){
		if ($type==$session['user']['specialty']){
			$cheap=true;
		}
		if ($cheap==true){
			if ($session[user][gold]>4999 && $session[user][gems]>1){
				output("`3\"I see you you want to learn more about your interests.  Good, I like a person who pursues what they enjoy.  Sit down for a while and I will teach you all about %s.\"`0`n`n",$specialties[$type]);
				output("You sit and listen to Myrrdin's wise words for a time.");
				increment_specialty("`3");
				$session[user][gold]-=5000;
				$session[user][gems]-=2;
				debuglog("Gave 2 gems and 5000 gold to Myrrdin");
			}
			else{
				output("`3\"You don't appear to have enough to cover my costs, I'm afraid I can't teach you unless I receive the full payment up front.  Remember that the benefit of education far outweighs the costs.\"");
			}
		}
		else{
			if ($session[user][gold]>9999 && $session[user][gems]>3){
				output("`3\"Wonderful, you're seeking to expand your interests.  You know, I highly recommend learning about all of the things I can teach you, they are all quite rewarding.\"`n");
				output("`3\"If you would like to take a seat, I shall teach you a little about %s.\"`0`n`n",$specialties[$type]);
				output("You sit and listen to Myrrdin's wise words for a time.");
				$oldspecialty = $session['user']['specialty'];
				$session['user']['specialty'] = $type;
				increment_specialty("`3");
				$session['user']['specialty'] = $oldspecialty;
				$session[user][gold]-=10000;
				$session[user][gems]-=4;
				debuglog("Gave 4 gems and 10000 gold to Myrrdin");
			}
			else{
				output("`3\"You don't appear to have enough to cover my costs, I'm afraid I can't teach you unless I receive the full payment up front.  Remember that the benefit of education far outweighs the costs.\"");
			}
		}
	}
	
	addnav("Return to the Alley","runmodule.php?module=darkalley");
	
	page_footer();
}

?>