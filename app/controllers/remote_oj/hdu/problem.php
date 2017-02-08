<?php

if($isinclude !=="YES"){
    exit();
}

$url='http://acm.hdu.edu.cn/showproblem.php?pid='.$oj_problem_id;  
$content = file_get_contents($url);

if (stripos($content,"No such problem - <strong>Problem")===false) {
    $oj_problem["isfound"] =  true;

    if (preg_match("/<h1 style='color:#1A5CC8'>(.*)<\/h1>/sU", $content,$matches)) $oj_problem["title"]=trim($matches[1]);
    if (preg_match("/Time Limit:.*\/(.*) MS/sU", $content,$matches)) $oj_problem["time_limit"]=intval(trim($matches[1]));
    if (preg_match("/Memory Limit:.*\/(.*) K/sU", $content,$matches)) $oj_problem["memory_limit"]=intval(trim($matches[1]));
    if (preg_match("/Problem Description.*<div class=panel_content>(.*)<\/div><div class=panel_bottom>/sU", $content,$matches)) $oj_problem["description"]=trim($matches[1]);
    if (preg_match("/<div class=panel_title align=left>Input.*<div class=panel_content>(.*)<\/div><div class=panel_bottom>/sU", $content,$matches)) $oj_problem["input"]=trim($matches[1]);
    if (preg_match("/<div class=panel_title align=left>Output.*<div class=panel_content>(.*)<\/div><div class=panel_bottom>/sU", $content,$matches)) $oj_problem["output"]=trim($matches[1]);
    if (preg_match("/<pre><div.*>(.*)<\/div><\/pre>/sU", $content,$matches)) $oj_problem["sample_in"]=trim($matches[1]);
    if (preg_match("/<div.*>Sample Output<\/div><div.*><pre><div.*>(.*)<\/div><\/pre><\/div>/sU", $content,$matches)) $oj_problem["sample_out"]=trim($matches[1]);
    if (preg_match("/<i>Hint<\/i><\/div>(.*)<\/div><i style='font-size:1px'>/sU", $content,$matches)) $oj_problem["hint"]=trim($matches[1]);
    if (preg_match("/<div class=panel_title align=left>Source<\/div> (.*)<div class=panel_bottom>/sU", $content,$matches)) $oj_problem["source"]=trim(strip_tags($matches[1]));

    if(!$oj_problem["source"]){
        $oj_problem["source"] = "HDU Online Judge";
    }

    $oj_problem['id'] = $oj_problem_id;

    $oj_problem["description"] = iconv("GB2312", "UTF-8", $oj_problem["description"]);
    $oj_problem["input"] = iconv("GB2312", "UTF-8", $oj_problem["input"]);
    $oj_problem["output"] = iconv("GB2312", "UTF-8", $oj_problem["output"]);
    $oj_problem["title"] = iconv("GB2312", "UTF-8", $oj_problem["title"]);

    $oj_problem["description"] = str_replace('src="', 'src="http://acm.hdu.edu.cn/', $oj_problem["description"]);
    $oj_problem["description"] = str_replace('src=', 'src=http://acm.hdu.edu.cn/', $oj_problem["description"]);

} else {
    $oj_problem["isfound"] =  false;
}

?>