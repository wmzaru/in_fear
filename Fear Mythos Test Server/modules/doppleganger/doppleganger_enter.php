<?php
function doppleganger_enter(){
	page_header("An Encounter");
	global $session;
	$session['user']['specialinc']="doppleganger";
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['encountered']=1;
	set_module_pref('allprefs',serialize($allprefs));
	$allprefs=unserialize(get_module_pref('allprefs'));
	$sql = "SELECT name, acctid FROM ".db_prefix("accounts")." INNER JOIN ".db_prefix("module_userprefs")." ON acctid=userid WHERE modulename='doppleganger' AND setting='dopplename' AND value=1 ORDER BY rand(".e_rand().") LIMIT 1";
	$res = db_query($sql);
	$row = db_fetch_assoc($res);
	$name = $row['name'];
	$id = $row['acctid'];
	if ($id==0){
		$armor=get_module_setting("armor");
		$name=get_module_setting("name");
		$weapon=get_module_setting("weapon");
		$sex=get_module_setting("sex");
	}else{
		$allprefso=unserialize(get_module_pref('allprefs','doppleganger',$id));
		$armor=$allprefso['armor'];
		$name=$allprefso['name'];
		$weapon=$allprefso['weapon'];
		$sex=$allprefso['sex'];
	}
	$allprefs['dgid']=$id;
	$allprefs['dgname']=$name;
	$allprefs['dgweapon']=$weapon;
	$allprefs['dgsex']=$sex;
	output("`n`@You enter a clearing and");
	if ($id==$session['user']['acctid']){
		output("see yourself standing several feet away! How is this possible?");
		output("You notice that %s is wearing `&%s`@ and is brandishing a `&%s`@.",translate_inline($sex?"she":"he"),$armor,$weapon);
		output("`n`nSlowly you come to realize that this is the Doppleganger! You prepare your `&%s`@ and get ready for a great duel.",$session['user']['weapon']);
	}else{
		output("notice someone standing several feet away. You recognize that it's `^%s`@. %s is brandishing a `&%s`@ and wearing `&%s`@.",$name,translate_inline($sex?"She":"He"),$weapon,$armor);
		output("`n`n%s looks you over. `#'It is time to see who is the greater warrior.  Prepare for battle!'",translate_inline($sex?"She":"He"));
	}
	addnav(array("Fight `^%s", $name),"runmodule.php?module=doppleganger&op=attack&id=$id");
	set_module_pref('allprefs',serialize($allprefs));
}
?>