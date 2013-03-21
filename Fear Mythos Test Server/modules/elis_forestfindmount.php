<?php
/**********************************
  2005 by Basilius/Eliwood
**********************************/
require_once "common.php";
require_once "lib/e_rand.php";
require_once "lib/buffs.php";


function elis_forestfindmount_getmoduleinfo()
{
  $info = array(
    "name"=>"Forestfindmount",
    "category"=>"Eliwoods Module",
    "author"=>"`QEliwood",
    "version"=>"1.01",
    "settings"=>array(
      "Forestfindmount,title",
      "catchchance"=>"Chance to catch the mount (Second argument in function e_rand),range,10,100,25",
      "sourcelang"=>"Your prefer language (When you don't would translate in german but you had e german server) ,enum,en,English,de,Deutsch"
    )
  );
  return $info;
}

function elis_forestfindmount_install()
{
  module_addeventhook("forest", "return 75;");
  return true;
}

function elis_forestfindmount_uninstall()
{
  return true;
}

function elis_forestfindmount_dohook($hookname,$args){
	return $args;
}

function elis_forestfindmount_runevent($type,$link)
{
	global $session;
  /* Define specialinc */
  $session['user']['specialinc'] = "module:elis_forestfindmount";
  /* Select all cols from table mounts, additional count of all mounts, smallest of mountid, highest of mountid */
  $mount = db_fetch_assoc(db_query("SELECT count(mountid) AS count, min(mountid) AS minmounts, max(mountid) AS maxmounts FROM ".db_prefix("mounts").""));
  /* define values */
  define("MINMOUNTS",$mount['minmounts']);
  define("MAXMOUNTS",$mount['maxmounts']);
  define("MOUNTCOUNT",$mount['count']);
  if(!isset($_GET['mountid'])) define("FINDMOUNTID",e_rand(MINMOUNTS,MAXMOUNTS));
  else define("FINDMOUNTID",$_GET['mountid']);
  /* Get mount per id */
  $special_mount_result = db_query("SELECT * FROM ".db_prefix("mounts")." WHERE mountid='".FINDMOUNTID."'");
  $special_mount = db_fetch_assoc($special_mount_result);
  debug(nl2br("Num of Rows: ".db_num_rows($special_mount_result)." \n ID: ".FINDMOUNTID." \n smalles ID: ".MINMOUNTS." \n highest ID: ".MAXMOUNTS." \n Totely mounts: ".MOUNTCOUNT));
  switch ($_GET['op']):
    case "":
    case "search":
      if("en" == get_module_setting("sourcelang"))
      {
        if($session['user']['hashorse'] == 0 && db_num_rows($special_mount_result)>0 && FINDMOUNTID!=0)
        {
          output("`3While you travel through the forest, you see a shade in some distance.`n");
          output("As you approach the shade, you can see that it is a young `^%s`3.`n",$special_mount['mountname']);
          output("You are confident that you can catch it.");
          addnav("Catch it","forest.php?op=catch&mountid=".$special_mount['mountid']);
          addnav("Forget it","forest.php?mountid=0");
        }
        else
        {
          if(FINDMOUNTID>0)
            output("`3While you travel through the forest, you see a shade in some distance.`n");
          output("`3But you think that you must defeat any creatures, and so you go back into the forest.`n`n");
          if($session['user']['turns']>0)
          {
            output("`#But you lose one forestfight!");
            $session['user']['turns'] -= 1;
          }
          $session['user']['specialinc'] = "";
        }
      }
      else
      {
        if($session['user']['hashorse'] == 0 && db_num_rows($special_mount_result)>0)
        {
          output("`3Während du durch den Wald ziehst, siehst du weit entfernt einen Schatten.`n");
          output("Du näherst dich dem Schatten und erkennst, dass der Schatten ein junges `^%s`3 ist.`n",$special_mount['mountname']);
          output("Nun stellt sich die Frage. Möchtest du es einfangen oder nicht?");
          addnav("Einfangen","forest.php?op=catch&mountid=".$special_mount['mountname']);
          addnav("Das ganze vergessen","forest.php?mountid=0");
        }
        else
        {
          output("`3Während du durch den Wald ziehst, siehst du weit entfernt einen Schatten.`n");
          output("Aber du denkst, dass du heute noch viele Monster zu besiegen hast, und so gehst du zurück in den Wald.`n`n");
          if($session['user']['turns']>0)
          {
            output("`#Aber nicht, ohne einen Waldkampf zu verlieren!");
            $session['user']['turns'] -= 1;
          }
          $session['user']['specialinc'] = "";
        }
      }
      break;
    case "catch":
      $session['user']['specialinc'] = "";
      if("en" == get_module_setting("sourcelang"))
      {
        output("`3You take a Rope and you try to catch `^%s`3.`n",$special_mount['mountname']);
        $rand = e_rand(0,get_module_setting("catchchance"));
        debug("Rand: $rand");
        if($rand > get_module_setting("catchchance")-2)
        {
          output("`3And the luck is with you, the rope has catchet it. You have a new mount, congratulations!");
          $session['user']['hashorse'] = $special_mount['mountid'];
          apply_buff("mount",unserialize($special_mount['mountbuff']));
        }
        else
        {
          output("`3But its not your day now. `^%s`3 has been flee, and you lose a forest fight.",$special_mount['mountname']);
          if($session['user']['turns']>0) $session['user']['turns']--;
        }
      }
      else
      {
        output("`3Du nimmst ein Seil und versuchst, `^%s`3 einzufangen.`n",$special_mount['mountname']);
        $rand = e_rand(0,get_module_setting("catchchance"));
        debug("Rand: $rand");
        if($rand > get_module_setting("catchchance")-2)
        {
          output("`3Und das Glück ist mit dir, das Seil schlingt sich um das Tier. Du bist nun im Besitz eines neuen Tieres, Gratulation!");
          $session['user']['hashorse'] = $special_mount['mountid'];
          apply_buff("mount",unserialize($special_mount['mountbuff']));
        }
        else
        {
          output("`3Aber heute ist nicht dein Tag. `^%s`3 ist geflüchtet, und du hast sogar noch einen Waldkampf verloren!",$special_mount['mountname']);
          if($session['user']['turns']>0) $session['user']['turns']--;
        }
      }
      break;
    endswitch;
}

function elis_forestfindmount_run()
{

}

?>
