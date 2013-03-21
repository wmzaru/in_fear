<?php

function dragonpoints_getmoduleinfo(){
                $info = array(
                        "name"=>"Dragon points reset",
                        "author"=>"`)ShadowRaven",
                        "version"=>"1.0",
                        "category"=>"Lodge",
                        "download"=>"http://dragonprime.net/users/ShadowRaven/dragonpoints.zip",
                        "description"=>"Players can spend Donation points to reset their Dragon points and redistribute them..",

                "settings"=>array(
                        "Lodge Settings,title",
                        "cost"=>"Donation points needed to reset Dragon points,int|1000",
                        "limited"=>"Is this only for a limited time?,bool|1",
        ),
         "prefs" => array(
             "times"=>"How many times has this user reset points?,int|0",
         ),
        );
        return $info;
}
function dragonpoints_install(){
             module_addhook("lodge");
             module_addhook("pointsdesc");
return true;
}
function dragonpoints_uninstall(){
return true;
}
function dragonpoints_dohook($hookname,$args){
        global $session;
    $cost = get_module_setting("cost");
            switch ($hookname){
                   case "lodge":
                         addnav("Use Points");
                          $dp = ($session['user']['donation'] - $session['user']['donationspent']);
                          if ($dp >= $cost) addnav("Reset Dragon points","runmodule.php?module=dragonpoints&op=reset");
                          break;
                    case "pointsdesc":
                        $limited = get_module_setting("limited");
                        $cost = get_module_setting("cost");
                        $args['count']++;
                        $format = $args['format'];
                        if($limited==1){
                        $str = translate("Reset your Dragon points to redistribute. `#%s points `^**Limited time only.**`0",$cost);
                         }else{
                         $str = translate("Reset your Dragon points to redistribute. `#%s points`0",$cost);
                         }
                        $str = sprintf($str, get_module_setting("cost"));
                        output($format, $str, true);
                        break;
                }
        return $args;
}
function dragonpoints_run(){
        global $session;
        $op = httpget('op');
        $cost=get_module_setting("cost");
 if ($op == "reset") {
page_header("JCP's Hunter's Lodge");
        output("`&Clicking on `@'Reset Points'`& will reset your Dragon points to 0 and allow you to redistribute them.`n");
        if ($session['user']['level']<15){
        output("You must be at level 15 to use this funtion, please come back when you reach this level.");
        }else{
        addnav("`@Reset Points","runmodule.php?module=dragonpoints&op=reset2");
        }
addnav("Return to the Lodge","lodge.php");
page_footer();
 }
if ($op == "reset2") {
        page_header("JCP's Hunter's Lodge");
         $id = $session['user']['acctid'];
     $sql = "UPDATE " . db_prefix("accounts") . " SET dragonpoints='' WHERE acctid='$id'";
        db_query($sql);
      output("`&Your Points have been reset. Please click `@'Continue' `&to redistribute them.`n");
      addnav("`@Continue","village.php");
      $session['user']['donationspent']+=$cost;
       $times = get_module_pref("times");
        $set = $times+=1;
        set_module_pref("times",$set);
        $session['user']['lasthit']="0000-00-00 00:00";

        page_footer();
}
 }
?>
