<?php

abstract class SchoolPortal
{
    /**
     *  Connects to the school portal.
     *  @param string username
     *  @param string password
     */
    abstract function connect($username, $password);

    /**
     *  Returns the transcript for a logged in student.
     *  @return string
     */
    abstract function getTranscripts();
};
