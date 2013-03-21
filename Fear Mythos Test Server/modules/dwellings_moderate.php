<?php
/**
	05/10/08 - v0.0.1

	17/04/09 - v0.0.2
	+ Fixed a major bug where you couldn't delete posts.
*/
function dwellings_moderate_getmoduleinfo()
{
	$info = array(
		"name"=>"Dwellings Moderate",
		"description"=>"Allow superusers to moderate dwelling commentary more easily.",
		"version"=>"0.0.2",
		"author"=>"`@MarcTheSlayer",
		"category"=>"Administrative",
		"download"=>"http://dragonprime.net/index.php?topic=9850.0",
		"requires"=>array(
			"dwellings"=>"1.0|Dwellings Project Team, http://dragonprime.net/index.php?module=Downloads;sa=dlview;id=162"
		),
	);
	return $info;
}

function dwellings_moderate_install()
{
	output("`c`b`Q%s 'dwellings_moderate' Module.`b`n`c", translate_inline(is_module_active('dwellings_moderate')?'Updating':'Installing'));
	module_addhook('header-moderate');
	return TRUE;
}

function dwellings_moderate_uninstall()
{
	output("`n`c`b`Q'dwellings_moderate' Module Uninstalled`0`b`c");
	return TRUE;
}

function dwellings_moderate_dohook($hookname, $args)
{
	addnav('Dwellings');
	addnav('Dwelling Commentary','runmodule.php?module=dwellings_moderate');
	// Block 'dwellingseditor' module nav link.
	$dwid = httpget('dwid');
	if( httpget('area') == "dwellings-$dwid" )
	{
		blocknav("runmodule.php?module=dwellingseditor&op=dwsu&dwid=$dwid");
	}

	return $args;
}

function dwellings_moderate_run()
{
	page_header('Dwelling Commentary Moderate');

	addnav('Main Overview');
	addnav('Commentary Overview','moderate.php');

	require_once('lib/superusernav.php');
	superusernav();

	// Get dwelling names and add to nav list.
	$sql = "SELECT dwid, name, type
			FROM " . db_prefix('dwellings') . "
			ORDER BY dwid";
	$result = db_query($sql);
	if( $count = db_num_rows($result) )
	{
		tlschema('notranslate');
		while( $row = db_fetch_assoc($result) )
		{
			addnav(array('%s',$row['type']));
			$name = ( $row['name'] ) ? $row['name'] : translate_inline('Unnamed');
			$x = '(' . $row['dwid'] . ') ' . htmlentities(full_sanitize($name));
			addnav(array('%s`0',$x),'moderate.php?area=dwellings-' . $row['dwid'] . '&dwid=' . $row['dwid']); // Main dwelling chat.
	//
	// Ignore this. I modified the dwinns module to add a lounge.
	//
	//		if( $row['type'] == 'dwinns' )
	//		{
	//			$y = ' - ' . translate_inline('The Lounge');
	//			addnav(array('%s`0',$y),'moderate.php?area=inn-lounge-' . $row['dwid'] . '&dwid=' . $row['dwid']); // Dwinn lounge chat.				
	//		}
			$z = ' - ' . translate_inline('The Coffers');
			addnav(array('%s`0',$z),'moderate.php?area=coffers-' . $row['dwid'] . '&dwid=' . $row['dwid']); // Coffers chat.
		}
		db_free_result($result);
		tlschema();
	}

	output('`n`n`^Select a dwelling on the nav list. The number inside the brackets is the dwelling ID and the coffers for that dwelling is right below the dwelling name.`0`n');

	page_footer();
}
?>