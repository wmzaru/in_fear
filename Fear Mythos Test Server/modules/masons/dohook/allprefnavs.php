<?php
	$id=httpget('userid');
	addnav("Village Modules");
	addnav("Secret Order of Masons","runmodule.php?module=masons&op=superuser&subop=edit&userid=$id");
?>