<?php
//module builder - put each builder in as a library.... store the module info in prefs as an array
function modulebuilder_getmoduleinfo(){
	global $session;
	$info = array(
		"name"=>"Module Builder",
		"author"=>"Lonny Luberts, modified and completed by `i`)Ae`7ol`&us`i`0",
		"version"=>"0.57+Aeo",
		"category"=>"PQ",
		"download"=>"http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=175",
		"vertxtloc"=>"http://www.pqcomp.com/",
		"prefs" => array(
			"suaccess"=>"Access to Module Builder?,bool",
			"author"=>"Author Name,text|".$session['user']['name'],
			"usever"=>"Use version file?,bool",
			"vertxtloc"=>"Version file location.,text",
			"downfold"=>"Download folder.,text|http://www.pqcomp.com/modules/mydownloads/visit.php?cid=3&lid=175",
			"delupload"=>"Delete file after upload?,bool|1",
		),
	);
	return $info;
}

function modulebuilder_install(){
	module_addhook("superuser");
	return true;
}

function modulebuilder_uninstall(){
	return true;
}

function modulebuilder_dohook($hookname, $args){	
	if (get_module_pref("suaccess") == "1"){
		addnav("Editors");
		addnav("Module Builder", "runmodule.php?module=modulebuilder");
	}
	return $args;
}

function modulebuilder_run(){
	global $session;
	page_header("Module Builder");
	addnav("Mechanics");
	addnav("`2Set your info","runmodule.php?module=modulebuilder&op=setinfo");
	addnav("`2Saved Modules","runmodule.php?module=modulebuilder&op=savedmodules");
	addnav("Build Modules");
	addnav("`2City","runmodule.php?module=modulebuilder&op=town");
	addnav("`2Race","runmodule.php?module=modulebuilder&op=race");
	addnav("`2Specialty","runmodule.php?module=modulebuilder&op=specialty");
	addnav("`2Special Event","runmodule.php?module=modulebuilder&op=event");
	addnav("`2Book for Library","runmodule.php?module=modulebuilder&op=book");
	addnav("`2Quest for Questpack","runmodule.php?module=modulebuilder&op=quest");
	// addnav("`4Dag Quest","runmodule.php?module=modulebuilder&op=dagquest");
	modulehook("modulebuilder",array());
	
	foreach ($_GET  as $key => $value) $$key = $value;
	foreach ($_POST as $key => $value) $$key = $value;
	
	if ($op == "setinfo") {
		if ($author == ""){
			if ($author <> "") output("`4Required Information Missing! Try Again!`n");
			if (get_module_pref("author") <> "") $author = get_module_pref("author");
			if (get_module_pref("verloc") <> "") $verloc = get_module_pref("verloc");
			if (get_module_pref("downloc") <> "") $downloc = get_module_pref("downloc");
			if (get_module_pref("delupload") <> "") $delupload = get_module_pref("delupload");
			if (get_module_pref("usever") == 1){
				$useveryes = " selected='selected'";
			}else{
				$useverno = " selected='selected'";
			}
			rawoutput("<form action='runmodule.php?module=modulebuilder&op=setinfo' method='post'>");
			rawoutput("Author Name: <input value='".$author."' size='40' name='author'><br>");
			rawoutput("Use Version File? <select name='vertext'><option".$useveryes." value='1'>Yes</option><option".$useverno."  value='0'>No</option></select><br>");
			rawoutput("Version File Location: <input value='".$verloc."' size='50' name='verloc'><br>");
			rawoutput("Download Folder: <input value='".$downloc."' size='50' name='downloc'><br>");
			rawoutput("Delete file after upload?: <select name='delupload'><option value='0'".($delupload?"":" selected").">No</option><option value='1'".($delupload?" selected":"").">Yes</option></select><br>");
			rawoutput("<input name='Submit' value='Submit' type='submit'></form>");
			addnav("","runmodule.php?module=modulebuilder&op=setinfo");
		}else{
			//save the data
			set_module_pref("author", $author);
			set_module_pref("usever", $vertext);
			set_module_pref("vertxtloc", $verloc);
			set_module_pref("downfold", $downloc);
			set_module_pref("delupload", $delupload);
			output("Saved Settings");
		}
	} elseif ($op == "savedmodules") {
		$ar = array("race"=>"race", "book"=>"book_", "event"=>"", "quest"=>"quest_", "specialty"=>"specialty_", "town"=>"");
		// manage saved modules... load them, delete them
		// save to an array... first value stores the builder it is for... save as a pref....
		$fgc = file_get_contents("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}.txt");
		$fgd = file_get_contents("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}_vals.txt");
		$savedmodules = unserialize($fgc);
		$savedvals	  = unserialize($fgd);
		if ($mop){
			list ($mopp, $mid, $mid2) = explode(":", $mop);
			switch ($mopp){
				case "del":
					unset($savedvals[$mid][$mid2]);
					unset($savedmodules[$mid][$mid2]);
					if (!count($savedvals[$mid])) unset($savedvals[$mid]);
					if (!count($savedmodules[$mid])) unset($savedmodules[$mid]);
					output("`@Deleted!`0`n");
				break;
				case "edt":
					output("`b`QPreparing module`n...`n...`n...`n...`n`n`b`0");
					$fgd = file_get_contents("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}_vals.txt");
					$postvals = unserialize($fgd);
					$pvals = $postvals[$mid][$mid2];
					rawoutput("<form method='post' action='runmodule.php?module=modulebuilder&op=".$mid."' name='".$mid."builder'>");
					foreach ($pvals as $key => $value){
						rawoutput("<input type='hidden' name='".$key."' value='".$value."'>");
					}
					rawoutput("<input name='editmodule' value='Edit Module' type='submit'></form><br />");
					addnav("","runmodule.php?module=modulebuilder&op=".$mid."");
				break;
				case "dld":
					$file = "./modules/".$ar[$mid].$mid2.".php";
					
					$mod = $savedmodules[$mid][$mid2];
					$mod = str_replace('\"', '"', $mod);
					$mod = str_replace("\'", "'", $mod);
					$mod = preg_replace('#<br\s*/?>#i', "", $mod);
					$f = fopen($file,"w+");
					fwrite($f,"<?php\n".$mod."\n?>");
					fclose($f);
					
					header('Content-Description: File Transfer');
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename='.basename($file));
					header('Content-Transfer-Encoding: binary');
					header('Expires: 0');
					header('Cache-Control: must-revalidate');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));
					ob_clean();
					flush();
					readfile($file);
					
					unlink($file);
				break;
				case "uld":
					$mod = $savedmodules[$mid][$mid2];
					$mod = str_replace('\"', '"', $mod);
					$mod = str_replace("\'", "'", $mod);
					$mod = preg_replace('#<br\s*/?>#i', "", $mod);
					$f = fopen("./modules/".$ar[$mid].$mid2.".php","w+");
					fwrite($f,"<?php\n".$mod."\n?>");
					fclose($f);
					if (get_module_pref("delupload")){
						unset($savedvals[$mid][$mid2]);
						unset($savedmodules[$mid][$mid2]);
						if (!count($savedvals[$mid])) unset($savedvals[$mid]);
						if (!count($savedmodules[$mid])) unset($savedmodules[$mid]);
					}
					output("`@Uploaded to ./modules folder as a module!`0`n");
				break;
			}
		}
		
		$f = fopen("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}.txt","w+");
		fwrite($f,serialize($savedmodules));
		fclose($f);
		
		$g = fopen("./modules/modulebuilder/savedmodules/{$session['user']['acctid']}_vals.txt","w+");
		fwrite($g,serialize($savedvals));
		fclose($g);
		
		rawoutput("<table border='0' cellpadding='2' cellspacing='2'>");
		if (!is_array($savedmodules) || !count($savedmodules)){
			output("`&No saved modules!");
		} else {
			foreach ($savedmodules as $key => $arr){
				$i = 0;
				rawoutput("<tr class='trhead'><td colspan='2' style='text-align:center;'>");
					output_notl("`@`b%s`b`0",ucfirst($key));
				rawoutput("</td></tr>");
				foreach ($arr as $key2 => $module){
					rawoutput("<tr class='".($i%2?'trlight':'trdark')."'><td>");
						output("%s",$key2);
					rawoutput("</td><td>");
						rawoutput("[<a href='runmodule.php?module=modulebuilder&op=savedmodules&mop=del:$key:$key2' onClick='return confirm(\"Do you wish to delete the module named $key2?\");'>Del</a>] |");
						rawoutput("[<a href='runmodule.php?module=modulebuilder&op=savedmodules&mop=edt:$key:$key2'>Edit</a>] |");
						rawoutput("[<a href='runmodule.php?module=modulebuilder&op=savedmodules&mop=dld:$key:$key2'>Download</a>] |");
						rawoutput("[<a href='runmodule.php?module=modulebuilder&op=savedmodules&mop=uld:$key:$key2'>Upload</a>]");
						addnav("","runmodule.php?module=modulebuilder&op=savedmodules&mop=del:$key:$key2");
						addnav("","runmodule.php?module=modulebuilder&op=savedmodules&mop=edt:$key:$key2");
						addnav("","runmodule.php?module=modulebuilder&op=savedmodules&mop=dld:$key:$key2");
						addnav("","runmodule.php?module=modulebuilder&op=savedmodules&mop=uld:$key:$key2");
					rawoutput("</td></tr>");
					$i++;
				}
			}
		}
		rawoutput("</table>");
	} elseif ($op != "") {
		include_once("modules/modulebuilder/".$op."builder.php");
	}
	
	addnav("Navigation");
	villagenav();
	addnav("Return to Grotto","superuser.php");
	page_footer();
}
?>