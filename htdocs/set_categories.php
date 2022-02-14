<?php
include "head.php";

$pagetitle = "Kategorien";
echo "<h2>$pagetitle</h2>";
changeTitle($pagetitle);

// Új kat. felvétele
if (isset($_POST['new_submit']) && !empty($_POST['new_submit'])) {
    $newCat = mysqli_real_escape_string($mysqli, $_POST['newCat']);
    $insert = $mysqli->query(
        "INSERT INTO category(name, user)
        VALUES ('$newCat', $userNo)"
    );
    twoway_alert($insert, 'Einfügen');
}

// Módosítás/törlés
if (isset($_POST['catSave']) && count($_POST['catNo']) > 0) {
    $setQuery = "";
    for ($i = 0; $i < count($_POST['catNo']); $i++) {
        $catNo   = mysqli_real_escape_string($mysqli, $_POST['catNo'][$i]);
        $catName = mysqli_real_escape_string($mysqli, $_POST['catName'][$i]);
        $setQuery .=
            "UPDATE category SET name = '$catName'
            WHERE no = $catNo AND user = $userNo;"; // Ne írja át másét
    }
    $update = $mysqli->multi_query($setQuery);

    while ($mysqli->next_result()) { // flush multi_queries
        if (!$mysqli->more_results()) break; // magic, amitől működik
    }

    // Törlés
    if (isset($_POST['catDel']) && count($_POST['catDel']) > 0) {
        $delArray = array();
        foreach ($_POST['catDel'] as $delNo) {
            array_push($delArray, $delNo);
        }
        $toDel = implode(',', $delArray);
        $delQuery =
            "DELETE FROM category
            WHERE no in ($toDel) AND user = $userNo"; // Ne törölje másét
        $del = $mysqli->query($delQuery);

        // Kiadások kategóriájának átállítása egyébbe
        $catReset =
            "UPDATE expense
            SET category = 0
            WHERE category in ($toDel) AND user = $userNo"; // Ha vhogy beletrollkodna
        $cres = $mysqli->query($catReset);
    }
    twoway_alert(($update || ($del && $cres)), 'Modifizieren');
}

// Beépített kategóriák
$mysqli->real_query("SELECT * FROM category WHERE user = 0");
$result = $mysqli->use_result();
echo "<h3>Eingebaute kategorien</h3>";
echo "<ul>";
while ($row = $result->fetch_assoc()) {
    echo "<li>".$row["name"]."</li>";
}
echo "</ul>";
$result->close();

// Saját kategóriák (szerkesztő)
$mysqli->real_query("SELECT * FROM category WHERE user = $userNo");
$result = $mysqli->use_result();
echo "<h3>Eigene kategorien</h3>";
echo "<form method='post'>";
echo "<table><tr><td>Name</td><td>Del</td></tr>";
while ($row = $result->fetch_assoc()) {
    $catName = $row["name"];
    $catNo   = $row["no"];
    echo "<input type='hidden' name='catNo[]' value='$catNo'>";
    echo "<tr><td>";
    echo "<input type='text' name='catName[]' value='$catName'>";
    echo "</td><td>";
    echo "<input type='checkbox' name='catDel[]' value='$catNo'>";
    echo "</td></tr>";
}
echo "</table>";
$result->close();
echo "<p><input type='submit' name='catSave' value='Speichern/Löschen'></p>";
echo "</form>";
echo "<hr width='50%' align='left'>";

// Új
echo "<form method='post'>";
    echo "<input type='text' name='newCat'> ";
    echo "<input type='submit' value='Neu' name='new_submit'>";
echo "</form>";

include "foot.php";
?>
