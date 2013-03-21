<?php
	$id=httpget('userid');
	addnav("Forest Specials");
	addnav("Doppleganger","runmodule.php?module=doppleganger&op=superuser&subop=edit&userid=$id");
?>