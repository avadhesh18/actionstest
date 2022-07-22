<?php 
date_default_timezone_set("Asia/Kolkata"); 
function decryptfile($encryptedstring, $password, $encoding = null) {
    if ($encryptedstring != null && $password != null) {
        $encryptedstring = $encoding == "hex" ? hex2bin($encryptedstring) : ($encoding == "base64" ? base64_decode($encryptedstring) : $encryptedstring);
        $keysalt = substr($encryptedstring, 0, 16);
        $key = hash_pbkdf2("sha512", $password, $keysalt, 20000, 32, true);
        $ivlength = openssl_cipher_iv_length("aes-256-gcm");
        $iv = substr($encryptedstring, 16, $ivlength);
        $tag = substr($encryptedstring, -16);
        return openssl_decrypt(substr($encryptedstring, 16 + $ivlength, -16), "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $tag);
    }
}

function statuspage($url){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,  $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.2; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.1837.131 Safari/537.36');
curl_setopt($ch, CURLOPT_REFERER, "https://www.google.com/");
curl_setopt($ch, CURLOPT_TIMEOUT_MS, 7000);
curl_setopt($ch, CURLOPT_ENCODING , "gzip");
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_POST, false);
$exec = curl_exec($ch);
$ret = curl_getinfo($ch);
curl_close($ch);
return $ret;
}
 
$sitesfile = decryptfile(file_get_contents('list.txt'),$_SERVER['super_secret'],'base64');
$sites = explode(PHP_EOL, $sitesfile); //Split the file by each line

foreach ($sites as $site) { 
    $site = trim($site);
$sitex = substr(str_replace('https://','',$site),0,4);
$data = array();
$info = statuspage($site);
$data['time'] = date("F j, Y, g:i a");
$data['ts'] = time();
$data['status'] =  $info['http_code'];
$data['resp_time'] = $info['total_time'];
file_put_contents('docs/'.$sitex.'.json',json_encode($data).PHP_EOL,FILE_APPEND | LOCK_EX);
    if($sitex == 'clou'){
    list($first_line, $contents) = explode(PHP_EOL, file_get_contents('docs/'.$sitex.'.json'), 2);
file_put_contents('docs/'.$sitex.'.json', implode(PHP_EOL, $contents));

    }
}
