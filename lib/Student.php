<?php

class Student
{

    function __construct($schoolConnection)
    {
        $this->schoolConnection = $schoolConnection;
    }

    function setUsername($newUsername)
    {
        $this->username = $newUsername;
    }

    function setPassword($newPassword)
    {
        $this->password = $newPassword;
    }

    function getTranscript()
    {
        if($this->schoolConnection->connect($this->username, $this->password)) {
            $transcripts = $this->schoolConnection->getTranscripts();
            return $transcripts;
        }

        return null;
    }

    function getUsername()
    {
        return $this->username;
    }

    /**
    *   The username of the user logged into OCC.
    *   @var string
    */
    private $username;

    /**
     *   The password of the user logged into OCC.
     *   Read-only.
     *   @var string
     */
    private $password;

    /**
     *   The school the user wants to connect to.
     *   @var SchoolConnection
     */
    private $schoolConnection;
};
