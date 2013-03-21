<?php

//V. 20060618: Translation readyed the module, added fix by DaveS
//V. 20060621: Added two get_module_setting to $battle
//V. 20060731: Changed the robbedbank reset to a newday-runonce, translation readied mails
//V. 20060911: Corrected minimum values bug, added instance for when there was nothing to rob
 

function robbank_getmoduleinfo(){
	$info = array(
		"name"=>"Rob the Bank",
		"author"=>"<a href='http://www.sixf00t4.com' target=_new>Sixf00t4</a>, fixes by DaveS and SexyCook",
		"version"=>"20060911",
		"category"=>"Bank",
		"download"=>"http://dragonprime.net/users/sixf00t4/robbank.zip",
		"description"=>"Lets people rob the bank - recommended with jail module.",
		"vertxtloc"=>"http://dragonprime.net/users/sixf00t4/",
		"settings"=>array(
			"Bank robbing Settings,title",
				"robchance"=>"Chance out of 100 they will have the option to rob bank,in|25",
				"maxrobs"=>"How many times a day can they rob the bank? (0 for infinite),int|1",
				"mindk"=>"Minimum DK to be robbed?,int|0",
				"minlevel"=>"Minimum level to be robbed?,int|3",
				"mingold"=>"minimum gold in victims account to be robbed?,int|50",
				"robpercent"=>"Max percent of gold to take from each player robbed?,int|10",
				"maxgoldrobbed"=>"Max gold a player can lose?,int|10000",

			"bank guard settings,title",
				"runactive"=>"Can they run away from guard?,bool|1",				
				"runchance"=>"Chance out of 100 they'll be able to run?,int|10",
				"guardname"=>"Guard name,text|Old man McGee",
				"guardweapon"=>"Guard weapon,text|wrinkly old fists",
				"guardatk"=>"Multiplier of player's attack for guard attack,floatrange,.5,5,.1|1",
				"guarddef"=>"Multiplier of player's defense for guard def,floatrange,.5,5,.1|1",
				"guardhp"=>"Multiplier of player's maxHP for guard hp,floatrange,.5,5,.1|2",
   				"xploss"=>"XP loss per level on defeat,int|100",
			"allowspecial"=>"Allow players to use their specialty when fighting the guard?,bool|1",
			
			"Other module settings,title",
   				"tojail"=>"Guard sends player to jail instead of underworld?,bool|1",
				"robevil"=>"How much evil added if bank robbed?,int|20",				 
				"bounty"=>"How much bounty per level added?,int|1000",				 
		),
		"prefs"=>array(
			"Rob Bank Preferences,title",
				"robbedbank"=>"Attempts to rob bank today?,int|0",
				"mailonrob"=>"Receive mail when bank is robbed?,bool|1",
				),
	);
	return $info;
}
function robbank_install(){
//	module_addhook("newday");
	module_addhook("newday-runonce");
	module_addhook("footer-bank");
	return true;
	}
function robbank_uninstall(){
	return true;
}
function robbank_dohook($hookname,$args){
	global $session;
	switch ($hookname){
/*		case "newday":
			set_module_pref("robbedbank",0,"robbank");
		break;*/
		
		case "newday-runonce":
			$sql = "UPDATE ".db_prefix("module_userprefs")." SET value=0 WHERE setting='robbedbank' AND value=1";
			$res = db_query($sql);
			break;				
		
		case "footer-bank":
		$maxrobs=get_module_setting("maxrobs","robbank");
		$robbedbank=get_module_pref("robbedbank","robbank");
		$robchance=get_module_setting("robchance","robbank");
  		if( ($maxrobs==0) || ($maxrobs > $robbedbank) ){
			if ( (e_rand(1,100)) <= $robchance ){
				addnav("`$ Rob the bank`0","runmodule.php?module=robbank");
			}
		}
		break;	
	}
	return $args;
}
function robbank_run(){
	global $session;
	$op = httpget('op'); 
	page_header("Rob the Bank");
	
if ($op == "") {
			output("Bags upon bags of shiny gold lay on the floor.  Doing some quick calculations, you think you can at least make out with one of them.  You just have to make it past the guard.");
			addnav("Go for it","runmodule.php?module=robbank&op=rob");
			addnav("Not today","bank.php");
}
		
if ($op == "rob") {
			output("You wait for the guard to turn his back and snatch a bag of gold!  Now you just gotta figure a way out of the bank!");
			set_module_pref("wantedlevel",+1,"jail");
			addnav("Fight the guard","runmodule.php?module=robbank&op=fightguard");
			if ((get_module_setting("runactive"))>0){
				addnav("Run for it","runmodule.php?module=robbank&op=run");
			}
}
	
if ($op == "run") {
			if (e_rand(1,100)<=(get_module_setting("runchance"))){
				output("`#That was almost too easy!`0  You easily ran past the guard and everyone in the bank without even a scratch!  After you get to a safe place, you count your newly acquired fundage.");
			addnav("Count it already!","runmodule.php?module=robbank&op=count");
			}else{
				output("You tried to run, but the guard is ready for you.  There's only one way you're getting that gold, and that's through him!`n");
				addnav("Fight the guard!","runmodule.php?module=robbank&op=fightguard");
			}	
}
		
if ($op == "count") {

	require_once("lib/systemmail.php");
			
	$mindk=get_module_setting("mindk");
	$minlvl=get_module_setting("minlevel");
	$playername=$session['user']['name'];
	$mingold=get_module_setting("mingold");
	$robpercent=get_module_setting("robpercent");				 
	$mailonrob=get_module_setting("mailonrob");
	$maxgoldrobbed=get_module_setting("maxgoldrobbed");
	$accntid=$session['user']['acctid'];	
	$xploss=get_module_setting("xploss");
	$totalgold=0;	
	
			$sql = "SELECT acctid,goldinbank FROM " . db_prefix("accounts") . " where goldinbank>=$mingold and dragonkills>=$mindk and level>=$minlvl and acctid!=$accntid";
			$result = db_query($sql);
			if(db_num_rows($result)>0){
				for ($i=0;$i<db_num_rows($result);$i++){
					$row = db_fetch_assoc($result);
					$victim=$row['acctid'];
					$robper=((e_rand(0,$robpercent))*(0.01));  
				  $takengold=round(($row['goldinbank'])*($robper));
					if ($takengold > $maxgoldrobbed) $takengold = $maxgoldrobbed;
					$totalgold=$takengold+$totalgold;
					$sql2 = "UPDATE " . db_prefix("accounts") . "  SET goldinbank=goldinbank-$takengold WHERE acctid = $victim";
					db_query($sql2);
					$mailmessage = array("`^%s robbed the bank and has taken %s of your gold.", $playername, $takengold);
					systemmail($victim,array("`2The bank was robbed!"),$mailmessage);		   }
				addnews("`%%s`5 robbed the bank and got away with %s gold!",$playername,$totalgold);
				$session['user']['gold']+=$totalgold;
				output("You did quite well for yourself!  You collected %s gold from robbing the bank.  Account holders are now aware it was you though, and they may seek revenge!",$totalgold);
			}
			else{
				addnews("`%%s`5 tried to rob the bank, but run away only with a handfull of chocolate coins!",$playername);
				output("You gave it your best shot, but during those stressful minutes you seem to have taken not gold, but the chocolate gold coins the bank has shut up noisy kids. Oh well, at least they taste good.");
				$session['user']['hitpoints']=$session['user']['maxhitpoints'];
			}
			set_module_pref("robbedbank",+1,"robbank");
			if (is_module_active('dag')){
				$bounty=(get_module_setting("bounty"))*($session['user']['level']);
				$setdate = time();
				$sql = "INSERT INTO ". db_prefix("bounty") . " (amount, target, setter, setdate) VALUES ($bounty,$accntid,0,'".date("Y-m-d H:i:s",$setdate)."')";
				db_query($sql);
			}
			if (is_module_active('alignment')){
				$robevil=get_module_setting("robevil");
				set_module_pref('alignment',get_module_pref('alignment','alignment') - $robevil,'alignment');
			}			   
			addnav("Back to the village","village.php");		  
}
				
if ($op == "fightguard") {
			$guardatk = get_module_setting("guardatk");
			$guarddef = get_module_setting("guarddef");
			$guardhp = get_module_setting("guardhp");  
			$guardname = get_module_setting("guardname");		
			$guardweapon = get_module_setting("guardweapon");		
 
			$badguy = array(
				"creaturename"=>$guardname,
				"creatureweapon"=>$guardweapon,
				"creaturelevel"=>15,
				"creatureattack"=>round($session['user']['attack']*$guardatk),
				"creaturedefense"=>round($session['user']['defense']*$guarddef),
				"creaturehealth"=>round($session['user']['maxhitpoints']*$guardhp), 
				"diddamage"=>0,
			);
			$session['user']['badguy'] = createstring($badguy);
			$op = "fight";
}
if ($op == "fight"){
			$battle = true;
 }		   
if ($battle){

	$playername=$session['user']['name'];
	$guardname = get_module_setting("guardname");		
	  $xploss=get_module_setting("xploss");	
	
		include("battle.php");
		$session['user']['specialinc'] = "module:robbank";
			if ($victory){
			output("The guard is slain!  Quick, run away before anyone else is able to catch you!");
			addnav("Run and hide!","runmodule.php?module=robbank&op=count");
			$session['user']['specialinc']="";			
			}elseif($defeat){
				if(get_module_setting("tojail","robbank")){
					output("%s grabs the stolen bag of gold before delivering you straight to jail.",$guardname);
					addnews("%s was caught and sent to jail for trying to rob the bank!",$playername);
					addnav("To your cell","runmodule.php?module=jail");
										$session['user']['hitpoints'] = 1;
					$session['user']['specialinc']="";
					set_module_pref("injail",1,"jail");
				}else{
					output("%s grabs the stolen bag of gold before delivering the final blow to relinquish you of the burden of life.",$guardname);
					$session['user']['experience']-=$xploss;
					$session['user']['alive'] = false;
					$session['user']['hitpoints'] = 0;
					addnews("%s was caught and killed when trying to rob the bank!",$playername);
					addnav("To the shades","shades.php");
					$session['user']['specialinc']="";
				}
			}else{
			require_once("lib/fightnav.php");
			fightnav((bool)get_module_setting("allowspecial"),false,"runmodule.php?module=robbank");
			}
		}	

	page_footer();
}
?>
