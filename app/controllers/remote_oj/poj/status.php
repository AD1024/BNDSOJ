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

$submit_url = "http://poj.org/showsource?solution_id=".$oj_code['id'];

$opts = array (
    'http' => array (
        'method' => 'GET',
        'header' => 'Cookie: '.$cookie
    )
);

$context = stream_context_create($opts);
$content = file_get_contents($submit_url, false, $context);

if(!preg_match("/Error Occurred/i", $content, $match)){
    $oj_status['isfound'] = true;
    preg_match("/<td><b>Memory:<\/b>(.*)<\/td><td width=10px>/i", $content, $match);
    $oj_status['memory'] = trim($match[1]);
    preg_match("/<td><b>Time:<\/b>(.*)<\/td>/i", $content, $match);
    $oj_status['time'] = trim($match[1]);
    preg_match("/<b>Result:.*<font.*>(.*)<\/font>/i", $content, $match);
    $oj_status['result'] = trim($match[1]);

    if(strpos($oj_status['result'], "Error") === null and strpos($oj_status['result'], "Exceeded") === null and strpos($oj_status['result'], "Answer") === null and strpos($oj_status['result'], "Accepted") === null){
        $oj_status['result'] = "w";
    }


}else{
    $oj_status['isfound'] = false;
}

?>
