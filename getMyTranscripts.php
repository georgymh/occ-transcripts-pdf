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
$milliseconds = round(microtime(true) * 1000) + 5000 + rand(1000, 5000); // + 5 seconds + 1-5 seconds
$cookie_file_path = "cookies/$milliseconds.txt";
$cookie_file = fopen($cookie_file_path, "w");

// 1.a. Log into MyCoast.

// Set up POST info with Milliseconds.
$postinfo = "user=".$username."&pass=".$password."&uuid=".$milliseconds;

// Perform the curl request.
$url="https://mycoast.cccd.edu/cp/home/login";
$c = curl_init();
curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: mycoast.cccd.edu:443'));
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_URL, $url);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_file_path);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_REFERER, 'https://mycoast.cccd.edu/cp/home/displaylogin');
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($c, CURLOPT_POST, 1);
curl_setopt($c, CURLOPT_POSTFIELDS, $postinfo);
$loginResponse = curl_exec($c);
curl_close($c);

if ( strpos($loginResponse, '<title>Error: Failed Login</title>') == true) {
	echo 0;
	exit();
}

// 1.b. Log into MyCoast.
$c = curl_init('https://mycoast.cccd.edu/cp/home/loginok.html');
curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: mycoast.cccd.edu:443'));
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($c, CURLOPT_VERBOSE, true);
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_REFERER, 'https://mycoast.cccd.edu/cp/home/displaylogin');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 0);
curl_exec($c);
curl_close($c);

// 1.c. Log into MyCoast.
$c = curl_init('https://mycoast.cccd.edu/cp/home/next');
curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: mycoast.cccd.edu:443'));
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($c, CURLOPT_VERBOSE, true);
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 0);
curl_exec($c);
curl_close($c);

// 1.d. Finish Login into MyCoast.
$c = curl_init('https://mycoast.cccd.edu/render.userLayoutRootNode.uP?uP_root=root');
curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: mycoast.cccd.edu:443'));
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($c, CURLOPT_VERBOSE, true);
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 0);
curl_exec($c);
curl_close($c);

// 2. Access MyCoast Student Tab.
$c = curl_init('https://mycoast.cccd.edu/tag.1644b8f9cbdfde0f.render.userLayoutRootNode.uP?uP_root=root&uP_sparam=activeTab&activeTab=u12l1s2&uP_tparam=frm&frm=');
curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: mycoast.cccd.edu:443'));
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($c, CURLOPT_VERBOSE, true);
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 0);
curl_exec($c);
curl_close($c);

// 3.a. Access Unofficial Transcript Term Selector.
$c = curl_init('https://mycoast.cccd.edu/cp/render.UserLayoutRootNode.uP?uP_tparam=utf&utf=https%3A%2F%2Fmycoast.cccd.edu%2Fcp%2Fip%2Flogin%3Fsys%3Dsctssb%26url%3Dhttps%3A%2F%2Fbannerlsp.cccd.edu%2Fpls%2FPROD%2Fbwskotrn.P_ViewTermTran');
curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: mycoast.cccd.edu:443'));
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_file_path);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($c, CURLOPT_VERBOSE, true);
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 0);
curl_exec($c);
curl_close($c);

// 3.b. Access Unofficial Transcript Term Selector.

// Curl request.
$c = curl_init('https://mycoast.cccd.edu/cp/ip/login?sys=sctssb&url=https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran');
curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: mycoast.cccd.edu:443'));
curl_setopt($c, CURLOPT_HEADER, true); //include headers in http data
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_file_path);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($c, CURLOPT_VERBOSE, true);
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 0); // we could follow the location, but we would lose control...
$http_data = curl_exec($c);

// Get the Location response header.
$curl_info = curl_getinfo($c);
$headers = substr($http_data, 0, $curl_info["header_size"]); //split out header
preg_match("!\r\n(?:Location|URI): *(.*?) *\r\n!", $headers, $matches);
$url = $matches[1]; // Location to follow next...
curl_close($c);

// 3.c. Access Unofficial Transcript Term Selector.
$c = curl_init($url);
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_file_path);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($c, CURLOPT_VERBOSE, true);
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_REFERER, 'https://mycoast.cccd.edu/cp/render.uP?uP_root=root&uP_sparam=activeTab&activeTab=u12l1s2&uP_tparam=frm&frm=backLinked&uP_tparam=utf&utf=&https://mycoast.cccd.edu/cp/ip/login?sys=sctssb&url=https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
curl_exec($c);
curl_close($c);

// 3.d. Access Unofficial Transcript Term Selector.
$c = curl_init('https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran');
curl_setopt($c, CURLOPT_HTTPHEADER, array('Host: bannerlsp.cccd.edu:443'));
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_file_path);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($c, CURLOPT_VERBOSE, true);
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_REFERER, 'https://mycoast.cccd.edu/cp/render.uP?uP_root=root&uP_sparam=activeTab&activeTab=u11l1s2&uP_tparam=frm&frm=backLinked&uP_tparam=utf&utf=&https://mycoast.cccd.edu/cp/ip/login?sys=sctssb&url=https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
curl_exec($c);
curl_close($c);

// 4. Access the Unofficial Transcripts.

// Set up the POST Info.
$transcriptPostinfo = "levl=&tprt=WEB"; // levl=OC only for OCC

// Perform the curl request.
$c = curl_init('https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTran');
curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_file_path);
curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
curl_setopt($c, CURLOPT_VERBOSE, true);
curl_setopt($c, CURLOPT_NOBODY, false);
curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36");
curl_setopt($c, CURLOPT_REFERER, 'https://bannerlsp.cccd.edu/pls/PROD/bwskotrn.P_ViewTermTran');
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($c, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($c, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($c, CURLOPT_POST, 1);
curl_setopt($c, CURLOPT_POSTFIELDS, $transcriptPostinfo);
$transcriptsHTML = curl_exec($c);
curl_close($c);

// Close the cookie file.
fclose($cookie_file);

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

// 5.d. Get rid of encoded characters.
$transcriptsHTML = str_replace('&amp;nbsp', '', $transcriptsHTML);

// 5.e. Add a style fix (CSS). 
$styleFix = "<style>a { margin-left: 15px; }</style>";
$transcriptsHTML = $styleFix . $transcriptsHTML;

// 6. Return back (print) the transcripts.
echo $transcriptsHTML;

?>