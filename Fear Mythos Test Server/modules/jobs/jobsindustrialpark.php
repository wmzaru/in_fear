<?php
	page_header("Industrial Park");
	output("`c`b`&The Industrial Park`c`n`b");
	output("You find yourself standing in the nexus of busy workers.  You see many types of buildings; each with their own benefit to the community.");
	output("`n`nVillagers new to the area seem to be entering the 'Job Services' building. Other villagers are leaving the Industrial Market carrying all different types of goods that they recently purchased.");
	output("`n`nYou see other villagers rushing to their jobs.");
	output("`n`nWhat will you do today?");
	addnav("The Market");
	addnav("Industrial Market","runmodule.php?module=jobs&place=market");
	addnav("Human Resources Department");
	addnav("Job Services","runmodule.php?module=jobs&place=jobservice");
	addnav("Jobs");
	$limit=get_module_setting("maxnum");
	if (is_module_active("furniture")==0 && $limit==10) $limit=9;
	for ($i=1;$i<=$limit;$i++) {
		if (get_module_setting("type".$i)!=""){
			addnav(array("%s", get_module_setting("type".$i)),"runmodule.php?module=jobs&place=$i");
		}
	}
	addnav("Other");
	villagenav();
	page_footer();
?>