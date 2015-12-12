<?php

// First of all, check if the request is correct, and set username and password variables.
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

// Now, go ahead and generate the transcripts.
include "lib/OCCPortal.php";
include "lib/Student.php";

$myOCC = new OCCPortal();
$student = new Student($myOCC);
$student->setUsername($username);
$student->setPassword($password);
$transcripts = $student->getTranscript();

echo $transcripts;
