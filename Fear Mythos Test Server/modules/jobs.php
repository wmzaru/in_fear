<?php
function jobs_getmoduleinfo(){
	$info = array(
		"name"=>"Jobs: Even More",
		"version"=>"1.04",
		"author"=>"Lonny Luberts and Hex,Even More by DaveS",
		"download"=>"",
		"category"=>"Village",
		"settings"=>array(
			"Jobs Module Settings,title",
			"reset"=>"Reset turns:,enum,0,System Newday,1,Any Newday,2,Any Newday under X Experience|0",
			"resetx"=>"If to reset under X Experience:,int|10000",
			"dkmin"=>"Minimum dks needed to play this module?,int|0",
			"maxnum"=>"Maximum number of jobs available:,range,1,10,1|10",
			"1=Farm 2=Mill 3=Textile Mill 4= Brewery 5=Foundry 6=Castle Guard 7=Forest Recycling 8=Coal Factory 9=Manager's Training 10=Wood Shop,note",
			"You must have the Furniture store installed for the Wood Shop to be available.,note",
			"requireapp"=>"Require application approval by staff before receiving a job?,bool|1",
			"woodapprove"=>"Require Custom furniture names to be approved by Staff?,enum,0,No,1,Yes,2,No; but send YoM informing of the name|1",
			"industrialpark"=>"Make everything appear in one Industrial park?,bool|1",
			"indusloc"=>"Where is the Industrial Park located?,location|".getsetting("villagename", LOCATION_FIELDS),
			"If the Industrial Park is not utilized you need to pick each location accordingly,note",
			"Vacation/Firing,title",
			"fire"=>"How should firing be based?,enum,0,Real Time Missed,1,Not Working X Gamedays|,0",
			"Gamedays are defined as days that the player has logged on and not gone to work,note",
			"xdays"=>"How many missed gamedays before getting fired?,int|5",
			"vacation"=>"Allow players to request vacation time?,bool|1",
			"Note: Vacations are applied  for  at Job Services. Managers CANNOT get vacation.,note",
			"Pay Rates,title",
			"Note: Watch out! If you make these too high it will be too easy for players to get a lot of money without any danger!,note",
			"pay"=>"What is the basic gold pay per turn for working at the first job?,int|50",
			"incr"=>"How much more gold is earned at each promotion for basic work?,int|10",
			"Note: The manager bonus is 50% of this amount,note",
			"First Job,title",
			"type1"=>"What kind of place is the First Job:,text|Farm",
			"desc1"=>"What is the description:,text|Things like milk, meat, and vegetables are produced for use by the villagers.",
			"loc1"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts1"=>"Unused shifts for First Job:,int|0",
			"Second Job,title",
			"type2"=>"What kind of place is the Second Job:,text|Mill",
			"desc2"=>"What is the description:,text|At a mill, flour is produced for use by the villagers.",
			"loc2"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts2"=>"Unused shifts for Second Job:,int|0",
			"Third Job,title",
			"type3"=>"What kind of place is the Third Job:,text|Textile Mill",
			"desc3"=>"What is the description:,text|At a textile mill, fabric and leather is produced for use by the villagers to make clothing.",
			"loc3"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts3"=>"Unused shifts for Third Job:,int|0",
			"Fourth Job,title",
			"type4"=>"What kind of place is the Fourth Job:,text|Brewery",
			"desc4"=>"What is the description:,text|At the brewery, ale is produced for consumption by the villagers.",
			"loc4"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts4"=>"Unused shifts for Fourth Job:,int|0",
			"Fifth Job,title",
			"type5"=>"What kind of place is the Fifth Job:,text|Foundry",
			"desc5"=>"What is the description:,text|At a foundry, weapons and armor are produced for use by the villagers.",
			"loc5"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts5"=>"Unused shifts for Fifth Job:,int|0",
			"Sixth Job,title",
			"type6"=>"What kind of place is the Sixth Job:,text|Castle Guard Post",
			"desc6"=>"What is the description:,text|Work to protect the Kingdom and guard from invaders.",
			"loc6"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts6"=>"Unused shifts for Sixth Job:,int|0",
			"Seventh Job,title",
			"type7"=>"What kind of place is the Seventh Job:,text|Forest Recycling Center",
			"desc7"=>"What is the description:,text|Collect the corpses of dead animals and villagers from the forest.",
			"loc7"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts7"=>"Unused shifts for Seventh Job:,int|0",
			"Eighth Job,title",
			"type8"=>"What kind of place is the Eighth Job:,text|Coal Factory",
			"desc8"=>"What is the description:,text|Help to collect the coal and feed the fires to heat the castle.",
			"loc8"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts8"=>"Unused shifts for Eighth Job:,int|0",
			"Ninth Job,title",
			"type9"=>"What kind of place is the Ninth Job:,text|Manager's Training Center",
			"desc9"=>"What is the description:,text|Learn how to become a manager.",
			"loc9"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts9"=>"Unused shifts for Ninth Job:,int|0",
			"Tenth Job,title",
			"type10"=>"What kind of place is the Tenth Job:,text|Wood Shop",
			"desc10"=>"What is the description:,text|At a wood shop, custom chairs, tables, and beds are produced for use by the villagers in their homes.",
			"loc10"=>"Where does this job appear:,location|".getsetting("villagename", LOCATION_FIELDS),
			"shifts10"=>"Unused shifts for Tenth Job:,int|0",
			"Custom Furniture Production,note",
			"cust1"=>"How many turns does it take to make the 1st custom chair?,int|10",
			"cwood1"=>"How much wood does it take to make the 1st custom chair?,int|1",
			"cstone1"=>"How much stone does it take to make the 1st custom chair?,int|0",
			"cust2"=>"How many turns does it take to make the 2nd custom chair?,int|13",
			"cwood2"=>"How much wood does it take to make the 2nd custom chair?,int|1",
			"cstone2"=>"How much stone does it take to make the 2nd custom chair?,int|0",
			"cust3"=>"How many turns does it take to make the 1st custom table?,int|15",
			"cwood3"=>"How much wood does it take to make the 1st custom table?,int|1",
			"cstone3"=>"How much stone does it take to make the 1st custom table?,int|1",
			"cust4"=>"How many turns does it take to make the 2nd custom table?,int|18",
			"cwood4"=>"How much wood does it take to make the 2nd custom table?,int|1",
			"cstone4"=>"How much stone does it take to make the 2nd custom table?,int|1",
			"cust5"=>"How many turns does it take to make the 1st custom bed?,int|20",
			"cwood5"=>"How much wood does it take to make the 1st custom bed?,int|2",
			"cstone5"=>"How much stone does it take to make the 1st custom bed?,int|1",
			"cust6"=>"How many turns does it take to make the 2nd custom bed?,int|25",
			"cwood6"=>"How much wood does it take to make the 2nd custom bed?,int|2",
			"cstone6"=>"How much stone does it take to make the 2nd custom bed?,int|1",
			"Custom Furniture Payment,note",
			"cgold1"=>"How much bonus gold for completing Custom Chair 1?,int|275",
			"cgems1"=>"How many bonus gems for completing Custom Chair 1?,int|0",
			"cgold2"=>"How much bonus gold for completing Custom Chair 2?,int|425",
			"cgems2"=>"How many bonus gems for completing Custom Chair 2?,int|1",
			"cgold3"=>"How much bonus gold for completing Custom Table 1?,int|350",
			"cgems3"=>"How many bonus gems for completing Custom Table 1?,int|1",
			"cgold4"=>"How much bonus gold for completing Custom Table 2?,int|425",
			"cgems4"=>"How many bonus gems for completing Custom Table 2?,int|2",
			"cgold5"=>"How much bonus gold for completing Custom Bed 1?,int|400",
			"cgems5"=>"How many bonus gems for completing Custom Bed 1?,int|2",
			"cgold6"=>"How much bonus gold for completing Custom Bed 2?,int|475",
			"cgems6"=>"How many bonus gems for completing Custom Bed 2?,int|3",
			"Store Inventory,title",
			"milk"=>"Milk Quantity:,int|0",
			"egg"=>"Egg Quantity:,int|0",
			"pork"=>"Pork Quantity:,int|0",
			"beef"=>"Beef Quantity:,int|0",
			"chicken"=>"Chicken Quantity:,int|0",
			"bread"=>"Bread Quantity:,int|0",
			"cloth"=>"Cloth Quantity:,int|0",
			"leather"=>"Leather Quantity:,int|0",
			"ale"=>"Ale Quantity:,int|0",
			"breastplate"=>"Breastplate Quantity:,int|0",
			"longsword"=>"LongSword Quantity:,int|0",
			"chainmail"=>"Chainmail Quantity:,int|0",
			"duallongsword"=>"Dual Longsword Quantity:,int|0",
			"fullarmor"=>"Full Armor Quantity:,int|0",
			"duallongsworddaggers"=>"Dual Longsword with daggers Quantity:,int|0",
			"HoF,title",
			"nosuper"=>"Exclude Superusers from the HoF?,bool|0",
			"usehof"=>"Use Jobs Hall of Fame?,bool|1",
			"pp"=>"Entries per page in HoF:,int|40",
		),
		"prefs"=>array(
			"Jobs Module User Preferences,title",
			"user_stat"=>" Would you like a YoM informing you when a worker at your shop does something wrong?,bool|1",
			"user_furnyomsell"=>"Would you like a YoM when Your Custom Furniture is purchased?,bool|1",
			"super"=>"Allow player to process Job Applications/Furniture Names if superuser?,bool|0",
			"email"=>"Receive YoM when a Job Application/Furniture Name is pending?,bool|0",
			"Allprefs,title",
			"Note: Please edit with caution. Consider using the Allprefs Editor instead.,note",
			"allprefs"=>"Preferences for Jobs,textarea|",
		),
	);
	return $info;
}

function jobs_install(){
	module_addhook("newday");
	module_addhook("newday-runonce");
	module_addhook("village");
	module_addhook("superuser");
	module_addhook("footer-hof");
	module_addhook("allprefs");
	module_addhook("allprefnavs");
	return true;
}

function jobs_uninstall(){
	return true;
}

function jobs_dohook($hookname,$args){
	global $session;
	require("modules/jobs/dohook/$hookname.php");
	return $args;
}

function jobs_run(){
	global $session;
	$op = httpget('op');
	$op2 = httpget('op2');
	$place = httpget('place');
	$shifts = $_POST['shifts'];
	$C1 = $_POST['C1'];
	$C2 = $_POST['C2'];
	$T1 = $_POST['T1'];
	if ($place == "industrialpark") include("modules/jobs/jobsindustrialpark.php");
	elseif ($place == "jobservice") include("modules/jobs/jobsjobservice.php");
	elseif ($place == "market") include("modules/jobs/jobsmarket.php");
	elseif ($place == "super") include("modules/jobs/jobssuper.php");
	elseif ($place == "supername") include("modules/jobs/jobssupername.php");
	elseif ($place >=1) include("modules/jobs/jobs.php");

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
	$allprefse=unserialize(get_module_pref('allprefs',"jobs",$id));
	if ($allprefse['reason']=="") $allprefse['reason']="";
	if ($allprefse['job']=="") $allprefse['job']=0;
	if ($allprefse['lastworked']=="") $allprefse['lastworked']="";
	if ($allprefse['jobexp']=="") $allprefse['jobexp']=0;
	if ($allprefse['dayssince']=="") $allprefse['dayssince']=0;
	if ($allprefse['vacation']=="") $allprefse['vacation']=0;
	if ($allprefse['ffspent']=="") $allprefse['ffspent']=0;
	if ($allprefse['name1']=="") $allprefse['name1']="";
	if ($allprefse['name2']=="") $allprefse['name2']="";
	if ($allprefse['name3']=="") $allprefse['name3']="";
	if ($allprefse['name4']=="") $allprefse['name4']="";
	if ($allprefse['name5']=="") $allprefse['name5']="";
	if ($allprefse['name6']=="") $allprefse['name6']="";
	set_module_pref('allprefs',serialize($allprefse),'jobs',$id);
	if ($subop!='edit'){
		$allprefse=unserialize(get_module_pref('allprefs',"jobs",$id));
		$allprefse['reason']= httppost('reason');
		$allprefse['lastworked']= httppost('lastworked');
		$allprefse['job']= httppost('job');
		$allprefse['jobexp']= httppost('jobexp');
		$allprefse['jobapp']= httppost('jobapp');
		$allprefse['jobworked']= httppost('jobworked');
		$allprefse['dayssince']= httppost('dayssince');
		$allprefse['vacation']= httppost('vacation');
		$allprefse['type']= httppost('type');
		$allprefse['ffspent']= httppost('ffspent');
		$allprefse['cust1']= httppost('cust1');
		$allprefse['name1']= httppost('name1');
		$allprefse['cust2']= httppost('cust2');
		$allprefse['name2']= httppost('name2');
		$allprefse['cust3']= httppost('cust3');
		$allprefse['name3']= httppost('name3');
		$allprefse['cust4']= httppost('cust4');
		$allprefse['name4']= httppost('name4');
		$allprefse['cust5']= httppost('cust5');
		$allprefse['name5']= httppost('name5');
		$allprefse['cust6']= httppost('cust6');
		$allprefse['name6']= httppost('name6');
		set_module_pref('allprefs',serialize($allprefse),'jobs',$id);
		output("Allprefs Updated`n");
		$subop="edit";
	}
	if ($subop=="edit"){
		require_once("lib/showform.php");
		$form = array(
			"Jobs Even More,title",
			"reason"=>"Reason they are applying:,text",
			"lastworked"=>"Date Last Worked:,text",
			"job"=>"Job:,enum,-1,Quit,0,None,1,Farm,2,Farm Management,3,Mill,4,Mill Management,5,Textile,6,Textile Management,7,Brewery,8,Brewery Management,9,Foundry,10,Foundry Management,11,Castle Guard,12,Castle Guard Management,13,Forest Recycling Center,14,Forest Recycling Center Management,15,Coal Factory,16,Coal Factory Management,17,Manager's Training Center,18,Manager's Training Center Manager,19,Wood Shop,20,Wood Shop Management",
			"jobexp"=>"Job Experience:,int",
			"jobapp"=>"Current Job applied for:,enum,0,None,1,Farm,2,Farm Management,3,Mill,4,Mill Management,5,Textile,6,Textile Management,7,Brewery,8,Brewery Management,9,Foundry,10,Foundry Management,11,Castle Guard,12,Castle Guard Management,13,Forest Recycling Center,14,Forest Recycling Center Management,15,Coal Factory,16,Coal Factory Management,17,Manager's Training Center,18,Manager's Training Center Manager,19,Wood Shop,20,Wood Shop Management",
			"jobworked"=>"Job Worked today?,bool",
			"dayssince"=>"How many days has it been since last worked?,int",
			"vacation"=>"How many days does the player have left for vacation?,int",
			"Wood Shop,title",
			"type"=>"What project are they current working on?,enum,0,Basic Furniture,1,Custom Chair 1,2,Custom Chair 2,3,Custom Table 1,4,Custom Table 2,5,Custom Bed 1,6,Custom Bed 2",
			"ffspent"=>"How many turns has the player spent working on their current furniture?,int",
			"cust1"=>"Has the player finished a General Chair?,enum,0,No,1,Yes,2,Pending Approval",
			"name1"=>"What is the name of their General Chair?,text",
			"cust2"=>"Has the player finished an Heirloom Chair?,enum,0,No,1,Yes,2,Pending Approval",
			"name2"=>"What is the name of their Heirloom Chair?,text",
			"cust3"=>"Has the player finished a General Table?,enum,0,No,1,Yes,2,Pending Approval",
			"name3"=>"What is the name of their General Table?,text",
			"cust4"=>"Has the player finished an Heirloom Table?,enum,0,No,1,Yes,2,Pending Approval",
			"name4"=>"What is the name of their Heirloom Table?,text",
			"cust5"=>"Has the player finished a General Bed?,enum,0,No,1,Yes,2,Pending Approval",
			"name5"=>"What is the name of their General Bed?,text",
			"cust6"=>"Has the player finished an Heirloom Bed?,enum,0,No,1,Yes,2,Pending Approval",
			"name6"=>"What is the name of their Heirloom Bed?,text",
		);
		$allprefse=unserialize(get_module_pref('allprefs',"jobs",$id));
		rawoutput("<form action='runmodule.php?module=jobs&op=superuser&userid=$id' method='POST'>");
		showform($form,$allprefse,true);
		$click = translate_inline("Save");
		rawoutput("<input id='bsave' type='submit' class='button' value='$click'>");
		rawoutput("</form>");
		addnav("","runmodule.php?module=jobs&op=superuser&userid=$id");
	}
	page_footer();
}
}
?>