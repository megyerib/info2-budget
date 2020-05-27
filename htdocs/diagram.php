<?php
function barGraph($values, $text=array()) {
// Üres
if (count($values) == 0 || array_sum($values) == 0) return; // Ha valaha is előjön a sum==0, akkor bocsi.

$minVal = min($values);
$maxVal = max($values);
if ($minVal * $maxVal > 0) { // Nullától kezdjen
    if ($minVal + $maxVal > 0)
        $minVal = 0;
    else
        $maxVal = 0;
}
$maxDiff = $maxVal - $minVal;
$gridlineVal = pow(10, floor(log10($maxDiff)));

$gridlineUp   = floor($maxVal/$gridlineVal);
$gridlineDown = floor(-$minVal/$gridlineVal);
// Kétszerező (ha kevés a vonal)
if ($gridlineUp + $gridlineDown + 1 < 5) {
    $gridlineUp   = floor(2*$maxVal/$gridlineVal);
    $gridlineDown = floor(-2*$minVal/$gridlineVal);
    $gridlineVal  /= 2;
}

// Valódi méretek
$pxWidthX = 800-22-40;
$pxWidthY = $pxWidthX*0.5;

// Szegély méret
$verGap = 20;
$horGap = 20;

// Oszlop, oszloptáv.
$GapPerCol = 0.8; // Táv/oszlopszél. arány
$colWidth  = $pxWidthX/(count($values)*(1+$GapPerCol)+$GapPerCol);
$colGapWidth = $colWidth*$GapPerCol;

// Vízsz. rácsvonaltáv.
$glDist = $pxWidthY/($gridlineUp+$gridlineDown+2); // Vízsz. vonaltáv.

$diagHeight = $glDist*($gridlineUp+$gridlineDown+2);
$diagWidth  = count($values)*($colWidth+$colGapWidth)+$colGapWidth;
$fullHeight = $diagHeight + 2*$verGap;
$fullWidth  = $diagWidth  + 2*$horGap;

$colColor = '#24AF61'; // Szép zöld

// SVG start
echo "<svg width='100%' viewBox='0 0 $fullWidth $fullHeight'>";
// http://tutorials.jenkov.com/svg/svg-viewport-view-box.html

// Y tengely
echo "<line x1='$horGap' y1='$verGap' x2='$horGap' y2='".($diagHeight+$verGap)."' style='stroke:black;'/>";
// Vízsz. elválasztók
$xAxisY = $verGap + ($gridlineUp+1)*$glDist; // X teng. y koord.
for ($i = $gridlineUp; $i >= -1*$gridlineDown; $i--) {
    if ($i == 0) {continue;}
    $axisY = $xAxisY - $i*$glDist;
    echo "<line x1='$horGap' y1='$axisY' x2=".($horGap+$diagWidth)." y2='$axisY' style='stroke:#aaa; stroke-dasharray:3,3;'/>";
    // Felirat
    echo "<text x='".($horGap+3)."' y='".($axisY-3)."' fill='#aaa'>".($i*$gridlineVal)."</text>";
}

// X tengely
echo "<line x1='$horGap' y1='$xAxisY' x2='".($horGap+$diagWidth)."' y2='$xAxisY' style='stroke:black;'/>";

// Oszlopok
for ($i = 0; $i < count($values); $i++) {
    $x1 = $horGap+$colGapWidth+$i*($colWidth+$colGapWidth);
    $y1 = $xAxisY;
    $x2 = $x1+$colWidth;
    $y2 = $y1-($glDist*$values[$i]/$gridlineVal);

    $points = "$x1,$y1 $x2,$y1 $x2,$y2 $x1,$y2";
    echo "<polygon points='$points' style='fill:$colColor;' class='diag_col'/>";

    // Értékek az oszlop fölött
    $textX = ($x1+$x2)/2;
    $textY = $values[$i]>=0?$y2-5:$y2+16;
    echo "<text x='$textX' y='$textY' text-anchor='middle' fill='#444'>$values[$i]</text>";

    // Szöveg
    if (isset($text[$i])) {
        $textY = $values[$i]<0?$y1-5:$y1+16;
        echo "<text x='$textX' y='$textY' text-anchor='middle' fill='#444'>$text[$i]</text>";
    }
}
echo "</svg>"; // SVG end
}
?>
