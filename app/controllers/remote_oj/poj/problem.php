<?php

if($isinclude !=="YES"){
    exit();
}

$url='http://poj.org/problem?id='.$oj_problem_id;  
$content = file_get_contents($url);

if(preg_match('/<div class="ptt" lang="en-US">(.*)<\/div>/sU', $content, $matches)){
    $oj_problem['title'] = trim($matches[1]);
    $oj_problem["isfound"] =  true;

    if(preg_match('/<title>(.*) --/sU', $content, $matches)) $oj_problem["id"] = intval(trim($matches[1]));
    if(preg_match('/<td><b>Time Limit:<\/b> (.*)MS<\/td>/sU', $content, $matches)) $oj_problem["time_limit"] = intval(trim($matches[1]));
    if(preg_match('/<td><b>Memory Limit:<\/b> (.*)K<\/td>/sU', $content, $matches)) $oj_problem["memory_limit"] = intval(trim($matches[1]));
    if(preg_match('/<p class="pst">Description<\/p><div class="ptx" lang="en-US">(.*)<\/div>/sU', $content, $matches)) $oj_problem["description"] = trim($matches[1]);
    if(preg_match('/<p class="pst">Input<\/p><div class="ptx" lang="en-US">(.*)<\/div>/sU', $content, $matches)) $oj_problem["input"] = trim($matches[1]);
    if(preg_match('/<p class="pst">Output<\/p><div class="ptx" lang="en-US">(.*)<\/div>/sU', $content, $matches)) $oj_problem["output"] = trim($matches[1]);
    if(preg_match('/<p class="pst">Sample Input<\/p><pre class="sio">(.*)<\/pre>/sU', $content, $matches)) $oj_problem["sample_in"] = trim($matches[1]);
    if(preg_match('/<p class="pst">Sample Output<\/p><pre class="sio">(.*)<\/pre>/sU', $content, $matches)) $oj_problem["sample_out"] = trim($matches[1]);
    if(preg_match('/<p class="pst">Source<\/p><div class="ptx" lang="en-US">(.*)<\/div>/sU', $content, $matches)) $oj_problem["source"] = trim(strip_tags($matches[1]));
    if(strpos($content, '<td style="font-weight:bold; color:red;">Special Judge</td>') !== false) $oj_problem["special_judge_status"] = 1;
    else $oj_problem["special_judge_status"] = 0;

    $oj_problem["description"] = str_replace('src="', 'src="http://poj.org/', $oj_problem["description"]);
    $oj_problem["description"] = str_replace('src=', 'src=http://poj.org/', $oj_problem["description"]);

} else {
    $oj_problem["isfound"] =  false;
}

?>