<?php

if($isinclude !=="YES"){
    exit();
}

$url='http://poj.org/problemlist?volume='.$oj_page_id;
$content = file_get_contents($url);

if(preg_match('/<tr align=center><td>(.*)<\/td>/sU', $content, $matches)){
    $oj_problem_list["isfound"] =  true;

    if(preg_match_all('/<tr align=center><td>(.*)<\/td><td align=left>/sU', $content, $matches)) $oj_problem_list["id"] = $matches[1];
    if(preg_match_all('/<td align=left><a lang="en-US" (.*)>(.*)<\/a><\/td>/sU', $content, $matches))$oj_problem_list["title"] = $matches[2];
    //if(preg_match('/<font size=5 color=red>(.*)<\/font><\/a><\/center><\/p>/sU', $content, $matches))$oj_problem_list["page_cnt"] = $matches;


} else {
    $oj_problem_list["isfound"] =  false;
}
return $oj_problem_list;

?>