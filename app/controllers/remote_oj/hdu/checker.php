<?php

if($isinclude !=="YES"){
    exit();
}

$login_url = "http://poj.org/login";

$post = "user_id1=".$oj_config["poj"]["username"]."&password1=".$oj_config["poj"]["password"]."&B1=login&url=.";

$cookie = 'cookie_poj.txt';

$curl = curl_init(); 
curl_setopt($curl, CURLOPT_URL, $login_url);
curl_setopt($curl, CURLOPT_HEADER, 0); 
curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0); 
curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
$html = curl_exec($curl); 
curl_close($curl);

//http://poj.org/submit
//problem_id=1000&language=0&source=I2luY2x1ZGUgPGlvc3RyZWFtPgp1c2luZyBuYW1lc3BhY2Ugc3RkOwoKaW50IG1haW4oKXsKaW50IGEsYjsKY2luID4%2BYT4%2BYjsKY291dCA8PCBhK2I7CgpyZXR1cm4gMDsKfQ%3D%3D&submit=Submit&encoded=1
?>