<?php
increment_module_pref("used");
$mgchance = e_rand(1,100) + floor($session['user']['level'] / 5) - get_module_setting('chancemod');
$mgresult = 0;
if ($mgchance>70) $mgresult = 1;
if ($mgchance>95) $mgresult = 2;
addnav("Buy more", "runmodule.php?module=mysterygems&op=enter");
villagenav();
?>