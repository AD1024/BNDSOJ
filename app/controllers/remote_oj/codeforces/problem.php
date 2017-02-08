<?php

if($isinclude !=="YES"){
    exit();
}

$url='http://codeforces.com/problemset/problem/'.substr($oj_problem_id, 0, 3).'/'.substr($oj_problem_id, 3);
$content = file_get_contents($url);

if (stripos($content,"<title>Codeforces</title>")===false) {

    $oj_problem["isfound"] =  true;

    if (preg_match("/<div class=\"title\">(.*)<\\/div>/sU", $content,$matches)) $oj_problem["title"]=trim($matches[1]);
    if (preg_match("/time limit per test<\\/div>(.*) second/sU", $content,$matches)) $oj_problem["time_limit"]=intval(trim($matches[1]))*1000;
    if (preg_match("/memory limit per test<\\/div>(.*) megabyte/sU", $content,$matches)) $oj_problem["memory_limit"]=intval(trim($matches[1]))*1024;
    if (preg_match("/output<\\/div>.*<div><p>(.*)<\\/div>/sU", $content,$matches)) $oj_problem["description"]=trim($matches[1]);
    if (preg_match("/Input<\\/div>(.*)<\\/div>/sU", $content,$matches)) $oj_problem["input"]=trim($matches[1]);
    if (preg_match("/Output<\\/div>(.*)<\\/div>/sU", $content,$matches)) $oj_problem["output"]=trim($matches[1]);
    if (preg_match("/<div class=\"input\"><div class=\"title\">Input<\/div><pre>(.*)<\/pre><\/div>/sU", $content,$matches)) $oj_problem["sample_in"]=trim($matches[1]);
    if (preg_match("/<div class=\"output\"><div class=\"title\">Output<\/div><pre>(.*)<\/pre><\/div>/sU", $content,$matches)) $oj_problem["sample_out"]=trim($matches[1]);
    if (preg_match("/Note<\\/div>(.*)<\\/div><\\/div>/sU", $content,$matches)) $oj_problem["hint"]=trim($matches[1]);

    $oj_problem["source"] = "Codeforces";
    $oj_problem["id"] = $oj_problem_id;

} else {

    $oj_problem["isfound"] =  false;
}

?>