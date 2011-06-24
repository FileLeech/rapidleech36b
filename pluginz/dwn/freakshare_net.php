<?php    
if (!defined('RAPIDLEECH')){
  require_once("404.php");
  exit;
}

	$LINK = "http://freakshare.com".$Url["path"];
echo "<form action=\"".$PHP_SELF."\" method=\"post\">$nn";
echo "<input type=\"hidden\" name=\"link\" value=\"$LINK\">$nn";

if (($_GET["premium_acc"] == "on" && $_GET["premium_user"] && $_GET["premium_pass"]) || ($_GET["premium_acc"] == "on" && $premium_acc["freakshare_net"]["user"] && $premium_acc["freakshare_net"]["pass"]))
{
	$premium_user = ($_GET["premium_user"] ? $_GET["premium_user"] : $premium_acc["freakshare_net"]["user"]);
	$premium_pass = ($_GET["premium_pass"] ? $_GET["premium_pass"] : $premium_acc["freakshare_net"]["pass"]);
echo "<input type=\"hidden\" name=\"premium_acc\" value=\"on\">$nn";
echo "<input type=\"hidden\" name=\"premium_user\" value=\"$premium_user\">$nn";
echo "<input type=\"hidden\" name=\"premium_pass\" value=\"$premium_pass\">$nn";
}
?>
<script language="JavaScript">
void(document.forms[0].submit());
</script>
<?php
echo "</form></body></html>";
?>