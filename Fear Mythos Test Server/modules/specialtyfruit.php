<?php
function specialtyfruit_getmoduleinfo(){
	$info = array(
	"name"=>"Specialty - Fruit Thrower",
	"author"=>"`%Simon Welsh`#, using the specialties guide by Chris Vorndran",
	"version"=>"1.0.0",
//	"download"=>"http://dragonprime.net/",
	"vertxtloc"=>"http://simon.geek.nz/",
	"category"=>"Specialties",
	"settings"=>array(
		"Specialty - Fruit Thrower Settings,title",
		"mindk"=>"Minimum DK for specialty,int|5",
	),
	"prefs" => array(
		"Specialty - Fruit Thrower User Prefs,title",
		"skill"=>"Skill points in Fruit Thrower,int|0",
		"uses"=>"Uses of Fruit Thrower allowed,int|0",
		),
	);
	return $info;
}
function specialtyfruit_install(){
	module_addhook("choose-specialty");
	module_addhook("set-specialty");
	module_addhook("fightnav-specialties");
	module_addhook("apply-specialties");
	module_addhook("newday");
	module_addhook("incrementspecialty");
	module_addhook("specialtynames");
	module_addhook("specialtymodules");
	module_addhook("specialtycolor");
	module_addhook("dragonkill");
	return true;
}
function specialtyfruit_uninstall(){
	$sql = "UPDATE " . db_prefix("accounts") . " SET
specialty='' WHERE specialty='FT'";
	db_query($sql);
	return true;
}
function specialtyfruit_dohook($hookname,$args){
	global $session,$resline;
	$spec = "FT";
	$name = "Fruit Thrower";
	$ccode = "`4";

	switch ($hookname) {
		case "dragonkill":
			set_module_pref("uses", 0);
			set_module_pref("skill", 0);
		break;
		case "choose-specialty":
			if ($session['user']['dragonkills'] < get_module_setting("mindk")) break;
			if ($session['user']['specialty'] == "" || $session['user']['specialty'] == '0')
			{
				addnav("$ccode$name`0","newday.php?setspecialty=".$spec."$resline");
				$t1 = translate_inline("Fruit Throwers have never gone hungry`n");
				$t2 = appoencode(translate_inline("$ccode$name`0"));
				rawoutput("<a class='link'
href='newday.php?setspecialty=$spec$resline'>$t1
($t2)</a>");
				addnav("","newday.php?setspecialty=$spec$resline");
			}
		break;
		case "set-specialty":
			if($session['user']['specialty'] == $spec) {
				page_header($name);
				output("Growing up, you discovered that many different kinds of fruit can do	many different kinds of damage.");
				output("Never leaving home without your pockets stuffed full of fruit, you gave the local bully many a black eye.");
			}
		break;
		case "specialtycolor":
			$args[$spec] = $ccode;
		break;
		case "specialtynames":
			$args[$spec] = translate_inline($name);
		break;
		case "specialtymodules":
			$args[$spec] = "specialtyfruit";
		break;
		case "incrementspecialty":
			if($session['user']['specialty'] == $spec) {
				$new = get_module_pref("skill") + 1;
				set_module_pref("skill", $new);
				$name = translate_inline($name);
				$c = $args['color'];
				output("`n%sYou gain a level in `&%s%s to
`#%s%s!",$c, $name, $c, $new, $c);
				$x = $new % 3;
					if ($x == 0){
					
output("`n`^You gain an extra use point!`n");
					
set_module_pref("uses", get_module_pref("uses") + 1);
					}else{
						if (3-$x == 1) {
					
output("`n`^Only 1 more skill level until you gain an extra use point!`n");
					}else{
					
output("`n`^Only %s more skill levels until you gain an extra use point!`n",
(3-$x));
					}
				}
			output_notl("`0");
			}
		break;
		case "newday":
			$bonus = getsetting("specialtybonus", 1);
			if($session['user']['specialty'] == $spec) {
				$name = translate_inline($name);
				if ($bonus == 1) {
					output("`n`2For being interested in %s%s`2, you receive `^1`2 extra
`&%s%s`2 use for today.`n", $ccode, $name, $ccode, $name);
				}else{
					output("`n`2For being interested in %s%s`2, you receive `^%s`2 extra
`&%s%s`2 uses for today.`n", $ccode, $name, $bonus, $ccode, $name);
				}
			}
			$amt = (int)(get_module_pref("skill") / 3);
			if ($session['user']['specialty'] == $spec) $amt = $amt + $bonus;
			set_module_pref("uses", $amt);
		break;
		case "fightnav-specialties":
			$uses = get_module_pref("uses");
			$script = $args['script'];
			if ($uses > 0) {
				addnav(array("$ccode$name (%s points)`0", $uses), "");
				addnav(array("$ccode � Throw Apples`7 (%s)`0", 1),
$script."op=fight&skill=$spec&l=1", true);
			}
			if ($uses > 1) {
				addnav(array("$ccode � Drop Banana Peel`7 (%s)`0", 2),
$script."op=fight&skill=$spec&l=2",true);
			}
			if ($uses > 2) {
				addnav(array("$ccode � Eat Fruit`7 (%s)`0", 3),
$script."op=fight&skill=$spec&l=3",true);
			}
			if ($uses > 5) {
				addnav(array("$ccode � Fruit Cannon`7 (%s)`0", 6),
$script."op=fight&skill=$spec&l=6",true);
			}
		break;
		case "apply-specialties":
			$skill = httpget('skill');
			$l = httpget('l');
				if ($skill==$spec){
					if (get_module_pref("uses") >= $l){
						switch($l){
							case 1:
								apply_buff('ts1',array(
									"startmsg"=>"You take some apples out of your pockets",
									"rounds"=>5,
									"name"=>array("`\$Apple chucking"),
									"wearoff"=>"You ran out of apples!",
									"roundmsg"=>"You chuck an apple at {badguy}",
									"minioncount"=>1,
									"maxbadguydamage"=>get_module_pref("uses")*$session['user']['attack']+1,
									"minbadguydamage"=>1,
									"effectmsg"=>"Your apple hits {badguy} for {damage}!",
									"effectnodmgmsg"=>"Your apple missed!",
									"schema"=>"module-specialtyfruit"
								));
							break;
							case 2:
								apply_buff('ts2',array(
									"startmsg"=>"You take out a banana and peel it",
									"name"=>array("`^Dropped Peel"),
									"rounds"=>1,
									"wearoff"=>"{badguy} chucks the peel away!",
									"roundmsg"=>"{badguy} stands on the peel and falls over.`nIt is unable to defend or attack!",
									"badguydefmod"=>"0.1",
									"badguyatkmod"=>"0.1",
									"schema"=>"module-specialtyfruit"
								));
							break;
							case 3:
								apply_buff('ts3', array(
									"startmsg"=>"You start eating some fruit.",
									"name"=>array("Fruity goodness"),
									"rounds"=>5,
									"wearoff"=>"You finish digesting your fruit",
									"regen"=>ceil($session['user']['turns']/2),
									"effectmsg"=>"Your fruit gives you {damage} more health.",
									"effectnodmgmsg"=>"That bit of fruit wasn't as nice as the others.",
									"schema"=>"module-specialtyfruit"
								));
							break;
							case 6:
								apply_buff('ts6',array(
									"startmsg"=>"You build a cannon out of some fruit.",
									"name"=>array("Fruit Cannon"),
									"rounds"=>5,
									"wearoff"=>"You ran out of ammo!",
									"atkmod"=>ceil(($sesion['user']['charm']+$session['user']['level'])/4),
									"schema"=>"module-specialtyfruit"
								));
							break;
						}
						set_module_pref("uses", get_module_pref("uses") - $l);
					}else{
						apply_buff('ts0', array(
							"startmsg"=>"CHEATER!!!",
							"rounds"=>1,
							"atkmod"=>0,
							"defmod"=>0,
							"schema"=>"module-specialtyfruit",
						));
					}
				}
		break;
	}
	return $args;
}
?>