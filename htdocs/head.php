<?php
// HTTPS
if($_SERVER["HTTPS"] != "on") {
    header("Location: https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
    exit();
}

session_start();

// Alert box
include 'alertbox.php';

// MySQL
include 'db.php';

include 'login.php';

// Username
$userNo = $_COOKIE['userNo'];
$mysqli->real_query(
    "SELECT *
    FROM user
    WHERE no = $userNo"
);
$result = $mysqli->use_result();
$row = $result->fetch_assoc();
$userName = $row['name'];
$result->close();

function categoryDropdown($chosen = 1) {
    global $categoriesArray;
    global $userNo;
    if (empty($categoriesArray)) {
        global $mysqli;

        $mysqli->real_query(
            "SELECT *
            FROM category
            WHERE user = $userNo
            OR user = 0 -- Közös kategóriák
            ORDER BY name"
        );
        $result = $mysqli->use_result();
        while ($row = $result->fetch_assoc()) {
            $categoriesArray[$row['no']] = $row['name'];
        }
        $result->close();
    } // Globális létrehozása

    if ($chosen == 'load') return; // Csak betölteni akarjuk a listát

    // Kiír
    ob_start();
    echo '<select name="category[]">';
    foreach ($categoriesArray as $no => $name) {
        echo '<option value="'.$no.'"'.($chosen == $no ? ' selected' : '').'>'.
            $name.'</option>';
    }
    echo '</select>';
    $ret = ob_get_contents();
    ob_clean();
    return $ret;
}

function changeTitle($title) {
    echo "<script>document.title = '$title';</script>";
}
?>
<html>
<head>
    <meta charset="UTF-8">
    <link type="text/css" rel="stylesheet" href="/css/style.css">
    <link type="text/css" rel="stylesheet" href="/css/menu.css">
    <link type="text/css" rel="stylesheet" href="/css/alertbox.css">
    <link rel="shortcut icon" type="image/png" href="/img/favicon.png"/>
</head>
<body>
    <!-- Title -->
    <div style="position:absolute; right:0px; text-align:right;">
        <!-- Username -->
        Angemeldet als <?php echo $userName; ?><br>
        <a href="/logout/">Abmelden</a>
    </div>
    <h1 id="title">Transaktionsverwalterapp</h1>

    <!-- Menu -->
    <div id="menuBar"><nav><ul>
        <li><a href="/">Heim</a></li>
        <li><a href="/expenses">Ausgaben</a></li>
        <li><a href="/import">Import</a></li>
        <li><a href="/stats">Statistik</a></li>
        <li><a href="/settings">Einstellungen</a>
            <ul>
                <li><a href="/settings/user">Benutzer</a></li>
                <li><a href="/settings/categories">Kategorien</a></li>
                <li><a href="/settings/autodetect">Kategorienerkennung</a></li>
            </ul>
        </li>
    </ul></nav></div>

    <!-- Main div -->
    <div id="main">
