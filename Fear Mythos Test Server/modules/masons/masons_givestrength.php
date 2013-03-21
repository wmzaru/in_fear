<?php
function masons_givestrength(){
	global $session;
	output("`&You are guided to the `&M`)ason `&S`)pell `&R`)oom`&.`n`n");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$id = $allprefs['gifttoid'];
	$name = $allprefs['gifttoname'];
	if ($allprefs['gattackboost']==1) {
		output("`7'Unfortunately, I can only cast the `@Spell of Strength`7 on ANY other player once per `@Dragon Kill`7.");
		output("This means only one `&M`)ason`7 or Non-Mason per time.");
		output("I'm sorry, but you've already had me cast this spell.'");
		output("`n`n'Finding this out will not cost against your benefits though.'");
		masons_masonnav1();
		debuglog("tried to give an attack boost to another mason when that was already done once this dk.");
	}elseif ($id==$session['user']['acctid']){
		output("The resident `&M`)agic `&U`)ser`& starts to cast a `@Spell of Strength`& but suddenly collapses.");
		output("He looks up at you and shakes his head.");
		output("`n`n`7'Oh my, you cannot have this spell cast on yourself.");
		output("Unfortunately, it will take me a while to recover from this spell casting and therefore I will still have to count this for `&`b2 benefits`b`7.");
		output("In addition, I will not be able to cast this spell for you until you kill the `@Green Dragon`7 again.'`n`n");
		$allprefs['gattackboost']=1;
		set_module_pref('allprefs',serialize($allprefs));
		debuglog("tried to give themself an attack boost when that was already done once this dk.");
		masons_giftmasons();
		masons_masonbenefit1();
	}else{
		$op2 = httpget('op2');
		if ($op2==1) $chance=get_module_setting("masonchance");
		else $chance=get_module_setting("nonmasonchance");
		output("The resident `&M`)agic `&U`)ser`& attempts to cast a `@Spell of Strength`& to`& on %s`&.",$name);
		$allprefs['gattackboost']=1;
		set_module_pref('allprefs',serialize($allprefs));
		if ($chance>=e_rand(1,100)){
			output("`n`nFailure!`n`n%s`& won't receive any attack from your attempt.`n`n",$name);
			debuglog("tried to give attack to $name but the spell failed by the Mason Magic User.");
		}else{
			output("`n`n%s`& receives `bOne Attack`b`&.`n`n",$name);
			$subj = array("`&A Special Gift from T`)he `&M`)asons");
			$body = array("`&Dear %s`&,`n`nYou have received `bOne Extra Attack`b`& through an anonymous gift from one of the members of `&T`)he `&O`)rder`&.`n`nSincerely,`n`n `&T`)he `&S`)ecret `&O`)rder `&o`)f `&M`)asons",$name);
			require_once("lib/systemmail.php");
			systemmail($id,$subj,$body);
			$sql = "UPDATE ". db_prefix("accounts") . " SET attack=attack+1 WHERE acctid='$id'";
			db_query($sql);
			debuglog("gave 1 attack to $name as a Mason gift.");
		}
		masons_giftmasons();
		masons_masonbenefit1();
	}
}
?>