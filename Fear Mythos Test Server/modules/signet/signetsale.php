<?php
global $session, $REQUEST_URI;
$op = httpget('op');
$page = httpget('page');
page_header("Unique Map Sales");
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
	$allprefse=unserialize(get_module_pref('allprefs',"signetsale",$id));
	if ($allprefse['completednum']=="") $allprefse['completednum']= 0;
	if ($allprefse['dksince']=="") $allprefse['dksince']= 0;
	if ($allprefse['hoftemp']=="") $allprefse['hoftemp']= 0;
	if ($allprefse['paidturna']=="") $allprefse['paidturna']= 0;
	if ($allprefse['']=="") $allprefse['paidgolda']= 0;
	if ($allprefse['']=="") $allprefse['paidgema']= 0;
	if ($allprefse['']=="") $allprefse['paidturne']= 0;
	if ($allprefse['']=="") $allprefse['paidgolde']= 0;
	if ($allprefse['']=="") $allprefse['paidgeme']= 0;
	if ($allprefse['']=="") $allprefse['paidturnw']= 0;
	if ($allprefse['']=="") $allprefse['paidgoldw']= 0;
	if ($allprefse['']=="") $allprefse['paidgemw']= 0;
	if ($allprefse['']=="") $allprefse['paidturnf']= 0;
	if ($allprefse['']=="") $allprefse['paidgoldf']= 0;
	if ($allprefse['']=="") $allprefse['paidgemf']= 0;
	if ($allprefse['']=="") $allprefse['paidturnm']= 0;
	if ($allprefse['']=="") $allprefse['paidgoldm']= 0;
	if ($allprefse['']=="") $allprefse['paidgemm']= 0;
	set_module_pref('allprefs',serialize($allprefse),'signetsale',$id);
	if ($subop!='edit'){
		$allprefse=unserialize(get_module_pref('allprefs',"signetsale",$id));
		$allprefse['completednum']= httppost('completednum');
		$allprefse['dksince']= httppost('dksince');
		$allprefse['nodkopen']= httppost('nodkopen');
		$allprefse['scroll1']= httppost('scroll1');
		$allprefse['hoftemp']= httppost('hoftemp');
		$allprefse['incomplete']= httppost('incomplete');
		$allprefse['dkopena']= httppost('dkopena');
		$allprefse['paidturna']= httppost('paidturna');
		$allprefse['paidgolda']= httppost('paidgolda');
		$allprefse['paidgema']= httppost('paidgema');
		$allprefse['airsigmap']= httppost('airsigmap');
		$allprefse['dkopene']= httppost('dkopene');
		$allprefse['paidturne']= httppost('paidturne');
		$allprefse['paidgolde']= httppost('paidgolde');
		$allprefse['paidgeme']= httppost('paidgeme');
		$allprefse['earthsigmap']= httppost('earthsigmap');
		$allprefse['dkopenw']= httppost('dkopenw');
		$allprefse['paidturnw']= httppost('paidturnw');
		$allprefse['paidgoldw']= httppost('paidgoldw');
		$allprefse['paidgemw']= httppost('paidgemw');
		$allprefse['watersigmap']= httppost('watersigmap');
		$allprefse['dkopenf']= httppost('dkopenf');
		$allprefse['paidturnf']= httppost('paidturnf');
		$allprefse['paidgoldf']= httppost('paidgoldf');
		$allprefse['paidgemf']= httppost('paidgemf');
		$allprefse['firesigmap']= httppost('firesigmap');
		$allprefse['dkopenm']= httppost('dkopenm');
		$allprefse['paidturnm']= httppost('paidturnm');
		$allprefse['paidgoldm']= httppost('paidgoldm');
		$allprefse['paidgemm']= httppost('paidgemm');
		$allprefse['finalsigmap']= httppost('finalsigmap');
		set_module_pref('allprefs',serialize($allprefse),'signetsale',$id);
		output("Allprefs Updated`n");
		$subop="edit";
	}
	if ($subop=="edit"){
		require_once("lib/showform.php");
		$form = array(
			"Signet Sale,title",
			"completednum"=>"How many times has the player completed the Signet Series?,int",
			"dksince"=>"How many dks has it been since player last completed the Signet Series?,int",
			"nodkopen"=>"Heard opening offer but not at dk high enough to purchase a map?,bool",
			"scroll1"=>"Given Scroll 1?,bool",
			"hoftemp"=>"Calculation field for status hof?,text",
			"incomplete"=>"Has player received a map but the next dungeon is not installed?,bool",
			"Air Signet Dungeon Sales,title",
			"dkopena"=>"`3Heard opening offer & dk high enough to purchase Air map?,bool",
			"paidturna"=>"`3How many `@turns`3 have been given up for the Air map?,int",
			"paidgolda"=>"`3How much `^gold`3 has been paid for the Air map?,int",
			"paidgema"=>"`3How many `%gems`3 have been paid for Air map?,int",
			"airsigmap"=>"`3Purchased Air Dungeon Map?,bool",
			"Earth Signet Dungeon Sales,title",
			"dkopene"=>"`QHeard opening offer & dk high enough & completed `3Air map`Q?,bool",
			"paidturne"=>"`QHow many `@turns`Q have been given up for the Earth map?,int",
			"paidgolde"=>"`QHow much `^gold`Q has been paid for the Earth map?,int",
			"paidgeme"=>"`QHow many `%gems`Q have been paid for the Earth map?,int",
			"earthsigmap"=>"`QPurchased Earth Dungeon Map?,bool",
			"Water Signet Dungeon Sales,title",
			"dkopenw"=>"`!Heard opening offer & dk high enough & completed `QEarth map`!?,bool",
			"paidturnw"=>"`!How many `@turns`! have been given up for the Water map?,int",
			"paidgoldw"=>"`!How much `^gold`! has been paid for the Water map?,int",
			"paidgemw"=>"`!How many `%gems`! have been paid for the Water map?,int",
			"watersigmap"=>"`!Purchased Water Dungeon Map?,bool",
			"Fire Signet Dungeon Sales,title",
			"dkopenf"=>"`\$Heard opening offer & dk high enough & completed `!Water map`\$?,bool",
			"paidturnf"=>"`\$How many `@turns`$ have been given up for the Fire map?,int",
			"paidgoldf"=>"`\$How much `^gold`$ has been paid for the Fire map?,int",
			"paidgemf"=>"`\$How many `%gems`$ have been paid for the Fire map?,int",
			"firesigmap"=>"`\$Purchased Fire Dungeon Map?,bool",
			"Final Signet Dungeon Sales,title",
			"dkopenm"=>"`%Heard opening offer & dk high enough & completed `\$Fire map`%?,bool",
			"paidturnm"=>"`%How many `@turns`% have been given up for the Final map?,int",
			"paidgoldm"=>"`%How much `^gold`% has been paid for the Final map?,int",
			"paidgemm"=>"`%How many gems have been paid for the Final map?,int",
			"finalsigmap"=>"`%Purchased Final Dungeon Map?,bool",
		);
		$allprefse=unserialize(get_module_pref('allprefs',"signetsale",$id));
		rawoutput("<form action='runmodule.php?module=signetsale&op=superuser&userid=$id' method='POST'>");
		showform($form,$allprefse,true);
		$click = translate_inline("Save");
		rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=signetsale&op=superuser&userid=$id");
	}
}
if ($op != 'hof' && $op != 'hof2' && $op != 'signetnotes' && $op!="superuser"){
	villagenav();
	if (is_module_active("mapmaker") && get_module_setting("mapmaker")==1) addnav("Look at Other Maps","runmodule.php?module=mapmaker");
	if (is_module_active("cartographer") && get_module_setting("mapmaker")==1) addnav("Look at Other Maps","runmodule.php?module=cartographer");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefsd1=unserialize(get_module_pref('allprefs','signetd1'));
	$allprefsd2=unserialize(get_module_pref('allprefs','signetd2'));
	$allprefsd3=unserialize(get_module_pref('allprefs','signetd3'));
	$allprefsd4=unserialize(get_module_pref('allprefs','signetd4'));
	$allprefsd5=unserialize(get_module_pref('allprefs','signetd5'));
	$current=0;
	if ($allprefs['sigmap1']==0){
		$current=1;
		$map=$allprefs['sigmap1'];
		$costturn=get_module_setting("costturn1");
		$paidturn=$allprefs['paidturn1'];
		$costgold=get_module_setting("costgold1");
		$paidgold=$allprefs['paidgold1'];
		$costgem=get_module_setting("costgem1");
		$paidgem=$allprefs['paidgem1'];
	}elseif ($allprefs['sigmap2']==0 && $allprefsd1["airsignet"]==1){
		$current=2;
		$map=$allprefs['sigmap2'];
		$costturn=get_module_setting("costturn2");
		$paidturn=$allprefs['paidturn2'];
		$costgold=get_module_setting("costgold2");
		$paidgold=$allprefs['paidgold2'];
		$costgem=get_module_setting("costgem2");
		$paidgem=$allprefs['paidgem2'];
	}elseif ($allprefs['sigmap3']==0 && $allprefsd2["earthsignet"]==1){
		$current=3;
		$map=$allprefs['sigmap3'];
		$costturn=get_module_setting("costturn3");
		$paidturn=$allprefs['paidturn3'];
		$costgold=get_module_setting("costgold3");
		$paidgold=$allprefs['paidgold3'];
		$costgem=get_module_setting("costgem3");
		$paidgem=$allprefs['paidgem3'];
	}elseif ($allprefs['sigmap4']==0 && $allprefsd3["watersignet"]==1){
		$current=4;
		$map=$allprefs['sigmap4'];
		$costturn=get_module_setting("costturn4");
		$paidturn=$allprefs['paidturn4'];	
		$costgold=get_module_setting("costgold4");
		$paidgold=$allprefs['paidgold4'];
		$costgem=get_module_setting("costgem4");
		$paidgem=$allprefs['paidgem4'];
	}elseif ($allprefs['sigmap5']==0 && $allprefsd4["firesignet"]==1){
		$current=5;
		$map=$allprefs['sigmap5'];
		$costturn=get_module_setting("costturn5");
		$paidturn=$allprefs['paidturn5'];
		$costgold=get_module_setting("costgold5");
		$paidgold=$allprefs['paidgold5'];
		$costgem=get_module_setting("costgem5");
		$paidgem=$allprefs['paidgem5'];
	}
	$dks=$session['user']['dragonkills'];
	$dksincewin=get_module_setting("dksincewin");
	if ($allprefs['completednum']==0) $dksincewin=0;
	$cn=get_module_setting("dkfinal")*$allprefs['completednum']+$dksincewin;
	//I know this next line is a hack but it works if you don't want players to replay the series
	if ($dksincewin<0) $cn=1000000;
	if ($costturn<0) $costturn=0;
	$topayturn=$costturn-$paidturn;
	if ($costgold<0) $costgold=0;
	$topaygold=$costgold-$paidgold;
	if ($costgem<0) $costgem=0;
	$topaygem=$costgem-$paidgem;
	$dungeonnames=array("","Aria Dungeon","Aarde Temple","Wasser's Castle","Fiamma's Castle","Mierscri's Lair");
}
if ($op=="enter"){
	//Player is in process of purchasing their map
	if (($current==1 && $allprefs['dkopen1']==1) || ($current==2 && $allprefs['dkopen2']==1) ||($current==3 && $allprefs['dkopen3']==1) ||($current==4 && $allprefs['dkopen4']==1) ||($current==5 && $allprefs['dkopen5']==1)){
		redirect("runmodule.php?module=signetsale&op=sales");
	//Player completed last map but not enough dks to get the next map
	}elseif (($current==2 && $allprefs['dkopen2']==0 && $dks<(get_module_setting("dk2")+$cn)) ||($current==3 && $allprefs['dkopen3']==0 && $dks<(get_module_setting("dk3")+$cn)) ||($current==4 && $allprefs['dkopen4']==0 && $dks<(get_module_setting("dk4")+$cn)) ||($current==5 && $allprefs['dkopen5']==0 && ($dks<get_module_setting("dk5")+$cn))){
		output("`#'I see you've completed `&%s`#. Unfortunately, I do not think you have the strength to complete the next map.'`n`n",$dungeonnames[$current-1]);
		output("`#'Please check back after you've killed the `@Green Dragon`# again.'");
	//Player completed last map and now for first time strong enough to get the next map
	}elseif (($current==2 && $allprefs['dkopen2']==0 && $dks>=(get_module_setting("dk2")+$cn)) ||($current==3 && $allprefs['dkopen3']==0 && $dks>=(get_module_setting("dk3")+$cn)) ||($current==4 && $allprefs['dkopen4']==0 && $dks>=(get_module_setting("dk4")+$cn)) ||($current==5 && $allprefs['dkopen5']==0 && $dks>=(get_module_setting("dk5")+$cn))){
		output("`#'Congratulations on completing `&%s`#.  I see you've grown a bit since we last met.  Very well, I think I may have another map that will suit your fancy.'",$dungeonnames[$current-1]);
		addnav("Continue","runmodule.php?module=signetsale&op=sales");
		if ($current==2){
			$allprefs['dkopen2']=1;
			set_module_pref("hoftemp",1900+$allprefs['completednum']);
		}elseif ($current==3){
			$allprefs['dkopen3']=1;
			set_module_pref("hoftemp",2900+$allprefs['completednum']);
		}elseif ($current==4){
			$allprefs['dkopen4']=1;
			set_module_pref("hoftemp",3900+$allprefs['completednum']);
		}elseif ($current==5){
			$allprefs['dkopen5']=1;
			set_module_pref("hoftemp",4900+$allprefs['completednum']);
		}
	//Player coming back but still not enough dks to get the first map
	}elseif($allprefs['nodkopen']==1 && $dks<get_module_setting("dk1")+$cn){
		output("`#'Listen, I don't need you coming around here belittling my products and wasting my time.  I have nothing to offer you right now.");
		output("Come back when you've shown the world that you've got some real talent.'");
	//Player coming back and now for first time strong enough to get the first map
	}elseif($allprefs['nodkopen']==1 && $dks>=get_module_setting("dk1")+$cn){
		output("`#'Ah, I see you've grown a bit since the last time I saw you.");
		output("Well, perhaps I have something a great warrior such as yourself may be interested in.`n`n");
		output("'You see, I have some maps... very unique maps.  They will lead you to new adventures that perhaps you have never dreamed of.'");
		output("`n`n`0You notice that there's a change in the man's voice. This isn't talk of the drivel that's on display.  There may actually be something to his offer.");
		output("`#'Come around to the back of my cart and we can talk more privately.'");
		set_module_pref("hoftemp",900+$allprefs['completednum']);
		$allprefs['dkopen1']=1;
		$allprefs['nodkopen']=0;
		addnav("Continue","runmodule.php?module=signetsale&op=newsales");
	//These statements are needed if all the dungeons haven't been installed/written yet
	}elseif($allprefs['incomplete']==1){
		if ($allprefs['sigmap5']==1){
			if (is_module_active("signetd5")){
				output("`#'Ah, welcome back.  I'm sorry about the delay.  It appears that `&Mierscri's Lair`# is located in %s.",get_module_setting("finalmaploc","signetd5"));
				output("`n`n`#Once you've finished it, please come back and visit.'");
				$allprefs['incomplete']=0;
			}else output("`#'I'm sorry, `&Mierscri's Lair`# is not quite ready for you to explore.  Please come back soon.'");
		}elseif ($allprefs['sigmap4']==1){
			if (is_module_active("signetd4")){
				output("`#'Ah, welcome back.  I'm sorry about the delay.  It appears that `&Fiamma's Castle`# is located in %s.",get_module_setting("firemaploc","signetd4"));
				output("`n`n`#Once you've finished it, please come back and visit.'");
				$allprefs['incomplete']=0;
			}else output("`#'I'm sorry, `&Fiamma's Castle`# is not quite ready for you to explore.  Please come back soon.'");
		}elseif ($allprefs['sigmap3']==1){
			if (is_module_active("signetd3")){
				output("`#'Ah, welcome back.  I'm sorry about the delay.  It appears that `&Wasser's Castle`# is located in %s.",get_module_setting("watermaploc","signetd3"));
				output("`n`n`#Once you've finished it, please come back and visit.'");
				$allprefs['incomplete']=0;
			}else output("`#'I'm sorry, `&Wasser's Castle`# is not quite ready for you to explore.  Please come back soon.'");
		}elseif ($allprefs['sigmap2']==1){
			if (is_module_active("signetd2")){
				output("`#'Ah, welcome back.  I'm sorry about the delay.  It appears that `&Aarde Temple`# is located in %s.",get_module_setting("earthmaploc","signetd2"));
				output("`n`n`#Once you've finished it, please come back and visit.'");
				$allprefs['incomplete']=0;
			}else output("`#'I'm sorry, `&Aarde Temple`# is not quite ready for you to explore.  Please come back soon.'");
		}
	//Player hasn't completed their current dungeon 
	}elseif($current==0 && $allprefs['sigmap5']==0){
		output("`#'I'm sorry, but you'll have to complete the dungeon for the last map I gave you before we can talk about another of these precious maps.'`0");
	}elseif($current==0 && $allprefs['sigmap5']==1){
		output("`#'Hey, don't you think you should be out trying to finish that final map I sold to you?'");
	//First visit to the Mapmaker Shop (already checked to see if they have enough DKs at the mapshop nav)
	}elseif($allprefs['nodkopen']==0 && (is_module_active("mapmaker")||is_module_active("cartographer")) && get_module_setting("mapmaker")==1){
		output("You lean up to the counter and whisper `@'special maps'`0.");
		output("`n`nThe mapmaker takes little notice of your whispering and continues to organize the shop.");
		output("`n`n`@'Psssssst... SPECIAL Maps...'");
		output("`0The mapmaker looks up at you and looks at you as if you're a 3 headed snake trying to share a dead rat.`n`n");
		output("`#'Listen,'`0 says the mapmaker, `#'I may have something more unique...  maps that could lead you to new adventures that perhaps you have never dreamed of.");
		output("However, the price isn't cheap and I don't offer these maps to just anyone.'");
		output("`n`n`#'Come around to the back of my store and we can talk more privately.'");
		addnav("Continue","runmodule.php?module=signetsale&op=newsales");	
	//First visit to the Antiquities Shop 
	}elseif ($allprefs['nodkopen']==0){
		output("`c`b`^Antiquities For Sale`c`b`0");
		output("`nYou walk up to the shabby little cart and take a look at the so-called `^'Antiquities'`0 available for sale.");
		output("`n`nYou give a quiet snicker as you peruse the selection of great artifacts:");
		output("A `Q'tooth'`0 (which seems rather more like a river-polished piece of granite) supposedly from `@Grand Cthuton`0; the most evil dragon the land has ever known.");
		output("`n`n`#'Special price for that... it has magical powers I tell you.  Only `^10 gold`#.'");
		output("`n`n`0You pick up a piece of wood in the shape of the letter `q'Y'`0 and your curiosity gets the best of you.  You look up at the old man.");
		output("`n`n`#'That's the sling that slayed Grogron the Awful Two Headed Giant.  Selling fast for only `^7 gold`#.'");
		output("`n`n`0It's pretty much all junk and the little snicker that escapes from your lips does not go unnoticed.");
		//Enough Dks to get the first map
		if ($dks>=get_module_setting("dk1")+$cn && $current==1){
			output("`n`n`#'Ah... a discriminating connoisseur.  I understand.  Well, perhaps I have something a great warrior such as yourself may be interested in.");
			output("You see, I have some maps... very unique maps.  They will lead you to new adventures that perhaps you have never dreamed of.'");
			output("`n`n`0You notice that there's a change in the man's voice. This isn't talk of the drivel that's on display.  There may actually be something to his offer.");
			output("`#'Come around to the back of my cart and we can talk more privately.'");
			addnav("Continue","runmodule.php?module=signetsale&op=newsales");
		// Not enough dks to get the first map
		}elseif ($dks<get_module_setting("dk1")+$cn && $current==1){
			output("`#'Listen, that's all I have available for a small fish like you.  Perhaps when you've grown a little stronger I'll have something worth your time.'");
			output("`n`n'Good day to you, sir.'");
			output("`n`n`0You don't find anything worth purchasing quite yet.  After you've killed the `@Green Dragon`0 a couple of times you may find something worthwhile here.");
			$allprefs['nodkopen']=1;
		}
	}
	set_module_pref('allprefs',serialize($allprefs));
}
//First visit to the shop and Reviewing the price of the first map
if ($op=="newsales"){
	$allprefs['dkopen1']=1;
	set_module_pref('allprefs',serialize($allprefs));
	output("`c`b`^Elemental Signet Maps`b`c`0");
	output("`n'`#Okay, here's the deal.  I have five very unique maps for sale. But I cannot even begin to tell you how much work they were to obtain.");
	output("As I'm sure you can understand, my price, therefore, is proportionally shocking.");
	output("However, I do offer a guaranty.  If my maps aren't satisfying to you, I offer a full apology.  Your payment, however, is not refundable.'");
	output("`n`n'Let's see what I have available for someone of your prowess.'");
	output("`n`n`0After shuffling through some papers, the vendor holds up a rather shaggy-looking map.");
	output("`n`n`#'Yes, this one may interest you.  It's a map to a very ancient dungeon.  Unfortunately, the individual that made it wasn't able to finish mapping out all the details.'");
	output("`n`n'I'm pretty sure it's full of danger and traps and treasure and all that.'");
	//I don't recommend setting all the prices to zero, but if they are...
	if ($costturn==0 && $costgold==0 && $costgem==0) {
		output("`n`n'In fact, on second thought, this thing looks like a death trap.  I'd feel better just giving it to you.'");
		addnav("Free Map","runmodule.php?module=signetsale&op=getmap");
	}else{
		output("`n`n'Here's my price:'`c`n");
		$total=0;
		if ($topayturn>=1) {
			output("`^%s `@%s working for me`n",$topayturn,translate_inline($topayturn>1?"turns":"turn"));
			addnav("Spend Turns","runmodule.php?module=signetsale&op=payturn");
			$total=1;
		}
		if ($topaygold>=1) {
			if ($total==1) output("`#AND`n");
			output("`^%s gold`n",$topaygold);
			addnav("Pay gold","runmodule.php?module=signetsale&op=paygold");
			$total=1;
		}
		if ($topaygem>=1) {
			if ($total==1) output("`#AND`n");
			output("`^%s `%%s`n",$topaygem,translate_inline($topaygem>1?"gems":"gem"));
			addnav("Pay gems","runmodule.php?module=signetsale&op=paygems");
		}
		output_notl("`c");
	}
}
if ($op=="sales"){
	if ($topayturn<=0 && $topaygold<=0 && $topaygem<=0){
		addnav("Collect Your Map","runmodule.php?module=signetsale&op=getmap");
		output("`#'Well, it seems like you've paid me in full.  If you're ready, I can get you your map.'");
	}else{
		$dungeonnames=array("","Aria Dungeon","Aarde Temple","Wasser's Castle","Fiamma's Castle","Mierscri's Lair");
		output("`#'Would you like to pay for the map to `&%s`#? This is what it will cost you:'`c`n",$dungeonnames[$current]);
		if ($topayturn>=1) {
			output("`@%s %s working for me`n",$topayturn,translate_inline($topayturn>1?"turns":"turn"));
			addnav("Spend Turns","runmodule.php?module=signetsale&op=payturn");
		}
		if ($topaygold>=1) {
			output("`^%s gold`n",$topaygold);
			addnav("Pay gold","runmodule.php?module=signetsale&op=paygold");	
		}
		if ($topaygem>=1) {
			output("`%%s %s`n",$topaygem,translate_inline($topaygem>1?"gems":"gem"));
			addnav("Pay gems","runmodule.php?module=signetsale&op=paygems");
		}
		output_notl("`c");
	}
}
if ($op=="payturn"){
	output("`#'You have to work `@%s %s`# for me for the map. How many turns would you like to work for me today?'",$topayturn,translate_inline($topayturn>1?"turns":"turn"));
	output("<form action='runmodule.php?module=signetsale&op=payoffturn' method='POST'><input name='turnp' id='turnp'><input type='submit' class='button' value='Spend Turns'></form>",true);
	addnav("","runmodule.php?module=signetsale&op=payoffturn");
	if ($topaygold>0) addnav("Pay Gold","runmodule.php?module=signetsale&op=paygold");
	if ($topaygem>0) addnav("Pay Gems","runmodule.php?module=signetsale&op=paygems");
}
if ($op=="payoffturn"){
	$turn = httppost('turnp');
	$max = $session['user']['turns'];
	if ($turn < 0) $turn = 0;
	if ($turn >= $max) $turn = $max;
	if ($turn>$topayturn) {
		output("`#'Actually, I don't expect you to work that hard for the map.");
		output("You only need to work");
		if ($turn>=1) output("`@%s %s`#.'`n`n",$topayturn,translate_inline($topayturn>1?"turns":"turn"));
		$turn=$topayturn;
	}
	if ($turn>=$topayturn) {
		output("`0Before you know it you're arranging items for sale and doing what you believe are menial tasks.");
		output("`n`nSoon enough,`@%s`0 %s passed and the work is done.`n`n`#",$turn,translate_inline($turn>1?"turns have":"turn has"));
		signetsale_work();
		output("You've fulfilled that part of our bargain.'");
	}elseif ($turn==0){
		output("`#'Umm, well, thanks for nothing.'`n`n");
		output("'Perhaps you would actually like to do some work instead of wasting my time?");
	}else{
		output("`0Before you know it you're arranging items for sale and doing what you believe are menial tasks.");
		output("`n`nSoon enough, `@%s`0 %s passed and the work is done.`n`n`#",$turn,translate_inline($turn>1?"turns have":"turn has"));
	}
	$allprefs['paidturn'.$current]=$allprefs['paidturn'.$current]+$turn;
	$paidturn=$allprefs['paidturn'.$current];
	set_module_pref('allprefs',serialize($allprefs));
	set_module_pref("hoftemp",($current*1000)+$allprefs['completednum']);
	$topayturn=$costturn-$paidturn;
	$session['user']['turns']-=$turn;
	if ($topayturn>0){
		output("'You still have to work `@%s more %s `#before you've fulfilled your obligation of working for me.'",$topayturn,translate_inline($topayturn>1?"turns":"turn"));
		addnav("Spend More Turns","runmodule.php?module=signetsale&op=payturn");
	}
	if ($topaygold>0) addnav("Pay Gold","runmodule.php?module=signetsale&op=paygold");
	if ($topaygem>0) addnav("Pay Gems","runmodule.php?module=signetsale&op=paygems");
	if ($topayturn<=0 && $topaygold<=0 && $topaygem<=0) addnav("Collect Your Map","runmodule.php?module=signetsale&op=getmap");
}
if ($op=="paygold"){
	output("`#'You will have to pay `^%s gold`# for the map. How much would you like to give me?'",$topaygold);
	output("<form action='runmodule.php?module=signetsale&op=payoffgold' method='POST'><input name='goldp' id='goldp'><input type='submit' class='button' value='Pay Gold'></form>",true);
	addnav("","runmodule.php?module=signetsale&op=payoffgold");
	if ($topayturn>0) addnav("Spend Turns","runmodule.php?module=signetsale&op=payturn");
	if ($topaygem>0) addnav("Pay Gems","runmodule.php?module=signetsale&op=paygems");
}
if ($op=="payoffgold"){
	$gold = httppost('goldp');
	$max = $session['user']['gold'];
	if ($gold < 0) $gold = 0;
	if ($gold >= $max) $gold = $max;
	if ($gold>$topaygold) {
		output("`#'Actually, I don't expect you to pay that much for the map. You only need to pay `^%s gold`#.'`n`n",$topaygold);
		$gold=$topaygold;
	}
	if ($gold>=$topaygold) output("`0You hand over `^%s gold`0 and he counts it out carefully.`n`n`#'Looks like that's enough `^gold`# to make me happy.'",$gold);
	elseif ($gold==0) output("`#'Umm, well, thanks for nothing.'`n`n'Maybe you should actually give me some gold instead of wasting my time.'`n`n");
	else output("`0You hand over the `^%s gold`0 and he counts it out carefully.`n`n",$gold);
	$allprefs['paidgold'.$current]=$allprefs['paidgold'.$current]+$gold;
	set_module_pref('allprefs',serialize($allprefs));
	set_module_pref("hoftemp",($current*1000)+$allprefs['completednum']);
	$paidgold=$allprefs['paidgold'.$current];
	$topaygold=$costgold-$paidgold;
	$session['user']['gold']-=$gold;
	if ($topaygold>0){
		output("`#'You will need `^%s gold`# more before you're paid in full for gold.'",$topaygold);
		addnav("Pay More Gold","runmodule.php?module=signetsale&op=paygold");
	}
	if ($topayturn>0) addnav("Spend Turns","runmodule.php?module=signetsale&op=payturn");
	if ($topaygem>0) addnav("Pay Gems","runmodule.php?module=signetsale&op=paygems");
	if ($topayturn<=0 && $topaygold<=0 && $topaygem<=0) addnav("Collect Your Map","runmodule.php?module=signetsale&op=getmap");
}
if ($op=="paygems"){
	output("`#'You will have to pay `%%s %s`# for the map. How much would you like to give me?'",$topaygem,translate_inline($topaygem>1?"gems":"gem"));
	output("<form action='runmodule.php?module=signetsale&op=payoffgems' method='POST'><input name='gemp' id='gemp'><input type='submit' class='button' value='Pay Gems'></form>",true);
	addnav("","runmodule.php?module=signetsale&op=payoffgems");
	if ($topayturn>0) addnav("Spend Turns","runmodule.php?module=signetsale&op=payturn");
	if ($topaygold>0) addnav("Pay Gold","runmodule.php?module=signetsale&op=paygold");
}
if ($op=="payoffgems"){
	$gem = httppost('gemp');
	$max = $session['user']['gems'];
	if ($gem < 0) $gem = 0;
	if ($gem >= $max) $gem = $max;
	if ($gem>$topaygem) {
		output("`#'Actually, I don't expect you to pay that much for the map. You only need to pay `%%s %s`#.'`n`n",$topaygem,translate_inline($topaygem>1?"gems":"gem"));
		$gem=$topaygem;
	}
	if ($gem>=$topaygem) output("`0You hand over`% %s %s`0 and he counts %s carefully.`n`n`#'Looks like that's enough `%gems`# to make me happy.'",$gem,translate_inline($gem>1?"gems":"gem"),translate_inline($gem>1?"them out":"it"));
	elseif ($gem==0) output("`#'Umm, well, thanks for nothing.'`n`n'Maybe you should actually give me some gems instead of wasting my time.`n`n");
	else output("`0You hand over`% %s %s`0 and he counts them out carefully.`n`n",$gem,translate_inline($gem>1?"gems":"gem"));
	$allprefs['paidgem'.$current]=$allprefs['paidgem'.$current]+$gem;
	set_module_pref('allprefs',serialize($allprefs));
	set_module_pref("hoftemp",($current*1000)+$allprefs['completednum']);
	$paidgem=$allprefs['paidgem'.$current];
	$topaygem=$costgem-$paidgem;
	$session['user']['gems']-=$gem;
	if ($topaygem>0){
		output("`#'You will need`% %s %s `# more before you're paid in full for gems.'",$topaygem,translate_inline($topaygem>1?"gems":"gem"));
		addnav("Pay More Gems","runmodule.php?module=signetsale&op=paygems");
	}
	if ($topaygold>0) addnav("Pay Gold","runmodule.php?module=signetsale&op=paygold");
	if ($topayturn>0) addnav("Spend Turns","runmodule.php?module=signetsale&op=payturn");
	if ($topayturn<=0 && $topaygold<=0 && $topaygem<=0) addnav("Collect Your Map","runmodule.php?module=signetsale&op=getmap");
}
if ($op=="getmap"){
	output("`0You happily take the precious map from the mapmaker.");
	output("`n`nAfter some careful study of the map, you discover that the entrance to the `&%s`0 is located in",$dungeonnames[$current]);
	if ($current==1){
		output("`&%s`0.",get_module_setting("airmaploc","signetd1"));
		output("`n`n`#'Like I said, you've been warned... Do not go there unprepared.  Once you've finished exploring `&%s`#, you can come back and see what else I have to offer you.'",$dungeonnames[$current]);
		output("`n`n`0Almost as an afterthought, the vendor gives you another piece of paper. `#'Maybe this will answer some questions about what these maps are all about.'");
		output("`n`n`0You take the fragile scroll and look at it briefly.");
		output("`n`n`^Would you like to read the scroll?  If you don't want to right now, it will be available in your Bio for review.");
		if (get_module_setting("usepics")==1) {
			output("`n`n`0You decide to take a quick look at the map:`n`n");
			rawoutput("<br><center><table><tr><td align=center><img src=modules/signetimg/d1.gif></td></tr></table></center><br>"); 
		}
		addnav("Read Scroll 1","runmodule.php?module=signetsale&op=scroll1b");
		$allprefs['scroll1']=1;
		$allprefs['sigmap1']=1;
		set_module_pref("hoftemp",1100+$allprefs['completednum']);
	}elseif (is_module_active("signetd2") && $current==2){
		output("`&%s`0.",get_module_setting("earthmaploc","signetd2"));
		if (get_module_setting("usepics")==1){
			output("`n`n`0You decide to take a quick look at the map:`n`n");
			rawoutput("<br><center><table><tr><td align=center><img src=modules/signetimg/d2.gif></td></tr></table></center><br>"); 
		}
		$allprefs['sigmap2']=1;
		set_module_pref("hoftemp",2100+$allprefs['completednum']);
	}elseif (is_module_active("signetd3") && $current==3){
		output("`&%s`0.",get_module_setting("watermaploc","signetd3"));
		if (get_module_setting("usepics")==1){
			output("`n`n`0You decide to take a quick look at the map:`n`n");
			rawoutput("<br><center><table><tr><td align=center><img src=modules/signetimg/d3.gif></td></tr></table></center><br>"); 
		}
		$allprefs['sigmap3']=1;
		set_module_pref("hoftemp",3100+$allprefs['completednum']);
	}elseif (is_module_active("signetd4") && $current==4){
		output("`&%s`0.",get_module_setting("firemaploc","signetd4"));
		if (get_module_setting("usepics")==1){
			output("`n`n`0You decide to take a quick look at the map:`n`n");
			rawoutput("<br><center><table><tr><td align=center><img src=modules/signetimg/d4.gif></td></tr></table></center><br>"); 
		}
		$allprefs['sigmap4']=1;
		set_module_pref("hoftemp",4100+$allprefs['completednum']);
	}elseif (is_module_active("signetd5") && $current==5){
		output("`&%s`0.",get_module_setting("finalmaploc","signetd5"));
		output("`n`n`#'This is the last map I have to offer you from my collection of rare maps.  It's been my pleasure doing business with you.");
		output("I wish you the best of luck accomplishing this last map.  From what I recall, this is a very dangerous place to visit.'`n`n");
		output("'Good Luck!'");
		if (get_module_setting("usepics")==1){
			output("`n`n`0You decide to take a quick look at the map:`n`n");
			rawoutput("<br><center><table><tr><td align=center><img src=modules/signetimg/d5.gif></td></tr></table></center><br>"); 
		}
		$allprefs['sigmap5']=1;
		set_module_pref("hoftemp",5100+$allprefs['completednum']);
	}else{
	//If the next of the remaining modules haven't been written/installed, this message will default
		if ($current==2) $allprefs['sigmap2']=1;
		elseif ($current==3) $allprefs['sigmap3']=1;
		elseif ($current==4) $allprefs['sigmap4']=1;
		elseif ($current==5) $allprefs['sigmap5']=1;
		$allprefs['incomplete']=1;
		output("...  Actually, after careful examination of the map, you can't find the entrance!");
		output("You look over at the vendor with an evil scowl.`n`n");
		output("`#'I'm sorry, I don't think it's ready for you to explore yet.  Please check back and see if I can tell you where it is in the future.'");
		output("`n`n`0Unfortunately, there's not much you can do at this time.  If you are looking forward to finding the next dungeon, please send a note to the ruler of the land.");
	}
	set_module_pref('allprefs',serialize($allprefs));
}
if ($op=="scroll1b"){
	output("`c`b`^The Aria Dungeon`0`c`b`n");
	output("The Aria Dungeon was once the center of a small community of dwarves.  About 15 years ago, a band of orcs invaded the community and defeated the dwarves.");
	output("`n`nIt is said that only the leader of the dwarves, Kilmor, and a few others escaped from the Orcs.");
}
//Bio Information starts here
if ($op=="signetnotes"){
	$userid = httpget("user");
	$strike = false;
	$allprefs=unserialize(get_module_pref('allprefs','signetsale',$userid));
	$allprefsd1=unserialize(get_module_pref('allprefs','signetd1',$userid));
	$allprefsd2=unserialize(get_module_pref('allprefs','signetd2',$userid));
	$allprefsd3=unserialize(get_module_pref('allprefs','signetd3',$userid));
	$allprefsd4=unserialize(get_module_pref('allprefs','signetd4',$userid));
	$allprefsd5=unserialize(get_module_pref('allprefs','signetd5',$userid));
	page_header("Signet Elementals");
	rawoutput("<big>");
	if ($allprefs['completednum']>0){
		output("`cSignet Recognition Title: ");
		if ($allprefs['completednum']>10) output("`^`bGreat Mage of the Signets`b");
		elseif ($allprefs['completednum']==10) output("`%Power Signet Mage");
		elseif ($allprefs['completednum']==9) output("`\$Fire Signet Mage");
		elseif ($allprefs['completednum']==8) output("`!Water Signet Mage");
		elseif ($allprefs['completednum']==7) output("`QEarth Signet Mage");
		elseif ($allprefs['completednum']==6) output("`3Air Signet Mage");
		elseif ($allprefs['completednum']==5) output("`6Dark Lord's Bane");
		elseif ($allprefs['completednum']==4) output("`1Nemesis of Mierscri");
		elseif ($allprefs['completednum']==3) output("`#Supreme Vanquisher");
		elseif ($allprefs['completednum']==2) output("`@Grand Vanquisher");
		else output("`^Vanquisher");
		output_notl("`n`c");
	}
	if ($allprefsd1['airsignet']==1) output("`c`^`bSignet Markings`c `n`c`3~ Air Signet ~`b`c");
	if ($allprefsd2['earthsignet']==1) output("`b`n`c`Q~ Earth Signet ~`c`b");
	if ($allprefsd3['watersignet']==1) output("`b`n`c`!~ Water Signet ~`c`b");
	if ($allprefsd4['firesignet']==1) output("`b`n`c`\$~ Fire Signet ~`c`b");
	if ($allprefsd5['powersignet']==1) output("`b`n`c`%~ Power Signet ~`c`b");
	rawoutput("</big>");
	if ($userid==$session['user']['acctid'] && $allprefs['scroll1']==1 && $allprefsd1['airsignet']==0 && $allprefs['completednum']==0) output("`c`b`&Scrolls of the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`& Signets`b`c");
	if ($userid==$session['user']['acctid'] && $allprefs['scroll1']==1) addnav("Scroll 1","runmodule.php?module=signetsale&op=scroll1&user=$userid");			
	modulehook("scrolls-signetsale");
	$return = httpget('return');
	$return = cmd_sanitize($return);
	$return = substr($return,strrpos($return,"/")+1);
	tlschema("nav");
	addnav("Return whence you came",$return);
	tlschema();
}
if ($op=="scroll1"){
	$userid = httpget("user");
	output("`c`b`^The Aria Dungeon`0`c`b`n");
	output("The Aria Dungeon was once the center of a small community of dwarves.  About 15 years ago, a band of orcs invaded the community and defeated the dwarves.");
	output("`n`nIt is said that only the leader of the dwarves, Kilmor, and a few others escaped from the Orcs.");
	addnav("Return to Signets","runmodule.php?module=signetsale&op=signetnotes&user=$userid");
}
if ($op == "hof") {
	page_header("Hall of Fame");
	$pp = get_module_setting("frpp","signetd5");
	$pageoffset = (int)$page;
	if ($pageoffset > 0) $pageoffset--;
	$pageoffset *= $pp;
	$limit = "LIMIT $pageoffset,$pp";
	$sql = "SELECT COUNT(*) AS c FROM " . db_prefix("module_userprefs") . " WHERE modulename = 'signetd5' AND setting = 'frhofnum' AND value > 0";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$total = $row['c'];
	$count = db_num_rows($result);
	if (($pageoffset + $pp) < $total){
		$cond = $pageoffset + $pp;
	}else{
		$cond = $total;
	}
	$rank = translate_inline("Rank");
	$name = translate_inline("Name");
	$none = translate_inline("Mierscri is Undefeated!");
	output("`b`c`@Vanquishers of the Dark Lord Mierscri`c`b`n`n");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td>$rank</td><td>$name</td></tr>");
	if (get_module_setting("dksincewin")==-1){
		$sql = "SELECT ".db_prefix("module_userprefs").".value, ".db_prefix("accounts").".name FROM " . db_prefix("module_userprefs") . "," . db_prefix("accounts") . " WHERE acctid = userid AND modulename = 'signetd5' AND setting = 'frhofnum' AND value > 0 ORDER BY (value+0) ASC $limit";
		$result = db_query($sql);
		if (db_num_rows($result)==0){
			output_notl("<tr class='trlight'><td colspan='3' align='center'>`&$none`0</td></tr>",true);
		}else{
			for($i = $pageoffset; $i < $cond && $count; $i++) {
				$row = db_fetch_assoc($result);
				if ($row['name']==$session['user']['name']){
					rawoutput("<tr class='trhilight'><td>");
				}else{
					rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
				}
				$j=$i+1;
				output_notl("$j.");
				rawoutput("</td><td>");
				output_notl("`&%s`0",$row['name']);
				rawoutput("</td></tr>");
				}
		}
	}else{
		$names=translate_inline(array("`)DarkSlayer","`&Vanquisher","`@Grand Vanquisher","`#Supreme Vanquisher","`1Nemesis","`6Bane of Evil","`3Air Mage","`QEarth Mage","`!Water Mage","`\$Fire Mage","`%Power Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage","`^Signet Mage"));
		$sql= "SELECT acctid,name FROM ".db_prefix("accounts")."";
		$res= db_query($sql);
		for ($i=0; $i<db_num_rows($res);$i++){
			$row=db_fetch_assoc($res);
			$array=unserialize(get_module_pref("allprefs","signetsale",$row['acctid']));
			if ($array['completednum'] > 0) {
				$new_array[$row['name']] = $array['completednum'];
			}
		}
		arsort($new_array);
		foreach($new_array AS $name => $value){
			$n=$n+1;
			if ($n>$pageoffset && $n<=$cond){
			//for($i = $pageoffset; $i < $cond && $count; $i++) {
				if ($name==$session['user']['name']) rawoutput("<tr class='trhilight'><td>");
				else rawoutput("<tr class='".($n%2?"trdark":"trlight")."'><td>");
				output_notl("%s",$name);
				rawoutput("</td><td>");
				output_notl("%s",$names[$value]);
				rawoutput("</td></tr>");
			}
		}
	}
	rawoutput("</table>");
	if ($total>$pp){
		addnav("Pages");
		for ($p=0;$p<$total;$p+=$pp){
			addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=signetsale&op=hof&page=".($p/$pp+1));
		}
	}
	addnav("Other");
	addnav("Back to HoF", "hof.php");
	villagenav();
}
if ($op == "hof2") {
	page_header("Hall of Fame");
	$pp = get_module_setting("pp");
	$pageoffset = (int)$page;
	if ($pageoffset > 0) $pageoffset--;
	$pageoffset *= $pp;
	$limit = "LIMIT $pageoffset,$pp";
	$sql = "SELECT COUNT(*) AS c FROM " . db_prefix("module_userprefs") . " WHERE modulename = 'signetsale' AND setting = 'hoftemp' AND value > 0";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	$total = $row['c'];
	$count = db_num_rows($result);
	if (($pageoffset + $pp) < $total){
		$cond = $pageoffset + $pp;
	}else{
		$cond = $total;
	}
	if ($total>$pp){
		addnav("Pages");
		for ($p=0;$p<$total;$p+=$pp){
			addnav(array("Page %s (%s-%s)", ($p/$pp+1), ($p+1), min($p+$pp,$total)), "runmodule.php?module=signetsale&op=hof&page=".($p/$pp+1));
		}
	}
	$sql = "SELECT ".db_prefix("module_userprefs").".value, ".db_prefix("module_userprefs").".userid, ".db_prefix("accounts").".name FROM " . db_prefix("module_userprefs") . "," . db_prefix("accounts") . " WHERE acctid = userid AND modulename = 'signetsale' AND setting = 'hoftemp' AND value > 0 ORDER BY (value+0) DESC $limit";
	$result = db_query($sql);
	$rank = translate_inline("Rank");
	$name = translate_inline("Name");
	$temple1 = translate_inline("`b`c`3~ Aria Dungeon ~`c`b`0");
	$temple2 = translate_inline("`b`c`Q~ Aarde Temple ~`c`b`0");
	$temple3 = translate_inline("`b`c`!~ Wasser's Castle ~`c`b`0");
	$temple4 = translate_inline("`b`c`\$~ Fiamma's Fortress ~`c`b`0");
	$temple5 = translate_inline("`b`c`%~ Dark Lair ~`c`b`0");
	$none = translate_inline("No Signet Heroes Yet");
	output("`b`c`@Status of the Quest for the Elemental Signets`c`b`n`n");
	rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
	rawoutput("<tr class='trhead'><td>");
	output($rank);
	rawoutput("</td><td>");
	output($name);
	rawoutput("</td><td>");
	output($temple1);
	rawoutput("</td><td>");
	output($temple2);
	rawoutput("</td><td>");
	output($temple3);
	rawoutput("</td><td>");
	output($temple4);
	rawoutput("</td><td>");
	output($temple5);
	rawoutput("</td></tr>");
	if (db_num_rows($result)==0) output_notl("<tr class='trlight'><td colspan='7' align='center'>`&$none`0</td></tr>",true);
	else{
		for($i = $pageoffset; $i < $cond && $count; $i++) {
			$row = db_fetch_assoc($result);
			if ($row['name']==$session['user']['name']){
				rawoutput("<tr class='trhilight'><td>");
			}else{
				rawoutput("<tr class='".($i%2?"trdark":"trlight")."'><td>");
			}
			$j=$i+1;
			output_notl("$j.");
			rawoutput("</td><td>");
			output_notl("`&%s`0",$row['name']);
			rawoutput("</td><td>");
			$allprefss=unserialize(get_module_pref('allprefs','signetsale',$row['userid']));
			$allprefsd1=unserialize(get_module_pref('allprefs','signetd1',$row['userid']));
			if ($allprefsd1['complete']>0) $text1 = translate_inline("`b`c`@~ Completed ~`c`b`0");
			elseif ($allprefsd1["airsignet"]>0) $text1 = translate_inline("`b`c`!~ Has Signet ~`c`b`0");
			elseif ($allprefss['sigmap1']>0) $text1 = translate_inline("`c`^~ Has Map ~`c`0");
			elseif ($allprefss['paidgold1']>0||$allprefss['paidgem1']>0||$allprefss['paidturn1']>0) $text1 = translate_inline("`c`\$~ Financing ~`c`0");
			elseif ($allprefss['dkopen1']>0) $text1 = translate_inline("`c`&~ Started ~`c`0");
			else $text1 = translate_inline("`c`)Not Started`c`0");
			output_notl($text1);
			rawoutput("</td><td>");
			$allprefsd2=unserialize(get_module_pref('allprefs','signetd2',$row['userid']));
			if ($allprefsd2['complete']>0) $text2 = translate_inline("`b`c`@~ Completed ~`c`b`0");
			elseif ($allprefsd2["earthsignet"]>0) $text2 = translate_inline("`b`c`!~ Has Signet ~`c`b`0");
			elseif ($allprefss['sigmap2']>0) $text2 = translate_inline("`c`^~ Has Map ~`c`0");
			elseif ($allprefss['paidgold2']>0||$allprefss['paidgem2']>0||$allprefss['paidturn2']>0) $text2 = translate_inline("`c`\$~ Financing ~`c`0");
			elseif ($allprefss['dkopen2']>0) $text2 = translate_inline("`c`&~ Started ~`c`0");
			else $text2 = translate_inline("`c`)Not Started`c`0");
			output_notl($text2);
			rawoutput("</td><td>");
			$allprefsd3=unserialize(get_module_pref('allprefs','signetd3',$row['userid']));
			if ($allprefsd3['complete']>0) $text3 = translate_inline("`b`c`@~ Completed ~`c`b`0");
			elseif ($allprefsd3["watersignet"]>0) $text3 = translate_inline("`b`c`!~ Has Signet ~`c`b`0");
			elseif ($allprefss['sigmap3']>0) $text3 = translate_inline("`c`^~ Has Map ~`c`b`0");
			elseif ($allprefss['paidgold3']>0||$allprefss['paidgem3']>0||$allprefss['paidturn3']>0) $text3 = translate_inline("`c`\$~ Financing ~`c`0");
			elseif ($allprefss['dkopen3']>0) $text3 = translate_inline("`c`&~ Started ~`c`0");
			else $text3 = translate_inline("`c`)Not Started`c`0");
			output_notl($text3);
			rawoutput("</td><td>");
			$allprefsd4=unserialize(get_module_pref('allprefs','signetd4',$row['userid']));
			if ($allprefsd4['complete']>0) $text4 = translate_inline("`b`c`@~ Completed ~`c`b`0");
			elseif ($allprefsd4["firesignet"]>0) $text4 = translate_inline("`b`c`!~ Has Signet ~`c`b`0");
			elseif ($allprefss['sigmap4']>0) $text4 = translate_inline("`c`^~ Has Map ~`c`0");
			elseif ($allprefss['paidgold4']>0||$allprefss['paidgem4']>0||$allprefss['paidturn4']>0) $text4 = translate_inline("`c`\$~ Financing ~`c`0");
			elseif ($allprefss['dkopen4']>0) $text4 = translate_inline("`c`&~ Started ~`c`0");
			else $text4 = translate_inline("`c`)Not Started`c`0");
			output_notl($text4);
			rawoutput("</td><td>");
			$allprefsd5=unserialize(get_module_pref('allprefs','signetd5',$row['userid']));
			if ($allprefsd5["powersignet"]>0) $text5 = translate_inline("`b`c`@~ Completed ~`c`b`0");
			elseif ($allprefss['sigmap5']>0) $text5 = translate_inline("`c`^~ Has Map ~`c`0");
			elseif ($allprefss['paidgold5']>0||$allprefss['paidgem5']>0||$allprefss['paidturn5']>0) $text5 = translate_inline("`c`\$~ Financing ~`c`0");
			elseif ($allprefss['dkopen5']>0) $text5 = translate_inline("`c`&~ Started ~`c`0");
			else $text5 = translate_inline("`c`)Not Started`c`0");
			output_notl($text5);
			rawoutput("</td></tr>");
		}
	}
	rawoutput("</table>");
	addnav("Other");
	addnav("Back to HoF", "hof.php");
	villagenav();
}
page_footer();

function signetsale_work(){
	global $session;
	switch(e_rand(1,5)){
		case 1:
			output("'Well, you've certainly done a really mediocre job.");
		break;
		case 2:
			output("'Your organizational skills are pretty impressive.  Good Job!");
		break;
		case 3:
			output("'I think you've made more of a mess than there was before you started.  Very disappointing.");
		break;
		case 4:
			output("'Hold on for a second.  Let me think.  Oh yeah, I DID know a monkey with more talent than you have.");
		break;
		case 5:
			output("'Wonderful!  Keep up the good work.");
		break;
	}
}
?>