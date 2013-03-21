<?php
function secretlab_dodisplay($main){
	$display = "<center><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	$display .= "<tr><td colspan=\"6\"><p align=\"center\">";
	$display .= "<img border=\"0\" src=\"./images/".$main."\" width=\"288\" height=\"215\"></td>";
	$display .= "</tr><tr>";
	$display .= "<td><a href=\"runmodule.php?module=secretlab&op=jar1\"><img border=\"0\" src=\"./images/secret_lab_jar1.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><a href=\"runmodule.php?module=secretlab&op=jar2\"><img border=\"0\" src=\"./images/secret_lab_jar2.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><a href=\"runmodule.php?module=secretlab&op=jar3\"><img border=\"0\" src=\"./images/secret_lab_jar3.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><a href=\"runmodule.php?module=secretlab&op=jar4\"><img border=\"0\" src=\"./images/secret_lab_jar4.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><a href=\"runmodule.php?module=secretlab&op=jar5\"><img border=\"0\" src=\"./images/secret_lab_jar5.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><a href=\"runmodule.php?module=secretlab&op=jar6\"><img border=\"0\" src=\"./images/secret_lab_jar6.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "</tr></table></center>";
	output("`b`cMix a Potion!`c`b`n");
	rawoutput($display);
	output("`c`b`@Click a bottle to add that potion to the base.`b`c`n");
	addnav("","runmodule.php?module=secretlab&op=jar1");
	addnav("","runmodule.php?module=secretlab&op=jar2");
	addnav("","runmodule.php?module=secretlab&op=jar3");
	addnav("","runmodule.php?module=secretlab&op=jar4");
	addnav("","runmodule.php?module=secretlab&op=jar5");
	addnav("","runmodule.php?module=secretlab&op=jar6");
	addnav("`2Add `1Blue`2 Chemical`0","runmodule.php?module=secretlab&op=jar1");
	addnav("`2Add `@Green`2 Chemical`0","runmodule.php?module=secretlab&op=jar2");
	addnav("`2Add `6Yellow`2 Chemical`0","runmodule.php?module=secretlab&op=jar3");
	addnav("`2Add `4Red`2 Chemical`0","runmodule.php?module=secretlab&op=jar4");
	addnav("`2Add `QOrange`2 Chemical`0","runmodule.php?module=secretlab&op=jar5");
	addnav("`2Add `5Violet`2 Chemical`0","runmodule.php?module=secretlab&op=jar6");
}

function secretlab_nonavdisplay($main){
	$display = "<center><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\">";
	$display .= "<tr><td colspan=\"6\"><p align=\"center\">";
	$display .= "<img border=\"0\" src=\"./images/".$main."\" width=\"288\" height=\"215\"></td>";
	$display .= "</tr><tr>";
	$display .= "<td><img border=\"0\" src=\"./images/secret_lab_jar1.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><img border=\"0\" src=\"./images/secret_lab_jar2.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><img border=\"0\" src=\"./images/secret_lab_jar3.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><img border=\"0\" src=\"./images/secret_lab_jar4.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><img border=\"0\" src=\"./images/secret_lab_jar5.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "<td><img border=\"0\" src=\"./images/secret_lab_jar6.gif\" width=\"48\" height=\"108\"></td>";
	$display .= "</tr></table></center>";
	output("`b`cMix a Potion!`c`b`n");
	rawoutput($display);
}

function secretlab_bigtext($text){
	rawoutput("<big>");
	$text = translate_inline($text);
	output_notl($text);
	rawoutput("</big>");
}
?>