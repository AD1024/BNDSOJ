<?php
	if($myUser == null || !isSuperUser($myUser)){
		Become404Page();
	}
	
	if(validateUInt($_GET['id'])){
		mysql_query("delete from user_homework where homework_id = ".$_GET['id'].";");
		DB::query("delete from homework_index where id = ".$_GET['id'].";");
		echo 'OK';
	}else{
		echo "Error!";
	}
?>
