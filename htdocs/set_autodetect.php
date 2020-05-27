<?php
include "head.php";

$pagetitle = "Kategorieerkennung";
echo "<h2>$pagetitle</h2>";
changeTitle($pagetitle);

// Új kat. felvétele
if (isset($_POST['new_submit']) && !empty($_POST['new_submit'])) {
    $newCat = mysql_real_escape_string($_POST['newCat']);
    $C = $_POST['category'][0];
    $insert = $mysqli->query(
        "INSERT INTO category_detect(pattern, user, category)
        VALUES ('$newCat', $userNo, $C)"
    );
    twoway_alert($insert, 'Einfügen');
}

// Módosítás/törlés
if (isset($_POST['catSave']) && count($_POST['catNo']) > 0) {
    $setQuery = "";
    for ($i = 0; $i < count($_POST['catNo']); $i++) {
        $catNo   = mysql_real_escape_string($_POST['catNo'][$i]);
        $catName = mysql_real_escape_string($_POST['catName'][$i]);
        $C       = mysql_real_escape_string($_POST['category'][$i]);
        $setQuery .=
            "UPDATE category_detect SET pattern = '$catName', category = $C
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
            "DELETE FROM category_detect
            WHERE no in ($toDel) AND user = $userNo"; // Ne törölje másét
        $del = $mysqli->query($delQuery);
    }
    twoway_alert(($update || $del), 'Modifizieren');
}

// Beépített kategóriák
$mysqli->real_query(
    "SELECT d.pattern, c.name
    FROM category_detect d
    JOIN category c ON d.category = c.no
    WHERE d.user = 0"
);
$result = $mysqli->use_result();
echo "<h3>Eingebaut</h3>";
echo "<table><tr><th>RegEx</th><th>Kategorie</th></tr>";
while ($row = $result->fetch_assoc()) {
    echo "<tr>";
    echo "<td style='padding-right:15px;'>".$row["pattern"]."</td><td>".
        $row["name"]."<td>";
    echo "</tr>";
}
echo "</table>";
$result->close();

echo categoryDropdown('load'); // Lekérdezés a lekérdezésben alább nem működik

// Saját kategóriák (szerkesztő)
$mysqli->real_query("SELECT * FROM category_detect WHERE user = $userNo");
$result = $mysqli->use_result();
echo "<h3>Eigene</h3>";
echo "<form method='post'>";
echo "<table><tr><th>RegEx</th><th>Kategorie</th><th>Del</th></tr>";
while ($row = $result->fetch_assoc()) {
    $catName = $row["pattern"];
    $catNo   = $row["no"];
    echo "<input type='hidden' name='catNo[]' value='$catNo'>";
    echo "<tr><td>";
    echo "<input type='text' name='catName[]' value='$catName'>";
    echo "</td><td>";
    echo categoryDropdown($row["category"]);
    echo "</td><td>";
    echo "<input type='checkbox' name='catDel[]' value='$catNo'>"; // Del
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
    echo categoryDropdown();
    echo " <input type='submit' value='Neu' name='new_submit'>";
echo "</form>";

include "foot.php";
?>
