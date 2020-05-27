<?php
include 'head.php';
echo '<h2>Monatliche statistiken</h2>';
changeTitle('Monatliche statistiken');

// Ezekbe a tömbökbe olvasunk be
$cats = array();
$rows = array();

// Oszlopdiagram tömbjei
$colDiagValues = array();
$colDiagText   = array();

// Léptethető idő
$mysqli->real_query(
    "SELECT 12 * (YEAR(CURDATE())
    	- YEAR(MIN(date)))
        + (MONTH(CURDATE())
        - MONTH(MIN(date))) AS diff_months
    FROM expense
    WHERE user = $userNo"
);
$result = $mysqli->use_result();
$row = $result->fetch_assoc();
$monthDiff = $row['diff_months'];
$pages = ceil($monthDiff/6);
$result->close();

if (isset($_GET['page']))   $tablePage = $_GET['page'];
else                        $tablePage = 1;

$startMonth = $tablePage*6;
$endMonth   = $startMonth-6;

$mysqli->real_query(
    "SELECT MONTH(date) month, YEAR(date) yr, category, SUM(amount) sum
    FROM expense
    WHERE date >= DATE_SUB(CURDATE(), INTERVAL $startMonth MONTH)
    AND date <= DATE_SUB(CURDATE(), INTERVAL $endMonth MONTH)
    AND user = $userNo
    GROUP BY MONTH(date), category
    ORDER BY date, category"
);

$result = $mysqli->use_result();
$prevMonth = $prevYr = 0;
while ($row = $result->fetch_assoc()) {
    // Köztes üres hónapok beírása (gány)
    if ($prevMonth != 0 && !($prevMonth == $row['month'] - 1 || $prevMonth == $row['month'] + 11)) {
        while ($prevMonth != $row['month']) {
            $prevYr    = $prevMonth!=13?$prevYr:$prevYr+1;
            $prevMonth = $prevMonth!=13?$prevMonth+1:1;
            $dateLabel = $prevYr.' '.date('M', mktime(0, 0, 0, $prevMonth, 10));
            $rows[$dateLabel][0] = 0;
        }
    }
    // Normális adatok bevitele
    $dateLabel = $row['yr'].' '.date('M', mktime(0, 0, 0, $row['month'], 10));
    $rows[$dateLabel][$row['category']] = $row['sum'];

    $prevMonth = $row['month'];
    $prevYr    = $row['yr'];
}
$result->close();

// Kategóriák
$mysqli->real_query(
    "SELECT *
    FROM category
    WHERE no > 0
    AND (user = $userNo OR user = 0)
    ORDER BY no"
);
$result = $mysqli->use_result();
while ($row = $result->fetch_assoc()) {
    $cats[$row['no']] = $row['name'];
}
    $cats[0] = 'Andere';
$result->close();

echo '<table width="100%">';
echo '<tr><th width="120"></th>';
foreach ($rows as $month => $content) {
    array_push($colDiagText, $month); // Oszlopdiag. felirat
    echo "<th>$month</th>";
}
echo '</tr>';
foreach ($cats as $cat_no => $cat_name) {
    echo '<tr>';
    echo "<td>$cat_name</td>";
    foreach ($rows as $col) {
        $amount = isset($col[$cat_no]) ? $col[$cat_no] : 0;
        echo "<td align='right'>$amount</td>";
    }
    echo '</tr>';
}

// Havi összes
echo '<tr style="font-weight:bold;"><td>Insgesamt</td>';
foreach ($rows as $month) {
    echo '<td align="right">'.array_sum($month).'</td>';
    array_push($colDiagValues, array_sum($month)); // Tömb feltöltése a diagramhoz
}
echo '</tr>';
echo '</table>';

// Oldalléptető
echo "<p>";
for ($i = 1; $i <= $pages; $i++) {
    echo "<a href='/stats/page$i'>";
    echo $i != $tablePage ? $i : "<b>[$i]</b>";
    echo " </a>";
}
echo "</p>";

// Végösszeg

// Kategóriák
$mysqli->real_query(
    "SELECT SUM(amount)
    FROM expense
    WHERE user = $userNo"
);
$result = $mysqli->use_result();
$row = $result->fetch_row();
$sum = $row[0];
$result->close();

echo "<div class='highlight' style='margin-top:10px;'>Kasse: $sum</div>";

echo "<h2 style='text-align:center;'>Monatliche Statistik</h2>";

include 'diagram.php';
barGraph($colDiagValues, $colDiagText);

// Kördiagram (külön bevétel, kiadás)
$mysqli->real_query(
    "SELECT MONTH(date) month, YEAR(date) yr, category, SIGN(amount) as sign, SUM(amount) sum
    FROM expense
    WHERE date >= DATE_SUB(CURDATE(), INTERVAL $startMonth MONTH)
    AND date <= DATE_SUB(CURDATE(), INTERVAL $endMonth MONTH)
    AND user = $userNo
    AND amount != 0
    GROUP BY MONTH(date), category, sign
    ORDER BY date desc, category asc, SIGN(amount)"
);
$pieSource = $pieText = $pieData = $pieTitle = array();

$result = $mysqli->use_result();
while ($row = $result->fetch_assoc()) {
    $pieSource[$row['month']][$row['sign']>0?'income':'expense'][$row['category']] = $row['sum'];
     $pieTitle[$row['month']] = $row['yr'].' '.date('M', mktime(0, 0, 0, $row['month'], 10));
}
$result->close();

include 'pie.php';

// Feliratok (sznob módon táblázat nélkül)
echo "<div style='text-align:center;'>";
echo "<h1 style='display:inline-block; margin:0; width:380; text-align:center;'>Einkommen</h1>";
echo "<h1 style='display:inline-block; margin:0; width:380; text-align:center;'>Ausgabe</h1>";
echo "</div>";

$pie_i = 1;

foreach ($pieSource as $month => $data) {
    echo "<div style='text-align:center; display:".($pie_i!=1?'none':'inline').";' class='pie_div' id='pie_$month'>";
    $pie_i++;
    if (isset($data['income']))
        foreach ($data['income'] as $cat => $amount) {
            array_push($pieText, $cats[$cat]);
            array_push($pieData, $amount);
        }
    pie($pieData, $pieText);
    $pieText = $pieData = array(); // Tömbök nullázása

    if (isset($data['expense']))
        foreach ($data['expense'] as $cat => $amount) {
            array_push($pieText, $cats[$cat]);
            array_push($pieData, -1*$amount);
        }
    pie($pieData, $pieText);
    $pieText = $pieData = array(); // Tömbök nullázása
    echo "<p style='text-align:center; position:relative; bottom:50px;'>$pieTitle[$month]</p>";
    echo '</div>';
}

echo "<p style='text-align:center;'>";
foreach ($pieTitle as $month => $dontcare) {
    echo "<a href='' onclick='pie_reveal($month); return false;'>$pieTitle[$month]</a>&nbsp;&nbsp;&nbsp;&nbsp;";
}
echo '</p>';
echo "<script>
    function pie_reveal(no) {
        div_id = 'pie_'+no;
        var divsToHide = document.getElementsByClassName('pie_div');
            for(var i = 0; i < divsToHide.length; i++){
                divsToHide[i].style.display = 'none';
        }
        document.getElementById(div_id).style.display='inline';
    }
</script>";

include 'foot.php';
?>
