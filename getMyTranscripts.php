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

// 0. Set the cookie file.
$milliseconds = round(microtime(true) * 1000);
$milliseconds += rand(1, 10000);
$milliseconds = number_format($milliseconds, 0, '', '');
$cookie_file_path = "cookies/$milliseconds.txt";
$cookie_file = fopen($cookie_file_path, "w");
// Close the cookie file.
fclose($cookie_file);

// 1.a. Log into MyCoast.

$loginInfo = "user=".$username."&pass=".$password."&uuid=".$milliseconds; // Set up POST info with Milliseconds.
$url = 'https://mycoast.cccd.edu/cp/home/login';
$options = [
  'post' => $loginInfo,
  'referer' => 'https://mycoast.cccd.edu/cp/home/displaylogin',
  'host' => 'mycoast.cccd.edu:443',
  'follow_location' => false
];

$data = performRequest($url, $cookie_file_path, $options);
unset($options['post']);

// Check if login was successful.
if ( strpos($data['response'], '<title>Error: Failed Login</title>') == true ) {
	echo 0;
	exit();
}

// 1.b. Log into MyCoast.
$url = 'https://mycoast.cccd.edu/cp/home/loginok.html';
$data = performRequest($url, $cookie_file_path, $options);
unset($options['referer']);

// 1.c. Log into MyCoast.
$url = 'https://mycoast.cccd.edu/cp/home/next';
$data = performRequest($url, $cookie_file_path, $options);

// 1.d. Finish Login into MyCoast.
$url = 'https://mycoast.cccd.edu/render.userLayoutRootNode.uP?uP_root=root';
$data = performRequest($url, $cookie_file_path, $options);

// 2. Access MyCoast Student Tab.
$url = 'https://mycoast.cccd.edu/tag.1644b8f9cbdfde0f.render.userLayoutRootNode.uP?uP_root=root&uP_sparam=activeTab&activeTab=u12l1s2&uP_tparam=frm&frm=';
$data = performRequest($url, $cookie_file_path, $options);

// 3.a. Access Unofficial Transcript Term Selector.
$url = 'https://mycoast.cccd.edu/cp/render.UserLayoutRootNode.uP?uP_tparam=utf&utf=https%3A%2F%2Fmycoast.cccd.edu%2Fcp%2Fip%2Flogin%3Fsys%3Dsctssb%26url%3Dhttps%3A%2F%2Fbannerlsp.cccd.edu%2Fpls%2FPROD%2Fbwskotrn.P_ViewTermTran';
$data = performRequest($url, $cookie_file_path, $options);

// 3.b. Access Unofficial Transcript Term Selector.
// Curl request.
$url = 'https://mycoast.cccd.edu/cp/ip/login?sys=sctssb&url=https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran';
$options['header'] = true;
$data = performRequest($url, $cookie_file_path, $options); // we could follow_location,
                                                           // but we would lose control...
unset($options['header']);

// Get the Location response header.
$curl_info = $data["info"];
$headers = substr($data["response"], 0, $curl_info["header_size"]); //split out header
preg_match("!\r\n(?:Location|URI): *(.*?) *\r\n!", $headers, $matches);
$url = $matches[1]; // Location to follow next...

// 3.c. Access Unofficial Transcript Term Selector.
$options['headers'] = false;
$options['follow_location'] = true;
$options['referer'] = 'https://mycoast.cccd.edu/cp/render.uP?uP_root=root&uP_sparam=activeTab&activeTab=u12l1s2&uP_tparam=frm&frm=backLinked&uP_tparam=utf&utf=&https://mycoast.cccd.edu/cp/ip/login?sys=sctssb&url=https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran';
$data = performRequest($url, $cookie_file_path, $options);

// 3.d. Access Unofficial Transcript Term Selector.
$url = 'https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran';
$options['referer'] = 'https://mycoast.cccd.edu/cp/render.uP?uP_root=root&uP_sparam=activeTab&activeTab=u11l1s2&uP_tparam=frm&frm=backLinked&uP_tparam=utf&utf=&https://mycoast.cccd.edu/cp/ip/login?sys=sctssb&url=https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran';
$data = performRequest($url, $cookie_file_path, $options);

// 4. Access the Unofficial Transcripts.

// Set up the POST Info.
$transcriptPostInfo = "levl=&tprt=WEB"; // levl=OC only for OCC

// Perform the curl request.
$url = 'https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTran';
$options['referer'] = 'https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran';
$options['post'] = $transcriptPostInfo;
$options['follow_location'] = false;
$data = performRequest($url, $cookie_file_path, $options);
$transcriptsHTML = $data['response'];

// 5. Post-process the Transcripts.

// 5.a. Fix the banner remote includes.
$banner = 'https://bannerlsp.cccd.edu'; // Note, banner has tags in uppercase (HREF, SRC)

$HREF_Banner_Change = array(
	"/wtlgifs/",// banner
	"/css/"		// banner
);

$transcriptsHTML = str_replace('HREF="' . $HREF_Banner_Change[0], 'href="' . $banner . $HREF_Banner_Change[0], $transcriptsHTML);
$transcriptsHTML = str_replace('HREF="' . $HREF_Banner_Change[1], 'href="' . $banner . $HREF_Banner_Change[1], $transcriptsHTML);

// 5.b. Get rid of some tags.
$dom = new DOMDocument();
if(@$dom->loadHTML($transcriptsHTML))
{
  while (($r = $dom->getElementsByTagName("script")) && $r->length) {
    $r->item(0)->parentNode->removeChild($r->item(0));
  }

  while (($r = $dom->getElementsByTagName("img")) && $r->length) {
    $r->item(0)->parentNode->removeChild($r->item(0));
  }

  // 5.c. Get rid of div element with a specific class.
  $xpath = new DOMXpath($dom);
  $nodes = $xpath->query( "//div[contains(concat(' ', @class, ' '), ' headerwrapperdiv ')]");
  foreach( $nodes as $node) {
    $node->parentNode->removeChild( $node);
  }

	$transcriptsHTML = $dom->saveHTML();
}

$start = strpos($transcriptsHTML, '<a href="#insti_credit">Institution Credit</a>');
$end = strpos($transcriptsHTML, '<table class="datadisplaytable"');
for ($i = $start; $i < $end; $i++) {
  $transcriptsHTML[$i] = ' ';
}

$start = strpos($transcriptsHTML, '<div class="pagefooterdiv">');
$end = strpos($transcriptsHTML, '<div class="banner_copyright">');
for ($i = $start; $i < $end; $i++) {
  $transcriptsHTML[$i] = ' ';
}

$start = strpos($transcriptsHTML, '<a href="#top" alt="TOP">-Top-</a>');
$end = $start + 34;
for ($i = $start; $i < $end; $i++) {
  $transcriptsHTML[$i] = ' ';
}

$start = strpos($transcriptsHTML, '<a href="#top" alt="TOP">-Top-</a>');
$end = $start + 34;
for ($i = $start; $i < $end; $i++) {
  $transcriptsHTML[$i] = ' ';
}

$start = strpos($transcriptsHTML, '<a href="#top" alt="TOP">-Top-</a>');
$end = $start + 34;
for ($i = $start; $i < $end; $i++) {
  $transcriptsHTML[$i] = ' ';
}

// 5.d. Get rid of encoded characters.
$transcriptsHTML = str_replace('&amp;nbsp', '', $transcriptsHTML);

// 5.e. Add a style fix (CSS).
$styleFix = "<style>a { margin-left: 15px; } .captiontext { margin-top: 0 !important; } .ddseparator { padding: 0 }</style>";
$transcriptsHTML = $styleFix . $transcriptsHTML;

// 5.f. Add some credits.
$transcriptsHTML = str_replace("2015 Ellucian Company L.P. and its affiliates.",
                   "<center>Transcripts generated with <b>Easy OCC Transcripts</b>. A creation of Georgy Marrero and Linda Lam.</center>",
                   $transcriptsHTML);

// 6. Return back (print) the transcripts.
echo $transcriptsHTML;

/* USEFUL METHODS
*******************************************/

/**
* Performs a cURL request to a given url and returns the response headers and content.
*/
function performRequest($url, $cookie_file_path, $options = NULL) {
  $c = curl_init($url);
  curl_setopt($c, CURLOPT_NOBODY, false);
  curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
  curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_file_path);
  curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
  curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
  curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);

  if ($options != NULL) {
    foreach ($options as $option => $value) {
      if ($value != NULL) {
        switch ($option) {
          case 'host':
            curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: ' . $value));
          break;
          case 'referer':
            curl_setopt($c, CURLOPT_REFERER, $value);
          break;
          case 'post':
            curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $value);
          break;
          case 'follow_location':
            if ($value) {
              curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
            }
          break;
          case 'header':
            if ($value) {
              curl_setopt($c, CURLOPT_HEADER, true); //include headers in http data
            }
        }
      }
    }
  }

  $response = curl_exec($c);
  $curl_info = curl_getinfo($c);

  $data = array(
    'response' => $response,
    'info' => $curl_info
  );

  curl_close($c);

  return $data;
}

?>
