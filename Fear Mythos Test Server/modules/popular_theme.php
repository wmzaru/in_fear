<?php
/**
	18/02/09 - v0.0.1
	+ Released.
*/
function popular_theme_getmoduleinfo()
{
	$info = array(
		"name"=>"Popular Player Themes",
		"description"=>"Lists the installed themes and who's using them.",
		"version"=>"0.0.1",
		"author"=>"`@MarcTheSlayer",
		"category"=>"Administrative",
		"download"=>"http://dragonprime.net/index.php?topic=9849.0",
		"prefs"=>array(
			"Player's Theme,title",
			"skin"=>"This is the player's theme.,string,30|"
		)
	);
	return $info;
}

function popular_theme_install()
{
	output("`4%s 'popular_theme' Module.`n", translate_inline(is_module_active('popular_theme')?'Updating':'Installing'));
	module_addhook('player-login');
	module_addhook('player-logout');
	module_addhook('superuser');
	return TRUE;
}

function popular_theme_uninstall()
{
	output("`4Un-Installing 'popular_theme' Module.`n`0");
	return TRUE;
}

function popular_theme_dohook($hookname, $args)
{
	switch( $hookname )
	{
		case 'player-login':
		case 'player-logout':
			global $session;

			$skin = $session['templatename'];
			if( empty($skin) )
			{
				$skin = getsetting('defaultskin','jade.htm');
			}
			$skin = substr($skin, 0, strpos($skin,'.htm'));
			set_module_pref('skin',$skin,'popular_theme',$session['user']['acctid']);
		break;

		case 'superuser':
			addnav('Mechanics');
			addnav('Popular Player Themes','runmodule.php?module=popular_theme');
		break;			
	}

	return($args);
}

function popular_theme_run()
{
	global $session;

	page_header('Popular Player Themes');

	output('`3In the table below are the installed themes and who\'s using which one. Because the player\'s theme is stored in a cookie, ');
	output('this module has added a preference to store the theme name to make this work, but it is only set when a player logs in or out.`n`n');
	output('If you have recently installed this module then please allow some time for your player\'s to either login or logout.`n`n');
	output('If you click on the template name, your theme will change. Temporarily or permanent depending on your browser.`n`n');

	$skin = httpget('op');
	if( !empty($skin) )
	{
		set_module_pref('skin',$skin,'popular_theme',$session['user']['acctid']);
	}

	$skins = array();
	$handle = @opendir('templates');
	// Template directory open failed
	if( ($handle = @opendir('templates')) !== FALSE )
	{
		while( ($file = @readdir($handle)) !== FALSE )
		{
			if( ($part = strpos($file,'.htm')) > 0 )
			{
				$file = substr($file, 0, $part);
				array_push($skins, $file);
			}
		}

		if( count($skins) > 0 )
		{
			natcasesort($skins);
			$skins2 = array();

			// I couldn't see any other way of putting the skins as keys in the array *and* in alphabetical order.
			foreach( $skins as $key => $value )
			{
				$skins2[$value] = '';
			}
			unset($skins);

			$sql = "SELECT a.name, b.value
					FROM " . db_prefix('accounts') . " a, " . db_prefix('module_userprefs') . " b
					WHERE b.modulename = 'popular_theme'
						AND b.setting = 'skin'
						AND b.value != ''
						AND a.acctid = b.userid
					ORDER BY b.userid ASC";
			$result = db_query($sql);
			while( $row = db_fetch_assoc($result) )
			{
				if( !empty($row['name']) && array_key_exists($row['value'], $skins2) )
				{
					$skins2[$row['value']][] = $row['name'];
				}
			}
			db_free_result($result);

			$total = translate_inline('Total');
			$theme = translate_inline('Theme');
			$players = translate_inline('Players');

			rawoutput('<table width="90%" border="0" cellpadding="2" cellspacing="1" align="center">');
			rawoutput('<tr class="trhead"><td align="center">' . $total . '</td><td>' . $theme . '</td><td>' . $players . '</td></tr>');

			$j = 0;
			$k = 0;
			foreach( $skins2 as $key => $value )
			{
				$name_count = ( is_array($skins2[$key]) ) ? count($skins2[$key]) : 0;
				rawoutput('<tr class="' . ($j%2?'trlight':'trdark') . '"><td align="center" valign="top">' . $name_count . '</td><td valign="top"><a href="runmodule.php?module=popular_theme&op=' . $key . '" onClick=\'document.cookie="template=' . $key . '.htm;expires=' . strtotime("+45 days") . '"\'>' . $key . '</a></td><td valign="top">');
				addnav('',"runmodule.php?module=popular_theme&op=$key");
				if( is_array($skins2[$key]) && !empty($skins2[$key]) )
				{
					for( $i=0; $i<$name_count; $i++ )
					{
						output_notl('%s`0, ', $skins2[$key][$i]);
					}
				}
				else
				{
					output('`2No Players');
				}
				rawoutput('</td></tr>');
				$j++;
				$k += $name_count;
			}
			unset($skins2);

			$sql = "SELECT count(acctid) AS c FROM " . db_prefix('accounts') . " WHERE locked = 0";
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$total_players = $row['c'];
			$left = $total_players - $k;
			$left_players = translate_inline(($left==1?'Player':'Players').' have still yet to log in or out.');

			rawoutput('<tr class="trhead"><td align="center">' . $k . '/' . $total_players . '</td><td colspan="2">' . $left . ' ' . $left_players . '</td></tr>');

			rawoutput('</table>');
		}
		else
		{
			output('`^There are no themes available.`n`n');
		}
	}
	else
	{
		output('`^The "templates" directory could not be opened!`n`n');
	}

	addnav('Refresh');
	addnav('Refresh','runmodule.php?module=popular_theme');
	require_once('lib/superusernav.php');
	superusernav();

	page_footer();
}
?>