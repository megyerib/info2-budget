<?php
include('head.php');

if (isset($_GET['expense'])) {
    $expense = $_GET['expense'];
}
else {
    header('Location: /expenses');
    exit();
}

// Igen
if (isset($_POST['yes'])) {
    $delete = $mysqli->query(
        "DELETE FROM expense
        WHERE no = $expense
        AND user = $userNo"
    );
    twoway_alert($delete, 'Löschen');
    if ($delete) {
        header('Location: /expenses');
        exit();
    }
}
// Nem
if (isset($_POST['no'])) {
    header('Location: /expenses/'.$expense);
    exit();
}

// Kérdés
echo '<h2 style="color:red;">Wirklich möchtest du dieses Artikel Löschen?</h2>';
changeTitle('Artikel löschen');

// Kiírás
$mysqli->real_query(
    "SELECT *
    FROM expense e
    JOIN category c
    ON e.category = c.no
    WHERE e.no = $expense
    AND e.user = $userNo"
);
$result = $mysqli->use_result();
$row = $result->fetch_assoc();
echo '<table width="100%"><tr>';
echo '<td>'.$row['date'].'</td><td>'.$row['description'].'</td><td>'.
    $row['name'].'</td><td style="text-align:right;">'.$row['amount'].'</td>';
echo '</tr></table>';

echo '<br><form method="post">';
echo '<input type="submit" name="yes" value="Ja"> ';
echo '<input type="submit" name="no" value="Nein">';
echo '</form>';

include('foot.php');
?>
