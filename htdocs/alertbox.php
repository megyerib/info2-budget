<?php
function alertbox() {
	global $_SESSION;

	if (isset($_SESSION['alertbox']) && !empty($_SESSION['alertbox'])) {
		/*if (isset($_SESSION['waitforit'])) {
			unset($_SESSION['waitforit']);
			return;
		}*/

		$msg   = $_SESSION['alertbox'];
		$class = $_SESSION['alertbox_class'];

		echo
		"<a onclick=\"document.getElementById('alertbox').style.display = 'none';\"><div id='alertbox' class='alert $class'>$msg</div></a>

		<script>
			setTimeout(function hide() {document.getElementById('alertbox').style.display='none'}, 4000);
			hide();
		</script>";

		unset($_SESSION['alertbox']);
		unset($_SESSION['alertbox_class']);
	}
}
function alert($msg, /*$thispage = true,*/ $class = '') {
	$_SESSION['alertbox'] = $msg;
	$_SESSION['alertbox_class'] = $class;
	/*if (!$thispage) {
		$_SESSION['waitforit'] = true;
	}*/
}
function twoway_alert($cond, $msg) {
	if ($cond)
		alert("$msg erfolgreich! :)");
	else
		alert("Fehler bei $msg! :(", 'error');
}
?>
