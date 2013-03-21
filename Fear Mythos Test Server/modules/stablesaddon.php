<?php

function stablesaddon_getmoduleinfo(){
	$info = array(
		"name"=>"Stables addon",
		"version"=>"20070207",
		"author"=>"Sixf00t4-oh yeah, that XChrisX character bailed me out too...again",
		"category"=>"Cities",
		"description"=>"allows for stables to easily be added to villages without them",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=1199",
		"vertxtloc"=>"http://www.legendofsix.com/",
        "requires"=>array(
	       "cityprefs"=>"20051110|By Sixf00t4, available on DragonPrime",
        ), 
        "prefs-city"=>array(
			"addstable" => "Turn on the stable in this village?, bool|0",
			"stabletitle" => "What is the name of this stable?, text|Bertold's Bestiary",
            "stablelad" => "Name for a lad?, text|lad",
            "stablelass" => "Name for a lass?, text|lass",
            "stabledesc" => "Description:, textarea|`6Just outside the outskirts of the village, a training area and riding range has been set up.  Many people from all across the land mingle as Bertold, a strapping man with a wind-weathered face, extols the virtues of each of the creatures in his care.  As you approach, Bertold smiles broadly, \"`^Ahh!, how can I help you today, my %s?`6\", he asks in a booming voice.",
            "stablenosuchbeast" => "No such beast message:, textarea|`6\"`^I'm sorry, I don't stock any such animal.`6\", Bertold say apologetically.",
            "stablefinebeast1" => "First fine beast messages:, textarea|`6\"`^Yes, yes, that's one of my finest beasts!`6\", says Bertold.`n`n",
            "stablefinebeast2" => "Second fine beast messages:, textarea|`6\"`^Not even Merick has a finer speciman than this!`6\", Bertold boasts.`n`n",
            "stablefinebeast3" => "Third fine beast messages:, textarea|`6\"`^Doesn't this one have fine musculature?`6\", he asks.`n`n",
            "stablefinebeast4" => "Fourth fine beast messages:, textarea|`6\"`^You'll not find a better trained creature in all the land!`6\", exclaims Bertold.`n`n",
            "stablefinebeast5" => "Fifth fine beast messages:, textarea|`6\"`^And a bargain this one'd be at twice the price!`6\", booms Bertold.`n`n",
            "stabletoolittle" => "Too little message:, textarea|`6Bertold looks over the gold and gems you offer and turns up his nose, \"`^Obviously you misheard my price.  This %s will cost you `&%s `^gold  and `%%s`^ gems and not a penny less.`6\"",
            "stablerepmount" => "Replace mount message:, textarea|`6Patting %s`6 on the rump, you hand the reins as well as the money for your new creature, and Bertold hands you the reins of a `&%s`6.",
            "stablenewmount" => "New mount message:, textarea|`6You hand over the money for your new creature, and Bertold hands you the reins of a new `&%s`6.",
            "stablenofeed" => "No feed message:, textarea|`6\"`^I'm terribly sorry %s, but I don't stock feed here.  I'm not a common stable after all!  Perhaps you should look elsewhere to feed your creature.`6\"",
            "stablenothungry" => "Not hungry message:, textarea|`&%s`6 picks briefly at the food and then ignores it.  Bertold, being honest, shakes his head and hands you back your gold.",
            "stablehalfhungry" => "Half hungry message:, textarea|`&%s`6dives into the provided food and gets through about half of it before stopping.  \"`^Well, it wasn't as hungry as you thought.`6\" says Bertold as he hands you back all but %s gold.",
            "stablehungry" => "Hungry message:, textarea|`6%s`6 seems to inhale the food provided.  %s`6, the greedy creature that it is, then goes snuffling at Bertold's pockets for more food.`nBertold shakes his head in amusement and collects `&%s`6 gold from you.",
            "stablemountfull" => "Mount full message:, textarea|`n`6\"`^Well, %s, your %s`^ was truly hungry today, but we've got it full up now.  Come back tomorrow if it hungers again, and I'll be happy to sell you more.`6\", says Bertold with a genial smile.",
            "stablenofeedgold" => "No feed gold message:, textarea|`6\"`^I'm sorry, but that is just not enough money to pay for food here.`6\"  Bertold turns his back on you, and you lead %s away to find other places for feeding.",
            "stablemountsold" => "Mount sold message:, textarea|`6With but a single tear, you hand over the reins to your %s`6 to Bertold's stableboy.  The tear dries quickly, and the %s in hand helps you quickly overcome your sorrow.",
            "stableconfirmsale" => "Confirm sale message:, textarea|`n`n`6Bertold eyes your mount up and down, checking it over carefully.  \"`^Are you quite sure you wish to part with this creature?`6\"",
            "stableoffer" => "Offer message:, textarea|`n`n`6Bertold strokes your creature's flank and offers you `&%s`6 gold and `%%s`6 gems for %s`6.",
			),
    );
	return $info;
}

function stablesaddon_install(){
	module_addhook("villagetext");
	module_addhook("stabletext");
	module_addhook("stablelocs");
	return true;
}

function stablesaddon_uninstall(){
	return true;
}

function stablesaddon_dohook($hookname,$args){
	global $session;
	$city = $session['user']['location'];
    require_once("lib/nltoappon.php");
    require_once("modules/cityprefs/lib.php");

	switch($hookname){
	case "villagetext":
        $cityid=get_cityprefs_cityid("cityname",$city);
		$module=get_cityprefs_module("cityname",$city);
        if (get_module_objpref("city",$cityid,"addstable")==0) break;
			$args['stablename']=get_module_objpref("city",$cityid,"stabletitle");
			$args['schemas']['stablename'] = "module-$module";
			$args['gatenav']="Village Gates";
			$args['schemas']['gatenav'] = "module-$module";
			unblocknav("stables.php");
		break;
        
	case "stabletext":
        $cityid=get_cityprefs_cityid("cityname",$city);
		if (get_module_objpref("city",$cityid,"addstable")==0) break;
		$args['title'] = nltoappon(stripslashes(get_module_objpref("city",$cityid,"stabletitle")));
		$args['schemas']['title'] = "module-$module";
		$args['desc'] = nltoappon(stripslashes(get_module_objpref("city",$cityid,"stabledesc")));
		$args['schemas']['desc'] = "module-$module";
		$args['lad']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablelad")));
		$args['schemas']['lad'] = "module-$module";
		$args['lass']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablelass")));
		$args['schemas']['lass'] = "module-$module";
		$args['nosuchbeast']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablenobeast")));
		$args['schemas']['nosuchbeast'] = "module-$module";
        $fb1=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablefinebeast1")));
        $fb2=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablefinebeast2")));
        $fb3=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablefinebeast3")));
        $fb4=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablefinebeast4")));
        $fb5=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablefinebeast5")));
		$args['finebeast']=array($fb1,$fb2,$fb3,$fb4,$fb5);
		$args['schemas']['finebeast'] = "module-$module";
		$args['toolittle']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stabletoolittle")));
		$args['schemas']['toolittle'] = "module-$module";
		$args['replacemount']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablereplacemount")));
		$args['schemas']['replacemount'] = "module-$module";
		$args['newmount']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablenewmount")));
		$args['schemas']['newmount'] = "module-$module";
		$args['nofeed']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablenofeed")));
		$args['schemas']['nofeed'] = "module-$module";
		$args['nothungry']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablenothungry")));
		$args['schemas']['nothungry'] = "module-$module";
		$args['halfhungry']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablehalfhungry")));
		$args['schemas']['halfhungry'] = "module-$module";
		$args['hungry']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablehungry")));
		$args['schemas']['hungry'] = "module-$module";
		$args['mountfull']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablemountfull")));
		$args['schemas']['mountfull'] = "module-$module";
		$args['nofeedgold']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablegold")));
		$args['schemas']['nofeedgold'] = "module-$module";
		$args['confirmsale']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stableconfirmsale")));
		$args['schemas']['confirmsale'] = "module-$module";
		$args['mountsold']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stablemountsold")));
		$args['schemas']['mountsold'] = "module-$module";
		$args['offer']=nltoappon(stripslashes(get_module_objpref("city",$cityid,"stableoffer")));
		$args['schemas']['offer'] = "module-$module";
		break;
	case "stablelocs":
        //select all locations that have the additional stable turned on
        $sql="select objid from ".db_prefix("module_objprefs")." where objtype='city' and setting='addstable' and value=1";
        $result=db_query($sql);
        //enter those locations into the db for stable locs
        for ($i = 0; $i < db_num_rows($result); $i++){
            $row=db_fetch_assoc($result);
            tlschema("mounts");
            $city=get_cityprefs_cityname("cityid",$row['objid']);
            $args[$city]=sprintf_translate("The Village of %s", $city);
            tlschema();
        }
    break;
	}
	return $args;
}

function stablesaddon_run(){
}
?>
