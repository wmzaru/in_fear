<?php
function deckofmanythings_getmoduleinfo(){
	$info = array(
		"name"=>"Deck of Many Things",
		"version"=>"5.2",
		"author"=>"DaveS",
		"category"=>"Forest Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=184",
		"settings"=>array(
			"Deck of Many Things - Settings,title",
			"dksneeeded"=>"How many dks needed before encountering this forest special?,int|0",
			"usepics"=>"Use images?,bool|1",
			"gypsycharm"=>"How charming is the gypsy?,int|0",
			"penavt"=>"How many turns to lose for avoiding the gypsy?,range,0,4,1|2",
			"penavg"=>"How much gold to lose for avoiding the gypsy?,enum,0,None,1,100 Gold,2,500 Gold,3,1000 Gold,4,10% of Gold,5,25% of Gold,6,50% of Gold,7,100% of Gold|0",
			"penavge"=>"How many gems to lose for avoiding the gypsy?,range,0,4,1|0",
			"givepermhp"=>"Allow Permanent hps to be given or lost?,bool|1",
			"deckseduction"=>"Allow player to sleep with Foocubus?,bool|1",
			"Nothing graphic but may not be acceptable on a G-Rated site,note",
		),
		"prefs"=>array(
			"Deck of Many Things - Preferences,title",
			"Note: Please edit with caution. Consider using the Allprefs Editor instead.,note",
			"allprefs"=>"Preferences for Deck of Many Things,textarea|",
		),
	);
	return $info;
}
function deckofmanythings_chance() {
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs','deckofmanythings',$session['user']['acctid']));
	if ($allprefs['deckcheck']==1 || $session['user']['dragonkills']<get_module_setting('dksneeeded',"deckofmanythings",$session['user']['acctid'])) return 0;
	else return 100;
}
function deckofmanythings_install(){
	module_addeventhook("forest","require_once(\"modules/deckofmanythings.php\"); 
	return deckofmanythings_chance();");
	module_addhook("newday");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	return true;
}
function deckofmanythings_uninstall(){
	return true;
}
function deckofmanythings_dohook($hookname,$args){
	global $session;
	$id=httpget('userid');
	switch ($hookname) {
		case "newday":
			$allprefs=unserialize(get_module_pref('allprefs'));
			$allprefs['deckcheck']=0;
			if ($allprefs['deckpain']>1) {
				$allprefs['deckpain']=$allprefs['deckpain']-1;
				output("`n`\$You wake to excruciating pain with only `bone hitpoint`b because of the Deck of Many Things.`n");
				$session['user']['hitpoints']=1;
			}elseif ($allprefs['deckpain']==1) {
				$allprefs['deckpain']=0;
				output("`n`\$The pain from the Deck of Many Things finally subsides.`n");
			}
			set_module_pref('allprefs',serialize($allprefs));	
		break;
		case "allprefs": case "allprefnavs":
			if ($session['user']['superuser'] & SU_EDIT_USERS){
				addnav("Forest Specials");
				addnav("Deck of Many Things","runmodule.php?module=deckofmanythings&op=superuser&subop=edit&userid=$id");
			}
		break;
	}
	return $args;
}
function deckofmanythings_runevent($type) {
	global $session;
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['deckcheck']=1;
	set_module_pref('allprefs',serialize($allprefs));
	output("`#`nWhile slashing through the `@Forest`# in a desperate attempt to protect the kingdom, your %s`# stops inches away from chopping the head off of an `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^a`%n`#.`n`nLuckily, you didn't hit her, because everyone knows the penalty for accidentally killing `%o`^l`%d `%g`^y`%p`^s`%y `%w`^o`%m`^e`%n`#  is usually some awful curse or something.`n`n",$session['user']['weapon']);
	output("`%'You're lucky you didn't hit me,'`# she says. `%'Because I would have cast an awful curse on you if you had.'`#`n`n(See! I tell you, these old fables are true!)`n`n");
	output("She looks at you and pokes you with her walking stick. `%'Quit daydreaming! I got a proposition for you!'`n`n`#Part of you starts to shiver at the thought of being propositioned by the old bat, so you stare at her and let her continue.`n`n");
	output("She pulls out a very beautifully ornate `\$Deck `^of `\$Cards`#. `%'I will sell you the opportunity to draw a card from my magic deck.  Who knows what benefits you will enjoy.  Or perhaps what curses will consume you.' `#She shrugs.`% 'I don't care either way.  Just give me");
	if ($session['user']['gold']>=400){
		output("`^400 gold`% and");
		addnav("Give her the `^Gold","runmodule.php?module=deckofmanythings&op=deckplaygold");
	}elseif ($session['user']['gems']>=1){
		output("`bOne Gem`b and");
		addnav("Give her the `%Gem","runmodule.php?module=deckofmanythings&op=deckplaygem");
	}else{
		output("`&two of your charm points`%. You see, I have a special potion that will take `&two of your charm`% and give it to me so I will look even MORE beautiful!'`n`n`#You consider how much she could benefit from a little beautification project when she pokes you with her stick again.`n`n`%'If you agree,");
		addnav("Give her Your Charm","runmodule.php?module=deckofmanythings&op=deckplaycharm");
	}
	output("I'll let you pick a card.'`n`n`#What would you like to do?");
	addnav("Fight the `%O`^l`%d G`^y`%p`^s`%y`#","runmodule.php?module=deckofmanythings&op=gypsyattack");
	addnav("Slowly Back Away","runmodule.php?module=deckofmanythings&op=exitstageleft");
}
function deckofmanythings_run(){
	include("modules/deckofmanythings/deckofmanythings.php");
}
?>