<?php
include('head.php');

echo '<h2>Ausgaben</h2>';
changeTitle('Ausgaben');

// Add new
if (isset($_POST['addnew'])) {
    $desc = mysql_real_escape_string($_POST['description']);
    $date = mysql_real_escape_string($_POST['date']);
    $cat  = mysql_real_escape_string($_POST['category'][0]);
    $amnt = mysql_real_escape_string($_POST['amount']);

    $add = $mysqli->query(
        "INSERT INTO expense(description, date, category, amount, user)
        VALUES('$desc', '$date', $cat, $amnt, $userNo)"
    );
    twoway_alert($add, "Einfügen");
} // Addnew vége

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}
else {
    $page = 1;
}

$perPage = 10;
$skip = ($page-1)*$perPage;

// Táblázat
$mysqli->real_query(
    "SELECT *, e.no AS eno
    FROM expense e
    LEFT JOIN category c -- Legalább megjelenik a bejegyzés és lehet szerkeszteni
    ON e.category = c.no -- (más helyeken a törölt kategória nincs lekezelve)
    WHERE e.user = $userNo
    ORDER BY e.date DESC, e.no DESC
    LIMIT $skip, $perPage"
);
$result = $mysqli->use_result();

echo '<table width="100%">';
echo '<tr><th align="left">Date</th><th align="left">Beschreibung</th><th align="left">Kategorie</th><th align="right">Preis</th></tr>';
while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td>'.$row['date'].'</td><td>'.$row['description'].'</td><td>'.$row['name'].'</td><td style="text-align:right;">'.$row['amount'].'</td><td class="tableEdit"><a href="/expenses/'.$row['eno'].'">aufb.</a></td>';
    echo '</tr>';
}
$result->close();
// Add new
echo '<tr style="background:none;"><form method="post">';
echo '<td><input type="date" name="date" value="'.date('Y-m-d').'" style="width:160px;"></td>';
echo '<td><input type="text" name="description"></td>';
echo '<td>'.categoryDropdown().'</td>';
echo '<td style="text-align:right;"><input type="number" name="amount" style="width:60px;" value="0"></td>';
echo '<td><input type="submit" name="addnew" value="Neu"></td>';
echo '</form></tr>';
echo '</table>';

$mysqli->real_query(
    "SELECT COUNT(*) cnt
    FROM expense
    WHERE user = $userNo"
);
$result1 = $mysqli->use_result();
$row = $result1->fetch_array();
$pages = ceil($row['cnt']/$perPage);

echo '<p>';
for ($i = 1; $i <= $pages; $i++) {
    if ($page != $i) {
        echo '<a href="/expenses/page'.$i.'">'.$i.'</a> ';
    }
    else {
        echo '<a href="/expenses/page'.$i.'"><b>['.$i.']</b></a> ';
    }
}
echo '</p>';

include('foot.php');
?>
