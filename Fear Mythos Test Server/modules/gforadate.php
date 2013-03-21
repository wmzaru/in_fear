<?php
/*
Details:
 * This is a module to allow admin to let users get a gift of Gems on a certain day
*/
function gforadate_getmoduleinfo(){
	$d = date("Y-m-d");
	$info = array(
		"name"=>"Gift for a Date",
		"author"=>"`@CortalUX",
		"version"=>"1.0",
		"category"=>"General",
		"download"=>"http://www.dragonprime.net/users/CortalUX/gforadate.zip",
		"vertxtloc"=>"http://dragonprime.net/users/CortalUX/",
		"settings"=>array(
			"Gift for a Date - General Settings,title",
			"gems"=>"Gems in the reward?,int|3",
			"maxUser"=>"Maximum amount of users to get the reward?,int|0",
			"(0 for unlimited),note",
			"date"=>"What day do users get a reward?,dayrange,+90 days,+1 day|$d 00:00:00",
			"(may the 10th will automatically yield a reward),note",
			"count"=>"Users so far?,viewonly|0",
			"Gift for a Date - Reason Settings,title",
			"reason"=>"The reason for the reward?,textarea|`@Because!!",
		),
		"prefs"=>array(
			"Gift for a Date - Reward,title",
			"reward"=>"Got Gem reward?,bool|0",
		),
	);
	return $info;
}
function gforadate_install(){
	module_addhook("newday");
	return true;
}
function gforadate_uninstall(){
	return true;
}
function gforadate_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "newday":
			$date=strtotime(date("Y-m-d H:i:s",time()));
			$begin=strtotime(get_module_setting('date'));
			$end=strtotime(get_module_setting('date'))+86400;
			output_notl("`c");
			if (date("m-d",time())=="05-10") {
				if (get_module_pref('reward')==0) {
					$gems=get_module_setting('gems');
					if ($gems<=0) $gems=1;
					$session['user']['gems']+=$gems;
					if ($gems==1) {
						output("`n`@`iYou've been given a gift of `&a gem`@!`i");
					} elseif ($gems>1) {
						output("`n`@`iYou've been given a gift of `&%s gems`@!`i",$gems);
					}
					output("`n`^This is because `bit's CortalUX's birthday!!`b");
					set_module_pref('reward',1);
				} else {
					output("`n`b`@You've had your gift!`b");
				}
			} elseif ($date<=$end&&$date>=$begin&&get_module_setting('maxUser')==0||$date<=$end&&$date>=$begin&&get_module_setting('maxUser')==1&&get_module_setting('count')<get_module_setting('maxUser')) {
				set_module_setting('count',(get_module_setting('count')+1));
				if (get_module_pref('reward')==0) {
					$gems=get_module_setting('gems');
					if ($gems<=0) $gems=1;
					$session['user']['gems']+=$gems;
					if ($gems==1) {
						output("`n`@`i`@You've been given a gift of `Qa gem`@!`i");
					} elseif ($gems>1) {
						output("`n`@`i`@You've been given a gift of `Q%s gems`@!`i",$gems);
					}
					output("`n`^This is because... `&`b%s`b",get_module_setting("reason"));
					set_module_pref('reward',1);
				} else {
					output("`n`b`@You've had your gift!`b");
				}
			} elseif ($date<=$end&&$date>=$begin&&get_module_setting('maxUser')==1&&get_module_setting('count')>=get_module_setting('maxUser')) {
				output("`n`&No more gifts are being given out today...");
			} else {
				set_module_setting('count',0);
				set_module_pref('reward',0);
			}
			output_notl("`c");
		break;
	}
	return $args;
}
function gforadate_run(){
}
?>