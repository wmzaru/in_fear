<?php
/**
	02/06/09 - v0.0.2
	+ Newest warriors in the realm page.

	19/06/09 - v0.0.3
	+ Last online column to new warriors page.
*/
function grotto_warriorsonline_getmoduleinfo()
{
	$info = array(
		"name"=>"Grotto Warriors Online",
		"description"=>"Have a link in the grotto to view warriors online without newdaying.",
		"version"=>"0.0.3",
		"author"=>"`@MarcTheSlayer",
		"category"=>"Administrative",
		"override_forced_nav"=>TRUE,
		"download"=>"http://dragonprime.net/index.php?topic=10165.0",
		"settings"=>array(
			"Newest Warriors,title",
			"shownew"=>"Display how many new warriors?,int|20",
			"`^The link to the newest warriors page only appears to those with the `bedit user`b superuser flag and above.,note",
			"`$ Due to a bug in LotGD v1.1.1 the registering date gets deleted after a player's first DK. If it displays `bNever`b then that's why.,note",
		)
	);
	return $info;
}

function grotto_warriorsonline_install()
{
	output("`c`b`Q%s 'grotto_warriorsonline' Module.`b`n`c", translate_inline(is_module_active('grotto_warriorsonline')?'Updating':'Installing'));
	module_addhook('superuser');
	return TRUE;
}

function grotto_warriorsonline_uninstall()
{
	output("`n`c`b`Q'grotto_warriorsonline' Module Uninstalled`0`b`c");
	return TRUE;
}

function grotto_warriorsonline_dohook($hookname,$args)
{
	global $session;

	addnav('Actions');
	addnav('Warriors Online','runmodule.php?module=grotto_warriorsonline',FALSE,TRUE,'600x400');

	if( $session['user']['superuser'] & SU_EDIT_USERS )
	{
		addnav('Warriors New','runmodule.php?module=grotto_warriorsonline&subop=new',FALSE,TRUE,'600x400');
	}

	return $args;
}

function grotto_warriorsonline_run()
{
	$op = httpget('op');

	if( $op == 'bio' )
	{
		require_once('lib/sanitize.php');
		require_once('lib/censor.php');

		$return = httpget('ret');
		$return = cmd_sanitize($return);

		$userid = httpget('userid');

		$sql = "SELECT login, name, level, sex, title, specialty, hashorse, acctid, resurrections, bio, dragonkills, race, clanname, clanshort, clanrank, " . db_prefix('accounts') . ".clanid, laston, loggedin
				FROM " . db_prefix('accounts') . " LEFT JOIN " . db_prefix('clans') . " ON " . db_prefix('accounts') . ".clanid = " . db_prefix('clans') . ".clanid WHERE acctid = '$userid'";
		$result = db_query($sql);	
		$row = db_fetch_assoc($result);

		popup_header('Shortened Bio: %s', full_sanitize($row['name']));

		$c = translate_inline("Return to Previous Page");
		rawoutput('<a href="'.$return.'">'.$c.'</a><hr />');
		addnav('',$return);

		output('`^Biography for %s`^.', $row['name']);
		$write = translate_inline("Write Mail");
		rawoutput('<a href="mail.php?op=write&to='.$row['login'].'"><img src="images/newscroll.GIF" width="16" height="16" alt="'.$write.'" border="0" /></a><br /><br />');

		if( $row['clanname'] > '' && getsetting('allowclans',false) )
		{
			$ranks = array(CLAN_APPLICANT=>"`!Applicant`0",CLAN_MEMBER=>"`#Member`0",CLAN_OFFICER=>"`^Officer`0",CLAN_LEADER=>"`&Leader`0", CLAN_FOUNDER=>"`\$Founder");
			$ranks = modulehook('clanranks', array('ranks'=>$ranks, 'clanid'=>$row['clanid']));
			tlschema("clans"); //just to be in the right schema
			array_push($ranks['ranks'],"`\$Founder");
			$ranks = translate_inline($ranks['ranks']);
			tlschema();
			output("`@%s`2 is a %s`2 to `%%s`2`n", $row['name'], $ranks[$row['clanrank']], $row['clanname']);
		}

		output('`^Title: `@%s`n', $row['title']);
		output('`^Level: `@%s`n', $row['level']);
		output('`^Resurrections: `@%s`n', $row['resurrections']);

		$race = $row['race'];
		if( !$race ) $race = RACE_UNKNOWN;
		tlschema('race');
		$race = translate_inline($race);
		tlschema();
		output('`^Race: `@%s`n', $race);

		$genders = translate_inline(array('Male','Female'));
		output('`^Gender: `@%s`n', $genders[$row['sex']]);

		$specialties = modulehook('specialtynames', array(''=>translate_inline('Unspecified')));
		if( isset($specialties[$row['specialty']]) )
		{
			output('`^Specialty: `@%s`n', $specialties[$row['specialty']]);
		}

		$sql = "SELECT *
				FROM " . db_prefix('mounts') . "
				WHERE mountid = '{$row['hashorse']}'";
		$result = db_query_cached($sql, "mountdata-{$row['hashorse']}", 3600);
		$mount = db_fetch_assoc($result);

		$mount['acctid'] = $row['acctid'];
		$mount = modulehook("bio-mount",$mount);
		$none = translate_inline('`iNone`i');
		if( !isset($mount['mountname']) || empty($mount['mountname']) )
		{
			$mount['mountname'] = $none;
		}
		output('`^Creature: `@%s`0`n', $mount['mountname']);

		if( $row['dragonkills'] > 0 )
		{
			output('`^Dragon Kills: `@%s`n', $row['dragonkills']);
		}

		if( !empty($row['bio']) )
		{
			output('`^Bio: `@`n%s`n', soap($row['bio']));
		}

		output("`n`^Recent accomplishments (and defeats) of %s`0", $row['name']);

		$sql = "SELECT *
				FROM " . db_prefix('news') . "
				WHERE accountid = {$row['acctid']}
				ORDER BY newsdate DESC, newsid ASC LIMIT 50";
		$result = db_query($sql);

		$odate = '';
		tlschema('news');
		while( $row = db_fetch_assoc($result) )
		{
			tlschema($row['tlschema']);
			if( $row['arguments'] > '' )
			{
				$arguments = array();
				$base_arguments = unserialize($row['arguments']);
				array_push($arguments, $row['newstext']);
				while( list($key, $val) = each($base_arguments) )
				{
					array_push($arguments, $val);
				}
				$news = call_user_func_array('sprintf_translate', $arguments);
				rawoutput(tlbutton_clear());
			}
			else
			{
				$news = translate_inline($row['newstext']);
				rawoutput(tlbutton_clear());
			}
				tlschema();
			if( $odate != $row['newsdate'] )
			{
				output_notl("`n`b`@%s`0`b`n",
				date("D, M d", strtotime($row['newsdate'])));
				$odate = $row['newsdate'];
			}
			output_notl("`@$news`0`n");
		}
		tlschema();

		rawoutput('<hr /><a href="'.$return.'">'.$c.'</a><br /><br />');
	}
	else
	{
		$subop = httpget('subop');
		if( $subop == 'new' )
		{
			popup_header('Realms Newest Warriors');

			$sql = "SELECT acctid, name, login, alive, location, race, sex, level, loggedin, lastip, uniqueid, laston, regdate
					FROM " . db_prefix('accounts') . "
					WHERE locked = 0
					ORDER BY regdate DESC, level DESC, dragonkills DESC, login ASC
					LIMIT " . get_module_setting('shownew');

			$warr = translate_inline(array('new warrior','newest warriors'));
		}
		else
		{
			popup_header('Warriors Currently Online');

			$sql = "SELECT acctid, name, login, alive, location, race, sex, level, laston, loggedin, lastip, uniqueid
					FROM " . db_prefix('accounts') . "
					WHERE locked = 0
						AND loggedin = 1
						AND laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."'
					ORDER BY level DESC, dragonkills DESC, login ASC";

			$warr = translate_inline(array('warrior','warriors'));
		}

		$result = db_query($sql);
		$count = db_num_rows($result);

		$alive = translate_inline('Alive');
		$level = translate_inline('Level');
		$name = translate_inline('Name');
		$loc = translate_inline('Location');
		$race = translate_inline('Race');
		$sex = translate_inline('Sex');
		$last = translate_inline('Last On');
		$since = translate_inline('Days Since');
		$writemail = translate_inline('Write Mail');
		$yesno = translate_inline(array('`4No`0','`1Yes`0'));
		$genders = translate_inline(array('`!Male`0','`%Female`0'));

		output("`n`c`b(%s %s)`b`c`n`n", $count, ($count==1?$warr[0]:$warr[1]));

		rawoutput('<table border="0" cellpadding="2" cellspacing="1" bgcolor="#999999" align="center">');
		rawoutput("<tr class=\"trhead\"><td>$alive</td><td>$level</td><td>$name</td><td>$loc</td><td align=\"center\">$race</td><td align=\"center\">$sex</td><td>$last</td>");
		if( $subop == 'new' )
		{
			rawoutput("<td>$since</td>");
		}

		rawoutput('</tr>');

		$i = 1;
		while( $row = db_fetch_assoc($result) )
		{
			rawoutput('<tr class="'.($i%2?'trdark':'trlight').'"><td align="center">');
			output_notl('%s', $yesno[$row['alive']]);
			rawoutput('</td><td align="center">');
			output_notl('`^%s`0', $row['level']);
			rawoutput('</td><td>');
			rawoutput('<a href="mail.php?op=write&to='.rawurlencode($row['login']).'">');
			rawoutput('<img src="images/newscroll.GIF" width="16" height="16" alt="'.$writemail.'" border="0" /></a>');
			rawoutput('<a href="runmodule.php?module=grotto_warriorsonline&op=bio&ret=' . urlencode($_SERVER['REQUEST_URI']).'&userid='.$row['acctid'].'">');
			addnav('','runmodule.php?module=grotto_warriorsonline&op=bio&ret=' . urlencode($_SERVER['REQUEST_URI']).'&userid='.$row['acctid']);
			output_notl('`&%s`0', $row['name']);
			rawoutput('</a></td><td>');
			output_notl('`&%s`0', $row['location']);
			rawoutput('</td><td>');
			if( !$row['race'] ) $row['race'] = RACE_UNKNOWN;
			tlschema('race');
			output($row['race']);
			tlschema();
			rawoutput('</td><td align="center">');
			output_notl('%s', $genders[$row['sex']]);
			rawoutput('</td><td>');
			$laston = relativedate($row['laston']);
			output_notl('%s', $laston);
			if( $subop == 'new' )
			{
				rawoutput('</td><td>');
				$regdate = relativedate($row['regdate']);
				output_notl('%s', $regdate);
			}
			rawoutput('</td></tr>');
			$i++;
		}

		rawoutput('</table><br /><br />');
	}

	popup_footer();
}
?>