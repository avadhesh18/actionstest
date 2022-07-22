<?php 

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,  'http://xhhxx.nip.io/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.1837.131 Safari/537.36');
curl_setopt($ch, CURLOPT_REFERER, "https://www.google.com/");
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 7000);
curl_setopt($ch, CURLOPT_ENCODING , "gzip");
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, false);
$shopclues = curl_exec($ch);
$info = curl_getinfo($ch);
curl_close($ch);

$data = array();
$data['time'] = date("F j, Y, g:i a");
$data['status'] =  $info['http_code'];
$data['resp_time'] = $info['total_time'];
$data['ssl'] = $info['ssl_verify_result'];

file_put_contents('docs/results.json',json_encode($data).PHP_EOL,FILE_APPEND | LOCK_EX);
