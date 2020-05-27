<?php
include "head.php";

$pagetitle = "Einstellungen";
echo "<h2>$pagetitle</h2>";
changeTitle($pagetitle);
?>
<ul>
    <li><a href="/settings/user">Benutzer</a></li>
    <li><a href="/settings/categories">Kategorien</a></li>
    <li><a href="/settings/autodetect">Kategorienerkennung</a></li>
</ul>
<?php
include "foot.php";
?>
