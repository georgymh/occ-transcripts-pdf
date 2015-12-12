<?php

require "Student.php";
require "OCCPortal.php";

$myOCC = new OCCPortal();
$student = new Student($myOCC);
$student->setUsername('username');
$student->setPassword('password');
$transcripts = $student->getTranscript();

echo $transcripts;
