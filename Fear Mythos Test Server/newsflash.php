<?php
/**
	Created by MarcTheSlayer
	12/02/2012 - v1.0.0
	Part 2/2 of the newsflash module. This file must be placed in the LOtGD root directory.

	30/06/2012 - v1.0.1
	+ Translations not being pulled from database. I had removed the 2 tlschema() lines from the news code.

	Credits:
		Dragonprime Code team (www.dragonprime.net) for their news getting code.
		CavemanJoe (www.improbableisland.com) for his ajax commentary javascript code. (Used in the other file).
*/
	global $session;
	
	define('ALLOW_ANONYMOUS',TRUE);
	define('OVERRIDE_FORCED_NAV',TRUE);
	require_once('common.php');

	if( $session['user']['loggedin'] == TRUE )
	{
		if( !isset($session['user']['prefs']['newsflash']) ) $session['user']['prefs']['newsflash'] = 0;

		$where = ( $session['user']['prefs']['newsflash'] > 0 ) ? 'WHERE newsid > ' . $session['user']['prefs']['newsflash'] : '';

		$sql = "SELECT *
				FROM " . db_prefix('news') . "
				$where
				ORDER BY newsid
				DESC LIMIT 1";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		if( $row['newsid'] > 0 && $session['user']['prefs']['newsflash'] != $row['newsid'] )
		{
			tlschema($row['tlschema']);
			if( $row['arguments'] > '' )
			{
				$arguments = array();
				$base_arguments = unserialize($row['arguments']);
				array_push($arguments,$row['newstext']);
				while( list($key,$val) = each($base_arguments) )
				{
					array_push($arguments,$val);
				}
				$news = call_user_func_array("sprintf_translate",$arguments);
				rawoutput(tlbutton_clear());
			}
			else
			{
				$news = translate_inline($row['newstext']);
			}
			tlschema();
			$session['user']['prefs']['newsflash'] = $row['newsid'];
			$title = translate_inline('`b`^News Flash:`0`b`n','newsflash');
			$news = appoencode($title.$news,TRUE);
			echo '<div id="newsflashdiv2">'.$news.'</div>';
		}
		else
		{
			echo '<div id="newsflashdiv2" style="display:none"></div>';
		}
	}
	saveuser();
	exit();
?>