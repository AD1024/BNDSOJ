<?php

if($isinclude !=="YES"){
    exit();
}

$url = 'http://codeforces.com/problemset/page/'.$oj_page_id;
$content = file_get_contents($url);

if(preg_match('/<td class="id">(.*)<a href="\/problemset\/problem(.*)>(.*)<\/a>(.*)<\/td>/sU', $content, $matches)){
    $oj_problem_list["isfound"] = true;

    if(preg_match_all('/<td class="id">(.*)<a href="\/problemset\/problem(.*)>(.*)<\/a>(.*)<\/td>/sU', $content, $matches))$oj_problem_list["id"] = $matches[3];
    if(preg_match_all('/<div style="float: left;">(\s*)<a href="\/problemset\/problem(.*)>(.*)<\/a>(.*)<\/div>/sU', $content, $matches))$oj_problem_list["title"] = $matches[3];

} else {
    $oj_problem_list["isfound"] =  false;
}

?>
