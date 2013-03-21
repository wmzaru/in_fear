<?php
function signetconvert_getmoduleinfo(){
	$info = array(
		"name"=>"Signet Converter",
		"version"=>"5.22",
		"author"=>"DaveS",
		"category"=>"Converter",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		"settings"=>array(
			"Signet Sale,title",
			"mapmaker"=>"If `^cartographer`0 installed use as location to buy maps?,bool|0",
			"mapsaleloc"=>"Where does the `3E`Ql`!e`\$m`3e`Qn`!t`\$a`3l`^ Signet`0 Shop appear,location|".getsetting("villagename", LOCATION_FIELDS),
			"usepics"=>"Show small picture of the map to the player upon purchase?,bool|1",
			"dksincewin"=>"Number of DKs before allowing players to replay the Signet Series after completing it?,int|-1",
			"usehof"=>"Use detailed HoF?,bool|1",
			"pp"=>"Number of players to show per page on the HoF?,int|25",
			"dk1"=>"`3How many `2dks`3 needed before able to purchase the Air map?,int|0",
			"costturn1"=>"`3How many `@turns`3 are required to buy the Air map?,int|16",
			"costgold1"=>"`3How much `^gold`3 is required to buy the Air map?,int|12000",
			"costgem1"=>"`3How many `%gems`3 are required to buy the Air map?,int|16",
			"dk2"=>"`QHow many `2dks`Q needed before able to purchase the Earth map?,int|0",
			"costturn2"=>"`QHow many `@turns`Q are required to buy the Earth map?,int|15",
			"costgold2"=>"`QHow much `^gold `Qis required to buy the Earth map?,int|10000",
			"costgem2"=>"`QHow many `%gems`Q are required to buy the Earth map?,int|15",
			"dk3"=>"`!How many `2dks`! needed before able to purchase the Water map?,int|0",
			"costturn3"=>"`!How many `@turns`! are required to buy the Water map?,int|15",
			"costgold3"=>"`!How much `^gold`! is required to buy the Water map?,int|10000",
			"costgem3"=>"`!How many `%gems`! are required to buy the Water map?,int|15",
			"dk4"=>"`\$How many `2dks`\$ needed before able to purchase the Fire map?,int|0",
			"costturn4"=>"`\$How many `@turns`\$ are required to buy the Fire map?,int|15",
			"costgold4"=>"`\$How much `^gold`\$ is required to buy the Fire map?,int|10000",
			"costgem4"=>"`\$How many `%gems`\$ are required to buy the Fire map?,int|15",
			"dk5"=>"`%How many `2dks`% needed before able to purchase the Final map?,int|0",
			"costturn5"=>"`%How many `@turns`% are required to buy the Final map?,int|15",
			"costgold5"=>"`%How much `^gold`% is required to buy the Final map?,int|10000",
			"costgem5"=>"`%How many gems are required to buy the Final map?,int|15",
			"Signet d1,title",
			"randomd1"=>"How many random monsters can be encountered in the dungeon?,int|5",
			"randencd1"=>"Likelihood of random encounter:,enum,1,Common,5,Uncommon,10,Rare,15,Very Rare,20,Extremely Rare|10",
			"healingd1"=>"Allow for players to have a chance to find a partial healing potion after fights?,bool|1",
			"airmaploc"=>"Where does the Air Dungeon appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"exitsaved1"=>"Allow users to return to the dungeon from an emergency exit?,enum,0,No,1,Yes,2,Require|0",
			"Signet d2,title",
			"randomd2"=>"How many random monsters can be encountered in the temple?,int|5",
			"randencd2"=>"Likelihood of random encounter:,enum,1,Common,5,Uncommon,10,Rare,15,Very Rare,20,Extremely Rare|10",
			"healingd2"=>"Allow for players to have a chance to find a partial healing potion after fights?,bool|1",
			"impalignd2"=>"Allow players to improve alignment by donating to temple?,bool|0",
			"costalignd2"=>"Cost in gold for each point to improve alignment if allowed?,int|1000",
			"alignmaxd2"=>"Max number of alignment points that can be improved?,int|20",
			"nodonated2"=>"Number of times no donation has been offered:,int|0",
			"earthmaploc"=>"Where does the Aarde Temple appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"Signet d3,title",
			"randomd3"=>"How many random monsters can be encountered in the dungeon?,int|5",
			"randencd3"=>"Likelihood of random encounter:,enum,1,Common,5,Uncommon,10,Rare,15,Very Rare,20,Extremely Rare|10",
			"healingd3"=>"Allow for players to have a chance to find a partial healing potion after fights?,bool|1",
			"watermaploc"=>"Where does the Water Dungeon appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"exitsaved3"=>"Allow users to return to the dungeon from an emergency exit?,enum,0,No,1,Yes,2,Require|0",
			"Signet d4,title",
			"randomd4"=>"How many random monsters can be encountered in the dungeon?,int|5",
			"randencd4"=>"Likelihood of random encounter:,enum,1,Common,5,Uncommon,10,Rare,15,Very Rare,20,Extremely Rare|10",
			"healingd4"=>"Allow for players to have a chance to find a partial healing potion after fights?,bool|1",
			"firemaploc"=>"Where does the Fire Dungeon appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"exitsaved4"=>"Allow users to return to the dungeon from an emergency exit?,enum,0,No,1,Yes,2,Require|0",
			"Signet d5,title",
			"randomd5"=>"How many random monsters can be encountered in the dungeon?,int|5",
			"randencd5"=>"Likelihood of random encounter:,enum,1,Common,5,Uncommon,10,Rare,15,Very Rare,20,Extremely Rare|10",
			"healingd5"=>"Allow for players to have a chance to find a partial healing potion after fights?,bool|1",
			"finalmaploc"=>"Where does the Final Dungeon appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"exitsaved5"=>"Allow users to return to the dungeon from an emergency exit?,enum,0,No,1,Yes,2,Require|0",
			"losegold"=>"`6Lose Percentage `^OR `6Amount `^of goldinbank for answering keypad wrong:,enum,0,Percentage,1,Amount|Percentage",
			"percentage"=>"`^Percentage of gold to lose from bank:,range,0,100,1|5",
			"numberlow"=>"`^Amount of gold to lose minimum from bank:,int|100",
			"numberhigh"=>"`^Amount of gold to lose maximum from bank:,int|1000",
			"goldwand"=>"`4Gold award after destroying the wand:,int|1000",
			"gemwand"=>"`4Gem award after destroying the wand:,int|2",
			"perexpr"=>"`@Percentage of experience lost if defeated by a Random Monster:,range,0,100,1|7",
			"perexpk"=>"`0Percentage of experience lost if defeated by a Black Warlock:,range,0,100,1|10",
			"perexpm"=>"`)Percentage of experience lost if defeated by Mierscri:,range,0,100,1|15",
			"dlhp"=>"`)Multiplier for Mierscri's hitpoints:,floatrange,1.0,3.5,0.1|1.6",
			"dlatt"=>"`)Multiplier for Mierscri's attack:,floatrange,1.0,2.5,0.1|1.6",
			"dldef"=>"`)Multiplier for Mierscri's defense:,floatrange,1.0,2.5,0.1|1.5",
			"frexpdk"=>"`#Experience per dragon kill for defeating Mierscri:,range,0,15,1|10",
			"frexplvl"=>"`#Experience per level for defeating Mierscri:,range,20,100,5|100",
			"frgold"=>"`^Gold reward:,int|5000",
			"frgems"=>"`%Gem reward:,int|5",
			"frdefense"=>"`&Defense points rewarded:,int|0",
			"frattack"=>"`&Attack points rewarded:,int|0",
			"frcharm"=>"`&Charm points rewarded:,int|5",
			"fralign"=>"`&Improve alignment by this amount for completion:,int|5",
			"frtitleoff"=>"`6Offer title?,bool|1",
			"frsystemmail"=>"`^Send out system wide YoM of successful completion?,bool|0",
			"frsend"=>"`^Send letter from Staff congratulating the player?,bool|1",
			"frwhosend"=>"`^Who is the Staff sending the letters?,text|`@King  Arthur",
			"frhof"=>"`QUse Hall of Fame record of completion,bool|0",
			"frpp"=>"`QNumber of names per page in Hall of Fame,int|35",
			"frhofnumb"=>"`QNumber of players that have completed the Signet Series,viewonly|0",
			"frnewday"=>"`@Make announcement of successful completion on newday screen?,bool|0",
			"frscroll"=>"`@Victory announcement in Village for the rest of the day?,enum,0,None,1,Scrolling,2,Stable|1",
			"frlastscroll"=>"`@Is the announcement still being displayed in the village?,bool|0",
			"frlastone"=>"`@What is the name of the last player to complete the Signet Series?,text|",
			"frtitle"=>"`6Title for completing the Signet Series 1st time?,text|`b`&Vanquisher`0`b",
			"frtitle2"=>"`6Title for completing the Signet Series 2nd time?,text|`b`@GrandVanquisher`0`b",
			"frtitle3"=>"`6Title for completing the Signet Series 3rd time?,text|`b`#SupremeVanquisher`0`b",
			"frtitle4"=>"`6Title for completing the Signet Series 4th time?,text|`b`1Nemesis`0`b",
			"frtitle5"=>"`6Title for completing the Signet Series 5th time?,text|`b`6BaneOfEvil`0`b",
			"frtitle6"=>"`6Title for completing the Signet Series 6th time?,text|`b`3AirMage`0`b",
			"frtitle7"=>"`6Title for completing the Signet Series 7th time?,text|`b`QEarthMage`0`b",
			"frtitle8"=>"`6Title for completing the Signet Series 8th time?,text|`b`!WaterMage`0`b",
			"frtitle9"=>"`6Title for completing the Signet Series 9th time?,text|`b`4FireMage`0`b",
			"frtitle10"=>"`6Title for completing the Signet Series 10th time?,text|`b`%PowerMage`0`b",
			"frtitle11"=>"`6Title for completing the Signet Series 11th time or more?,text|`b`^SignetMage`0`b",
		),
		"prefs"=>array(
			"Signet Sale,title",
			"allprefssale"=>"Preferences for signet sale,viewonly|",
			"super"=>"Super User Acces:,bool|0",
			"Signet d1,title",
			"allprefsd1"=>"Preferences for d1,viewonly|",
			"mazed1"=>"Maze,viewonly|",
			"pqtempd1"=>"Temp Info|",
			"Signet d2,title",
			"allprefsd2"=>"Preferences for d2,viewonly|",
			"mazed2"=>"Maze,viewonly|",
			"pqtempd2"=>"Temp Info|",
			"Signet d3,title",
			"allprefsd3"=>"Preferences for d3,viewonly|",
			"mazed3"=>"Maze,viewonly|",
			"pqtempd3"=>"Temp Info|",
			"Signet d4,title",
			"allprefsd4"=>"Preferences for d4,viewonly|",
			"mazed4"=>"Maze,viewonly|",
			"pqtempd4"=>"Temp Info|",
			"Signet d5,title",
			"allprefsd5"=>"Preferences for d5,viewonly|",
			"frhofnum"=>"What number to complete the series is this player?,int",
			"mazed5"=>"Maze,viewonly|",
			"pqtempd5"=>"Temp Info|",
		),	
		"requires"=>array(
			"signetsale"=>"3.0|by DaveS",
			"signetd1"=>"3.0|by DaveS",
			"signetd2"=>"3.0|by DaveS",
			"signetd3"=>"3.0|by DaveS",
			"signetd4"=>"3.0|by DaveS",
			"signetd5"=>"3.0|by DaveS",
		),
	);
	return $info;
}
function signetconvert_install(){
	module_addhook("superuser");
	$sql = "SELECT acctid FROM ".db_prefix("accounts")."";
	$res = db_query($sql);
	for ($i=0;$i<db_num_rows($res);$i++){
		$row = db_fetch_assoc($res);
		$id=$row['acctid'];
		//Signet Sale
		$allprefssale=unserialize(get_module_pref('allprefssale',"signetconvert",$id));
		$allprefssale['completednum']=get_module_pref("completednum","signetsale",$id);
		$allprefssale['dksince']=get_module_pref("dksince","signetsale",$id);
		$allprefssale['hoftemp']=get_module_pref("hoftemp","signetsale",$id);
		$allprefssale['nodkopen']=get_module_pref("nodkopen","signetsale",$id);
		$allprefssale['scroll1']=get_module_pref("scroll1","signetsale",$id);
		$allprefssale['incomplete']=get_module_pref("incomplete","signetsale",$id);
		$allprefssale['dkopen1']=get_module_pref("dkopena","signetsale",$id);
		$allprefssale['dkopen2']=get_module_pref("dkopene","signetsale",$id);
		$allprefssale['dkopen3']=get_module_pref("dkopenw","signetsale",$id);
		$allprefssale['dkopen4']=get_module_pref("dkopenf","signetsale",$id);
		$allprefssale['dkopen5']=get_module_pref("dkopenm","signetsale",$id);
		$allprefssale['paidturn1']=get_module_pref("paidturna","signetsale",$id);
		$allprefssale['paidturn2']=get_module_pref("paidturne","signetsale",$id);
		$allprefssale['paidturn3']=get_module_pref("paidturnw","signetsale",$id);
		$allprefssale['paidturn4']=get_module_pref("paidturnf","signetsale",$id);
		$allprefssale['paidturn5']=get_module_pref("paidturnm","signetsale",$id);
		$allprefssale['paidgold1']=get_module_pref("paidgolda","signetsale",$id);
		$allprefssale['paidgold2']=get_module_pref("paidgolde","signetsale",$id);
		$allprefssale['paidgold3']=get_module_pref("paidgoldw","signetsale",$id);
		$allprefssale['paidgold4']=get_module_pref("paidgoldf","signetsale",$id);
		$allprefssale['paidgold5']=get_module_pref("paidgoldm","signetsale",$id);
		$allprefssale['paidgem1']=get_module_pref("paidgema","signetsale",$id);
		$allprefssale['paidgem2']=get_module_pref("paidgeme","signetsale",$id);
		$allprefssale['paidgem3']=get_module_pref("paidgemw","signetsale",$id);
		$allprefssale['paidgem4']=get_module_pref("paidgemf","signetsale",$id);
		$allprefssale['paidgem5']=get_module_pref("paidgemm","signetsale",$id);
		$allprefssale['sigmap1']=get_module_pref("airsigmap","signetsale",$id);
		$allprefssale['sigmap2']=get_module_pref("earthsigmap","signetsale",$id);
		$allprefssale['sigmap3']=get_module_pref("watersigmap","signetsale",$id);
		$allprefssale['sigmap4']=get_module_pref("firesigmap","signetsale",$id);
		$allprefssale['sigmap5']=get_module_pref("finalsigmap","signetsale",$id);
		set_module_pref("allprefssale",serialize($allprefssale),"signetconvert",$id);
		//Signet d1
		$allprefsd1=unserialize(get_module_pref('allprefsd1',"signetconvert",$id));
		$allprefsd1['complete']=get_module_pref("complete","signetd1",$id);
		$allprefsd1['reset']=get_module_pref("reset","signetd1",$id);
		$allprefsd1['randomp']=get_module_pref("randomp","signetd1",$id);
		$allprefsd1['airsignet']=get_module_pref("airsignet","signetd1",$id);
		$allprefsd1['scroll2']=get_module_pref("scroll2","signetd1",$id);
		$allprefsd1['scroll3']=get_module_pref("scroll3","signetd1",$id);
		$allprefsd1['loc411']=get_module_pref("loc411","signetd1",$id);
		$allprefsd1['loc54']=get_module_pref("loc54","signetd1",$id);
		$allprefsd1['loc113']=get_module_pref("loc113","signetd1",$id);
		$allprefsd1['loc113b']=get_module_pref("loc113b","signetd1",$id);
		$allprefsd1['loc303']=get_module_pref("loc303","signetd1",$id);
		$allprefsd1['loc333']=get_module_pref("loc333","signetd1",$id);
		$allprefsd1['loc453']=get_module_pref("loc453","signetd1",$id);
		$allprefsd1['loc465']=get_module_pref("loc465","signetd1",$id);
		$allprefsd1['loc494']=get_module_pref("loc494","signetd1",$id);
		$allprefsd1['loc521']=get_module_pref("loc521","signetd1",$id);
		$allprefsd1['loc593']=get_module_pref("loc593","signetd1",$id);
		$allprefsd1['loc623']=get_module_pref("loc623","signetd1",$id);
		$allprefsd1['loc673']=get_module_pref("loc673","signetd1",$id);
		$allprefsd1['loc677']=get_module_pref("loc677","signetd1",$id);
		$allprefsd1['loc685']=get_module_pref("loc685","signetd1",$id);
		$allprefsd1['loc689']=get_module_pref("loc689","signetd1",$id);
		$allprefsd1['loc711']=get_module_pref("loc711","signetd1",$id);
		$allprefsd1['loc759']=get_module_pref("loc759","signetd1",$id);
		$allprefsd1['loc787']=get_module_pref("loc787","signetd1",$id);
		$allprefsd1['loc891']=get_module_pref("loc891","signetd1",$id);
		$allprefsd1['loc899']=get_module_pref("loc899","signetd1",$id);
		$allprefsd1['loc973']=get_module_pref("loc973","signetd1",$id);
		$allprefsd1['loc983']=get_module_pref("loc983","signetd1",$id);
		$allprefsd1['loc1006']=get_module_pref("loc1006","signetd1",$id);
		$allprefsd1['loc1008']=get_module_pref("loc1008","signetd1",$id);
		$allprefsd1['loc1009']=get_module_pref("loc1009","signetd1",$id);
		$allprefsd1['loc1011']=get_module_pref("loc1011","signetd1",$id);
		$allprefsd1['loc1015']=get_module_pref("loc1015","signetd1",$id);
		$allprefsd1['loc1133']=get_module_pref("loc1133","signetd1",$id);
		$allprefsd1['loc1152']=get_module_pref("loc1152","signetd1",$id);
		$allprefsd1['loc1197']=get_module_pref("loc1197","signetd1",$id);
		$allprefsd1['loc1199']=get_module_pref("loc1199","signetd1",$id);
		$allprefsd1['loc1216']=get_module_pref("loc1216","signetd1",$id);
		$allprefsd1['loc1287']=get_module_pref("loc1287","signetd1",$id);
		$allprefsd1['loc1133b']=get_module_pref("loc1133b","signetd1",$id);
		$allprefsd1['mazeturn']=get_module_pref("mazeturn","signetd1",$id);
		$allprefsd1['startloc']=get_module_pref("startloc","signetd1",$id);
		$allprefsd1['header']=get_module_pref("header","signetd1",$id);
		set_module_pref("allprefsd1",serialize($allprefsd1),"signetconvert",$id);
		set_module_pref("mazed1",get_module_pref("maze","signetd1"),"signetconvert",$id);
		set_module_pref("pqtempd1",get_module_pref("pqtemp","signetd1"),"signetconvert",$id);
		//Signet d2
		$allprefsd2=unserialize(get_module_pref('allprefsd2',"signetconvert",$id));
		$allprefsd2['complete']=get_module_pref("complete","signetd2",$id);
		$allprefsd2['reset']=get_module_pref("reset","signetd2",$id);
		$allprefsd2['randomp']=get_module_pref("randomp","signetd2",$id);
		$allprefsd2['earthsignet']=get_module_pref("earthsignet","signetd2",$id);
		$allprefsd2['scroll4']=get_module_pref("scroll4","signetd2",$id);
		$allprefsd2['loc334']=get_module_pref("loc334","signetd2",$id);
		$allprefsd2['loc685']=get_module_pref("loc685","signetd2",$id);
		$allprefsd2['loc1098']=get_module_pref("loc1098","signetd2",$id);
		$allprefsd2['donated']=get_module_pref("donated","signetd2",$id);
		$allprefsd2['donatenum']=get_module_pref("donatenum","signetd2",$id);
		$allprefsd2['loc55']=get_module_pref("loc55","signetd2",$id);
		$allprefsd2['loc59']=get_module_pref("loc59","signetd2",$id);
		$allprefsd2['loc82']=get_module_pref("loc82","signetd2",$id);
		$allprefsd2['loc109']=get_module_pref("loc109","signetd2",$id);
		$allprefsd2['loc109b']=get_module_pref("loc109b","signetd2",$id);
		$allprefsd2['loc163']=get_module_pref("loc163","signetd2",$id);
		$allprefsd2['loc279']=get_module_pref("loc279","signetd2",$id);
		$allprefsd2['loc327']=get_module_pref("loc327","signetd2",$id);
		$allprefsd2['loc334b']=get_module_pref("loc334b","signetd2",$id);
		$allprefsd2['loc381']=get_module_pref("loc381","signetd2",$id);
		$allprefsd2['loc386']=get_module_pref("loc386","signetd2",$id);
		$allprefsd2['loc496']=get_module_pref("loc496","signetd2",$id);
		$allprefsd2['loc537']=get_module_pref("loc537","signetd2",$id);
		$allprefsd2['loc537b']=get_module_pref("loc537b","signetd2",$id);
		$allprefsd2['loc556']=get_module_pref("loc556","signetd2",$id);
		$allprefsd2['loc776']=get_module_pref("loc776","signetd2",$id);
		$allprefsd2['loc822']=get_module_pref("loc822","signetd2",$id);
		$allprefsd2['loc841']=get_module_pref("loc841","signetd2",$id);
		$allprefsd2['loc843']=get_module_pref("loc843","signetd2",$id);
		$allprefsd2['loc865']=get_module_pref("loc865","signetd2",$id);
		$allprefsd2['loc890']=get_module_pref("loc890","signetd2",$id);
		$allprefsd2['loc946']=get_module_pref("loc946","signetd2",$id);
		$allprefsd2['loc947']=get_module_pref("loc947","signetd2",$id);
		$allprefsd2['loc956']=get_module_pref("loc956","signetd2",$id);
		$allprefsd2['loc970']=get_module_pref("loc970","signetd2",$id);
		$allprefsd2['loc1010']=get_module_pref("loc1010","signetd2",$id);
		$allprefsd2['loc1012']=get_module_pref("loc1012","signetd2",$id);
		$allprefsd2['loc1026']=get_module_pref("loc1026","signetd2",$id);
		$allprefsd2['loc1082']=get_module_pref("loc1082","signetd2",$id);
		$allprefsd2['loc1098']=get_module_pref("loc1098","signetd2",$id);
		$allprefsd2['loc1147']=get_module_pref("loc1147","signetd2",$id);
		$allprefsd2['loc1148']=get_module_pref("loc1148","signetd2",$id);
		$allprefsd2['mazeturn']=get_module_pref("mazeturn","signetd2",$id);
		$allprefsd2['header']=get_module_pref("header","signetd2",$id);
		set_module_pref("allprefsd2",serialize($allprefsd2),"signetconvert",$id);
		set_module_pref("mazed2",get_module_pref("maze","signetd2"),"signetconvert",$id);
		set_module_pref("pqtempd2",get_module_pref("pqtemp","signetd2"),"signetconvert",$id);
		//Signet d3
		$allprefsd3=unserialize(get_module_pref('allprefsd3',"signetconvert",$id));
		$allprefsd3['complete']=get_module_pref("complete","signetd3",$id);
		$allprefsd3['reset']=get_module_pref("reset","signetd3",$id);
		$allprefsd3['randomp']=get_module_pref("randomp","signetd3",$id);
		$allprefsd3['watersignet']=get_module_pref("watersignet","signetd3",$id);
		$allprefsd3['scroll5']=get_module_pref("scroll5","signetd3",$id);
		$allprefsd3['loc159']=get_module_pref("loc159","signetd3",$id);
		$allprefsd3['prisonkey']=get_module_pref("prisonkey","signetd3",$id);
		$allprefsd3['loc11']=get_module_pref("loc11","signetd3",$id);
		$allprefsd3['loc11b']=get_module_pref("loc11b","signetd3",$id);
		$allprefsd3['loc11c']=get_module_pref("loc11c","signetd3",$id);
		$allprefsd3['loc29']=get_module_pref("loc29","signetd3",$id);
		$allprefsd3['loc48']=get_module_pref("loc48","signetd3",$id);
		$allprefsd3['loc53']=get_module_pref("loc53","signetd3",$id);
		$allprefsd3['loc65']=get_module_pref("loc65","signetd3",$id);
		$allprefsd3['loc66']=get_module_pref("loc66","signetd3",$id);
		$allprefsd3['loc82']=get_module_pref("loc82","signetd3",$id);
		$allprefsd3['loc87']=get_module_pref("loc87","signetd3",$id);
		$allprefsd3['loc94']=get_module_pref("loc94","signetd3",$id);
		$allprefsd3['loc148']=get_module_pref("loc148","signetd3",$id);
		$allprefsd3['loc157']=get_module_pref("loc157","signetd3",$id);
		$allprefsd3['loc164']=get_module_pref("loc164","signetd3",$id);
		$allprefsd3['loc221']=get_module_pref("loc221","signetd3",$id);
		$allprefsd3['loc331']=get_module_pref("loc331","signetd3",$id);
		$allprefsd3['loc352']=get_module_pref("loc352","signetd3",$id);
		$allprefsd3['loc354']=get_module_pref("loc354","signetd3",$id);
		$allprefsd3['loc358']=get_module_pref("loc358","signetd3",$id);
		$allprefsd3['loc401']=get_module_pref("loc401","signetd3",$id);
		$allprefsd3['loc483']=get_module_pref("loc483","signetd3",$id);
		$allprefsd3['loc499']=get_module_pref("loc499","signetd3",$id);
		$allprefsd3['loc537']=get_module_pref("loc537","signetd3",$id);
		$allprefsd3['loc561']=get_module_pref("loc561","signetd3",$id);
		$allprefsd3['loc591']=get_module_pref("loc591","signetd3",$id);
		$allprefsd3['loc619']=get_module_pref("loc619","signetd3",$id);
		$allprefsd3['loc625']=get_module_pref("loc625","signetd3",$id);
		$allprefsd3['loc635']=get_module_pref("loc635","signetd3",$id);
		$allprefsd3['loc745']=get_module_pref("loc745","signetd3",$id);
		$allprefsd3['loc746']=get_module_pref("loc746","signetd3",$id);
		$allprefsd3['loc839']=get_module_pref("loc839","signetd3",$id);
		$allprefsd3['loc931']=get_module_pref("loc931","signetd3",$id);
		$allprefsd3['loc939']=get_module_pref("loc939","signetd3",$id);
		$allprefsd3['loc1071']=get_module_pref("loc1071","signetd3",$id);
		$allprefsd3['loc1079']=get_module_pref("loc1079","signetd3",$id);
		$allprefsd3['loc1097']=get_module_pref("loc1097","signetd3",$id);
		$allprefsd3['loc1206']=get_module_pref("loc1206","signetd3",$id);
		$allprefsd3['mazeturn']=get_module_pref("mazeturn","signetd3",$id);
		$allprefsd3['startloc']=get_module_pref("startloc","signetd3",$id);
		$allprefsd3['header']=get_module_pref("header","signetd3",$id);
		set_module_pref("allprefsd3",serialize($allprefsd3),"signetconvert",$id);
		set_module_pref("mazed3",get_module_pref("maze","signetd3"),"signetconvert",$id);
		set_module_pref("pqtempd3",get_module_pref("pqtemp","signetd3"),"signetconvert",$id);
		//Signet d4
		$allprefsd4=unserialize(get_module_pref('allprefsd4',"signetconvert",$id));
		$allprefsd4['complete']=get_module_pref("complete","signetd4",$id);
		$allprefsd4['reset']=get_module_pref("reset","signetd4",$id);
		$allprefsd4['randomp']=get_module_pref("randomp","signetd4",$id);
		$allprefsd4['firesignet']=get_module_pref("firesignet","signetd4",$id);
		$allprefsd4['loc182']=get_module_pref("loc182","signetd4",$id);
		$allprefsd4['scroll7']=get_module_pref("scroll7","signetd4",$id);
		$allprefsd4['scroll6']=get_module_pref("scroll6","signetd4",$id);
		$allprefsd4['loc12']=get_module_pref("loc12","signetd4",$id);
		$allprefsd4['loc18']=get_module_pref("loc18","signetd4",$id);
		$allprefsd4['loc27']=get_module_pref("loc27","signetd4",$id);
		$allprefsd4['loc29']=get_module_pref("loc29","signetd4",$id);
		$allprefsd4['loc83']=get_module_pref("loc83","signetd4",$id);
		$allprefsd4['loc87']=get_module_pref("loc87","signetd4",$id);
		$allprefsd4['loc90']=get_module_pref("loc90","signetd4",$id);
		$allprefsd4['loc93']=get_module_pref("loc93","signetd4",$id);
		$allprefsd4['loc138']=get_module_pref("loc138","signetd4",$id);
		$allprefsd4['loc152']=get_module_pref("loc152","signetd4",$id);
		$allprefsd4['loc177']=get_module_pref("loc177","signetd4",$id);
		$allprefsd4['loc178']=get_module_pref("loc178","signetd4",$id);
		$allprefsd4['loc198']=get_module_pref("loc198","signetd4",$id);
		$allprefsd4['loc250']=get_module_pref("loc250","signetd4",$id);
		$allprefsd4['loc312']=get_module_pref("loc312","signetd4",$id);
		$allprefsd4['loc328']=get_module_pref("loc328","signetd4",$id);
		$allprefsd4['loc343']=get_module_pref("loc343","signetd4",$id);
		$allprefsd4['loc362']=get_module_pref("loc362","signetd4",$id);
		$allprefsd4['loc377']=get_module_pref("loc377","signetd4",$id);
		$allprefsd4['loc383']=get_module_pref("loc383","signetd4",$id);
		$allprefsd4['loc386']=get_module_pref("loc386","signetd4",$id);
		$allprefsd4['loc394']=get_module_pref("loc394","signetd4",$id);
		$allprefsd4['loc459']=get_module_pref("loc459","signetd4",$id);
		$allprefsd4['loc463']=get_module_pref("loc463","signetd4",$id);
		$allprefsd4['loc467']=get_module_pref("loc467","signetd4",$id);
		$allprefsd4['loc485']=get_module_pref("loc485","signetd4",$id);
		$allprefsd4['loc506']=get_module_pref("loc506","signetd4",$id);
		$allprefsd4['loc513']=get_module_pref("loc513","signetd4",$id);
		$allprefsd4['loc561']=get_module_pref("loc561","signetd4",$id);
		$allprefsd4['loc587']=get_module_pref("loc587","signetd4",$id);
		$allprefsd4['loc635']=get_module_pref("loc635","signetd4",$id);
		$allprefsd4['loc655']=get_module_pref("loc655","signetd4",$id);
		$allprefsd4['loc683']=get_module_pref("loc683","signetd4",$id);
		$allprefsd4['loc689']=get_module_pref("loc689","signetd4",$id);
		$allprefsd4['loc717']=get_module_pref("loc717","signetd4",$id);
		$allprefsd4['loc726']=get_module_pref("loc726","signetd4",$id);
		$allprefsd4['loc840']=get_module_pref("loc840","signetd4",$id);
		$allprefsd4['loc853']=get_module_pref("loc853","signetd4",$id);
		$allprefsd4['loc868']=get_module_pref("loc868","signetd4",$id);
		$allprefsd4['loc874']=get_module_pref("loc874","signetd4",$id);
		$allprefsd4['loc931']=get_module_pref("loc931","signetd4",$id);
		$allprefsd4['loc931b']=get_module_pref("loc931b","signetd4",$id);
		$allprefsd4['loc934']=get_module_pref("loc934","signetd4",$id);
		$allprefsd4['loc934b']=get_module_pref("loc934b","signetd4",$id);
		$allprefsd4['loc948']=get_module_pref("loc948","signetd4",$id);
		$allprefsd4['loc1057']=get_module_pref("loc1057","signetd4",$id);
		$allprefsd4['loc1059']=get_module_pref("loc1059","signetd4",$id);
		$allprefsd4['loc1099']=get_module_pref("loc1099","signetd4",$id);
		$allprefsd4['loc1104']=get_module_pref("loc1104","signetd4",$id);
		$allprefsd4['loc1143']=get_module_pref("loc1143","signetd4",$id);
		$allprefsd4['loc1177']=get_module_pref("loc1177","signetd4",$id);
		$allprefsd4['loc1177count']=get_module_pref("loc1177count","signetd4",$id);
		$allprefsd4['mazeturn']=get_module_pref("mazeturn","signetd4",$id);
		$allprefsd4['startloc']=get_module_pref("startloc","signetd4",$id);
		$allprefsd4['header']=get_module_pref("header","signetd4",$id);
		set_module_pref("allprefsd4",serialize($allprefsd4),"signetconvert",$id);
		set_module_pref("mazed4",get_module_pref("maze","signetd4"),"signetconvert",$id);
		set_module_pref("pqtempd4",get_module_pref("pqtemp","signetd4"),"signetconvert",$id);
		//Signet d5
		$allprefsd5=unserialize(get_module_pref('allprefsd5','signetconvert',$id));
		$allprefsd5['complete']=get_module_pref("complete","signetd5",$id);
		$allprefsd5['announce']=get_module_pref("announce","signetd5",$id);
		$allprefsd5['randomp']=get_module_pref("randomp","signetd5",$id);
		$allprefsd5['powersignet']=get_module_pref("powersignet","signetd5",$id);
		$allprefsd5['darkdead']=get_module_pref("darkdead","signetd5",$id);
		$allprefsd5['transport']=get_module_pref("transport","signetd5",$id);
		$allprefsd5['usedtrans']=get_module_pref("usedtrans","signetd5",$id);
		$allprefsd5['bankloss']=get_module_pref("bankloss","signetd5",$id);
		$allprefsd5['loc109']=get_module_pref("loc109","signetd5",$id);
		$allprefsd5['loc113']=get_module_pref("loc113","signetd5",$id);
		$allprefsd5['loc115']=get_module_pref("loc115","signetd5",$id);
		$allprefsd5['loc117']=get_module_pref("loc117","signetd5",$id);
		$allprefsd5['loc119']=get_module_pref("loc119","signetd5",$id);
		$allprefsd5['loc121']=get_module_pref("loc121","signetd5",$id);
		$allprefsd5['loc123']=get_module_pref("loc123","signetd5",$id);
		$allprefsd5['loc127']=get_module_pref("loc127","signetd5",$id);
		$allprefsd5['loc143']=get_module_pref("loc143","signetd5",$id);
		$allprefsd5['loc147']=get_module_pref("loc147","signetd5",$id);
		$allprefsd5['loc149']=get_module_pref("loc149","signetd5",$id);
		$allprefsd5['loc151']=get_module_pref("loc151","signetd5",$id);
		$allprefsd5['loc153']=get_module_pref("loc153","signetd5",$id);
		$allprefsd5['loc155']=get_module_pref("loc155","signetd5",$id);
		$allprefsd5['loc157']=get_module_pref("loc157","signetd5",$id);
		$allprefsd5['loc161']=get_module_pref("loc161","signetd5",$id);
		$allprefsd5['loc279']=get_module_pref("loc279","signetd5",$id);
		$allprefsd5['loc283']=get_module_pref("loc283","signetd5",$id);
		$allprefsd5['loc285']=get_module_pref("loc285","signetd5",$id);
		$allprefsd5['loc287']=get_module_pref("loc287","signetd5",$id);
		$allprefsd5['loc289']=get_module_pref("loc289","signetd5",$id);
		$allprefsd5['loc291']=get_module_pref("loc291","signetd5",$id);
		$allprefsd5['loc293']=get_module_pref("loc293","signetd5",$id);
		$allprefsd5['loc297']=get_module_pref("loc297","signetd5",$id);
		$allprefsd5['loc335']=get_module_pref("loc335","signetd5",$id);
		$allprefsd5['loc347']=get_module_pref("loc347","signetd5",$id);
		$allprefsd5['loc361']=get_module_pref("loc361","signetd5",$id);
		$allprefsd5['loc381']=get_module_pref("loc381","signetd5",$id);
		$allprefsd5['loc385']=get_module_pref("loc385","signetd5",$id);
		$allprefsd5['loc387']=get_module_pref("loc387","signetd5",$id);
		$allprefsd5['loc389']=get_module_pref("loc389","signetd5",$id);
		$allprefsd5['loc391']=get_module_pref("loc391","signetd5",$id);
		$allprefsd5['loc393']=get_module_pref("loc393","signetd5",$id);
		$allprefsd5['loc395']=get_module_pref("loc395","signetd5",$id);
		$allprefsd5['loc399']=get_module_pref("loc399","signetd5",$id);
		$allprefsd5['loc421']=get_module_pref("loc421","signetd5",$id);
		$allprefsd5['loc425']=get_module_pref("loc425","signetd5",$id);
		$allprefsd5['loc503']=get_module_pref("loc503","signetd5",$id);
		$allprefsd5['loc503b']=get_module_pref("loc503b","signetd5",$id);
		$allprefsd5['loc505']=get_module_pref("loc505","signetd5",$id);
		$allprefsd5['loc505b']=get_module_pref("loc505b","signetd5",$id);
		$allprefsd5['loc507']=get_module_pref("loc507","signetd5",$id);
		$allprefsd5['loc519']=get_module_pref("loc519","signetd5",$id);
		$allprefsd5['loc524']=get_module_pref("loc524","signetd5",$id);
		$allprefsd5['loc528']=get_module_pref("loc528","signetd5",$id);
		$allprefsd5['loc533']=get_module_pref("loc533","signetd5",$id);
		$allprefsd5['loc685']=get_module_pref("loc685","signetd5",$id);
		$allprefsd5['loc687']=get_module_pref("loc687","signetd5",$id);
		$allprefsd5['loc691']=get_module_pref("loc691","signetd5",$id);
		$allprefsd5['loc694']=get_module_pref("loc694","signetd5",$id);
		$allprefsd5['loc698']=get_module_pref("loc698","signetd5",$id);
		$allprefsd5['loc701']=get_module_pref("loc701","signetd5",$id);
		$allprefsd5['loc705']=get_module_pref("loc705","signetd5",$id);
		$allprefsd5['loc707']=get_module_pref("loc707","signetd5",$id);
		$allprefsd5['loc724']=get_module_pref("loc724","signetd5",$id);
		$allprefsd5['loc736']=get_module_pref("loc736","signetd5",$id);
		$allprefsd5['loc766']=get_module_pref("loc766","signetd5",$id);
		$allprefsd5['loc774']=get_module_pref("loc774","signetd5",$id);
		$allprefsd5['loc777']=get_module_pref("loc777","signetd5",$id);
		$allprefsd5['loc792']=get_module_pref("loc792","signetd5",$id);
		$allprefsd5['loc804']=get_module_pref("loc804","signetd5",$id);
		$allprefsd5['loc834']=get_module_pref("loc834","signetd5",$id);
		$allprefsd5['loc849']=get_module_pref("loc849","signetd5",$id);
		$allprefsd5['loc860']=get_module_pref("loc860","signetd5",$id);
		$allprefsd5['loc872']=get_module_pref("loc872","signetd5",$id);
		$allprefsd5['loc895']=get_module_pref("loc895","signetd5",$id);
		$allprefsd5['loc898']=get_module_pref("loc898","signetd5",$id);
		$allprefsd5['loc902']=get_module_pref("loc902","signetd5",$id);
		$allprefsd5['loc905']=get_module_pref("loc905","signetd5",$id);
		$allprefsd5['loc1002']=get_module_pref("loc1002","signetd5",$id);
		$allprefsd5['loc1012']=get_module_pref("loc1012","signetd5",$id);
		$allprefsd5['loc1016']=get_module_pref("loc1016","signetd5",$id);
		$allprefsd5['loc1016b']=get_module_pref("loc1016b","signetd5",$id);
		$allprefsd5['loc1104']=get_module_pref("loc1104","signetd5",$id);
		$allprefsd5['loc1104b']=get_module_pref("loc1104b","signetd5",$id);
		$allprefsd5['loc1138']=get_module_pref("loc1138","signetd5",$id);
		$allprefsd5['loc1172']=get_module_pref("loc1172","signetd5",$id);
		$allprefsd5['loc1257']=get_module_pref("loc1257","signetd5",$id);
		$allprefsd5['mazeturn']=get_module_pref("mazeturn","signetd5",$id);
		$allprefsd5['startloc']=get_module_pref("startloc","signetd5",$id);
		$allprefsd5['header']=get_module_pref("header","signetd5",$id);
		set_module_pref("allprefsd5",serialize($allprefsd5),"signetconvert",$id);
		set_module_pref("frhofnum",get_module_pref("frhofnum","signetd5"),"signetconvert",$id);
		set_module_pref("mazed5",get_module_pref("maze","signetd5"),"signetconvert",$id);
		set_module_pref("pqtempd5",get_module_pref("pqtemp","signetd5"),"signetconvert",$id);
	}
	//Signet Sale
	set_module_setting("mapmaker",get_module_setting("mapmaker","signetsale"),"signetconvert");
	set_module_setting("mapsaleloc",get_module_setting("mapsaleloc","signetsale"),"signetconvert");
	set_module_setting("usepics",get_module_setting("usepics","signetsale"),"signetconvert");
	set_module_setting("dksincewin",get_module_setting("dksincewin","signetsale"),"signetconvert");
	set_module_setting("usehof",get_module_setting("usehof","signetsale"),"signetconvert");
	set_module_setting("pp",get_module_setting("pp","signetsale"),"signetconvert");
	set_module_setting("dk1",get_module_setting("dkair","signetsale"),"signetconvert");
	set_module_setting("dk2",get_module_setting("dkearth","signetsale"),"signetconvert");
	set_module_setting("dk3",get_module_setting("dkwater","signetsale"),"signetconvert");
	set_module_setting("dk4",get_module_setting("dkfire","signetsale"),"signetconvert");
	set_module_setting("dk5",get_module_setting("dkfinal","signetsale"),"signetconvert");
	set_module_setting("costturn1",get_module_setting("costturna","signetsale"),"signetconvert");
	set_module_setting("costturn2",get_module_setting("costturne","signetsale"),"signetconvert");
	set_module_setting("costturn3",get_module_setting("costturnw","signetsale"),"signetconvert");
	set_module_setting("costturn4",get_module_setting("costturnf","signetsale"),"signetconvert");
	set_module_setting("costturn5",get_module_setting("costturnm","signetsale"),"signetconvert");
	set_module_setting("costgold1",get_module_setting("costgolda","signetsale"),"signetconvert");
	set_module_setting("costgold2",get_module_setting("costgolde","signetsale"),"signetconvert");
	set_module_setting("costgold3",get_module_setting("costgoldw","signetsale"),"signetconvert");
	set_module_setting("costgold4",get_module_setting("costgoldf","signetsale"),"signetconvert");
	set_module_setting("costgold5",get_module_setting("costgoldm","signetsale"),"signetconvert");
	set_module_setting("costgem1",get_module_setting("costgema","signetsale"),"signetconvert");
	set_module_setting("costgem2",get_module_setting("costgeme","signetsale"),"signetconvert");
	set_module_setting("costgem3",get_module_setting("costgemw","signetsale"),"signetconvert");
	set_module_setting("costgem4",get_module_setting("costgemf","signetsale"),"signetconvert");
	set_module_setting("costgem5",get_module_setting("costgemm","signetsale"),"signetconvert");
	//Signet D1
	set_module_setting("randomd1",get_module_setting("random","signetd1"),"signetconvert");
	set_module_setting("randencd1",get_module_setting("randenc","signetd1"),"signetconvert");
	set_module_setting("healingd1",get_module_setting("healing","signetd1"),"signetconvert");
	set_module_setting("airmaploc",get_module_setting("airmaploc","signetd1"),"signetconvert");
	set_module_setting("exitsaved1",get_module_setting("exitsave","signetd1"),"signetconvert");
	//Signet D2
	set_module_setting("randomd2",get_module_setting("random","signetd2"),"signetconvert");
	set_module_setting("randencd2",get_module_setting("randenc","signetd2"),"signetconvert");
	set_module_setting("healingd2",get_module_setting("healing","signetd2"),"signetconvert");
	set_module_setting("impalignd2",get_module_setting("impalign","signetd2"),"signetconvert");
	set_module_setting("costalignd2",get_module_setting("costalign","signetd2"),"signetconvert");
	set_module_setting("alignmaxd2",get_module_setting("alignmax","signetd2"),"signetconvert");
	set_module_setting("nodonated2",get_module_setting("nodonate","signetd2"),"signetconvert");
	set_module_setting("earthmaploc",get_module_setting("earthmaploc","signetd2"),"signetconvert");
	//Signet D3
	set_module_setting("randomd3",get_module_setting("random","signetd3"),"signetconvert");
	set_module_setting("randencd3",get_module_setting("randenc","signetd3"),"signetconvert");
	set_module_setting("healingd3",get_module_setting("healing","signetd3"),"signetconvert");
	set_module_setting("watermaploc",get_module_setting("watermaploc","signetd3"),"signetconvert");
	set_module_setting("exitsaved3",get_module_setting("exitsave","signetd3"),"signetconvert");
	//Signet D4
	set_module_setting("randomd4",get_module_setting("random","signetd4"),"signetconvert");
	set_module_setting("randencd4",get_module_setting("randenc","signetd4"),"signetconvert");
	set_module_setting("healingd4",get_module_setting("healing","signetd4"),"signetconvert");
	set_module_setting("firemaploc",get_module_setting("firemaploc","signetd4"),"signetconvert");
	set_module_setting("exitsaved4",get_module_setting("exitsave","signetd4"),"signetconvert");
	//Signet D5
	set_module_setting("randomd5",get_module_setting("random","signetd5"),"signetconvert");
	set_module_setting("randencd5",get_module_setting("randenc","signetd5"),"signetconvert");
	set_module_setting("healingd5",get_module_setting("healing","signetd5"),"signetconvert");
	set_module_setting("finalmaploc",get_module_setting("finalmaploc","signetd5"),"signetconvert");
	set_module_setting("exitsaved5",get_module_setting("exitsave","signetd5"),"signetconvert");
	set_module_setting("losegold",get_module_setting("losegold","signetd5"),"signetconvert");
	set_module_setting("percentage",get_module_setting("percentage","signetd5"),"signetconvert");
	set_module_setting("numberlow",get_module_setting("numberlow","signetd5"),"signetconvert");
	set_module_setting("numberhigh",get_module_setting("numberhigh","signetd5"),"signetconvert");
	set_module_setting("goldwand",get_module_setting("goldwand","signetd5"),"signetconvert");
	set_module_setting("gemwand",get_module_setting("gemwand","signetd5"),"signetconvert");
	set_module_setting("perexpr",get_module_setting("perexpr","signetd5"),"signetconvert");
	set_module_setting("perexpk",get_module_setting("perexpk","signetd5"),"signetconvert");
	set_module_setting("perexpm",get_module_setting("perexpm","signetd5"),"signetconvert");
	set_module_setting("dlhp",get_module_setting("dlhp","signetd5"),"signetconvert");
	set_module_setting("dlatt",get_module_setting("dlatt","signetd5"),"signetconvert");
	set_module_setting("dldef",get_module_setting("dldef","signetd5"),"signetconvert");
	set_module_setting("frexpdk",get_module_setting("frexpdk","signetd5"),"signetconvert");
	set_module_setting("frexplvl",get_module_setting("frexplvl","signetd5"),"signetconvert");
	set_module_setting("frgold",get_module_setting("frgold","signetd5"),"signetconvert");
	set_module_setting("frgems",get_module_setting("frgems","signetd5"),"signetconvert");
	set_module_setting("frdefense",get_module_setting("frdefense","signetd5"),"signetconvert");
	set_module_setting("frattack",get_module_setting("frattack","signetd5"),"signetconvert");
	set_module_setting("frcharm",get_module_setting("frcharm","signetd5"),"signetconvert");
	set_module_setting("fralign",get_module_setting("fralign","signetd5"),"signetconvert");
	set_module_setting("frtitleoff",get_module_setting("frtitleoff","signetd5"),"signetconvert");
	set_module_setting("frsystemmail",get_module_setting("frsystemmail","signetd5"),"signetconvert");
	set_module_setting("frsend",get_module_setting("frsend","signetd5"),"signetconvert");
	set_module_setting("frwhosend",get_module_setting("frwhosend","signetd5"),"signetconvert");
	set_module_setting("frhof",get_module_setting("frhof","signetd5"),"signetconvert");
	set_module_setting("frpp",get_module_setting("frpp","signetd5"),"signetconvert");
	set_module_setting("frhofnumb",get_module_setting("frhofnumb","signetd5"),"signetconvert");
	set_module_setting("frnewday",get_module_setting("frnewday","signetd5"),"signetconvert");
	set_module_setting("frscroll",get_module_setting("frscroll","signetd5"),"signetconvert");
	set_module_setting("frlastscroll",get_module_setting("frlastscroll","signetd5"),"signetconvert");
	set_module_setting("frlastone",get_module_setting("frlastone","signetd5"),"signetconvert");
	set_module_setting("frtitle",get_module_setting("frtitle","signetd5"),"signetconvert");
	set_module_setting("frtitle2",get_module_setting("frtitle2","signetd5"),"signetconvert");
	set_module_setting("frtitle3",get_module_setting("frtitle3","signetd5"),"signetconvert");
	set_module_setting("frtitle4",get_module_setting("frtitle4","signetd5"),"signetconvert");
	set_module_setting("frtitle5",get_module_setting("frtitle5","signetd5"),"signetconvert");
	set_module_setting("frtitle6",get_module_setting("frtitle6","signetd5"),"signetconvert");
	set_module_setting("frtitle7",get_module_setting("frtitle7","signetd5"),"signetconvert");
	set_module_setting("frtitle8",get_module_setting("frtitle8","signetd5"),"signetconvert");
	set_module_setting("frtitle9",get_module_setting("frtitle9","signetd5"),"signetconvert");
	set_module_setting("frtitle10",get_module_setting("frtitle10","signetd5"),"signetconvert");
	set_module_setting("frtitle11",get_module_setting("frtitle11","signetd5"),"signetconvert");

	output("`b`nPLEASE DO NOT UNINSTALL THIS MODULE YET.");
	output("`nYou should now UNINSTALL the OLD signet module.");
	output("`nAfter you have uninstalled the Old signet Module, Copy the new signet to your module directory and install it.");
	output("`nThen go to the grotto to Converters: Convert signet.`n`n`b");
	return true;
}
function signetconvert_uninstall(){
	return true;
}
function signetconvert_dohook($hookname,$args){
	switch($hookname){
		case "superuser":
			addnav("Converters");
			addnav("Convert Signet Series","runmodule.php?module=signetconvert&op=super");
		break;
	}
	return $args;
}
function signetconvert_run(){
	global $session;
	$op = httpget('op');
	page_header("signet Converter");
	if ($op=="super"){
		$sql = "SELECT acctid FROM ".db_prefix("accounts")."";
		$res = db_query($sql);
		for ($i=0;$i<db_num_rows($res);$i++){
			$row = db_fetch_assoc($res);
			$id=$row['acctid'];
			//Signet Sale
			$allprefssale=get_module_pref("allprefssale","signetconvert",$id);
			set_module_pref("allprefs",$allprefssale,"signetsale",$id);
			//Signet D1
			$allprefsd1=get_module_pref("allprefsd1","signetconvert",$id);
			set_module_pref("allprefs",$allprefsd1,"signetd1",$id);
			set_module_pref("maze",get_module_pref("mazed1"),"signetd1",$id);
			set_module_pref("pqtemp",get_module_pref("pqtempd1"),"signetd1",$id);
			//Signet D2
			$allprefsd2=get_module_pref("allprefsd2","signetconvert",$id);
			set_module_pref("allprefs",$allprefsd2,"signetd2",$id);
			set_module_pref("maze",get_module_pref("mazed2"),"signetd2",$id);
			set_module_pref("pqtemp",get_module_pref("pqtempd2"),"signetd2",$id);
			//Signet D3
			$allprefsd3=get_module_pref("allprefsd3","signetconvert",$id);
			set_module_pref("allprefs",$allprefsd3,"signetd3",$id);
			set_module_pref("maze",get_module_pref("mazed3"),"signetd3",$id);
			set_module_pref("pqtemp",get_module_pref("pqtempd3"),"signetd3",$id);
			//Signet D4
			$allprefsd4=get_module_pref("allprefsd4","signetconvert",$id);
			set_module_pref("allprefs",$allprefsd4,"signetd4",$id);
			set_module_pref("maze",get_module_pref("mazed4"),"signetd4",$id);
			set_module_pref("pqtemp",get_module_pref("pqtempd4"),"signetd4",$id);
			//Signet D5
			$allprefsd5=get_module_pref("allprefsd5","signetconvert",$id);
			set_module_pref("allprefs",$allprefsd5,"signetd5",$id);
			set_module_pref("maze",get_module_pref("mazed5"),"signetd5",$id);
			set_module_pref("pqtemp",get_module_pref("pqtempd5"),"signetd5",$id);
			set_module_pref("frhofnum",get_module_pref("frhofnum"),"signetd5",$id);
		}
		//Signet Sale
		set_module_setting("mapmaker",get_module_setting("mapmaker","signetconvert"),"signetsale");
		set_module_setting("mapsaleloc",get_module_setting("mapsaleloc","signetconvert"),"signetsale");
		set_module_setting("usepics",get_module_setting("usepics","signetconvert"),"signetsale");
		set_module_setting("dksincewin",get_module_setting("dksincewin","signetconvert"),"signetsale");
		set_module_setting("usehof",get_module_setting("usehof","signetconvert"),"signetsale");
		set_module_setting("pp",get_module_setting("pp","signetconvert"),"signetsale");
		set_module_setting("dk1",get_module_setting("dk1","signetconvert"),"signetsale");
		set_module_setting("dk2",get_module_setting("dk2","signetconvert"),"signetsale");
		set_module_setting("dk3",get_module_setting("dk3","signetconvert"),"signetsale");
		set_module_setting("dk4",get_module_setting("dk4","signetconvert"),"signetsale");
		set_module_setting("dk5",get_module_setting("dk5","signetconvert"),"signetsale");
		set_module_setting("costturn1",get_module_setting("costturn1","signetconvert"),"signetsale");
		set_module_setting("costturn2",get_module_setting("costturn2","signetconvert"),"signetsale");
		set_module_setting("costturn3",get_module_setting("costturn3","signetconvert"),"signetsale");
		set_module_setting("costturn4",get_module_setting("costturn4","signetconvert"),"signetsale");
		set_module_setting("costturn5",get_module_setting("costturn5","signetconvert"),"signetsale");
		set_module_setting("costgold1",get_module_setting("costgold1","signetconvert"),"signetsale");
		set_module_setting("costgold2",get_module_setting("costgold2","signetconvert"),"signetsale");
		set_module_setting("costgold3",get_module_setting("costgold3","signetconvert"),"signetsale");
		set_module_setting("costgold4",get_module_setting("costgold4","signetconvert"),"signetsale");
		set_module_setting("costgold5",get_module_setting("costgold5","signetconvert"),"signetsale");
		set_module_setting("costgem1",get_module_setting("costgem1","signetconvert"),"signetsale");
		set_module_setting("costgem2",get_module_setting("costgem2","signetconvert"),"signetsale");
		set_module_setting("costgem3",get_module_setting("costgem3","signetconvert"),"signetsale");
		set_module_setting("costgem4",get_module_setting("costgem4","signetconvert"),"signetsale");
		set_module_setting("costgem5",get_module_setting("costgem5","signetconvert"),"signetsale");
		//Signet D1
		set_module_setting("random",get_module_setting("randomd1","signetconvert"),"signetd1");
		set_module_setting("randenc",get_module_setting("randencd1","signetconvert"),"signetd1");
		set_module_setting("healing",get_module_setting("healingd1","signetconvert"),"signetd1");
		set_module_setting("airmaploc",get_module_setting("airmaploc","signetconvert"),"signetd1");
		set_module_setting("exitsave",get_module_setting("exitsaved1","signetconvert"),"signetd1");		
		//Signet D2
		set_module_setting("random",get_module_setting("randomd2","signetconvert"),"signetd2");
		set_module_setting("randenc",get_module_setting("randencd2","signetconvert"),"signetd2");
		set_module_setting("healing",get_module_setting("healingd2","signetconvert"),"signetd2");
		set_module_setting("impalign",get_module_setting("impalignd2","signetconvert"),"signetd2");
		set_module_setting("costalign",get_module_setting("costalignd2","signetconvert"),"signetd2");
		set_module_setting("alignmax",get_module_setting("alignmaxd2","signetconvert"),"signetd2");
		set_module_setting("nodonate",get_module_setting("nodonated2","signetconvert"),"signetd2");
		set_module_setting("earthmaploc",get_module_setting("earthmaploc","signetconvert"),"signetd2");
		//Signet D3
		set_module_setting("random",get_module_setting("randomd3","signetconvert"),"signetd3");
		set_module_setting("randenc",get_module_setting("randencd3","signetconvert"),"signetd3");
		set_module_setting("healing",get_module_setting("healingd3","signetconvert"),"signetd3");
		set_module_setting("watermaploc",get_module_setting("watermaploc","signetconvert"),"signetd3");
		set_module_setting("exitsave",get_module_setting("exitsaved3","signetconvert"),"signetd3");		
		//Signet D4
		set_module_setting("random",get_module_setting("randomd4","signetconvert"),"signetd4");
		set_module_setting("randenc",get_module_setting("randencd4","signetconvert"),"signetd4");
		set_module_setting("healing",get_module_setting("healingd4","signetconvert"),"signetd4");
		set_module_setting("firemaploc",get_module_setting("firemaploc","signetconvert"),"signetd4");
		set_module_setting("exitsave",get_module_setting("exitsaved4","signetconvert"),"signetd4");		
		//Signet D5
		set_module_setting("random",get_module_setting("randomd5","signetconvert"),"signetd5");
		set_module_setting("randenc",get_module_setting("randencd5","signetconvert"),"signetd5");
		set_module_setting("healing",get_module_setting("healingd5","signetconvert"),"signetd5");
		set_module_setting("finalmaploc",get_module_setting("finalmaploc","signetconvert"),"signetd5");
		set_module_setting("exitsave",get_module_setting("exitsaved5","signetconvert"),"signetd5");		
		set_module_setting("losegold",get_module_setting("losegold","signetconvert"),"signetd5");
		set_module_setting("percentage",get_module_setting("percentage","signetconvert"),"signetd5");
		set_module_setting("numberlow",get_module_setting("numberlow","signetconvert"),"signetd5");
		set_module_setting("numberhigh",get_module_setting("numberhigh","signetconvert"),"signetd5");
		set_module_setting("goldwand",get_module_setting("goldwand","signetconvert"),"signetd5");
		set_module_setting("gemwand",get_module_setting("gemwand","signetconvert"),"signetd5");
		set_module_setting("perexpr",get_module_setting("perexpr","signetconvert"),"signetd5");
		set_module_setting("perexpk",get_module_setting("perexpk","signetconvert"),"signetd5");
		set_module_setting("perexpm",get_module_setting("perexpm","signetconvert"),"signetd5");
		set_module_setting("dlhp",get_module_setting("dlhp","signetconvert"),"signetd5");
		set_module_setting("dlatt",get_module_setting("dlatt","signetconvert"),"signetd5");
		set_module_setting("dldef",get_module_setting("dldef","signetconvert"),"signetd5");
		set_module_setting("frexpdk",get_module_setting("frexpdk","signetconvert"),"signetd5");
		set_module_setting("frexplvl",get_module_setting("frexplvl","signetconvert"),"signetd5");
		set_module_setting("frgold",get_module_setting("frgold","signetconvert"),"signetd5");
		set_module_setting("frgems",get_module_setting("frgems","signetconvert"),"signetd5");
		set_module_setting("frdefense",get_module_setting("frdefense","signetconvert"),"signetd5");
		set_module_setting("frattack",get_module_setting("frattack","signetconvert"),"signetd5");
		set_module_setting("frcharm",get_module_setting("frcharm","signetconvert"),"signetd5");
		set_module_setting("fralign",get_module_setting("fralign","signetconvert"),"signetd5");
		set_module_setting("frtitleoff",get_module_setting("frtitleoff","signetconvert"),"signetd5");
		set_module_setting("frsystemmail",get_module_setting("frsystemmail","signetconvert"),"signetd5");
		set_module_setting("frsend",get_module_setting("frsend","signetconvert"),"signetd5");
		set_module_setting("frwhosend",get_module_setting("frwhosend","signetconvert"),"signetd5");
		set_module_setting("frhof",get_module_setting("frhof","signetconvert"),"signetd5");
		set_module_setting("frpp",get_module_setting("frpp","signetconvert"),"signetd5");
		set_module_setting("frhofnumb",get_module_setting("frhofnumb","signetconvert"),"signetd5");
		set_module_setting("frnewday",get_module_setting("frnewday","signetconvert"),"signetd5");
		set_module_setting("frscroll",get_module_setting("frscroll","signetconvert"),"signetd5");
		set_module_setting("frlastscroll",get_module_setting("frlastscroll","signetconvert"),"signetd5");
		set_module_setting("frlastone",get_module_setting("frlastone","signetconvert"),"signetd5");
		set_module_setting("frtitle",get_module_setting("frtitle","signetconvert"),"signetd5");
		set_module_setting("frtitle2",get_module_setting("frtitle2","signetconvert"),"signetd5");
		set_module_setting("frtitle3",get_module_setting("frtitle3","signetconvert"),"signetd5");
		set_module_setting("frtitle4",get_module_setting("frtitle4","signetconvert"),"signetd5");
		set_module_setting("frtitle5",get_module_setting("frtitle5","signetconvert"),"signetd5");
		set_module_setting("frtitle6",get_module_setting("frtitle6","signetconvert"),"signetd5");
		set_module_setting("frtitle7",get_module_setting("frtitle7","signetconvert"),"signetd5");
		set_module_setting("frtitle8",get_module_setting("frtitle8","signetconvert"),"signetd5");
		set_module_setting("frtitle9",get_module_setting("frtitle9","signetconvert"),"signetd5");
		set_module_setting("frtitle10",get_module_setting("frtitle10","signetconvert"),"signetd5");
		set_module_setting("frtitle11",get_module_setting("frtitle11","signetconvert"),"signetd5");

		output("Conversion Complete.  You may now Uninstall the signet Converter Module.");
		addnav("Navigation");
		addnav("Return to the Grotto","superuser.php");
		addnav("Manage Modules","modules.php");
		villagenav();
	}
page_footer();
}
?>