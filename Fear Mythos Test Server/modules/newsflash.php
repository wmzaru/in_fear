<?php
/**
	Created by MarcTheSlayer
	12/02/2012 - v1.0.0
	Part 1/2 of the newsflash module. This file must be placed in the LOtGD modules directory.

	30/06/2012 - v1.0.1
	+ Translations not being pulled from database. I had removed the 2 tlschema() lines from the news code.

	Credits:
		Dragonprime Code team (www.dragonprime.net) for their news getting code. (Used in the other file).
		CavemanJoe (www.improbableisland.com) for his ajax commentary javascript code.
*/
function newsflash_getmoduleinfo()
{
	$info = array(
		"name"=>"News Flash",
		"description"=>"Flash each bit of news in a small CSS popup as it happens.",
		"version"=>"1.0.1",
		"author"=>"`@MarcTheSlayer`2, CavemanJoe, Dragonprime Team",
		"category"=>"News",
		"download"=>"http://dragonprime.net/index.php?topic=12121.0",
		"settings"=>array(
			"News Flash,title",
				"refresh"=>"Refresh rate in seconds:,range,1,60,1|10",
				"`^Don't make this too low where by people can't read it before it disappears.,note",
				"limit"=>"Stop refreshing after this many:,int|50",
				"`^Don't make this too high or people wont time out. A good value is to take the game logout time and divide by the above refresh rate.,note",
			"README,title",
				"`^News Flash is turned off by default and can be turned on/off by each player in their preferences so be sure to inform them.,title",
		),
		"prefs"=>array(
			"News Flash,title",
				"user_newspopup"=>"Turn on the news flash popup?,bool",
		)
	);
	return $info;
}

function newsflash_install()
{
	output("`c`b`Q%s 'newsflash' Module.`b`n`c", translate_inline(is_module_active('newsflash')?'Updating':'Installing'));
	module_addhook('everyfooter-loggedin');
	return TRUE;
}

function newsflash_uninstall()
{
	output("`n`c`b`Q'newsflash' Module Uninstalled`0`b`c");
	return TRUE;
}

function newsflash_dohook($hookname,$args)
{
	if( !get_module_pref('user_newspopup') ) return $args;

	rawoutput("<style type=\"text/css\">
		div#newsflashdiv
		{
			position:fixed;
			top:0;
			left:0;
			display:block;
			width:100%;
		}
		div#newsflashdiv2
		{
			font-face: Verdana;
			background-color: black;
			border: 1px solid gray;
			padding: 5px;
			top:0;
			left:0;
			right:0;
			width:100%;
		}
		body
		{
			margin:0;
			padding:0 10px 0 10px;
			border:0;
			height:100%;
			overflow-y:auto;
		}
		* html div#newsflashdiv
		{
			position:absolute;
		}
	</style>
	<!--[if lte IE 6]>
		<style type=\"text/css\">
		/*<![CDATA[*/ 
			html {overflow-x:auto; overflow-y:hidden;}
		/*]]>*/
		</style>
	<![endif]-->
	<!-- Fixed div box css code that doesn't move when window is scrolled. -->
	<!-- ©2004 Stuart A Nicholls -->
	<!-- http://www.cssplay.co.uk/layouts/fixed.html -->");

	$refreshrate = get_module_setting('refresh').'000'; // 1000 = 1 second.
	$limit = get_module_setting('limit');

	$timeoutmsg = addslashes(appoencode(translate_inline('`QNews Flash update has timed out. Refresh the page to restart. You can turn this off in your preferences.`0')));

	rawoutput("<script type=\"text/javascript\"><!--
		var newsflashBar = document.createElement('div');
		newsflashBar.id = 'newsflashdiv';
		document.body.insertBefore(newsflashBar,document.body.firstChild);

		var limitnewsflash = 0;
		var timeoutmsg = '<div id=\"newsflashdiv2\">".$timeoutmsg."</div>';
		timernewsflash=setTimeout('loadnewnewsflash()',".$refreshrate.");
		function loadnewnewsflash()
		{
			limitnewsflash ++;
			if( window.XMLHttpRequest )
			{
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttpnewsflash=new XMLHttpRequest();
			}
			else
			{
				// code for IE6, IE5
				xmlhttpnewsflash=new ActiveXObject('Microsoft.XMLHTTP');
			}
			xmlhttpnewsflash.onreadystatechange=function()
			{
				if( xmlhttpnewsflash.readyState==4 && xmlhttpnewsflash.status==200 && limitnewsflash < ".$limit." )
				{
					document.getElementById('newsflashdiv').innerHTML = xmlhttpnewsflash.responseText;
					timernewsflash=setTimeout('loadnewnewsflash()',".$refreshrate.");
				}
				else if( limitnewsflash == ".$limit." )
				{
					document.getElementById('newsflashdiv').innerHTML = timeoutmsg;
					timernewsflash=setTimeout('loadnewnewsflash()',".$refreshrate.");
				}
				else if( limitnewsflash > ".$limit." )
				{
					document.getElementById('newsflashdiv2').style.display = 'none';
				}
			}
			xmlhttpnewsflash.open('GET','newsflash.php',true);
			xmlhttpnewsflash.send();
		}
		//-->
	</script>");

	return $args;
}

function newsflash_run()
{
}
?>