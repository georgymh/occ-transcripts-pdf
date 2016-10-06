<?php

require_once "HTTPRequest.php";

class OCCTranscripts {

    /**
     *  Constructor.
     *  @param string cookiesPath - path to cookie file with logged in user
     */
    function __construct($cookiesPath)
    {
        $this->cookiesPath = $cookiesPath;
        $this->transcriptsHTML = "";
    }

    /**
     *  Retrieves the transcripts of an OCC user.
     *  @return string
     */
    public function get()
    {
        // 1. Go to the transcript retrieval process
        $this->accessTranscriptsPage();

        // 2. Access the Unofficial Transcripts.
        // Set up options for the request.
        $transcriptPostInfo = "levl=&tprt=WEB"; // levl=OC only for OCC
        $options = [
            'post' => $transcriptPostInfo,
            'host' => 'mycoast.cccd.edu:443',
            'follow_location' => false,
            'referer' => 'https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran',
        ];
        $url = 'https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTran';

        // Perform the request.
        $data = performRequest($url, $this->cookiesPath, $options);
        $this->transcriptsHTML = $data['response'];

        // 3. Clean up transcripts
        $this->cleanUp();

        // 4. Return the transcripts.
        return $this->transcriptsHTML;
    }

    /**
     *  Path of the text file with the cookies of the current session.
     *  @var string
     */
    private $cookiesPath;

    /**
     *  HTML code of a transcripts.
     *  @var string
     */
    private $transcriptsHTML;

    /**
     *  Accesses the transcripts page inside MyOCC's portal.
     */
    private function accessTranscriptsPage()
    {
        $options = [
            'host' => 'mycoast.cccd.edu:443',
            'follow_location' => false
        ];

        // 1. Access Unofficial Transcript Term Selector.
        $url = 'https://mycoast.cccd.edu/cp/render.UserLayoutRootNode.uP?' +
        'uP_tparam=utf&utf=https%3A%2F%2Fmycoast.cccd.edu%2Fcp%2Fip%2Flogin' +
        '%3Fsys%3Dsctssb%26url%3Dhttps%3A%2F%2Fbannerlsp.cccd.edu%2Fpls%2FP' +
        'ROD%2Fbwskotrn.P_ViewTermTran';

        $data = performRequest($url, $this->cookiesPath, $options);

        // 2. Access Unofficial Transcript Term Selector.
        // Curl request.
        $url = 'https://mycoast.cccd.edu/cp/ip/login?sys=sctssb&url=https://' +
        'bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran';

        $options['header'] = true;
        $data = performRequest($url, $this->cookiesPath, $options); // we could follow_location,
                                                                    // but we would lose control...
        unset($options['header']);

        // Get the Location response header.
        $curl_info = $data["info"];
        $headers = substr($data["response"], 0, $curl_info["header_size"]); //split out header
        preg_match("!\r\n(?:Location|URI): *(.*?) *\r\n!", $headers, $matches);
        $url = $matches[1]; // Location to follow next...

        // 3. Access Unofficial Transcript Term Selector.
        $options['headers'] = false;
        $options['follow_location'] = true;
        $options['referer'] = 'https://mycoast.cccd.edu/cp/render.uP?uP_root=root&uP_sparam=activeTab&' +
        'activeTab=u12l1s2&uP_tparam=frm&frm=backLinked&uP_tparam=utf&utf=&https://mycoast.cccd.edu/cp' +
        '/ip/login?sys=sctssb&url=https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran';
        $data = performRequest($url, $this->cookiesPath, $options);

        // 4. Access Unofficial Transcript Term Selector.
        $url = 'https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran';
        $options['referer'] = 'https://mycoast.cccd.edu/cp/render.uP?uP_root=root&uP_sparam=activeTab&' +
        'activeTab=u11l1s2&uP_tparam=frm&frm=backLinked&uP_tparam=utf&utf=&https://mycoast.cccd.edu/cp' +
        '/ip/login?sys=sctssb&url=https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran';
        $data = performRequest($url, $this->cookiesPath, $options);
    }

    /**
     *  Cleans up the transcripts HTML.
     *  This function will do some manual parsing on the HTML transcript file, to make things look right
     *  for the user.
     *  NOTE: this function is currently very unefficient -- it has to be fixed soon.
     */
    private function cleanUp()
    {
        // 1. Fix the banner remote includes.
        $banner = 'https://bannerlsp.cccd.edu'; // Note, banner has tags in uppercase (HREF, SRC)

        $HREF_Banner_Change = array(
            "/wtlgifs/",// banner
            "/css/"     // banner
        );

        $this->transcriptsHTML = str_replace('HREF="' . $HREF_Banner_Change[0],
            'href="' . $banner . $HREF_Banner_Change[0],
            $this->transcriptsHTML
        );

        $this->transcriptsHTML = str_replace('HREF="' . $HREF_Banner_Change[1],
            'href="' . $banner . $HREF_Banner_Change[1],
            $this->transcriptsHTML
        );

        // 2. Get rid of some tags.
        $dom = new DOMDocument();
        if(@$dom->loadHTML($this->transcriptsHTML))
        {
            while (($r = $dom->getElementsByTagName("script")) && $r->length) {
                $r->item(0)->parentNode->removeChild($r->item(0));
            }

            while (($r = $dom->getElementsByTagName("img")) && $r->length) {
                $r->item(0)->parentNode->removeChild($r->item(0));
            }

            // 3. Get rid of div element with a specific class.
            $xpath = new DOMXpath($dom);
            $nodes = $xpath->query( "//div[contains(concat(' ', @class, ' '), ' headerwrapperdiv ')]");
            foreach( $nodes as $node) {
                $node->parentNode->removeChild($node);
            }

            $this->transcriptsHTML = $dom->saveHTML();
        }

        $start = strpos($this->transcriptsHTML, '<a href="#insti_credit">Institution Credit</a>');
        $end = strpos($this->transcriptsHTML, '<table class="datadisplaytable"');
        for ($i = $start; $i < $end; $i++) {
            $this->transcriptsHTML[$i] = ' ';
        }

        $start = strpos($this->transcriptsHTML, '<div class="pagefooterdiv">');
        $end = strpos($this->transcriptsHTML, '<div class="banner_copyright">');
        for ($i = $start; $i < $end; $i++) {
            $this->transcriptsHTML[$i] = ' ';
        }
        
        for ($j = 0; $j < 3; $j++) {
            $start = strpos($this->transcriptsHTML, '<a href="#top" alt="TOP">-Top-</a>');
            $end = $start + 34;
            for ($i = $start; $i < $end; $i++) {
                $this->transcriptsHTML[$i] = ' ';
            }
        }

        // 4. Get rid of encoded characters.
        $this->transcriptsHTML = str_replace('&amp;nbsp', '', $this->transcriptsHTML);

        // 5. Add a styling fix (CSS).
        $styleFix = "<style>a { margin-left: 15px; } .captiontext { margin-top: 0 !important; }" +
        ".ddseparator { padding: 0 }</style>";
        $this->transcriptsHTML = $styleFix . $this->transcriptsHTML;

        // 6. Add some credits.
        $this->transcriptsHTML = str_replace("© 2015 Ellucian Company L.P. and its affiliates.",
            "<center>Transcripts generated with <b>Easy OCC Transcripts</b>. A creation of Georgy Marrero and Linda Lam.</center>",
            $this->transcriptsHTML
        );
    }

};
