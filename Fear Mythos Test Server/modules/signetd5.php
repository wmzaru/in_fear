<?php
//Date: April 25, 2006
function signetd5_getmoduleinfo(){
	$info = array(
		"name"=>"Signet Maze 5: `%Dark Lair",
		"version"=>"5.22",
		"author"=>"DaveS",
		"category"=>"Signet Series",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		"settings"=>array(
			"Dark Lair Settings,title",
			"random"=>"How many random monsters can be encountered in the Dark Lair?,range,0,40,1|5",
			"randenc"=>"Likelihood of random encounter:,enum,1,Common,5,Uncommon,10,Rare,15,Very Rare,20,Extremely Rare|10",
			"healing"=>"Allow for players to have a chance to find a partial healing potion after fights?,bool|1",
			"finalmaploc"=>"Where does the Dark Lair appear?,location|".getsetting("villagename", LOCATION_FIELDS),
			"losegold"=>"`6Lose Percentage `^OR `6Amount `^of goldinbank for answering keypad wrong:,enum,0,Percentage,1,Amount|Percentage",
			"percentage"=>"`^Percentage of gold to lose from bank:,range,0,100,1|5",
			"numberlow"=>"`^Amount of gold to lose minimum from bank:,int|100",
			"numberhigh"=>"`^Amount of gold to lose maximum from bank:,int|1000",
			"goldwand"=>"`4Gold award after destroying the wand:,int|1000",
			"gemwand"=>"`4Gem award after destroying the wand:,int|2",
			"perexpr"=>"`@Percentage of experience lost if defeated by a Random Monster:,range,0,100,1|7",
			"perexpk"=>"`0Percentage of experience lost if defeated by a Black Warlock:,range,0,100,1|10",
			"exitsave"=>"Allow users to return to the dungeon from an emergency exit?,enum,0,No,1,Yes,2,Require|0",
			"`\$Note: If you chose 'Require' then players MUST come back in at the same location that they leave from; otherwise players will have a choice to come back through the main entrance or the exit location,note",
			"Dark Lord Settings,title",
			"perexpm"=>"`)Percentage of experience lost if defeated by Mierscri:,range,0,100,1|15",
			"dlhp"=>"`)Multiplier for Mierscri's hitpoints:,floatrange,1.0,3.5,0.1|1.6",
			"dlatt"=>"`)Multiplier for Mierscri's attack:,floatrange,1.0,2.5,0.1|1.6",
			"dldef"=>"`)Multiplier for Mierscri's defense:,floatrange,1.0,2.5,0.1|1.5",
			"Final Reward,title",
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
			"Title Options,title",
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
			"Dark Lair Preferences,title",
			"frhofnum"=>"What number to complete the series is this player?,int",
			"Dark Lair Allprefs,title",
			"allprefs"=>"Allprefs for Dark Lair:,textarea|",
			"Dark Lair Map,title",
			"maze"=>"Maze,viewonly",
			"pqtemp"=>"Temporary Information,int|",
		),
		"requires"=>array(
			"signetsale" => "5.01| by DaveS, http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=972",
		),
	);
	return $info;
}
function signetd5_install(){
	module_addhook("village");
	module_addhook("newday");
	module_addhook("newday-runonce");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	return true;
}
function signetd5_uninstall(){
	return true;
}
function signetd5_dohook($hookname,$args){
	global $session;
	$last=get_module_setting("frlastone");
	switch($hookname){
		case "newday-runonce":
			set_module_setting("frlastscroll",0);
		break;
		case "newday":
			$allprefs=unserialize(get_module_pref('allprefs'));
			if ($allprefs['announce']==1 && get_module_setting("frnewday")==1){
				output("`n`@`bHear Ye! Hear Ye! All rejoice for the Evil Mierscri has been destroyed by`b %s`@`b!`b`n",$last);
				$allprefs['announce']=0;
				set_module_pref('allprefs',serialize($allprefs));
			}
		break;
		case "village":
			$allprefs=unserialize(get_module_pref('allprefs'));
			$allprefss=unserialize(get_module_pref('allprefs','signetsale'));
			if ($session['user']['location'] == get_module_setting("finalmaploc") && (($allprefss['sigmap5']==1 && $allprefs['complete']==0)||get_module_pref("super","signetsale")==1)){
				tlschema($args['schemas']['tavernnav']);
				addnav($args['tavernnav']);
				tlschema();
				addnav("`)`bThe Dark Lair`b","runmodule.php?module=signetd5");
			}
			if ($session['user']['location']==get_module_setting("finalmaploc") && get_module_setting("frscroll")==1 && get_module_setting("frlastscroll")==1){
				require_once("lib/sanitize.php");
				$last = full_sanitize($last);
				output("`n");
				rawoutput("<marquee height=\"15\" width=\"100%\" onMouseover=\"this.stop()\" onMouseout=\"this.start()\" direction=left scrollamount=\"5\" style=\"Filter:Alpha(Opacity=50, FinishOpacity=100, Style=1, StartX=0, StartY=100, FinishX=0, FinishY=0); text-align:center\"><font class=body><font color=00FFFF>Hear Ye! Hear Ye! All rejoice for the</font><font color=FF0000> Evil Mierscri </font><font color=00FFFF>has been destroyed by</font><font color=66FF00> $last</font><font color=00FFFF>!</font><br></marquee>");
				output("`n");
			}
			if ($session['user']['location']==get_module_setting("finalmaploc") && get_module_setting("frscroll")==2 && get_module_setting("frlastscroll")==1){
				output("`n`@`b`cHear Ye! `#Hear Ye!`^ All rejoice for the `\$Evil M`)ierscri`^ has been destroyed by `@%s`^!`b`n`c",$last);
			}
		break;
		case "allprefs": case "allprefnavs":
			if ($session['user']['superuser'] & SU_EDIT_USERS){
				$id=httpget('userid');
				addnav("Signet Series");
				addnav("Signet Maze 5: `%Dark Lair","runmodule.php?module=signetd5&op=superuser&subop=edit&userid=$id");
			}
		break;
	}
	return $args;
}
function signetd5_run(){
	require_once("modules/signet/signetd5_func.php");
	include("modules/signet/signetd5.php");
}
?>
