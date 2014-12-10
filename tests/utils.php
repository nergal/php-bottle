<?php

/**
 * Sends a request using cURL, and returns headers list, HTTP code, and
 * response content
 *
 * @param string $url
 * @param string $method optionnal the HTTP method name to use. Defaults to GET
 * @param array $params optionnal the HTTP params to send (must be an
 * associative array)
 * @return array an associative array containing the HTTP code ('httpcode' key),
 * the headers list ('headers' key) as another associative array, and the
 * response content ('content' key)
 */
function send_request($url, $method='GET', array $params=[]) {
    if(!function_exists('curl_init')) {
        $this->assertTrue(false, 'cURL is not available. Passing.');
    }
    if(!in_array($method, ['GET', 'POST'])) {
        throw new InvalidArgumentException('Method '.$method.' is not supported (yet?)');
    }
    $ch = curl_init();

    switch($method) {
        case 'GET':
            if(count($params)) {
                $params_formatted = [];
                foreach($params as $k => $param) {
                    $params_formatted[] = urlencode($k).'='.urlencode($param);
                }
                $url .= '?'.implode('&', $params_formatted);
            }
            break;
        case 'POST':
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
            break;
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    list($headers_content, $response_content) = explode("\r\n\r\n", $response);
    $headers_raw = explode("\r\n", $headers_content);
    $headers = [];
    foreach($headers_raw as $header) {
        if(strpos($header, ':') !== false) {
            list($k, $v) = explode(':', $header);
            $headers[$k] = trim($v);
        }
    }

    curl_close($ch);
    return ['httpcode' => $httpcode, 'headers' => $headers, 'content' => $response_content];
}
