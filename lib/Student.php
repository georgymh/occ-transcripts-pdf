<?php

class Student
{

    function __construct($schoolConnection)
    /**
     *  Constructor.
     */
    {
        $this->schoolConnection = $schoolConnection;
    }

    /**
     *  Sets the username of the user.
     *  @param string
     */
    function setUsername($newUsername)
    {
        $this->username = $newUsername;
    }

    /**
     *  Sets the password of the user.
     *  @param string
     */
    function setPassword($newPassword)
    {
        $this->password = $newPassword;
    }

    /**
     *  Returns the transcript of the user.
     *  @return string
     */
    function getTranscript()
    {
        if($this->schoolConnection->connect($this->username, $this->password)) {
            $transcripts = $this->schoolConnection->getTranscripts();
            return $transcripts;
        }

        return null;
    }

    /**
     *  Returns the username of the current user.
     *  @return string
     */
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
     *   Write-only.
     *   @var string
     */
    private $password;

    /**
     *   The school the user wants to connect to.
     *   @var SchoolConnection
     */
    private $schoolConnection;
};
