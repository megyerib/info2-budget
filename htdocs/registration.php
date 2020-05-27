<html>
<head>
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <link type="text/css" rel="stylesheet" href="/css/alertbox.css">
    <title>Registration</title>
    <link rel="shortcut icon" type="image/png" href="/img/favicon.png"/>
</head>
<body style="width:400px;">
<?php
    session_start();
    include 'db.php';
    include 'alertbox.php';
    if (isset($_POST['register'])) {
        $name  = mysql_real_escape_string($_POST['name']);
        $user  = mysql_real_escape_string($_POST['user']);
        $email = mysql_real_escape_string($_POST['email']);
        $pw1   = mysql_real_escape_string($_POST['password1']);
        $pw2   = mysql_real_escape_string($_POST['password2']);

        $alertMsg = '';
        if (!preg_match('/^[a-z0-9-_]+$/', $user))
            $alertMsg .= 'Falsche Benutzername!<br>';
        if (!preg_match("/^[\\p{L} .'-]+$/u", $name))
            $alertMsg .= 'Falsche Vollständige Name!<br>';
        $mailRegex = "/^[a-z0-9._-]+@[a-z0-9.-]+\.[a-z]{2,4}$/";
        if (!preg_match($mailRegex, $email))
            $alertMsg .= 'Falsche E-mail Addresse!<br>';
        if ($pw1 != $pw2 || !preg_match('/^.{8,}$/', $pw1))
            $alertMsg .= 'Falsches Passwort!<br>';

        if (!empty($alertMsg)) {
            alert($alertMsg, 'error');
        }
        else {
            $query =
                "INSERT INTO user(user, name, email, password)
                VALUES ('$user', '$name', '$email', SHA('$pw1'))";
            $insert = $mysqli->query($query);
            if ($insert) {
                alert('Registration war erfolgreich!<br>Sie können schon anmelden.');
                header('Location: /');
                exit();
            }
            else {
                alert('Es gab Fehler beim Registration!<br>Benutzer/Email ist schon registriert.', 'error');
            }
        }
    }
    alertbox();
?>
    <h1 style="color:darkorange; text-align:center;">Transaktionsverwalterapp</h1>
    <h2 style="text-align:center;">Registration</h2>
    <div id="main">
        <?php if(isset($error)){echo $error;} ?>
        <form method="post">
            <label style="width:120px; margin-bottom:10px;">Benutzername</label>
            <input type="text" name="user" style="width:253px;" value="<?php echo isset($user)?$user:''; ?>">
            <label style="width:120px; margin-bottom:10px;">Vollst. Name</label>
            <input type="text" name="name" style="width:253px;" value="<?php echo isset($name)?$name:''; ?>">
            <label style="width:120px; margin-bottom:10px;">E-mail</label>
            <input type="text" name="email" style="width:253px;" value="<?php echo isset($email)?$email:''; ?>">
            <label style="width:120px; margin-bottom:10px;">Passwort</label>
            <input type="password" name="password1" style="width:253px;">
            <label style="width:120px; margin-bottom:10px;">Noch einmal</label>
            <input type="password" name="password2" style="width:253px;">

            <center><input type="submit" name="register" value="Registration"></center>
        </form>
    </div>


    <p style='text-align:center;'><a href='/'>Zurück zur Hauptseite</a></p>
</body>
</html>
