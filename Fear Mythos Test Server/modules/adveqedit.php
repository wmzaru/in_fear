<?php

function adveqedit_getmoduleinfo(){
	$info = array(
		"name"=>"Advanced Equipment Editor",
		"author"=>"Chris Vorndran",
		"category"=>"Administrative",
		"version"=>"1.3",
		"download"=>"http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=50",
		"vertxtloc"=>"http://dragonprime.net/users/Sichae/",
		"settings"=>array(
			"Advanced Equipment Editor,title",
				"ahead"=>"How far ahead can equipment be created?,int|5",
				"dk"=>"Display DK Titles?,bool|1",
				"Turning this off should reduce server load.,note",
				"I turned it off once to test and it reduced queries on the Main Editor screen by several hundred.,note",
		),
		"prefs"=>array(
			"Advanced Equipment Editor Prefs,title",
				"access"=>"Does this user have access to the Editor?,bool|0",
		),
	);
	return $info;
}
function adveqedit_install(){
	module_addhook("superuser");
	return true;
}
function adveqedit_uninstall(){
	return true;
}
function adveqedit_dohook($hookname,$args){
	global $session;
	switch ($hookname){
		case "superuser":
			if ($session['user']['superuser'] & SU_EDIT_EQUIPMENT && get_module_pref("access")){
				addnav("Editors");
				addnav("Weapon/Armor Editor","runmodule.php?module=adveqedit");
				blocknav("armoreditor.php");
				blocknav("weaponeditor.php");
			}
			break;
		}
	return $args;
}
function adveqedit_run(){
	global $session;
	
	page_header("Equipment Editor");
	$op = httpget('op');
	if (!httppost('type'))
		$type = httpget('type');
	else
		$type = httppost('type');
	$level = httpget('level');
	$attr = ($type == "weapon"?"damage":"defense");
	$id = $type."id";
	$eq_name = $type."name";
	$db = ($type == "weapon"?"weapons":"armor");
	$attribute_tl = ($type == "weapon" ? translate_inline("Damage"):translate_inline("Defense"));
	$edit = translate_inline("Edit");
	$delete = translate_inline("Delete");
	$values = array(1=>48,225,585,990,1575,2250,2790,3420,4230,5040,5850,6840,8010,9000,10350);
	switch ($op){
		case "":
			if ($level == ""){
				output("`#Once you add a single weapon into a level, you will be able to add more weapon and armor into that level.`0`n`n");
				$mode = httpget('mode');
				switch ($mode){
					case "save":
						$subop = httpget('subop');
						if ($subop == "add"){
							$level_add = httppost('level');
							$attribute = httppost('attribute');
							$name = httppost('name');
							$value = $values[$attribute];
							$sql = "INSERT INTO ".db_prefix($db)." (level,$attr,$eq_name,value)
									VALUES (\"$level_add\",\"$attribute\",\"$name\",\"$value\")";
							db_query($sql);
							if (db_affected_rows())
								output("`c`^%s `#(`3%s`#) added at DK `^%s`#.`n`c",stripslashes($name),$type,$level_add);
						}elseif ($subop == "edit"){
							$all = httpallpost();
							$hidden_names = array();
							$names = array();
							$attribute = array();
							foreach($all AS $name => $value){
								$a = explode("-",$name);
								if ($a[0] == "hidden") array_push($hidden_names,$value);
								if ($a[0] == "attribute") array_push($attribute,$value);
								if ($a[0] == "name") array_push($names,$value);
							}
							$hidden_names = array_unique($hidden_names);
							$attribute = array_unique($attribute);
							$names = array_unique($names);
							$c = 0;
							foreach($hidden_names AS $key => $val){
								debug($key.": ".$val);
								$sql = "SELECT $id FROM ".db_prefix($db)." WHERE $eq_name='$val'";
								$res = db_query($sql);
								$row = db_fetch_assoc($res);
								if ($val != $names[$key]){ // Don't update if name hasn't changed.
									$sql = "UPDATE ".db_prefix($db)." 
									SET $eq_name='$names[$key]',
									$attr='$attribute[$key]'
									WHERE $id={$row[$id]}";
									$c++;
								}
								db_query($sql);
							}
							if ($c > 0) output("`c`^%s `#%s(s) updated at DK `^%s`#.`c`n",$c,$type,httppost('level'));
						}
						break;
				}
				$sql = "SELECT DISTINCT level FROM ".db_prefix("weapons")." ORDER BY level ASC";
				$res = db_query($sql);
				$ops = translate_inline("Ops");
				$eq_level = translate_inline("EQ Level");
				$count = translate_inline("Count (Weapons/Armor)");
				$title = translate_inline("DK Title (Male/Female)");
				$dk = get_module_setting("dk");
				rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' bgcolor='#999999'>");
				rawoutput("<tr class='trhead'><td>$ops</td><td>$eq_level</td><td>$count</td>".($dk?"<td>$title</td>":"")."</tr>");
				$i = 0;
				while ($row = db_fetch_assoc($res)){
					$i++;
					rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td style='text-align:center;'>");
					rawoutput("[ <a href='runmodule.php?module=adveqedit&level={$row['level']}'>");
					addnav("","runmodule.php?module=adveqedit&level=".$row['level']);
					output_notl($edit);
					rawoutput("</a> ]</td><td style='text-align:center;'>");
					output_notl($row['level']);
					rawoutput("</td><td style='text-align:center;'>");
					$row_w = db_fetch_assoc(db_query("SELECT count(*) AS c FROM ".db_prefix("weapons")." WHERE level={$row['level']}"));
					$row_a = db_fetch_assoc(db_query("SELECT count(*) AS c FROM ".db_prefix("armor")." WHERE level={$row['level']}"));
					output_notl("`b{$row_w['c']}`b / `b{$row_a['c']}`b");
					rawoutput("</td>");
					if ($dk){
						rawoutput("<td style='text-align:center;'>");
						require_once("lib/titles.php");
						$male = get_dk_title($row['level'],0);
						require_once("lib/titles.php");
						$female = get_dk_title($row['level'],1);
						output_notl("%s`0/%s`0",$male,$female);
						rawoutput("</td>");
					}
					rawoutput("</tr>");
				}
				rawoutput("</table>");
				addnav("Add Equipment","runmodule.php?module=adveqedit&op=add");
			}else{
				if (httpget('count')) output("`c`^%s `#items deleted.`n`c",httpget('count'));
				$sql = "SELECT * FROM ".db_prefix("weapons")." WHERE level=$level ORDER BY damage ASC";
				$res = db_query($sql);
				$attack = translate_inline("Attack");
				$name = translate_inline("Name");
				rawoutput("<form action='runmodule.php?module=adveqedit&op=modify&type=weapon&level=$level' method='post'>");
				addnav("","runmodule.php?module=adveqedit&op=modify&type=weapon&level=$level");
				rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' width='75%' bgcolor='#999999'>");
				rawoutput("<tr class='trhead'><td>$ops</td><td>$attack</td><td>$name</td></tr>");
				rawoutput("<tr><td colspan=3 class='trdark'>");
				rawoutput("<a href='runmodule.php?module=adveqedit&op=add&type=weapon&level=$level'>");
				output("Add New Weapon");
				rawoutput("</a></td></tr>");
				addnav("","runmodule.php?module=adveqedit&op=add&type=weapon&level=$level");
				$i = 0;
				while($row = db_fetch_assoc($res)){
					$i++;
					rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td style='text-align:center;'>");
					$del = "<input type='checkbox' name='val[{$row['weaponid']}]'/>";
					rawoutput($del);
					rawoutput("</td><td style='text-align:center;'>");
					output_notl($row['damage']);
					rawoutput("</td><td style='text-align:center;'>");
					output_notl($row['weaponname']);
					rawoutput("</td></tr>");
				}
				rawoutput("</table>");
				rawoutput("<div style='text-align:center;'>");
				rawoutput("<input type='submit' name='edit' class='button' value='$edit'/>&nbsp;");
				rawoutput("<input type='submit' name='delete' class='button' value='$delete'/>");
				rawoutput("</div>");
				rawoutput("</form>");
				
				$sql = "SELECT * FROM ".db_prefix("armor")." WHERE level=$level ORDER BY defense ASC";
				$res = db_query($sql);
				$defense = translate_inline("Defense");
				rawoutput("<form action='runmodule.php?module=adveqedit&op=modify&type=armor&level=$level' method='post'>");
				addnav("","runmodule.php?module=adveqedit&op=modify&type=armor&level=$level");
				rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' width='75%' bgcolor='#999999'>");
				rawoutput("<tr class='trhead'><td>$ops</td><td>$defense</td><td>$name</td></tr>");
				rawoutput("<tr><td colspan=3 class='trdark'>");
				rawoutput("<a href='runmodule.php?module=adveqedit&op=add&type=armor&level=$level'>");
				output("Add New Armor");
				rawoutput("</a></td></tr>");
				addnav("","runmodule.php?module=adveqedit&op=add&type=armor&level=$level");
				$i = 0;
				while($row = db_fetch_assoc($res)){
					$i++;
					rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td style='text-align:center;'>");
					$del = "<input type='checkbox' name='val[{$row['armorid']}]'/>";
					rawoutput($del);
					rawoutput("</td><td style='text-align:center;'>");
					output_notl($row['defense']);
					rawoutput("</td><td style='text-align:center;'>");
					output_notl($row['armorname']);
					rawoutput("</td></tr>");
				}
				rawoutput("</table>");
				rawoutput("<div style='text-align:center;'>");
				rawoutput("<input type='submit' name='edit' class='button' value='$edit'/>&nbsp;");
				rawoutput("<input type='submit' name='delete' class='button' value='$delete'/>");
				rawoutput("</div>");
				rawoutput("</form>");
			}
			break;
		case "add":
			rawoutput("<form action='runmodule.php?module=adveqedit&mode=save&subop=add' method='post'>");
			addnav("","runmodule.php?module=adveqedit&mode=save&subop=add");
			rawoutput("Equipment Type: <select name='type'>");
			rawoutput("<option value='weapon'>Weapon</option>");
			rawoutput("<option value='armor'>Armor</option>");
			rawoutput("</select><br/>");
			$level_grab = httpget('level');
			if ($level_grab){
				rawoutput("<input type='hidden' name='level' value='$level_grab'/>");
			}else{
				$tl_level = translate_inline("Dragonkill Level");
				rawoutput("$tl_level: <input type='text' name='level'/><br/>");
			}
			$name = translate_inline("Name");
			rawoutput("$name: <input type='text' name='name'/><br/>");
			$eq_tl = translate_inline("Equipment Level");
			rawoutput("$eq_tl: ".adveqedit_bar());
			rawoutput("<input type='submit' class='button' value='".translate_inline("Add")."'/></form>");
			break;
		case "modify":
			$val = httppost('val');
			debug($val);
			if (httppost('delete')){
				$sql = "DELETE FROM ".db_prefix($db)." WHERE $id IN ('".join("','",array_keys($val))."')";
				db_query($sql);
				$c = db_affected_rows();
				$level = httpget('level');
				redirect("runmodule.php?module=adveqedit&level=$level&count=$c");
			}elseif (httppost('edit')){
				$sql = "SELECT * FROM ".db_prefix($db)." WHERE $id IN ('".join("','",array_keys($val))."')";
				$a = translate_inline("ID");
				$res = db_query($sql);
				$name = translate_inline("Name");
				rawoutput("<form action='runmodule.php?module=adveqedit&mode=save&subop=edit&type=$type' method='post'>");
				addnav("","runmodule.php?module=adveqedit&mode=save&subop=edit&type=$type");
				rawoutput("<table border='0' cellpadding='2' cellspacing='1' align='center' width='75%' bgcolor='#999999'>");
				rawoutput("<tr class='trhead'><td>$a</td><td>$attribute_tl</td><td>$name</td></tr>");
				$i = 0;
				rawoutput("<input type='hidden' name='level' value='$level'/>");
				while($row = db_fetch_assoc($res)){
					$i = 0;
					rawoutput("<tr class='".($i%2?"trlight":"trdark")."'><td>");
					output_notl($row[$id]);
					rawoutput("</td><td>");
					rawoutput(adveqedit_bar($row[$id],$row[$attr]));
					rawoutput("</td><td>");
					rawoutput("<input type='hidden' name='hidden-{$row[$id]}' value=\"{$row[$eq_name]}\"/>");
					rawoutput("<input type='text' size='30' name='name-{$row[$id]}' value=\"".str_replace("`%","`5",$row[$eq_name])."\"/>");
					rawoutput("</td></tr>");
				}
				rawoutput("</table><br/>");
				rawoutput("<input type='submit' class='button' value='".translate_inline("Save")."'/></form>");
			}
			break;
	}
	addnav("Return");
	addnav("Main Editor","runmodule.php?module=adveqedit");
	require_once("lib/superusernav.php");
	superusernav();
	page_footer();
}
function adveqedit_bar($id = FALSE, $cur = FALSE){
	if ($id) 
		$doot = "attribute-$id";
	else
		$doot = "attribute";
	$bar = "<select name='$doot'>";
	for ($i = 1; $i <= 15; $i++){
		if ($cur)
			$bar .= ("<option value='$i'".($i==$cur?" selected":"").">".HTMLEntities("$i")."</option>");
		else
			$bar .= ("<option value='$i'>".HTMLEntities("$i")."</option>");
	}
	$bar .= "</select>";
	return $bar;
}
?>