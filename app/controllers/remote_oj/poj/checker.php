<?php

if($isinclude !=="YES"){
    exit();
}

$login_url = "http://poj.org/login";

$post = "user_id1=".$oj_config["poj"]["username"]."&password1=".$oj_config["poj"]["password"]."&B1=login&url=.";

$opts = array (
    'http' => array (
        'method' => 'POST',
        'content' => $post
    )
);

$context = stream_context_create($opts);
$content = file_get_contents($login_url, false, $context);

$rinfo = $http_response_header;

foreach ($rinfo as $ginfo) {
    if(strpos($ginfo, 'Set-Cookie') !== false){
        $cookie = trim(substr($ginfo, 12));
    }
}

preg_match("/JSESSIONID(.*);/i", $cookie, $match);

$cookie = $match[0];

$oj_code['code'] = base64_encode($oj_code['code']);

if($oj_code['language'] === 'C++'){
    $oj_code['language'] = '0';
}else if($oj_code['language'] === 'C'){
    $oj_code['language'] = '1';
}else if($oj_code['language'] === 'Java'){
    $oj_code['language'] = '2';
}else if($oj_code['language'] === 'Pascal'){
    $oj_code['language'] = '3';
}

$submit_url = "http://poj.org/submit";
$post = "problem_id=".$oj_code['problem_id']."&language=".$oj_code['language']."&source=".urlencode($oj_code["code"])."&submit=Submit&encoded=1";

$opts = array (
    'http' => array (
        'method' => 'POST',
        'header' => 'Cookie: '.$cookie,
        'content' => $post
    )
);

$context = stream_context_create($opts);
$content = file_get_contents($submit_url, false, $context);

if(preg_match("/<tr align=center><td>(.*)<\/td><td><a.*>".$oj_config["poj"]["username"]."<\/a><\/td>/i", $content, $match)){
    $oj_check['success'] = true;
    $oj_check['id'] = trim($match[1]);
    preg_match("/<a.*".$oj_check['id'].".*>.*<\/a><\/td><td>(.*)<\/td><td>/i", $content, $match);
    $oj_check['size'] = trim($match[1]);
}else{
    $oj_check['success'] = false;
}

?>
