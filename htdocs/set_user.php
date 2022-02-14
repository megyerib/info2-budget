<?php
include 'head.php';

echo '<h2>Benutzereinstellungen</h2>';
changeTitle('Benutzereinstellungen');

if (isset($_POST['save'])) {
    $name  = mysqli_real_escape_string($mysqli, $_POST['name']);
    $email = mysqli_real_escape_string($mysqli, $_POST['email']);
    $pw1   = mysqli_real_escape_string($mysqli, $_POST['password1']);
    $pw2   = mysqli_real_escape_string($mysqli, $_POST['password2']);

    $alertMsg = '';
    if (!preg_match("/^[\\p{L} .'-]+$/u", $name))
        $alertMsg .= 'Falsche Vollständige Name!<br>';
    $mailRegex = "/^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$/";
    if (!preg_match($mailRegex, $email))
        $alertMsg .= 'Falsche E-mail Addresse!<br>';

    $pwset = '';
    if (!empty($pw1) || !empty($pw2)) {
        if (($pw1 != $pw2 || !preg_match('/^.{8,}$/', $pw1)))
            $alertMsg .= 'Falsches Passwort!<br>';
        else
            $pwset = ", password = SHA('$pw1')";
    }

    if (!empty($alertMsg)) {
        alert($alertMsg, 'error');
    }
    else {
        $query =
            "UPDATE user
            SET name = '$name',
            email = '$email'$pwset
            WHERE no = $userNo";
        $update = $mysqli->query($query);
        twoway_alert($update, 'Speichern');
        header("Location: $_SERVER[REQUEST_URI]"); // Frissítse a nevet is felül
        exit();
    }
}

$mysqli->real_query("SELECT * FROM user WHERE no = $userNo LIMIT 1");
$result = $mysqli->use_result();
$user = $result->fetch_assoc();
$result->close();

echo "<form method='post'>";
echo "<label>Benutzername</label>$user[user]<br><br>"; // Wat?!
echo "<label style='width:120px; margin-bottom:10px;'>Vollst. Name</label>";
echo "<input type='text' name='name' style='width:253px;' value='$user[name]'><br>";
echo "<label style='width:120px; margin-bottom:10px;'>E-mail</label>";
echo "<input type='text' name='email' style='width:253px;' value='$user[email]'><br><br>";
echo "<label style='width:120px; margin-bottom:10px;'>Passwort</label>";
echo "<input type='password' name='password1' style='width:253px;'><br>";
echo "<label style='width:120px; margin-bottom:10px;'>Noch einmal</label>";
echo "<input type='password' name='password2' style='width:253px;'><br>";

echo "<input type='submit' name='save' value='Speichern'>";
echo "</form>";

echo "<p><a href='/settings/user/delete'>Benutzer löschen</a></p>";

include 'foot.php';
?>
