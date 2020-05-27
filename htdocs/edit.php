<?php
include('head.php');
echo '<h2>Artikel aufbereiten</h2>';
changeTitle('Artikel aufbereiten');

if (isset($_GET['expense'])) {
    $expense = $_GET['expense'];
}
else {
    header('Location: /expenses');
    exit();
}

// Mentés
if (isset($_POST['save']) || isset($_POST['savequit'])) {
    $desc = mysql_real_escape_string($_POST['description']);
    $date = mysql_real_escape_string($_POST['date']);
    $cat  = mysql_real_escape_string($_POST['category'][0]);
    $amnt = mysql_real_escape_string($_POST['amount']);

    $save = $mysqli->query(
        "UPDATE expense
        SET description = '$desc',
        date = '$date',
        category = $cat,
        amount = $amnt
        WHERE no = $expense
        AND user = $userNo"
    );

    twoway_alert($save, 'Speichern');

    if (isset($_POST['savequit'])) {
        header('Location: /expenses');
        exit();
    }
}

// Törlés
if (isset($_POST['delete'])) {
    header('Location: /expenses/'.$expense.'/delete');
    exit();
}

// Vissza
if (isset($_POST['back'])) {
    header('Location: /expenses');
    exit();
}

// Megjelenítés
$mysqli->real_query(
    "SELECT *
    FROM expense
    WHERE no = $expense
    AND user = $userNo"
);
$result = $mysqli->use_result();
$exp    = $result->fetch_assoc();
$result->close();
//echo print_r($exp);

?>
<form method="post">
    <p><label>Date</label>
    <input type="date" style="width:250px;" name="date"
        value="<?php echo $exp['date']; ?>"></p>
    <p><label>Beschreibung</label>
    <input type="text" style="width:250px;" name="description"
        value="<?php echo $exp['description']; ?>"></p>
    <p><label>Kategorie</label>
    <?php echo categoryDropdown($exp['category']); ?></p>
    <p><label>Preis</label>
    <input type="number" style="width:250px;" name="amount"
        value="<?php echo $exp['amount']; ?>"></p>

    <input type="submit" name="savequit" value="Sp. & Verlassen">
    <input type="submit" name="save" value="Speichern">
    <input type="submit" name="delete" value="Löschen">
    <input type="submit" name="back" value="Zurück">
</form>
<?php
include('foot.php');
?>
