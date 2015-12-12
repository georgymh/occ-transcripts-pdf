<?php

include "../vendor/autoload.php";

require_once "SchoolPortal.php";
require_once "OCCTranscripts.php";
require_once "HTTPRequest.php";

class OCCPortal extends SchoolPortal
{

    function __construct()
    {
        $randomStr = random_str(8); // generate 8 characters random string
        $this->cookiesPath = "../cookies/" . $randomStr . ".txt";
        $cookiesFile = fopen($this->cookiesPath, "w+");
        fclose($cookiesFile);
    }

    /**
     *  Connects to the MyOCC Portal with a student username and password.
     *  @param string username of the student
     *  @param string password of the student
     */
    public function connect($username, $password)
    {
        $milliseconds = $this->getMilliseconds();

        $loginInfo = "user=".$username."&pass=".$password."&uuid=".$milliseconds; // Set up POST data.
        $url = 'https://mycoast.cccd.edu/cp/home/login';
        $options = [
            'post' => $loginInfo,
            'referer' => 'https://mycoast.cccd.edu/cp/home/displaylogin',
            'host' => 'mycoast.cccd.edu:443',
            'follow_location' => false
        ];

        // 1. Log into MyCoast.
        $data = performRequest($url, $this->cookiesPath, $options);
        unset($options['post']);

        // Check if login was successful.
        if (strpos($data['response'], '<title>Error: Failed Login</title>') == true) {
            return false;
        }

        // 2. Log into MyCoast.
        $url = 'https://mycoast.cccd.edu/cp/home/loginok.html';
        $data = performRequest($url, $this->cookiesPath, $options);
        unset($options['referer']);

        // 3. Log into MyCoast.
        $url = 'https://mycoast.cccd.edu/cp/home/next';
        $data = performRequest($url, $this->cookiesPath, $options);

        // 4. Finish Login into MyCoast.
        $url = 'https://mycoast.cccd.edu/render.userLayoutRootNode.uP?uP_root=root';
        $data = performRequest($url, $this->cookiesPath, $options);

        $this->connectionIsEstablished = true;
        return true;
    }

    /**
     *  Returns the clean transcripts of the user in HTML format.
     *  @return string
     */
    public function getTranscripts()
    {
        // Is user connected?
        if ($this->connectionIsEstablished) {
            // Access Student Tab
            $this->accessStudentTab();
            // Access Transcripts Page
            $this->transcripts = new OCCTranscripts($this->cookiesPath);
            $transcripts = $this->transcripts->get();
            // Return transcripts
            return $transcripts;
        }

        return null;
    }

    /**
     *  Path to the text file with the cookies of the current session.
     *  @var string
     */
    private $cookiesPath;

    /**
     *  If connection to the portal is established or not.
     *  @var boolean
     */
    private $connectionIsEstablished = false;

    /**
     *  Transcripts object.
     *  @var Transcripts
     */
    private $transcripts;

    /**
     *  Accesses the student tab inside the MyOCC Portal.
     */
    private function accessStudentTab()
    {
        // Accesses the second tab -- Student.
        $options = [
            'host' => 'mycoast.cccd.edu:443',
            'follow_location' => false
        ];

        $url = 'https://mycoast.cccd.edu/tag.1644b8f9cbdfde0f.render.userLayoutRootNode.uP?uP_root=root' +
        '&uP_sparam=activeTab&activeTab=u12l1s2&uP_tparam=frm&frm=';
        $data = performRequest($url, $this->cookiesPath, $options);
    }

    /**
     *  Generate unix epoch timestamp in milliseconds.
     *  Useful for MyOCC login.
     *  @return string
     */
    private function getMilliseconds()
    {
        $milliseconds = round(microtime(true) * 1000);
        $milliseconds += rand(1, 10000);
        $milliseconds = number_format($milliseconds, 0, '', '');
        return $milliseconds;
    }

};

/**
 * Generate a random string, using a cryptographically secure
 * pseudorandom number generator (random_int)
 *
 * For PHP 7, random_int is a PHP core function
 * For PHP 5.x, depends on https://github.com/paragonie/random_compat
 *
 * @param int $length      How many characters do we want?
 * @param string $keyspace A string of all possible characters
 *                         to select from
 * @return string
 */
function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
{
    $str = '';
    $max = mb_strlen($keyspace, '8bit') - 1;
    for ($i = 0; $i < $length; ++$i) {
        $str .= $keyspace[random_int(0, $max)];
    }
    return $str;
}
