<?php
tlschema("faq");
popup_header("General Questions on Modules");
$c = translate_inline("Return to Contents");
rawoutput("<a href='petition.php?op=faq'>$c</a><hr>");
output("`n`n`c` Module questions`b`c`n");
output("`^1. What is a module?`n`n");
output("`@A mod, pretty much. Any and most of the content of the game exists due to the modules`n`n");
output("`^2. I played on different server to this one before and they had this cool feature. Can you add it?`n`n");
output("`@Depends, you gonna tell me what it is or link it?`n`n");
output("`^3. Okay, okay. Fine. How do I do that, then, smart arse?`n`n");
output("`@ Sending us money.`n`n");
output("`@ Nah, go to the server's log in page, or if you ARE logged in, go to the daily news.`n");
output("`@ Click About the [Game], with [Game] being server's name or LoGD, and then Module Info. Try finding it there and hope it comes with a working download link.`n");
rawoutput("<hr><a href='petition.php?op=faq'>$c</a>");
?>