<?php
$op=httpget('op');
global $session;
$dks=$session['user']['dragonkills'];
$mapmaker = get_module_setting("cartographername");
$storename=get_module_setting("storename");
$header = color_sanitize($storename);
if ($op!="locations") page_header("%s",$header);
if ($op!="superuser" && $op!="locations") output("`n`c`b%s`b`@`c`n",$storename);

if ($op=="superuser"){
	require_once("modules/allprefseditor.php");
	allprefseditor_search();
	page_header("Allprefs Editor");
	$subop=httpget('subop');
	$id=httpget('userid');
	addnav("Navigation");
	addnav("Return to the Grotto","superuser.php");
	villagenav();
	addnav("Edit user","user.php?op=edit&userid=$id");
	modulehook('allprefnavs');
	if ($subop!='edit'){
		$allprefse=unserialize(get_module_pref('allprefs','cartographer',$id));
		for ($i=1;$i<=20;$i++) {
			$allprefse['hasmap'.$i]=httppost('hasmap'.$i);
		}
		set_module_pref('allprefs',serialize($allprefse),'cartographer',$id);
		output("Allprefs Updated`n");
		$subop="edit";
	}
	if ($subop=="edit"){
		require_once("lib/showform.php");
		$form = array(
			"Cartographer,title",
			"hasmap1"=>"Has a map to Location 1?,bool",
			"hasmap2"=>"Has a map to Location 2?,bool",
			"hasmap3"=>"Has a map to Location 3?,bool",
			"hasmap4"=>"Has a map to Location 4?,bool",
			"hasmap5"=>"Has a map to Location 5?,bool",
			"hasmap6"=>"Has a map to Location 6?,bool",
			"hasmap7"=>"Has a map to Location 7?,bool",
			"hasmap8"=>"Has a map to Location 8?,bool",
			"hasmap9"=>"Has a map to Location 9?,bool",
			"hasmap10"=>"Has a map to Location 10?,bool",
			"hasmap11"=>"Has a map to Location 11?,bool",
			"hasmap12"=>"Has a map to Location 12?,bool",
			"hasmap13"=>"Has a map to Location 13?,bool",
			"hasmap14"=>"Has a map to Location 14?,bool",
			"hasmap15"=>"Has a map to Location 15?,bool",
			"hasmap16"=>"Has a map to Location 16?,bool",
			"hasmap17"=>"Has a map to Location 17?,bool",
			"hasmap18"=>"Has a map to Location 18?,bool",
			"hasmap19"=>"Has a map to Location 19?,bool",
			"hasmap20"=>"Has a map to Location 20?,bool",
		);
		$allprefse=unserialize(get_module_pref('allprefs','cartographer',$id));
		rawoutput("<form action='runmodule.php?module=cartographer&op=superuser&userid=$id' method='POST'>");
		showform($form,$allprefse,true);
		$click = translate_inline("Save");
		rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=cartographer&op=superuser&userid=$id");
	}
}

if ($op==""){
	output("%s `@sits at a desk strewn with maps, globes, rulers, compasses for measuring, and directional compasses, pencils, books, and even more maps.`n`n",$mapmaker);
	$allprefs=unserialize(get_module_pref('allprefs'));
	for ($i=1;$i<=20;$i++) {
		$module=get_module_setting("modulename".$i);
		if (is_module_active($module) && get_module_setting("modulename".$i)!="" && $dks>=get_module_setting("dksneeded".$i) && $dks<get_module_setting("dkclosed".$i) && $allprefs['hasmap'.$i]==0){
			$count=$count+1;
			if ($count==1) {
				output("Ask for a map to:`n`n");
				addnav("Maps for Sale");
			}
			addnav(array("%s",get_module_setting("name".$i)),"runmodule.php?module=cartographer&op=mapsell&op2=$i");
				$name=get_module_setting("name".$i);
				output("`^<a href=\"runmodule.php?module=cartographer&op=mapsell&op2=$i\">`c`^$name`c`n</a>",true);
				addnav("","runmodule.php?module=cartographer&op=mapsell&op2=$i");
		}
	}
	addnav("Leave");
	if ($count==0) output("`#'Unfortunately, I don't see any maps that you would be interested in at this time.  Why don't you come back after you've killed that pesky Dragon?'");
}
if ($op=="mapsell"){
	$i=httpget('op2');
	switch(e_rand(1,5)){
		case 1: 
			output("`#'An excellent choice indeed. That's a really nice map.'");
		break;
		case 2:
			output("`#'I see you've chosen one of my more exotic maps. I think you'll be very satisfied.'");
		break;
		case 3:
			output("`#'Well, are you sure you're ready to go visit there?  But that's your choice to make, not mine.'");
		break;
		case 4:
			output("`#'Yes, I've been to `^%s`# before, and I have to tell you it's very nice this time of year.'",get_module_setting("name".$i));
		break;
		case 5:
			output("`#'Well, that's a very elite location to go.  Are you sure you want to go there?'");
		break;
	}
	$gold=get_module_setting("goldcost".$i);
	$gems=get_module_setting("gemcost".$i);
	output("`n`n`#'Let me check to see how much I'm charging for this particular map...'");
	if ($gold>0 || $gems>0){
		output("`n`n'Well, I can't let it go for less than");
		if ($gold>0) output("`^%s gold`#",$gold);
		if ($gold>0 && $gems>0) output("and");
		if ($gems>0) output("`%%s %s`#",$gems,translate_inline($gems>1?"gems":"gem"));
		output("since it's so nice. Are you still interested?'");
		addnav("Buy the Map","runmodule.php?module=cartographer&op=buy&op2=$i&gold=$gold&gems=$gems");
	}else{
		output("`n`n'Actually, it looks like this is from the back of a restaurant.  I have a ton of these and you can have it for free.'");
		addnav("Take the Map","runmodule.php?module=cartographer&op=buy&op2=$i");
	}
	addnav("Leave");
	addnav("Storefront","runmodule.php?module=cartographer");
}
if ($op=="buy"){
	$i=httpget('op2');
	$gold=httpget('gold');
	$gems=httpget('gems');
	if ($session['user']['gold']>=$gold && $session['user']['gems']>=$gems){
		if ($gold>0 || $gems>0){
			output("`@You hand over");
			if ($gold>0) output("`^%s gold`@",$gold);
			if ($gold>0 && $gems>0) output("and");
			if ($gems>0) output("`%%s %s`@",$gems,translate_inline($gems>1?"gems":"gem"));
			$session['user']['gold']-=$gold;
			$session['user']['gems']-=$gems;
			output("and take the map happily.");
		}else{
			output("Cool! Free Map!");
		}
		$allprefs=unserialize(get_module_pref('allprefs'));
		$allprefs['hasmap'.$i]=1;
		set_module_pref('allprefs',serialize($allprefs));
		output("`n`n`@You now have a map to %s`@.  Let the adventures begin!",get_module_setting("name".$i));
		if (get_module_setting("listloc")==1) output("`n`nAlso, you can check your bio to review locations of maps you've already purchased.");
	}else{
		output("`@You pull out some pocket lint and hand it to %s`@, smiling happily and waiting for your map.",$mapmaker);
		output("`n`n`#'Oh no, you don't get such a nice map for pocket lint.  Yes, I agree, it's very nice pocket lint.");
		output("However, why don't you come back when you have");
		if ($gold>0) output("`^%s gold`#",$gold);
		if ($gold>0 && $gems>0) output("and");
		if ($gems>0) output("`%%s %s`#",$gems,translate_inline($gems>1?"gems":"gem"));
		output("and perhaps we can do some business then.'");
	}
	addnav("Leave");
	addnav("Storefront","runmodule.php?module=cartographer");
}
if ($op=="locations"){
	page_header("Map Locations");
	output("`c`b`^Map Locations`b`c`n");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$colorarray=array("","`!","`@","`#","`\$","`%","`^");
	$b=1;
	for ($i=1;$i<=20;$i++) {
		if ($allprefs['hasmap'.$i]==1){
			$b++;
			if ($b>7) $b=1;
			if (get_module_setting("loc".$i)!="") $location=get_module_setting(get_module_setting("loc".$i),get_module_setting("modulename".$i));
			else $location=translate_inline("Unknown");
			output("`c%s%s Location: %s`c",$colorarray[$b],get_module_setting("name".$i),$location);
		}
	}
	$return = httpget('return');
	$return = cmd_sanitize($return);
	$return = substr($return,strrpos($return,"/")+1);
	tlschema("nav");
	addnav("Return whence you came",$return);
	tlschema();
}
if ($op!="superuser" && $op!="locations") villagenav();
page_footer();
?>