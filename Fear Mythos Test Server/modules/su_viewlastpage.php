<?php
function su_viewlastpage_getmoduleinfo()
{
	$info = array(
		"name"=>"View Last Page Hit",
		"description"=>"Allow the easy viewing and refreshing of a player's last viewed page.",
		"version"=>"0.0.1",
		"author"=>"`@MarcTheSlayer",
		"category"=>"Administrative",
		"download"=>"http://dragonprime.net/index.php?topic=10284.0",
		"override_forced_nav"=>TRUE,
	);
	return $info;
}

function su_viewlastpage_install()
{
	output("`c`b`Q%s 'su_viewlastpage' Module.`b`n`c", translate_inline(is_module_active('su_viewlastpage')?'Updating':'Installing'));
	module_addhook('footer-user');
	return TRUE;
}

function su_viewlastpage_uninstall()
{
	output("`n`c`b`Q'su_viewlastpage' Module Uninstalled`0`b`c");
	return TRUE;
}

function su_viewlastpage_dohook($hookname,$args)
{
	$op = httpget('op');
	if( !empty($op) && $op != 'search' )
	{
		$userid = (int)httpget('userid');
		blocknav('user.php?op=lasthit&userid='.$userid);
		addnav('Operations');
		addnav('View last page hit','runmodule.php?module=su_viewlastpage&userid='.$userid, FALSE, TRUE, '');
	}

	return $args;
}

function su_viewlastpage_run()
{
	$userid = httpget('userid');

	$sql = "SELECT output
			FROM " . db_prefix('accounts_output') . "
			WHERE acctid = '$userid'";
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	echo str_replace(".focus();",".blur();",str_replace("<iframe src=","<iframe Xsrc=",$row['output']));
	exit();
}
?>