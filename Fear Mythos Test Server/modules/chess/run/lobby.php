<?php
require_once('lib/commentary.php');
output_notl("`n`n`c");
output("`7Here you wait for other players, and chat to game players.");
output_notl("`n`n`c");
addcommentary();
commentdisplay("", "chess-lobby","",25,"posts");
addnav("Main Page", "runmodule.php?module=chess&op=enter");
?>