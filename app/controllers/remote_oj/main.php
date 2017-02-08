<?php

include "ojnames.php";

function getojinfo($oj_name, $oj_action, $oj_problem_id, $oj_page_id, $oj_code){
	$isinclude = "YES";

	include "settings.php";

	if($oj_name === 'poj'){
    	include "poj/main.php";
	}else if($oj_name === 'hdu'){
		include "hdu/main.php";
	}else if($oj_name === 'codeforces'){
		include "codeforces/main.php";
	}

	if($oj_action === "show"){
    	return $oj_problem;
	}else if($oj_action === "check"){
	    return $oj_check;
	}else if($oj_action === "list"){
	    return $oj_problem_list;
	}else if($oj_action === "status"){
	    return $oj_status;
	}
}

?>
