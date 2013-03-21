<?php

function barrel_getmoduleinfo(){
	$info = array(
		"name"=>"The Barrel",
		"version"=>"20070321",
		"author"=>"<a href='http://www.sixf00t4.com' target=_new>`^Sixf00t4</a>",
		"category"=>"Village Specials",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1194",
		"description"=>"village event that has explosive potential.",
		"vertxtloc"=>"http://www.legendofsix.com/",
		"settings"=>array(
			"Barrel - Explosion Settings,title",
			"explode"=>"Percentage the barrel explodes,int|33",
			"killplayer"=>"Kill Player on explode,bool|1",
			"playerhp"=>"If no - percentage of hitpoints lost,int|50",
			//"killothers"=>"Kill others with explosion?,bool|1",
			//"hurtothers"=>"Hurt others with explosion?,bool|1",
			//"otherplayerhp"=>"If no - percentage of hitpoints lost,int|50",
			//"maxkill"=>"Max players to kill <i>0 for no limit</i>,int|5",
			"Barrel - General Settings,title",
			"visitmax"=>"Number of times allowed to visit the barrel,int|3",
			"mingold"=>"Min. amount of gold in the barrel,int|10",
			"maxgold"=>"Max. amount of gold in the barrel,int|10000",
			"rawchance"=>"Raw chance of seeing the barrel,range,5,50,5|25",
			"mingems"=>"Min. amount of gems in the barrel,int|2",
			"maxgems"=>"Max. amount of gems in the barrel,int|10",
		),
		"prefs"=>array(
			"Barrel - Preferences,title",
			"visits"=>"How many visits to the barrel has the player made today?,bool|0",
		),
	);
	return $info;
}

function barrel_seentest(){
	$visits=get_module_pref("visits","barrel");
	$visitmax=get_module_setting("visitmax","barrel");
	$chance=get_module_setting("rawchance","barrel")*(($visits)<($visitmax));
	return($chance);
}

function barrel_install(){
	module_addeventhook("village", "require_once(\"modules/barrel.php\"); return barrel_seentest();");
	module_addhook("newday");
}

function barrel_uninstall(){
	return true;
}

function barrel_dohook($hookname,$args){
	global $session;
	switch($hookname){
	case "newday":
		set_module_pref("visits",0);
		break;
	}
	return $args;
}

function barrel_runevent($type)
{
	global $session;
	$from = "village.php?";
	$session['user']['specialinc'] = "module:barrel";
	$op = httpget('op');

	switch($op){
	case "":
        page_header("The Barrel");
		output("`7Walking along through the village, you notice two men rolling a large barrel around to the back of the bank.  They stand it on end, and walk back the way they came.  They go into the inn, possibly for some ale, leaving you and your curiosity alone with the barrel.");
		addnav("Break it!",$from."op=break");
		addnav("Ignore the barrel",$from."op=walkaway");
		break;
	case "break":
        output("You let your curiosity get the best of you, and you decide to break open the barrel to see what's inside.  With a firm grip on your %s to the barrel and ",$session['user']['weapon']);
        $exploder=e_rand(1,100);
		set_module_pref("visits",get_module_pref("visits")+1);
        if($exploder<get_module_setting('explode')){
            output("`n`n`c`b`\$B`!O`^O`)M`!!`@!`&!`c`b`0`n`n");
            if(get_module_setting('killplayer')){
                output("That'll teach you to attack barrels full of sulfur, potassium nitrate, and charcoal!  You got all bloweded upped! ");
                $session['user']['alive']=0;
                $session['user']['hitpoints']=0;
                $session['user']['specialinc'] = "";
                addnav("To the shades","shades.php");
                addnews("%s was killed after attacking a barrel full of gun powder!",$session['user']['name']);
                debuglog("got blown up by the barrel.");
            }else{
                if(get_module_setting("turnloss")){
                output("A few minutes pass while you were blacked out.  You should really think about not fighting anything else today. ");
                $session['user']['turns']-=get_module_setting('turnslost');
                }
            output(" Besides a few cuts and bruises, you survived the exploding barrel.  Now, if you could just get rid of that ringing in your ears...");    
            $session['user']['hitpoints']-=(get_module_setting('playerhp')*.01)*$session['user']['hitpoints'];
            }
        }
        elseif($exploder<51){
            $gold=e_rand(get_module_setting('mingold'),get_module_setting('maxgold'));
            $session['user']['gold']+=$gold;
            output("outpours `^%s Gold!",$gold);
            $session['user']['specialinc'] = "";
            addnav("Return to village","village.php");
        }else{
            $gems=e_rand(get_module_setting('mingems'),get_module_setting('maxgems'));
            $session['user']['gems']+=$gems;
            output("outpours `@%s gems!",$gems);
            $session['user']['specialinc'] = "";
            addnav("Return to village","village.php");
            }   
		break;
	case "walkaway":
		output("`nYou decide that barrel busting just doesn't fit into your schedule today, and continue on your way.`n");
		$session['user']['specialinc'] = "";
		set_module_pref("visits",get_module_pref("visits")+1);
		break;
	}
}

function barrel_run(){
}
?>