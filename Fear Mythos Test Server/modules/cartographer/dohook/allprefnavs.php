<?php
	$id=httpget('userid');
	addnav("Village Modules");
	addnav("Cartographer","runmodule.php?module=cartographer&op=superuser&subop=edit&userid=$id");
?>