<?php
/*
* Date:         April 17, 2005
* Author:       Robert of MaddRio dot com
* LOGD VER:     Module for v1.0.2
*/
function ravenswoop_getmoduleinfo(){
     $info = array(
          "name"=>"Raven Swoop",
          "version"=>"1.0",
          "author"=>"`2Robert",
          "category"=>"Forest Specials",
          "download"=>"http://dragonprime.net/users/Robert/ravenswoop098.zip",
          );
     return $info;
}

function ravenswoop_install(){
        module_addeventhook("forest", "return 100;");
        return true;
}

function ravenswoop_uninstall(){
        return true;
}

function ravenswoop_dohook($hookname,$args){
        return $args;
}
function ravenswoop_runevent($type){
global $session;
output("`n`n`2A large Raven swoops down upon you.  `n`n ");
      switch (e_rand(1,8)){
      case 1: case 5: output("The bird poo's on your armour, you lost a charm point!");
       $session['user']['charm']--;
       break;
      case 2: case 6: output("The Raven strikes your arm, you are slightly injured!");
       $session['user']['hitpoints']=$session['user']['hitpoints']*.95;
       break;
      case 3: case 7: output("The Raven strikes your armour, you fall to the ground and lose a turn!");
       $session['user']['turns']--;
       break;
      case 4: case 8: output("The Raven strikes your head, you are injured quite a bit!");
       $session['user']['hitpoints']=$session['user']['hitpoints']*.75;
       break;
      }
}
function ravenswoop_run(){
}
?>