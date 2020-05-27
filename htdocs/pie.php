<?php
function pie($values, $text = array()) {
    // Alap méretek
    $r = 50;
    $d = 2*$r;
    $k = $d*pi();
    $diagWidth  = 380;
    $diagHeight = 380;
    $centerX = $diagWidth/2;
    $centerY = $diagHeight/2;

    // Svg
    echo "<svg width='$diagWidth' height='$diagHeight'>";

    // Ha nincs érték, kilép, de a hely megmarad (kell a másik mellé)
    if (empty($values)) {
        echo "<text x='$centerX' y='$centerY' text-anchor='middle'>0</text>";
        echo "</svg>";
        return;
    }

    // Szögek számolgatása, stb.
    $percents = array();
    $colors = array('orange', '#24AF61', 'darkorange', '#1a7f46');

    $sum = array_sum($values);
    for ($i = 0; $i < count($values); $i++)
        $percents[$i] = $values[$i]/$sum;

    // Körcikkrajzolgatás
    $remaining = $k;
    for ($i = 0; $i < count($percents); $i++) {
        $fromBack = count($percents)-1-$i; // Sorszám hátulról
        $sliceColor = $colors[$fromBack % count($colors)];
        if ($i == 0 && count($percents) % count($colors) == 1) // Első != utolsó szín
            $sliceColor = $colors[2]; // Ha 4 színű, váltakozó a színséma
        echo "<circle cx='$centerX' cy='$centerY' r='$r' style='fill:none; stroke:$sliceColor; stroke-width:$d; stroke-dasharray:$remaining,$k' transform='rotate(-90 $centerX $centerY)' />";
        $remaining -= $percents[$fromBack]*$k;
        // El kell forgatni 90 fokkal, hogy 0-tól kezdjen
    }

    // Körvonal
    echo "<circle cx='$centerX' cy='$centerY' r='$d' style='fill:none; stroke:black; stroke-width:1;' />";
    $angle = 0;
    if (count($values) > 1)
    for ($i = 0; $i < count($percents); $i++) {
        $x1 = $centerX;
        $y1 = $centerY;
        $x2 = $centerX + sin($angle)*$d;
        $y2 = $centerX - cos($angle)*$d;
        echo "<line x1='$x1' y1='$y1' x2='$x2' y2='$y2' style='stroke:black;'/>";
        $angle += 2*pi()*$percents[$i];
    }

    // Szöveg
    $textDistance = 10;
    $textHeight   = 9;

    $angle = 0;
    for ($i = 0; $i < count($percents); $i++) {
        $angle += pi()*$percents[$i]; // Elugrik a körcikk feléig
        $angle_deg = $angle * (180/pi());

        $lowerHalf = $angle_deg > 90 && $angle_deg < 270;

        $angle_deg += $lowerHalf ? 180 : 0;
        $x = $centerX + sin($angle)*($d+$textDistance+($lowerHalf ? $textHeight : 0));
        $y = $centerX - cos($angle)*($d+$textDistance+($lowerHalf ? $textHeight : 0));
        echo "<text x='$x' y='$y' fill='black' transform='rotate($angle_deg $x, $y)' text-anchor='middle'>$text[$i] ($values[$i])</text>";
        $angle += pi()*$percents[$i]; // Elugrik a körcikk végéig
    }
    echo '</svg>';
}

/*$values = array(1,9,5,7,6);
$text   = array('eins', 'zwei', 'drei', 'vier', 'fünf');
pie($values, $text);*/
?>
