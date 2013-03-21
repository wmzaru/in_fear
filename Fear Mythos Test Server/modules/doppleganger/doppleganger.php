<?php
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$op3 = httpget('op3');
	$id = httpget('id');
	$ap = httpget('ap');
	page_header("The Doppleganger");
	
if ($op=="superuser"){
	require_once("modules/allprefseditor.php");
	allprefseditor_search();
	page_header("Allprefs Editor");
	$subop=httpget('subop');
	$id=httpget('userid');
	addnav("Navigation");
	addnav("Return to the Grotto","superuser.php");
	villagenav();
	addnav("Edit user","user.php?op=edit&userid=$id");
	modulehook('allprefnavs');
	$allprefse=unserialize(get_module_pref('allprefs',"doppleganger",$id));
	if ($allprefse['dgname']== "") $allprefse['dgname']= "";
	if ($allprefse['dgid']== "") $allprefse['dgid']= "";
	if ($allprefse['dgweapon']== "") $allprefse['dgweapon']= "";
	if ($allprefse['name']== "") $allprefse['name']= "";
	if ($allprefse['armor']== "") $allprefse['armor']= "";
	if ($allprefse['weapon']== "") $allprefse['weapon']= "";
	if ($allprefse['phrase1']== "") $allprefse['phrase1']= "";
	if ($allprefse['phrase2']== "") $allprefse['phrase2']= "";
	if ($allprefse['phrase3']== "") $allprefse['phrase3']= "";
	set_module_pref('allprefs',serialize($allprefse),'doppleganger',$id);
	if ($subop!='edit'){
		$allprefse=unserialize(get_module_pref('allprefs',"doppleganger",$id));
		$allprefse['encountered']= httppost('encountered');
		$allprefse['dgname']= httppost('dgname');
		$allprefse['dgid']= httppost('dgid');
		$allprefse['dgweapon']= httppost('dgweapon');
		$allprefse['dgsex']= httppost('dgsex');
		$allprefse['name']= httppost('name');
		$allprefse['armor']= httppost('armor');
		$allprefse['weapon']= httppost('weapon');
		$allprefse['sex']= httppost('sex');
		$allprefse['phrase1']= httppost('phrase1');
		$allprefse['approve1']= httppost('approve1');
		$allprefse['phrase2']= httppost('phrase2');
		$allprefse['approve2']= httppost('approve2');
		$allprefse['phrase3']= httppost('phrase3');
		$allprefse['approve3']= httppost('approve3');
		set_module_pref('allprefs',serialize($allprefse),'doppleganger',$id);
		output("Allprefs Updated`n");
		$subop="edit";
	}
	if ($subop=="edit"){
		require_once("lib/showform.php");
		$form = array(
			"Doppleganger Encounter,title",
			"encountered"=>"Has player encountered the Doppleganger today?,bool",
			"dgname"=>"What was the name of the last Doppleganger encountered?,text",
			"dgid"=>"What was the id of the last Doppleganger encountered?,text",
			"dgweapon"=>"What was the weapon from the last Doppleganger encountered?,text",
			"dgsex"=>"What was the sex of the last Doppleganger encountered?,text",
			"Doppleganger of Player,title",
			"name"=>"What is the player's Doppleganger's name?,text",
			"armor"=>"What is the player's Doppleganger's armor?,text",
			"weapon"=>"What is the player's Doppleganger's weapon?,text",
			"sex"=>"What is the player's Doppleganger's sex?,enum,0,Male,1,Female",
			"phrase1"=>"What is the player's Phrase 1?,text",
			"approve1"=>"Has Phrase 1 been approved?,enum,0,NA,1,Yes,2,To Submit,3,Pending,4,Declined",
			"phrase2"=>"What is the player's Phrase 2?,text",
			"approve2"=>"Has Phrase 2 been approved?,enum,0,NA,1,Yes,2,To Submit,3,Pending,4,Declined",
			"phrase3"=>"What is the player's Phrase 3?,text",
			"approve3"=>"Has Phrase 3 been approved?,enum,0,NA,1,Yes,2,To Submit,3,Pending,4,Declined",
		);
		$allprefse=unserialize(get_module_pref('allprefs',"doppleganger",$id));
		rawoutput("<form action='runmodule.php?module=doppleganger&op=superuser&userid=$id' method='POST'>");
		showform($form,$allprefse,true);
		$click = translate_inline("Save");
		rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=doppleganger&op=superuser&userid=$id");
	}
}
if ($op=="enter"){
	require_once("modules/doppleganger/doppleganger_enter.php");
	doppleganger_enter();
}
if ($op=="choose"){
	require_once("modules/doppleganger/doppleganger_choose.php");
	doppleganger_choose();
}
if ($op=="decline"){
	output("`c`b`@The Doppleganger`b`c`n");
	output("`#'It is within your rights to decline a battle cry. I understand.'");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['approve'.$op2]=4;
	set_module_pref('allprefs',serialize($allprefs));
	addnav("News","news.php");
	villagenav();
}
if ($op=="fwin"){
	require_once("modules/doppleganger/doppleganger_fwin.php");
	doppleganger_fwin();
}
if ($op=="changewin"){
	output("`c`b`@The Doppleganger`b`c`n");
	output("`#'On your next day you will be able to choose a new phrase.'");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['approve'.$op2]=2;
	set_module_pref('allprefs',serialize($allprefs));
	$session['user']['specialinc']="";
	addnav("To the Forest","forest.php");

}
if ($op=="exit"){
	$session['user']['specialinc']="";
	redirect("forest.php");
}
if ($op=="super1"){
	require_once("modules/doppleganger/doppleganger_super1.php");
	doppleganger_super1();
}
if ($op=="attack") {
	if ($id==0){
		$name=get_module_setting("name");
		$weapon=get_module_setting("weapon");
	}else{
		$allprefs=unserialize(get_module_pref('allprefs','doppleganger',$id));
		$name=$allprefs['name'];
		$weapon=$allprefs['weapon'];
	}
	$level=$session['user']['level']+1;
	if ($level>15) $targetlevel=15;
	$hitpoints=$session['user']['maxhitpoints'];
	$badguy = array(
		"creaturename"=>$name,
		"creatureweapon"=>$weapon,
		"creaturelevel"=>$level,
		"creatureattack"=>$session['user']['attack']*get_module_setting("dgatt"),
		"creaturedefense"=>$session['user']['defense']*get_module_setting("dgdef"),
		"creaturehealth"=>round($hitpoints*get_module_setting("dghp")),
		"diddamage"=>0
	);
	$session['user']['badguy']=createstring($badguy);
	$op="fight";
}
if ($op=="fight") {
	$battle=true;
	blocknav("runmodule.php?module=doppleganger&op=fight&auto=ten");
	blocknav("runmodule.php?module=doppleganger&op=fight&auto=full");
	$speak=e_rand(1,3);
	rawoutput("<big>");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$id=$allprefs['dgid'];
	$allprefsd=unserialize(get_module_pref('allprefs','doppleganger',$id));
	if ($speak==1 && $allprefsd['approve1']==1) output("`c`@The Doppleganger cries out `b'%s`@'`c`b`n",$allprefsd['phrase1']);
	if ($speak==2 && $allprefsd['approve2']==1) output("`c`@The Doppleganger gives a battle cry: `b'%s`@'`c`b`n",$allprefsd['phrase2']);
	if ($speak==3 && $allprefsd['approve3']==1) output("`c`@The Doppleganger attacks and yells `b'%s`@'`c`b`n",$allprefsd['phrase3']);
	rawoutput("</big>");
}
if ($battle){       
	include("battle.php");
	if ($victory){
		$allprefs=unserialize(get_module_pref('allprefs'));
		$sex=$allprefs['dgsex'];
		$name=$allprefs['dgname'];
		addnews("%s `@defeated %s`@ in the forest... or was it something else?",$session['user']['name'],$name);
		output("`n`@Before you are able to strike your final blow, the Doppleganger raises %s hand.",translate_inline($sex?"her":"his"));
		if ($allprefs['dgid']!=$session['user']['acctid'] && get_module_setting("dopyom")==1 && get_module_pref("user_stat","doppleganger",$allprefs['dgid'])==1){
			require_once("lib/systemmail.php");
			$subj = sprintf("`@%s `@Doppleganger Defeat",$name);
			$body = sprintf("`#I am writing to inform you that I have been defeated by `^%s`# while fighting in your guise. Perhaps your name does not inspire the fear I thought it would.`n`nSigned,`n`nThe Doppleganger",$session['user']['name']);
			systemmail($allprefs['dgid'],$subj,$body);
		}
		addnav("Continue","runmodule.php?module=doppleganger&op=fwin");
	}elseif($defeat){
		$allprefs=unserialize(get_module_pref('allprefs'));
		$name=$allprefs['dgname'];
		if ($allprefs['dgid']!=$session['user']['acctid'] && get_module_setting("dopyom")==1 && get_module_pref("user_stat","doppleganger",$allprefs['dgid'])==1){
			require_once("lib/systemmail.php");
			$subj = sprintf("`@%s `@Doppleganger Success",$name);
			$body = sprintf("`#I am writing to inform you that I have defeated `^%s`# while fighting in your guise.  Your name has been glorified once again.  Gloating may be in order.`n`nSigned,`n`nThe Doppleganger",$session['user']['name']);
			systemmail($allprefs['dgid'],$subj,$body);
		}
		require_once("lib/taunt.php");
		$taunt = select_taunt_array();
		$session['user']['gold']=0;
		$exploss = round($session['user']['experience']*get_module_setting("perexpl")/100);
		$session['user']['experience']-=$exploss;
		$rand=e_rand(1,2);
		$session['user']['specialinc']="";
		output("`b`4You lose `#%s experience`4.`b`n`n",$exploss);
		output("`^The Doppleganger takes all of your gold.`n`n");
		output("`#'You fought bravely, but");
		if (($rand==1 && get_module_setting("death")==2) || get_module_setting("death")==1){
			output("it's time for you to sleep a deep sleep.'");
			addnews("%s `@was killed by %s`@... or was it something else?`n%s",$session['user']['name'],$name,$taunt);
			addnav("Daily news","news.php");
			$session['user']['alive'] = false;
			$session['user']['hitpoints'] = 0;
		}else{
			output("you were not strong enough to win this battle.  This victory is mine, but I will let you learn from the lessons I have taught you.'");
			addnews("%s `@was defeated in battle by %s`@... or was it something else?`n%s",$session['user']['name'],$name,$taunt);		
			$session['user']['hitpoints'] = 1;
			addnav("Return to the Forest","forest.php");
		}
	}else{
		require_once("lib/fightnav.php");
		fightnav(true,false,"runmodule.php?module=doppleganger");
	}
}
page_footer();
?>