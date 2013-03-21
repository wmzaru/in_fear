<?php
function bakery_purchase(){
	output("`n`@`c`bHara's `^Bakery`b`c`n");
	addnav("V?(V) Return to Village","village.php");
	$allprefs=unserialize(get_module_pref('allprefs'));
	$allprefs['pastrytoday']=1;
	set_module_pref('allprefs',serialize($allprefs));
}
function bakery_nogold(){
	output("`@Hara`# waits for the gold but you realize you don't have it.  She raises her voice and points to the door. `$'No pastry for you.  Come back tomorrow!'`n`n");
	output("`#Hanging your head in shame, you leave the store and hope she'll give you another chance tomorrow.");
}
?>