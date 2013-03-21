<?php
page_header("Roleplay Alignment Editor");
$op = httpget('op');
$run = "runmodule.php?module=rpalignment&case=superuser&op=";
require_once("modules/rpalignment/functions.php");

if ($op == "enter" || $op == "addnew"){
	addnav("Return");
	addnav("Grotto","superuser.php");
	villagenav();
	addnav("Navigation");
	$preset = get_module_setting("preset");
	addnav("Add New Alignment",$run."addnew");
	if (!$preset){
		addnav("Add Preset Alignments",$run."preset");
	}
	addnav("Refresh","runmodule.php?module=rpalignment&case=superuser&op=enter");
	addnav("Module Settings","configuration.php?op=modulesettings&module=rpalignment");
	if ($op == "enter"){
		output("`@Alignment Editor`n`n");
	}
	rawoutput("<table border=0><tr class='trhead'><td>ID</td><td>Alignment</td><td>Followers</td><td>Edit</td></tr>");
		$sql = "SELECT id,name FROM ".db_prefix("rpalignment")." ORDER BY id ASC";
		$res = db_query($sql);
		$i = 0;
		while ($row = db_fetch_assoc($res)){
			$i++;
			rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>".$row['id']."</td><td>".$row['name']."</td><td>".rpalign_followcount($row['id'])."</td><td>[<a href='".$run."edit&id=".$row['id']."'>Edit</a>] | [<a href='".$run."delete&id=".$row['id']."'>Delete</a>]</td></tr>");
			addnav("",$run."edit&id=".$row['id']);
			addnav("",$run."delete&id=".$row['id']);
		}
	rawoutput("</table>");
	db_query("ALTER TABLE ".db_prefix("rpalignment")." ORDER BY id ASC");
	modulehook("rpalignment-superuser_enter");
}

if ($op == "addnew"){
	output("`n`@Add new alignment.`n`n");
	$rpalign_data = $form_data = array();
	$alignmentarray = array(
		"id"=>"",
		"name"=>"",
	);
	$form = array(
		"Edit Alignment,title",
		"id"=>"Alignment ID,",
		"name"=>"Name,",
	);

	$rpalign_data = array_merge($rpalign_data, $alignmentarray);
	$form_data = array_merge($form_data, $form);
	require_once("lib/showform.php");
	rawoutput("<form action='".$run."addnew2' method='POST'>");
	addnav("",$run."addnew2");
	showform($form_data, $rpalign_data);
	rawoutput("</form>");
	addnav("Return",$run."enter");
	
}

if ($op == "addnew2"){
	$post = httpallpost();
//	$idnew = 1 + db_num_rows(db_query("SELECT * FROM ".db_prefix("rpalignment")));
	rpalign_create($post['name'],$post['id']);
	output("`@Alignment created!");
	addnav("Return",$run."enter");
}

if ($op == "delete"){
	$id = httpget('id');
	rpalign_delete($id);
	addnav("Return",$run."enter");
}

if ($op == "edit"){
	$save = httpget('save');
	$id = httpget('id');
	if ($save == 1){
		$post = httpallpost();
		rpalign_update($post['name'],$id);
		output("`^Alignment saved!`n`0");
	}
	output("`@Alignment Editor.");
	$info = rpalign_info($id,false);
	
	$rpalign_data = $form_data = array();
	$deity = array(
		"id"=>$id,
		"name"=>$info['name'],
		"follow"=>rpalign_followcount($id),
	);
	$form = array(
		"Edit Alignment,title",
		"id"=>"Alignment ID,viewonly",
		"name"=>"Name,",
	);

	$alignmentarray = array(
		"id"=>"",
		"name"=>"",
	);
	
	$rpalign_data = array_merge($rpalign_data, $alignmentarray);
	$form_data = array_merge($form_data, $form);
	
	require_once("lib/showform.php");
	rawoutput("<form action='".$run."edit&id=$id&save=1' method='POST'>");
	addnav("",$run."edit&id=$id&save=1");
	showform($form_data, $rpalign_data);
	rawoutput("</form>");
	addnav("Return",$run."enter");
	module_editor_navs("prefs-alignfollow","runmodule.php?module=rpalignment&case=superuser&op=editmodule&dt=".$id."&mdule=");
	modulehook("rpalignment-superuser_edit");
}

if ($op == "editmodule" || $op == "editmodulesave"){
	$mdule = httpget("mdule");
	$dt = httpget('dt');
	require_once("lib/showform.php");
	if ($op=="editmodulesave") {
		// Save module prefs
		$post = httpallpost();
		reset($post);
		while(list($key, $val) = each($post)) {
			set_module_objpref("alignfollow", $dt, $key, $val, $mdule);
		}
		output("`^Saved!`0`n");
	}
	rawoutput("<form action='runmodule.php?module=rpalignment&case=superuser&op=editmodulesave&dt=$dt&mdule=$mdule' method='POST'>");
	module_objpref_edit("alignfollow", $mdule, $dt);
	rawoutput("</form>");
	addnav("","runmodule.php?module=rpalignment&case=superuser&op=editmodulesave&dt=$dt&mdule=$mdule");
	addnav("Return",$run."enter");
	addnav("Edity Alignment",$run."edit&id=$dt");
	module_editor_navs("prefs-alignfollow","runmodule.php?module=rpalignment&case=superuser&op=editmodule&dt=".$id."&mdule=");
}

if ($op == "preset"){
	$sql1 = "INSERT INTO ".db_prefix("rpalignment")." (name,id) VALUES ('Good',1)";
	$sql2 = "INSERT INTO ".db_prefix("rpalignment")." (name,id) VALUES ('Neutral',2)";
	$sql3 = "INSERT INTO ".db_prefix("rpalignment")." (name,id) VALUES ('Evil',3)";
	db_query($sql1);
	db_query($sql2);
	db_query($sql3);
	set_module_setting("preset",1);
	output("`b`^Inserted!`b");
	addnav("Return");
	addnav("Return to Editor","runmodule.php?module=rpalignment&case=superuser&op=enter");
}

page_footer();
?>