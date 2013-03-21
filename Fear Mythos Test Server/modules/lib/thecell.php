<?php
	global $session;
	$op = httpget('op');
	if ($op == "nonono"){
		popup_header("Restricted!");
		output("Access Restriced from the Cell!");
		popup_footer();
	}else{
	$user = httpget('user');
	$whom=$_POST['whom'];
	require_once("lib/commentary.php");
	set_module_pref('commons',1);
	page_header("The Cell");
	output("`c`b`&Interogation Room`0`b`c`n`n");
	if ($op == ""){
	set_module_pref('location',$session['user']['location']);
	$session['user']['location']="";
	if (get_module_pref('incell')) output("`4You have done something wrong or you wouldn't be here!`n`#");
	if (get_module_pref('thecell')){
		output("`3Who have we here.`n`#");
		$sql1 = "SELECT userid FROM ".db_prefix("module_userprefs")." WHERE value = '".get_module_pref('commons')."' and modulename = 'thecell' and setting = 'commons' and userid <> '".$session['user']['acctid']."'";
		$result1 = db_query($sql1);
		$sql2 = "SELECT name FROM ".db_prefix("accounts")." WHERE loggedin = 1 and name <> \"".$session['user']['name']."\" and laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",300)." seconds"))."'";
		$result2 = db_query($sql2);
		$k=db_num_rows($result2);
		for ($i=0;$i<db_num_rows($result1);$i++){
		    $row1 = db_fetch_assoc($result1);
			$sql = "SELECT name,acctid,location FROM ".db_prefix("accounts")." WHERE loggedin = 1 and acctid = '".$row1['userid']."' and name <> \"".$session['user']['name']."\" and laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",300)." seconds"))."' LIMIT 1";
		    $result = db_query($sql);
			$row = db_fetch_assoc($result);
			if ($row['name'] <> $session['user']['name']){
				$inchat = $row['name'];
				$j+=1;
				if (get_module_pref('incell','thecell',$row['acctid'])){
					$linkcode="`&[<a href=\"runmodule.php?module=thecell&op=letout&user=".$row['acctid']."\"><span style=\"color: rgb(0, 204, 204);\">Let Out</span></a>`&]`2-$inchat`7`n";
					output("%s",$linkcode,true);
					addnav("","runmodule.php?module=thecell&op=letout&user=".$row['acctid']);
				}else{
					output("%s`7`n",$inchat);	
				}
			}
		}
	if ($j==0) output("`2No one`7..`6");
	output("`n`2-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-`n");
	}
	addcommentary();
	viewcommentary("thecell","Talk",20,"says");
	addnav("Refresh","runmodule.php?module=thecell");
	if (get_module_pref('thecell')){
		output("`n`nSearch for Name of user to put in cell`n");
		$linkcode="<form action='runmodule.php?module=thecell&op=send2' method='POST'>";
		output("%s",$linkcode,true);
		$linkcode="<p><input type=\"text\" name=\"whom\" size=\"37\"></p>";
		output("%s",$linkcode,true);
		$linkcode="<p><input type=\"submit\" value=\"Submit\" name=\"B1\"><input type=\"reset\" value=\"Reset\" name=\"B2\"></p>";
		output("%s",$linkcode,true);
		$linkcode="</form>";
		output("%s",$linkcode,true);
		addnav("","runmodule.php?module=thecell&op=send2");
	}
	if (!get_module_pref('incell')) addnav("Return to the Village","runmodule.php?module=thecell&op=village");
	if (get_module_pref('thecell')) addnav("Return to the Grotto","runmodule.php?module=thecell&op=grotto");
}
	if ($op == "send2" ){
		$sql = "SELECT login,name,level,acctid FROM ".db_prefix("accounts")." WHERE name LIKE '%".$whom."%' and acctid <> '".$session['user']['acctid']."' ORDER BY level,login LIMIT 100";
		$result = db_query($sql);
		    if (db_num_rows($result) < 1) output ("No on matching that name found.");
				output("Choose who to send to the cell:`n");
		        output("<table cellpadding='3' cellspacing='0' border='0'>",true);
		        output("<tr class='trhead'><td>Name</td><td>Level</td></tr>",true);
		          for ($i=0;$i<db_num_rows($result);$i++){
			      $row = db_fetch_assoc($result);
			      $linkcode="<tr class='".($i%2?"trlight":"trdark")."'><td><a href='runmodule.php?module=thecell&op=send3&user=".$row['acctid']."'>";
			      output("%s",$linkcode,true);
			      output("%s",$row['name']);
			      $linkcode="</a></td><td>";
			      output("%s",$linkcode,true);
			      output("%s",$row['level']);
			      $linkcode="</td></tr>";
			      output("%s",$linkcode,true);
			      addnav("","runmodule.php?module=thecell&op=send3&user=".$row['acctid']);
		          }
		          output("</table>",true);
		          output("`n");
		          addnav("Go Back","runmodule.php?module=thecell");
	}
	if ($op == "send3" ){
		set_module_pref('incell',1,'thecell',$user);
		redirect("runmodule.php?module=thecell");
	}
	if ($op == "village" ){
		set_module_pref('commons',0);
		$session['user']['location'] = get_module_pref('location');
		set_module_pref('location',"");
		redirect("village.php");	
	}
	if ($op == "grotto" ){
		set_module_pref('commons',0);
		$session['user']['location'] = get_module_pref('location');
		set_module_pref('location',"");
		redirect("superuser.php");	
	}
	if ($op == "letout" ){
		set_module_pref('incell',0,'thecell',$user);
		redirect("runmodule.php?module=thecell");
	}
	if ($op == "restrict" ){
		redirect("runmodule.php?module=thecell");
	}
	page_footer();
}
?>