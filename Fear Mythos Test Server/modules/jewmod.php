<?php

/*Version as an addon for the Jeweler module by Shannon Brown */

//v1.01 made owned jewelry only displayed when the player has jewelry
//v1.02 now for 1.1.0 ... tl-ready *cough* shortened the code, also added the coding scheme for 1.1.0 
//v1.03 took out the bioinfo as it gets displayed now in jeweler.php

function jewmod_getmoduleinfo(){
	$info = array(
	    "name"=>"Jeweler Addon - BioStats Display/Gift",
		"version"=>"1.01",
		"author"=>"Oliver Brendel - adds to Jeweler by Shannon Brown",
		"category"=>"Village",
		"download"=>"http://dragonprime.net/dls/jewmod.zip",
		"settings"=>array(
			"jewels_as_gifts"=>"Can a user send Jewel Items to a player as a gift?,bool|1",
			),
		"requires"=>array
			(
			"jeweler"=>"1.1 | Jeweler from Shannon Brown  -  Core Module",
	  		),
 		);
    return $info;
}

function jewmod_install()
{
	if (!is_module_installed("jeweler"))
	{
		output_notl("You must have installed the Jeweler Module, this is just an Add-on");
		return false;
	}
		if (!is_module_active("jewmod"))
		{
			debug("Installing Jeweler Add-On.`n");
		}
		else
		{
			debug("Updating Jeweler Add-On.`n");
		}
	module_addhook("footer-jeweler");
    return true;
}

function jewmod_uninstall()
{
  debug ("Performing Uninstall on Jeweler Add-On. Thank you for using!`n`n");
  return true;
}


function jewmod_dohook($hookname,$args)
{
	global $session;
	switch ($hookname)
	{
	case "footer-jeweler":
		tlschema("module-jeweler");
		$jewelry=array();
		if (get_module_pref("ringheld", "jeweler", $session['user']['acctid'])) array_push($jewelry,"ring");
		if (get_module_pref("braceletheld", "jeweler", $session['user']['acctid'])) array_push($jewelry,"bracelet");
		if (get_module_pref("necklaceheld", "jeweler", $session['user']['acctid'])) array_push($jewelry,"necklace");
		if (get_module_pref("amuletheld", "jeweler", $session['user']['acctid'])) array_push($jewelry,"amulet");
		if (get_module_pref("chokerheld", "jeweler", $session['user']['acctid'])) array_push($jewelry,"choker");
		if ($jewelry!=array() && get_module_setting("jewels_as_gifts","jewmod")) {
			addnav("Send a gift");
			while (list($key,$val)= each($jewelry)) {
				$translated=translate_inline(strtoupper(substr($val,0,1)).substr($val,1));
				addnav(array("Send %s as a gift",$translated),"runmodule.php?module=jewmod&op=donate&type=".$val);
			}
		}
		tlschema();
		break;
	}
	return $args;
}

function jewmod_run()
{
	global $session;
	$op=httpget('op');
	$type=httpget('type');
	switch ($op)
		{
		 case "donate":
		 	tlschema("module-jeweler");
			$translatedtype=translate_inline(strtoupper(substr($type,0,1)).substr($type,1));			
		 	page_header("Oliver, the Jeweler");
			output("`&`c`bOliver's Jewelry`b`c`n");
			//Finding a "target", code altered from Sichae's House Module
            $to = httppost("to");
            $nickname = httppost("nick");
            if ($to != "")
            {
                $to = explode ("|", $to, 2);
                $sql = "SELECT value FROM ".db_prefix("module_userprefs")." WHERE modulename='jeweler' AND userid='".$to[0]."' AND setting='".$type."held"."'";
                $result = db_query($sql);
                $numlines=db_num_rows ($result);
                $receivable=false;
                if ($numlines==0)
                    {
                    $receivable=true;
                    }
                    else
                    {
                    $row=db_fetch_assoc($result);
                    $val=$row['value'];
                    if ($val==0)
                    	$receivable=true;
                    	else
                    	$receivable=false;
                    }
                if ($receivable)
                	{
                    $sql = "REPLACE INTO ".db_prefix("module_userprefs")." (modulename,setting,userid,value) VALUES ('jeweler','".$type."held"."','".$to[0]."', '1')";
                    db_query($sql);
                    $sql = "SELECT value FROM ".db_prefix("module_userprefs")." WHERE modulename='jeweler' AND userid='".$to[0]."' AND setting='totalheld'";
 	                $result = db_query($sql);
                    $numlines=db_num_rows ($result);
					if ($numlines==0)
						{
						$tot=1;
						}
						else
						{
    	                $row=db_fetch_assoc($result);
        	            $tot=$row['value'];
						$tot++;
						}
                    $sql = "REPLACE INTO ".db_prefix("module_userprefs")." (modulename,setting,userid,value) VALUES ('jeweler','totalheld','".$to[0]."', '".$tot."')";
                    db_query($sql);
                    $mailsubject = array("%s has sent you an expensive present!`0",$session['user']['name']);
                    $mailbody = array("`^%s `^ has sent you a `%%s`^ as a present. Isn't that very nice?`n`n",$session['user']['name'],$translatedtype);
					require_once("lib/systemmail.php");
                  	systemmail($to[0],$mailsubject,$mailbody);
                    set_module_pref($type."held",0,"jeweler");
                    $tot=get_module_pref("totalheld","jeweler")-1;
                    set_module_pref("totalheld",$tot,"jeweler");
					$sql = "SELECT name FROM ".db_prefix("accounts")." WHERE acctid=".$to[0];
	              	$result = db_query($sql);
   	                $row=db_fetch_assoc($result);
       	            $recipientname=$row['name'];
                    output("Your %s will be delivered to %s.",$translatedtype,$recipientname);
                	}else
                	{
                    output ("`3This Player already has a %s!",$translatedtype);
                	}
            }elseif ($nickname != ""){
                $search="%";
                for ($x=0;$x<strlen($nickname);$x++){
                    $search .= substr($nickname,$x,1)."%";
                }
                $search=" AND name LIKE '".addslashes($search)."' ";
                $sql = "SELECT acctid,name,login FROM " . db_prefix("accounts") . " WHERE locked=0 $search ORDER BY level DESC, dragonkills DESC, login ASC $limit";
                $result = db_query($sql);
                if(db_num_rows($result)>=1){
                    rawoutput("<form action='runmodule.php?module=jewmod&op=donate&type=$type' method='POST'>");
                    output("`3Give %s to",$translatedtype);
                    $msg = translate_inline("Give");
                    rawoutput("<select name='to' class='input'>");
                    for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        rawoutput("<option value=\"".$row['acctid']."|".HTMLEntities($row['name'],ENT_COMPAT,getsetting("charset", "ISO-8859-1"))."\">".full_sanitize($row['name'])."</option>");
                    }
                    rawoutput("</select><input type='submit' class='button' value='$msg'></form>",true);
                }else{
                    output ("`3Sorry, no player found with that name.");
                }
            }else{
                output("Who would you like to give your %s to?`n",$translatedtype);
                $search = translate_inline("Search by name: ");
                $search2 = translate_inline("Search");

                rawoutput("<form action='runmodule.php?module=jewmod&op=donate&type=$type' method='POST'>$search<input name='nick'><input type='submit' class='button' value='$search2'></form>");
            }
            addnav("Return");
            addnav("Back to Oliver","runmodule.php?module=jeweler&op=");
            addnav("","runmodule.php?module=jewmod&op=donate&type=$type");
			tlschema();
			break;
		}
	output_notl("`0");
	page_footer();
}

?>