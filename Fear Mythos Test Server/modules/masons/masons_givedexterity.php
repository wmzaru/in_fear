<?php
function masons_givedexterity(){
	global $session;
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$id = $allprefs['gifttoid'];
	$name = $allprefs['gifttoname'];
	if ($allprefs['gdefenseboost']==1) {
		output("`7'Unfortunately, I can only cast the `2Spell of Dexterity`7 on ANY other player once per `@Dragon Kill`7.");
		output("This means only one `&M`)ason`7 or Non-Mason per time.");
		output("I'm sorry, but you've already had me cast this spell.'");
		output("`n`n'Finding this out will not cost against your Benefits though.'");
		masons_masonnav1();
		debuglog("tried to give a defense boost to another mason when that was already done once this dk.");
	}elseif ($id==$session['user']['acctid']){
		output("The resident `&M`)agic `&U`)ser`& starts to cast a `2Spell of Dexterity`& but suddenly collapses.");
		output("He looks up at you and shakes his head.");
		output("`n`n`7'Oh my, you cannot have this spell cast on yourself.");
		output("Unfortunately, it will take me a while to recover from this spell casting and therefore I will still have to count this for `b`&2 benefits`7`b.");
		output("In addition, I will not be able to cast this spell for you until you kill the `@Green Dragon`7 again.'`n`n");
		$allprefs['gdefenseboost']=1;
		set_module_pref('allprefs',serialize($allprefs));
		debuglog("tried to give themself a defense boost when that was already done once this dk.");
		masons_giftmasons();
		masons_masonbenefit1();
	}else{
		$op2 = httpget('op2');
		if ($op2==1) $chance=get_module_setting("masonchance");
		else $chance=get_module_setting("nonmasonchance");
		output("The resident `&M`)agic `&U`)ser`& attempts to cast a `2Spell of Dexterity`& to`& on %s`&.",$name);
		$allprefs['gdefenseboost']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if ($chance>=e_rand(1,100)){
			output("`n`nFailure!`n`n%s`& won't receive any defense from your attempt.`n`n",$name);
			debuglog("tried to give defense to $name but the spell failed by the Mason Magic User.");
		}else{
			output("`n`n%s`& receives `bOne Defense`b`&.`n`n",$name);
			$subj = array("`&A Special Gift from T`)he `&M`)asons");
			$body = array("`&Dear %s`&,`n`nYou have received `bOne Extra Defense`b`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
			require_once("lib/systemmail.php");
			systemmail($id,$subj,$body);
			$sql = "UPDATE ". db_prefix("accounts") . " SET defense=defense+1 WHERE acctid='$id'";
			db_query($sql);
			debuglog("gave 1 defense to $name as a Mason gift.");
		}
		masons_giftmasons();
		masons_masonbenefit1();
	}
}
?>