<?php
if (isset($_POST['login'])) { // Megpróbáltak bejelentkezni
    include 'db.php';

    $user = mysqli_real_escape_string($mysqli, $_POST['user']);
    $pass = mysqli_real_escape_string($mysqli, $_POST['password']);

    $mysqli->real_query(
        "SELECT *
        FROM user
        WHERE user = '$user'
        AND password = SHA('$pass')"
    );
    $result = $mysqli->use_result();
    if ($row = $result->fetch_assoc()) {
        setcookie('userNo', $row['no'], time()+(60*60*24*30), "/");
        header('Location: ' . $_SERVER['REQUEST_URI']);
    }
    else {
        alert('Falscher Benutzer/Passwort!', 'error');
    }
    $result->close();
}

// Logout
if (isset($_GET['logout'])) {
    setcookie('userNo', '', time()-3600, "/"); // Süti törlése
    header('Location: /');
    exit();
}

// Login page
if (!isset($_COOKIE['userNo'])) {
?>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <link type="text/css" rel="stylesheet" href="/css/alertbox.css">
</head>
<body style="width:400px;">
    <h1 style="color:darkorange; text-align:center;">Transaktionsverwalterapp</h1>
    <h2 style="text-align:center;">Anmelden</h2>
    <?php changeTitle('Anmelden'); ?>
    <div id="main">
        <?php if(isset($error)){echo $error;} ?>
        <form method="post">
            <label style="width:70px; margin-bottom:10px;">Benutzer</label>
            <input type="text" name="user" style="width:303px;">
            <label style="width:70px; margin-bottom:10px;">Passwort</label>
            <input type="password" name="password" style="width:303px;">
            <center><input type="submit" name="login" value="Anmelden"></center>
        </form>
    </div>
    <p style='text-align:center;'><a href='/registration'>Registration</a></p>

    <?php
    // Tesztfelhasználók
    $testPass = 'password';
    $mysqli->real_query(
        "SELECT user
        FROM user
        WHERE password = SHA('$testPass')"
    );
    $result = $mysqli->use_result();
    echo '<p>Testbenutzern:</p><ul>';
    while ($row = $result->fetch_row()) {
        echo '<li>'.$row[0].'</li>';
    }
    $result->close();
    echo "</ul><p>Passwort: <i>$testPass</i></p>";
    alertbox(); // Üzenet
    ?>
</body>
</html>
<?php
if (isset($mysqli)) {
    $mysqli->close();
}
die();
}// If vége
?>
