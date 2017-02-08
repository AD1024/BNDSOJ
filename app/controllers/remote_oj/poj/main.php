<?php

if($isinclude !=="YES"){
    exit();
}


if($oj_action === "show"){
    include "problem.php";
}else if($oj_action === "check"){
    include "checker.php";
}else if($oj_action === "list"){
    include "problem_list.php";
}else if($oj_action === "status"){
    include "status.php";
}

?>