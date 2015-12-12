<?php

/**
*   Performs a cURL request to a given url and returns the response headers and content.
*
*   @param string url - URL to request
*   @param string cookie_file_path - path to the cookies text file
*   @param array[associative] options - options of the request
*
*   @return array[associative]
*/
function performRequest($url, $cookie_file_path, $options = NULL) {
    $c = curl_init($url);
    curl_setopt($c, CURLOPT_NOBODY, false);
    curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($c, CURLOPT_COOKIEJAR, $cookie_file_path);
    curl_setopt($c, CURLOPT_COOKIEFILE, $cookie_file_path);
    curl_setopt($c, CURLOPT_USERAGENT,
        "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2228.0 Safari/537.36"
    );
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
