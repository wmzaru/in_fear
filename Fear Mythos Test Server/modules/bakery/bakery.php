<?php
	global $session;
	$op = httpget("op");
	page_header("Hara's Bakery");
	
	//the settings
	$eclair = get_module_setting("eclair");
	$danish = get_module_setting("danish");
	$croissant = get_module_setting("croissant");
	$tart = get_module_setting("tart");
	$strudel = get_module_setting("strudel");
	$dayold = get_module_setting("dayold");
	$cakecost = get_module_setting("cakecost");

	$allprefs=unserialize(get_module_pref('allprefs'));
	$caketoday = $allprefs['caketoday'];
	$pastrytoday = $allprefs['pastrytoday'];
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
	if ($subop!='edit'){
		$allprefse=unserialize(get_module_pref('allprefs',"bakery",$id));
		$allprefse['pastrytoday']= httppost('pastrytoday');
		$allprefse['caketoday']=httppost('caketoday');
		$allprefse['usedcake']=httppost('usedcake');
		$allprefse['jailvisittoday']=httppost('jailvisittoday');
		set_module_pref('allprefs',serialize($allprefse),'bakery',$id);
		output("Allprefs Updated`n");
		$subop="edit";
	}
	if ($subop=="edit"){
		require_once("lib/showform.php");
		$form = array(
			"Bakery,title",
			"pastrytoday"=>"Bought a pastry today,bool",
			"caketoday"=>"Bought the Cake with a File Today,bool",
			"usedcake"=>"Have they used the cake today?,bool",
			"jailvisittoday"=>"Been to see a prisoner today?,bool",
		);
		$allprefse=unserialize(get_module_pref('allprefs',"bakery",$id));
		rawoutput("<form action='runmodule.php?module=bakery&op=superuser&userid=$id' method='POST'>");
		showform($form,$allprefse,true);
		$click = translate_inline("Save");
		rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=bakery&op=superuser&userid=$id");
	}
}
if ($op=="enter") {
	if ($allprefs['jailvisittoday']==1){
		output("`2`nThe sheriff appears very annoyed. `#'You can only visit one prisoner a day. Come back tomorrow.'");
		addnav("V?(V) Return to Village","village.php");
	}else{
		addnav("Back to jail","runmodule.php?module=jail");
		output("`2`nWho would you like to visit?");
		output("<table border='0' cellpadding='3' cellspacing='0'><tr class='trhead'><td style=\"width:350px\">Name</td><td style=\"width:150px\">Level</td><td>&nbsp;</td></tr>",true);
		$accounts = db_prefix("accounts");
		$module_userprefs = db_prefix("module_userprefs");
		$sql = "SELECT $accounts.name AS name,$
		accounts.level AS level,$accounts.login AS login,$accounts.acctid AS acctid, $module_userprefs.userid FROM $module_userprefs INNER JOIN $accounts ON $accounts.acctid = $module_userprefs.userid WHERE $module_userprefs.setting = 'injail' AND $module_userprefs.value > 0 order by $accounts.level DESC";
		$result = db_query($sql) or die(db_error(LINK)); 
		for ($i=0;$i<db_num_rows($result);$i++){ 
			$row = db_fetch_assoc($result); 
			output("<tr class='".($i%2?"trlight":"trdark")."'><td>",true); 
			output("<a href=\"mail.php?op=write&to=".rawurlencode($row['login'])."\" target=\"_blank\" onClick=\"".popup("mail.php?op=write&to=".rawurlencode($row['login'])."").";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Write Mail' border='0'></a>",true); 
			output("".$row['name']."</a></td><td>`^".$row['level']."`7</td><td>[<a href='runmodule.php?module=bakery&op=visiting&player=".rawurlencode($row['acctid'])."'>Visit</a> ]</td></tr>",true);
			addnav("","bio.php?char=".rawurlencode($row['login'])."");
			addnav("","runmodule.php?module=bakery&op=visiting&player=".rawurlencode($row['acctid'])."");
		}
		output("</table>",true);
	}
}
if ($op=="visiting") {
	$allprefs['jailvisittoday']=1;
	set_module_pref('allprefs',serialize($allprefs));
	$allprefs=unserialize(get_module_pref('allprefs'));
	$player=httpget('player'); 
	$sql="SELECT name,level,dragonkills FROM ".db_prefix("accounts")." WHERE acctid =".$player;
	$result = db_query($sql) or die(db_error(LINK)); 
	$row = db_fetch_assoc($result);
	$playername=$row['name'];
	addnav("V?(V) Return to Village","village.php");
	if ($allprefs['caketoday']==1 && $allprefs['usedcake']==0){
		$allprefs['usedcake']=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("`n`2The sheriff eyes you and your cake a little suspiciously, but decides you look trustworthy enough.`n`n");
		switch(e_rand(1,5)){
			case 1:
				output("`#You slide the cake to your friend. However, your friend shows more ignorance than you ever imagined.`n`nThe jailbird calls over the sheriff to share the cake. Cake is shared all around. Then you see the sheriff wince as you hear a loud `icrunch`i.`n`nThe file that `@Hara `#baked into the cake is discovered`% (and the sheriff loses a tooth)`#! `n`n`$ In a whirlwind of justice you are tossed into jail!!!");
				set_module_pref("injail",1,"jail");
				addnav("To your cell","runmodule.php?module=jail");
				$subj = sprintf("`^You had a visitor!");
				$body = sprintf("`^%s `#stopped by and joined you in a jail cell.",$session['user']['name']);
				blocknav("village.php");
			break;
			case 2: case 3:
				output("`#With a wink, you slide your precious cake to your friend. Your friend is confused and awkwardly winks back to you. The sheriff comes up before you and before you have a chance to explain about the file in the cake, it's shared with all the cellmates. `n`n Somehow, the file gets eaten without being noticed. You shake your head and walk out figuring that some people need to stay in jail to learn a lesson anyway.");
				$subj = sprintf("`^You had a visitor!");
				$body = sprintf("`^%s `#stopped by and visited you in jail.",$session['user']['name']);
			break;
			case 4: case 5:
				output("`#You slide the cake to your friend and give a subtle wink. With a knowing nod, your friend takes the cake back to the cell.`n`n`$ Soon enough, you hear the sirens of a prison break! `# You're friend made it out thanks to you!");
				set_module_pref("injail",0,"jail",$player);
				$subj = sprintf("`^Lucky you!");
				$body = sprintf("`^%s `#has helped you escape out of jail!",$session['user']['name']);
			break;
		}
	}else{
		output("`n`#The sheriff leads you to your friend and you sit down for a short chat. You feel like you've been the good friend and helped alleviate some of the boredom of being in the slammer.`n`n You're escorted out of the jail and back to the street.");
		$subj = sprintf("`^You had a visitor!");
		$body = sprintf("`^%s`# stopped by and visited you in jail!",$session['user']['name']);
	}
	require_once("lib/systemmail.php");
	systemmail($player,$subj,$body);
}
if($op=="cake"){
	output("`n`@`c`bHara's `^Bakery`b`c`n");
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['caketoday']==1 && $allprefs['pastrytoday']==0){
		output("`%'Whoa there. You can only get one cake a day. In fact, I think it may be best if you didn't come back to my bakery at all today. Do you understand?'");
		$allprefs['pastrytoday']=1;
		set_module_pref('allprefs',serialize($allprefs));
		addnav("V?(V) Return to Village","village.php");
	}else{
		if ($session['user']['gold']<$cakecost){
			output("`%'You know, it's bad enough when people try to stiff me for the pastries. But this goes beyond the pale. Get out of my bakery!'`n`n`#Hanging your head in shame, you leave the store.");
			if ($session['bufflist']['cheapskate']==""){
				apply_buff('cheapskate',array(
					"name"=>"Cheapskate",
					"rounds"=>10,"wearoff"=>"`4You know you'll invest better from now on!",
					"atkmod"=>.95,
					"defmod"=>.95,
					"activate"=>"offense"
				));
			}
			addnav("V?(V) Return to Village","village.php");
		}else{
			$session['user']['gold']-=$cakecost;
			$allprefs['caketoday']=1;
			set_module_pref('allprefs',serialize($allprefs));
			output("`%'Well, this will take a little while to make. You just sit tight and I'll be back with your 'iron fortified' cake.`n`n`#You `@lose a forest fight`# waiting.`n`n");
			$session['user']['turns']--;
			output("`#Beaming with pride, `@Hara`# brings you the cake. `%'Well, you know what to do with it...'`n`n`#You stare at the freshly baked cake and know why this cost so much. You decide to use it wisely, knowing that it won't last for longer than a day.");
			addnav("Cake Options");
			addnav("Eat the Cake Now","runmodule.php?module=bakery&op=eatcake");
			addnav("Save it for Later","village.php");
		}
	}
}
if ($op=="eatcake") {
	output("`n`@`c`bHara's `^Bakery`b`c`n");
	output("`#This is the `ibest`i cake you've ever had and you gobble it down with lightning speed. Yes, this was well worth the money. For some reason, it has a little bit of a crunch when you eat it... but the sugar rush hits you and you feel the power!");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['usedcake']=1;
	set_module_pref('allprefs',serialize($allprefs));
	apply_buff('specialcake',array(
		"name"=>"Iron Fortified",
		"rounds"=>10,
		"wearoff"=>"`4The cake power fades!",
		"atkmod"=>1.2,
		"defmod"=>1.35,
		"activate"=>"offense"
	));
	addnav("V?(V) Return to Village","village.php");
}
if ($op=="" || $op=="food"){
	output("`n`@`c`bHara's `^Bakery`b`c`n");
	addnav("V?(V) Return to Village","village.php");
	$allprefs=unserialize(get_module_pref('allprefs'));
	if ($allprefs['caketoday']==1 && $allprefs['pastrytoday']==1 && $allprefs['usedcake']==0) {
		output("`@Hara `#looks at you with disbelief. `$'Why are you still carrying that cake around? You know what you have to do with it!'`n`n`#She pushes you out the door and back to the village.");
	}elseif ($allprefs['caketoday']==1 && $allprefs['pastrytoday']==1 && $allprefs['usedcake']==1) output("`$'No. I'm sorry. You've been here enough for one day. Come back tomorrow!'");
	elseif ($allprefs['pastrytoday']==1 && $allprefs['caketoday']==0) {
		output("`%'Well, you know I have to save some pastries for my other customers. But if you're still interested in getting a `i'special'`i cake, then I think I can help you.'");
		output("`n`n`%Special Cake for `^%s gold",$cakecost);
		addnav("Pastries");
		addnav("Special Cake","runmodule.php?module=bakery&op=cake");
	}elseif ($allprefs['pastrytoday']==0) {
		output("`#After a careful search you find the entrance to the tiny bakery and walk down several steps. Deftly avoiding hitting your head on the low ceiling, you enter into the simple little shop. `n`n At the counter stands one of the greatest pastry chefs in all the land. `@Hara `#is short with beautiful dark hair and wears an apron covered in flour.");
		output("She wipes her hands quickly before turning her attention to you. She's got a reputation for being a little tough so you watch your step. Her prices are a little steep, but you know it's worth it.`n`n");
		output("`%'Okay, what can I get you? The bakery's pretty stocked right now, so what would you like?'`n`n");
		output("`#You spend several minutes salivating in front of the pastries before making your choice.`n`n");
		output("`@Eclair for `^%s gold`n`@Danish for `^%s gold`n`@Croissant for `^%s gold`n`@Tart for `^%s gold`n`@Strudel for `^%s gold`n`@Day Old Pastry for `^%s gold`n`n`%Special Cake for `^%s gold",$eclair,$danish,$croissant,$tart,$strudel,$dayold,$cakecost);
		addnav("Pastries");
		addnav("Eclair","runmodule.php?module=bakery&op=eclair");
		addnav("Danish","runmodule.php?module=bakery&op=danish");
		addnav("Croissant","runmodule.php?module=bakery&op=croissant");
		addnav("Tart","runmodule.php?module=bakery&op=tart");
		addnav("Strudel","runmodule.php?module=bakery&op=strudel");
		addnav("O?Day Old Pastry","runmodule.php?module=bakery&op=dayold");
		addnav("Special Cake","runmodule.php?module=bakery&op=cake");
	}
}
if ($op=="eclair") {
	require_once("modules/bakery/bakery_func.php");
	bakery_purchase();
	if ($session['user']['gold']<$eclair) bakery_nogold();
	else{
		output("`%'Ah, the tasty eclair. It is, of course, one of the Choux pastries, which uses eggs. The high water content allows the pastry to rise when the water boils and thereby puffing it out.'`n`n");
		output("`#Barely listening to her description, you start to gobble down the tasty treat.`n`n");
		$session['user']['gold']-=$eclair;
		$eateclair=(e_rand(1,4));
		if ($eateclair==1){
			output("`#The pastry is all you thought it would be. You feel stronger but a little sluggish as it settles in your stomach");
			apply_buff('eclairstrength',array(
				"name"=>"Eclair Energy",
				"rounds"=>10,
				"wearoff"=>"`4The power of the eclair fades.",
				"atkmod"=>1.06,
				"defmod"=>0.96,
				"activate"=>"offense"
			));
		}elseif ($eateclair==2){
			output("`#The pastry is all you thought it would be. You feel so comfortable and in control, but you feel the energy in your muscles slow a little.");
			apply_buff('eclairdefense',array(
				"name"=>"Eclair Defense",
				"rounds"=>5,
				"wearoff"=>"`4The power of the eclair fades.",
				"atkmod"=>0.96,
				"defmod"=>1.06,
				"activate"=>"offense"
			));
		}elseif ($eateclair==3){
			output("`^'Chomp Chomp Chomp' `# You chew so loudly you don't hear `@Hara's`# warning that a piece of plaster from the ceiling is falling. It hits you on the head! `@Hara `#looks at you sheepishly and gives you a little extra back hoping you won't report this to the sheriff. Maybe next time you'll chew with your mouth closed.");
			$pain = round($session['user']['hitpoints']*0.1);
			$damage = e_rand(1, $pain);
			$session['user']['hitpoints']-=$damage;
			$session['user']['gold']+=$eclair*2;
		}else output("`#After taking a bite of the eclair you suddenly realize you wanted a danish. However, `@Hara`# doesn't have time for your shenanigans and pushes you out the door without giving you a refund.");
	}
}
if ($op=="danish") {
	require_once("modules/bakery/bakery_func.php");
	bakery_purchase();
	if ($session['user']['gold']<$danish) bakery_nogold();
	else{
		output("`%'So you like the Danish? It's ingredients are simple... flour, milk, eggs, and butter... but the best danishes are made by a chef that has studied Bonetti's Rolling Technique... which I have!'`n`n");
		output("`#Barely listening to her description, you quickly gobble down the tasty treat.`n`n");
		$session['user']['gold']-=$danish;
		$eatdanish=(e_rand(1,4));
		if ($eatdanish==1){
			output("`#Ah... the pleasant danish settles into your stomach with a happy little bounce. You feel Danish Power! You also realize you can take on another round of monsters in the forest!");
			apply_buff('danishstrength',array(
				"name"=>"Danish Power",
				"rounds"=>5,
				"wearoff"=>"`4The strength of the Danish leaves you.",
				"atkmod"=>1.05,
				"activate"=>"offense"
			));
			$session['user']['turns']++;
		}elseif ($eatdanish==2){
			output("`#Three bites and you're to the center of the Danish. But then it settles a little rough. In a strange paradox, you feel strong enough to fight more monsters but you won't be on your best game.");
			output("`n`nYou gain `@two forest fights`# but you feel kinda sluggish.");
			$session['user']['turns']+=2;
			apply_buff('danishslug',array(
				"name"=>"Danish Slugger",
				"rounds"=>12,
				"wearoff"=>"`4The Danish Depression Disappears.",
				"defmod"=>0.97,
				"activate"=>"offense"
			));
		}elseif ($eatdanish==3){
			output("`#The Danish is a powerful ally. However, it does come with a price. You feel stronger, but the extra danish hanging from your nose makes you less attractive and you `&Lose One Charm`#.");
			$session['user']['charm']--;
			apply_buff('danishpower',array(
				"name"=>"Danish Power",
				"rounds"=>20,
				"wearoff"=>"`4You will never forget that danish.",
				"atkmod"=>1.1,
				"defmod"=>1.1,
				"activate"=>"offense"
			));
		}else{
			output("`#You try to pay with a pocket full of change. `@Hara`# doesn't tolerate this and you are dismissed from the bakery for the day.");
			output("She doesn't notice that you stole a danish anyway!`n`n But your guilt gets to you as you leave the store and you share half of it with a little raggy boy.");
			output("You feel Danish Power and the strength to go `@back to the forest`#!");
			$session['user']['gold']+=$danish;
			$session['user']['turns']++;
			apply_buff('danishpower',array(
				"name"=>"Danish Power",
				"rounds"=>10,
				"wearoff"=>"`4You will never forget that danish.",
				"atkmod"=>1.1,
				"activate"=>"offense"
			));
		}
	}
}
if ($op=="croissant") {
	require_once("modules/bakery/bakery_func.php");
	bakery_purchase();
	if ($session['user']['gold']<$croissant) bakery_nogold(); 
	else{
		output("`%'The 'croissant' is a pastry from the Puff Pastry Family. The difference between a Puff Pastry and a Choux Pastry is that you don't add eggs to the Puff.'`n`n");
		output("`#Barely listening to her description, you quickly gobble down the tasty treat.`n`n");
		$session['user']['gold']-=$danish;
		$eatecroissant=(e_rand(1,4));
		if ($eatecroissant==1){
			output("`#It is light! It is flaky! You are light! You are flaky! You feel stronger, but your flakiness makes you less attractive.`n`n");
			output("You `&gain an attack`# but you `^lose charm`#.");
			$session['user']['charm']--;
			$session['user']['attack']++;
		}elseif ($eatecroissant==2){
			output("`#You turn to leave chomping happily on the croissant. You hear a loud 'crunch' and realize that you just bit into something hard. You look down to see a tooth. Oh no! It's your tooth. You look at the croissant and see a beautiful gem. You figure it was a fair trade. `n`n You `%gain a gem and`# lose a tooth (oh, and you `^lose charm`# because of losing the tooth).");
			$session['user']['gems']++;
			$session['user']['charm']--;
		}elseif ($eatecroissant==3){
			output("`#You eat the croissant with the most elegant flare. You appear to be VERY charming. But your narcissism makes you forget you're training. You `^gain 3 charm`# but `&lose a defense point`#.");
			$session['user']['charm']+=3;
			$session['user']['defense']--;
		}else{
			output("You are amazing! Never has someone eaten a croissant without dropping a crumb! In fact, this is worthy of writing home about! You'll be in the news!`n`n Your `^charm increases`#! The adrenaline from the act gives you `@one more forest fight`#.");
			$session['user']['charm']+=3;
			$session['user']['turns']++;
			addnews("%s `#ate a croissant without dropping a crumb. Is this news or what?!?!",$session['user']['name']);
		}
	}
}
if ($op=="tart") {
	require_once("modules/bakery/bakery_func.php");
	bakery_purchase();	
	if ($session['user']['gold']<$tart) bakery_nogold();	
	else{
		output("`%'The Tart is a Shortcrust Pastry. It doesn't puff up because it doesn't have a rising agent.'`n`n");
		output("`#Barely listening to her description, you quickly gobble down the tasty treat.`n`n");
		$session['user']['gold']-=$tart;
		$eattart=(e_rand(1,4));
		if ($eattart==1){
			output("Is it ironic that the tart was too tart? So tart that you feel weak all over? You `@lose a forest fight`#, and`& lose a defense`#.");
			$session['user']['turns']--;
			$session['user']['defense']--;
		}elseif ($eattart==2){
			output("`#Can there be a more perfect tart? You think not!`n`n You `&gain an attack`#, `&gain a defense`#, and `@gain 2 forest fights`#!");
			$session['user']['turns']+=2;
			$session['user']['attack']++;
			$session['user']['defense']++;
		}elseif ($eattart==3){
			output("`#You can't believe you ate the whole thing. You feel like you wasted your gold. Oh well.");
		}else{
			output("You can't believe the tart power! Go Tart Go!");
			apply_buff('goodtart',array(
				"name"=>"Super Tart",
				"rounds"=>8,
				"wearoff"=>"`4You aren't as Tarty anymore!",
				"atkmod"=>1.2,
				"defmod"=>1.2,
				"activate"=>"offense"
			));
		}
	}
}
if ($op=="strudel") {
	require_once("modules/bakery/bakery_func.php");
	bakery_purchase();
	if ($session['user']['gold']<$strudel) bakery_nogold(); 
	else{
		output("`%'Strudels are very elastic, low in fat, low in sugar. Some goblins have tried to string 500 together to make a trampoline. They failed.'`n`n");
		output("`#Barely listening to her description, you quickly gobble down the tasty treat.`n`n");
		$session['user']['gold']-=$strudel;
		$eattart=(e_rand(1,4));
		if ($eatstrudel==1){
			output("`#It was sooooo good! In fact, you realize you can't live without another pastry. You buy a disguise and plan to sneak back in for another later today. In addition, you `@gain a forest fight`#!");
			$allprefs=unserialize(get_module_pref('allprefs'));
			$allprefs['pastrytoday']=0;
			set_module_pref('allprefs',serialize($allprefs));
			$session['user']['turns']++;
		}elseif ($eatstrudel==2){
			output("`#After you take a bite, you suddenly realize that you thought strudel was something completely different than what it is. You stop eating it, go out onto the street, and sell it for twice the going price! All that for a partially eaten strudel! Did someone see you doing it?!?");
			$session['user']['gold']+=$strudel*2;
			addnews("%s was seen selling half eaten strudel in the village.",$session['user']['name']);
			apply_buff('paranoia',array(
				"name"=>"Paranoia",
				"rounds"=>5,
				"wearoff"=>"`4They stop watching everything you do.",
				"atkmod"=>1.3,
				"defmod"=>.9,
				"activate"=>"offense"
			));
		}elseif ($eatstrudel==3){
			output("`#You can't believe you ate the whole thing. But hey, you eat a seashell and you feel better!");
			apply_buff('seashell',array(
				"name"=>"Seashell Power",
				"rounds"=>10,
				"wearoff"=>"No more power of the shell!",
				"atkmod"=>1.05,
				"defmod"=>1.07,
				"activate"=>"offense"
			));
		}else{
			output("`#It's strudel. That's all it was. It was pretty darn good though.`$`n`n You get distracted. You choke. Oh my oh my. You cough out the huge chunk of strudel... but my that was a close one!`n`n `#You only have 1 hitpoint left!");
			$session['user']['hitpoints']=1;
		}
	}
}
if ($op=="dayold") {
	require_once("modules/bakery/bakery_func.php");
	bakery_purchase();
	if ($session['user']['gold']<$dayold){
		output("`%'Wow. Too poor for day old pastry?'`n`n`@Hara's`# heart bleeds for you and she digs deep into her pocket. She hands you `%a gem `#and a day old Strudel. `%'Good luck!'");
		$session['user']['gems']++;
		apply_buff('oldstrudel',array(
			"name"=>"Old Strudel",
			"rounds"=>5,
			"wearoff"=>"`4No more Strudel Power for you!",
			"atkmod"=>1.05,
			"activate"=>"offense"
		));
	}else{
		output("`#You're handed a day old pastry. You realize you should get a job and stop being so darn cheap.`n`n");
		$session['user']['gold']-=$dayold;
		$eatdayold=(e_rand(1,5));
		if ($eatdayold==1){
			output("`#It's an old eclair. You feel a little stronger and plan to `@go to the forest`# a couple more times.");
			$session['user']['turns']+=2;
		}elseif ($eatdayold==2){
			output("`#You yell out `Q'Hara! Get me a Danish'`#. She brings you a day-old danish. You find a hair in it. It grosses you out so bad that you `@lose a forest fight`#.");
			$session['user']['turns']--;
		}elseif ($eatdayold==3){
			output("`#It's a day old croissant. You look at for a little while and decide not to eat it. You walk out of the shop and pretend it's a moon. Small children gather around you and laugh. `n`n You are `^more charming `#because of your playful antics.");
			$session['user']['charm']++;
		}elseif ($eatdayold==4){
			output("`#It's a day old tart. You can't believe you ate the whole thing. You feel weakened.");
			apply_buff('oldtart',array(
				"name"=>"Foul Tart",
				"rounds"=>8,
				"wearoff"=>"`4Begone Foul Tart!",
				"atkmod"=>.8,
				"defmod"=>.8,
				"activate"=>"offense"
			));
		}else{
			output("`#You taste the day old Lamington. You ask `@Hara`# to explain what a Lamington is and she says`% 'It's a soft sponge cake covered in chocolate and sprinkled with coconut'`#. `n`n It's amazing. It's better than you dreamed. This is the best thing you've eaten in a long time.");
			apply_buff('oldlamington',array(
				"name"=>"Chocolate Charge",
				"rounds"=>10,
				"wearoff"=>"`4The taste of chocolate fades!",
				"atkmod"=>1.7,
				"defmod"=>1.7,
				"activate"=>"offense"
			));
		}
	}
}
page_footer();
?>