<?php
include('head.php');

// "Állapotválasztó"
if ((isset($_POST['uploadSubmit']) && isset($_FILES['csvData'])) || isset($_POST['CSVedited'])) {
    $state = 'categorySet';
} else if(isset($_POST['completeUpload'])) {
    $state = 'dbInsert';
} else if(isset($_POST['fullCSV'])) {
    $state = 'csvEdit';
} else {
    $state = 'uploadForm';
}

// Cím
$pageTitle = "Dateiimport aus csv File";
echo "<h2>$pageTitle</h2>";
changeTitle($pageTitle);

// 1. Form
if ($state == 'uploadForm') {
    echo '<div style="float:left;"><img src="/img/csv.png" style="height:93px; padding-right:10px;"></div>';
    echo '<form method="post" enctype="multipart/form-data" style="margin:0;">
        <p>Wählen Sie ein <b>csv</b> File zu hochzuladen:</p>
        <input type="file" name="csvData"><br>
        <p><input type="submit" value="File hochladen" name="uploadSubmit"></p>
    </form>';
}

// 2. Táblázat, kategóriabeállítás
if ($state == 'categorySet') {
    // Függvények kategóriafelismeréshez
    function removeAccents($str) {
      $a = array('Á','É','Í','Ó','Ő','Ú','Ű','á','é','í','ó','ő','ú','ű');
      $b = array('A','E','I','O','O','U','U','a','e','i','o','o','u','u');
      return str_replace($a, $b, $str);
    }
    function categoryDetect($subject) {
        global $mysqli;
        global $userNo;

        $mysqli->real_query(
            "SELECT *
            FROM category_detect
            WHERE user = $userNo
            OR user = 0"
        );
        $result = $mysqli->use_result();
        while ($row = $result->fetch_assoc()) {
            $pattern = removeAccents(strtolower($row['pattern']));
            $text    = removeAccents(strtolower($subject));
            if (preg_match($pattern, $text)) {
                $result->close();
                return (int)$row['category'];
            }
        }
        return 1; // Egyéb
    }

    // Feltöltött fájl ok, szerkesztő táblázat kirajzolása
    if (isset($_POST['csv']) || $_FILES['csvData']['type'] == 'text/csv') { // Ellenőrzés (bővíthető)
        // Ha fájlt töltöttek fel
        if (!isset($_POST['csv'])) {
            $filePath  = $_FILES['csvData']['tmp_name'];
            $inputData = file_get_contents($filePath);
            $inputData = str_replace("'", "", $inputData);
        }
        // Ha szerkesztették a csv-t
        else {
            $inputData = $_POST['csv']; // Szerkesztették
        }
        $inputArray     = explode(PHP_EOL, $inputData); // Szétbontás sorokra
        $items = array();

        // Táblázat
        echo "<p>Stell Typen ein!</p>";
        echo "<form method='post'>";
        echo "<input type='hidden' name='fullCSV' value='$inputData'>";
        for ($i = 1; $i < count($inputArray)-1; $i++) { // count-1? Üres sor a végén?
            $row = str_getcsv($inputArray[$i]);
            $date = preg_replace('/(\d+).(\d+).(\d+)./i', '$1-$2-$3', $row[0]);

            echo"<input type='date' name='date[]'        value='$date'> ".
                "<input type='text' name='description[]' value='$row[1]' style='width:400px;'> ".
                categoryDropdown(categoryDetect($row[1])).' '.
                "<input type='text' name='amount[]'      value='$row[6]' style='width:80px;'><br>";
        }
        echo '<br>
                <div style="float:left;"><input type="submit" value="CSV aufbereiten" name="csvEdit"></div>
            <center>
                <input type="submit" value="Hochladen" name="completeUpload"><br><br>
            </center>';
        echo '</form>';
    }
    // Rossz fájltípus
    else {
        echo '<p class="error">Falscher Filetyp!<br>(Soll .csv sein)</p>';
        echo '<p><a href="">Neuen Versuch</a></p>';
    }
}

// 2.5 CSV szerkesztése
if ($state == 'csvEdit') {
    $csv = $_POST['fullCSV'];
    echo "<form method='post'>";
    echo "<center><textarea style='width:99%; height:400px;' name='csv'>$csv</textarea>";
    echo "<p><input type='submit' name='CSVedited' value='Weiter'></center></p>";
    echo "</form>";
}

// 3. Adatbázisba feltöltés
if ($state == 'dbInsert') {
    $importOK = $importNOK = 0; // Sikeres/hibás beszúrások

    for ($i = 0; $i < count($_POST['date']); $i++) {
        $insert_dat = mysqli_real_escape_string($mysqli, $_POST['date'][$i]);
        $insert_des = mysqli_real_escape_string($mysqli, $_POST['description'][$i]);
        $insert_cat = mysqli_real_escape_string($mysqli, $_POST['category'][$i]);
        $insert_mnt = mysqli_real_escape_string($mysqli, $_POST['amount'][$i]);

        $queryString =
            "INSERT INTO expense(date, description, category, amount, user)
             VALUES ('$insert_dat', '$insert_des', $insert_cat, $insert_mnt, $userNo)";

        // Duplikátummegelőzés miatt csak egyenként lehet beszúrni
        // http://stackoverflow.com/questions/8756689/avoiding-inserting-duplicate-rows-in-mysql
        $error = false;
        try {
            $insert = $mysqli->query($queryString);
        }
        catch (Exception $e) {
        	$error = true;
        }

        if (!$error) $importOK++;
        else $importNOK++;
    }


    if ($importOK)
        echo "<p>$importOK Reihen wurde erfolgreich importiert. :)</p>";
    if ($importNOK)
        echo "<p style='color:red;'>Es passierte Fehler bei $importNOK Reihen. Vielleicht waren sie schon gespeichert.</p>";

    if ($importNOK) $backLink = 'Neuer Versuch';
    else $backLink = 'Andere Tafel importieren';

    echo "<p><a href=''>$backLink</a></p>";
}
?>
<?php include('foot.php'); ?>
