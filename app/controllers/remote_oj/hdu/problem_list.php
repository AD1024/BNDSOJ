<?php

if($isinclude !=="YES"){
    exit();
}

$url='http://acm.hdu.edu.cn/listproblem.php?vol='.$oj_page_id;
$content = file_get_contents($url);

if(preg_match('/p\([0-1],(.*),-1,"(.*)",(.*),(.*)\);/sU', $content, $matches)){
    $oj_problem_list["isfound"] =  true;

    if(preg_match_all('/p\([0-1],(.*),-1,"(.*)",(.*),(.*)\);/sU', $content, $matches)) $oj_problem_list["id"] = $matches[1];
    if(preg_match_all('/p\([0-1],(.*),-1,"(.*)",(.*),(.*)\);/sU', $content, $matches)) $oj_problem_list["title"] = $matches[2];
    //if(preg_match('/<font size=5 color=red>(.*)<\/font><\/a><\/center><\/p>/sU', $content, $matches))$oj_problem_list["page_cnt"] = $matches;

    $i = 0;

	while($oj_problem_list["id"][$i]){
		$oj_problem_list["title"][$i] = iconv("GB2312", "UTF-8", $oj_problem_list["title"][$i]);
		$i++;
	}


} else {
    $oj_problem_list["isfound"] =  false;
}

?>