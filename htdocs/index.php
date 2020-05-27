<?php
include('head.php');
?>

<h2>Hello!</h2>
<?php changeTitle('Heim'); ?>
<p>Das ist die Index Page meines Apps.</p>


<div style="float:left;"><img src="/img/youtube.png" style="height:93px; padding-right:10px;"></div>
<h2 style="margin-top:0;">Videodokumentation</h2>
<p>Videodokumentation meines Applikations:<br>
<?php
    echo "<a href='https://youtu.be/tVhjnyPattU' target='blank'>Youtube link</a>";
?>
</p>


<div style="float:left;"><img src="/img/pdf.png" style="height:93px; padding-right:10px;"></div>
<h2 style="margin-top:0;">Dokumentation</h2>
<p>Hier kann die Dokumentation heruntergeladet werden:<br>
<?php
    $filename = 'dokumentation.pdf';
    echo "<a href='/files/$filename'>$filename</a>";
    echo ' ('.number_format(filesize("files/$filename")/1024, 1).' kB)';
?>
</p>


<div style="float:left;"><img src="/img/pdf.png" style="height:93px; padding-right:10px;"></div>
<h2 style="margin-top:0;">Beschreibung</h2>
<p>Die Beschreibung der Große Hausaufgabe<br>
<?php
    $filename = 'ghf.pdf';
    echo "<a href='/files/$filename'>$filename</a>";
    echo ' ('.number_format(filesize("files/$filename")/1024, 1).' kB)';
?>
</p>

<?php
include('foot.php');
?>
