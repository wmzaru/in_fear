<?php
page_header("Alignment Palace");

$op = httpget('op');
$alignfollow = get_module_pref("alignfollow");
require_once("modules/rpalignment/functions.php");
$run = "runmodule.php?module=rpalignment&case=palace&op=";
$priest = get_module_setting("priest");

if ($op == "enter"){
	addnav("Navigation");
	addnav("List Alignments",$run."list");
	if ($alignfollow >= 1){
		$info = rpalign_info($alignfollow);
		addnav(array("Stop Following %s",$info['name']),$run."stop");
		addnav(array("View %s`0 Info",$info['name']),$run."view&id=".$alignfollow);
		addnav(array("Chat Room for the %s",$info['name']),$run."chat&id=".$alignfollow);
	}
	addnav("Return");
	villagenav();
	
	output("`@You walk into the large palace, home to alignment experts around. You are approached by %s`@, who seems very interested in you.`n`n",$priest);
	if ($alignfollow == 0){
		output("`@\"`2I see you are currently following atheism. Would you care to follow one of our alignments?`@\"`n`n");
		rpalign_list();
	}else{
		$info = rpalign_info($alignfollow);
		output("`@\"`2Ahh! I see you are following the %s`2! How have your worshippings been going?`@\"",$info['name']);
	}
	modulehook("rpalignment-palace_enter");
}

if ($op == "list"){
	output("`@You decide to inquire about other alignments there are. %s`@ smiles and leads you to a small pedestal with a large book on it. It is opened to a table of contents page.`n`n",$priest);
	rpalign_list();
	addnav("Navigation");
	addnav("Main Hall",$run."enter");
	addnav("Return");
	villagenav();
	modulehook("rpalignment-palace_list");
}

if ($op == "members"){
	$id = httpget('id');
	output("`@You decide to inquire about the members in the other alignments. %s`@ smiles and leads you to a small pedestal with a large book on it. It is opened to an index page.`n`n",$priest);
	rpalign_members($id);
	addnav("Navigation");
	addnav("Main Hall",$run."enter");
	addnav("List Alignments","runmodule.php?module=rpalignment&case=palace&op=list");
	addnav("Return");
	villagenav();
	modulehook("rpalignment-palace_members");
}

if ($op == "stop"){
	output("`@You whisper quietly to %s`@ about trying to stop following a certain alignment.`n",$priest);
	output("`@He smiles at you, \"`2Ah, but that is so simple. I just remove your name from the list of followers for that alignment.`@\"`nHe opens a book, erases something, then looks up. \"`2See. That easy. It is done!`@\"");
	output("`n`n`@He frowns. \"However, this means you will have to die for your sins of leaving.. You will find yourself in the Shades for %s (game) days.",get_module_setting("rdeadtime"));
	set_module_pref("alignfollow",0);
	set_module_pref("alignremove",1);
	set_module_pref("rdead",1);
	$session['user']['alive']=0;
	$session['user']['hitpoints']=0;
	addnav("Navigation");
	addnav("Main Hall",$run."enter");
	addnav("Return");
	villagenav();
	modulehook("rpalignment-palace_stop");
}

if ($op == "view"){
	$id = httpget('id');
	$info = rpalign_info($id);
	output("`@You walk over to a statue of the %s`@ and look at it. Inscribed in a book next to it is important information that you decide to look at.`n`n",$info['name']);
	output("`^Name: `@%s`0`n",$info['name']);
	output("`^Followers: `@%s`0`n",rpalign_followcount($id));
	addnav("Navigation");
	addnav("Main Hall",$run."enter");
	if ($alignfollow == 0){
		addnav("Follow Alignment",$run."follow&id=".$id);
	}else{
		if ($alignfollow == $id){
			addnav("Stop Following",$run."stop");
		}
	}
	addnav("Return");
	villagenav();
	modulehook("rpalignment-palace_view");
}

if ($op == "follow"){
	$id = httpget('id');
	$info = rpalign_info($id);
	output("`@You decide to follow the %s`@. You tell %s`@ this and he marks you in his books of followers.",$info['name'],$priest);
	addnav("Navigation");
	addnav("Main Hall",$run."enter");
	addnav("Return");
	villagenav();
	set_module_pref("alignfollow",$id);
}

if ($op == "go"){

	$sql = "SELECT name,id FROM ".db_prefix("rpalignment")." ORDER BY id ASC";
	$res = db_query($sql);
	
	output("`Q`c`bSet Roleplay Alignment`b`0`n");
	if (get_module_pref("alignremove") == 1){
		output("<h3>`^You left your previous alignment!</h3>",true);
	}
	if (get_module_pref("aligndelete") == 0){
		output("<h3>`^You are required to set your roleplay alignment. Do so now! </h3>`n`n",true);
	} else {
		output("<h3>`^Your alignment was deleted, and you are required to reset your roleplay alignment. Do so now!</h3>`n`n",true);
	}
	rawoutput("<form action=\"runmodule.php?module=rpalignment&case=palace&op=set\" method='POST'>");
	output("Roleplay Alignment:");
	
	rawoutput("<select name='select'>");
	while ($row = db_fetch_assoc($res)){
		rawoutput("<option value={$row['id']}>{$row['name']}</option>");
	}
	rawoutput("</select>");
	rawoutput("<br><br>");
	
	$createbutton = translate_inline("Enter!");
	rawoutput("<input type='submit' class='button' value='$createbutton'>");
	rawoutput("</form></center>");
	addnav("","runmodule.php?module=rpalignment&case=palace&op=set");
}

if ($op == "set"){
       output("`Q`c`bThank you!`n`^You may now proceed.`b`c");
			$name = $session['user']['login'];	
			$sql = "SELECT acctid FROM " . db_prefix("accounts") . " WHERE login='$name'";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$id=$row['acctid'];
			set_module_pref('alignfollow',$select,'rpalignment',$id);
			set_module_pref('aligndelete',0,'rpalignment',$id);
			set_module_pref('alignremove',0,'rpalignment',$id);
        villagenav();
}

if ($op == "su"){
	set_module_pref("rdead",0);
	set_module_pref("rdeadleft",0);
	output("Continue!");
	addnav("It is a new day!","newday.php");
}

if ($op == "chat"){
	require_once("lib/commentary.php");
	require_once("lib/villagenav.php");
	$id = httpget('id');
	$info = rpalign_info($id);
	output("`b`c`\$Chat Room for the %s`c`b`n",$info['name']);
	output("You are in the middle of a room reserved only for the members of your own Roleplay Alignment, to discuss roleplay and similar topics.");
	addcommentary();
	commentdisplay("`n`n`@People converse:`n","rpalignment$id","Talk",20,"talks");
	addnav("Return");
	addnav("Main Hall",$run."enter");
	villagenav();
}

page_footer();