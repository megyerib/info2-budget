<?php
include 'head.php';
if ($userNo == 0) {
    alert('U can\'t kill Urself!', 'error');
    header('Location: /settings/user');
    exit();
}

if (!(isset($_POST['wirklich']) || isset($_POST['nuke']))) {
    echo "<center>";
    echo "<h2 style='color:red; margin-top:40px;'>Möchten Sie WIRKLICH Ihre Account löschen?</h2>";
    changeTitle('Benutzer Löschen');
    echo "Diese Aktion ist UNWIEDERRUFLICH!";

    echo "<form method='post' style='margin:40px;'>";
    echo "<p>Passwort:<br>";
    echo "<input type='password' name='pw'></p>";
    echo "<input type='submit' name='wirklich' value='Ja!'>";
    echo "</form>";
    echo "</center>";
}

if (isset($_POST['wirklich'])) {
    $mysqli->real_query("SELECT COUNT(*) FROM user WHERE password = SHA('$_POST[pw]') AND no = $userNo");
    $result = $mysqli->use_result();
    $row = $result->fetch_row();
    $result->close();
    if ($row[0] == 0) {
        alert('Falsches Passwort!', 'error');
        header('Location: /settings/user/delete');
        exit();
    }

    echo "<center>";
    echo "<h2 style='color:red; margin-top:40px; font-size:200%;'>WIRKLICH?</h2>";
    changeTitle('Benutzer Löschen');

    echo "<form method='post' style='margin:40px;'>";
    echo "<input type='submit' name='nuke' value=\"Let's nuke it!\"></center>";
    echo "</form>";
    echo "<center>";
}

if (isset($_POST['nuke'])) {
    $d1 = $mysqli->query("DELETE FROM expense WHERE user = $userNo");
    $d2 = $mysqli->query("DELETE FROM category WHERE user = $userNo");
    $d3 = $mysqli->query("DELETE FROM category_detect WHERE user = $userNo");
    $d4 = $mysqli->query("DELETE FROM user WHERE no = $userNo");

    if ($d1 && $d2 && $d3 && $d4) {
        alert('Tschüss!');
        header('location: /logout/');
        exit();
    }
    else if ($d4) { // Csak úgy, ha később kéne
        alert('Tschüss!');
        header('location: /logout/');
        exit();
    }
    else {
        alert('Wir können dich nincht löschen. Vielleicht bist du aus Gold.', 'error');
        header('location: /settings/user');
        exit();
    }
}

include 'foot.php';
?>
