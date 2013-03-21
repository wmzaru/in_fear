<?php
require_once("lib/nltoappon.php");
output_notl("`n`n`c");
output("`#RULES`0");
output_notl("`n`n`c");
output("%s", nltoappon(get_module_setting('rules')));
addnav("Return to Main Page", "runmodule.php?module=chess&op=enter");
addnav("Return to Village", "village.php");
?>