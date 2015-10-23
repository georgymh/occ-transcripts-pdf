<?php

if ( isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['action']) ) {
	$username = $_POST['user'];
	$password = $_POST['pass'];
	$action = $_POST['action'];

	if ($action != 'transcripts') {
		echo 'Error2.';
		exit();
	}

} else {
	echo 'Error.';
	exit();
}
?>